<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Refer_friend extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('Referfriend_model');
		date_default_timezone_set("Asia/Kolkata");
		
	}
	public function index() {
	    if ($this->input->post('submit')) {
			$city   = $this->input->post('city');
			$data['list']=$this->Referfriend_model->get_refer_friend_listsort($city);
	    	$data['city']=$this->Referfriend_model->get_locations();
		}else{
			$data['list']=$this->Referfriend_model->get_refer_friend_list();
	    	$data['city']=$this->Referfriend_model->get_locations();
		}
		$this->load->template('refer_friend_list',$data);
	}
	
	//////////////// hot offer setting add button  /////////////////////
	public function add_refer_friend(){
		
	        $data['city']=$this->Referfriend_model->get_locations();
			$this->load->template('add_refer_friend',$data);
	}
	////////// add Buy Coin setting start ////////////////////
	/////////////// Check any active booking setting on selected city //////////////////////
	public function check_booking_setting(){
	    $city =$this->input->post('city');
	    $start_date =date("Y-m-d", strtotime($this->input->post('start_date')));
	    $end_date =date("Y-m-d", strtotime($this->input->post('end_date')));
	
	    $venue_list = $this->Referfriend_model->check_booking_setting($city,$start_date,$end_date);
	    $data = count($venue_list);
	
		if ($data) {
			echo json_encode($data);
		}else{
            $datas=0;
            echo json_encode($datas);
		}

	}
	
    ////////// add refer bonus setting start ////////////////////
	
	public function add_refer_friend_setting(){
	   if($this->input->post()){
            $city=$this->input->post('city');
        	$start_date=date("Y-m-d", strtotime($this->input->post('sdate')));
        	$end_date=date("Y-m-d", strtotime($this->input->post('edate')));
	    	$install_status=$this->input->post('install_status');
	    	$install_no=$this->input->post('install_no');
        	$install_bonus=$this->input->post('install_bonus');
        	$booking_status=$this->input->post('booking_status');
	    	$booking_bonus=$this->input->post('booking_bonus');
        	$status=$this->input->post('status');
        	$status_change=$this->input->post('status_change');
        	//echo "<pre>";print_r($status_change);
        	//echo "<pre>";print_r($status);exit();
        	if($status_change!=0 && $status_change!=99999){

        			$insert_data=array('status'=>0);
        			$this->Referfriend_model->update($insert_data,'refer_bonus_setting',$status_change);
			        	if($install_status!=0 || $booking_status!=0){
			        	$data=array(
					        'location_id'=>$city,
							'start_date'=>$start_date,
							'end_date'=>$end_date,
							'install_count'=>$install_no,
							'install_bonus_coin'=>$install_bonus,
							'install_status'=>$install_status,
							'booking_bonus_coin'=>$booking_bonus,
							'booking_bonus_status'=>$booking_status,
							'status'=>1,
							'added_date'=>date('Y-m-d h:i:sa'),
							);

			        	//echo "<pre>";print_r($data);exit();
			        	$add=$this->Referfriend_model->insert_referfriend_setting($data);

			               	if($add){
						        $this->session->set_flashdata('success-msg',"Refer a Friend Setting has been added!");
			    			    redirect("refer_friend/");
					        }else{
				
						        $this->session->set_flashdata('error-msg','Refer a Friend Setting not added!');
						        redirect("refer_friend");		
					    }
			            }else{
			            	$this->session->set_flashdata('error-msg','Missing Install status & Booking status');
						        redirect("refer_friend/add_refer_friend");
			            }
 
        	}else{
        		if($status_change==0 && $status==0){
	        			if($install_status!=0 || $booking_status!=0){
				        	$data=array(
						        'location_id'=>$city,
								'start_date'=>$start_date,
								'end_date'=>$end_date,
								'install_count'=>$install_no,
								'install_bonus_coin'=>$install_bonus,
								'install_status'=>$install_status,
								'booking_bonus_coin'=>$booking_bonus,
								'booking_bonus_status'=>$booking_status,
								'status'=>1,
								'added_date'=>date('Y-m-d h:i:sa'),
								);

				        	//echo "<pre>";print_r($data);exit();
				        	$add=$this->Referfriend_model->insert_referfriend_setting($data);

				               	if($add){
							        $this->session->set_flashdata('success-msg',"Refer a Friend Setting has been added!");
				    			    redirect("refer_friend/");
						        }else{
					
							        $this->session->set_flashdata('error-msg','Refer a Friend Setting not added!');
							        redirect("refer_friend");		
						    }
				            }else{
				            	$this->session->set_flashdata('error-msg','Missing Install status & Booking status');
							        redirect("refer_friend/add_refer_friend");
				            }	
        		}else{

        			$this->session->set_flashdata('error-msg','Already one active setting');
							        redirect("refer_friend/add_refer_friend");
        		}

        	}
            
	
	   }
	
	}
    
     //////////// change  status start /////////////////////////
	public function change_refer_friend_status($id,$status)
	{
		if($status==1){
		$insert_data=array('status'=>0);
		}else{
		$insert_data=array('status'=>1);
		}
		$result=$this->Referfriend_model->update($insert_data,'refer_bonus_setting',$id);
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("refer_friend");
		}
		
	}
	//////////// change  status end /////////////////////////
	
	/////////////// Check any active bokking bonus on selected start_date and end_date start//////////////////////
	public function check_refer_exist(){
	    $city =$this->input->post('city');
	    $start_date =date("Y-m-d", strtotime($this->input->post('start_date')));
	
	    $venue_list = $this->Referfriend_model->check_refer_setting($city,$start_date);
	    foreach($venue_list as $row) {
             $id = $row->id;
             }
	    $data = $id;
	
		if ($data) {
			echo json_encode($data);
		}else{
            $datas=0;
            echo json_encode($datas);
		}

	}
	
    /////////////// Check any active bokking bonus on selected start_date and end_date end //////////////////////

	
}

