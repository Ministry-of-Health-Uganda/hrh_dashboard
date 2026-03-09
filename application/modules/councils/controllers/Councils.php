<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Councils extends MX_Controller {
	
	
	public function __Construct(){
		parent::__Construct();
	}

	public function allied(){
		$data['page']     = 'allied';
		$data['module']   = 'councils';
		echo Modules::run('template/layout',$data);
	}

	public function medical(){
		$data['page']     = 'medical';
		$data['module']   = 'councils';
		echo Modules::run('template/layout',$data);
	}

	public function pharmacy(){
		$data['page']     = 'pharmacy';
		$data['module']   = 'councils';
		echo Modules::run('template/layout',$data);
	}

	public function pharma_society(){
		$data['page']     = 'pharmacy_society';
		$data['module']   = 'councils';
		echo Modules::run('template/layout',$data);
	}

}
