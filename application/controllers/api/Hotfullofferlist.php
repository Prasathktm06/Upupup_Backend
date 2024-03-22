<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Hotfullofferlist extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/Hotofferfull_slider_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");

	}









/*-----------------------------To fetch full offer list filter  based  on user_id firt priority my sports,after that normal sports start here---------------------------------*/

public function user_fulloffer_post()
{
    

$user_id=$this->input->post('user_id');
//$user_id=12; 
	 	$user_check=$this->Hotofferfull_slider_model->get_user_detail($user_id);
	 
					$user_check=$this->Hotofferfull_slider_model->get_user_detail($user_id);
				if(!empty($user_check)){
					 	    $hot_details=$this->Hotofferfull_slider_model->get_user_oncallmark($user_id);
//print_r($hot_details); exit();
		       // $hot_details=$this->hotofferuser_model->get_hot_details($venue_id);
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
                	            $court_selected_capacity=0;
                	           // $calculated_price=0;
                	     
                	            $hot_booking=$this->Hotofferfull_slider_model->get_hot_booking_all($user_id,$venue_id,$sports_id,$court_id,$dates,$slot_time,$slot_time);
                	            
                	            //   print_r($hot_cntbooking);exit();
                	            
                	            	if(!empty($hot_booking))
                	            	{
                	            	   
                                    $slot_times= $hot_booking[0]->court_time;
                                    $crt_id= $hot_booking[0]->court_id;
                                    $dats= $hot_booking[0]->date;
                                    $booking_capacity= $hot_booking[0]->capacity;
                                   // echo $court_id; exit();
                                   
                        //   get_hot_coutbooking($user_id,$slot_times,$crt_id,$dats)
                         $hot_temperoryBooking=$this->Hotofferfull_slider_model->get_hot_coutbooking($user_id,$slot_times,$crt_id,$dats);
                                   
         $hot_cntbooking=$this->Hotofferfull_slider_model->get_hot_booking($user_id,$venue_id,$sports_id,$court_id,$dates,$slot_time,$slot_time);
      
 
                                     	if(!empty($hot_cntbooking))
                                     	{
                                          $cntbooking_capacity= $hot_cntbooking[0]->capacity;
                                          
                                     	}
                                     	$hot_temperoryBooking=$this->Hotofferfull_slider_model->get_hot_coutbooking($user_id,$slot_times,$crt_id,$dats);	
                                     	
                                     	
                                     if(!empty($hot_temperoryBooking))
                                     	{
                                          $cntbooking_capacity= $hot_temperoryBooking[0]->capacity;
                                          
                                     	}	
                                     	
                                     	
                                 $totalbookingcapacity=    $cntbooking_capacity+	$cntbooking_capacity;
                	            	}
                	           
                	            	
                	         $calculated_price= $court_price*($hot_percentage/100);
                	         
                	         $offer_price=$court_price-$calculated_price;
                                    
                                    $bookingcnt= $booking_capacity+$cntbooking_capacity;
                                    $remaing_capacity=$court_capacity- $totalbookingcapacity;
                	            
                	            if($court_id!=0){
			                        	$hotoffer[]=array(	'court_id'=>$court_id,
                                                    'court_name'=>$court_name,
                                                    'court_capacity'=>$court_capacity,
                                                    'court_price'=>$court_price,
                                                    'court_selected_capacity'=>(int)$court_selected_capacity,
                                                     'court_remaining_capacity'=>(int)$remaing_capacity,
													'calculated_price'=>(double)$offer_price,
												    'slot_id'=>	$slot_id,
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
					
			                                    	$hot_offer=$hotoffer;
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
      }
      else{
					$result=array(
						'errorCode'=>0,
						'data'=>[],
						'message'=>"user does not exit!"
						);
				return $this->response($result,200);
				}
      
}





/*-----------------------------To fetch full offer list filter  based  on user_id firt priority my sports,after that normal sports start here---------------------------------*/

public function user_hotoffer_list_get()
{
    

//$user_id=$this->input->post('user_id');
$user_id=14; 
	 	$user_check=$this->Hotofferfull_slider_model->get_user_detail($user_id);
	 
					$user_check=$this->Hotofferfull_slider_model->get_user_detail($user_id);
				if(!empty($user_check)){
				            
					 	    $hot_details=$this->Hotofferfull_slider_model->get_user_oncallmark($user_id);
//print_r($hot_details); exit();
		       // $hot_details=$this->hotofferuser_model->get_hot_details($venue_id);
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
                	            $court_selected_capacity=0;
                	           // $calculated_price=0;
                	     
                	            $hot_booking=$this->Hotofferfull_slider_model->get_hot_booking_all($user_id,$venue_id,$sports_id,$court_id,$dates,$slot_time,$slot_time);
                	            
                	            //   print_r($hot_cntbooking);exit();
                	            
                	            	if(!empty($hot_booking))
                	            	{
                	            	   
                                    $slot_times= $hot_booking[0]->court_time;
                                    $crt_id= $hot_booking[0]->court_id;
                                    $dats= $hot_booking[0]->date;
                                    $booking_capacity= $hot_booking[0]->capacity;
                                   // echo $court_id; exit();
                                   
                        //   get_hot_coutbooking($user_id,$slot_times,$crt_id,$dats)
                         $hot_temperoryBooking=$this->Hotofferfull_slider_model->get_hot_coutbooking($user_id,$slot_times,$crt_id,$dats);
                                   
         $hot_cntbooking=$this->Hotofferfull_slider_model->get_hot_booking($user_id,$venue_id,$sports_id,$court_id,$dates,$slot_time,$slot_time);
      
 
                                     	if(!empty($hot_cntbooking))
                                     	{
                                          $cntbooking_capacity= $hot_cntbooking[0]->capacity;
                                          
                                     	}
                                     	$hot_temperoryBooking=$this->Hotofferfull_slider_model->get_hot_coutbooking($user_id,$slot_times,$crt_id,$dats);	
                                     	
                                     	
                                     if(!empty($hot_temperoryBooking))
                                     	{
                                          $cntbooking_capacity= $hot_temperoryBooking[0]->capacity;
                                          
                                     	}	
                                     	
                                     	
                                 $totalbookingcapacity=    $cntbooking_capacity+	$cntbooking_capacity;
                	            	}
                	           
                	            	
                	         $calculated_price= $court_price*($hot_percentage/100);
                	         
                	         $offer_price=$court_price-$calculated_price;
                                    
                                    $bookingcnt= $booking_capacity+$cntbooking_capacity;
                                    $remaing_capacity=$court_capacity- $totalbookingcapacity;
                	            
                	            if($court_id!=0){
			                        	$hotoffer[]=array(	'court_id'=>$court_id,
                                                    'court_name'=>$court_name,
                                                    'court_capacity'=>$court_capacity,
                                                    'court_price'=>$court_price,
                                                    'court_selected_capacity'=>(int)$court_selected_capacity,
                                                     'court_remaining_capacity'=>(int)$remaing_capacity,
													'calculated_price'=>(double)$offer_price,
												    'slot_id'=>	$slot_id,
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
					
			                                    	$hot_offer=$hotoffer;
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
      }
      else{
					$result=array(
						'errorCode'=>0,
						'data'=>[],
						'message'=>"user does not exit!"
						);
				return $this->response($result,200);
				}
      
}





}
