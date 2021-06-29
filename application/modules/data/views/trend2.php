


<style>
.highcharts-figure{
    background:#FEFFFF;
    
}
</style>

<?php 

if($this->uri->segment(1)=="dashboard"){
    
    $col="col-md-12  offset-2";
}
else{
    $col="col-md-12 offset-2";
}

foreach($tests as $test){

   foreach($test as $ftest){
   }
}
                            
?>
<div class="col-md-12">
    <div class="row">
        <?php //print_r($this->uri->segment(3)); ?>
        <ul class="nav nav-tabs">
                <?php
                $dimsub=Modules::run("data/getdimSubject",$this->uri->segment(3)); 
                ?>
                <li class=""> <a href="<?php echo base_url().'data/kpidata/'.$this->uri->segment(3).'/'.$dimsub; ?>" class="dropdown-item">Trend</a> </li>

                <?php
                
                        $dim1=Modules::run('data/dimalldisplay',$this->uri->segment(3));

                                    foreach ($dim1 as $dim){ ?>
                                        <li class="<?php if ($this->uri->segment(2)=='dimension1') { echo ''; } ?>"> <a href="<?php echo base_url().'data/dimension1/'.$dim->kpi_id.'/'.$dimsub; ?>" class="dropdown-item"><?php echo $dim->dimension1_key; ?></a> </li>
                                        <li class="<?php if ($this->uri->segment(2)=='dimension2') { echo ''; } ?>"> <a href="<?php echo base_url().'data/dimension2/'.$dim->kpi_id.'/'.$dimsub; ?>" class="dropdown-item active"><?php echo $dim->dimension2_key; ?></a> </li>
                                        <li class="<?php if ($this->uri->segment(2)=='dimension3') { echo ''; } ?>"> <a href="<?php echo base_url().'data/dimension3/'.$dim->kpi_id.'/'.$dimsub;?>" class="dropdown-item"><?php echo $dim->dimension3_key; ?></a> </li> 
                                    <?php    }  
                        ?>
</ul>
</div>

</div>
<!--Trends-->
  
     
    
<section class="content">
      <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            
        
        <section class="col-lg-12 connectedSortable">
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
                  <div class=""
                       style="position: relative; height:auto;">
                      <div id="line<?php echo $chartkpi; ?>"></div>                         
                  </div>
                  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          
        </section>
        </div>
         

  
       
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
       
    
   


<?php require("includes/trend2js.php") ?>




