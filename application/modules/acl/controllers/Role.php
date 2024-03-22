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
 * @license		ht	tp://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 * @since		2012.12.30
 */

// ------------------------------------------------------------------------
	
/**
 * ACL Controller (Role)
 * 
 * Provides a set functions to maintain user roles within the system
 * 
 * @package		ACL
 * @subpackage	Controllers
 * @author		William Duyck <fuzzyfox0@gmail.com>
 *
 * @todo	document this class
 */
class Role extends CI_controller {
	
	private $acl_table;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		
		$this->acl_table = (object)$this->config->item('acl');
		$this->acl_table =& $this->acl_table->table;
	}
		
	public function add() {

		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_role')) {
			show_error('Permission denied.', 401);
		}
		
		$this->form_validation->set_rules('name',			'Name',			'trim|required|max_length[70]|is_unique['.$this->acl_table['role'].'.name]');
		$this->form_validation->set_rules('slug',			'Slug',			'trim|strtolower|required|max_length[35]|is_unique['.$this->acl_table['role'].'.slug]');
		$this->form_validation->set_rules('description',	'Description',	'trim');
		
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$this->load->template('acl/form/add_role.php', NULL, FALSE, 'back');
		}
		else {
			$data = array(
				'name'			=> $this->input->post('name'),
				'slug'			=> $this->input->post('slug'),
				'description'	=> $this->input->post('description')
			);
			
			if($role_id=$this->acl_model->add_role($data)) {
				if ($this->input->post('venue_user')) {
				$data=array(

						'venue_users'=>$this->input->post('venue_user'),
					);
				$this->acl_model->update_venue_users($data,$role_id);
			}
				$this->acl_model->add_role_perm($role_id,1);
				$this->session->set_flashdata('success-msg','Role Added!');
				redirect('acl/role');
			}
			else {
				show_error('Failed to add role');
			}
		}
	}
	
	public function edit($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_role')) {
			show_error('Permission denied.', 401);
		}
		$role_id = $id;
		$this->form_validation->set_rules('name',			'Name',			'trim|required|max_length[70]|callback__edit_unique[name.'.$id.']');
		
		//$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[70]');
		$this->form_validation->set_rules('slug',			'Slug',			'trim|strtolower|required|max_length[35]|callback__edit_unique[slug.'.$id.']');
		$this->form_validation->set_rules('description',	'Description',	'trim');
		
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$data['role']			= $this->acl_model->get_role($id);
			$data['role']->perms	= $this->acl_model->get_role_perms_list($id);
			$data['perm_list']		= $this->acl_model->get_all_perms();
			$data['perm_name']	= $this->acl_model->get_perm_name();	
			foreach ($data['perm_name']as $val){
				
				$data['group'][$val->name]=$this->acl_model->get_perm_group($val->id);
			}
			//print_r($data);exit;
			if(is_array($data['role']->perms)) {
				foreach($data['perm_list'] as &$perm) {
					$perm->set = in_array($perm, $data['role']->perms);
				}
			}
			else {
				foreach($data['perm_list'] as &$perm) {
					$perm->set = FALSE;
				}
			}			
			$this->load->template('acl/form/edit_role.php', $data, FALSE, 'back');
		}
		else {
			
			$data = array(
					'name'			=> $this->input->post('name'),
					'slug'			=> $this->input->post('slug'),
					'description'	=> $this->input->post('description')
			);
				
			if($this->acl_model->edit_role($id, $data) && $this->acl_model->edit_role_perms($id, $this->input->post('perms'))) {
				$this->session->set_flashdata('success-msg','Role has been updated!');
				redirect('acl/role');
			}
			else {
				show_error('Failed to edit role.');
			}
		}
	}
	
	public function del($id) {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_role')) {
			show_error('Permission denied.', 401);
		}
		
		if($this->acl_model->del_role($id)) {
			redirect('acl/role');
		}
		else {
			show_error('Unable to delete role');
		}
	}
	
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_role')) {
			show_error('Permission denied.', 401);
		}
		
		$this->db->order_by('name', 'asc');
		$data['role_list'] = $this->acl_model->get_all_roles();
		
		foreach($data['role_list'] as &$role) {
			$role->perms = $this->acl_model->get_role_perms($role->role_id);
		}
		
		$this->load->template('acl/role', $data, FALSE, 'back');
	}
	
	public function _edit_unique($str, $id){

		sscanf($id, '%[^.].%[^.]', $field,$id);
		$count = $this->db->where($field,$str)->where('role_id !=',$id)->get('role')->num_rows();
		if($field=="name")
			$this->form_validation->set_message('_edit_unique', 'Name already exists.');
		if($field=="slug")
			$this->form_validation->set_message('_edit_unique', 'Slug already exists.');
		//$this->form_validation->set_message('_edit_unique', 'Category already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}

}

/* End of file role.php */
/* Location: ./application/controllers/acl/role.php */