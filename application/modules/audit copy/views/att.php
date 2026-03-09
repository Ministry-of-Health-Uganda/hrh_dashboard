
<?php

$districts=Modules::run('districts/getAttDistricts');

$distoptions="";

$distoptions .="<option value='0'>All Districts</option>";

		foreach ($districts as $dist) {

				if($dist->district_id=="district|".$district){

				$distoptions .="<option selected value='".str_replace("district|", "",$dist->district_id)."'>".$dist->district."</option>";
			}

			$distoptions .="<option value='".str_replace("district|", "",$dist->district_id)."'>".$dist->district."</option>";
		
		}



if($district){

		$facilities=Modules::run('facilities/getAttFacilities',$district);

   //<option disabled >--Select Facility--</option>
		$faci_options="";

		foreach ($facilities as $myfacility):

			if($myfacility->facility_id==$facility){

				$faci_options .="<option selected value='".$facility."'>".$myfacility->facility."</option>";
			}

			$faci_options.='<option value="'.htmlspecialchars($myfacility->facility_id).'">'.$myfacility->facility.'</option>';
		
		endforeach;

		

	}


	$facility_types=Modules::run('facilities/getTypes','ihris_att'); 

	$types="";

	foreach ($facility_types as $type) {
		

		$types.="<option value='".$type->type."'>".$type->type."</option>";

		}

?>

<style type="text/css">
	
	.form-group{

		padding-bottom: 1.5em;

	}
</style>



<h4 class="text-center" style="padding-top:0px;margin-top: 0px;"><?php //echo $facility; ?></h4>

<div class="collapsebox">
    <a href="#hide1" class="hider" id="hide1"><i class="fa fa-search"></i> Show Filters</a>
    <a href="#show1" class="vision" id="show1">Hide Filters</a>
    
      
  <div class="collapse-content">  

<form class="form" method="post" action="<?=base_url()?>jobs/showAttendance">

	<div class="form-group">

             <select class="select district"  name="district">
                <option disabled selected value="">Choose District</option>
                <?php echo $distoptions; ?>
              </select>
     </div>


      <div class="form-group ">

             <select   name="type" class="select">
                 
                 <?php if($thistype){ ?>

             	<option selected  value='<?=$thistype?>'><?=$thistype?></option>

             	<?php } ?>

                 
                 <?php if(!$thistype){ ?>

             	<option selected disabled value=''>--Facility Type--</option>

             	<?php } ?>

             	<option  value=''>All Facility Types </option>;

			     <?php


					print_r($types);

				?>

              </select>
     </div>

    <div class="form-group ">

             <select   name="facility" class="select facility">

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

     <div class="form-group ">

     	<select   name="year" class="select month" >

             	<option  value='<?=$thisyear;?>'><?=$thisyear;?></option>

             	<?php for($year=2018;$year<(2101);$year++){  ?>

             	<option  value='<?=$year;?>'><?=$year;?> </option>

            <?php } ?>
         </select>

         <select   name="month" class="select month" >

             	<option  value='<?=$thismonth?>'><?=$thismonth?></option>

             		<?php for($month=1;$month<13;$month++){  ?>

             	<option  value="<?php echo date('F',strtotime('2018-'.$month.'-01')); ?>">

             		<?php echo date('F',strtotime("2018-".$month."-01")); ?>
             	</option>

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
    
        
    if($thisyear){
        
        $link.=$thisyear."/";
    }
    
     if($thismonth){
        
        $link.=$thismonth."/";
    } 
    
    
    if($district){
        
        $link.=$district."/";
    }else if(!$district){
        
        $link.="NODST"."/";
    }
    
    if($facility){
        
        $link.=$facility."/";
    }else if(!$facility){
        
        $link.="NOFC"."/";
    }
    
     if($thistype){
        
        $link.=$thistype."/";
    }else if(!$thistype){
        
        $link.="NOTYPE"."/";
    }

    
    ?>
    
    <center>
    <a class="btn" style="width:100%; background-color:#4bcf23;" href="<?php echo base_url(); ?>jobs/printAttendance/<?=$link?>"><i class="fa fa-download"></i> RENDER PDF</a>
    </center>
</div>

<center style="padding: 0.2em;">

	<h5 id='pro' style="color: green; display: none;">Processing...</h5>

		<small>
		
		<?php if($facility){

				echo Modules::run('jobs/getFacilityName',$facility);
		} ?>

		<br>


	<small>
	
	<?php foreach($schedules as $schedule): 
	 

	 echo " ".$schedule->letter." = <b>".$schedule->schedule."</b> "; 

	
	endforeach; 
	?>

</small></center>


<table>


<thead>
	<th>Position</th>

	<?php foreach($schedules as $schedule): ?>
	<th class="num"><?php echo $schedule->letter; ?></th>
	<?php endforeach; ?>
	
</thead

<?php 

$sum=0;

foreach ($attendances as $ttendance):

?>

	<tr>
		<td><?php echo $ttendance->job; ?></td>

		
		
		<td  class="num"><?php echo Modules::run('jobs/sumAtt','present',$ttendance->job_id,$thisyear,$thismonth,$district,$facility,$thistype); ?></td>

		<td  class="num"><?php echo Modules::run('jobs/sumAtt','request',$ttendance->job_id,$thisyear,$thismonth,$district,$facility,$thistype); ?></td>
		<td  class="num"><?php echo Modules::run('jobs/sumAtt','offduty',$ttendance->job_id,$thisyear,$thismonth,$district,$facility,$thistype); ?></td>
		<td  class="num"><?php echo Modules::run('jobs/sumAtt','leavedays',$ttendance->job_id,$thisyear,$thismonth,$district,$facility,$thistype); ?></td>
		<td  class="num"><?php echo Modules::run('jobs/sumAtt','absent',$ttendance->job_id,$thisyear,$thismonth,$district,$facility,$thistype); ?></td>

	<tr>

	<?php

	 endforeach;

	//echo $sum;


	 ?>

</table>

<?php if(count($attendances)<1){ ?>

<center><b style="color:red">No matching data</b></b></center>

<?php } ?>

<script type="text/javascript">

	$('.district').change(function(){

var districtId=$(this).val();

if(districtId!==''){

console.log(districtId);

var url="<?php echo base_url(); ?>jobs/getAttFacilities/"+districtId;

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




