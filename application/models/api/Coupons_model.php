<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Coupons_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	/////////////////////////Coupon List////////////////////////////////////////////////////
	public function get_coupons($id){
		$today 	= date("Y-m-d");
		$this->db->select('coupons.*');
		$this->db->from('coupons');
		//$this->db->join('coupon_user','coupon_user.coupon_id=coupons.coupon_id','left');
		$this->db->where('valid_to>=',$today);

		if(!empty($id)){
		foreach ($id as $key => $value) {
			$this->db->where('coupons.coupon_id!=',$value->coupon_id);
		}
		}
		
		$this->db->where('status',1);
		
		return $this->db->get()->result_array();
	}
	public function redeem_coupon($data,$coupon)
	{
		return $this->db->update('coupons', $data, array('coupon_id' => $coupon));
	}

	public function add_coupon($data)
	{
		if($this->db->insert('coupon_user', $data))
			return true;
			else
				return false;
	}

	public function get_user_coupons($id)
	{
		$this->db->select('coupon_id');
		$this->db->from('coupon_user');
		$this->db->where('user_id',$id);
		return $this->db->get()->result();
	}
	
	public function get_user_areas($id)
	{
		$this->db->select('area_id');
		$this->db->from('user_area');
		$this->db->where('user_id',$id);
		return $this->db->get()->result();
	}
	//////////////////////////////////////////////////////////////////////////////////////////
}