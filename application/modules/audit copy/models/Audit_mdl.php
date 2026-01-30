<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Audit_mdl extends CI_Model
{

	public function __Construct()
	{
		parent::__Construct();
	}

	public function getAuditReport($facilityid, $serverSide = false, $start = 0, $length = 10, $searchValue = '', $orderColumn = 0, $orderDir = 'asc')
	{
		$search = (object) $this->input->post();
		
		if (empty($search->month_year)) {
			$table = "national_jobs";
		} else {
			$table = "quarterly_national_jobs";
		}
		
		$aggregation = (!empty($search->aggregate)) ? $search->aggregate : "job_name";
		
		if ($serverSide) {
			// Build base query for total count
			$this->db->reset_query();
			$this->db->select($aggregation);
			$this->db->from($table);
			$this->auditReportFilters($search);
			if (!empty($facilityid)) {
				$this->db->where("facility_id", "$facilityid");
			}
			if (!empty($search->month_year)) {
				$month = explode('-',$search->month_year)[0];
				$year = explode('-', $search->month_year)[1];
				$this->db->where("month", $month);
				$this->db->where("year", $year);
			}
			$this->db->group_by($aggregation);
			$totalQuery = $this->db->get_compiled_select('', false);
			$totalRecords = $this->db->query("SELECT COUNT(*) as cnt FROM ($totalQuery) as total_count")->row()->cnt;
			
			// Build filtered query with data
			$this->db->reset_query();
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
			$this->db->from($table);
			$this->auditReportFilters($search);
			if (!empty($facilityid)) {
				$this->db->where("facility_id", "$facilityid");
			}
			if (!empty($search->month_year)) {
				$month = explode('-',$search->month_year)[0];
				$year = explode('-', $search->month_year)[1];
				$this->db->where("month", $month);
				$this->db->where("year", $year);
			}
			
			// Apply search filter if provided
			if (!empty($searchValue)) {
				$this->db->group_start();
				$this->db->like($aggregation, $searchValue);
				$this->db->or_like('salary_scale', $searchValue);
				$this->db->or_like('job_classification', $searchValue);
				$this->db->group_end();
			}
			
			$this->db->group_by($aggregation);
			
			// Get filtered count
			$filteredQuery = $this->db->get_compiled_select('', false);
			$filteredRecords = $this->db->query("SELECT COUNT(*) as cnt FROM ($filteredQuery) as filtered_count")->row()->cnt;
			
			// Rebuild query for data retrieval with ordering and pagination
			$this->db->reset_query();
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
			$this->db->from($table);
			$this->auditReportFilters($search);
			if (!empty($facilityid)) {
				$this->db->where("facility_id", "$facilityid");
			}
			if (!empty($search->month_year)) {
				$month = explode('-',$search->month_year)[0];
				$year = explode('-', $search->month_year)[1];
				$this->db->where("month", $month);
				$this->db->where("year", $year);
			}
			
			// Apply search filter if provided
			if (!empty($searchValue)) {
				$this->db->group_start();
				$this->db->like($aggregation, $searchValue);
				$this->db->or_like('salary_scale', $searchValue);
				$this->db->or_like('job_classification', $searchValue);
				$this->db->group_end();
			}
			
			$this->db->group_by($aggregation);
			
			// Apply ordering
			$columns = array($aggregation, 'salary_scale', 'approved', 'filled', 'vacant', 'excess', 'male', 'female');
			if (isset($columns[$orderColumn])) {
				$orderColumnName = $columns[$orderColumn];
				$this->db->order_by($orderColumnName, $orderDir);
			} else {
				$this->db->order_by('salary_scale', 'asc');
			}
			
			// Apply pagination
			if ($length > 0) {
				$this->db->limit($length, $start);
			}
			
			$data = $this->db->get()->result();
			
			return array(
				'data' => $data,
				'recordsTotal' => $totalRecords,
				'recordsFiltered' => $filteredRecords
			);
		} else {
			// Original non-server-side logic
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
			$this->db->group_by($aggregation);
			return $this->db->get($table)->result();
		}
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
			if (is_array($search->job_category)) {
				$this->db->where_in('job_category', $search->job_category);
			} else {
				$this->db->where('job_category', $search->job_category);
			}
		}

		if (!empty($search->job_class)) {
			if (is_array($search->job_class)) {
				$this->db->where_in('job_classification', $search->job_class);
			} else {
				$this->db->where('job_classification', $search->job_class);
			}
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
			$categories = is_array($search->job_category) ? $search->job_category : array($search->job_category);
			$legend .= " <b class='text-success'>Job Category: </b>" . implode(', ', $categories);
		}

		if (!empty($search->job)) {
			//job name
			$legend .= " <b class='text-success'>Job : </b>" . $search->job;
		}

		if (!empty($search->job_class)) {
			$classes = is_array($search->job_class) ? $search->job_class : array($search->job_class);
			$legend .= " <b class='text-success'>Job Classification : </b>" . implode(', ', $classes);
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
	
	public function getLastStaffUpdate()
	{
		$query = $this->db->select_max('last_gen')
			->from('ihrisdata')
			->get();
		
		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result->last_gen;
		}
		return null;
	}
	
	public function getLastAuditGeneration()
	{
		// Get the most recent month/year combination from national_jobs
		$query = $this->db->select('month, year')
			->from('national_jobs')
			->where('month IS NOT NULL')
			->where('month !=', '')
			->where('year IS NOT NULL')
			->where('year !=', '')
			->group_by('month, year')
			->order_by('year', 'DESC')
			->order_by('month', 'DESC')
			->limit(1)
			->get();
		
		if ($query->num_rows() > 0) {
			$result = $query->row();
			if (!empty($result->month) && !empty($result->year)) {
				// Convert month name to number
				$monthNum = date('m', strtotime($result->month . ' 1, 2000'));
				if ($monthNum) {
					return $result->year . '-' . $monthNum . '-01 00:00:00';
				}
				// Fallback: return formatted string
				return $result->month . ' ' . $result->year;
			}
		}
		
		return null;
	}

	public function district_facility($district_id)
	{
		$dname = $this->getdname($district_id);
		$data = $this->db->query("SELECT distinct facility_id,facility_name FROM `national_jobs` WHERE district_name='$dname'");
		return $data->result();
	}
	
	public function getAuditReportTotals($facilityid = false)
	{
		$search = (object) $this->input->post();
		
		if (empty($search->month_year)) {
			$table = "national_jobs";
		} else {
			$table = "quarterly_national_jobs";
		}
		
		$this->db->reset_query();
		$this->db->select("
			sum(approved) as total_approved,
			sum(total)  as total_filled,
			sum(male)   as total_male,
			sum(female) as total_female
			");
		$this->db->from($table);
		$this->auditReportFilters($search);
		if (!empty($facilityid)) {
			$this->db->where("facility_id", "$facilityid");
		}
		if (!empty($search->month_year)) {
			$month = explode('-',$search->month_year)[0];
			$year = explode('-', $search->month_year)[1];
			$this->db->where("month", $month);
			$this->db->where("year", $year);
		}
		
		$result = $this->db->get()->row();
		
		if ($result) {
			$totalApproved = (int)$result->total_approved;
			$totalFilled = (int)$result->total_filled;
			$totalMale = (int)$result->total_male;
			$totalFemale = (int)$result->total_female;
			
			// Calculate vacant and excess from totals (matching original logic)
			$difference = $totalApproved - $totalFilled;
			$totalVacant = ($difference > 0) ? $difference : 0;
			$totalExcess = ($difference < 0) ? $difference * -1 : 0;
			
			// Calculate percentages
			$filledPct = ($totalApproved > 0) ? ($totalFilled / $totalApproved) * 100 : 0;
			$vacantPct = ($totalApproved > 0) ? ($totalVacant / $totalApproved) * 100 : 0;
			$malePct = ($totalFilled > 0) ? ($totalMale / $totalFilled) * 100 : 0;
			$femalePct = ($totalFilled > 0) ? ($totalFemale / $totalFilled) * 100 : 0;
			
			return array(
				'totalApproved' => $totalApproved,
				'totalFilled' => $totalFilled,
				'totalVacant' => $totalVacant,
				'totalExcess' => $totalExcess,
				'totalMale' => $totalMale,
				'totalFemale' => $totalFemale,
				'filledPct' => number_format($filledPct, 1),
				'vacantPct' => number_format($vacantPct, 1),
				'malePct' => number_format($malePct, 1),
				'femalePct' => number_format($femalePct, 1)
			);
		}
		
		return array(
			'totalApproved' => 0,
			'totalFilled' => 0,
			'totalVacant' => 0,
			'totalExcess' => 0,
			'totalMale' => 0,
			'totalFemale' => 0,
			'filledPct' => '0.0',
			'vacantPct' => '0.0',
			'malePct' => '0.0',
			'femalePct' => '0.0'
		);
	}
}
