<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indicator extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();
		$this->load->model('Indicator_mdl','kpi_mdl');
		$this->load->model('Graph_mdl','graph_mdl');
		$this->module = "indicator";
	}

	public function kpis(){
		$data['title']='Key Performance Indicators';
		$data['page'] ='kpi';
		$data['module']=$this->module;
		echo Modules::run('template/layout', $data); 
	}

	public function kpiData(){
      return   $this->kpi_mdl->kpiData();
	}

	public function dashKpi($id=FALSE){
		$kpis = $this->kpi_mdl->navkpi($id);
        return $kpis;
	}

	public function subject(){

		$data['title']='Subject Areas';
		$data['page']='subject';
		$data['module']=$this->$this->module;
		echo Modules::run('template/layout', $data); 
	}

	public function subjectData(){

		$menu=$this->kpi_mdl->subjectData();
		return $menu;
	}
	
	public function addKpi(){
	  $insert=$this->input->post();
	  $data['message']=$this->kpi_mdl->addKpi($insert);
	  $this->session->set_flashdata('message','Added');
	  $data['title']='Key Performance Indicators';
	  $data['page']='kpi';
	  $data['module']=$this->$this->module;
	  echo Modules::run('template/layout', $data); 
	}

	public function updateKpi(){
	    $kpi=$this->input->post('kpi_id');
		$is=$this->input->post('indicator_statement');
		$sn=$this->input->post('short_name');
		$des=$this->input->post('description');
		$ds=$this->input->post('data_sources');
		$freq=$this->input->post('frequency');
		$target=$this->input->post('current_target');
		$comp=$this->input->post('computation');
		$sa=$this->input->post('subject_area');
		$ic=$this->input->post('is_cumulative');
		$count=count($kpi);
		//print_r($count);
		for($i=0;$i<$count; $i++){
		//build and insert array
		$insert=array('kpi_id'=>$kpi[$i],'indicator_statement'=>$is[$i],'description'=>$des[$i],
		'frequency'=>$freq[$i],'data_sources'=>$ds[$i],'current_target'=>$target[$i],'computation'=>$comp[$i],
		'subject_area'=>$sa[$i],'is_cumulative'=>$ic[$i],'short_name'=>$sn[$i]);
	
		 $data['message']=$this->kpi_mdl->updatekpiData($insert);
		// print_r($insert);
		} 
	
		$this->session->set_flashdata('message','Saved');
		$data['title']='Key Performance Indicators';
		$data['page']='kpi';
		$data['module']=$this->$this->module;
		echo Modules::run('template/layout', $data); 
	  }

	public function addSubject(){
	  $insert=$this->input->post();
	  //print_r($insert);
      $data['message']=$this->kpi_mdl->addsubject($insert);
	   $this->session->set_flashdata('message',$data['message']);
      $data['title']='Subject Areas';
	  $data['page']='subject';
	  $data['module']=$this->$this->module;
	 // echo Modules::run('template/layout', $data); 
	}

	public function kpiDisplayData(){

      return   $this->kpi_mdl->kpiDisplayData();
	}
	public function insertDisplayData(){
		$kpi=$this->input->post('kpi_id');
		$dash=$this->input->post('dashboard_index');
		$sub=$this->input->post('subject_index');
		$count=count($kpi);
		for($i=0;$i<$count; $i++){
		//build and insert array
		$insert=array('kpi_id'=>$kpi[$i],'dashboard_index'=>$dash[$i],'subject_index'=>$sub[$i]);
		$data['message']=$this->kpi_mdl->insertDisplayData($insert);
		} 
	
		$this->session->set_flashdata('message','Saved');
	
		$data['title']='KPI Display ';
		$data['page']='kpi_display';
		$data['module']=$this->$this->module;
		echo Modules::run('template/layout', $data); 
		//$data['message']=$this->kpi_mdl->InsertDisplay($insert);
	    //print_r($save);
     	// return   $this->kpiDisplay()();
	}

	public function kpiDisplay(){
	 
      $data['title']='KPI Display ';
	  $data['page']='kpi_display';
	  $data['module']=$this->$this->module;
	  echo Modules::run('template/layout', $data); 
	}

	public function summary(){
	 
		$data['title']='KPI Summary ';
		$data['page']='kpi_summary';
		$data['module']=$this->$this->module;
		echo Modules::run('template/layout', $data); 
	}

	public function summaryData(){

		return   $this->kpi_mdl->kpiSummaryData();
	}

	public function kpiTrendcolors($current_target,$gauge_value,$previousgauge_value,$current_period=FALSE, $previous_period=FALSE){
		if ($previous_period!=0){
			$previous_period='for '. $previous_period;
		}
		else{
		   $previous_period='';
		}
   
		if(($current_target)>40){
		if ($gauge_value >= $current_target){
		 return 'style="background-color:green; color:white;"';
		} 
		elseif (($gauge_value < $current_target)&&($gauge_value >= 50)){
		 return 'style="background-color:orange; color:white;"';
		} 
		
		else
		{
		return 'style="background-color:red; color:white;"';
		}
		}  
		//reducing
	   if(($current_target)<40){
	  
		if ($gauge_value <= $current_target){
			return 'style="background-color:green; color:white;"';
		   } 
		   elseif (($gauge_value < $current_target)&&($gauge_value >= 50)){
			return 'style="background-color:orange; color:white;"';
		   } 
		   
		   else
		   {
		   return 'style="background-color:red; color:white;"';
		   }
		}  
   
	   }
	   public function gaugeData($kpi){
		//print_r(json_encode($kpi));
		$data['chartkpi']=$kpi;
		//gauge data
		$data['gauge'] = $this->graph_mdl->gaugeData(str_replace(" ",'',$kpi));
		$data['financial_year'] = $_SESSION['financial_year'];
		$data['module']="data";
		//print_r(json_encode($data['gauge']));
		//$data['tests']=$this->test();
	    return $data;
		
		}

		public function assessments(){
			
			$data['title']='iHRIS Assessment';
			$data['uptitle']='iHRIS Assessment';
			$data['page']='assessments';
			$data['module']=$this->module;
			$this->load->library('pagination');
			$config=array();
			$config['base_url']=base_url()."indicator/assessments";
			$config['total_rows']=$this->db->get('support')->num_rows();
			$config['per_page']=10000000; //records per page
			$config['uri_segment']=3; //segment in url  
			//pagination links styling
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['attributes'] = ['class' => 'page-link'];
			$config['first_link'] = true;
			$config['last_link'] = true;
			$config['first_tag_open'] = '<li class="page-item">';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li class="page-item">';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li class="page-item">';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
			$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
			$config['num_tag_open'] = '<li class="page-item">';
			$config['num_tag_close'] = '</li>';
			$config['use_page_numbers'] = true;
			$this->pagination->initialize($config);
			
			$dateFrom =$this->input->post('dateFrom');
			$dateTo =$this->input->post('dateTo');

			$page=($this->uri->segment(3))? $this->uri->segment(3):0; //default starting point for limits 
			$data['links']=$this->pagination->create_links();
			$institution =$this->input->post('facility');
            $data['elements']=$this->kpi_mdl->getassessment($dateFrom,$dateTo,$institution);
			echo Modules::run('template/layout', $data); 
		
		
			
			}

	

	



}
