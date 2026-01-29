<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditgen extends MX_Controller {

	
	public function __Construct(){

		$this->backupdir = "/var/HRH_bkp";
        $this->db_user = 'ihris_manage';
        $this->db_pass = 'managi123';
        $this->host='172.27.1.109';
        $this->database ='hrh_dashboard';
      
	}
    
public function index(){
    //echo "Test";
}

public function dbcon(){
    $dbConn = new mysqli($this->host,$this->db_user,$this->db_pass,$this->database);
    if($dbConn->connect_error)
    {
	die("Database Connection Error, Error No.: ".$dbConn->connect_errno." | ".$dbConn->connect_error);
    }
return $dbConn;
}
	//Cache Filled

		////////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
 //iupdate gender 
	public function cache_filled(){
		$query=$this->db->query('SELECT Distinct facility_name, facility_id FROM staff')->result();

		foreach($query as $row):
			       $facility_id = $row->facility_id;
                    $facility_name = str_replace(".","",str_replace(")","",str_replace("(","-",str_replace("'","",$row->facility_name))));
					
                    $this->db->query("UPDATE staff SET facility_name='$facility_name' WHERE facility_id='$facility_id'");
		endforeach;

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
		$data=$this->db->query("INSERT into structure_filled SELECT facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_name,job_classification,job_category,cadre_name,salary_scale,approved,              
		(case when gender = 'Male' then filled else 0 end) male,
		(case when gender = 'Female' then filled else 0 end) female, ((case when gender = 'Male' then filled else 0 end)+
		(case when gender = 'Female' then filled else 0 end)) as total,'0','0','0'
		FROM   staff  GROUP BY facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_classification,job_category,cadre_name,salary_scale,approved");
	echo "<br><p style=color='green';>".$this->db->affected_rows()."</p>";
    }
	
	//truncate structure filled
	public function truncate_filled(){
		 $this->db->query("TRUNCATE TABLE structure_filled");
	}

	///////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////

	//Cache Structure
	

	public function cache_structure(){
		$this->db->query("DELETE FROM structure WHERE approved='0'");
		$this->db->query("TRUNCATE TABLE structure_approved");
		$this->template_structure_approved1();
		$this->template_structure_approved2();
		


	}

	public function template_structure_approved1(){
		$sql = "SELECT facility_name,facility_type_name,region_name,facility_id,dhis_facility_id,institution_type,district_name FROM total_facilities_temp_districts WHERE facility_type_name IN ('Regional Referral Hospital','Ministry','National Referral Hospital','Specialised National Facility') ";
            
		$data=$this->db->query($sql)->result_array();
		foreach($data as $row):

			$facility_type_name = $row['facility_type_name'];

			$region_name = $row['region_name'];

			$facility_name = $row['facility_name'];
		 
			$facility_id = $row['facility_id'];

			$dhis_facility_id = $row['dhis_facility_id'];

			$institution_type = $row['institution_type'];

			$district_name = $row['district_name'];
			
			$sql1 = "SELECT approved,job,job_classification,job_id,job_category,cadre,salary_grade,dhis_job_id FROM structure WHERE facility_facility_level LIKE '$facility_name' ";
			$mydata=$this->db->query($sql1)->result_array();      
	
		//endforeach;
		foreach($mydata as $row1):
			$job = $row1['job']; 

			$approved = $row1['approved']; 

			$job_id = $row1['job_id'];

			$dhis_job_id = $row1['dhis_job_id'];

			$job_classification = $row1['job_classification'];

			$job_category = $row1['job_category']; 

			$cadre_name = $row1['cadre']; 

			$salary_scale = $row1['salary_grade'];


			$facility_namei=str_replace(")","",str_replace("(","-",str_replace("'","",$facility_name)));
			$facility_type_namei=  str_replace(")","",str_replace("(","-",str_replace("'","",$facility_type_name)));
			
		

	 
		 $sql="INSERT INTO structure_approved (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`,`excess`,`vacant`,`pec_filled`) VALUES ('$facility_id','$dhis_facility_id','$facility_namei','$facility_type_namei','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','0','0','0','0','0','0')";   
		 $this->dbcon()->query("$sql");
        //print_r($mydata);
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

					      $facility_namei=str_replace(")","",str_replace("(","-",str_replace("'","",$facility_name)));
						  $facility_type_namei= str_replace(")","",str_replace("(","-", str_replace("'","",$facility_type_name)));
						
                      
                          $job = $row1['job']; 

                          $approved = $row1['approved']; 

                          $job_id = $row1['job_id'];

                          $dhis_job_id = $row1['dhis_job_id'];

                          $job_classification = $row1['job_classification'];

                          $job_category = $row1['job_category']; 

                          $cadre_name = $row1['cadre']; 

                          $salary_scale = $row1['salary_grade'];

					
						 $this->db->query("INSERT INTO structure_approved (`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`,`excess`,`vacant`,`pec_filled`) VALUES ('$facility_id','$dhis_facility_id','$facility_namei','$facility_type_namei','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','0','0','0','0','0','0')");   
                        
				 endforeach;
         
                       
                endforeach;
				echo "<br><p style=color='green';>".$this->db->affected_rows()."</p> Template structure 2";



	}

	///////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////

	//Cache National Jobs

    public function cache_nationaljobs(){
		
		$this->db->query("TRUNCATE TABLE national_jobs");

		$sql1 = "(SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f RIGHT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id)  ) UNION (SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f LEFT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id))";

		$result1 = $this->db->query($sql1)->result_array();

		foreach($result1 as $row1) {

			if (!isset($row1['fill_facility_id'])) {

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

			} elseif (!isset($row1['app_facility_id'])) {

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
			} else {

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





			$SQL3 = $this->db->query("INSERT INTO national_jobs (`month`,`year`,`facility_id`,`dhis_facility_id`,`facility_name`,`facility_type_name`,`region_name`,`institution_type`,`district_name`,`job_id`,`dhis_job_id`,`job_name`,`job_classification`,`job_category`,`cadre_name`,`salary_scale`,`approved`,`male`,`female`,`total`) VALUES ('$month','$year','$facility_id','$dhis_facility_id','$facility_name','$facility_type_name','$region_name','$institution_type','$district_name','$job_id','$dhis_job_id','$job_name','$job_classification','$job_category','$cadre_name','$salary_scale','$approved','$male','$female','$total')");



		}                              
    
	    
		echo "<br><p style=color='green';>".$this->db->affected_rows()."</p> National Jobs";
	}

	public function cache_ownership(){
		$this->db->query("UPDATE national_jobs SET ownership='Public' WHERE institution_type IN ('National Referral Hospital, Central Government','Specialised Facility, Central Government','Ministry, Central Government','Regional Referral Hospital, Central Government','District, Local Government (LG)','UBTS, Central Government','City, Local Government (LG)','Municipality, Local Government (LG)','Ministry','City, Local Government -LG','Municipality, Local Government -LG','District, Local Government -LG' )");
		$this->db->query("UPDATE national_jobs SET ownership='Private' WHERE institution_type IN ('UCBHCA, Private for Profit (PFPs)','UCBHCA', 'Private for Profit -PFPs','UCBHCA', 'Private for Profit -PFPs')");
		$this->db->query("UPDATE national_jobs SET ownership='PNFP' WHERE institution_type IN ('UCMB, Private not for Profit (PNFPs)', 'UPMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit (PNFPs)','UOMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit -PNFPs','UOMB, Private not for Profit -PNFPs','UCMB, Private not for Profit -PNFPs','UPMB, Private not for Profit -PNFPs','Civil Society Organisations -CSO, Private not for Profit -PNFPs','Civil Society Organisations -CSO, Private not for Profit -PNFPs','Civil Society Organisations (CSO)','Civil Society Organisations (CSO), Private not for Profit (PNFPs)')");
		$this->db->query("UPDATE national_jobs SET ownership='Prison' WHERE institution_type IN ('Uganda Prison Services, Security Forces')");
		$this->db->query("UPDATE national_jobs SET ownership='Police' WHERE institution_type IN ('Uganda Police, Security Forces')");
		$this->db->query("UPDATE national_jobs SET ownership='UPDF' WHERE institution_type IN ('Uganda Peoples Defence Force (UPDF), Security Forces','Uganda Peoples Defence Force -UPDF, Security Forces','Uganda Peoples Defence Force -UPDF, Security Forces')");
	echo $this->db->affected_rows()." updated";
	}


	public function render_audit(){
		//$this->db->query("CALL populate_ihrisdata()");
		$this->fetch_ihrisdata();
		$this->cache_filled();
		$this->cache_structure();
		$this->cache_nationaljobs();
		$this->cache_ownership();
	}

	public function quarterly_jobs()
	{
		$this->db->query("INSERT into quarterly_national_jobs SELECT * from national_jobs");
	}

	private function _draw_progress_bar($current, $total, $bar_length = 50) {
		if ($total == 0) return '';
		
		$percentage = min(100, round(($current / $total) * 100, 1));
		$filled = round(($percentage / 100) * $bar_length);
		$empty = $bar_length - $filled;
		
		$bar = '[' . str_repeat('=', $filled) . str_repeat(' ', $empty) . ']';
		return sprintf("%s %s%% (%s/%s)", $bar, $percentage, number_format($current), number_format($total));
	}

	public function fetch_ihrisdata($offset = 0, $batch_size = 100){
		// Clear existing data before starting
		$this->db->query("TRUNCATE TABLE ihrisdata");
		
		$base_url = "https://hris.health.go.ug/apiv1/index.php/api/ihrisdatapaginated/92cfdef7-8f2c-433e-ba62-49fa7a243974";
		$total_records = 0;
		$total_inserted = 0;
		$current_offset = $offset;
		$batch_data = array();
		$page_limit = 200; // Request 200 records per page
		$start_time = microtime(true);
		
		// Initialize cURL handle once
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		
		// Check if running from CLI
		$is_cli = (php_sapi_name() === 'cli');
		$line_break = $is_cli ? "\r" : "<br>";
		
		echo $is_cli ? "\n" : "";
		echo "Fetching iHRIS data...\n";
		if (!$is_cli) echo "<pre>";
		
		do {
			// Fetch data using offset and page_limit parameters
			$url = $base_url . "?offset=" . $current_offset . "&page_limit=" . $page_limit;
			curl_setopt($ch, CURLOPT_URL, $url);
			
			$result = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if ($http_code !== 200 || $result === false) {
				$error = curl_error($ch);
				log_message('error', "Failed to fetch offset $current_offset: HTTP $http_code - $error");
				// Wait before retrying
				sleep(2);
				continue;
			}
			
			$response = json_decode($result, true);
			
			if (!isset($response['status']) || $response['status'] !== 'SUCCESS') {
				log_message('error', "API returned error for offset $current_offset: " . json_encode($response));
				break;
			}
			
			// Get total records info on first request
			if ($total_records == 0 && isset($response['pagination'])) {
				$total_records = $response['pagination']['total_records'];
				echo "Total records: " . number_format($total_records) . " | Page size: $page_limit\n";
				if (!$is_cli) echo "<br>";
			}
			
			// Process data from current request
			$records_fetched = 0;
			if (isset($response['data']) && is_array($response['data'])) {
				$records_fetched = count($response['data']);
				foreach ($response['data'] as $record) {
					// Map API fields to database columns
					$batch_data[] = array(
						'ihris_pid' => isset($record['ihris_pid']) ? $record['ihris_pid'] : null,
						'district_id' => isset($record['district_id']) ? $record['district_id'] : null,
						'district' => isset($record['district']) ? $record['district'] : null,
						'nin' => isset($record['nin']) ? $record['nin'] : null,
						'ipps' => isset($record['ipps']) ? $record['ipps'] : null,
						'facility_type_id' => isset($record['facility_type_id']) ? $record['facility_type_id'] : null,
						'facility_id' => isset($record['facility_id']) ? $record['facility_id'] : null,
						'facility' => isset($record['facility']) ? $record['facility'] : null,
						'department' => isset($record['department']) ? $record['department'] : null,
						'job_id' => isset($record['job_id']) ? $record['job_id'] : null,
						'job' => isset($record['job']) ? $record['job'] : null,
						'surname' => isset($record['surname']) ? $record['surname'] : null,
						'firstname' => isset($record['firstname']) ? $record['firstname'] : null,
						'othername' => isset($record['othername']) ? $record['othername'] : null,
						'mobile' => isset($record['mobile']) ? $record['mobile'] : null,
						'telephone' => isset($record['telephone']) ? $record['telephone'] : null,
						'institution_type' => isset($record['institutiontype_name']) ? $record['institutiontype_name'] : null,
						'last_gen' => isset($record['last_update']) ? $record['last_update'] : null
					);
					
					// Insert in batches to avoid memory issues
					if (count($batch_data) >= $batch_size) {
						$this->db->insert_batch('ihrisdata', $batch_data);
						$total_inserted += count($batch_data);
						$batch_data = array();
					}
				}
			}
			
			// Check if there's more data to fetch
			$has_next = isset($response['pagination']['has_next_page']) ? $response['pagination']['has_next_page'] : false;
			
			// Update progress
			$processed = $current_offset + $records_fetched;
			$progress_bar = $this->_draw_progress_bar($processed, $total_records);
			
			// Calculate ETA
			$elapsed = microtime(true) - $start_time;
			$rate = $processed > 0 ? $processed / $elapsed : 0;
			$remaining = $total_records - $processed;
			$eta_seconds = $rate > 0 ? round($remaining / $rate) : 0;
			$eta_formatted = $eta_seconds > 0 ? sprintf("%dm %ds", floor($eta_seconds / 60), $eta_seconds % 60) : "calculating...";
			
			// Display progress (single line update)
			if ($is_cli) {
				printf("\rProgress: %s | Inserted: %s | ETA: %s   ", 
					$progress_bar, 
					number_format($total_inserted),
					$eta_formatted
				);
			} else {
				echo "<div style='font-family: monospace;'>Progress: $progress_bar | Inserted: " . number_format($total_inserted) . " | ETA: $eta_formatted</div>";
				flush();
			}
			
			// Stop if no records were fetched (end of data)
			if ($records_fetched == 0) {
				break;
			}
			
			// Update offset for next request
			$current_offset += $page_limit;
			
			// Small delay to avoid overwhelming the API server
			usleep(100000); // 0.1 second delay between requests
			
		} while ($has_next && ($total_records == 0 || $current_offset < $total_records));
		
		// Insert remaining batch data
		if (!empty($batch_data)) {
			$this->db->insert_batch('ihrisdata', $batch_data);
			$total_inserted += count($batch_data);
		}
		
		curl_close($ch);
		
		// Final summary
		$elapsed_total = round(microtime(true) - $start_time, 2);
		$final_progress = $this->_draw_progress_bar($total_inserted, $total_records);
		
		if ($is_cli) {
			echo "\n\n";
			echo "═══════════════════════════════════════════════════════════\n";
			echo "  Status: COMPLETED\n";
			echo "  Records Inserted: " . number_format($total_inserted) . "\n";
			echo "  Progress: $final_progress\n";
			echo "  Time Elapsed: " . round($elapsed_total, 2) . "s\n";
			echo "═══════════════════════════════════════════════════════════\n";
		} else {
			echo "<br><div style='font-family: monospace; padding: 10px; background: #f0f0f0; border: 1px solid #ccc;'>";
			echo "<strong>Status:</strong> COMPLETED<br>";
			echo "<strong>Records Inserted:</strong> " . number_format($total_inserted) . "<br>";
			echo "<strong>Progress:</strong> $final_progress<br>";
			echo "<strong>Time Elapsed:</strong> " . round($elapsed_total, 2) . "s";
			echo "</div></pre>";
		}
		
		return $total_inserted;
	}


	













	


}
