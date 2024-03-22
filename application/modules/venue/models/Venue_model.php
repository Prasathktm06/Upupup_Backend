<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Venue_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}
	public function get_location($id=''){
		$this->db->select('*');
		$this->db->from('locations');
		if($id!=='')
			$this->db->where('id',$id);
			$this->db->where('status',1);
			return $this->db->get()->result();

	}
	public function get_speciality($id=''){
		$this->db->select('*');
		$this->db->from('facilities');
		if($id!=='')
			$this->db->where('id',$id);
		return $this->db->get()->result();
	}
	public function get_sports($id=''){
		$this->db->select('*');
		$this->db->from('sports');
		if($id!=='')
			$this->db->where('id',$id);
			$this->db->where('status',1);
			return $this->db->get()->result();
	}
        public function get_userapp_share($id){
		$this->db->select('*');
		$this->db->from('share_user_app');
		$this->db->where('venue_id',$id);
                
		return $this->db->get()->result();
	}
	
	 public function get_venue_rating($id){
		$this->db->select('*');
		$this->db->from('rate_venue');
		$this->db->where('venue_id',$id);
                
		return $this->db->get()->result_array();
	}
	public function get_vendorapp_share($id){
		$this->db->select('*');
		$this->db->from('share_vendor_app');
		$this->db->where('venue_id',$id);
                
		return $this->db->get()->result();
	}

	public function update_speciality($id,$data){
		return $this->db->update('facilities', $data, array('id' => $id));
	}

	public function add_speciality($data){
		if($this->db->insert('facilities', $data))
			return true;
			else
				return false;
	}

	public function add($data,$table){
		if($this->db->insert($table, $data))
			return $this->db->insert_id() ;
			else
				return false;
	}

	public function update_venue($id,$data){
		return $this->db->update('venue', $data, array('id' => $id));
	}

	public function get_venue_details($id){
		$this->db->select('venue.*,GROUP_CONCAT(facilities.id) as facility ,GROUP_CONCAT(sports.id) as sports,GROUP_CONCAT(court.id) as court_id,venue.morning,venue.evening,venue.amount,venue.status');
		$this->db->from('venue');
		$this->db->join('venue_facilities','venue.id=venue_facilities.venue_id','left');
		$this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
		$this->db->join('sports','sports.id=venue_sports.sports_id','left');
		$this->db->join('facilities','facilities.id=venue_facilities.facility_id','left');
		$this->db->join('venue_court','venue.id=venue_court.venue_id','left');
		$this->db->join('court','court.id=venue_court.court_id','left');
		if($id!=='')
			$this->db->where('venue.id',$id);
			$this->db->group_by('venue.id');
			return $this->db->get()->row();
	}
	////////////////////////////////// Venue List /////////////////////////////////////////
	public function get_venuedetails($venue_manager){
		$this->db->select('venue.venue,locations.location,area.area,venue.id,venue.image,venue.amount,venue.status,venue.phone');
		$this->db->from('venue');
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");

		if($venue_manager!=""){
			$this->db->join("venue_manager","venue_manager.venue_id=venue.id","left");
			$this->db->where('venue_manager.user_id',$venue_manager);

		}


		$this->db->order_by('venue.id','ASC');
		return $this->db->get()->result_array();
	}
	public function get_venueTable($edit,$delete,$venue_manager){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,venue.venue,locations.location,area.area,venue.id,venue.image,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete,venue.amount,venue.status,venue.phone',false);

		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");

		if($venue_manager!=""){
			$this->db->join("venue_manager","venue_manager.venue_id=venue.id","left");
			$this->db->where('venue_manager.user_id',$venue_manager);

		}
		$this->db->from('venue , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('venue',$dir);
		}

		$where = "(venue.venue LIKE '%".$value."%' || locations.location LIKE '%".$value."%' || area.area LIKE '%".$value."%' || venue.phone LIKE '%".$value."%' )";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();

		$this->db->from('venue');
		if($venue_manager!=""){
			$this->db->join("venue_manager","venue_manager.venue_id=venue.id","left");
			$this->db->where('venue_manager.user_id',$venue_manager);

		}
		
		$this->db->join("area","area.id=venue.area_id","left");
		$this->db->join("locations","locations.id=area.location_id","left");

		$where = "(venue.venue LIKE '%".$value."%' || locations.location LIKE '%".$value."%' || area.area LIKE '%".$value."%' || venue.phone LIKE '%".$value."%' )";
		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('venue');
		
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function get_specialityTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,facilities.*,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);

		$this->db->from('facilities , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('facilities',$dir);
		}

		$where = "(facilities.facility LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('facilities');
		$where = "(facilities.facility LIKE '%".$value."%')";

		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('facilities');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}
        public function get_venue_book($id)
	{
		$this->db->select('date,venue_id');
		$this->db->from('venue_booking');
                $this->db->where("date >=",date('Y-m-d'));
		$this->db->where('venue_id',$id);
		return $this->db->get()->result();
	}

	public function delete_venue_speciality($id){
		$this->db->delete('venue_facilities', array('venue_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	public function add_venue_speciality($data){
		if($this->db->insert('venue_facilities', $data))
			return true;
			else
				return false;
	}

	public function delete_venue_sports($id){
		$this->db->delete('venue_sports', array('venue_id' => $id));
		return ($this->db->affected_rows() == 1);
	}

	public function add_venue_sports($data){
		if($this->db->insert('venue_sports', $data))
			return true;
			else
				return false;
	}
	public function delete($id){
		$this->db->delete('venue', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	public function get_court()
	{
		$this->db->select('*');
		$this->db->from('court');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	public function get_court2($court,$venue_id)
	{
		$this->db->select('court.id');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id','left');
		$this->db->where('venue_court.venue_id',$venue_id);
		$this->db->where('court.court',$court);
		return $this->db->get()->row();
	}
	public function delete_venue_court($id)
	{
		$this->db->delete('venue_court', array('venue_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	public function delete_speciality($id)
	{
		$this->db->delete('facilities', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	public function get_role($id)
	{
		$this->db->select('*');
		$this->db->from('role');
		$this->db->join('user_role','user_role.role_id=role.role_id');
		$this->db->where('user_role.user_id',$id);
		return $this->db->get()->row();
	}
	public function get_facility_id($facility)
	{
		$this->db->select('id');
		$this->db->from('facilities');
		$this->db->where('facility',$facility);
		return $this->db->get()->row();
	}
	public function get_role2($role)
	{
		$this->db->select('role_id');
		$this->db->from('role');
		$this->db->where('name',$role);
		return $this->db->get()->row();
	}
        public function get_inactive_court($id)
	{
		$this->db->select('*');
		$this->db->from('inactive_court');
		if($id!=='')
			$this->db->where('venue_id',$id);
		return $this->db->get()->result();
	}

	public function get_court_id($court)
	{
		$this->db->select('id');
		$this->db->from('court');
		$this->db->where('court',$court);
		return $this->db->get()->row();
	}

	public function get_area_id($area)
	{
		$this->db->select('id');
		$this->db->from('area');
		$this->db->where('area',$area);
		return $this->db->get()->row();
	}
	public function get_user_id($user)
	{
		$this->db->select('user_id');
		$this->db->from('user');
		$this->db->where('email',$user);
		return $this->db->get()->row();
	}
	public function add_court_time($data)
	{
		if($this->db->insert('court_time', $data))
			return true;
			else
				return false;
	}
	public function get_sports_id($sports)
	{
		$this->db->select('id');
		$this->db->from('sports');
		$this->db->where('sports',$sports);
		return $this->db->get()->row();
	}
	public function add_court($data)
	{
		if($this->db->insert('court', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	public function get_users()
	{
		$this->db->select('user.user_id as id,name');
		$this->db->from('user');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	public function get_venue($venue)
	{
		$this->db->select('id');
		$this->db->from('venue');
		$this->db->where('venue',$venue);
		return $this->db->get()->row();
	}
	public function get_area($area)
	{
		$this->db->select('id');
		$this->db->from('area');
		$this->db->where('area',$area);
		return $this->db->get()->row();
	}
	public function get_facility($facility_id)
	{
		$this->db->select('id');
		$this->db->from('facilities');
		$this->db->where('facility',$facility_id);
		return $this->db->get()->row();
	}
	public function get_sport($sports)
	{
		$this->db->select('id');
		$this->db->from('sports');
		$this->db->where('sports',$sports);
		return $this->db->get()->row();
	}
	public function get_venue_location($area_id)
	{
		$this->db->select('locations.id');
		$this->db->from('area');
		$this->db->join('locations','area.location_id=locations.id');
		$this->db->where('area.id',$area_id);
		return $this->db->get()->row();
	}
	public function get_venue_area($location_id)
	{
		$this->db->select('area  ,id ');
		$this->db->from('area');

		$this->db->where('area.location_id',$location_id);
		$this->db->where('status',1);

		return $this->db->get()->result();
	}
	public function add_holidays($data)
	{
		if($this->db->insert('holidays', $data))
			return true;
			else
				return false;
	}
	public function delete_holidays($id)
	{
		$this->db->delete('holidays', array('id'=>$id));
		return ($this->db->affected_rows() == 1);
	}
	public function get_holidays($id)
	{
		$this->db->select('*');
		$this->db->from('holidays');
		$this->db->where('venue_id',$id);
		$this->db->order_by('date','DESC');
		return $this->db->get()->result();
	}

	public function delete_holiday($year,$month,$venue)
	{
		$this->db->delete('holidays', array('venue_id' => $venue,'month'=>$month,'year'=>$year));
		return ($this->db->affected_rows() == 1);
	}
	public function get_venueUsersTable($edit,$delete,$venue_id)
	{
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,user.user_id as id,user.name as name,email,role.name as role,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);

		$this->db->join("user_role","user_role.user_id=user.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$this->db->where('venue_manager.venue_id',$venue_id);
		$this->db->from('user , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('venue',$dir);
		}

		$where = "(user.name LIKE '%".$value."%' OR role.name LIKE '%".$value."%' OR user.email LIKE '%".$value."%')";

		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('user');
		$where = "(user.name LIKE '%".$value."%' OR role.name LIKE '%".$value."%' OR user.email LIKE '%".$value."%')";
			$this->db->join("user_role","user_role.user_id=user.user_id","left");
			$this->db->join("role","role.role_id=user_role.role_id","left");
                        $this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$this->db->where($where);
$this->db->where('venue_manager.venue_id',$venue_id);
		$query = $this->db->get();


		$this->db->from('user');
		$this->db->join("user_role","user_role.user_id=user.user_id","left");
			$this->db->join("role","role.role_id=user_role.role_id","left");
                        $this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function get_image($id)
	{
		$this->db->select('*');
		$this->db->from('venue_gallery');
		$this->db->where('venue_id',$id);
		return $this->db->get()->result();
	}

	public function add_gallery($data)
	{
		if($this->db->insert('venue_gallery', $data))
			return true;
			else
				return false;
	}
	public function get_venue_sports($id)
	{
		$this->db->select('GROUP_CONCAT(sports_id) as sports_id');
		$this->db->from('venue_sports');
		$this->db->where('venue_id',$id);
		return $this->db->get()->row();
	}
	public function check_holiday($venue,$date)
	{
		$this->db->select('id');
		$this->db->from('holidays');
		$this->db->where('date',$date);
		$this->db->where('venue_id',$venue);
		return $this->db->get()->row();
	}
        public function check_bookeddays($venue_id)
	{
		$this->db->select('date');
		$this->db->from('venue_booking');
       
        $this->db->where("payment_mode",1);
        $this->db->where("venue_id",$venue_id);
	$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}

        public function check_booking($venue_id,$date)
	{
		$this->db->select('id');
		$this->db->from('venue_booking');
        $this->db->where("payment_mode",1);
        $this->db->where("date", $date);
        $this->db->where("venue_id",$venue_id);
	    return $this->db->get()->result();
	}
	////////////////////////////////Venue managers//////////////////////////////////////////////
	public function venue_managers()
	{
		$this->db->select('user.user_id,user.name,user.email,user.phone,role.name as rolename,role.slug,venue.venue,user.status,venue.id as venue_id');
		$this->db->from('user');
		$this->db->join("user_role","user_role.user_id=user.user_id","left");
		$this->db->join("role","role.role_id=user_role.role_id","left");
		$this->db->join("venue_manager","venue_manager.user_id=user.user_id","left");
		$this->db->join("venue","venue.id=venue_manager.venue_id","left");
		/*$this->db->join("court_manager_courts","court_manager_courts.user_id=user.user_id","left");
		$this->db->join("court","court.id=court_manager_courts.court_id","left");*/

		$this->db->where("(role.venue_users = '1' OR role.venue_users='2')");
		$this->db->order_by('user.user_id','DESC');
		return $this->db->get()->result_array();
	}
	//////////////////////////Courts Assigned/////////////////////////////////////////////////
	public function court_assigned($user_id)
	{
		$this->db->select('*');
		$this->db->from('court_manager_courts');
		$this->db->join("court","court.id=court_manager_courts.court_id","left");

		$this->db->where('court_manager_courts.user_id',$user_id);
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
	/////////////////////////////////////////Venue List////////////////////////////////////////
	public function get_venues(){
		$this->db->select('venue.id as venue_id,venue.venue');
		$this->db->from('venue');
		return $this->db->get()->result_array();
	}
	///////////////////Court Based on venue/////////////////////////////////////////////
	public function get_court_venue($venue_id,$status=""){
		$this->db->select('court.id,court.court,court.intervel');
		$this->db->from('venue_court');
		$this->db->join("court","court.id=venue_court.court_id","left");
		$this->db->where('venue_court.venue_id',$venue_id);
		if ($status) {
			$this->db->where('court.status',$status);
		}

		return $this->db->get()->result_array();
	}
	/////////////////Delete Venue Managers///////////////////////////////
	public function venue_user_delete($id){
		 $this->db->where('user_id',$id);
		 $row=$this->db->delete('user');
		 return $row;
	}
	///////////////////////////////////////Update Venue manager Venue////////////////////////////////////////
	public function venue_manager_update($id,$update_array){
		$row=$this->db->update('venue_manager',$update_array,array('user_id'=>$id));
		return $row;
	}
	/////////////////////////////Venue manager Venue////////////////////////////////////////
	public function venu_manager_venue($user_id){
		$this->db->select('venue_id');
		$this->db->from('venue_manager');
		$this->db->where('user_id',$user_id);

		$query 	= $this->db->get();
		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array = $row['venue_id'];
		    }
		}else{
			$array="";
		}

		return $array;
	}
	/////////////////////////////Venue manager Role////////////////////////////////////////
	public function venu_manager_role($user_id){
		$this->db->select('role_id');
		$this->db->from('user_role');
		$this->db->where('user_id',$user_id);

		$query 	= $this->db->get();
		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array = $row['role_id'];
		    }
		}else{
			$array="";
		}

		return $array;
	}
        ///////////////// Add User app share/////////////////////////
	
	public function insert_userapp_share($data)
	{
		if($this->db->insert('share_user_app', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	////////////////// Add vendor app share////////////////////////////////
	public function insert_vendorapp_share($data)
	{
		if($this->db->insert('share_vendor_app', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}

	//////////////// Change Share Status////////////////////////////
       public function update_usershare($id,$data){
		return $this->db->update('share_user_app', $data, array('id' => $id));
	}
	public function update_vendorshare($id,$data) {
		return $this->db->update('share_vendor_app', $data, array('id' => $id));
	}
        ///////////////////////////////////////// User App Details ////////////////////////////////////////
	public function get_userapp_details($venue_id,$id){
		$this->db->select('id,venue_id,share');
		$this->db->from('share_user_app');
		$this->db->where('id',$id);
		$this->db->where('venue_id',$venue_id);

		return $this->db->get()->row();
	}
    ///////////////////////////////////////// Vendor App Details ////////////////////////////////////////
	public function get_vendorapp_details($venue_id,$id){
		$this->db->select('id,venue_id,share');
		$this->db->from('share_vendor_app');
		$this->db->where('id',$id);
		$this->db->where('venue_id',$venue_id);

		return $this->db->get()->row();
	}
        //////////////// update user app share////////////////////////////
       public function update_userapp_share($id,$data){
		return $this->db->update('share_user_app', $data, array('id' => $id));
	}
	//////////////// update vendor app share////////////////////////////
	public function update_vendorapp_share($id,$data){
		return $this->db->update('share_vendor_app', $data, array('id' => $id));
	}
        //////////////// Delete user app share////////////////////////////
	public function delete_userapp_share($id){
		$this->db->delete('share_user_app', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	//////////////// Delete vendor app share////////////////////////////
	public function delete_vendorapp_share($id){
		$this->db->delete('share_vendor_app', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
        ////////////////////////////////// Venue offer Details /////////////////////////////////////////
	public function get_venue_offer($id){
                $this->db->distinct('offer.id');
		$this->db->select('offer.*');
		$this->db->from('offer');
                $this->db->where('offer.venue_id ',$id);
		$this->db->order_by('offer.id','DESC');
		return $this->db->get()->result_array();
	}
       		/////////////////////////////////// offer days////////////////////////////////////
	public function get_venue_offerdays($id){
		$this->db->distinct('offer_time.offer_id');
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
      /////////////////////////////////// offer court////////////////////////////////////
	public function get_venue_offercourt($id){
		$this->db->distinct('court.court');
		$this->db->select('court.court');
		$this->db->from('court');
		$this->db->join("offer_court","offer_court.court_id=court.id","left");
		$this->db->where('offer_court.offer_id',$id);
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
        /////////////////////////////////// offer sports ////////////////////////////////////
	public function get_venue_offersports($id){
		$this->db->distinct('sports.sports');
		$this->db->select('sports.sports');
		$this->db->from('sports');
		$this->db->join("court","court.sports_id=sports.id","left");
		$this->db->join("offer_court","offer_court.court_id=court.id","left");
		$this->db->where('offer_court.offer_id',$id);
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
        ////////////////////////////////// Venue offer Details /////////////////////////////////////////
	public function get_venue_inactive($id){
		$this->db->distinct('inactive_court.id');
		$this->db->select('inactive_court.*,court.court');
		$this->db->from('inactive_court');
		$this->db->join("court","court.id=inactive_court.court_id","left");
        $this->db->where('inactive_court.venue_id ',$id);
        $this->db->where("inactive_court.sdate >=",date('Y-m-d'));

		$this->db->order_by('inactive_court.sdate','ASC');
		return $this->db->get()->result_array();
	}
	
	
	////////////////////////////////// Venue hot offer Details /////////////////////////////////////////
	public function get_venue_hot_offer($id){
	    $this->db->select('hot_offer.id as hot_id,hot_offer.name as hot_name,hot_offer.venue_id,hot_offer.date as hot_date,hot_offer.precentage as hot_percentage,hot_offer.status');
	    $this->db->from('hot_offer');
	    $this->db->where("venue_id",$id);
	    $this->db->order_by('hot_offer.date','desc');
	    return $this->db->get()->result_array();
	}
	
	/////////////////////////////////// hot offer sports////////////////////////////////////
	public function get_hot_sports($hot_id){
		$this->db->distinct('sports.sports');
		$this->db->select('sports.sports');
		$this->db->from('sports');
		$this->db->join('hot_offer_court','hot_offer_court.sports_id=sports.id');
	        $this->db->where("hot_offer_court.hot_offer_id",$hot_id);
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
/////////////////////////////////// hot offer courts////////////////////////////////////
	public function get_hot_courts($hot_id){
		$this->db->distinct('court.court');
		$this->db->select('court.court');
		$this->db->from('court');
		$this->db->join('hot_offer_court','hot_offer_court.court_id=court.id');
	        $this->db->where("hot_offer_court.hot_offer_id",$hot_id);
	        $this->db->order_by('court.court','asc');
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

/////////////////////////////////// hot offer slots time ////////////////////////////////////
	public function get_hot_slots($hot_id){ 
		$this->db->distinct('court_time_intervel.id');
		$this->db->select('TIME_FORMAT(court_time_intervel.time, "%h:%i %p") as time');
		$this->db->from('court_time');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
	    	$this->db->join('hot_offer_slot','hot_offer_slot.court_time_intervel_id=court_time_intervel.id');
		$this->db->where("hot_offer_slot.hot_offer_id",$hot_id);
		$this->db->order_by('court_time_intervel.time','asc');
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['time'];
		    }
		}else{
			$array[]="";
		}

		return $array;
		
	}
//////////////////////////////////////////////////////////////////////////
}
