
<!-- <style>
.highcharts-figure{
    background:#FEFFFF;
  
}
</style> -->

<?php 

if(($this->uri->segment(1)=="performance")||($this->uri->segment(1)=="")){
    
    $col="col-lg-".$setting->dash_rows;
}
else{
    $col="col-lg-".$setting->kpi_rows."offset-2";
}
//print_r($chartkpi);
//print_r($_SESSION['period']);
                 // print_r($gaugedetails[0]->current_target);
//use this for the bar graph
// foreach($tests as $test){

// }
                                   

?>


    <section class="<?php echo $col ?> connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
            
                <div class="card-tools">
          
                
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative;">
                       <div id="gauge<?php echo $chartkpi; ?>"> 
                        </div>

                        <?php 

                        echo $kstatus=Modules::run("data/kpiTrend",$gauge['data'][0]->current_target,$gauge['data'][0]->current_value,$gauge['data'][0]->previous_value,$gauge['data'][0]->cp,$gauge['data'][0]->pp);

                        ?>                          
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          
        </section>
       
    
   <?php require("includes/gaugejs.php");
  ?>

  







