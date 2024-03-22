<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Sports_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_details($table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->order_by('sports.sports');
		$this->db->where('sports.status',1);
		return $this->db->get()->result();
	}

	public function update_user_sports($data){
		if($this->db->insert('user_sports', $data))
			return $this->db->insert_id();
			else
				return false;
		
		
	}
	
	public function delete_user_sports($user_id){
		$this->db->delete('user_sports', array('user_id' => $user_id));
		return ($this->db->affected_rows() == 1);
	}
	public function get_user_sports($id){
		$this->db->select('sports.sports,sports.id,sports.image');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		$this->db->where('user_sports.user_id',$id);
		$this->db->where('sports.status',1);

		return $this->db->get()->result();
	}

}