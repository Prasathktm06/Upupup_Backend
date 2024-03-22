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
class Sports extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/sports_model");

	}
	

	public function index_get($id="")
	{
		$data=$this->sports_model->get_details('sports');
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}
	
	public function edit_user_sports_post(){
		$user_id=$this->input->post('user_id');
		$sports=json_decode($this->input->post('sports'));
		
		$this->sports_model->delete_user_sports($user_id);
		foreach ($sports as $val){
			$data = array(
					'sports_id' =>$val,
					'user_id'	=>	$user_id
			);
			$data=$this->sports_model->update_user_sports($data);
		}
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>"Sports Updated "
		);
		return $this->response($result,200);
		
	}

	public function get_user_sports_get($id){
		$data=$this->sports_model->get_user_sports($id);
		
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>"Failed"
		);
		return $this->response($result,200);
	}

}
