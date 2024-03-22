<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Version_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	/////////////////////////Coupon List////////////////////////////////////////////////////
	public function get_version($identifier){
		$this->db->select('*');
		$this->db->from('versions');
		$this->db->where('identifier',$identifier);
		
		return $this->db->get()->result_array();
	}
	//////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////// check version////////////////////////////////////////////////////
	public function get_checkversion($package_name){
		$this->db->select('*');
		$this->db->from('versions');
		$this->db->where('version_code',$package_name);
		
		return $this->db->get()->result_array();
	}
	//////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////// user check in user_version table ////////////////////////////////////////////////////
	public function get_usercheck($user_id){
		$this->db->select('*');
		$this->db->from('user_version');
		$this->db->where('user_id',$user_id);
		
		return $this->db->get()->result_array();
	}
	//////////////////////////////////////////////////////////////////////////////////////////
	public function insert_user_version($data,$table){
		if($this->db->insert($table, $data))
			return true;
			else
				return false;
	}
	public function update_user_version($user_id,$data){
		return $this->db->update('user_version', $data, array('user_id' => $user_id));
	}
}