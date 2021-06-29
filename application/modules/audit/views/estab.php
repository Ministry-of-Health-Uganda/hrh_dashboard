


<?php


$districts=Modules::run('districts/getDistricts');

$distoptions="";

$distoptions .="<option value='0'>All Districts</option>";

		foreach ($districts as $dist) {

				if($dist->district_id=="district|".$district){

				$distoptions .="<option selected value='".str_replace("district|", "",$dist->district_id)."'>".$dist->district_name."</option>";
			}

			$distoptions .="<option value='".str_replace("district|", "",$dist->district_id)."'>".$dist->district_name."</option>";
		
		}



if($district){

		$facilities=Modules::run('facilities/getFacilities',$district);

   //<option disabled >--Select Facility--</option>
		$faci_options="";

		foreach ($facilities as $myfacility):

			if($myfacility->facility_id==$facility){

				$faci_options .="<option selected value='".$facility."'>".$myfacility->facility."</option>";
			}

			$faci_options.='<option value="'.htmlspecialchars($myfacility->facility_id).'">'.$myfacility->facility.'</option>';
		
		endforeach;

		

	}


	$facility_types=Modules::run('facilities/getTypes','establishment'); 

	$types="";

	foreach ($facility_types as $type) {

		

		if($type_id==$thistype){

			$types.="<option selected value='".$thistype."'>".$type->type."</option>";

			}

		$types.="<option value='".$type_id."'>".$type->type."</option>";

		}

?>

<style type="text/css">
	
	.form-group{

		padding-bottom: 1.5em;

	}
	
	@media:print{
	    
	    .form{
	        display:none;
	    }
	}
</style>



<h4 class="text-center" style="padding-top:0px;margin-top: 0px;"><?php //echo $facility; ?></h4>

  <center>


<div class="collapsebox">
    <a href="#hide1" class="hider" id="hide1"><i class="fa fa-search"></i> Show Filters</a>
    <a href="#show1" class="vision" id="show1">Hide Filters</a>
    
      
  <div class="collapse-content">   
 
<form class="form" method="post" action="<?=base_url()?>audit/showEstablishments" >

	<div class="form-group" style="margin-top:10px;">

             <select class="select district"  name="district">
                <option disabled selected value="">Choose District</option>
                <?php echo $distoptions; ?>
              </select>
     </div>


      <div class="form-group ">

             <select   name="type" class="select" >

             	<?php if(!$thistype){ ?>

             	<option selected disabled value=''>--Facility Type--</option>

             	<?php } ?>

             	<option  value='0'>All Facility Types </option>

			     <?php


					print_r($types);

				?>

              </select>
     </div>

    <div class="form-group ">

             <select   name="facility" class="select facility" >

             	<option  value=''>All Facilities </option>;

			     <?php


				if($district){

					print_r($faci_options);

				}
			
			 if(!$facility){  ?>

                <option disabled selected value="">Choose Facility</option>
                
              <?php } ?>

              </select>
     </div>
     
     <div class="form-group">
         <center><button type="submit" onclick="$('#pro').show();" class="btn" style="width:80%; background-color:#4bcf23;" >Apply filters</button></center>
    
     </div>



</form>
 
</div>
</div>
    
<div class="form-group">
    
    <?php 
    
    $link="";
    
    //district
    if(!$district){
		    $link.="NODST";
		 } else
    if($district){
        
        $link.=$district;
    }
    
	//facility
	 if(!$facility){
        
        $link.="/NOFC";
    } else  if($facility){
        
        $link.="/".str_replace("facility|","",$facility);
    }
    
    //type
    if(!$thistype){
        
        $link.="/NOTYPE";
    }else
     if($thistype){
        
        $link.="/".$thistype;
    }
    
    
    ?>
    
    <center>
    <a class="btn" style="width:100%; background-color:#4bcf23;" href="<?php echo base_url(); ?>audit/printEstablishments/<?=$link?>"><i class="fa fa-download"></i> RENDER PDF</a>
    </center>
</div>

 </center>

<center style="padding: 0.2em;">

	<h5 id='pro' style="color: green; display: none;">Processing...</h5>

	<small>
		
		<?php if($facility){ echo Modules::run('audit/getFacilityName',$facility);  } ?>

		<br>

	</small>

	<small>E=Establishment, F=Filled, V=Vacant, X=Excess</small>
</center>

<div style="overflow-x:auto;">
    
<table style="max-width:100%;">


<thead>

	<th width='150px'>Position</th>
	<th class="num">E</th>
	<th class="num">F</th>
	<th class="num">V</th>
	<th class="num">X</th>
	
	
</thead>

<?php 
	
ini_set('max_execution_time',0);

$filled_sum=0;
$esta_sum=0;
$vac_sum=0;
$ex_sum=0;

foreach ($jobs as $job):

	$job_id=str_replace("job|","",$job->job_id);

		//$thistype comes from controller fro selected facility type

	$esta=Modules::run('audit/getEstablishment',$district,$facility,$job_id,$thistype);


    //print_r($esta);

	$establishment= $esta->esta;

	$filled=$esta->filled;
	
	$filled_sum+=$filled;
	$esta_sum+=$establishment;
	
	if($esta->excess_vacant>0){
	    $vac_sum+=$esta->excess_vacant;
	}else{
	    
	     $ex_sum+=$esta->excess_vacant;
	}
?>

	<tr>
		<td width='150px'><?php echo $job->job; ?></td>
		<td  class="num"><?php echo $establishment; ?></td>
		<td  class="num"><?php echo $filled; ?></td>
		<td  class="num"><?php if($esta->excess_vacant>0){echo $esta->excess_vacant;} else{ echo "0";} ?></td>
		<td  class="num"><?php if($esta->excess_vacant<0){echo $esta->excess_vacant*-1;}else{ echo "0";} ?></td>


	<tr>

	<?php

	 endforeach;
	 
	 

	//echo $sum;


	 ?>

</table>

</div>

<table style="margin-top:0.5em;">
    	 

	<tr><th colspan='2'>Sub Totals</th></tr>
	<tr><td>Establishment</td><th class="num"><?=$esta_sum?></th></tr>
	<tr><td>Filled</td><th class="num"><?=$filled_sum?></th></tr>
	<tr><td>Vacant</td><th class="num"><?=$vac_sum?></th></tr>
	<tr><td>Excess</td><th class="num"><?=$ex_sum*-1?></th></tr>


</table>

<?php if(count($jobs)<1){ ?>

<center><b style="color:red">No matching data</b></b></center>

<?php } ?>

<script type="text/javascript">

	$('.district').change(function(){

var districtId=$(this).val();

if(districtId!==''){

console.log(districtId);

var url="<?php echo base_url(); ?>audit/getFacilities/"+districtId;

$.ajax({
  url:url,
  success:function(response){

 $('.facility').html(response);


    console.log(response);
  }
});

}

});


 $('.select').select2();

 


</script>




