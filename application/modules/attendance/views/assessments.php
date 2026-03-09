 
 <section class="col-lg-12">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </h3>
                <div class="card-tools">
                
                  <form class="form-horizontal" action="<?php echo base_url() ?>indicator/assessments" method="post">
                
                  <div class="row">
                    <div class="form-group col-md-2">
                    <label>Date From:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                        </div>
                        <input type="text"  name="dateFrom" class="form-control datepicker" autocomplete="off">
                    </div>
                    <!-- /.input group -->
                    </div>
                    <div class="form-group col-md-2">
                    <label>Date To:</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                        </div>
                        <input type="text"  name="dateTo" class="form-control datepicker "  autocomplete="off">
                    </div>
                    <!-- /.input group -->
                    </div>
                            
                     
     
     
                  <div class="form-group col-md-6">
                       
                       <label for="aw_description">
                         Institution / District /Regional Referrals  </label>
                        
                         <select name="facility" class="form-control select2">
                         <option value="" >ALL</option>
                         <?php $facilities=Modules::run("facilities/getFacilities");
                         foreach ($facilities as $element) {
                             
                         
                         ?>
                       
                        <option value="<?php echo $element->facility;?>" <?php if($this->input->post('facility')==$element->facility){ echo "selected"; } ?> ><?php echo $element->facility;?></option>
                        <?php } ?>
                       </select>  
                     
                    </div>
                        
    
                <div class="form-group col-md-2">
            
                <button type="submit" class="btn btn-primary" style="margin-top:24px;">Apply</button>
              </div>
              

                  </form>
               
                
 

                             
              </div><!-- /.card-header -->
              </div>
              </div>
              <div class="card-body">

                                    <table id="kpi"  class="table table-responsive table-striped table-bordered" id="mytab2">
                                 
                            
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Name</th>
                                                <th style="width:30%">Email</th>
                                                <th>Phone Number</th>
                                                <th>Institution </th>
                                                <th>Current Staff </th>
                                                <th>iHRIS Staff </th>
                                                <th>Gap</th>
                                                <th>% Entry</th>
                                                <th>IHRIS on Budget</th>
                                                <th style="width:5% !important;">Reports</th>
                                                <th>Support</th>
                                                
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $ii=1;
                                                
                                                    foreach($elements as $element):?>

                                                <tr>
                                                    <td><?php echo $ii++; ?></td>
                                                    <td style="width:5%;"><?php echo  $element->date; ?></td>
                                                    <td style="width:10%;"><?php echo  $element->name; ?></td>
                                                    <td style="width:5% !important;"><?php echo  trim($element->email); ?></td>
                                                    <td style="width:5%;"><?php echo  $element->contact; ?></td>
                                                    <td style="width:10%;"><?php   if (strpos($element->institution, 'DHO') !== false){ echo $district = explode(" ", $element->institution)[0]; } else{ echo $element->institution; } ?></td>
                                                    <td style="width:3%;"><?php echo  $element->current_staff; ?></td>
                                                    <td style="width:3%;"><?php echo  $element->ihris_staff; ?></td>
                                                    <?php  $gap=($element->ihris_staff-$element->current_staff); ?>
                                                    <td <?php if(($gap<0)||($gap>0)){ echo'style="width:3%"; background:red; color:#FFF;';}else if ($gap==0){ echo  'style="width:3%"; background:green; color:#FFF;'; } ?>><?php echo $gap; ?></td>
                                                    <td style="width:3%;"><?php echo  round((($element->ihris_staff/$element->current_staff)*100),1); ?></td>
                                                    <td style="width:5%;"><?php echo  $element->budget_part; ?></td>
                                                    <td style="width:15x; !important;"><?php $count=count( json_decode($element->reports)); 
                                                    
                                                            for($i=0;$i<=$count; $i++) { 
                                                             $result=json_decode($element->reports)[$i].', ' ;

                                                             echo str_replace(",,","",$result);
                                                            }
                                                    
                                                    
                                                    ?> </td>
                                                    <td style="width:30%!important;"><?php echo  $element->support; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                                                
                                        </tbody>
                                    </form>
                                    </table>
                                    </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
                               

<script>
 $(document).ready(function() {
    $('#kpi').DataTable( {
        dom: 'Bfrtip',
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            
            
        ]
    } );
});
</script>

     