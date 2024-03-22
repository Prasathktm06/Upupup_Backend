<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Hotofferuser_slider_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get_venuecount(){
		$this->db->select('venue.*,area.*,locations.*,venue_court.*,court.*,offer_court.*,offer.*,offer.amount as off_amount');
		$this->db->from('venue');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		$this->db->join('venue_court','venue_court.venue_id=venue.id');
    		$this->db->join('court','court.id=venue_court.court_id');
    		$this->db->join('offer_court','offer_court.court_id=court.id');
    		$this->db->join('offer','offer.id=offer_court.offer_id');
    		$this->db->where('locations.id',19);
		$this->db->where('offer.end >=','2018-08-20');
		$this->db->where('venue.status',1);
		return $this->db->get()->result();
	}
/////////////////////// hot offer settings ///////////////////////////
	public function get_setting(){
		$this->db->select('hot_offer_setting.id,hot_offer_setting.name,locations.location,hot_offer_setting.dates,hot_offer_setting.life,hot_offer_setting.percentage,hot_offer_setting.status');
		$this->db->from('hot_offer_setting');
		$this->db->join('locations','locations.id=hot_offer_setting.location_id');
		$this->db->order_by('hot_offer_setting.id','desc');
		return $this->db->get()->result_array();
	}
///////////// court details based on venue id and sports id /////////////
	public function get_court($venue_id,$sports_id){
		$this->db->select('court.id,court.court,court.cost,court.intervel,court.capacity as total_capacity');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('court.status',1);
		$this->db->where('court.sports_id',$sports_id);
		$this->db->where('venue_court.venue_id',$venue_id);
		$this->db->order_by('court.id','asc');
		return $this->db->get()->result();
	}

////////////////// all slot details based on court id /////////////////// 
	public function get_slot($court_id,$nameOfDay,$date){
	    	date_default_timezone_set("Asia/Kolkata");
		$this->db->select('court.id as courts_id,court_time_intervel.id as slot_id,court_time_intervel.time,court_time.slotfor');
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
//////////////////////// checking is holiday ///////////////////////////////
	public function get_holidays($venue_id,$date){
		$this->db->select('id,date');
		$this->db->from('holidays');
    		$this->db->where("date =",$date);
		$this->db->where("venue_id",$venue_id);
		return $this->db->get()->result();
	}

///////////////////// single day slot open for upupup ////////////////////////
	public function get_single_day($court_id,$nameOfDay,$date,$slot_time){
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
//////////////////// check any temporary booking ////////////////////////////
	public function get_temp_booking($court_id,$date,$time){
        	$this->db->select('IFNULL(SUM(capacity),0)as tempsum');
		$this->db->from('court_book');
		$this->db->where('date',$date);
		$this->db->where('court_id',$court_id);
		$this->db->where('court_time',date('h:i A', strtotime($time)));
		return $this->db->get()->result();
	}
//////////////////// checking is holiday on court ////////////////////////////
	public function get_court_holiday($court_id,$date){
		$this->db->select('holidays.id,holidays.date');
		$this->db->from('holidays');
		$this->db->join('venue','venue.id=holidays.venue_id');
		$this->db->join('venue_court','venue_court.venue_id=venue.id');
		$this->db->where('venue_court.court_id',$court_id);
    		$this->db->where("holidays.date =",$date);
		return $this->db->get()->result();
	}
/////////////////// check any booking exist on that selected date //////////////
	public function get_bookings($court_id,$slot_time,$date){
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
	public function get_temp_bookings($court_id,$slot_time,$date){
        	$this->db->select('IFNULL(SUM(capacity),0)as tempsum');
		$this->db->from('court_book');
		$this->db->where('date',$date);
		$this->db->where('court_id',$court_id);
		$this->db->where('court_time',date('h:i A', strtotime($slot_time)));
		return $this->db->get()->result();
	}
////////////////// court capacity  ///////////////////////////////
	public function get_court_capacity($court_id){
		$this->db->select('court.capacity as total_capacity');
		$this->db->from('court');
		$this->db->where('court.status',1);
		$this->db->where('court.id',$court_id);
		return $this->db->get()->result();
	}
////////////////////// hot offer checking on slot /////////////////////////////
	public function get_hot_slot($court_id,$slot_id,$date){
        	$this->db->select('hot_offer.id,hot_offer.precentage');
		$this->db->from('hot_offer');
		$this->db->join('hot_offer_court','hot_offer_court.hot_offer_id=hot_offer.id');
		$this->db->join('hot_offer_slot','hot_offer_slot.hot_offer_id=hot_offer.id');
		$this->db->where("hot_offer_court.court_id",$court_id);
		$this->db->where("hot_offer_slot.court_time_intervel_id",$slot_id);
		$this->db->where("hot_offer.date",$date);
		return $this->db->get()->result();
	}
/////////////////////// check user is inactive  ///////////////////////////
	public function get_vendor_active($user_id){
	        $this->db->select('user_id');
	        $this->db->from('user');
	        $this->db->where("user_id",$user_id);
	        $this->db->where("status",0);
	        return $this->db->get()->result();
	}
/////////////////////// check user is exist  ///////////////////////////
	public function get_vendor_exist($user_id){
	        $this->db->select('user_id');
	        $this->db->from('user');
	        $this->db->where("user_id",$user_id);
	        return $this->db->get()->result();
	}
////////////////// add datas ////////////////////////////////
	public function add_datas($table,$data){
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
	/////////////////////// hot venue details ///////////////////////////
	
	
	
	public function get_hot_details_venue($user_id){
  
    	$today=date('Y-m-d');	
		$second= date('Y-m-d', strtotime($today. ' + 1 days'));
		$third= date('Y-m-d', strtotime($today. ' + 2 days'));
		 $this->db->select('
		   venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   user_area.id as user_area_id,
		    user_area.user_id as user_id,
			user_area.area_id as area_area_id,
			area.id as area_id,
			area.area as area,
		     hot_offer.date as hot_date,
		     hot_offer.id as hot_id
		   ');
		  
		  		$this->db->from('venue');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('hot_offer','hot_offer.venue_id=venue.id');
		    $this->db->where("user_area.user_id",$user_id);
			$this->db->where("hot_offer.date",$today);
			$this->db->group_by('venue.id','asc');
				$this->db->order_by('venue.id','asc');
		        $result1=  $this->db->get()->result();
		
	 $this->db->select('
		   venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   user_area.id as user_area_id,
		    user_area.user_id as user_id,
			user_area.area_id as area_area_id,
			area.id as area_id,
			area.area as area,
			 hot_offer.date as hot_date,
			   hot_offer.id as hot_id
		   ');
	//	   	$this->db->distinct('venue.id');
		  		$this->db->from('venue');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('hot_offer','hot_offer.venue_id=venue.id');
				$this->db->group_by('venue.id','asc');
				$this->db->order_by('venue.id','asc');
		    $this->db->where("user_area.user_id",$user_id);
			$this->db->where("hot_offer.date",$second);
			$result2= $this->db->get()->result();
		//	$data = array_unique (array_merge ($result1, $result2));
	$data = array_merge_recursive($result1, $result2);
		
		
		$this->db->select('
		   venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   user_area.id as user_area_id,
		    user_area.user_id as user_id,
			user_area.area_id as area_area_id,
			area.id as area_id,
			area.area as area,
			 hot_offer.date as hot_date,
			   hot_offer.id as hot_id
		   ');
		  // 	$this->db->distinct('venue.id');
		  		$this->db->from('venue');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('hot_offer','hot_offer.venue_id=venue.id');
			
		    $this->db->where("user_area.user_id",$user_id);
			$this->db->where("hot_offer.date",$third);
			$result3= $this->db->get()->result();
			
				$result = array_merge_recursive($data, $result3);
						//	$result = array_unique (array_merge ($data, $result3));
		
		
				return $result;
}
	
	/////////////////////// hot venue details ///////////////////////////
	
		/////////////////////// hot sports details details ///////////////////////////
	
	public function get_hot_sports_list($hot_id){
	
	//$hot_id=196;
		$this->db->distinct('sports.id');
	       $this->db->select('sports.id as sports_id,
	       sports.sports as sports_name,
	       sports.image as sports_image,
	        venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   hot_offer.id as hot_id
		   ,hot_offer.name as hot_name,
		   hot_offer.venue_id,
		   hot_offer.date as hot_date,
		   hot_offer.precentage as hot_percentage,
		   '
	       );
	       $this->db->from('sports');
	    $this->db->join('hot_offer_court','hot_offer_court.sports_id=sports.id');
		 $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
		 	$this->db->join('venue','hot_offer.venue_id=venue.id');
		 		
	        $this->db->where("hot_offer_court.hot_offer_id",$hot_id);
	        $this->db->order_by('hot_offer.precentage','asc');
	        return $this->db->get()->result();
	}
	
		///////////////////////  hot sports details details ///////////////////////////
	
	
	
	
/////////////////////// hot offer details ///////////////////////////
	public function get_hot_details($user_id){
		$today=date('Y-m-d');	
		$second= date('Y-m-d', strtotime($today. ' + 1 days'));
		$third= date('Y-m-d', strtotime($today. ' + 2 days'));

	       $this->db->select('hot_offer.id as hot_id
		   ,hot_offer.name as hot_name,
		   hot_offer.venue_id,
		   hot_offer.date as hot_date,
		   hot_offer.precentage as hot_percentage,
		   venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   user_area.id as user_area_id,
		    user_area.user_id as user_id,
			user_area.area_id as area_area_id,
			area.id as area_id,
			area.area as area
		   ');
		  
			
			$this->db->from('hot_offer');
			$this->db->join('venue','venue.id=hot_offer.venue_id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->where("user_area.user_id",$user_id);
			//   $this->db->where("status",1);
			$this->db->where("hot_offer.date",$today);
			$this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.id','asc');
			$this->db->order_by('hot_offer.precentage','asc');
			$this->db->limit(10);
			$result1= $this->db->get()->result();
			
			 $this->db->select('hot_offer.id as hot_id
		   ,hot_offer.name as hot_name,
		   hot_offer.venue_id,
		   hot_offer.date as hot_date,
		   hot_offer.precentage as hot_percentage,
		   venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   user_area.id as user_area_id,
		    user_area.user_id as user_id,
			user_area.area_id as area_area_id,
			area.id as area_id,
			area.area as area
		   ');
		  
	    $this->db->from('hot_offer');
			$this->db->join('venue','venue.id=hot_offer.venue_id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
	        $this->db->where("user_area.user_id",$user_id);
	//   $this->db->where("status",1);
			$this->db->where("hot_offer.date",$second);
			$this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.id','asc');
			$this->db->order_by('hot_offer.precentage','asc');
			$this->db->limit(10);
			
			$result2= $this->db->get()->result();
			
			
			$data = array_merge_recursive($result1, $result2);
			 $this->db->select('hot_offer.id as hot_id
		   ,hot_offer.name as hot_name,
		   hot_offer.venue_id,
		   hot_offer.date as hot_date,
		   hot_offer.precentage as hot_percentage,
		   venue.id as venue_id,
		   venue.venue as venue,
		   venue.morning as morning,
		   venue.evening as evening,
		   venue.area_id as venue_area_id,
		   venue.lat as lat,
		   venue.lon as lon,
		   venue.address as address,
		   user_area.id as user_area_id,
		    user_area.user_id as user_id,
			user_area.area_id as area_area_id,
			area.id as area_id,
			area.area as area
		   ');
		  
   
	    $this->db->from('hot_offer');
			$this->db->join('venue ','venue.id=hot_offer.venue_id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
	        $this->db->where("user_area.user_id",$user_id);
	//   $this->db->where("status",1);
			$this->db->where("hot_offer.date",$third);
			$this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.id','asc');
			$this->db->order_by('hot_offer.precentage','asc');
			$this->db->limit(10);
			
			$result3= $this->db->get()->result();
			
			$result = array_merge_recursive($data, $result3);
	        return $result;
	}
/////////////////////// hot offer sports details ///////////////////////////
///////////////////////get user details ///////////////////////////
	public function get_user_details($user_id){
	
	//$hot_id=196;
			$this->db->select('id');  
	        $this->db->from('users');
	      $this->db->where("status",1);
			$this->db->where("id",$user_id);
			$seeker_query = $this->db->get();
			if($seeker_query->num_rows() > 0)
			return $seeker_query->result_array();
			else 
			return array();
	       
	}
///////////////////////get user details ///////////////////////////

	public function get_hot_sports($hot_id){
	
	//$hot_id=196;
		$this->db->distinct('sports.id');
	       $this->db->select('sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
	       $this->db->from('sports');
	    $this->db->join('hot_offer_court','hot_offer_court.sports_id=sports.id');
		
	        $this->db->where("hot_offer_court.hot_offer_id",$hot_id);
	        return $this->db->get()->result();
	}
/////////////////////// hot offer court details ///////////////////////////
	public function get_hot_courts($hot_id){
	        $this->db->select('court.id as court_id,court.court as court_name,hot_offer_court.id as hot_offer_court_id');
	        $this->db->from('court');
	        $this->db->join('hot_offer_court','hot_offer_court.court_id=court.id');
	        $this->db->where("hot_offer_court.hot_offer_id",$hot_id);
	        return $this->db->get()->result();
	}
/////////////////////// hot offer slot details ///////////////////////////
	public function get_hot_slots($hot_offer_court_id){
	        $this->db->select('court_time_intervel.id as slot_id,
			court_time_intervel.time as slot_time,
			court_time.slotfor as slotfor,
			court.intervel as intervel
			');
	        $this->db->from('court_time');
	        $this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
	        $this->db->join('hot_offer_slot','hot_offer_slot.court_time_intervel_id=court_time_intervel.id');
			 $this->db->join('court','court_time_intervel.court_id=court.id');
	        $this->db->where("hot_offer_slot.hot_offer_court_id",$hot_offer_court_id);
	        $this->db->order_by('court_time_intervel.time','asc');
	        return $this->db->get()->result();
	}
/////////////////////// hot offer users ///////////////////////////
	public function get_hot_venue($hot_id){
	        $this->db->select('venue.id,venue.venue,venue.area_id');
	        $this->db->from('venue');
	        $this->db->join('hot_offer','hot_offer.venue_id=venue.id');
	        $this->db->where("hot_offer.id",$hot_id);
	        $this->db->where("venue.status",1);
	        return $this->db->get()->result();
	}
/////////////////////// hot offer users ///////////////////////////
	public function get_hot_users($area_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.id,users.name,users.phone_no,users.device_id');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->where("user_area.area_id",$area_id);
	        $this->db->where("users.status",1);
	        return $this->db->get()->result();
	}
/////////////////////// location id based on area id ///////////////////////////
	public function get_area_location($area_id){
	        $this->db->select('area.id,area.area,area.location_id');
	        $this->db->from('area');
	        $this->db->where("area.id",$area_id);
	        return $this->db->get()->result();
	}
/////////////////////// Notification setting based on location id ///////////////////////////
	public function get_not_setting($location_id){
	        $this->db->select('hot_offer_notification.id as hot_not_id,hot_offer_notification.hot_not_setting_id as setting_id');
	        $this->db->from('hot_offer_notification');
	        $this->db->where("hot_offer_notification.location_id",$location_id);
	        $this->db->where("hot_offer_notification.status",1);
	        return $this->db->get()->result();
	}
/////////////////////// hot offer details based on hot id ///////////////////////////
	public function get_hot_datas($hot_id){
		$this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer_court.sports_id,sports.sports,hot_offer.precentage');
	        $this->db->from('hot_offer');
	        $this->db->join('hot_offer_court','hot_offer_court.hot_offer_id=hot_offer.id');
	        $this->db->join('sports','sports.id=hot_offer_court.sports_id');
	        $this->db->where("hot_offer.id",$hot_id);
	        return $this->db->get()->result();
	}
/////////////////////// user details based on city ///////////////////////////
	public function get_city_user($location_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.device_id,users.id,users.phone_no');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->join('area','area.id=user_area.area_id');
	        $this->db->where("area.location_id",$location_id);
	        $this->db->where("users.status",1);
	        $this->db->order_by('users.id','asc');
	        return $this->db->get()->result_array();
	}
/////////////////////// user details based on city ///////////////////////////
	public function get_city_users($location_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.device_id,users.id,users.phone_no');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->join('area','area.id=user_area.area_id');
	        $this->db->where("area.location_id",$location_id);
	        $this->db->where("users.status",1);
	        $this->db->order_by('users.id','asc');
	        return $this->db->get()->result();
	}
/////////////////////// user details based on city and sports ///////////////////////////
	public function get_sports_user($location_id,$sports_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.device_id,users.id,users.phone_no');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->join('area','area.id=user_area.area_id');
	        $this->db->join('user_sports','user_sports.user_id=users.id');
	        $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.sports_id",$sports_id);
	        $this->db->where("users.status",1);
	        $this->db->order_by('users.id','asc');
	        return $this->db->get()->result_array();
	}
/////////////////////// user details based on city and sports ///////////////////////////
	public function get_sports_users($location_id,$sports_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.device_id,users.id,users.phone_no');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->join('area','area.id=user_area.area_id');
	        $this->db->join('user_sports','user_sports.user_id=users.id');
	        $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.sports_id",$sports_id);
	        $this->db->where("users.status",1);
	        $this->db->order_by('users.id','asc');
	        return $this->db->get()->result();
	}
/////////////////////// user details based on city and sports ///////////////////////////
	public function get_area_user($location_id,$sports_id,$area_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.device_id,users.id,users.phone_no');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->join('area','area.id=user_area.area_id');
	        $this->db->join('user_sports','user_sports.user_id=users.id');
	        $this->db->where("area.id",$area_id);
	        $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.sports_id",$sports_id);
	        $this->db->where("users.status",1);
	        $this->db->order_by('users.id','asc');
	        return $this->db->get()->result_array();
	}
/////////////////////// user details based on city and sports ///////////////////////////
	public function get_area_users($location_id,$sports_id,$area_id){
	        $this->db->distinct('users.id');
	        $this->db->select('users.device_id,users.id,users.phone_no');
	        $this->db->from('users');
	        $this->db->join('user_area','user_area.user_id=users.id');
	        $this->db->join('area','area.id=user_area.area_id');
	        $this->db->join('user_sports','user_sports.user_id=users.id');
	        $this->db->where("area.id",$area_id);
	        $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.sports_id",$sports_id);
	        $this->db->where("users.status",1);
	        $this->db->order_by('users.id','asc');
	        return $this->db->get()->result();
	}
/////////////////////// hot offer slot times based on hot id ///////////////////////////
	public function get_slot_time($hot_id){
	        $this->db->select('court_time_intervel.id,court_time_intervel.time');
	        $this->db->from('hot_offer_slot');
	        $this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->where("hot_offer_slot.hot_offer_id",$hot_id);
	        $this->db->order_by('court_time_intervel.time','asc');
	        return $this->db->get()->result();
	}
	
	
	
	
/////////////////////jinson/////////////////////////////
///////////////////////get user details ///////////////////////////
	public function get_user_detailsj($user_id){
			$this->db->select('id');  
	        $this->db->from('users');
	        $this->db->where("status",1);
			$this->db->where("id",$user_id);
		    return $this->db->get()->result();
	       
	}
///////////////////////get user details ///////////////////////////
	public function get_user_venuesj($user_ids){
	    
	        $today=date('Y-m-d');	
		    $second= date('Y-m-d', strtotime($today. ' + 1 days'));
		    $third= date('Y-m-d', strtotime($today. ' + 2 days'));
		
	        $this->db->distinct('hot_offer.id');
	        $this->db->select('venue.id,hot_offer.*');
			$this->db->from('hot_offer');
			$this->db->join('venue','venue.id=hot_offer.venue_id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('hot_offer_court','hot_offer_court.hot_offer_id=hot_offer.id');
	        $this->db->where("user_area.user_id",$user_ids);
	        $this->db->where("hot_offer.date",$today);
	        $this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.id','asc');
	        $this->db->limit(10);
	        $result1= $this->db->get()->result();
	        
	        $this->db->distinct('hot_offer.id');
	        $this->db->select('venue.id,hot_offer.*');
			$this->db->from('hot_offer');
			$this->db->join('venue','venue.id=hot_offer.venue_id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('hot_offer_court','hot_offer_court.hot_offer_id=hot_offer.id');
	        $this->db->where("user_area.user_id",$user_ids);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.id','asc');
	        $this->db->limit(10);
	        $result2= $this->db->get()->result();
	        $data = array_merge_recursive($result1, $result2);
	        
	        $this->db->distinct('hot_offer.id');
	        $this->db->select('venue.id,hot_offer.*');
			$this->db->from('hot_offer');
			$this->db->join('venue','venue.id=hot_offer.venue_id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('hot_offer_court','hot_offer_court.hot_offer_id=hot_offer.id');
	        $this->db->where("user_area.user_id",$user_ids);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.id','asc');
	        $this->db->limit(10);
	        $result3= $this->db->get()->result();
	        
	        $result = array_merge_recursive($data, $result3);
	        usort($result, 'sortByOne');

            $limitedTable = array_slice($result, 0,10);
	        return $limitedTable;
	        
	       
	}
///////////////////////get user details ///////////////////////////
	public function get_user_venuesjj($user_ids){
	        $this->db->select('venue.id');
			$this->db->from('venue');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
	        $this->db->where("user_area.user_id",$user_ids);
	        return $this->db->get()->result();
	        
	       
	}
	
	
	//////getuser based on location
	
		public function get_user_venuesjjtest($user_ids)
		{
		     $this->db->distinct('venue.id');
	        $this->db->select('venue.id,area.location_id as location_id,user_area.area_id as area_id');
			$this->db->from('venue');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('area','venue.area_id=area.id');
	        $this->db->where("user_area.user_id",$user_ids);
	       $result1= $this->db->get()->result();
	        $this->db->distinct('venue.id');
	       $this->db->select('venue.id');
			$this->db->from('venue');
			$this->db->join('area','venue.area_id=area.id');
			$this->db->join('user_area','venue.area_id=user_area.area_id');
			$this->db->join('locations','area.location_id=locations.id');
            foreach($result1 as $value=>$val){
                 $venue_id=  $val->id;
            $location_id=  $val->location_id;
            $area_id=  $val->area_id;
           //  echo  $venue_id.'</br>';
           // echo  $location_id.'</br>';
          //  echo  $area_id.'</br>';
            $this->db->where('venue.id!=',$venue_id);
            $this->db->where('area.id!=',$area_id);
            $this->db->where('locations.id ', $location_id);
            
            }
             $result2= $this->db->get()->result();
	          $data1 = array_merge ($result1,$result2); 
	          return $data1;
	          
	       
	}
	
	
///////////////////////get user details ///////////////////////////
	public function get_venue_sportjt($venue_id,$today){
	        $this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer.venue_id,hot_offer_court.sports_id,hot_offer.date');
			$this->db->from('hot_offer_court');
			$this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->where("hot_offer.venue_id",$venue_id);
	        $this->db->where("hot_offer.date",$today);
	        return $this->db->get()->result();
	        
	       
	}
	
///////////////////////get user details ///////////////////////////
	public function get_venue_sportjs($venue_id,$second){
	        $this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer.venue_id,hot_offer_court.sports_id,hot_offer.date');
			$this->db->from('hot_offer_court');
			$this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->where("hot_offer.venue_id",$venue_id);
	        $this->db->where("hot_offer.date",$second);
	        return $this->db->get()->result();
	        
	       
	}
	
///////////////////////get user details ///////////////////////////
	public function get_venue_sportjth($venue_id,$third){
	        $this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer.venue_id,hot_offer_court.sports_id,hot_offer.date');
			$this->db->from('hot_offer_court');
			$this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->where("hot_offer.venue_id",$venue_id);
	        $this->db->where("hot_offer.date",$third);
	        return $this->db->get()->result();
	        
	       
	}
	
///////////////////////get user details ///////////////////////////
	public function get_venue_detailsj($venue_ids){
	        $this->db->select('venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,area.area');
			$this->db->from('venue');
			$this->db->join('area','venue.area_id=area.id');
	        $this->db->where("venue.id",$venue_ids);
	        return $this->db->get()->result();
	        
	       
	}
	
///////////////////////get user details ///////////////////////////
	public function get_venue_sportjtss($venue_id){
	    $time = date('H:i:s');
	    $today=date('Y-m-d');
	    $second= date('Y-m-d', strtotime($today. ' + 1 days'));
	    $third= date('Y-m-d', strtotime($today. ' + 2 days'));
	    
	        $this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer.venue_id,hot_offer_court.sports_id,hot_offer.date,MAX(hot_offer.precentage) as hot_percentage');
			$this->db->from('hot_offer_court');
			$this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->where("hot_offer.venue_id",$venue_id);
	       // $this->db->where('court_time_intervel.time >=',$time);
	        $this->db->where("hot_offer.date",$today);
	        $this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.date','asc');
	        $result1= $this->db->get()->result();
	      
	        $this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer.venue_id,hot_offer_court.sports_id,hot_offer.date,MAX(hot_offer.precentage) as hot_percentage');
			$this->db->from('hot_offer_court');
			$this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->where("hot_offer.venue_id",$venue_id);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.date','asc');
	        $result2= $this->db->get()->result();
	        $data = array_merge_recursive($result1, $result2);
	        
	        $this->db->distinct('hot_offer_court.sports_id');
	        $this->db->select('hot_offer.venue_id,hot_offer_court.sports_id,hot_offer.date,MAX(hot_offer.precentage) as hot_percentage');
			$this->db->from('hot_offer_court');
			$this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->where("hot_offer.venue_id",$venue_id);
	        $this->db->where("hot_offer.date",$third);
	        $this->db->order_by('hot_offer.date','asc');
			$this->db->group_by('hot_offer.date','asc');
	        $result3= $this->db->get()->result();
	        
	        $result = array_merge_recursive($data, $result3);
	        return $result;
	}
/////////// sports details ///////////////	
public function get_sports_detailsj($sports_ids){
	
		    $this->db->distinct('sports.id');
	        $this->db->select('sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
	        $this->db->from('sports');
	        $this->db->where("sports.id",$sports_ids);
	        return $this->db->get()->result();
	}

/////////// slot details  ///////////////	
public function get_slot_detailsj($venue_ids,$sports_id,$dates){
	        $time = date('H:i:s');
	        $this->db->distinct('court_time_intervel.time');
	        $this->db->select('court_time_intervel.time,court.intervel,court.id,court.capacity');
	        $this->db->from('hot_offer_slot');
	        $this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
	        $this->db->where("hot_offer.venue_id",$venue_ids);
	        $this->db->where("hot_offer_court.sports_id",$sports_id);
	        if($dates==date('Y-m-d'))
	        {
	         $this->db->where('court_time_intervel.time >=',$time);
	        }
	        $this->db->where("hot_offer.date",$dates);
	        $this->db->order_by('court_time_intervel.time','asc');
	        $this->db->group_by('court_time_intervel.time','asc');
	        return $this->db->get()->result();
	}
	
//check any booking exist on that selected date
public function get_booking_check($venue_ids,$sports_id,$court_id,$dates,$slot_time){
        $this->db->select('IFNULL(SUM(venue_booking_time.capacity),0)as capacity ');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.venue_id",$venue_ids);
		$this->db->where("venue_booking.sports_id",$sports_id);
		$this->db->where("venue_booking.court_id",$court_id);
		$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->where('venue_booking.payment_mode !=',3);
		$this->db->where("venue_booking.date",$dates);
		$this->db->where("venue_booking_time.court_id",$court_id);
		$this->db->where("venue_booking_time.date",$dates);
                $this->db->where("venue_booking_time.court_time",$slot_time);
		return $this->db->get()->result();
	}
	
//check any booking data  exist in temp table on that selected date in 
public function get_tempbooking($court_id,$dates,$slot_time){
        $this->db->select('IFNULL(SUM(capacity),0)as tempsum'); 
		$this->db->from('court_book');
		$this->db->where('date',$dates);
		$this->db->where('court_id',$court_id);
		$this->db->where('court_time',date('h:i A', strtotime($slot_time)));
		return $this->db->get()->result();
	}
	
	





}
