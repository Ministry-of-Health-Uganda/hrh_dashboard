<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Newaudit – Pipeline to build national_jobs from staff + structure.
 *
 * SOURCES:
 * - staff: one row per person per job; (person_id, job_id) unique; has approved (positions), filled (count);
 *   facility level for structure match: for "Specialised National Facility", "National Referral Hospital",
 *   "Regional Referral Hospital", "Ministry" use facility_name; else use facility_type_name.
 * - structure: establishment per facility level (facility_facility_level) and job; approved = positions.
 *
 * TARGET: national_jobs – one row per (facility, job): approved (structure), male/female/total (staff),
 *         vacant = max(0, approved - total), excess = max(0, total - approved).
 * - Gender: staff with no gender are treated as Male.
 * - Special characters in facility/job names are normalized so search and filters on the audit front end
 *   work reliably (exact match on facility_name, HTML option values, LIKE search). Same rules as Auditgen:
 *   remove '.', remove ')', replace '(' with '-', remove single quote.
 */
class Newaudit extends MX_Controller {

	public function __Construct(){
		// parent if needed
	}

	/**
	 * SQL expression to clean string columns for national_jobs (same as Auditgen template_structure_approved).
	 * REPLACE(REPLACE(REPLACE(expr, ')', ''), '(', '-'), "'", '') – clean dataset for search/filters.
	 */
	private static function _norm_sql($column_expr) {
		return "REPLACE(REPLACE(REPLACE($column_expr, ')', ''), '(', '-'), \"'\", '')";
	}

	/**
	 * Main entry: run the full pipeline to populate national_jobs from staff and structure.
	 */
	public function index(){
		$this->run_pipeline();
	}

	/**
	 * Run the full pipeline: normalize staff → build approved + filled → merge into national_jobs.
	 */
	public function run_pipeline(){
		ini_set('memory_limit', '2G');
		ini_set('max_execution_time', 0);
		$is_cli = (php_sapi_name() === 'cli');
		$lb = $is_cli ? "\n" : "<br>\n";

		echo $is_cli ? "\n" : "";
		if (!$is_cli) echo "<pre>";
		echo "National Jobs Pipeline: starting...$lb";
		flush();

		try {
			// Step 1: Normalize staff (gender default Male; facility/job name special chars for search/filter safety)
			$this->_step1_normalize_staff();
			echo "Step 1: Staff normalized (gender default Male; facility/job names cleaned for front-end).$lb";
			flush();

			// Step 2 & 3 & 4: Build approved + filled and merge into national_jobs in one INSERT
			$this->db->query("TRUNCATE TABLE national_jobs");
			$inserted = $this->_step2_merge_into_national_jobs();
			echo "Step 2–4: national_jobs populated: " . number_format($inserted) . " rows.$lb";

			echo "Pipeline COMPLETED.$lb";
		} catch (Exception $e) {
			echo "Pipeline ERROR: " . $e->getMessage() . $lb;
			echo $e->getFile() . ":" . $e->getLine() . $lb;
			if (!$is_cli) echo "</pre>";
			throw $e;
		}
		if (!$is_cli) echo "</pre>";
	}

	/**
	 * Step 1: Set empty/null gender to 'Male'; normalize facility_name (same as Auditgen cache_filled).
	 * REPLACE(REPLACE(REPLACE(REPLACE(facility_name, '.', ''), ')', ''), '(', '-'), "'", '') for clean dataset.
	 */
	private function _step1_normalize_staff(){
		$this->db->query("UPDATE staff SET gender = 'Male' WHERE COALESCE(TRIM(gender), '') = ''");
		$this->db->query("UPDATE staff SET facility_name = REPLACE(REPLACE(REPLACE(REPLACE(facility_name, '.', ''), ')', ''), '(', '-'), \"'\", '')");
	}

	/**
	 * Facility types that use facility_name (not facility_type_name) to match structure.facility_facility_level.
	 */
	private static function _national_level_types(){
		return array(
			'Specialised National Facility',
			'National Referral Hospital',
			'Regional Referral Hospital',
			'Ministry'
		);
	}

	/**
	 * Build approved rows: national-level (LIKE facility_name%) + district-level (exact facility_type_name).
	 * National: structure.facility_facility_level is like 'GULU Regional Referral Hospital - Regional Referral Hospital';
	 * staff.facility_name is 'GULU Regional Referral Hospital' – match with LIKE facility_name% (same as cache_structure).
	 * District: exact match on facility_type_name (HCII, HCIII, General Hospital, etc.).
	 */
	private function _sql_approved_base(){
		$national_types = self::_national_level_types();
		$national_list = implode(',', array_map(function($t) { return $this->db->escape($t); }, $national_types));
		$district_list = "'HCII','HCIII','HCIV','General Hospital','DHOs Office','Town Council','Municipal Health Office','Blood Bank Main Office','Blood Bank Regional Office','Medical Bureau Main Office','City Health Office'";

		$norm_struct = self::_norm_sql('s.facility_facility_level');
		$norm_ftype = self::_norm_sql('st.facility_type_name');

		$national_approved = "
		(SELECT
			st.facility_id,
			st.dhis_facility_id,
			st.facility_name,
			st.facility_type_name,
			st.region_name,
			st.institution_type,
			st.district_name,
			s.job_id,
			s.dhis_job_id,
			s.job AS job_name,
			s.job_classification,
			s.job_category,
			s.cadre AS cadre_name,
			s.salary_grade AS salary_scale,
			s.approved
		FROM (
			SELECT DISTINCT facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name
			FROM staff
			WHERE facility_type_name IN ($national_list)
		) st
		INNER JOIN structure s ON (s.facility_facility_level LIKE CONCAT(st.facility_name, '%') OR s.facility_facility_level = st.facility_name))
		";

		$district_approved = "
		(SELECT
			st.facility_id,
			st.dhis_facility_id,
			st.facility_name,
			st.facility_type_name,
			st.region_name,
			st.institution_type,
			st.district_name,
			s.job_id,
			s.dhis_job_id,
			s.job AS job_name,
			s.job_classification,
			s.job_category,
			s.cadre AS cadre_name,
			s.salary_grade AS salary_scale,
			s.approved
		FROM (
			SELECT DISTINCT facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name,
				$norm_ftype AS level
			FROM staff
			WHERE facility_type_name IN ($district_list)
		) st
		INNER JOIN structure s ON $norm_struct = st.level)
		";

		return "($national_approved UNION $district_approved)";
	}

	/**
	 * Build filled rows: staff grouped by facility_id, job_id; male = Male or blank, female = Female; total = count.
	 * Returns SQL for a derived table `f` with: facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name, job_id, dhis_job_id, job_name, job_classification, job_category, cadre_name, salary_scale, male, female, total.
	 */
	private function _sql_filled_base(){
		return "
		(SELECT
			facility_id,
			dhis_facility_id,
			facility_name,
			facility_type_name,
			region_name,
			institution_type,
			district_name,
			job_id,
			dhis_job_id,
			job_name,
			job_classification,
			job_category,
			cadre_name,
			salary_scale,
			SUM(CASE WHEN COALESCE(TRIM(gender), '') IN ('', 'Male') THEN 1 ELSE 0 END) AS male,
			SUM(CASE WHEN TRIM(gender) = 'Female' THEN 1 ELSE 0 END) AS female,
			COUNT(*) AS total
		FROM staff
		GROUP BY facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, district_name, job_id, dhis_job_id, job_name, job_classification, job_category, cadre_name, salary_scale)
		";
	}

	/**
	 * Step 2–4: Merge approved (structure) and filled (staff) into national_jobs.
	 * FULL OUTER JOIN style: (approved LEFT JOIN filled) UNION (filled LEFT JOIN approved), then COALESCE and vacant/excess.
	 */
	private function _step2_merge_into_national_jobs(){
		$approved_sql = $this->_sql_approved_base();
		$filled_sql   = $this->_sql_filled_base();

		$union = "
		(SELECT a.facility_id AS app_facility_id, a.dhis_facility_id AS app_dhis_facility_id, a.facility_name AS app_facility_name, a.facility_type_name AS app_facility_type_name, a.region_name AS app_region_name, a.institution_type AS app_institution_type, a.district_name AS app_district_name, a.job_id AS app_job_id, a.dhis_job_id AS app_dhis_job_id, a.job_name AS app_job_name, a.job_classification AS app_job_classification, a.job_category AS app_job_category, a.cadre_name AS app_cadre_name, a.salary_scale AS app_salary_scale, a.approved AS app_approved, f.facility_id AS fill_facility_id, f.dhis_facility_id AS fill_dhis_facility_id, f.facility_name AS fill_facility_name, f.facility_type_name AS fill_facility_type_name, f.region_name AS fill_region_name, f.institution_type AS fill_institution_type, f.district_name AS fill_district_name, f.job_id AS fill_job_id, f.dhis_job_id AS fill_dhis_job_id, f.job_name AS fill_job_name, f.job_classification AS fill_job_classification, f.job_category AS fill_job_category, f.cadre_name AS fill_cadre_name, f.salary_scale AS fill_salary_scale, f.male AS fill_male, f.female AS fill_female, f.total AS fill_total FROM ($approved_sql) a LEFT JOIN ($filled_sql) f ON a.facility_id = f.facility_id AND a.job_id = f.job_id)
		UNION
		(SELECT a.facility_id AS app_facility_id, a.dhis_facility_id AS app_dhis_facility_id, a.facility_name AS app_facility_name, a.facility_type_name AS app_facility_type_name, a.region_name AS app_region_name, a.institution_type AS app_institution_type, a.district_name AS app_district_name, a.job_id AS app_job_id, a.dhis_job_id AS app_dhis_job_id, a.job_name AS app_job_name, a.job_classification AS app_job_classification, a.job_category AS app_job_category, a.cadre_name AS app_cadre_name, a.salary_scale AS app_salary_scale, a.approved AS app_approved, f.facility_id AS fill_facility_id, f.dhis_facility_id AS fill_dhis_facility_id, f.facility_name AS fill_facility_name, f.facility_type_name AS fill_facility_type_name, f.region_name AS fill_region_name, f.institution_type AS fill_institution_type, f.district_name AS fill_district_name, f.job_id AS fill_job_id, f.dhis_job_id AS fill_dhis_job_id, f.job_name AS fill_job_name, f.job_classification AS fill_job_classification, f.job_category AS fill_job_category, f.cadre_name AS fill_cadre_name, f.salary_scale AS fill_salary_scale, f.male AS fill_male, f.female AS fill_female, f.total AS fill_total FROM ($approved_sql) a RIGHT JOIN ($filled_sql) f ON a.facility_id = f.facility_id AND a.job_id = f.job_id)
		";

		$inst = self::_norm_sql('COALESCE(u.fill_institution_type, u.app_institution_type)');
		$ownership_sql = "CASE
			WHEN $inst IN ('National Referral Hospital, Central Government','Specialised Facility, Central Government','Ministry, Central Government','Regional Referral Hospital, Central Government','District, Local Government (LG)','UBTS, Central Government','City, Local Government (LG)','Municipality, Local Government (LG)','Ministry','City, Local Government -LG','Municipality, Local Government -LG','District, Local Government -LG') THEN 'Public'
			WHEN $inst IN ('UCBHCA, Private for Profit (PFPs)','UCBHCA', 'Private for Profit -PFPs') THEN 'Private'
			WHEN $inst IN ('UCMB, Private not for Profit (PNFPs)', 'UPMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit (PNFPs)','UOMB, Private not for Profit (PNFPs)','UMMB, Private not for Profit -PNFPs','UOMB, Private not for Profit -PNFPs','UCMB, Private not for Profit -PNFPs','UPMB, Private not for Profit -PNFPs','Civil Society Organisations -CSO, Private not for Profit -PNFPs','Civil Society Organisations (CSO)','Civil Society Organisations (CSO), Private not for Profit (PNFPs)') THEN 'PNFP'
			WHEN $inst IN ('Uganda Prison Services, Security Forces') THEN 'Prison'
			WHEN $inst IN ('Uganda Police, Security Forces') THEN 'Police'
			WHEN $inst IN ('Uganda Peoples Defence Force (UPDF), Security Forces','Uganda Peoples Defence Force -UPDF, Security Forces') THEN 'UPDF'
			ELSE 'Public'
		END";

		$insert_sql = "
		INSERT INTO national_jobs (month, year, facility_id, dhis_facility_id, facility_name, facility_type_name, region_name, institution_type, ownership, district_name, job_id, dhis_job_id, job_name, job_category, job_classification, cadre_name, salary_scale, approved, male, female, total, excess, vacant)
		SELECT
			DATE_FORMAT(CURDATE(), '%M'),
			YEAR(CURDATE()),
			COALESCE(u.fill_facility_id, u.app_facility_id),
			COALESCE(u.fill_dhis_facility_id, u.app_dhis_facility_id),
			" . self::_norm_sql('IF(u.fill_facility_id IS NOT NULL AND u.app_facility_id IS NOT NULL, u.app_facility_name, COALESCE(u.fill_facility_name, u.app_facility_name))') . ",
			" . self::_norm_sql('COALESCE(u.fill_facility_type_name, u.app_facility_type_name)') . ",
			" . self::_norm_sql('COALESCE(u.fill_region_name, u.app_region_name)') . ",
			" . $inst . ",
			" . $ownership_sql . ",
			" . self::_norm_sql('COALESCE(u.fill_district_name, u.app_district_name)') . ",
			COALESCE(u.fill_job_id, u.app_job_id),
			COALESCE(u.fill_dhis_job_id, u.app_dhis_job_id),
			" . self::_norm_sql('IF(u.fill_facility_id IS NOT NULL AND u.app_facility_id IS NOT NULL, u.app_job_name, COALESCE(u.fill_job_name, u.app_job_name))') . ",
			" . self::_norm_sql('COALESCE(u.fill_job_classification, u.app_job_classification)') . ",
			" . self::_norm_sql('COALESCE(u.fill_job_category, u.app_job_category)') . ",
			" . self::_norm_sql('COALESCE(u.fill_cadre_name, u.app_cadre_name)') . ",
			COALESCE(u.fill_salary_scale, u.app_salary_scale),
			CAST(COALESCE(u.app_approved, 0) AS UNSIGNED),
			CAST(COALESCE(u.fill_male, 0) AS UNSIGNED),
			CAST(COALESCE(u.fill_female, 0) AS UNSIGNED),
			CAST(COALESCE(u.fill_total, 0) AS UNSIGNED),
			GREATEST(0, CAST(COALESCE(u.fill_total, 0) AS SIGNED) - CAST(COALESCE(u.app_approved, 0) AS SIGNED)),
			GREATEST(0, CAST(COALESCE(u.app_approved, 0) AS SIGNED) - CAST(COALESCE(u.fill_total, 0) AS SIGNED))
		FROM ($union) u
		";
		$result = $this->db->query($insert_sql);
		if ($result === false) {
			$err = $this->db->error();
			throw new RuntimeException("Merge into national_jobs failed: " . (isset($err['message']) ? $err['message'] : 'Unknown DB error'));
		}
		return (int) $this->db->affected_rows();
	}
}
