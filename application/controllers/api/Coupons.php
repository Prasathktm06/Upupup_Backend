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
class Coupons extends REST_Controller {

	function __construct()
	{
		
		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/coupons_model");
		
	}
	/////////////////////////Coupon List////////////////////////////////////////////////////
	public function index_get($id)
	{
		$data=[];
		$user_coupons=$this->coupons_model->get_user_coupons($id);
		$user_areas=$this->coupons_model->get_user_areas($id);
		$coupons 	=$this->coupons_model->get_coupons($user_coupons,$user_areas);
	
		foreach ($coupons as $key => $value) {
		    foreach($user_areas as $row) {
           $area_id = $row->area_id;
           
		    if($area_id == $value['area_id']){
		      $data[] =array('coupon_id' 		=> $value['coupon_id'],
							   'valid_from' 	=> $value['valid_from'], 
							   'valid_to' 		=> $value['valid_to'],
							   'description' 	=> $value['description'],
							   'coupon_code' 	=> $value['coupon_code'],
							   'coupon_amount' 	=> $value['coupon_value'],
							   'percentage' 	=> $value['percentage'],);  
		    }
		    
		    }
			
		}

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
	


	public function add_coupons_post()
	{
		
		$data=array(
				'coupon_id'=>$this->input->post('coupon_id'),
				'user_id' =>$this->input->post('user_id')
			);
		$this->coupons_model->add_coupon($data);
		if(!empty($data)){
		$result=array(
					'ErrorCode'=>0,
					'Data'=>'',
					'Message'=>"Success"
			);
	}else{
		$result=array(
					'ErrorCode'=>1,
					'Data'=>'',
					'Message'=>"Failed"
			);
	}
		return $this->response($result,200);
		

	}
	public function redeem_coupons_post()
	{
		$coupon=$this->input->post('coupon_id');
		$rate=$this->input->post('rate');

		$data=array(
				'coupon_value'=>$rate
			);
		$res=$this->coupons_model->redeem_coupon($data,$coupon);
		if($res){
		$result=array(
					'ErrorCode'=>0,
					'Data'=>'',
					'Message'=>"Success"
			);
	}else{
		$result=array(
					'ErrorCode'=>0,
					'Data'=>'',
					'Message'=>"Failed"
			);
	}
			return $this->response($result,200);

	}
	///////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////Coupon List////////////////////////////////////////////////////
	public function coupon_details_get()
	{
	    $id=2088;
		$data=[];
		$user_coupons=$this->coupons_model->get_user_coupons($id);
		$user_areas=$this->coupons_model->get_user_areas($id);
		$coupons 	=$this->coupons_model->get_coupons($user_coupons,$user_areas);
		foreach ($coupons as $key => $value) {
		    foreach($user_areas as $row) {
           $area_id = $row->area_id;
           
		    if($area_id == $value['area_id']){
		      $data[] =array('coupon_id' 		=> $value['coupon_id'],
							   'valid_from' 	=> $value['valid_from'], 
							   'valid_to' 		=> $value['valid_to'],
							   'description' 	=> $value['description'],
							   'coupon_code' 	=> $value['coupon_code'],
							   'coupon_amount' 	=> $value['coupon_value'],
							   'percentage' 	=> $value['percentage'],);  
		    }
		    
		    }
			
		}

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
}
