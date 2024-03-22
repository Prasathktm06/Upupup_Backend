<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Sharevenue_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

public function get_upupupemail(){
	$this->db->select('email');
	$this->db->from('upupup_email');
	$this->db->where("share_venue",1);
	return $this->db->get()->result();
	}
//user id corresponding to venue
public function get_venue($user_id){
	$this->db->select('venue_id');
	$this->db->from('venue_manager');
	$this->db->where("user_id",$user_id);
	return $this->db->get()->result();
	}
//venue owners email
public function get_owneremail($venue_id){
	$this->db->select('email');
	$this->db->from('user');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->where("venue_manager.venue_id",$venue_id);
	$this->db->where("user_role.role_id",28);
	return $this->db->get()->result();

	}
//check vendor user staus is active
public function get_vendorusers($manager_id,$vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$manager_id);
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",1);
        return $this->db->get()->result();
	}
//check vendor user staus is in-active
public function get_vendorcheck($manager_id,$vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$manager_id);
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",0);
        return $this->db->get()->result();
	}


}
 