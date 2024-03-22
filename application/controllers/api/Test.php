<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends REST_Controller{
    function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/venue_model");
		$this->load->model("api/test_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");
	}
	
	
	public function court_timing_get(){
	    $court_id		=343;//$this->input->post('court_id');
		$day			=25;//$this->input->post('day');
		$month			=02;$this->input->post('month');
		$year			=2020;$this->input->post('year');
		$venue_id 		=753;$this->input->post('venue_id');
		$date  			=date($year."-".$month."-".$day); 
		
		$week 			= date('l', strtotime($date));
        $nameOfDay = date('D', strtotime($date));

		$court_timing=$this->venue_model->court_time($court_id,$week,$date);
		$court_capacity=$this->venue_model->get_court($court_id)[0]->capacity;
		echo "<pre>";
// 		print_r($court_timing);
		
		$offer=$this->venue_model->court_offer($court_id,$date,$nameOfDay);
		print_r($offer);
        $inactive=$this->venue_model->court_inactive($court_id,$date,$nameOfDay);
		$holiday= $this->venue_model->is_holiday2($date,$venue_id);
// 		print_r($holiday);

		if(!empty($holiday)){
		    $result=array(	'ErrorCode'=>1,
							'Data'=>"",
							'Message'=>"Holiday"
						);
		}else{
		    $i=0;
		    echo count($court_timing)."<br>";
		    
		    foreach ($court_timing as $key => $value) {
		        echo "<br>".$key;
		        if($value->time!="Holiday")	{
		            echo"<br> ! Holiday";
		            $value->slots[$i]=array('time'=>  date('h:i A', strtotime($value->time)));
		          //  print_r( "<br>".$value->slots[$i]['time']);
				    $value->slots[$i]['id']=$value->id;
				    $value->slots[$i]['intervel']=$value->intervel;
				    $value->slots[$i]['court']=$value->court;

				   	$slot_id=$value->slot_id;
	                $court_id=$value->id;
	                $hot_offer=$this->venue_model->get_hot_slot($court_id,$slot_id,$date);   
	                
	                if(!empty($hot_offer)){
	                    echo "<br>!hot offer";
	                    foreach ($hot_offer as $key => $value1) {
	                        $hot_offer_id=$value1->id;
	                        $hot_offer_percentage=$value1->precentage;
	                    }
	                   $value->slots[$i]['isOffer']="True";
	                   $value->slots[$i]['offer_type']=2;
	                   $value->slots[$i]['percentage']=$hot_offer_percentage;
	                   $offer_price=($hot_offer_percentage/100)*$value->cost;
	                   $value->slots[$i]['offer_price']=$value->cost-$offer_price;
	                   $value->slots[$i]['offer_id']=$hot_offer_id;
	                          
	                }else{
	                    if(!empty($offer)){
	                       //print_r($value->slots[$i]);
	                       //if($offer_timing=$this->test_model->offer_timing($offer,$value->slots[$i],$date))
	                       // print_r($value->slots[$i]);
	                    }else{
	                       echo "<br>No offer";
	                    }
	                }
		        }
		    }
		}
		
	}
	
	public function court_timing1_get(){
	    $court_id		=432;//$this->input->post('court_id');
		$day			=25;//$this->input->post('day');
		$month			=02;$this->input->post('month');
		$year			=2020;$this->input->post('year');
		$venue_id 		=779;$this->input->post('venue_id');
		$date  			=date($year."-".$month."-".$day); 
		
		$week 			= date('l', strtotime($date));
        $nameOfDay = date('D', strtotime($date));

		$court_timing=$this->venue_model->court_time($court_id,$week,$date);
		$court_capacity=$this->venue_model->get_court($court_id)[0]->capacity;
		echo "<pre>";
// 		print_r($court_timing);exit;
		
		$offer=$this->venue_model->court_offer($court_id,$date,$nameOfDay);
        $inactive=$this->venue_model->court_inactive($court_id,$date,$nameOfDay);
		$holiday= $this->venue_model->is_holiday2($date,$venue_id);
// 		print_r($holiday);
		
		if(!empty($holiday)){
		    $result=array(	'ErrorCode'=>1,
							'Data'=>"",
							'Message'=>"Holiday"
						);
		}else{
		    $i=0;
		    foreach ($court_timing as $key => $value) {
		        echo "<br>".$value->time;
		        if($value->time!="Holiday")	{
		            echo "<br>!holyday";
		            $value->slots[$i]=array('time'=>  date('h:i A', strtotime($value->time)));
				    $value->slots[$i]['id']=$value->id;
				    $value->slots[$i]['intervel']=$value->intervel;
				    $value->slots[$i]['court']=$value->court;

				   	$slot_id=$value->slot_id;
	                $court_id=$value->id;
	                $hot_offer=$this->venue_model->get_hot_slot($court_id,$slot_id,$date);
	                
	                if(!empty($hot_offer)){
	                    foreach ($hot_offer as $key => $value1) {
	                        $hot_offer_id=$value1->id;
	                        $hot_offer_percentage=$value1->precentage;
	                    }
	                   $value->slots[$i]['isOffer']="True";
	                   $value->slots[$i]['offer_type']=2;
	                   $value->slots[$i]['percentage']=$hot_offer_percentage;
	                   $offer_price=($hot_offer_percentage/100)*$value->cost;
	                   $value->slots[$i]['offer_price']=$value->cost-$offer_price;
	                   $value->slots[$i]['offer_id']=$hot_offer_id;
	                          
	                }else{
	                    if(!empty($offer)){
	                       
		                    if($offer_timing=$this->test_model->offer_timing($offer,$value->slots[$i],$date))
		                         echo "<br>Inside offer";
		                      //  print_r($offer_timing);
		                    
		                    if(!empty($offer_timing)){
		                        if($offer_timing[0]->amount==0){

    		                        $value->slots[$i]['isOffer']="True";
    		                        $value->slots[$i]['offer_type']=1;
    		                        $value->slots[$i]['percentage']=$offer_timing[0]->percentage;
    		                        $offer_price=($offer_timing[0]->percentage/100)*$value->cost;
    		                        $value->slots[$i]['offer_price']=$value->cost-$offer_price;
    		                        $value->slots[$i]['offer_id']=$offer_timing[0]->offer_id;

		                        }else{

    		                        $value->slots[$i]['isOffer']="False";
    		                        $value->slots[$i]['offer_type']=0;
    		                        $value->slots[$i]['percentage']='';
    		                        $value->slots[$i]['offer_price']='';
    		                        $value->slots[$i]['offer_id']='';

		                        }
		                                   
		                    }else{
		                        $value->slots[$i]['isOffer']="False";
		                        $value->slots[$i]['offer_type']=0;
		                        $value->slots[$i]['percentage']='';
		                        $value->slots[$i]['offer_price']='';
		                        $value->slots[$i]['offer_id']='';
		                    }

		                }
		                
		                $court_booked=$this->venue_model->court_booked2($court_id,end($value->slots),$date,$value->capacity);
		                
		                if(!empty($court_booked)){
    
    			   			$value->slots[$i]['isBooked']="True";
    
    			   			$value->slots[$i]['remaining_capacity']=0;
    
    			   		}else{
    			   		    if($date==date('Y-m-d')){
    			   		        if(strtotime($value->slots[$i]['time'])< time()) {

    				   				$value->slots[$i]['isBooked']="True";
    
    				   				$value->slots[$i]['remaining_capacity']=0;
    
    				   			}else{
    				   			    $value->slots[$i]['isBooked']="False";
									$res=$this->venue_model->get_court_book($value->slots[$i]['time'],$court_id,$date,$court_capacity);


									// if(!empty($res))
									// 	$value->slots[$i]['isBooked']="True";
									$res=$this->venue_model->court_booked2($court_id,end($value->slots),$date);

									if(empty($res))
								    	$remaining_capacity=$value->capacity;
									else
								    	$remaining_capacity=$value->capacity-$res[0]['remaining_capacity']-@$this->venue_model->get_court_book_capacity($court_id,$value->slots[$i]['time'],$date)->capacity;


					   			    $value->slots[$i]['remaining_capacity']=$remaining_capacity;
    				   			}
    			   		    }else{
    			   		        $value->slots[$i]['isBooked']="False";

    							$res=$this->venue_model->get_court_book($value->slots[$i]['time'],$court_id,$date,$court_capacity);
    
    							// if(!empty($res))
    							// 	$value->slots[$i]['isBooked']="True";
    							$res=$this->venue_model->court_booked2($court_id,end($value->slots),$date);
    							if(empty($res))
    							$remaining_capacity=$value->capacity;
    							else
    							$remaining_capacity=$value->capacity-$res[0]['remaining_capacity']-@$this->venue_model->get_court_book_capacity($court_id,$value->slots[$i]['time'],$date)->capacity;
    
    
    
    							$value->slots[$i]['remaining_capacity']=$remaining_capacity;
    			   		    }
    			   		}
    			   		if(!empty($inactive)){
    			   		    $inactive_timing=$this->venue_model->inactive_timing2($inactive,$value->slots[$i],$date);
    			   		    if(!empty($inactive_timing)){	
    				   			$value->slots[$i]['isInactive']="True";                                
    			   			}else{
    			   			 	$value->slots[$i]['isInactive']="False";
    			   			}
    			   		}else{
    			   		    $value->slots[$i]['isInactive']="False";
    			   		}
    			   		
    			   		$i+=1;
	                }
	                unset($value->id,$value->time,$value->week,$value->cost,$value->timing,$value->court_id,$value->court,$value->intervel,$value->court_time_id,$value->capacity);

		        }
		        $court[0]=[];
        		foreach ($court_timing as $key => $value) {
        			foreach ($value as $key2 => $value2) {
        				foreach ($value2 as $key3 => $value3) {
        					$court[0][]=$value3;
        				}
        			}
        		}
        		
        		$result=array(
						'ErrorCode'=>0,
						'Data'=>$court[0],
						'total_capacity'=>$court_capacity,
						'Message'=>""
				);
				
				// print_r($result);
		    }
		}
		
	}
}