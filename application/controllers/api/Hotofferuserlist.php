<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Hotofferuserlist extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/Hotofferuser_slider_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");

	}

///////////////////////// hot offer details based on user _id start for slider //////////////////////////
	public function user_offer_post(){
	     
		    $user_ids=$this->input->post('user_id');
		    //  $user_ids=12;
				$user_check=$this->Hotofferuser_slider_model->get_user_detailsj($user_ids);
				
				if(!empty($user_check)){
				   
				 $user_venues=$this->Hotofferuser_slider_model->get_user_venuesjjtest($user_ids);
				 //print_r($user_venues); exit(); 
				 $tc_count=0;
				 for ($m = 0; $m < count($user_venues); $m++) {
				  $venue_id=(int)$user_venues[$m]->id;
				  //echo "<pre>";print_r($venue_id);exit();
				  /* first day */
				  $venue_sport=$this->Hotofferuser_slider_model->get_venue_sportjtss($venue_id);
				 $count=count($venue_sport);
				 $st_count=$tc_count;
				 $tc_count=$tc_count+$count;
				 
				  if(!empty($venue_sport)){
				  	$countof=count($venue_sport);
				  		$counts=0;
    				   for ($c= $st_count; $c <= $tc_count; $c++){
    				   
    				   	//echo "<pre>";print_r($counts);
    				   	if($countof!=$counts){
    				     $venue_ids=$venue_sport[$counts]->venue_id; 
    				     $sports_ids=$venue_sport[$counts]->sports_id; 
    				     $dates=$venue_sport[$counts]->date;
    				     $hot_percentage=$venue_sport[$counts]->hot_percentage;
    				       
    				    $venue_details=$this->Hotofferuser_slider_model->get_venue_detailsj($venue_ids);
    				    for ($d= 0; $d < count($venue_details); $d++){
            				    
            				    $venue_ids=(int)$venue_details[$d]->venue_id;     
                    		    $venue=$venue_details[$d]->venue;
                				$morning=$venue_details[$d]->morning;
                				$evening=$venue_details[$d]->evening;
                				$area_id=$venue_details[$d]->venue_area_id;
                				$lat=$venue_details[$d]->lat;
                				$lon=$venue_details[$d]->lon;
                				$address=$venue_details[$d]->address;
                				$area=$venue_details[$d]->area;
    				    }
        				$hotslide=[];
        			$sports_details=$this->Hotofferuser_slider_model->get_sports_detailsj($sports_ids);
        			for ($p = 0; $p < count($sports_details); $p++) {
        			    $sports_id=(int)$sports_details[$p]->sports_id;
        			}
        			
        			$slot_details=$this->Hotofferuser_slider_model->get_slot_detailsj($venue_ids,$sports_id,$dates);
        			$slot_count=0;
                    for ($v = 0; $v < count($slot_details); $v++) {
				 	$slot_time=$slot_details[$v]->time;
					$slot_intervel=$slot_details[$v]->intervel;
					$court_id=$slot_details[$v]->id;
					$court_capacity=$slot_details[$v]->capacity;
					
					$venue_booking=$this->Hotofferuser_slider_model->get_booking_check($venue_ids,$sports_id,$court_id,$dates,$slot_time);
                            foreach($venue_booking as $row) {
                                  $booking_capacity = $row->capacity;
                            }
                    $temp_booking=$this->Hotofferuser_slider_model->get_tempbooking($court_id,$dates,$slot_time);
                            foreach($temp_booking as $row) {
                                  $temp_booking_capacity = $row->tempsum;
                            }
                    $total_booking_capacity=$booking_capacity+$temp_booking_capacity;
                    $remaining_capacity=$court_capacity-$total_booking_capacity;
					$slot=[];
					
					if($remaining_capacity !=0){
					   
					$slot_count=$slot_count+1;
					}
				 }
        			if(!empty($slot_details) && $slot_count!=0){
        			  	$hotslide=array(
    				    'venue_id'=>(int)$venue_ids,
    					'venue_name'=>$venue,
    					'venue_latitude'=>$lat,
    					'venue_longitude'=>$lon,
    					'venue_address'=>$address,
    					'venue_area'=>$area,
    					'hot_date'=>$dates,
    					'percentage'=>$hot_percentage
					
						);
    				   
					$hot_slide[$c]=$hotslide;  
        			}
        				
					
					$sports_details=$this->Hotofferuser_slider_model->get_sports_detailsj($sports_ids);	
					for ($p = 0; $p < count($sports_details); $p++) {
				 	$sports_id=(int)$sports_details[$p]->sports_id;
					$sports_name=$sports_details[$p]->sports_name;
					$sports_image=$sports_details[$p]->sports_image;
					$sports=[];
					$sports=array(
						'sports_id'=>(int)$sports_id,
						'sports_name'=>$sports_name,
						'sports_image'=>$sports_image
						);
				 }
				 	$slot_details=$this->Hotofferuser_slider_model->get_slot_detailsj($venue_ids,$sports_id,$dates);
        			if(!empty($slot_details) && $slot_count!=0){
				        $hot_slide[$c]['sports']=$sports;
        			}   
                    $slot_details=$this->Hotofferuser_slider_model->get_slot_detailsj($venue_ids,$sports_id,$dates);
                    $slot_count=0;
                    for ($v = 0; $v < count($slot_details); $v++) {
				 	$slot_time=$slot_details[$v]->time;
					$slot_intervel=$slot_details[$v]->intervel;
					$court_id=$slot_details[$v]->id;
					$court_capacity=$slot_details[$v]->capacity;
					
					$venue_booking=$this->Hotofferuser_slider_model->get_booking_check($venue_ids,$sports_id,$court_id,$dates,$slot_time);
                            foreach($venue_booking as $row) {
                                  $booking_capacity = $row->capacity;
                            }
                    $temp_booking=$this->Hotofferuser_slider_model->get_tempbooking($court_id,$dates,$slot_time);
                            foreach($temp_booking as $row) {
                                  $temp_booking_capacity = $row->tempsum;
                            }
                    $total_booking_capacity=$booking_capacity+$temp_booking_capacity;
                    $remaining_capacity=$court_capacity-$total_booking_capacity;
					$slot=[];
					
					if($remaining_capacity !=0){
					    $slot=array(
						'slot_time'=>$slot_time,
						'slot_intervel'=>$slot_intervel,
						);
						
					$hot_slide[$c]['slots'][$slot_count]=$slot;
					$slot_count=$slot_count+1;
					}
				 }
				 $counts=$counts+1;
						
						}
    				   }
				  }
				  
		
				  
				 }
				   
				 usort($hot_slide, 'sortByOne');

            $limitedTable = array_slice($hot_slide, 0,10); 
				  if(!empty($limitedTable))
                {
                    $result=array(
                    'errorCode'=>1,
                    'data'=>$limitedTable,
                    'message'=>"success"
                    );
                return $this->response($result,200);
                }
                else
                {
                    $result=array(
                    'errorCode'=>1,
                    'data'=>[],
                    'message'=>"success"
                );
                return $this->response($result,200);
                }
				   
				   
				   
				}else{
				  $result=array(
						'errorCode'=>0,
						'data'=>[],
						'message'=>"User does not exist!"
						);
				return $this->response($result,200);  
				}

	}
///////////////////////// hot offer details based on venue_id end //////////////////////////

///////////////////////// hot offer details based on user _id start for slider //////////////////////////
	public function user_offer_demo_get(){
	     
		    //$user_ids=$this->input->post('user_id');
		      $user_ids=12;
				$user_check=$this->Hotofferuser_slider_model->get_user_detailsj($user_ids);
				
				if(!empty($user_check)){
				   
				 $user_venues=$this->Hotofferuser_slider_model->get_user_venuesjjtest($user_ids);
				 //print_r($user_venues); exit(); 
				 $tc_count=0;
				 for ($m = 0; $m < count($user_venues); $m++) {
				  $venue_id=(int)$user_venues[$m]->id;
				  //echo "<pre>";print_r($venue_id);exit();
				  /* first day */
				  $venue_sport=$this->Hotofferuser_slider_model->get_venue_sportjtss($venue_id);
				 $count=count($venue_sport);
				 $st_count=$tc_count;
				 $tc_count=$tc_count+$count;
				 
				  if(!empty($venue_sport)){
				  	$countof=count($venue_sport);
				  		$counts=0;
    				   for ($c= $st_count; $c <= $tc_count; $c++){
    				   
    				   	//echo "<pre>";print_r($counts);
    				   	if($countof!=$counts){
    				     $venue_ids=$venue_sport[$counts]->venue_id; 
    				     $sports_ids=$venue_sport[$counts]->sports_id; 
    				     $dates=$venue_sport[$counts]->date;
    				     $hot_percentage=$venue_sport[$counts]->hot_percentage;
    				       
    				    $venue_details=$this->Hotofferuser_slider_model->get_venue_detailsj($venue_ids);
    				    for ($d= 0; $d < count($venue_details); $d++){
            				    
            				    $venue_ids=(int)$venue_details[$d]->venue_id;     
                    		    $venue=$venue_details[$d]->venue;
                				$morning=$venue_details[$d]->morning;
                				$evening=$venue_details[$d]->evening;
                				$area_id=$venue_details[$d]->venue_area_id;
                				$lat=$venue_details[$d]->lat;
                				$lon=$venue_details[$d]->lon;
                				$address=$venue_details[$d]->address;
                				$area=$venue_details[$d]->area;
    				    }
        				$hotslide=[];
        			$sports_details=$this->Hotofferuser_slider_model->get_sports_detailsj($sports_ids);
        			for ($p = 0; $p < count($sports_details); $p++) {
        			    $sports_id=(int)$sports_details[$p]->sports_id;
        			}
        			
        			$slot_details=$this->Hotofferuser_slider_model->get_slot_detailsj($venue_ids,$sports_id,$dates);
        			$slot_count=0;
                    for ($v = 0; $v < count($slot_details); $v++) {
				 	$slot_time=$slot_details[$v]->time;
					$slot_intervel=$slot_details[$v]->intervel;
					$court_id=$slot_details[$v]->id;
					$court_capacity=$slot_details[$v]->capacity;
					
					$venue_booking=$this->Hotofferuser_slider_model->get_booking_check($venue_ids,$sports_id,$court_id,$dates,$slot_time);
                            foreach($venue_booking as $row) {
                                  $booking_capacity = $row->capacity;
                            }
                    $temp_booking=$this->Hotofferuser_slider_model->get_tempbooking($court_id,$dates,$slot_time);
                            foreach($temp_booking as $row) {
                                  $temp_booking_capacity = $row->tempsum;
                            }
                    $total_booking_capacity=$booking_capacity+$temp_booking_capacity;
                    $remaining_capacity=$court_capacity-$total_booking_capacity;
					$slot=[];
					
					if($remaining_capacity !=0){
					   
					$slot_count=$slot_count+1;
					}
				 }
        			if(!empty($slot_details) && $slot_count!=0){
        			  	$hotslide=array(
    				    'venue_id'=>(int)$venue_ids,
    					'venue_name'=>$venue,
    					'venue_latitude'=>$lat,
    					'venue_longitude'=>$lon,
    					'venue_address'=>$address,
    					'venue_area'=>$area,
    					'hot_date'=>$dates,
    					'percentage'=>$hot_percentage
					
						);
    				   
					$hot_slide[$c]=$hotslide;  
        			}
        				
					
					$sports_details=$this->Hotofferuser_slider_model->get_sports_detailsj($sports_ids);	
					for ($p = 0; $p < count($sports_details); $p++) {
				 	$sports_id=(int)$sports_details[$p]->sports_id;
					$sports_name=$sports_details[$p]->sports_name;
					$sports_image=$sports_details[$p]->sports_image;
					$sports=[];
					$sports=array(
						'sports_id'=>(int)$sports_id,
						'sports_name'=>$sports_name,
						'sports_image'=>$sports_image
						);
				 }
				 	$slot_details=$this->Hotofferuser_slider_model->get_slot_detailsj($venue_ids,$sports_id,$dates);
        			if(!empty($slot_details) && $slot_count!=0){
				        $hot_slide[$c]['sports']=$sports;
        			}   
                    $slot_details=$this->Hotofferuser_slider_model->get_slot_detailsj($venue_ids,$sports_id,$dates);
                    $slot_count=0;
                    for ($v = 0; $v < count($slot_details); $v++) {
				 	$slot_time=$slot_details[$v]->time;
					$slot_intervel=$slot_details[$v]->intervel;
					$court_id=$slot_details[$v]->id;
					$court_capacity=$slot_details[$v]->capacity;
					
					$venue_booking=$this->Hotofferuser_slider_model->get_booking_check($venue_ids,$sports_id,$court_id,$dates,$slot_time);
                            foreach($venue_booking as $row) {
                                  $booking_capacity = $row->capacity;
                            }
                    $temp_booking=$this->Hotofferuser_slider_model->get_tempbooking($court_id,$dates,$slot_time);
                            foreach($temp_booking as $row) {
                                  $temp_booking_capacity = $row->tempsum;
                            }
                    $total_booking_capacity=$booking_capacity+$temp_booking_capacity;
                    $remaining_capacity=$court_capacity-$total_booking_capacity;
					$slot=[];
					
					if($remaining_capacity !=0){
					    $slot=array(
						'slot_time'=>$slot_time,
						'slot_intervel'=>$slot_intervel,
						);
						
					$hot_slide[$c]['slots'][$slot_count]=$slot;
					$slot_count=$slot_count+1;
					}
				 }
				 $counts=$counts+1;
						
						}
    				   }
				  }
				  
		
				  
				 }
				   
				 usort($hot_slide, 'sortByOne');

            $limitedTable = array_slice($hot_slide, 0,10); 
				  if(!empty($limitedTable))
                {
                    $result=array(
                    'errorCode'=>1,
                    'data'=>$limitedTable,
                    'message'=>"success"
                    );
                return $this->response($result,200);
                }
                else
                {
                    $result=array(
                    'errorCode'=>1,
                    'data'=>[],
                    'message'=>"success"
                );
                return $this->response($result,200);
                }
				   
				   
				   
				}else{
				  $result=array(
						'errorCode'=>0,
						'data'=>[],
						'message'=>"User does not exist!"
						);
				return $this->response($result,200);  
				}

	}
///////////////////////// hot offer details based on venue_id end //////////////////////////





}
