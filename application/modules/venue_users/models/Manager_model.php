<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Manager_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	

}