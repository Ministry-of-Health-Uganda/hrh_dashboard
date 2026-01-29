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
	
	public function auditReportData(){
		// Server-side DataTables endpoint
		Modules::run('dataprep/shareModel');
		
		$start = $this->input->post('start') ? $this->input->post('start') : 0;
		$length = $this->input->post('length') ? $this->input->post('length') : 10;
		$searchValue = $this->input->post('search')['value'] ? $this->input->post('search')['value'] : '';
		$orderColumn = $this->input->post('order')[0]['column'] ? $this->input->post('order')[0]['column'] : 0;
		$orderDir = $this->input->post('order')[0]['dir'] ? $this->input->post('order')[0]['dir'] : 'asc';
		
		$result = $this->auditMdl->getAuditReport(FALSE, true, $start, $length, $searchValue, $orderColumn, $orderDir);
		
		$search = (Object) $this->input->post();
		$aggColumn = (!empty($search->aggregate)) ? $search->aggregate : "job_name";
		
		$data = array();
		foreach ($result['data'] as $row) {
			$structure = $row->approved;
			$difference = $row->approved - $row->filled;
			$vacantPosts = ($difference > 0) ? $difference : 0;
			$excessPosts = ($difference < 0) ? $difference * -1 : 0;
			
			$male = ($structure > 0) ? ($row->male / $row->filled) * 100 : 0;
			$female = ($structure > 0) ? ($row->female / $row->filled) * 100 : 0;
			$vacant = ($structure > 0) ? ($vacantPosts / $structure) * 100 : 0;
			$filled = ($structure > 0) ? ($row->filled / $structure) * 100 : 0;
			
			$rowData = array(
				$row->$aggColumn,
				(($search->aggregate == 'job_name') || ($search->aggregate == '')) ? $row->salary_scale : '',
				$row->approved,
				$row->filled,
				$vacantPosts,
				$excessPosts,
				$row->male,
				$row->female,
				($filled > 0) ? number_format($filled, 1) . '%' : '0%',
				($vacant > 0) ? number_format($vacant, 1) . '%' : '0%',
				($male > 0) ? number_format($male, 1) . '%' : '0%',
				($female > 0) ? number_format($female, 1) . '%' : '0%'
			);
			$data[] = $rowData;
		}
		
		$output = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $result['recordsTotal'],
			"recordsFiltered" => $result['recordsFiltered'],
			"data" => $data
		);
		
		echo json_encode($output);
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
