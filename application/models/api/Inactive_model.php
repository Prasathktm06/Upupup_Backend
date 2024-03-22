<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Inactive_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
     
//sports id selection for inactive courts on based venue
public function get_sports($venue_id){
    	$this->db->distinct('sports.id');
		$this->db->select('sports.id,sports.sports,sports.image,sports.status');
		$this->db->from('sports');
		$this->db->join('court','court.sports_id=sports.id');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('sports.status',1);
		$this->db->where('venue_court.venue_id',$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//court details selection for inactive courts on based venue and sports
public function get_courts($venue_id,$sports_id){
		$this->db->select('court.id,court.court,court.cost');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('court.status',1);
		$this->db->where('court.sports_id',$sports_id);
		$this->db->where('venue_court.venue_id',$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//court list when multiple sports selected in vendors app
public function get_courtlist($venue_id,$sports_id){
		$this->db->select('court.id,court.sports_id,court.court,court.cost');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('court.status',1);
		$this->db->where('court.sports_id',$sports_id);
		$this->db->where('venue_court.venue_id',$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//check booking exist on selection of date and time of court inactivation
public function get_bookingexist($venue_id,$court_id,$date,$time){
		$this->db->select('venue_booking_time.id,venue_booking_time.court_time,venue_booking.date,court.court,sports.sports');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->join('court','court.id=venue_booking_time.court_id');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->where("venue_booking_time.court_time",$time);
		$this->db->where("venue_booking.payment_mode !=",2);
		$this->db->where("venue_booking.payment_mode !=",3);
        $this->db->where("venue_booking.date", $date);
        $this->db->where("venue_booking.court_id",$court_id);
        $this->db->where("venue_booking.venue_id",$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//insert data on  inactive court 
public function insert_inactivecourt($data){
	if($this->db->insert('inactive_court', $data))
	return  $this->db->insert_id();
	else
	return false;
	}
//Check court is already inactive in date,time on day
public function get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay){
		$this->db->select('inactive_court_time.id');
		$this->db->from('inactive_court_time');
		$this->db->join('inactive_court','inactive_court.id=inactive_court_time.inactive_court_id');
        $this->db->where("inactive_court_time.day",$nameOfDay);
		$this->db->where("inactive_court_time.time",$time);
		$this->db->where('inactive_court.sdate <=',$date);
		$this->db->where('inactive_court.edate >=',$date);
		$this->db->where("inactive_court.court_id",$court_id);
		$this->db->where("inactive_court.venue_id",$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//delete court is inactive on selected date in range and if time and day are same 
public function delete_inactivatecourttime($id){
		$this->db->delete('inactive_court_time', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}

//insert data on  inactive_court_time 
public function insert_inactivecourttime($datas){
	if($this->db->insert('inactive_court_time', $datas))
	return  $this->db->insert_id();
	else
	return false;
	}
//delete court is inactive on selected date in range and if time and day are same 
public function delete_emptytimes(){
		
    $query = $this->db->query("DELETE inactive_court FROM   inactive_court LEFT JOIN inactive_court_time ON inactive_court.id = inactive_court_time.inactive_court_id WHERE  inactive_court_time.inactive_court_id IS NULL");
    return true;
	}

//fetching inactive court data based on venue_id
public function get_inactivecourtlist($venue_id){
		$this->db->select('inactive_court.id,inactive_court.court_id,inactive_court.sdate,inactive_court.edate,inactive_court.description,court.court');
		$this->db->from('inactive_court');
		$this->db->join('court','court.id=inactive_court.court_id');
		$this->db->where('inactive_court.venue_id',$venue_id);
        $this->db->where("inactive_court.sdate >=",date('Y-m-d'));
        $this->db->order_by('inactive_court.sdate','asc');
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//fetching inactive court data based on venue_id,$sports_id
public function get_sportsfilter($venue_id,$sports_id){
		$this->db->select('inactive_court.id,inactive_court.court_id,inactive_court.sdate,inactive_court.edate,inactive_court.description,court.court');
		$this->db->from('inactive_court');
		$this->db->join('court','court.id=inactive_court.court_id');
		$this->db->where('inactive_court.venue_id',$venue_id);
		$this->db->where('court.sports_id',$sports_id);
        $this->db->where("inactive_court.sdate >=",date('Y-m-d'));
        $this->db->order_by('inactive_court.sdate','asc');
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

//courtdetails based on venue_id
public function get_courtdetails($venue_id){
		$this->db->select('court.id,court.court');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('court.status',1);
		$this->db->where('venue_court.venue_id',$venue_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//fetching inactive court data based on venue_id,court_id
public function get_courtfilter($venue_id,$court_id){
		$this->db->select('inactive_court.id,inactive_court.court_id,inactive_court.sdate,inactive_court.edate,inactive_court.description,court.court');
		$this->db->from('inactive_court');
		$this->db->join('court','court.id=inactive_court.court_id');
		$this->db->where('inactive_court.venue_id',$venue_id);
		$this->db->where('court.id',$court_id);
        $this->db->where("inactive_court.sdate >=",date('Y-m-d'));
        $this->db->order_by('inactive_court.sdate','asc');
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
//inactive single court details
public function singlecourt($inactive_id){
		$this->db->distinct('inactive_court_time.day');
        $this->db->select('inactive_court.id,inactive_court.sdate,inactive_court.edate,inactive_court.stime,inactive_court.etime,inactive_court.description,inactive_court_time.day');
		$this->db->from('inactive_court');
		$this->db->join('inactive_court_time','inactive_court_time.inactive_court_id=inactive_court.id');
		$this->db->where('inactive_court.id',$inactive_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}
// inactivate court update
public function update_inactivate($data,$inactive_id){
	  $this->db->update('inactive_court',$data,  array('id'=>$inactive_id));
		if($this->db->affected_rows()==1)
			return true;
		else
			return false;
	}
//delete court is inactive on selected date in range and if time and day are same 
public function delete_inactivatecourtupdate($inactive_id){
		$this->db->delete('inactive_court_time', array('inactive_court_id' => $inactive_id));
		return ($this->db->affected_rows() == 1);
	}
//delete single inactive court
public function delete_singlecourt($inactive_id){
		$this->db->delete('inactive_court', array('id' => $inactive_id));
		return ($this->db->affected_rows() == 1);
	}
//delete court is inactive on selected date in range and if time and day are same 
public function delete_timings(){
		
		$query = $this->db->query("DELETE inactive_court_time FROM   inactive_court_time LEFT JOIN inactive_court ON inactive_court_time.inactive_court_id = inactive_court.id  WHERE  inactive_court.id IS NULL");
		return true;
	}
//sports image based on court_id
public function sportsimage($courts_id){
		$this->db->select('sports.id,sports.sports,sports.image');
		$this->db->from('sports');
		$this->db->join('court','court.sports_id=sports.id');
		$this->db->where('sports.status',1);
		$this->db->where('court.id',$courts_id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

}
 