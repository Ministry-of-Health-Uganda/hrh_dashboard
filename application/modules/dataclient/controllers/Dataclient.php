<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataclient extends MX_Controller {
	
	const BASE_URL = [
		1 => "https://hris2.health.go.ug/attendance/api/",
		
		2 => "https://attend.health.go.ug/api/data/",
        3 => "https://hris.health.go.ug/apiv1/api/" 
	];

	
	
	public function __Construct(){
		parent::__Construct();
        $this->load->model('dataclient_model','mdl');
	}
    public function index(){
		echo "I am Here Dont Joke";
	}
	
	public function syncData(){

		$this->getAttendanceData();
		$this->getRosterData();
		$this->getFacilityAttendance();

	}
	

	//Fetches roster data using the above baseurl, calls SendRequest
	public function getRosterDRS($opt=1){

		$endpoint ='person_roster/2021-01-01/2021-09-31';
		$url = self::BASE_URL[$opt]."$endpoint";

		$data   = $this->sendRequest($url);
		$result = $this->mdl->saveRoster($data);
		$res    = $this->prettyJSON($result);

		if($opt ==1):
			 $this->getRosterData(2);
		endif;

		echo $result;
	}
	public function getRosterHRM($opt=2){

		$endpoint ='person_roster/2021-01-01/2021-09-31';
		$url = self::BASE_URL[$opt]."$endpoint";

		$data   = $this->sendRequest($url);
		$result = $this->mdl->saveRoster($data);
		$res    = $this->prettyJSON($result);

		// if($opt ==1):
		// 	 $this->getRosterData(2);
		// endif;

		echo $result;
	}

	//Fetches attendance from District Duty Roster
	public function RosterAttendance($opt=1){

		$endpoint ='person_attend/2021-01-01/2021-05-31';
		$url  = self::BASE_URL[$opt]."$endpoint";
		$data = $this->sendRequest($url);
		$result  = $this->mdl->saveAttendance($data);
		$res  = $this->prettyJSON($result);
	   
		echo $result;
	}
	public function HRMAttendance($opt=2){

		$endpoint ='person_attend/2021-01-01/2021-05-31';
		$url  = self::BASE_URL[$opt]."$endpoint";
		$data = $this->sendRequest($url);
		$result  = $this->mdl->saveAttendance($data);
		$res  = $this->prettyJSON($result);

		// if($opt ==1):
		// 	$this->getAttendanceData(2);
	    // endif;
	   
		echo $result;
	}


    //Fetches attendance data from iHRIS Manage to update
	public function iHRISAttendance(){

		$endpoint ='person_attend/2021-01-01/2021-05-31';
		$url  = self::BASE_URL[3]."$endpoint";
		$data = $this->sendRequest($url);
		
        $result  = $this->mdl->saveAttendance($data);
		$res     = $this->prettyJSON($result);
        print_r($result);
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
