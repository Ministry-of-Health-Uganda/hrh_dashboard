<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Districts_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function getDistricts()
	{

		$this->db->select('distinct(district_id),district_name');
		$this->db->where("district_id!=''");
		$query=$this->db->get('establishment');

		return $query->result();
 
	}

		public function getDistrict($id)
	{

		$this->db->select('district_name');
		$this->db->where('district_id',"district|".$id);
		$query=$this->db->get('establishment');

		return $query->row();
 
	}



	


}
