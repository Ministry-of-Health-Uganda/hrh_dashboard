
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div> 
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                        <!-- Button trigger modal -->
                       
                    <div class="card-content">
                      
                        <div class="col-md-6">    
                        <button class="btn btn-success" onclick="printTable('kpitable')"><i class="fa fa-print"  ></i>Print</button>
                        </div>
                        <div class="col-md-6">
                        <button type="button" class="btn btn-success" style="float:right;" data-toggle="modal" data-target="#definition">
                        <?php echo display("definition"); ?>
                        </button>
                        
                        </div>

                        <div id="kpitable">

                                    <table id="kpi"  class="table table-responsive table-striped table-bordered print table">
                                    
                            
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Subject Area</th>
                                                <th>Indicator Statement</th>
                                                <th>Target</th>
                                                <th style="width:13%;">Current Performance</th>
                    
                                            </tr>
                                            </thead>
                                            <tbody>
                                                  <?php 
                                                    $i=1;
                                                
                                                //print_r($gauge['gauge']['data'][0]->current_target);
                                                $elements=Modules::run('indicator/summaryData');
                                                    foreach($elements as $element):?>

                                                <tr class="table-row tbrow content strow">
                                                    <td><?php echo $i ?></td>
                                                    <td style="width:20%;"><?php echo $element->name; ?></td>
                                                    <td><?php 
                                                    $gauge=Modules::run('indicator/gaugeData',$element->kpi_id); ?>
                                                    <a href="<?php echo base_url().'data/kpidata/'.$gauge['gauge']['details'][0]->kpi_id.'/'.$gauge['gauge']['details'][0]->subject_area; ?>" target="_self"><p class=""  style=" color:green; font-size:12px;" ><?php echo $gauge['gauge']['details'][0]->short_name; ?></p></a></td>
                                                    <td><?php echo $gauge['gauge']['data'][0]->current_target; ?></td>
                                                    <td  <?php 
                                                    
                                                    echo Modules::run("indicator/kpiTrendcolors",$gauge['gauge']['data'][0]->current_target,$gauge['gauge']['data'][0]->current_value,$gauge['gauge']['data'][0]->previous_value,$gauge['gauge']['data'][0]->cp,$gauge['gauge']['data'][0]->pp);
                                                     ?>>
                                                     <?php echo  $gauge['gauge']['data'][0]->current_value.'%'; ?>
                                                     </td>
                     
                                                    
                                                </tr>
                                                    <?php 
                                                        $i++;
                                                    endforeach; 

                                                    if(count($elements)==0){

                                                        echo "<tr><td colspan='8'><center><h3 class='text-warning'>Please Add Indicators</h3></center></td></tr>";
                                                    }
                                                        ?>
                                            </tr>

                                            
                                                                
                                        </tbody>
                                    </table>
                                    </div>
                               </div>
                           </div> 
                        </div>
                    </div>
                </div>
            </div> 
    



    <script>
		function printTable(divName){
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			document.body.innerHTML = originalContents;

		}
	</script>


