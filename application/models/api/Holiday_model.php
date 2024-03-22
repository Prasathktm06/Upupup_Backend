<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Holiday_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
     
public function get_holiday($user_id){
	$this->db->select('venue.id,venue.venue,venue.book_status');
	$this->db->from('venue');
	$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
	$this->db->where('venue_manager.user_id',$user_id);
	$this->db->where("venue.status",1);
	return $this->db->get()->result();
	}
public function get_hotoffersetting($venue_id){
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

public function get_addedholiday($venue_id){
	$this->db->select('date');
	$this->db->from('holidays');
    $this->db->where("date >=",date('Y-m-d'));
	$this->db->where("venue_id",$venue_id);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}

public function get_holidaysavailabe($venue_id){
	$this->db->select('id,date,description');
	$this->db->from('holidays');
    $this->db->where("date >=",date('Y-m-d'));
	$this->db->where("venue_id",$venue_id);
    $this->db->order_by('date','asc');
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}

public function delete_holidays($id){
	$this->db->delete('holidays', array('id' => $id));
	return ($this->db->affected_rows() == 1);
	}

public function get_bookholiday($venue_id,$date){
	$this->db->select('id');
	$this->db->from('venue_booking');
    $this->db->where("payment_mode!=",2);
    $this->db->where("payment_mode!=",3);
    $this->db->where("date", $date);
    $this->db->where("venue_id",$venue_id);
	return $this->db->get()->result();
	}

public function insert_holiday($data){
	if($this->db->insert('holidays', $data))
	return  $this->db->insert_id();
	else
	return false;
	}
public function get_userrole($user_id){
	$this->db->select('role.name,user.phone,user.name as user_rname');
	$this->db->from('role');
	$this->db->join('user_role','user_role.role_id=role.role_id');
	$this->db->join('user','user.user_id=user_role.user_id');
	$this->db->where('user.user_id',$user_id);
	return $this->db->get()->result();
	}
public function get_venuename($venue_id){
	$this->db->select('venue');
	$this->db->from('venue');
	$this->db->where('id',$venue_id);
	return $this->db->get()->result();
	}
public function get_upupupemail(){
	$this->db->select('email');
	$this->db->from('upupup_email');
	$this->db->where('holiday',1);
	return $this->db->get()->result();
	}
public function get_owner($venue_id){
	$this->db->select('user.name,user.email');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('venue_manager.venue_id',$venue_id);
	$this->db->where('role.venue_users',3);
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
public function get_areaaname($venue_id){
	$this->db->select('area.area');
	$this->db->from('area');
	$this->db->join('venue','venue.area_id=area.id');
	$this->db->where('venue.id',$venue_id);
	return $this->db->get()->result();
	}

}
 