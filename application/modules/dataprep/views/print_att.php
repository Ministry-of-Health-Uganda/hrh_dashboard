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
       <h3><?php echo $title; ?>
       <?php if($thistype && !$district){  echo " <br>".urldecode($thistype);  }  ?>
       </h3>
       
       
       
       <?php if($district){  ?>
       
       <h4><?php  echo Modules::run('districts/getDistrict',$district);   ?> District
       
       <?php if($thistype){  echo " - ".$thistype;  }  ?>
       
       </h4> 
      
       <?php if($facility){ 
           ini_set('max_execution_time',0);
       echo "<h5>".Modules::run('districts/getFacilityName',$facility)."</h5>";
       } ?>
       
       <?php } ?>
	
<center>
	<small style="padding:0.5em;">
	
	<?php foreach($schedules as $schedule): 
	 

	 echo " ".$schedule->letter." = <b>".$schedule->schedule."</b> "; 

	
	endforeach; 
	?>

</small></center>
<?php

//echo $thismonth.$thisyear.$district;
?>


<table class="items" style="font-size: 12pt; border-collapse: collapse; " cellpadding="8" width="100%">


<tr>
	<th class="cost">Position</th>

	<?php foreach($schedules as $schedule): ?>
	<th class="cost"><?php echo $schedule->letter; ?></th>
	<?php endforeach; ?>
	
</tr>
<tbody>

<?php 

$sum=0;

//print_r($attendances);


foreach ($attendances as $attendance):

?>

	<tr>
		<td class="cost"><?php echo $attendance->job; ?></td>

		<td  class="cost"><?php echo Modules::run('jobs/pdfsumAtt','present',$thisyear,$thismonth,$attendance->job_id,$district,$facility,$thistype); ?></td>

		<td  class="cost"><?php echo Modules::run('jobs/pdfsumAtt','request',$thisyear,$thismonth,$attendance->job_id,$district,$facility,$thistype); ?></td>
		<td  class="cost"><?php echo Modules::run('jobs/pdfsumAtt','offduty',$thisyear,$thismonth,$attendance->job_id,$district,$facility,$thistype); ?></td>
		<td  class="cost"><?php echo Modules::run('jobs/pdfsumAtt','leavedays',$thisyear,$thismonth,$attendance->job_id,$district,$facility,$thistype); ?></td>
		<td  class="cost"><?php echo Modules::run('jobs/pdfsumAtt','absent',$thisyear,$thismonth,$attendance->job_id,$district,$facility,$thistype); ?></td>

	</tr>

	<?php

	 endforeach;

	//echo $sum;


	 ?>
	 
</tbody>

</table>
