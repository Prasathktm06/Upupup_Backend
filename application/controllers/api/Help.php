<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Help extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/api_model");

	}

	public function index_get()
	{	
		$data=$this->api_model->get_help();
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}

	public function faq_get()
	{	
		$data['faq']=$this->api_model->get_faq();
		$data['phone']=$this->api_model->get_phone_help();
		if ($data) {
			$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
			);
		}else{
			$result=array(
				'ErrorCode'=>1,
				'Data'=>"",
				'Message'=>"No Data Available"
			);
		}
		
		return $this->response($result,200);
	}

}	
