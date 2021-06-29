
      
<?php
 //load subject dashboards 

 
foreach ($subdash as $subd) {
                                   
       echo Modules::run('data/kpi',$subd->kpi_id,'on');
                              
 }

 ?>
