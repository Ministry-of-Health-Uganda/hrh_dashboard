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

		$data['legend']	= $this->auditMdl->auditReportLegend($search);
		$data['filters']= $this->DataPrep_mdl->getFilters(true);
		$data['audit']  = $this->auditMdl->getAuditReport();

		if(isset($search->getPdf ) && $search->getPdf == 1):
			//$html     = 
			$this->load->view("audit/audit_report_pdf",$data);
			//$filename = "audit_report_".time().".pdf";
			//Modules::run('template/makePdf',$html,$filename,"D");
		else:
			echo Modules::run('template/layout',$data);
		endif;
	}


	


}
