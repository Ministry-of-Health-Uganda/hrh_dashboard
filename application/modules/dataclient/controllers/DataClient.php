<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataClient extends MX_Controller {
	
	const BASE_URL = [
		1 => "https://hris2.health.go.ug/attendance/api/", 
		2 => "https://attend.health.go.ug/api/data/",
        3 => "https://hris.health.go.ug/apiv1/api/" 
	];

	
	public function __Construct(){
		parent::__Construct();
        $this->load->model('dataclient_model','mdl');
	}

    //Fetches attendance data to update
	public function getFacilityAttendance(){

		$endpoint ='person_attend/2021-08-01/2021-08-01';
		$url  = self::BASE_URL[3]."$endpoint";
		$data = $this->sendRequest($url);
		
        $result  = $this->mdl->saveAttendanceSummary($data);
		$res     = $this->prettyJSON($result);

        echo $res;
		//print_r($data);
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
