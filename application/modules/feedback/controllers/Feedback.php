<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Feedback extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('faq/faq_model');
			
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

			
		}else{
			$data['table'] 	= $this->faq_model->get_feedback_list();
			//echo "<pre>";print_r($data);exit();		
			$this->load->template('list',$data);
		}
	}
	//////////////////////////////////////////////////////////////
	
}

