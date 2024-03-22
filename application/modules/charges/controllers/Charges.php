<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Charges extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('charges_model');
	
		
	}
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_service_charge')) {
			redirect('acl');
		}else{
			$data['list']=$this->charges_model->get_service_charges();
			$this->load->template('list',$data);
		}
	}
	///////////////////////////////////////// add service charge start //////////////////////////////////////////////////////
	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_service_charge')) {
			redirect('acl');
		}
		if($this->input->post()){
		    $status=$this->input->post('status');
		    if($status==1){
		      $location_id=$this->input->post('city');
		      $insert_data=array('status'=>0);
			$this->charges_model->update_status($insert_data,'service_charge',$location_id);
            	$data=array(
    						'location_id'=>$this->input->post('city'),
    						'amount'=>$this->input->post('amount'),
    						'status'=>$this->input->post('status'),
    						'added_date'=>date('Y-m-d H:i:s'),
    				);
    				$add=$this->charges_model->insert_data($data,'service_charge');
    		        if($add){
    					$this->session->set_flashdata('success-msg',"New service charge has been added!");
    		    			redirect("charges");
    				}else{
    					$this->session->set_flashdata('error-msg','service charge not added!');
    					redirect("charges/add");		
    				} 
			
		    }else{
            	$data=array(
    						'location_id'=>$this->input->post('city'),
    						'amount'=>$this->input->post('amount'),
    						'status'=>$this->input->post('status'),
    						'added_date'=>date('Y-m-d H:i:s'),
    				);
    				$add=$this->charges_model->insert_data($data,'service_charge');
    		        if($add){
    					$this->session->set_flashdata('success-msg',"New service charge has been added!");
    		    			redirect("charges");
    				}else{
    					$this->session->set_flashdata('error-msg','service charge not added!');
    					redirect("charges/add");		
    				}  
		    }

		}else{
			$data['locations']= $this->charges_model->get_location();
			$this->load->template('add',$data);
		}
	}
	///////////////////////////////////////// add service charge end//////////////////////////////////////////////
	//////////////////////////////////  trainers edit start /////////////////////////////////////////////////////
	public function edit($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_service_charge')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
						'amount'=>$this->input->post('amount'),
				);
		        $this->charges_model->update_charges($id,$data);
				$this->session->set_flashdata('success-msg','Service Charge Edited!');
				redirect("charges");
		}else{
		    $data['charge']= $this->charges_model->get_charge_details($id);
		    $this->load->template('edit',$data);
		}
	}
	//////////////////////////////////  trainers edit end /////////////////////////////////////////////////////
	//////////////////////////////////  service charge delete start /////////////////////////////////////////////////////
	public function delete($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_service_charge')) {
			redirect('acl');
		}
		$data=$this->charges_model->delete_charge($id);
 		if($data){
				$this->session->set_flashdata('success-msg','Service Charge has been  Deleted!');
				redirect("charges");
		}else{
				$this->session->set_flashdata('error-msg','Service Charge not deleted!');
				redirect("charges");
		}
	}
	//////////////////////////////////  service charge delete end /////////////////////////////////////////////////////
    ////////////////////// change  service charge status start ////////////////////////////////////////////
	public function status_change($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->charges_model->update_charge_status($insert_data,'service_charge',$id);
		}else{
			$location_data=$this->charges_model->get_location_data($id);
				foreach($location_data as $row) {
	                    $location_id = $row->location_id;
	                }
		    $insert_data=array('status'=>0);
			$this->charges_model->update_status($insert_data,'service_charge',$location_id);
        	$insert_data=array('status'=>1);
        	$result=$this->charges_model->update_charge_status($insert_data,'service_charge',$id);
		}
		
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("charges");
		}
		
	}
	////////////////////// change  service charge status end ////////////////////////////////////////////
}


