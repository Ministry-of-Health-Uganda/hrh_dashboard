<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word">
<head>
<meta charset="UTF-8">
<meta name="ProgId" content="Word.Document">
<meta name="Generator" content="Microsoft Word">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!--[if gte mso 9]>
<xml>
  <w:WordDocument>
    <w:View>Print</w:View>
    <w:Zoom>100</w:Zoom>
    <w:DoNotOptimizeForBrowser/>
  </w:WordDocument>
</xml>
<![endif]-->
<style>
@page {
  size: landscape;
  margin: 1cm;
}
body {
  font-family: Arial, sans-serif;
  font-size: 8pt;
  margin: 0;
  padding: 0.5cm;
  max-width: 100%;
}
p { margin: 0 0 0.2em 0; }
.logo-cell img {
  width: 80px;
  height: 80px;
  object-fit: contain;
}
table.items {
  border-collapse: collapse;
  width: 100%;
  table-layout: fixed;
  font-size: 8pt;
}
table.items td,
table.items th {
  border: 1px solid #000;
  padding: 3px 4px;
  vertical-align: middle;
}
table.items thead th {
  background-color: #ccc;
  text-align: center;
  font-weight: bold;
}
table.items tfoot th {
  background-color: #e0e0e0;
  text-align: center;
}
table.items tr:nth-child(even) { background-color: #e6f2f0; }
table.items .section-header td {
  background: #ddd !important;
  font-weight: bold;
  padding: 4px;
}
.header-table { width: 100%; margin-bottom: 6px; }
.header-table td { border: none; vertical-align: top; padding: 0; }
h2 { margin: 0 0 0.2em 0; font-size: 12pt; }
.text-bold { font-weight: bold; }
</style>
</head>
<body>

  <table class="header-table">
    <tr>
      <td class="logo-cell" style="width:90px;"><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="80" height="80" alt="Logo" /></td>
      <td>
        <h2>Staff Audit Report</h2>
        <?php if (!empty($legend)): ?>
          <p style="text-transform: capitalize; font-weight: bold; font-size: 8pt;"><?php echo $legend; ?></p>
        <?php endif; ?>
      </td>
    </tr>
  </table>
  <hr style="margin: 4px 0;" />

  <?php $hasAgg2 = !empty($search->aggregate2) && !empty($aggColumn2); ?>
  <table class="items">
    <thead>
      <tr>
        <?php if ($hasAgg2): ?>
          <th style="width:15%; text-transform: capitalize;"><?php echo isset($aggTitle2) ? htmlspecialchars($aggTitle2) : ''; ?> (Side)</th>
        <?php else: ?>
          <th style="width:18%; text-transform: capitalize;"><?php echo $aggTitle; ?></th>
        <?php endif; ?>
        <?php if ($search->aggregate == 'job_name') { ?><th style="width:8%;">Salary Scale</th><?php } ?>
        <th style="width:6%;">Approved</th>
        <th style="width:6%;">Filled</th>
        <th style="width:5%;">Vacant</th>
        <th style="width:5%;">Excess</th>
        <th style="width:5%;">Male</th>
        <th style="width:5%;">Female</th>
        <th style="width:6%;">Filled %</th>
        <th style="width:6%;">Vacant %</th>
        <th style="width:5%;">Male %</th>
        <th style="width:5%;">Female %</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $totalApproved = 0;
        $totalFilled   = 0;
        $totalVacant   = 0;
        $totalExcess   = 0;
        $totalMales    = 0;
        $totalFemales  = 0;

        if ($hasAgg2):
          $groups = array();
          foreach ($audit as $row) {
            $k = $row->$aggColumn;
            if (!isset($groups[$k])) $groups[$k] = array();
            $groups[$k][] = $row;
          }
          foreach ($groups as $value1 => $rows):
            $ncols = ($search->aggregate == 'job_name') ? 12 : 11;
            echo '<tr class="section-header"><td colspan="' . $ncols . '">' . htmlspecialchars($value1) . '</td></tr>';
            foreach ($rows as $row):
              $structure    = $row->approved;
              $difference   = $row->approved - $row->filled;
              $vacantPosts  = (isset($row->vacant) && $row->vacant !== null && $row->vacant !== '') ? (int)$row->vacant : (($difference>0) ? $difference : 0);
              $excessPosts  = (isset($row->excess) && $row->excess !== null && $row->excess !== '') ? (int)$row->excess : (($difference<0) ? $difference * -1 : 0);
              $male    = ($structure >0 && $row->filled > 0) ? ($row->male/$row->filled)* 100 : 0;
              $female  = ($structure >0 && $row->filled > 0) ? ($row->female/$row->filled) * 100 : 0;
              $vacant  = ($structure >0)?($vacantPosts/$structure) * 100:0;
              $filled  = ($structure >0)?($row->filled/$structure) * 100:0;
              $totalApproved += $structure; $totalFilled += $row->filled; $totalVacant += $vacantPosts; $totalExcess += $excessPosts; $totalFemales += $row->female; $totalMales += $row->male;
      ?>
        <tr>
          <td><?php echo htmlspecialchars($row->$aggColumn2); ?></td>
          <?php if ($search->aggregate == 'job_name') { ?><td><?php echo $row->salary_scale; ?></td><?php } ?>
          <td><?php echo $row->approved; ?></td>
          <td><?php echo $row->filled; ?></td>
          <td><?php echo $vacantPosts; ?></td>
          <td><?php echo $excessPosts; ?></td>
          <td><?php echo $row->male; ?></td>
          <td><?php echo $row->female; ?></td>
          <td class="text-bold"><?php echo ($filled>0)?number_format($filled,1):0; ?>%</td>
          <td class="text-bold"><?php echo ($vacant>0)?number_format($vacant,1):0; ?>%</td>
          <td class="text-bold"><?php echo ($male>0)?number_format($male,1):0; ?>%</td>
          <td class="text-bold"><?php echo ($female>0)?number_format($female,1):0; ?>%</td>
        </tr>
      <?php endforeach; endforeach;
        else:
          foreach ($audit as $row):
            $structure    = $row->approved;
            $difference   = $row->approved - $row->filled;
            $vacantPosts  = (isset($row->vacant) && $row->vacant !== null && $row->vacant !== '') ? (int)$row->vacant : (($difference>0) ? $difference : 0);
            $excessPosts  = (isset($row->excess) && $row->excess !== null && $row->excess !== '') ? (int)$row->excess : (($difference<0) ? $difference * -1 : 0);
            $male    = ($structure >0 && $row->filled > 0) ? ($row->male/$row->filled)* 100 : 0;
            $female  = ($structure >0 && $row->filled > 0) ? ($row->female/$row->filled) * 100 : 0;
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
          <td><?php echo $row->$aggColumn; ?></td>
          <?php if ($search->aggregate == 'job_name') { ?><td><?php echo $row->salary_scale; ?></td><?php } ?>
          <td><?php echo $row->approved; ?></td>
          <td><?php echo $row->filled; ?></td>
          <td><?php echo $vacantPosts; ?></td>
          <td><?php echo $excessPosts; ?></td>
          <td><?php echo $row->male; ?></td>
          <td><?php echo $row->female; ?></td>
          <td class="text-bold"><?php echo ($filled>0)?number_format($filled,1):0; ?>%</td>
          <td class="text-bold"><?php echo ($vacant>0)?number_format($vacant,1):0; ?>%</td>
          <td class="text-bold"><?php echo ($male>0)?number_format($male,1):0; ?>%</td>
          <td class="text-bold"><?php echo ($female>0)?number_format($female,1):0; ?>%</td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
    <tfoot>
      <tr>
        <th>TOTALS</th>
        <?php if ($search->aggregate == 'job_name') { ?><th></th><?php } ?>
        <th><?php echo $totalApproved; ?></th>
        <th><?php echo $totalFilled; ?></th>
        <th><?php echo $totalVacant; ?></th>
        <th><?php echo $totalExcess; ?></th>
        <th><?php echo $totalMales; ?></th>
        <th><?php echo $totalFemales; ?></th>
        <th><?php echo ($totalApproved > 0) ? number_format(($totalFilled/$totalApproved)*100,1) : 0; ?>%</th>
        <th><?php echo ($totalApproved > 0) ? number_format(($totalVacant/$totalApproved)*100,1) : 0; ?>%</th>
        <th><?php echo ($totalFilled > 0) ? number_format(($totalMales/$totalFilled)*100,1) : 0; ?>%</th>
        <th><?php echo ($totalFilled > 0) ? number_format(($totalFemales/$totalFilled)*100,1) : 0; ?>%</th>
      </tr>
    </tfoot>
  </table>
</body>
</html>
