<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Holiday extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/holiday_model");

	}
public function index_post(){
         $user_id=$this->input->post('user_id');

        $data=$this->holiday_model->get_holiday($user_id);
        foreach($data as $key => $value) {
            $venue_id = (int)$value->id;
            $setting=$this->holiday_model->get_hotoffersetting($venue_id);

                if($setting){
                  foreach($setting as $row) {
                       $percent = $row->hot_percentage;
                       $percent_id = $row->settings_id;
                    }
                $hot_percentage=$percent;
                $hot_id=$percent_id;

          }else{
            $hot_percentage='100';
            $hot_id='0';
            
          }
        $hot_offer=[];
        $hot_offer=array(
            'hot_id'=>(int)$hot_id,
            'hot_percentage'=>(int)$hot_percentage,
          );
        $value->hot_offer=$hot_offer;
        
             }
             
        if($data){ 
        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($result,200);
          }

  }
public function bookholi_post(){
	      $venue_id=$this->input->post('venue_id');
		
	      $data=$this->holiday_model->get_addedholiday($venue_id);
                if($data){ 
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }     

	}
public function holidaysadded_post(){
	      $venue_id=$this->input->post('venue_id');
		
	      $data=$this->holiday_model->get_holidaysavailabe($venue_id);
                if($data){ 
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result=[];
		     return $this->response($result,200);
	        }     

	}
	
	
	public function holidayslist_post(){
	      $venue_id=$this->input->post('venue_id');
		
	      $data=$this->holiday_model->get_holidaysavailabe($venue_id);
	      
	      
	      	  if(!empty($data))
                {
                    $result=array(
                    'errorCode'=>1,
                    'data'=>$data,
                    'message'=>"success"
                    );
                return $this->response($result,200);
                }
                else
                {
                    $result=array(
                    'errorCode'=>1,
                    'data'=>[],
                    'message'=>"user does not exist"
                );
                return $this->response($result,200);
                }
	      
	      
              

	}
	
public function holidaysdelete_post(){
	      $id=$this->input->post('id');
		$vendor_phone=$this->input->post('vendor_phone');
        $manager_id=$this->input->post('user_id');
        $vendor_mgr=$this->holiday_model->get_vendorusers($manager_id,$vendor_phone);
       foreach($vendor_mgr as $row) {
                 $vendor_name = $row->name;
                 }
            if($vendor_name==""){
             $vendor_mgrs=$this->holiday_model->get_vendorcheck($manager_id,$vendor_phone); 
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
	      $data=$this->holiday_model->delete_holidays($id);
            if($data){ 
            $result="success";
		    return $this->response($result,200);
	        }else{
	  	      
	  	    $result="fail";
		     return $this->response($result,200);
	        }     

	}
public function booked_post(){
	        $venue_id=$this->input->post('venue_id');
	        $date=$this->input->post('date');   
            $books=$this->holiday_model->get_bookholiday($venue_id,$date);
                if($books){ 
		    $result="exist";
			return $this->response($result,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }    

	}


       
public function holidays_post(){
			$applicants=$this->input->post('holiday');
			$count=$this->input->post('count');
			$json_output = json_decode($applicants,TRUE );
			
			for($mn=0;$mn<$count;$mn++){
       $manager_id= $json_output[$mn][user_id]; 
      }
        $vendor_phone=$this->input->post('vendor_phone');
        $vendor_mgr=$this->holiday_model->get_vendorusers($manager_id,$vendor_phone);
       foreach($vendor_mgr as $row) {
                 $vendor_name = $row->name;
                 }
            if($vendor_name==""){
             $vendor_mgrs=$this->holiday_model->get_vendorcheck($manager_id,$vendor_phone); 
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
			
			for($i=0;$i<$count;$i++){
				$user_id= $json_output[$i][user_id];
				$venue_id= $json_output[$i][venue_id];
				$date= $json_output[$i][date];
				$description= $json_output[$i][description];
				$name= $json_output[$i][name];
				$email= $json_output[$i][email];
                               
				$data=array(
			         'venue_id'=>$venue_id,
			         'date'=>$date,
			         'description'=>$description
			        );
				$adds=$this->holiday_model->insert_holiday($data);

				 if($adds)
                     {
                      $inst=1;
                     }else{
                       if($inst==1){
              	         $inst=1;
                           }else{
              	            $inst=0;
                             }
                          }
			}
			if($inst==1){
                        $kcu="";
            for($j=0;$j<$count;$j++){
            $dates= $json_output[$j][date];
            $kcf=''.$kcu.''.$dates.''."\t".'';
            $kcu=$kcf;	
            }
            $role=$this->holiday_model->get_userrole($user_id);
             foreach($role as $row) {
            $role_name = $row->name;
            $phone = $row->phone;
            $uname = $row->user_rname;
            }
            $venue=$this->holiday_model->get_venuename($venue_id);
             foreach($venue as $row) {
            $venue_name = $row->venue;
            }
            
            $areaa=$this->holiday_model->get_areaaname($venue_id);
             foreach($areaa as $row) {
            $area_name = $row->area;
            }
            
            
             /* email for upupup start*/
            $upupemail=$this->holiday_model->get_upupupemail();
              foreach($upupemail as $row) {
            $upup_mail = $row->email;

            if($role_name!=''){
          $data['data']=array(
             'role'=>$role_name,
             'phone'=>$phone,
             'description'=>$description,
             'date'=>date('dS F Y', strtotime($date)),
             'venue_name'=>$venue_name,
             'uname'=>$uname,
             'area'=>$area_name,
             );
          $to_email = $upup_mail;
          $subject='Holiday Added !!';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('holiday_mailup',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }

            }
          /* email for upupup end */

        /* email for venue owner start */
           $owner=$this->holiday_model->get_owner($venue_id);
            foreach($owner as $row) {
            $owner_name = $row->name;
            $owner_email = $row->email;
            if($owner_email!=''){
          $data['data']=array(
             'name'=>$owner_name,
             'role'=>$role_name,
             'phone'=>$phone,
             'description'=>$description,
             'date'=>date('dS F Y', strtotime($date)),
             'venue_name'=>$venue_name,
             'uname'=>$uname,
             'area'=>$area_name,
             );
          $to_email = $owner_email;
          $subject='Holiday Added !!';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('holiday_mailowner',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
            }
         /* email for venue owner end */

              $result="success";
		    return $this->response($result,200);
			}else{
				$result="insertion fail";
		        return $this->response($result,200);
			}


	}


//////////////////////////////////////////////// test /////////////////////////////////////////////////
public function checktest_mode_get(){

          $to_email = "jinson.gooseberry@gmail.com";
          $subject='email design 26';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('newformat',$data,true);
          $this->email->message($message);
          $this->email->send();
          
          $result="mail send";
          return $this->response($result,200);
}
/////////////////////////////////////////////// test /////////////////////////////////////////////////



}
