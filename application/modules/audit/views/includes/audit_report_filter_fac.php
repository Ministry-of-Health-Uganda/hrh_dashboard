<form class="form-horizontal row searchForm" method="post" action="<?php echo base_url()?>audit/facAudit?districts=<?php echo $_SESSION['district_id']?>">
   <?php  $filters = Modules::run('audit/facAudit')['filters']; ?>
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

	<div class="form-group col-md-3">
		<label>Aggregate By</label>
		<select class="select form-control" name="aggregate">
			<option value="job_name">
			   Job
		     </option>
			
			 <option value="cadre_name" 
			 <?php echo ($search->aggregate == 'cadre_name')?'selected':''; ?>>
			  Cadre
			 </option>
		</select>
	</div>

	<div class="form-group col-md-1">
		<br>
		<input  type="submit" class="btn  btn-success" value="Apply Filter" />
	</div>
	<div class="form-group col-md-2">
		<br>
		<!-- <button  type="reset" class="btn btn-sm btn-default" >Reset Filters</button> -->
	</div>

	<div class="form-group col-md-1">
		<br>
		 
		 <button  type="submit" name="getPdf" class="btn btn-default" >Download</button>
	</div>
	
</form>