 <!-- Main content -->
 <section class="content" ng-controller="DashboardCtrl">
      <div class="container-fluid">
        <!-- Last Update Alert -->
        <?php if (!empty($last_update)): 
          try {
            $last_update_date = new DateTime($last_update);
            $now = new DateTime();
            $interval = $now->diff($last_update_date);
            
            $time_ago = '';
            if ($interval->days > 0) {
              $time_ago = $interval->days . ' day' . ($interval->days > 1 ? 's' : '') . ' ago';
            } elseif ($interval->h > 0) {
              $time_ago = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
            } elseif ($interval->i > 0) {
              $time_ago = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
            } else {
              $time_ago = 'Just now';
            }
            
            $formatted_date = $last_update_date->format('F j, Y \a\t g:i A');
          } catch (Exception $e) {
            $formatted_date = $last_update;
            $time_ago = '';
          }
        ?>
        <div class="row mb-3">
          <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
              <i class="fas fa-sync-alt mr-2"></i>
              <strong>Last Data Update:</strong> 
              <?php echo $formatted_date; ?>
              <?php if (!empty($time_ago)): ?>
                <span class="badge badge-light ml-2"><?php echo $time_ago; ?></span>
              <?php endif; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Main row -->
        <div class="row">
           

        <div class="col-md-3">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-blue shadow-sm">
              <span class="info-box-icon"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Health Workers</span>
                <span class="info-box-number">
                  <?php echo number_format($staff) ?>
                </span>
                <div class="progress">
                  <div class="progress-bar bg-white" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Active health workers
                </span>
              </div>
              <!-- /.info-box-content-->
            </div>
            <!-- /.info-box -->
        <div class="info-box mb-3 bg-green shadow-sm">
              <span class="info-box-icon"><i class="far fa-building"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Facilities</span>
                <span class="info-box-number"><?php echo number_format($facilities) ?></span>
                <div class="progress">
                  <div class="progress-bar bg-white" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Health facilities registered
                </span>
        </div>
              <!-- /.info-box-content -->
        </div>
            <!-- /.info-box -->
         <div class="info-box mb-3 bg-danger shadow-sm">
              <span class="info-box-icon"><i class="fas fa-map-marked-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Districts</span>
                <span class="info-box-number"><?php echo number_format($districts) ?></span>
                <div class="progress">
                  <div class="progress-bar bg-white" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Districts covered
                </span>
        </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        <div class="info-box mb-3 bg-info shadow-sm">
              <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Job Positions</span>
                <span class="info-box-number"><?php echo number_format($jobs) ?></span>
                <div class="progress">
                  <div class="progress-bar bg-white" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Available job positions
                </span>
              </div>
              <!-- /.info-box-content-->
        </div>
            <!-- /.info-box -->

           
              <!-- /.footer -->
        </div>
          <!-- Left col -->
          <section class="col-lg-9 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-2"></i>
                  iHRIS National Health Worker Force - Cadre Distribution
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 350px;">
                      <div id="cadredistribution" height="350" style="height: 350px;"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 350px;">
                    <canvas id="sales-chart-canvas" height="350" style="height: 350px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          
          </section>
          <!-- right col -->

          <?php /*
          <section class="col-lg-12 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                iHRIS National Annual Attendance Analysis
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <!-- <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li> -->
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; ">
                      <div id="absoluteAbsenteeism"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          
          </section>
          */ ?>

          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card card-success card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-2"></i>
                  Age Distribution
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 320px;">
                      <div id="ageDistribution" height="320" style="height: 320px;"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 320px;">
                    <canvas id="sales-chart-canvas" height="320" style="height: 320px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          
          </section>

          
          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card card-warning card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-2"></i>
                  Gender Distribution
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 320px;">
                      <div id="gender_chart" height="320" style="height: 320px;"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 320px;">
                    <canvas id="sales-chart-canvas" height="320" style="height: 320px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          
          </section>
        </div>
        <!-- /.row (main row) -->
        
        <!-- New Charts Row -->
        <div class="row">
          <section class="col-lg-6 connectedSortable">
            <!-- Staff by Region -->
            <div class="card card-info card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-map mr-2"></i>
                  Staff by Region
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart" style="position: relative; height: 320px;">
                  <div id="regionChart" style="height: 320px;"></div>
                </div>
              </div>
            </div>
          </section>

          <section class="col-lg-6 connectedSortable">
            <!-- Staff by Institution Type -->
            <div class="card card-secondary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-building mr-2"></i>
                  Staff by Institution Type
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart" style="position: relative; height: 320px;">
                  <div id="institutionTypeChart" style="height: 320px;"></div>
                </div>
              </div>
            </div>
          </section>
        </div>
        
        <div class="row">
          <section class="col-lg-6 connectedSortable">
            <!-- Staff by Facility Level -->
            <div class="card card-danger card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-hospital mr-2"></i>
                  Staff by Facility Level
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart" style="position: relative; height: 320px;">
                  <div id="facilityLevelChart" style="height: 320px;"></div>
                </div>
              </div>
            </div>
          </section>

          <section class="col-lg-6 connectedSortable">
            <!-- Staff by Ownership -->
            <div class="card card-dark card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-handshake mr-2"></i>
                  Staff by Ownership
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart" style="position: relative; height: 320px;">
                  <div id="ownershipChart" style="height: 320px;"></div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
    <!-- Chart Data and Scripts -->
    <script>
    // Pass PHP data to JavaScript
    var regionData = <?php echo json_encode($region_data); ?>;
    var institutionTypeData = <?php echo json_encode($institution_type_data); ?>;
    var facilityLevelData = <?php echo json_encode($facility_level_data); ?>;
    var ownershipData = <?php echo json_encode($ownership_data); ?>;
    
    // Render charts when document is ready
    $(document).ready(function() {
      // Staff by Region Chart (Column Chart)
      if (regionData && regionData.labels && regionData.values) {
        Highcharts.chart('regionChart', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Staff Distribution by Region'
          },
          xAxis: {
            categories: regionData.labels,
            crosshair: true,
            labels: {
              rotation: -45,
              style: {
                fontSize: '11px'
              }
            }
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Number of Staff'
            }
          },
          tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
              '<td style="padding:0"><b>{point.y:,.0f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
          },
          plotOptions: {
            column: {
              pointPadding: 0.2,
              borderWidth: 0,
              dataLabels: {
                enabled: true,
                format: '{point.y:,.0f}'
              }
            }
          },
          series: [{
            name: 'Staff',
            data: regionData.values,
            color: '#17a2b8'
          }],
          credits: {
            enabled: false
          }
        });
      }
      
      // Staff by Institution Type Chart (Pie Chart)
      if (institutionTypeData && institutionTypeData.length > 0) {
        Highcharts.chart('institutionTypeChart', {
          chart: {
            type: 'pie'
          },
          title: {
            text: 'Staff Distribution by Institution Type'
          },
          tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Total: <b>{point.y:,.0f}</b>'
          },
          plotOptions: {
            pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              depth: 35,
              dataLabels: {
                enabled: true,
                format: '{point.name}: {point.percentage:.1f}%'
              }
            }
          },
          series: [{
            name: 'Staff',
            data: institutionTypeData
          }],
          credits: {
            enabled: false
          }
        });
      }
      
      // Staff by Facility Level Chart (Pie Chart)
      if (facilityLevelData && facilityLevelData.length > 0) {
        Highcharts.chart('facilityLevelChart', {
          chart: {
            type: 'pie'
          },
          title: {
            text: 'Staff Distribution by Facility Level'
          },
          tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Total: <b>{point.y:,.0f}</b>'
          },
          plotOptions: {
            pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              depth: 35,
              dataLabels: {
                enabled: true,
                format: '{point.name}: {point.percentage:.1f}%'
              }
            }
          },
          series: [{
            name: 'Staff',
            data: facilityLevelData
          }],
          credits: {
            enabled: false
          }
        });
      }
      
      // Staff by Ownership Chart (Pie Chart)
      if (ownershipData && ownershipData.length > 0) {
        Highcharts.chart('ownershipChart', {
          chart: {
            type: 'pie'
          },
          title: {
            text: 'Staff Distribution by Ownership'
          },
          tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Total: <b>{point.y:,.0f}</b>'
          },
          plotOptions: {
            pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              depth: 35,
              dataLabels: {
                enabled: true,
                format: '{point.name}: {point.percentage:.1f}%'
              }
            }
          },
          series: [{
            name: 'Staff',
            data: ownershipData
          }],
          credits: {
            enabled: false
          }
        });
      }
    });
    </script>
       