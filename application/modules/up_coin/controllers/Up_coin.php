<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Up_coin extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('Upcoin_model');
		date_default_timezone_set("Asia/Kolkata");
		
	}
	public function index() {
	    
		$data['list']=$this->Upcoin_model->get_upcoin_set();
		$this->load->template('up_coin_setting',$data);
	}
	
	//////////////// hot offer setting add button  /////////////////////
	public function add_upcoin(){
		
	        $data="";
			$this->load->template('add_upcoin',$data);
	}
	
	////////// add UP Coin setting start ////////////////////
	
	public function add_upcoin_setting(){
	   if($this->input->post()){

	    	$rupee=$this->input->post('rupee');
	    	$coin=$this->input->post('coin');
        	//$status=$this->input->post('status');
        	/*
            $active=$this->Upcoin_model->get_active_upcoin_setting();
            foreach($active as $row) {
             $id = $row->id;
             $insert_data=array('status'=>0);
             $result=$this->Upcoin_model->update($insert_data,'upcoin_setting',$id);
             }
             */
        	$data=array(
		        'coin'=>$coin,
				'rupee'=>$rupee,
				'status'=>0,
				'added_date'=>date('Y-m-d h:i:sa'),
				);
        	$add=$this->Upcoin_model->insert_upcoin_set($data);
        	
               	if($add){
			        $this->session->set_flashdata('success-msg',"UP Coin Setting has been added!");
    			    redirect("up_coin");
		        }else{
	
			        $this->session->set_flashdata('error-msg','UP Coin Setting not added!');
			        redirect("up_coin");		
		    } 
	
	   }
	
	}
    ////////// add UP Coin setting end ////////////////////
     //////////// change  status start /////////////////////////
	public function change_status($id,$status)
	{
		if($status==1){
		$insert_data=array('status'=>0);
		$result=$this->Upcoin_model->update($insert_data,'upcoin_setting',$id);
		}else{
		$active=$this->Upcoin_model->get_active_upcoin_setting();
            foreach($active as $row) {
             $ids = $row->id;
             $insert_data=array('status'=>0);
             $result=$this->Upcoin_model->update($insert_data,'upcoin_setting',$ids);
             }
        $insert_data=array('status'=>1);
        $result=$this->Upcoin_model->update($insert_data,'upcoin_setting',$id);
		}
		
		
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("up_coin");
		}
		
	}
	//////////// change  status end /////////////////////////	
   ///////////// buy coin setting list page /////////////////
   public function buycoin() {
	    
		if ($this->input->post('submit')) {
	    	$city   = $this->input->post('city');
	    	$data['list']=$this->Upcoin_model->get_buycoin_filter($city);
			$data['city']=$this->Upcoin_model->get_locations();
	    }else{
		    $data['list']=$this->Upcoin_model->get_buycoin_set();
			$data['city']=$this->Upcoin_model->get_locations();
	    }
		
		$this->load->template('buy_coin_setting',$data);
	}

    	//////////////// hot offer setting add button  /////////////////////
	public function add_buycoin(){
		
	        $data['city']=$this->Upcoin_model->get_locations();
			$this->load->template('add_buycoin',$data);
	}
	////////// add Buy Coin setting start ////////////////////
	
	public function add_buycoin_setting(){
	   if($this->input->post()){
            $city=$this->input->post('city');
        	$start_date=date("Y-m-d", strtotime($this->input->post('sdate')));
        	$end_date=date("Y-m-d", strtotime($this->input->post('edate')));
	    	$rupee=$this->input->post('rupee');
	    	$coin=$this->input->post('coin');
        	$status=$this->input->post('status');
            $check=$this->Upcoin_model->get_buycoin_exist($city,$start_date,$end_date,$rupee);
                foreach($check as $row) {
                    $check_rupee = $row->rupee;
           
           			if($check_rupee == $rupee){
           				$this->session->set_flashdata('error-msg','Buy Coin Setting already active!');
			        	redirect("up_coin/buycoin");
           			}
                }
        	$data=array(
		        'location_id'=>$city,
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'rupee'=>$rupee,
				'coin'=>$coin,
				'status'=>$status,
				'added_date'=>date('Y-m-d h:i:sa'),
				);
        	$add=$this->Upcoin_model->insert_buycoin_set($data);

               	if($add){
			        $this->session->set_flashdata('success-msg',"Buy Coin Setting has been added!");
    			    redirect("up_coin/buycoin");
		        }else{
	
			        $this->session->set_flashdata('error-msg','Buy Coin Setting not added!');
			        redirect("up_coin/buycoin");		
		    } 
	
	   }
	
	}
    ////////// add Buy Coin setting end ////////////////////
    
    //////////// change  status start /////////////////////////
	public function change_buycoin_status($id,$status)
	{
		if($status==1){
		$insert_data=array('status'=>0);
		}else{
		$insert_data=array('status'=>1);
		}
		$result=$this->Upcoin_model->update($insert_data,'buycoin_setting',$id);
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("up_coin/buycoin");
		}
		
	}
	//////////// change  status end /////////////////////////
	//////////// change  status start /////////////////////////
	public function change_buycoin_block($id,$status)
	{
		if($status==1){
		$insert_data=array('block_status'=>0);
		}else{
		$insert_data=array('block_status'=>1);
		}
		$result1=$this->Upcoin_model->update($insert_data,'buycoin_setting',$id);
		if($status==1){
		$insert_data=array('status'=>0);
		}else{
		$insert_data=array('status'=>1);
		}
		$result2=$this->Upcoin_model->update($insert_data,'buycoin_setting',$id);
		if($result1 && $result2){
			$this->session->set_flashdata('success-msg',' Buycoin Blocked!');
			redirect("up_coin/buycoin");
		}
		
	}
	//////////// change  status end /////////////////////////
    ///////////// booking bonus setting list page /////////////////
   public function booking_bonus() {
	    if ($this->input->post('submit')) {
	    	    $city   = $this->input->post('city');
	    	    $data['city']=$this->Upcoin_model->get_locations();
	            $data['list']=$this->Upcoin_model->get_booking_bonus_filter($city);
	    }else{
	            $data['city']=$this->Upcoin_model->get_locations();
	            $data['list']=$this->Upcoin_model->get_booking_bonus_list();   
	    }
		$this->load->template('booking_bonus_setting',$data);
	}
	
	//////////////// booking bonus add page  /////////////////////
	public function add_booking_bonus(){
		
	        $data['city']=$this->Upcoin_model->get_locations();
			$this->load->template('add_booking_bonus',$data);
	}
    
   ////////// add Booking Bonus setting start ////////////////////
	
	public function add_booking_bonus_setting(){
	   if($this->input->post()){
            $city=$this->input->post('city');
        	$start_date=date("Y-m-d", strtotime($this->input->post('sdate')));
        	$end_date=date("Y-m-d", strtotime($this->input->post('edate')));
	    	$coin=$this->input->post('coin');
        	$status=$this->input->post('status');
            
        	$data=array(
		        'location_id'=>$city,
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'coin'=>$coin,
				'status'=>$status,
				'added_date'=>date('Y-m-d h:i:sa'),
				);
        	$add=$this->Upcoin_model->insert_booking_bonus($data);

               	if($add){
			        $this->session->set_flashdata('success-msg',"Booking Bonus Setting has been added!");
    			    redirect("up_coin/booking_bonus");
		        }else{
	
			        $this->session->set_flashdata('error-msg','Booking Bonus Setting not added!');
			        redirect("up_coin/booking_bonus");		
		    } 
	
	   }
	
	}
    ////////// add Booking Bonus setting end ////////////////////	
    //////////// change  booking bonus setting status start /////////////////////////
	public function change_booking_bonus_status($id,$status)
	{
		if($status==1){
		$insert_data=array('status'=>0);
		}else{
		$insert_data=array('status'=>1);
		}
		$result=$this->Upcoin_model->update($insert_data,'booking_bonus_setting',$id);
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("up_coin/booking_bonus");
		}
		
	}
	//////////// change Booking Bonus Setting status end /////////////////////////	

	/////////////// Check any active bokking bonus on selected start_date and end_date start//////////////////////
	public function booking_bonus_exists(){
	    $city =$this->input->post('city');
	    $start_date =date("Y-m-d", strtotime($this->input->post('start_date')));
	
	    $venue_list = $this->Upcoin_model->check_booking_setting($city,$start_date);
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

