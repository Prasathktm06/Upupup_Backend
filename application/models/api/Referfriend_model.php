<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Referfriend_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
////////////////////////// check referal id exist or not ///////////////////////////////
 public function get_check_user_refer($user_id)
	{
		$this->db->select("id,referal_id");
		$this->db->from("user_referal");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
	
//////////////////////////// add referal id if not exist /////////////////////////////
    public function add_user_referal($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
////////////////////////// Refer a friend details ///////////////////////////////
 public function get_refer_details($user_id)
	{
		$this->db->select("SUM(install_count) as install_count,SUM(install_coin) as install_coin");
		$this->db->from("referal_bonus");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////// Refer a friend installation count ///////////////////////////////
 public function get_refer_counts($user_id)
	{
		$this->db->select("count(users_id) as install_count");
		$this->db->from("refer_friend_bonus");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////// Refer a friend settings ///////////////////////////////
 public function get_refer_setting($location_id)
	{
		$this->db->select("id,install_count,install_bonus_coin");
		$this->db->from("refer_bonus_setting");
		$this->db->where('install_status',1);
		$this->db->where('status',1);
		$this->db->where('location_id',$location_id);
		return $this->db->get()->result();
	}
////////////////////////// Refer a friend booking settings ///////////////////////////////
 public function get_refer_book_setting($location_id)
	{
		$this->db->select("booking_bonus_coin");
		$this->db->from("refer_bonus_setting");
		$this->db->where('booking_bonus_status',1);
		$this->db->where('status',1);
		$this->db->where('location_id',$location_id);
		return $this->db->get()->result();
	}
////////////////////////// fetch user_id based on referal id ///////////////////////////////
 public function get_refer_check($referal_id)
	{
		$this->db->select("users_id");
		$this->db->from("user_referal");
		$this->db->where('referal_id',$referal_id);
		return $this->db->get()->result();
	}
//////////////////////////// add buy coin payment details start /////////////////////////////
    public function add_friend_refer($table,$referal_data)
	{
		if($this->db->insert($table,$referal_data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
////////////////////////// check user installation count ///////////////////////////////
 public function get_check_refer_count($user_id)
	{
		$this->db->select("id,referal_count");
		$this->db->from("user_referal_count");
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////////////// my account //////////////////////////////////
	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
////////////////////////// user location details ///////////////////////////////
 public function get_user_location($user_id)
	{
		$this->db->distinct('area.location_id');
		$this->db->select("area.location_id");
		$this->db->from("user_area");
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////// my account details ///////////////////////////////
 public function get_my_account($user_id)
	{
		$this->db->select("up_coin,bonus_coin,total");
		$this->db->from("my_account");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////////////// update my account //////////////////////////////////
	public function update_my_account($data,$table,$user_id)
	{
		return $this->db->update($table, $data, array('users_id' => $user_id));
	}
//////////////////////////// add refer bonus data /////////////////////////////
    public function add_refer_bonusset($table,$referal_bonus_data)
	{
		if($this->db->insert($table,$referal_bonus_data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
////////////////////////// check already get referal bonus ///////////////////////////////
 public function get_installation_check($installed_user_id)
	{
		$this->db->select("id");
		$this->db->from("refer_friend_bonus");
		$this->db->where('installed_user_id',$installed_user_id);
		return $this->db->get()->result();
	}

}
