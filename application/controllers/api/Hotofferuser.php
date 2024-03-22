<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Hotofferuser extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/hotofferuser_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");

	}
	
///////////////////////// slots based on court start //////////////////////////
	public function court_slot_post(){
	     
		        $court_id=$this->input->post('court_id');
		        $date=$this->input->post('date');
		        $nameOfDay =date('l', strtotime($date));
		     	$holiday=$this->hotofferuser_model->get_court_holiday($court_id,$date);
		     	if(!empty($holiday)){
		                       
				        	$result=array(
								'errorCode'=>0,
								'data'=>[],
								'message'=>"Today is Holiday"
							);
		        	return $this->response($result,200);
		        }else{
					$court[court]=$this->hotofferuser_model->get_slot($court_id,$nameOfDay,$date);
					foreach($court[court] as $key => $value) {
						$slot_id=(int)$value->slot_id;
						$slotfor=(int)$value->slotfor;
						$courts_id=(int)$value->courts_id;
						$slot_time=$value->time;
						$single_slot=$this->hotofferuser_model->get_single_day($court_id,$nameOfDay,$date,$slot_time);
						if(!empty($single_slot)){
						foreach($single_slot as $key5 => $value5) {
							$slot_id=(int)$value5->slot_id;
							$slotfor=(int)$value5->slotfor;
						}
						}
						$booking=$this->hotofferuser_model->get_bookings($court_id,$slot_time,$date);
						foreach($booking as $key1 => $value1) {
							$capacity=(int)$value1->capacity;	
						}
						$temp_booking=$this->hotofferuser_model->get_temp_bookings($court_id,$slot_time,$date);
						foreach($temp_booking as $key2 => $value2) {
							$temp_capacity=(int)$value2->tempsum;	
						}
						$booked_capacity=$capacity+$temp_capacity;
						$courts_capacity=$this->hotofferuser_model->get_court_capacity($court_id);
						foreach($courts_capacity as $key3 => $value3) {
							$court_capacity=(int)$value3->total_capacity;	
						}
						$remaining_capacity=$court_capacity-$booked_capacity;
						$hot_offer=$this->hotofferuser_model->get_hot_slot($court_id,$slot_id,$date);
						if(!empty($hot_offer)){
						foreach($hot_offer as $key4 => $value4) {
							$hot_id=(int)$value4->id;
							$hot_percentage=(int)$value4->precentage;	
						}
						}
						//return $this->response($hot_offer,200);
						if($remaining_capacity>0 && empty($hot_offer)){
						$slots[]=array(
						'slot_id'=>$slot_id,
						'court_id'=>(int)$value->courts_id,
						'time'=>$value->time,
						'slotfor'=>$slotfor,
						'remaining_capacity'=>$remaining_capacity,
						'is_hot_offer'=>0,
						'hot_percentage'=>0
						);
						}
						/*
						if($remaining_capacity>0 && !empty($hot_offer)){
						$slots[]=array(
						'slot_id'=>$slot_id,
						'court_id'=>(int)$value->courts_id,
						'time'=>$value->time,
						'slotfor'=>$slotfor,
						'remaining_capacity'=>$remaining_capacity,
						'is_hot_offer'=>1,
						'hot_percentage'=>$hot_percentage
						);
						}
						*/
						$cou=$slots;
						
						
					}
					if(!empty($cou)){
					$result=array(
								'errorCode'=>1,
								'data'=>$cou,
								'message'=>"success"
							);
					}else{
					$result=array(
								'errorCode'=>0,
								'data'=>[],
								'message'=>"No available slots"
							);
					}
					
					return $this->response($result,200);
		        }


	}
///////////////////////// slots based on court end //////////////////////////
///////////////////////// add hot offer start //////////////////////////
	public function add_hotoffer_post(){
		$json_output = json_decode(file_get_contents('php://input'));				
  		$venue_id=$json_output->hot_offer->venue_id;
  		$user_id=$json_output->hot_offer->user_id;
  		$offer_name=$json_output->hot_offer->offer_name;
  		$offer_date=$json_output->hot_offer->offer_date;
  		$offer_percentage=(int)$json_output->hot_offer->offer_percentage;
  		$nameOfDay =date('l', strtotime($offer_date));
  	/////////////// check user is inactive  start /////////////////////
  		$manager=$this->hotofferuser_model->get_vendor_active($user_id);
  		if(!empty($manager)){
		                       
			$result=array(
				'errorCode'=>0,
				'data'=>0,
				'message'=>"vendor_inactive"
				);
		        return $this->response($result,200);
		        }
	/////////////// check user is inactive  end /////////////////////
	/////////////// check user is exist  start ///////////////////////

		$vendor=$this->hotofferuser_model->get_vendor_exist($user_id);
  		if(empty($vendor)){
		                       
			$result=array(
				'errorCode'=>0,
				'data'=>0,
				'message'=>"vendor_deleted"
				);
		        return $this->response($result,200);
		        }
		if(empty($manager) && !empty($vendor)){
	/////////////// check user is exist  end ///////////////////////
				$data=array(
      					'name'=>$offer_name,
      					'venue_id'=>$venue_id,
      					'date'=>$offer_date,
      					'precentage'=>$offer_percentage,
      					'status'=>'1',
      					);
		        $hot_id=$this->hotofferuser_model->add_datas('hot_offer',$data);
	/////////////////// insert data on hot_offer_court start ///////////////////
		         for ($j = 0; $j < count($json_output->hot_offer->court_info); $j++) {
					$court_id=$json_output->hot_offer->court_info[$j]->court_id;
					$sports_id=$json_output->hot_offer->court_info[$j]->sports_id;
						
				}
	/////////////////// insert data on hot_offer_court end ///////////////////
	/////////////////// insert data on hot_offer_slot start ///////////////////
				for ($m = 0; $m < count($json_output->hot_offer->court_info); $m++) {

					$court_id=$json_output->hot_offer->court_info[$m]->court_id;
					$sports_id=$json_output->hot_offer->court_info[$m]->sports_id;
					
					$data=array(
		      					'hot_offer_id'=>$hot_id,
		      					'court_id'=>$court_id,
		      					'sports_id'=>$sports_id,
      							);
					$hot_court=$this->hotofferuser_model->add_datas('hot_offer_court',$data);

					 	for ($n = 0; $n < count($json_output->hot_offer->court_info[$m]->slots); $n++) {
						$slot_id=$json_output->hot_offer->court_info[$m]->slots[$n]->slot_id;
						$slotfor=$json_output->hot_offer->court_info[$m]->slots[$n]->slotfor;
						$slot_time=$json_output->hot_offer->court_info[$m]->slots[$n]->slot_time;

							if($slotfor!=0){
						///// not an upupup slot start //////
								 $data=array(
								      'court_id'=>$court_id,
								      'week'=>$nameOfDay,
								      'slotfor'=>0,
								      );
        						$court_time_id=$this->hotofferuser_model->add_datas('court_time',$data);
 

						        $data=array(
								      'court_time_id'=>$court_time_id,
								      'court_id'=>$court_id,
								      'time'=>$slot_time,
								      'date'=>$offer_date,
								      'added_date'=>date('Y-m-d h:i:sa')
								      );
						        $court_time_intervel_id=$this->hotofferuser_model->add_datas('court_time_intervel',$data);
						        $data=array(
								      'court_time_intervel_id'=>$court_time_intervel_id,
								      'hot_offer_id'=>$hot_id,
								      'hot_offer_court_id'=>$hot_court
								      );
						        $hot_offer_slot_id=$this->hotofferuser_model->add_datas('hot_offer_slot',$data);
        				///// not an upupup slot end //////
							}else{

								 $data=array(
								      'court_time_intervel_id'=>$slot_id,
								      'hot_offer_id'=>$hot_id,
								      'hot_offer_court_id'=>$hot_court
								      );
						        $hot_offer_slot_id=$this->hotofferuser_model->add_datas('hot_offer_slot',$data);
							}
								
					 	}
				}
	/////////////////// insert data on hot_offer_slot end /////////////////// 
	//////////////////// hot offer manager details start ///////////////////
				$data=array(
					'hot_offer_id'=>$hot_id,
					'user_id'=>$user_id
					);
			$hot_offer_manager_id=$this->hotofferuser_model->add_datas('hot_offer_manager',$data);
	//////////////////// hot offer manager details end ///////////////////
			if(!empty($hot_id)){
		                       
				$result=array(
					'errorCode'=>1,
					'data'=>(int)$hot_id,
					'message'=>"success"
					);
		        return $this->response($result,200);
			}else{
				$result=array(
					'errorCode'=>0,
					'data'=>0,
					'message'=>"failed"
					);
			return $this->response($result,200);
			}
		}
				

	}
///////////////////////// add hot offer end //////////////////////////
///////////////////////// hot offer details based on venue_id start //////////////////////////
	public function hot_offer_post(){
	     
		        $venue_id=$this->input->post('venue_id');
		        $hot_details=$this->hotofferuser_model->get_hot_details($venue_id);
		        for ($m = 0; $m < count($hot_details); $m++) {
		        $hot_id=(int)$hot_details[$m]->hot_id;
				$hot_name=$hot_details[$m]->hot_name;
				$venue_id=(int)$hot_details[$m]->venue_id;
				$hot_date=$hot_details[$m]->hot_date;
				$hot_percentage=(int)$hot_details[$m]->hot_percentage;
				$hotoffer[]=array(
					'hot_id'=>(int)$hot_id,
					'hot_name'=>$hot_name,
					'hot_date'=>$hot_date,
					'hot_percentage'=>(int)$hot_percentage,
					);
				$hot_offer=$hotoffer;
				$hot_sports=$this->hotofferuser_model->get_hot_sports($hot_id);
				for ($n = 0; $n < count($hot_sports); $n++) {
				 	$sports_id=(int)$hot_sports[$n]->sports_id;
					$sports_name=$hot_sports[$n]->sports_name;
					$sports_image=$hot_sports[$n]->sports_image;
					$sports=[];
					$sports=array(
						'sports_id'=>(int)$sports_id,
						'sports_name'=>$sports_name,
						'sports_image'=>$sports_image
						);
				 }
				 $hotoffer[$m]['sports']=$sports;
				 $hot_court=$this->hotofferuser_model->get_hot_courts($hot_id);
				 for ($p = 0; $p < count($hot_court); $p++) {
				 	$court_id=(int)$hot_court[$p]->court_id;
					$court_name=$hot_court[$p]->court_name;
					$hot_offer_court_id=$hot_court[$p]->hot_offer_court_id;
					$courts=[];
					$courts=array(
						'court_id'=>(int)$court_id,
						'court_name'=>$court_name
						);
				$hotoffer[$m]['courts'][$p]=$courts;
				$hot_slot=$this->hotofferuser_model->get_hot_slots($hot_offer_court_id);
				for ($q = 0; $q < count($hot_slot); $q++) {
					$slot_id=(int)$hot_slot[$q]->slot_id;
					$slot_time=$hot_slot[$q]->slot_time;
					$slotfor=$hot_slot[$q]->slotfor;
					$slots=[];
					$slots=array(
						'slot_id'=>(int)$slot_id,
						'slot_time'=>$slot_time,
						'slotfor'=>$slotfor
						);
				$hotoffer[$m]['courts'][$p]['slots'][$q]=$slots;
				}
				 }
		        }
			if(!empty($hotoffer)){
		                       
				$result=array(
					'errorCode'=>1,
					'data'=>$hotoffer,
					'message'=>"success"
					);
		        return $this->response($result,200);
				}else{
					$result=array(
						'errorCode'=>0,
						'data'=>[],
						'message'=>"failed"
						);
				return $this->response($result,200);
				}
      

	}
///////////////////////// hot offer details based on venue_id end //////////////////////////
///////////////////////// hot offer notification start /////////////////////////
	public function hot_notification_post(){
		$hot_id=$this->input->post('hot_id');
		$venue=$this->hotofferuser_model->get_hot_venue($hot_id);
		foreach($venue as $key1 => $value1) {
			$area_id=(int)$value1->area_id;
			$venue_name=$value1->venue;
			$venue_id=(int)$value1->id;
			}
		$location=$this->hotofferuser_model->get_area_location($area_id);
		foreach($location as $key2 => $value2) {
			$location_id=(int)$value2->location_id;
			$area_name=$value2->area;
			}
		$not_setting=$this->hotofferuser_model->get_not_setting($location_id);
			if(!empty($not_setting)){
				foreach($not_setting as $key3 => $value3) {
				$setting_id=(int)$value3->setting_id;
				}
			}else{
			$setting_id=3;
			}
		$hot_details=$this->hotofferuser_model->get_hot_datas($hot_id);
		foreach($hot_details as $key4 => $value4) {
			$sports_id=(int)$value4->sports_id;
			$sports_name=$value4->sports;
			$hot_percentage=$value4->precentage;
			}
		$slot_details=$this->hotofferuser_model->get_slot_time($hot_id);
		$slot_detail=$this->hotofferuser_model->get_slot_times($hot_id);
		$slot_time="";
		$slot_counter=0;
		$count_slot=count($slot_detail);
			foreach($slot_detail as $key5 => $value5) {
			    $slot_counter++;
				$time=date( ' h:i A ',strtotime($value5->time));
				$slot_times=''.$slot_time.''.$time.'';
				if($slot_counter!=$count_slot){
				 $slot_time_seperation=''.$slot_times.''.",".'';
				$slot_time=$slot_time_seperation;   
				}else{
				  $slot_time=$slot_times;  
				}
				
				}
		$message="".$slot_time." Today @ ".$venue_name.",".$area_name."";
		$title="".$hot_percentage."% Hot Offer | ".$sports_name." ";
		//return $this->response($slot_time,200);
			if($setting_id==1){
				$city_user=$this->hotofferuser_model->get_city_user($location_id);
				
      				$data_push =array('result' => array('message'=> $message,
		                                     'title'  => $title,
		                                     'venue_name'=>$venue_name,
		                                     'sports'=>$sports_name,
		                                     'slots'=>$slot_detail,
		                                     'precentage'=>$hot_percentage,
		                                     'type'   => 9),
		                                     'status'=> "true",
		                                     'type'  => "GENERAL",
		                                     'venue_id'=>$venue_id,
		                                     'hot_id'=>$hot_id
		                                                 );

      			$notification= $this->notification->push_notification($city_user,$message,$title,$data_push);
      			$city_users=$this->hotofferuser_model->get_city_users($location_id);
      			foreach($city_users as $key6 => $value6) {
					$users_id=(int)$value6->id;	
					$data=array(
					'hot_offer_id'=>$hot_id,
					'user_id'=>$users_id
					);
					$notification_history=$this->hotofferuser_model->add_datas('hot_offer_notify_history',$data);
					}
      			
			}elseif($setting_id==2){
				$sports_user=$this->hotofferuser_model->get_sports_user($location_id,$sports_id);
				
      				$data_push =array('result' => array('message'=> $message,
		                                     'title'  => $title,
		                                     'venue_name'=>$venue_name,
		                                     'sports'=>$sports_name,
		                                     'slots'=>$slot_detail,
		                                     'precentage'=>$hot_percentage,
		                                     'type'   => 9),
		                                     'status'=> "true",
		                                     'type'  => "GENERAL",
		                                     'venue_id'=>$venue_id,
		                                     'hot_id'=>$hot_id
		                                     
		                                                 );

      			$notification= $this->notification->push_notification($sports_user,$message,$title,$data_push);
      			$sports_users=$this->hotofferuser_model->get_sports_users($location_id,$sports_id);
      			foreach($sports_users as $key7 => $value7) {
					$users_id=(int)$value7->id;	
					$data=array(
					'hot_offer_id'=>$hot_id,
					'user_id'=>$users_id
					);
					$notification_history=$this->hotofferuser_model->add_datas('hot_offer_notify_history',$data);
					}
      				
			}elseif($setting_id==3){
				$area_user=$this->hotofferuser_model->get_area_user($location_id,$sports_id,$area_id);
				
      				$data_push =array('result' => array('message'=> $message,
		                                     'title'  => $title,
		                                     'venue_name'=>$venue_name,
		                                     'sports'=>$sports_name,
		                                     'slots'=>$slot_detail,
		                                     'precentage'=>$hot_percentage,
		                                     'type'   => 9),
		                                     'status'=> "true",
		                                     'type'  => "GENERAL",
		                                     'venue_id'=>$venue_id,
		                                     'hot_id'=>$hot_id
		                                     
		                                                 );

      			$notification= $this->notification->push_notification($area_user,$message,$title,$data_push);
      			$area_users=$this->hotofferuser_model->get_area_users($location_id,$sports_id,$area_id);
      			foreach($area_users as $key8 => $value8) {
					$users_id=(int)$value8->id;	
					$data=array(
					'hot_offer_id'=>$hot_id,
					'user_id'=>$users_id
					);
					$notification_history=$this->hotofferuser_model->add_datas('hot_offer_notify_history',$data);
					}
      				
			}else{
			/////// no operation //////
			}
			
			
		
	}
///////////////////////// hot offer notification end  //////////////////////////





}
