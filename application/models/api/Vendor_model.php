<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Vendor_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
public function get_vendor($phone){
	$this->db->select('user.user_id,user.name,user.email,user.phone,user.image,role.role_id,role.name as role,role.venue_users as role_level');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('user.phone',$phone);
    $this->db->where('user.status',1);
    $this->db->where('role.venue_users !=',0);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
public function get_vendorrole($user_id){
	$this->db->select("role.role_id,role.name as role,role.venue_users as role_level");
	$this->db->from("user_role");
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('user_id',$user_id);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
//check that already registered user status have any change
public function get_userstatus($user_id){
	$this->db->select('status,phone');
	$this->db->from('user');
	$this->db->where("status",1);
	$this->db->where("user_id",$user_id);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
//check status is inactive or user id exist
public function get_statuscheck($user_id){
	$this->db->select('status');
	$this->db->from('user');
	$this->db->where("status",0);
	$this->db->where("user_id",$user_id);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}

public function update_vendorimage($user_id,$imagename){
 	return $this->db->update('user',array('image'=>$imagename),  array('user_id'=>$user_id));
    $result = $query->result_array();
    return $result;
	}
//update user details
public function update_userdata($userdata,$user_id){
	$this->db->update('user', $userdata, array('user_id' => $user_id));
	if($this->db->affected_rows()==1)
	return true;
	else
	return false;
	}
//fetch manager email
public function get_useremail($user_id){
	$this->db->select('email');
	$this->db->from('user');
	$this->db->where("status",1);
	$this->db->where("user_id",$user_id);
	return $this->db->get()->result();
	}
//upupup email
public function get_upupupemail(){
	$this->db->select('email');
        $this->db->from('upupup_email');
	$this->db->where("non_vendors",1);
	return $this->db->get()->result();
	}
//check VDROID version details
public function get_version($identifier){
		$this->db->select('*');
		$this->db->from('versions');
		$this->db->where('identifier',$identifier);
		
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
// venue status check if any venues is attached on that venue
public function get_venuestatus($user_id){
	$this->db->select('venue.id,venue.venue,venue.book_status');
	$this->db->from('venue');
	$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
	$this->db->where('venue_manager.user_id',$user_id);
	$this->db->where("venue.status",1);
	return $this->db->get()->result();
	}
////////// check vendor_id staus is active////////////
public function get_vendor_check($user_id){

		$this->db->select('status,phone');
		$this->db->from('user');
		$this->db->where("status",1);
		$this->db->where("user_id",$user_id);
		return $this->db->get()->result();
	}
////////// check vendor_id staus is inactive ////////////
public function get_vendor_status($user_id){
	
		$this->db->select('status');
		$this->db->from('user');
		$this->db->where("status",0);
		$this->db->where("user_id",$user_id);
		return $this->db->get()->result();
	}
/////////////// role details /////////////////
public function get_home_details($user_id){
		$this->db->select("role.role_id,role.name as role,role.venue_users as role_level");
		$this->db->from("user_role");
		$this->db->join('role','role.role_id=user_role.role_id');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
/////////// vendor app version details /////////////
public function get_app_version($identifier){
		$this->db->select('*');
		$this->db->from('versions');
		$this->db->where('identifier',$identifier);
		
		return $this->db->get()->result();
	}
/////////// venue details ////////////////////
public function get_home_venue($user_id){
		$this->db->select('venue.id,venue.venue,venue.book_status');
		$this->db->from('venue');
		$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
		$this->db->where('venue_manager.user_id',$user_id);
		$this->db->where("venue.status",1);
		return $this->db->get()->result();
	}
///////////// hot offer setting /////////////////////
public function get_home_hotset($venue_id){
		$this->db->select('hot_offer_setting.id as settings_id,hot_offer_setting.percentage as hot_percentage');
		$this->db->from('venue');
		$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		$this->db->join('hot_offer_setting','hot_offer_setting.location_id=locations.id');
		$this->db->where('venue.id',$venue_id);
		$this->db->where("hot_offer_setting.status",1);
		return $this->db->get()->result();
	}
public function get_vendorchk($phone){
	$this->db->select('user.user_id,user.name,user.email,user.phone,user.image,role.role_id,role.name as role,role.venue_users as role_level');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('user.phone',$phone);
    $this->db->where('user.status',0);
    $this->db->where('role.venue_users !=',0);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
/////////////////// check phone number exist and active ////////////////////////////////
public function get_vendorph($phone){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.phone',$phone);
    $this->db->where('user.status',1);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
///////////////////////// check phone number exist and inactive /////////////////////////////
public function get_vendorinst($phone){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.phone',$phone);
    $this->db->where('user.status',0);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
//////////////////// check phone number and email id exist and active ////////////////////
public function get_phemlext($phone,$email){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.phone',$phone);
	$this->db->where('user.email',$email);
	$this->db->where('user.status',1);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
////////// checkphone number exist and active //////////////////
public function get_phexst($phone){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.phone',$phone);
	$this->db->where('user.status',1);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
/////////////// check email id exist and active /////////////////
public function get_emailexst($email){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.email',$email);
	$this->db->where('user.status',1);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
////////////// check phone number and email id exist and inactive /////////////////
public function get_phemlnext($phone,$email){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.phone',$phone);
	$this->db->where('user.email',$email);
	$this->db->where('user.status',0);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
//////////////// check phone number exist and inactive ////////////////////
public function get_phnexst($phone){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.phone',$phone);
	$this->db->where('user.status',0);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
////////////////////// check email id exist and inactive //////////////////
public function get_emailnexst($email){
	$this->db->select('user.*');
	$this->db->from('user');
	$this->db->where('user.email',$email);
	$this->db->where('user.status',0);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}

}
 