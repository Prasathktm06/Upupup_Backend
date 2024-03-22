<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}
	public function get_up_phone($col)
	{
		$this->db->select('phone');
		$this->db->from('upupup_phone');
		$this->db->where($col,1);

		return $this->db->get()->result();
	}
	public function get_up_mail($col)
	{
		$this->db->select('email');
		$this->db->from('upupup_email');
		$this->db->where($col,1);

		return $this->db->get()->result();
	}
	//////////////////////////City List///////////////////////////////
	public function location_list(){
		$this->db->select('id,location');
		$this->db->from('locations');
		return $this->db->get()->result_array();
	}
	//////////////////////////////Area List/////////////////////////////
	public function area_list($location_id){
		$this->db->select('id,area');
		$this->db->from('area');
		$this->db->where('area.location_id',$location_id);
		return $this->db->get()->result_array();
	}
	//////////////////////////Sports List//////////////////////////////////////////
	public function sports_list(){
		$this->db->select('id,sports');
		$this->db->from('sports');
		return $this->db->get()->result_array();
	}
	///////////////////////////////Offer List//////////////////////////////////////////
	public function offer_list(){
		$this->db->select('id,offer');
		$this->db->from('offer');
		return $this->db->get()->result_array();
	}
	//////////////////////////////////Users List/////////////////////////////////////////////////
	public function users_list($city="",$area="",$sports="")
	{
		$this->db->select('users.device_id,users.id,users.phone_no,area.location_id');
		$this->db->from('users');
		$this->db->join('user_area','user_area.user_id=users.id','left');
		$this->db->join('user_sports','user_sports.user_id=users.id','left');
		$this->db->join('area','area.id=user_area.area_id','left');
		
		if ($city!="") {
			
			$this->db->where('area.location_id',$city);
		}
		if ($area!="") {
			$this->db->where('user_area.area_id',$area);
		}
		if ($sports!="") {
			$this->db->where('user_sports.sports_id',$sports);
		}



		$this->db->group_by('users.id');
		return $this->db->get()->result_array();
		//return  $this->db->last_query();
	}
	///////////////////////////Offer Details/////////////////////////////////////////////
	public function offer_details($id){
		$this->db->select("offer.id,DATE_FORMAT(`start`, '%m/%d/%Y ')as start,DATE_FORMAT(`end`, '%m/%d/%Y ') as end,venue_id,offer,offer.image,amount,percentage ");
		$this->db->from('offer');
		$this->db->join("offer_court","offer_court.offer_id=offer.id");
		$this->db->where('offer.id',$id);
		return $this->db->get()->row();
	}
	////////////////////////////////Venue details///////////////////////////////////
	public function venue_details($id){
		$this->db->select('venue.id as venue_id,venue.venue,venue.morning,venue.evening,venue.description,venue.cost,venue.phone,venue.address,venue.image,venue.lat,venue.lon,venue.area_id,area.area');
		$this->db->from('venue');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->where('venue.id',$id);
		return $this->db->get()->row();
	}
	//////////////////////////////Venue Facilities////////////////////////////////////////////
	public function venue_facilities($id){
		$this->db->select('*');
		$this->db->from('venue_facilities');
		$this->db->join('facilities','facilities.id=venue_facilities.facility_id');
		$this->db->where('venue_facilities.venue_id',$id);

		$row1	= $this->db->get()->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['facility'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	//////////////////////////////Venue Court///////////////////////////////////////
	public function venue_court($id){
		$this->db->select('court.id,court.court,court.cost,court.sports_id');
		$this->db->from('venue_court');
		$this->db->join('court','court.id=venue_court.court_id');
		$this->db->where('venue_court.venue_id',$id);

		return $row1	= $this->db->get()->result_array();

	}
	////////////////////Court Sports////////////////////////////////////////////////
	public function court_sports($id){
		$this->db->select('*');
		$this->db->from('sports');
		$this->db->where('sports.id',$id);

		return $row1	= $this->db->get()->result_array();

	}
	///////////////////////////Insert Notification////////////////////////////////////////
	public function insert_notification($insert_array){
		$row =$this->db->insert('notification',$insert_array);
		return $row;
	}
	///////////////////////Venue Rating//////////////////////////////////////////////////
	public function venue_rating($id){
		$this->db->select('*');
		$this->db->from('venue_booking');
		$this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id');
		$this->db->where('venue_booking.venue_id',$id);
		$this->db->order_by('venue_booking.date','DESC');

		return $row1	= $this->db->get()->row();

	}
	///////////////////////////////////Gallery Images/////////////////////////////////////
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
			$array[]="";
		}

		return $array;
	}
	//////////////////////////////////Venue Sports/////////////////////////////////////////////
	public function venue_sports($venue)
	{
		$this->db->select('sports.sports,sports.id,sports.image');
		$this->db->from('venue');
		$this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
		$this->db->join('sports','sports.id=venue_sports.sports_id','left');
		$this->db->where('venue.id',$venue);
		return $this->db->get()->result();
	}
	/////////////////////////////////////Insert History///////////////////////////////
	public function add_notification($insert_array){
		$row =$this->db->insert('notification_history',$insert_array);
		return $row;
	}
	/////////////////////////////////General History////////////////////////////////////
	public function notification_history($send_type)
	{
		$this->db->select('notification_history.title,notification_history.message,notification_history.image,notification_history.type,notification_history.send_type,notification_history.send_date,locations.location as city,area.area,sports.sports,offer.offer,venue.venue');
		$this->db->from('notification_history');
		$this->db->join('locations','locations.id=notification_history.city_id','left');
		$this->db->join('area','area.id=notification_history.area_id','left');
		$this->db->join('sports','sports.id=notification_history.sports_id','left');
		$this->db->join('offer','offer.id=notification_history.offer_id','left');
		$this->db->join('venue','venue.id=notification_history.venue_id','left');
		$this->db->where('notification_history.send_type',$send_type);
		$this->db->order_by('notification_history.notification_id','desc');
		return $this->db->get()->result_array();
	}
	/////////////////////////////////////////Venues ////////////////////////////////////////
	public function get_venues($area){
		$this->db->select('venue.id as venue_id,venue.venue');
		$this->db->from('venue');
		$this->db->where('venue.area_id',$area);
		return $this->db->get()->result_array();
	}
	//////////////////////////////////Court/////////////////////////////////////////////////
	public function get_court($venue_id){
		$this->db->select('court.id,court.court');
		$this->db->from('venue_court');
		$this->db->join("court","court.id=venue_court.court_id","left");
		$this->db->where('venue_court.venue_id',$venue_id);
		return $this->db->get()->result_array();
	}
	//////////////////////////////////Court/////////////////////////////////////////////////
	public function get_offer($court_id){
		$this->db->select('offer.id,offer.offer');
		$this->db->from('offer_court');
		$this->db->join("offer","offer.id=offer_court.offer_id","left");
		$this->db->where('offer_court.court_id',$court_id);
		$this->db->where('offer.end >=',date('Y-m-d'));
		$this->db->where('offer.status',1);
		return $this->db->get()->result_array();
	}
	//////////////////////////////////////////////////////////////////////////////////////

}
