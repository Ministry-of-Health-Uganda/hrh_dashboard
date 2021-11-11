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

	//Cache Structure
	

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
		
        $data=$this->db->query("SELECT distinct facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM staff WHERE facility_type_name IN ('HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office' ,'Blood Bank Main Office'  ,'Blood Bank Regional Office'  ,'Medical Bureau Main Office'  ,'City Health Office' ) ORDER BY facility_type_name")->result_array();

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
		echo "<br><p style=color='green';>".$this->db->affected_rows()."</p> Template Structure";

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
				echo "<br><p style=color='green';>".$this->db->affected_rows()."</p> Template structure 2";



	}

	///////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////

	//Cache National Jobs

    public function cache_nationaljobs(){
	$data=$this->db->query("SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name 
	  AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type
	  AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name 
	  AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale
	  AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS 
	  fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS 
	  fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id 
	  AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name
	   AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f 
	   RIGHT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id)  ) UNION (SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name 
	   AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id 
	   AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale 
	   AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name 
	   AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS 
	   fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved 
	   AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f LEFT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id)  ");
          						
      $this->query("TRUNCATE TABLE national_jobs");

       foreach($data as $row1):
         
         if(!isset($row1['fill_facility_id'])){
             
                    $facility_id = $row1['app_facility_id'];
             
                    $dhis_facility_id = $row1['app_dhis_facility_id'];

                    $facility_name = $row1['app_facility_name'];
              
                    $facility_type_name = $row1['app_facility_type_name'];

		            $region_name = $row1['app_region_name'];
             
                    $institution_type = $row1['app_institution_type'];
              
                    $district_name = $row1['app_district_name'];

                    $job_id = $row1['app_job_id'];
              
                    $dhis_job_id = $row1['app_dhis_job_id'];

                    $job_name = $row1['app_job_name'];
              
                    $job_category = $row1['app_job_category'];

                    $job_classification = $row1['app_job_classification'];

                    $cadre_name = $row1['app_cadre_name'];

                    $salary_scale = $row1['app_salary_scale'];

                    $approved = $row1['app_approved'];

                    $male = $row1['app_male'];

                    $female = $row1['app_female'];
              
                    $total = $row1['app_total'];
             
         }elseif(!isset($row1['app_facility_id'])){
              
                    $facility_id = $row1['fill_facility_id'];
              
                    $dhis_facility_id = $row1['fill_dhis_facility_id'];

                    $facility_name = $row1['fill_facility_name'];
              
                    $facility_type_name = $row1['fill_facility_type_name'];

		            $region_name = $row1['fill_region_name'];
              
                    $institution_type = $row1['fill_institution_type'];
              
                    $district_name = $row1['fill_district_name'];

                    $job_id = $row1['fill_job_id'];
              
                    $dhis_job_id = $row1['fill_dhis_job_id'];

                    $job_name = $row1['fill_job_name'];
              
                    $job_category = $row1['fill_job_category'];

                    $job_classification = $row1['fill_job_classification'];

                    $cadre_name = $row1['fill_cadre_name'];

                    $salary_scale = $row1['fill_salary_scale'];

                    $approved = $row1['fill_approved'];

                    $male = $row1['fill_male'];

                    $female = $row1['fill_female'];
              
                    $total = $row1['fill_total'];
          }else{
              
                    $facility_id = $row1['fill_facility_id'];
              
                    $dhis_facility_id = $row1['fill_dhis_facility_id'];

                    $facility_name = $row1['app_facility_name'];
              
                    $facility_type_name = $row1['fill_facility_type_name'];

		            $region_name = $row1['fill_region_name'];
              
                    $institution_type = $row1['fill_institution_type'];
              
                    $district_name = $row1['fill_district_name'];

                    $job_id = $row1['fill_job_id'];
              
                    $dhis_job_id = $row1['fill_dhis_job_id'];

                    $job_name = $row1['fill_job_name'];
              
                    $job_category = $row1['fill_job_category'];

                    $job_classification = $row1['fill_job_classification'];

                    $cadre_name = $row1['fill_cadre_name'];

                    $salary_scale = $row1['fill_salary_scale'];

                    $approved = $row1['app_approved'];

                    $male = $row1['fill_male'];

                    $female = $row1['fill_female'];
              
                    $total = $row1['fill_total'];
              
          }
          




         $this->db->query("INSERT INTO national_jobs (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`) VALUES ('$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job_name','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','$male','$female','$total')"); 
                    
		endforeach;
		echo "<br><p style=color='green';>".$this->db->affected_rows()."</p> National Jobs";
	}

	public function cache_ownership(){
		$this->db->query("UPDATE national_jobs SET ownership='Public' WHERE institution_type IN ('National Referral Hospital, Central Government','Specialised Facility, Central Government','Ministry, Central Government','Regional Referral Hospital, Central Government','District, Local Government (LG)','UBTS, Central Government','City, Local Government (LG)','Municipality, Local Government (LG)')");


		$this->db->query("UPDATE national_jobs SET ownership='Private' WHERE institution_type LIKE 'UCBHCA, Private for Profit (PFPs)'");


		$this->db->query("UPDATE national_jobs SET ownership='PNFP' WHERE institution_type IN ('UCMB, Private not for Profit (PNFPs)', 'UPMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit (PNFPs)','UOMB, Private not for Profit (PNFPs)','Civil Society Organisations (CSO)')");


		$this->db->query("UPDATE national_jobs SET ownership='Prison' WHERE institution_type IN ('Uganda Prison Services, Security Forces')");


		$this->db->query("UPDATE national_jobs SET ownership='Police' WHERE institution_type IN ('Uganda Police, Security Forces')");
	}


	public function render_audit(){
		$this->cache_filled();
		$this->cache_structure();
		$this->cache_nationaljobs();
	}



	













	


}
