<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Users_model extends CI_Model
{

	public function __construct()
	{
		date_default_timezone_set("Asia/Kolkata");
		parent::__construct();
	}


	public function get_skills($user_id){
		$this->db->select('sports.*');
		$this->db->from('sports');
		$this->db->join('user_sports','user_sports.sports_id=sports.id');
		$this->db->where('user_sports.user_id',$user_id);

		return $this->db->get()->result();

	}
	public function count_match($user)
	{
		$this->db->select('id');
		$this->db->from('matches_players');
		$this->db->join('user_sports','user_sports.sports_id = sports.id','left');

		$this->db->where('user_sports.user_id',$user_id);
		$this->db->group_by('sports.id');
	}
	public function delete_user_preference($id,$table){
		$this->db->delete($table, array('user_id' => $id));
		return ($this->db->affected_rows() == 1);
	}

	public function insert_user_preference($data,$table){
		if($this->db->insert($table, $data))
			return true;
			else
				return false;
	}

	public function get_co_players($user_id){
	/*	$this->db->select('users1.name user,users1.id as user_id,users2.phone_no as coplayer_phone,users2.name as co_player,users2.id as co_player_id,users2.image as co_player_image,GROUP_CONCAT(sports.id) as sports_id,GROUP_CONCAT(sports.sports) as sports,GROUP_CONCAT(sports.image) as sports_image');*/
	$this->db->select('users.name as co_player,users.id as co_player_id,users.image as co_player_image,users.phone_no as coplayer_phone,GROUP_CONCAT(sports.id) as sports_id,GROUP_CONCAT(sports.sports) as sports,GROUP_CONCAT(sports.image) as sports_image');
		$this->db->from('users ');

		$this->db->join('user_sports','user_sports.user_id=users.id');
		$this->db->join('sports','sports.id=user_sports.sports_id');

		$this->db->where('users.id',$user_id);
		//$this->db->where('users2.id!=',$user_id);
		$this->db->group_by('users.id');
		return $this->db->get()->row();
	}
public function get_co_players2($user)
{

	$this->db->select('co_player.co_player as co_player_id,users.name as co_player,users.phone_no as coplayer_phone,users.image as co_player_image,GROUP_CONCAT(distinct sports.sports) as sport,GROUP_CONCAT(distinct sports.id) as sports_id,GROUP_CONCAT(distinct sports.image) as sports_image');
	$this->db->from('co_player ');

	$this->db->join('users','users.id=co_player.co_player','left');
	$this->db->join('user_sports','user_sports.user_id=users.id','left');
	$this->db->join('sports','sports.id=user_sports.sports_id','left');

	$this->db->where('co_player.user_id',$user);
	$this->db->where('co_player!=',$user);

	$this->db->group_by('co_player');
	return $this->db->get()->result();
}
	/*public function get_co_players($user_id){
		$this->db->select('users.id,users.name,users.phone_no,users.image,GROUP_CONCAT(sports.id) as sports_id,GROUP_CONCAT(sports.sports) as sports,GROUP_CONCAT(sports.image) as sports_image');
		$this->db->from('co_player');
		$this->db->join('sports','sports.id=co.sports_id');

		$this->db->where('users1.id',$user_id);

		$this->db->group_by('users2.id');
		return $this->db->get()->result();
	}
*/
	public function get_co_players_details($co_player){
		$this->db->select('sports.sports,sports.id as sports_id,sports.image,users.phone_no,user_sports.user_id ');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id','left');
		$this->db->join('users','users.id=user_sports.user_id','left');
		//$this->db->join('user_sports','user_sports.sports_id=sports.id','left');
		$this->db->where('user_sports.user_id',$co_player);
		//$this->db->where('co_player.co_player',$co_player);
		return $this->db->get()->result();
	}
	public function rate_co_players_details($user_id,$co_player,$sports,$data){
		return  $this->db->update('co_player', $data, array('user_id' => $user_id,'co_player'=>$co_player,'sports_id'=>$sports));


	}
	public function get_booking($user_id)
	{
		$this->db->select('id,venue_id');
		$this->db->from('venue_booking');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}

	public function update_name($id,$data){
		return $this->db->update('users', $data, array('id' => $id));
	}

	public function get_otp($user)
	{
		$this->db->select('otp.otp');
		$this->db->from('otp');
		$this->db->where('user_id',$user);
		return $this->db->get()->result();
	}

	public function get_rate($users,$sports)
	{
		$this->db->select('id');
		$this->db->from('rating');
		$this->db->where('user_id',$users);
		$this->db->where('sports_id',$sports);
		return $this->db->get()->row();
	}

	public function insert_rate($data){
		if($this->db->insert('co_player', $data))
			return true;
			else
				return false;
	}
	public function get_sports($user)
	{
		$this->db->select("sports_id");
		$this->db->from("user_sports");
		$this->db->where("user_id",$user);
		return $this->db->get()->result();
	}

	public function get_sports_count($user,$sport)
	{
		$this->db->select("count(matches.id) as sports_played,sports.sports,sports.id");
		$this->db->from("matches_players");
		$this->db->join("matches","matches.id=matches_players.match_id");
		$this->db->join("sports","sports.id=matches.sports_id");
		$this->db->where("matches.user_id",$user);
		$this->db->where("matches.sports_id",$sport);
		return $this->db->get()->row();
	}

	public function get_co_player_rating($user_id,$co_player,$sports)
	{
		$this->db->select("rating");
		$this->db->from("co_player");
		$this->db->where("user_id",$user_id);
		$this->db->where("co_player",$co_player);
		$this->db->where("sports_id",$sports);
		return $this->db->get()->row();
	}
	public function get_venue_player($booking_id,$co_player)
	{
		$this->db->select("id");
		$this->db->from("venue_players");
		$this->db->where("user_id",$co_player);
		$this->db->where("booking_id",$booking_id);
		return $this->db->get()->result();
	}
	public function get_co_player_rating2($user_id)
	{
		$this->db->select("rating,sports_id,sports.sports,sports.id as sports_id,sports.image");
		$this->db->from("co_player");
		$this->db->join('sports','sports.id=co_player.sports_id','left');
		$this->db->where("user_id",$user_id);

		return $this->db->get()->result();
	}

	public function get_status($user_id,$sports_id)
	{
		/*$this->db->select("rating");
		$this->db->from("co_player");
		$this->db->join('sports','sports.id=co_player.sports_id','left');
		$this->db->where("user_id",$user_id);
		$this->db->where("co_player",$user_id);
		$this->db->where("sports_id",$sports_id);*/

		$this->db->select('AVG(rating) as rating');
		$this->db->from('co_player');
		$this->db->join('sports','sports.id=co_player.sports_id','left');
		//$this->db->where('user_id',$user_id);
		$this->db->where('co_player',$user_id);
		$this->db->where('sports_id',$sports_id);
		$this->db->where('rating!=',0);


		return $this->db->get()->row();
	}

	public function user_rate($user_id,$sports_id)
	{
		/*$this->db->select("rating");
		$this->db->from("co_player");
		$this->db->join('sports','sports.id=co_player.sports_id','left');
		$this->db->where("user_id",$user_id);
		$this->db->where("co_player",$user_id);
		$this->db->where("sports_id",$sports_id);*/

		$this->db->select('AVG(rating) as rating');
		$this->db->from('co_player');
		$this->db->join('sports','sports.id=co_player.sports_id','left');
		$this->db->where('user_id',$user_id);
		$this->db->where('co_player',$user_id);
		$this->db->where('sports_id',$sports_id);
		$this->db->where('rating!=',0);


		return $this->db->get()->row();
	}

	public function get_user_sports($user,$sports)
	{
		//print_r($sports);exit;
		$this->db->select('sports.sports,sports.id as id,sports.image');
		$this->db->from('user_sports');
		$this->db->join('sports','sports.id=user_sports.sports_id');
		foreach ($sports as $key => $value) {
			$this->db->where('user_sports.sports_id !=',$value->id);
		}

		$this->db->where('user_sports.user_id',$user);
		return $this->db->get()->result();
	}
	public function get_co_players_rating($sports_id,$co_player)
	{
		$this->db->select('avg(rating) as rating');
		$this->db->from('co_player');
		$this->db->where('sports_id',$sports_id);
		$this->db->where('co_player',$co_player);
		$this->db->where('rating!=',0);
		return  $this->db->get()->row();
	}
	////////////////////////////////User Data////////////////////////////////////////////
	public function user_details($id){

		$this->db->select('users.id ,users.name,users.email,users.address,users.phone_no,users.image');
		$this->db->from('users');
		$this->db->where('users.id',$id);
		return $this->db->get()->row();
	}
	public function get_match_played($user,$sports,$co_player)
	{
		$this->db->select('booking_id');
		$this->db->from('venue_booking');
		$this->db->where('user_id',$user);
		$this->db->where('sports_id',$sports);
		$res= $this->db->get()->result();
		if(!empty($res)){
		foreach ($res as $key => $value) {
			$booking_ids[]=$value->booking_id;
		}
	}else{
		return (object)array('count'=>0);
	}

		return $this->get_match_played2($booking_ids,$co_player);
	}
	public function get_match_played2($booking_ids,$co_player)
	{
		$this->db->select('count(booking_id) as count');
		$this->db->from('venue_players');
		$this->db->where('user_id',$co_player);
		$this->db->where_in('booking_id',$booking_ids);
		return $this->db->get()->row();
	}
	/*public function get_match_played_user($user,$sports,$cur_date="")
	{
		$this->db->select('count(venue_booking.booking_id) as count');
		$this->db->from('venue_booking');
		$this->db->where('user_id',$user);
		$this->db->where('sports_id',$sports);
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id');
		if ($cur_date) {
			$this->db->where('date(venue_booking.date)<=',date('Y:m:d'));
			$this->db->where('time(venue_booking_time.court_time)<',date('G:i:s'));

		}
		return $this->db->get()->row();
	}*/

	public function get_match_played_user($user,$sports)
	{

		$cur_date=date("Y-m-d");
		$cur_time=date("H:i:s");
		$this->db->select('*');
		$this->db->from('venue_players');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_players.booking_id','left');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_players.booking_id','left');

		$this->db->where('venue_players.user_id',$user);
		//$this->db->where("(venue_booking.user_id = '$user' OR venue_players.user_id = '$user') ");
		$this->db->where('venue_booking.sports_id',$sports);
		$this->db->where('date(venue_booking.date)<=',$cur_date);
		//$this->db->where('time(venue_booking_time.court_time)<',$cur_time);
		$this->db->where('venue_booking.payment_mode!=',2);
		$this->db->group_by('venue_players.booking_id');
		return $this->db->get()->result();
	}
	public function get_co_players_rating_status($sports_id,$user_id,$co_player)
	{
		date_default_timezone_set("Asia/Kolkata");
		$cur_time=date("G:i:s");
		$cur_date=date("Y-m-d");
		$this->db->select('booking_rating.id,(venue_booking_time.court_time) as court_time,(venue_booking.date) as date,venue_booking.sports_id,booking_rating.status,booking_rating.booking_id');
		$this->db->from('booking_rating');
		$this->db->join('venue_booking','booking_rating.booking_id=venue_booking.booking_id','left');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');
		$this->db->where('venue_booking.sports_id',$sports_id);
		$this->db->where('date(venue_booking.date)<=',$cur_date);
		$this->db->where('booking_rating.coplayer',$user_id);
		$this->db->where('booking_rating.coplayer2',$co_player);
		$this->db->where('venue_booking.payment_mode!=',2);
		$this->db->where('booking_rating.status',0);
		return $this->db->get()->result();

	}
	public function get_booking_coplayer($sports_id,$user_id,$co_player)
	{
		$this->db->select('venue_booking.booking_id');
		$this->db->from('venue_booking');
		$this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id','left');
		//$this->db->where('venue_booking.user_id',$user_id);
		$this->db->where("(venue_booking.user_id = '$user_id' OR venue_booking.user_id = '$co_player') ");
		$this->db->where('venue_booking.sports_id',$sports_id);
		$this->db->where('venue_players.user_id',$co_player);

		return $this->db->get()->result();
	}
	public function update_venue_player_rating($data,$condition)
	{
		$row=$this->db->update('venue_players',$data,$condition);
		return $row;
	}
	public function get_coplayer_rating($coplayer,$sports)
	{
		$this->db->select('AVG(rating) as avg');
		$this->db->from('co_player');
		$this->db->where('co_player.co_player',$coplayer);
		$this->db->where('co_player.sports_id',$sports);
		$this->db->where('rating!=',0);

		return $this->db->get()->row();
	}
	////////////////////////////////////////////////////////////////////
	public function get_match_played_count($co_player,$cur_date,$user,$cur_time)
	{
		$this->db->distinct();
		$this->db->select('venue_players.booking_id');
		$this->db->from('venue_players');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_players.booking_id','left');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');

		$this->db->where('venue_players.user_id',$user);
		//$this->db->where('venue_booking.user_id',$user);

		$this->db->where('date(venue_players.date)<=',$cur_date);
		$this->db->where('venue_booking.payment_mode!=',2);
		$this->db->group_by('venue_booking_time.booking_id');
		$booking_ids=$this->db->get()->result();
		foreach ($booking_ids as $key => $value) {
			$bookings_array[]=$this->match_count($value->booking_id,$co_player);
		}
		return $bookings_array;
		//$this->match_count($booking_ids,$co_player);
		/*$this->db->distinct();
		$this->db->select('*');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');
		$this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id','left');
		$this->db->where("(venue_booking.user_id = '$user' OR venue_booking.user_id = '$co_player') ");
		$this->db->where('venue_players.user_id!=',$user);
		$this->db->where('venue_players.user_id',$co_player);
		//$this->db->where('venue_booking.user_id',$user);

		$this->db->where('date(venue_players.date)<=',$cur_date);
		$this->db->where('venue_booking.payment_mode!=',2);
		$this->db->group_by('venue_booking_time.booking_id');
		return $this->db->get()->result();*/
	}
	public function get_match_played_user_count($user,$sports)
	{

		$this->db->select('venue_players.booking_id,venue_booking_time.court_time as court_time,venue_booking.date');
		$this->db->from('venue_players');
		$this->db->where('venue_players.user_id',$user);
		$this->db->join('venue_booking','venue_booking.booking_id=venue_players.booking_id','left');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_players.booking_id');
		$this->db->where("venue_booking.sports_id",$sports);
		$this->db->where('date(venue_booking.date)<=',date('Y:m:d'));
				$this->db->where('venue_booking.payment_mode !=',2);
		$this->db->group_by('venue_players.booking_id');
		return $this->db->get()->result();
	}

	public function match_count($booking_id,$co_player){
		$this->db->select('*');
		$this->db->from('venue_players');
		$this->db->join('venue_booking','venue_booking.booking_id=venue_players.booking_id','left');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');

		$this->db->where('venue_players.user_id',$co_player);
		$this->db->where('venue_players.booking_id',$booking_id);
		return $this->db->get()->row();
	}

	public function get_match_played_rate($user,$sports_id,$co_player,$cur_date,$cur_time)
	{
		$this->db->select('*');
		$this->db->from('venue_booking');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');
		$this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id','left');
		$this->db->where("(venue_booking.user_id = '$user' OR venue_booking.user_id = '$co_player') ");
		$this->db->where('venue_players.user_id!=',$user);
		$this->db->where('venue_players.user_id',$co_player);
		//$this->db->where('venue_booking.user_id',$user);

		$this->db->where('date(venue_booking.date)<=',$cur_date);
		$this->db->where('time(venue_booking_time.court_time)<',$cur_time);
		$this->db->where('venue_booking.sports_id',$sports_id);
		$this->db->where('venue_booking.payment_mode!=',2);
		$this->db->group_by('venue_players.booking_id');
		return $this->db->get()->result();
	}
	///////////////////////////////////////////////////////////////////////////
	public function coplayer_rating_update($data,$condition)
	{
		$row=$this->db->update('co_player',$data,$condition);
		return $row;
	}
	//////////////////////////////////////////////////////////////////////////////////
	public function insert_rate_coplayer($data){
		$row =$this->db->insert('co_player',$data);
		return $row;
	}
	public function get_match_count_coplayer($user)
	{

		$this->db->select('venue_players.booking_id,venue_booking_time.court_time as court_time,venue_booking.date');
		$this->db->from('venue_players');
		$this->db->where('venue_players.user_id',$user);
		$this->db->join('venue_booking','venue_booking.booking_id=venue_players.booking_id','left');
		$this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_players.booking_id','left');
		//$this->db->where("venue_booking.sports_id",$sports);
		$this->db->where('date(venue_booking.date)<=',date('Y:m:d'));
		$this->db->where('venue_booking.payment_mode !=',2);

		$this->db->group_by('venue_players.booking_id');
		return $this->db->get()->result();
	}
	public function update_booking_rating($data,$id)
	{
		$this->db->where('id',$id);
		$row=$this->db->update('booking_rating',$data);
		return $row;
	}



}
