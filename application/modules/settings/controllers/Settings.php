<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Settings extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('settings_model');
		if(isset($_SESSION['signed_in'])==TRUE ){
			
		}else{
			redirect('acl/user/sign_in');
		}
			
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

			
		}else{
			$this->load->template('backup');
		}
	}
	
		
	/*public function offerTable(){
		$edit 	= $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_offer');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_offer');
		$table 	= $this->coupons_model->get_offerTable($edit,$delete);
		echo json_encode($table);
	}*/
	////////////////////////////////////Database Backup//////////////////////////////////////////
	public function database_backup(){
        $this->load->dbutil();
        $prefs = array(     
                'format'      => 'zip',             
                'filename'    => 'my_db_backup.sql',
                'foreign_key_checks'=>FALSE
              );

        $backup =& $this->dbutil->backup($prefs); 

        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
        $save = '/upload/_tmp/'.$db_name;

        $this->load->helper('file');
        write_file($save, $backup); 

        $this->load->helper('download');
        force_download($db_name, $backup); 

	}
	/////////////////////////////////////Image Backup/////////////////////////////////////////
	function image_backup() 
	{ 
	    $this->load->library('zip'); 
	    $path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); 
	    //print_r($path);exit();
	    $this->zip->read_dir($path.'/upupup/pics/',false);
	    $this->zip->archive('backup.zip'); 
	    $this->zip->download('backup.zip');
	} 
	
	/////////////////////////////////////////Truncate Tables///////////////////////////////////////
	public function truncate_table(){
		$tables 	= $this->db->list_tables();
		$acl_tables = array('0' => 'perm',
							'1' => 'perm_name',
							'2' => 'role',
							'3' => 'role_perm',
							'4' => 'user',
							'5' => 'user_role', 
							'6' => 'month',
							'7' => 'status');
		//echo "<pre>";print_r($acl_tables);
		foreach ($tables as $key => $value) {
			if (in_array($value, $acl_tables)==0) {//echo "<pre>";print_r($value);exit();
				$result=$this->db->empty_table($value);
			}
		}
		
		if ($result) {
			$pics = glob('pics/*'); // get all file names
			//echo "<pre>";print_r($files);exit();
			foreach($pics as $file){ // iterate files
			  if(is_file($file))
			    unlink($file); // delete file
			}

			$icons = glob('pics/icons/*'); // get all file names
			//echo "<pre>";print_r($files);exit();
			foreach($icons as $file1){ // iterate files
			  if(is_file($file1))
			    unlink($file1); // delete file
			}
			$this->session->set_flashdata('success-msg',' Data has been deleted!');
			redirect('settings');
		}
	}
	////////////////////////////////////////////////
	public function file_delete(){
		$pics = glob('pics/*'); // get all file names
		//echo "<pre>";print_r($files);exit();
		foreach($pics as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}

		$icons = glob('pics/icons/*'); // get all file names
		//echo "<pre>";print_r($files);exit();
		foreach($icons as $file1){ // iterate files
		  if(is_file($file1))
		    unlink($file1); // delete file
		}
	}
	////////////////////////////////Contact Details///////////////////////////////////
	public function contact_data(){
		$data['email']=$this->settings_model->email_list();
		$data['phone']=$this->settings_model->phone_list();
		$this->load->template('contact',$data);
	}
	///////////////////////////Email Insertion/////////////////////////////////////
	public function email_add(){
		$email =$this->input->post('email');
		$insert = array('email' => $email, );
		$result=$this->settings_model->email_insert($insert);
		if ($result) {
			redirect('settings/contact_data?email=1');
		}
	}
	////////////////////////////////////////////////////////////////////////////
	public function phone_add(){
		$phone =$this->input->post('phone');
		$insert = array('phone' => $phone, );
		$result=$this->settings_model->phone_insert($insert);
		if ($result) {
			redirect('settings/contact_data?phone=1');
		}
	}
	//////////////////////////////////Change Status//////////////////////////////////////
	public function email_status($id,$status="",$test="")
	{ 
		if($id){
			if($status){
				$insert_data=array($test=>'0');
			}else{
				$insert_data=array($test=>'1');
			}
			//echo "<pre>";print_r($insert_data);exit();
			$result=$this->settings_model->update_data($insert_data,$id,'upupup_email');
			if($result){
				/*$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('settings/contact_data?email=1');*/
				echo "Success";
			}
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////
	public function phone_status($id,$status="",$test="")
	{ 
		//echo "<pre>";print_r($id);exit();
		if($id){
			if($status){
				$insert_data=array($test=>'0');
			}else{
				$insert_data=array($test=>'1');
			}
			//echo "<pre>";print_r($insert_data);exit();
			$result=$this->settings_model->update_data($insert_data,$id,'upupup_phone');
			if($result){
				echo "Success";
				/*$this->session->set_flashdata('success-msg',' Status has been changed!');
				//redirect('settings/contact_data?phone=1');*/
			}
		}
	}

	/*public function phone_status($id,$status="",$test="")
	{ 
		if($id){
			if($status){
				$insert_data=array($test=>'0');
			}else{
				$insert_data=array($test=>'1');
			}
			//echo "<pre>";print_r($insert_data);exit();
			$result=$this->settings_model->update_data($insert_data,$id,'upupup_phone');
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('settings/contact_data?phone=1');
			}
		}
	}*/
	/////////////////////////////////////Email Edit//////////////////////////////////////
	public function email_edit($id){
		if($this->input->post()){
			$data=array(
					'email'	=>$this->input->post('email'),
					);
			$result=$this->settings_model->update_data($data,$id,'upupup_email');
			if ($result) {
				$this->session->set_flashdata('success-msg',' Email has been edited!');
				redirect('settings/contact_data?email=1');
			}
		}else{
			$data['details']=$this->settings_model->get_details($id,'upupup_email');
			//echo "<pre>";print_r($data);exit();
			$this->load->template('email_edit',$data);
		}
	}
	/////////////////////////////////////Phone Edit//////////////////////////////////////
	public function phone_edit($id){
		if($this->input->post()){
			$data=array(
					'phone'	=>$this->input->post('phone'),
					);
			$result=$this->settings_model->update_data($data,$id,'upupup_phone');
			if ($result) {
				$this->session->set_flashdata('success-msg',' Phone has been edited!');
				redirect('settings/contact_data?phone=1');
			}
		}else{
			$data['details']=$this->settings_model->get_details($id,'upupup_phone');
			//echo "<pre>";print_r($data);exit();
			$this->load->template('phone_edit',$data);
		}
	}
	////////////////////////////////Delete Function///////////////////////////////////////
	public function email_delete($id){
		$result=$this->settings_model->delete_data($id,'upupup_email');
		if ($result) {
			$this->session->set_flashdata('success-msg',' Email has been deleted!');
			redirect('settings/contact_data?email=1');
		}
	}
	public function phone_delete($id){
		$result=$this->settings_model->delete_data($id,'upupup_phone');
		if ($result) {
			$this->session->set_flashdata('success-msg',' Phone has been deleted!');
			redirect('settings/contact_data?phone=1');
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////
}

