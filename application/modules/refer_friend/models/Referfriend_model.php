<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Referfriend_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
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
///////////////// check any active booking setting //////////////////////
	public function check_booking_setting($city="",$start_date="",$end_date="")
	{
	$this->db->distinct('booking_bonus_setting.id');
	$this->db->select('booking_bonus_setting.id');
	$this->db->from('booking_bonus_setting');
	$this->db->where('location_id',$city);
	$this->db->where('status',1);
	return $this->db->get()->result();
	}
	
//////////////// add refer a friend setting to table ////////////////////
//////////////// insert buy coin data //////////////////
public function insert_referfriend_setting($data)
	{
		if($this->db->insert('refer_bonus_setting', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	
//////////////////// Refer friend list ////////////////////	
public function get_refer_friend_list()
	   {
		$this->db->select('refer_bonus_setting.id,locations.location,refer_bonus_setting.start_date,refer_bonus_setting.end_date,refer_bonus_setting.install_count,refer_bonus_setting.install_bonus_coin,refer_bonus_setting.install_status,refer_bonus_setting.booking_bonus_coin,refer_bonus_setting.booking_bonus_status,refer_bonus_setting.status');
		$this->db->from('refer_bonus_setting');
		$this->db->join('locations','locations.id=refer_bonus_setting.location_id');
		$this->db->where('refer_bonus_setting.status',1);
		$result1=$this->db->get()->result_array();
		
		$this->db->select('refer_bonus_setting.id,locations.location,refer_bonus_setting.start_date,refer_bonus_setting.end_date,refer_bonus_setting.install_count,refer_bonus_setting.install_bonus_coin,refer_bonus_setting.install_status,refer_bonus_setting.booking_bonus_coin,refer_bonus_setting.booking_bonus_status,refer_bonus_setting.status');
		$this->db->from('refer_bonus_setting');
		$this->db->join('locations','locations.id=refer_bonus_setting.location_id');
		$this->db->where('refer_bonus_setting.status',0);
		$result2=$this->db->get()->result_array();
		$data = array_merge_recursive($result1, $result2);
		return $data;

	}
	

/////// update status /////////
	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
///////////////// check any active refer a friend setting //////////////////////
	public function check_refer_setting($city="",$start_date="")
	{
	$this->db->distinct('refer_bonus_setting.id');
	$this->db->select('refer_bonus_setting.id');
	$this->db->from('refer_bonus_setting');
	$this->db->where('location_id',$city);
	$this->db->where('refer_bonus_setting.start_date <=',$start_date);
	$this->db->where('refer_bonus_setting.end_date >=',$start_date);
	$this->db->where('status',1);
	return $this->db->get()->result();
	}
//////////////////// Refer friend list sort based on location_id ////////////////////	
public function get_refer_friend_listsort($city='')
	   {
		$this->db->select('refer_bonus_setting.id,locations.location,refer_bonus_setting.start_date,refer_bonus_setting.end_date,refer_bonus_setting.install_count,refer_bonus_setting.install_bonus_coin,refer_bonus_setting.install_status,refer_bonus_setting.booking_bonus_coin,refer_bonus_setting.booking_bonus_status,refer_bonus_setting.status');
		$this->db->from('refer_bonus_setting');
		$this->db->join('locations','locations.id=refer_bonus_setting.location_id');
		if($city!=''){
		    $this->db->where('refer_bonus_setting.location_id',$city);
		}
		$this->db->where('refer_bonus_setting.status',1);
		$result1=$this->db->get()->result_array();
		
		$this->db->select('refer_bonus_setting.id,locations.location,refer_bonus_setting.start_date,refer_bonus_setting.end_date,refer_bonus_setting.install_count,refer_bonus_setting.install_bonus_coin,refer_bonus_setting.install_status,refer_bonus_setting.booking_bonus_coin,refer_bonus_setting.booking_bonus_status,refer_bonus_setting.status');
		$this->db->from('refer_bonus_setting');
		$this->db->join('locations','locations.id=refer_bonus_setting.location_id');
		if($city!=''){
		    $this->db->where('refer_bonus_setting.location_id',$city);
		}
		$this->db->where('refer_bonus_setting.status',0);
		$result2=$this->db->get()->result_array();
		$data = array_merge_recursive($result1, $result2);
		return $data;

	}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
