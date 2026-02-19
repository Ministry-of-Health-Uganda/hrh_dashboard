<?php
// Facility audit: same UX as main audit report (AJAX table, collapsible filters, legend). URL preserves display & districts for embedding.
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
<?php require_once 'includes/audit_report_filter_fac.php'; ?>
  </div>
</div>

<style>
  div.dataTables_wrapper div.dataTables_filter { text-align: right; }
  .audit-info-card {
    background: #fff; border: 1px solid #dee2e6; border-radius: 4px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  .audit-info-card h4 { color: #495057; margin-bottom: 15px; font-weight: 600; border-bottom: 2px solid #6c757d; padding-bottom: 10px; }
  .audit-info-item { display: flex; align-items: center; margin-bottom: 10px; font-size: 14px; color: #495057; }
  .audit-info-item i { margin-right: 10px; font-size: 18px; width: 24px; text-align: center; color: #6c757d; }
  .audit-info-item strong { margin-right: 8px; min-width: 180px; color: #212529; }
  .audit-legend { background: #f8f9fa; border-left: 4px solid #6c757d; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
  .audit-legend h5 { margin: 0 0 10px 0; color: #495057; font-weight: 600; }
  #facAuditReportTable thead th { background-color: #343a40; color: white; font-weight: 600; text-align: center; vertical-align: middle; }
  #facAuditReportTable tbody td { text-align: center; vertical-align: middle; }
  #facAuditReportTable tfoot th { background-color: #6c757d; color: white; font-weight: 700; text-align: center; }
  .audit-table-overlay {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.85); z-index: 10;
    display: flex; align-items: center; justify-content: center; border-radius: 4px;
  }
  .audit-table-overlay-inner { text-align: center; color: #6c757d; }
</style>

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
  <div id="facAuditLegendText"><?php echo isset($legend) ? htmlspecialchars($legend) : ''; ?></div>
</div>

<div class="card card-primary card-outline">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
    <h3 class="card-title mb-0"><i class="fas fa-table mr-2"></i>Facility Audit Report Data</h3>
    <div class="d-flex align-items-center flex-wrap">
      <label class="mb-0 mr-2">Show</label>
      <select id="facAuditPageSize" class="form-control form-control-sm" style="width: auto;">
        <option value="25" selected>25</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="250">250</option>
        <option value="500">500</option>
      </select>
      <span class="ml-2 mr-3">per page</span>
      <form id="facAuditExportForm" method="post" action="<?php echo base_url('audit/auditReportExcel'); echo !empty($ajax_query) ? '?' . $ajax_query : ''; ?>" target="_blank" class="d-inline">
        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-file-excel mr-1"></i> Export to Excel</button>
      </form>
      <button type="button" id="facAuditExportPdf" class="btn btn-sm btn-danger ml-2" data-report-url="<?php echo htmlspecialchars(base_url('audit/printfacAudit') . (!empty($ajax_query) ? '?' . $ajax_query : '')); ?>"><i class="fas fa-file-pdf mr-1"></i> Export PDF</button>
      <button type="button" class="btn btn-tool ml-1" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
    </div>
  </div>
  <div class="card-body position-relative">
    <div id="facAuditTableOverlay" class="audit-table-overlay" style="display: none;">
      <div class="audit-table-overlay-inner">
        <i class="fas fa-spinner fa-spin fa-3x text-secondary mb-2"></i>
        <div>Loading...</div>
      </div>
    </div>
    <table id="facAuditReportTable" class="table table-striped table-bordered table-hover" style="width:100%">
      <thead>
        <tr>
          <th width="25%" class="audit-sort" data-col="0" style="cursor:pointer; text-transform: capitalize;" title="Sort"><?php echo isset($aggTitle) ? htmlspecialchars($aggTitle) : 'Job'; ?> <i class="fas fa-sort ml-1"></i></th>
          <?php if (empty($search->aggregate) || $search->aggregate === 'job_name') { ?><th class="audit-sort" data-col="1" style="cursor:pointer" title="Sort">Salary Scale <i class="fas fa-sort ml-1"></i></th><?php } ?>
          <th class="audit-sort" data-col="<?php echo (empty($search->aggregate) || $search->aggregate === 'job_name') ? 2 : 1; ?>" style="cursor:pointer" title="Sort">Approved <i class="fas fa-sort ml-1"></i></th>
          <th class="audit-sort" data-col="<?php echo (empty($search->aggregate) || $search->aggregate === 'job_name') ? 3 : 2; ?>" style="cursor:pointer" title="Sort">Filled <i class="fas fa-sort ml-1"></i></th>
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
          <th width="25%">TOTALS</th>
          <?php if (empty($search->aggregate) || $search->aggregate === 'job_name') { ?><th></th><?php } ?>
          <th id="facTotalApproved">0</th>
          <th id="facTotalFilled">0</th>
          <th id="facTotalVacant">0</th>
          <th id="facTotalExcess">0</th>
          <th id="facTotalMales">0</th>
          <th id="facTotalFemales">0</th>
          <th id="facTotalFilledPct">0%</th>
          <th id="facTotalVacantPct">0%</th>
          <th id="facTotalMalePct">0%</th>
          <th id="facTotalFemalePct">0%</th>
        </tr>
      </tfoot>
    </table>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <div id="facAuditTableInfo" class="text-muted">0 rows</div>
      <div>
        <button type="button" id="facAuditPrevPage" class="btn btn-sm btn-outline-secondary" disabled>Previous</button>
        <span id="facAuditPageNum" class="mx-2">Page 1</span>
        <button type="button" id="facAuditNextPage" class="btn btn-sm btn-outline-secondary">Next</button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  var dataUrl = "<?php echo base_url('audit/auditReportData'); echo !empty($ajax_query) ? '?' . $ajax_query : ''; ?>";
  var colOffset = <?php echo (empty($search->aggregate) || $search->aggregate === 'job_name') ? 2 : 1; ?>;
  var page = 0;
  var pageSize = 25;
  var sortCol = colOffset;
  var sortDir = 'asc';
  var filteredRecords = 0;
  var draw = 0;

  function getFormPayload() {
    var d = { start: page * pageSize, length: pageSize, draw: draw };
    d.order = [{ column: sortCol, dir: sortDir }];
    d.search = { value: '' };
    var formData = $('.searchForm').serializeArray();
    $.each(formData, function(i, field) {
      if (field.name === 'getPdf') return;
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
    $('#facAuditTableOverlay').show();
    $('#facAuditReportTable tbody').empty();
    $.ajax({
      url: dataUrl,
      type: 'POST',
      data: getFormPayload(),
      dataType: 'json'
    }).done(function(json) {
      draw++;
      filteredRecords = json.recordsFiltered || 0;
      if (json.legend !== undefined) {
        var $leg = $('#facAuditLegendText');
        if ($leg.length) $leg.text(json.legend);
      }
      if (json.totals) {
        $('#facTotalApproved').text(json.totals.totalApproved.toLocaleString());
        $('#facTotalFilled').text(json.totals.totalFilled.toLocaleString());
        $('#facTotalVacant').text(json.totals.totalVacant.toLocaleString());
        $('#facTotalExcess').text(json.totals.totalExcess.toLocaleString());
        $('#facTotalMales').text(json.totals.totalMale.toLocaleString());
        $('#facTotalFemales').text(json.totals.totalFemale.toLocaleString());
        $('#facTotalFilledPct').text(json.totals.filledPct + '%');
        $('#facTotalVacantPct').text(json.totals.vacantPct + '%');
        $('#facTotalMalePct').text(json.totals.malePct + '%');
        $('#facTotalFemalePct').text(json.totals.femalePct + '%');
      }
      $.each(json.data || [], function(i, row) {
        var tr = '<tr>';
        for (var c = 0; c < row.length; c++) tr += '<td>' + (row[c] !== undefined && row[c] !== null ? escapeHtml(String(row[c])) : '') + '</td>';
        tr += '</tr>';
        $('#facAuditReportTable tbody').append(tr);
      });
      var from = filteredRecords === 0 ? 0 : page * pageSize + 1;
      var to = Math.min((page + 1) * pageSize, filteredRecords);
      $('#facAuditTableInfo').text(from + '–' + to + ' of ' + filteredRecords.toLocaleString());
      $('#facAuditPageNum').text('Page ' + (page + 1) + ' of ' + (Math.ceil(filteredRecords / pageSize) || 1));
      $('#facAuditPrevPage').prop('disabled', page === 0);
      $('#facAuditNextPage').prop('disabled', (page + 1) * pageSize >= filteredRecords);
    }).fail(function() {
      $('#facAuditTableInfo').text('Error loading data');
    }).always(function() {
      $('#facAuditTableOverlay').hide();
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
    $('#facAuditPageSize').on('change', function() {
      pageSize = parseInt($(this).val(), 10);
      page = 0;
      loadTable();
    });
    $('#facAuditPrevPage').on('click', function() { if (page > 0) { page--; loadTable(); } });
    $('#facAuditNextPage').on('click', function() { if ((page + 1) * pageSize < filteredRecords) { page++; loadTable(); } });
    $(document).on('click', '#facAuditReportTable .audit-sort', function() {
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
      loadTable();
      return false;
    });
    loadTable();

    $('#facAuditExportPdf').on('click', function() {
      var reportUrl = $(this).data('report-url');
      if (reportUrl) $('.searchForm').attr('action', reportUrl);
      $('#print').val(1);
      $('.searchForm').attr('target', '_blank').submit();
      setTimeout(function() { $('#print').val(0); $('.searchForm').removeAttr('target'); }, 500);
    });

    $(document).on('submit', '#facAuditExportForm', function() {
      var $form = $(this);
      $form.find('input[type="hidden"]').remove();
      var formData = $('.searchForm').serializeArray();
      $.each(formData, function(i, field) {
        if (field.name === 'getPdf') return;
        $form.append($('<input type="hidden">').attr('name', field.name).val(field.value));
      });
    });
  });
})();
</script>
