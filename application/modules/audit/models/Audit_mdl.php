<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Audit_mdl extends CI_Model
{

	public function __Construct()
	{
		parent::__Construct();
	}

	public function getAuditReport($facilityid)
	{

		$search = (object) $this->input->post();
		$this->auditReportFilters($search);
		if (!empty($facilityid)) {
			$this->db->where("facility_id", "$facilityid");
		}
		if (empty($search->month_year)) {
			$table = "national_jobs";
		} else {
			$table = "quarterly_national_jobs";
			$month = explode('-',$search->month_year)[0];
			$year = explode('-', $search->month_year)[1];
			$this->db->where("month", $month);
			$this->db->where("year", $year);
		}
		$this->db->select("
			job_name,
			salary_scale,
			job_classification,
			institution_type,
			district_name,
			facility_name,
			facility_type_name,
			region_name,
			cadre_name,
			sum(approved) as approved,
			sum(total)  as filled,
			sum(male)   as male,
			sum(female) as female,
			sum(excess) as excess,
			sum(vacant) as vacant
			");
		$this->db->order_by('salary_scale', 'asc');
		$aggregation = (!empty($search->aggregate)) ? $search->aggregate : "job_name";

		$this->db->group_by($aggregation);
		$this->db->order_by('salary_scale', 'asc');
		return $this->db->get($table)->result();
	}
	public function getdname($district)
	{
		$ddata = $this->db->query("SELECT  district from ihrisdata where district_id='$district'")->row();
		return $dname = $ddata->district;
	}

	private function auditReportFilters($search)
	{

		if (!empty($search->district)) {

			$this->db->where('district_name', $search->district);
		}
		if (!empty($_GET['districts']) && empty($search->district)) {
			$district_id = $_GET['districts'];
			$dname = $this->getdname($district_id);
			$_SESSION['district_id'] = $district_id;
			$_SESSION['district'] = $dname;

			$this->db->where("district_name", "$dname");
		}
		if (!empty($_GET['districts']) && empty($search->institution)) {
			$_SESSION['institution_type'] = 'District, Local Government (LG)';

			$this->db->where('institution_type', 'District, Local Government (LG)');
		}

		if (!empty($search->institution)) {

			$this->db->like('institution_type', $search->institution,'after');
		}

		if (!empty($search->facility_type)) {

			$this->db->where('facility_type_name', $search->facility_type);
		}

		if (!empty($search->job_category)) {

			$this->db->where('job_category', $search->job_category);
		}

		if (!empty($search->job_class)) {

			$this->db->where('job_classification', $search->job_class);
		}

		if (!empty($search->job)) {
			//job name
			$this->db->where('job_name', $search->job);
		}

		if (!empty($search->facility)) {
			//facility
			$facility = str_replace("'", "", $search->facility);
			$this->db->where('facility_name', $facility);
		}

		if (!empty($search->region)) {
			//facility
			$this->db->where('region_name', $search->region);
		}

		if (!empty($search->ownership)) {
			//ownership
			$this->db->where('ownership', $search->ownership);
		}

		if (!empty($search->cadre)) {
			//cadre_name
			$this->db->where('cadre_name', $search->cadre);
		}
	}

	public function auditReportLegend($search)
	{
		$legend = "";

		if (!empty($search->district)) {

			$legend .= "<b class='text-success'>District: </b>" . $search->district;
		}
		if (!empty($_SESSION['district']) && $_GET['display'] == 'ihris') {

			$legend .= "<b class='text-success'>District: </b>" . $_SESSION['district'];
		}

		if (!empty($search->institution)) {
			$legend .= " <b class='text-success'>Institution Type: </b>" . $search->institution;
		}

		if (!empty($_SESSION['institution_type']) && $_GET['display'] == 'ihris') {
			$legend .= " <b class='text-success'>Institution Type: </b>" . $_SESSION['institution_type'];
		}

		if (!empty($search->job_category)) {

			$legend .= " <b class='text-success'>Job Category: </b>" . $search->job_category;
		}

		if (!empty($search->job)) {
			//job name
			$legend .= " <b class='text-success'>Job : </b>" . $search->job;
		}

		if (!empty($search->job_class)) {
			//job class
			$legend .= " <b class='text-success'>Job Classification : </b>" . $search->job_class;
		}

		if (!empty($search->facility_type)) {
			//facility type
			$legend .= " <b class='text-success'>Facility Type : </b>" . $search->facility_type;
		}

		if (!empty($search->region)) {
			//facility type
			$legend .= " <b class='text-success'>Region : </b>" . $search->region;
		}
		if (!empty($search->ownership)) {
			//facility type
			$legend .= " <b class='text-success'>Ownership : </b>" . $search->ownership;
		}
	

		if (!empty($search->facility)) {
			//facility type
			$legend .= " <b class='text-success'>Facility : </b>" . $search->facility;
		}

		if (!empty($search->aggregate)) {

			$legend .= " <b class='text-success'>Aggregated By : </b>" . $this->getAggregateLabel($search->aggregate);
		}
		if (!empty($search->month_year)) {
			//facility type
			$legend .= " <b class='text-success'>Month_year : </b>" . $search->month_year;
		}
		



		return $legend;
	}

	public function getAggregateLabel($aggregateLabel)
	{
		$aggregate = str_replace('name', "", str_replace('_', " ", (!empty($aggregateLabel)) ? $aggregateLabel : "job_name"));
		return $aggregate;
	}

	public function district_facility($district_id)
	{
		$dname = $this->getdname($district_id);
		$data = $this->db->query("SELECT distinct facility_id,facility_name FROM `national_jobs` WHERE district_name='$dname'");
		return $data->result();
	}
}
