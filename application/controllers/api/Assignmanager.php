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
class Assignmanager extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/assignmanager_model");
		date_default_timezone_set("Asia/Kolkata");
	}


//add venue manager
public function addmanager_post(){
   $role=$this->input->post('role');
   $name=$this->input->post('name');
   $phone=$this->input->post('phone');
   $email=$this->input->post('email');
   $venues=$this->input->post('venues');
   $manager_id=$this->input->post('user_id');
   $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->assignmanager_model->get_vendorusers($vendor_phone);
   if(empty($vendor_mgr)){
       foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->assignmanager_model->get_vendorcheck($vendor_phone); 
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
   }
   $json_output = json_decode($venues,TRUE );
   $venuecount  = count($json_output);
   $data=$this->assignmanager_model->get_usercheck($phone);
   if($data){
    $result="phone number already exist";
    return $this->response($result,200);
   }else{
    //check email id already exist start
    if($email!=""){
     $em=$this->assignmanager_model->get_emailcheck($email); 

    
    if($em){
     $result="email id already exist";
    return $this->response($result,200); 
    }else{
       $status=1;
    $data=array(
      'name'=>$name,
      'email'=>$email,
      'phone'=>$phone,
      'status'=>$status,
      );
    $user_id=$this->assignmanager_model->insert_manager($data);
     $role_id=11;
   $data=array(
      'user_id'=>$user_id,
      'role_id'=>$role_id,
      );
    $user_role=$this->assignmanager_model->insert_userrole($data);
    //venue id extraction start
    for($i=0;$i<$venuecount;$i++){
      $venue_id=$json_output[$i][venue];
      $data=array(
      'user_id'=>$user_id,
      'venue_id'=>$venue_id,
      );
      $add=$this->assignmanager_model->insert_venuemanager($data);
    }//venue id extraction end
//venue data extraction start
    $venue_names="";
    for($i=0;$i<$venuecount;$i++){
      $venue_id=$json_output[$i][venue];
      $venue_details=$this->assignmanager_model->get_venuedata($venue_id);
      foreach($venue_details as $row) {
             $venue_name = $row->venue;
             }
             $venue_names=$venue_names.$venue_name.", ";
    }//venue data extraction end

    $manager_data=$this->assignmanager_model->get_managerdata($manager_id);
     foreach($manager_data as $row) {
             $mgr_name = $row->name;
             $mgr_email = $row->email;
             $mgr_phone = $row->phone;
             }
    if($user_id){
         /* email for manager */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'venue_name'=>$venue_names,
             'mgr_name'=>$mgr_name,
             );
          $to_email = $email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('managers_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for manager */
      $message="Congratulations!! You are now part of the upUPUP family. ".$mgr_name." has added you as ".$role.". Login with the below credentials \r\nLogin to : https://play.google.com/store/apps/details?id=com.dev.gooseberryits.upupup \r\nLogin with : ".$phone."";
      $this->common->sms(str_replace(' ', '', $phone),urlencode($message));

      /* email for owner */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'venue_name'=>$venue_names,
             'mgr_name'=>$mgr_name,
             );
          $to_email = $mgr_email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('assignowner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for owner */
     /* email for owner */
      $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'venue_name'=>$venue_names,
             'mgr_name'=>$mgr_name,
             'mgr_phone'=>$mgr_phone,
             );
          $to_email = $upupup_email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('assignupupup_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */ 
     $result="success";
    return $this->response($result,200); 
    }else{
      $result="error";
    return $this->response($result,200); 
    }
    }
    //check email id already exist end
  }else{
         $status=1;
    $data=array(
      'name'=>$name,
      'email'=>$email,
      'phone'=>$phone,
      'status'=>$status,
      );
    $user_id=$this->assignmanager_model->insert_manager($data);
 $role_id=11;
   $data=array(
      'user_id'=>$user_id,
      'role_id'=>$role_id,
      );
    $user_role=$this->assignmanager_model->insert_userrole($data);
    //venue id extraction start
    for($i=0;$i<$venuecount;$i++){
      $venue_id=$json_output[$i][venue];
      $data=array(
      'user_id'=>$user_id,
      'venue_id'=>$venue_id,
      );
      $add=$this->assignmanager_model->insert_venuemanager($data);
    }//venue id extraction end
    //venue data extraction start
    $venue_names="";
    for($i=0;$i<$venuecount;$i++){
      $venue_id=$json_output[$i][venue];
      $venue_details=$this->assignmanager_model->get_venuedata($venue_id);
      foreach($venue_details as $row) {
             $venue_name = $row->venue;
             }
             $venue_names=$venue_names.$venue_name.", ";
    }//venue data extraction end

    $manager_data=$this->assignmanager_model->get_managerdata($manager_id);
     foreach($manager_data as $row) {
             $mgr_name = $row->name;
             $mgr_email = $row->email;
             $mgr_phone = $row->phone;
             }
    if($user_id){
    $message="Congratulations!! You are now part of the upUPUP family.".$mgr_name." has added you as ".$role.". Login with the below credentials \r\nLogin to : https://play.google.com/store/apps/details?id=com.dev.gooseberryits.upupup \r\nLogin with : ".$phone."";
                            $this->common->sms(str_replace(' ', '', $phone),urlencode($message));
        
                            /* email for owner */
                                $data['data']=array(
                                   'name'=>$name,
                                   'phone'=>$phone,
                                   'role'=>$role,
                                   'venue_name'=>$venue_names,
                                   'mgr_name'=>$mgr_name,
                                   );
                                $to_email = $mgr_email;
                                $subject='Congratulations!!';
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
                                $message = $this->load->view('assignowner_mail',$data,true);
                                $this->email->message($message);
                                $this->email->send();
                                    
                              
                            /* email for owner */
                                                       /* email for owner */
                            $upupup=$this->assignmanager_model->get_upupupemail();
                           foreach($upupup as $row) {
                                   $upupup_email = $row->email;
                                  /* email for upupup */
                                $data['data']=array(
                                   'name'=>$name,
                                   'phone'=>$phone,
                                   'role'=>$role,
                                   'venue_name'=>$venue_names,
                                   'mgr_name'=>$mgr_name,
                                   'mgr_phone'=>$mgr_phone,
                                   );
                                $to_email = $upupup_email;
                                $subject='Congratulations!!';
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
                                $message = $this->load->view('assignupupup_mail',$data,true);
                                $this->email->message($message);
                                $this->email->send();
                                    
                              
                           
                                   }
                               /* email for upupup */ 
     $result="success";
    return $this->response($result,200); 
    }else{
      $result="error";
    return $this->response($result,200); 
    }
  }
         
  }
}
//court and sports details corresponding to the venue_id
public function details_post(){
         $venue_id=$this->input->post('venue_id');

      $data=$this->assignmanager_model->get_details($venue_id);

        if($data){

        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($result,200);
          }

  }

//add court manager
public function addcourtmanager_post(){
  $role=$this->input->post('role');
  $name=$this->input->post('name');
  $phone=$this->input->post('phone');
  $email=$this->input->post('email');
  $venue_id=$this->input->post('venue_id');
  $courts=$this->input->post('courts');
  $manager_id=$this->input->post('user_id');
  $vendor_phone=$this->input->post('vendor_phone');
    $vendor_mgr=$this->assignmanager_model->get_vendorusers($vendor_phone);

   if(empty($vendor_mgr)){
       foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->assignmanager_model->get_vendorcheck($vendor_phone); 
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
   }
  $json_output = json_decode($courts,TRUE );
  $courtcount  = count($json_output);      
     $data=$this->assignmanager_model->get_usercheck($phone);
   if($data){
    $result="phone number already exist";
    return $this->response($result,200);
   }else{
    //check email id already exist start
    if($email!=""){
     $em=$this->assignmanager_model->get_emailcheck($email); 

    
    if($em){
     $result="email id already exist";
    return $this->response($result,200); 
    }else{
       $status=1;
    $data=array(
      'name'=>$name,
      'email'=>$email,
      'phone'=>$phone,
      'status'=>$status,
      );
    $user_id=$this->assignmanager_model->insert_manager($data);
     $role_id=25;
   $data=array(
      'user_id'=>$user_id,
      'role_id'=>$role_id,
      );
    $user_role=$this->assignmanager_model->insert_userrole($data);

      $data=array(
      'user_id'=>$user_id,
      'venue_id'=>$venue_id,
      );
      $add=$this->assignmanager_model->insert_venuemanager($data);
      //court id extraction start
       for($i=0;$i<$courtcount;$i++){
      $court_id=$json_output[$i][court];
      $data=array(
      'user_id'=>$user_id,
      'court_id'=>$court_id,
      );
      $add=$this->assignmanager_model->insert_courtmanager($data);
    }//court id extraction end
      //court details extraction start
    $court_names="";
       for($i=0;$i<$courtcount;$i++){
      $court_id=$json_output[$i][court];
      $court_details=$this->assignmanager_model->get_courtdata($court_id);
      foreach($court_details as $row) {
             $court_name = $row->court;
             }
             $court_names=$court_names.$court_name.", ";
    }
    //court details extraction end
    $manager_data=$this->assignmanager_model->get_managerdata($manager_id);
     foreach($manager_data as $row) {
             $mgr_name = $row->name;
             $mgr_email = $row->email;
             $mgr_phone = $row->phone;
             }
    if($user_id){
         /* email for manager */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'venue_name'=>$court_names,
             'mgr_name'=>$mgr_name,
             );
          $to_email = $email;
          $subject='Congratulations!!';
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
          $this->email->from('admin@upupup.in','UPupup.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('managers_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for manager */
      //sms for new manager
      $message="Congratulations!! You are now part of the upUPUP family.".$mgr_name." has added you as ".$role.". Login with the below credentials \r\n Login to      :  https://play.google.com/store/apps/details?id=com.dev.gooseberryits.upupup \r\n Login with   : ".$phone."";
      $this->common->sms(str_replace(' ', '', $phone),urlencode($message));

      $owner_data=$this->assignmanager_model->get_ownerdata($venue_id);
      foreach($owner_data as $row) {
             $ow_name = $row->name;
             $ow_email = $row->email;
             
               /* email for owner */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'court_name'=>$court_names,
             'mgr_name'=>$mgr_name,
             'ow_name'=>$ow_name,
             );
          $to_email = $ow_email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('assigncowner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for owner */

             }
       $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'venue_name'=>$court_names,
             'mgr_name'=>$mgr_name,
             'mgr_phone'=>$mgr_phone,
             );
          $to_email = $upupup_email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('assignupupup_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */ 
     $result="success";
    return $this->response($result,200); 
    }else{
      $result="error";
    return $this->response($result,200); 
    }
    }
    //check email id already exist end
  }else{
         $status=1;
    $data=array(
      'name'=>$name,
      'email'=>$email,
      'phone'=>$phone,
      'status'=>$status,
      );
    $user_id=$this->assignmanager_model->insert_manager($data);
 $role_id=25;
   $data=array(
      'user_id'=>$user_id,
      'role_id'=>$role_id,
      );
    $user_role=$this->assignmanager_model->insert_userrole($data);
    $data=array(
      'user_id'=>$user_id,
      'venue_id'=>$venue_id,
      );
      $add=$this->assignmanager_model->insert_venuemanager($data);
      //court id extraction start
       for($i=0;$i<$courtcount;$i++){
      $court_id=$json_output[$i][court];
      $data=array(
      'user_id'=>$user_id,
      'court_id'=>$court_id,
      );
      $add=$this->assignmanager_model->insert_courtmanager($data);
    }//court id extraction end
        $court_names="";
       for($i=0;$i<$courtcount;$i++){
      $court_id=$json_output[$i][court];
      $court_details=$this->assignmanager_model->get_courtdata($court_id);
      foreach($court_details as $row) {
             $court_name = $row->court;
             }
             $court_names=$court_names.$court_name.", ";
    }
    //court details extraction end
    $manager_data=$this->assignmanager_model->get_managerdata($manager_id);
     foreach($manager_data as $row) {
             $mgr_name = $row->name;
             $mgr_email = $row->email;
             $mgr_phone = $row->phone;
             }
    if($user_id){
      //sms for new manager
      $message="Congratulations!! You are now part of the upUPUP family.".$mgr_name." has added you as ".$role.". Login with the below credentials \r\n Login to      :  https://play.google.com/store/apps/details?id=com.dev.gooseberryits.upupup \r\n Login with   : ".$phone."";
      $this->common->sms(str_replace(' ', '', $phone),urlencode($message));

      $owner_data=$this->assignmanager_model->get_ownerdata($venue_id);
      foreach($owner_data as $row) {
             $ow_name = $row->name;
             $ow_email = $row->email;
             
               /* email for owner */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'court_name'=>$court_names,
             'mgr_name'=>$mgr_name,
             'ow_name'=>$ow_name,
             );
          $to_email = $ow_email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('assigncowner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for owner */

             }
       $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
          $data['data']=array(
             'name'=>$name,
             'phone'=>$phone,
             'role'=>$role,
             'venue_name'=>$court_names,
             'mgr_name'=>$mgr_name,
             'mgr_phone'=>$mgr_phone,
             );
          $to_email = $upupup_email;
          $subject='Congratulations!!';
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
          $message = $this->load->view('assignupupup_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */ 
     $result="success";
    return $this->response($result,200);
    }else{
      $result="error";
    return $this->response($result,200); 
    }
  }
         
  }
  }

//managers list coresponding venue
public function managerlist_post(){
     $user_id=$this->input->post('user_id');
     $venues=$this->assignmanager_model->get_venues($user_id);
     $manage=[];
     foreach($venues as $row) {
            $venue_id = $row->venue_id;
            $roles="Venue Owner";
          $role_adm="Administrator";
          $role_edt="Back end editor";
          $manager=$this->assignmanager_model->get_managerlist($venue_id,$roles,$role_adm,$role_edt);
          $manage = array_merge_recursive($manage, $manager);
      }
   $uniqueArray= array_values(array_unique($manage, SORT_REGULAR));
   return $this->response($uniqueArray,200);
         
  }
//fetching court manager details
public function cmdetails_post(){
     $user_id=$this->input->post('user_id');
     $data=$this->assignmanager_model->get_cmdetails($user_id);
     return $this->response($data,200);
         
  }
//edit court manager courts
public function cmedit_post(){
     $user_id=$this->input->post('user_id');
     $courts=$this->input->post('courts');
     $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->assignmanager_model->get_vendorusers($vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->assignmanager_model->get_vendorcheck($vendor_phone); 
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
     $data=$this->assignmanager_model->delete_cmcourts($user_id);
     $json_output = json_decode($courts,TRUE );
     $courtcount  = count($json_output);
    //court id extraction start
    for($i=0;$i<$courtcount;$i++){
      $court_id=$json_output[$i][court];
      $data=array(
      'user_id'=>$user_id,
      'court_id'=>$court_id,
      );
      $add=$this->assignmanager_model->insert_courtmanager($data);
    }//court id extraction end 
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
    $court_names="";
       for($j=0;$j<$courtcount;$j++){
      $court_id=$json_output[$j][court];
      $court_details=$this->assignmanager_model->get_courtdata($court_id);
      foreach($court_details as $row) {
             $court_name = $row->court;
             }
             $court_names=$court_names.$court_name.", ";
    }

    $assigned_mgr_details=$this->assignmanager_model->get_assigned_mgr($vendor_phone);
     foreach($assigned_mgr_details as $row) {
             $asgn_mgr_id = $row->user_id;
             $asgn_mgr_name = $row->name;
             $asgn_mgr_email = $row->email;
             $asgn_mgr_phone = $row->phone;
             }
    
    $cmgr_details=$this->assignmanager_model->get_cmgr($user_id);
    foreach($cmgr_details as $row) {
             $cmgr_id = $row->user_id;
             $cmgr_name = $row->name;
             $cmgr_email = $row->email;
             $cmgr_phone = $row->phone;
             }
    $cmgr_role_details=$this->assignmanager_model->get_cmgrrole($user_id);
    foreach($cmgr_role_details as $row) {
             $cmgr_role = $row->name;
             }
    
    for($j=0;$j<$courtcount;$j++){
      $court_id=$json_output[$j][court];
    }
    $court_venue=$this->assignmanager_model->get_court_venue($court_id);
    foreach($court_venue as $row) {
             $venue_id = $row->venue_id;
             }
    $venue_details=$this->assignmanager_model->get_venue_details($venue_id);
    foreach($venue_details as $row) {
             $venue_name = $row->venue;
             }
    $owner_data=$this->assignmanager_model->get_ownerdata($venue_id);

      foreach($owner_data as $row) {
             $ow_name = $row->name;
             $ow_email = $row->email;
             /* email for owner */
          $data['data']=array(
             'owner_name'=>$ow_name,
             'assigned_mgr_name'=>$asgn_mgr_name,
             'assigned_mgr_phone'=>$asgn_mgr_phone,
             'mgr_name'=>$cmgr_name,
             'mgr_phone'=>$cmgr_phone,
             'mgr_role'=>$cmgr_role,
             'court_names'=>$court_names,
             'venue'=>$venue_name,
             );
            
          $to_email = $ow_email;
          $subject='Court Reassigned !!';
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
          $message = $this->load->view('asgncmgr_edit_owner',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for owner */
           }

      $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
           $data['data']=array(
             'owner_name'=>$ow_name,
             'assigned_mgr_name'=>$asgn_mgr_name,
             'assigned_mgr_phone'=>$asgn_mgr_phone,
             'mgr_name'=>$cmgr_name,
             'mgr_phone'=>$cmgr_phone,
             'mgr_role'=>$cmgr_role,
             'court_names'=>$court_names,
             'venue'=>$venue_name,
             );
          $to_email = $upupup_email;
          $subject='Court Reassigned !!';
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
          $message = $this->load->view('asgncmgr_edit_upupup',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */ 

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
     if($add){
      $result="success";
      return $this->response($result,200);
     }else{
      $result="error";
      return $this->response($result,200);
     }
     
         
  }

/////////////////////inactivate  court manager details  ///////////////////////////
public function cmdelete_post(){
     $user_id=$this->input->post('user_id');
     $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->assignmanager_model->get_vendorusers($vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->assignmanager_model->get_vendorcheck($vendor_phone); 
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
    $insert_data=array('status'=>0);
     $dataset=$this->assignmanager_model->update_status($insert_data,'user',$user_id); 
     //$venue=$this->assignmanager_model->delete_cmvenue($user_id);
     //$court=$this->assignmanager_model->delete_cmcourts($user_id);
     //$role=$this->assignmanager_model->delete_cmroles($user_id);
     //$data=$this->assignmanager_model->delete_cmuser($user_id);
     $court_details=$this->assignmanager_model->get_court_detail($user_id);
     $court_names="";
     foreach($court_details as $row) {
             $court_name = $row->court;
             $court_names=$court_names.$court_name.", ";
             }
    $court_details=$this->assignmanager_model->get_court_detail($user_id);
    foreach($court_details as $row) {
             $court_id = $row->id;
             }
    $court_venue=$this->assignmanager_model->get_court_venue($court_id);
    foreach($court_venue as $row) {
             $venue_id = $row->venue_id;
             }
    $venue_details=$this->assignmanager_model->get_venue_details($venue_id);
    foreach($venue_details as $row) {
             $ven_name = $row->venue;
             }
    $vmgr_detail=$this->assignmanager_model->get_venue_mgrdata($user_id);
    foreach($vmgr_detail as $row) {
             $vmgr_name = $row->name;
             $vmgr_email = $row->email;
             $vmgr_phone = $row->phone;
             }
    $assign_manager=$this->assignmanager_model->get_assign_manager($vendor_phone);
    foreach($assign_manager as $row) {
             $owner_name = $row->name;
             $owner_email = $row->email;
             $owner_phone = $row->phone;
             }
    $data['data']=array(
             'owner_name'=>$owner_name,
             'owner_phone'=>$owner_phone,
             'mgr_name'=>$vmgr_name,
             'mgr_phone'=>$vmgr_phone,
             'court_names'=>$court_names,
             'venue_name'=>$ven_name
             );
          $to_email = $owner_email;
          $subject='Court Manager Deletion';
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
          $message = $this->load->view('cmgr_deletion_owner',$data,true);
          $this->email->message($message);
          $this->email->send();
          
        $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
           $data['data']=array(
             'owner_name'=>$owner_name,
             'owner_phone'=>$owner_phone,
             'mgr_name'=>$vmgr_name,
             'mgr_phone'=>$vmgr_phone,
             'court_names'=>$court_names,
             'venue_name'=>$ven_name
             );
          $to_email = $upupup_email;
          $subject='Court Manager Deletion';
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
          $message = $this->load->view('cmgr_deletion_upupup',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */
     if($dataset){
      $result="success";
      return $this->response($result,200);
     }else{
      $result="error";
      return $this->response($result,200);
     }
         
  }
//fetching venue manager details
public function vmdetails_post(){
     $user_id=$this->input->post('user_id');
     $data=$this->assignmanager_model->get_vmdetails($user_id);
     return $this->response($data,200);
         
  }
//edit venue manager courts
public function vmedit_post(){
     $user_id=$this->input->post('user_id');
     $venues=$this->input->post('venues');
     $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->assignmanager_model->get_vendorusers($vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->assignmanager_model->get_vendorcheck($vendor_phone); 
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
     $venue=$this->assignmanager_model->delete_cmvenue($user_id);
     $json_output = json_decode($venues,TRUE );
    $venuescount  = count($json_output);
    //venue id extraction start
    for($i=0;$i<$venuescount;$i++){
      $venue_id=$json_output[$i][venue];
      $data=array(
      'user_id'=>$user_id,
      'venue_id'=>$venue_id,
      );
      $add=$this->assignmanager_model->insert_venuemanager($data);
    }//venue id extraction end 
   ///////////////////////////////////////////////////////////////////////////
    $venue_names="";
       for($j=0;$j<$venuescount;$j++){
      $venue_id=$json_output[$j][venue];
      $venue_details=$this->assignmanager_model->get_venuedata($venue_id);
      foreach($venue_details as $row) {
             $venue_name = $row->venue;
             }
             $venue_names=$venue_names.$venue_name.", ";
    }
     $assigned_mgr_details=$this->assignmanager_model->get_assigned_mgr($vendor_phone);
     foreach($assigned_mgr_details as $row) {
             $asgn_mgr_id = $row->user_id;
             $asgn_mgr_name = $row->name;
             $asgn_mgr_email = $row->email;
             $asgn_mgr_phone = $row->phone;
             }
    $vmgr_details=$this->assignmanager_model->get_vmgr($user_id);
    foreach($vmgr_details as $row) {
             $vmgr_id = $row->user_id;
             $vmgr_name = $row->name;
             $vmgr_email = $row->email;
             $vmgr_phone = $row->phone;
             }
    $owner_data=$this->assignmanager_model->get_ownerdata($venue_id);
      foreach($owner_data as $row) {
             $ow_name = $row->name;
             $ow_email = $row->email;

             /* email for owner */
          $data['data']=array(
             'owner_name'=>$ow_name,
             'assigned_mgr_name'=>$asgn_mgr_name,
             'assigned_mgr_phone'=>$asgn_mgr_phone,
             'mgr_name'=>$vmgr_name,
             'mgr_phone'=>$vmgr_phone,
             'mgr_role'=>"Venue Manager",
             'venue_names'=>$venue_names,
             );
          $to_email = $ow_email;
          $subject='Venue Reassigned !!';
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
          $message = $this->load->view('asgnvmgr_edit_owner',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
      /* email for owner */
           }

    $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
           $data['data']=array(
             'owner_name'=>$ow_name,
             'assigned_mgr_name'=>$asgn_mgr_name,
             'assigned_mgr_phone'=>$asgn_mgr_phone,
             'mgr_name'=>$vmgr_name,
             'mgr_phone'=>$vmgr_phone,
             'mgr_role'=>"Venue Manager",
             'venue_names'=>$venue_names,
             );
          $to_email = $upupup_email;
          $subject='Venue Reassigned !!';
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
          $message = $this->load->view('asgnvmgr_edit_upupup',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */ 

  //////////////////////////////////////////////////////////////////////////       
     if($add){
      $result="success";
      return $this->response($result,200);
     }else{
      $result="error";
      return $this->response($result,200);
     }
     
         
  }
//remove venue manager details
public function vmdelete_post(){
     $user_id=$this->input->post('user_id');
     $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->assignmanager_model->get_vendorusers($vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->assignmanager_model->get_vendorcheck($vendor_phone); 
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
    $insert_data=array('status'=>0);
     $dataset=$this->assignmanager_model->update_status($insert_data,'user',$user_id); 
     //$venue=$this->assignmanager_model->delete_cmvenue($user_id);
     //$role=$this->assignmanager_model->delete_cmroles($user_id);
     //$data=$this->assignmanager_model->delete_cmuser($user_id);
     
      $venue_detail=$this->assignmanager_model->get_venue_detail($user_id);
     $venue_names="";
     foreach($venue_detail as $row) {
             $venue_name = $row->venue;
             $venue_names=$venue_names.$venue_name.", ";
             }
    $vmgr_detail=$this->assignmanager_model->get_venue_mgrdata($user_id);
    foreach($vmgr_detail as $row) {
             $vmgr_name = $row->name;
             $vmgr_email = $row->email;
             $vmgr_phone = $row->phone;
             }
    $assign_manager=$this->assignmanager_model->get_assign_manager($vendor_phone);
    foreach($assign_manager as $row) {
             $owner_name = $row->name;
             $owner_email = $row->email;
             $owner_phone = $row->phone;
             }
    $data['data']=array(
             'owner_name'=>$owner_name,
             'owner_phone'=>$owner_phone,
             'mgr_name'=>$vmgr_name,
             'mgr_phone'=>$vmgr_phone,
             'venue_names'=>$venue_names
             );
          $to_email = $owner_email;
          $subject='Venue Manager Deletion';
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
          $message = $this->load->view('vmgr_deletion_owner',$data,true);
          $this->email->message($message);
          $this->email->send();
          
        $upupup=$this->assignmanager_model->get_upupupemail();
     foreach($upupup as $row) {
             $upupup_email = $row->email;
            /* email for upupup */
           $data['data']=array(
             'owner_name'=>$owner_name,
             'owner_phone'=>$owner_phone,
             'mgr_name'=>$vmgr_name,
             'mgr_phone'=>$vmgr_phone,
             'venue_names'=>$venue_names
             );
          $to_email = $upupup_email;
          $subject='Venue Manager Deletion';
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
          $message = $this->load->view('vmgr_deletion_upupup',$data,true);
          $this->email->message($message);
          $this->email->send();
              
        
     
             }
         /* email for upupup */ 
         
         
     if($dataset){
      $result="success";
      return $this->response($result,200);
     }else{
      $result="error";
      return $this->response($result,200);
     }
         
  }
   

  

}