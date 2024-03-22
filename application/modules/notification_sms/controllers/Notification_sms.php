<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Notification_sms extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('notification_model');
		$this->load->library('notification');
		$this->load->library('common');
		date_default_timezone_set("Asia/Kolkata");

	}
	public function index() {

		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {


		}else{
			$data['location']	= $this->notification_model->location_list();
			$data['sports']		= $this->notification_model->sports_list();
			//$data['venue']     	= $this->notification_model->get_venues();
			$data['offers']		= $this->notification_model->offer_list();
			//echo "<pre>";print_r($data);exit();
			$this->load->template('list',$data);
		}
	}
	/////////////////////////General Notification//////////////////////////////////
	public function general_send(){
		$type 		=$this->input->post('type');
		$city 		=$this->input->post('city');
		$area 		=$this->input->post('area');
		$sports 	=$this->input->post('sports');
		$message 	=$this->input->post('message');
		$title 		=$this->input->post('title');
		$users_list = $this->notification_model->users_list($city,$area,$sports);
		if($_FILES["file"]["tmp_name"]!=""){
			$imagedetails = getimagesize($_FILES['file']['tmp_name']);
			 $width = $imagedetails[0];
			$height = $imagedetails[1];
			 if($width==450 && $height==300){
          		 $path="assets/notification/";
				 $image=$this->common->file_upload_image($path);
      		}else{
      			$this->session->set_flashdata('error-msg','Sorry wrong resolution!');
				redirect("notification_sms");
      		}
		}
		$date 	 	= date("Y-m-d h:i:sa");
		$details 	= array('image' => $image,
							'date'  => $date);
		//echo "<pre>";print_r($details);exit();
		if ($type=="notification") {
			if ($users_list) {
				$title 	 	=$title;
				$message 	=$message;
				$data 		=array('result' => array('message'=> $message,
	                                                 'title'  => $title,
	                                                 'type'   => 5 ),
	                                'status'=> "true",
	                                'type'  => "GENERAL",
	                                'details'=>$details
	                                            );
				//echo "<pre>";print_r($data);exit();
				$notification= $this->notification->push_notification($users_list,$message,$title,$data);
			}
		}else if ($type=="sms") {
			if ($users_list) {//echo "<pre>";print_r($users_list);exit();
				foreach ($users_list as $key => $value) {
					$sms= $this->common->sms(trim($value['phone_no']),urlencode($message));
				}//echo "<pre>";print_r($sms);exit();
			}
		}else if ($type=="both") {
			if ($users_list) {//echo "<pre>";print_r($users_list);exit();
				$title 	 	=$title;
				$message 	=$message;
				$data 		=array('result' => array('message'=> $message,
	                                                 'title'  => $title,
	                                                 'type'   => 5 ),
	                                'status'=> "true",
	                                'type'  => "GENERAL",
	                                'details'=>$details
	                                            );
				//echo "<pre>";print_r($data);exit();
				$notification= $this->notification->push_notification($users_list,$message,$title,$data);

				foreach ($users_list as $key => $value) {
					$sms= $this->common->sms(trim($value['phone_no']),urlencode($message));
				}
				//print_r($sms);exit();
			}
		}
		$insert_data = array('title' 		=> $title,
							 'message' 		=> $message,
							 'city_id' 		=> $city,
							 'area_id' 		=> $area,
							 'sports_id' 	=> $sports,
							 'image' 		=> $image,
							 'type' 		=> $type,
							 'send_date' 	=> $date,
							 'send_date' 	=> date('Y-m-d H:i:s'),
							 'send_type' 	=> "General");

		$history_add= $this->notification_model->add_notification($insert_data);
		$upupup_phone=$this->notification_model->get_up_phone('notifications');
		foreach($upupup_phone as $val){
		   $this->common->sms(str_replace(' ', '', $val->phone),urlencode($message));
		   }
		   $upupup_mail=$this->notification_model->get_up_mail('notifications');
		   foreach ($upupup_mail as $key => $value) {
			
			
							$subject='Feedback';
					  //$subject="some text";
					
					  $to_email = $value->email	;
					  //Load email library
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
					  $this->email->from('feedback@upupup.in','upUPUP.');
					  $this->email->to($to_email);
					  $this->email->subject($subject);
					  //$message = $this->load->view('feedback_mail',$data,true);
					  $this->email->message($message);
					  $this->email->send();
					  }
		$this->session->set_flashdata('success-msg','Message Or/And Notification Send Successfully');
		redirect('notification_sms');
	}
	//////////////////////////////Offer Notification/////////////////////////////////////
	public function offer_send(){
		//echo "<pre>";print_r($this->input->post());exit();
		$type 		=$this->input->post('type');
		$offer 		=$this->input->post('offer');
		$message 	=$this->input->post('message');
		$title 		=$this->input->post('title');
		$venue_id 	=$this->input->post('venue');
		$area 		=$this->input->post('area');
		$city 		=$this->input->post('city');
		$court_id 	=$this->input->post('court');
		

		if($_FILES["file"]["tmp_name"]!=""){
			$imagedetails = getimagesize($_FILES['file']['tmp_name']);
			 $width = $imagedetails[0];
			$height = $imagedetails[1];
			 if($width==450 && $height==300){
          		 $path="assets/notification/";
				 $image=$this->common->file_upload_image($path);
      		}else{
      			$this->session->set_flashdata('error-msg','Sorry wrong resolution!');
				redirect("notification_sms");
      		}

		}
		$offer_data = $this->notification_model->offer_details($offer);
		//echo "<pre>";print_r($offer_data);exit();
		$venue_id 	= $offer_data->venue_id;
		$percentage =$offer_data->percentage;
		$venue_data =$this->notification_model->venue_details($venue_id);
		
		$gallery_image=$this->notification_model->venue_images($venue_id);
		
		//echo "<pre>";print_r($venue_data);exit();
		$area_id 	=$venue_data->area_id;
		$venue 		=$venue_data->venue;
		$varea 		=$venue_data->area;
		$users_list =$this->notification_model->users_list("",$area_id);
		//echo "<pre>";print_r($users_list);exit();
		$venue_data->facilities =$this->notification_model->venue_facilities($venue_id);
		$venue_data->court =$this->notification_model->venue_court($venue_id);

		foreach ($venue_data->court as $key => $value) {
			$venue_data->court[$key]['sports'] 	=$this->notification_model->court_sports($value['sports_id']);
			$venue_data->court[$key]['offer_price'] = ($percentage/100)*$venue_data->court[$key]['cost'];
			$venue_data->court[$key]['offer'] =	1;

		}
		$venue_data->rating =$this->notification_model->venue_rating($venue_id);
		$venue_data->sports=$this->notification_model->venue_sports($venue_id);
		$venue_details 	= array('venue_id' 		=> $venue_data->venue_id,
								'venue' 		=> $venue_data->venue,
								'venue_image' 	=> $venue_data->image,
								'description' 	=> $venue_data->description,
								'morning' 		=> $venue_data->morning,
								'evening' 		=> $venue_data->evening,
								'cost' 			=> $venue_data->cost,
								'phone' 		=> $venue_data->phone,
								'lat' 			=> $venue_data->lat,
								'lon' 			=> $venue_data->lon,
								'area' 			=> $venue_data->area,
								'facilities' 	=> $venue_data->facilities,
								'court' 		=> $venue_data->court,
								'percentage' 	=> $percentage,
								'rating' 		=> $venue_data->rating,
								'notification_image'	=> $image,
								'sports'=>			$venue_data->sports,
								'gallery_images'=> $gallery_image,);

		//echo "<pre>";print_r($venue_details);exit();
		if ($type=="notification") {
			if ($users_list) {
				/*$title 	 	="New Offer in ".$area;
				$message 	=$offer ." from ". $start ." to ". $end .". Get Upto ".$percentage." % Off.";*/
				$data 		=array('result' => array('message'=> $message,
	                                                 'title'  => $title,
	                                                 'type'   => 4 ),
	                                'status'=> "true",
	                                'type'  => "GENERAL",
	                                'venue_details' =>$venue_details,
	                                            );
				//echo "<pre>";print_r($data);exit();
				$notification= $this->notification->push_notification($users_list,$message,$title,$data);
			}
		}else if ($type=="sms") {
			if ($users_list) {
				foreach ($users_list as $key => $value) {
					$sms= $this->common->sms(trim($value['phone_no']),urlencode($message));
				}//echo "<pre>";print_r($sms);exit();
			}
		}
		else if ($type=="both") {
			if ($users_list) {
				$data 		=array('result' => array('message'=> $message,
	                                                 'title'  => $title,
	                                                 'type'   => 4 ),
	                                'status'=> "true",
	                                'type'  => "GENERAL",
	                                'venue_details' =>$venue_details,
	                                            );
				//echo "<pre>";print_r($data);exit();
				$notification= $this->notification->push_notification($users_list,$message,$title,$data);

				foreach ($users_list as $key => $value) {
					$sms= $this->common->sms(trim($value['phone_no']),urlencode($message));
				}//echo "<pre>";print_r($sms);exit();
			}
		}
		$insert_data = array('title' 		=> $title,
							 'message' 		=> $message,
							 'offer_id' 	=> $offer,
							 'image' 		=> $image,
							 'city_id' 		=> $city,
							 'area_id' 		=> $area,
							 'sports_id' 	=> $value['sports_id'],
							 'type' 		=> $type,
							 'send_type' 	=> "Offer",
							 'send_date' 	=> date('Y-m-d H:i:s'),
							 'venue_id' 	=> $venue_id);
							 
		$history_add= $this->notification_model->add_notification($insert_data);
		foreach ($users_list as $key => $value) {
				 $insert_data = array(
							 'offer_id' 	=> $offer,
							 'user_id ' 	=> $value['id']);
				 $this->notification_model->insert_notification($insert_data);
	
				}
		
		$upupup_phone=$this->notification_model->get_up_phone('offers');
		foreach($upupup_phone as $val){
		   $this->common->sms(str_replace(' ', '', $val->phone),urlencode($message));
		   }
		   /*
		   $upupup_mail=$this->notification_model->get_up_mail('offers');
		   foreach ($upupup_mail as $key => $value) {
			
			
							$subject='Feedback';
					  //$subject="some text";
					
					  $to_email = $value->email	;
					  //Load email library
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
					  $this->email->from('feedback@upupup.in','UPupup.');
					  $this->email->to($to_email);
					  $this->email->subject($subject);
					  //$message = $this->load->view('feedback_mail',$data,true);
					  $this->email->message($message);
					  $this->email->send();
					  }*/
		$this->session->set_flashdata('success-msg','Message Or/And Notification Send Successfully');
		redirect('notification_sms');
	}
	//////////////////////////////Area List//////////////////////////////////////////////////
	public function area_list(){
		$city 	= $this->input->post('city');
		$data['area']	= $this->notification_model->area_list($city);

		//echo "<pre>";print_r($data);exit();
		if ($data) {
			echo json_encode($data);
		}

	}
	//////////////////////////////////////////////////////////////////////////////////////
	public function history() {

		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {


		}else{
			$data['general']	= $this->notification_model->notification_history('General');
			$data['offer']		= $this->notification_model->notification_history('Offer');
			//echo "<pre>";print_r($data);exit();
			$this->load->template('history',$data);
		}
	}
	////////////////////////////Court List- AJAX/////////////////////////////////////////////
	public function court_list(){
		$venue 	= $this->input->post('venue');
		$data['court']		=$this->notification_model->get_court($venue);
		//echo "<pre>";print_r($data);exit();
		if ($data) {
			echo json_encode($data);
		}

	}
	/////////////////////////Offer List//////////////////////////////////////////////////
	public function offer_list(){
		$court 	= $this->input->post('court');
		$data['offer']		=$this->notification_model->get_offer($court);
		//echo "<pre>";print_r($data);exit();
		if ($data) {
			echo json_encode($data);
		}

	}
	/////////////////////////Users Count///////////////////////////////////////////
	public function users_count(){
		$offer 		= $this->input->post('offer_id');
		$offer_data = $this->notification_model->offer_details($offer);
		$venue_id 	= $offer_data->venue_id;
		$venue_data =$this->notification_model->venue_details($venue_id);
		$area_id 	=$venue_data->area_id;
		$users_list =$this->notification_model->users_list("",$area_id);
		$data 		= " Target Audience Count is ".count($users_list);
		//echo "<pre>";print_r($offer);exit();
		if ($data) {
			echo json_encode($data);
		}else{

		}

	}

	public function users_count_general(){
		$city 		=$this->input->post('city');
		$area 		=$this->input->post('area');
		$sports 	=$this->input->post('sports');
	
		$users_list = $this->notification_model->users_list($city,$area,$sports);
// 		echo json_encode($users_list);
		$data 		= " Target Audience Count is ".count($users_list);
	
		if ($data) {
			echo json_encode($data);
		}else{

		}

	}
	/////////////////////////Venue List//////////////////////////////////////////////////
	public function venue_list(){
		$area_o 	= $this->input->post('area_o');
		$data['venue']		=$this->notification_model->get_venues($area_o);
		//echo "<pre>";print_r($data);exit();
		if ($data) {
			echo json_encode($data);
		}

	}
	////////////////////////////////////////////////////////////////////////////////////////////


}
