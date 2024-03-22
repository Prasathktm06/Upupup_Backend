<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Feedback extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/api_model");
		$this->load->library('common');

	}

	public function index_get($user)
	{
		$data=$this->api_model->get_feedback($user);
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}

	public function index_post()
	{

		date_default_timezone_set("Asia/Kolkata");
		$user=$this->input->post('user_id');
		$feedback=$this->input->post('feedback');
		$data=array(
			'user_id'=>$user,
			'feedback'=>$feedback,
			'time'=>date('Y-m-d H:i:s')
			);


		$res=$this->api_model->insert($data,'feedback');
		if($res){
		$email=$this->api_model->get_feedback_mail();
		$user_data=$this->api_model->get_user($user);
		$data['data']= array('user' => $user_data,
	  						'feedback'=>$feedback);
		foreach ($email as $key => $value) {


				$subject='Feedback';
          //$subject="some text";
        
          $to_email = $value->email	;
          //Load email library
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('feedback@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
					$message = $this->load->view('feedback_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
      	}
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
	public function mail_get()
	{


					$subject='Congratulations';		
		          	$to_email = 'jinson.gooseberry@gmail.com'	;
		          	//Load email library 
		          	$this->load->library('email'); 
		          	$this->email->clear();
		          	$config['protocol']    = 'smtp';
		          	$config['smtp_host']    = 'upupup.in';
		          	$config['smtp_port']    = '25';
		          	$config['smtp_timeout'] = '7';
		          	$config['smtp_user']    = 'admin@upupup.in';
		          	$config['smtp_pass']    = '%S+1q)yiC@DW';
		          	$config['charset']    = 'utf-8';
		          	$config['newline']    = '\r\n';
		          	$config['mailtype'] = 'html'; // or html
		          	$config['validation'] = TRUE; // bool whether to validate email or not      
		          	$this->email->initialize($config);
		          	$this->load->library('email', $config);
		          	$this->email->set_newline("\r\n");
		          	$this->email->from('feedback@upupup.in','upUPUP.');
		          	$this->email->to($to_email);
		          	$this->email->subject($subject);
		          //  $message = $this->load->view('user_mail',$data,true);
					$this->email->message('asd');
		          	echo $this->email->send();
	}

}
