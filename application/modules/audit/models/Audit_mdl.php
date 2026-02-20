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
		$aggregation2 = (!empty($search->aggregate2)) ? $search->aggregate2 : null;
		$groupByCols = $aggregation2 ? array($aggregation, $aggregation2) : array($aggregation);
		
		if ($serverSide) {
			// Build base query for total count
			$this->db->reset_query();
			$this->db->select(implode(', ', $groupByCols));
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
			$this->db->group_by($groupByCols);
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
				if ($aggregation2) $this->db->or_like($aggregation2, $searchValue);
				$this->db->or_like('salary_scale', $searchValue);
				$this->db->or_like('job_classification', $searchValue);
				$this->db->group_end();
			}
			
			$this->db->group_by($groupByCols);
			
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
				if ($aggregation2) $this->db->or_like($aggregation2, $searchValue);
				$this->db->or_like('salary_scale', $searchValue);
				$this->db->or_like('job_classification', $searchValue);
				$this->db->group_end();
			}
			
			$this->db->group_by($groupByCols);
			
			// Apply ordering: value1 then value2
			$this->db->order_by($aggregation, 'asc');
			if ($aggregation2) $this->db->order_by($aggregation2, 'asc');
			$colMap = array_values(array_filter(array($aggregation, $aggregation2, 'salary_scale', 'approved', 'filled', 'vacant', 'excess', 'male', 'female'), function($c) { return $c !== null && $c !== ''; }));
			if (isset($colMap[$orderColumn])) {
				$this->db->order_by($colMap[$orderColumn], $orderDir);
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
			$this->db->order_by($aggregation, 'asc');
			if ($aggregation2) $this->db->order_by($aggregation2, 'asc');
			$this->db->group_by($groupByCols);
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
		// Ignore jobs where both approved and filled are 0 (applies to report, totals, and PDF)
		$this->db->where('(approved != 0 OR total != 0)');

		if (!empty($search->district)) {
			if (is_array($search->district)) {
				$this->db->where_in('district_name', $search->district);
			} else {
				$this->db->where('district_name', $search->district);
			}
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
			if (is_array($search->institution)) {
				$this->db->where_in('institution_type', $search->institution);
			} else {
				$this->db->where('institution_type', $search->institution);
			}
		}

		if (!empty($search->facility_type)) {
			if (is_array($search->facility_type)) {
				$this->db->where_in('facility_type_name', $search->facility_type);
			} else {
				$this->db->where('facility_type_name', $search->facility_type);
			}
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
			if (is_array($search->job)) {
				$this->db->where_in('job_name', $search->job);
			} else {
				$this->db->where('job_name', $search->job);
			}
		}

		if (!empty($search->facility)) {
			if (is_array($search->facility)) {
				$this->db->where_in('facility_name', array_map(function($f) { return str_replace("'", "", $f); }, $search->facility));
			} else {
				$facility = str_replace("'", "", $search->facility);
				$this->db->where('facility_name', $facility);
			}
		}

		if (!empty($search->region)) {
			if (is_array($search->region)) {
				$this->db->where_in('region_name', $search->region);
			} else {
				$this->db->where('region_name', $search->region);
			}
		}

		if (!empty($search->ownership)) {
			$this->db->where('ownership', $search->ownership);
		}

		if (!empty($search->cadre)) {
			if (is_array($search->cadre)) {
				$this->db->where_in('cadre_name', $search->cadre);
			} else {
				$this->db->where('cadre_name', $search->cadre);
			}
		}
	}

	/**
	 * Format a single filter value or array of values for display.
	 */
	private function _legendValues($v) {
		if ($v === null || $v === '') return '';
		if (is_array($v)) return implode(', ', array_filter(array_map('trim', array_map('strval', $v))));
		return trim((string) $v);
	}

	public function auditReportLegend($search)
	{
		$limitedBy = array();
		if (!empty($search->ownership)) $limitedBy[] = 'Ownership (' . $this->_legendValues($search->ownership) . ')';
		if (!empty($search->institution)) $limitedBy[] = 'Institution Type (' . $this->_legendValues($search->institution) . ')';
		if (!empty($search->region)) $limitedBy[] = 'Region (' . $this->_legendValues($search->region) . ')';
		if (!empty($search->district)) $limitedBy[] = 'District (' . $this->_legendValues($search->district) . ')';
		if (!empty($search->facility_type)) $limitedBy[] = 'Facility Level (' . $this->_legendValues($search->facility_type) . ')';
		if (!empty($search->facility)) $limitedBy[] = 'Facility (' . $this->_legendValues($search->facility) . ')';
		if (!empty($search->cadre)) $limitedBy[] = 'Job Cadre (' . $this->_legendValues($search->cadre) . ')';
		if (!empty($search->job_category)) $limitedBy[] = 'Job Category (' . $this->_legendValues($search->job_category) . ')';
		if (!empty($search->job_class)) $limitedBy[] = 'Job Classification (' . $this->_legendValues($search->job_class) . ')';
		if (!empty($search->job)) $limitedBy[] = 'Job Name (' . $this->_legendValues($search->job) . ')';
		if (!empty($search->month_year)) $limitedBy[] = 'Month Year (' . $this->_legendValues($search->month_year) . ')';
		if (!empty($_SESSION['district']) && !empty($_GET['display']) && $_GET['display'] == 'ihris') $limitedBy[] = 'District (' . $_SESSION['district'] . ')';
		if (!empty($_SESSION['institution_type']) && !empty($_GET['display']) && $_GET['display'] == 'ihris') $limitedBy[] = 'Institution Type (' . $_SESSION['institution_type'] . ')';

		$limitedStr = count($limitedBy) ? implode('; ', $limitedBy) : 'None';
		$aggLabel = $this->getAggregateLabel(isset($search->aggregate) ? $search->aggregate : 'job_name');
		$agg2Label = !empty($search->aggregate2) ? $this->getAggregateLabel($search->aggregate2) : '';
		$aggStr = $agg2Label ? $aggLabel . ' (top), ' . $agg2Label . ' (side)' : $aggLabel;
		return 'Limited by: ' . $limitedStr . '. Aggregated by: ' . $aggStr . '.';
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
		// Get the most recent date_time from national_jobs
		$query = $this->db->select('date_time')
			->from('national_jobs')
			->where('date_time IS NOT NULL')
			->order_by('date_time', 'DESC')
			->limit(1)
			->get();
		
		if ($query->num_rows() > 0) {
			$result = $query->row();
			if (!empty($result->date_time)) {
				return $result->date_time;
			}
		}
		
		return null;
	}

	public function district_facility($district_id)
	{
		$dname = $this->getdname($district_id);
		$this->db->select('facility_id, facility_name');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('district_name', $dname);
		return $this->db->get()->result();
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
			sum(female) as total_female,
			sum(vacant) as total_vacant,
			sum(excess) as total_excess
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
			// Use DB sum(vacant) and sum(excess) so table, Excel and PDF match row-level data
			if (property_exists($result, 'total_vacant') && $result->total_vacant !== null && property_exists($result, 'total_excess') && $result->total_excess !== null) {
				$totalVacant = (int)$result->total_vacant;
				$totalExcess = (int)$result->total_excess;
			} else {
				$difference = $totalApproved - $totalFilled;
				$totalVacant = ($difference > 0) ? $difference : 0;
				$totalExcess = ($difference < 0) ? $difference * -1 : 0;
			}
			
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
