<?php
   require_once 'includes/rate_filter_form.php';
?>

<table class="table table-striped table-bordered mytable">
    <thead>
       <tr>
           <th style="text-transform:capitalize;"><?php echo $aggTitle; ?></th>
           <th>Month</th>
           <th>Year</th>
           <th>% Present</th>
           <th>% On Leave</th>
           <th>% Off Duty</th>
           <th>% Requested Absence</th>
           <th>% Absent</th>
       </tr>
    </thead>
    <tbody> 
    <?php

     $totalDays     = 0;
     $totalAbsent    = 0;
     $totalPresent   = 0;
     $totalOffDuty   = 0;
     $totalLeave     = 0;
     $totalOffical   = 0;

      foreach($data as $row):

        $todaDays       = $row->days_tracked + $row->absolute_days_absent; //days tracked + days untracked
        $unAccountedFor = $row->days_tracked;
        $percPresent    = ($row->daysPresent/ $todaDays )*100;
        $percLeave      = ($row->daysOnLeave/ $todaDays )*100;
        $percOffDuty    = ($row->daysOffDuty/ $todaDays )*100;
        $percOfficial   = ($row->daysRequest/ $todaDays )*100;
        $percAbsent     = ($row->absolute_days_absent/ $todaDays )*100;

        $statusClass = ($percAbsent>=50)?'bg-danger':(($percAbsent>=20)?'bg-warning':'bg-success');

        $totalDays    +=  $todaDays;
        $totalAbsent  += $row->absolute_days_absent;
        $totalPresent += $row->daysPresent;
        $totalOffDuty += $row->daysOffDuty;
        $totalLeave   += $row->daysOnLeave;
        $totalOffical += $row->daysRequest;

      ?>      
       <tr>
           <td><?php echo $row->$aggColumn; ?></td>
           <td><?php echo $row->monthWords; ?></td>
           <td><?php echo $row->year; ?></td>
           <td><?php echo number_format($percPresent,1); ?></td>
           <td><?php echo number_format($percLeave,1); ?></td>
           <td><?php echo number_format($percOffDuty,1); ?></td>
           <td><?php echo number_format($percOfficial ,1); ?></td>
           <td class="<?=$statusClass?> text-bold"><?php echo number_format($percAbsent,1); ?></td>
       </tr>
   <?php endforeach; ?>
    </tbody>
   <tfoot>
   <tr>
      <th colspan="3" >OVERALL</th>
      <!-- <th><?php  echo  $totalDays; ?> days </th> -->
      <th><?php   echo  number_format(($totalPresent/$totalDays)*100,1); ?>% Present</th>
      <th><?php   echo number_format(($totalLeave/$totalDays)*100,1); ?>% Leave</th>
      <th><?php   echo number_format(($totalOffDuty/$totalDays)*100,1); ?>% Off Duty</th>
      <th><?php   echo number_format(($totalOffical/$totalDays)*100,1); ?>% Official Request</th>
      <th><?php   echo number_format(($totalAbsent/$totalDays)*100,1);  ?>% Absent</th>
   </tr>
   </tfoot>
</table>


