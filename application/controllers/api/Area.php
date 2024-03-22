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
class Area extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/area_model");

	}



	public function edit_user_area_post()
	{
		$user_id=$this->input->post('user_id');
		$area=json_decode($this->input->post('area'));
	    $res= $this->area_model->delete_user_area($user_id);
	   // print_r($res);exit;
	
			foreach ($area as $val){
			$data	=array(
					'user_id'=>$user_id,
					'area_id'=>$val
			);
			$data=$this->area_model->update_user_area($data);

			}
			$result=array(
					'ErrorCode'=>0,
					'Data'=>$data,
					'Message'=>"Area Updated"
			);
	  
		return $this->response($result,200);
	}

	public function get_user_area_post(){
	    $user_id=$this->input->post('user_id');
		$data=$this->area_model->get_user_area($user_id);
		
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}


}
