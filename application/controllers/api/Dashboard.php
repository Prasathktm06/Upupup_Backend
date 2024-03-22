<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Dashboard extends REST_Controller {

  function __construct()
  {

    parent::__construct();

    // Configure limits on our controller methods
    // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
    $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
    $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    $this->load->model("api/dashboard_model");
    date_default_timezone_set("Asia/Kolkata");
  }

//booking details for vendor app
public function index_post(){
         $venue_id=$this->input->post('venue_id');
         $start_date=$this->input->post('start_date');
         $end_date=$this->input->post('end_date');
         $type=$this->input->post('type');
         $user_id=$this->input->post('user_id');
         $user_role=$this->input->post('user_role');
         $role="Court Manager";
         if($user_role!=$role){
              //booking details of a venue between date start 
    if($type=="all"){
   $data=$this->dashboard_model->get_booking($venue_id,$start_date,$end_date);
   return $this->response($data,200);
   }else{
    //upupup booking details between date start
        if($type=="upupup"){
          $vendor="vendor";
        $data=$this->dashboard_model->get_upupupbooking($venue_id,$start_date,$end_date,$vendor);
        return $this->response($data,200);
        }else{
          //vendor booking details between date start
            if($type=="vendor"){
              $vendor="vendor";
            $data=$this->dashboard_model->get_vendorbooking($venue_id,$start_date,$end_date,$vendor);
            return $this->response($data,200);
            }else{
                $result="type miss matching";
               return $this->response( $result,200); 
              } //vendor booking details between date end
          }//upupup booking details between date end 
        }//booking details of a venue between date end
        }else{
              //booking details for court manager a venue between date start 
    if($type=="all"){
   $data=$this->dashboard_model->get_cmbooking($venue_id,$start_date,$end_date,$user_id);
   return $this->response($data,200);
   }else{
    //upupup booking details between date start
        if($type=="upupup"){
          $vendor="vendor";
        $data=$this->dashboard_model->get_cmupupupbooking($venue_id,$start_date,$end_date,$vendor,$user_id);
        return $this->response($data,200);
        }else{
          //vendor booking details between date start
            if($type=="vendor"){
              $vendor="vendor";
            $data=$this->dashboard_model->get_cmvendorbooking($venue_id,$start_date,$end_date,$vendor,$user_id);
            return $this->response($data,200);
            }else{
                $result="type miss matching";
               return $this->response( $result,200); 
              } //vendor booking details between date end
          }//upupup booking details between date end 
        }//booking details for court manager a venue between date end
        } 

 }

//booking details
public function bookingdetails_post(){
  $booking_id=$this->input->post('booking_id');
  $data=$this->dashboard_model->get_bookingdetails($booking_id);
  return $this->response($data,200);
}

//cancel vendors booking from vendor app
public function cancelbooking_post(){
  $booking_id=$this->input->post('booking_id');
  $user_id=$this->input->post('user_id');
  $vendor_phone=$this->input->post('vendor_phone');
  $manager_id=$this->input->post('user_id');
  $vendor_mgr=$this->dashboard_model->get_vendorusers($manager_id,$vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->dashboard_model->get_vendorcheck($manager_id,$vendor_phone); 
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
  $payment_mode=3;
   $data=array(
          'booking_id'=>$booking_id,
          'user_id'=>$user_id,
          'cancel_date'=>date('Y-m-d h:i:sa'),
        );
  $cancel=$this->dashboard_model->add_cancelbooking('booking_cancel',$data);
  $cancelbk=$this->dashboard_model->update_cancelbook($payment_mode,$booking_id);

 

  if($cancelbk){
  $manager_role=$this->dashboard_model->get_managerrole($user_id);
  foreach($manager_role as $row) {
           $role_name = $row->name;
       }
   $manager_name=$this->dashboard_model->get_managername($user_id);
  foreach($manager_name as $row) {
           $mgr_name = $row->name;
           $mgr_phone = $row->phone;
       }
  $books=$this->dashboard_model->get_bookdata($booking_id);
  foreach($books as $row) {
           $user_name = $row->user_name;
           $user_phone = $row->phone_no;
           $user_email = $row->user_email;
           $venue_name = $row->venue;
           $venue_id = $row->venue_id;
           $area = $row->area;
           $booking_date = $row->date;
           $booking_time = $row->court_time;
           $sports = $row->sports;
           $court = $row->court;
       }
    
    $message=$venue_name." ".$area." has cancelled your booking of ".date('dS F  Y', strtotime($booking_date))." ,".date('l',strtotime($booking_date))." ,".date("g:i a", strtotime($booking_time)).". [Booking ID: ".$booking_id."] for ".$sports." in ".$court." ";
    $this->common->sms(str_replace(' ', '',$user_phone),urlencode($message));
/* email for upupup start */  
        $upupupemail=$this->dashboard_model->get_upupupemail();
             foreach($upupupemail as $row) {
        $upupup_email = $row->email;
        if($upupup_email!=''){
        $data['data']=array(
      'role'=>$role_name,
      'venue_name'=>$venue_name,
      'area'=>$area,
      'booking_date'=>date('dS F  Y', strtotime($booking_date)),
      'booking_time'=>date("g:i a", strtotime($booking_time)),
      'booking_day'=>date('l',strtotime($booking_date)),
      'booking_id'=>$booking_id,
      'court'=>$court,
      'sports'=>$sports,
      'user_name'=>$user_name,
      'user_phone'=>$user_phone,
      'ow_name'=>$ow_name,
      'ow_phone'=>$ow_phone,
      'mgr_phone'=>$mgr_phone,

      );
          $to_email = $upupup_email;
          $subject='Booking cancelled !!';
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
          $this->email->message($this->load->view('cancel_mailup',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
              
            }
       }
        /* email for upupup end */  
        /* email for venue owner start */  
        
       $owner_data=$this->dashboard_model->get_ownerdata($venue_id);
      foreach($owner_data as $row) {
             $ow_name = $row->name;
             $ow_email = $row->email;
             $ow_phone = $row->phone;
             
             $data['data']=array(
      'role'=>$role_name,
      'venue_name'=>$venue_name,
      'area'=>$area,
      'booking_date'=>date('dS F  Y', strtotime($booking_date)),
      'booking_time'=>date("g:i a", strtotime($booking_time)),
      'booking_day'=>date('l',strtotime($booking_date)),
      'booking_id'=>$booking_id,
      'court'=>$court,
      'sports'=>$sports,
      'user_name'=>$user_name,
      'user_phone'=>$user_phone,
      'ow_name'=>$ow_name,
      'ow_phone'=>$ow_phone,
      'mgr_name'=>$mgr_name,
      'mgr_phone'=>$mgr_phone,

      );
          $to_email = $ow_email;
          $subject='Booking cancelled !!';
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
          $this->email->message($this->load->view('cancel_mailow',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
      }
      
      /* email for venue owner end */  

    $result="success";
    return $this->response($result,200);
  }else{
    $result="error";
    return $this->response($result,200);
  }
}



}
