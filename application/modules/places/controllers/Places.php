<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Places extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('places_model');
		if(isset($_SESSION['signed_in'])==TRUE ){
			
		}else{
			redirect('acl/user/sign_in');
		}
		
	}
	
	public function location() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
			
		}else{			
			$this->load->template('list');
		}
	}
	
	public function location_add(){
		if($this->input->post()){
			$this->form_validation->set_rules('location',	'Location',	'required|is_unique[locations.location]');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("places/location_add");
			}else{
				$data=array(
						'location'=>$this->input->post('location')
				);
				$this->places_model->add_location($data);
				$this->session->set_flashdata('success-msg','New City has been added!');	
				redirect('places/location');
			}
			
		}else{
			$this->load->template('add');
		}
	}
	
	public function location_edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules('location',	'Location',	'required|callback__location_unique[location.'.$id.']');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("places/location_edit/$id");
			}else{
				$data=array(
						'location'=>$this->input->post('location')
				);
				$this->places_model->update_location($id,$data);
				$this->session->set_flashdata('success-msg','City has been edited!');
				redirect("places/location/$id");
			}
				
		}else{
			$data['location']=$this->places_model->get_location($id);
			$data['id']=$id;
			//print_r($data);exit;
			$this->load->template('edit',$data);
		}
	}
	
	public function location_delete($id){
		$this->places_model->delete($id,'locations','id');
		$this->session->set_flashdata('success-msg','City deleted!');
		redirect('places/location');
	}
	
	public function area_delete($id){
		$this->places_model->delete($id,'area','id');
		$this->session->set_flashdata('success-msg','Area deleted!');
		redirect('places/area');
	}

	public function locationTable(){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_location');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_location');
		$table=$this->places_model->get_locationTable($edit,$delete);
		echo json_encode($table);
	}
	
	public function area(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
				
		}else{

			$this->load->template('area');
		}
	}
	
	public function areaTable(){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_area');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_area');
		$table=$this->places_model->get_areaTable($edit,$delete);
		echo json_encode($table);
	}
	
	public function area_add(){
		if($this->input->post()){
			$this->form_validation->set_rules('area',	'Area',	'required|is_unique[area.area]');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("places/area_add");
			}else{
				$data=array(
						'area'=>$this->input->post('area'),
						'location_id'=> $this->input->post('location')
				);
				$this->places_model->add_area($data);
				$this->session->set_flashdata('success-msg','New Area has been added!');
				redirect('places/area');
			}
				
		}else{
			$data['location']=$this->places_model->get_location();
			$this->load->template('area_add',$data);
		}
	}
	
	public function area_edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules('area',	'Area',	'required|callback__area_unique[area.'.$id.']');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("places/area_edit/$id");
			}else{
				$data=array(
						'area'=>$this->input->post('area'),
						'location_id'=> $this->input->post('location')
				);
				$this->places_model->update_area($id,$data);
				$this->session->set_flashdata('success-msg','Area has been edited!');
				redirect('places/area');
			}
	
		}else{
			$data['location']=$this->places_model->get_location();
			$data['id']=$id;
			$data['area']=$this->places_model->get_area($id);
			$this->load->template('area_edit',$data);
		}
	}
	
	
	public function _location_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);
		
		$count = $this->db->where($field,$str)->where('id !=',$id)->get('locations')->num_rows();
		
		$this->form_validation->set_message('_edit_unique', 'Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	public function _area_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);
	
		$count = $this->db->where($field,$str)->where('id !=',$id)->get('area')->num_rows();
	
		$this->form_validation->set_message('_edit_unique', 'Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	//////////////////////////////////////Change Status///////////////////////////////////////////
	public function change_status($id,$status="")
	{ //print_r($status);exit();
		if($id){
			if($status){
				$insert_data=array('status'=>'0');
				$result=$this->places_model->update_area($id,$insert_data);
			}else{
				$insert_data 		=array('status'=>'1');
				$location_id 		=$this->places_model->area_details($id)->location_id;
				$location_active 	=$this->places_model->location_active($location_id);
				if ($location_active=="") {
					$this->session->set_flashdata('success-msg',' Please Enable The City First');
					redirect('places/area');
				}else{
					$result=$this->places_model->update_area($id,$insert_data);
				}
			}
			

			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('places/area');
			}
		}
	}
	///////////////////////////Bulk Upload Function-City///////////////////////////////////////
	public function bulk_upload_city()
	{
		
		$this->load->library('excelphp');
		if($_FILES){

			$path="pics/csv/";
        	$file_name=$this->common->file_upload_csv($path);
        	//echo "<pre>";print_r($file_name);exit();
        	$result = $this->excelphp->parse_file($path.$file_name);
        	//echo "<pre>";print_r($result);exit();
        	
        	if ($result) {
	        	foreach ($result['values'] as $key => $value) {
	        		if ($value['city']!="") {
	        			$city_exist	= $this->places_model->city_exist(trim($value['city']));
	        			if (count($city_exist)==0) {
		        			$insert_city 	= array('location' 	=> $value['city'],
		        									'status'	=>0);
		        			$location_id 	=$this->places_model->add_location($insert_city)->id;
		        			//echo "<pre>";print_r($location_id);exit();
		        			if ($value['area']!="") {
		        				$area_array 	= explode(",",$value['area']);
			        			foreach ($area_array as $key2 => $value2) {
			        				$area_array = array('location_id' 	=> $location_id, 
			        							  		'area'			=> $value2,
			        							  		'status'		=>0);
			        				$area_exist	= $this->places_model->area_exist($area_array);
			        				//echo "<pre>";print_r($area_array);exit();
			        				if (!$area_exist) {
				        				$insert 		=$this->places_model->add_area($area_array);
				        			}
			        			}
		        			}	
		        		}else{
		        			$location_id 	=$city_exist->id;
		        			if ($value['area']!="") {
		        				$area_array 	= explode(",",$value['area']);
			        			foreach ($area_array as $key2 => $value2) {
			        				$area_array = array('location_id' 	=> $location_id, 
			        							  		'area'			=> $value2);
			        				$area_exist	= $this->places_model->area_exist($area_array);
			        				//echo "<pre>";print_r($area_array);exit();
			        				if (!$area_exist) {
				        				$insert 		=$this->places_model->add_area($area_array);
				        			}
			        			}
		        			}
		        		}
	        		}	
	        	}
		    }
            
        	$this->session->set_flashdata('success-msg','Data Uploaded Successfully!');
			redirect('places/location');
        	
        }else{
			$this->load->template('bulk.php');
		}
	}
	///////////////////////////Bulk Upload Function-Area///////////////////////////////////////
	public function bulk_upload_area()
	{
		
		$this->load->library('excelphp');
		if($_FILES){

			$path="pics/csv/";
        	$file_name=$this->common->file_upload_csv($path);
        	//echo "<pre>";print_r($file_name);exit();
        	$result = $this->excelphp->parse_file($path.$file_name);
        	//echo "<pre>";print_r($result);exit();
        	
        	if ($result) {
	        	foreach ($result['values'] as $key => $value) {
	        		$city_exist	= $this->places_model->city_exist(trim($value['city']));
	        		if ($city_exist) {
	        			$city_id 		= $city_exist->id;
	        			$insert_array 	= array('area' 			=> $value['area'], 
	        							  		'location_id'	=> $city_id,
	        							  		'status'		=>0);
	        			$area_exist		= $this->places_model->area_exist($insert_array);
	        			if (!$area_exist) {
	        				$insert 		=$this->places_model->add_area($insert_array);
	        			}
	        		}
	        		
	        	}
		    }
            
        	$this->session->set_flashdata('success-msg','Data Uploaded Successfully!');
			redirect('places/area');
        	
        }else{
			$this->load->template('bulk_area.php');
		}
	}
	/////////////////////////////Change Status Area///////////////////////////////////////
	public function change_status_area($id,$status="")
	{ //print_r($status);exit();
		$areas=$this->places_model->city_areas($id);
		//echo "<pre>";print_r($areas);exit();
		if($id){
			if($status){
				$insert_data=array('status'=>'0');
				if ($areas) {
					foreach ($areas as $key => $value) {
						$result=$this->places_model->update_area($value['id'],$insert_data);
					}
				}
			}else{
				$insert_data=array('status'=>'1');
				if ($areas) {
					foreach ($areas as $key => $value) {
						$result=$this->places_model->update_area($value['id'],$insert_data);
					}
				}
			}
			$result=$this->places_model->update_location($id,$insert_data);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('places/location');
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////
}

