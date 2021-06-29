 <!-- Main content -->
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
        <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="fas fa-mobile-alt" ></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Phone Directory</span>
                <span class="info-box-number"><?php echo $focalpersons ?></span>
              </div -->
              <!-- /.info-box-content-->
        </div>
            <!-- /.info-box -->

           
              <!-- /.footer -->
        </div>
          <!-- Left col -->
          <section class="col-lg-9 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <!-- iHRIS National Health Worker Force Gender Distribution -->
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
                      <div id="genderdistribution" height="300" style="height: 300px;"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          
          </section>
          <!-- right col -->
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

          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
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
                      <div id="ageDistribution" height="300" style="height: 300px;"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          
          </section>

          
          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
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
                      <div id="critical_cadre" height="300" style="height: 300px;"></div>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
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

    <script>
    Highcharts.setOptions({
    colors: ['#28a745', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
    });
    Highcharts.chart('genderdistribution', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
    },
    title: {
        text: 'iHRIS National Health Worker Force Gender Distribution'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Browser share',
        data: [
            ['Male', 48.2],
           
            {
                name: 'Female',
                y: 53.8,
                sliced: true,
                selected: true
            },
          
        ]
    }],
    credits: {
    enabled: false
  }
});

</script>

<script>
    Highcharts.chart('critical_cadre', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
    },
    title: {
        text: 'iHRIS Cadre Analysis'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Browser share',
        data: [
            ['Medical Officers', 10.2],
            ['Clinicians', 20.8],
            ['Nursing Professionals', 30.0],
           
           
            {
                name: 'Others',
                y: 39.0,
                sliced: true,
                selected: true
            },
          
        ]
    }],
    credits: {
    enabled: false
  }
});

</script>

<script type="text/javascript">
Highcharts.chart('ageDistribution', {
    chart: {
        type: 'column'
    },
    title: {
      text: 'Uganda  Health Workers Age Distribution'
    },
    xAxis: {
        categories: ['Below 30','Between 30 -55','Between 55-65','Above 65'],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'No of Staff'
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Age distribution',
        data: [15819,39551,2473,82]

    }], 
    credits: {
    enabled: false
      }
    });

                                        
   </script>

<script type="text/javascript">
    
    Highcharts.chart('absoluteAbsenteeism', {
        chart: {
            type: 'area'
        },
        title: {
            text: 'Overall Attendance for Uganda'
        },
        xAxis: {
            categories: ["May 2020","June 2020","July 2020","August 2020","September 2020","October 2020","November 2020","December 2020","January 2021","February 2021","March 2021","April 2021"],
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: 'Percent'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f} Days)<br/>',
            split: true
        },
        plotOptions: {
            area: {
                stacking: 'percent',
                lineColor: '#ffffff',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#ffffff'
                }
            }
        },
        series: [{ //person_id, month, year, daysPresent, daysOffDuty, daysOnLeave, daysRequest, daysAbsent, absenteeismRate
            name: 'Present',
            data: [142654,410390,99816,136577,189791,359491,132560,83056,175834,119749,144064,72059],
            
        }, {
            name: 'OffDuty',
            data: [75518,0,0,6780,91981,184438,42995,48510,102824,61584,75407,30458],
           
        }, {
            name: 'Leave',
            data: [21791,58836,17530,27472,37112,74858,23794,26989,31660,18159,28876,11777],
          
        }, {
            name: 'Official Request',
            data: [6755,0,0,53,15745,32770,8425,6276,11668,6581,9488,4085],
            
        }, {
            name: 'Absent',
            data: [51688,284415,65337,86759,93791,157822,45713,62399,73698,59194,74733,10612],
           
        }],
        credits: {
        enabled: false
      }
    });
    </script>              