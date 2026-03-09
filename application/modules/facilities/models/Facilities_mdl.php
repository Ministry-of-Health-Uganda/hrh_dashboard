<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facilities_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();

	}



	public function getFacilities()
	{   $array=array('facility_type|HCIII','facility_type|HCII','facility_type|Ghospital
		','facility_type|HCIV','facility_type|Town');
		$this->db->select('distinct(facility),facility_id');
		$this->db->where_not_in('facility_type_id',$array);
		$this->db->order_by('facility', 'ASC');
		$query=$this->db->get('facilities');
		return $query->result();
 
	}
	public function getFacility($id)
	{   
		$this->db->select('facility');
		$this->db->where('facility_id',$id);
		$query=$this->db->get('facilities');
		return $query->row();
 
	}


    

	



	


}
