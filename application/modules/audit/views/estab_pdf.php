<html>
<head>
    <style>
body {font-family: Arial;
	font-size: 12pt;
	max-width:21cm;
	max-height:29.7cm;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
td { vertical-align: top; }
.items td {
	border-left: 0.2mm solid #000000;
	border-right: 0.2mm solid #000000;
}
table thead th { background-color: #ccc;
	text-align: center;
	border: 0.2mm solid #000000;
	/*font-variant: small-caps;*/
}

.items tr td {
	border: 0.2mm solid #000000;
	
}

.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}
.logo{
margin-top:0em;
margin-left:20%;
margin-right:20%;
margin-bottom:0.5em;
}

.heading{
margin-top:0.4em;
margin-left:20%;
margin-right:10%;
margin-bottom:0.1em;
}

.title{
margin-top:0.0em;
margin-left:30%;
margin-right:10%;
margin-bottom:0.1em;
}

table tr:nth-child(even){
    
    background-color:#e6f2f0;
}


</style>
    
</head>
<body>
    
   <?php  //print_r($jobs); ?>
       <h3><?php echo $title; ?></h3>
       <?php if($district){  ?>
       
       <h4><?php  echo Modules::run('districts/getDistrict',$district);   ?> District
       
       <?php if($thistype){ ?>
       <?php  echo " - ". $thistype;   ?>
       
       <?php } ?>
       
       </h4> 
       
      
       <?php if($facility){ 
           ini_set('max_execution_time',0);
       echo "<h5>".Modules::run('districts/getFacilityName',$facility)."</h5>";
       } ?>
       
       <?php } ?>
<center style="padding:0.7em;">

    	<small>E=<b>Establishment</b>, F=<b>Filled</b>, V=<b>Vacant</b>, X=<b>Excess</b></small>
</center>

<table class="items" style="font-size: 12pt; border-collapse: collapse; " cellpadding="8" width="100%">

<tr>

	<th width="50%">Position</th>
	<th class="cost">E</th>
	<th class="cost">F</th>
	<th class="cost">V</th>
	<th class="cost">X</th>
	
	
</tr>
<tbody>
<?php 
	

	
ini_set('max_execution_time',0);

$filled_sum=0;
$esta_sum=0;
$vac_sum=0;
$ex_sum=0;

$facility_prefix="";	

	if($facility){
	    
	    $facility_prefix="facility|";
	}


foreach ($jobs as $job):

	$job_id=str_replace("job|","",$job->job_id);

		//$thistype comes from controller fro selected facility type

	$esta=Modules::run('jobs/getEstablishment',$district, $facility_prefix.$facility,$job_id,$thistype);


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
		<td><?php echo $job->job; ?></td>
		<td  class="cost"><?php echo $establishment; ?></td>
		<td  class="cost"><?php echo $filled; ?></td>
		<td  class="cost"><?php if($esta->excess_vacant>0){echo $esta->excess_vacant;} else{ echo "0";} ?></td>
		<td  class="cost"><?php if($esta->excess_vacant<0){echo $esta->excess_vacant*-1;}else{ echo "0";} ?></td>


	<tr>

	<?php

	 endforeach;
	 
	 

	//echo $sum;


	 ?>
	 
	 </tbody>
	 
	 <tfoot>

	<th width="50%">Total</th>
	<th class="num"><?=$esta_sum?></th>
	<th class="num"><?=$filled_sum?></th>
	<th class="num"><?=$vac_sum?></th>
	<th class="num"><?=$ex_sum*-1?></th>
</tfoot>


</table>

</body>

</html>
