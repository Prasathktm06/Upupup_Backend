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
class Version extends REST_Controller {

	function __construct()
	{
		
		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/version_model");
		
	}
	/////////////////////////Coupon List////////////////////////////////////////////////////
	public function index_get($identifier,$package_name,$user_id)
	{
		$version =$this->version_model->get_checkversion($package_name);
		if(!empty($version)){
		$usercheck =$this->version_model->get_usercheck($user_id);
		if(!empty($usercheck)){
		    $data=array(
			'version'=>$package_name
			);
			$this->version_model->update_user_version($user_id,$data);
		}else{
		  $data= array(
						'user_id'=>$user_id,
						'version'=>$package_name
				);
				$this->version_model->insert_user_version($data,'user_version');	  
		}
		}
		$data 	=$this->version_model->get_version($identifier);
		//echo "<pre>";print_r($data);exit();
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
	///////////////////////////////////////////////////////////////////////////////////////

}
