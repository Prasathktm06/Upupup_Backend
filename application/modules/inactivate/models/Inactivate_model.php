<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Inactivate_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}
	
	public function get_inactiveTable($edit,$delete,$venue_id='')
	{
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,inactive_court.id as id,court.court as court,DATE_FORMAT(inactive_court.sdate, "%d-%m-%Y") as sdate,DATE_FORMAT(inactive_court.edate, "%d-%m-%Y") as edate,TIME_FORMAT(inactive_court.stime, "%r") as stime,TIME_FORMAT(inactive_court.etime, "%r") as etime,inactive_court.description as description,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);

		$this->db->join("court","court.id=inactive_court.court_id","left");
		$this->db->where('inactive_court.venue_id',$venue_id);
		$this->db->from('inactive_court , (SELECT @s:='.$_GET['start'].') AS s');
                $this->db->order_by('inactive_court.sdate','asc');

		if($column==1)
		{
			$this->db->order_by('inactive_court.id',$dir);
		}

		$where = "(court.court LIKE '%".$value."%' OR inactive_court.sdate LIKE '%".$value."%' OR inactive_court.edate LIKE '%".$value."%' OR inactive_court.stime LIKE '%".$value."%' OR inactive_court.etime LIKE '%".$value."%' OR inactive_court.description LIKE '%".$value."%' )";

		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('inactive_court');
		$where = "(court.court LIKE '%".$value."%' OR inactive_court.sdate LIKE '%".$value."%' OR inactive_court.edate LIKE '%".$value."%' OR inactive_court.stime LIKE '%".$value."%' OR inactive_court.etime LIKE '%".$value."%' OR inactive_court.description LIKE '%".$value."%')";
			$this->db->join("court","court.id=inactive_court.court_id","left");
		$this->db->where($where);
        $this->db->where('inactive_court.venue_id',$venue_id);
		$query = $this->db->get();


		$this->db->from('inactive_court');
		$this->db->join("court","court.id=inactive_court.court_id","left");
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function delete($id,$venue_id)
{
	if($this->db->delete('inactive_court',array('id'=>$id))){
		return true;
	}else{
		return false;
	}
}
       public function get_venue_court($venue_id)
	{
		$this->db->select('court.court,court.id');
		$this->db->from('venue_court');
		$this->db->join('court','court.id=venue_court.court_id');
		$this->db->where('venue_court.venue_id',$venue_id);
		return $this->db->get()->result();
	}
       public function get_venue_sports($venue_id)
	{
		$this->db->select('sports.sports,sports.id');
		$this->db->from('sports');
		$this->db->join('venue_sports','venue_sports.sports_id=sports.id');
		$this->db->where('venue_sports.venue_id',$venue_id);
		return $this->db->get()->result();
	}
           //check booking exist on selection of date and time of court inactivation
	   public function get_bookingexist($venue_id,$court_id,$date,$time){
		$this->db->select('venue_booking_time.id,venue_booking_time.court_time,venue_booking.date,court.court,sports.sports');
		$this->db->from('venue_booking_time');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_booking_time.booking_id');
		$this->db->join('court','court.id=venue_booking_time.court_id');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->where("venue_booking_time.court_time",$time);
		$this->db->where("venue_booking.payment_mode",1);
                $this->db->where("venue_booking.date", $date);
                $this->db->where("venue_booking.court_id",$court_id);
                $this->db->where("venue_booking.venue_id",$venue_id);
		$query = $this->db->get();
                $result = $query->result_array();
                return $result;
	}
          //insert data on  inactive court 
	public function insert_inactivecourt($data)
	{
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
	public function insert_inactivecourttime($datas)
	{
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

   //delete court is inactive on selected date in range and if time and day are same 
        public function delete_inempty(){
		
                $query = $this->db->query("DELETE inactive_court_time FROM   inactive_court_time LEFT JOIN inactive_court ON inactive_court.id = inactive_court_time.inactive_court_id WHERE  inactive_court.id IS NULL");
                return true;
	}

public function get_details($id){
		$this->db->select("id,venue_id,court_id,sdate,edate,stime,etime,description ");
		$this->db->from('inactive_court');
		$this->db->where('id',$id);
		return $this->db->get()->row();
	}

public function get_venue($venue_id){
		$this->db->select("venue.venue");
		$this->db->from('venue');
		$this->db->where('id',$venue_id);
		return $this->db->get()->row();
	}
public function get_sportname($id){
		$this->db->select("sports.id,sports.sports");
		$this->db->from('sports');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('court','court.id=inactive_court.court_id');
		$this->db->where('inactive_court.id',$id);
		return $this->db->get()->row();
	}
public function get_incourt_name($id,$venue_id){
		$this->db->select("court.id,court.court");
		$this->db->from('court');
		$this->db->join('inactive_court','inactive_court.court_id=court.id');
		$this->db->where('inactive_court.id ',$id);
		return $this->db->get()->row();
	}

public function get_inactive_days($id){
	        $this->db->distinct('day');
		$this->db->select("day");
		$this->db->from('inactive_court_time');
		$this->db->where('inactive_court_id',$id);
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

        // inactivate court update
	public function update_inactivate($data,$id){
	  $this->db->update('inactive_court',$data,  array('id'=>$id));
		if($this->db->affected_rows()==1)
			return true;
		else
			return false;
	}
//delete court is inactive on selected date in range and if time and day are same 
	public function delete_inactivatecourtup($id){
		$this->db->delete('inactive_court_time', array('inactive_court_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
//reason for inactivation
public function get_inactive_reason(){
		$this->db->select("reason");
		$this->db->from('inactive_reasons');
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
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
// venue name corresponding to venue_id
public function get_venuename($venue_id){
	$this->db->select('venue');
	$this->db->from('venue');
	$this->db->where('id',$venue_id);
	return $this->db->get()->result();
	}
// court name corresponding to court_id
public function get_courtname($court_id){
	$this->db->select('court');
	$this->db->from('court');
	$this->db->where('id',$court_id);
	return $this->db->get()->result();
	}
// sports name corresponding to court_id
public function get_sportsname($court_id){
	$this->db->select('sports.sports');
	$this->db->from('sports');
	$this->db->join('court','court.sports_id=sports.id');
	$this->db->where('court.id',$court_id);
	return $this->db->get()->result();
	}
 /////////////////////////////////////////court list based on sports and vaenue ////////////////////////////////////////
	public function get_courtlistin($venue_id,$sports_id){
	$this->db->select('court.id as courts_id,court.court as court_name');
	$this->db->from('court');
	$this->db->join('venue_court','venue_court.court_id=court.id');
        $this->db->where("court.sports_id",$sports_id);
        $this->db->where("venue_court.venue_id",$venue_id);
	return $this->db->get()->result_array();
	}
        public function get_courtlistsin($sports_id,$venue_id)
	{
		$this->db->select('court.id');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id');
        $this->db->where("court.sports_id",$sports_id);
        $this->db->where("venue_court.venue_id",$venue_id);
		return $this->db->get()->result();
	}



}
