<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_mdl extends CI_Model {

	public function __Construct(){
		parent::__Construct();
	}
   
	public function getAuditReport(){

		$search = (Object) $this->input->post();
		
		$this->auditReportFilters($search);

		$this->db->select("
			job_name,
			salary_scale,
			institution_type,
			district_name,
			facility_name,
			facility_type_name,
			cadre_name,
			sum(approved) as approved,
			sum(total)  as filled,
			sum(male)   as male,
			sum(female) as female,
			sum(excess) as excess,
			sum(vacant) as vacant
			");

		$aggregation = (!empty($search->aggregate))?$search->aggregate:"job_name";

		$this->db->group_by($aggregation);
		return $this->db->get('national_jobs')->result();
		
	}
	
	private function auditReportFilters($search)
	{

		if(!empty($search->district)){

			$this->db->where('district_name',$search->district);

		}
		
		if(!empty($search->institution)){

			$this->db->where('institution_type',$search->institution);
		}

		if(!empty($search->job_category)){

			$this->db->where('job_category',$search->job_category);
		}

		if(!empty($search->job)){
			//job name
			$this->db->where('job_name',$search->job);
		}

		if(!empty($search->facility)){
			//facility
			$this->db->where('facility_name',$search->facility);
		}

		if(!empty($search->region)){
			//facility
			$this->db->where('region_name',$search->region);
		}

		if(!empty($search->ownership)){
			//ownership
			$this->db->where('ownership',$search->ownership);
		}

		if(!empty($search->cadre)){
			//cadre_name
			$this->db->where('cadre_name',$search->cadre);
		}
	}

	public function auditReportLegend($search)
	{
		$legend = "";

		if(!empty($search->district)){

			$legend .= "<b class='text-success'>District: </b>".$search->district;

		}
		
		if(!empty($search->institution)){
			$legend .= " <b class='text-success'>Institution Type: </b>".$search->institution;
		}

		if(!empty($search->job_category)){

			$legend .= " <b class='text-success'>Job Category: </b>".$search->job_category;
		}

		if(!empty($search->job)){
			//job name
			$legend .= " <b class='text-success'>Job : </b>".$search->job;
		}

		if(!empty($search->job_class)){
			//job class
			$legend .= " <b class='text-success'>Job Classification : </b>".$search->job_class;
		}

		if(!empty($search->facility_type)){
			//facility type
			$legend .= " <b class='text-success'>Facility Type : </b>".$search->facility_type;
		}

		if(!empty($search->region)){
			//facility type
			$legend .= " <b class='text-success'>Region : </b>".$search->region;
		}

		if(!empty($search->facility)){
			//facility type
			$legend .= " <b class='text-success'>Facility : </b>".$search->facility;
		}

        if(!empty($search->aggregate)){
			
			$legend .= " <b class='text-success'>Aggregated By : </b>".$this->getAggregateLabel($search->aggregate);
		}


		return $legend;
	}

	public function getAggregateLabel($aggregateLabel){
		$aggregate = str_replace('name',"",str_replace('_'," ",(!empty($aggregateLabel))?$aggregateLabel:"job_name"));
		return $aggregate;
	}



}
