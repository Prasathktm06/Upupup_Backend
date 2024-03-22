<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}

	/////////////////////////////////////Users List///////////////////////////////////////////////
	public function get_users_list($city="",$area=""){
		$this->db->distinct();
		$this->db->select('users.*,locations.location,area.location_id');
		$this->db->from('users');
		$this->db->join("user_area","user_area.user_id=users.id","left");
		$this->db->join("area","area.id=user_area.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('user_area.area_id',$area);
		}
		$this->db->order_by('users.added_date','DESC');
		return $this->db->get()->result_array();
	}
	/////////////////////////////////////////User Areas/////////////////////////////////////////
	public function get_user_areas($user_id,$area=""){
		$this->db->select('*');
		$this->db->from('user_area');
		$this->db->join("area","area.id=user_area.area_id","left");
		$this->db->where('user_area.user_id',$user_id);
		if ($area) {
			$this->db->where('user_area.area_id',$area);
		}


		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['area'];
		    }
		}else{
			$array[]="";
		}

		return $array;

	}
	
	/////////////////////////////////////////User Sports/////////////////////////////////////////
	public function get_user_sports($user_id){
		$this->db->select('sports.sports');
		$this->db->from('sports');
		$this->db->join("user_sports","user_sports.sports_id=sports.id");
		$this->db->where('user_sports.user_id',$user_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['sports'];
		    }
		}else{
			$array[]="";
		}

		return $array;

	}
	
	/////////////////////////////////////////User channel/////////////////////////////////////////
	public function get_user_channel($user_id){
	    $this->db->distinct('user_id');
		$this->db->select('channel_id');
		$this->db->from('user_channel');
		$this->db->where('user_id',$user_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['channel_id'];
		    }
		}else{
			$array[]="";
		}

		return $array;

	}
	/////////////////////////////////Month Data//////////////////////////////////////////////////
	public function get_months(){
		$this->db->select('*');
		$this->db->from('month');
		return $this->db->get()->result_array();
	}

	////////////////////////////////// upUPUP Booking List/////////////////////////////////////////
	public function get_booking_list($city="",$area="",$venue="",$sports="",$date="",$enddate=""){
		$this->db->select('venue_booking.*,venue.venue,venue.phone as venue_phone,users.name,users.phone_no,users.email,sports.sports,court.court,court.intervel,court.capacity,court.cost as court_cost,offer.percentage as offer_percentage,venue.amount,venue.address,coupons.coupon_code,offer.offer,locations.location,area.area,coupons.coupon_value,coupons.percentage,coupons.currency,coupons.description,coupons.coupon_code');
		$this->db->from('venue_booking');
		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("offer","offer.id=venue_booking.offer_value","left");
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
                $this->db->where('venue_booking.payment_id !=',"vendor");
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($venue) {
			$this->db->where('venue_booking.venue_id', $venue);
		}
		if ($sports) {
			$this->db->where('venue_booking.sports_id',$sports);
		}
		if ($date) {
			//$this->db->where('date(date)',$date);
			$date = strftime("%Y-%m-%d", strtotime("$date"));
            
			$this->db->where('venue_booking.date >=', $date);
		
		}
		if ($enddate) {
		    $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('venue_booking.date <=', $enddate);
		}


		$this->db->order_by('venue_booking.id','DESC');
		return $this->db->get()->result_array();
	}
    ////////////////////////////////// Vendor Booking List/////////////////////////////////////////
	public function get_vendorbooking_list($city="",$area="",$venue="",$sports="",$date="",$enddate=""){
		$this->db->select('venue_booking.*,venue.venue,venue.phone as venue_phone,users.name,users.phone_no,users.email,sports.sports,court.court,court.intervel,court.capacity,court.cost as court_cost,offer.percentage as offer_percentage,offer.amount as offer_amount, venue.amount,venue.address,coupons.coupon_code,offer.offer,locations.location, area.area,coupons.coupon_value,coupons.percentage, coupons.currency, coupons.description, coupons.coupon_code, role.name as role_name,venue_booking_time.capacity as booked_capacity,user.phone  as mgr_phone');
		$this->db->from('venue_booking');
		$this->db->join("venue_booking_time","venue_booking_time.booking_id=venue_booking.booking_id","left");
		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		$this->db->join("booked_manager","booked_manager.booking_id=venue_booking.booking_id","left");
		$this->db->join("user","user.user_id=booked_manager.user_id","left");
		$this->db->join("user_role","user_role.user_id=booked_manager.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->where('venue_booking.payment_id =',"vendor");
		$this->db->where('venue_booking.payment_mode =',1);
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($venue) {
			$this->db->where('venue_booking.venue_id', $venue);
		}
		if ($sports) {
			$this->db->where('venue_booking.sports_id',$sports);
		}
		if ($date) {
			$date = strftime("%Y-%m-%d", strtotime("$date"));
			$this->db->where('venue_booking.date >=', $date);
		
		}
		if ($enddate) {
		    $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('venue_booking.date <=', $enddate);
		}


		$this->db->order_by('venue_booking.id','DESC');
		return $this->db->get()->result_array();
	}

	////////////////////////////////// Cancel Booking List/////////////////////////////////////////
	public function get_cancelbooking_list($city="",$area="",$venue="",$sports="",$date="",$enddate=""){
		$this->db->select('venue_booking.*,venue.venue,venue.phone as venue_phone,users.name,users.phone_no,users.email,sports.sports,court.court,court.intervel,court.capacity,court.cost as court_cost,offer.percentage as offer_percentage,offer.amount as offer_amount, venue.amount, venue.address, coupons.coupon_code, offer.offer, locations.location, area.area, coupons.coupon_value, coupons.percentage, coupons.currency, coupons.description, coupons.coupon_code,role.name as role_name,venue_booking_time.capacity as booked_capacity,user.phone as cm_phone,booking_cancel.cancel_date as cancel_date');
		$this->db->from('venue_booking');
		$this->db->join("venue_booking_time","venue_booking_time.booking_id=venue_booking.booking_id","left");
		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("users","users.id=venue_booking.user_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("coupons","coupons.coupon_id=venue_booking.coupon_id","left");
		$this->db->join("booking_offer","booking_offer.booking_id=venue_booking.booking_id","left");
		$this->db->join("offer","offer.id=booking_offer.offer_id","left");
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		$this->db->join("booking_cancel","booking_cancel.booking_id=venue_booking.booking_id","left");
        $this->db->join("user","user.user_id=booking_cancel.user_id","left");
		$this->db->join("user_role","user_role.user_id=booking_cancel.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->where('venue_booking.payment_id =',"vendor");
		$this->db->where('venue_booking.payment_mode =',3);
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($venue) {
			$this->db->where('venue_booking.venue_id', $venue);
		}
		if ($sports) {
			$this->db->where('venue_booking.sports_id',$sports);
		}
		if ($date) {
			$date = strftime("%Y-%m-%d", strtotime("$date"));
			$this->db->where('venue_booking.date >=', $date);
		
		}
		if ($enddate) {
		    $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('venue_booking.date <=', $enddate);
		}


		$this->db->order_by('venue_booking.id','DESC');
		return $this->db->get()->result_array();
	}
	/////////////////////////////////////////Users ////////////////////////////////////////
	public function get_users(){
		$this->db->select('id,phone_no');
		$this->db->from('users');
		return $this->db->get()->result_array();
	}
	/////////////////////////////////////////Venues ////////////////////////////////////////
	public function get_venues($area=""){
		$this->db->select('venue.id as venue_id,venue.venue');
		if ($area) {
			$this->db->where('area_id',$area);
		}
		$this->db->from('venue');
		return $this->db->get()->result_array();
	}
	//////////////////////////////////Court/////////////////////////////////////////////////
	public function get_court($venue_id){
		$this->db->select('court.id,court.court,court.intervel');
		$this->db->from('venue_court');
		$this->db->join("court","court.id=venue_court.court_id","left");
		$this->db->where('venue_court.venue_id',$venue_id);
		return $this->db->get()->result_array();
	}
	////////////////////////////Sports//////////////////////////////////////////////////
	public function get_sports($venue_id){
		$this->db->select('sports.id as sports_id,sports.sports');
		$this->db->from('venue_sports');
		//$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("sports","sports.id=venue_sports.sports_id","left");
		$this->db->where('venue_sports.venue_id',$venue_id);
		$this->db->group_by('sports.id');
		return $this->db->get()->result_array();
	}
	//////////////////////////////////Backend Users///////////////////////////////////////
	public function get_backend_users_list($name="",$email="",$role=""){
		$this->db->select('user.user_id,user.name,user.email,user.date,role.name as role_name,user.image,user.phone,venue.venue,role.slug');
		$this->db->from('user');
		$this->db->join("user_role","user_role.user_id=user.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$this->db->join("venue","venue.id=venue_manager.venue_id","left");
		if ($name) {
			$this->db->where('user.name',$name);
		}
		if ($email) {
			$this->db->where('user.email', $email);
		}
		if ($role) {
			$this->db->where('user_role.role_id',$role);
		}
		$this->db->order_by('date','DESC');
		return $this->db->get()->result_array();
	}
	
	/////////////////////////////////// backend user court ///////////////////////////////////////////
	public function get_backend_users_court($user_id){
		$this->db->select('court.court');
		$this->db->from('role');
		$this->db->join("user_role","user_role.role_id=role.role_id");
		$this->db->join("user","user.user_id=user_role.user_id");
		$this->db->join("court_manager_courts","court_manager_courts.user_id=user.user_id");
		$this->db->join("court","court.id=court_manager_courts.court_id");
		$this->db->where('user.user_id',$user_id);
		$this->db->where('role.venue_users',2);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['court'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	
	//////////////////////////////////Backend Users///////////////////////////////////////
	public function get_backend_users_data(){
		$this->db->select('user.name,user.email,user.date,role.name as role_name,user.image,user.phone,venue.venue,role.slug');
		$this->db->from('user');
		$this->db->join("user_role","user_role.user_id=user.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$this->db->join("venue","venue.id=venue_manager.venue_id","left");
		$this->db->order_by('user.user_id','DESC');
		$this->db->group_by('user.user_id');
		return $this->db->get()->result_array();
	}
	//////////////////////////////////Backend Users///////////////////////////////////////
	public function get_backend_users_email(){
		$this->db->select('user.name,user.email,user.date,role.name as role_name,user.image,user.phone,venue.venue,role.slug');
		$this->db->from('user');
		$this->db->join("user_role","user_role.user_id=user.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$this->db->join("venue","venue.id=venue_manager.venue_id","left");
		$this->db->where('user.email !=',"");
		$this->db->order_by('user.user_id','DESC');
		$this->db->group_by('user.email');
		return $this->db->get()->result_array();
	}
	/////////////////////////////////Role List///////////////////////////////////
	public function get_roles(){
		$this->db->select('role_id,name');
		$this->db->from('role');
		return $this->db->get()->result_array();
	}
	///////////////////////////////Venue Managers//////////////////////////////////////////
	public function get_venue_managers(){
		$this->db->select('*');
		$this->db->from('venue_manager');
		$this->db->join("user","user.user_id=venue_manager.user_id","left");
		return $this->db->get()->result_array();
	}
	/////////////////////////////////////////////////////////////////////////////////////////
	public function insert(){
		$insert_array = array('location' => 'Kannur', );
		$row =$this->db->insert('locations',$insert_array);
		return $row;
	}
	///////////////////////////////////Booked Slots////////////////////////////////////
	public function get_booked_slots($booking_id){
		$this->db->select('*');
		$this->db->from('venue_booking_time');
		$this->db->where('booking_id',$booking_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] =date( ' h:i:s A ',strtotime($row['court_time'])) ;
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	///////////////////////////////////Booking service charge////////////////////////////////////
	public function get_booked_service_charge($booking_id){
		$this->db->select('*');
		$this->db->from('service_charge_booking');
		$this->db->where('booking_id',$booking_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] =$row['total_service_charge'] ;
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	///////////////////////////////////Booked Capacity////////////////////////////////////
	public function get_booked_capacity($booking_id){
		$this->db->select('SUM(capacity) as book_capacity');
		$this->db->from('venue_booking_time');
		$this->db->where('booking_id',$booking_id);
		$query 	= $this->db->get();

		$row1	= $query->row();
		/*if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['court_time'];
		    }
		}else{
			$array[]="";
		}*/

		return $row1;
	}
	/////////////////////////////////Hosting List//////////////////////////////////////
	public function get_hosting_list($city="",$area="",$date="",$enddate="",$time=""){
		$this->db->distinct();
		$this->db->select('users.name,users.phone_no,users.email,locations.location,area.area,sports.sports,matches.date,matches.time,matches.id,matches.added_date');
		$this->db->from('matches');
		$this->db->join("users","users.id=matches.user_id","left");
		$this->db->join("area","area.id=matches.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		$this->db->join("sports","sports.id=matches.sports_id","left");
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('matches.area_id',$area);
		}
		if ($date) {
		    $date = strftime("%Y-%m-%d", strtotime("$date"));
			$this->db->where('matches.date >=',$date);
		}
		if ($enddate) {
		    $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('matches.date <=',$enddate);
		}
		if ($time) {
			$this->db->where('matches.time',$time);
		}
		$this->db->order_by('matches.date','DESC');
		return $this->db->get()->result_array();
	}
	///////////////////////////////////Co Players List/////////////////////////////////////////////
	public function get_co_players_list($match_id){
		$this->db->distinct('matches_players.user_id');
		$this->db->select('*');
		$this->db->from('matches_players');
		$this->db->join("users","users.id=matches_players.user_id","left");
		$this->db->where('matches_players.match_id',$match_id);
		$this->db->where('matches_players.status',2);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['name'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	////////////////////////////Venue Utilization/////////////////////////////////////////
	public function get_venue_list($city="",$area="",$venue="",$date="",$enddate=""){
		$this->db->distinct();
		$this->db->select('venue.id as venue_id,venue.venue,locations.location,area.area,venue.added_date');
		$this->db->from('venue');
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		$this->db->join("venue_sports","venue_sports.venue_id=venue.id","left");
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($venue) {
			$this->db->where('venue.id', $venue);
		}
		if ($date) {
		    $date = strftime("%Y-%m-%d", strtotime("$date"));
                
			$this->db->where('date(venue.added_date) >=', $date);
		}
		if ($enddate) {
		    $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('date(venue.added_date) <=', $enddate);
		}

		return $this->db->get()->result_array();
	}
	///////////////////////////Roles List//////////////////////////////////////////////
	public function get_roles_list($role=""){
		$this->db->select('role_id,name,added_date');
		$this->db->from('role');
		if ($role) {
			$this->db->where('role_id', $role);
		}
		
		return $this->db->get()->result_array();
	}
	/////////////////////////////////Get Permissions//////////////////////////////////////////////
	public function get_permission_list($role_id,$permission=""){
		$this->db->select('perm.name');
		$this->db->from('role_perm');
		$this->db->join("perm","perm.perm_id=role_perm.perm_id","left");
		$this->db->where('role_perm.role_id',$role_id);
		if ($permission) {
			$this->db->where('perm.perm_id', $permission);
		}
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['name'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	///////////////////////////////////Venue Court List///////////////////////////////////////////
	public function get_court_list($venue_id){
		$this->db->select('court.court');
		$this->db->from('venue_court');
		$this->db->join("court","court.id=venue_court.court_id","left");
		$this->db->where('venue_court.venue_id',$venue_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['court'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	////////////////////////////////////Venue Sports List////////////////////////////////////////////
	public function get_sports_list($venue_id){
		$this->db->select('sports.sports');
		$this->db->from('venue_sports');
		$this->db->join("sports","sports.id=venue_sports.sports_id","left");
		$this->db->where('venue_sports.venue_id',$venue_id);
		$this->db->group_by('sports.id');
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['sports'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	////////////////////////////////////Court Booked///////////////////////////////////////////////////////
	public function court_time_booked($court_id){
		$this->db->select('court_time');
		$this->db->from('venue_booking_time');
		$this->db->join("venue_booking","venue_booking.booking_id=venue_booking_time.booking_id","left");
		$this->db->where('venue_booking_time.court_id',$court_id);
        $this->db->where('venue_booking.payment_mode !=',3);
        $this->db->where('venue_booking.payment_mode !=',2);
		return $this->db->get()->result_array();
	}
	///////////////////////////Notification////////////////////////////////////////////////////////
	public function notification_list($send_type,$city="",$area="",$date="",$enddate="")
	{
		$this->db->select('notification_history.title,notification_history.message,notification_history.image,notification_history.type,notification_history.send_type,notification_history.send_date,locations.location as city,area.area,sports.sports,offer.offer,offer.start,offer.end,offer.percentage,venue.venue');
		$this->db->from('notification_history');
		$this->db->join('locations','locations.id=notification_history.city_id','left');
		$this->db->join('area','area.id=notification_history.area_id','left');
		$this->db->join('sports','sports.id=notification_history.sports_id','left');
		$this->db->join('offer','offer.id=notification_history.offer_id','left');
		$this->db->join('venue','venue.id=notification_history.venue_id','left');
		$this->db->where('notification_history.send_type',$send_type);
		if ($city) {
			$this->db->where('notification_history.city_id',$city);
		}
		if ($area) {
			$this->db->where('notification_history.area_id',$area);
		}
		if ($date) {
		    $date = strftime("%Y-%m-%d", strtotime("$date"));
			$this->db->where('date(notification_history.send_date) >=', $date);
		}
		if ($enddate) {
		    $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('date(notification_history.send_date) <=', $enddate);
		}
		$this->db->order_by('notification_history.send_date','DESC');

		return $this->db->get()->result_array();
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
	/////////////////////////////Permission List////////////////////////////////////
	public function get_permission(){
		$this->db->select('perm_id,name');
		$this->db->from('perm');
		return $this->db->get()->result_array();
	}
	///////////////////////////Coupon List//////////////////////////////////////////////
	public function coupons_list($city="",$area="",$date="",$coupon_code=""){
		$this->db->select('coupons.coupon_value,coupons.percentage,coupons.currency,coupons.valid_from,coupons.valid_to,coupons.coupon_code,users.name,users.phone_no,venue_booking.time,locations.location,area.area,coupon_user.booking_id');
		$this->db->from('coupon_user');
		$this->db->join('coupons','coupons.coupon_id=coupon_user.coupon_id','left');
		$this->db->join('users','users.id=coupon_user.user_id','left');
		$this->db->join('venue_booking','venue_booking.booking_id=coupon_user.booking_id','left');
		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($date) {
			$this->db->where('date(venue_booking.time)', $date);
		}
		if ($coupon_code) {
			$this->db->like('coupons.coupon_code', $coupon_code);
		}
		$this->db->where('coupon_user.coupon_id!=','0');

		return $this->db->get()->result_array();
	}
	///////////////////////Coupon Code//////////////////////////////////////////////////
	public function coupon_code_list(){
		$this->db->select('coupon_code');
		$this->db->from('coupons');
		return $this->db->get()->result_array();
	}
	//////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// Offer Report /////////////////////////////////////////
	public function get_offer_list($city="",$area="",$venue="",$sports="",$date="",$enddate=""){
                $this->db->distinct('offer.id');
                $this->db->select('offer.id,locations.location,area.area,venue.venue,court.court,sports.sports,venue_booking.offer_value,offer.percentage,offer.start,offer.end,offer.start_time,offer.end_time,');
		$this->db->from('offer');
		$this->db->join("booking_offer","booking_offer.offer_id=offer.id");
		$this->db->join("venue_booking","venue_booking.booking_id=booking_offer.booking_id");
		$this->db->join("venue","venue.id=venue_booking.venue_id","left");
		$this->db->join("sports","sports.id=venue_booking.sports_id","left");
		$this->db->join("court","court.id=venue_booking.court_id","left");
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");
		//$this->db->where('venue_booking.offer_value !=',0);
		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($venue) {
			$this->db->where('venue_booking.venue_id', $venue);
		}
		if ($sports) {
			$this->db->where('venue_booking.sports_id',$sports);
		}
		if ($date) {
			 $date = strftime("%Y-%m-%d", strtotime("$date"));
			$this->db->where('date(offer.start) >=', $date);
		}
		if ($enddate) {
			 $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
			$this->db->where('date(offer.start) <=', $enddate);
		}

                
		$this->db->order_by('venue_booking.time','DESC');
		return $this->db->get()->result_array();
	}
  /////////////////////////////////// Offer booking id's ///////////////////////////////////////////
	public function get_offer_bklist($id){
		$this->db->select('booking_offer.booking_id');
		$this->db->from('booking_offer');
		$this->db->where('booking_offer.offer_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['booking_id'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	/////////////////////////////////// offer applicable days ///////////////////////////////////////////
	public function get_days_list($id){
		$this->db->distinct('offer_time.day');
		$this->db->select('offer_time.day');
		$this->db->from('offer_time');
		$this->db->where('offer_time.offer_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['day'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// booking id's count ///////////////////////////////////////////
	public function get_bookscount_list($id){
                $this->db->select('count(booking_offer.booking_id) as booking_count');
		$this->db->from('booking_offer');
		$this->db->where('booking_offer.offer_id',$id);
                $query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['booking_count'];
		    }
		}else{
			$array[]="";
		}

		return $array;
		
	}
/////////////////////////////////// get_booked_offer ////////////////////////////////////
	public function get_booked_offer($booking_id){
		$this->db->select('*');
		$this->db->from('offer');
		$this->db->join("booking_offer","booking_offer.offer_id=offer.id","left");
		$this->db->where('booking_offer.booking_id',$booking_id);
		$query 	= $this->db->get();

		return $query->result_array();
		
		
	}
	
////////////////////////////////// Hot offer booking List/////////////////////////////////////////
	public function get_hotoffer_list($city="",$area="",$venue="",$sports="",$date="",$enddate=""){
		$this->db->select('locations.location,area.area,venue.venue,sports.sports,court.court,venue_booking_time.court_time,hot_offer.precentage,venue_booking.date,venue_booking.booking_id');
		$this->db->from('booking_hot_offer');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=booking_hot_offer.booking_id');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->join('venue','venue.id=venue_booking.venue_id');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		$this->db->join('court','court.id=venue_booking_time.court_id');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('hot_offer','hot_offer.id=booking_hot_offer.hot_offer_id');

		if ($city) {
			$this->db->where('area.location_id',$city);
		}
		if ($area) {
			$this->db->where('venue.area_id',$area);
		}
		if ($venue) {
			$this->db->where('venue_booking.venue_id', $venue);
		}
		if ($sports) {
			$this->db->where('venue_booking.sports_id',$sports);
		}
        if ($date) {
             $date = strftime("%Y-%m-%d", strtotime("$date"));
            $this->db->where('date(venue_booking.date) >=', $date);
        }
        if ($enddate) {
             $enddate = strftime("%Y-%m-%d", strtotime("$enddate"));
            $this->db->where('date(venue_booking.date) <=', $enddate);
        }
		
		$this->db->order_by('booking_hot_offer.id','DESC');
		return $this->db->get()->result_array();


	}
	
	////////////////////////////////// Refer a friend List/////////////////////////////////////////
	public function get_refer_list($city=""){
		$this->db->distinct('users.id');
		$this->db->select('users.id,users.phone_no,SUM(referal_bonus.install_count) as install_count,SUM(referal_bonus.install_coin) as install_coin');
		$this->db->from('users');
		$this->db->join('referal_bonus','referal_bonus.users_id=users.id');
		$this->db->join('user_area','user_area.user_id=referal_bonus.users_id');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		if ($city) {
			$this->db->where('locations.id',$city);
		}
		$this->db->group_by('users.id');
		return $this->db->get()->result_array();


	}
	
	///////////////////////////////////Booked Capacity////////////////////////////////////
	public function get_install_count($id){
		$this->db->select('SUM(install_count) as install_count');
		$this->db->from('referal_bonus');
		$this->db->where('users_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->row();
		return $row1;
	}
	
	/////////////////////////////////// referal booking count ///////////////////////////////////////////
	public function get_referal_booking_count($id){
		$this->db->select('count(user_id) as referal_booking_count');
		$this->db->from('referal_booking_bonus');
		$this->db->where('user_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['referal_booking_count'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// installation count ///////////////////////////////////////////
	public function get_referal_install_count($id){
		$this->db->select('count(installed_user_id) as install_counts');
		$this->db->from('refer_friend_bonus');
		$this->db->where('users_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['install_counts'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// installation bonus coin ///////////////////////////////////////////
	public function get_referal_install_boncoins($id){
		$this->db->select('SUM(install_coin) as install_bonus_coins');
		$this->db->from('referal_bonus');
		$this->db->where('users_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['install_bonus_coins'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// referal booking bonus coin ///////////////////////////////////////////
	public function get_referal_booking_coin($id){
		$this->db->select('SUM(bonus_coin) as booking_bonus');
		$this->db->from('referal_booking_bonus');
		$this->db->where('user_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['booking_bonus'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// referal users city ///////////////////////////////////////////
	public function get_referal_user_city($id){
	    $this->db->distinct('locations.id');
		$this->db->select('locations.location');
		$this->db->from('refer_friend_bonus');
		$this->db->join('user_area','user_area.user_id=refer_friend_bonus.installed_user_id');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		$this->db->where('refer_friend_bonus.users_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['location'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
////////////////////////////////// Refer a friend List/////////////////////////////////////////
	public function get_upcoin_list($city=""){
		$this->db->distinct('buy_coin.id');
		$this->db->select('users.id,users.phone_no,locations.location,buy_coin.rupee,buy_coin.rupee,buy_coin.coin,buy_coin.added_date');
		$this->db->from('buy_coin');
		$this->db->join('users','users.id=buy_coin.users_id');
		$this->db->join('user_area','user_area.user_id=users.id');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		if ($city) {
			$this->db->where('locations.id',$city);
		}
		return $this->db->get()->result_array();


	}
////////////////////////////////// Refer a friend List/////////////////////////////////////////
	public function get_bookcoin_list($city=""){
		$this->db->distinct('booking_payment_mode.id');
		$this->db->select('users.id,users.phone_no,locations.location,venue_booking.booking_id,venue_booking.cost as paid_amount,venue_booking.price as total_amount,venue_booking.coupon_id,venue_booking.venue_id,booking_payment_mode.added_date');
		$this->db->from('booking_payment_mode');
		$this->db->join('venue_booking','venue_booking.booking_id=booking_payment_mode.booking_id');
		$this->db->join('users','users.id=venue_booking.user_id');
		$this->db->join('user_area','user_area.user_id=users.id');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		if ($city) {
			$this->db->where('locations.id',$city);
		}
		return $this->db->get()->result_array();


	}
/////////////////////////////////// referal users city ///////////////////////////////////////////
	public function get_booking_coupon_status($coupon_id){
		$this->db->select('percentage');
		$this->db->from('coupons');
		$this->db->where('coupon_id',$coupon_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['percentage'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// referal users city ///////////////////////////////////////////
	public function get_booking_coupon_value($coupon_id){
		$this->db->select('coupon_value');
		$this->db->from('coupons');
		$this->db->where('coupon_id',$coupon_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['coupon_value'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
/////////////////////////////////// referal users city ///////////////////////////////////////////
	public function get_upupup_share_value($venue_id){
		$this->db->select('share as upupup_share');
		$this->db->from('share_user_app');
		$this->db->where('venue_id',$venue_id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['upupup_share'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	
////////////////////////////////// Refer a friend List/////////////////////////////////////////
	public function get_refund_list($city=""){
		$this->db->distinct('booking_refund.id');
		$this->db->select('booking_refund.id,booking_refund.booking_id,booking_refund.amount,booking_refund.coin,booking_refund.reason,booking_refund.date,booking_refund.added_date,users.name,users.phone_no,locations.location');
		$this->db->from('booking_refund');
		$this->db->join('venue_booking','venue_booking.booking_id=booking_refund.booking_id');
		$this->db->join('users','users.id=venue_booking.user_id');
		$this->db->join('venue','venue.id=venue_booking.venue_id');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->join('locations','locations.id=area.location_id');
		if ($city) {
			$this->db->where('locations.id',$city);
		}
		return $this->db->get()->result_array();
	}
////////////////////////////////// Trainers List/////////////////////////////////////////
	public function get_trainers_list($city="",$area="",$sports="",$start_date="",$end_date="")
	{
		$this->db->select('trainers.*,locations.location');
		$this->db->from('trainers');
		$this->db->join('locations','locations.id=trainers.location_id');
		if ($city) {
			$this->db->where('trainers.location_id',$city);
		}
		if ($area) 
		{
			$this->db->join('trainers_area','trainers_area.trainers_id=trainers.id');
			$this->db->where('trainers_area.area_id',$area);
		}
		if ($sports) 
		{
			$this->db->join('trainers_sports','trainers_sports.trainers_id=trainers.id');
			$this->db->where('trainers_sports.sports_id',$sports);
		}
		if ($start_date) {
			$this->db->where('trainers.added_date >=', $start_date);
		}
		if ($end_date) {
			$this->db->where('trainers.added_date <=', $end_date);
		}
		$this->db->order_by('trainers.id','desc');
		return $this->db->get()->result_array();
	}
/////////////////////////////////// installation count ///////////////////////////////////////////
	public function get_trainer_followers($id){
		$this->db->select('count(id) as count');
		$this->db->from("trainers_followers");
		$this->db->where('trainers_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['count'];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	/////////////////////////////////////////Trainer Sports/////////////////////////////////////////
	public function get_trainer_sports($id){
		$this->db->select('sports.sports');
		$this->db->from('sports');
		$this->db->join("trainers_sports","trainers_sports.sports_id=sports.id");
		$this->db->where('trainers_sports.trainers_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['sports'];
		    }
		}else{
			$array[]="";
		}

		return $array;

	}
	////////////////////////// Sports List///////////////////////////////
	public function sport_list(){
		$this->db->select('id,sports');
		$this->db->from('sports');
		$this->db->where('status',1);
		return $this->db->get()->result_array();
	}
}
