<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Vusers extends CI_controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('manager_model');
		
	}
	
	public function add($id) {
		
		echo "sad";
	}
}