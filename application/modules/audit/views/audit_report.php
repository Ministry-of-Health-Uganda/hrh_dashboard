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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  .audit-info-card h4 {
    color: white;
    margin-bottom: 15px;
    font-weight: 600;
  }
  .audit-info-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    font-size: 14px;
  }
  .audit-info-item i {
    margin-right: 10px;
    font-size: 18px;
    width: 24px;
    text-align: center;
  }
  .audit-info-item strong {
    margin-right: 8px;
    min-width: 180px;
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
  <div class="card-header">
    <h3 class="card-title">
      <i class="fas fa-table mr-2"></i>
      Audit Report Data
    </h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <table id="auditReportTable" class="table table-striped table-bordered table-hover audit-table" style="width:100%">
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
  <tfoot>
    <tr>
      <th width="25%">TOTALS</th>
      <?php if (($search->aggregate  == 'job_name') || ($search->aggregate  == '')) { ?><th></th> <?php } ?>
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
</div>

<script>
$(document).ready(function() {
    var table = $('#auditReportTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('audit/auditReportData'); ?>",
            "type": "POST",
            "data": function(d) {
                // Add form filter data to the request
                var formData = $('.searchForm').serializeArray();
                $.each(formData, function(i, field) {
                    if (field.name.indexOf('[]') !== -1) {
                        // Handle array fields
                        var name = field.name.replace('[]', '');
                        if (!d[name]) {
                            d[name] = [];
                        }
                        if (field.value) {
                            d[name].push(field.value);
                        }
                    } else {
                        d[field.name] = field.value;
                    }
                });
            },
            "dataSrc": function(json) {
                // Update footer totals with data from all filtered records
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
                return json.data;
            }
        },
        "columns": [
            { "data": 0 }<?php 
            $colOffset = 1;
            if (($search->aggregate  == 'job_name') || ($search->aggregate  == '')) { 
                echo ', { "data": 1 }';
                $colOffset = 2;
            } 
            ?>,
            { "data": <?php echo $colOffset; ?> },
            { "data": <?php echo $colOffset + 1; ?> },
            { "data": <?php echo $colOffset + 2; ?> },
            { "data": <?php echo $colOffset + 3; ?> },
            { "data": <?php echo $colOffset + 4; ?> },
            { "data": <?php echo $colOffset + 5; ?> },
            { "data": <?php echo $colOffset + 6; ?> },
            { "data": <?php echo $colOffset + 7; ?> },
            { "data": <?php echo $colOffset + 8; ?> },
            { "data": <?php echo $colOffset + 9; ?> }
        ],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[<?php echo $colOffset; ?>, "asc"]],
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "footerCallback": function (row, data, start, end, display) {
            // This function can be used to calculate footer totals if needed
            // For now, totals will be calculated server-side if needed
        }
    });
    
    // Reload table when form is submitted (but not for PDF download)
    $('.searchForm').on('submit', function(e) {
        var isPdf = $('#print').val() == '1';
        if (!isPdf) {
            e.preventDefault();
            table.ajax.reload();
            return false;
        }
    });
    
    // Initialize Select2 for multiple selects
    $('select[name="job_category[]"], select[name="job_class[]"]').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true
    });
});
</script>