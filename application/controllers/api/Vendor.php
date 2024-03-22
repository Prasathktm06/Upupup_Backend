<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Vendor extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/vendor_model");
                date_default_timezone_set("Asia/Kolkata");

	}
	public function index_post(){
	        $phone=$this->input->post('phone');
			$data=$this->vendor_model->get_vendor($phone);
           if($data){

		    return $this->response($data,200);
	        }else{
	   /* email for upupup */
           $data=$this->vendor_model->get_upupupemail();
           foreach($data as $row) {
           $user_email = $row->email;
          if($user_email!=''){
          $data['data']=array(
             'user_phone'=>$phone,
             'date'=>date('d-m-Y'),
             'time'=>date('h:i A', time()),
             );
          $to_email = $user_email;
          $subject='New vendor try to login';
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
          $this->email->from('booking@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('non_vendor',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
        
             }/* email for upupup */

	  	     $result="not exist";
		     return $this->response($result,200);
	        }

	}
	public function verole_post(){
	        $user_id=$this->input->post('user_id');
			$data=$this->vendor_model->get_vendorrole($user_id);
           if($data){
      	     
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result=array(
				
				"user has no role"
		     );
		     return $this->response($result,200);
	        }

	}
        //check that already registered user status have any change
        public function userstatus_post(){
			$user_id=$this->input->post('user_id');
			$data=$this->vendor_model->get_userstatus($user_id);
           if($data){
      	     
		    return $this->response($data,200);
	        }else{
	            //check status is inactive or user id exist
	  	     $check=$this->vendor_model->get_statuscheck($user_id); 
	  	    if($check){
      	     
		    $result="inactive";
		     return $this->response($result,200);
	        }else{
	  	      
	  	    $result="removed";
		     return $this->response($result,200);
	        }
	        }

	}
        public function veimage_post(){
		    $user_id=$this->input->post('user_id');
		    $image = imagecreatefromstring(base64_decode($image));
		    if($image != false) 
				{ 
   					imagejpeg($image, 'pics/users/'); 
				} 
			$$data=array(
				'image'=>$image
			);
			$this->vendor_model->update_vendorimage($user_id,$data);
           if($data){
      	     
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result=array(
				
				"Image upload fail"
		     );
		     return $this->response($result,200);
	        }

	}
// check phone number is already exist
public function user_check_post(){
	        $phone=$this->input->post('phone');
			$data=$this->vendor_model->get_vendor($phone);
           if($data){
            $result="exist";
		    return $this->response($result,200);
	        }else{

	  	     $result="not_exist";
		     return $this->response($result,200);
	        }

	}
//update user profile
public function update_profile_post(){
	        $user_id=$this->input->post('user_id');
	        $email=$this->input->post('email');
	         $name=$this->input->post('vendor_name');
	        $phone=$this->input->post('phone');
	       	$manager_id=$this->input->post('user_id');
            $vendor_phone=$this->input->post('vendor_phone');
            $vendor_mgr=$this->vendor_model->get_vendorusers($manager_id,$vendor_phone);
            foreach($vendor_mgr as $row) {
                  $vendor_name = $row->name;
              }
			    if($vendor_name==""){
			     $vendor_mgrs=$this->vendor_model->get_vendorcheck($manager_id,$vendor_phone); 
			      foreach($vendor_mgrs as $row) {
			             $vdr_name = $row->name;
			             }
			        if($vdr_name==""){
			          $result="vendor_deleted";
			    return $this->response($result,200);
			        }else{
			          $result="vendor_inactive";
			    return $this->response($result,200);
			        }
			    }
			   $userdata= array('email'=>$email,
		                 'name'=>$name,
		                 'phone'=>$phone);
			$data=$this->vendor_model->update_userdata($userdata,$user_id);
           if($data){
      	    $result="success";
		    return $this->response($result,200);
	        }else{
	  	      
	  	    $result="error";
		     return $this->response($result,200);
	        }

	}
//fetch manager email
public function useremail_post(){
	$user_id=$this->input->post('user_id');
	$data=$this->vendor_model->get_useremail($user_id); 
           if($data){
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="error";
		     return $this->response($result,200);
	        }

	}
//check vendor app version details
public function version_post(){
	        $identifier=$this->input->post('identifier');
		    $manager_id=$this->input->post('user_id');
            $vendor_phone=$this->input->post('vendor_phone');
            $vendor_mgr=$this->vendor_model->get_vendorusers($manager_id,$vendor_phone);
            foreach($vendor_mgr as $row) {
                  $vendor_name = $row->name;
              }
			    if($vendor_name==""){
			     $vendor_mgrs=$this->vendor_model->get_vendorcheck($manager_id,$vendor_phone); 
			      foreach($vendor_mgrs as $row) {
			             $vdr_name = $row->name;
			             }
			        if($vdr_name==""){
			          $result="vendor_deleted";
			    return $this->response($result,200);
			        }else{
			          $result="vendor_inactive";
			    return $this->response($result,200);
			        }
			    }
	$data 	=$this->vendor_model->get_version($identifier); 
           if($data){
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="No Data Found";
		     return $this->response($result,200);
	        }

	}
//fetch manager email
public function venuestatus_post(){
	$user_id=$this->input->post('user_id');
	$data=$this->vendor_model->get_venuestatus($user_id); 
           if(!empty($data)){
               $result="venue_exist";
		    return $this->response($result,200);
	        }else{
	  	      
	  	    $result="venue_not_exist";
		     return $this->response($result,200);
	        }

	}
	
///////////////////// home details start /////////////////////////////////////////////////

		public function home_post(){
		
		$user_id=$this->input->post('user_id');
		$identifier=$this->input->post('identifier');
        	$vendor_phone=$this->input->post('vendor_phone');
        	$vendor_check=$this->vendor_model->get_vendor_check($user_id);
           		if(!empty($vendor_check)){
      	     
		   		$home_details=$this->vendor_model->get_home_details($user_id);
			   		if(!empty($home_details)){

			   			foreach($home_details as $key1 => $value1) {
								$role_id=(int)$value1->role_id;
								$role=$value1->role;
								$role_level=(int)$value1->role_level;	
							}

							$homes['vendor_role']=array(
								'role_id'=>$role_id,
								'role'=>$role,
								'role_level'=>$role_level,
								);
							
							$home=$homes;

			   		}else{
			   				$homes['vendor_role']=[];
							
							$home=$homes;


			   		}

			   		$version=$this->vendor_model->get_app_version($identifier); 
					           if(!empty($version)){
								   foreach($version as $key2 => $value2) {
											$version_code=$value2->version_code;
											$version_name=$value2->version_name;
											$optional=$value2->optional;
											$url=$value2->url;	
										}
									$homes['app_version']=array(
		
											'version_code'=>$version_code,
											'version_name'=>$version_name,
											'optional'=>$optional,
											'url'=>$url,
										);
									
									$home=$homes;
						        }else{
						  	      	$homes['app_version']=[];
						  	   		$home=$homes;
						        }
					$home_venue=$this->vendor_model->get_home_venue($user_id);
				    	if(!empty($home_venue)){
						   for ($q = 0; $q < count($home_venue); $q++) {

									$venue_id=$home_venue[$q]->id;
									$venue_name=$home_venue[$q]->venue;
									$book_status=$home_venue[$q]->book_status;

									$venues=array(

									'id'=>$venue_id,
									'venue'=>$venue_name,
									'book_status'=>$book_status,
								);
							
							$home['venue_list'][$q]=$venues;
							$setting=$this->vendor_model->get_home_hotset($venue_id);
								if($setting){
						                
						                for ($r = 0; $r < count($setting); $r++) {
						                	$percent=(int)$setting[$r]->hot_percentage;
						                	$percent_id=(int)$setting[$r]->settings_id;
										}

						                $hot_percentage=$percent;
						                $hot_id=$percent_id;

						          }else{
						            $hot_percentage='100';
						            $hot_id='0';
						            
						          }

						          $hot_offer=array(
							            'hot_id'=>(int)$hot_id,
							            'hot_percentage'=>(int)$hot_percentage,
							          );
								$home['venue_list'][$q]['hot_offer']=$hot_offer;
							}
				        }else{
				  	      	$homes['venue_list']=[];
				  	   		$home=$homes;
				        }
				        
		   				$result=array(
								'errorCode'=>1,
								'data'=>$home,
								'message'=>"Success"
							);
				     return $this->response($result,200);

	        	}else{
	        
			  	    $vendor_status=$this->vendor_model->get_vendor_status($user_id); 
			  	    if($vendor_status){
		      	     
				    	$result=array(
								'errorCode'=>0,
								'data'=>[],
								'message'=>"Inactive"
							);
				     return $this->response($result,200);
			        }else{
			  	      
			  	    $result=array(
								'errorCode'=>0,
								'data'=>[],
								'message'=>"Deleted"
							);
				     return $this->response($result,200);
			        }
	        }
		}
///////////////////// home details end /////////////////////////////////////////////////
public function logincheck_post(){
	        $phone=$this->input->post('phone');
	        $email=$this->input->post('email');
	        if($email==""){
	        		$data=$this->vendor_model->get_vendorph($phone);
           			if($data){
						$result="Phone number exist";
		            	return $this->response($result,200); 
	        		}else{
	             		$vendor_status=$this->vendor_model->get_vendorinst($phone); 
	             		if($vendor_status){
	                		$result="The given number is inactive, kindly contact upUPUP";
		            		return $this->response($result,200); 
	             		}else{
	                		$result="Not exist";
		            		return $this->response($result,200);  
	             		}
	  	     
	        		}	
	        }else{
	        		$phemlext=$this->vendor_model->get_phemlext($phone,$email);
	        		if($phemlext){
						$result="Phone number and email id exist";
		            	return $this->response($result,200); 
	        		}else{

		        		$phexst=$this->vendor_model->get_phexst($phone);
		        		if($phexst){
		        			$result="Phone number exist";
			            	return $this->response($result,200); 
		        		}else{
		        		$emailexst=$this->vendor_model->get_emailexst($email);	
			        		if($emailexst){
			        			$result="Email id exist";
				            	return $this->response($result,200); 
			        		}else{
			        			$phemlnext=$this->vendor_model->get_phemlnext($phone,$email);
			        			if($phemlnext){
			        				$result="The given number is inactive, kindly contact upUPUP";
				            		return $this->response($result,200); 
			        			}else{
			        				$phnexst=$this->vendor_model->get_phnexst($phone);
				        			if($phnexst){
				        				$result="The given number is inactive, kindly contact upUPUP";
					            		return $this->response($result,200); 
				        			}else{
				        				$emailnexst=$this->vendor_model->get_emailnexst($email);
					        			if($emailnexst){
					        				$result="The given number is inactive, kindly contact upUPUP";
						            		return $this->response($result,200); 
					        			}else{
					        				$result="Not exist";
						            		return $this->response($result,200); 

					        			}
				        			}
			        			}
			        		}

		        		}

	        		}
	        }


	}


	

}
