<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Hotoffer_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

///////////////////////get user details ///////////////////////////
	public function get_user_detail($user_id){
	
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
	
////////////////////////// user location ///////////////////////////////
 public function get_user_location($user_id)
	{
		$this->db->distinct('area.location_id');
		$this->db->select("area.location_id");
		$this->db->from("user_area");
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////// user sports hot offer ///////////////////////////////
 public function get_user_sports_hotoffer($user_id,$location_id)
	{
		$time = date('H:i:s');
		$today=date('Y-m-d');	
		$second= date('Y-m-d', strtotime($today. ' + 1 days'));
		$third= date('Y-m-d', strtotime($today. ' + 2 days'));

		//////////////////// today hot offer start //////////////////////
		$this->db->distinct('hot_offer_slot.court_time_intervel_id');
	    $this->db->select('court.id as court_id, court.court as court_name,court.cost as court_price,court.capacity as court_capacity, court_time_intervel.id as slot_id,court_time_intervel.time as slot_time,court.intervel as slot_intervel,venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,MAX(hot_offer.precentage) as hot_percentage,area.id as area_id,area.area as area,sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('user_sports','hot_offer_court.sports_id=user_sports.sports_id');
			$this->db->join('sports','user_sports.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.user_id",$user_id);
	        $this->db->where("hot_offer.date",$today);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         if($today==date('Y-m-d'))
	         {
	        $this->db->where('court_time_intervel.time >=',$time);
	         } 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result1= $this->db->get()->result();
	        
	        usort( $result1, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });

	    //////////////////// today hot offer end //////////////////////
	    
	    //////////////////// second day hot offer start //////////////////////
	    $this->db->distinct('hot_offer_slot.court_time_intervel_id');
	    $this->db->select('court.id as court_id, court.court as court_name,court.cost as court_price,court.capacity as court_capacity, court_time_intervel.id as slot_id,court_time_intervel.time as slot_time,court.intervel as slot_intervel,venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,MAX(hot_offer.precentage) as hot_percentage,area.id as area_id,area.area as area,sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('user_sports','hot_offer_court.sports_id=user_sports.sports_id');
			$this->db->join('sports','user_sports.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.user_id",$user_id);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result2= $this->db->get()->result();
	        usort( $result2, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        $data1 = array_merge ($result1,$result2);
	    //////////////////// second day hot offer end //////////////////////
	    //////////////////// third day hot offer start //////////////////////
	    $this->db->distinct('hot_offer_slot.court_time_intervel_id');
	    $this->db->select('court.id as court_id, court.court as court_name,court.cost as court_price,court.capacity as court_capacity, court_time_intervel.id as slot_id,court_time_intervel.time as slot_time,court.intervel as slot_intervel,venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,MAX(hot_offer.precentage) as hot_percentage,area.id as area_id,area.area as area,sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('user_sports','hot_offer_court.sports_id=user_sports.sports_id');
			$this->db->join('sports','user_sports.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.user_id",$user_id);
	        $this->db->where("hot_offer.date",$third);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result3= $this->db->get()->result();
	         usort( $result3, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        
	        $data2 = array_merge ($data1,$result3);
	        return  $data2;
	    //////////////////// third day hot offer end //////////////////////
	}
	
////////////////////////// user sports hot offer ///////////////////////////////
 public function get_user_non_sports_hotoffer($user_id,$location_id)
	{
		$time = date('H:i:s');
		$today=date('Y-m-d');	
		$second= date('Y-m-d', strtotime($today. ' + 1 days'));
		$third= date('Y-m-d', strtotime($today. ' + 2 days'));

		
		$this->db->select('sports.sports,sports.id,sports.image');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		$this->db->where('user_sports.user_id',$user_id);

		$user_sports= $this->db->get()->result();

		//////////////////// today hot offer start //////////////////////
		$this->db->distinct('hot_offer_slot.court_time_intervel_id');
	    $this->db->select('court.id as court_id, court.court as court_name,court.cost as court_price,court.capacity as court_capacity, court_time_intervel.id as slot_id,court_time_intervel.time as slot_time,court.intervel as slot_intervel,venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,MAX(hot_offer.precentage) as hot_percentage,area.id as area_id,area.area as area,sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('sports','hot_offer_court.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("hot_offer.date",$today);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         if($today==date('Y-m-d'))
	         {
	        $this->db->where('court_time_intervel.time >=',$time);
	         }
	         foreach($user_sports as $value=>$val)
        	{
        	    $sports_id=  $val->id;
        	    
        	   $this->db->where("hot_offer_court.sports_id !=",$sports_id);
        	} 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result1= $this->db->get()->result();
	        usort( $result1, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        
	    //////////////////// today hot offer end //////////////////////
	    
	    //////////////////// second day hot offer start //////////////////////
	    $this->db->distinct('hot_offer_slot.court_time_intervel_id');
	    $this->db->select('court.id as court_id, court.court as court_name,court.cost as court_price,court.capacity as court_capacity, court_time_intervel.id as slot_id,court_time_intervel.time as slot_time,court.intervel as slot_intervel,venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,MAX(hot_offer.precentage) as hot_percentage,area.id as area_id,area.area as area,sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('sports','hot_offer_court.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         foreach($user_sports as $value=>$val)
        	{
        	    $sports_id=  $val->id;
        	    
        	   $this->db->where("hot_offer_court.sports_id !=",$sports_id);
        	} 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result2= $this->db->get()->result();
	        usort( $result2, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        $data1 = array_merge ($result1,$result2);
	    //////////////////// second day hot offer end //////////////////////
	    
	    	    //////////////////// third day hot offer start //////////////////////
	    	    $this->db->distinct('hot_offer_slot.court_time_intervel_id');
	    $this->db->select('court.id as court_id, court.court as court_name,court.cost as court_price,court.capacity as court_capacity, court_time_intervel.id as slot_id,court_time_intervel.time as slot_time,court.intervel as slot_intervel,venue.id as venue_id,venue.venue as venue,venue.morning as morning,venue.evening as evening,venue.area_id as venue_area_id,venue.lat as lat,venue.lon as lon,venue.address as address,hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,MAX(hot_offer.precentage) as hot_percentage,area.id as area_id,area.area as area,sports.id as sports_id,sports.sports as sports_name,sports.image as sports_image');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('sports','hot_offer_court.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("hot_offer.date",$third);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         foreach($user_sports as $value=>$val)
        	{
        	    $sports_id=  $val->id;
        	    
        	   $this->db->where("hot_offer_court.sports_id !=",$sports_id);
        	} 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result3= $this->db->get()->result();
	         usort( $result3, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        $data2 = array_merge ($data1,$result3);
	    //////////////////// third day hot offer end //////////////////////
	    
	    return  $data2;
	}
	
    
    //check any booking exist on that selected date
public function get_booking($venue_id,$sports_id,$court_id,$dates,$slot_time){
        $this->db->select('IFNULL(SUM(venue_booking_time.capacity),0)as capacity ');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->where("venue_booking.venue_id",$venue_id);
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
	



////////////////////////// user sports hot offer ///////////////////////////////
 public function get_today_venue($user_id,$location_id)
	{
		$time = date('H:i:s');
		$today=date('Y-m-d');	
		$second= date('Y-m-d', strtotime($today. ' + 1 days'));
		$third= date('Y-m-d', strtotime($today. ' + 2 days'));

		//////////////////// today hot offer start //////////////////////
		$this->db->distinct('venue.id');
	    $this->db->select('venue.id as venue_id,sports.id as sports_id,hot_offer.date as hot_date');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('user_sports','hot_offer_court.sports_id=user_sports.sports_id');
			$this->db->join('sports','user_sports.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.user_id",$user_id);
	        $this->db->where("hot_offer.date",$today);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         if($today==date('Y-m-d'))
	         {
	        $this->db->where('court_time_intervel.time >=',$time);
	         } 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result1= $this->db->get()->result();
	        
	        usort( $result1, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });

	    //////////////////// today hot offer end //////////////////////
	    
	    //////////////////// second day hot offer start //////////////////////
	    $this->db->distinct('venue.id');
	    $this->db->select('venue.id as venue_id,sports.id as sports_id,hot_offer.date as hot_date');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('user_sports','hot_offer_court.sports_id=user_sports.sports_id');
			$this->db->join('sports','user_sports.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.user_id",$user_id);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result2= $this->db->get()->result();
	        usort( $result2, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        $data1 = array_merge ($result1,$result2);
	    //////////////////// second day hot offer end //////////////////////
	    //////////////////// third day hot offer start //////////////////////
	    $this->db->distinct('venue.id');
	    $this->db->select('venue.id as venue_id,sports.id as sports_id,hot_offer.date as hot_date');
			$this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('user_sports','hot_offer_court.sports_id=user_sports.sports_id');
			$this->db->join('sports','user_sports.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("user_sports.user_id",$user_id);
	        $this->db->where("hot_offer.date",$third);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by("user_sports.sports_id",'ASC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result3= $this->db->get()->result();
	         usort( $result3, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        
	        $data2 = array_merge ($data1,$result3);
	        return  $data2;
	    //////////////////// third day hot offer end //////////////////////
	}
	
////////////////////////// user sports hot offer ///////////////////////////////
 public function get_user_nsp_venue($user_id,$location_id)
	{
		$time = date('H:i:s');
		$today=date('Y-m-d');	
		$second= date('Y-m-d', strtotime($today. ' + 1 days'));
		$third= date('Y-m-d', strtotime($today. ' + 2 days'));

		
		$this->db->select('sports.sports,sports.id,sports.image');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		$this->db->where('user_sports.user_id',$user_id);

		$user_sports= $this->db->get()->result();

		//////////////////// today hot offer start //////////////////////
		    $this->db->distinct('venue.id');
	        $this->db->select('venue.id as venue_id,sports.id as sports_id,hot_offer.date as hot_date');
	        $this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('sports','hot_offer_court.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("hot_offer.date",$today);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         if($today==date('Y-m-d'))
	         {
	        $this->db->where('court_time_intervel.time >=',$time);
	         }
	         foreach($user_sports as $value=>$val)
        	{
        	    $sports_id=  $val->id;
        	    
        	   $this->db->where("hot_offer_court.sports_id !=",$sports_id);
        	} 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result1= $this->db->get()->result();
	        usort( $result1, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        
	    //////////////////// today hot offer end //////////////////////
	    
	    //////////////////// second day hot offer start //////////////////////
	        $this->db->distinct('venue.id');
	        $this->db->select('venue.id as venue_id,sports.id as sports_id,hot_offer.date as hot_date');
	        $this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('sports','hot_offer_court.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("hot_offer.date",$second);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         foreach($user_sports as $value=>$val)
        	{
        	    $sports_id=  $val->id;
        	    
        	   $this->db->where("hot_offer_court.sports_id !=",$sports_id);
        	} 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result2= $this->db->get()->result();
	        usort( $result2, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        $data1 = array_merge ($result1,$result2);
	    //////////////////// second day hot offer end //////////////////////
	    
	    	    //////////////////// third day hot offer start //////////////////////
	    	$this->db->distinct('venue.id');
	        $this->db->select('venue.id as venue_id,sports.id as sports_id,hot_offer.date as hot_date');
	        $this->db->from('hot_offer_slot');
	       	$this->db->join('court_time_intervel','court_time_intervel.id=hot_offer_slot.court_time_intervel_id');
	        $this->db->join('hot_offer_court','hot_offer_court.id=hot_offer_slot.hot_offer_court_id');
	        $this->db->join('hot_offer','hot_offer.id=hot_offer_court.hot_offer_id');
			$this->db->join('sports','hot_offer_court.sports_id=sports.id');
	        $this->db->join('court','court.id=hot_offer_court.court_id');
		    $this->db->join('venue','hot_offer.venue_id=venue.id');
			$this->db->join('area','venue.area_id=area.id');
		    $this->db->where("area.location_id",$location_id);
	        $this->db->where("hot_offer.date",$third);
	        $this->db->where("venue.status",1);
	        $this->db->where("hot_offer.status",1);
	        $this->db->where("court.status",1);
	         foreach($user_sports as $value=>$val)
        	{
        	    $sports_id=  $val->id;
        	    
        	   $this->db->where("hot_offer_court.sports_id !=",$sports_id);
        	} 
	        $this->db->order_by('hot_offer.precentage','DESC');
	        $this->db->order_by('court_time_intervel.id','asc');
	        $this->db->group_by('court_time_intervel.id','asc');
	        $result3= $this->db->get()->result();
	         usort( $result3, function( $a, $b ){
                        if($a->hot_percentage == $b->hot_percentage ) {
                            return 0;
                        }
                    return ($a->hot_percentage > $b->hot_percentage ) ? -1 : 1;
                    });
	        $data2 = array_merge ($data1,$result3);
	    //////////////////// third day hot offer end //////////////////////
	    
	    return  $data2;
	}




}
