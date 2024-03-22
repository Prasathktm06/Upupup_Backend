<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Offer_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

public function insert_offer($data)
	{
		if($this->db->insert('offer', $data))
		return  $this->db->insert_id();
		else
		return false;
	}

//Check offer is already added
public function get_offeradded($venue_id,$court_id,$date,$time,$nameOfDay){
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

//Check offer is already added on update
public function get_offeraddedu($venue_id,$court_id,$date,$time,$nameOfDay,$offer_id){
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
		$this->db->where("offer.id !=",$offer_id);
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}

public function insert_offertime($datas)
	{
		if($this->db->insert('offer_time', $datas))
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

public function get_offerlist($venue_id)
	{
		$this->db->select('id,offer,start,end,percentage');
		$this->db->from('offer');
		$this->db->where("status",1);
		$this->db->where("venue_id",$venue_id);
		$this->db->where("end >=",date('Y-m-d'));
                $this->db->order_by('start','asc');
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}

public function get_offerdetails($id)
	{
		$this->db->distinct('court.court');
		$this->db->select('offer.offer,offer.amount,offer.percentage,offer.start,offer.end,offer.start_time,offer.end_time,court.court,court.id');
		$this->db->from('offer');
		$this->db->join('offer_court','offer_court.offer_id=offer.id');
		$this->db->join('court','court.id=offer_court.court_id');
                $this->db->where("offer.id",$id);
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
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
public function delete_offercourtu($offer_id){
		$this->db->delete('offer_court', array('offer_id' => $offer_id));
		return ($this->db->affected_rows() == 1);
	}
public function delete_offertimeu($offer_id){
		$this->db->delete('offer_time', array('offer_id' => $offer_id));
		return ($this->db->affected_rows() == 1);
	}
// offer update
public function update_offer($data,$offer_id){
	  $this->db->update('offer',$data,  array('id'=>$offer_id));
		if($this->db->affected_rows()==1)
		return true;
		else
		return false;
	}
// user role corresponding to user id 
public function get_vendorrole($user_id){
	$this->db->select('role.name');
	$this->db->from('role');
	$this->db->join('user_role','user_role.role_id=role.role_id');
	$this->db->where('user_role.user_id',$user_id);
	return $this->db->get()->result();
	}
// venue owner name and email
public function get_owner($venue_id){
	$this->db->select('user.name,user.email');
	$this->db->from('user');
	$this->db->join('user_role','user_role.user_id=user.user_id');
	$this->db->join('venue_manager','venue_manager.user_id=user.user_id');
	$this->db->join('role','role.role_id=user_role.role_id');
	$this->db->where('venue_manager.venue_id',$venue_id);
	$this->db->where('role.venue_users',3);
	return $this->db->get()->result();
	}
// venue user phone
public function get_userphone($user_id){
	$this->db->select('phone,name');
	$this->db->from('user');
	$this->db->where('user_id',$user_id);
	return $this->db->get()->result();
	}
// venue name corresponding to venue_id
public function get_venuename($venue_id){
	$this->db->select('venue');
	$this->db->from('venue');
	$this->db->where('id',$venue_id);
	return $this->db->get()->result();
	}
// location name corresponding to venue_id
public function get_locname($venue_id){
	$this->db->select('locations.location');
	$this->db->from('venue');
	$this->db->join('area','area.id=venue.area_id');
	$this->db->join('locations','locations.id=area.location_id');
	$this->db->where('venue.id',$venue_id);
	return $this->db->get()->result();
	}
// court name corresponding to court_id
public function get_courtname($court_id){
	$this->db->select('court');
	$this->db->from('court');
	$this->db->where('id',$court_id);
	return $this->db->get()->result();
	}
//fetch upupup email
public function get_upupupemail(){
	$this->db->select('email');
	$this->db->from('upupup_email');
	$this->db->where("offers",1);
	return $this->db->get()->result();
	}
//check vendor user staus is active
public function get_vendorusers($manager_id,$vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$manager_id);
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",1);
        return $this->db->get()->result();
	}
//check vendor user staus is in-active
public function get_vendorcheck($manager_id,$vendor_phone){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where("user_id",$manager_id);
        $this->db->where("phone",$vendor_phone);
        $this->db->where("status",0);
        return $this->db->get()->result();
	}
////////////////////////////////// my account //////////////////////////////////
	public function update_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}	


}