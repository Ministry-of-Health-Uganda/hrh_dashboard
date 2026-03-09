<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('Assessment_mdl');

	}
	public function index(){

		$data['title']='HRH Assessment';
		$data['page'] ='support';
		$data['module']="assessment";
		
		echo Modules::run('template/layout', $data); 

	}
	public function addSupport(){
	  $insert=$this->input->post();

	
	$timestamp = date('Y-m-d',strtotime($this->input->post('date')));
	$insert=array
	('date'=>$timestamp,
	'name'=>$this->input->post('name'),
	'email'=>$this->input->post('email'),
	'contact'=>$this->input->post('contact'),
	'is_focalperson'=>$this->input->post('is_focalperson'),
	'user_accounts'=>$this->input->post('login'),
	'institution'=>$this->input->post('institution'),
	'current_staff'=>$this->input->post('current_staff'),
	'reports'=>json_encode($this->input->post('reports')),
	'other_report'=>$this->input->post('other_report'),
	'budget_part'=>$this->input->post('budget_part'),
	'is_support'=>$this->input->post('is_support'),
	'support'=>$this->input->post('support_needed')

	);

	 if(json_encode($this->input->post('reports')!='null')){
	 
	  $count=$this->Assessment_mdl->addSupport($insert);
	 }
	 else
	 {
	 $this->session->set_flashdata('message',"Failed, Select a report");
	 }
      

	// print_r($insert);
	$gap=($count-($this->input->post('current_staff')));
	 if($gap){
	  $this->session->set_flashdata('message',"Thanks, your feedback has been received, Your have a gap of   $gap  Records");
	 }
	  $data['title']='HRH Assessment';
	  $data['page']='support';
	  $data['module']="assessment";
	  echo Modules::run('template/layout', $data); 
	}
	


	

	



}
