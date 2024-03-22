<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Hotoffer extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/Hotoffer_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");

	}


/*-----------------------------To fetch full offer list filter  based  on user_id firt priority my sports,after that normal sports start here---------------------------------*/

public function user_hotoffer_list_post()
{
    

$user_id=$this->input->post('user_id');
//$user_id=14; 
	 
		$user_check=$this->Hotoffer_model->get_user_detail($user_id);
				if(!empty($user_check)){
				        
                $user_location=$this->Hotoffer_model->get_user_location($user_id);
                foreach($user_location as $row) {
                    $location_id = $row->location_id;
                 }
                 $user_sports_hotoffer=$this->Hotoffer_model->get_user_sports_hotoffer($user_id,$location_id);
                     
                 $user_non_sports_hotoffer=$this->Hotoffer_model->get_user_non_sports_hotoffer($user_id,$location_id);
                 $hot_details = array_merge ($user_sports_hotoffer,$user_non_sports_hotoffer);
                    for ($m = 0; $m < count($hot_details); $m++) {
                        $venue_id=(int)$hot_details[$m]->venue_id;     
                        $venue=$hot_details[$m]->venue;
                        $morning=$hot_details[$m]->morning;
                        $evening=$hot_details[$m]->evening;
                        $area_id=$hot_details[$m]->venue_area_id;
                        $lat=$hot_details[$m]->lat;
                        $lon=$hot_details[$m]->lon;
                        $address=$hot_details[$m]->address;
                        $area=$hot_details[$m]->area;
                        $court_id=(int)$hot_details[$m]->court_id;
                        $court_name=$hot_details[$m]->court_name;
                        $court_capacity=(int)$hot_details[$m]->court_capacity;
                        $court_price=(double)$hot_details[$m]->court_price;
                        $slot_id=(int)$hot_details[$m]->slot_id;
                        $slot_time=$hot_details[$m]->slot_time;
                        $slot_intervel=$hot_details[$m]->slot_intervel;
                        $dates=$hot_details[$m]->hot_date;
                        $hot_id=(int)$hot_details[$m]->hot_id;
                        $hot_percentage=$hot_details[$m]->hot_percentage;
                        $sports_id=(int)$hot_details[$m]->sports_id;
                        $sports_name=$hot_details[$m]->sports_name;
                        $sports_image=$hot_details[$m]->sports_image;
                        

                        $venue_booking=$this->Hotoffer_model->get_booking($venue_id,$sports_id,$court_id,$dates,$slot_time);
                            foreach($venue_booking as $row) {
                                  $booking_capacity = $row->capacity;
                            }
                        $temp_booking=$this->Hotoffer_model->get_tempbooking($court_id,$dates,$slot_time);
                            foreach($temp_booking as $row) {
                                  $temp_booking_capacity = $row->tempsum;
                            }
                        $total_capacity=$booking_capacity+$temp_booking_capacity;
                        
                        if($total_capacity !=0){
                            $remaing_capacity=$court_capacity-$total_capacity;
                        }else{
                            $remaing_capacity=$court_capacity;  
                        }
                        $slot_offer_price= $court_price*($hot_percentage/100);
                        $slot_booking_cost=$court_price-$slot_offer_price;

                        if($remaing_capacity !=0){

                          $hotoffer[]=array(  'court_id'=>$court_id,
                                            'court_name'=>$court_name,
                                            'court_capacity'=>$court_capacity,
                                            'court_price'=>$court_price,
                                            'court_selected_capacity'=>0,
                                            'court_remaining_capacity'=>(int)$remaing_capacity,
                                            'calculated_price'=>(double)$slot_booking_cost,
                                            'slot_id'=> $slot_id,
                                            'slot_time'=>$slot_time,
                                            'slot_intervel'=>$slot_intervel,
                                            'venue_id'=>(int)$venue_id,
                                            'venue_name'=>$venue,
                                            'venue_address'=>$address,
                                            'venue_area'=>$area,
                                            'venue_latitude'=>$lat,
                                            'venue_longitude'=>$lon,
                                            'hot_id'=>$hot_id,
                                            'hot_date'=>$dates,
                                            'hot_percentage'=>$hot_percentage,
                                            'sports_id'=>(int)$sports_id,
                                            'sports_name'=>$sports_name,
                                            'sports_image'=>$sports_image);

                        }
          

      

            }
		 // print_r($hotoffer); exit();
    			if(!empty($hotoffer))
    			{
    		                       
    				    $result=array(
    					'errorCode'=>1,
    					'data'=>$hotoffer,
    					'message'=>"success"
    					);
    		          return $this->response($result,200);
				}
				else
				{
				    	$result=array(
						'errorCode'=>1,
						'data'=>[],
						'message'=>"sucess"
						);
				    return $this->response($result,200);
				}
            }else{
					$result=array(
						'errorCode'=>0,
						'data'=>[],
						'message'=>"user does not exit!"
						);
				return $this->response($result,200);
				}
      
}

public function home_slider_post()
{
    

        $user_id=$this->input->post('user_id');
        //$user_id=22; 
     
        $user_check=$this->Hotoffer_model->get_user_detail($user_id);
                if(!empty($user_check)){
                        
                $user_location=$this->Hotoffer_model->get_user_location($user_id);
                foreach($user_location as $row) {
                    $location_id = $row->location_id;
                 }
                 $user_venue_sp=$this->Hotoffer_model->get_today_venue($user_id,$location_id);
                 $user_slider_sp=$this->Hotoffer_model->get_user_sports_hotoffer($user_id,$location_id);
                 //echo "<pre>";print_r($user_slider_sp);
                 for ($m = 0; $m < count($user_venue_sp); $m++) {

                    $venue_id=(int)$user_venue_sp[$m]->venue_id;
                    $sports_id=(int)$user_venue_sp[$m]->sports_id;
                    $hot_date=$user_venue_sp[$m]->hot_date;
                    $count=0;

                    for ($n = 0; $n < count($user_slider_sp); $n++) {

                        if($venue_id == $user_slider_sp[$n]->venue_id && $sports_id == $user_slider_sp[$n]->sports_id && $hot_date == $user_slider_sp[$n]->hot_date){

                            if($count==0){
                                
                                    $slot_count=0;
                                    $hot_slide=[];

                                for ($p = 0; $p < count($user_slider_sp); $p++) {

                                  if($venue_id == $user_slider_sp[$p]->venue_id && $sports_id == $user_slider_sp[$p]->sports_id && $hot_date == $user_slider_sp[$p]->hot_date){

                                        $slot_time=$user_slider_sp[$p]->slot_time;
                                        $slot_intervel=$user_slider_sp[$p]->slot_intervel; 
                                        $court_id=$user_slider_sp[$p]->court_id;
                                        $dates=$user_slider_sp[$p]->hot_date;
                                        $court_capacity=$user_slider_sp[$p]->court_capacity;

                                        $venue_booking=$this->Hotoffer_model->get_booking($venue_id,$sports_id,$court_id,$dates,$slot_time);
                                        //echo "<pre>";print_r($venue_booking);
                                        foreach($venue_booking as $row) {
                                              $booking_capacity = $row->capacity;
                                        }
                                    $temp_booking=$this->Hotoffer_model->get_tempbooking($court_id,$dates,$slot_time);
                                    //echo "<pre>";print_r($temp_booking);
                                        foreach($temp_booking as $row) {
                                              $temp_booking_capacity = $row->tempsum;
                                        }
                                    $total_capacity=$booking_capacity+$temp_booking_capacity;
                                    //echo "<pre>";print_r($total_capacity);
                                    if($total_capacity !=0){
                                        $remaing_capacity=$court_capacity-$total_capacity;
                                    }else{
                                        $remaing_capacity=$court_capacity;  
                                    }
                                    $slot_offer_price= $court_price*($hot_percentage/100);
                                    $slot_booking_cost=$court_price-$slot_offer_price;
                                    //echo "<pre>";print_r($remaing_capacity);
                                            if($remaing_capacity !=0){

                                                        $slot=[];
                                                            $slot[]=array(
                                                                    'slot_time'=>$slot_time,
                                                                    'slot_intervel'=>$slot_intervel
                                                             );
                                                        $hot_slide[$slot_count]=$slot;
                                                        $slot_count=$slot_count+1;
                                                        

                                            }
                                  }  
                                }
                                if(!empty($hot_slide)){
                                   
                                   $slide[$m]=array(  'venue_id'=>$venue_id,
                                            'court_name'=>$user_slider_sp[$n]->court_name,
                                            'venue_name'=>$user_slider_sp[$n]->venue,
                                            'venue_latitude'=>$user_slider_sp[$n]->lat,
                                            'venue_longitude'=>$user_slider_sp[$n]->lon,
                                            'venue_address'=>$user_slider_sp[$n]->address,
                                            'hot_date'=>$user_slider_sp[$n]->hot_date,
                                            'percentage'=>$user_slider_sp[$n]->hot_percentage,
                                            );
                                $sports=[];
                                $sports=array(
                                    'sports_id'=>$user_slider_sp[$n]->sports_id,
                                    'sports_name'=>$user_slider_sp[$n]->sports_name,
                                    'sports_image'=>$user_slider_sp[$n]->sports_image
                                    );
                                $slide[$m]['sports']=$sports;
                                $slot_count=0;
                                for ($p = 0; $p < count($user_slider_sp); $p++) {

                                  if($venue_id == $user_slider_sp[$p]->venue_id && $sports_id == $user_slider_sp[$p]->sports_id && $hot_date == $user_slider_sp[$p]->hot_date){

                                        $slot_time=$user_slider_sp[$p]->slot_time;
                                        $slot_intervel=$user_slider_sp[$p]->slot_intervel; 
                                        $court_id=$user_slider_sp[$p]->court_id;
                                        $dates=$user_slider_sp[$p]->hot_date;
                                        $court_capacity=$user_slider_sp[$p]->court_capacity;

                                        $venue_booking=$this->Hotoffer_model->get_booking($venue_id,$sports_id,$court_id,$dates,$slot_time);
                                        //echo "<pre>";print_r($venue_booking);
                                        foreach($venue_booking as $row) {
                                              $booking_capacity = $row->capacity;
                                        }
                                    $temp_booking=$this->Hotoffer_model->get_tempbooking($court_id,$dates,$slot_time);
                                    //echo "<pre>";print_r($temp_booking);
                                        foreach($temp_booking as $row) {
                                              $temp_booking_capacity = $row->tempsum;
                                        }
                                    $total_capacity=$booking_capacity+$temp_booking_capacity;
                                    //echo "<pre>";print_r($total_capacity);
                                    if($total_capacity !=0){
                                        $remaing_capacity=$court_capacity-$total_capacity;
                                    }else{
                                        $remaing_capacity=$court_capacity;  
                                    }
                                    $slot_offer_price= $court_price*($hot_percentage/100);
                                    $slot_booking_cost=$court_price-$slot_offer_price;
                                    //echo "<pre>";print_r($remaing_capacity);
                                            if($remaing_capacity !=0){

                                                        $slot=[];
                                                            $slot=array(
                                                                    'slot_time'=>$slot_time,
                                                                    'slot_intervel'=>$slot_intervel
                                                             );
                                                        $slide[$m]['slots'][$slot_count]=$slot;
                                                        $slot_count=$slot_count+1;
                                                        

                                            }
                                  }  
                                }
                                $count=$count+1; 
                                }
                                
                            }
                            
                        }

                    }

                 }
                 $today=date('Y-m-d');	
		         $second= date('Y-m-d', strtotime($today. ' + 1 days'));
		         $third= date('Y-m-d', strtotime($today. ' + 2 days'));
                
                usort( $slide, function( $a, $b ){
                     
                        if( $a['percentage'] == $b['percentage'] ) {
                            return 0;
                        }
                    return ($a['percentage'] > $b['percentage']  ) ? -1 : 1;
                    });
                    
                     usort( $slide, function( $a, $b ){
                     
                        if( $a['hot_date'] == $b['hot_date'] ) {
                            return 0;
                        }
                    return ($a['hot_date'] < $b['hot_date']  ) ? -1 : 1;
                    });
                 
    //////////////////////////////////////////////////////// user non sports hot offers ///////////////////////////////
                 $user_venue_nsp=$this->Hotoffer_model->get_user_nsp_venue($user_id,$location_id);
                 $user_slider_nsp=$this->Hotoffer_model->get_user_non_sports_hotoffer($user_id,$location_id);
                 //echo "<pre>";print_r($user_slider_nsp);exit();
                 for ($m = 0; $m < count($user_venue_nsp); $m++) {

                    $venue_id=(int)$user_venue_nsp[$m]->venue_id;
                    $sports_id=(int)$user_venue_nsp[$m]->sports_id;
                    $hot_date=$user_venue_nsp[$m]->hot_date;
                    $count=0;

                    for ($n = 0; $n < count($user_slider_nsp); $n++) {

                        if($venue_id == $user_slider_nsp[$n]->venue_id && $sports_id == $user_slider_nsp[$n]->sports_id && $hot_date == $user_slider_nsp[$n]->hot_date){

                            if($count==0){
                                
                                $slot_count=0;
                                $hot_slides=[];
                                for ($p = 0; $p < count($user_slider_nsp); $p++) {

                                  if($venue_id == $user_slider_nsp[$p]->venue_id && $sports_id == $user_slider_nsp[$p]->sports_id && $hot_date == $user_slider_nsp[$p]->hot_date){

                                        $slot_time=$user_slider_nsp[$p]->slot_time;
                                        $slot_intervel=$user_slider_nsp[$p]->slot_intervel; 
                                        $court_id=$user_slider_nsp[$p]->court_id;
                                        $dates=$user_slider_nsp[$p]->hot_date;
                                        $court_capacity=$user_slider_nsp[$p]->court_capacity;

                                        $venue_booking=$this->Hotoffer_model->get_booking($venue_id,$sports_id,$court_id,$dates,$slot_time);
                                        //echo "<pre>";print_r($venue_booking);
                                        foreach($venue_booking as $row) {
                                              $booking_capacity = $row->capacity;
                                        }
                                    $temp_booking=$this->Hotoffer_model->get_tempbooking($court_id,$dates,$slot_time);
                                    //echo "<pre>";print_r($temp_booking);
                                        foreach($temp_booking as $row) {
                                              $temp_booking_capacity = $row->tempsum;
                                        }
                                    $total_capacity=$booking_capacity+$temp_booking_capacity;
                                    //echo "<pre>";print_r($total_capacity);
                                    if($total_capacity !=0){
                                        $remaing_capacity=$court_capacity-$total_capacity;
                                    }else{
                                        $remaing_capacity=$court_capacity;  
                                    }
                                    $slot_offer_price= $court_price*($hot_percentage/100);
                                    $slot_booking_cost=$court_price-$slot_offer_price;
                                    //echo "<pre>";print_r($remaing_capacity);
                                            if($remaing_capacity !=0){

                                                        $slot=[];
                                                            $slot[]=array(
                                                                    'slot_time'=>$slot_time,
                                                                    'slot_intervel'=>$slot_intervel
                                                             );
                                                        $hot_slides[$slot_count]=$slot;
                                                        $slot_count=$slot_count+1;
                                                        

                                            }
                                  }  
                                }
                                if(!empty($hot_slides)){
                                   
                                   $slide_nsp[$m]=array(  'venue_id'=>$venue_id,
                                            'court_name'=>$user_slider_nsp[$n]->court_name,
                                            'venue_name'=>$user_slider_nsp[$n]->venue,
                                            'venue_latitude'=>$user_slider_nsp[$n]->lat,
                                            'venue_longitude'=>$user_slider_nsp[$n]->lon,
                                            'venue_address'=>$user_slider_nsp[$n]->address,
                                            'hot_date'=>$user_slider_nsp[$n]->hot_date,
                                            'percentage'=>$user_slider_nsp[$n]->hot_percentage,
                                            );
                                $sports=[];
                                $sports=array(
                                    'sports_id'=>$user_slider_nsp[$n]->sports_id,
                                    'sports_name'=>$user_slider_nsp[$n]->sports_name,
                                    'sports_image'=>$user_slider_nsp[$n]->sports_image
                                    );
                                $slide_nsp[$m]['sports']=$sports;
                                $slot_count=0;
                                for ($p = 0; $p < count($user_slider_nsp); $p++) {

                                  if($venue_id == $user_slider_nsp[$p]->venue_id && $sports_id == $user_slider_nsp[$p]->sports_id && $hot_date == $user_slider_nsp[$p]->hot_date){

                                        $slot_time=$user_slider_nsp[$p]->slot_time;
                                        $slot_intervel=$user_slider_nsp[$p]->slot_intervel; 
                                        $court_id=$user_slider_nsp[$p]->court_id;
                                        $dates=$user_slider_nsp[$p]->hot_date;
                                        $court_capacity=$user_slider_nsp[$p]->court_capacity;

                                        $venue_booking=$this->Hotoffer_model->get_booking($venue_id,$sports_id,$court_id,$dates,$slot_time);
                                        //echo "<pre>";print_r($venue_booking);
                                        foreach($venue_booking as $row) {
                                              $booking_capacity = $row->capacity;
                                        }
                                    $temp_booking=$this->Hotoffer_model->get_tempbooking($court_id,$dates,$slot_time);
                                    //echo "<pre>";print_r($temp_booking);
                                        foreach($temp_booking as $row) {
                                              $temp_booking_capacity = $row->tempsum;
                                        }
                                    $total_capacity=$booking_capacity+$temp_booking_capacity;
                                    //echo "<pre>";print_r($total_capacity);
                                    if($total_capacity !=0){
                                        $remaing_capacity=$court_capacity-$total_capacity;
                                    }else{
                                        $remaing_capacity=$court_capacity;  
                                    }
                                    $slot_offer_price= $court_price*($hot_percentage/100);
                                    $slot_booking_cost=$court_price-$slot_offer_price;
                                    //echo "<pre>";print_r($remaing_capacity);
                                            if($remaing_capacity !=0){

                                                        $slot=[];
                                                            $slot=array(
                                                                    'slot_time'=>$slot_time,
                                                                    'slot_intervel'=>$slot_intervel
                                                             );
                                                        $slide_nsp[$m]['slots'][$slot_count]=$slot;
                                                        $slot_count=$slot_count+1;
                                                        

                                            }
                                  }  
                                }
                                $count=$count+1; 
                                }
                                
                            }
                            
                        }

                    }

                 }
                 
                 usort( $slide_nsp, function( $a, $b ){
                     
                        if( $a['percentage'] == $b['percentage'] ) {
                            return 0;
                        }
                    return ($a['percentage'] > $b['percentage']  ) ? -1 : 1;
                    });
                    
                     usort( $slide_nsp, function( $a, $b ){
                     
                        if( $a['hot_date'] == $b['hot_date'] ) {
                            return 0;
                        }
                    return ($a['hot_date'] < $b['hot_date']  ) ? -1 : 1;
                    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                 if(!empty($slide) && !empty($slide_nsp) ){
                    $slide_content = array_merge ($slide,$slide_nsp);
                    $slider_data=array_slice($slide_content, 0, 10);
                }elseif(empty($slide_nsp)){
                   $slider_data=array_slice($slide, 0, 10);
                }elseif(empty($slide)){
                   $slider_data=array_slice($slide_nsp, 0, 10);
                }
                 if(!empty($slider_data)){

                    $result=array(
                        'errorCode'=>1,
                        'data'=>$slider_data,
                        'message'=>"success"
                        );
                    return $this->response($result,200);
                 }else{

                    $result=array(
                        'errorCode'=>0,
                        'data'=>[],
                        'message'=>"no data"
                        );
                    return $this->response($result,200);
                 }
                 
                
            }else{
                    $result=array(
                        'errorCode'=>0,
                        'data'=>[],
                        'message'=>"user does not exit!"
                        );
                return $this->response($result,200);
                }
      
}




}