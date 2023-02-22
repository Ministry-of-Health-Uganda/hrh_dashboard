<form class="form-horizontal row searchForm" method="post" action="">

	<div class="form-group col-md-3">
		<label>Institution Type</label>
		<select class="select form-control select2" name="institution">
			<option value="">All</option>
			<?php foreach ($filters->institutions as $inst):

				$selected = (isset($search->institution) && $search->institution == $inst->institution_type) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $inst->institution_type; ?>">
					<?php echo $inst->institution_type; ?>
				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Region</label>
		<select class="select form-control" name="region">
			<option value="">All</option>

			<?php foreach ($filters->regions as $region):

				$selected = (isset($search->region) && $search->region == $region->region_id) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $region->region_name; ?>">

					<?php echo $region->region_name; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>District</label>
		<select class="select form-control select2" name="district">
			<option value="">All</option>
			<?php foreach ($filters->districts as $dist):

				$selected = ($search->district == $dist->district) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $dist->district; ?>">

					<?php echo $dist->district; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Facility</label>
		<select class="select form-control select2" name="facility">
			<option value="">All</option>
			<?php foreach ($filters->facilities as $facility):

				$selected = ($search->facility == $facility->facility) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $facility->facility; ?>">

					<?php echo $facility->facility; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Facility Level</label>
		<select class="select form-control select2" name="facility_type">
			<option value="">All</option>
			<?php foreach ($filters->facility_types as $facilityType):

				$selected = ($search->facility_type == $facilityType->facility_type) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $facilityType->facility_type; ?>">

					<?php echo $facilityType->facility_type; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Name</label>
		<select class="select form-control select2" name="job">
			<option value="">All</option>
			<?php foreach ($filters->jobs as $job):

				$selected = ($search->job == $job->job) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $job->job; ?>">

					<?php echo $job->job; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Category</label>
		<select class="select form-control select2" name="job_category">
			<option value="">All</option>
			<?php foreach ($filters->job_categories as $jobCat):

				$selected = ($search->job_category == $jobCat->job_category) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $jobCat->job_category; ?>">

					<?php echo $jobCat->job_category; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Classification</label>
		<select class="select form-control select2" name="job_class">
			<option value="">All</option>
			<?php foreach ($filters->job_classifications as $jobClass):

				$selected = ($search->job_class == $jobClass->job_class) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $jobClass->job_class; ?>">

					<?php echo $jobClass->job_class; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Cadre</label>
		<select class="select form-control select2" name="cadre">
			<option value="">All</option>
			<?php foreach ($filters->job_cadres as $cadre):

				$selected = ($search->cadre == $cadre->cadre_name) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $cadre->cadre_name; ?>">

					<?php echo $cadre->cadre_name; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-2">
		<label>OwnerShip</label>
		<select class="select form-control" name="ownership">
			<option value="">All</option>
			<?php foreach ($filters->ownership as $owner):

				$selected = ($search->ownership == $owner->ownership) ? 'selected' : '';
				?>
				<option <?php echo $selected ?> value="<?php echo $owner->ownership; ?>">

					<?php echo $owner->ownership; ?>

				</option>
			<?php endforeach; ?>

		</select>
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
		</select>
	</div>
	<div class="form-group col-md-1">
		<label>Month</label>
		<select class="select form-control" name="month">
			<option value="">Current</option>          
			<?php 
		
			foreach ($filters->period as $period):

				$selected = ($search->month == $owner->month) ? 'selected' : '';
				?>
		
				<option <?php echo $selected ?> value="<?php echo $period->month; ?>">

					<?php echo $period->month; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>
	<div class="form-group col-md-1">
		<label>Year</label>
		<select class="select form-control" name="year">
			<option value="">Current</option> 
			<?php foreach ($filters->period as $period):

				$selected = ($search->year == $owner->year) ? 'selected' : '';
				?>
				
				<option <?php echo $selected ?> value="<?php echo $period->year; ?>">

					<?php echo $period->year; ?>

				</option>
			<?php endforeach; ?>

		</select>
	</div>

	<div class="form-group col-md-1">
		<br>
		<input type="submit" class="btn btn-sm btn-success" value="Apply Filter" />
	</div>
	<div class="form-group col-md-1">
	</div>
	<div class="form-group col-md-1">
		<br>
		<input type="hidden" name="getPdf" id="print">
		<button onclick="$('#print').val(1); $('.searchForm').submit();$('#print').val(0);" type="button"
			class="btn btn-sm btn-default">Download</button>
	</div>

</form>