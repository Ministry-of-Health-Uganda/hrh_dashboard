


</script>

        <div class="col-md-12">
      <div class="align-items-center" style="margin:0 auto;">
        <?php 
             $message = $_SESSION['message'];
                  if(isset($message)){?>
                  <div class="alert alert-info alert-dismissible fade show" role="alert">
                  <p style="text-align:center;"> <?php echo $message; ?></p>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>
                  
                 <?php } ?>
      </div>

      <form action="<?php echo base_url(); ?>assessment/addSupport" method="post" >
          
      
          <div class="col-sm-12">
                <div class="form-group row">
                       
                  <label for="award_name" class="col-sm-3 col-form-label">
                        Date</label>
                      <div class="col-sm-9">
                       <input type="text" name="date" placeholder="" id="date-picker" value="<?php echo date('j F, Y');?>" data-provide="datepicker" class="form-control date-picker" readonly>
                           
                       </div>
                       
                 </div>

                 <div class="form-group row">
                       
                       <label for="award_name" class="col-sm-3 col-form-label">
                             Surname & Firstname</label>
                           <div class="col-sm-9">
                            <input type="text" name="name" placeholder="Name" id="" class="form-control" required>
                                
                            </div>
                            
                  </div>
                  <div class="form-group row">
                       
                       <label for="award_name" class="col-sm-3 col-form-label">
                             Email</label>
                           <div class="col-sm-9">
                            <input type="text" name="email" placeholder="Email" id="" class="form-control">
                                
                            </div>
                            
                  </div>
                  <div class="form-group row">
                       
                       <label for="award_name" class="col-sm-3 col-form-label">
                             Phone Number</label>
                           <div class="col-sm-9">
                            <input type="text" name="contact" placeholder="Contact" id="" class="form-control" required>
                                
                            </div>
                            
                  </div>
                  <div class="form-group row">
                       
                       <label for="" class="col-sm-3 col-form-label">
                       Are you the iHRIS focal person  </label>
                       <div class="col-sm-9">
                       <select name="is_focalperson" class="form-control">
 
                       <option>YES</option>
                       <option>NO</option>
                       </select>
                       </div>
                   </div>
                      
                  <div class="form-group row">
                       
                      <label for="aw_description" class="col-sm-3 col-form-label">
                        Institution / District /Regional Referrals  </label>
                        <div class="col-sm-9">
                        <select name="institution" class="form-control select2">
                        <?php $facilities=Modules::run("facilities/getFacilities");
                        foreach ($facilities as $element) {
                            
                        
                        
                        ?>
                      
                       <option value="<?php echo $element->facility;?>" selected="selected"><?php echo $element->facility;?></option>
                       <?php } ?>
                      </select>  
                    
                      </div>
                       
                  </div>
                
                  <div class="form-group row">
                       
                       <label for="" class="col-sm-3 col-form-label">
                             Current Staff On ground</label>
                           <div class="col-sm-9">
                            <input type="number" name="current_staff" placeholder="Current Staff" id="" class="form-control">
                                
                            </div>
                   </div>
            </div>

            <!-- close col -->
            <div class="col-sm-12 ">
                  <div class="form-group row">
                  <label for="awr_gift_item" class="col-sm-3 col-form-label">
                         Reports Recently Generated</label>
          
                   
                    <!-- checkbox -->
                   
                    
                      <label  class="checkbox-inline btn btn-default"  >Staff List
                      <input  type="checkbox" name="reports[]" value="stafflist">
                      </label>
                      
                     
                      <label  class="checkbox-inline btn btn-default"  style="margin-left:2px;">HRH Audit
                      <input  type="checkbox" name="reports[]" value="hrhaudit" style="margin-left:2px;">
                      </label>
                    
                      
                      <label  class="checkbox-inline btn btn-default"  style="margin-left:2px;">Attendance
                      <input  type="checkbox" name="reports[]" value="attendance">
                      </label>

                      <label  class="checkbox-inline btn btn-default" style="margin-left:2px;"  >Retirement
                      <input type="checkbox" name="reports[]" value="retirement">
                      </label>

                      
                     

                      <label  class="checkbox-inline btn btn-default"  style="margin-left:25%;">Registration / Licensure
                      <input  type="checkbox" name="reports[]" value="registration">
                      </label>
                      
                     
                     
                     
                      <label  class="checkbox-inline btn btn-default"  style="margin-left:2px;">Others
                      <input  type="checkbox" name="reports[]" value="attendance">
                      </label>
                    
                      
                  </div>
                  <div class="form-group row">
                       
                       <label for="awr_gift_item" class="col-sm-3 col-form-label">
                       Specify (Other Report)</label>
                       <div class="col-sm-9">
                      <input type="text" class="form-control" name="other_report" cols="5" rows="5"> 
                          
                 </div>
                 </div>
                 <div class="form-group row">
                 <label for="awr_gift_item" class="col-sm-3 col-form-label">
                 Is iHRIS support part of annual budget</label>
                       
                       <div class="col-sm-9">
                        <select class="form-control" name="budget_part" required>
                        <option value="">Select Option</option>
                        <option>Yes</option>
                        <option>No</option>

                        </select> 
                          
                 </div>

                 </div>
               
                 <div class="form-group row">
                 <label for="awr_gift_item" class="col-sm-3 col-form-label">
                 Any Techical Support Needed?</label>
                       
                       <div class="col-sm-9">
                        <select class="form-control" name="is_support" required>
                        <option value="">Select Option</option>
                        <option>Yes</option>
                        <option>No</option>

                        </select> 
                          
                 </div>

                 </div>

                  <div class="form-group row">
                       
                        <label for="awr_gift_item" class="col-sm-3 col-form-label">
                       Describe the Technical Support Needed(If the above is Yes) </label>
                        <div class="col-sm-9">
                       <textarea class="form-control" name="support_needed" cols="5" rows="3"> </textarea>
                           
                  </div>
                  </div>
            </div>
                       
          


            </div>
            <div class="justify-content-between">
              <button type="reset" class="btn btn-default">Reset</button>
              <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            </form>
          