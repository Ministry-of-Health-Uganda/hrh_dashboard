
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Africa/Kampala');	
require_once("includes/header.php");
require_once("includes/navtop.php");
require_once("includes/sidenav.php"); 

//db connection
?>

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <br>
    <!-- Content Header (Page header) -->
    <?php 
           if($this->uri->segment(4)=="ihris_dashboard"||$this->uri->segment(1)=="data"): 
                        $subjects=Modules::run('kpi/subjectData');
                        ?>
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

          
          <div class="col-sm-12" style="background: #feffff; font-size:12px; border-radius:4px;">
          <ol>
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Layout</a></li>
              <li class="breadcrumb-item active">Fixed Layout</li> -->                    
                      <?php
                      if($subjects):
                      ?>
                        
                      <ul class="nav nav-pills nav-fill" >
                        <?php foreach ($subjects as $subject):
                          ?>
                        
                        <li >
                         <?php 
                         $url =  base_url()."data/subject/".$subject->id."/".str_replace("+","_",urlencode($subject->name)); ?>
                           
                        <a href="<?php echo $url; ?>"  target="_self" class="nav-link  <?php if ($subject->id == $this->uri->segment(3)||$subject->id == $this->uri->segment(4)){ echo 'active'; } ?>" >
                    
                         <p><?php echo $subject->name; ?></p>
                               
                           
                            </a>
                            
        
                        </li>
                     
          

                       <?php  
                       endforeach; 
                       ?>
                        </ul>
                        <?php
                        
                       endif; ?>
                       <hr>
             
                 <?php  if($this->uri->segment(4)!="ihris_dashboard") { ?>
                 <ul class="nav nav-pills nav-fill">
                    <?php 
                    if ($this->uri->segment(2)=="subject"){
                      $uri=$this->uri->segment(3);
                    }
                    else{
                      $uri=$this->uri->segment(4);

                    }
                  $kpis= Modules::run('Kpi/dashkpi', $uri); 

                         if ($this->uri->segment(2)=="subject"){$kpiuri=$this->uri->segment(3);} else{ $kpiuri=$this->uri->segment(4);}
                         
                      
                      foreach ($kpis as $kpi):
                        $url = base_url().'data/kpiData/'.$kpi->kpi_id.'/'.$kpiuri;
                      
                      ?>
                    <li class="" data-toggle="tooltip"  title="<?php echo $kpi->short_name; ?>"  ><a href="<?php echo $url; ?>" class="nav-link  <?php if ($kpi->kpi_id == $this->uri->segment(3)){ echo 'active'; } ?>" > 
                      <?php echo $kpi->short_name; ?></a> 
                      </li>
                      <?php endforeach; 

                      }

                      
                    ?>
                </ul>
              

            
          </div>
       
        </div>
      </div>
    </section>
    <?php endif; ?>
    <!-- /.container-fluid -->

    <!-- Main content -->
    <section class="content" style="font-size:12px;" >

      <div class="container-fluid">

        <div class="row">
          <div class="col-12" style="margin-bottom:3px;">
             <div class="card">
                   <div class="card-body">
                        <?php 
                        $this->load->view($module.'/'.$page) ?>
                   </div>
             <!-- /.card-body -->
         <div class="card-footer">
       
      </div>
      <!-- /.card-footer-->
    </div>
             
           
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
      require_once("includes/footer.php");
?>