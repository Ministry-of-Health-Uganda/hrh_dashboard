<?php 

    include('connect.php');
             $SQL =	mysqli_query($mysqli,"DELETE FROM structure WHERE approved=0 ");

             $SQL = mysqli_query($mysqli, "TRUNCATE TABLE structure_approved");

             $sql = "SELECT facility_name,facility_id FROM staff WHERE facility_type_id = 'facility_type|DHO' ";

             $result = mysqli_query($mysqli, $sql);

             while($row = mysqli_fetch_assoc($result)){

                    $facility_id = $row['facility_id'];

                    $facility_name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $row['facility_name']);
                 
                    $sql1 = mysqli_query($mysqli,"UPDATE staff SET facility_name='$facility_name' WHERE facility_id='$facility_id'");
	      }
             $sql = "SELECT facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM total_facilities_temp_districts WHERE facility_type_name IN ('Regional Referral Hospital','Ministry','National Referral Hospital','Specialised National Facility') ";

             $result = mysqli_query($mysqli, $sql);

             while($row = mysqli_fetch_assoc($result)){

                    $facility_type_name = $row['facility_type_name'];

		                $region_name = $row['region_name'];

                    $facility_name = $row['facility_name'];
                 
                    $facility_name2 = $facility_name.'%';

                    $facility_id = $row['facility_id'];

                    $dhis_facility_id = $row['dhis_facility_id'];

                    $institution_type = $row['institution_type'];

                    $district_name = $row['district_name'];

                    $sql1 = "SELECT approved,job,job_classification,job_id,job_category,cadre,salary_grade,dhis_job_id FROM structure WHERE facility_facility_level LIKE '$facility_name2' ";
                 
                 $result1 = mysqli_query($mysqli, $sql1);

                 while($row1 = mysqli_fetch_assoc($result1)){
                      
                          $job = $row1['job']; 

                          $approved = $row1['approved']; 

                          $job_id = $row1['job_id'];

                          $dhis_job_id = $row1['dhis_job_id'];

                          $job_classification = $row1['job_classification'];

                          $job_category = $row1['job_category']; 

                          $cadre_name = $row1['cadre']; 

                          $salary_scale = $row1['salary_grade'];

                   
                      $SQL4 = mysqli_query($mysqli, "INSERT INTO structure_approved (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`,`excess`,`vacant`,`pec_filled`) VALUES ('$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','0','0','0','0','0','0')");   
                        
                    }
              
                        
                    }



//$sql = "SELECT facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM total_facilities_temp_districts WHERE facility_type_name IN ('HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office' ,'Blood Bank Main Office'  ,'Blood Bank Regional Office'  ,'Medical Bureau Main Office'  ,'City Health Office' ) ORDER BY facility_type_name";


$sql = "SELECT distinct facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM staff WHERE facility_type_name IN ('HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office' ,'Blood Bank Main Office'  ,'Blood Bank Regional Office'  ,'Medical Bureau Main Office'  ,'City Health Office' ) ORDER BY facility_type_name";

//$sql = "SELECT facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM total_facilities_temp_districts WHERE facility_type_name IN ('DHOs Office') ORDER BY facility_type_name";

//$sql = "SELECT facility_name,facility_type_name,facility_id,dhis_facility_id,institution_type,district_name FROM total_facilities_temp_districts WHERE facility_type_name IN ('DHOs Office' ) ORDER BY facility_type_name";

             $result = mysqli_query($mysqli, $sql);

             while($row = mysqli_fetch_assoc($result)){

                    $facility_type_name = $row['facility_type_name'];

		                $region_name = $row['region_name'];

                    $facility_name = $row['facility_name'];

		   // $facility_name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $row3['facility_name']);

                    $facility_id = $row['facility_id'];

                    $dhis_facility_id = $row['dhis_facility_id'];

                    $institution_type = $row['institution_type'];

                    $district_name = $row['district_name'];

                    $sql1 = "SELECT approved,job,job_classification,job_id,job_category,cadre,salary_grade,dhis_job_id FROM structure WHERE facility_facility_level = '$facility_type_name' ";
                 
                 $result1 = mysqli_query($mysqli, $sql1);

               while($row1 = mysqli_fetch_assoc($result1)){
                      
                          $job = $row1['job']; 

                          $approved = $row1['approved']; 

                          $job_id = $row1['job_id'];

                          $dhis_job_id = $row1['dhis_job_id'];

                          $job_classification = $row1['job_classification'];

                          $job_category = $row1['job_category']; 

                          $cadre_name = $row1['cadre']; 

                          $salary_scale = $row1['salary_grade'];

                    
                      $SQL4 = mysqli_query($mysqli, "INSERT INTO structure_approved (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`,`excess`,`vacant`,`pec_filled`) VALUES ('$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','0','0','0','0','0','0')");   
                        
                    }
         
                       
                    }


     	
?> 
