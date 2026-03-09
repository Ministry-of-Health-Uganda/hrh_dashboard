<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_mdl extends CI_Model {

	
	public function __Construct(){

		parent::__Construct();
		

	}
public function supportData(){

	$query=$this->db->get('support_form');
	return $query->result();
}

public function addSupport($data){
      $query = $this->db->insert('support
	  ',$data);

	  
	  if ($query){
		$count=$this->facilitycount($data);
	  } else{
	     $count = 0;
     
	  }
	  return $count;
}
public function facilitycount($data){

	$institution=$data['institution'];
	$sinstitution=$data['institution'];
	$inst=$data['institution'];
	$date=$data['date'];
	$name=$data['name'];
	$staff=$data['current_staff'];
	//get institution Type
	// Test if institution contains the DHO
	if(strpos($institution, 'DHO') !== false){
	$district = explode(" ", $institution)[0];
	$query=$this->db->query("select distinct (ihris_pid) from ihrisdata where district like '$district' AND institution_type='District'");
	$ihriscount= $query->num_rows();
	} 
	elseif((strpos($institution, 'MUNICIPAL') !== false)||(strpos($institution, 'Municipal') !== false)){
		
		$district = explode(" ", $institution)[0];
		$query=$this->db->query("select distinct (ihris_pid) from ihrisdata where district like '$district' AND institution_type='Municipality'");
		$ihriscount= $query->num_rows();
		}
	elseif((strpos($institution, 'CITY') !== false)||(strpos($institution, 'City') !== false)){
		
			$district = explode(" ", $institution)[0];
			$query=$this->db->query("select distinct (ihris_pid) from ihrisdata where district like '$district' AND institution_type='City'");
			$ihriscount= $query->num_rows();
			}
	else{
	$type=$this->insititutionType($sinstitution);
	// cater for municipal offices
	$query=$this->db->query("select distinct (ihris_pid) from ihrisdata where facility='$inst' AND institution_type='$type'");
	$ihriscount = $query->num_rows();
    }
	$this->db->set("ihris_staff", "$ihriscount");
	$this->db->where('institution', "$inst");
	$this->db->where('name', "$name");
	$this->db->where('date', "$date");
    $this->db->where('current_staff', "$staff");
	$this->db->update('support');

 return $ihriscount;
}

public function insititutionType($institution){
	$query=$this->db->query("SELECT institution_type from ihrisdata where facility like '".$institution."' ");
    $type=$query->result();
	return $type->institution_type;
}
public function kpiDisplayData(){

	$query=$this->db->query("SELECT kpi.kpi_id, subject_areas.name, kpi.indicator_statement,kpi_displays.dashboard_index,kpi_displays.subject_index FROM kpi left join subject_areas on subject_areas.id=kpi.subject_area left join kpi_displays on kpi.kpi_id=kpi_displays.kpi_id");
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



}
