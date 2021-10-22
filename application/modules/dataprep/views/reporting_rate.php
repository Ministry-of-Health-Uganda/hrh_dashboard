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
           <th>Staff on Roster</th>
           <th>% on Roster</th>
           <th>Attendance Reported</th>
           <th>% Attendance</th>
       </tr>
    </thead>
    <tbody> 
    <?php

         $totalStaff        = 0;
         $totalOnRoster     = 0;
         $totalOnAttendance = 0;

      foreach($data as $row):

         $totalAttendance = $row->attendance_count;

         $totalStaff        += $row->staff_count;
         $totalOnRoster     += $row->roster_count;
         $totalOnAttendance += $totalAttendance;

      ?>      
       <tr>
           <td>
            <?php echo $row->$aggColumn; ?>
               
            </td>
           <td><?php echo $row->monthWords; ?></td>
           <td><?php echo $row->year; ?></td>
           <td><?php echo $row->staff_count; ?></td>
           <td><?php echo $row->roster_count; ?></td>
           <td><?php echo number_format( ($row->roster_count/$row->staff_count)*100,1); ?>%</td>
           <td><?php echo $row->attendance_count; ?></td>
           <td><?php 
           echo ($totalAttendance>0)? number_format( ($totalAttendance/$row->attendance_count)*100,1):0; 
            ?>%</td>
       </tr>
   <?php endforeach; ?>
    </tbody>
   <tfoot>
   <tr>
      <th >TOTALS</th>
      <th></th>
      <th></th>
      <th ><?php echo $totalStaff; ?> </th>
      <th ><?php echo $totalOnRoster; ?> </th>
      <th></th>
      <th ><?php echo $totalOnAttendance; ?> </th>
      <th></th>
   </tr>
   </tfoot>
</table>


