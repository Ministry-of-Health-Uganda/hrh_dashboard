<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indicator_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();
		

	}
public function kpiData(){

	$query=$this->db->query("SELECT s.id as sid,k.*,s.* FROM kpi k left join subject_areas s on s.id=k.subject_area ORDER BY kpi_id ASC");
	return $query->result();
}
public function navkpi($id){


	$this->db->where('subject_area', $id);
	$this->db->join("subject_areas", "subject_areas.id=$id");
	$query=$this->db->get('kpi');
	return $query->result();
}
public function subjectData(){
	
	$query=$this->db->query("SELECT * FROM subject_areas");


	return $query->result();
}

public function addKpi($data){
      $query = $this->db->insert('kpi',$data);
	  if ($query){
		  $message ="Succesful";
	  } else{
	     $message ="Failed";

      return $data;
	  }
}
public function addSubject($data){
      $query = $this->db->insert('subject_areas',$data);
	  if ($query){
		  $message ="Succesful";
	  } else{
	     $message = "Failed";

      return $data;
	  }
}
public function kpiDisplayData(){

	$query=$this->db->query("SELECT kpi.kpi_id, subject_areas.name, kpi.indicator_statement,kpi_displays.dashboard_index,kpi_displays.subject_index FROM kpi left join subject_areas on subject_areas.id=kpi.subject_area left join kpi_displays on kpi.kpi_id=kpi_displays.kpi_id");
	return $query->result();
}


public function kpiSummaryData(){

	$query=$this->db->query("SELECT kpi.kpi_id, report_kpi_summary.kpi_id, short_name, subject_areas.name FROM kpi,report_kpi_summary,subject_areas WHERE kpi.kpi_id=report_kpi_summary.kpi_id AND subject_areas.id=kpi.subject_area order by subject_areas.name ASC, short_name ASC ");
	return $query->result();
}
public function insertDisplayData($data){
	 $query = $this->db->replace('kpi_displays',$data);
	  if ($query){
		  $message ="Succesful";
	  } else{
	     $message = "Failed";

      return $data;
	  }

}
public function updatekpiData($data){
	
	$query = $this->db->replace('kpi', $data);

	 if ($query){
		 $message ="Succesful";
	 } else{
		$message = "Failed";

	 return $data;
	 }

}
public function getassessment($dateFrom, $dateTo,$institution){
	if(!empty($dateFrom)){
		$dfil="and date between '$dateFrom' and $dateTo ";
	}
	else{
		$dfil="";
	}
	if(!empty($institution)){
		$ifil="and institution like'$institution'";
	}
	else{
		$ifil="";
	}

	
	 $query = $this->db->query("SELECT * from support where date!='' $dfil $ifil order by date DESC");
     $data=$query->result();

	 return $data;
	 }





}
