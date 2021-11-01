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

	public function getFilters($showAll=false){

		$data['facilities']   = $this->db->get("facilities")->result();
		$data['districts']    = $this->db->get("districts")->result();
		$data['institutions'] = $this->db->get("institutions")->result();
		$data['regions'] 	  = $this->db->get("regions")->result();

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
