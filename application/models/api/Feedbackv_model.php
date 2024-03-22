<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Feedbackv_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}



	public function get_help(){

		$this->db->select("question,answer");
		$this->db->from("help");
		//$this->db->select("help");

		return $this->db->get()->result();
	}
	public function get_feedback($user){

		$this->db->select("feedback");
		$this->db->from("feedback");
		$this->db->where("user_id",$user);

		return $this->db->get()->row();
	}

	public function insert($data,$table)
	{
		if($this->db->insert($table, $data))
			return  $this->db->insert_id();
			else
				return false;
	}

	/*public function search($user,$word,$area)
	{
		$this->db->select('venue.id,venue,venue,area.area,venue.morning,venue.evening,venue.description,venue.cost,venue.phone,venue.image,venue.address,sports.sports,area.id as area_id');
		$this->db->from('venue');
		$this->db->join('area','venue.area_id=area.id','left');
		$this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
		$this->db->join('sports','venue_sports.sports_id=sports.id','left');


		$this->db->like('venue', $word);
		$this->db->or_like('area', $word);
		$this->db->or_like('sports', $word);
		$this->db->where('venue.area_id',$area);
		$this->db->where('venue.status',1);
		$this->db->where('area.status',1);
		$this->db->where('sports.status',1);
		$this->db->group_by('venue.id');
		return $this->db->get()->result();
	}*/

	public function search($user,$word,$location_id,$area_id='')
	{
		$this->db->distinct('venue.id');
		$this->db->select('venue.id,venue,venue,area.area,venue.morning,venue.evening,venue.description,venue.cost,venue.phone,venue.image,venue.address,sports.sports,area.id as area_id');
		$this->db->from('venue');
	//	$this->db->join('user_area','user_area.area_id=venue.area_id','left');
		$this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
		$this->db->join('area','venue.area_id=area.id','left');
		$this->db->join('locations','locations.id=area.location_id','left');
		$this->db->join('sports','venue_sports.sports_id=sports.id','left');
	//	$this->db->where('user_area.user_id',$user);
		$where = "(venue.venue LIKE '%".$word."%' OR area.area LIKE '%".$word."%' OR sports.sports LIKE '%".$word."%')";
		$this->db->where($where);
		//$this->db->or_like('sports', $word);
		$this->db->where('venue.status',1);
		$this->db->where('area.status',1);
		$this->db->where('sports.status',1);
		$this->db->where('locations.status',1);
		$this->db->where_in('area.id',$area_id->area_id);
		$this->db->order_by('venue.venue','asc');
		$this->db->group_by('venue.id');
		return $this->db->get()->result();
	}

	public function get_notification($user)
	{
		$this->db->select('*');
		$this->db->from('notification');
		$this->db->join('offer');
		$this->db->where('user_id',$user);
		return $this->db->get()->result();

	}
	public function delete_notification($id)
	{
		if($id!='')
		$this->db->delete('notification',array('id'=>$id));
		else
			$this->db->truncate('notification');

	}
	public function get_venue($user_id){

		$this->db->select('venue.venue,venue.id,venue.image,venue.description,venue.morning,venue.evening,venue.cost,venue.phone,venue.lat,venue.lon,area.area,GROUP_CONCAT(DISTINCT facilities.facility) as facility,venue.book_status,venue.amount,
		 offer.percentage,offer.offer,offer.start,offer.end,offer.percentage,offer.image as offer_image,offer.time as offerTime,area.area,notification.id as notification_id,GROUP_CONCAT(DISTINCT sports.sports) as venue_sports,GROUP_CONCAT(DISTINCT sports.id) as venue_sports_id,GROUP_CONCAT(DISTINCT sports.image) as venue_sports_image');
	$this->db->from('venue');
	$this->db->join('user_area','user_area.area_id=venue.area_id');
	$this->db->join('venue_facilities','venue_facilities.venue_id=venue.id','left');
	$this->db->join('facilities','facilities.id=venue_facilities.facility_id','left');
    $this->db->join('venue_sports','venue_sports.venue_id=venue.id','left');
    $this->db->join('area','area.id=venue.area_id','left');
    $this->db->join('venue_court','venue_court.venue_id=venue.id','left');
    $this->db->join('court','court.id=venue_court.court_id','left');
    $this->db->join('sports','sports.id=venue_sports.sports_id','left');
     $this->db->join('offer_court','offer_court.court_id=venue_court.court_id');
    $this->db->join('offer','offer.id=offer_court.offer_id','left');

    $this->db->join('notification','offer.id=notification.offer_id','left');
   // /  $this->db->join('offer','offer.venue_id=venue.id','left');
    $this->db->where('notification.user_id',$user_id);
		$this->db->group_by('venue.id');


			return $this->db->get()->result();
	}
	public function get_upupup_mail()
	{
		$this->db->select('*');
		$this->db->from('upupup_email');

		return $this->db->get()->result();
	}
	public function get_user($user)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id',$user);
		return $this->db->get()->row();
	}
	public function user_area($user_id)
	{
		$this->db->select('area.id');
		$this->db->from('area');
		$this->db->join('user_area','user_area.area_id=area.id');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
	//////////////////////////////Feedback Email////////////////////////////////////////////
	public function get_feedback_mail()
	{
		$this->db->select('*');
		$this->db->from('upupup_email');
		$this->db->where('feedback',1);
		return $this->db->get()->result();
	}
	///////////////////////////////////////FAQ//////////////////////////////////////////////
	public function get_faq(){

		$this->db->select("*");
		$this->db->from("faq");
		$this->db->where('status',1);

		return $this->db->get()->result();
	}
	////////////////////////////UPUPUP Phone/////////////////////////////////////////////////
	public function get_phone()
	{
		$this->db->select('phone');
		$this->db->from('upupup_phone');
		return $this->db->get()->result();
	}
	////////////////////////////Help Phone////////////////////////////////////////////////////////////
	public function get_phone_help()
	{
		$this->db->select('phone');
		$this->db->from('upupup_phone');
		$this->db->where('help',1);
		return $this->db->get()->result();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////
	public function offer_notification($venue_id){
		$this->db->select('notification_history.title,notification_history.message,notification_history.image,notification_history.send_date,notification_history.venue_id');
		$this->db->from('notification_history');
		$this->db->where('venue_id',$venue_id);
		$this->db->order_by('notification_history.notification_id','DESC');
		return $this->db->get()->result();
	}
	/////////////////////////////////////////////////////////////////////////////////////////
	public function get_venue_user($user)
	{
		$this->db->select('venue.id');
		$this->db->from('user_area');
		$this->db->join('venue','venue.area_id=user_area.area_id');
		$this->db->where('user_area.user_id',$user);
		return $this->db->get()->result();
	}
	public function get_user_location($user_id){
	  $this->db->select('area_id');
		$this->db->from('user_area');
		$this->db->where('user_id',$user_id);
		$area= $this->db->get()->row()->area_id;

	    $this->db->select('location_id');
		$this->db->from('area');
		$this->db->where('id',$area);

		return $this->db->get()->row()->location_id;


	}
	public function get_user_area($user_id){
		$this->db->select('GROUP_CONCAT(distinct area_id) as area_id');
		  $this->db->from('user_area');
		  $this->db->where('user_id',$user_id);
		  return $this->db->get()->row();
  
	  }
}
