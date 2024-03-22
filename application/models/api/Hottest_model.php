<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Hottest_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}


public function get_user_detail($user_id){
			$this->db->select('id');  
	        $this->db->from('users');
	        $this->db->where("status",1);
			$this->db->where("id",$user_id);
		    return $this->db->get()->result();
	       
	}




}
