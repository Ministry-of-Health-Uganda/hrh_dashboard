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
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
       