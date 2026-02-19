<?php
require_once 'includes/audit_report_filter.php';
?>
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
    border-bottom: 2px solid #007bff;
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
    color: #007bff;
  }
  .audit-info-item strong {
    margin-right: 8px;
    min-width: 180px;
    color: #212529;
  }
  .audit-legend {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
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

<?php if (!empty($legend)) : ?>
  <div class="audit-legend">
    <h5><i class="fas fa-filter"></i> Active Filters</h5>
    <div style="text-transform: capitalize;"><?php echo $legend; ?></div>
  </div>
<?php endif; ?>

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
      </select>
      <span class="ml-2 mr-3">per page</span>
      <form id="auditExportForm" method="post" action="<?php echo base_url('audit/auditReportExcel'); ?>" target="_blank" class="d-inline">
        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-file-excel mr-1"></i> Export to Excel</button>
      </form>
      <button type="button" id="auditExportPdf" class="btn btn-sm btn-danger ml-2" data-report-url="<?php echo htmlspecialchars(base_url('audit/auditReport')); ?>"><i class="fas fa-file-pdf mr-1"></i> Export PDF</button>
      <button type="button" class="btn btn-tool ml-1" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
    </div>
  </div>
  <div class="card-body">
    <div id="auditTableLoading" class="text-center py-4" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
    <table id="auditReportTable" class="table table-striped table-bordered table-hover audit-table" style="width:100%">
      <thead>
        <tr>
          <th width="25%" class="audit-sort" data-col="0" style="cursor:pointer; text-transform: capitalize;" title="Sort"><?php echo $aggTitle; ?> <i class="fas fa-sort ml-1"></i></th>
          <?php if (($search->aggregate == 'job_name') || ($search->aggregate == '')) { ?><th class="audit-sort" data-col="1" style="cursor:pointer" title="Sort">Salary Scale <i class="fas fa-sort ml-1"></i></th><?php } ?>
          <th class="audit-sort" data-col="<?php echo ($search->aggregate == 'job_name' || $search->aggregate == '') ? 2 : 1; ?>" style="cursor:pointer" title="Sort">Approved <i class="fas fa-sort ml-1"></i></th>
          <th class="audit-sort" data-col="<?php echo ($search->aggregate == 'job_name' || $search->aggregate == '') ? 3 : 2; ?>" style="cursor:pointer" title="Sort">Filled <i class="fas fa-sort ml-1"></i></th>
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
          <?php if (($search->aggregate == 'job_name') || ($search->aggregate == '')) { ?><th></th><?php } ?>
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
  var colOffset = <?php echo ($search->aggregate == 'job_name' || $search->aggregate == '') ? 2 : 1; ?>;
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
    $('#auditTableLoading').show();
    $('#auditReportTable tbody').empty();
    $.ajax({
      url: dataUrl,
      type: 'POST',
      data: getFormPayload(),
      dataType: 'json'
    }).done(function(json) {
      draw++;
      filteredRecords = json.recordsFiltered || 0;
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
      $.each(json.data || [], function(i, row) {
        var tr = '<tr>';
        for (var c = 0; c < row.length; c++) tr += '<td>' + (row[c] !== undefined && row[c] !== null ? escapeHtml(String(row[c])) : '') + '</td>';
        tr += '</tr>';
        $('#auditReportTable tbody').append(tr);
      });
      var from = filteredRecords === 0 ? 0 : page * pageSize + 1;
      var to = Math.min((page + 1) * pageSize, filteredRecords);
      $('#auditTableInfo').text(from + '–' + to + ' of ' + filteredRecords.toLocaleString());
      $('#auditPageNum').text('Page ' + (page + 1) + ' of ' + (Math.ceil(filteredRecords / pageSize) || 1));
      $('#auditPrevPage').prop('disabled', page === 0);
      $('#auditNextPage').prop('disabled', (page + 1) * pageSize >= filteredRecords);
    }).fail(function() {
      $('#auditTableInfo').text('Error loading data');
    }).always(function() {
      $('#auditTableLoading').hide();
    });
  }

  function escapeHtml(s) {
    var div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
  }

  $(function() {
    $('#auditPageSize').on('change', function() {
      pageSize = parseInt($(this).val(), 10);
      page = 0;
      loadTable();
    });
    $('#auditPrevPage').on('click', function() { if (page > 0) { page--; loadTable(); } });
    $('#auditNextPage').on('click', function() { if ((page + 1) * pageSize < filteredRecords) { page++; loadTable(); } });
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
      loadTable();
      return false;
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
  });

  // Export to Excel: copy current filter state from search form so export uses same filters as table
  $(document).on('submit', '#auditExportForm', function() {
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