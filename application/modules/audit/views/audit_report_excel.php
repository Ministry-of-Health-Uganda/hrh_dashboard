<!DOCTYPE html>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
table { border-collapse: collapse; width: 100%; font-size: 10pt; font-family: Calibri, Arial, sans-serif; }
th, td { border: 1px solid #333; padding: 5px 8px; vertical-align: middle; }
thead th { background: #217346; color: #fff; font-weight: bold; text-align: center; }
th.col-num, td.col-num { width: 3.5em; min-width: 3em; max-width: 3.5em; }
tbody tr:nth-child(even) { background: #f0f7f4; }
tfoot th, .subtotal-row { background: #e0e8e4 !important; font-weight: bold; }
.totals-table thead th { background: #1a5c38; color: #fff; }
.report-title { font-size: 14pt; font-weight: bold; margin: 0 0 4px 0; }
.report-legend { font-size: 9pt; font-weight: bold; color: #333; margin: 0 0 10px 0; }
.section-heading { font-size: 11pt; font-weight: bold; margin: 12px 0 6px 0; }
.logo-cell { vertical-align: middle; padding-right: 12px; }
</style>
</head>
<body>
<table style="border: none; margin-bottom: 12px;">
  <tr>
    <td class="logo-cell" style="border: none; width: 60px;"><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="48" height="48" alt="Logo" style="width: 48px; height: 48px; object-fit: contain;" /></td>
    <td style="border: none;">
      <div class="report-title">Staff Audit Report</div>
      <?php if (!empty($legend)): ?>
        <div class="report-legend"><?php echo htmlspecialchars($legend); ?></div>
      <?php endif; ?>
    </td>
  </tr>
</table>

<?php
$hasAgg2 = !empty($search->aggregate);
$totalApproved = 0; $totalFilled = 0; $totalVacant = 0; $totalExcess = 0; $totalMales = 0; $totalFemales = 0;
if ($hasAgg2):
  $groups = array();
  foreach ($audit as $row) {
    $k = $row->$aggColumn;
    if (!isset($groups[$k])) $groups[$k] = array();
    $groups[$k][] = $row;
  }
  $sideLabel = isset($aggTitle2) ? $aggTitle2 : 'Job';
  foreach ($groups as $sectionVal => $rows):
    $subApproved = 0; $subFilled = 0; $subVacant = 0; $subExcess = 0; $subMales = 0; $subFemales = 0;
    echo '<div class="section-heading">' . htmlspecialchars($sectionVal) . '</div>';
    echo '<table class="data-table"><thead><tr>';
    echo '<th class="col-num">#</th><th>' . htmlspecialchars($sideLabel) . '</th>';
    if (!empty($showSalaryScale)) echo '<th>Salary Scale</th>';
    echo '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr></thead><tbody>';
    $rowIndex = 0;
    foreach ($rows as $row):
      $structure = $row->approved;
      $vacantPosts = ($row->approved >= $row->filled) ? ((int)$row->approved - (int)$row->filled) : 0;
      $excessPosts = ($row->filled > $row->approved) ? ((int)$row->filled - (int)$row->approved) : 0;
      $male = ($structure > 0 && $row->filled > 0) ? ($row->male/$row->filled)*100 : 0;
      $female = ($structure > 0 && $row->filled > 0) ? ($row->female/$row->filled)*100 : 0;
      $vacant = ($structure > 0) ? ($vacantPosts/$structure)*100 : 0;
      $filled = ($structure > 0) ? (($row->filled >= $structure) ? 100 : ($row->filled/$structure)*100) : 0;
      $subApproved += $structure; $subFilled += $row->filled; $subVacant += $vacantPosts; $subExcess += $excessPosts; $subMales += $row->male; $subFemales += $row->female;
      $totalApproved += $structure; $totalFilled += $row->filled; $totalVacant += $vacantPosts; $totalExcess += $excessPosts; $totalFemales += $row->female; $totalMales += $row->male;
      echo '<tr><td class="col-num">' . ($rowIndex + 1) . '</td><td>' . htmlspecialchars($row->$aggColumn2) . '</td>';
      if (!empty($showSalaryScale)) echo '<td>' . htmlspecialchars($row->salary_scale) . '</td>';
      echo '<td>' . $row->approved . '</td><td>' . $row->filled . '</td><td>' . $vacantPosts . '</td><td>' . $excessPosts . '</td><td>' . $row->male . '</td><td>' . $row->female . '</td>';
      echo '<td>' . (($filled>0)?number_format($filled,1):0) . '%</td><td>' . (($vacant>0)?number_format($vacant,1):0) . '%</td><td>' . (($male>0)?number_format($male,1):0) . '%</td><td>' . (($female>0)?number_format($female,1):0) . '%</td></tr>';
      $rowIndex++;
    endforeach;
    $subFilledPct = $subApproved > 0 ? number_format(($subFilled >= $subApproved) ? 100 : ($subFilled/$subApproved)*100, 1) : 0;
    $subVacantPct = $subApproved > 0 ? number_format(($subVacant/$subApproved)*100, 1) : 0;
    $subMalePct = $subFilled > 0 ? number_format(($subMales/$subFilled)*100, 1) : 0;
    $subFemalePct = $subFilled > 0 ? number_format(($subFemales/$subFilled)*100, 1) : 0;
    echo '</tbody><tfoot><tr class="subtotal-row"><th class="col-num"></th><th>Subtotal</th>';
    if (!empty($showSalaryScale)) echo '<th></th>';
    echo '<th>' . $subApproved . '</th><th>' . $subFilled . '</th><th>' . $subVacant . '</th><th>' . $subExcess . '</th><th>' . $subMales . '</th><th>' . $subFemales . '</th><th>' . $subFilledPct . '%</th><th>' . $subVacantPct . '%</th><th>' . $subMalePct . '%</th><th>' . $subFemalePct . '%</th></tr></tfoot></table>';
  endforeach;
  if (!empty($totals)):
  echo '<table class="totals-table" style="margin-top: 12px;"><thead><tr><th class="col-num">#</th><th>TOTALS</th>';
  if (!empty($showSalaryScale)) echo '<th></th>';
  echo '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr></thead><tbody><tr><th class="col-num"></th><th>TOTALS</th>';
  if (!empty($showSalaryScale)) echo '<th></th>';
  echo '<th>' . $totalApproved . '</th><th>' . $totalFilled . '</th><th>' . $totalVacant . '</th><th>' . $totalExcess . '</th><th>' . $totalMales . '</th><th>' . $totalFemales . '</th>';
  echo '<th>' . ($totalApproved > 0 ? number_format(($totalFilled >= $totalApproved) ? 100 : ($totalFilled/$totalApproved)*100, 1) : 0) . '%</th><th>' . ($totalApproved > 0 ? number_format(($totalVacant/$totalApproved)*100,1) : 0) . '%</th>';
  echo '<th>' . ($totalFilled > 0 ? number_format(($totalMales/$totalFilled)*100,1) : 0) . '%</th><th>' . ($totalFilled > 0 ? number_format(($totalFemales/$totalFilled)*100,1) : 0) . '%</th></tr></tbody></table>';
  endif;
else:
?>
<table class="data-table">
  <thead>
    <tr>
      <th class="col-num">#</th>
      <th><?php echo htmlspecialchars($aggTitle); ?></th>
      <?php if (!empty($showSalaryScale)) { ?><th>Salary Scale</th><?php } ?>
      <th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th>
    </tr>
  </thead>
  <tbody>
  <?php $rowNum = 0;
  foreach ($audit as $row):
    $rowNum++;
    $structure = $row->approved;
    $vacantPosts = ($row->approved >= $row->filled) ? ((int)$row->approved - (int)$row->filled) : 0;
    $excessPosts = ($row->filled > $row->approved) ? ((int)$row->filled - (int)$row->approved) : 0;
    $male = ($structure > 0 && $row->filled > 0) ? ($row->male/$row->filled)*100 : 0;
    $female = ($structure > 0 && $row->filled > 0) ? ($row->female/$row->filled)*100 : 0;
    $vacant = ($structure > 0) ? ($vacantPosts/$structure)*100 : 0;
    $filled = ($structure > 0) ? (($row->filled >= $structure) ? 100 : ($row->filled/$structure)*100) : 0;
    $totalApproved += $structure; $totalFilled += $row->filled; $totalVacant += $vacantPosts; $totalExcess += $excessPosts; $totalFemales += $row->female; $totalMales += $row->male;
  ?>
    <tr>
      <td class="col-num"><?php echo $rowNum; ?></td>
      <td><?php echo htmlspecialchars($row->$aggColumn); ?></td>
      <?php if (!empty($showSalaryScale)) { ?><td><?php echo htmlspecialchars($row->salary_scale); ?></td><?php } ?>
      <td><?php echo $row->approved; ?></td><td><?php echo $row->filled; ?></td><td><?php echo $vacantPosts; ?></td><td><?php echo $excessPosts; ?></td><td><?php echo $row->male; ?></td><td><?php echo $row->female; ?></td>
      <td><?php echo ($filled>0)?number_format($filled,1):0; ?>%</td><td><?php echo ($vacant>0)?number_format($vacant,1):0; ?>%</td><td><?php echo ($male>0)?number_format($male,1):0; ?>%</td><td><?php echo ($female>0)?number_format($female,1):0; ?>%</td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr class="subtotal-row">
      <th class="col-num"></th>
      <th>TOTALS</th>
      <?php if (!empty($showSalaryScale)) { ?><th></th><?php } ?>
      <th><?php echo $totalApproved; ?></th><th><?php echo $totalFilled; ?></th><th><?php echo $totalVacant; ?></th><th><?php echo $totalExcess; ?></th><th><?php echo $totalMales; ?></th><th><?php echo $totalFemales; ?></th>
      <th><?php echo ($totalApproved > 0) ? number_format(($totalFilled >= $totalApproved) ? 100 : ($totalFilled/$totalApproved)*100, 1) : 0; ?>%</th><th><?php echo ($totalApproved > 0) ? number_format(($totalVacant/$totalApproved)*100,1) : 0; ?>%</th>
      <th><?php echo ($totalFilled > 0) ? number_format(($totalMales/$totalFilled)*100,1) : 0; ?>%</th><th><?php echo ($totalFilled > 0) ? number_format(($totalFemales/$totalFilled)*100,1) : 0; ?>%</th>
    </tr>
  </tfoot>
</table>
<?php endif; ?>
</body>
</html>
