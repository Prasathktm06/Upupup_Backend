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
 * ACL Controller (Perm)
 * 
 * Provides a set functions to maintain user roles within the system
 * 
 * @package		ACL
 * @subpackage	Controllers
 * @author		William Duyck <fuzzyfox0@gmail.com>
 *
 * @todo	document this class
 */
class Perm extends CI_controller {
	
	private $acl_table;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		
		$this->acl_table = (object)$this->config->item('acl');
		$this->acl_table =& $this->acl_table->table;
	}
	
	public function add() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_perm')) {
			show_error('Permission denied.', 401);
		}
	
		$this->form_validation->set_rules('name',			'Name',			'trim|required|max_length[70]|is_unique['.$this->acl_table['role'].'.name]');
		$this->form_validation->set_rules('slug',			'Slug',			'trim|strtolower|required|max_length[35]|is_unique['.$this->acl_table['role'].'.slug]');
		$this->form_validation->set_rules('description',	'Description',	'trim');
		
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$data['perm_name']= $this->acl_model->get_perm_name();
			
			$this->load->template('acl/form/add_perm', $data, FALSE, 'back');
		}
		else {
			
			$data = array(
				'parent_id'		=>$this->input->post('perm_name'),
				'name'			=> $this->input->post('name'),
				'slug'			=> $this->input->post('slug'),
				'description'	=> $this->input->post('description')
			);
			
			if($this->acl_model->add_perm($data)) {
				$this->session->set_flashdata('success-msg','New Permission has been added!');
				redirect('acl/perm/add');
			}
			else {
				show_error('Failed to add role');
			}
		}
	}
	
	
	public function edit($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_perm')) {
			show_error('Permission denied.', 401);
		}
		
		$this->form_validation->set_rules('name',			'Name',			'trim|required|max_length[70]|callback__edit_unique[name.'.$id.']');
	//	$this->form_validation->set_rules('slug',			'Slug',			'trim|strtolower|required|max_length[35]|callback__edit_unique[slug.'.$id.']');
		$this->form_validation->set_rules('description',	'Description',	'trim');
		
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$data['perm'] = $this->acl_model->get_perm($id);
			
			$this->load->template('acl/form/edit_perm', $data, FALSE, 'back');
		}
		else {
			$data = array(
				'name'			=> $this->input->post('name'),
			//	'slug'			=> $this->input->post('slug'),
				'description'	=> $this->input->post('description')
			);
			
			if($this->acl_model->edit_perm($id, $data)) {
				$this->session->set_flashdata('success-msg','Permission has been updated!');
				redirect('acl/perm');
			}
			else {
				show_error('Failed to edit role');
			}
		}
	}
	
	public function del($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_perm')) {
			show_error('Permission denied.', 401);
		}
		
		if($this->acl_model->del_perm($id)) {
			$this->session->set_flashdata('delete-msg','Role has been updated!');
			redirect('acl/perm');
		}
		else {
			show_error('Unable to delete permission');
		}
	}
	
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_perm')) {
			show_error('Permission denied.', 401);
		}
		
		$this->db->order_by('name', 'asc');
		$data['perm_list'] = $this->acl_model->get_all_perms();
		
		$this->load->template('acl/perm', $data, FALSE, 'back');
	}
	
	public function _edit_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);
		$count = $this->db->where($field,$str)->where('perm_id !=',$id)->get($this->acl_table['perm'])->num_rows();
		if($field=="slug")
			$this->form_validation->set_message('_edit_unique', 'Slug already exists.');
		if($field=="name")
			$this->form_validation->set_message('_edit_unique', 'Name already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	public function add_perm_name(){
		if($this->input->post()){

			$this->form_validation->set_rules('perm_name',	'Permission Name',	'required');
			if($this->form_validation->run() == FALSE) {
				echo("acl/perm/add");
			}
			$data= array(
				'name'=>$this->input->post('perm_name')	
			);
			$perm_name=$this->acl_model->add_perm_name($data);
			
			$this->session->set_flashdata('success-msg','New Permission has been added!');
			redirect("acl/perm/add");
			
		}else{	
			$this->load->template('form/add_perm_name');
		}
	}

	public function delete_perm_name($id){
		$this->acl_model->delete_perm_name($id);
		$this->session->set_flashdata('error-msg',"Permission Name delete!");
		redirect('acl/perm/add');
	}



}

/* End of file perm.php */
/* Location: ./application/controllers/acl/perm.php */