<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Trainers extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('trainers_model');
		date_default_timezone_set("Asia/Kolkata");
	
		
	}
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_trainers')) {
			redirect('acl');
		}else{
			if($this->input->post()){
					$location_id=$this->input->post('city');
					$data['list']=$this->trainers_model->get_trainers($location_id);
					$data['locations']= $this->trainers_model->get_location_list();
					$this->load->template('list',$data);
			}else{
					$data['list']=$this->trainers_model->get_trainers();
					$data['locations']= $this->trainers_model->get_location_list();
					$this->load->template('list',$data);
			}
		}
	}
		
	/////////////////////////////////////// add trainers start ////////////////////////////////////////////////////////
	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_trainers')) {
			redirect('acl');
		}else{
            $data['locations']= $this->trainers_model->get_location();
            $data['sports']=$this->trainers_model->get_sports();
			$this->load->template('add',$data);
		}
	}
	///////////////////////////////////////////// add trainers end //////////////////////////////////////////	

	///////////////////////////////////////// add trainers //////////////////////////////////////////////////////
	public function trainers_add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_trainers')) {
			redirect('acl');
		}
		if($this->input->post()){
			$phone_number=$this->input->post('phone');
			$trainer_data['trainer']= $this->trainers_model->get_phone_check($phone_number);
			if(empty($trainer_data['trainer'])){
					$path="pics/trainers/profile/";
		        	$image=$this->common->file_upload_image($path);
		        	$data=array(
								'name'=>$this->input->post('trainer'),
								'age'=>$this->input->post('age'),
								'location_id'=>$this->input->post('location'),
								'address'=>$this->input->post('address'),
								'phone'=>(int)$this->input->post('phone'),
								'experience'=>$this->input->post('experience'),
								'certifications'=>$this->input->post('certifications'),
								'availability'=>$this->input->post('availability'),
								'speciality'=>$this->input->post('speciality'),
								'profile_image'=>str_replace(" ","%20",$image),
								'training_image'=>"",
								'achievement'=>$this->input->post('achievements'),
								'status'=>1,
								'added_date'=>date('Y-m-d H:i:s'),
						);
						$id=$this->trainers_model->insert_trainer($data);
		        				
		        				$data_area=array(
		        						'trainers_id'=> $id,
		        						'area_id'=>$this->input->post('area'),
		        						'status'=>1,
		        						'added_date'=>date('Y-m-d H:i:s'),
		        				);
		        				$this->trainers_model->add_areas($data_area,'trainers_area');
		                    $sports=$this->input->post('sports');
		        			foreach ($sports as $val){
		        				$data_sports=array(
		        						'trainers_id'=> $id,
		        						'sports_id'=>$val,
		        						'status'=>1,
		        						'added_date'=>date('Y-m-d H:i:s'),
		        				);
		        				$this->trainers_model->add_sports($data_sports,'trainers_sports');
		        			}
				        if($id){
							$this->session->set_flashdata('success-msg',"New Trainer has been added!");
				    			redirect("trainers");
						}else{
							$this->session->set_flashdata('error-msg','Trainer not added!');
							redirect("trainers");		
						}
					}else{
							$this->session->set_flashdata('error-msg','Phone Number al-ready exist!');
							redirect("trainers/add");	
					}

		}else{
			$data['locations']= $this->trainers_model->get_location();
			$this->load->template('add',$data);
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////  trainers edit start /////////////////////////////////////////////////////
	public function trainers_edit($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_trainers')) {
			redirect('acl');
		}
		if($this->input->post()){
				$path="pics/trainers/profile/";
        	$image=$this->common->file_upload_image($path);
        	if(empty($image))
        	{
        	$data=array(
						'name'=>$this->input->post('trainer'),
						'age'=>$this->input->post('age'),
						'location_id'=>$this->input->post('city'),
						'address'=>$this->input->post('address'),
						'phone'=>(int)$this->input->post('phone'),
						'experience'=>$this->input->post('experience'),
						'certifications'=>$this->input->post('certifications'),
						'availability'=>$this->input->post('availability'),
						'speciality'=>$this->input->post('speciality'),
						'achievement'=>$this->input->post('achievements'),
						'status'=>$this->input->post('status'),
				);
		        $this->trainers_model->update_trainers($id,$data);
				$this->session->set_flashdata('success-msg','Trainers Edited!');
				redirect("trainers");    
        	}else{
        	$data=array(
						'name'=>$this->input->post('trainer'),
						'age'=>$this->input->post('age'),
						'location_id'=>$this->input->post('city'),
						'address'=>$this->input->post('address'),
						'phone'=>(int)$this->input->post('phone'),
						'experience'=>$this->input->post('experience'),
						'certifications'=>$this->input->post('certifications'),
						'availability'=>$this->input->post('availability'),
						'speciality'=>$this->input->post('speciality'),
						'profile_image'=>str_replace(" ","%20",$image),
						'achievement'=>$this->input->post('achievements'),
						'status'=>$this->input->post('status'),
				);
		        $this->trainers_model->update_trainers($id,$data);
				$this->session->set_flashdata('success-msg','Trainers Edited!');
				redirect("trainers");    
        	}

		}else{
		    $data['locations']= $this->trainers_model->get_location();
			$data['trainer']= $this->trainers_model->get_trainer_details($id);
			$data['sports']=$this->trainers_model->get_sports();
		    $data['trainer_sports']=$this->trainers_model->get_trainer_sports($id);
		    $data['areas']=$this->trainers_model->get_location_areas($id);
		    $data['trainer_areas']=$this->trainers_model->get_trainer_areas($id);
		    $data['programs']=$this->trainers_model->get_trainer_programs($id);
		    $data['testimonial']=$this->trainers_model->get_trainer_testimonial($id);
		    $this->load->template('edit',$data);
		}
	}
	//////////////////////////////////  trainers edit end /////////////////////////////////////////////////////
	//////////////////////////////////  add training image start /////////////////////////////////////////////////////
	public function add_training_image($path='')
	{
		$id=$this->input->post('trainer') ;
		$imagedetails = getimagesize($_FILES['file']['tmp_name']);
		$width = $imagedetails[0];
		$height = $imagedetails[1];
		//echo "<pre>";print_r($width);
		//echo "<pre>";print_r($height);
		if($width !=300 || $height!=200 ){
	        $this->session->set_flashdata('error-msg','Sorry wrong resolution!');
			redirect("trainers/trainers_edit/$id?gallery=1");
	    }else{
	    	$path="pics/trainers/training/";
	    	$image=$this->common->file_upload_image($path);
	    	        	$data=array(
						'training_image'=>$image,
				);
						
			$this->trainers_model->update_trainers($id,$data);
			$this->session->set_flashdata('success-msg','Image Added Successfully!');
			redirect("trainers/trainers_edit/$id?gallery=1");
	    }
	}
	//////////////////////////////////  add training image end /////////////////////////////////////////////////////
	//////////////////////////////////////    add sports start////////////////////////////////////////////////
	public function add_sports($id){
		if($this->input->post()){
			$this->trainers_model->delete_sports($id);
			$sports=$this->input->post('sports');
			foreach ($sports as $val){
				$data=array(
						'trainers_id'=> $id,
						'sports_id'=>$val,
						'status'=>1,
						'added_date'=>date('Y-m-d H:i:s'),
				);
				$this->trainers_model->add_sports($data,'trainers_sports');
			}
		}
		$this->session->set_flashdata('success-msg','Sports Added!');
		redirect("trainers/trainers_edit/$id?sports=1");
	}	
	////////////////////////////////////// edit sports start////////////////////////////////////////////////
	//////////////////////////////////////    add areas start////////////////////////////////////////////////
	public function add_areas($id){
		if($this->input->post()){
			$this->trainers_model->delete_areas($id);
			$areas=$this->input->post('areas');
			foreach ($areas as $val){
				$data=array(
						'trainers_id'=> $id,
						'area_id'=>$val,
						'status'=>1,
						'added_date'=>date('Y-m-d H:i:s'),
				);
				$this->trainers_model->add_areas($data,'trainers_area');
			}
		}
		$this->session->set_flashdata('success-msg','Areas Added!');
		redirect("trainers/trainers_edit/$id?areas=1");
	}	
	////////////////////////////////////// edit areas start////////////////////////////////////////////////	
    ////////////////////// change  trainers status start ////////////////////////////////////////////
	public function change_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->trainers_model->update_status($insert_data,'trainers',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->trainers_model->update_status($insert_data,'trainers',$id);
		}	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("trainers");		
		}
		
	}
	////////////////////// change  trainers status end ////////////////////////////////////////////
	//////////////////////////////////  Trainer delete start /////////////////////////////////////////////////////
	public function delete_trainer($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_trainers')) {
			redirect('acl');
		}
		$this->trainers_model->delete_trainer_program($id);
		$this->trainers_model->delete_trainer_area($id);
		$this->trainers_model->delete_trainer_sport($id);
		$this->trainers_model->delete_trainer_testimonial($id);
		$this->trainers_model->delete_trainer_followers($id);
		$data=$this->trainers_model->delete_trainers($id);
 		if($data){
				$this->session->set_flashdata('success-msg','Trainer has been  Deleted!');
				redirect("trainers");
		}else{
				$this->session->set_flashdata('error-msg','Trainer not deleted!');
				redirect("trainers");	
		}
	}
	//////////////////////////////////  Trainer delete end /////////////////////////////////////////////////////
	/////////////////////////////////////// programs start ////////////////////////////////////////////////////////
	public function programs(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_program')) {
			redirect('acl');
		}else{
            $data['locations']= $this->trainers_model->get_location();
			$this->load->template('add_programs',$data);
		}
	}
	/////////////////////////////////////// programs end ////////////////////////////////////////////////////////
	///////////////////////////////////////// add programs start //////////////////////////////////////////////////////
	public function add_programs(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_program')) {
			redirect('acl');
		}
		if($this->input->post()){
		    $id=$this->input->post('trainer_id');
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
				$add=$this->trainers_model->insert_programs($data);
		        if($add){
					$this->session->set_flashdata('success-msg',"New Program has been added!");
		    			redirect("trainers/trainers_edit/$id?programs=1");
				}else{
					$this->session->set_flashdata('error-msg','Program not added!');
					redirect("trainers/programs");		
				}

		}else{
			$data['locations']= $this->trainers_model->get_location();
			$this->load->template('add',$data);
		}
	}
	////////////////////////////////////////// add programs end/////////////////////////////////////////////
    ////////////////////// change  program status start ////////////////////////////////////////////
	public function program_status($id,$status,$trainers_id)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->trainers_model->update_program_status($insert_data,'trainers_program',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->trainers_model->update_program_status($insert_data,'trainers_program',$id);
		}
		
	    $id=$trainers_id;	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("trainers/trainers_edit/$id?programs=1");
		}
		
	}
	////////////////////// change  program status end ////////////////////////////////////////////
	//////////////////////////////////  program edit start /////////////////////////////////////////////////////
	public function program_edit($id,$trainers_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_program')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
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
		        $this->trainers_model->update_programs($id,$data);
				$this->session->set_flashdata('success-msg','Programs Edited!');
				$id=$trainers_id;
				redirect("trainers/trainers_edit/$id?programs=1");
		}else{
            $data['program']=$this->trainers_model->get_program_details($id);
		    $this->load->template('edit_program',$data);
		}
	}
	//////////////////////////////////  program edit end /////////////////////////////////////////////////////
	//////////////////////////////////  program delete start /////////////////////////////////////////////////////
	public function program_delete($id,$trainers_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_program')) {
			redirect('acl');
		}
		$data=$this->trainers_model->delete_program($id);
		$id=$trainers_id;
 		if($data){
				$this->session->set_flashdata('success-msg','Program has been  Deleted!');
				redirect("trainers/trainers_edit/$id?programs=1");
		}else{
				$this->session->set_flashdata('error-msg','Program not deleted!');
				redirect("trainers/trainers_edit/$id?programs=1");
		}
	}
	//////////////////////////////////  program delete end /////////////////////////////////////////////////////
	/////////////////////////////////////// testimonial start ////////////////////////////////////////////////////////
	public function testimonial(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_trainers')) {
			redirect('acl');
		}else{
            $data['locations']= $this->trainers_model->get_location();
			$this->load->template('add_testimonial',$data);
		}
	}
	/////////////////////////////////////// testimonial end ////////////////////////////////////////////////////////
	///////////////////////////////////////// add testimonial start //////////////////////////////////////////////////////
	public function add_testimonials(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_trainers')) {
			redirect('acl');
		}
		if($this->input->post()){
		    $id=$this->input->post('trainer_id');
        	$data=array(
        				'trainers_id'=>$this->input->post('trainer_id'),
						'name'=>$this->input->post('name'),
						'testimonial'=>$this->input->post('description'),
						'status'=>$this->input->post('status'),
						'added_date'=>date('Y-m-d H:i:s'),
				);
				$add=$this->trainers_model->insert_testimonial($data);
		        if($add){
					$this->session->set_flashdata('success-msg',"New Testimonial has been added!");
		    			redirect("trainers/trainers_edit/$id?testimonial=1");
				}else{
					$this->session->set_flashdata('error-msg','Testimonial not added!');
					redirect("trainers/testimonial");		
				}

		}else{
			$data['locations']= $this->trainers_model->get_location();
			$this->load->template('add_testimonial',$data);
		}
	}
	////////////////////////////////////////// add testimonial end/////////////////////////////////////////////
    ////////////////////// change  testimonial status start ////////////////////////////////////////////
	public function testimonial_status($id,$status,$trainers_id)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->trainers_model->update_testimonial_status($insert_data,'trainers_testimonial',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->trainers_model->update_testimonial_status($insert_data,'trainers_testimonial',$id);
		}
		
	    $id=$trainers_id;	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("trainers/trainers_edit/$id?testimonial=1");
		}
		
	}
	////////////////////// change  testimonial status end ////////////////////////////////////////////
	//////////////////////////////////  testimonial edit start /////////////////////////////////////////////////////
	public function testimonial_edit($id,$trainers_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_trainers')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
						'name'=>$this->input->post('name'),
						'testimonial'=>$this->input->post('description'),
						'status'=>$this->input->post('status'),
				);
		        $this->trainers_model->update_testimonial($id,$data);
				$this->session->set_flashdata('success-msg','Testimonial Edited!');
				$id=$trainers_id;
				redirect("trainers/trainers_edit/$id?testimonial=1");
		}else{
            $data['testimonial']=$this->trainers_model->get_testimonial_details($id);
		    $this->load->template('edit_testimonial',$data);
		}
	}
	//////////////////////////////////  testimonial edit end /////////////////////////////////////////////////////
	//////////////////////////////////  testimonial delete start /////////////////////////////////////////////////////
	public function testimonial_delete($id,$trainers_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_trainers')) {
			redirect('acl');
		}
		$data=$this->trainers_model->delete_testimonial($id);
		$id=$trainers_id;
 		if($data){
				$this->session->set_flashdata('success-msg','Testimonial has been  Deleted!');
				redirect("trainers/trainers_edit/$id?testimonial=1");
		}else{
				$this->session->set_flashdata('error-msg','Testimonial not deleted!');
				redirect("trainers/trainers_edit/$id?testimonial=1");
		}
	}
	//////////////////////////////////  testimonial delete end /////////////////////////////////////////////////////
}


