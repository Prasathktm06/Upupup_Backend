<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Place_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_details($table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}

	public function get_area($id){
		$this->db->select('area.*,locations.location');
		$this->db->from('area');
		$this->db->join('locations','locations.id=area.location_id');
		$this->db->where('area.status',1);
		$this->db->order_by('area.area');
		$this->db->where('location_id',$id);
		return $this->db->get()->result();
	}
	
public function update_booking($data,$merchantTransactionId){
	  $this->db->update('venue_booking',$data,  array('booking_id'=>$merchantTransactionId));
		if($this->db->affected_rows()==1)
		return true;
		else
		return false;
	}
}