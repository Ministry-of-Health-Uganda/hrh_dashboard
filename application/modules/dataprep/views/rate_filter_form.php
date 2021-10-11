<form class="form-horizontal row" method="post" action="">
	<div class="form-group col-md-2">
		<label>Institution Type</label>
		<select class="select form-control">
			<?php foreach($filters->institutions as $inst): ?>
				<option><?php echo $inst->institution_type; ?></option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-2">

		<label>Facility</label>
		<select class="select form-control">
			<?php foreach($filters->facilities as $facility): ?>
				<option><?php echo $facility->facility; ?></option>
			<?php endforeach; ?>
			
		</select>
	</div>

	<div class="form-group col-md-2">
		<label>Year</label>
		<select class="select form-control">
			<option></option>
			
		</select>
	</div>

	<div class="form-group col-md-2">
		<label>Month</label>
		<select class="select form-control">
			<option></option>
			
		</select>
	</div>
</form>

