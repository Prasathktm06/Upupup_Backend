<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI_ACL
 * 
 * Yet another ACL implementation for CodeIgniter. More specifically this is 
 * a role-based access control list for CodeIgniter.
 * 
 * @package		ACL
 * @author		William Duyck <fuzzyfox0@gmail.com>
 * @copyright	Copyright (c) 2012, William Duyck
 * @license		http://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 * @since		2012.12.30
 */

// ------------------------------------------------------------------------

/**
 * ACL Controller (User)
 * 
 * Provides a set functions to maintain user roles within the system
 * 
 * @package		ACL
 * @subpackage	Controllers
 * @author		William Duyck <fuzzyfox0@gmail.com>
 *
 * @todo	document this class
 */
class User extends CI_controller {
	
	private $acl_table;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		
		$this->acl_conf = (object)$this->config->item('acl');
		$this->acl_table =& $this->acl_conf->table;
		$this->load->library('csvreader');	
		$this->load->library('common');	
	}
	///////////////////////////////////////////////////////////////////////////////////////////////
	public function index() {
		//print_r($this->acl_model->get(1));exit();
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_user')) {
			redirect('acl');
		}
		
		$this->db->order_by('name', 'asc');
		$data['user_list'] = $this->acl_model->get_all_users();
		foreach($data['user_list'] as &$user) {
			$user->roles = $this->acl_model->get_user_roles($user->user_id);
		}
		
		$this->load->template('acl/user', $data, FALSE, 'back');
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////
	public function add($venue='') {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_user')&& !$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_venue_user')) {
			redirect('acl');
		}
		
		$this->form_validation->set_rules('name','Name','trim|required|max_length[70]');
		$this->form_validation->set_rules('email','Email','trim|strtolower|required|valid_email|is_unique['.$this->acl_table['user'].'.email]');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('roles','Roles ','required');
		
		if($this->form_validation->run() == FALSE) {
			if($venue!=''){
				$data['roles']=$this->acl_model->get_all_roles2();
				$data['venue']=$venue;
				$data['users']=$this->acl_model->get_all_users2();
				$data['court']=$this->acl_model->get_all_court($venue);
				//print_r($data);exit;
			}
			else{
	            $data['roles']=$this->acl_model->get_all_roles();
	            $data['venue']=$venue;
	        }
         	$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$this->load->template('acl/form/add_user', $data, FALSE, 'back');
		}
		else {	
		     $numcheck=$this->acl_model->get_number_check($this->input->post('phone'));
				if(!empty($numcheck)){
				    $this->session->set_flashdata('error-msg',"Entered Phone Number Already Exists.");
					redirect('acl/user/edit/'.$id);
				}
			$path="pics/users/";
        	@$image=$this->common->file_upload_image($path);
			$data = array(
				'name'		=> $this->input->post('name'),
				'email'		=> $this->input->post('email'),
				'password'	=> hash('sha512', $this->input->post('password')),
				'phone'		=> $this->input->post('phone'),
				'image'		=>	$image
			);
			$roles = $this->input->post('roles');
			
			if($id=$this->acl_model->add_user($data) ) {
				if($venue!=''){
					$data=array(
							'user_id'=>$id,
							'venue_id'=>$venue
						);
					$this->acl_model->add_venue_manager($data);
					if ($this->input->post('court')) {
						$court = $this->input->post('court');
						foreach ($court as $key5 => $value5) {
							$court_array = array('user_id' 	=> $id, 
												 'court_id' => $value5, );
							$res=$this->acl_model->court_manager_court($court_array);
						}
					}
				}	
				if($this->acl_model->add_user_role($id,$roles)){
                                	$user_role=$this->acl_model->get_userrole($id);
                                	//echo "<pre>";print_r($user_role);
				foreach($user_role as $row) {
           			$role_mode = $row->venue_users;
          		}
                    
                    if(($role_mode==0)){
                     /* mail for users on admin panel excluding venue owners,venue manager,court manager start  */ 
				    	$data['data']=array(
						'email'=>$this->input->post('email'),
						'password'=>$this->input->post('password'),
						'name'=> $this->input->post('name')
						);
					$subject='Congratulations';
          			//$subject="some text";
		          	$message=$message;
		          	$to_email = $this->input->post('email')	;
		          	//Load email library 
		          	$this->load->library('email'); 
		          	$config['protocol']    = 'smtp';
		          	$config['smtp_host']    = 'upupup.in';
		          	$config['smtp_port']    = '25';
		          	$config['smtp_timeout'] = '7';
		          	$config['smtp_user']    = 'admin@upupup.in';
		          	$config['smtp_pass']    = '%S+1q)yiC@DW';
		          	$config['charset']    = 'utf-8';
		          	$config['newline']    = '\r\n';
		          	$config['mailtype'] = 'html'; // or html
		          	$config['validation'] = TRUE; // bool whether to validate email or not      
		          	$this->email->initialize($config);
		          	$this->load->library('email', $config);
		          	$this->email->set_newline("\r\n");
		          	$this->email->from('admin@upupup.in','upUPUP.');
		          	$this->email->to($to_email);
		          	$this->email->subject($subject);
		            $message = $this->load->view('user_mail',$data,true);
					$this->email->message($message);
		          	$this->email->send();
		          	/* mail for users on admin panel excluding venue owners,venue manager,court manager end */ 
          			if($venue!=''){
          			    $this->session->set_flashdata('success-msg','New User has been added!');
          				redirect('acl/user');
          			}
          			    $this->session->set_flashdata('success-msg','New User has been added!');
						redirect('acl/user');
							
                    }
				}
			$this->session->set_flashdata('success-msg','New User has been added!');
			redirect('acl/user');
			}
			else {
				show_error('Failed to add user.');
			}
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////
	public function del($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_user')) {
			redirect('acl');
		}
		
		if($this->acl_model->del_user($id)) {
			$this->session->set_flashdata('success-msg','User successfully deleted!');
			redirect('acl/user');
		}
		else {
			show_error('Unable to delete user.');
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////
	public function edit($id,$venue_id="") {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_user')&& !$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_venue_user')&& !$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'profile')) {
			redirect('acl');
		}
		if($this->session->userdata('role_slug')!='admin'){
			if($id==1){
				$this->session->set_flashdata('error-msg',"Warning");
				redirect('acl');
			}
		}
		if($this->input->post()){
	      	$this->form_validation->set_rules('name',		'Name',	'required');
		  	$this->form_validation->set_rules('email',	'Email',	'trim|strtolower|required|valid_email|callback__check_user_email['.$id.'.email]');
		 	
			if($this->form_validation->run() == FALSE) {	
        
				$this->session->set_flashdata('error-msg',trim(validation_errors()));		
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));	
				redirect('acl/user/edit/'.$id);
			}else{
			   $numcheck=$this->acl_model->get_number_check($this->input->post('phone'));
				if(!empty($numcheck)){
				    $this->session->set_flashdata('error-msg',"Entered Phone Number Already Exists.");
					redirect('acl/user/edit/'.$id);
				}
			    
				$path="pics/users/";
        		$image1=$this->common->file_upload_image($path);
        		if ($image1) {
        			$image=$image1;
        		}else{
        			$image=$this->input->post('img_old');
        		}
				$data=array(
					'name'=>$this->input->post('name'),
					'email'=>$this->input->post('email'),
					'phone'		=> $this->input->post('phone'),
					'image'		=>	$image
				);
    
				$role_data=array(
					'role_id' 	=>$this->input->post('roles'),
				);
				if ($venue_id) {
					$res1=$this->acl_model->court_manager_court_delete($id);
					if ($this->input->post('court')) {
						$court = $this->input->post('court');
						foreach ($court as $key5 => $value5) {
							$court_array = array('user_id' 	=> $id, 
												 'court_id' => $value5, );
							$res=$this->acl_model->court_manager_court($court_array);
						}
					}
				}
				$this->acl_model->update_user_role($role_data,$id);
				if($this->input->post('password')!=""){
					$password=hash('sha512',$this->input->post('password'));
					$data['password'] = $password;
				}
				$this->acl_model->edit_user($id,$data);

				$this->session->set_flashdata('success-msg',"Your profile has been updated");
				if($venue_id!=''){
          			redirect("venue/venue_edit/$venue_id?tab=6");
          		}else{
          			redirect('acl/user/');
          		}
				//redirect('acl/user/edit/'.$id);
				redirect('acl/user/');
			}
		}else{
			$data['profile']= $this->acl_model->get_user_by('user_id',$id);
			$data['roles'] = $this->acl_model->get_all_roles();
			$data['user_roles'] = $this->acl_model->get_user_roles($id);
			$data['court']=$this->acl_model->get_all_court($venue_id);
			$data['court_assigned']=$this->acl_model->court_assigned($id);
			$data['venue_id']=$venue_id;
			//echo "<pre>";print_r($data);exit();
			$this->load->template('acl/form/edit_user',$data);
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////
	public function edit_password($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_user')) {
			redirect('acl');
		}
		$data['profile']= $this->acl_model->get_user_by('user_id',$this->session->userdata('user_id'));

		if($this->input->post()){
	     // $this->form_validation->set_rules('current-password',		'Current Password',	'required');
		  $this->form_validation->set_rules('new-password',		'New Password',	'required');
		//  $this->form_validation->set_rules('confirm-password',		'Confirm Password',	'required');
		 
			if($this->form_validation->run() == FALSE) {					
				$this->session->set_flashdata('error-msg',trim(validation_errors()));
					$this->load->template('acl/form/edit_profile', NULL, FALSE, 'back');
				}else{
				//	if($data['profile'][0]->password==hash('sha512',$this->input->post('current-password'))){
					$data=array(
						'password'=>hash('sha512',$this->input->post('new-password')));
					if($this->acl_model->edit_user($id,$data)){
						$this->session->set_flashdata('success-msg','Password has been changed!');
							redirect('acl/user');
					}
				/*}else{
					$this->session->set_flashdata('error-msg','Incorrect password ');
				//	$this->session->set_flashdata('error', 'Incorrect password ');
					redirect('acl/user/edit/'.$id);
				}*/
			}
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	public function assign($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'assign_role')) {
			redirect('acl');
		}
		
		$this->form_validation->set_rules('roles[]', 'Roles', 'required');
		
		if($this->form_validation->run() == FALSE) {
			$data['user']			= $this->acl_model->get_user($id);
			$data['user']->roles	= $this->acl_model->get_user_roles($id);
			$data['role_list']		= $this->acl_model->get_all_roles();
			$this->session->set_flashdata('error-msg',trim(validation_errors()));
			if(is_array($data['user']->roles)) {
				foreach($data['role_list'] as &$role) {
					$role->set = in_array($role, $data['user']->roles);
				}
			}
			else {
				foreach($data['role_list'] as &$role) {
					$role->set = FALSE;
				}
			}
			
			$this->load->template('acl/form/assign_user', $data, FALSE, 'back');
		}
		else {
			if($this->acl_model->edit_user_roles($id, $this->input->post('roles'))) {
				$this->session->set_flashdata('success-msg','Successfully Assigned!');
				redirect('acl/user');
			}
			else {
				show_error('Failed assign user.');
			}
		}
	}
	
	/*
	| -------------------------------------------------------------------
	| User sign in methods
	| -------------------------------------------------------------------
	*/
	
	public function sign_in() {
		if($this->acl_conf->sign_in_enabled) {
			if(!$this->session->userdata('signed_in')) {
				$this->form_validation->set_rules('email',		'Email',	'trim|strtolower|required|valid_email['.$this->acl_table['user'].'.email]');
				$this->form_validation->set_rules('password',	'Password',	'required|callback__valid_sign_in['.$this->input->post('email').']');
				
				$this->form_validation->set_message('email', FALSE);
			
				if($this->form_validation->run() == FALSE) {					
					//$this->load->template('acl/form/sign_in', NULL, FALSE, 'back');
					$this->load->view('acl/form/sign_in', NULL, FALSE, 'back');
				}
				else {

					$user = $this->acl_model->get_user_by('email', $this->input->post('email'),1);
					$role=$this->acl_model->get_user_roles($user[0]->user_id);
					
					$this->session->set_userdata(array(
						'user_id'	=> $user[0]->user_id,
						'email'		=> $this->input->post('email'),
						'name' 		=> $user[0]->name,
						'role'=>  $role[0]->name,
						'role_slug'=>$role[0]->slug,
						'image'	=> $user[0]->image,
						'signed_in'	=> TRUE
					));
					
					redirect('acl');
				}
			}
			else {
				redirect('acl');
			}
		}
		else {
			show_404();
		}
	}
	
	public function _valid_sign_in($password, $email) {
		$email		= trim(strtolower($email));
		$password	= hash('sha512', $password);
		
		$user = $this->acl_model->get_user_by('email', $email);
		
		$this->form_validation->set_message('_valid_sign_in', 'Invalid sign in credentials.');
		
		return ($user !== FALSE) ? ($user[0]->password == $password) : FALSE;
	}
	
	public function sign_out() {
		if($this->acl_conf->sign_in_enabled) {
			if($this->session->userdata('signed_in')) {
				$this->session->sess_destroy();
				
				redirect('');
			}
			
			show_error('You need to be signed in to sign out!', 401);
		}
		else {
			show_404();
		}
	}

	public function lost_password() {
		if($this->input->post()){
			$this->form_validation->set_rules('email',		'Email',	'trim|strtolower|required|valid_email['.$this->acl_table['user'].'.email]');
			$this->form_validation->set_message('lost_password', 'Invalid Email.');
	
			if($this->form_validation->run() == FALSE) {					
						
				$this->load->view('acl/form/lost_password', NULL, FALSE, 'back');
			}

			$random= mt_rand(10000000,99999999);  //8 digits
			$data = array(
				'password'=> hash('sha512', $random)
				);

			if($user = $this->acl_model->get_user_by('email', $this->input->post('email'))){
				$this->acl_model->edit_user($user[0]->user_id,$data);
				
				$subject="Password Lost ";
			    //$subject="some text";
			    $message="Hello User,Your new Password is ".$random;
			    $to_email = $this->input->post('email');
			    //Load email library 
			    $this->load->library('email'); 
			    $config['protocol']    = 'smtp';
			    $config['smtp_host']    = 'upupup.in';
			    $config['smtp_port']    = '25';
			    $config['smtp_timeout'] = '7';
			    $config['smtp_user']    = 'admin@upupup.in';
			    $config['smtp_pass']    = '%S+1q)yiC@DW';
			    $config['charset']    = 'utf-8';
			    $config['newline']    = '\r\n';
			    $config['mailtype'] = 'html'; // or html
			    $config['validation'] = TRUE; // bool whether to validate email or not      
			    $this->email->initialize($config);
			    $this->load->library('email', $config);
			    $this->email->set_newline("\r\n");
			    $this->email->from('admin@upupup.in','upUPUP.');
			    $this->email->to($to_email);
			    $this->email->subject($subject);
			    $this->email->message($message);
			    $this->email->send();
			    $this->session->set_flashdata('success-msg','Please check your email for further information!');
				$this->load->view('acl/form/sign_in');
			}
			redirect('acl/user/sign_in');
		}else{	
			$this->session->set_flashdata('error-msg','Sorry wrong credentials!');
			$this->load->view('acl/form/lost_password');
		}
	
	}


	public function _check_user_email($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $id, $field);
		$count = $this->db->where($field,$str)->where('user_id !=',$id)->get($this->acl_table['user'])->num_rows();
		
		$this->form_validation->set_message('_check_user_email', 'Entered Email Already Exists.');
		return ($count>=1) ? FALSE:TRUE;
	}

	public function bulk()
	{
		$error=0;
		if($_FILES){
			$path="files/";
        	$file_name=$this->common->file_upload_csv($path);
        	//print_r($file_name);exit;
        	$result = $this->csvreader->parse_file($path.$file_name);


foreach ($result as $key => $value) {

	$role=$this->acl_model->get_role_id($value['role']);
	if(!empty($role)){
	$result[$key]['role']=$role->role_id;

}else{
	$this->session->set_flashdata('error-msg',"Wrong Role Name");
	redirect('acl/user');
	}
}
foreach ($result as $key => $value) {
	$data = array('name' =>$value['name'] ,
				   'email'=>$value['email'],
				   	'password'=>hash('sha512', $value['password'])
	 );
	$email=$this->acl_model->email_exits($value['email']);
	
	if (empty($email)) {
		if (!filter_var($value['email'], FILTER_VALIDATE_EMAIL) === false) {
	$id=$this->acl_model->add_user($data);
	$this->acl_model->add_user_role($id,$value['role']);

	}else{
		$error=1;
		$msg='Invaild Email!';
	}
	}else{
		$error=1;
		$msg='Duplicate Email Found!';
}
}
if($error==0){
$this->session->set_flashdata('success-msg',"Bulk Upload Success");
}else{
	$this->session->set_flashdata('error-msg',$msg);
}
redirect('acl/user');
        		
        	
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
								$user_role=$this->acl_model->get_userrole($id);
				foreach($user_role as $row) {
           			$role_mode = $row->venue_users;
          		}


                    if(($role_mode==1) || ($role_mode==2) || ($role_mode==3) ){
                     /* mail for users on admin panel excluding venue owners,venue manager,court manager start  */
                    $user_data=$this->acl_model->get_userdatas($id);
				foreach($user_data as $row) {
           			$vendor_name = $row->name;
           			$vendor_email = $row->email;
           			$vendor_phone = $row->phone;
          		}

          		$data['data']=array(
						'email'=>$vendor_email,
						'name'=> $vendor_name,
						'phone'	=> $vendor_phone,
						);
					$subject='Congratulations';
          			//$subject="some text";
		          	$message=$message;
		          	$to_email = $vendor_email;
		          	//Load email library 
		          	$this->load->library('email'); 
		          	$config['protocol']    = 'smtp';
		          	$config['smtp_host']    = 'upupup.in';
		          	$config['smtp_port']    = '25';
		          	$config['smtp_timeout'] = '7';
		          	$config['smtp_user']    = 'admin@upupup.in';
		          	$config['smtp_pass']    = '%S+1q)yiC@DW';
		          	$config['charset']    = 'utf-8';
		          	$config['newline']    = '\r\n';
		          	$config['mailtype'] = 'html'; // or html
		          	$config['validation'] = TRUE; // bool whether to validate email or not      
		          	$this->email->initialize($config);
		          	$this->load->library('email', $config);
		          	$this->email->set_newline("\r\n");
		          	$this->email->from('admin@upupup.in','upUPUP.');
		          	$this->email->to($to_email);
		          	$this->email->subject($subject);
		            $message = $this->load->view('mgradmin_mail',$data,true);
					$this->email->message($message);
		          	$this->email->send();
		          	/* mail for venue owners,venue manager,court manager end */

							
                    }
          		
			}
			$result=$this->acl_model->edit_user($id,$insert_data);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('acl/user');
			}
		}
	}
	/////////////////////////////////////////////////////////////////////////////

	public function add_venue_user()
	{
		$venue=$this->input->post('venue');
		$check=$this->acl_model->get_venue_manager($venue,$this->input->post('user'));
		if(empty($check)){
			$data=array(
						'user_id'=>$this->input->post('user'),
						'venue_id'=>$venue
					);
				$this->acl_model->add_venue_manager($data);
				$this->session->set_flashdata('success-msg',' Venue manager added!');
				
		}else{
		    $this->session->set_flashdata('error-msg',' This user is already venue manager!');
		}
				redirect("venue/venue_edit/$venue?tab=6");
	}
	public function delete_venue_users($user_id,$venue)
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_venue_user')) {
		redirect('acl');
		}
		$this->acl_model->delete_venue_users($user_id,$venue);
		redirect("venue/venue_edit/$venue?tab=6");
	}
	/////////////////////////////////////////////////////////////////////////////////////////
	public function court_manager_test(){
		$role 	= $this->input->post('role');
		$data	=$this->acl_model->get_slug($role)->slug;
		//echo "<pre>";print_r($data);exit();	
		if ($data) {
			echo json_encode($data);
		}

	}

	public function testMail()
	{
				$subject="Password Lost ";
			    //$subject="some text";
			    $message="Hello User,Your new Password is ";
			    //$to_email = $this->input->post('email');
			    //Load email library 
			    $this->load->library('email'); 
			    $config['protocol']    = 'smtp';
			    $config['smtp_host']    = 'upupup.in';
			    $config['smtp_port']    = '25';
			    $config['smtp_timeout'] = '7';
			    $config['smtp_user']    = 'admin@upupup.in';
			    $config['smtp_pass']    = '%S+1q)yiC@DW';
			    $config['charset']    = 'utf-8';
			    $config['newline']    = '\r\n';
			    $config['mailtype'] = 'html'; // or html
			    $config['validation'] = TRUE; // bool whether to validate email or not      
			    $this->email->initialize($config);
			    $this->load->library('email', $config);
			    $this->email->set_newline("\r\n");
			    $this->email->from('admin@upupup.in','upUPUP.');
			    $this->email->to('varungopalkp@gmail.com');
			    $this->email->subject($subject);
			    $this->email->message($message);
			   $res= $this->email->send();
			   echo "Testing Mail: ";
			   print_r($res);
	}

}?>