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
class Notification extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/api_model");
		$this->load->model("api/venue_model");
	}

	public function index_get($user)
	{
		$data=$this->api_model->get_venue($user);
		//print_r($data);exit;
		$venue_sports=[];
		foreach ($data as $key => $value) {
			$value->gallery_image=$this->venue_model->venue_images($value->id);
		}

		$sum=0;
		$sports=[];

		foreach ($data as $key=>$val){
		//	print_r($data);exit;
			$val->book_status=$val->book_status;
		//	$val->sports=explode(',', $val->sports);
		//	$val->sports_image=explode(',', $val->sports_image);
			$val->facility=explode(',', $val->facility);
			$val->title="New Offer in ".$val->area;
			//$val->offer_image=$val->offer_image;
			$val->offer=$val->offer ." from ". $val->start ." to ". $val->end .". Get Upto ".$val->percentage." % Off.";
			//$val->court_cost=explode(',', $val->court_cost);
		//	$val->sports_id=explode(',', $val->sports_id);
			//$val->court_sports=explode(',', $val->court_sports);
		//	$val->total_cost=($val->percentage*$val->cost)/100;

			$rating=$this->venue_model->get_venue_rating($val->id);
			if($rating){
			$rating=explode(',',$rating->rating);
			$val->rating=array_sum($rating);
		}else{
			$val->rating=0;
		}
		}

		foreach ($data as $key => $value) {


			$result=$this->venue_model->get_venue_court($value->id);

			//print_r($result);
			if(!empty($result)){
			//exit;
			$value->court=explode(',', $result->court);
			$value->court_id=explode(',', $result->court_id);
			$value->court_cost=explode(',', $result->court_cost);
			$value->sports_id=explode(',', $result->sports_id);
			$value->sports=explode(',', $result->sports);
			$value->sports_image=explode(',', $result->image);

			$value->venue_image=explode(',', $value->image);

			$value->notification_id=$value->notification_id;
			$value->venue_sports=explode(',', $value->venue_sports);
			$value->venue_sports_id=explode(',', $value->venue_sports_id);
			$value->venue_sports_image=explode(',', $value->venue_sports_image);
			}else{
			$value->court=array();
			$value->court_id=array();
			$value->court_cost=array();
			$value->sports_id=array();
			$value->sports_image=array();
			$value->venue_image=array();
			$value->notification='';
			$value->venue_sports=array();
			$value->venue_sports_id=array();
			$value->venue_sports_image=array();
			}
			@$result->notification_id='';
			@$result->venue_image='';
	//	print_r($result);
		}

	//	exit;
		$court=array();
		foreach ($data as $key => $value) {
			foreach ($value->court as $key2 => $value2) {
				if($value->percentage==0){
					$offer_price=$value->court_cost[$key2];
				}else{
					$offer_price=((int)$value->percentage/(int)$value->court_cost[$key2])*100;

				}
				$value->percentage2=$value->percentage."%";
					$offer_price=number_format((float)$offer_price, 2, '.', '');
				$sports[]=array(
						'sports_id'=>$value->sports_id[$key2],
						'sports'=>$value->sports[$key2],
						'image'=>$value->sports_image[$key2]

					);
					$sports=array($sports[count($sports)-1]);
				$court[]=array(
						'court_id'=>$value->court_id[$key2],
						'court'=>$value2,
						'offer_price'=>$offer_price,
						'cost'=>$value->court_cost[$key2],
						'offer'=>$value->percentage."%",
						'sports'=>$sports


					);

			}
			foreach ($value->venue_sports as $key3 => $value3) {
				$venue_sports[]=array(
						'sports_id'=>$value->venue_sports_id[$key3],
						'sports'=>$value3,
						'image'=>$value->venue_sports_image[$key3]

					);
			}
			unset($value->sports_id,$value->sports,$value->court,$value->court_cost,$value->court_id,$value->sports_image,$value->percentage);
			$value->court=$court;
			$value->venue_sports_2=($venue_sports?$venue_sports:array());
			$court=array();
			$sports=array();
			$venue_sports=array();
			//$value->sports=$sports;
		}


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
	public function delete_get($id='')
	{
		$data=$this->api_model->delete_notification($id);
		$result=array(
					'ErrorCode'=>0,
					'Data'=>'',
					'Message'=>"Success"
			);
			return $this->response($result,200);
	}

	//////////////////////////////////////////////////////////////////////////////
	public function offer_notification_get($user){
		$notification=$this->api_model->get_venue_user($user);
		//print_r($notification);exit;
		foreach ($notification as $key => $value) {
			$notification[$key]=$this->api_model->offer_notification($value->id);
		}

		$data=array();
		foreach ($notification as $key2 => $value2) {
			foreach ($value2 as $key3 => $value3) {
				array_push($data,$value3);
			}
		}
		
		usort( $data, function( $a, $b ){
                        if($a->send_date == $b->send_date ) {
                            return 0;
                        }
                    return ($a->send_date > $b->send_date ) ? -1 : 1;
                    });
		//print_r($data);exit();
		if(!empty($data)){
			$result=array(
					'ErrorCode'=>0,
					'Data'=>$data,
					'Message'=>""
			);
			return $this->response($result,200);
		}else {
			$result=array(
					'ErrorCode'=>1,
					'Data'=>$data,
					'Message'=>"No Data Found"
			);
			return $this->response($result,200);
		}
	}

}
