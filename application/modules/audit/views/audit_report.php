<?php
require_once 'includes/audit_report_filter.php';
?>
<style>
  div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    display: none;
  }
</style>

<hr />
<br />

<?php if (!empty($legend)) : ?>
  <div class="form-group">
    <h5 style="text-transform: capitalize;"><?php echo $legend; ?></h5>
  </div>
  <hr />
<?php endif; ?>


<table class="table table-striped table-bordered mytable">
  <thead>
    <tr>
      <th width="25%" style="text-transform: capitalize;">
        <?php echo  $aggTitle; ?>
      </th>
      <?php if (($search->aggregate  == 'job_name') || ($search->aggregate  == '')) { ?><th>Salary Scale</th> <?php } ?>
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

    foreach ($audit as $row) :

      $structure    = $row->approved;
      $difference   = $row->approved - $row->filled;

      $vacantPosts  = ($difference > 0) ? $difference : 0; //vacant +
      $excessPosts  = ($difference < 0) ? $difference * -1 : 0; //excess -

      $male    = ($structure > 0) ? ($row->male / $row->filled) * 100 : 0;
      $female  = ($structure > 0) ? ($row->female / $row->filled) * 100 : 0;
      $vacant  = ($structure > 0) ? ($vacantPosts / $structure) * 100 : 0;
      $filled  = ($structure > 0) ? ($row->filled / $structure) * 100 : 0;

      $totalApproved += $structure;
      $totalFilled   += $row->filled;
      $totalVacant   += $vacantPosts;
      $totalExcess   += $excessPosts;
      $totalFemales  += $row->female;
      $totalMales    += $row->male;

    ?>
      <tr>
        <td><?php echo  $row->$aggColumn; ?></td>
        <?php if (($search->aggregate  == 'job_name') || ($search->aggregate  == '')) { ?><td><?php echo  $row->salary_scale; ?></td><?php } ?>
        <td><?php echo  $row->approved; ?></td>
        <td><?php echo  $row->filled; ?></td>
        <td><?php echo  $vacantPosts; ?></td>
        <td><?php echo  $excessPosts; ?></td>
        <td><?php echo  $row->male;   ?></td>
        <td><?php echo  $row->female; ?></td>
        <td class="text-bold">
          <?php echo ($filled > 0) ? number_format($filled, 1) : 0; ?>%
        </td>
        <td class="text-bold">
          <?php echo ($vacant > 0) ? number_format($vacant, 1) : 0; ?>%
        </td>
        <td class="text-bold">
          <?php echo ($male > 0) ? number_format($male, 1) : 0;   ?>%
        </td>
        <td class="text-bold">
          <?php echo ($female > 0) ? number_format($female, 1) : 0; ?>%
        </td>
      </tr>

    <?php endforeach; ?>

  </tbody>
  <tfoot>
    <tr>
      <th width="25%">TOTALS</th>
      <?php if ($search->aggregate  == 'job_name') { ?><th></th> <?php } ?>
      <th><?php echo $totalApproved; ?></th>
      <th><?php echo $totalFilled; ?></th>
      <th><?php echo $totalVacant; ?></th>
      <th><?php echo $totalExcess; ?></th>
      <th><?php echo $totalMales; ?></th>
      <th><?php echo $totalFemales; ?></th>
      <th>
        <?php echo number_format(($totalFilled / $totalApproved) * 100, 1); ?>%
      </th>
      <th>
        <?php echo number_format(($totalVacant / $totalApproved) * 100, 1); ?>%
      </th>
      <th>
        <?php echo number_format(($totalMales / $totalFilled) * 100, 1); ?>%
      </th>
      <th>
        <?php echo number_format(($totalFemales / $totalFilled) * 100, 1); ?>%
      </th>
    </tr>

  </tfoot>
</table>