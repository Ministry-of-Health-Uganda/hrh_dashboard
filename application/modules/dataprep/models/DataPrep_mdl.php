<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPrep_mdl extends CI_Model {

	public function __Construct(){
		parent::__Construct();
	}

	public function cardreReport(){
		$this->db->select('cadre_name,COUNT(*) as count');
		$this->db->group_by('cadre_id');
		$qry = $this->db->get('staff');
		return $qry->result();
	}

	public function getAgeRanges(){
		$qry = $this->db->get('age_range_report');
		return $qry->row_array();
	}

	public function genderReport(){
		$qry = $this->db->get('gender_report');
		return $qry->row_array();
	}

	public function trackRates($data){
		$this->db->where('entry_id',$data['entry_id']);
		$this->db->update('attendance_rate',$data);
	}

	public function saveAttendance($data){

	   $attendanceData = array();
	   $finished = array(); //marks worked on facilities

	   foreach($data as $row):

		$row = (Object) $row;
		$date = $this->dateData($row->duty_date);

		$inFacility = array_filter($data,function($element) use($row) {
			return $element['facility_id'] == $row->facility_id;
		});

		if(!in_array($row->facility_id,$finished)):
			array_push($finished,$row->facility_id);

	        $totalStaffAttendance = count($inFacility);//has attendance
			$totalAtFacility = $this->countFacilityStaff($row->facility_id);

            $staff = $this->getStaffData($row->ihris_pid);

			$rates = array(
				'facility_id'=>$row->facility_id,
				'attendance_count'=>$totalStaffAttendance,
				'district_id'=>$staff->district_id,
				'district_name'=>$staff->district_name,
				'month'=>$date->month,
				'year'=>$date->year,
				'staff_count'=>$totalAtFacility,
				'entry_id'=>$row->facility_id.$date->year.$date->month);

		    $this->trackRates($rates);

		endif;


		$off       = $row->O; //off duty
		$present   = $row->P; //present
		$leave     = $row->L; //Leave
		$requested = $row->R; //Official Request
		$holiday   = (isset($row->H))?$row->H:0; //Holidays

		//considered on roster whatsoever
		$daysAttributed = $off + $present + $leave + $requested + $holiday;
		//not any type of leave & not round
		$absent = $date->monthdays - $daysAttributed;
		//not worked
		$daysNotAround =  $date->monthdays - $present;

		$staff = $this->getStaffData($row->ihris_pid);

		if($staff):

			$attendRow = array(

				"month"=>$date->month,
				"monthWords"=>$date->monthName,
				"days_tracked"=>$daysAttributed,
				"year"=>$date->year,
				"daysPresent"=>$present,
				"daysOffDuty"=>$off,
				"daysOnLeave"=>$leave,
				"daysRequest"=>$requested,
				"absolute_days_absent"=>$absent,
				"days_not_at_facility"=>$daysNotAround,
				"person_id"=>$staff->person_id,
				"cadre_name"=>$staff->cadre_name,
				"job_name"=>$staff->job_name,
				"salary_scale"=>$staff->salary_scale,
				"district_name"=>$staff->district_name,
				"region_name"=>$staff->region_name,
				"facility_type_name"=>$staff->facility_type_name,
				"facility_id"=>$staff->facility_id,
				"district_id"=>$staff->district_id,
				"facility_name"=>$staff->facility_name,
				"institution_type"=>$staff->institution_type
			);
			
			array_push($attendanceData,$attendRow);
			$this->db->insert('staff_attendance_dr',$attendRow);
		endif;

		endforeach;

		//$this->db->insert_batch('staff_attendance_dr',$attendanceData);

	   return $attendanceData;

	}

	public function saveRoster($data){

		$rosterData = array();
		$finished = array();

		foreach($data as $row):
			
			$row  = (Object) $row;

				if(!in_array($row->facility_id,$finished)):

					array_push($finished,$row->facility_id);

				$inFacility = array_filter($data,function($element) use($row) {
						return $element['facility_id'] == $row->facility_id;
					});

				$totalOnDutyRoster = count($inFacility);//on roster
	
				$date  = $this->dateData($row->duty_date);
				//considered on duty
				$totalAtFacility = $this->countFacilityStaff($row->facility_id);
				$totalAttendance = $this->countFacilityAttedance($row->facility_id,$date->month,$date->year);


				$staff = $this->getStaffData($row->ihris_pid);

				
				$rates = array(
					'facility_id'=>$row->facility_id,
					'roster_count'=>$totalOnDutyRoster,
					'month'=>$date->month,
					'district_id'=>$staff->district_id,
					'district_name'=>$staff->district_name,
					'year'=>$date->year,
					'staff_count'=>$totalAtFacility,
					'entry_id'=>$row->facility_id.$date->year.$date->month
				);

				$this->trackRates($rates);


				
				$present = count( array_filter($inFacility,function($element){
					return $element['D'] > 0; //
				}));

				$off = count( array_filter($inFacility,function($element){
					return $element['O'] > 0;
				}));

				$request = count( array_filter($inFacility,function($element){
					return $element['Z'] > 0;
				}));


				$annualleave = count( array_filter($inFacility,function($element){
					return $element['A'] > 0;
				}));

		
				if($staff):

						$attendRow = array(
			
							"month"=>$date->month,
							"monthWords"=>$date->monthName,
							"year"=>$date->year,
							"district_name"=>$staff->district_name,
							"region_id"=>$staff->region_id,
							"region_name2"=>$staff->region_name,
							"facility_type_name"=>$staff->facility_type_name,
							"facility_id"=>$staff->facility_id,
							"district_id"=>$staff->district_id,
							"facility_name"=>$staff->facility_name,
							"institution_type"=>$staff->institution_type,
							"total"=>$totalAtFacility,
							"total_dutyroster"=>$totalOnDutyRoster,
							"total_attendance"=>$totalAttendance,
							"present"=>$present,
							"off_duty"=>$off,
							"on_leave"=>$annualleave,
							"official_request"=>$request
						);

						array_push($rosterData,$attendRow);
						$this->db->insert('monthly_static_figures',$attendRow);
				endif;

				$data = @array_diff_assoc($data, $inFacility);

				endif;
 
		 endforeach;
		 return $rosterData;
	}


	private function dateData($date){

		$dateData = explode('-',$date);
		$days = cal_days_in_month(CAL_GREGORIAN,$dateData[1],$dateData[0]);
		$montNum = $dateData[1];
		$monthNYear = $dateData[0].'-'.$dateData[1];
		$data = array(
			"monthdays"=>$days,
			"month"=>$montNum,
			"monthName"=>$this->getMonthName($monthNYear),
			"year"=>$dateData[0]
		);
		return (Object) $data;
	}
	
	private function getMonthName($date){
		return date('F',strtotime($date."-01"));
	}

	private function getStaffData($personId){
		$this->db->where('person_id',$personId);
		$query = $this->db->get('staff');
		return $query->row();
	}

	private function countFacilityStaff($facility){
		$this->db->where('facility_id',$facility);
		$query = $this->db->get('staff');
		return $query->num_rows();
	}

	private function countFacilityAttedance($facility,$month,$year){
		$this->db->where('facility_id',$facility);
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$qry = $this->db->get('staff_attendance_dr');
		return $qry->num_rows();
	}

	public function getAttendance(){
		$qry = $this->db->get('attendance_report');
		return $qry->result();
	}

	public function getRoster(){
		
	}

	private function getCondition($search_input){

		$search = array(
			'facility_id' => $search_input->facility,
			'district_id' => $search_input->district,
			'region_name'   => $search_input->region,
			'institution_type' => $search_input->institution,
		);

		$condition = "";
		$count = 0;

		foreach($search as $key => $value ):
			
			$cond = ($count == 0)?' WHERE':' AND';
			if(!empty($value)){
				$condition .="$cond m.$key='$value'";
			     $count++;
			}
		endforeach;

		if($search_input->toDate){

			$toDate 	= explode('-',$search_input->toDate);

			$condition .= ($condition == '')?' WHERE':' AND';
			$condition .=' m.year<='.$toDate[0];
			$condition .=' AND m.month<='.$toDate[1];
		}

		if($search_input->fromDate){

			$fromDate = explode('-',$search_input->fromDate);

			$condition .= ($condition == '')?' WHERE':' AND';
			$condition .=' m.year>='.$fromDate[0];
			$condition .=' AND m.month>='.$fromDate[1];
		}

		// print_r($condition);
		// exit();

		return $condition;
	}


	public function getReportingRates(){

		$search_input = (Object) $this->input->post();
		$condition 	  =""; // $this->getCondition($search_input);

		$grouping = (!empty($search_input->grouping))?$search_input->grouping:'facility_id';

		$sql = 'SELECT 
				a.facility_name,
				a.district_name,
				m.monthWords,
				m.year,
				sum(a.staff_count) as staff_count,
				sum(a.roster_count) as roster_count,
				sum(a.attendance_count) as attendance_count
		        FROM monthly_static_figures m
		        RIGHT JOIN `attendance_rate` a 
		        on a.facility_id = m.facility_id
		        '.$condition." group by m.".$grouping;

		$qry = $this->db->query($sql);
		return $qry->result();
	}

	
	public function getAttendanceAnalysis(){

		$search_input = (Object) $this->input->post();
		$condition 	  = $this->getCondition($search_input);

		$grouping = (!empty($search_input->grouping))?$search_input->grouping:'facility_id';

		$sql = 'SELECT 
				m.facility_name,
				m.district_name,
				m.institution_type,
				m.monthWords,
				m.year,
				sum(m.days_tracked) as days_tracked,
				sum(m.daysPresent)  as daysPresent,
				sum(m.daysOnLeave)  as daysOnLeave,
				sum(m.daysOffDuty)  as daysOffDuty,
				sum(m.daysRequest) as daysRequest,
				sum(m.absolute_days_absent) as absolute_days_absent
		        FROM staff_attendance_dr m
		        '.$condition." group by m.".$grouping;

		$qry = $this->db->query($sql);
		return $qry->result();
	}

	/**
	 * Apply chained filters to current query (national_jobs). All params are "previous" selections.
	 * Column names match national_jobs: ownership, institution_type, region_name, district_name,
	 * facility_type_name, facility_name, cadre_name, job_category, job_classification.
	 */
	private function applyChainFilters($ownership = '', $institution_types = array(), $regions = array(), $districts = array(),
		$facility_levels = array(), $facilities = array(), $cadres = array(), $job_categories = array(), $job_classifications = array()) {
		if ($ownership !== '' && $ownership !== null) {
			$this->db->where('ownership', $ownership);
		}
		if (!empty($institution_types) && is_array($institution_types)) {
			$this->db->where_in('institution_type', $institution_types);
		}
		if (!empty($regions) && is_array($regions)) {
			$this->db->where_in('region_name', $regions);
		}
		if (!empty($districts) && is_array($districts)) {
			$this->db->where_in('district_name', $districts);
		}
		if (!empty($facility_levels) && is_array($facility_levels)) {
			$this->db->where_in('facility_type_name', $facility_levels);
		}
		if (!empty($facilities) && is_array($facilities)) {
			$this->db->where_in('facility_name', $facilities);
		}
		if (!empty($cadres) && is_array($cadres)) {
			$this->db->where_in('cadre_name', $cadres);
		}
		if (!empty($job_categories) && is_array($job_categories)) {
			$this->db->where_in('job_category', $job_categories);
		}
		if (!empty($job_classifications) && is_array($job_classifications)) {
			$this->db->where_in('job_classification', $job_classifications);
		}
	}

	/**
	 * Chain: Ownership -> Institution Type. Returns distinct institution_type from national_jobs.
	 */
	public function getInstitutionTypesForChain($ownership = '') {
		$this->db->select('institution_type');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('institution_type !=', '');
		$this->applyChainFilters($ownership);
		$this->db->order_by('institution_type', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: Region (start of chain). Returns all distinct region_name.
	 * Ownership and Institution Type are chained separately; region → job name chain does not use them.
	 */
	public function getRegionsForChain($ownership = '', $institution_types = array()) {
		$this->db->select('region_name');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('region_name !=', '');
		$this->db->order_by('region_name', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + District. Returns distinct district_name.
	 */
	public function getDistrictsForChain($ownership = '', $institution_types = array(), $regions = array()) {
		$this->db->select('district_name as district');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('district_name !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions);
		$this->db->order_by('district_name', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + Facility Level (facility_type_name). Returns distinct facility_type_name.
	 */
	public function getFacilityLevelsForChain($ownership = '', $institution_types = array(), $regions = array(), $districts = array()) {
		$this->db->select('facility_type_name as facility_type');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('facility_type_name !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions, $districts);
		$this->db->order_by('facility_type_name', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + Facility. Returns distinct facility_name.
	 */
	public function getFacilitiesForChain($ownership = '', $institution_types = array(), $regions = array(), $districts = array(), $facility_levels = array()) {
		$this->db->select('facility_name as facility');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('facility_name !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions, $districts, $facility_levels);
		$this->db->order_by('facility_name', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + Job Cadre (cadre_name). Returns distinct cadre_name.
	 */
	public function getJobCadresForChain($ownership = '', $institution_types = array(), $regions = array(), $districts = array(), $facility_levels = array(), $facilities = array()) {
		$this->db->select('cadre_name');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('cadre_name !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions, $districts, $facility_levels, $facilities);
		$this->db->order_by('cadre_name', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + Job Category. Returns distinct job_category.
	 */
	public function getJobCategoriesForChain($ownership = '', $institution_types = array(), $regions = array(), $districts = array(), $facility_levels = array(), $facilities = array(), $cadres = array()) {
		$this->db->select('job_category');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('job_category !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions, $districts, $facility_levels, $facilities, $cadres);
		$this->db->order_by('job_category', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + Job Classification. Returns distinct job_classification (as job_class).
	 */
	public function getJobClassificationsForChain($ownership = '', $institution_types = array(), $regions = array(), $districts = array(), $facility_levels = array(), $facilities = array(), $cadres = array(), $job_categories = array()) {
		$this->db->select('job_classification as job_class');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('job_classification !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions, $districts, $facility_levels, $facilities, $cadres, $job_categories);
		$this->db->order_by('job_classification', 'asc');
		return $this->db->get()->result();
	}

	/**
	 * Chain: + Job Name. Returns distinct job_name (as job).
	 */
	public function getJobNamesForChain($ownership = '', $institution_types = array(), $regions = array(), $districts = array(), $facility_levels = array(), $facilities = array(), $cadres = array(), $job_categories = array(), $job_classifications = array()) {
		$this->db->select('job_name as job');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('job_name !=', '');
		$this->applyChainFilters($ownership, $institution_types, $regions, $districts, $facility_levels, $facilities, $cadres, $job_categories, $job_classifications);
		$this->db->order_by('job_name', 'asc');
		return $this->db->get()->result();
	}

	/** Legacy: institution types (all or by category). Kept for backward compatibility. */
	public function getInstitutionTypesByCategory($category) {
		$this->db->select('institution_type');
		$this->db->distinct();
		$this->db->from('national_jobs');
		$this->db->where('institution_type !=', '');
		if (!empty($category)) {
			$this->db->where('TRIM(SUBSTRING_INDEX(institution_type, ",", -1))', $category);
		}
		$this->db->order_by('institution_type', 'asc');
		return $this->db->get()->result();
	}

	public function getFilters($showAll=false){

		$data['facilities']   = $this->db->get("facilities")->result();
		$data['districts']    = $this->db->get("districts")->result();
		$data['institutions'] = $this->db->get("institutions")->result();
		$data['regions'] 	  = $this->db->get("regions")->result();
		$data['period'] = $this->db->query("SELECT distinct CONCAT(month,'-',year) as month_year FROM `quarterly_national_jobs`")->result();


		if($showAll):
			$data['jobs']         = $this->db->get("jobs")->result();
			$data['facility_types']  = $this->db->get("facility_types")->result();
			$data['job_cadres']      = $this->db->get("job_cadres")->result();
			$data['job_classifications']  = $this->db->get("job_classifications")->result();
			$data['job_categories']  = $this->db->get("job_categories")->result();
			$data['ownership']  = $this->db->get("ownership")->result();
	    endif;

		return (Object) $data;
	}

	public function getAggregateLabel($aggregateLabel){
		$aggregate = str_replace('id',"",str_replace('_'," ",(!empty($aggregateLabel))?$aggregateLabel:"facility"));
		return $aggregate;
	}



}
