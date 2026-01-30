<?php 

$mysqli = mysqli_connect("localhost","root","password","hrh_dashboard");
     
////National jobs for filled

		 $SQL = mysqli_query($mysqli, "UPDATE staff SET gender='Male' WHERE gender=''");

                 $SQL = mysqli_query($mysqli, "TRUNCATE TABLE structure_filled");

		

    $SQL4 = "SELECT facility_id,job_id,approved,filled FROM staff WHERE approved=0 AND filled=0 ORDER BY facility_id,job_id";

      

      $result4 = mysqli_query($mysqli, $SQL4);

            
            while($row4 = mysqli_fetch_assoc($result4)){

                $job_id = $row4['job_id'];

                $facility_id = $row4['facility_id'];
                
               
                     $SQL5 = "SELECT COUNT(person_id) AS no FROM staff WHERE job_id='$job_id' AND facility_id='$facility_id'";
               
                     $result5 = mysqli_query($mysqli, $SQL5);
                    
                     $row5 = mysqli_fetch_assoc($result5);
                    
                     $no = $row5['no'];
                    
                    $SQL6 =     mysqli_query($mysqli,"UPDATE  staff SET filled= $no WHERE job_id='$job_id' AND facility_id='$facility_id'");     
                    
            
            }


		
          //loop where facility_name works fine
                // $sql3 = "SELECT facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_name,job_classification,job_category,cadre_name,salary_scale,approved,case when gender='Male' then count(person_id) else 0 end AS male,case when gender='Female' then count(person_id) else 0 end AS female
                // FROM   staff  GROUP BY dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_classification,job_category,cadre_name,salary_scale,approved;";
                
                //oldquery   
                $sql3 = "SELECT facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_name,job_classification,job_category,cadre_name,salary_scale,approved,              
                (case when gender = 'Male' then filled else 0 end) male,
                (case when gender = 'Female' then filled else 0 end) female
                FROM   staff  GROUP BY facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_classification,job_category,cadre_name,salary_scale,approved";
               

          $result3 = mysqli_query($mysqli, $sql3);

          while($row3 = mysqli_fetch_assoc($result3)){

                    $facility_id = $row3['facility_id'];
              
                    $dhis_facility_id = $row3['dhis_facility_id'];

                   // $facility_name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $row3['facility_name']);

		            $facility_name = $row3['facility_name'];
              
                    $facility_type_name = $row3['facility_type_name'];

		            $region_name = $row3['region_name'];
              
                    $institution_type = $row3['institution_type'];
              
                    $district_name = $row3['district_name'];

                    $job_id = $row3['job_id'];
              
                    $dhis_job_id = $row3['dhis_job_id'];

                    $job_name = $row3['job_name'];
              
                    $job_category = $row3['job_category'];

                    $job_classification = $row3['job_classification'];

                    $cadre_name = $row3['cadre_name'];

                    $salary_scale = $row3['salary_scale'];

                    $approved = $row3['approved'];

                    $male = $row3['male'];

                    $female = $row3['female'];
              
                    $total = $male + $female;
              
                   
              
              
              $SQL3 = mysqli_query($mysqli, "INSERT INTO structure_filled (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`) VALUES ('$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job_name','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','$male','$female','$total')"); 
                    
               
          }
          

		
?> 
