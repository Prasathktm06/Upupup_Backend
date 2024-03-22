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
class Users_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}

	public function get_sports($id=''){
		$this->db->select('*');
		$this->db->from('sports');
		return $this->db->get()->result();
	}

	public function get_venue($id='',$where='id'){
		$this->db->select('*');
		$this->db->from('venue');
		if($id!=='')
			$this->db->where($where,$id);
			return $this->db->get()->result();
	}
	public function get_details($id='',$table){
		$this->db->select("*");
		$this->db->from($table);
		if($id!=='')
			$this->db->where('id',$id);
			return $this->db->get()->result();
	}

	public function update_users($id,$data){
		return $this->db->update('users', $data, array('id' => $id));
	}
	public function add_users($data){
		if($this->db->insert('users', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	public function delete($id,$table){
		$this->db->delete($table, array('id'=>$id));
		return ($this->db->affected_rows() == 1);
	}
	public function add_users_sports($data){
		if($this->db->insert('user_sports', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	public function add_users_area($data){
		if($this->db->insert('user_area', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	public function add_coplayer($data){
		if($this->db->insert('co_player', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	public function get_usersTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,users.id,users.name,users.phone_no,users.device_id,users.image,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);
		$this->db->from('users , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('name',$dir);
		}
		if($column==1)
		{
			$this->db->order_by('phone_no',$dir);
		}
		if($column==1)
		{
			$this->db->order_by('rating',$dir);
		}

		$where = "(users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' )";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('users');
		$where = "(users.name LIKE '%".$value."%' OR users.phone_no LIKE '%".$value."%' )";

		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('offer');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function get_data($id){

		$this->db->select('users.id ,users.name,users.email,users.address,users.phone_no,GROUP_CONCAT(DISTINCT sports.id) as sports,GROUP_CONCAT(DISTINCT area.id) as area,locations.id as location,users.image');

		$this->db->from('users');
		$this->db->join('user_area','user_area.user_id=users.id','left');
		$this->db->join('area','user_area.area_id=area.id','left');
		$this->db->join('user_sports','user_sports.user_id=users.id','left');
		$this->db->join('sports','user_sports.sports_id=sports.id','left');

		$this->db->join('locations','locations.id=area.location_id','left');
		$this->db->where('users.id',$id);
		$this->db->group_by('users.id');
		return $this->db->get()->row();
	}

	public function get_coplayers($user,$sports){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];

		$this->db->select('users.id,@s:=@s+1 serial_number, users.id,users.name,co_player.rating,sports.id as sports_id',false);
		$this->db->from('co_player , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->join("sports","sports.id=co_player.sports_id");
		$this->db->join("users","users.id=co_player.co_player");
		$this->db->where('co_player.user_id',$user);
		$this->db->where('co_player.co_player !=',$user);
		$this->db->where('co_player.sports_id',$sports);
		//$this->db->group_by('co_player.user_id');
		if($column==1)
		{
			$this->db->order_by('name',$dir);
		}


		$where = "(users.name LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.name  LIKE '%".$value."%' )";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('co_player');
		$where = "(users.name LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR users.name  LIKE '%".$value."%' )";
		$this->db->join("sports","sports.id=co_player.sports_id");
		$this->db->join("users","users.id=co_player.co_player");
		$this->db->where('co_player.user_id',$user);
			$this->db->where('co_player.co_player !=',$user);
		$this->db->where('co_player.sports_id',$sports);
		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('co_player');
		$this->db->join("sports","sports.id=co_player.sports_id");
		$this->db->join("users","users.id=co_player.co_player");
		$this->db->where('co_player.user_id',$user);
			$this->db->where('co_player.co_player !=',$user);
		$this->db->where('co_player.sports_id',$sports);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function update_coplayers_rating($co,$sports,$data){
		return $this->db->update('co_player', $data, array('co_player' => $co,'sports_id'=>$sports));
	}

	public function delete_coplayers($co,$sports){
		$this->db->delete('co_player', array('co_player' => $co,'sports_id'=>$sports));
		return ($this->db->affected_rows() == 1);
	}
	public function delete_users($user){
		$this->db->delete('users', array('id'=>$user));
		return ($this->db->affected_rows() == 1);
	}

	public function get_area_id($area)
	{
		$this->db->select('id');
		$this->db->from('area');
		$this->db->where('area',$area);
		return $this->db->get()->row();
	}
	public function get_sport_id($sports)
	{
		$this->db->select('id');
		$this->db->from('sports');
		$this->db->where('sports',$sports);
		return $this->db->get()->row();
	}
	////////////////////////////User Profile Details///////////////////////////////////////////
	public function get_item_data($condition,$field,$table)
	{
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($condition);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array = $row[$field];
		    }
		}else{
			$array[]="";
		}

		return $array;
	}
	////////////////////////////////Delete Preference///////////////////////////////////////
	public function delete_preference($id,$table){
		$this->db->where('user_id',$id);
		$row=$this->db->delete($table);
		return $row;		
	}
	////////////////////////// booking deatils based on venue_booking_id //////////////////
	public function get_user_booking($booking_id,$user_id)
	{

		 $this->db->select('venue.id,venue.venue,venue.lat as lat,venue.lon as lon,sports.sports,venue_booking.date,venue_booking.payment_mode,sports.image,area.area,venue_booking.transaction_id,venue_booking.time,venue_booking.payment_id,court.court,venue_booking.booking_id,venue_booking.cost,venue_booking.offer_value,venue_booking.price,court.intervel,venue_booking_time.court_time,users.name,users.id as user_id,venue_booking.bal');
		 $this->db->from('venue_booking');
		 $this->db->join('venue','venue.id=venue_booking.venue_id','left');
		 $this->db->join('sports','sports.id=venue_booking.sports_id','left');
		 $this->db->join('venue_players','venue_players.booking_id=venue_booking.booking_id','left');
		 $this->db->join('venue_booking_time','venue_booking_time.booking_id=venue_booking.booking_id','left');
		 $this->db->join('area','area.id=venue.area_id','left');
		 $this->db->join('users','users.id=venue_booking.user_id','left');
		 $this->db->join('court','court.id=venue_booking.court_id','left');
		 $this->db->where('venue_players.user_id',$user_id);
		 $this->db->where('venue_booking.booking_id ',$booking_id);
		 $this->db->where('venue_booking.payment_mode',1);
		 $this->db->order_by('venue_booking.date','ASC');
		 return $this->db->get()->result_array();
	}
	/////////////////// up coin active setting  ////////////////////////////
	public function get_up_coin()
	{
		$this->db->select('id,coin');
		$this->db->from('upcoin_setting');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	///////////////// refund details insertion //////////////////////////////
	public function add_refund_details($data){
		if($this->db->insert('booking_refund', $data))
			return $this->db->insert_id();
			else
				return false;
	}
	/////////////////// check any refund occuered on this booking id //////////////////
	public function get_refund_check($booking_id)
	{
		$this->db->select('id');
		$this->db->from('booking_refund');
		$this->db->where('booking_id',$booking_id);
		return $this->db->get()->result();
	}
	/////////////////// check user_id is already exist in my_account //////////////////
	public function get_myaccount_check($user_id)
	{
		$this->db->select('id,up_coin,bonus_coin,total');
		$this->db->from('my_account');
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
	/////// update my account /////////
	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
	////////////////// add my account details /////////////////////
	public function add_my_account_details($data){
		if($this->db->insert('my_account', $data))
			return $this->db->insert_id();
			else
				return false;
	}
////////////////////////// my account details ///////////////////////////////
 public function get_myaccount($user_id)
	{
		$this->db->select("up_coin,bonus_coin,total");
		$this->db->from("my_account");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->row();
	}
////////////////////////// total buy coins ///////////////////////////////
 public function get_buycoins($user_id)
	{
		$this->db->select("SUM(coin) AS purchased_coin");
		$this->db->from("buy_coin");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->row();
	}
////////////////////////// total buy coins ///////////////////////////////
 public function get_refund($user_id)
	{
		$this->db->select("SUM(coin) AS refund_coin");
		$this->db->from("booking_refund");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->row();
	}
////////////////////////// total booking bonus coins ///////////////////////////////
 public function get_booking_bonus($user_id)
	{
		$this->db->select("SUM(bonus_coin) AS booking_bonus");
		$this->db->from("booking_bonus");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->row();
	}
////////////////////////// total installation coin ///////////////////////////////
 public function get_install_bonus($user_id)
	{
		$this->db->select("SUM(install_coin) AS install_bonus");
		$this->db->from("referal_bonus");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->row();
	}
////////////////////////// total referal bonus coin ///////////////////////////////
 public function get_ref_bk_bonus($user_id)
	{
		$this->db->select("SUM(bonus_coin) AS refbk_bonus");
		$this->db->from("referal_booking_bonus");
		$this->db->where('user_id',$user_id);
		return $this->db->get()->row();
	}
////////////////////////// user channel ///////////////////////////////
 public function get_user_channel($user_id)
	{
		$this->db->select("user_channel.channel_id");
		$this->db->from("user_channel");
		$this->db->join('users','users.id=user_channel.user_id');
		$this->db->where('user_channel.user_id',$user_id);
		$this->db->where('users.image','');
		return $this->db->get()->row();
	}
}
