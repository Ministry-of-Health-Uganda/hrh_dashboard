<?php
// Filters in collapsible panel
?>
<div id="auditFiltersCard" class="card card-default collapsed-card mb-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filters</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" id="auditFiltersToggle">
        <span id="auditFiltersToggleText">Show filters</span>
        <i class="fas fa-plus ml-1"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
<?php require_once 'includes/audit_report_filter.php'; ?>
  </div>
</div>
<style>
  div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
  }
  .dataTables_wrapper .dataTables_processing {
    top: 50%;
    left: 50%;
    margin-left: -100px;
    margin-top: -26px;
  }
  .audit-info-card {
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }
  .audit-info-card h4 {
    color: #495057;
    margin-bottom: 15px;
    font-weight: 600;
    border-bottom: 2px solid #6c757d;
    padding-bottom: 10px;
  }
  .audit-info-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    font-size: 14px;
    color: #495057;
  }
  .audit-info-item i {
    margin-right: 10px;
    font-size: 18px;
    width: 24px;
    text-align: center;
    color: #6c757d;
  }
  .audit-info-item strong {
    margin-right: 8px;
    min-width: 180px;
    color: #212529;
  }
  .audit-legend {
    background: #f8f9fa;
    border-left: 4px solid #6c757d;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
  }
  .audit-legend h5 {
    margin: 0 0 10px 0;
    color: #495057;
    font-weight: 600;
  }
  #auditReportTable {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  #auditReportTable thead th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
  }
  #auditReportTable tbody td {
    text-align: center;
    vertical-align: middle;
  }
  #auditReportTable tfoot th {
    background-color: #6c757d;
    color: white;
    font-weight: 700;
    text-align: center;
  }
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.02);
  }
  .table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
  }
  .audit-table-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.85);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
  }
  .audit-table-overlay-inner {
    text-align: center;
    color: #6c757d;
  }
</style>

<!-- Timestamp Information Card -->
<div class="row">
  <div class="col-12">
    <div class="audit-info-card">
      <h4><i class="fas fa-info-circle"></i> Report Information</h4>
      <div class="row">
        <div class="col-md-6">
          <?php if (!empty($last_staff_update)): 
            try {
              $staff_update_date = new DateTime($last_staff_update);
              $formatted_staff = $staff_update_date->format('F j, Y \a\t g:i A');
            } catch (Exception $e) {
              $formatted_staff = $last_staff_update;
            }
          ?>
          <div class="audit-info-item">
            <i class="fas fa-users"></i>
            <strong>Last Staff Data Update:</strong>
            <span><?php echo $formatted_staff; ?></span>
          </div>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <?php if (!empty($last_audit_generation)): 
            try {
              $audit_gen_date = new DateTime($last_audit_generation);
              $formatted_audit = $audit_gen_date->format('F j, Y \a\t g:i A');
            } catch (Exception $e) {
              $formatted_audit = $last_audit_generation;
            }
          ?>
          <div class="audit-info-item">
            <i class="fas fa-chart-bar"></i>
            <strong>Last Audit Generation:</strong>
            <span><?php echo $formatted_audit; ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="audit-legend">
  <h5><i class="fas fa-filter"></i> Active Filters</h5>
  <div id="auditLegendText"><?php echo isset($legend) ? htmlspecialchars($legend) : ''; ?></div>
</div>

<div class="card card-primary card-outline">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
    <h3 class="card-title mb-0">
      <i class="fas fa-table mr-2"></i>
      Audit Report Data
    </h3>
    <div class="d-flex align-items-center flex-wrap">
      <label class="mb-0 mr-2">Show</label>
      <select id="auditPageSize" class="form-control form-control-sm" style="width: auto;">
        <option value="25" selected>25</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="250">250</option>
        <option value="500">500</option>
        <option value="1000">1000</option>
      </select>
      <span class="ml-2 mr-3">per page</span>
      <form id="auditExportForm" method="post" action="<?php echo base_url('audit/auditReportExcel'); ?>" class="d-inline">
        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-file-excel mr-1"></i> Export to Excel</button>
      </form>
      <form id="auditExportWordForm" method="post" action="<?php echo base_url('audit/auditReportWord'); ?>" class="d-inline ml-2">
        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-file-word mr-1"></i> Export to Word</button>
      </form>
      <button type="button" id="auditExportPdf" class="btn btn-sm btn-danger ml-2" data-report-url="<?php echo htmlspecialchars(base_url('audit/auditReport')); ?>"><i class="fas fa-file-pdf mr-1"></i> Export PDF</button>
      <button type="button" class="btn btn-tool ml-1" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
    </div>
  </div>
  <div class="card-body position-relative">
    <div id="auditTableOverlay" class="audit-table-overlay" style="display: none;">
      <div class="audit-table-overlay-inner">
        <i class="fas fa-spinner fa-spin fa-3x text-secondary mb-2"></i>
        <div>Loading...</div>
      </div>
    </div>
    <?php
      $hasAggregate2 = !empty($search->aggregate); // multiple tables when Section by (top) is set
      $rowsCol = !empty($search->aggregate2) ? $search->aggregate2 : 'job_name';
      $showSalaryScaleCol = ($rowsCol === 'job_name');
      $sideLabel = isset($aggTitle2) ? $aggTitle2 : 'Job';
    ?>
    <div id="auditReportWrapper">
      <div id="auditReportSingleTable" style="<?php echo $hasAggregate2 ? 'display:none;' : ''; ?>">
        <table id="auditReportTable" class="table table-striped table-bordered table-hover audit-table" style="width:100%">
          <thead>
            <tr>
              <th style="width:3%;">#</th>
              <th width="25%" class="audit-sort" data-col="0" style="cursor:pointer; text-transform: capitalize;" title="Sort"><?php echo $aggTitle; ?> <i class="fas fa-sort ml-1"></i></th>
              <?php if ($showSalaryScaleCol) { ?><th class="audit-sort" data-col="1" style="cursor:pointer" title="Sort">Salary Scale <i class="fas fa-sort ml-1"></i></th><?php } ?>
              <th class="audit-sort" data-col="<?php echo $showSalaryScaleCol ? 2 : 1; ?>" style="cursor:pointer" title="Sort">Approved <i class="fas fa-sort ml-1"></i></th>
              <th class="audit-sort" data-col="<?php echo $showSalaryScaleCol ? 3 : 2; ?>" style="cursor:pointer" title="Sort">Filled <i class="fas fa-sort ml-1"></i></th>
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
          <tbody></tbody>
          <tfoot>
            <tr>
              <th></th>
              <th width="25%">TOTALS</th>
              <?php if ($showSalaryScaleCol) { ?><th></th><?php } ?>
              <th id="totalApproved">0</th>
              <th id="totalFilled">0</th>
              <th id="totalVacant">0</th>
              <th id="totalExcess">0</th>
              <th id="totalMales">0</th>
              <th id="totalFemales">0</th>
              <th id="totalFilledPct">0%</th>
              <th id="totalVacantPct">0%</th>
              <th id="totalMalePct">0%</th>
              <th id="totalFemalePct">0%</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div id="auditReportMultiTables" style="<?php echo $hasAggregate2 ? '' : 'display:none;'; ?>"></div>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <div id="auditTableInfo" class="text-muted">0 rows</div>
      <div>
        <button type="button" id="auditPrevPage" class="btn btn-sm btn-outline-secondary" disabled>Previous</button>
        <span id="auditPageNum" class="mx-2">Page 1</span>
        <button type="button" id="auditNextPage" class="btn btn-sm btn-outline-secondary">Next</button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  var dataUrl = "<?php echo base_url('audit/auditReportData'); ?>";
  var chainedFilterUrl = "<?php echo base_url('audit/getChainedFilterOptions'); ?>";
  var hasAggregate2 = <?php echo $hasAggregate2 ? 'true' : 'false'; ?>;
  var showSalaryScaleCol = <?php echo $showSalaryScaleCol ? 'true' : 'false'; ?>;
  var sideLabel = <?php echo json_encode(isset($sideLabel) ? $sideLabel : 'Job'); ?>;
  var colOffset = hasAggregate2 ? (showSalaryScaleCol ? 3 : 2) : (showSalaryScaleCol ? 2 : 1);
  var page = 0;
  var pageSize = 25;
  var sortCol = colOffset;
  var sortDir = 'asc';
  var filteredRecords = 0;
  var draw = 0;

  /** Build POST data for getChainedFilterOptions from current form values */
  function getChainedPostData(level) {
    var regions = $('#filterRegion').val() || [];
    var districts = $('#filterDistrict').val() || [];
    var facilityLevels = $('#filterFacilityLevel').val() || [];
    var facilities = $('#filterFacility').val() || [];
    var cadres = $('#filterJobCadre').val() || [];
    var jobCategories = $('#filterJobCategory').val() || [];
    var jobClasses = $('#filterJobClassification').val() || [];
    var jobNames = $('#filterJobName').val() || [];
    var data = { level: level };
    if (regions.length) data['region[]'] = regions;
    if (districts.length) data['district[]'] = districts;
    if (facilityLevels.length) data['facility_type[]'] = facilityLevels;
    if (facilities.length) data['facility[]'] = facilities;
    if (cadres.length) data['cadre[]'] = cadres;
    if (jobCategories.length) data['job_category[]'] = jobCategories;
    if (jobClasses.length) data['job_class[]'] = jobClasses;
    if (jobNames.length) data['job[]'] = jobNames;
    return data;
  }

  /** Fetch chained options and repopulate a select. valueKey/labelKey: property name in API response objects (e.g. "district", "facility_type", "facility"). */
  function refreshChainedSelect(level, selectId, valueKey, labelKey, thenReload) {
    var $sel = $('#' + selectId);
    if (!$sel.length) return $.when();
    var payload = getChainedPostData(level);
    return $.ajax({
      url: chainedFilterUrl,
      type: 'POST',
      data: payload,
      dataType: 'json'
    }).done(function(list) {
      var currentVal = $sel.val();
      $sel.empty();
      if (list && list.length) {
        $.each(list, function(i, row) {
          var val = row[valueKey] !== undefined ? row[valueKey] : (row[labelKey] || '');
          if (val === '' || val == null) return;
          var label = row[labelKey] !== undefined ? row[labelKey] : (row[valueKey] || String(val));
          $sel.append($('<option>').val(String(val)).text(String(label)));
        });
      }
      var newVal = currentVal && $.isArray(currentVal) ? currentVal.filter(function(v) { return $sel.find('option[value="' + String(v).replace(/"/g, '&quot;') + '"]').length; }) : (($sel.find('option[value="' + String(currentVal).replace(/"/g, '&quot;') + '"]').length) ? currentVal : null);
      $sel.val(newVal || null).trigger('change.select2');
      if (thenReload && typeof loadTable === 'function') loadTable();
    });
  }

  function getFormPayload() {
    var sectionSet = ($('select[name="aggregate"]').val() || '').toString().trim() !== '';
    var effectivePageSize = sectionSet ? 1000 : pageSize;
    var d = { start: page * effectivePageSize, length: effectivePageSize, draw: draw };
    d.order = [{ column: sortCol, dir: sortDir }];
    d.search = { value: '' };
    var formData = $('.searchForm').serializeArray();
    $.each(formData, function(i, field) {
      if (field.name.indexOf('[]') !== -1) {
        var name = field.name.replace('[]', '');
        if (!d[name]) d[name] = [];
        if (field.value) d[name].push(field.value);
      } else {
        d[field.name] = field.value;
      }
    });
    return d;
  }

  function loadTable() {
    $('#auditTableOverlay').show();
    $('#auditReportTable tbody').empty();
    $.ajax({
      url: dataUrl,
      type: 'POST',
      data: getFormPayload(),
      dataType: 'json'
    }).done(function(json) {
      draw++;
      filteredRecords = json.recordsFiltered || 0;
      if (json.legend !== undefined) {
        var $leg = $('#auditLegendText');
        if ($leg.length) $leg.text(json.legend);
      }
      if (json.totals) {
        $('#totalApproved').text(json.totals.totalApproved.toLocaleString());
        $('#totalFilled').text(json.totals.totalFilled.toLocaleString());
        $('#totalVacant').text(json.totals.totalVacant.toLocaleString());
        $('#totalExcess').text(json.totals.totalExcess.toLocaleString());
        $('#totalMales').text(json.totals.totalMale.toLocaleString());
        $('#totalFemales').text(json.totals.totalFemale.toLocaleString());
        $('#totalFilledPct').text(json.totals.filledPct + '%');
        $('#totalVacantPct').text(json.totals.vacantPct + '%');
        $('#totalMalePct').text(json.totals.malePct + '%');
        $('#totalFemalePct').text(json.totals.femalePct + '%');
      }
      // Use current form selection: Section by (top) set = multiple tables (one per section value)
      var sectionVal = $('select[name="aggregate"]').val();
      var useMultiTable = (sectionVal !== undefined && sectionVal !== null && String(sectionVal).trim() !== '');
      var rowsVal = $('select[name="aggregate2"]').val();
      var showSalary = (rowsVal === 'job_name' || rowsVal === '' || rowsVal === undefined);
      var sideLbl = sideLabel;
      var $agg2Sel = $('select[name="aggregate2"] option:selected');
      if ($agg2Sel.length && $agg2Sel.text()) sideLbl = $agg2Sel.text();
      if (useMultiTable && (json.data || []).length) {
        $('#auditReportSingleTable').hide();
        var $multi = $('#auditReportMultiTables').empty().show();
        var groups = {};
        $.each(json.data, function(i, row) {
          var key = row[0] !== undefined && row[0] !== null ? String(row[0]) : '';
          if (!groups[key]) groups[key] = [];
          groups[key].push(row);
        });
        var dataOffset = showSalary ? 3 : 2; // first numeric column index (approved)
        var headerRow = '<tr><th>#</th><th>' + escapeHtml(sideLbl) + '</th>' + (showSalary ? '<th>Salary Scale</th>' : '') + '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr>';
        $.each(groups, function(sectionName, rows) {
          var sub = { approved: 0, filled: 0, vacant: 0, excess: 0, male: 0, female: 0 };
          $.each(rows, function(j, row) {
            sub.approved += parseFloat(row[dataOffset]) || 0;
            sub.filled   += parseFloat(row[dataOffset + 1]) || 0;
            sub.vacant   += parseFloat(row[dataOffset + 2]) || 0;
            sub.excess   += parseFloat(row[dataOffset + 3]) || 0;
            sub.male     += parseFloat(row[dataOffset + 4]) || 0;
            sub.female   += parseFloat(row[dataOffset + 5]) || 0;
          });
          var subFilledPct = sub.approved > 0 ? (100 * sub.filled / sub.approved).toFixed(1) : 0;
          var subVacantPct = sub.approved > 0 ? (100 * sub.vacant / sub.approved).toFixed(1) : 0;
          var subMalePct   = sub.filled > 0 ? (100 * sub.male / sub.filled).toFixed(1) : 0;
          var subFemalePct = sub.filled > 0 ? (100 * sub.female / sub.filled).toFixed(1) : 0;
          var subRow = '<tr class="table-secondary font-weight-bold"><th></th><th>Subtotal</th>' + (showSalary ? '<th></th>' : '') + '<th>' + sub.approved + '</th><th>' + sub.filled + '</th><th>' + sub.vacant + '</th><th>' + sub.excess + '</th><th>' + sub.male + '</th><th>' + sub.female + '</th><th>' + subFilledPct + '%</th><th>' + subVacantPct + '%</th><th>' + subMalePct + '%</th><th>' + subFemalePct + '%</th></tr>';
          $multi.append('<div class="audit-section mb-4"><h5 class="audit-section-heading font-weight-bold mb-2">' + escapeHtml(sectionName) + '</h5></div>');
          var $last = $multi.children('.audit-section').last();
          var tbl = '<table class="table table-striped table-bordered table-sm audit-table" style="width:100%"><thead>' + headerRow + '</thead><tbody>';
          $.each(rows, function(j, row) {
            tbl += '<tr><td>' + (j + 1) + '</td>';
            tbl += '<td>' + (row[1] !== undefined && row[1] !== null ? escapeHtml(String(row[1])) : '') + '</td>';
            for (var c = 2; c < row.length; c++) tbl += '<td>' + (row[c] !== undefined && row[c] !== null ? escapeHtml(String(row[c])) : '') + '</td>';
            tbl += '</tr>';
          });
          tbl += '</tbody><tfoot>' + subRow + '</tfoot></table>';
          $last.append(tbl);
        });
        if (json.totals) {
          var t = json.totals;
          var totRow = '<tr><th></th><th>TOTALS</th>' + (showSalary ? '<th></th>' : '') + '<th>' + (t.totalApproved || 0) + '</th><th>' + (t.totalFilled || 0) + '</th><th>' + (t.totalVacant || 0) + '</th><th>' + (t.totalExcess || 0) + '</th><th>' + (t.totalMale || 0) + '</th><th>' + (t.totalFemale || 0) + '</th><th>' + (t.filledPct || 0) + '%</th><th>' + (t.vacantPct || 0) + '%</th><th>' + (t.malePct || 0) + '%</th><th>' + (t.femalePct || 0) + '%</th></tr>';
          $multi.append('<div class="audit-section mt-2"><table class="table table-bordered table-sm"><thead><tr><th>#</th><th>TOTALS</th>' + (showSalary ? '<th></th>' : '') + '<th>Approved</th><th>Filled</th><th>Vacant</th><th>Excess</th><th>Male</th><th>Female</th><th>Filled %</th><th>Vacant %</th><th>Male %</th><th>Female %</th></tr></thead><tbody>' + totRow + '</tbody></table></div>');
        }
      } else {
        $('#auditReportMultiTables').hide().empty();
        $('#auditReportSingleTable').show();
        $('#auditReportTable tbody').empty();
        $.each(json.data || [], function(i, row) {
          var tr = '<tr><td>' + (i + 1) + '</td>';
          for (var c = 0; c < row.length; c++) tr += '<td>' + (row[c] !== undefined && row[c] !== null ? escapeHtml(String(row[c])) : '') + '</td>';
          tr += '</tr>';
          $('#auditReportTable tbody').append(tr);
        });
      }
      var sectionSet = ($('select[name="aggregate"]').val() || '').toString().trim() !== '';
      var effectivePageSize = sectionSet ? 1000 : pageSize;
      var from = filteredRecords === 0 ? 0 : page * effectivePageSize + 1;
      var to = Math.min((page + 1) * effectivePageSize, filteredRecords);
      $('#auditTableInfo').text(from + '–' + to + ' of ' + filteredRecords.toLocaleString());
      $('#auditPageNum').text('Page ' + (page + 1) + ' of ' + (Math.ceil(filteredRecords / effectivePageSize) || 1));
      $('#auditPrevPage').prop('disabled', page === 0);
      $('#auditNextPage').prop('disabled', (page + 1) * effectivePageSize >= filteredRecords);
    }).fail(function() {
      $('#auditTableInfo').text('Error loading data');
    }).always(function() {
      $('#auditTableOverlay').hide();
    });
  }

  function updateFiltersToggleText() {
    var $card = $('#auditFiltersCard');
    var $text = $('#auditFiltersToggleText');
    $text.text($card.hasClass('collapsed-card') ? 'Show filters' : 'Hide filters');
  }

  function escapeHtml(s) {
    var div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
  }

  $(function() {
    updateFiltersToggleText();
    $(document).on('expanded.lte.cardwidget collapsed.lte.cardwidget', function() {
      updateFiltersToggleText();
    });
    $('#auditFiltersToggle').on('click', function() {
      setTimeout(updateFiltersToggleText, 150);
    });
    $('#auditPageSize').on('change', function() {
      pageSize = parseInt($(this).val(), 10);
      page = 0;
      loadTable();
    });
    $('#auditPrevPage').on('click', function() { if (page > 0) { page--; loadTable(); } });
    $('#auditNextPage').on('click', function() {
      var sectionSet = ($('select[name="aggregate"]').val() || '').toString().trim() !== '';
      var eff = sectionSet ? 1000 : pageSize;
      if ((page + 1) * eff < filteredRecords) { page++; loadTable(); }
    });
    $(document).on('click', '.audit-sort', function() {
      var col = parseInt($(this).data('col'), 10);
      if (sortCol === col) sortDir = sortDir === 'asc' ? 'desc' : 'asc';
      else { sortCol = col; sortDir = 'asc'; }
      page = 0;
      loadTable();
    });
    $('.searchForm').on('submit', function(e) {
      if ($('#print').val() == '1') return;
      e.preventDefault();
      page = 0;
      if (($('select[name="aggregate"]').val() || '').toString().trim() !== '') {
        $('#auditPageSize').val(1000);
        pageSize = 1000;
      }
      loadTable();
      return false;
    });
    $('select[name="aggregate"]').on('change', function() {
      if (($(this).val() || '').toString().trim() !== '') {
        $('#auditPageSize').val(1000);
        pageSize = 1000;
        page = 0;
        loadTable();
      }
    });
    loadTable();
    // Export PDF: submit search form with current filters so PDF uses same filters as table
    $('#auditExportPdf').on('click', function() {
      var reportUrl = $(this).data('report-url');
      if (reportUrl) $('.searchForm').attr('action', reportUrl);
      $('#print').val(1);
      $('.searchForm').attr('target', '_blank').submit();
      setTimeout(function() { $('#print').val(0); $('.searchForm').removeAttr('target'); }, 500);
    });
    $('select[name="job_category[]"], select[name="job_class[]"], select[name="district[]"], select[name="institution[]"], select[name="region[]"], select[name="facility_type[]"], select[name="facility[]"], select[name="cadre[]"], select[name="job[]"]').select2({
      placeholder: function() { return $(this).data('placeholder') || 'All'; },
      allowClear: true
    });

    // Chained filters: when region/district/facility_level change, refresh dependent dropdowns via AJAX
    $('#filterRegion').on('change', function() {
      refreshChainedSelect('district', 'filterDistrict', 'district', 'district').done(function() {
        refreshChainedSelect('facility_level', 'filterFacilityLevel', 'facility_type', 'facility_type');
        refreshChainedSelect('facility', 'filterFacility', 'facility', 'facility');
      });
    });
    $('#filterDistrict').on('change', function() {
      refreshChainedSelect('facility_level', 'filterFacilityLevel', 'facility_type', 'facility_type');
      refreshChainedSelect('facility', 'filterFacility', 'facility', 'facility');
    });
    $('#filterFacilityLevel').on('change', function() {
      refreshChainedSelect('facility', 'filterFacility', 'facility', 'facility');
    });
    // When facility changes: refresh Facility Level (levels for selected facilities, or all levels if none selected)
    $('#filterFacility').on('change', function() {
      refreshChainedSelect('facility_level', 'filterFacilityLevel', 'facility_type', 'facility_type');
    });
  });

  // Export to Excel/Word: copy current filter state from search form so export uses same filters as table
  $(document).on('submit', '#auditExportForm, #auditExportWordForm', function() {
    var $form = $(this);
    $form.find('input[type="hidden"]').remove();
    var formData = $('.searchForm').serializeArray();
    $.each(formData, function(i, field) {
      if (field.name === 'getPdf') return;
      $form.append($('<input type="hidden">').attr('name', field.name).val(field.value));
    });
  });
})();
</script>