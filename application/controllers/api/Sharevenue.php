<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Sharevenue extends REST_Controller {

function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/sharevenue_model");

	}


	public function index_post(){
	        $user_id=$this->input->post('user_id');
	        $name=$this->input->post('name');
	        $phone=$this->input->post('phone');
	        $role=$this->input->post('role');
            $description=$this->input->post('description');
	        $imaget=$this->input->post('image');
	        $manager_id=$this->input->post('user_id');
               $vendor_phone=$this->input->post('phone');
               $vendor_mgr=$this->sharevenue_model->get_vendorusers($manager_id,$vendor_phone);
               foreach($vendor_mgr as $row) {
                         $vendor_name = $row->name;
                         }
                if($vendor_name==""){
                 $vendor_mgrs=$this->sharevenue_model->get_vendorcheck($manager_id,$vendor_phone); 
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
		    $image = imagecreatefromstring(base64_decode($imaget)); 
                    if($image != false) 
                       { 
                         imagejpeg($image, 'pics/sharevenue/'.$user_id.'.jpg'); 

                       }
            /* email for upupup */
            $data=$this->sharevenue_model->get_upupupemail();
            foreach($data as $row) {
            $user_email = $row->email;
          if($user_email!=''){
          $to_email = $user_email;
          $subject='Share My Venue';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '60';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message('Hi upUPUP,'."\n\n".'Description of Image : '.$description.''."\n\n".'From :'.$name.''."\n".'Role :'.$role.''."\n".'Mobile No :'.$phone.' ');
          $this->email->attach( 'http://upupup.in/partnerup/pics/sharevenue/'.$user_id.'.jpg');
          $this->email->send();
          $this->email->clear(TRUE);    
            }
        
             }/* email for upupup */
    /* email for venue owners */
    $venue=$this->sharevenue_model->get_venue($user_id);
    foreach($venue as $row) {
            $venue_id = $row->venue_id;
    }
    $owner=$this->sharevenue_model->get_owneremail($venue_id);
    foreach($owner as $row) {
            $user_email = $row->email;
            if($user_email!=''){
          $to_email = $user_email;
          $subject='Share My Venue';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message('Hi ,'."\n\n".' Description of Image :'.$description.''."\n\n".'From :'.$name.''."\n".'Role :'.$role.''."\n".'Mobile No :'.$phone.' ');
          $this->email->attach( 'http://upupup.in/partnerup/pics/sharevenue/'.$user_id.'.jpg');
          $this->email->send();
          $this->email->clear(TRUE);
              
            }
    }/* email for venue owners */

            $result="Image has been shared";
		    return $this->response($result,200);
	       
	}
	
	


}
