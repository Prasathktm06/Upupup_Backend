<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Login extends REST_Controller {

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
	
	public function getAllUser(){
		$data=$this->login_model->get_users($this->input->post('phone_no'));
		return $this->response($data,200);

	}

	public function index_post(){
	
			$data=$this->login_model->get_users($this->input->post('phone_no'));

			if(empty($data)){
				$otp_code=1234;//rand(1000,9999);
				$data=	array(
						'phone_no'=>$this->input->post('phone_no'),
						'device_id'=>$this->input->post('device_id')
				        );
				$user_id=$this->login_model->add_users($data);
				$data=array(
							'otp'=>$otp_code,
							'user_id'=>$user_id
						);
				$this->login_model->add_otp($data);
				$user=$this->login_model->get_users($this->input->post('phone_no'));

				$user->auth=false;
				$user->pref=false;
				$result=array(
					    'ErrorCode'=>0,
					    'Data'=>$user,
					    'Message'=>""
			            );
			    //$Message=urlencode("Dear user,Your verification code for upUPUP is $otp_code");
			    //$this->common->sms($this->input->post('phone_no'),$Message);
			    return $this->response($result,200);
			}else{
					
				$otpcheck=$this->login_model->get_otp_check($data->id);
				if (empty($otpcheck)) {
					$otp_code=1234;//rand(1000,9999);
					$data=array(
							'otp'=>$otp_code,
							'user_id'=>$data->id
						);
					$this->login_model->add_otp($data);
				}else{
					$otp_code=1234;//rand(1000,9999);
					$this->login_model->update_otp($otp_code,$data->id);
				}
				$data=array(
							'device_id'=>$this->input->post('device_id')
						);
				$this->login_model->update_device_id($data,$this->input->post('phone_no'));
				$user=$this->login_model->get_users($this->input->post('phone_no'));
				$auth=$this->login_model->get_auth($user->id);
				$userPref=$this->login_model->get_user_area($user->id);
				$userPrefsport=$this->login_model->get_user_sport($user->id);

				if (empty($userPref) || empty($userPrefsport) ) {
					$user->pref=false;
				}else{
					$user->pref=true;
				}
				if(empty($auth)){
					$user->auth=false;
				}else{
					$user->auth=true;
				}
				if(!empty($userPref[0])){
					$user->location=$userPref[0]->location_id;
					$user->location_name=$userPref[0]->location;
				}else{
					$user->location="";
					$user->location_name=""; 
				}
					
				$result=array(
					    'ErrorCode'=>0,
					    'Data'=>$user,
					    'Message'=>""
			            );
			//$Message=urlencode("Dear user,Your verification code for upUPUP is $otp_code");
			//$this->common->sms($this->input->post('phone_no'),$Message);
			return $this->response($result,200);

		}
	
	}
	public function otp_verify_post(){
	    $user_id=	$this->input->post('user_id');
		$otp=$this->input->post('otp');
		$res=$this->login_model->otp_verify($user_id,$otp);

		if($res){
			$result=array(
					'ErrorCode'=>0,
					'Data'=>"",
					'Message'=>"Success"
			);

		}else{
			$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"Failed"
			);
		}
		return $this->response($result,200);
	}

	public function get_otp_get($user){
		$otp_code=	rand(1000,9999);
		$data=$this->login_model->update_otp($otp_code,$user);
		if($data){
			$phone_no=$this->login_model->get_phone_no($user)->phone_no;

			$this->common->sms(str_replace(' ', '', $phone_no),"Dear%20user,Your%20verification%20code%20for%20upUPUP%20is%20$otp_code");

			$result=array(
						'ErrorCode'=>0,
						'Data'=>array('otp'=>$otp_code),
						'Message'=>"OTP Regenerated Successfully"
					);
		}else{
			$result=array(
				'ErrorCode'=>1,
				'Data'=>'',
				'Message'=>"OTP Failed To Regenerate"
			);
		}
		return $this->response($result,200);
	}


	public function auth_post()
	{
		$user_id=$this->input->post('user_id');
		$auth=$this->input->post('auth');
		$name=$this->input->post('name');
		$email=$this->input->post('email');
		$image=$this->input->post('image');
		if (empty($image)) {
			$image=base_url()."pics/upupup.png";
		}
		$data=array(
				'name'=>$name,
				'image'=>$image,
				'email'=>$email
			);
		$this->login_model->update_user($user_id,$data);
		$data=$this->login_model->get_auth($user_id);
		//print_r($data);exit;
		if($data){
			$result1=$this->login_model->update_auth($user_id);
			$result=array(
					'ErrorCode'=>0,
					'Data'=>"",
					'Message'=>"Success"
			);
		}else{

			$result=array(
				'ErrorCode'=>0,
				'Data'=>"",
				'Message'=>"Success"
			);
			$data=array(
					'user_id'=>$user_id,
					'facebook_id'=>$auth
				);
			$result1=$this->login_model->insert_auth($data);
		}
		if (!$result1) {
			$result=array(
				'ErrorCode'=>1,
				'Data'=>"",
				'Message'=>"Failed"
			);
		}

		return $this->response($result,200);
	}





}
