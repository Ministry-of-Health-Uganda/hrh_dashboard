<form class="form-horizontal row" method="post" action="">
<div class="form-group col-md-2">
		<label>From</label>
		<input type="text"
		  value="<?php echo isset($search->fromDate)?$search->fromDate:'';?>" 
		name="fromDate" class="datepicker form-control" autocomplete="off">
	</div>

	<div class="form-group col-md-2">
		<label>To</label>
		<input type="text"
		  value="<?php echo isset($search->toDate)?$search->toDate:'';?>" 
		 name="toDate"  class="datepicker form-control" autocomplete="off">
	</div>
	<div class="form-group col-md-2">
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
	
	<div class="form-group col-md-2">
		<label>Region</label>
		<select class="select form-control select2" name="region">
			<option value="">All</option>
			<?php foreach($filters->regions as $region):

				$selected = (isset($search->region ) && $search->region == $region->region_id)?'selected':'';
			 ?>
			   <option <?php echo $selected ?> 
				value="<?php echo $region->region_id; ?>">

				<?php echo $region->region_name; ?>
					
				</option>
			<?php endforeach; ?>
			
		</select>
	</div>
	
	<div class="form-group col-md-2">
		<label>District</label>
		<select class="select form-control select2" name="district">
			<option value="">All</option>
			<?php foreach($filters->districts as $dist): 

				$selected = ($search->district == $dist->district_id)?'selected':'';
			 ?>
			   <option <?php echo $selected ?> 
				value="<?php echo $dist->district_id; ?>">

				<?php echo $dist->district; ?>

				</option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-2">
		<label>Facility</label>
		<select class="select form-control select2" name="facility">
			<option value="">All</option>
			<?php foreach($filters->facilities as $facility):

				$selected = ($search->facility == $facility->facility_id)?'selected':'';
			 ?>
				<option <?php echo $selected ?> 
				   value="<?php echo $facility->facility_id; ?>">

				       <?php echo $facility->facility; ?>
				   	
				   </option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-2">
		<label>Aggregation</label>
		<select class="select form-control" name="grouping">
			<option value="facility_id" <?php echo (isset($search->grouping) && $search->grouping=='facility_id')?"selected":""; ?>>
			 Facility
		    </option>
			<option value="district_id" <?php echo (isset($search->grouping) && $search->grouping=='district_id')?"selected":""; ?>>
				District
			</option>
		</select>
	</div>

	
	<div class="form-group col-md-2">
		<br>
		<br>
		<input  type="submit" class="btn btn-success" value="Apply Filter" />
	</div>

	
</form>
<style>
div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    display:none;
}
</style>