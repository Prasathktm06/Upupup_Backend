<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI_ACL
 * 
 * Yet another ACL implementation for CodeIgniter. More specifically this is 
 * a role-based access control list for CodeIgniter.
 * 
 * @package		ACL
 * @author		William Duyck <fuzzyfox0@gmail.com>
 * @copyright	Copyright (c) 2012, William Duyck
 * @license		http://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 * @since		2012.12.23
 */

// ------------------------------------------------------------------------

/**
 * ACL Model
 * 
 * Provides a set of simple functions for interacting with data relating to 
 * user roles and permissions
 * 
 * @package		ACL
 * @subpackage	Models
 * @author		William Duyck <fuzzyfox0@gmail.com>
 *
 * @todo	write a unit test suite for this model
 */
class Offer_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	
	public function get_details($id){
		$this->db->select("offer.id,DATE_FORMAT(`start`, '%m/%d/%Y ')as start,DATE_FORMAT(`end`, '%m/%d/%Y ') as end,percentage,venue_id,offer,offer.image,offer.status ");
		$this->db->from('offer');
		$this->db->join("offer_court","offer.id=offer_court.offer_id");
	     $this->db->join("venue_court","offer_court.court_id=venue_court.court_id");
		$this->db->join("venue","venue_court.venue_id=venue.id");
		$this->db->where('offer.id',$id);
		return $this->db->get()->row();
	}
	
	public function get_venue($id=''){
		$this->db->select('*');
		$this->db->from('venue');
		if($id!=='')
			$this->db->where('id',$id);
			return $this->db->get()->result();
	}
	
	public function update_offer($data,$id){
		return $this->db->update('offer', $data, array('id' => $id));
	}
	
	public function add_offer($data){
		if($this->db->insert('offer', $data))
			return  $this->db->insert_id();
			else
				return false;
	}
   public function get_venue_sports($venue_id)
	{
		$this->db->select('sports.sports,sports.id');
		$this->db->from('sports');
		$this->db->join('venue_sports','venue_sports.sports_id=sports.id');
		$this->db->where('venue_sports.venue_id',$venue_id);
		return $this->db->get()->result();
	}
//Check offer is already added
public function get_offerexist($venue_id,$court_id,$date,$time,$nameOfDay){
		$this->db->select('offer_time.id');
		$this->db->from('offer_time');
		$this->db->join('offer','offer.id=offer_time.offer_id');
		$this->db->join('offer_court','offer_court.offer_id=offer.id');
        $this->db->where("offer_time.day",$nameOfDay);
		$this->db->where("offer_time.time",$time);
		$this->db->where('offer.start <=',$date);
		$this->db->where('offer.end >=',$date);
		$this->db->where("offer_court.court_id",$court_id);
		$this->db->where("offer.venue_id",$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
	//Check offer is already added
public function get_offerexists($venue_id,$court_id,$date,$time,$nameOfDay,$id){
		$this->db->select('offer_time.id');
		$this->db->from('offer_time');
		$this->db->join('offer','offer.id=offer_time.offer_id');
		$this->db->join('offer_court','offer_court.offer_id=offer.id');
        $this->db->where("offer_time.day",$nameOfDay);
		$this->db->where("offer_time.time",$time);
		$this->db->where('offer.start <=',$date);
		$this->db->where('offer.end >=',$date);
		$this->db->where("offer_court.court_id",$court_id);
		$this->db->where("offer.venue_id",$venue_id);
		$this->db->where("offer.id !=",$id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

public function insert_offer($data)
	{
		if($this->db->insert('offer', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
public function insert_offercourt($datas)
	{
		if($this->db->insert('offer_court', $datas))
			return  $this->db->insert_id();
			else
		    return false;
	}
public function insert_offertime($datas)
	{
		if($this->db->insert('offer_time', $datas))
			return  $this->db->insert_id();
			else
		    return false;
	}
	
	public function get_offerTable($edit,$delete,$venue_id=''){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
                $this->db->distinct('offer.id');
		$this->db->select('@s:=@s+1 serial_number,offer.id as id,offer.offer as offer,DATE_FORMAT(offer.start, "%d-%m-%Y") as start,DATE_FORMAT(offer.end, "%d-%m-%Y") as end,TIME_FORMAT(offer.start_time, "%r") as start_time,TIME_FORMAT(offer.end_time, "%r") as end_time,offer.status as status,venue.venue,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);
	    $this->db->join("offer_court","offer.id=offer_court.offer_id");
	     $this->db->join("venue_court","offer_court.court_id=venue_court.court_id");
		$this->db->join("venue","venue_court.venue_id=venue.id");
		$this->db->from('offer , (SELECT @s:='.$_GET['start'].') AS s');
                $this->db->order_by('offer.start','asc');
		if($venue_id!='')
		$this->db->where('venue.id',$venue_id);
		if($column==1)
		{
			$this->db->order_by('offer',$dir);
		}
		
		$where = "(offer.offer LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();
		
		
		$this->db->from('offer');
		$where = "(offer.offer LIKE '%".$value."%')";
		
		$this->db->where($where);
		 $this->db->join("offer_court","offer.id=offer_court.offer_id");
	     $this->db->join("venue_court","offer_court.court_id=venue_court.court_id");
		$this->db->join("venue","venue_court.venue_id=venue.id");
		$query = $this->db->get();
		
		
		$this->db->from('offer');
		 $this->db->join("offer_court","offer.id=offer_court.offer_id");
	     $this->db->join("venue_court","offer_court.court_id=venue_court.court_id");
		$this->db->join("venue","venue_court.venue_id=venue.id");
		$query1 = $this->db->get();
		
		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();
		
		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];
		
		return $result;
	}
	
	public function delete_offer($id){
		$this->db->delete('offer', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
public function delete_offercourt($id){
		$this->db->delete('offer_court', array('offer_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
public function delete_offertime($id){
		$this->db->delete('offer_time', array('offer_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
public function get_offerdetails($id)
	{
		$this->db->select('offer.id,offer.offer,offer.amount,offer.percentage,DATE_FORMAT(offer.start, "%d-%m-%Y") as start,DATE_FORMAT(offer.end, "%d-%m-%Y") as end,TIME_FORMAT(offer.start_time, "%r") as start_time,TIME_FORMAT(offer.end_time, "%r") as end_time,offer.status');
		$this->db->from('offer');
        $this->db->where("offer.id",$id);
		return $this->db->get()->row();
	}
public function get_offercourt($id)
	{
		$this->db->distinct('court.court');
		$this->db->select('court.court,court.id');
		$this->db->from('offer');
		$this->db->join('offer_court','offer_court.offer_id=offer.id');
		$this->db->join('court','court.id=offer_court.court_id');
        $this->db->where("offer.id",$id);
		return $this->db->get()->result();
	}
public function get_offerdays($id)
	{
		$this->db->distinct('day');
		$this->db->select('day');
		$this->db->from('offer_time');
        $this->db->where("offer_id",$id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	//////////////////////////////////Users List/////////////////////////////////////////////////
	public function users_list($area_id)
	{
		$this->db->select('users.device_id,users.id');
		$this->db->from('user_area');
		$this->db->join('users','users.id=user_area.user_id');
		$this->db->where('user_area.area_id',$area_id);
		$this->db->group_by('user_area.user_id');
		return $this->db->get()->result_array();
	}
	////////////////////////////////Venue details///////////////////////////////////
	public function venue_details($id){
		$this->db->select('venue.id as venue_id,venue.venue,venue.morning,venue.evening,venue.description,venue.cost,venue.phone,venue.address,venue.image,venue.lat,venue.lon,venue.area_id,area.area');
		$this->db->from('venue');
		$this->db->join('area','area.id=venue.area_id');
		$this->db->where('venue.id',$id);
		return $this->db->get()->row();
	}
	
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

	public function venue_court($id){
		$this->db->select('court.id,court.court,court.cost,court.sports_id');
		$this->db->from('venue_court');
		$this->db->join('court','court.id=venue_court.court_id');
		$this->db->where('venue_court.venue_id',$id);
		 
		return $row1	= $this->db->get()->result_array();
		
	}

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

	public function get_venue_court($venue_id)
	{
		$this->db->select('court.court,court.id,court.sports_id as sports_ids');
		$this->db->from('venue_court');
		$this->db->join('court','court.id=venue_court.court_id');
		$this->db->where('venue_court.venue_id',$venue_id);
		return $this->db->get()->result();
	}

	public function add_offer_court($data)
	{
		if($this->db->insert('offer_court', $data))
			return  $this->db->insert_id();
			else
				return false;
	}

	public function get_offer_court($offer)
	{
		$this->db->select('GROUP_CONCAT(court.id) as court_id');
		$this->db->from('offer_court');
		$this->db->join('court','court.id=offer_court.court_id');
		$this->db->where('offer_court.offer_id',$offer);
		return $this->db->get()->row();
	}

	public function delete_offer_court($offer)
	{
		$this->db->delete('offer_court', array('offer_id' => $offer));
		return ($this->db->affected_rows() == 1);
	}
	///////////////////////////////////////////////////////////////////////////////////////
	
	public function add_offer_time($data)
	{
		if($this->db->insert('offer_time', $data))
			return  $this->db->insert_id();
			else
				return false;
	}
	public function delete_offer_time($offer)
	{
		$this->db->delete('offer_time', array('offer_id' => $offer));
		return ($this->db->affected_rows() == 1);
	}
	public function get_offer_time($offer)
	{
		$this->db->select('start_time,end_time');
		$this->db->from('offer_time');
		
		$this->db->where('offer_id',$offer);
		return $this->db->get()->result();
	}
	public function delete_offer_daily()
	{
		$this->db->where('date(end)<',date('Y:m:d'));
		$this->db->delete('offer');

		return ($this->db->affected_rows() == 1);
	}
		public function offer_unique($offer,$id,$venue_id)
	{
		$this->db->select('offer');
		$this->db->from('offer');
		$this->db->join('offer_court','offer_court.offer_id=offer.id','left');

		$this->db->where('offer.offer',$offer);
		$this->db->join('venue_court','venue_court.court_id=offer_court.court_id','left');

		
		if($venue_id!=''){
		$this->db->where('offer.id !=',$id);
		$this->db->where('venue_court.venue_id',$venue_id);
	}else{
		$this->db->where('venue_court.venue_id',$id);
	}
		return $this->db->get()->result();
	}

	public function court_offer_check($id)
	{
		$this->db->select('court.court,court.id');
		$this->db->from('court');
		$this->db->join('offer_court','offer_court.court_id=court.id','left');

		$this->db->where('offer_court.court_id =',$id);
		return $this->db->get()->row();
	}
	public function check_offer_court($court,$start,$end,$dstart,$dend)
	{
		$this->db->select('offer_time.id');
		$this->db->from('offer_time');
		$this->db->join('offer_court','offer_court.offer_id=offer_time.offer_id','left');
		$this->db->join('offer','offer.id=offer_time.offer_id','left');
		$this->db->where('offer_court.court_id =',$court);
		$this->db->where('offer_time.start_time <=',$start);
		$this->db->where('offer_time.end_time >=',$end);
		$this->db->where('offer.start <=',$dstart);
		$this->db->where('offer.end >=',$dend);
		return $this->db->get()->result();
	}
         public function update_offerdata($id,$data){
		return $this->db->update('offer', $data, array('id' => $id));
	}
        public function get_courtlistsof($sports_id,$venue_id)
	{
		$this->db->select('court.id,court.court,court.cost');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
                $this->db->where("court.sports_id",$sports_id);
                $this->db->where("venue_court.venue_id",$venue_id);
		return $this->db->get()->result();
	}
        /////////////////////////////////////////court list based on sports and vaenue ////////////////////////////////////////
	public function get_courtlistoff($venue_id,$sports_id){
		$this->db->select('court.id as courts_id,court.court as court_name');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
                $this->db->where("court.sports_id",$sports_id);
                $this->db->where("venue_court.venue_id",$venue_id);
		return $this->db->get()->result_array();
	}
       /////////////////////////////////////////court name and cost based on court id ////////////////////////////////////////
    public function get_courtdataoff($court_id)
	{
		$this->db->select('court.id,court.court,court.cost');
		$this->db->from('court');
        $this->db->where("court.id",$court_id);
		return $this->db->get()->result();
	}
/////////////////////////////////////////  ////////////////////////////////////////
        public function get_courtofspt($sports_id,$venue_id)
	{
		$this->db->select('court.id,court.court,court.cost');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
        $this->db->where("court.sports_id",$sports_id);
        $this->db->where("venue_court.venue_id",$venue_id);
		return $this->db->get()->result();
	}
}

