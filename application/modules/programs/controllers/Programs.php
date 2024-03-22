<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Programs extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('programs_model');
	
		
	}
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_program')) {
			redirect('acl');
		}else{
			$data['programs']=$this->programs_model->get_programs();
			//echo "<pre>";print_r($data);exit();
			$this->load->template('list',$data);
		}
	}
	/////////////////////////////////////// add progams start ////////////////////////////////////////////////////////
	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_program')) {
			redirect('acl');
		}else{
            $data['trainers']= $this->programs_model->get_trainers();
            $data['locations']= $this->programs_model->get_location();
			$this->load->template('add',$data);
		}
	}
	///////////////////////////////////////////// add progams end //////////////////////////////////////////
	///////////////////////////////////////// add programs start //////////////////////////////////////////////////////
	public function add_programs(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_program')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
        				'trainers_id'=>$this->input->post('trainer_id'),
						'name'=>$this->input->post('name'),
						'venue_name'=>$this->input->post('venue'),
						'location_id'=>$this->input->post('location'),
						'fees'=>$this->input->post('fees'),
						'lat'=>$this->input->post('lat'),
						'lon'=>$this->input->post('lon'),
						'start_date'=>$this->input->post('day'),
						'end_date'=>$this->input->post('days'),
						'start_time'=>$this->input->post('stime'),
						'end_time'=>$this->input->post('etime'),
						'description'=>$this->input->post('description'),
						'status'=>$this->input->post('status'),
						'added_date'=>date('Y-m-d H:i:s'),
				);
				$add=$this->programs_model->insert_programs($data);
		        if($add){
					$this->session->set_flashdata('success-msg',"New Program has been added!");
		    			redirect("programs");
				}else{
					$this->session->set_flashdata('error-msg','Program not added!');
					redirect("programs/add");		
				}

		}else{
		    
			$data['trainers']= $this->programs_model->get_trainers();
			$data['locations']= $this->programs_model->get_location();
			$this->load->template('add',$data);
		}
	}
	////////////////////////////////////////// add programs end/////////////////////////////////////////////
    ////////////////////// change  program status start ////////////////////////////////////////////
	public function program_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->programs_model->update_program_status($insert_data,'trainers_program',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->programs_model->update_program_status($insert_data,'trainers_program',$id);
		}
		
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("programs");
		}
		
	}
	////////////////////// change  program status end ////////////////////////////////////////////
	//////////////////////////////////  program edit start /////////////////////////////////////////////////////
	public function programs_edit($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_program')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
        				'trainers_id'=>$this->input->post('trainer_id'),
						'name'=>$this->input->post('name'),
						'venue_name'=>$this->input->post('venue'),
						'fees'=>$this->input->post('fees'),
						'lat'=>$this->input->post('lat'),
						'lon'=>$this->input->post('lon'),
						'start_date'=>$this->input->post('day'),
						'end_date'=>$this->input->post('days'),
						'start_time'=>$this->input->post('stime'),
						'end_time'=>$this->input->post('etime'),
						'description'=>$this->input->post('description'),
						'status'=>$this->input->post('status'),
				);
		        $this->programs_model->update_programs($id,$data);
				$this->session->set_flashdata('success-msg','Programs Edited!');
				redirect("programs");
		}else{
            $data['program']=$this->programs_model->get_program_details($id);
            $data['trainers']= $this->programs_model->get_trainers();
		    $this->load->template('edit',$data);
		}
	}
	//////////////////////////////////  program edit end /////////////////////////////////////////////////////
	//////////////////////////////////  program delete start /////////////////////////////////////////////////////
	public function program_delete($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_program')) {
			redirect('acl');
		}
		$data=$this->programs_model->delete_program($id);
 		if($data){
				$this->session->set_flashdata('success-msg','Program has been  Deleted!');
				redirect("programs");
		}else{
				$this->session->set_flashdata('error-msg','Program not deleted!');
				redirect("programs");
		}
	}
	//////////////////////////////////  program delete end /////////////////////////////////////////////////////
}


