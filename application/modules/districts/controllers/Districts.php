<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Districts extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('districts_mdl');

	}



	public function getDistricts()
	{

		$districts=$this->districts_mdl->getDistricts();

		return $districts;

		//print_r($districts);

		
 
	}
	
		public function getAttDistricts()
	{
            $this->db->select('distinct(district),district_id');
			$qry=$this->db->get('ihris_att');
		    $districts=$qry->result();

		return $districts;

		//print_r($districts);

		
 
	}
	
	
	public function getAttFacilityName($id){

			$this->db->select('facility');
			$this->db->where('facility_id',"facility|".$id);
			$qry=$this->db->get('ihris_att');

			$res=$qry->row();

			return $res->facility;
	    }


		public function getDistrict($id)
	{

		$district=$this->districts_mdl->getDistrict($id);

		return $district->district;

		//print_r($district);
 
	}
	
	public function getFacilityName($id){

			$this->db->select('facility');
			$this->db->where('facility_id',"facility|".$id);
			$qry=$this->db->get('establishment');

			$res=$qry->row();

			return $res->facility;
	    }
	



	


}
