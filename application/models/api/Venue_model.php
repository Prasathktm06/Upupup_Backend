<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Venue_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_venue_old($user_id,$venue='',$sports,$area,$location){
		
		$this->db->select('venue.venue,venue.id,venue.image,venue.description,venue.morning,venue.evening,venue.cost,venue.phone,venue.lat,venue.lon,area.area,GROUP_CONCAT(DISTINCT facilities.facility) as facility ,venue.book_status,venue.amount,venue.amount_type, offer.percentage,max(offer.percentage) as max,GROUP_CONCAT(DISTINCT sports.sports) as venue_sports,GROUP_CONCAT(DISTINCT sports.id) as venue_sports_id,GROUP_CONCAT(DISTINCT sports.image) as venue_sports_image,GROUP_CONCAT(DISTINCT court.id) as court_id');
		$this->db->from('venue');
		if($area)
			$this->db->join('user_area','user_area.area_id=venue.area_id');


		$this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
		if($sports)
			$this->db->join('user_sports','user_sports.sports_id=venue_sports.sports_id','left');
		$this->db->join('venue_facilities','venue_facilities.venue_id=venue.id','left');
		$this->db->join('facilities','facilities.id=venue_facilities.facility_id','left');

		$this->db->join('area','area.id=venue.area_id','left');
		$this->db->join('locations','locations.id=area.location_id','left');
		$this->db->join('venue_gallery','venue_gallery.venue_id=venue.id','left');
		$this->db->join('venue_court','venue_court.venue_id=venue.id','left');
		$this->db->join('court','court.id=venue_court.court_id','left');
		$this->db->join('sports','sports.id=venue_sports.sports_id','left');
		$this->db->join('offer_court','offer_court.court_id=court.id','left');
		$this->db->join('offer','offer.id=offer_court.offer_id','left');
		$this->db->group_by('venue.id');
       	if($user_id!='0'){
			if($area)
				$this->db->where('user_area.user_id',$user_id);
			if($sports)
				$this->db->where('user_sports.user_id',$user_id);
       	}
       	if($location){
       		$this->db->where('locations.id',$location);
       		$this->db->where('locations.status',1);
       	}
		$this->db->where('venue.status',1);
		$this->db->where('sports.status',1);
		$this->db->where('locations.status',1);
		$this->db->where('area.status',1);
		$this->db->order_by('venue.venue','asc');

		if($venue!=''){
			$this->db->where('venue.id',$venue);
		}
		return $this->db->get()->result();
	}

	public function get_venue($user_id,$venue='',$sports,$area,$location){
	    
	    $subQueryUserSports = "SELECT sports_id FROM user_sports where user_id='$user_id'";
	    $subQueryUserArea = "SELECT area_id FROM user_area where user_id='$user_id'";
	    
		$this->db->select('venue.venue,venue.id,venue.image,venue.description,venue.morning,venue.evening,venue.cost,venue.phone,venue.lat,venue.lon,venue.book_status,venue.amount,venue.amount_type,area.area,GROUP_CONCAT(DISTINCT facilities.facility) as facility,max(offer.percentage) as max,GROUP_CONCAT(DISTINCT sports.sports) as venue_sports,GROUP_CONCAT(DISTINCT sports.id) as venue_sports_id,GROUP_CONCAT(DISTINCT sports.image) as venue_sports_image,GROUP_CONCAT(DISTINCT court.id) as court_id');
	    $this->db->from('venue');
		$this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
		$this->db->join('venue_facilities','venue_facilities.venue_id=venue.id','left');
		$this->db->join('facilities','facilities.id=venue_facilities.facility_id','left');
        $this->db->join('area','area.id=venue.area_id','left');
		$this->db->join('locations','locations.id=area.location_id','left');
		$this->db->join('venue_gallery','venue_gallery.venue_id=venue.id','left');
		$this->db->join('venue_court','venue_court.venue_id=venue.id','left');
		$this->db->join('court','court.id=venue_court.court_id','left');
		$this->db->join('sports','sports.id=venue_sports.sports_id','left');
		$this->db->join('offer_court','offer_court.court_id=court.id','left');
		$this->db->join('offer','offer.id=offer_court.offer_id','left');
		if($user_id!='0'){
		    if($sports)
		        $this->db->where("sports.id in ($subQueryUserSports)",NULL);
		    if($area)
		        $this->db->or_where("venue.area_id in ($subQueryUserArea)",NULL);
		}
		
		if($location){
			$this->db->where('locations.id',$location);
			$this->db->where('locations.status',1);
		}
		$this->db->where('venue.status',1);		
		$this->db->where('sports.status',1);
		$this->db->where('locations.status',1);
		$this->db->where('area.status',1);
		if($venue!=''){
			$this->db->where('venue.id',$venue);
		}
		$this->db->group_by('venue.id');
		$this->db->order_by('venue.venue','asc');
		return $this->db->get()->result();
	}

	public function court_status($court_id)
	{
		$this->db->select('court');
		$this->db->from('court');
		$this->db->where('id',$court_id);
		$this->db->where('status',1);
		return $this->db->get()->row();
	}


	public function rate($data){
		return $this->db->insert('rate_venue', $data);
	}
	public function get_court_time($court){

		$this->db->select('court.court,court.id,court_time.time');
		$this->db->from('court_time');
		$this->db->join('court','court.id=court_time.court_id','left');
		$this->db->where('court_time.court_id',$court);

		return $this->db->get()->result();
	}


	public function add_booking($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}

	public function get_venue_cost($id)
	{
		$this->db->select('cost');
		$this->db->from('court');
		$this->db->where('id',$id);
		return	$this->db->get()->row();
	}

	public function get_upcoming_venue_booking($user_id,$date)
	{

		 $this->db->select('venue.id,venue.venue,venue.lat as lat,venue.lon as lon,sports.sports,venue_booking.date,venue_booking.payment_mode,sports.image,area.area,venue_booking.transaction_id,venue_booking.time,venue_booking.payment_id,court.court,venue_booking.booking_id,venue_booking.cost,court.intervel,court.capacity as court_capacity,venue_booking_time.court_time,users.name,users.id as user_id,venue_booking.bal');
		 $this->db->from('venue_booking');
		 $this->db->join('venue','venue.id=venue_booking.venue_id','left');
		 $this->db->join('sports','sports.id=venue_booking.sports_id','left');
		  $this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id','left');
		   $this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');

		 $this->db->join('area','area.id=venue.area_id','left');
		  $this->db->join('users','users.id=venue_booking.user_id','left');
		  $this->db->join('court','court.id=venue_booking.court_id','left');
		  $this->db->where('venue_players.user_id',$user_id);
		 $this->db->where('venue_booking.date >=',$date);
		 $this->db->where('venue_booking.payment_mode !=',3);
		 $this->db->order_by('venue_booking.date','ASC');
		 $this->db->group_by('venue_booking.booking_id');
		return $this->db->get()->result();
	}
	public function get_past_venue_booking($user_id,$date)
	{
		 $this->db->select('venue_booking.booking_id as booking_id,venue.id as venue_id,venue.lat as lat,venue.lon as lon,venue.venue,sports.sports,venue_booking.date,venue_booking.payment_mode,sports.image,area.area,venue_booking.time,court.intervel,court.capacity as court_capacity,venue_booking.transaction_id,venue_booking.payment_id,venue_booking.cost,court.court,users.name,users.id as user_id,venue_booking.bal');
		  $this->db->from('venue_booking');
		 $this->db->join('venue','venue.id=venue_booking.venue_id','left');
		 $this->db->join('sports','sports.id=venue_booking.sports_id','left');
		  $this->db->join('users','users.id=venue_booking.user_id','left');
		  $this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id','left');
		 $this->db->join('area','area.id=venue.area_id','left');
		 $this->db->where('venue_players.user_id',$user_id);
		  $this->db->join('court','court.id=venue_booking.court_id','left');
		 $this->db->where('venue_booking.date <=',$date);
		 $this->db->where('venue_booking.payment_mode !=',3);
		 $this->db->group_by('venue_booking.booking_id');
		return $this->db->get()->result();
	}
	public function add_venue_players($table,$data)
	{
		if($this->db->insert($table,$data)){
			return true;
		}else{
			return false;
		}
	}

	public function get_venue_details($id='')
	{
		$this->db->select("venue.venue,venue.id,venue.image,venue.description,venue.morning,venue.evening,venue.cost,venue.phone,venue.lat,venue.lon,area.area,GROUP_CONCAT(DISTINCT facilities.facility) as facility, GROUP_CONCAT( DISTINCT sports.sports) as sports,GROUP_CONCAT( DISTINCT sports.id) as sports_id,GROUP_CONCAT( DISTINCT sports.image) as sports_image,GROUP_CONCAT( DISTINCT court.court) as court,GROUP_CONCAT( DISTINCT court.id) as court_id,
		 GROUP_CONCAT( DISTINCT court.sports_id) as court_sports");
		$this->db->from("venue");
		$this->db->join("venue_facilities","venue_facilities.venue_id=venue.id");
		$this->db->join("facilities","facilities.id=venue_facilities.facility_id");

		$this->db->join("venue_court","venue_court.venue_id=venue.id","left");
		$this->db->join("court","court.id=venue_court.court_id","left");

		$this->db->join("venue_sports","venue.id=venue_sports.venue_id");
		$this->db->join('sports','sports.id=venue_sports.sports_id');
		$this->db->join("area","area.id=venue.area_id");
		if($id!='')
		$this->db->where('venue.id',$id);
		return $this->db->get()->result();

	}

	public function get_court($id='')
	{
		$this->db->select('court,id,capacity');
		$this->db->from('court');
		if($id!='')
		$this->db->where('id',$id);
		return $this->db->get()->result();
	}

	public function get_venue_rating($id,$user_id='')
	{
		$this->db->select('GROUP_CONCAT(rating) as rating');
		$this->db->from('rate_venue');
		$this->db->where('venue_id',$id);
		if($user_id!='')
		$this->db->where('user_id',$user_id);
		$this->db->group_by('venue_id');
		return $this->db->get()->row();
	}
	public function get_venue_court($id)
	{
		$this->db->select('GROUP_CONCAT(court.court) as court,GROUP_CONCAT(court.id) as court_id,GROUP_CONCAT(court.cost) as court_cost,GROUP_CONCAT(court.sports_id) as sports_id,GROUP_CONCAT(sports.image) as image,GROUP_CONCAT(sports.sports) as sports');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id','left');
		$this->db->join('sports','sports.id=court.sports_id','left');

		$this->db->group_by('venue_court.venue_id');
		$this->db->where('venue_court.venue_id',$id);
		$this->db->where('court.status',1);
		return $this->db->get()->row();
	}

	public function get_booking_id($user_id,$venue_id,$booking_id)
	{
		$this->db->select('id');
		$this->db->from('rate_venue');
		//$this->db->join('venue_booking','venue_booking.booking_id=rate_venue.booking_id','left');
		$this->db->where('user_id',$user_id);
		$this->db->where('venue_id',$venue_id);
		//$this->db->where('venue_booking.date<',date("Y-m-d"));
		$this->db->where('booking_id',$booking_id);
		//$this->db->where('rating','');
		return $this->db->get()->result();
	}
	public function get_user_booking($venue_id,$user_id)
	{
		$this->db->select('id');
		$this->db->from('venue_booking');
		$this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id');
		$this->db->where('venue_booking.venue_id',$venue_id);
		$this->db->where('venue_booking.user_id',$user_id);

		return $this->db->get()->result();
	}
	public function get_venue_rating2($booking_id,$user_id)
	{
		$this->db->select('rating');
		$this->db->from('venue_players');
		$this->db->where('booking_id',$booking_id);
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}


	public function update_booking($data,$id)
	{
		return $this->db->update('venue_booking', $data, array('booking_id' => $id));
	}


	////////////////////////////////////////////////////////////////////////
	public function users_list($user_id)
	{
		$this->db->select('users.device_id,users.id,users.phone_no,users.name,users.email');
		$this->db->from('users');
		$this->db->where('users.id',$user_id);
		return $this->db->get()->row_array();
	}
	//////////////////////Holiday Or Not/////////////////////////////////
	public function is_holiday($conditon)
	{
		$this->db->select('*');
		$this->db->from('holidays');
		$this->db->where($conditon);
		return $this->db->get()->row_array();
	}
	///////////////////////Court Timing///////////////////////////////////
	public function court_timing($conditon)
	{
		$this->db->select('*');
		$this->db->from('court_time');
		$this->db->where($conditon);
		return $this->db->get()->result_array();
	}
	////////////////////////Court_intervel/////////////////////////////////
	public function court_intervel($conditon)
	{
		$this->db->select('*');
		$this->db->from('court');
		$this->db->where('id',$conditon);
		return $this->db->get()->row_array();
	}
	////////////////////////Court Booked/////////////////////////////////
	public function court_booked($court_id,$venue_id,$date)
	{
		$this->db->select('court_time');
		$this->db->from('venue_booking');
		$this->db->where('court_id',$court_id);
		$this->db->where('venue_id',$venue_id);
		$this->db->where('date(date)',$date);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['court_time'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	/////////////////////////////////Court Name////////////////////////////////////
	public function court_data($conditon)
	{
		$this->db->select('id,court');
		$this->db->from('court');
		$this->db->where('id',$conditon);
		return $this->db->get()->row_array();
	}
	///////////////////////////////////////////////////////////////////////


	public function venue_images($id){
		$this->db->select('*');
		$this->db->from('venue_gallery');
		$this->db->where('venue_gallery.venue_id',$id);

		$row1	= $this->db->get()->result_array();
		if ($row1) {
		foreach($row1 as $row)
		   {
		       $array[] = $row['image'];
		   }
		}else{
		$array=array();
		}

		return $array;
	}
	public function venue_sports_images($id)
	{
		$this->db->select('GROUP_CONCAT(sports.image) as venue_sports_image');
		$this->db->from('venue_sports');
		$this->db->join('sports','sports.id=venue_sports.sports_id','left');
		$this->db->where('venue_id',$id);
		return $this->db->get()->row();
	}
	/////////////////////////////////Court Offer////////////////////////////////
	public function court_offer($court_id,$date,$nameOfDay)
	{
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
		return $this->db->get()->result_array();
	}
        	/////////////////////////////////Court Inactive ////////////////////////////////
	public function court_inactive($court_id,$date,$nameOfDay)
	{
		$this->db->distinct('inactive_court.id');
		$this->db->select('inactive_court.id,inactive_court.sdate,inactive_court.edate,inactive_court.stime,inactive_court.etime,inactive_court.description');
		$this->db->from('inactive_court');
		$this->db->join('inactive_court_time','inactive_court_time.inactive_court_id=inactive_court.id');
		$this->db->where("inactive_court.court_id",$court_id);
		$this->db->where("inactive_court_time.day",$nameOfDay);
		$this->db->where('inactive_court.sdate <=',$date);
		$this->db->where('inactive_court.edate >=',$date);
		return $this->db->get()->result_array();
	}
         /////////////////////////////////Court Inactive Time ////////////////////////////////
        public function inactive_timing2($inactive_id,$time,$date)
{
		$this->db->select('inactive_court.stime,inactive_court.etime,inactive_court.description,inactive_court.id as inactive_court_id');
		$this->db->from('inactive_court');
		$this->db->join('inactive_court_time','inactive_court_time.inactive_court_id=inactive_court.id');
		foreach ($inactive_id as $key => $value) {
		$id[]=$value['id'];
		}
		$this->db->where_in('inactive_court.id', $id);
		$this->db->where('inactive_court.stime <=',date('H:i:s',strtotime($time['time'])));
		$this->db->where('inactive_court.etime >',date('H:i:s',strtotime($time['time'])));
		$this->db->where('inactive_court.sdate <=',$date);
		$this->db->where('inactive_court.edate >=',$date);
		return $this->db->get()->result();
}

	public function offer_timing($offer_id)
	{
		$this->db->select('offer_time.start_time,offer_time.end_time,offer.percentage');
		$this->db->from('offer_time');
		$this->db->join('offer','offer.id=offer_time.offer_id','left');
		$this->db->where('offer_time.offer_id',$offer_id);
		return $this->db->get()->result_array();
	}
	///////////////////////////////////////////////////////////////////////////


////////////////////////////courtTIme //////////////////JINSON//////////

public function court_time($court,$week,$date)
{               
                // upupup slots for date
	    $this->db->select('court.cost,court.capacity,court_time.week,court_time_intervel.time,court.court,court.id as id,court.intervel,court_time_intervel.id as slot_id');
		$this->db->from('court');
        $this->db->join('court_time','court_time.court_id=court.id');
        $this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court);
        $this->db->where("court_time.week",$week);
        $this->db->where("court_time.slotfor",0);
		$this->db->where("court.status",1);
        $this->db->where("court_time_intervel.date",1);
	    $query1=$this->db->get()->result();
                
                // vendors slots open for single day
	    $this->db->select('court.cost,court.capacity,court_time.week,court_time_intervel.time,court.court,court.id as id,court.intervel,court_time_intervel.id as slot_id');
		$this->db->from('court');
        $this->db->join('court_time','court_time.court_id=court.id');
        $this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court);
        $this->db->where("court_time.week",$week);
        $this->db->where("court_time.slotfor",0);
		$this->db->where("court.status",1);
        $this->db->where("court_time_intervel.date",$date);
	    $query2=$this->db->get()->result();
        if(!empty($query2)){
            //code if $query2 is not empty
            return array_merge($query1,$query2);
        }else{
            //code if $query2 is empty
            return $query1;
        }
 
}
public function offer_timing2($offer_id,$time,$date)
{
		$this->db->select('offer.start_time,offer.end_time,offer.percentage,offer.amount,offer.id as offer_id');
		$this->db->from('offer_time');
		$this->db->join('offer','offer.id=offer_time.offer_id','left');
		foreach ($offer_id as $key => $value) {
		$id[]=$value['id'];
		}
		$this->db->where_in('offer.id', $id);
		$this->db->where('offer.start_time <=',date('H:i:s',strtotime($time['time'])));
		$this->db->where('offer.end_time >',date('H:i:s',strtotime($time['time'])));
		$this->db->where('offer.start <=',$date);
		$this->db->where('offer.end >=',$date);
		$this->db->where('offer.status',1);
		return $this->db->get()->result();
}

	public function court_booked2($court_id,$time,$date,$capacity='')
	{
		$this->db->select('venue_booking_time.id,venue_booking_time.capacity,sum(venue_booking_time.capacity) as remaining_capacity');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id','left');
		$this->db->where('venue_booking_time.court_id',$court_id);
		$this->db->where('venue_booking_time.court_time', date('H:i:s', strtotime($time['time'])));
		$this->db->where('venue_booking_time.date',$date);
		$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->where('venue_booking.payment_mode !=',3);

		if($capacity!=''){
		$this->db->having("sum(venue_booking_time.capacity)=$capacity");
		$this->db->where('venue_booking_time.capacity <=',$capacity);
	}
		return $this->db->get()->result_array();
	}
	public function check_court_booked($court_id,$time,$date,$capacity)
	{
		$this->db->select('venue_booking_time.id,sum(venue_booking_time.capacity) as sum');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id','left');
		$this->db->where('venue_booking_time.court_id',$court_id);
		$this->db->where('venue_booking_time.court_time', date('H:i:s', strtotime($time['time'])));
		$this->db->where('venue_booking_time.date',$date);
		$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->where('venue_booking.payment_mode !=',3);
		//$this->db->having("sum(venue_booking_time.capacity)=$capacity");
		return $this->db->get()->result();
	}
	public function check_court_bookedcp($court_id,$court_time,$date)
	{
		$this->db->select('venue_booking_time.id,sum(venue_booking_time.capacity) as sum');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id','left');
		$this->db->where('venue_booking_time.court_id',$court_id);
		$this->db->where('venue_booking_time.court_time', date('H:i:s', strtotime($court_time)));
		$this->db->where('venue_booking_time.date',$date);
		$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->where('venue_booking.payment_mode !=',3);
		return $this->db->get()->result();
	}
	public function check_court_tempcp($court_time,$court_id,$date)
	{
		$this->db->select('sum(capacity) as tempsum');
	$this->db->from('court_book');
	$this->db->where('date',$date);
	$this->db->where('court_id',$court_id);
	$this->db->where('court_time',$court_time);
		return $this->db->get()->result();
	}

	public function is_holiday2($date,$venue)
	{
			$this->db->select('*');
			$this->db->from('holidays');
			$this->db->where('date',$date);
			$this->db->where('venue_id',$venue);
			return $this->db->get()->row_array();
	}

/////////////////////////////Used Coupon Insertion//////////////////////////
public function used_coupon_insert($insert_array){
$row =$this->db->insert('coupon_user',$insert_array);
return $row;
}
public function get_rating($booking_id,$user_id)
{
		$this->db->select('id');
		$this->db->from('rate_venue');
		$this->db->where('user_id',$user_id);
		$this->db->where('booking_id',$booking_id);
		return $this->db->get()->row_array();
}

public function venue_managers($venue_id)
{
	$this->db->select('user.email,user.name,user.phone');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('venue_manager.venue_id',$venue_id);
	$this->db->where('role.venue_users',3);
	$this->db->where('user.status',1);
	return $this->db->get()->result_array();
}
public function up_users_mail()
{
	$this->db->select('email');
	$this->db->from('upupup_email');
	$this->db->where('booking',1);
	return $this->db->get()->result();
}
public function up_users_phone()
{
	$this->db->select('phone');
	$this->db->from('upupup_phone');
	$this->db->where('booking',1);
	return $this->db->get()->result_array();
}
public function send_match_sms()
{
	$this->db->select('court_time,booking_id');
	$this->db->from('venue_booking');
	$this->db->where('date',date('Y-m-d '));

	return $this->db->get()->result();
}
public function get_users($booking_id)
{
	$this->db->select('users.phone_no');
	$this->db->from('venue_players');
	$this->db->join('users','venue_players.user_id=users.id','left');
	$this->db->where('booking_id',$booking_id);

	return $this->db->get()->result();
}

public function get_venue_booking_court_time($booking_id)
{
	$this->db->select('court_time,capacity');
	$this->db->from('venue_booking_time');
	//$this->db->join('users','venue_players.user_id=users.id','left');
	$this->db->where('booking_id',$booking_id);

	return $this->db->get()->result();
}

public function get_venue_booking($booking_id)
{
	$this->db->select('venue_booking.*,sports.sports,court.court,venue.venue,area.area,users.name,coupons.coupon_value,coupons.currency,coupons.coupon_code,venue.image,GROUP_CONCAT(offer.offer) as offer,court.intervel');
	$this->db->from('venue_booking');
	$this->db->join('sports','sports.id=venue_booking.sports_id','left');
	$this->db->join('court','court.id=venue_booking.court_id','left');
	$this->db->join('venue','venue.id=venue_booking.venue_id','left');
	$this->db->join('area','area.id=venue.area_id','left');
	$this->db->join('booking_offer','booking_offer.booking_id=venue_booking.booking_id','left');
	$this->db->join('offer','offer.id=booking_offer.offer_id','left');

	$this->db->join('coupons','coupons.coupon_id=venue_booking.coupon_id','left');
	$this->db->join('users','users.id=venue_booking.user_id','left');
	$this->db->where('venue_booking.booking_id',$booking_id);

	return $this->db->get()->row();
}
public function get_venue_court_timing($booking_id)
{
	$this->db->select('court_time');
	$this->db->from('venue_booking_time');
	$this->db->where('booking_id',$booking_id);
	return $this->db->get()->result_array();
}
public function get_venue_players($booking_id,$user_id)
{
	$this->db->select('user_id,users.name as coplayer');
	$this->db->from('venue_players');
	$this->db->where('booking_id',$booking_id);
	$this->db->where('user_id!=',$user_id);
	$this->db->join('users','users.id=venue_players.user_id','left');
	$this->db->where('users.status',1);
	return $this->db->get()->result_array();
}

public function add_court_book($data)
{
	if($this->db->insert('court_book', $data))
		return true;
		else
			return false;
}
public function delete_court_book($court_id,$court_time='',$date,$user_id='')
{
	$this->db->where('court_id',$court_id);
	if($court_time!='')
	$this->db->where('court_time',$court_time);
	$this->db->where('date',$date);
	if($user_id!='')
	$this->db->where('user_id',$user_id);
	$row=$this->db->delete('court_book');
	return $row;
}
public function get_court_book($court_time,$court_id,$date,$capacity)
{
	$this->db->select('id');
	$this->db->from('court_book');

	$this->db->where('date',$date);
	$this->db->where('court_id',$court_id);
	$this->db->where('court_time',$court_time);
	$this->db->having("sum(capacity)>=$capacity");
	return $this->db->get()->result();
}

public function get_coupon($coupon_id)
{
	$this->db->select('coupon_value,coupon_code,currency,percentage');
	$this->db->from('coupons');
	$this->db->where('coupon_id',$coupon_id);

	return $this->db->get()->row();
}
public function get_court_book_capacity($court_id,$court_time,$date)
{
	$this->db->select('sum(capacity) as capacity');
	$this->db->from('court_book');
	$this->db->where('date',$date);
	$this->db->where('court_id',$court_id);
	$this->db->where('court_time',$court_time);
	return $this->db->get()->row();
}

public function get_venue_offer($venue_id)
{
	$this->db->select('status,max(percentage) as percentage,max(amount) as offer_amount');
	$this->db->from('offer');
	$this->db->join('offer_court','offer_court.offer_id=offer.id','left');
	$this->db->join('venue_court','venue_court.court_id=offer_court.court_id','left');
	$this->db->where('status',1);
	$this->db->where('date(end)>=',date('Y-m-d'));
	$this->db->where('venue_court.venue_id',$venue_id);
	return $this->db->get()->row();
}
public function get_user_location($user_id){
  $this->db->select('area_id');
	$this->db->from('user_area');
	$this->db->where('user_id',$user_id);
	$area= $this->db->get()->row()->area_id;

    $this->db->select('location_id');
	$this->db->from('area');
	$this->db->where('id',$area);

	return $this->db->get()->row()->location_id;


}
//////////////// hot offer checking on slot//////////////////
////////////////////// hot offer checking on slot start /////////////////////////////
	public function get_hot_slot($court_id,$slot_id,$date){
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
/////////////////////hot offer checking on slot start /////////////////////////////
///////////////// user check start //////////////////////
public function get_usercheck($contact_number){
        $this->db->select('id');
		$this->db->from('users');
		$this->db->where("phone_no",$contact_number);
		return $this->db->get()->result();
	}
///////////////// user check end  //////////////////////
///////////// insert data on co-players start ///////////////

public function insert_coplayer($data)
	{
		if($this->db->insert('co_player', $data))
			return true;
			else
				return false;
	}
///////////// insert data on co-players end ///////////////
///////////////// user check start //////////////////////
public function get_coplayer_sports($sports_id,$co_player_id){
        $this->db->select('users.id');
		$this->db->from('users ');
		$this->db->join('user_sports','user_sports.user_id=users.id');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		$this->db->where('user_sports.user_id',$co_player_id);
		$this->db->where('user_sports.sports_id',$sports_id);
		return $this->db->get()->result();
	}
///////////////// user check end  //////////////////////
/////////////// venue location details start ////////////////
public function get_venue_location($booking_id){
  	$this->db->select('venue.lat,venue.lon');
	$this->db->from('venue');
	$this->db->join('venue_booking','venue_booking.venue_id=venue.id');
	$this->db->where('venue_booking.booking_id',$booking_id);
	return $this->db->get()->result();

}
////////////// venue location details end ////////////////
/////////////// check booking count of user start ////////////////
public function get_first_booking($user_id){
	$vendor="vendor";
  	$this->db->select('count(booking_id) as booking_count');
	$this->db->from('venue_booking');
	$this->db->where('payment_mode !=',2);
	$this->db->where('payment_mode !=',3);
	$this->db->where('payment_id !=',$vendor);
	$this->db->where('user_id',$user_id);
	return $this->db->get()->result();

}
/////////////// check booking count of user end ////////////////
////////////////////////// user location details ///////////////////////////////
 public function get_user_locationbok($user_id)
	{
		$this->db->distinct('area.location_id');
		$this->db->select("area.location_id");
		$this->db->from("user_area");
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
/////////////// check any active booking setting start ////////////////
public function get_check_book_setting($location_id){
  	$this->db->select('id,coin');
	$this->db->from('booking_bonus_setting');
	$this->db->where('location_id',$location_id);
	$this->db->where('status',1);
	return $this->db->get()->result();

}
/////////////// check any active booking setting end ////////////////
////////////////////////// my account details ///////////////////////////////
 public function get_my_account($user_id)
	{
		$this->db->select("up_coin,bonus_coin,total");
		$this->db->from("my_account");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////////////// update my account //////////////////////////////////
	public function update_my_account($data,$table,$user_id)
	{
		return $this->db->update($table, $data, array('users_id' => $user_id));
	}
/////////////// check already initial booking bonus applied start ////////////////
public function get_book_bonus_applied($user_id){
  	$this->db->select('id,users_id');
	$this->db->from('booking_bonus');
	$this->db->where('users_id',$user_id);
	return $this->db->get()->result();

}
/////////////// check already initial booking bonus applied end ////////////////
/////////////// check any referal booking bonus active start ////////////////
public function get_referal_booking_bonus_set($location_id){
  	$this->db->select('id,booking_bonus_coin');
	$this->db->from('refer_bonus_setting');
	$this->db->where('location_id',$location_id);
	$this->db->where('booking_bonus_status',1);
	return $this->db->get()->result();

}
/////////////// check any referal booking bonus active end ////////////////
/////////////// check booking user is a referal user start ////////////////
public function get_referal_details($user_id){
  	$this->db->select('id,users_id,referral_id');
	$this->db->from('refer_friend_bonus');
	$this->db->where('installed_user_id',$user_id);
	return $this->db->get()->result();

}
/////////////// check booking user is a referal user end ////////////////
/////////////// user exist from refered start ////////////////
public function get_user_referal_checks($refered_user_id,$user_id){
  	$this->db->select('id');
	$this->db->from('refer_friend_bonus');
	$this->db->where('installed_user_id',$user_id);
	$this->db->where('users_id',$refered_user_id);
	return $this->db->get()->result();

}
///////////////  user exist from refered end ////////////////
/////////////// check user and refered user have already get refered booking bonus start////////////////
public function get_booking_bonus_applied($refered_user_id,$user_id){
  	$this->db->select('id');
	$this->db->from('referal_booking_bonus');
	$this->db->where('booked_user_id',$user_id);
	$this->db->where('user_id',$refered_user_id);
	return $this->db->get()->result();

}
///////////////   check user and refered user have already get refered booking bonus end ////////////////
/////////////// check user and refered user have already get refered booking bonus start////////////////
public function get_payment_mode_details($booking_id){
  	$this->db->select('id,booking_id,payment_mode_id,rupee,coin');
	$this->db->from('booking_payment_mode');
	$this->db->where('booking_id',$booking_id);
	$this->db->where('payment_mode_id',2);
	return $this->db->get()->result();

}
///////////////   check user and refered user have already get refered booking bonus end ////////////////
/////////////// payment mode by cash booking details start////////////////
public function get_payment_mode_bycash($booking_id){
  	$this->db->select('id,booking_id,payment_mode_id,rupee,coin');
	$this->db->from('booking_payment_mode');
	$this->db->where('booking_id',$booking_id);
	$this->db->where('payment_mode_id',1);
	return $this->db->get()->result();

}
///////////////   payment mode by cash booking details end  ////////////////
/////////////// payment mode by cash and by wallet booking details start////////////////
public function get_payment_mode_mixed($booking_id){
  	$this->db->select('id,booking_id,payment_mode_id,rupee,coin');
	$this->db->from('booking_payment_mode');
	$this->db->where('booking_id',$booking_id);
	$this->db->where('payment_mode_id',3);
	return $this->db->get()->result();

}
///////////////   payment mode by cash and by wallet booking details end  ////////////////
////////////////////////// Booking Capacity count start///////////////////////////////
 public function get_booking_capacity_count($booking_id)
	{
		$this->db->select("id,capacity");
		$this->db->from("venue_booking_time");
		$this->db->where('booking_id',$booking_id);
		return $this->db->get()->result();
	}
////////////////////////// Booking Capacity count end ///////////////////////////////
///////////////////////get user check ///////////////////////////
	public function get_user_availability($user_id){

		$this->db->select('id');  
	    $this->db->from('users');
	    $this->db->where("status",1);
		$this->db->where("id",$user_id);
		return $this->db->get()->result();
	       
	}
/////////////// //////////////// booking service charge ////////////////////////
public function get_booking_service_charge($booking_id)
{
  	$this->db->select('total_service_charge');
	$this->db->from('service_charge_booking');
	$this->db->where('booking_id',$booking_id);
	return $this->db->get()->result();

}
}
