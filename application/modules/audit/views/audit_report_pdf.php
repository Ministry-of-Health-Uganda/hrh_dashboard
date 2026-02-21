<!DOCTYPE html>
<html>
<head>
<style>

body { font-family: Arial; font-size: 9pt; max-width: 22cm; max-height: 29.7cm; }
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

.text-success { color: green; }
.text-bold { font-weight: bold; }

</style>
</head>
<body>

  <table>
      <tr>
        <td><img src="<?php echo base_url(); ?>assets/images/MOH.png" width="48" height="48" style="width:48px; height:48px; object-fit:contain;" alt="Logo" /></td>
        <td>
            <br/>
            <h2> Staff Audit Report</h2>
            <?php if(!empty($legend)): ?>
                <br/>
                <p style="text-transform: capitalize; font-weight: bold;"><?php echo $legend; ?></p>
            <?php endif; ?>
        </td>
      </tr>
  </table>
  <hr />
  <br/>
  <?php $hasAgg2 = !empty($search->aggregate); // multiple tables when Section (top) is set
        $totalApproved = 0; $totalFilled = 0; $totalVacant = 0; $totalExcess = 0; $totalMales = 0; $totalFemales = 0;
        if ($hasAgg2):
          $groups = array();
          foreach ($audit as $row) {
            $k = $row->$aggColumn;
            if (!isset($groups[$k])) $groups[$k] = array();
            $groups[$k][] = $row;
          }
          $tableFont = '8pt';
          $sideLabel = isset($aggTitle2) ? $aggTitle2 : 'Job';
          foreach ($groups as $sectionVal => $rows):
            $subApproved = 0; $subFilled = 0; $subVacant = 0; $subExcess = 0; $subMales = 0; $subFemales = 0;
            echo '<p style="font-weight:bold; margin-top:1em; font-size:9pt;">' . htmlspecialchars($sectionVal) . '</p>';
            echo '<table class="items" style="font-size:' . $tableFont . '; border-collapse: collapse; margin-bottom: 0.8em;" cellpadding="4" width="100%"><thead><tr>';
            echo '<th style="width:3.5em;">#</th><th width="25%" style="text-transform: capitalize;">' . htmlspecialchars($sideLabel) . '</th>';
            if (!empty($showSalaryScale)) echo '<th>Salary Scale</th>';
            echo '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr></thead><tbody>';
            foreach ($rows as $idx => $row):
              $structure = $row->approved;
              $vacantPosts = ($row->approved >= $row->filled) ? ((int)$row->approved - (int)$row->filled) : 0;
              $excessPosts = ($row->filled > $row->approved) ? ((int)$row->filled - (int)$row->approved) : 0;
              $male = ($structure > 0 && $row->filled > 0) ? ($row->male/$row->filled)*100 : 0;
              $female = ($structure > 0 && $row->filled > 0) ? ($row->female/$row->filled)*100 : 0;
              $vacant = ($structure > 0) ? ($vacantPosts/$structure)*100 : 0;
              $filled = ($structure > 0) ? ($row->filled/$structure)*100 : 0;
              $subApproved += $structure; $subFilled += $row->filled; $subVacant += $vacantPosts; $subExcess += $excessPosts; $subMales += $row->male; $subFemales += $row->female;
              $totalApproved += $structure; $totalFilled += $row->filled; $totalVacant += $vacantPosts; $totalExcess += $excessPosts; $totalFemales += $row->female; $totalMales += $row->male;
              echo '<tr><td>' . ($idx + 1) . '</td><td>' . htmlspecialchars($row->$aggColumn2) . '</td>';
              if (!empty($showSalaryScale)) echo '<td>' . htmlspecialchars($row->salary_scale) . '</td>';
              echo '<td>' . $row->approved . '</td><td>' . $row->filled . '</td><td>' . $vacantPosts . '</td><td>' . $excessPosts . '</td><td>' . $row->male . '</td><td>' . $row->female . '</td>';
              echo '<td class="text-bold">' . (($filled>0)?number_format($filled,1):0) . '%</td><td class="text-bold">' . (($vacant>0)?number_format($vacant,1):0) . '%</td><td class="text-bold">' . (($male>0)?number_format($male,1):0) . '%</td><td class="text-bold">' . (($female>0)?number_format($female,1):0) . '%</td></tr>';
            endforeach;
            $subFilledPct = $subApproved > 0 ? number_format(($subFilled/$subApproved)*100, 1) : 0;
            $subVacantPct = $subApproved > 0 ? number_format(($subVacant/$subApproved)*100, 1) : 0;
            $subMalePct = $subFilled > 0 ? number_format(($subMales/$subFilled)*100, 1) : 0;
            $subFemalePct = $subFilled > 0 ? number_format(($subFemales/$subFilled)*100, 1) : 0;
            echo '</tbody><tfoot><tr style="background-color:#e0e0e0; font-weight:bold;"><th></th><th>Subtotal</th>';
            if (!empty($showSalaryScale)) echo '<th></th>';
            echo '<th>' . $subApproved . '</th><th>' . $subFilled . '</th><th>' . $subVacant . '</th><th>' . $subExcess . '</th><th>' . $subMales . '</th><th>' . $subFemales . '</th><th>' . $subFilledPct . '%</th><th>' . $subVacantPct . '%</th><th>' . $subMalePct . '%</th><th>' . $subFemalePct . '%</th></tr></tfoot></table>';
          endforeach;
          echo '<table class="items" style="font-size:' . $tableFont . '; border-collapse: collapse;" cellpadding="4" width="100%"><thead><tr><th style="width:3.5em;">#</th><th>TOTALS</th>';
          if (!empty($showSalaryScale)) echo '<th></th>';
          echo '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr></thead><tbody><tr><th></th><th>TOTALS</th>';
          if (!empty($showSalaryScale)) echo '<th></th>';
          echo '<th>' . $totalApproved . '</th><th>' . $totalFilled . '</th><th>' . $totalVacant . '</th><th>' . $totalExcess . '</th><th>' . $totalMales . '</th><th>' . $totalFemales . '</th>';
          echo '<th>' . ($totalApproved > 0 ? number_format(($totalFilled/$totalApproved)*100,1) : 0) . '%</th><th>' . ($totalApproved > 0 ? number_format(($totalVacant/$totalApproved)*100,1) : 0) . '%</th>';
          echo '<th>' . ($totalFilled > 0 ? number_format(($totalMales/$totalFilled)*100,1) : 0) . '%</th><th>' . ($totalFilled > 0 ? number_format(($totalFemales/$totalFilled)*100,1) : 0) . '%</th></tr></tbody></table>';
        else: ?>
  <table class="items" style="font-size: 8pt; border-collapse: collapse;" cellpadding="4" width="100%">
    <thead>
        <tr>
            <th style="width:3.5em;">#</th>
            <th width="25%" style="text-transform: capitalize;"><?php echo $aggTitle; ?></th>
            <?php if (!empty($showSalaryScale)) { ?><th>Salary Scale</th><?php } ?>
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
            $rowNum = 0;
            foreach($audit as $row):
              $rowNum++;
              $structure    = $row->approved;
              $vacantPosts  = ($row->approved >= $row->filled) ? ((int)$row->approved - (int)$row->filled) : 0;
              $excessPosts  = ($row->filled > $row->approved) ? ((int)$row->filled - (int)$row->approved) : 0;

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
            <td><?php echo $rowNum; ?></td>
            <td><?php echo $row->$aggColumn; ?></td>
            <?php if (!empty($showSalaryScale)) { ?><td><?php echo $row->salary_scale; ?></td><?php } ?>
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

    <?php endforeach; endif; ?>
    
      </tbody>
      
      <tfoot>
          <tr>
            <th></th>
            <th width="25%">TOTALS</th>
            <?php if (!empty($showSalaryScale)) { ?><th></th><?php } ?>
            <th><?php echo $totalApproved; ?></th>
            <th><?php echo $totalFilled; ?></th>
            <th><?php echo $totalVacant; ?></th>
            <th><?php echo $totalExcess; ?></th>
            <th><?php echo $totalMales; ?></th>
            <th><?php echo $totalFemales; ?></th>
            <th>
              <?php echo ($totalApproved > 0) ? number_format(($totalFilled/$totalApproved)*100,1) : 0; ?>%   
              </th>
              <th>
              <?php echo ($totalApproved > 0) ? number_format(($totalVacant/$totalApproved)*100,1) : 0; ?>%   
              </th>
              <th>
              <?php echo ($totalFilled > 0) ? number_format(($totalMales/$totalFilled)*100,1) : 0; ?>%   
              </th>
            <th>
              <?php echo ($totalFilled > 0) ? number_format(($totalFemales/$totalFilled)*100,1) : 0; ?>%   
              </th>
        </tr>
          
      </tfoot>
  </table>
</body>
</html>