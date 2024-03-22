<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sports extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('sports_model');
	
		
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

			
		}else{
						
			$this->load->template('list');
		}
	}
	
		
	public function sportsTable(){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_sports');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_sports');
		$table=$this->sports_model->get_sportsTable($edit,$delete);
		echo json_encode($table);
	}
	
	public function add(){
		if($this->input->post()){
			$this->form_validation->set_rules('sports',	'Sports',	'required|is_unique[sports.sports]');
			
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("sports/add");
			}else{
				$path="pics/icons/";
        		$image=$this->common->file_upload_image($path);
				$data=array(
						'sports'=>$this->input->post('sports'),
						'image'=>$image
				);
				$this->sports_model->add_sports($data);
				$this->session->set_flashdata('success-msg','New Sports has been added!');
				redirect('sports');
			}
				
		}else{
			$this->load->template('add');
		}
	}
	
	public function edit($id){
		if($this->input->post()){
			$data=array();
			$this->form_validation->set_rules('sports',	'Sports',	'required|callback__edit_unique[sports.'.$id.']');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("sports/edit/$id");
			}else{
				if($_FILES["file"]["tmp_name"]!=""){
					$path="pics/icons/";
        		$image=$this->common->file_upload_image($path);
        		$data['image']=$image;
        		
				}
				$data['sports']=$this->input->post('sports');
				
				
				$this->sports_model->update_sports($id,$data);
				$this->session->set_flashdata('success-msg','Sports has been edited!');
				redirect('sports');
			}
	
		}else{
			$data['sports']=$this->sports_model->get_sports($id);
			$data['id']=$id;
			$this->load->template('edit',$data);
		}
		
	}

	public function delete($id)
	{
		$this->sports_model->delete($id);
		$this->session->set_flashdata('success-msg','Sports Deleted!');
				redirect('sports');
	}
	
	
	
	public function _edit_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);
		
		$count = $this->db->where($field,$str)->where('id !=',$id)->get('sports')->num_rows();
		
		$this->form_validation->set_message('_edit_unique', 'Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	///////////////////////////Bulk Upload Function///////////////////////////////////////
	public function bulk_upload()
	{
		
		$this->load->library('excelphp');
		if($_FILES){

			$path="pics/csv/";
        	$file_name=$this->common->file_upload_csv($path);
        	//echo "<pre>";print_r($file_name);exit();
        	$result = $this->excelphp->parse_file($path.$file_name);
        	
        	if ($result) {
        		$path2 = "pics/icons/"; // Upload directory
	        	foreach ($result['values'] as $key => $value) {
	        		$sports_exist	= $this->sports_model->sports_exist(trim($value['sports']));
	        		if (count($sports_exist)==0) {
	        			$insert_array 	= array('sports' 	=> $value['sports'],
	        							  		'image' 	=> base_url().$path2.$value['image'], 
	        							  		'status'	=> 1);
	        			$insert 		=$this->sports_model->add_sports($insert_array);

	        		}
	        		
	        	}

	        	$valid_formats = array("jpg", "png", "gif", "zip", "bmp");
				$max_file_size = 1024*100; //100 kb
				
				$count = 0;

				if(!empty($_FILES['userFiles']['name'])){

					// Loop $_FILES to exeicute all files
					foreach ($_FILES['userFiles']['name'] as $f => $name) {     
					    if ($_FILES['userFiles']['error'][$f] == 4) {
					        continue; // Skip file if any error found
					    }	     
					    if ($_FILES['userFiles']['error'][$f] == 0) {	           
					        if ($_FILES['userFiles']['size'][$f] > $max_file_size) {
					            $message[] = "$name is too large!.";
					            continue; // Skip large files
					            //echo "<pre>";print_r($message);exit();  
					        }
							elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
								$message[] = "$name is not a valid format";
								continue; // Skip invalid file formats
							}
					        else{ // No error found! Move uploaded files 
					            if(move_uploaded_file($_FILES["userFiles"]["tmp_name"][$f], $path2.$name))
					            $count++; // Number of successfully uploaded file
					        }
					    }
					}
				}
	        	
		    }
            
        	$this->session->set_flashdata('success-msg','Data Uploaded Successfully!');
			redirect('sports');
        	
        }else{
			$this->load->template('bulk.php');
		}
	}
	//////////////////////////////////////Change Status///////////////////////////////////////////
	public function change_status($id,$status="")
	{ 
		if($id){
			if($status){
				$insert_data=array('status'=>'0');
			}else{
				$insert_data=array('status'=>'1');
			}
			$result=$this->sports_model->update_sports($id,$insert_data);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('sports');
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////
	
}


