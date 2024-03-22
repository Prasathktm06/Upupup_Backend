<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Api extends REST_Controller {

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

	public function search_get()
	{
		$word=$_GET['word'];
		$user=$_GET['user_id'];
		$location_id=$this->api_model->get_user_location($user);
		$area_id=$this->api_model->get_user_area($user);
		
		//print_r($area_id);exit;
		$result=$this->api_model->search($user,$word,$location_id,$area_id);
		//print_r($result);exit;


		$result=array(
					'ErrorCode'=>0,
					'Data'=>$result,
					'Message'=>""
			);
			return $this->response($result,200);
	}

		
}
