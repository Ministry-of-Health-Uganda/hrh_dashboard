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
	// Cache filled: normalize facility names, set gender, compute filled counts, render structure_filled.
	public function cache_filled(){
		$start_time = microtime(true);
		$total_steps = 5;
		$is_cli = (php_sapi_name() === 'cli');

		if (!$is_cli) echo "<pre>";
		echo $is_cli ? "\n" : "";
		echo "Cache Filled: starting...\n";
		flush();
		
		// Step 1/5: Normalize facility_name for all staff in one UPDATE (no per-facility loop)
		$this->db->query("UPDATE staff SET facility_name = REPLACE(REPLACE(REPLACE(REPLACE(facility_name, '.', ''), ')', ''), '(', '-'), \"'\", '')");
		$step1_rows = $this->db->affected_rows();
		$this->_cache_filled_progress(1, $total_steps, "Facility names normalized", $step1_rows, $start_time, $is_cli);

		// Step 2/5: Set empty gender to 'Male'
		$this->db->query("UPDATE staff SET gender = 'Male' WHERE gender = ''");
		$step2_rows = $this->db->affected_rows();
		$this->_cache_filled_progress(2, $total_steps, "Gender default set", $step2_rows, $start_time, $is_cli);

		// Step 3/5: Truncate structure_filled
		$this->truncate_filled();
		$this->_cache_filled_progress(3, $total_steps, "structure_filled truncated", 0, $start_time, $is_cli);

		// Step 4/5: Set filled = count per (facility_id, job_id) in one UPDATE with JOIN (no per-group loop)
		$this->db->query("
			UPDATE staff s
			INNER JOIN (
				SELECT facility_id, job_id, COUNT(person_id) AS cnt
				FROM staff
				GROUP BY facility_id, job_id
			) c ON s.facility_id = c.facility_id AND s.job_id = c.job_id
			SET s.filled = c.cnt
			WHERE s.approved = '0'
		");
		$step4_rows = $this->db->affected_rows();
		$this->_cache_filled_progress(4, $total_steps, "Filled counts updated", $step4_rows, $start_time, $is_cli);

		// Step 5/5: Render into structure_filled
		$this->render_filled();
		$step5_rows = $this->db->affected_rows();
		$this->_cache_filled_progress(5, $total_steps, "structure_filled rendered", $step5_rows, $start_time, $is_cli);

		$elapsed = round(microtime(true) - $start_time, 2);
		if ($is_cli) {
			echo "\n═══════════════════════════════════════════════════════════\n";
			echo "  Cache Filled: COMPLETED in {$elapsed}s\n";
			echo "═══════════════════════════════════════════════════════════\n";
		} else {
			echo "<br><div style='font-family: monospace; padding: 10px; background: #f0f0f0; border: 1px solid #ccc;'>";
			echo "<strong>Cache Filled:</strong> COMPLETED in {$elapsed}s";
			echo "</div></pre>";
		}
	}

	private function _cache_filled_progress($current, $total, $label, $rows, $start_time, $is_cli) {
		$bar = $this->_draw_progress_bar($current, $total);
		$elapsed = round(microtime(true) - $start_time, 2);
		$rows_msg = $rows > 0 ? " (" . number_format($rows) . " rows)" : "";
		if ($is_cli) {
			echo "\r" . $bar . " Step {$current}/{$total}: {$label}{$rows_msg} | {$elapsed}s   ";
		} else {
			echo "<div style='font-family: monospace;'>" . $bar . " Step {$current}/{$total}: {$label}{$rows_msg} | {$elapsed}s</div>";
			flush();
		}
	}


	//helper functions
	public function render_filled(){
		$data = $this->db->query("INSERT into structure_filled SELECT facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_name,job_classification,job_category,cadre_name,salary_scale,approved,              
		(case when gender = 'Male' then filled else 0 end) male,
		(case when gender = 'Female' then filled else 0 end) female, ((case when gender = 'Male' then filled else 0 end)+
		(case when gender = 'Female' then filled else 0 end)) as total,'0','0','0'
		FROM   staff  GROUP BY facility_id,dhis_facility_id,facility_name,facility_type_name,region_name,institution_type,district_name,job_id,dhis_job_id,job_classification,job_category,cadre_name,salary_scale,approved");
		$is_cli = (php_sapi_name() === 'cli');
		if (!$is_cli) {
			echo "<br><p style=\"color:green;\">" . $this->db->affected_rows() . "</p>";
		}
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
		ini_set('memory_limit', '4G');
		ini_set('max_execution_time', 0);
		$start_time = microtime(true);
		$total_steps = 4;
		$is_cli = (php_sapi_name() === 'cli');

		if (!$is_cli) echo "<pre>";
		echo $is_cli ? "\n" : "";
		echo "Cache Structure: starting...\n";
		flush();

		try {
			// Step 1/4: Delete unapproved structure rows
			$this->db->query("DELETE FROM structure WHERE approved = '0'");
			$this->_cache_filled_progress(1, $total_steps, "Structure cleaned (unapproved deleted)", $this->db->affected_rows(), $start_time, $is_cli);

			// Step 2/4: Truncate structure_approved
			$trunc = $this->db->query("TRUNCATE TABLE structure_approved");
			if ($trunc === false) {
				$err = $this->db->error();
				throw new RuntimeException("TRUNCATE structure_approved failed: " . (isset($err['message']) ? $err['message'] : 'Unknown DB error'));
			}
			$this->_cache_filled_progress(2, $total_steps, "structure_approved truncated", 0, $start_time, $is_cli);

			// Step 3/4: Template 1 – National/Regional facilities (uses view total_facilities_temp_districts)
			$rows1 = $this->template_structure_approved1();
			$this->_cache_filled_progress(3, $total_steps, "Template Structure 1 (National/Regional)", $rows1, $start_time, $is_cli);

			// Step 4/4: Template 2 – District-level facility types
			$rows2 = $this->template_structure_approved2();
			$this->_cache_filled_progress(4, $total_steps, "Template Structure 2 (District-level)", $rows2, $start_time, $is_cli);

			$elapsed = round(microtime(true) - $start_time, 2);
			if ($is_cli) {
				echo "\n═══════════════════════════════════════════════════════════\n";
				echo "  Cache Structure: COMPLETED in {$elapsed}s\n";
				echo "  Total rows: " . number_format($rows1 + $rows2) . "\n";
				echo "═══════════════════════════════════════════════════════════\n";
			} else {
				echo "<br><div style='font-family: monospace; padding: 10px; background: #f0f0f0; border: 1px solid #ccc;'>";
				echo "<strong>Cache Structure:</strong> COMPLETED in {$elapsed}s | Total rows: " . number_format($rows1 + $rows2);
				echo "</div></pre>";
			}
		} catch (Exception $e) {
			$msg = "Cache Structure ERROR: " . $e->getMessage();
			$detail = $e->getFile() . ":" . $e->getLine();
			if ($is_cli) {
				echo "\n\n!!! " . $msg . "\n";
				echo "    " . $detail . "\n";
				if (method_exists($e, 'getTraceAsString')) {
					echo $e->getTraceAsString() . "\n";
				}
			} else {
				echo "<br><div style='font-family: monospace; padding: 10px; background: #ffebee; border: 1px solid #c62828; color: #b71c1c;'>";
				echo "<strong>ERROR</strong><br>" . htmlspecialchars($msg) . "<br><small>" . htmlspecialchars($detail) . "</small>";
				echo "</div></pre>";
			}
			throw $e;
		}
	}

	/**
	 * Template 1: National/Regional facilities – single INSERT...SELECT (no per-row queries).
	 * @return int Rows inserted
	 */
	public function template_structure_approved1(){
		$sql = "INSERT INTO structure_approved (
			facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name,
			job_id, dhis_job_id, job_name, job_classification, job_category, cadre_name, salary_scale, approved,
			male, female, total, excess, vacant, pec_filled
		)
		SELECT
			t.facility_id,
			t.dhis_facility_id,
			REPLACE(REPLACE(REPLACE(t.facility_name, ')', ''), '(', '-'), \"'\", ''),
			REPLACE(REPLACE(REPLACE(t.facility_type_name, ')', ''), '(', '-'), \"'\", ''),
			t.region_name,
			t.institution_type,
			t.district_name,
			s.job_id,
			s.dhis_job_id,
			s.job,
			s.job_classification,
			s.job_category,
			s.cadre,
			s.salary_grade,
			s.approved,
			'0', '0', '0', '0', '0', '0'
		FROM total_facilities_temp_districts t
		INNER JOIN structure s ON s.facility_facility_level = t.facility_name
		WHERE t.facility_type_name IN ('Regional Referral Hospital','Ministry','National Referral Hospital','Specialised National Facility')";
		$result = $this->db->query($sql);
		if ($result === false) {
			$err = $this->db->error();
			throw new RuntimeException("Template 1 (total_facilities_temp_districts) failed: " . (isset($err['message']) ? $err['message'] : 'Unknown DB error'));
		}
		return (int) $this->db->affected_rows();
	}

	/**
	 * Template 2: District-level facility types – single INSERT...SELECT (no per-row queries).
	 * @return int Rows inserted
	 */
	public function template_structure_approved2(){
		$sql = "INSERT INTO structure_approved (
			facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name,
			job_id, dhis_job_id, job_name, job_classification, job_category, cadre_name, salary_scale, approved,
			male, female, total, excess, vacant, pec_filled
		)
		SELECT
			t.facility_id,
			t.dhis_facility_id,
			REPLACE(REPLACE(REPLACE(t.facility_name, ')', ''), '(', '-'), \"'\", ''),
			REPLACE(REPLACE(REPLACE(t.facility_type_name, ')', ''), '(', '-'), \"'\", ''),
			t.region_name,
			t.institution_type,
			t.district_name,
			s.job_id,
			s.dhis_job_id,
			s.job,
			s.job_classification,
			s.job_category,
			s.cadre,
			s.salary_grade,
			s.approved,
			'0', '0', '0', '0', '0', '0'
		FROM (
			SELECT DISTINCT facility_name, facility_type_name, region_name, facility_id, dhis_facility_id, institution_type, district_name
			FROM staff
			WHERE facility_type_name IN ('HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office','Blood Bank Main Office','Blood Bank Regional Office','Medical Bureau Main Office','City Health Office')
		) t
		INNER JOIN structure s ON s.facility_facility_level = t.facility_type_name";
		$result = $this->db->query($sql);
		if ($result === false) {
			$err = $this->db->error();
			throw new RuntimeException("Template 2 (District-level) failed: " . (isset($err['message']) ? $err['message'] : 'Unknown DB error'));
		}
		return (int) $this->db->affected_rows();
	}

	///////////////////////////////////////////////
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////

	//Cache National Jobs

    public function cache_nationaljobs($batch_size = 500){
		// Allow plenty of memory and time for large national jobs cache (server has 32GB RAM)
		ini_set('memory_limit', '4G');
		ini_set('max_execution_time', 0);

		$start_time = microtime(true);
		$is_cli = (php_sapi_name() === 'cli');

		$out = function ($msg, $br = "\n") use ($is_cli) {
			echo $is_cli ? $msg . $br : $msg . ($br === "\n" ? "<br>\n" : "");
			if (!$is_cli) ob_flush();
			flush();
		};

		echo $is_cli ? "\n" : "";
		if (!$is_cli) echo "<pre>";
		$out("National Jobs Cache: starting...");
		flush();

		try {
			$out("Truncating national_jobs table...");
			$truncate = $this->db->query("TRUNCATE TABLE national_jobs");
			if ($truncate === false) {
				$err = $this->db->error();
				throw new RuntimeException("TRUNCATE failed: " . (isset($err['message']) ? $err['message'] : 'Unknown DB error'));
			}
			$out("Truncate done. Building and running INSERT...SELECT (this may take a while)...");

			// Single INSERT...SELECT: all logic in SQL, no PHP loop, no data dropped
			$union_sql = "(SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f RIGHT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id)) UNION (SELECT a.facility_id AS app_facility_id,a.dhis_facility_id AS app_dhis_facility_id,a.facility_name AS app_facility_name,a.facility_type_name AS app_facility_type_name,a.region_name AS app_region_name,a.institution_type AS app_institution_type,a.district_name AS app_district_name,a.job_id AS app_job_id,a.dhis_job_id AS app_dhis_job_id,a.job_name AS app_job_name,a.job_category AS app_job_category,a.job_classification AS app_job_classification,a.cadre_name AS app_cadre_name,a.salary_scale AS app_salary_scale,a.approved AS app_approved,a.male AS app_male,a.female AS app_female,a.total AS app_total,f.facility_id AS fill_facility_id,f.dhis_facility_id AS fill_dhis_facility_id,f.facility_name AS fill_facility_name,f.facility_type_name AS fill_facility_type_name,f.region_name AS fill_region_name,f.institution_type AS fill_institution_type,f.district_name AS fill_district_name,f.job_id AS fill_job_id,f.dhis_job_id AS fill_dhis_job_id,f.job_name AS fill_job_name,f.job_category AS fill_job_category,f.job_classification AS fill_job_classification,f.cadre_name AS fill_cadre_name,f.salary_scale AS fill_salary_scale,f.approved AS fill_approved,f.male AS fill_male,f.female AS fill_female,f.total AS fill_total FROM structure_filled f LEFT JOIN structure_approved a ON( a.job_id= f.job_id AND a.facility_id=f.facility_id))";

			$insert_sql = "INSERT INTO national_jobs (month, year, facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name, job_id, dhis_job_id, job_name, job_classification, job_category, cadre_name, salary_scale, approved, male, female, total)
SELECT
  DATE_FORMAT(CURDATE(), '%M'),
  YEAR(CURDATE()),
  COALESCE(u.fill_facility_id, u.app_facility_id),
  COALESCE(u.fill_dhis_facility_id, u.app_dhis_facility_id),
  IF(u.fill_facility_id IS NOT NULL AND u.app_facility_id IS NOT NULL, u.app_facility_name, COALESCE(u.fill_facility_name, u.app_facility_name)),
  COALESCE(u.fill_facility_type_name, u.app_facility_type_name),
  COALESCE(u.fill_region_name, u.app_region_name),
  COALESCE(u.fill_institution_type, u.app_institution_type),
  COALESCE(u.fill_district_name, u.app_district_name),
  COALESCE(u.fill_job_id, u.app_job_id),
  COALESCE(u.fill_dhis_job_id, u.app_dhis_job_id),
  COALESCE(u.fill_job_name, u.app_job_name),
  COALESCE(u.fill_job_classification, u.app_job_classification),
  COALESCE(u.fill_job_category, u.app_job_category),
  COALESCE(u.fill_cadre_name, u.app_cadre_name),
  COALESCE(u.fill_salary_scale, u.app_salary_scale),
  CAST(IF(u.fill_facility_id IS NOT NULL AND u.app_facility_id IS NOT NULL, u.app_approved, COALESCE(u.fill_approved, u.app_approved)) AS UNSIGNED),
  CAST(COALESCE(u.fill_male, u.app_male) AS UNSIGNED),
  CAST(COALESCE(u.fill_female, u.app_female) AS UNSIGNED),
  CAST(COALESCE(u.fill_total, u.app_total) AS UNSIGNED)
FROM (" . $union_sql . ") u";

			$insert_result = $this->db->query($insert_sql);
			if ($insert_result === false) {
				$err = $this->db->error();
				throw new RuntimeException("INSERT...SELECT failed: " . (isset($err['message']) ? $err['message'] : 'Unknown DB error'));
			}
			$total_inserted = (int) $this->db->affected_rows();
			$elapsed_total = round(microtime(true) - $start_time, 2);

			if ($is_cli) {
				echo "\n";
				echo "═══════════════════════════════════════════════════════════\n";
				echo "  Status: COMPLETED\n";
				echo "  Records Inserted: " . number_format($total_inserted) . "\n";
				echo "  Time Elapsed: " . $elapsed_total . "s\n";
				echo "═══════════════════════════════════════════════════════════\n";
			} else {
				echo "<br><div style='font-family: monospace; padding: 10px; background: #f0f0f0; border: 1px solid #ccc;'>";
				echo "<strong>Status:</strong> COMPLETED<br>";
				echo "<strong>Records Inserted:</strong> " . number_format($total_inserted) . "<br>";
				echo "<strong>Time Elapsed:</strong> " . $elapsed_total . "s";
				echo "</div></pre>";
			}
		} catch (Exception $e) {
			$msg = "National Jobs Cache ERROR: " . $e->getMessage();
			$detail = $e->getFile() . ":" . $e->getLine();
			if ($is_cli) {
				echo "\n\n!!! " . $msg . "\n";
				echo "    " . $detail . "\n";
				if (method_exists($e, 'getTraceAsString')) {
					echo $e->getTraceAsString() . "\n";
				}
			} else {
				echo "<br><div style='font-family: monospace; padding: 10px; background: #ffebee; border: 1px solid #c62828; color: #b71c1c;'>";
				echo "<strong>ERROR</strong><br>" . htmlspecialchars($msg) . "<br><small>" . htmlspecialchars($detail) . "</small>";
				echo "</div></pre>";
			}
			throw $e;
		}
	}

	public function cache_ownership(){

		ini_set('memory_limit', '4G');
		ini_set('max_execution_time', 0);
		$sql = "UPDATE national_jobs SET ownership = CASE
			WHEN institution_type IN (
				'National Referral Hospital, Central Government','Specialised Facility, Central Government','Ministry, Central Government','Regional Referral Hospital, Central Government','District, Local Government (LG)','UBTS, Central Government','City, Local Government (LG)','Municipality, Local Government (LG)','Ministry','City, Local Government -LG','Municipality, Local Government -LG','District, Local Government -LG'
			) THEN 'Public'
			WHEN institution_type IN (
				'UCBHCA, Private for Profit (PFPs)','UCBHCA', 'Private for Profit -PFPs'
			) THEN 'Private'
			WHEN institution_type IN (
				'UCMB, Private not for Profit (PNFPs)','UPMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit (PNFPs)','UOMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit -PNFPs','UOMB, Private not for Profit -PNFPs','UCMB, Private not for Profit -PNFPs','UPMB, Private not for Profit -PNFPs','Civil Society Organisations -CSO, Private not for Profit -PNFPs','Civil Society Organisations (CSO)','Civil Society Organisations (CSO), Private not for Profit (PNFPs)'
			) THEN 'PNFP'
			WHEN institution_type IN ('Uganda Prison Services, Security Forces') THEN 'Prison'
			WHEN institution_type IN ('Uganda Police, Security Forces') THEN 'Police'
			WHEN institution_type IN (
				'Uganda Peoples Defence Force (UPDF), Security Forces','Uganda Peoples Defence Force -UPDF, Security Forces'
			) THEN 'UPDF'
			ELSE ownership
		END
		WHERE institution_type IN (
			'National Referral Hospital, Central Government','Specialised Facility, Central Government','Ministry, Central Government','Regional Referral Hospital, Central Government','District, Local Government (LG)','UBTS, Central Government','City, Local Government (LG)','Municipality, Local Government (LG)','Ministry','City, Local Government -LG','Municipality, Local Government -LG','District, Local Government -LG',
			'UCBHCA, Private for Profit (PFPs)','UCBHCA', 'Private for Profit -PFPs',
			'UCMB, Private not for Profit (PNFPs)','UPMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit (PNFPs)','UOMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit -PNFPs','UOMB, Private not for Profit -PNFPs','UCMB, Private not for Profit -PNFPs','UPMB, Private not for Profit -PNFPs','Civil Society Organisations -CSO, Private not for Profit -PNFPs','Civil Society Organisations (CSO)','Civil Society Organisations (CSO), Private not for Profit (PNFPs)',
			'Uganda Prison Services, Security Forces',
			'Uganda Police, Security Forces',
			'Uganda Peoples Defence Force (UPDF), Security Forces','Uganda Peoples Defence Force -UPDF, Security Forces'
		)";
		$this->db->query($sql);
		echo $this->db->affected_rows() . " updated";
	}


	public function render_audit(){
		//$this->db->query("CALL populate_ihrisdata()");
		//$this->fetch_ihrisdata();
		//$this->cache_filled();
		$this->cache_structure();
		//$this->cache_nationaljobs();
		//$this->cache_ownership();
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

	public function fetch_ihrisdata($page = 1, $batch_size = 100){
		// Clear existing data before starting
		$this->db->query("TRUNCATE TABLE ihrisdata");
		
		$base_url = "https://hris.health.go.ug/apiv1/index.php/api/ihrisdatapaginated/92cfdef7-8f2c-433e-ba62-49fa7a243974";
		$per_page = 200; // 200 records per page as per new server changes
		$total_pages = 0;
		$total_records = 0;
		$total_inserted = 0;
		$current_page = $page;
		$batch_data = array();
		$start_time = microtime(true);
		
		// Check if running from CLI
		$is_cli = (php_sapi_name() === 'cli');
		$line_break = $is_cli ? "\r" : "<br>";
		
		echo $is_cli ? "\n" : "";
		echo "Fetching iHRIS data...\n";
		if (!$is_cli) echo "<pre>";
		
		// Initialize cURL handle once
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		
		do {
			// Fetch page data with 200 records per page
			$url = $base_url . "?page=" . $current_page . "&per_page=" . $per_page;
			curl_setopt($ch, CURLOPT_URL, $url);
			
			$result = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if ($http_code !== 200 || $result === false) {
				$error = curl_error($ch);
				log_message('error', "Failed to fetch page $current_page: HTTP $http_code - $error");
				// Wait before retrying
				sleep(2);
				continue;
			}
			
			$response = json_decode($result, true);
			
			if (!isset($response['status']) || $response['status'] !== 'SUCCESS') {
				log_message('error', "API returned error for page $current_page: " . json_encode($response));
				break;
			}
			
			// Get pagination info on first page
			if ($total_pages == 0 && isset($response['pagination'])) {
				$total_pages = $response['pagination']['total_pages'];
				$total_records = $response['pagination']['total_records'];
				echo "Total records: " . number_format($total_records) . " | Total pages: $total_pages | Page size: $per_page\n";
				if (!$is_cli) echo "<br>";
				flush();
			}
			
			// Process data from current page
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
			
			// Check if there's a next page
			$has_next = isset($response['pagination']['has_next_page']) ? $response['pagination']['has_next_page'] : false;
			$current_page++;
			
			// Update progress with progress bar
			$processed = $total_inserted;
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
			
			// Small delay to avoid overwhelming the API server
			usleep(100000); // 0.1 second delay between requests
			
		} while ($has_next && ($total_pages == 0 || $current_page <= $total_pages));
		
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
