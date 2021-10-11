
<?php

$districts=Modules::run('districts/getDistricts');

$distoptions="";

		foreach ($districts as $dist) {

			if($dist->district_id=="district|".$district){

				$distoptions .="<option selected value='".str_replace("district|", "",$dist->district_id)."'>".$dist->district." (Selected)</option>";
			}

			$distoptions .="<option value='".str_replace("district|", "",$dist->district_id)."'>".$dist->district."</option>";
		
		}

		//print_r($distoptions);

?>

<style type="text/css">
	
	.form-group{

		padding-bottom: 1.5em;

	}
</style>





<form class="form" method="post" action="<?=base_url()?>jobs/showEstablishments">

	<div class="form-group">

             <select class="select district"  name="district">
             	
             	<?php if(!$district){  ?>
                
                <option selected disabled value="">Choose District</option>

                 <?php } echo $distoptions; ?>

              </select>
     </div>

    <div class="form-group ">

             <select   name="facility" class="select facility" onchange="$('.form').submit();">

             	<?php if($facility){  ?>

				<option style="display: none;" value='<?php echo $facility; ?>'> </option>;
			

			<?php } ?>
                
                <option disabled selected value="">Choose Facility</option>
               

              </select>
     </div>




</form>

<center style="padding: 0.2em;">

	<small>
		
		<?php if($facility){ echo Modules::run('jobs/getFacilityName',$facility);  } ?>

		<br>

	</small>

	<small>E=Establishment, F=Filled, V=Vacant, X=Excess</small>
</center>

<table>

	<thead>
	<th>Position</th>
	<th class="num">E</th>
	<th class="num">F</th>
	<th class="num">V</th>
	<th class="num">X</th>
	
</thead>


<?php 

$sum=0;

foreach ($jobs as $job):

		$esta=Modules::run('jobs/getEstablishment',FALSE,FALSE,$job->job_id);


		$establishment= $esta->esta;

	  
?>

	<tr>
		<td><?php echo $job->job; ?></td>
		<td  class="num"><?php echo $establishment; ?></td>
		<td  class="num"><?php echo $esta->filled; ?></td>
		<td  class="num"><?php if($esta->excess_vacant>0){echo $esta->excess_vacant;} else{ echo "0";} ?></td>
		<td  class="num"><?php if($esta->excess_vacant<0){echo $esta->excess_vacant*-1;}else{ echo "0";} ?></td>


	<tr>

	<?php

	 endforeach;

	//echo $sum;


	 ?>

</table>

<script type="text/javascript">

	var dist=$('.district').val();

	if(dist!==''){

var url="<?php echo base_url(); ?>jobs/getFacilities/"+dist;

$.ajax({
  url:url,
  success:function(response){

 $('.facility').html(response);


    console.log(response);
  }
});

}


//when district is selected
	$('.district').change(function(){

var districtId=$(this).val();

var url="<?php echo base_url(); ?>jobs/getFacilities/"+districtId;

$.ajax({
  url:url,
  success:function(response){

 $('.facility').html(response);


    console.log(response);
  }
});

});


 $('.select').select2();

 


</script>




