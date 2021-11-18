<html>
<head>
<style>

body {font-family: Arial;
	font-size: 12pt;
	max-width:21cm;
	font-size: 10pt;
	max-width:22cm;
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

.text-success{
  color:green;
}

</style>
</head>
<body>
<table class="items" style="font-size: 12pt; border-collapse: collapse; " cellpadding="8" width="100%">

<table>
    <tr>
      <td><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="80px" /></td>
      <td>
          <br/>
          <h2> Staffing Audit Report</h2>
          <?php if(!empty($legend)): ?>
              <br/>
              <p style="text-transform: capitalize;"><?php echo $legend; ?></p>
          <?php endif; ?>
      </td>
    </tr>
</table>
<hr />
<br/>

<table class="items" style="font-size: 10pt; border-collapse: collapse; " cellpadding="8" width="100%">
  <thead>
       <tr>
           <th width="25%" style="text-transform: capitalize;">
             <?php echo  $aggTitle; ?>
           </th>
           <th>Salary Scale</th>
           <th>Approved</th>
           <th>Filled</th>
           <th>Vacant</th>
           <th>Excess</th>
           <th>Male</th>
           <th>Female</th>
           <th>Filled %</th>
           <th>Vacant %</th>
           <th>Male %</th>
           <th>Female %</th>
       </tr>
    </thead>

    <tbody> 
    <?php
          $totalApproved = 0;
          $totalFilled   = 0;
          $totalVacant   = 0;
          $totalExcess   = 0;
          $overAllTotal  = 0;
          $totalMales    = 0;
          $totalFemales  = 0;

          foreach($audit as $row):

            $structure    = $row->approved;
            $difference   = $row->approved - $row->filled;

            $vacantPosts  = ($difference>0)?$difference :0; //vacant +
            $excessPosts  = ($difference<0)?$difference *-1:0; //excess -

            $male    = ($structure >0)?($row->male/$row->filled)* 100:0;
            $female  = ($structure >0)?($row->female/$row->filled) * 100:0;
            $vacant  = ($structure >0)?($vacantPosts/$structure) * 100:0;
            $filled  = ($structure >0)?($row->filled/$structure) * 100:0;

          $totalApproved += $structure;
          $totalFilled   += $row->filled;
          $totalVacant   += $vacantPosts;
          $totalExcess   += $excessPosts;
          $totalFemales  += $row->female;
          $totalMales    += $row->male;

    ?>      
       <tr>
           <td><?php echo  $row->$aggColumn; ?></td>
           <td><?php echo  $row->salary_scale; ?></td>
           <td><?php echo  $row->approved; ?></td>
           <td><?php echo  $row->filled; ?></td>
           <td><?php echo  $vacantPosts; ?></td>
           <td><?php echo  $excessPosts; ?></td>
           <td><?php echo  $row->male;   ?></td>
           <td><?php echo  $row->female; ?></td>
           <td class="text-bold">
            <?php echo  ($filled>0)?number_format($filled,1):0; ?>%
           </td>
           <td class="text-bold">
            <?php echo  ($vacant>0)?number_format($vacant,1):0; ?>%
           </td>
           <td class="text-bold">
             <?php echo  ($male>0)?number_format($male,1):0;   ?>%
           </td>
           <td class="text-bold">
             <?php echo  ($female>0)?number_format($female,1):0; ?>%
           </td>
       </tr>

   <?php endforeach; ?>
   
    </tbody>
    
    <tfoot>
        <tr>
           <th width="25%">TOTALS</th>
           <th></th>
           <th><?php echo $totalApproved; ?></th>
           <th><?php echo $totalFilled; ?></th>
           <th><?php echo $totalVacant; ?></th>
           <th><?php echo $totalExcess; ?></th>
           <th><?php echo $totalMales; ?></th>
           <th><?php echo $totalFemales; ?></th>
           <th>
            <?php echo number_format(($totalFilled/$totalApproved)*100,1); ?>%   
            </th>
            <th>
            <?php echo number_format(($totalVacant/$totalApproved)*100,1); ?>%   
            </th>
            <th>
            <?php echo number_format(($totalMales/$totalFilled)*100,1); ?>%   
            </th>
           <th>
            <?php echo number_format(($totalFemales/$totalFilled)*100,1); ?>%   
            </th>
       </tr>
        
    </tfoot>
</table>

          </body>
          </html>