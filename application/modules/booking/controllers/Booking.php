<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Booking extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('booking_model');
	        $this->load->model('reports/reports_model');
		
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_booking')) {
			redirect('acl');
			
		}else{	
                      /*booking from upupup app start */
                        $data['list'] 		= $this->reports_model->get_booking_list('','',$id);
		
						foreach ($data['list'] as $key => $value) {
							$data['list'][$key]['booked_slots']	= $this->reports_model->get_booked_slots($value['booking_id']);
							$data['list'][$key]['service_charge'] = $this->reports_model->get_booked_service_charge($value['booking_id']);
		
						}//echo "<pre>";print_r($data);exit();
						foreach ($data['list'] as $key3 => $value3) {
							$data['list'][$key3]['total_capacity']	= $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;
		
						}
						foreach ($data['list'] as $key2 => $value2) {
							$data['list'][$key2]['duration']	=count($value2['booked_slots'])*$value2['intervel'];
							$data['list'][$key2]['time_slots'] 	=implode("\n",$value2['booked_slots']);
							$data['list'][$key2]['charges']  =implode("\n",$value2['service_charge']);
						}
		/*booking from upupup app end */ 	
                /*booking from vendors app start */ 
                         $data['vendor']       = $this->reports_model->get_vendorbooking_list('','',$id);
                                               foreach ($data['vendor'] as $key => $value) {
                                               $data['vendor'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);

                                               }//echo "<pre>";print_r($data);exit();
                                               foreach ($data['vendor'] as $key3 => $value3) {
                                               $data['vendor'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                                               }
                                               foreach ($data['vendor'] as $key2 => $value2) {
                                               $data['vendor'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                                               $data['vendor'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                                               }
                                               //echo "<pre>";print_r($data['vendor']);exit();
                /*booking from vendors app end */
                /* Cancel booking from vendors app start */
                 $data['bookcan']       = $this->reports_model->get_cancelbooking_list('','',$id);
                                              foreach ($data['bookcan'] as $key => $value) {
                                              $data['bookcan'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);

                                              }//echo "<pre>";print_r($data['vendor']);exit();
                                              foreach ($data['bookcan'] as $key3 => $value3) {
                                              $data['bookcan'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                                              }
                                              foreach ($data['bookcan'] as $key2 => $value2) {
                                              $data['bookcan'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                                              $data['bookcan'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                                              }
              /* Cancel booking from vendors app end */
                //echo "<pre>";print_r($data['bookcan']);exit();
                        $this->load->template('list',$data);
		}
	}
		public function bookingTable($venue=''){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), '');
		if($venue!='')
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), '');
		else
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_booking');
		
	}
        
        	public function vendorbookingTable($venue=''){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), '');
		if($venue!='')
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), '');
		else
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_booking');
		$table=$this->booking_model->get_vendorbookingTable($edit,$delete,$venue);
		echo json_encode($table);
	}
        
        public function cancelbookingTable($venue=''){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), '');
		if($venue!='')
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), '');
		else
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_booking');
		$table=$this->booking_model->get_cancelbookingTable($edit,$delete,$venue);
		echo json_encode($table);
	}

	public function delete($id)
	{
			if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_booking')) {
			redirect('acl');
		}
		$this->booking_model->delete($id);
		$this->session->set_flashdata('success-msg',"Booking Deleted");
		redirect('booking');
	}


}