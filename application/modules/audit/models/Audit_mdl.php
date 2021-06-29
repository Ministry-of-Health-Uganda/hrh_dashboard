<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();

	}





	public function getJobs($district,$facility_id,$facility_type)
	{
		
		//$this->db->cache_delete_all();

		if($district){

			$this->db->where('establishment.district_id',"district|".$district);

		}

		if($facility_id){

			$this->db->where('establishment.facility_id',$facility_id);

		}

		if($facility_type){

			$this->db->where('establishment.facility_type',$facility_type);

		}

	/*	if(!$district){

			$this->db->limit(20,0);

		}*/

		$this->db->select('distinct(establishment.job_id),job');

		$this->db->where("establishment.job!=''");
		$query=$this->db->get('establishment');

		return $query->result();
 
	}




	public function getEstablishment($district,$facility,$job_id,$facility_type){

		
		  $this->db->select('sum(approved) as esta,sum(filled)  as filled,sum(excess_vacant) as excess_vacant');

		     

			if($district){

		      $this->db->where('establishment.district_id',"district|".$district);

		      
			}

			if($facility){
		       $this->db->where('establishment.facility_id',$facility);

			}

			if($facility_type){

				$this->db->where('establishment.facility_type',$facility_type);
			}

		$this->db->where('establishment.job_id',"job|".$job_id);

       $query=$this->db->get('establishment');

		$result=$query->row();

		return $result;

		

	}


	public function getJob($id)
	{
		$this->db->select('distinct(job_id),job');
		$this->db->where('job_id',$id);
		$query=$this->db->get('establishment');

		return $query->row();
 
	}

	public function getFacilities($district){

		$this->db->select('distinct(facility),facility_id');
		$this->db->where('district_id',"district|".$district);
		$query=$this->db->get('ihris_att');

		return $query->result();


	}

	public function getSchedules(){

		$this->db->where('purpose','a');

		$qry=$this->db->get('schedules');

		return $qry->result();
	}




	public function getAtt($district,$facility_id,$year,$month,$facility_type){

			$this->db->select('distinct(job),job_id');

            if($facility_id){

			$this->db->where('facility_id',$facility_id);

		}
		
		  if($facility_type){

			$this->db->where('facility_type',$facility_type);

		}

		if($district){

			$this->db->where('district_id',"district|".$district);

		}


			$this->db->where("month",$month);
			$this->db->where("year",$year);

			
						
		$query=$this->db->get('ihris_att');

		$result=$query->result();

		return $result;
	}

	public function sumAtt($schedule,$job_id,$facility,$district,$year,$month,$facility_type){



		$this->db->select("sum(".$schedule.") as att");

		if($facility){

			$this->db->where('facility_id',$facility);

		}

		if($district){

			$this->db->where('district_id',"district|".$district);

		}
		
		
		if($facility_type){

			$this->db->where('facility_type',$facility_type);

		}

		$this->db->where('job_id',$job_id);

			$this->db->where("month='$month'");
			$this->db->where("year ='$year'");

			$query=$this->db->get('ihris_att');

			$result=$query->row();

			return $result->att;


	}


	



	


}
