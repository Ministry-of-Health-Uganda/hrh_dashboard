<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('Audit_mdl');
		
		$this->watermark=FCPATH."assets/watermark.png";

	}



	public function index()
	{

		$data['module']="audit";
		$data['page']="hr_menu";
		$data['title']="MOH";

		echo Modules::run('template/layout', $data); 

		
	}



	public function getJobs($district,$facility,$facility_type=FALSE)
	{

		//$district=404;



		$jobs=$this->Audit_mdl->getJobs($district,$facility,$facility_type);

		return $jobs;

		//print_r($jobs);

		
	}

	public function getJob($id)
	{

		$job=$this->Audit_mdl->getJob("job|".$id);

		print_r($job);

		
	}


	public function showEstablishments(){

		$district=FALSE;
		$facility=FALSE;
		$facility_type=FALSE;

		$year=date('Y');
	    $m=date('m');
		$month=date('F',strtotime('2018-'.$m.'-d'));

		if($this->input->post()){

	     $district=$this->input->post('district');
	     $year=$this->input->post('year');
	     $month=$this->input->post('month');

		 $facility=$this->input->post('facility');
		 $facility_type=$this->input->post('type');

		 }

		 if(!$year){

		 	$year=date('Y');
		 }


		 if(!$month){

		 	$m=date('m');
		 	$month=date('F',strtotime('2018-'.$m.'-01'));
		 }
		 
		 

		$data['district']=$district;
		$data['facility']=$facility;
		$data['thistype']=$facility_type;
		$data['thisyear']=$year;
		$data['thismonth']=$month;
		
		$data['module']="audit";
		$data['page']="estab";
		$data['title']="Audit Report";
		$data['jobs']=$this->getJobs($district,$facility,$facility_type);

		echo Modules::run('template/layout', $data); 
	}

	
	public function getEstablishment($district=FALSE,$facility=FALSE,$job,$facility_type=FALSE){

		ini_set('max_execution_time',0);

		$esta=$this->Audit_mdl->getEstablishment($district,$facility,$job,$facility_type);


		return $esta;
	}


	
	public function getFacilities($district=FALSE){


		$facilities=@$this->Audit_mdl->getFacilities($district);

   //<select   name='facility_id'>
		$faci_options="<option  value='0'>All Facilities </option>";
		$faci_options.="<option disabled selected>--Select Facility--</option>";

		foreach ($facilities as $facility):

			$faci_options.='<option value="'.htmlspecialchars($facility->facility_id).'">'.$facility->facility.'</option>';
		
		endforeach;

		//$faci_options .="</select>";


		print_r($faci_options);

		//print_r($facilities)


	}



		public function showAttendance()
	{
		
		$district=FALSE;
		$facility=FALSE;
        $facility_type=FALSE;
        
        $year=date('Y');
	    $m=date('m');
		$month=date('F',strtotime('2018-'.$m.'-d'));


		if($this->input->post()){

	     $district=$this->input->post('district');
	     $year=$this->input->post('year');
	     $month=$this->input->post('month');
	     $facility_type=$this->input->post('type');

		 $facility=$this->input->post('facility');

		 }

		 if(!$year){

		 	$year=date('Y');
		 }
		 
		 if(!$month){

		 	$m=date('m');
		 	$month=date('F',strtotime('2018-'.$m.'-d'));
		 }

		$data['district']=$district;
		$data['facility']=$facility;

		$data['thisyear']=$year;
		$data['thismonth']=$month;
        $data['thistype']=$facility_type;
		
		$data['module']="audit";
		$data['page']="att";
		$data['title']="Att Summary";
		$data['attendances']=$this->Audit_mdl->getAtt($district,$facility,$year,$month,$facility_type);

		$data['schedules']=$this->Audit_mdl->getSchedules();

		echo Modules::run('template/layout', $data); 


	}



	public function sumAtt($schedule,$job_id,$year,$month,$district=FALSE,$facility=FALSE,$facility_type=FALSE){

		ini_set('max_execution_time',0);

		$att=$this->Audit_mdl->sumAtt($schedule,$job_id,$facility,$district,$year,$month,urldecode($facility_type));

		return $att;
	}

  public function pdfsumAtt($schedule,$year,$month,$job_id,$district=FALSE,$facility=FALSE,$facility_type=FALSE){

		ini_set('max_execution_time',0);

		$att=$this->Audit_mdl->sumAtt($schedule,$job_id,$facility,$district,$year,$month,urldecode($facility_type));

		return $att;
	}

//


	public function getFacilityName($id){

			$this->db->select('facility');
			$this->db->where('facility_id',$id);
			$qry=$this->db->get('establishment');

			$res=$qry->row();

			return $res->facility;
	    }
	
	
	//pdfs

	
	public function printEstablishments($district=FALSE,$facility=FALSE,$facility_type=FALSE){
	
		 if($district=="NODST"){
		     
		    $district=FALSE;
		 }
		 
		  if($facility=="NOFC"){
		     
		     $facility=FALSE;
		 }
		 
		  if($facility_type=="NOTYPE"){
		     
		     $facility_type=FALSE;
		 }
		 
		 //
		 
		$data['district']=$district;
		$data['facility']=$facility;
		$data['thistype']=$facility_type;
		
		$data['title']="Facilities Audit Report";
		    
		    if($facility){
		        
		    $facility="facility|".$facility;
		    
		        }
		
		$data['jobs']=$this->getJobs($district,$facility,$facility_type);

        ini_set('max_execution_time',0);
        
		$html=$this->load->view('estab_pdf', $data, true);
		
		$filename=date("y-m-d")."_establishment.pdf";

        $this->load->library('M_pdf');  //or i used ML_pdf for landscape
        
        //$this->m_pdf->pdf->debug = true;
        
        $this->m_pdf->pdf->SetWatermarkImage($this->watermark);
        $this->m_pdf->pdf->showWatermarkImage = true;
        
         ini_set('max_execution_time',0);
         $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
         
         ini_set('max_execution_time',0);
        
        $this->m_pdf->pdf->WriteHTML($html); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
         
        //download it D save F.
        $this->m_pdf->pdf->Output($filename,"D");
	}
	
	

  public function printAttendance($year=FALSE,$month=FALSE,$district=FALSE,$facility=FALSE,$facility_type=FALSE)
	{
		if($district=="NODST"){
		     
		    $district=FALSE;
		 }
		 
		  if($facility=="NOFC"){
		     
		     $facility=FALSE;
		 }
		 
		  if($facility_type=="NOTYPE"){
		     
		     $facility_type=FALSE;
		 }


		 if(!$year){

		 	$year=date('Y');
		 }
		 
		 if(!$month){

		 	$m=date('m');
		 	$month=date('F',strtotime('2018-'.$m.'-d'));
		 }

        
	    $data['thismonth']=$month;
	    $data['thisyear']=$year;
	    $data['thistype']=$facility_type;
	    $data['facility']=$facility;
	    $data['district']=$district;
	    
		$data['title']="Comparative Attendance Summary";
		$data['attendances']=$this->Audit_mdl->getAtt($district,$facility,$year,$month,urldecode($facility_type));

		$data['schedules']=$this->Audit_mdl->getSchedules();
		
		$html=$this->load->view("print_att", $data, true);
		
		//print_r($data['attendances']);
	
		$filename=date("y-m-d")."_attendance.pdf";
		
        $this->load->library('M_pdf');  //or i used ML_pdf for landscape
        
        $this->m_pdf->pdf->SetWatermarkImage($this->watermark);
        $this->m_pdf->pdf->showWatermarkImage = true;
        
         ini_set('max_execution_time',0);
         $PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        
        
         ini_set('max_execution_time',0);
        
        $this->m_pdf->pdf->WriteHTML($PDFContent ); //ml_pdf because we loaded the library ml_pdf for landscape format not m_pdf
         
        //download it D save F.
        $this->m_pdf->pdf->Output($filename,"D");

	}


	


}
