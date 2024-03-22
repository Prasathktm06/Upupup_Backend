<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Messagev extends REST_Controller {

function __construct()
  {

    parent::__construct();

    // Configure limits on our controller methods
    // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
    $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
    $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    $this->load->model("api/login_model");

  }


  public function index_post(){
          $user_id=$this->input->post('user_id');
          $name=$this->input->post('name');
          $phone=$this->input->post('phone');
          $role=$this->input->post('role');
          $message=$this->input->post('message');
          $vendor_mgr=$this->login_model->get_vendorusers($user_id,$phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->login_model->get_vendorcheck($user_id,$phone); 
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
      $data=$this->login_model->get_vendormessage($this->input->post('user_id'));
           if($data){

                         /* email for upupup start*/
            $upupemail=$this->login_model->get_upupmsgemail();
              foreach($upupemail as $row) {
               $upup_mail = $row->email;
               //return $this->response($upup_mail,200);
               $data['data']=array(
             'message'=>$message,
             'name'=>$name,
             'role'=>$role,
             'phone'=>$phone,
             );
          //$message="";
          $to_email = $upup_mail;
          $subject='New message from upUPUP Vendors App ';
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
          //$message = ;
          $this->email->message($this->load->view('vendor_message',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
          //$this->email->clear(TRUE);    
            }

          /* email for upupup end */
          $result="Successfully send message";
        return $this->response($result,200);
          }else{
          $result="please try again";
         return $this->response($result,200);
          }

  }
  
  


}
