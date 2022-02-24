<form class="form-horizontal row searchForm" method="post" action="">
   <?php  $filters = Modules::run('audit/facAudit',$fac->facility_id)['audit']; ?>
	<div class="form-group col-md-3">
		<label>Institution Type</label>
		<select class="select form-control select2" name="institution">
			<option value="">All</option>
			<?php foreach($filters->institutions as $inst): 
				
				$selected = (isset($search->institution ) && $search->institution == $inst->institution_type)?'selected':'';
			 ?>
			   <option <?php echo $selected ?> 
				value="<?php echo $inst->institution_type; ?>">
				    <?php echo $inst->institution_type; ?>
				</option>
			<?php endforeach; ?>
			
		</select>
	</div>
	
	
	<div class="form-group col-md-3">
		<label>Facility Level</label>
		<select class="select form-control select2" name="facility_type">
			<option value="">All</option>
			<?php foreach($filters->facility_types as $facilityType):

			$selected = ($search->facility_type == $facilityType->facility_type)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $facilityType->facility_type; ?>">

				       <?php echo $facilityType->facility_type; ?>
				   	
				   </option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Name</label>
		<select class="select form-control" name="job">
			<option value="">All</option>
			<?php foreach($filters->jobs as $job):

				$selected = ($search->job == $job->job)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $job->job; ?>">

				       <?php echo $job->job; ?>
				   	
				   </option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Category</label>
		<select class="select form-control select2" name="job_category">
			<option value="">All</option>
			<?php foreach($filters->job_categories as $jobCat):

				$selected = ($search->job_category == $jobCat->job_category)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $jobCat->job_category; ?>">

				       <?php echo $jobCat->job_category; ?>
				   	
				   </option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Classification</label>
		<select class="select form-control select2" name="job_class">
			<option value="">All</option>
			<?php foreach($filters->job_classifications as $jobClass):

				$selected = ($search->job_class == $jobClass->job_class)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $jobClass->job_class; ?>">

				       <?php echo $jobClass->job_class; ?>
				   	
				   </option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-3">
		<label>Job Cadre</label>
		<select class="select form-control select2" name="cadre">
			<option value="">All</option>
			<?php foreach($filters->job_cadres as $cadre):

				$selected = ($search->cadre == $cadre->cadre_name)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $cadre->cadre_name; ?>">

				       <?php echo $cadre->cadre_name; ?>
				   	
				   </option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-3">
		<label>OwnerShip</label>
		<select class="select form-control" name="ownership">
			<option value="">All</option>
			<?php foreach($filters->ownership as $owner):

				$selected = ($search->ownership == $owner->ownership)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $owner->ownership; ?>">

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
			<option value="institution_type"
			<?php echo ($search->aggregate == 'institution_type')?'selected':''; ?>>
			  Institution Type
			 </option>
			<option value="district_name" 
			 <?php echo ($search->aggregate == 'district_name')?'selected':''; ?>>
			  District
			 </option>
			 <option value="facility_name" 
			 <?php echo ($search->aggregate == 'facility_name')?'selected':''; ?>>
			  Facility
			 </option>
			 <option value="facility_type_name" 
			 <?php echo ($search->aggregate == 'facility_type_name')?'selected':''; ?>>
			  Facility Type
			 </option>
			 <option value="cadre_name" 
			 <?php echo ($search->aggregate == 'cadre_name')?'selected':''; ?>>
			  Cadre
			 </option>
		</select>
	</div>

	<div class="form-group col-md-1">
		<br>
		<input  type="submit" class="btn btn-sm btn-success" value="Apply Filter" />
	</div>
	<div class="form-group col-md-2">
		<br>
		<!-- <button  type="reset" class="btn btn-sm btn-default" >Reset Filters</button> -->
	</div>

	<div class="form-group col-md-1">
		<br>
		 <input type="hidden" name="getPdf" id="print">
		 <button onclick="$('#print').val(1); $('.searchForm').submit();$('#print').val(0);"  type="button" class="btn btn-sm btn-default" >Download</button>
	</div>
	
</form>