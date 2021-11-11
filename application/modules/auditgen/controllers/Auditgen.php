<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditgen extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		
	}
	//Cache Filled

		///////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
 //iupdate gender 
	public function cache_filled(){
		 $gender_up=$this->db->query("UPDATE staff SET gender='Male' WHERE gender=''");
		//truncate filled
		 $this->truncate_filled();
		 $no_calcs=$this->db->query("SELECT facility_id,job_id,approved,filled FROM staff WHERE approved='0' AND filled='0' ORDER BY facility_id,job_id")->result();
         foreach($no_calcs as $no_calc){

			$job_id = $no_calc->job_id;
            $facility_id = $no_calc->facility_id;
			//print_r($no_calc);

			//count jobs
		$count_jobs=$this->db->query("SELECT count(person_id) as count FROM staff WHERE job_id='$job_id' AND facility_id='$facility_id'")->result();
        

		$counted=$count_jobs[0]->count;

		// //update 
		$this->db->query("UPDATE  staff SET filled= '$counted' WHERE job_id='$job_id' AND facility_id='$facility_id'");

		}
		 //render filled
		$this->render_filled();
	
	}


	//helper functions
	public function render_filled(){
		$data=$this->db->query('INSERT into structure_filled SELECT facility_id,dhis_facility_id,replace(facility_name,"\'","") as facility_name ,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_name,job_classification,job_category,cadre_name,salary_scale,approved,              
		(case when gender = "Male" then filled else 0 end) male,
		(case when gender = "Female" then filled else 0 end) female
		FROM   staff GROUP BY facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_classification,job_category,cadre_name,salary_scale,approved');
	echo "<br><p style=color='green';>".$this->db->affected_rows()."</p>";
    }
	
	//truncate structure filled
	public function truncate_filled(){
		 $this->db->query("TRUNCATE TABLE structure_filled");
	//echo $this->db->affected_rows();

	}

	///////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////

	//Cache 
	

	public function cache_structure(){
		$this->db->query("DELETE FROM structure WHERE approved='0'");
		$this->db->query("TRUNCATE TABLE structure_approved");
		$query=$this->db->query('SELECT replace(facility_name,"\'","") as facility_name ,facility_id FROM staff WHERE facility_type_id = "facility_type|DHO"')->result();

		foreach($query as $row):
			       $facility_id = $row->facility_id;
                    $facility_name = $row->facility_name;
					
                    $this->db->query("UPDATE staff SET facility_name='$facility_name' WHERE facility_id='$facility_id'");
		endforeach;
		
		$this->template_structure_approved();
		$this->template_structure_approved2();
		


	}

	public function template_structure_approved(){
		
        $data=$this->db->query("SELECT distinct facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM staff WHERE facility_type_name IN ('HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office' ,'Blood Bank Main Office'  ,'Blood Bank Regional Office'  ,'Medical Bureau Main Office'  ,'City Health Office' ) ORDER BY facility_type_name")->result_arry();

		foreach($data as $row):

		$facility_type_name = $row['facility_type_name'];

		$region_name = $row['region_name'];

		$facility_name = $row['facility_name'];
	 
		$facility_name2 = $facility_name.'%';

		$facility_id = $row['facility_id'];

		$dhis_facility_id = $row['dhis_facility_id'];

		$institution_type = $row['institution_type'];

		$district_name = $row['district_name'];

		$mydata->db->query("SELECT approved,job,job_classification,job_id,job_category,cadre,salary_grade,dhis_job_id FROM structure WHERE facility_facility_level LIKE '$facility_name2'");

		foreach($mydata as $row1):
			$job = $row1['job']; 

			$approved = $row1['approved']; 

			$job_id = $row1['job_id'];

			$dhis_job_id = $row1['dhis_job_id'];

			$job_classification = $row1['job_classification'];

			$job_category = $row1['job_category']; 

			$cadre_name = $row1['cadre']; 

			$salary_scale = $row1['salary_grade'];

	 
		$this->db->query("INSERT INTO structure_approved (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`,`excess`,`vacant`,`pec_filled`) VALUES ('$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','0','0','0','0','0','0')");   
		  

		endforeach;
		
		endforeach;
		echo "<br><p style=color='green';>".$this->db->affected_rows()."</p>";

	}
	public function template_structure_approved2(){
		$data=$this->db->query("SELECT distinct facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM staff WHERE facility_type_name IN ('HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office' ,'Blood Bank Main Office'  ,'Blood Bank Regional Office'  ,'Medical Bureau Main Office'  ,'City Health Office' ) ORDER BY facility_type_name")->result_array();
             foreach($data as $row):

                    $facility_type_name = $row['facility_type_name'];

		            $region_name = $row['region_name'];

                    $facility_name = $row['facility_name'];

                    $facility_id = $row['facility_id'];

                    $dhis_facility_id = $row['dhis_facility_id'];

                    $institution_type = $row['institution_type'];

                    $district_name = $row['district_name'];

         $data1=$this->db->query("SELECT approved,job,job_classification,job_id,job_category,cadre,salary_grade,dhis_job_id FROM structure WHERE facility_facility_level = '$facility_type_name' ")->result_array();
                 foreach($data1 as $row1):
                      
                          $job = $row1['job']; 

                          $approved = $row1['approved']; 

                          $job_id = $row1['job_id'];

                          $dhis_job_id = $row1['dhis_job_id'];

                          $job_classification = $row1['job_classification'];

                          $job_category = $row1['job_category']; 

                          $cadre_name = $row1['cadre']; 

                          $salary_scale = $row1['salary_grade'];

                    
                      $SQL4 = mysqli_query($mysqli, "INSERT INTO structure_approved (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`,`excess`,`vacant`,`pec_filled`) VALUES ('$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','0','0','0','0','0','0')");   
                        
				 endforeach;
         
                       
                endforeach;
				echo "<br><p style=color='green';>".$this->db->affected_rows()."</p>";



	}

	













	


}
