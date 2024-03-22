<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Myvenue_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
//fetch venues corresponding to user id 
public function get_venuelist($user_id){
	$this->db->select('venue.id,venue.venue,venue.book_status');
	$this->db->from('venue');
	$this->db->join('venue_manager','venue_manager.venue_id=venue.id');
	$this->db->where('venue_manager.user_id',$user_id);
	$this->db->where('venue.status',1);
	$query = $this->db->get();
    $result = $query->result_array();
    return $result;
	}
//sports id selection for managers
public function get_sportsdetails($venue_id){
    	$this->db->distinct('sports.id');
	$this->db->select('sports.id,sports.sports,sports.image');
	$this->db->from('sports');
	$this->db->join('court','court.sports_id=sports.id');
	$this->db->join('venue_court','venue_court.court_id=court.id');
	$this->db->where('sports.status',1);
	$this->db->where('venue_court.venue_id',$venue_id);
	$this->db->order_by('sports.id','asc');
	return $this->db->get()->result();
	}
//sports  selection for court manager
public function get_sportslist($user_id){
    	$this->db->distinct('sports.id');
	$this->db->select('sports.id,sports.sports,sports.image');
	$this->db->from('sports');
	$this->db->join('court','court.sports_id=sports.id');
	$this->db->join('court_manager_courts','court_manager_courts.court_id=court.id');
	$this->db->where('sports.status',1);
	$this->db->where('court_manager_courts.user_id',$user_id);
	$this->db->order_by('sports.id','asc');
	return $this->db->get()->result();
	}
//court details of owner and venue manager
public function get_courtlist($venue_id,$sports_id){
	$this->db->select('court.id as court_id,court.court,court.cost');
	$this->db->from('court');
	$this->db->join('venue_court','venue_court.court_id=court.id');
	$this->db->where('court.status',1);
	$this->db->where('court.sports_id',$sports_id);
	$this->db->where('venue_court.venue_id',$venue_id);
	$this->db->order_by('court.id','asc');
	return $this->db->get()->result();
	}
//court details for court manager
public function get_courtlists($user_id,$sports_id){
	$this->db->select('court.id as court_id,court.court,court.cost');
	$this->db->from('court');
	$this->db->join('court_manager_courts','court_manager_courts.court_id=court.id');
	$this->db->where('court.status',1);
	$this->db->where('court.sports_id',$sports_id);
	$this->db->where('court_manager_courts.user_id',$user_id);
	$this->db->order_by('court.id','asc');
	return $this->db->get()->result();
	}

//all slot values of upupup time and venue time
public function get_slot($court_id,$nameOfDay){
	    date_default_timezone_set("Asia/Kolkata");
		$this->db->select('court.cost,court.capacity,court_time_intervel.id as slot_id,court_time_intervel.time');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
                $this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
		$this->db->where('court_time_intervel.time >=',date('H:i:s', time()));
                $this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();
                
               
	}
//all slot values of upupup time and venue time
public function get_slots($court_id,$nameOfDay){
	    date_default_timezone_set("Asia/Kolkata");
		$this->db->select('court.cost,court.capacity,court_time_intervel.id as slot_id,court_time_intervel.time');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
                $this->db->where("court_time_intervel.date",1);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court.status",1);
                $this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();
	}

//all slot values of upupup time 
public function get_upupupslot($court_id,$nameOfDay){
		$this->db->select('court.cost,court.capacity,court_time_intervel.id as slot_id,court_time_intervel.time');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time.slotfor",0);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court.status",1);
                $this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();
	}

//all slot opened for upupup for one day 
public function get_openslot($court_id,$nameOfDay,$date){
		$this->db->select('court.cost,court.capacity,court_time_intervel.id as slot_id,court_time_intervel.time');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time.slotfor",0);
		$this->db->where("court_time_intervel.court_id",$court_id);
                $this->db->where("court_time_intervel.date",$date);
		$this->db->where("court.status",1);
                $this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();

	}

//all slot values of  venue time
public function get_venueslot($court_id,$nameOfDay){
		$this->db->select('court.cost,court.capacity,court_time_intervel.id as slot_id,court_time_intervel.time');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time.slotfor",1);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court.status",1);
                $this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();
	}

//all slot values of  member time
public function get_memberslot($court_id,$nameOfDay){
		$this->db->select('court.cost,court.capacity,court_time_intervel.id as slot_id,court_time_intervel.time');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time.slotfor",2);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court.status",1);
                $this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();
	}

//court offer details on selected date
public function get_offer($court_id,$date,$nameOfDay){
                $this->db->distinct('offer.id');
		$this->db->select('offer.id,offer.offer,offer.start_time,offer.end_time,offer.amount,offer.percentage');
		$this->db->from('offer');
		$this->db->join('offer_court','offer_court.offer_id=offer.id');
		$this->db->join('offer_time','offer_time.offer_id=offer_court.offer_id');
		$this->db->where("offer_court.court_id",$court_id);
		$this->db->where("offer_time.day",$nameOfDay);
		$this->db->where('offer.start <=',$date);
		$this->db->where('offer.end >=',$date);
		$this->db->where("offer.status",1);
		return $this->db->get()->result();
	}
///court upupup booking details on selected date
public function get_upupupbooking($venue_id,$sports_id,$court_id,$date,$vendor){
		$this->db->select('venue_booking_time.court_time,venue_booking_time.capacity');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
		$this->db->where("venue_booking.payment_id !=",$vendor);
        $this->db->group_by('venue_booking_time.id');
		$query1=$this->db->get()->result();
        $this->db->select('court_time,capacity');
		$this->db->from('court_book');
		$this->db->where('date',$date);
		$this->db->where('court_id',$court_id);
		$query2=$this->db->get()->result();
		if(!empty($query2)){
                //code if $query2 is not empty
                return array_merge($query1,$query2);
                }else{
                //code if $query2 is empty
                return $query1;
                }
		

	}
//court venue booking details on selected date
public function get_venuebooking($venue_id,$sports_id,$court_id,$date,$vendor){
		$this->db->select('venue_booking_time.booking_id,venue_booking_time.court_time,venue_booking_time.capacity');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where("venue_booking.payment_mode",1);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
		$this->db->where("venue_booking.payment_id",$vendor);
                $this->db->group_by('venue_booking_time.id');
		return $this->db->get()->result();
	}

//court inactive details on selected date
public function get_inactive($venue_id,$court_id,$date,$nameOfDay){
	        $this->db->distinct('inactive_court.id');
		$this->db->select('inactive_court.id,inactive_court.stime,inactive_court.etime,inactive_court.description');
		$this->db->from('inactive_court');
		$this->db->join('inactive_court_time','inactive_court_time.inactive_court_id=inactive_court.id');
		$this->db->where("inactive_court.venue_id",$venue_id);
		$this->db->where("inactive_court.court_id",$court_id);
		$this->db->where('inactive_court.sdate <=',$date);
		$this->db->where('inactive_court.edate >=',$date);
		$this->db->where("inactive_court_time.day",$nameOfDay);
		return $this->db->get()->result();
	}

//check any booking exist on that selected date
public function get_booking($venue_id,$sports_id,$court_id,$date,$time){
                $this->db->select('IFNULL(SUM(venue_booking_time.capacity),0)as capacity ');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->where('venue_booking.payment_mode !=',3);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
                $this->db->where("venue_booking_time.court_time",$time);
		return $this->db->get()->result();
	}
//check any booking data  exist in temp table on that selected date in 
public function get_tempbooking($court_id,$date,$time){
        	$this->db->select('sum(capacity) as tempsum');
		$this->db->from('court_book');
		$this->db->where('date',$date);
		$this->db->where('court_id',$court_id);
		$this->db->where('court_time',date('h:i A', strtotime($time)));
		return $this->db->get()->result();
	}
//check phone number exist in user table
public function get_usercheck($phone){
        $this->db->select('id');
		$this->db->from('users');
		$this->db->where("phone_no",$phone);
		$this->db->where("status",1);
		return $this->db->get()->result();
	}

//add new user from vendor app
public function add_user($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}

//area id corresponding to venue
public function get_area($venue_id){
                $this->db->select('area_id');
		$this->db->from('venue');
		$this->db->where("id",$venue_id);
		return $this->db->get()->result();
	}
//check user area count
public function get_userareacount($user_id){
                $this->db->select('count(user_area.area_id) as area_count ');
		$this->db->from('user_area');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where("user_area.user_id",$user_id);
		$this->db->where('area.status',1);
		return $this->db->get()->result();
	}
//check user location 
public function get_userareas($user_id){
        $this->db->select('area.location_id as user_location ');
		$this->db->from('user_area');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where("user_area.user_id",$user_id);
		$this->db->where('area.status',1);
		return $this->db->get()->result();
	}
//check user location 
public function get_arealoc($area_id){
        $this->db->select('area.location_id as user_locations ');
		$this->db->from('area');
		$this->db->where("area.id",$area_id );
		$this->db->where('area.status',1);
		return $this->db->get()->result();
	}
//check user sports count
public function get_usersportscount($user_id){
                $this->db->select('count(user_sports.sports_id) as sports_count ');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		$this->db->where('user_sports.user_id',$user_id);
		$this->db->where('sports.status',1);
		return $this->db->get()->result();
	}

//check any booking exist on that selected date
public function get_bookings($venue_id,$sports_id,$court_id,$date,$court_time){
                $this->db->select('venue_booking_time.booking_id');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where("venue_booking.payment_mode",1);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
                $this->db->where("venue_booking_time.court_time",$court_time);
		return $this->db->get()->result();
	}
//select sports name
public function get_sports($sports_id){
                $this->db->select('sports');
		$this->db->from('sports');
		$this->db->where("id",$sports_id);
		return $this->db->get()->result();
	}

//select court name
public function get_court($court_id){
                $this->db->select('court');
		$this->db->from('court');
		$this->db->where("id",$court_id);
		return $this->db->get()->result();
	}

//select venue name
public function get_venue($venue_id){
                $this->db->select('venue,area_id,phone');
		$this->db->from('venue');
		$this->db->where("id",$venue_id);
		return $this->db->get()->result();
	}
//select venue area name
public function get_venuearea($ar_id){
                $this->db->select('area');
		$this->db->from('area');
		$this->db->where("id",$ar_id);
		return $this->db->get()->result();
	}
//select venue area name
public function get_upupupsms(){
                $this->db->select('phone');
		$this->db->from('upupup_phone');
		$this->db->where("booking",1);
		return $this->db->get()->result();
	}

//select user email
public function get_useremail($user_id){
                $this->db->select('email');
		$this->db->from('users');
		$this->db->where("id",$user_id);
		return $this->db->get()->result();
	}
//select venue image
public function get_venueimage($venue_id){
        $this->db->select('image');
		$this->db->from('venue');
		$this->db->where("id",$venue_id);
		return $this->db->get()->result();
	}

//select offer name
public function get_offername($offer){
        $this->db->select('offer');
		$this->db->from('offer');
		$this->db->where("id",$offer);
		return $this->db->get()->result();
	}
//select venue managers mail id
public function get_vendoremail($venue_id){
        $this->db->select('email');
		$this->db->from('user');
		$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
		$this->db->where("venue_manager.venue_id",$venue_id);
		return $this->db->get()->result();
	}
//select venue owner phone
public function get_venueownerph($venue_id){
        $this->db->select('user.phone');
		$this->db->from('user');
    	$this->db->join('user_role','user_role.user_id=user.user_id');
    	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
    	$this->db->join('role','role.role_id=user_role.role_id');
    	$this->db->where('venue_manager.venue_id',$venue_id);
    	$this->db->where('role.venue_users',3);
    	$this->db->where('user.status',1);
		$this->db->limit(1);
		return $this->db->get()->result();
	}
//select upupup booking email
public function get_upupupemail(){
        $this->db->select('email');
		$this->db->from('upupup_email');
		$this->db->where("booking",1);
		return $this->db->get()->result();
	}

//add booking from vendor app
public function add_booking($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}

//insert open slot time 
public function add_time($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
public function delete_slot_time($slot_id)
	{
		$this->db->delete('court_time_intervel',array('id'=>$slot_id));
	}
//booked manager details
public function get_databookmg($manager_id){
        $this->db->select('user.name,user.phone,role.name as role_name');
		$this->db->from('user');
		$this->db->join('user_role','user_role.user_id=user.user_id');
		$this->db->join('role','role.role_id=user_role.role_id');
		$this->db->where("user.user_id",$manager_id);
		return $this->db->get()->result();
	}
////////////////////////////////////////////////////////////////////////
	public function users_bookdata($user_id)
	{
		$this->db->select('users.device_id,users.id,users.phone_no,users.name,users.email');
		$this->db->from('users');
		$this->db->where('users.id',$user_id);
		return $this->db->get()->row_array();
	}
/////////////////// user details of booking /////////////////////////////////////

public function get_userdetails($user_id){
        $this->db->select('name');
		$this->db->from('users');
		$this->db->where("id",$user_id);
		return $this->db->get()->result();
	}
/////////////////// owner details for slot open////////////////////////////////
public function get_ownerdata($venue_id){
        $this->db->select('name,email,phone');
		$this->db->from('user');
		$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
		$this->db->join('user_role','user_role.user_id=venue_manager.user_id');
		$this->db->where("venue_manager.venue_id",$venue_id);
		$this->db->where("user_role.role_id",28);
		return $this->db->get()->result();
	}
/////////////////// slot opened vendor app user details /////////////////////////////////////

public function get_managerdata($manager_id){
		$this->db->select('user.name,user.email,user.phone,role.name as role_name');
		$this->db->from('user');
		$this->db->join('user_role','user_role.user_id=user.user_id');
		$this->db->join('role','role.role_id=user_role.role_id');
		$this->db->where("user.user_id",$manager_id);
		return $this->db->get()->result();
	}
/////////////////// venue details  /////////////////////////////////////

public function get_venuedetails($venue_id){
        $this->db->select('venue');
		$this->db->from('venue');
		$this->db->where("id",$venue_id);
		return $this->db->get()->result();
	}
/////////////////// court details  /////////////////////////////////////

public function get_courtdetails($court_id){
        $this->db->select('court.court,sports.sports');
		$this->db->from('court');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->where("court.id",$court_id);
		return $this->db->get()->result();
	}
	
/////////////////// Unblock slot email for upupup  /////////////////////////////////////
public function get_upunbkemail(){
        $this->db->select('email');
		$this->db->from('upupup_email');
		$this->db->where("unblock",1);
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
///////////////////////// court details based on venue_id & sports id  ////////////////////////////
	public function get_my_courts($venue_id,$sports_id){
		$this->db->select('court.id as court_id,court.court,court.cost');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->join('sports','sports.id=court.sports_id');
    		$this->db->where('venue_court.venue_id',$venue_id);
    		$this->db->where('court.sports_id',$sports_id);
		$this->db->where('court.status',1);
		$this->db->order_by('court.id','asc');
		return $this->db->get()->result();
	}
///////////////////////// sports details based on venue_id  ////////////////////////////
	public function get_my_sports($venue_id){
		$this->db->distinct('sports.id');
		$this->db->select('sports.id,sports.sports,sports.image');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->join('sports','sports.id=court.sports_id');
    		$this->db->where('venue_court.venue_id',$venue_id);
		$this->db->where('court.status',1);
		$this->db->order_by('sports.id','asc');
		return $this->db->get()->result();
	}
//////////////////// checking is holiday on court ////////////////////////////
	public function get_mycourt_holiday($court_id,$date){
		$this->db->select('holidays.id,holidays.date');
		$this->db->from('holidays');
		$this->db->join('venue','venue.id=holidays.venue_id');
		$this->db->join('venue_court','venue_court.venue_id=venue.id');
		$this->db->where('venue_court.court_id',$court_id);
    		$this->db->where("holidays.date =",$date);
		return $this->db->get()->result();
	}
////////////////// all slot details based on court id /////////////////// 
	public function get_myslot($court_id,$nameOfDay,$date){
	    	date_default_timezone_set("Asia/Kolkata");
		$this->db->select('court.id as courts_id,court_time_intervel.id as slot_id,court_time_intervel.time,court_time.slotfor,court.cost as court_cost');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
        	$this->db->where("court_time_intervel.date",1);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court.status",1);
		if($date==date('Y-m-d')){
			$this->db->where('court_time_intervel.time >=',date('H:i:s', time()));
		}
		$this->db->where("court_time.slotfor !=",2);
        	$this->db->order_by('court_time_intervel.time','asc');
		return $this->db->get()->result();
	}
///////////////////// single day slot open for upupup ////////////////////////
	public function get_mysingle_day($court_id,$nameOfDay,$date,$slot_time){
		$this->db->select('court_time_intervel.id as slot_id,court_time.slotfor');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",$nameOfDay);
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time.slotfor",0);
		$this->db->where("court_time_intervel.court_id",$court_id);
        	$this->db->where("court_time_intervel.date",$date);
        	$this->db->where("court_time_intervel.time",$slot_time);
		$this->db->where("court.status",1);
		return $this->db->get()->result();
	}
/////////////////// check any booking exist on that selected date //////////////
	public function get_mybookings($court_id,$slot_time,$date){
        	$this->db->select('IFNULL(SUM(venue_booking_time.capacity),0)as capacity ');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->where('venue_booking.payment_mode !=',3);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
        	$this->db->where("venue_booking_time.court_time",$slot_time);
		return $this->db->get()->result();
	}
//check any booking data  exist in temp table on that selected date in 
	public function get_mytemp_bookings($court_id,$slot_time,$date){
        	$this->db->select('IFNULL(SUM(capacity),0)as tempsum');
		$this->db->from('court_book');
		$this->db->where('date',$date);
		$this->db->where('court_id',$court_id);
		$this->db->where('court_time',date('h:i A', strtotime($slot_time)));
		return $this->db->get()->result();
	}
////////////////// court capacity  ///////////////////////////////
	public function get_mycourt_capacity($court_id){
		$this->db->select('court.capacity as total_capacity');
		$this->db->from('court');
		$this->db->where('court.status',1);
		$this->db->where('court.id',$court_id);
		return $this->db->get()->result();
	}
////////////////////// hot offer checking on slot /////////////////////////////
	public function get_myhot_slot($court_id,$slot_id,$date){
        	$this->db->select('hot_offer.id,hot_offer.precentage');
		$this->db->from('hot_offer');
		$this->db->join('hot_offer_court','hot_offer_court.hot_offer_id=hot_offer.id');
		$this->db->join('hot_offer_slot','hot_offer_slot.hot_offer_id=hot_offer.id');
		$this->db->where("hot_offer_court.court_id",$court_id);
		$this->db->where("hot_offer_slot.court_time_intervel_id",$slot_id);
		$this->db->where("hot_offer.date",$date);
		$this->db->where("hot_offer.status",1);
		return $this->db->get()->result();
	}
/////////////////////// upupup booking  count /////////////////////////////////
public function get_myupupup_bookings($court_id,$sports_id,$slot_time,$date,$vendor){
		$this->db->select('IFNULL(SUM(venue_booking_time.capacity),0)as upupup_cap');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
		$this->db->where("venue_booking.payment_id !=",$vendor);
		$this->db->where("venue_booking_time.court_time",$slot_time);
        	$this->db->group_by('venue_booking_time.id');
		$query1=$this->db->get()->result();
        	$this->db->select('court_time,capacity');
		$this->db->from('court_book');
		$this->db->where('date',$date);
		$this->db->where('court_id',$court_id);
		$this->db->where('court_time',$slot_time);
		$query2=$this->db->get()->result();
		if(!empty($query2)){
                //code if $query2 is not empty
                return array_merge($query1,$query2);
                }else{
                //code if $query2 is empty
                return $query1;
                }
		

	}
//////////////////////// venue booking count/////////////////////////////////
public function get_myvendor_bookings($court_id,$sports_id,$slot_time,$date,$vendor){
		$this->db->select('IFNULL(SUM(venue_booking_time.capacity),0)as vendor_cap');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where("venue_booking.payment_mode",1);
		$this->db->where("venue_booking.date",$date);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$date);
		$this->db->where("venue_booking.payment_id",$vendor);
		$this->db->where("venue_booking_time.court_time",$slot_time);
        	$this->db->group_by('venue_booking_time.id');
		return $this->db->get()->result();
	}
	
////////////////////////// normal offer  ///////////////////////////////////
public function get_mynormal_offer($court_id,$slot_time,$date,$dayname){
        	$this->db->distinct('offer.id');
		$this->db->select('offer.id,offer.offer,offer.amount,offer.percentage');
		$this->db->from('offer');
		$this->db->join('offer_court','offer_court.offer_id=offer.id');
		$this->db->join('offer_time','offer_time.offer_id=offer_court.offer_id');
		$this->db->where("offer_court.court_id",$court_id);
		$this->db->where("offer_time.day",$dayname);
		$this->db->where('offer.start <=',$date);
		$this->db->where('offer.end >=',$date);
		$this->db->where('offer_time.time =',$slot_time);
		//$this->db->where('offer.end_time >=',$slot_time);
		$this->db->where("offer.status",1);
		return $this->db->get()->result();
	}
///////////////////// inactive court /////////////////////////////
public function get_myinactive_court($court_id,$slot_time,$date,$dayname){
	    $this->db->distinct('inactive_court.id');
		$this->db->select('inactive_court.id');
		$this->db->from('inactive_court');
		$this->db->join('inactive_court_time','inactive_court_time.inactive_court_id=inactive_court.id');
		$this->db->where("inactive_court.court_id",$court_id);
		$this->db->where('inactive_court.sdate <=',$date);
		$this->db->where('inactive_court.edate >=',$date);
		$this->db->where('inactive_court.stime <=',$slot_time);
		$this->db->where('inactive_court.etime >=',$slot_time);
		$this->db->where("inactive_court_time.day",$dayname);
		return $this->db->get()->result();
	}

}
