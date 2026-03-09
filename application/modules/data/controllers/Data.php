<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();
       
		$this->load->model('data_mdl');
		$this->load->model('graph_mdl');
		$this->kpi=$this->uri->segment(3);

	}
//GAUGE
	public function kpi($dashkpi=FALSE,$dashdis=FALSE){
	if($dashdis=='on'){
	    $kpi=$dashkpi;
	}
	else {	
        
	     $kpi=$this->kpi;
		
	}
	//print_r(json_encode($kpi));
	$data['chartkpi']=$kpi;

	//gauge data
    $data['gauge'] = $this->graph_mdl->gaugeData(str_replace(" ",'',$kpi));
	$data['financial_year'] = $_SESSION['financial_year'];
	$data['title']=str_replace("_"," ",urldecode($this->uri->segment(4)));
	$data['page']='dash_chart';
	$data['module']="data";

	//print_r(json_encode($data['gauge']));
	$data['tests']=$this->test();
	if(!empty($dashdis==='on')){
	 $this->load->view('dash_chart',$data);
	}
	else{
	echo Modules::run('template/layout', $data); 
	}
	
	}
	

	//TEST FUNC
	public function test(){

		//$data=$this->graph_mdl->dim3Graph($kpi="KPI-01");

	//   print_r(json_encode($data,JSON_NUMERIC_CHECK));
	//return 0 ;
	
	}
	public function dim1display($kpi){
		$data=$this->data_mdl->dim1display($kpi);
	  //print_r($data);
	return $data;
	
	}
	public function dimalldisplay($kpi){
		$data=$this->data_mdl->dimalldisplay($kpi);
	  //print_r($data);
	return $data;
	
	}
	public function dim2display($kpi){
		$data=$this->data_mdl->dim2display($kpi);
	  // print_r($data);
	return $data;
	
	}
	public function dim3display($kpi){
		$data=$this->data_mdl->dim3display($kpi);
	  // print_r($data);
	return $data;	
	}
  /// Dimension 0, KPI DRILL DOWMN

    public function kpiData($kpi){

		$data['module']="data";
	 	$data['page']='trend';
		$data['uptitle']=$this->data_mdl->subject_name($kpi);
		$data['title']=$this->data_mdl->kpi_name($kpi);
	
     echo Modules::run('template/layout', $data); 

	}
	public function kpiDetails($kpi){

		$data['module']="data";
	 	$data['page']='kpi_details';
		 $data['uptitle']=ucwords($kpi).' Details';
		$data['kpi_table']=$this->data_mdl->gaugeDetails($kpi);
	
     echo Modules::run('template/layout', $data); 

	}
	public function getdimSubject($kpi){

		$query=$this->db->query("SELECT subject_area from kpi where kpi_id='$kpi'")->row();
	return $query->subject_area;
	}
	//BAR GRAPH
	public function dimension0($kpi){
		$data['quaters']=$this->graph_mdl->dim0quaters($kpi);
		$data['data']=$this->graph_mdl->dim0data($kpi);
		$data['target']=$this->graph_mdl->dim0targets($kpi);
    return $data;
    }
	
   	public function dimension1($kpi){
		$data['module']="data";
	 	$data['page']='trend1';
		$data['uptitle']=$this->data_mdl->subject_name($kpi);
		$data['title']=$this->data_mdl->kpi_name($kpi);
     echo Modules::run('template/layout', $data); 
	  // print_r($data);
	return $data;
	}
	public function dimension2($kpi){
		$data['module']="data";
	 	$data['page']='trend2';
		$data['uptitle']=$this->data_mdl->subject_name($kpi);
		$data['title']=$this->data_mdl->kpi_name($kpi);
     echo Modules::run('template/layout', $data); 
	  // print_r($data);
	return $data;
	}
		public function dimension3($kpi){
		$data['module']="data";
	 	$data['page']='trend3';
		 $data['uptitle']=$this->data_mdl->subject_name($kpi);
		$data['title']=$this->data_mdl->kpi_name($kpi);
		
    
     echo Modules::run('template/layout', $data); 

	  // print_r($data);
	return $data;
	
	}
	public function trend($kpi){
		$data=$this->graph_mdl->dim0Graph($kpi);
    echo json_encode($data,JSON_NUMERIC_CHECK);
    }
    //Dimensions Graphs
		//Gauge 1 Data
	public function dim1data($kpi){
		$data=$this->graph_mdl->dim1Graph($kpi);
	  //print_r($data);
	echo json_encode($data,JSON_NUMERIC_CHECK);
	
	}
	public function dim2data($kpi){
		$data=$this->graph_mdl->dim2Graph($kpi);
	  // print_r($data);
	echo json_encode($data,JSON_NUMERIC_CHECK);
	
	}
	public function dim3data($kpi){
		$data=$this->graph_mdl->dim3Graph($kpi);
	  // print_r($data);
	echo  json_encode($data,JSON_NUMERIC_CHECK);
	}
  

   //subjectdashboard
	public function subject($subject){
	 
    
	    $data['module']="data";
	 	$data['page']='subject_charts';
		$data['subdash'] = $this->data_mdl->subjectDash($subject);
		$data['uptitle'] = str_replace("_"," ",$this->uri->segment(4));
    
     echo Modules::run('template/layout', $data); 



	}


	public function kpiTrend($current_target,$gauge_value,$previousgauge_value,$current_period=FALSE, $previous_period=FALSE){
	 if ($previous_period!=0){
		 $previous_period='for '. $previous_period;
	 }
	 else{
		$previous_period='';
	 }

	 if(($current_target)>40){
     if ($gauge_value > $previousgauge_value){
      return '<i class="fa fa-arrow-up" style="color:green; margin-bottom:10px;"></i>'.round($gauge_value).'% for  '.$current_period.'  compared to '. round($previousgauge_value).'%  '.$previous_period.'';
     } 
	 elseif ($gauge_value == $previousgauge_value){
      return ' <i class="fa fa-arrow-right" style="color:orange; margin-bottom:10px;"></i>'.round($gauge_value).'% for  '.$current_period.'  compared to '. round($previousgauge_value).'%  '.$previous_period.'';
     } 
	 
	 else
     {
     return '<i class="fa fa-arrow-down" style="color:red;margin-bottom:10px;"></i>'.round($gauge_value).'% for  '.$current_period.'  compared to '. round($previousgauge_value).'%  '.$previous_period.'';
     }
     }  
     //reducing
    if(($current_target)<40){
    if ($gauge_value < $previousgauge_value){
      return ' <i class="fa fa-arrow-up" style="color:green; margin-bottom:10px;"></i>'.round($gauge_value).'% for  '.$current_period.'  compared to '. round($previousgauge_value).'%  '.$previous_period.'';
     } 
	 elseif ($gauge_value == $previousgauge_value){
      return ' <i class="fa fa-arrow-right" style="color:orange; margin-bottom:10px;"></i>'.round($gauge_value).'% for  '.$current_period.'  compared to '. round($previousgauge_value).'%  '.$previous_period.'';
     } 
	 
	 else
     {
     return '<i class="fa fa-arrow-down" style="color:red;margin-bottom:10px;"></i>'.round($gauge_value).'% for  '.$current_period.'  compared to '. round($previousgauge_value).'% '.$previous_period=FALSE.'';
     }
     }  

	}
   

}
