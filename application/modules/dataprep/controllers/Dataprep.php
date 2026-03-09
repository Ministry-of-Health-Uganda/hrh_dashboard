<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataprep extends MX_Controller {
	
	public function __Construct(){
		parent::__Construct();
		$this->load->model('DataPrep_mdl','mdl');
	}


	//shares model with other modules
	public function shareModel(){

		$this->load->model('DataPrep_mdl');
	}

	//fetch roster data
	public function getRoster(){
		$this->mdl->getRoster();
	}

	private function organizeAttendanceReport($data){

		$labels  = array();
		$present = array();
		$leave   = array();
		$off     = array();

		foreach($data as $row):
			array_push($labels,$row->period);
			array_push($present,intval($row->present));
			array_push($leave,intval($row->official_request));
			array_push($off,intval($row->off_duty));
		endforeach;

		$organizedData = array(
			'labels'  => $labels,
			'plotData'=>[
			 array('data'=>$present,'name'=>'Present'),
			 array('data'=>$leave,'name'=>'On Leave'),
			 array('data'=>$off,'name'=>'Off duty')
			]
		);
		return (Object) $organizedData;
	}

	//fetch attendance data
	public function getAttendance(){

		$rows = $this->mdl->getAttendance();
		$data = $this->organizeAttendanceReport($rows);

		$res = array(
			'labels'=>$data->labels,
			'plots' =>$data->plotData
	    );

		return $res;
	}


	private function cardreReport(){

		$result = $this->mdl->cardreReport();

		$data = array();

		foreach($result as $res):
			$row[0] = $res->cadre_name;
			$row[1] = intval($res->count);
			array_push($data,$row);
		endforeach;

		return  array("labels"=>'',"values"=>$data);
	}


	public function genderReport(){

		$result = $this->mdl->genderReport();

		$labels = array_keys($result );
		$values = array_map('intval',array_values($result ));
		$data = array();

		for($i=0;$i<count($values);$i++):
			 $row[0] =$labels[$i];
			 $row[1] =$values[$i];
			 array_push($data,$row);
		endfor;

		$genderdata   = array("labels"=>$labels,"values"=>$data);

		return $genderdata;
	}


	public function ageRanges(){

		$result  = $this->mdl->getAgeRanges();
		$labels  = array_keys($result );

		$values  = array_map('intval',array_values($result ));
		$ageData = array("labels"=>$labels,"values"=>$values);

		$cadreData = $this->cardreReport();
		$genderData = $this->genderReport();
		$attendance = $this->getAttendance();

		$data   = array(
		'agedata'=>$ageData,
		'genderdata'=>$genderData,
		'cadredata'=>$cadreData,
		'attendance'=>$attendance
	   );

	   $res    = $this->prettyJSON($data);
		
		echo  $res ;
	}


	public function rates(){
		
		$search = (Object) $this->input->post();

		$rows = [];
		if($this->input->post()):
		  $rows =  $this->mdl->getReportingRates();
		endif;

		$data['search']   = $search;
		$data['data']     = $rows;
		$data['filters']  = $this->mdl->getFilters();
		$data['page']     = 'reporting_rate';
		$data['module']   = 'dataprep';
		$data['title']	  = 'Hello';
		$data['aggTitle'] = $this->mdl->getAggregateLabel(@$search->grouping);
		$data['aggColumn'] = (!empty($search->grouping))?str_replace('id', 'name',$search->grouping):'facility_name';

		echo Modules::run('template/layout',$data);
	}


	//attendance analysis
	public function absenteesm(){
		
		$search = (Object) $this->input->post();

		$rows = [];
		if($this->input->post()):
		  $rows =  $this->mdl->getAttendanceAnalysis();
		endif;

		$data['search']   = $search;
		$data['data']     = $rows;
		$data['filters']  = $this->mdl->getFilters();
		$data['page']     = 'attendance_analysis';
		$data['module']   = 'dataprep';
		$data['title']	  = 'Hello';
		$data['aggTitle'] = $this->mdl->getAggregateLabel(@$search->grouping);
		$data['aggColumn'] = (!empty($search->grouping))?str_replace('id', 'name',$search->grouping):'facility_name';

		echo Modules::run('template/layout',$data);
	}

	//pretty JSON
	private function prettyJSON($data){
		return json_encode($data);
	}



}
