<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Assignmanager_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

//check user already exist on that phone number
public function get_usercheck($phone){
        $this->db->select('user_id');
        $this->db->from('user');
        $this->db->where("phone",$phone);
        return $this->db->get()->result();
	}
//check vendor user staus is active where vendor_id and vendor_phone are same
public function get_vendorusers($vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",1);
        return $this->db->get()->result();
	}
//check vendor user staus is in-active where vendor_id and vendor_phone are same
public function get_vendorcheck($vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",0);
        return $this->db->get()->result();
	}
//check email id already exist
public function get_emailcheck($email){
        $this->db->select('user_id');
	$this->db->from('user');
	$this->db->where("email",$email);
	return $this->db->get()->result();
	}
//create manager details
public function insert_manager($data)
	{
	if($this->db->insert('user', $data))
	return  $this->db->insert_id();
	else
	return false;
	}
//connect added manager to venue
public function insert_venuemanager($data)
	{
	if($this->db->insert('venue_manager', $data))
	return  $this->db->insert_id();
	else
        return false;
	}
//add user role
public function insert_userrole($data)
	{
	if($this->db->insert('user_role', $data))
	return  $this->db->insert_id();
	else
        return false;
	}
//connect court manager to court
public function insert_courtmanager($data)
	{
	if($this->db->insert('court_manager_courts', $data))
	return  $this->db->insert_id();
	else
        return false;
	}
//court and sports details corresponding to the venue_id
public function get_details($venue_id){
        $this->db->select('court.id,court.court,sports.image');
        $this->db->from('court');
	$this->db->join('venue_court','venue_court.court_id=court.id');
	$this->db->join('sports','sports.id=court.sports_id');
	$this->db->where("venue_court.venue_id",$venue_id);
	$this->db->where("court.status",1);
	return $this->db->get()->result();
	}

//manager details based on venuelist
public function get_venues($user_id){
        $this->db->select('venue_id');
	$this->db->from('venue_manager');
	$this->db->where("user_id",$user_id);
	return $this->db->get()->result();
	} 

//manager details based on venuelist
public function get_managerlist($venue_id,$roles,$role_adm,$role_edt){
        $this->db->select('user.user_id,user.name AS user_name,role.name AS role_name');
	$this->db->from('user');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where("venue_manager.venue_id",$venue_id);
	$this->db->where("role.name !=",$roles);
	$this->db->where("role.name !=",$role_adm);
	$this->db->where("role.name !=",$role_edt);
	$this->db->where("user.status",1);
	return $this->db->get()->result();
	}
//court manager details based on user_id
public function get_cmdetails($user_id){
        $this->db->select('venue.id AS venue_id,venue.venue,court.id AS court_id,court.court,user.phone');
	$this->db->from('user');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('venue','venue.id=venue_manager.venue_id');
	$this->db->join('court_manager_courts','court_manager_courts.user_id=user.user_id');
	$this->db->join('court','court.id=court_manager_courts.court_id');
	$this->db->where("user.user_id",$user_id);
	$this->db->where("venue_manager.user_id",$user_id);
	$this->db->where("court_manager_courts.user_id",$user_id);
	$this->db->where("user.status",1);
	$this->db->order_by('court.id','asc');
	return $this->db->get()->result();
	}
//remove already assigned courts
public function delete_cmcourts($user_id){
	$this->db->delete('court_manager_courts', array('user_id' => $user_id));
	return ($this->db->affected_rows() == 1);
	}
//remove manager venue details
public function delete_cmvenue($user_id){
	$this->db->delete('venue_manager', array('user_id' => $user_id));
	return ($this->db->affected_rows() == 1);
	}
//remove manager venue details
public function delete_cmroles($user_id){
	$this->db->delete('user_role', array('user_id' => $user_id));
	return ($this->db->affected_rows() == 1);
	}
//remove  manager
public function delete_cmuser($user_id){
	$this->db->delete('user', array('user_id' => $user_id));
	return ($this->db->affected_rows() == 1);
	}
//venue manager details based on user_id
public function get_vmdetails($user_id){
        $this->db->select('venue.id AS venue_id,venue.venue,user.phone');
	$this->db->from('user');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('venue','venue.id=venue_manager.venue_id');
	$this->db->where("user.user_id",$user_id);
	$this->db->where("venue_manager.user_id",$user_id);
	$this->db->order_by('venue.id','asc');
	$this->db->where("user.status",1);
	return $this->db->get()->result();
	}
//venue details
public function get_venuedata($venue_id){
    $this->db->select('venue');
	$this->db->from('venue');
	$this->db->where("id",$venue_id);
	$this->db->where("status",1);
	return $this->db->get()->result();
	}
//Court details
public function get_courtdata($court_id){
    $this->db->select('court');
	$this->db->from('court');
	$this->db->where("id",$court_id);
	$this->db->where("status",1);
	return $this->db->get()->result();
	}
//role details
public function get_managerdata($manager_id){
    $this->db->select('name,email,phone');
	$this->db->from('user');
	$this->db->where("user_id",$manager_id);
	$this->db->where("status",1);
	return $this->db->get()->result();
	}
//select upupup assign manager email
public function get_upupupemail(){
        $this->db->select('email');
		$this->db->from('upupup_email');
		$this->db->where("manager",1);
		return $this->db->get()->result();
	}
////////////////////////////////// user table status change //////////////////////////////////
	public function update_status($data,$table,$user_id)
	{
		return $this->db->update($table, $data, array('user_id' => $user_id));
	}
///////////////////////// assigned manager details //////////////////
public function get_assigned_mgr($vendor_phone){
        $this->db->select('user_id,name,email,phone');
        $this->db->from('user');
        $this->db->where("phone",$vendor_phone);
        return $this->db->get()->result();
	}

///////////////////////// updated court manager details //////////////////
public function get_cmgr($user_id){
        $this->db->select('user_id,name,email,phone');
        $this->db->from('user');
        $this->db->where("user_id",$user_id);
        return $this->db->get()->result();
	}
///////////////////////// venue details //////////////////
public function get_court_venue($court_id){
	    $this->db->distinct('venue_id');
        $this->db->select('venue_id');
        $this->db->from('venue_court');
        $this->db->where("court_id",$court_id);
        return $this->db->get()->result();
	}
//manager details based on venuelist
public function get_cmgrrole($user_id){
    $this->db->select('name,slug');
	$this->db->from('role');
	$this->db->join('user_role','user_role.role_id=role.role_id');
	$this->db->where("user_role.user_id",$user_id);
	return $this->db->get()->result();
	}

public function get_ownerdata($venue_id){
	$this->db->select('user.name,user.email');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('venue_manager.venue_id',$venue_id);
	$this->db->where('role.venue_users',3);
	return $this->db->get()->result();
	}
	
public function get_venue_details($venue_id){
	$this->db->select('venue');
	$this->db->from('venue');
	$this->db->where('id',$venue_id);
	return $this->db->get()->result();
	}
public function get_venuedatass($venue_id){
	$this->db->select('venue');
	$this->db->from('venue');
	$this->db->where('id',$venue_id);
	return $this->db->get()->result();
	}
///////////////////////// updated venue manager details //////////////////
public function get_vmgr($user_id){
        $this->db->select('user_id,name,email,phone');
        $this->db->from('user');
        $this->db->where("user_id",$user_id);
        return $this->db->get()->result();
	}
///////////////////// court manager courts //////////////////////////
public function get_court_detail($user_id){
    $this->db->select('court.id,court.court');
	$this->db->from('court');
	$this->db->join('court_manager_courts','court_manager_courts.court_id=court.id');
	$this->db->where("court_manager_courts.user_id",$user_id);
	return $this->db->get()->result();
	}
///////////////////// venue manager venues //////////////////////////
public function get_venue_detail($user_id){
    $this->db->select('venue.venue');
	$this->db->from('venue');
	$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
	$this->db->where("venue_manager.user_id",$user_id);
	return $this->db->get()->result();
	}
///////////////////// venue manager venues //////////////////////////
public function get_venue_mgrdata($user_id){
    $this->db->select('user_id,name,email,phone');
	$this->db->from('user');
	$this->db->where("user_id",$user_id);
	return $this->db->get()->result();
	}
//////////////////////////// asigned mgr details /////////////////
public function get_assign_manager($vendor_phone){
        $this->db->select('name,email,phone');
        $this->db->from('user');
        $this->db->where("phone",$vendor_phone);
        return $this->db->get()->result();
	}

}
 