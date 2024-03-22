<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Version extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('version_model');
			
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_version')) {

			
		}else{
			$data['version'] 	= $this->version_model->get_coupan_list();
			//echo "<pre>";print_r($data);exit();		
			$this->load->template('list',$data);
		}
	}
	////////////////////////////////////////Add Function//////////////////////////////////////////
	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_version')) {

			
		}else{
			if($this->input->post()){
				//echo "<pre>";print_r($this->input->post());exit();
				$data=array(
						'platform'		=>$this->input->post('platform'),
						'identifier'	=>$this->input->post('identifier'),
						'version_name'	=>$this->input->post('version_name'),
						'version_code'	=>$this->input->post('version_code'),
						'optional'		=>$this->input->post('optional'),
						);
				//echo "<pre>";print_r($data);exit();
				$result 	=$this->version_model->add_version($data);
				if ($result) {
					$this->session->set_flashdata('success-msg','New Version has been added!');
					redirect('version');
				}	
			}else{
				$this->load->template('add');
			}
		}
	}
	////////////////////////////////////////////Edit Function//////////////////////////////////
	public function edit($version_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_version')) {

			
		}else{
			if($this->input->post()){
				$data=array(
						'platform'		=>$this->input->post('platform'),
						'identifier'	=>$this->input->post('identifier'),
						'version_name'	=>$this->input->post('version_name'),
						'version_code'	=>$this->input->post('version_code'),
						'optional'		=>$this->input->post('optional'),
						);
				$result=$this->version_model->update_data($data,$version_id);
				if ($result) {
					$this->session->set_flashdata('success-msg',' Version has been edited!');
					redirect('version');
				}
			}else{
				$data['details']=$this->version_model->get_details($version_id);
				//echo "<pre>";print_r($data);exit();
				$this->load->template('edit',$data);
			}
		}
	}
	////////////////////////////////////////Delete Function///////////////////////////////////////
	public function delete($version_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_version')) {

			
		}else{
			$result=$this->version_model->delete_data($version_id);
			if ($result) {
				$this->session->set_flashdata('success-msg',' Version has been deleted!');
				redirect('version');
			}
		}
	}
	//////////////////////////////////////Change Status//////////////////////////////////////////
	public function change_status($coupon_id,$status=null)
	{ 	
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_version')) {

			
		}else{
			if($coupon_id){
				
				if($status){
					$insert_data=array('status'=>'0');
				}else{
					$insert_data=array('status'=>'1');
				}
				$result=$this->version_model->update_coupon($insert_data,$coupon_id);
				if($result){
					$this->session->set_flashdata('success-msg',' Status has been changed!');
					redirect('coupons');
				}
			}
		}
	}
	//////////////////////////////////////////////////////////////
	
}

