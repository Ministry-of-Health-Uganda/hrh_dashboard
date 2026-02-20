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
    <w:Zoom>FitPage</w:Zoom>
    <w:DoNotOptimizeForBrowser/>
  </w:WordDocument>
</xml>
<![endif]-->
<style>
@page {
  size: landscape;
  margin: 0.75cm;
}
body {
  font-family: Arial, sans-serif;
  font-size: 7pt;
  margin: 0;
  padding: 0.4cm;
  max-width: 100%;
}
p { margin: 0 0 0.15em 0; }
.logo-cell img {
  width: 36px;
  height: 36px;
  object-fit: contain;
}
table.items {
  border-collapse: collapse;
  width: 100%;
  table-layout: fixed;
  font-size: 6.5pt;
  page-break-inside: auto;
}
table.items td,
table.items th {
  border: 1px solid #000;
  padding: 2px 3px;
  vertical-align: middle;
  font-size: 6.5pt;
}
table.items thead th {
  background-color: #ccc;
  text-align: center;
  font-weight: bold;
  font-size: 6.5pt;
}
table.items tfoot th {
  background-color: #e0e0e0;
  text-align: center;
  font-size: 6.5pt;
}
table.items tr:nth-child(even) { background-color: #e6f2f0; }
table.items .section-header td {
  background: #ddd !important;
  font-weight: bold;
  padding: 2px 3px;
}
.header-table { width: 100%; margin-bottom: 4px; }
.header-table td { border: none; vertical-align: top; padding: 0; }
h2 { margin: 0 0 0.15em 0; font-size: 10pt; }
.text-bold { font-weight: bold; }
</style>
</head>
<body>

  <table class="header-table">
    <tr>
      <td class="logo-cell" style="width:44px;"><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="36" height="36" alt="Logo" /></td>
      <td>
        <h2>Staff Audit Report</h2>
        <?php if (!empty($legend)): ?>
          <p style="text-transform: capitalize; font-weight: bold; font-size: 7pt;"><?php echo $legend; ?></p>
        <?php endif; ?>
      </td>
    </tr>
  </table>
  <hr style="margin: 4px 0;" />

  <?php $hasAgg2 = !empty($search->aggregate);
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
            echo '<p style="font-weight:bold; margin-top:0.5em; font-size:7pt;">' . htmlspecialchars($sectionVal) . '</p>';
            echo '<table class="items" style="margin-bottom:0.5em;"><thead><tr><th style="width:14%;">' . htmlspecialchars($sideLabel) . '</th>';
            if (!empty($showSalaryScale)) echo '<th style="width:5%;">Salary</th>';
            echo '<th style="width:4%;">Appr</th><th style="width:4%;">Fill</th><th style="width:4%;">Vac</th><th style="width:4%;">Exc</th><th style="width:4%;">M</th><th style="width:4%;">F</th><th style="width:4%;">Fill%</th><th style="width:4%;">Vac%</th><th style="width:4%;">M%</th><th style="width:4%;">F%</th></tr></thead><tbody>';
            foreach ($rows as $idx => $row):
              $structure = $row->approved;
              $difference = $row->approved - $row->filled;
              $vacantPosts = (isset($row->vacant) && $row->vacant !== null && $row->vacant !== '') ? (int)$row->vacant : (($difference>0) ? $difference : 0);
              $excessPosts = (isset($row->excess) && $row->excess !== null && $row->excess !== '') ? (int)$row->excess : (($difference<0) ? $difference * -1 : 0);
              $male = ($structure > 0 && $row->filled > 0) ? ($row->male/$row->filled)*100 : 0;
              $female = ($structure > 0 && $row->filled > 0) ? ($row->female/$row->filled)*100 : 0;
              $vacant = ($structure > 0) ? ($vacantPosts/$structure)*100 : 0;
              $filled = ($structure > 0) ? ($row->filled/$structure)*100 : 0;
              $subApproved += $structure; $subFilled += $row->filled; $subVacant += $vacantPosts; $subExcess += $excessPosts; $subMales += $row->male; $subFemales += $row->female;
              $totalApproved += $structure; $totalFilled += $row->filled; $totalVacant += $vacantPosts; $totalExcess += $excessPosts; $totalFemales += $row->female; $totalMales += $row->male;
              echo '<tr><td>' . htmlspecialchars($row->$aggColumn2) . '</td>';
              if (!empty($showSalaryScale)) echo '<td>' . htmlspecialchars($row->salary_scale) . '</td>';
              echo '<td>' . $row->approved . '</td><td>' . $row->filled . '</td><td>' . $vacantPosts . '</td><td>' . $excessPosts . '</td><td>' . $row->male . '</td><td>' . $row->female . '</td>';
              echo '<td class="text-bold">' . (($filled>0)?number_format($filled,1):0) . '%</td><td class="text-bold">' . (($vacant>0)?number_format($vacant,1):0) . '%</td><td class="text-bold">' . (($male>0)?number_format($male,1):0) . '%</td><td class="text-bold">' . (($female>0)?number_format($female,1):0) . '%</td></tr>';
            endforeach;
            $subFilledPct = $subApproved > 0 ? number_format(($subFilled/$subApproved)*100, 1) : 0;
            $subVacantPct = $subApproved > 0 ? number_format(($subVacant/$subApproved)*100, 1) : 0;
            $subMalePct = $subFilled > 0 ? number_format(($subMales/$subFilled)*100, 1) : 0;
            $subFemalePct = $subFilled > 0 ? number_format(($subFemales/$subFilled)*100, 1) : 0;
            echo '</tbody><tfoot><tr style="background-color:#e0e0e0; font-weight:bold;"><th>Subtotal</th>';
            if (!empty($showSalaryScale)) echo '<th></th>';
            echo '<th>' . $subApproved . '</th><th>' . $subFilled . '</th><th>' . $subVacant . '</th><th>' . $subExcess . '</th><th>' . $subMales . '</th><th>' . $subFemales . '</th><th>' . $subFilledPct . '%</th><th>' . $subVacantPct . '%</th><th>' . $subMalePct . '%</th><th>' . $subFemalePct . '%</th></tr></tfoot></table>';
          endforeach;
          echo '<table class="items" style="margin-top:0.5em;"><thead><tr><th>TOTALS</th>';
          if (!empty($showSalaryScale)) echo '<th></th>';
          echo '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr></thead><tbody><tr><th>TOTALS</th>';
          if (!empty($showSalaryScale)) echo '<th></th>';
          echo '<th>' . $totalApproved . '</th><th>' . $totalFilled . '</th><th>' . $totalVacant . '</th><th>' . $totalExcess . '</th><th>' . $totalMales . '</th><th>' . $totalFemales . '</th>';
          echo '<th>' . ($totalApproved > 0 ? number_format(($totalFilled/$totalApproved)*100,1) : 0) . '%</th><th>' . ($totalApproved > 0 ? number_format(($totalVacant/$totalApproved)*100,1) : 0) . '%</th>';
          echo '<th>' . ($totalFilled > 0 ? number_format(($totalMales/$totalFilled)*100,1) : 0) . '%</th><th>' . ($totalFilled > 0 ? number_format(($totalFemales/$totalFilled)*100,1) : 0) . '%</th></tr></tbody></table>';
        else: ?>
  <table class="items">
    <thead>
      <tr>
        <th style="width:14%; text-transform: capitalize;"><?php echo $aggTitle; ?></th>
        <?php if (!empty($showSalaryScale)) { ?><th style="width:5%;">Salary</th><?php } ?>
        <th style="width:4%;">Appr</th>
        <th style="width:4%;">Fill</th>
        <th style="width:4%;">Vac</th>
        <th style="width:4%;">Exc</th>
        <th style="width:4%;">M</th>
        <th style="width:4%;">F</th>
        <th style="width:4%;">Fill%</th>
        <th style="width:4%;">Vac%</th>
        <th style="width:4%;">M%</th>
        <th style="width:4%;">F%</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($audit as $row):
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
          <?php if (!empty($showSalaryScale)) { ?><td><?php echo $row->salary_scale; ?></td><?php } ?>
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
        <?php if (!empty($showSalaryScale)) { ?><th></th><?php } ?>
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
