<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends MX_Controller {

	/** When true, allow embedding in iframes and send CORS headers for cross-origin use. */
	private $embed = false;

	public function __Construct(){
		parent::__Construct();
		$this->load->model('Audit_mdl','auditMdl');
		$this->watermark = FCPATH."assets/watermark.png";
		$this->embed = ($this->input->get_post('embed') === '1' || $this->input->get_post('embed') === 1);
		// Preflight: respond to OPTIONS so cross-origin POST/fetch succeed without CORS errors
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
			if ($origin !== '') {
				header('Access-Control-Allow-Origin: ' . $origin);
				header('Access-Control-Allow-Credentials: true');
			} else {
				header('Access-Control-Allow-Origin: *');
			}
			header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
			header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Origin');
			header('Access-Control-Max-Age: 86400');
			if (function_exists('header_remove')) {
				header_remove('X-Frame-Options');
			}
			exit(0);
		}
	}

	/**
	 * Send CORS and embed headers so the audit view can be embedded in external systems and JS avoids CORS errors.
	 * Call before any output. When $for_html is true, also allows framing (frame-ancestors *).
	 */
	private function _setCorsAndEmbedHeaders($for_html = false) {
		$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
		$allow_embed = $this->embed || $origin !== '';
		if (!$allow_embed) {
			return;
		}
		// Allow the request origin so credentials (cookies) work when embedded; fallback to * for simple embed
		if ($origin !== '') {
			header('Access-Control-Allow-Origin: ' . $origin);
			header('Access-Control-Allow-Credentials: true');
		} else {
			header('Access-Control-Allow-Origin: *');
		}
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Origin');
		if ($for_html && $this->embed) {
			// Allow this page to be embedded in any frame (e.g. iframe on external site)
			header('Content-Security-Policy: frame-ancestors *');
			if (function_exists('header_remove')) {
				header_remove('X-Frame-Options');
			}
		}
	}

	public function auditReport(){
		// Skip CORS/embed headers when outputting PDF so mPDF can send its own headers
		if ($this->input->get_post('getPdf') != 1) {
			$this->_setCorsAndEmbedHeaders(true);
		}
		Modules::run('dataprep/shareModel'); //model sharing handle 

		$search = (Object) $this->input->post();
		$data['embed'] = $this->embed;
      
        $data['module']     = "audit";
		$data['page']       = "audit_report";
		$data['title']      = "Audit Report";
		$data['uptitle']    = "HRH Audit Report";
		$data['search']     = $search;

		$data['aggTitle']   = $this->auditMdl->getAggregateLabel(@$search->aggregate);
		$data['aggColumn']  = (!empty($search->aggregate))?$search->aggregate:"job_name";

		
		$data['filters'] = $this->DataPrep_mdl->getFilters(true);
		// Full chain from ownership -> job name (national_jobs columns order)
		$ownership = isset($search->ownership) ? $search->ownership : '';
		$inst_arr = $this->_chainArray(isset($search->institution) ? $search->institution : null);
		$region_arr = $this->_chainArray(isset($search->region) ? $search->region : null);
		$district_arr = $this->_chainArray(isset($search->district) ? $search->district : null);
		$facility_level_arr = $this->_chainArray(isset($search->facility_type) ? $search->facility_type : null);
		$facility_arr = $this->_chainArray(isset($search->facility) ? $search->facility : null);
		$cadre_arr = $this->_chainArray(isset($search->cadre) ? $search->cadre : null);
		$job_cat_arr = $this->_chainArray(isset($search->job_category) ? $search->job_category : null);
		$job_class_arr = $this->_chainArray(isset($search->job_class) ? $search->job_class : null);
		// Chain 1: Ownership -> Institution Type only
		$data['institution_types_for_chain'] = $this->DataPrep_mdl->getInstitutionTypesForChain($ownership);
		// Chain 2: Region -> District -> ... -> Job Name; only show dependent options when parent(s) selected
		$data['regions_for_chain'] = $this->DataPrep_mdl->getRegionsForChain();
		$data['districts_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getDistrictsForChain('', array(), $region_arr);
		$data['facility_levels_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getFacilityLevelsForChain('', array(), $region_arr, $district_arr);
		$data['facilities_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getFacilitiesForChain('', array(), $region_arr, $district_arr, $facility_level_arr);
		$data['job_cadres_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getJobCadresForChain('', array(), $region_arr, $district_arr, $facility_level_arr, $facility_arr);
		$data['job_categories_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getJobCategoriesForChain('', array(), $region_arr, $district_arr, $facility_level_arr, $facility_arr, $cadre_arr);
		$data['job_classifications_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getJobClassificationsForChain('', array(), $region_arr, $district_arr, $facility_level_arr, $facility_arr, $cadre_arr, $job_cat_arr);
		$data['job_names_for_chain'] = empty($region_arr) ? array() : $this->DataPrep_mdl->getJobNamesForChain('', array(), $region_arr, $district_arr, $facility_level_arr, $facility_arr, $cadre_arr, $job_cat_arr, $job_class_arr);
		$data['audit']  = $this->auditMdl->getAuditReport($facilityId=FALSE);
		$data['legend']	= $this->auditMdl->auditReportLegend($search);
		$data['last_staff_update'] = $this->auditMdl->getLastStaffUpdate();
		$data['last_audit_generation'] = $this->auditMdl->getLastAuditGeneration();

		if(isset($search->getPdf ) && $search->getPdf == 1):
			// Use same filtered data as the view (getAuditReport reads filters from input->post())
			$html     = $this->load->view("audit/audit_report_pdf", $data, true);
			$districtLabel = !empty($_SESSION['district']) ? $_SESSION['district'] . '_' : '';
			$filename = $districtLabel . "audit_report_" . date('Y-m-d_His') . ".pdf";
			// makePdf with 'D' returns PDF string and sets headers; echo and exit so download triggers
			$pdf = Modules::run('template/makePdf', $html, $filename, 'D');
			if ($pdf !== null && $pdf !== '') {
				echo $pdf;
			}
			exit;
		else:
			if ($this->embed) {
				echo Modules::run('template/layoutEmbed', $data);
			} else {
				echo Modules::run('template/layout', $data);
			}
		endif;
	}
	
	/**
	 * AJAX: get institution types by category for chained filter.
	 */
	public function getInstitutionTypesByCategory() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$category = $this->input->get_post('category');
		$types = $this->DataPrep_mdl->getInstitutionTypesByCategory($category);
		header('Content-Type: application/json');
		echo json_encode($types);
	}

	/**
	 * AJAX: get districts by region for chained filter.
	 */
	public function getDistrictsByRegion() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$region = $this->input->get_post('region');
		$districts = $this->DataPrep_mdl->getDistrictsByRegion($region);
		header('Content-Type: application/json');
		echo json_encode($districts);
	}

	/**
	 * AJAX: get facility levels by region and optional districts (chained filter).
	 */
	public function getFacilityLevelsByRegionDistrict() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$region = $this->input->get_post('region');
		$districts = $this->input->get_post('district') ?: $this->input->get_post('districts');
		if (is_string($districts)) {
			$districts = $districts !== '' ? (strpos($districts, ',') !== false ? explode(',', $districts) : array($districts)) : array();
		}
		$districts = is_array($districts) ? $districts : array();
		$levels = $this->DataPrep_mdl->getFacilityLevelsByRegionDistrict($region, $districts);
		header('Content-Type: application/json');
		echo json_encode($levels);
	}

	/**
	 * AJAX: get facilities by region, optional districts, and optional facility level (chained filter).
	 */
	public function getFacilitiesByRegionDistrictLevel() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$region = $this->input->get_post('region');
		$districts = $this->input->get_post('district') ?: $this->input->get_post('districts');
		if (is_string($districts)) {
			$districts = $districts !== '' ? (strpos($districts, ',') !== false ? explode(',', $districts) : array($districts)) : array();
		}
		$districts = is_array($districts) ? $districts : array();
		$facility_level = $this->input->get_post('facility_level');
		$facilities = $this->DataPrep_mdl->getFacilitiesByRegionDistrictLevel($region, $districts, $facility_level ?: '');
		header('Content-Type: application/json');
		echo json_encode($facilities);
	}

	/**
	 * AJAX: get job categories by cadre (chained job filter).
	 */
	public function getJobCategoriesByCadre() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$cadre = $this->input->get_post('cadre');
		$list = $this->DataPrep_mdl->getJobCategoriesByCadre($cadre ?: '');
		header('Content-Type: application/json');
		echo json_encode($list);
	}

	/**
	 * AJAX: get job classifications by cadre and optional job categories (chained).
	 */
	public function getJobClassificationsByCadreCategory() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$cadre = $this->input->get_post('cadre');
		$categories = $this->input->get_post('categories') ?: $this->input->get_post('job_category');
		if (is_string($categories)) {
			$categories = $categories !== '' ? (strpos($categories, ',') !== false ? explode(',', $categories) : array($categories)) : array();
		}
		$categories = is_array($categories) ? $categories : array();
		$list = $this->DataPrep_mdl->getJobClassificationsByCadreCategory($cadre ?: '', $categories);
		header('Content-Type: application/json');
		echo json_encode($list);
	}

	/**
	 * AJAX: get job names by cadre, optional categories, optional classifications (chained).
	 */
	public function getJobNamesByCadreCategoryClassification() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$cadre = $this->input->get_post('cadre');
		$categories = $this->input->get_post('categories') ?: $this->input->get_post('job_category');
		if (is_string($categories)) {
			$categories = $categories !== '' ? (strpos($categories, ',') !== false ? explode(',', $categories) : array($categories)) : array();
		}
		$categories = is_array($categories) ? $categories : array();
		$classifications = $this->input->get_post('classifications') ?: $this->input->get_post('job_class');
		if (is_string($classifications)) {
			$classifications = $classifications !== '' ? (strpos($classifications, ',') !== false ? explode(',', $classifications) : array($classifications)) : array();
		}
		$classifications = is_array($classifications) ? $classifications : array();
		$list = $this->DataPrep_mdl->getJobNamesByCadreCategoryClassification($cadre ?: '', $categories, $classifications);
		header('Content-Type: application/json');
		echo json_encode($list);
	}

	public function auditReportData(){
		$this->_setCorsAndEmbedHeaders(false);
		// Server-side DataTables endpoint
		Modules::run('dataprep/shareModel');
		
		$start = $this->input->post('start') ? $this->input->post('start') : 0;
		$length = $this->input->post('length') ? $this->input->post('length') : 10;
		$searchValue = $this->input->post('search')['value'] ? $this->input->post('search')['value'] : '';
		$orderColumn = $this->input->post('order')[0]['column'] ? $this->input->post('order')[0]['column'] : 0;
		$orderDir = $this->input->post('order')[0]['dir'] ? $this->input->post('order')[0]['dir'] : 'asc';
		
		$result = $this->auditMdl->getAuditReport(FALSE, true, $start, $length, $searchValue, $orderColumn, $orderDir);
		
		// Get totals from all filtered data (not just current page)
		$totals = $this->auditMdl->getAuditReportTotals(FALSE);
		
		$search = (Object) $this->input->post();
		$aggColumn = (!empty($search->aggregate)) ? $search->aggregate : "job_name";
		
		$data = array();
		foreach ($result['data'] as $row) {
			$structure = $row->approved;
			$difference = $row->approved - $row->filled;
			$vacantPosts = ($difference > 0) ? $difference : 0;
			$excessPosts = ($difference < 0) ? $difference * -1 : 0;
			
			$male = ($structure > 0 && $row->filled > 0) ? ($row->male / $row->filled) * 100 : 0;
			$female = ($structure > 0 && $row->filled > 0) ? ($row->female / $row->filled) * 100 : 0;
			$vacant = ($structure > 0) ? ($vacantPosts / $structure) * 100 : 0;
			$filled = ($structure > 0) ? ($row->filled / $structure) * 100 : 0;
			
			$rowData = array(
				$row->$aggColumn,
				(($search->aggregate == 'job_name') || ($search->aggregate == '')) ? $row->salary_scale : '',
				$row->approved,
				$row->filled,
				$vacantPosts,
				$excessPosts,
				$row->male,
				$row->female,
				($filled > 0) ? number_format($filled, 1) . '%' : '0%',
				($vacant > 0) ? number_format($vacant, 1) . '%' : '0%',
				($male > 0) ? number_format($male, 1) . '%' : '0%',
				($female > 0) ? number_format($female, 1) . '%' : '0%'
			);
			$data[] = $rowData;
		}
		
		$output = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $result['recordsTotal'],
			"recordsFiltered" => $result['recordsFiltered'],
			"data" => $data,
			"totals" => $totals
		);
		
		echo json_encode($output);
	}

	/**
	 * Export audit report to Excel (CSV). Uses same filters and excludes approved=0 and filled=0. Streams in chunks.
	 */
	public function auditReportExcel() {
		$this->_setCorsAndEmbedHeaders(false);
		$search = (object) $this->input->post();
		$aggColumn = (!empty($search->aggregate)) ? $search->aggregate : 'job_name';
		$aggTitle = $this->auditMdl->getAggregateLabel(@$search->aggregate);
		$showSalaryScale = ($search->aggregate == 'job_name' || $search->aggregate == '');
		$filename = 'audit_report_' . date('Y-m-d_His') . '.csv';
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		$out = fopen('php://output', 'w');
		fprintf($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
		$headers = array($aggTitle, 'Salary Scale', 'Approved', 'Filled', 'Vacant', 'Excess', 'Male', 'Female', 'Filled %', 'Vacant %', 'Male %', 'Female %');
		if (!$showSalaryScale) array_splice($headers, 1, 1);
		fputcsv($out, $headers);
		$pageSize = 5000;
		$start = 0;
		do {
			$result = $this->auditMdl->getAuditReport(FALSE, true, $start, $pageSize, '', 0, 'asc');
			foreach ($result['data'] as $row) {
				$difference = $row->approved - $row->filled;
				$vacantPosts = ($difference > 0) ? $difference : 0;
				$excessPosts = ($difference < 0) ? $difference * -1 : 0;
				$male = ($row->approved > 0 && $row->filled > 0) ? ($row->male / $row->filled) * 100 : 0;
				$female = ($row->approved > 0 && $row->filled > 0) ? ($row->female / $row->filled) * 100 : 0;
				$vacant = ($row->approved > 0) ? ($vacantPosts / $row->approved) * 100 : 0;
				$filled = ($row->approved > 0) ? ($row->filled / $row->approved) * 100 : 0;
				$r = array(
					$row->$aggColumn,
					$showSalaryScale ? $row->salary_scale : '',
					$row->approved,
					$row->filled,
					$vacantPosts,
					$excessPosts,
					$row->male,
					$row->female,
					$filled > 0 ? number_format($filled, 1) . '%' : '0%',
					$vacant > 0 ? number_format($vacant, 1) . '%' : '0%',
					$male > 0 ? number_format($male, 1) . '%' : '0%',
					$female > 0 ? number_format($female, 1) . '%' : '0%'
				);
				if (!$showSalaryScale) array_splice($r, 1, 1);
				fputcsv($out, $r);
			}
			$start += $pageSize;
			if (ob_get_level()) ob_flush();
			flush();
		} while (count($result['data']) == $pageSize);
		// Always append summary totals row
		$totals = $this->auditMdl->getAuditReportTotals(FALSE);
		fputcsv($out, array());
		$totalRow = array('TOTALS');
		if ($showSalaryScale) $totalRow[] = '';
		$totalRow = array_merge($totalRow, array(
			$totals['totalApproved'],
			$totals['totalFilled'],
			$totals['totalVacant'],
			$totals['totalExcess'],
			$totals['totalMale'],
			$totals['totalFemale'],
			$totals['filledPct'] . '%',
			$totals['vacantPct'] . '%',
			$totals['malePct'] . '%',
			$totals['femalePct'] . '%'
		));
		fputcsv($out, $totalRow);
		fclose($out);
		exit;
	}

	public function facAudit($facilityId=FALSE){

		Modules::run('dataprep/shareModel'); //model sharing handle 

		$search = (Object) $this->input->post();
      
        $data['module']     = "audit";
	    $data['page']       = "fac_audit_report_pdf";
		$data['title']      = "Facility Audit Report";
		$data['uptitle']    = "HRH Audit Report";
		$data['search']     = $search;

		$data['aggTitle']   = $this->auditMdl->getAggregateLabel(@$search->aggregate);
		$data['aggColumn']  = (!empty($search->aggregate))?$search->aggregate:"job_name";

		
		$data['filters']= $this->DataPrep_mdl->getFilters(true);
		$data['audit']  = $this->auditMdl->getAuditReport($facilityId);
		$data['legend']	= $this->auditMdl->auditReportLegend($search);
		return $data;
	
	}
	public function printfacAudit(){
            if($this->input->post('getPdf')==1){
			$html     = $this->load->view("audit/audit_report_fac_pdf","",true);
			$filename = $_SESSION['district']."_Facilities_audit_report_".date('Y-m-d_his').".pdf";
			Modules::run('template/makePdf',$html,$filename,"D");
			}
			else{
				$this->lfacAudit();
			}
			
	}

	public function lfacAudit(){

        $data['module']     = "audit";
		$data['page']       = "fac_audit";
		$data['title']      = "Facility Audit Report";
		$data['uptitle']    = "HRH Facility Audit Report";
		
	      echo Modules::run('template/layout',$data);
		
	}
	public function district_facility($district){
		
		$data = $this->auditMdl->district_facility($district);
	return $data;
	}

	/** Normalize request value to array for chained filters. */
	private function _chainArray($v) {
		if ($v === null || $v === '') return array();
		return is_array($v) ? $v : array($v);
	}

	/**
	 * AJAX: get chained filter options. Chain 1: ownership (single) -> institution_type only. Chain 2: region -> district -> ... -> job_name (no ownership/inst).
	 * POST: level, ownership (for institution_type), and for chain 2: regions[], districts[], facility_levels[], facilities[], cadres[], job_categories[], job_classifications[].
	 */
	public function getChainedFilterOptions() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$level = $this->input->get_post('level');
		if (!$level) {
			header('Content-Type: application/json');
			echo json_encode(array());
			return;
		}
		$ownership = trim((string)$this->input->get_post('ownership'));
		$inst = $this->_institutionTypesForRequest($level);
		$regions = $this->_chainPostArray('regions', 'region');
		$districts = $this->_chainPostArray('districts', 'district');
		$levels = $this->_chainPostArray('facility_levels', 'facility_type');
		$facilities = $this->_chainPostArray('facilities', 'facility');
		$cadres = $this->_chainPostArray('cadres', 'cadre');
		$job_cats = $this->_chainPostArray('job_categories', 'job_category');
		$job_class = $this->_chainPostArray('job_classifications', 'job_class');
		$result = array();
		// Chain 1: ownership -> institution_type only. Chain 2: region -> district -> ... -> job_name; require parent selection for distinct options.
		switch ($level) {
			case 'institution_type': $result = $this->DataPrep_mdl->getInstitutionTypesForChain($ownership); break;
			case 'region_name': $result = $this->DataPrep_mdl->getRegionsForChain(); break;
			case 'district': $result = empty($regions) ? array() : $this->DataPrep_mdl->getDistrictsForChain('', array(), $regions); break;
			case 'facility_level': $result = empty($regions) ? array() : $this->DataPrep_mdl->getFacilityLevelsForChain('', array(), $regions, $districts); break;
			case 'facility': $result = empty($regions) ? array() : $this->DataPrep_mdl->getFacilitiesForChain('', array(), $regions, $districts, $levels); break;
			case 'cadre': $result = empty($regions) ? array() : $this->DataPrep_mdl->getJobCadresForChain('', array(), $regions, $districts, $levels, $facilities); break;
			case 'job_category': $result = empty($regions) ? array() : $this->DataPrep_mdl->getJobCategoriesForChain('', array(), $regions, $districts, $levels, $facilities, $cadres); break;
			case 'job_classification': $result = empty($regions) ? array() : $this->DataPrep_mdl->getJobClassificationsForChain('', array(), $regions, $districts, $levels, $facilities, $cadres, $job_cats); break;
			case 'job_name': $result = empty($regions) ? array() : $this->DataPrep_mdl->getJobNamesForChain('', array(), $regions, $districts, $levels, $facilities, $cadres, $job_cats, $job_class); break;
			default: $result = array();
		}
		header('Content-Type: application/json');
		echo json_encode($result);
	}

	/** Get institution_types for chained request; used so Region (chained to institution type) always gets the selected value. Prefer JSON to avoid PHP array serialization issues. */
	private function _institutionTypesForRequest($level) {
		// Prefer JSON so exact string (e.g. "Municipality, Local Government -LG") is preserved; use $_POST to avoid CI input filtering
		$json = isset($_POST['institution_types_json']) ? $_POST['institution_types_json'] : $this->input->post('institution_types_json');
		if (is_string($json) && $json !== '') {
			$decoded = json_decode($json);
			if (is_array($decoded)) {
				$out = array_values(array_filter(array_map('trim', array_map('strval', $decoded))));
				if (!empty($out)) return $out;
			}
		}
		$raw = $this->input->post('institution_types');
		if (is_array($raw)) return array_values(array_filter(array_map('trim', $raw)));
		if (isset($_POST['institution_types']) && is_array($_POST['institution_types'])) return array_values(array_filter(array_map('trim', $_POST['institution_types'])));
		if (isset($_POST['institution_types[]']) && is_array($_POST['institution_types[]'])) return array_values(array_filter(array_map('trim', $_POST['institution_types[]'])));
		$raw = $this->input->post('institution');
		if (is_array($raw)) return array_values(array_filter(array_map('trim', $raw)));
		if (isset($_POST['institution']) && is_array($_POST['institution'])) return array_values(array_filter(array_map('trim', $_POST['institution'])));
		if (isset($_POST['institution[]']) && is_array($_POST['institution[]'])) return array_values(array_filter(array_map('trim', $_POST['institution[]'])));
		// Do not use _parseList here: institution_type values contain commas (e.g. "Municipality, Local Government -LG")
		$v = $this->input->get_post('institution');
		if (is_array($v)) return array_values(array_filter(array_map('trim', $v)));
		if ($v !== null && trim((string)$v) !== '') return array(trim((string)$v));
		return array();
	}

	/** Get POST param as array. Supports key and key[] (jQuery bracket notation). */
	private function _postArray($key) {
		$v = $this->input->post($key);
		if (is_array($v)) return array_values(array_filter($v, function($x) { return $x !== '' && $x !== null; }));
		if (isset($_POST[$key]) && is_array($_POST[$key])) return array_values(array_filter($_POST[$key], function($x) { return $x !== '' && $x !== null; }));
		$keyBracket = $key . '[]';
		if (isset($_POST[$keyBracket]) && is_array($_POST[$keyBracket])) return array_values(array_filter($_POST[$keyBracket], function($x) { return $x !== '' && $x !== null; }));
		return $v;
	}

	/** Get chained filter array from POST (regions, districts, etc.). Tries key, key[], and singular alt; returns trimmed array, no comma-split. */
	private function _chainPostArray($key, $altKey = null) {
		$v = $this->_postArray($key);
		if (is_array($v) && !empty($v)) return array_values(array_filter(array_map('trim', $v)));
		if ($altKey !== null) {
			$v = $this->_postArray($altKey);
			if (is_array($v) && !empty($v)) return array_values(array_filter(array_map('trim', $v)));
			$raw = $this->input->get_post($altKey);
			if (is_array($raw)) return array_values(array_filter(array_map('trim', $raw)));
			if ($raw !== null && trim((string)$raw) !== '') return array(trim((string)$raw));
		}
		return array();
	}

	private function _parseList($v) {
		if ($v === null || $v === '') return array();
		if (is_array($v)) return array_values(array_filter($v, function($x) { return $x !== '' && $x !== null; }));
		$v = trim((string)$v);
		if ($v === '') return array();
		return strpos($v, ',') !== false ? array_values(array_filter(array_map('trim', explode(',', $v)))) : array($v);
	}
}
