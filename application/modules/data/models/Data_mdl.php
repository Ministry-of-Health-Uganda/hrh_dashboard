<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_mdl extends CI_Model {

	
public function __Construct(){

		parent::__Construct();

//financial_year is from settings financial Year/ allows 1 2 for current and previous year respectively
$this->financial_year=$this->financialYear();
$this->fyperiods=$this->generatePeriods(); //not used anywhere though

//need to cater for different period types like annual, bi-annual, depending on the KPI. Best done in the cron job
$this->period=str_replace(" ","", "Q".$this->period());
$this->preperiod=@str_replace(" ","", "Q".($this->period()-1));

}
public function financialYear($financial_year=FALSE){
	 $query=$this->db->get('setting');
	 $result=$query->row();
if(empty($financial_year)){
	  // unset($_SESSION['financial_year']);
return $_SESSION['financial_year']=str_replace(" ","",$result->financial_year);
	 }
else{
	//  unset($_SESSION['financial_year']);
return $_SESSION['financial_year']=str_replace(" ","",$this->input->post('financial_year'));
}

}

//get the most recent period
public function period(){
  //sum
    $this->db->select_max('period');
    $this->db->where("financial_year","$this->financial_year");
    $query = $this->db->get("new_data");
	$data=$query->result();

	
	$maxperiod= str_replace("Q","",$data[0]->period);
	return $maxperiod;
  
}

public function gaugeDetails($kpi){

	$query = $this->db->query("SELECT DISTINCT k.* from kpi k WHERE kpi_id='$kpi'");
	return $query->result();
}
//neeed fixing


public function gaugeConfig($kpi){
    $this->db->where('kpi', 'ALL');
	$query = $this->db->get('gauge_config');
	return $query->result();
}

public function generateperiods(){
		$periods=array();
		for ($i=1; $i<=4; $i++){
			$id='Q'.$i;
		 array_push($periods,$id);
    }
		
    return $periods;
}

public function getallperiods($kpi){
    $queryp=$this->db->query("SELECT DISTINCT period from new_data where trim(financial_year)='$this->financial_year' and kpi_id='$kpi' order by period ASC");
	
	return $resps=$queryp->result();

}


//subject areas Dashboard
 public function subjectDash($subject_id){
	 
	 $this->db->where("subject_area",$subject_id);
	$query= $this->db->get('kpi');

	return $query->result();
}

//dimension 0
public function barperiodTotals($kpi){
	//get available financial years from the datasets

	$allps=$this->getallperiods($kpi);
	$barperiodTotals = array();
	foreach ($allps as $resp):
		$value=str_replace(" ","",$resp->period);
		$query1 = $this->db->query("SELECT (SUM(calc_value)/ COUNT(calc_value)) as current_value,kpi_id ,replace(CONCAT(kpi_id,period,financial_year),' ','') as entry_id, MAX(data_target) as target_value, financial_year, period from new_data WHERE kpi_id='$kpi' and trim(financial_year) = '$this->financial_year' and trim(period) = '$value'");
		$result1=$query1->row();
		array_push($barperiodTotals,$result1);  
	endforeach;
	$this->db->insert_batch("report_kpi_trend",$barperiodTotals);
	return $barperiodTotals;
}
//dimension 1
public function dimension1Data($kpi){

	$dim1  = $this->dimension1($kpi);
	$allps = $this->getallperiods($kpi);

	$allDimesiondata = array(); // data for all period, all dimensions
	$insertable = array(); 
	
	$sereis_data= array();
	$row_data=array();
	$graphData = array();
	$count=0;
	foreach ($allps as $resp):
		$period_data = array(); //for each period
		$period = str_replace(" ","",$resp->period);
		foreach($dim1 as $dm):
			$count++;
			$dms1   = str_replace(" ","",$dm->dimension1);
		    $query1 = $this->db->query(
				"SELECT (SUM(calc_value)/ COUNT(calc_value)) as cal_value, 
			     MAX(data_target) as target_value,
				 dimension1_key, dimension1, financial_year, 
				 period from new_data 
				 WHERE kpi_id = '$kpi' 
				 and trim(financial_year) = '$this->financial_year'
				 and trim(period) = '$period' 
				 and dimension1 = '$dms1'")->row(); 
				 array_push($period_data,$query1);
				 if(!empty($query1->dimension1))
				 array_push($insertable ,$query1);
				 //insert_batch(table, array( array(),array()) )
				
				 array_push($row_data ,$query1->cal_value);
				if($count == count($dim1)) 
				 {
					$graphData["name"] = $dms1;
					$graphData["data"] = $row_data;
					array_push($sereis_data, $graphData);
					//$row_data = [];
					//$graphData = [];
				 }
				 
		endforeach;
		$allDimesiondata[$period] = $period_data;
    endforeach;

	//$this->db->insert_batch('report_trend_dimension1',$insertable);
	return $allDimesiondata;
}

//dimension2
public function dimension2Data($kpi){

	$dim1  = $this->dimension1($kpi);
	$dim2  = $this->dimension2($kpi);
	$allps = $this->getallperiods($kpi);

	$allDimesiondata = array(); // data for all period, all dimensions
	$insertable = array();
	$dimensionTwoData = array();
	//foreach ($dim1 as $dms1):
		//$dimensionOne   = str_replace(" ","",$dms1->dimension1);
		foreach ($allps as $resp):
			$period_data = array(); //for each period
			$period = str_replace(" ","",$resp->period);
			foreach($dim2 as $dm):
				$dms2   = str_replace(" ","",$dm->dimension2);
				$query1 = $this->db->query(
					"SELECT (SUM(calc_value)/ COUNT(calc_value)) as cal_value, kpi_id,
					MAX(data_target) as target_value,
					dimension2_key, dimension2,dimension1_key, dimension1, financial_year, 
					period from new_data 
					WHERE kpi_id = '$kpi' 
					and trim(financial_year) = '$this->financial_year'
					and trim(period) = '$period' 
					and replace(dimension2,' ','') ='$dms2'")->row(); 

					array_push($period_data,$query1);
					if(!empty($query1->dimension2))
					array_push($insertable ,$query1);
					//insert_batch(table, array( array(),array()) )
			endforeach;
			$allDimesiondata[$period] = $period_data; // nest under period e.g Q1
			//array_push($allDimesiondata ,$period_data);
		endforeach;
		//$dimensionTwoData[$dimensionOne]=$allDimesiondata; //nest under dimension one
	//endforeach;
	$this->db->insert_batch('report_trend_dimension2',$insertable);
	return $insertable;
}
//dimension3
public function dimension3Data($kpi){

	$dim1  = $this->dimension1($kpi);
	$dim2  = $this->dimension2($kpi);
	$dim3  = $this->dimension3($kpi);
	$allps = $this->getallperiods($kpi);

	$periodsdata = array(); // data for all period, all dimensions
	$insertable = array();
	$dimensionTwoData = array(); //2nd highest
	$dimensionOneData = array(); //highest level

	// foreach ($dim1 as $dms1):
	// 	$dimension1  = str_replace(" ","",$dms1->dimension1);
	// foreach ($dim2 as $dms2):
	// 	$dimension2  = str_replace(" ","",$dms2->dimension2);
		foreach ($allps as $resp):
			$period_data = array(); //for each period
			$period = str_replace(" ","",$resp->period);
			foreach($dim3 as $dm):
				$dms3   = str_replace(" ","",$dm->dimension3);
				$query1 = $this->db->query(
					"SELECT (SUM(calc_value)/ COUNT(calc_value)) as cal_value, kpi_id,
					MAX(data_target) as target_value,
					dimension3_key, dimension3,dimension2_key, dimension2,dimension1_key, dimension1, financial_year, 
					period from new_data 
					WHERE kpi_id = '$kpi' 
					and trim(financial_year) = '$this->financial_year'
					and trim(period) = '$period' 
					and replace(dimension3,' ','') ='$dms3'")->row(); 

					array_push($period_data,$query1);
					if(!empty($query1->dimension3))
					 array_push($insertable ,$query1);
					//insert_batch(table, array( array(),array()) )
			endforeach;
			//$allDimesiondata[$period] = $period_data; // nest under period e.g Q1
			//array_push($allDimesiondata ,$period_data);
		endforeach;
	// 	$dimensionTwoData[$dimension2]=$periodsdata; //nest periods under dimension one
	// endforeach;
	// $dimensionOneData[$dimension1] = $dimensionTwoData; //nest dim2 into dim1
	// endforeach;
	 $this->db->insert_batch('report_trend_dimension3',$insertable);
	return $insertable; //wraps 2 & 1 1=[2[3[]]]
}


 //get dimension1
public function  dimension1($kpi){
	$query=$this->db->query("SELECT distinct kpi_id,dimension1,dimension1_key from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
return $query->result(); 
}

 //get dimension2
public function  dimension2($kpi){
	$query=$this->db->query("SELECT distinct kpi_id,dimension2,dimension2_key from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
    return $query->result(); 
}

 //get dimension3
public function  dimension3($kpi){
	$query=$this->db->query("SELECT distinct kpi_id,dimension3,dimension3_key from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
return $query->result(); 
}
public function kpi_name($kpi){  
    $query=$this->db->query("SELECT distinct indicator_statement from kpi where kpi_id='$kpi'");
$res=$query->row();
return $res->indicator_statement;
}
public function subject_name($kpi){  
    $query=$this->db->query("SELECT * from kpi,subject_areas where kpi.subject_area=subject_areas.id and kpi_id='$kpi'");
$res=$query->row();
return $res->name;
}
public function  dimalldisplay($kpi){
	$query=$this->db->query("SELECT distinct dimension1_key,dimension2_key,dimension3_key,kpi_id from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
return $query->result(); 
}
public function  dim1display($kpi){
	$query=$this->db->query("SELECT distinct dimension1_key,kpi_id from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
return $query->result(); 
}
public function  dim2display($kpi){
	$query=$this->db->query("SELECT distinct dimension2_key,kpi_id from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
return $query->result(); 
}
public function  dim3display($kpi){
	$query=$this->db->query("SELECT distinct dimension3_key,kpi_id from new_data where kpi_id='$kpi' and trim(financial_year) = '$this->financial_year'");
return $query->result(); 
}





}
