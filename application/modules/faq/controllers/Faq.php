<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Faq extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('faq_model');
			
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

			
		}else{
			$data['table'] 	= $this->faq_model->get_faq_list();
			//echo "<pre>";print_r($data);exit();		
			$this->load->template('list',$data);
		}
	}
	
		
	/*public function offerTable(){
		$edit 	= $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_offer');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_offer');
		$table 	= $this->faq_model->get_offerTable($edit,$delete);
		echo json_encode($table);
	}*/
	//////////////////////////////////////////Add Function//////////////////////////////////////////
	public function add(){
		if($this->input->post()){
			$data=array(
					'question'	=>$this->input->post('question'),
					'answer'	=>$this->input->post('answer'),
					'status'	=>$this->input->post('status'),
					);
			//echo "<pre>";print_r($data);exit();
			$result 	=$this->faq_model->add_data($data);
			if ($result) {
				$this->session->set_flashdata('success-msg','Data has been added!');
				redirect('faq');
			}	
		}else{
			$this->load->template('add');
		}
	}
	////////////////////////////////////////////Edit Function//////////////////////////////////
	public function edit($faq_id){
		if($this->input->post()){
			$data=array(
					'question'	=>$this->input->post('question'),
					'answer'	=>$this->input->post('answer'),
					'status'	=>$this->input->post('status'),
					);
			$result=$this->faq_model->update_data($data,$faq_id);
			if ($result) {
				$this->session->set_flashdata('success-msg',' Data has been edited!');
				redirect('faq');
			}
		}else{
			$data['details']=$this->faq_model->get_details($faq_id);
			//echo "<pre>";print_r($data);exit();
			$this->load->template('edit',$data);
		}
	}
	///////////////////////////////////////////Delete Function///////////////////////////////////////
	public function delete($faq_id){
		$result=$this->faq_model->delete_data($faq_id);
		if ($result) {
			$this->session->set_flashdata('success-msg',' Data has been deleted!');
			redirect('faq');
		}
	}
	//////////////////////////////////////Change Status///////////////////////////////////////////
	public function change_status($faq_id,$status=null)
	{ 
		if($faq_id){
			
			if($status){
				$insert_data=array('status'=>'0');
			}else{
				$insert_data=array('status'=>'1');
			}
			$result=$this->faq_model->update_data($insert_data,$faq_id);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('faq');
			}
		}
	}
	//////////////////////////////////////////////////////////////
	
}

