<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Profilev extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/vendor_model");

	}

	
	
        public function index_post(){
		    $user_id=$this->input->post('user_id');
                    $imaget=$this->input->post('image');
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
		    $image = imagecreatefromstring(base64_decode($imaget)); 
                    if($image != false) 
                       { 
                         imagejpeg($image, 'pics/users/'.$user_id.'.jpg'); 

                       } 
                      $imagename=$user_id.".jpg";
	            $data=$this->vendor_model->update_vendorimage($user_id,$imagename);
                     if($data){
			    $result="success";

		         }else{
			   $result="failed";
		}
		return $this->response($result,200);
	}
	


}
