<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Hot_offer_slider extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('Hotoffer_model');
		
	}
	public function index() {
	    
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_hot_offer')) {
			redirect('acl');
		}else{
		   
		//	$data['list']=$this->Hotoffer_model->get_setting();
			$data['city']=$this->Hotoffer_model->get_locations();
		//echo "<pre>";print_r($data);exit();
	$data="";
			$this->load->template('image_slider_list',$data);
		}
	}
	
	//////////////////////////////////////////////
	
		public function add_slider() {
	    
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_hot_offer')) {
			redirect('acl');
		}else{
		   
		//	$data['list']=$this->Hotoffer_model->get_setting();

		$this->load->template('add');
		}
	}
	
	
	
	/////////////////////////////////////////////
	
	//////////////// hot offer setting add button  /////////////////////

	
		public function slider(){
		    
		  
	
			$this->form_validation->set_rules('slider',	'slider',	'required|is_unique[slider.slider]');
			
	        
			    
			   
				$path="pics/slider/";
        		$image=$this->common->file_upload_image($path);
        //	print_r( slider [image]);
        //echo sliderone[image] ;exit();
				$data=array(
						'slider'=>$this->input->post('slider'),
						'image'=>$image
				);
				print_r($data);exit();
			//	$this->sports_model->add_sports($data);
				$this->session->set_flashdata('success-msg','New Hot slider has been added!');
				redirect('hot_offer_slider');
		
	}
	
	
    	/////////////// count venues of normal offers grater than the hot offer setting in //////////////////////
	public function venue_list_count(){
	    $city =$this->input->post('city');
	    $dates =date("Y-m-d", strtotime($this->input->post('dates')));
	    $percentage =$this->input->post('percentage');
	
	    $venue_list = $this->Hotoffer_model->venue_list($city,$dates,$percentage);
	    $data = " Target Venue Count is ".count($venue_list);
	
		if ($data) {
			echo json_encode($data);
		}else{

		}

	}
	////////// add hot offer setting  ////////////////////
	
	public function add_settings(){
	   if($this->input->post()){

        	$city=$this->input->post('city');
        	$day=date("Y-m-d", strtotime($this->input->post('day')));
		$name=$this->input->post('name');
	    	$life=$this->input->post('life');
	    	$percentage=$this->input->post('percentage');
        	$status=$this->input->post('status');
        	$city_count=sizeof($city);
            
	        for($i=0;$i<$city_count;$i++){
	            $location_id=$city[$i];
	
	            }
		        $data=array(
		        	'location_id'=>$location_id,
				'dates'=>$day,
				'name'=>$name,
				'life'=>$life,
				'percentage'=>$percentage,
				'status'=>$status
				);
		$add=$this->Hotoffer_model->insert_setting($data);
               	if($add){
			$this->session->set_flashdata('success-msg',"New Hot Offer Settings has been added!");
    			redirect("hot_offer");
		}else{
	
			$this->session->set_flashdata('error-msg','Hot Offer Settings not added!');
			redirect("hot_offer");		
		} 
	
	   }
	
	}
	/////////// load hot offer notification setting page ///////////
	public function notification() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_offer_notification')) {
			redirect('acl');
		}else{
			$data['list']=$this->Hotoffer_model->get_notification_list();
			//echo "<pre>";print_r($data);exit();
			//$this->load->template('notification');
			$this->load->template('notification',$data);
		}
	}
	
	////// add buuton to load datas on notification setting  //////////////
	public function add_notification(){
            $data['city']=$this->Hotoffer_model->get_locations();
            $data['not_setting']=$this->Hotoffer_model->get_not_settings();
	    $this->load->template('add_notification',$data);
	}
	
	///////// add notification setting   /////////////////////
	public function add_notification_settings(){
	   if($this->input->post()){

        	$city=$this->input->post('city');
		$not_setting=$this->input->post('not_setting');
		$status=$this->input->post('status');
		$time1=$this->input->post('time1');
		$time2=$this->input->post('time2');
	    	//echo "<pre>";print_r($hot_setting);
	    	//echo "<pre>";print_r($not_setting);exit();
	    	$data=array(
		        	'location_id'=>$city,
				'hot_not_setting_id'=>$not_setting,
				'time1'=>date('H:i:s', strtotime($time1)),
				'time2'=>date('H:i:s', strtotime($time2)),
				'status'=>$status
				);
		$add=$this->Hotoffer_model->insert_not_setting($data);
                if($add){
			$this->session->set_flashdata('success-msg',"New Notification Settings has been added!");
    			redirect("hot_offer/notification");
		}else{
	
			$this->session->set_flashdata('error-msg','Notification Settings not added!');
			redirect("hot_offer/notification");		
		} 
	
	   }
	
	}
	//////////// change notification status  /////////////////////////
	public function change_not_status($id,$status)
	{
		if($status==1){
		$insert_data=array('status'=>0);
		}else{
		$insert_data=array('status'=>1);
		}
		$result=$this->Hotoffer_model->update($insert_data,'hot_offer_notification',$id);
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("hot_offer/notification");
		}
		
	}
	//////////// edit hot notification settings  /////////////////////////
	public function edit_not_setting($id)
	{

		$data['notify']=$this->Hotoffer_model->get_notification_set($id);
		$data['not_setting']=$this->Hotoffer_model->get_not_settings();
		
		
		//echo "<pre>";print_r($data);exit();
		$this->load->template('edit_notification',$data);
		
	}
	
	//////////// edit hot notification settings  /////////////////////////
	public function edit_notify_settings($id)
	{
		$city=$this->input->post('city');
		$not_setting=$this->input->post('not_setting');
		$status=$this->input->post('status');
		$time1=$this->input->post('time1');
		$time2=$this->input->post('time2');
		$data=array(
			'hot_not_setting_id'=>$not_setting,
			'time1'=>date('H:i:s', strtotime($time1)),
			'time2'=>date('H:i:s', strtotime($time2)),
			'status'=>$status
			);
		$adds=$this->Hotoffer_model->update_notifys($data,$id);
		if($adds){
			$this->session->set_flashdata('success-msg','Updated Successfully');
			redirect("hot_offer/notification");
		}
	}

	//////////// delete hot notification settings  /////////////////////////
	public function delete_not_setting($id)
	{

		$data=$this->Hotoffer_model->delete_notification($id);
		if($data){
			$this->session->set_flashdata('success-msg','Notification Settings has been  Deleted!');
			redirect("hot_offer/notification");
		}
		
	}
	
	/////////////////////////Change hot offer Status/////////////////////////////////////
	public function offer_status($hot_id,$status,$venue_id)
	{ 
		
        if($status ==1){
        	$data=array('status'=>'0');
        }else{
        	$data=array('status'=>'1');
        }
            $this->Hotoffer_model->update_offerdata($hot_id,$data);
			$this->session->set_flashdata('success-msg','Status has been changed!');
			redirect("venue/venue_edit/$venue_id?hot_offer=1");	
	
	}
	
	//////////// change  status  /////////////////////////
	public function change_status($id,$status)
	{
		if($status==1){
		$insert_data=array('status'=>0);
		}else{
		$insert_data=array('status'=>1);
		}
		$result=$this->Hotoffer_model->update($insert_data,'hot_offer_setting',$id);
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("hot_offer");
		}
		
	}
	
	//////////// Delete hot offer settings /////////////////////////
	public function delete_hotset($id)
	{
		date_default_timezone_set('Asia/Kolkata');
		$date=date('Y-m-d');
		$data=$this->Hotoffer_model->get_hot_details($id,$date);
		
		if(!empty($data)){
			$result=$this->Hotoffer_model->delete_hotset($id);
			$this->session->set_flashdata('success-msg','Offer has been  Deleted!');
			redirect("hot_offer");
		}else{
			$this->session->set_flashdata('error-msg','You have no permission for delete this offer');
			redirect("hot_offer");
		}
		
		
	}
////////// hot offer filter  ////////////////////
	
	public function add_filter(){
	   if($this->input->post()){

        	$city=$this->input->post('city');
        	$day=date("Y-m-d", strtotime($this->input->post('day')));
	    	$percentage=$this->input->post('percentage');

	    	if(empty($city)){
	    		$city=0;
	    	}

	    	if($day==date("Y-m-d", strtotime('1970-01-01'))){
	    		$day=0;
	    	}

	    	if(empty($percentage)){
	    		$percentage=0;
	    	}
	    	
        	
        	$data['list']=$this->Hotoffer_model->get_filterset($city,$day,$percentage);
			$data['city']=$this->Hotoffer_model->get_locations();
			//echo "<pre>";print_r($data);exit();
			$this->load->template('list',$data);

	
	   }
	
	}


	
	
		

	
}

