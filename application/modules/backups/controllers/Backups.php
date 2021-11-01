<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backups extends MX_Controller {
	
	
	public function __Construct(){

		parent::__Construct();
        $this->backupdir = "/var/HRH_bkp";
        $this->db_user = 'ihris_manage';
        $this->db_pass = 'managi123';
        $this->host='172.27.1.109';
        $this->database ='information_schema';
        $this->ignore_list= array('old_distance','municipal_manage','moh_manage','mohattendance','mohattdemo','manage','ihris_ims','covid','demo_chwr','dhishrisanalysis','dutyrosterdemo','DES_demo', 'information_schema','phpmyadmin','performance_schema', 'mysql','demo_manage','train_demo');
        $this->suffix = date('Y-m-d');
	}
public function index(){
    //echo "Test";
}

public function dbcon(){
    $dbConn = new mysqli($this->host,$this->db_user,$this->db_pass,$this->database);
    if($dbConn->connect_error)
    {
	die("Database Connection Error, Error No.: ".$dbConn->connect_errno." | ".$dbConn->connect_error);
    }
return $dbConn;
}
public function db_backup(){
    $dbs = $this->dbcon()->query("SELECT schema_name FROM schemata where schema_name NOT IN ( '" . implode( "','", $this->ignore_list ) . "' )" );
    $backups = array();
    while( $data = $dbs->fetch_assoc() ) {
    $backups[] = $data['schema_name'];
    }
    //mysqli_free_result( $result );
    $dbs->free();
//print_r($backups);
foreach( $backups as $backup_db ) {
    $use_dir = $this->backupdir . "/" . $backup_db;
    if ( !is_dir( $use_dir ) ) {
    mkdir( $use_dir );
    }       
    $result = $this->dbcon()->query("SELECT table_name FROM tables WHERE table_schema = '$this->backup_db' AND table_name not like 'hippo_%' AND table_name not like 'zebra_%'" ) ;
    $tables = array();
    // print_r($tables);}
    while ( $data = $result->fetch_assoc()) {
        $tables[] = $data['table_name'];
    }
  
    //mysqli_free_result( $result );
    $result->free();
    echo "Backing up $backup_db...\n";
    exec( "mysqldump -u $this->db_user -h $this->host --password=$this->db_pass $backup_db  " . implode( " ", $tables ) . "| bzip2 > $use_dir/backup_${backup_db}_$this->suffix.sql.bz2" );
    //exec( "bzip2 $use_dir/backup_${backup_db}_$this->suffix.sql" );
}

   $this->dbcon()->close();

    exec( "find $this->backupdir -type f -mtime +7 -not -name \"*-01.sql.bz2\" -print -exec rm {} \;" );


}




}
