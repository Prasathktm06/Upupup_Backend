<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ACL extends CI_controller {
	
	private $acl_conf;
	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));

		$this->load->model('acl_model');
		$this->acl_conf = (object)$this->config->item('acl');
	}
	
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
			if($this->acl_conf->sign_in_enabled) {
				$this->session->set_flashdata('error-msg','Your blocked!');
				$this->session->sess_destroy();
				redirect('acl/user/sign_in');
				
			}
			else {
				
				redirect('acl/user/index');
                
			}
		}
		$data['app_users']=$this->acl_model->total_count('users');
		$data['venue']=$this->acl_model->total_count('venue');
		$data['matches']=$this->acl_model->total_count('matches');
		$data['booking']=$this->acl_model->total_count('venue_booking');
		$data['feedback']=$this->acl_model->feedback();
		
		$this->load->template('acl/index', $data, FALSE, 'back');
		//$this->load->view('dashboard');

	}
	public function profile(){
	if($this->input->post()){
      $this->form_validation->set_rules('name',		'Name',	'required');
	  $this->form_validation->set_rules('email',	'Email',	'trim|strtolower|required|valid_email['.$this->acl_table['user'].'.email]');
	  $this->form_validation->set_message('email', FALSE);
		if($this->form_validation->run() == FALSE) {					
				
				$this->load->template('acl/form/edit_profile', NULL, FALSE, 'back');
			}else{
				$data=array(
					'name'=>$this->input->post('name'),
					'email'=>$this->input->post('email')
					);
				$this->session->set_userdata(array(
					'email'		=> $this->input->post('email'),
					'name' 		=> $this->input->post('name'),
					));
				$this->session->set_flashdata('success-msg','New User has been added!');
				$this->acl_model->edit_user($this->session->userdata('user_id'),$data);
				redirect('acl/profile');
			}
	}else{

	$data['profile']= $this->acl_model->get_user_by('user_id',$this->session->userdata('user_id'));
		

	$this->load->template('acl/form/edit_profile',$data);
}
	}
		public function edit_password(){

		$data['profile']= $this->acl_model->get_user_by('user_id',$this->session->userdata('user_id'));

		if($this->input->post()){
	     
		  $this->form_validation->set_rules('new-password',		'New Password',	'required');
		  $this->form_validation->set_rules('confirm-password',		'Confirm Password',	'required');
		 
			if($this->form_validation->run() == FALSE) {					
					
					redirect('acl/profile');
				}else{
					//if($data['profile'][0]->password==hash('sha512',$this->input->post('current-password'))){
					if($this->input->post('new-password')==$this->input->post('confirm-password')){
					$data=array(
						'password'=>hash('sha512',$this->input->post('new-password')));
					$this->acl_model->edit_user($this->session->userdata('user_id'),$data);

					$this->session->set_flashdata('success-msg','Password has been changed!');
					redirect('acl/profile');
				}else{
					$this->session->set_flashdata('error-msg','Password not match!');
					redirect('acl/profile');
				}
			}
		}else{

		$data['profile']= $this->acl_model->get_user_by('user_id',$this->session->userdata('user_id'));
			

		$this->load->template('acl/form/edit_profile',$data);
}
	}
	
}

/* End of file acl.php */
/* Location: ./application/controllers/acl/acl.php */