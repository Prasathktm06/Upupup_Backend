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
class Callbook extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/callbook_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");
	}
///////////////// call booking enquiry //////////////////////
public function callenquire_post(){
         
		$venue_id=$this->input->post('venue_id');
		$user_id=$this->input->post('user_id');
		$owner=$this->callbook_model->get_ownerdeatils($venue_id);
		foreach($owner as $row) {
              $email = $row->email;
              $phone = $row->phone;
          }
        $user=$this->callbook_model->get_userdeatils($user_id);
        if(!empty($user)){

        	foreach($user as $row) {
              $user_name = $row->name;
              $user_phone = $row->phone_no;
          }
	          if( $user_name=="upUPUP User"){

	          	$message="An upUPUP user has an enquiry about your sports venue. \nDetails: mob:".$user_phone." \nupUPUP- Let's Play again";

	          }else{

	          	$message="An upUPUP user has an enquiry about your sports venue. \nDetails: ".$user_name.", mob:".$user_phone." \nupUPUP- Let's Play again";

	          }
        }
        
        $this->common->sms(str_replace(' ', '', $phone),urlencode($message));
        $data=array(
            'user_id'=>$user_id,
            'venue_id'=>$venue_id,
            'added_date'=>date('Y-m-d H:i:s'),
            );
    	$this->callbook_model->add_call_book('call_book',$data);  
  	}
  ///////////////////////// service charge start////////////////////////////////
	public function service_charge_post()
	{
      	
      	$location_id=$this->input->post('location_id');
      	$service_charge=$this->callbook_model->get_service_charge($location_id);
      	if(!empty($service_charge))
      	{
				foreach($service_charge as $row) {
		              $id = $row->id;
		              $amount = $row->amount;
		          }
                            $charge=array(
                                    'id'=>(int)$id,
                                    'amount'=>(int)$amount,
                            );
		         if(!empty($charge))
		         {
		                    $result=array(
		                            'errorCode'=>1,
		                            'data'=>$charge,
		                            'message'=>"success"
		                            );
		                    return $this->response($result,200);
		         }else{
		              $charge=array(
                                    'id'=>0,
                                    'amount'=>0,
                            );
		                    $result=array(
		                            'errorCode'=>0,
		                            'data'=>$charge,
		                            'message'=>"empty"
		                            );
		                    return $this->response($result,200);
		         }
      	}else{
		              $charge=array(
                                    'id'=>0,
                                    'amount'=>0,
                            );
		                    $result=array(
		                            'errorCode'=>0,
		                            'data'=>$charge,
		                            'message'=>"empty"
		                            );
		                    return $this->response($result,200);

      	}
  
  	}
  ///////////////////////// service charge end ////////////////////////////////
  ///////////////////////// booking service charge start////////////////////////////////
	public function booking_charge_post()
	{
      	
      	$booking_id=$this->input->post('booking_id');
      	$service_charge_id=$this->input->post('id');
      	$amount=$this->input->post('amount');
      	$total_service_charge=$this->input->post('total_charge');

      	   	$data=array(
          		'booking_id'=>$booking_id,
      			'service_charge_id'=>$service_charge_id,
      			'amount '=>$amount,
      			'total_service_charge'=>(int)$total_service_charge,
      			'added_date'=>date('Y-m-d H:i:s'),
      		);
        $adds=$this->callbook_model->insert_add('service_charge_booking',$data);
        if($adds)
        {
		                    $result=array(
		                            'errorCode'=>1,
		                            'data'=>$adds,
		                            'message'=>"success"
		                            );
		                    return $this->response($result,200);
        }else{
		                    $result=array(
		                            'errorCode'=>0,
		                            'data'=>0,
		                            'message'=>"empty"
		                            );
		                    return $this->response($result,200);	
        }
  
  	}
  ///////////////////////// booking service charge end ////////////////////////////////

}
