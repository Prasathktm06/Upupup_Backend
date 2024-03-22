<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Place extends REST_Controller {

	function __construct()
	{
		
		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/place_model");
		
	}	

	public function region_get()
	{
		$data=$this->place_model->get_details('locations');
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}

	public function area_get($location_id="")
	{
		if($location_id==""){
			$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"ERROR- Missing Parameter"
			);
			return $this->response($result,200);
			
		}
		$data=$this->place_model->get_area($location_id);
		

		
		if(!empty($data)){
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
		}else {
			$result=array(
					'ErrorCode'=>0,
					'Data'=>$data,
					'Message'=>"No Data Found"
			);
			return $this->response($result,200);
		}
	}


	
	

}
