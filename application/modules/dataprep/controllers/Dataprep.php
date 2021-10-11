<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataprep extends MX_Controller {
	
	const BASE_URL = [
		1 => "https://hris2.health.go.ug/attendance/api/", 
		2 => "https://attend.health.go.ug/api/data/"
	];

	
	public function __Construct(){
		parent::__Construct();
		$this->load->model('DataPrep_mdl','mdl');
	}

	//Fetches  remote data and fills the db
	public function syncData(){

		for($i=1;$i<3;$i++){

			$this->getAttendanceData($i);
			$this->getRosterData($i);
		}
	}
	public function getStaff(){
	return $data;
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

	//Fetches roster data using the above baseurl, calls SendRequest
	public function getRosterData($opt=1){

		$endpoint ='person_roster/2021-08-01/2021-08-31';
		$url = self::BASE_URL[$opt]."$endpoint";

		$data = $this->sendRequest($url);
		$result = $this->mdl->saveRoster($data);
		$res = $this->prettyJSON($result);
		return true;
	}

	//Fetches attendance data using the above baseurl, calls SendRequest
	public function getAttendanceData($opt=2){

		$endpoint ='person_attend/2021-08-01/2021-08-31';
		$url  = self::BASE_URL[$opt]."$endpoint";
		$data = $this->sendRequest($url);
		$result  = $this->mdl->saveAttendance($data);
		$res  = $this->prettyJSON($result);
		return true;
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
		
		$data['search'] = (Object) $this->input->post();

		$rows = [];
		if($this->input->post()):
		  $rows =  $this->mdl->getReportingRates();
		endif;

		$data['data'] = $rows;
		$data['filters']= $this->mdl->getFilters();
		$data['page']   = 'reporting_rate';
		$data['module'] = 'dataprep';
		$data['title']	= 'Hello';

		echo Modules::run('template/layout',$data);
	}

	
	//Sends out requests
	public  function  sendRequest($url){

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

		return json_decode($output,true);
	}

	//pretty JSON
	private function prettyJSON($data){

		return json_encode($data);
		
	}



}
