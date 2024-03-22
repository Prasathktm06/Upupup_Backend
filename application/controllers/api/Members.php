<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Members extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/login_model");

	}

	public function index_post(){
	       $user_id=$this->input->post('user_id');
			$data=$this->login_model->get_member($this->input->post('user_id'));

           if($data){


		
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result=array(
				
				"not exist"
		     );
		     return $this->response($result,200);
	        }

	}
	


}
