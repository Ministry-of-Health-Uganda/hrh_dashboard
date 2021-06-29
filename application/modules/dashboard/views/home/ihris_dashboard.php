
 <section class="content">
      <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            

        <div class="col-md-3">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-blue">
              <span class="info-box-icon"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Health Workers</span>
                <span class="info-box-number"><?php echo $staff ?></span>
              </div>
              <!-- /.info-box-content-->
            </div>
            <!-- /.info-box -->
        <div class="info-box mb-3 bg-green">
              <span class="info-box-icon"><i class="far fa-building"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Facilities</span>
                <span class="info-box-number"><?php echo $facilities ?></span>
        </div>
              <!-- /.info-box-content -->
        </div>
            <!-- /.info-box -->
         <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="fas fa-school"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Districts</span>
                <span class="info-box-number"><?php echo $districts ?></span>
        </div>
              <!-- /.info-box-content -->
        </div>
            <!-- /.info-box -->
        <div class="info-box mb-3 bg-orange">
              <span class="info-box-icon"><i class="fas fa-graduation-cap" style="color:#fff;"></i></span>

              <div class="info-box-content">
                <span class="info-box-text" style="color:#fff;">Jobs</span>
                <span class="info-box-number"><?php echo $jobs ?></span>
        </div>
              <!-- /.info-box-content -->
        </div>
            <!-- /.info-box -->
        <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="fas fa-mobile-alt" ></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Phone Directory</span>
                <span class="info-box-number"><?php echo $focal_persons ?></span>
              </div -->
            
        </div>
        </div>
        
        <section class="col-lg-9 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                iHRIS National Health Worker Force Age Distribution
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
                       style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          
        </section>
          <!-- Left col -->
        <!-- get kpi gauges from data -->
      
        <?php
            //Load KPI'S with display Index Dashboard
          
            // Bug fix load kpis which only have data(in Home mode DashData function)
            foreach ($dashkpis as $dashkpi) {
            // print_r($dashkpi->kpi_id);
            echo Modules::run($dashkpi->module.'/kpi',$dashkpi->kpi_id,'on');
            
            }
 
        ?>
                        </div>
         

  
       
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
