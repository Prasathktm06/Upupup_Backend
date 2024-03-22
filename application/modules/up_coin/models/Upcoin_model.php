<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Upcoin_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}
    public function insert_upcoin_set($data)
	{
		if($this->db->insert('upcoin_setting', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	
/////////////////////////// upcoin setting start ////////////////////
      public function get_upcoin_set()
	   {
		$this->db->select('id,coin,rupee,status');
		$this->db->from('upcoin_setting');
		$this->db->where('status',1);
		$result1=$this->db->get()->result_array();

		$this->db->select('id,coin,rupee,status');
		$this->db->from('upcoin_setting');
		$this->db->where('status',0);
		$result2=$this->db->get()->result_array();

		$data = array_merge_recursive($result1, $result2);
		return $data;
	}
/////////////////////////// upcoin setting end ////////////////////

/////// update status /////////
	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
//////////////////// fetch buy coin settings ////////////////////	
public function get_buycoin_set()
	   {
		$this->db->select('buycoin_setting.id,locations.location,buycoin_setting.start_date,buycoin_setting.end_date,buycoin_setting.rupee,buycoin_setting.coin,buycoin_setting.status,buycoin_setting.block_status');
		$this->db->from('buycoin_setting');
		$this->db->join('locations','locations.id=buycoin_setting.location_id');
		$this->db->where('buycoin_setting.status',1);
		$result1=$this->db->get()->result_array();
		
		$this->db->select('buycoin_setting.id,locations.location,buycoin_setting.start_date,buycoin_setting.end_date,buycoin_setting.rupee,buycoin_setting.coin,buycoin_setting.status,buycoin_setting.block_status');
		$this->db->from('buycoin_setting');
		$this->db->join('locations','locations.id=buycoin_setting.location_id');
		$this->db->where('buycoin_setting.status',0);
		$result2=$this->db->get()->result_array();
		$data = array_merge_recursive($result1, $result2);
		return $data;

	}
//////////////////// fetch buy coin settings based on city ////////////////////	
public function get_buycoin_filter($city='')
	   {
		$this->db->select('buycoin_setting.id,locations.location,buycoin_setting.start_date,buycoin_setting.end_date,buycoin_setting.rupee,buycoin_setting.coin,buycoin_setting.status,buycoin_setting.block_status');
		$this->db->from('buycoin_setting');
		$this->db->join('locations','locations.id=buycoin_setting.location_id');
		if($city!=''){
		    $this->db->where('buycoin_setting.location_id',$city);
		}
		$this->db->where('buycoin_setting.status',1);
		$result1=$this->db->get()->result_array();
		
		$this->db->select('buycoin_setting.id,locations.location,buycoin_setting.start_date,buycoin_setting.end_date,buycoin_setting.rupee,buycoin_setting.coin,buycoin_setting.status,buycoin_setting.block_status');
		$this->db->from('buycoin_setting');
		$this->db->join('locations','locations.id=buycoin_setting.location_id');
		if($city!=''){
		    $this->db->where('buycoin_setting.location_id',$city);
		}
		$this->db->where('buycoin_setting.status',0);
		$result2=$this->db->get()->result_array();
		$data = array_merge_recursive($result1, $result2);
		return $data;

	}
//////////////////////// locations //////////////////////////
   public function get_locations()
	{
		$this->db->select('locations.location,locations.id');
		$this->db->from('locations');
		$this->db->where('locations.status',1);
		$this->db->order_by('locations.location','asc');
		return $this->db->get()->result();
	}
//////////////// insert buy coin data //////////////////
public function insert_buycoin_set($data)
	{
		if($this->db->insert('buycoin_setting', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	
/////////////////////////// active upcoin setting start ////////////////////
      public function get_active_upcoin_setting()
	   {
		$this->db->select('id,coin,rupee,status');
		$this->db->from('upcoin_setting');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
/////////////////////////// upcoin setting end ////////////////////	

//////////////// insert Booking Bonus data //////////////////
public function insert_booking_bonus($data)
	{
		if($this->db->insert('booking_bonus_setting', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}	

//////////////////// fetch buy coin settings ////////////////////	
public function get_booking_bonus_list()
	   {
		$this->db->select('booking_bonus_setting.id,locations.location,booking_bonus_setting.start_date,booking_bonus_setting.end_date,booking_bonus_setting.coin,booking_bonus_setting.status');
		$this->db->from('booking_bonus_setting');
		$this->db->join('locations','locations.id=booking_bonus_setting.location_id');
		$this->db->where('booking_bonus_setting.status',1);
		$result1=$this->db->get()->result_array();
		
		$this->db->select('booking_bonus_setting.id,locations.location,booking_bonus_setting.start_date,booking_bonus_setting.end_date,booking_bonus_setting.coin,booking_bonus_setting.status');
		$this->db->from('booking_bonus_setting');
		$this->db->join('locations','locations.id=booking_bonus_setting.location_id');
		$this->db->where('booking_bonus_setting.status',0);
		$result2=$this->db->get()->result_array();
		$data = array_merge_recursive($result1, $result2);
		return $data;

	}
//////////////////// fetch booking bonus filter ////////////////////	
public function get_booking_bonus_filter($city='')
	   {
		$this->db->select('booking_bonus_setting.id,locations.location,booking_bonus_setting.start_date,booking_bonus_setting.end_date,booking_bonus_setting.coin,booking_bonus_setting.status');
		$this->db->from('booking_bonus_setting');
		$this->db->join('locations','locations.id=booking_bonus_setting.location_id');
		if($city!=''){
		    $this->db->where('booking_bonus_setting.location_id',$city);
		}
		$this->db->where('booking_bonus_setting.status',1);
		$result1=$this->db->get()->result_array();
		
		$this->db->select('booking_bonus_setting.id,locations.location,booking_bonus_setting.start_date,booking_bonus_setting.end_date,booking_bonus_setting.coin,booking_bonus_setting.status');
		$this->db->from('booking_bonus_setting');
		$this->db->join('locations','locations.id=booking_bonus_setting.location_id');
		if($city!=''){
		    $this->db->where('booking_bonus_setting.location_id',$city);
		}
		$this->db->where('booking_bonus_setting.status',0);
		$result2=$this->db->get()->result_array();
		$data = array_merge_recursive($result1, $result2);
		return $data;

	}
//////////////////// check buy_coin already exist ////////////////////	
public function get_buycoin_exist($city,$start_date,$end_date,$rupee)
	   {
		$this->db->select('buycoin_setting.id,buycoin_setting.rupee');
		$this->db->from('buycoin_setting');
		$this->db->where('buycoin_setting.start_date <=',$start_date);
		$this->db->where('buycoin_setting.end_date >=',$start_date);
		$this->db->where('buycoin_setting.location_id',$city);
		$this->db->where('buycoin_setting.status',1);
		return $this->db->get()->result();

	}
///////////////// check any active booking setting //////////////////////
	public function check_booking_setting($city="",$start_date="")
	{
	$this->db->distinct('booking_bonus_setting.id');
	$this->db->select('booking_bonus_setting.id');
	$this->db->from('booking_bonus_setting');
	$this->db->where('location_id',$city);
	$this->db->where('booking_bonus_setting.start_date <=',$start_date);
	$this->db->where('booking_bonus_setting.end_date >=',$start_date);
	$this->db->where('status',1);
	return $this->db->get()->result();
	}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
