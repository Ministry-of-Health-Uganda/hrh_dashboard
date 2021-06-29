<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('facilities_mdl');

	}



	

	public function getFacilities()
	{
		$facilities=$this->facilities_mdl->getFacilities();


		return $facilities;

		//print_r($facilities);


	}
	public function getFacility($facility_id)
	{
		$data=$this->facilities_mdl->getFacility($facility_id);
		return $data;

	}
	



	


}
