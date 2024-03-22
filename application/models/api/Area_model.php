<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Area_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function update_user_area($data){
		if($this->db->insert('user_area', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	
	public function delete_user_area($id){
		$this->db->delete('user_area', array('user_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	
	public function get_user_area($id){
		echo $id;
		$this->db->select('area.area,area.id');
		$this->db->from('user_area');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where("user_area.user_id",$id);
		$this->db->where('area.status',1);
		return $this->db->get()->result();
	}
	
}