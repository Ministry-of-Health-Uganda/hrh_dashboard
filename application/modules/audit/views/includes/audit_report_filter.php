<form class="form-horizontal row searchForm" method="post" action="">

	<!-- 1. Ownership (first) -->
	<div class="form-group col-md-3">
		<label>Ownership</label>
		<select class="select form-control" name="ownership" id="filterOwnership">
			<option value="">All</option>
			<?php foreach ($filters->ownership as $owner):
				$selected = (isset($search->ownership) && $search->ownership === $owner->ownership) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($owner->ownership); ?>"><?php echo htmlspecialchars($owner->ownership); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<!-- 2. Institution Type: chained to ownership, multiple -->
	<div class="form-group col-md-3">
		<label>Institution Type</label>
		<select class="select form-control select2" name="institution[]" id="filterInstitutionType" multiple="multiple" data-placeholder="All institution types">
			<?php
			$selectedInstitutions = empty($search->institution) ? array() : (is_array($search->institution) ? $search->institution : array($search->institution));
			$instOptions = isset($institution_types_for_chain) ? $institution_types_for_chain : array();
			foreach ($instOptions as $inst):
				$itype = isset($inst->institution_type) ? $inst->institution_type : '';
				if (empty($itype)) continue;
				$selected = in_array($itype, $selectedInstitutions) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($itype); ?>"><?php echo htmlspecialchars($itype); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 3. Region: multiple -->
	<div class="form-group col-md-3">
		<label>Region</label>
		<select class="select form-control select2" name="region[]" id="filterRegion" multiple="multiple" data-placeholder="All regions">
			<?php
			$selectedRegions = empty($search->region) ? array() : (is_array($search->region) ? $search->region : array($search->region));
			$regionOptions = isset($regions_for_chain) ? $regions_for_chain : array();
			foreach ($regionOptions as $r):
				$rname = isset($r->region_name) ? $r->region_name : '';
				if (empty($rname)) continue;
				$selected = in_array($rname, $selectedRegions) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($rname); ?>"><?php echo htmlspecialchars($rname); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 4. District: multiple -->
	<div class="form-group col-md-3">
		<label>District</label>
		<select class="select form-control select2" name="district[]" id="filterDistrict" multiple="multiple" data-placeholder="All districts">
			<?php
			$selectedDistricts = empty($search->district) ? array() : (is_array($search->district) ? $search->district : array($search->district));
			$districtsOptions = isset($districts_for_chain) ? $districts_for_chain : array();
			foreach ($districtsOptions as $dist):
				$dName = isset($dist->district) ? $dist->district : (isset($dist->district_name) ? $dist->district_name : '');
				if (empty($dName)) continue;
				$selected = in_array($dName, $selectedDistricts) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($dName); ?>"><?php echo htmlspecialchars($dName); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 5. Facility: multiple -->
	<div class="form-group col-md-3">
		<label>Facility</label>
		<select class="select form-control select2" name="facility[]" id="filterFacility" multiple="multiple" data-placeholder="All facilities">
			<?php
			$selectedFacilities = empty($search->facility) ? array() : (is_array($search->facility) ? $search->facility : array($search->facility));
			$facilityOptions = isset($facilities_for_chain) ? $facilities_for_chain : array();
			foreach ($facilityOptions as $facility):
				$fname = isset($facility->facility) ? $facility->facility : (isset($facility->facility_name) ? $facility->facility_name : '');
				if (empty($fname)) continue;
				$selected = in_array($fname, $selectedFacilities) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($fname); ?>"><?php echo htmlspecialchars($fname); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 6. Facility Level: multiple -->
	<div class="form-group col-md-3">
		<label>Facility Level</label>
		<select class="select form-control select2" name="facility_type[]" id="filterFacilityLevel" multiple="multiple" data-placeholder="All facility levels">
			<?php
			$selectedLevels = empty($search->facility_type) ? array() : (is_array($search->facility_type) ? $search->facility_type : array($search->facility_type));
			$levelOptions = isset($facility_levels_for_chain) ? $facility_levels_for_chain : array();
			foreach ($levelOptions as $facilityType):
				$ft = isset($facilityType->facility_type) ? $facilityType->facility_type : (isset($facilityType->facility_type_name) ? $facilityType->facility_type_name : '');
				if (empty($ft)) continue;
				$selected = in_array($ft, $selectedLevels) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($ft); ?>"><?php echo htmlspecialchars($ft); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 7. Job Cadre: multiple -->
	<div class="form-group col-md-3">
		<label>Job Cadre</label>
		<select class="select form-control select2" name="cadre[]" id="filterJobCadre" multiple="multiple" data-placeholder="All cadres">
			<?php
			$selectedCadres = empty($search->cadre) ? array() : (is_array($search->cadre) ? $search->cadre : array($search->cadre));
			$cadreOptions = isset($job_cadres_for_chain) ? $job_cadres_for_chain : array();
			foreach ($cadreOptions as $cadre):
				$cname = isset($cadre->cadre_name) ? $cadre->cadre_name : '';
				if (empty($cname)) continue;
				$selected = in_array($cname, $selectedCadres) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($cname); ?>"><?php echo htmlspecialchars($cname); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 8. Job Category: multiple -->
	<div class="form-group col-md-3">
		<label>Job Category</label>
		<select class="select form-control select2" name="job_category[]" id="filterJobCategory" multiple="multiple" data-placeholder="All job categories">
			<?php
			$selectedCategories = empty($search->job_category) ? array() : (is_array($search->job_category) ? $search->job_category : array($search->job_category));
			$jobCatOptions = isset($job_categories_for_chain) ? $job_categories_for_chain : array();
			foreach ($jobCatOptions as $jobCat):
				$jc = isset($jobCat->job_category) ? $jobCat->job_category : '';
				if (empty($jc)) continue;
				$selected = in_array($jc, $selectedCategories) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($jc); ?>"><?php echo htmlspecialchars($jc); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 9. Job Classification: multiple -->
	<div class="form-group col-md-3">
		<label>Job Classification</label>
		<select class="select form-control select2" name="job_class[]" id="filterJobClassification" multiple="multiple" data-placeholder="All job classifications">
			<?php
			$selectedClasses = empty($search->job_class) ? array() : (is_array($search->job_class) ? $search->job_class : array($search->job_class));
			$jobClassOptions = isset($job_classifications_for_chain) ? $job_classifications_for_chain : array();
			foreach ($jobClassOptions as $jobClass):
				$jcl = isset($jobClass->job_class) ? $jobClass->job_class : (isset($jobClass->job_classification) ? $jobClass->job_classification : '');
				if (empty($jcl)) continue;
				$selected = in_array($jcl, $selectedClasses) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($jcl); ?>"><?php echo htmlspecialchars($jcl); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<!-- 10. Job Name: multiple -->
	<div class="form-group col-md-3">
		<label>Job Name</label>
		<select class="select form-control select2" name="job[]" id="filterJobName" multiple="multiple" data-placeholder="All jobs">
			<?php
			$selectedJobs = empty($search->job) ? array() : (is_array($search->job) ? $search->job : array($search->job));
			$jobNameOptions = isset($job_names_for_chain) ? $job_names_for_chain : array();
			foreach ($jobNameOptions as $job):
				$jname = isset($job->job) ? $job->job : (isset($job->job_name) ? $job->job_name : '');
				if (empty($jname)) continue;
				$selected = in_array($jname, $selectedJobs) ? 'selected' : '';
			?>
				<option <?php echo $selected ?> value="<?php echo htmlspecialchars($jname); ?>"><?php echo htmlspecialchars($jname); ?></option>
			<?php endforeach; ?>
		</select>
		<small class="form-text text-muted">Leave empty for all.</small>
	</div>

	<div class="form-group col-md-2">
		<label>Aggregate By</label>
		<select class="select form-control" name="aggregate">
			<option value="job_name">
				Job
			</option>
			<option value="institution_type" <?php echo ($search->aggregate == 'institution_type') ? 'selected' : ''; ?>>
				Institution Type
			</option>
			<option value="district_name" <?php echo ($search->aggregate == 'district_name') ? 'selected' : ''; ?>>
				District
			</option>
			<option value="facility_name" <?php echo ($search->aggregate == 'facility_name') ? 'selected' : ''; ?>>
				Facility
			</option>
			<option value="facility_type_name" <?php echo ($search->aggregate == 'facility_type_name') ? 'selected' : ''; ?>>
				Facility Type
			</option>
			<option value="cadre_name" <?php echo ($search->aggregate == 'cadre_name') ? 'selected' : ''; ?>>
				Cadre
			</option>
			<option value="job_classification" <?php echo ($search->aggregate == 'job_classification') ? 'selected' : ''; ?>>
				Classification
			</option>
			<option value="region_name" <?php echo ($search->aggregate == 'region_name') ? 'selected' : ''; ?>>
				Region
			</option>
		</select>
	</div>
		<div class="form-group col-md-2">
		<label>Month Year</label>
		<select class="select form-control" name="month_year">
			<option value="">Current</option>
			<?php 
		
			foreach ($filters->period as $period):

				$selected = ($search->month_year == $period->month_year) ? 'selected' : '';
				?>
		
				<option <?php echo $selected ?> value="<?php echo $period->month_year; ?>">

					<?php echo $period->month_year; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-12 d-flex justify-content-end align-items-center mt-2 mb-0">
		<input type="hidden" name="getPdf" id="print">
		<?php if (!empty($embed)): ?><input type="hidden" name="embed" value="1"><?php endif; ?>
		<input type="submit" class="btn btn-sm btn-success mr-1" value="Apply Filter" />
		<button type="button" class="btn btn-sm btn-warning" onclick="resetFilters()">Reset Filters</button>
	</div>

</form>

<script>
function resetFilters() {
	var $form = $('.searchForm');
	if ($form.length) $form[0].reset();
	var ids = ['filterInstitutionType','filterRegion','filterDistrict','filterFacility','filterFacilityLevel','filterJobCadre','filterJobCategory','filterJobClassification','filterJobName'];
	$.each(ids, function(i, id) {
		var $s = $('#' + id);
		if ($s.length) $s.val(null).trigger('change.select2');
	});
	$('.select2').val(null).trigger('change');
	if (typeof $.fn.DataTable !== 'undefined' && $.fn.DataTable.isDataTable && $.fn.DataTable.isDataTable('.audit-table')) {
		$('.audit-table').DataTable().ajax.reload();
	} else if ($form.length) {
		$form.submit();
	}
}
</script>