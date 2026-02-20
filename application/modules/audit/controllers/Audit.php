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
	 * Resolve aggregation columns for audit report: side (rows) defaults to Job and is first; top (section) is optional.
	 * Returns array('aggColumn','aggTitle','aggColumn2','aggTitle2','showSalaryScale').
	 */
	private function _auditReportAggregation($search) {
		$rowsCol   = !empty($search->aggregate2) ? $search->aggregate2 : 'job_name';
		$sectionCol = !empty($search->aggregate) ? $search->aggregate : null;
		if ($sectionCol) {
			$aggColumn  = $sectionCol;
			$aggTitle   = $this->auditMdl->getAggregateLabel($sectionCol);
			$aggColumn2 = $rowsCol;
			$aggTitle2  = $this->auditMdl->getAggregateLabel($rowsCol);
		} else {
			$aggColumn  = $rowsCol;
			$aggTitle   = $this->auditMdl->getAggregateLabel($rowsCol);
			$aggColumn2 = null;
			$aggTitle2  = null;
		}
		$showSalaryScale = ($rowsCol === 'job_name');
		return compact('aggColumn', 'aggTitle', 'aggColumn2', 'aggTitle2', 'showSalaryScale');
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
		$agg = $this->_auditReportAggregation($search);
		$data['aggTitle']   = $agg['aggTitle'];
		$data['aggColumn']  = $agg['aggColumn'];
		$data['aggTitle2']  = $agg['aggTitle2'];
		$data['aggColumn2'] = $agg['aggColumn2'];
		$data['showSalaryScale'] = $agg['showSalaryScale'];

		$data['filters'] = $this->DataPrep_mdl->getFilters(true);
		// Unchained: each dropdown gets full list from national_jobs (no parent dependency)
		$data['institution_types_for_chain'] = $this->DataPrep_mdl->getInstitutionTypesForChain('');
		$data['regions_for_chain'] = $this->DataPrep_mdl->getRegionsForChain();
		$data['districts_for_chain'] = $this->DataPrep_mdl->getDistrictsForChain('', array(), array());
		$data['facility_levels_for_chain'] = $this->DataPrep_mdl->getFacilityLevelsForChain('', array(), array(), array());
		$data['facilities_for_chain'] = $this->DataPrep_mdl->getFacilitiesForChain('', array(), array(), array(), array());
		$data['job_cadres_for_chain'] = $this->DataPrep_mdl->getJobCadresForChain('', array(), array(), array(), array(), array());
		$data['job_categories_for_chain'] = $this->DataPrep_mdl->getJobCategoriesForChain('', array(), array(), array(), array(), array(), array());
		$data['job_classifications_for_chain'] = $this->DataPrep_mdl->getJobClassificationsForChain('', array(), array(), array(), array(), array(), array(), array());
		$data['job_names_for_chain'] = $this->DataPrep_mdl->getJobNamesForChain('', array(), array(), array(), array(), array(), array(), array(), array());
		$data['audit']  = $this->auditMdl->getAuditReport($facilityId=FALSE);
		$data['legend']	= $this->auditMdl->auditReportLegend($search);
		$data['last_staff_update'] = $this->auditMdl->getLastStaffUpdate();
		$data['last_audit_generation'] = $this->auditMdl->getLastAuditGeneration();

		if(isset($search->getPdf ) && $search->getPdf == 1):
			// Use same filtered data as the view (getAuditReport reads filters from input->post())
			$html     = $this->load->view("audit/audit_report_pdf", $data, true);
			$districtLabel = !empty($_SESSION['district']) ? $_SESSION['district'] . '_' : '';
			$filename = $districtLabel . "audit_report_" . date('Y-m-d_His') . ".pdf";
			// makePdf with 'I' displays PDF in browser (inline); use 'D' for attachment/download
			$pdf = Modules::run('template/makePdf', $html, $filename, 'I');
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
		
		$search = (object) $this->input->post();
		$start = $this->input->post('start') ? (int) $this->input->post('start') : 0;
		$length = $this->input->post('length') ? (int) $this->input->post('length') : 10;
		// When Section by (top) is set, use 1000 rows per page so many sections can appear per page
		if (!empty($search->aggregate)) {
			$length = 1000;
		}
		$searchValue = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';
		$orderColumn = isset($this->input->post('order')[0]['column']) ? (int) $this->input->post('order')[0]['column'] : 0;
		$orderDir = isset($this->input->post('order')[0]['dir']) ? $this->input->post('order')[0]['dir'] : 'asc';
		
		$result = $this->auditMdl->getAuditReport(FALSE, true, $start, $length, $searchValue, $orderColumn, $orderDir);
		
		// Get totals from all filtered data (not just current page)
		$totals = $this->auditMdl->getAuditReportTotals(FALSE);
		
		$agg = $this->_auditReportAggregation($search);
		$aggColumn = $agg['aggColumn'];
		$aggColumn2 = $agg['aggColumn2'];
		$showSalaryScale = $agg['showSalaryScale'];

		$data = array();
		foreach ($result['data'] as $row) {
			$structure = $row->approved;
			$difference = $row->approved - $row->filled;
			$vacantPosts = (isset($row->vacant) && $row->vacant !== null && $row->vacant !== '') ? (int)$row->vacant : (($difference > 0) ? $difference : 0);
			$excessPosts = (isset($row->excess) && $row->excess !== null && $row->excess !== '') ? (int)$row->excess : (($difference < 0) ? $difference * -1 : 0);
			
			$male = ($structure > 0 && $row->filled > 0) ? ($row->male / $row->filled) * 100 : 0;
			$female = ($structure > 0 && $row->filled > 0) ? ($row->female / $row->filled) * 100 : 0;
			$vacant = ($structure > 0) ? ($vacantPosts / $structure) * 100 : 0;
			$filled = ($structure > 0) ? ($row->filled / $structure) * 100 : 0;
			
			$rowData = array($row->$aggColumn);
			if ($aggColumn2) $rowData[] = $row->$aggColumn2;
			if ($showSalaryScale) $rowData[] = $row->salary_scale;
			$rowData = array_merge($rowData, array(
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
			));
			$data[] = $rowData;
		}
		
		$legend = $this->auditMdl->auditReportLegend($search);
		$output = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $result['recordsTotal'],
			"recordsFiltered" => $result['recordsFiltered'],
			"data" => $data,
			"totals" => $totals,
			"legend" => $legend
		);
		
		echo json_encode($output);
	}

	/**
	 * Export audit report to Excel (HTML as .htm). Formatted with logo, title, and modern tables. Opens in same window as download.
	 */
	public function auditReportExcel() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$search = (object) $this->input->post();
		$agg = $this->_auditReportAggregation($search);
		$data['search'] = $search;
		$data['aggColumn'] = $agg['aggColumn'];
		$data['aggTitle'] = $agg['aggTitle'];
		$data['aggColumn2'] = $agg['aggColumn2'];
		$data['aggTitle2'] = $agg['aggTitle2'];
		$data['showSalaryScale'] = $agg['showSalaryScale'];
		$data['legend'] = $this->auditMdl->auditReportLegend($search);
		$data['audit'] = $this->auditMdl->getAuditReport(FALSE);
		$data['totals'] = $this->auditMdl->getAuditReportTotals(FALSE);
		$filename = 'audit_report_' . date('Y-m-d_His') . '.htm';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		echo $this->load->view('audit/audit_report_excel', $data, true);
		exit;
	}

	/**
	 * Export audit report to Word (HTML as .doc). Uses same filters and data as PDF; outputs HTML with application/msword so Word opens it.
	 */
	public function auditReportWord() {
		$this->_setCorsAndEmbedHeaders(false);
		Modules::run('dataprep/shareModel');
		$search = (object) $this->input->post();
		$data['search']     = $search;
		$agg = $this->_auditReportAggregation($search);
		$data['aggTitle']   = $agg['aggTitle'];
		$data['aggColumn']  = $agg['aggColumn'];
		$data['aggTitle2']  = $agg['aggTitle2'];
		$data['aggColumn2'] = $agg['aggColumn2'];
		$data['showSalaryScale'] = $agg['showSalaryScale'];
		$data['audit']      = $this->auditMdl->getAuditReport(FALSE);
		$data['legend']     = $this->auditMdl->auditReportLegend($search);
		$html = $this->load->view('audit/audit_report_word', $data, true);
		$filename = 'audit_report_' . date('Y-m-d_His') . '.doc';
		header('Content-Type: application/msword');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		echo $html;
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
		$data['aggTitle2']  = $this->auditMdl->getAggregateLabel(@$search->aggregate2);
		$data['aggColumn2'] = (!empty($search->aggregate2)) ? $search->aggregate2 : null;

		
		$data['filters']= $this->DataPrep_mdl->getFilters(true);
		$data['audit']  = $this->auditMdl->getAuditReport($facilityId);
		$data['legend']	= $this->auditMdl->auditReportLegend($search);
		return $data;
	
	}
	public function printfacAudit(){
            if($this->input->post('getPdf')==1){
			$html     = $this->load->view("audit/audit_report_fac_pdf","",true);
			$filename = $_SESSION['district']."_Facilities_audit_report_".date('Y-m-d_his').".pdf";
			Modules::run('template/makePdf',$html,$filename,'I');
			}
			else{
				$this->lfacAudit();
			}
			
	}

	public function lfacAudit(){
		if ($this->input->get_post('getPdf') != 1) {
			$this->_setCorsAndEmbedHeaders(true);
		}
		Modules::run('dataprep/shareModel');

		$search = (object) $this->input->post();
		$district_param = $this->input->get('districts') ?: $this->input->get('district');
		$display = $this->input->get('display') ?: 'ihris';

		$data['module']     = "audit";
		$data['page']       = "fac_audit";
		$data['title']      = "Facility Audit Report";
		$data['uptitle']    = "HRH Facility Audit Report";
		$data['search']     = $search;
		$data['embed']      = $this->embed;

		$data['aggTitle']   = $this->auditMdl->getAggregateLabel(@$search->aggregate);
		$data['aggColumn']  = (!empty($search->aggregate)) ? $search->aggregate : "job_name";
		$data['aggTitle2']  = $this->auditMdl->getAggregateLabel(@$search->aggregate2);
		$data['aggColumn2'] = (!empty($search->aggregate2)) ? $search->aggregate2 : null;

		$data['filters'] = $this->DataPrep_mdl->getFilters(true);
		$data['institution_types_for_chain'] = $this->DataPrep_mdl->getInstitutionTypesForChain('');
		$data['regions_for_chain'] = $this->DataPrep_mdl->getRegionsForChain();
		$data['districts_for_chain'] = $this->DataPrep_mdl->getDistrictsForChain('', array(), array());
		$data['facility_levels_for_chain'] = $this->DataPrep_mdl->getFacilityLevelsForChain('', array(), array(), array());
		$data['facilities_for_chain'] = $this->DataPrep_mdl->getFacilitiesForChain('', array(), array(), array(), array());
		$data['job_cadres_for_chain'] = $this->DataPrep_mdl->getJobCadresForChain('', array(), array(), array(), array(), array());
		$data['job_categories_for_chain'] = $this->DataPrep_mdl->getJobCategoriesForChain('', array(), array(), array(), array(), array(), array());
		$data['job_classifications_for_chain'] = $this->DataPrep_mdl->getJobClassificationsForChain('', array(), array(), array(), array(), array(), array(), array());
		$data['job_names_for_chain'] = $this->DataPrep_mdl->getJobNamesForChain('', array(), array(), array(), array(), array(), array(), array(), array());

		$data['legend'] = $this->auditMdl->auditReportLegend($search);
		$data['district_param'] = $district_param;

		//print_r($data['legend']."<br>".$data['district_param']."<br>".$display);
		exit;
		$data['display'] = $display;
		$data['ajax_query'] = http_build_query(array_filter(array(
			'districts' => $district_param,
			'display'   => $display
		)));
		$data['last_staff_update'] = $this->auditMdl->getLastStaffUpdate();
		$data['last_audit_generation'] = $this->auditMdl->getLastAuditGeneration();

		if ($this->embed) {
			echo Modules::run('template/layoutEmbed', $data);
		} else {
			echo Modules::run('template/layout', $data);
		}
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
			case 'district': $result = $this->DataPrep_mdl->getDistrictsForChain('', array(), $regions); break;
			case 'facility_level': $result = $this->DataPrep_mdl->getFacilityLevelsForChain('', array(), $regions, $districts, $facilities); break;
			case 'facility': $result = $this->DataPrep_mdl->getFacilitiesForChain('', array(), $regions, $districts, $levels); break;
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
