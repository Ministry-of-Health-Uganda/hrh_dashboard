<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends MX_Controller {

	
	public function __Construct(){

		parent::__Construct();

		$this->load->model('Audit_mdl','auditMdl');
		$this->watermark=FCPATH."assets/watermark.png";
	}


	public function auditReport(){

		Modules::run('dataprep/shareModel'); //model sharing handle 

		$search = (Object) $this->input->post();
      
        $data['module']     = "audit";
		$data['page']       = "audit_report";
		$data['title']      = "Audit Report";
		$data['uptitle']    = "HRH Audit Report";
		$data['search']     = $search;

		$data['aggTitle']   = $this->auditMdl->getAggregateLabel(@$search->aggregate);
		$data['aggColumn']  = (!empty($search->aggregate))?$search->aggregate:"job_name";

		
		$data['filters']= $this->DataPrep_mdl->getFilters(true);
		$data['audit']  = $this->auditMdl->getAuditReport($facilityId=FALSE);
		$data['legend']	= $this->auditMdl->auditReportLegend($search);

		if(isset($search->getPdf ) && $search->getPdf == 1):
			$html     = $this->load->view("audit/audit_report_pdf",$data,true);
			$filename = $_SESSION['district']."_District_audit_report_".date('Y-m-d_his').".pdf";
			Modules::run('template/makePdf',$html,$filename,"D");
		else:
			echo Modules::run('template/layout',$data);
		endif;
	}
	public function facAudit($facilityId=FALSE){

		Modules::run('dataprep/shareModel'); //model sharing handle 

		$search = (Object) $this->input->post();
      
        $data['module']     = "audit";
	    $data['page']       = "fac_audit_report_pdf";
		$data['title']      = "Facility Audit Report";
		$data['uptitle']    = "HRH Audit Report";
		$data['search']     = $search;

		$data['aggTitle']   = $this->auditMdl->getAggregateLabel(@$search->aggregate);
		$data['aggColumn']  = (!empty($search->aggregate))?$search->aggregate:"job_name";

		
		$data['filters']= $this->DataPrep_mdl->getFilters(true);
		$data['audit']  = $this->auditMdl->getAuditReport($facilityId);
		$data['legend']	= $this->auditMdl->auditReportLegend($search);
		return $data;
	
	}
	public function printfacAudit(){
            if($this->input->post('getPdf')==1){
			$html     = $this->load->view("audit/audit_report_fac_pdf","",true);
			$filename = $_SESSION['district']."_Facilities_audit_report_".date('Y-m-d_his').".pdf";
			Modules::run('template/makePdf',$html,$filename,"D");
			}
			else{
				$this->lfacAudit();
			}
			
	}

	public function lfacAudit(){

        $data['module']     = "audit";
		$data['page']       = "fac_audit";
		$data['title']      = "Facility Audit Report";
		$data['uptitle']    = "HRH Facility Audit Report";
		
	      echo Modules::run('template/layout',$data);
		
	}
	public function district_facility($district){
		
		$data = $this->auditMdl->district_facility($district);
	return $data;
	}

	



	


}
