<?php
   require_once 'includes/rate_filter_form.php';
?>

<table class="table table-striped table-bordered mytable">
    <thead>
       <tr>
           <th style="text-transform:capitalize;"><?php echo $aggTitle; ?></th>
           <th>Month</th>
           <th>Year</th>
           <th>Total Staff</th>
           <th>No. Present</th>
           <th>% Present</th>
           <th>No. on Leave</th>
           <th>% on Leave</th>
           <th>No. on Off</th>
           <th>% on Off</th>
           <th>No. Absent</th>
           <th>% Absent</th>
       </tr>
    </thead>
    <tbody> 
    <?php

     $totalStaff     = 0;
     $totalAbsent    = 0;
     $totalPresent   = 0;
     $totalOffDuty   = 0;
     $totalLeave     = 0;
     $totalOffical   = 0;

      foreach($data as $row):

        $staffCount  = $row->staff_count;
        $unAccountedFor = ($row->staff_count)-($row->present+$row->on_leave+$row->off_duty+$row->official_request);
   $absentCount = ($unAccountedFor >0)?$unAccountedFor:0 ;
        $percPresent = ($row->present/$row->staff_count)*100;
        $percLeave   = ($row->on_leave/$row->staff_count)*100;
        $percOffDuty = ($row->off_duty/$row->staff_count)*100;
        $percOfficial = ($row->official_request/$row->staff_count)*100;
        $percAbsent   = ($absentCount/$row->staff_count)*100;

        $statusClass = ($percAbsent>=50)?'bg-danger':(($percAbsent>=30)?'bg-warning':'bg-success');

        $totalStaff   += $staffCount;
        $totalAbsent  += $absentCount;
        $totalPresent += $row->present;
        $totalOffDuty += $row->off_duty;
        $totalLeave   += $row->on_leave;
        $totalOffical += $row->official_request;

      ?>      
       <tr>
           <td><?php echo $row->$aggColumn; ?></td>
           <td><?php echo $row->monthWords; ?></td>
           <td><?php echo $row->year; ?></td>
           <td><?php echo $row->staff_count; ?></td>

           <td><?php echo $row->present; ?></td>
           <td><?php echo number_format($percPresent,1); ?> </td>

           <td><?php echo $row->on_leave; ?></td>
           <td><?php echo number_format($percLeave,1); ?> </td>

           <td><?php echo $row->off_duty; ?></td>
           <td><?php echo number_format($percOffDuty,1); ?> </td>

           <td><?php echo number_format($absentCount,1); ?></td>
           <td class="<?=$statusClass?> text-bold"><?php echo number_format($percAbsent,1); ?></td>
       </tr>
   <?php endforeach; ?>
    </tbody>
   <tfoot>
   <tr>
      <th colspan="3" >TOTALS</th>
      <th><?php  echo $totalStaff; ?> </th>
      <th><?php  echo $totalPresent; ?></th>
      <th></th>
      <th><?php  //echo $totalOffDuty; ?></th>
      <th><?php  //echo $totalAbsent; ?></th>
      <th><?php  //echo $totalOffical; ?></th>
      <th><?php  //echo ($totalAbsent/$totalStaff)*100; ?></th>
   </tr>
   </tfoot>
</table>


