<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Login_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_users($phone){
		$this->db->select('users.*,otp.otp');
		$this->db->from('users');
		$this->db->join('otp','otp.user_id=users.id','left');
		$this->db->where('users.phone_no',$phone);
		return $this->db->get()->row();
	}
   
   
        public function get_vendormessage($user_id){
		$this->db->select("role.role_id,role.name as role,role.venue_users as role_level");
		$this->db->from("user_role");
		$this->db->join('role','role.role_id=user_role.role_id');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}
        public function get_member($user_id){
		$this->db->select('venue.id,venue.venue,venue.area_id,venue.book_status');
		$this->db->from('venue');
		$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
		$this->db->where('venue_manager.user_id',$user_id);
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}
         //sports id selection for inactive courts on based venue
         public function get_sports($venue_id){
    	  $this->db->distinct('sports.id');
		$this->db->select('sports.id,sports.sports,sports.image,sports.status');
		$this->db->from('sports');
		$this->db->join('court','court.sports_id=sports.id');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('sports.status',1);
		$this->db->where('venue_court.venue_id',$venue_id);
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}
    
        public function get_addholidays($venue_id,$date,$stime)
	{
		$this->db->select('id');
		$this->db->from('holidays');
                $this->db->where('date', $date);
		$this->db->where("venue_id",$venue_id);
		return $this->db->get()->result();

	}
    
	public function get_users2($phone)
	{
		$this->db->select('users.*,otp.otp');
		$this->db->from('users');
		$this->db->join('otp','otp.user_id=users.id'); 
		$this->db->where('users.phone_no',$phone);
		return $this->db->get()->row();
	}
	public function get_phone_no($user_id)
	{
		$this->db->select('phone_no');
		$this->db->from('users');
		
		$this->db->where('id',$user_id);
		return $this->db->get()->row();
	}
	public function add_users($data){
		if($this->db->insert('users', $data))
			return  $this->db->insert_id();
			else
				return false;
	}
	public function insert_auth($data){
		if($this->db->insert('auth', $data))
			return  $this->db->insert_id();
			else
				return false;
	}
	public function add_otp($data){
		if($this->db->insert('otp', $data))
			return  $this->db->insert_id();
			else
				return false;
	}
	public function otp_verify($user_id,$otp){
		$this->db->select("id");
		$this->db->from("otp");
		$this->db->where('user_id',$user_id);
		$this->db->where('otp',$otp);
		return $this->db->get()->result();
	}
	
	public function update_otp($otp,$user){
	  $this->db->update('otp', array('otp'=>$otp), array('user_id' => $user));
		if($this->db->affected_rows()==1)
			return true;
		else
			return false;
	}
	public function get_auth($id)
	{
		$this->db->select("id");
		$this->db->from("auth");
		$this->db->where('user_id',$id);
		
		return $this->db->get()->result();
	}

	public function update_user($user,$data)
	{
		return $this->db->update('users',$data,  array('id'=>$user));
	}
	public function update_auth($facebook_id,$user){
		  $this->db->update('auth', array('facebook_id'=>$facebook_id), array('user_id' => $user));
		  return ($this->db->affected_rows() > 0);

	}

	public function get_user_area($id)
	{
		$this->db->select("user_area.area_id,area.location_id,locations.location");
		$this->db->from("user_area");
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		$this->db->where('user_id',$id);
		return $this->db->get()->result();
	}
    public function get_user_sport($id)
	{
		$this->db->select('sports.sports,sports.id,sports.image');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		$this->db->where('user_sports.user_id',$id);
		$this->db->where('sports.status',1);

		return $this->db->get()->result();
	}
	public function update_device_id($data,$phone)
	{
		return $this->db->update('users',$data,  array('phone_no'=>$phone));
	}

	public function get_upupmsgemail(){
	$this->db->select('email');
	$this->db->from('upupup_email');
	$this->db->where('message',1);
	return $this->db->get()->result();
	}
public function get_otp_check($id)
	{
		$this->db->select("id");
		$this->db->from("otp");
		$this->db->where('user_id',$id);
		return $this->db->get()->result();
	}
	//check vendor user staus is active
public function get_vendorusers($user_id,$phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$user_id);
        $this->db->where("phone",$phone);
        $this->db->where("status",1);
        return $this->db->get()->result();
	}
//check vendor user staus is in-active
public function get_vendorcheck($user_id,$phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$user_id);
        $this->db->where("phone",$phone);
        $this->db->where("status",0);
        return $this->db->get()->result();
	}

	public function get_details($table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}


}