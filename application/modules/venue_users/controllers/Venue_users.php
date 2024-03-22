<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Venue_users extends CI_controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->acl_conf = (object)$this->config->item('acl');
		$this->acl_table =& $this->acl_conf->table;
		$this->load->model('acl/acl_model');
		
	}
	
	public function add($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_user')) {
			redirect('acl');
		}
		
		$this->form_validation->set_rules('name',				'Name',				'trim|required|max_length[70]');
		$this->form_validation->set_rules('email',				'Email',			'trim|strtolower|required|valid_email|is_unique['.$this->acl_table['user'].'.email]');
		$this->form_validation->set_rules('password',			'Password',			'required');
		$this->form_validation->set_rules('roles',	'Roles ',	'required');
		
		if($this->form_validation->run() == FALSE) {

            $data['roles']=$this->acl_model->get_all_roles();
         	$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$this->load->template('add',$data);
		}
		else {
			$data = array(
				'name'		=> $this->input->post('name'),
				'email'		=> $this->input->post('email'),
				'password'	=> hash('sha512', $this->input->post('password'))
			);
			$roles = $this->input->post('roles');
				
			if($id=$this->acl_model->add_user($data) ) {
				
				if($this->acl_model->add_user_role($id,$roles)){
					$this->session->set_flashdata('success-msg','New User has been added!');
				redirect('acl/user');
			}
			}
			else {
				show_error('Failed to add user.');
			}
		
		}

		
	}
}