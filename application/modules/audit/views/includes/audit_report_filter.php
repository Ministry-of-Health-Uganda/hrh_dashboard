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
		<small class="form-text text-muted">Chained from ownership. Leave empty for all.</small>
	</div>

	<!-- 3. Region: chained, multiple -->
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
		<small class="form-text text-muted">Chain starts here. Select region then district, etc. Leave empty for all.</small>
	</div>

	<!-- 4. District: chained, multiple -->
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
		<small class="form-text text-muted">Chained from region. Leave empty for all.</small>
	</div>

	<!-- 5. Facility Level: chained, multiple -->
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
		<small class="form-text text-muted">Chained from region and district. Leave empty for all.</small>
	</div>

	<!-- 6. Facility: chained, multiple -->
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
		<small class="form-text text-muted">Chained from region through facility level. Leave empty for all.</small>
	</div>

	<!-- 7. Job Cadre: chained, multiple -->
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
		<small class="form-text text-muted">Chained from facility. Leave empty for all.</small>
	</div>

	<!-- 8. Job Category: chained, multiple -->
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
		<small class="form-text text-muted">Chained from job cadre. Leave empty for all.</small>
	</div>

	<!-- 9. Job Classification: chained, multiple -->
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
		<small class="form-text text-muted">Chained from job category. Leave empty for all.</small>
	</div>

	<!-- 10. Job Name: chained, multiple -->
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
		<small class="form-text text-muted">Chained from job classification. Leave empty for all.</small>
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

	

	<div class="form-group col-md-1">
		<br>
		<input type="submit" class="btn btn-sm btn-success" value="Apply Filter" />
	</div>
	<div class="form-group col-md-1">
		<br>
		<button type="button" class="btn btn-sm btn-warning" onclick="resetFilters()">Reset Filters</button>
	</div>
	<input type="hidden" name="getPdf" id="print">
	<?php if (!empty($embed)): ?><input type="hidden" name="embed" value="1"><?php endif; ?>
	<div class="form-group col-md-1">
		<br>
	</div>

</form>

<script>
(function() {
	'use strict';
	console.log('[Chain] Filter chain script loaded');
	if (typeof jQuery === 'undefined') {
		console.error('[Chain] jQuery not found – chain will not work');
		return;
	}
	var $ = jQuery;
	// Chain 1: Ownership (single) -> Institution Type only. Chain 2: Region -> District -> ... -> Job Name (all multiple)
	var baseUrl = ($('.base_url').length ? $('.base_url').text() : '').replace(/\s/g, '') || (typeof base_url !== 'undefined' ? base_url : '');
	baseUrl = baseUrl.replace(/\/?$/, '') + '/';
	var CHAIN_LEVELS = ['institution_type','region_name','district','facility_level','facility','cadre','job_category','job_classification','job_name'];

	function toArray(v) {
		if (v == null) return [];
		if ($.isArray(v)) return v;
		if (v === '') return [];
		return [v];
	}

	function getChainState() {
		var regionVal = $('#filterRegion').val();
		var regions = toArray(regionVal);
		if (regions.length === 0 && $('#filterRegion').data('select2')) {
			try {
				var sel2Data = $('#filterRegion').select2('data');
				if (sel2Data && sel2Data.length) regions = $(sel2Data).map(function() { return this.id != null ? String(this.id) : ''; }).get().filter(Boolean);
			} catch (e) {}
		}
		return {
			ownership: ($('#filterOwnership').val() || '').toString().trim(),
			institution_types: toArray($('#filterInstitutionType').val()),
			regions: regions,
			districts: toArray($('#filterDistrict').val()),
			facility_levels: toArray($('#filterFacilityLevel').val()),
			facilities: toArray($('#filterFacility').val()),
			cadres: toArray($('#filterJobCadre').val()),
			job_categories: toArray($('#filterJobCategory').val()),
			job_classifications: toArray($('#filterJobClassification').val())
		};
	}

	function getSelectIdForLevel(level) {
		var map = { institution_type: 'filterInstitutionType', region_name: 'filterRegion', district: 'filterDistrict',
			facility_level: 'filterFacilityLevel', facility: 'filterFacility', cadre: 'filterJobCadre',
			job_category: 'filterJobCategory', job_classification: 'filterJobClassification', job_name: 'filterJobName' };
		return map[level] || '';
	}

	function getValueKeyForLevel(level) {
		var map = { institution_type: 'institution_type', region_name: 'region_name', district: 'district',
			facility_level: 'facility_type', facility: 'facility', cadre: 'cadre_name',
			job_category: 'job_category', job_classification: 'job_class', job_name: 'job' };
		return map[level] || level;
	}

	function clearDownstreamFrom(level) {
		var idx = CHAIN_LEVELS.indexOf(level);
		if (idx < 0) return;
		for (var i = idx + 1; i < CHAIN_LEVELS.length; i++) {
			var id = getSelectIdForLevel(CHAIN_LEVELS[i]);
			var $s = $('#' + id);
			if ($s.length) {
				$s.find('option').remove();
				$s.val(null).trigger('change.select2');
			}
		}
	}

	function loadChainedOptions(level, thenClearDownstream) {
		if (!level || !baseUrl) {
			console.warn('[Chain] loadChainedOptions skipped: level=', level, 'baseUrl=', baseUrl);
			return;
		}
		var state = getChainState();
		var params = { level: level, ownership: state.ownership };
		if (state.institution_types.length) {
			params.institution_types = state.institution_types;
			params.institution_types_json = JSON.stringify(state.institution_types);
		}
		if (state.regions.length) params.regions = state.regions;
		else if (level !== 'region_name' && level !== 'institution_type') console.warn('[Chain] No regions in state for level=', level, '– district (and below) need at least one region');
		if (state.districts.length) params.districts = state.districts;
		if (state.facility_levels.length) params.facility_levels = state.facility_levels;
		if (state.facilities.length) params.facilities = state.facilities;
		if (state.cadres.length) params.cadres = state.cadres;
		if (state.job_categories.length) params.job_categories = state.job_categories;
		if (state.job_classifications.length) params.job_classifications = state.job_classifications;
		var id = getSelectIdForLevel(level);
		var key = getValueKeyForLevel(level);
		var $sel = $('#' + id);
		if (!$sel.length) {
			console.warn('[Chain] loadChainedOptions: select not found #' + id);
			return;
		}
		var url = baseUrl + 'audit/getChainedFilterOptions';
		console.log('[Chain] Request:', level, { url: url, params: params });
		$sel.find('option').remove();
		$.ajax({
			url: url,
			type: 'POST',
			data: params,
			dataType: 'json'
		})
			.done(function(data) {
				var list = data && $.isArray(data) ? data : [];
				if (!$.isArray(data)) {
					console.warn('[Chain] Response was not an array:', level, typeof data, data);
				}
				console.log('[Chain] Response OK:', level, 'count=' + list.length);
				$.each(list, function(i, d) {
					var name = (d && (d[key] != null ? d[key] : d.region_name || d.district || d.facility_type || d.facility || d.cadre_name || d.job_category || d.job_class || d.job || d.institution_type));
					if (name != null && name !== '') $sel.append($('<option></option>').val(String(name)).text(String(name)));
				});
				$sel.val(null).trigger('change.select2');
				if (thenClearDownstream) clearDownstreamFrom(level);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.error('[Chain] Request failed:', level, {
					url: url,
					status: jqXHR.status,
					statusText: jqXHR.statusText,
					textStatus: textStatus,
					errorThrown: errorThrown,
					responseText: (jqXHR.responseText || '').substring(0, 500)
				});
				$sel.trigger('change.select2');
			});
	}

	var chainDebounce = {};
	function scheduleChainLoad(level, nextLevel) {
		clearTimeout(chainDebounce[nextLevel]);
		chainDebounce[nextLevel] = setTimeout(function() {
			console.log('[Chain] scheduleChainLoad running:', nextLevel);
			loadChainedOptions(nextLevel, true);
		}, 120);
	}

	function bindChainHandlers() {
		// Chain 1: Ownership -> Institution Type only
		$(document).off('change.chain', '#filterOwnership').on('change.chain', '#filterOwnership', function() {
			loadChainedOptions('institution_type', false);
		});
		// Chain 2: Region -> District -> ...; bind on DOM ready and directly to #filterRegion so we catch change after Select2 init
		var $region = $('#filterRegion');
		$region.off('change.chain select2:select.chain select2:unselect.chain').on('change.chain select2:select.chain select2:unselect.chain', function() {
			console.log('[Chain] Region changed/selected');
			scheduleChainLoad('region_name', 'district');
		});
		$(document).off('change.chain select2:select.chain select2:unselect.chain', '#filterDistrict').on('change.chain select2:select.chain select2:unselect.chain', '#filterDistrict', function() { scheduleChainLoad('district', 'facility_level'); });
		$(document).off('change.chain select2:select.chain select2:unselect.chain', '#filterFacilityLevel').on('change.chain select2:select.chain select2:unselect.chain', '#filterFacilityLevel', function() { scheduleChainLoad('facility_level', 'facility'); });
		$(document).off('change.chain select2:select.chain select2:unselect.chain', '#filterFacility').on('change.chain select2:select.chain select2:unselect.chain', '#filterFacility', function() { scheduleChainLoad('facility', 'cadre'); });
		$(document).off('change.chain select2:select.chain select2:unselect.chain', '#filterJobCadre').on('change.chain select2:select.chain select2:unselect.chain', '#filterJobCadre', function() { scheduleChainLoad('cadre', 'job_category'); });
		$(document).off('change.chain select2:select.chain select2:unselect.chain', '#filterJobCategory').on('change.chain select2:select.chain select2:unselect.chain', '#filterJobCategory', function() { scheduleChainLoad('job_category', 'job_classification'); });
		$(document).off('change.chain select2:select.chain select2:unselect.chain', '#filterJobClassification').on('change.chain select2:select.chain select2:unselect.chain', '#filterJobClassification', function() { scheduleChainLoad('job_classification', 'job_name'); });
		console.log('[Chain] Handlers bound. #filterRegion exists:', $region.length);
	}

	// Run when DOM is ready so #filterRegion exists and we can bind directly (after Select2 may have inited)
	$(function() {
		bindChainHandlers();
		// Re-bind Region after a short delay in case Select2 inits later (e.g. in another ready callback)
		setTimeout(function() {
			if ($('#filterRegion').length && !$('#filterRegion').data('select2')) console.log('[Chain] Select2 not yet on #filterRegion');
			bindChainHandlers();
		}, 400);
	});
})();

function resetFilters() {
	var $form = $('.searchForm');
	if ($form.length) $form[0].reset();
	var ids = ['filterInstitutionType','filterRegion','filterDistrict','filterFacilityLevel','filterFacility','filterJobCadre','filterJobCategory','filterJobClassification','filterJobName'];
	$.each(ids, function(i, id) {
		var $s = $('#' + id);
		if ($s.length) { $s.find('option').remove(); $s.val(null).trigger('change.select2'); }
	});
	$('.select2').val(null).trigger('change');
	if (typeof $.fn.DataTable !== 'undefined' && $.fn.DataTable.isDataTable && $.fn.DataTable.isDataTable('.audit-table')) {
		$('.audit-table').DataTable().ajax.reload();
	} else if ($form.length) {
		$form.submit();
	}
}
</script>