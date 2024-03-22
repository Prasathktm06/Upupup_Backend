<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Shop_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

/////////////////////// trainers list ///////////////////////////
	public function get_shop_list($user_id,$location_ids){
            $this->db->distinct('shop.id');
	       	$this->db->select('shop.*');
			$this->db->from('shop');
			$this->db->join('user_area','shop.area_id=user_area.area_id');
			$this->db->join('user_sports','user_sports.user_id=user_area.user_id');
			$this->db->where("user_area.user_id",$user_id);
			$this->db->where("shop.status",1);
			$this->db->where("shop.location_id",$location_ids);
			return $this->db->get()->result();	

	}
////////////////////////// location details ///////////////////////////////
 public function get_shop_location($location_id)
	{
		$this->db->select("location");
		$this->db->from("locations");
		$this->db->where('id',$location_id);
		return $this->db->get()->result();
	}
////////////////////////// area details ///////////////////////////////
 public function get_shop_area($area_id)
	{
		$this->db->select("area");
		$this->db->from("area");
		$this->db->where('id',$area_id);
		return $this->db->get()->result();
	}
////////////////////////// shop offer details ///////////////////////////////
 public function get_shop_offers($id)
	{
		$this->db->select("*");
		$this->db->from("shop_offer");
		$this->db->where('shop_id',$id);
		$this->db->where('status',1);
		$this->db->where("end_date >=",date('d-m-Y'));
		return $this->db->get()->result();
	}
////////////////////////// shop sports details ///////////////////////////////
 public function get_shop_sports($id)
	{
		$this->db->select("sports.id,sports.sports,sports.image");
		$this->db->from("shop_sports");
		$this->db->join('sports','sports.id=shop_sports.sports_id');
		$this->db->where('shop_sports.shop_id',$id);
		return $this->db->get()->result();
	}
/////////////////////// trainers unfilter list ///////////////////////////
/////////////////////// trainers unfilter list ///////////////////////////
	public function get_shop_unfilter($user_id,$location_ids)
	{
            $this->db->distinct('shop.id');
	       	$this->db->select('shop.*');
			$this->db->from('shop');
			$this->db->join('user_area','shop.area_id=user_area.area_id');
			$this->db->join('user_sports','user_sports.user_id=user_area.user_id');
			$this->db->where("user_area.user_id",$user_id);
			$this->db->where("shop.status",1);
			$this->db->where("shop.location_id",$location_ids);
			$result1= $this->db->get()->result();

	       	$this->db->select('*');
			$this->db->from('shop');
			$this->db->where("status",1);
			$this->db->where("location_id",$location_ids);
	         foreach($result1 as $value=>$val)
        	{
        	    $shops_id=  $val->id;
        	    
        	   $this->db->where("id !=",$shops_id);
        	}	
            return $this->db->get()->result();
	}
////////////////////////// shop advertisement details ///////////////////////////////
 public function get_shop_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_shop");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// trainer advertisement details ///////////////////////////////
 public function get_trainer_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_trainers");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// venue advertisement details ///////////////////////////////
 public function get_venue_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_venue");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// shops advertisement details ///////////////////////////////
 public function get_shops_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_shops");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
//////////////////////// user details ////////////////////////////////
	public function get_user_details($user_id){
		$this->db->select('name,phone_no');
		$this->db->from('users');
		$this->db->where('id',$user_id);
		return $this->db->get()->result();
	}
}
