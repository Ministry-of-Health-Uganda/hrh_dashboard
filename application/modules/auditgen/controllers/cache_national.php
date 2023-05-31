<?php 

    include('connect.php');
     
    $sql1 =	"(SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f RIGHT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id)  ) UNION (SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f LEFT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id)  )";
                                        

								
      $SQL1 = mysqli_query($mysqli, "TRUNCATE TABLE national_jobs");

       $result1= mysqli_query($mysqli, $sql1);

       while($row1 = mysqli_fetch_assoc($result1)){
         
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
		 
		    $month = date('F');
		    $year = date('Y');
	
              
          }
          




         $SQL3 = mysqli_query($mysqli, "INSERT INTO national_jobs (`month`,`year`,`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`) VALUES ('$month','$year','$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job_name','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','$male','$female','$total')"); 
                    
                                  
                                 
     }
                                 

?> 
