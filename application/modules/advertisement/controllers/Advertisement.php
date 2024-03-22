<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Advertisement extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('advertisement_model');
	
		
	}
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) {
			redirect('acl');
		}else{
			$data['list']=$this->advertisement_model->get_shop_adv();
			$this->load->template('list_shop_adv',$data);
		}
	}
	///////////////////////////////////////// add shop advertisement start //////////////////////////////////////////////////////
	public function add_shop_adv(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
			$path="pics/advertisement/shop_advertisement/";
        	$image=$this->common->file_upload_image($path);
	        if(!empty($image)){
	        	$data=array(
	        	            'location_id'=>$this->input->post('location'),
	        				'image'=>$image,
							'status'=>$this->input->post('status'),
							'added_date'=>date('Y-m-d H:i:s'),
					);
					$add=$this->advertisement_model->insert_advertisement($data,'advertisement_shop');
			        if($add){
						$this->session->set_flashdata('success-msg',"New Shop Advertisement has been added!");
			    			redirect("advertisement");
					}else{
						$this->session->set_flashdata('error-msg','Shop Advertisement not added!');
						redirect("advertisement");		
					}
			}else{
						$this->session->set_flashdata('error-msg','Shop Advertisement not added!');
						redirect("advertisement/add_shop_adv");
			}

		}else{
		    $data['locations']= $this->advertisement_model->get_location();
			$this->load->template('add_shop_adv',$data);
		}
	}
	//////////////////////////////////////// add shop advertisement end///////////////////////////////////////////////
    ////////////////////// change  shop advertisement status start ////////////////////////////////////////////
	public function shop_adv_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0); 
			$result=$this->advertisement_model->update_adv($insert_data,'advertisement_shop',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->advertisement_model->update_adv($insert_data,'advertisement_shop',$id);
		}	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("advertisement");	
		}else{
			$this->session->set_flashdata('error-msg','Status not Updated!');
			redirect("advertisement");	
		}
		
	}
	////////////////////// change  shop advertisement status end ////////////////////////////////////////////
	//////////////////////////////////  shop advertisement edit start /////////////////////////////////////////////////////
	public function edit_shop_adv($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
		    
			$path="pics/advertisement/shop_advertisement/";
        	$image=$this->common->file_upload_image($path);
        	if(!empty($image)){
                	$data=array(
                				'image'=>$image,
        						'status'=>$this->input->post('status'),
        				);    
        	}else{
                	$data=array(
        						'status'=>$this->input->post('status'),
        				);
        	}

		        $this->advertisement_model->update_adv($data,'advertisement_shop',$id);
				$this->session->set_flashdata('success-msg','Shop Advertisement Edited!');
				redirect("advertisement");
		}else{
		    $data['advertisement']=$this->advertisement_model->get_shop_details($id);
		    $this->load->template('edit_shop_adv',$data);
		}
	}
	//////////////////////////////////  shop advertisement edit end /////////////////////////////////////////////////////
//////////////////////////////////  Shop Advertisement delete start /////////////////////////////////////////////////////
	public function delete_shop_adv($id)
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_advertisement')) 
		{
			redirect('acl');
		}
		$data=$this->advertisement_model->delete_adv($id,'advertisement_shop');
 		if($data)
 		{
				$this->session->set_flashdata('success-msg','Shop Advertisement has been  Deleted!');
				redirect("advertisement");
		}else{
				$this->session->set_flashdata('error-msg','Shop Advertisement not deleted!');
				redirect("advertisement");
		}
	}
//////////////////////////////////  Shop Advertisement delete end /////////////////////////////////////////////////////
//////////////////////////////////  Trainer Advertisement start /////////////////////////////////////////////////////
	public function trainer() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) {
			redirect('acl');
		}else{
			$data['list']=$this->advertisement_model->get_trainer_adv();
			$this->load->template('list_trainer_adv',$data);
		}
	}
//////////////////////////////////  Trainer Advertisement end /////////////////////////////////////////////////////
	///////////////////////////////////////// add Trainer advertisement start //////////////////////////////////////////////////////
	public function add_trainer_adv(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
			$path="pics/advertisement/trainer_advertisement/";
        	$image=$this->common->file_upload_image($path);
		       	if(!empty($image)){
		        	$data=array(
		        	            'location_id'=>$this->input->post('location'),
		        				'image'=>$image,
								'status'=>$this->input->post('status'),
								'added_date'=>date('Y-m-d H:i:s'),
						);
						$add=$this->advertisement_model->insert_advertisement($data,'advertisement_trainers');
				        if($add){
							$this->session->set_flashdata('success-msg',"New Trainer Advertisement has been added!");
				    			redirect("advertisement/trainer");
						}else{
							$this->session->set_flashdata('error-msg','Trainer Advertisement not added!');
							redirect("advertisement/trainer");		
						}
				}else{
							$this->session->set_flashdata('error-msg','Advertisement image missing!');
							redirect("advertisement/add_trainer_adv");
				}

		}else{
		    $data['locations']= $this->advertisement_model->get_location();
			$this->load->template('add_trainer_adv',$data);
		}
	}
	//////////////////////////////////////// add Trainer advertisement end///////////////////////////////////////////////
    ////////////////////// change  Trainer advertisement status start ////////////////////////////////////////////
	public function trainer_adv_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0); 
			$result=$this->advertisement_model->update_adv($insert_data,'advertisement_trainers',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->advertisement_model->update_adv($insert_data,'advertisement_trainers',$id);
		}	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("advertisement/trainer");		
		}else{
			$this->session->set_flashdata('error-msg','Status not Updated!');
			redirect("advertisement/trainer");		
		}
		
	}
	////////////////////// change  Trainer advertisement status end ////////////////////////////////////////////
	//////////////////////////////////  Trainer advertisement edit start /////////////////////////////////////////////////////
	public function edit_trainer_adv($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
		    
			$path="pics/advertisement/trainer_advertisement/";
        	$image=$this->common->file_upload_image($path);
        	if(!empty($image)){
                	$data=array(
                				'image'=>$image,
        						'status'=>$this->input->post('status'),
        				);    
        	}else{
                	$data=array(
        						'status'=>$this->input->post('status'),
        				);
        	}

		        $this->advertisement_model->update_adv($data,'advertisement_trainers',$id);
				$this->session->set_flashdata('success-msg','Trainer Advertisement Edited!');
				redirect("advertisement/trainer");
		}else{
		    $data['advertisement']=$this->advertisement_model->get_trainer_details($id);
		    $this->load->template('edit_trainer_adv',$data);
		}
	}
	//////////////////////////////////  Trainer advertisement edit end /////////////////////////////////////////////////////
//////////////////////////////////  Trainer Advertisement delete start /////////////////////////////////////////////////////
	public function delete_trainer_adv($id)
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_advertisement')) 
		{
			redirect('acl');
		}
		$data=$this->advertisement_model->delete_adv($id,'advertisement_trainers');
 		if($data)
 		{
				$this->session->set_flashdata('success-msg','Trainer Advertisement has been  Deleted!');
				redirect("advertisement/trainer");
		}else{
				$this->session->set_flashdata('error-msg','Trainer Advertisement not deleted!');
				redirect("advertisement/trainer");
		}
	}
//////////////////////////////////  Trainer Advertisement delete end /////////////////////////////////////////////////////
//////////////////////////////////  Venue Advertisement start /////////////////////////////////////////////////////
	public function venue() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) {
			redirect('acl');
		}else{
			$data['list']=$this->advertisement_model->get_venue_adv();
			$this->load->template('list_venue_adv',$data);
		}
	}
//////////////////////////////////  Venue Advertisement end /////////////////////////////////////////////////////
	///////////////////////////////////////// add Venue advertisement start //////////////////////////////////////////////////////
	public function add_venue_adv(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
			$path="pics/advertisement/venue_advertisement/";
        	$image=$this->common->file_upload_image($path);
		       	if(!empty($image)){
		        	$data=array(
		        	            'location_id'=>$this->input->post('location'),
		        				'image'=>$image,
								'status'=>$this->input->post('status'),
								'added_date'=>date('Y-m-d H:i:s'),
						);
						$add=$this->advertisement_model->insert_advertisement($data,'advertisement_venue');
				        if($add){
							$this->session->set_flashdata('success-msg',"New Venue Advertisement has been added!");
				    			redirect("advertisement/venue");
						}else{
							$this->session->set_flashdata('error-msg','Venue Advertisement not added!');
							redirect("advertisement/venue");		
						}
				}else{
							$this->session->set_flashdata('error-msg','Advertisement image missing!');
							redirect("advertisement/add_venue_adv");
				}

		}else{
		    $data['locations']= $this->advertisement_model->get_location();
			$this->load->template('add_venue_adv',$data);
		}
	}
	//////////////////////////////////////// add Venue advertisement end///////////////////////////////////////////////
    ////////////////////// change  Venue advertisement status start ////////////////////////////////////////////
	public function venue_adv_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0); 
			$result=$this->advertisement_model->update_adv($insert_data,'advertisement_venue',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->advertisement_model->update_adv($insert_data,'advertisement_venue',$id);
		}	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("advertisement/venue");		
		}else{
			$this->session->set_flashdata('error-msg','Status not Updated!');
			redirect("advertisement/venue");		
		}
		
	}
	////////////////////// change  Venue advertisement status end ////////////////////////////////////////////
	//////////////////////////////////  Venue advertisement edit start /////////////////////////////////////////////////////
	public function edit_venue_adv($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
		    
			$path="pics/advertisement/venue_advertisement/";
        	$image=$this->common->file_upload_image($path);
        	if(!empty($image)){
                	$data=array(
                				'image'=>$image,
        						'status'=>$this->input->post('status'),
        				);    
        	}else{
                	$data=array(
        						'status'=>$this->input->post('status'),
        				);
        	}

		        $this->advertisement_model->update_adv($data,'advertisement_venue',$id);
				$this->session->set_flashdata('success-msg','Venue Advertisement Edited!');
				redirect("advertisement/venue");
		}else{
		    $data['advertisement']=$this->advertisement_model->get_venue_details($id);
		    $this->load->template('edit_venue_adv',$data);
		}
	}
	//////////////////////////////////  Venue advertisement edit end /////////////////////////////////////////////////////
//////////////////////////////////  Venue Advertisement delete start /////////////////////////////////////////////////////
	public function delete_venue_adv($id)
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_advertisement')) 
		{
			redirect('acl');
		}
		$data=$this->advertisement_model->delete_adv($id,'advertisement_venue');
 		if($data)
 		{
				$this->session->set_flashdata('success-msg','Venue Advertisement has been  Deleted!');
				redirect("advertisement/venue");
		}else{
				$this->session->set_flashdata('error-msg','Venue Advertisement not deleted!');
				redirect("advertisement/venue");
		}
	}
//////////////////////////////////  Venue Advertisement delete end /////////////////////////////////////////////////////
//////////////////////////////////  Shop Advertisement2 start /////////////////////////////////////////////////////
	public function shops() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) {
			redirect('acl');
		}else{
			$data['list']=$this->advertisement_model->get_shops_adv();
			$this->load->template('list_shops_adv',$data);
		}
	}
//////////////////////////////////  Shop Advertisement2 end /////////////////////////////////////////////////////
	///////////////////////////////////////// add shop advertisement 2 start //////////////////////////////////////////////////////
	public function add_shops_adv(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
			$path="pics/advertisement/shops_advertisement/";
        	$image=$this->common->file_upload_image($path);
		       	if(!empty($image)){
			        	$data=array(
			        	            'location_id'=>$this->input->post('location'),
			        				'image'=>$image,
									'status'=>$this->input->post('status'),
									'added_date'=>date('Y-m-d H:i:s'),
							);
							$add=$this->advertisement_model->insert_advertisement($data,'advertisement_shops');
					        if($add){
								$this->session->set_flashdata('success-msg',"New Shop Advertisement 2 has been added!");
					    			redirect("advertisement/shops");
							}else{
								$this->session->set_flashdata('error-msg','Shop Advertisement 2 not added!');
								redirect("advertisement/shops");		
							}
				}else{
							$this->session->set_flashdata('error-msg','Advertisement image missing!');
							redirect("advertisement/add_shops_adv");
				}

		}else{
		    $data['locations']= $this->advertisement_model->get_location();
			$this->load->template('add_shops_adv',$data);
		}
	}
	//////////////////////////////////////// add shop advertisement 2 end///////////////////////////////////////////////
	//////////////////////////////////  shop advertisement 2 edit start /////////////////////////////////////////////////////
	public function edit_shops_adv($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_advertisement')) {
			redirect('acl');
		}
		if($this->input->post()){
		    
			$path="pics/advertisement/shops_advertisement/";
        	$image=$this->common->file_upload_image($path);
        	if(!empty($image)){
                	$data=array(
                				'image'=>$image,
        						'status'=>$this->input->post('status'),
        				);    
        	}else{
                	$data=array(
        						'status'=>$this->input->post('status'),
        				);
        	}

		        $this->advertisement_model->update_adv($data,'advertisement_shops',$id);
				$this->session->set_flashdata('success-msg','Shop Advertisement 2 Edited!');
				redirect("advertisement/shops");
		}else{
		    $data['advertisement']=$this->advertisement_model->get_shops_details($id);
		    $this->load->template('edit_shops_adv',$data);
		}
	}
	//////////////////////////////////  shop advertisement 2 edit end /////////////////////////////////////////////////////
    ////////////////////// change  shop advertisement 2 status start ////////////////////////////////////////////
	public function shops_adv_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0); 
			$result=$this->advertisement_model->update_adv($insert_data,'advertisement_shops',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->advertisement_model->update_adv($insert_data,'advertisement_shops',$id);
		}	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("advertisement/shops");
		}else{
			$this->session->set_flashdata('error-msg','Status not Updated!');
			redirect("advertisement/shops");
		}
		
	}
	////////////////////// change  shop advertisement 2 status end ////////////////////////////////////////////
//////////////////////////////////  Shop Advertisement 2 delete start /////////////////////////////////////////////////////
	public function delete_shops_adv($id)
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_advertisement')) 
		{
			redirect('acl');
		}
		$data=$this->advertisement_model->delete_adv($id,'advertisement_shops');
 		if($data)
 		{
				$this->session->set_flashdata('success-msg','Shop Advertisement 2 has been  Deleted!');
				redirect("advertisement/shops");
		}else{
				$this->session->set_flashdata('error-msg','Shop Advertisement 2 not deleted!');
				redirect("advertisement/shops");
		}
	}
//////////////////////////////////  Shop Advertisement 2 delete end /////////////////////////////////////////////////////
}


