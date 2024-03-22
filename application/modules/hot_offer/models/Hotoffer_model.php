<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Hotoffer_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}
	   public function get_locations()
	{
		$this->db->select('locations.location,locations.id');
		$this->db->from('locations');
		$this->db->where('locations.status',1);
		$this->db->order_by('locations.location','asc');
		return $this->db->get()->result();
	}
	public function venue_list($city="",$dates="",$percentage="")
	{
	$this->db->distinct('venue.id');
	$this->db->select('venue.id');
	$this->db->from('venue');
	$this->db->join('area','area.id=venue.area_id');
	$this->db->join('locations','locations.id=area.location_id');
	$this->db->join('venue_court','venue_court.venue_id=venue.id');
    	$this->db->join('court','court.id=venue_court.court_id');
    	$this->db->join('offer_court','offer_court.court_id=court.id');
    	$this->db->join('offer','offer.id=offer_court.offer_id');
	if ($city!="") {
		$this->db->where('locations.id',$city);
		}
	if ($dates!="") {
		$this->db->where('offer.end >=',$dates);
		}
	if ($dates!="") {
		$this->db->where('offer.percentage >=',$percentage);
		}
	$this->db->where('venue.status',1);
	return $this->db->get()->result();
	}
	public function insert_setting($data)
	{
		if($this->db->insert('hot_offer_setting', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	////////////// hot offer setting list //////////////
	public function get_setting(){
		$this->db->select('hot_offer_setting.id,hot_offer_setting.name,locations.location,locations.id as location_id,hot_offer_setting.dates,hot_offer_setting.percentage,hot_offer_setting.status');
		$this->db->from('hot_offer_setting');
		$this->db->join('locations','locations.id=hot_offer_setting.location_id');
		$this->db->order_by('hot_offer_setting.id','desc');
		return $this->db->get()->result_array();
	}
	public function get_hot_settings(){
		$this->db->select('hot_offer_setting.id,hot_offer_setting.name');
		$this->db->from('hot_offer_setting');
		$this->db->join('locations','locations.id=hot_offer_setting.location_id');
		return $this->db->get()->result();
	}
	public function get_not_settings(){
		$this->db->select('id,name');
		$this->db->from('hot_not_setting');
		$this->db->where("status",1);
		return $this->db->get()->result();
	}
	///////////////// add hot offer notification setting  //////////////////////
	public function insert_not_setting($data)
	{
		if($this->db->insert('hot_offer_notification', $data))
			return  $this->db->insert_id();
			else
		    	return false;
	}
	////////////// notification setting list /////////////////
	public function get_notification_list(){
		$this->db->select('hot_offer_notification.id,hot_offer_notification.status,locations.id as location_id,locations.location,hot_not_setting.name as notification_name,hot_offer_notification.time1,hot_offer_notification.time2');
		$this->db->from('hot_offer_notification');
		$this->db->join('locations','locations.id=hot_offer_notification.location_id');
		$this->db->join('hot_not_setting','hot_not_setting.id=hot_offer_notification.hot_not_setting_id');
		return $this->db->get()->result_array();
	}
	/////// update status /////////
	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
	/////////// delete hot notification  /////////// 
	public function delete_notification($id){
		$this->db->delete('hot_offer_notification', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// notification details based on id /////////////////
	public function get_notification_set($id){
		$this->db->select('hot_offer_notification.id,hot_offer_notification.status,locations.location,locations.id as location_id,hot_not_setting.name as notification_name,hot_not_setting.id as hot_not_setting_id,hot_offer_notification.time1,hot_offer_notification.time2');
		$this->db->from('hot_offer_notification');
		$this->db->join('locations','locations.id=hot_offer_notification.location_id');
		$this->db->join('hot_not_setting','hot_not_setting.id=hot_offer_notification.hot_not_setting_id');
		$this->db->where("hot_offer_notification.id",$id);
		return $this->db->get()->row();
	}
	////////////////// update hot offer notification  /////////////////////////
	public function update_notifys($data,$id){
		return $this->db->update('hot_offer_notification', $data, array('id' => $id));
	}
	/////////////////////////Change hot offer Status/////////////////////////////////////
	public function update_offerdata($hot_id,$data){
	
		return $this->db->update('hot_offer', $data, array('id' => $hot_id));
	}
	
	////////////// Hot Offer Settings details /////////////////
	public function get_hot_details($id,$date){
		date_default_timezone_set("Asia/Kolkata");
		$this->db->select('hot_offer_setting.id');
		$this->db->from('hot_offer_setting');
		$this->db->where("hot_offer_setting.id",$id);
		$this->db->where("hot_offer_setting.dates >",$date);
		return $this->db->get()->result();
	}
	
	/////////// delete hot settings  /////////// 
	public function delete_hotset($id){
		$this->db->delete('hot_offer_setting', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	
	////////////// hot offer setting filter list //////////////
	public function get_filterset($city,$day,$percentage){
		$this->db->select('hot_offer_setting.id,hot_offer_setting.name,locations.location,hot_offer_setting.dates,hot_offer_setting.percentage,hot_offer_setting.status');
		$this->db->from('hot_offer_setting');
		$this->db->join('locations','locations.id=hot_offer_setting.location_id');
		if($city!=0){
			$this->db->where("hot_offer_setting.location_id",$city);
		}
		if($day!=0){
			$this->db->where("hot_offer_setting.dates",$day);
		}
		if($percentage!=0){
			$this->db->where("hot_offer_setting.percentage",$percentage);
		}
		
		
		
		$this->db->order_by('hot_offer_setting.id','desc');
		return $this->db->get()->result_array();
	}
	
/////////////////////////// active active notification setting ////////////////////
      public function get_active_setting($location_id)
	   {
		$this->db->select('id');
		$this->db->from('hot_offer_notification');
		$this->db->where('location_id',$location_id);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	
/////////////////////////// active hot offer setting ////////////////////
      public function get_active_hotsetting($location_id)
	   {
		$this->db->select('id');
		$this->db->from('hot_offer_setting');
		$this->db->where('location_id',$location_id);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
