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
</style>

<hr />
<br />

<?php if (!empty($legend)) : ?>
  <div class="form-group">
    <h5 style="text-transform: capitalize;"><?php echo $legend; ?></h5>
  </div>
  <hr />
<?php endif; ?>

<table id="auditReportTable" class="table table-striped table-bordered audit-table" style="width:100%">
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
                // Calculate totals from all data (you may need to fetch totals separately)
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