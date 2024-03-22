<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Callbook_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
//////////////////////// venue owner details ////////////////////////////////
public function get_ownerdeatils($venue_id){
	$this->db->select('user.name,user.email,user.phone');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('venue_manager.venue_id',$venue_id);
	$this->db->where('role.venue_users',3);
	$this->db->limit(1); 
	return $this->db->get()->result();
	}
//////////////////////// user details ////////////////////////////////
public function get_userdeatils($user_id){
	$this->db->select('name,phone_no');
	$this->db->from('users');
	$this->db->where('id',$user_id);
	return $this->db->get()->result();
	}
//////////////////////////// add call book details to table /////////////////////////////
    public function add_call_book($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
//////////////////////// service charge details ////////////////////////////////
public function get_service_charge($location_id){
	$this->db->select('id,amount');
	$this->db->from('service_charge');
	$this->db->where('location_id',$location_id);
	$this->db->where('status',1);
	return $this->db->get()->result();
	}
//////////////////////////// add booking service charge table /////////////////////////////
    public function insert_add($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}

}
 