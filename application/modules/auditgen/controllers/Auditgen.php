<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditgen extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		
	}

 //iupdate gender 
	public function cache_filled(){
		 $gender_up=$this->db->query("UPDATE staff SET gender='Male' WHERE gender=''");
		//truncate filled
		 $this->truncate_filled();
		 $no_calcs=$this->db->query("SELECT facility_id,job_id,approved,filled FROM staff WHERE approved='0' AND filled='0' ORDER BY facility_id,job_id")->result_array();
         foreach($no_calcs as $no_calc){

			$job_id = $no_calc['job_id'];
            $facility_id = $no_calc['facility_id'];

			//count jobs
		$count_jobs=$this->db->query("SELECT * FROM staff WHERE job_id='$job_id' AND facility_id='$facility_id'")->result();
        $counted=$this->$count_jobs->num_rows();

		//update 
		$this->db->query("UPDATE  staff SET filled= '$counted' WHERE job_id='$job_id' AND facility_id='$facility_id'");

		 }
		 //render filled
		$data=$this->render_filled();
		//insert in to structure filled
		$this->insert('structure_filled',$data);

	echo $this->db->affected_rows();
	
	}


	//helper functions
	public function render_filled(){
		$data=$this->db->query('SELECT facility_id,dhis_facility_id,replace(facility_name,"\'","") as facility_name ,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_name,job_classification,job_category,cadre_name,salary_scale,approved,              
		(case when gender = "Male" then filled else 0 end) male,
		(case when gender = "Female" then filled else 0 end) female
		FROM   staff GROUP BY facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_classification,job_category,cadre_name,salary_scale,approved')->result();
	return $data;
    }
	
	//truncate structure filled
	public function truncate_filled(){
		 $this->db->query("TRUNCATE TABLE structure_filled");
	echo $this->db->affected_rows();

	}




	


}
