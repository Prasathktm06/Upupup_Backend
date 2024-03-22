<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Dashboard_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

//booking details of venue
public function get_booking($venue_id,$start_date,$end_date){
	  $this->db->select('venue_booking.booking_id,venue_booking.cost,venue_booking.payment_id,venue_booking.date,venue_booking_time.court_time,court.court,sports.image');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where('venue_booking.date >=',$start_date);
		$this->db->where('venue_booking.date <=',$end_date);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
                $this->db->order_by('venue_booking.date','asc');
		return $this->db->get()->result();
	}
//venue booked from upupup
public function get_upupupbooking($venue_id,$start_date,$end_date,$vendor){
	$this->db->select('venue_booking.booking_id,venue_booking.cost,venue_booking.payment_id,venue_booking.date,venue_booking_time.court_time,court.court,sports.image');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where('venue_booking.date >=',$start_date);
		$this->db->where('venue_booking.date <=',$end_date);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
		$this->db->where("venue_booking.payment_id !=",$vendor);
		$this->db->order_by('venue_booking.date','asc');
		return $this->db->get()->result();
	}
//venue booked from vendor
public function get_vendorbooking($venue_id,$start_date,$end_date,$vendor){
	$this->db->select('venue_booking.booking_id,venue_booking.cost,venue_booking.payment_id,venue_booking.date,venue_booking_time.court_time,court.court,sports.image');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where('venue_booking.date >=',$start_date);
		$this->db->where('venue_booking.date <=',$end_date);
		$this->db->where("venue_booking.payment_mode",1);
		$this->db->where("venue_booking.payment_id",$vendor);
		$this->db->order_by('venue_booking.date','asc');
		return $this->db->get()->result();
	}
//booking details for court manager
public function get_cmbooking($venue_id,$start_date,$end_date,$user_id){
		$this->db->select('venue_booking.booking_id,venue_booking.cost,venue_booking.payment_id,venue_booking.date,venue_booking_time.court_time,court.court,sports.image');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('court_manager_courts','court_manager_courts.court_id=court.id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("court_manager_courts.user_id",$user_id);
		$this->db->where('venue_booking.date >=',$start_date);
		$this->db->where('venue_booking.date <=',$end_date);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
		$this->db->order_by('venue_booking.date','asc');
		return $this->db->get()->result();
	}
//venue booked from upupup for court manager
public function get_cmupupupbooking($venue_id,$start_date,$end_date,$vendor,$user_id){
		$this->db->select('venue_booking.booking_id,venue_booking.cost,venue_booking.payment_id,venue_booking.date,venue_booking_time.court_time,court.court,sports.image');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('court_manager_courts','court_manager_courts.court_id=court.id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("court_manager_courts.user_id",$user_id);
		$this->db->where('venue_booking.date >=',$start_date);
		$this->db->where('venue_booking.date <=',$end_date);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
		$this->db->where("venue_booking.payment_id !=",$vendor);
		$this->db->order_by('venue_booking.date','asc');
		return $this->db->get()->result();
	}
//venue booked from vendor for court manager
public function get_cmvendorbooking($venue_id,$start_date,$end_date,$vendor,$user_id){
		$this->db->select('venue_booking.booking_id,venue_booking.cost,venue_booking.payment_id,venue_booking.date,venue_booking_time.court_time,court.court,sports.image');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('court_manager_courts','court_manager_courts.court_id=court.id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("court_manager_courts.user_id",$user_id);
		$this->db->where('venue_booking.date >=',$start_date);
		$this->db->where('venue_booking.date <=',$end_date);
		$this->db->where("venue_booking.payment_mode",1);
		$this->db->where("venue_booking.payment_id",$vendor);
		$this->db->order_by('venue_booking.date','asc');
		return $this->db->get()->result();
	}
//venue booked from vendor
public function get_bookingdetails($booking_id){
		$this->db->select('venue.venue,users.phone_no,sports.image,court.court,area.area,venue_booking.booking_id,(venue_booking.price - venue_booking.offer_value) as cost,venue_booking.date,venue_booking.time as added_time,venue_booking_time.court_time,venue_booking_time.capacity,venue_booking.payment_id');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->join('users','users.id=venue_booking.user_id');
		$this->db->join('venue','venue.id=venue_booking.venue_id');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->where("venue_booking.booking_id",$booking_id);
		return $this->db->get()->result();
	}
public function delete_booking($booking_id){
		$this->db->delete('venue_booking', array('booking_id' => $booking_id));
		return ($this->db->affected_rows() == 1);
	}
public function delete_bookingtime($booking_id){
		$this->db->delete('venue_booking_time', array('booking_id' => $booking_id));
		return ($this->db->affected_rows() == 1);
	}
public function delete_venueplayers($booking_id){
		$this->db->delete('venue_players', array('booking_id' => $booking_id));
		return ($this->db->affected_rows() == 1);
	}
public function delete_bookingoffer($booking_id){
		$this->db->delete('booking_offer', array('booking_id' => $booking_id));
		return ($this->db->affected_rows() == 1);
	}
//cancel booking
public function add_cancelbooking($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
//change status of booking for cancel
public function update_cancelbook($payment_mode,$booking_id){
	  $this->db->update('venue_booking', array('payment_mode'=>$payment_mode), array('booking_id' => $booking_id));
		if($this->db->affected_rows()==1)
			return true;
		else
			return false;
	}
	public function get_managerrole($user_id){
	$this->db->select("role.name as name");
	$this->db->from("user_role");
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('user_id',$user_id);
	return $this->db->get()->result();
	}
	public function get_managername($user_id){
	$this->db->select("name,phone");
	$this->db->from("user");
	$this->db->where('user_id',$user_id);
	return $this->db->get()->result();
	}
//select upupup booking email
public function get_upupupemail(){
        $this->db->select('email');
		$this->db->from('upupup_email');
		$this->db->where("booking",1);
		return $this->db->get()->result();
	}
//venue booked from vendor
public function get_bookdata($booking_id){
		$this->db->select('venue.venue,venue.id as venue_id,users.phone_no,users.name as user_name,users.email as user_email,sports.sports,court.court,area.area,venue_booking.booking_id,venue_booking.cost,venue_booking.date,venue_booking.time as added_time,venue_booking_time.court_time,venue_booking_time.capacity,venue_booking.payment_id');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		$this->db->join('court','court.id=venue_booking.court_id');
		$this->db->join('sports','sports.id=venue_booking.sports_id');
		$this->db->join('users','users.id=venue_booking.user_id');
		$this->db->join('venue','venue.id=venue_booking.venue_id');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->where("venue_booking.booking_id",$booking_id);
		return $this->db->get()->result();
	}
public function get_ownerdata($venue_id){
	$this->db->select('user.name,user.email,user.phone');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('venue_manager.venue_id',$venue_id);
	$this->db->where('role.venue_users',3);
	return $this->db->get()->result();
	}
//check vendor user staus is active where vendor_id and vendor_phone are same
public function get_vendorusers($manager_id,$vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$manager_id);
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",1);
        return $this->db->get()->result();
	}
//check vendor user staus is in-active where vendor_id and vendor_phone are same
public function get_vendorcheck($manager_id,$vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$manager_id);
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",0);
        return $this->db->get()->result();
	}

}
 