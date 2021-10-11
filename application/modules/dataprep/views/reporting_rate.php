<?php
   require_once 'includes/rate_filter_form.php';
?>

<table class="table table-striped table-bordered">
    <thead>
       <tr>
           <th>Institution</th>
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
    <?php foreach($data as $row):?>      
       <tr>
           <td><?php echo $row->facility_name; ?></td>
           <td><?php echo $row->monthWords; ?></td>
           <td><?php echo $row->year; ?></td>
           <td><?php echo $row->staff_count; ?></td>
           <td><?php echo $row->roster_count; ?></td>
           <td><?php echo number_format( ($row->roster_count/$row->staff_count)*100,1); ?>%</td>
           <td><?php echo $row->attendance_count; ?></td>
           <td><?php 
           $totalAttendance = $row->attendance_count;

           echo ($totalAttendance>0)? number_format( ($totalAttendance/$row->attendance_count)*100,1):0; 
            ?>%</td>
       </tr>
   <?php endforeach; ?>
    </tbody>
</table>


