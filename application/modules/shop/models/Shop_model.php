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
class Shop_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	////////////// location list  //////////////
	public function get_location(){
		$this->db->select('*');
		$this->db->from('locations');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	////////////// add data  //////////////
	public function insert_data($data,$table){
		if($this->db->insert($table, $data))
			return $this->db->insert_id() ;
			else
				return false;
	}
	////////////// shop list //////////////
	public function get_shop($location_id=""){
		$this->db->select('shop.*,locations.location,area.area');
		$this->db->from('shop');
		$this->db->join('locations','locations.id=shop.location_id');
		$this->db->join('area','area.id=shop.area_id');
		if ($location_id) {
			$this->db->where('shop.location_id',$location_id);
		}
		$this->db->order_by('shop.id','desc');
		return $this->db->get()->result_array();
	}
    /////////////// update details ///////////////////
	public function update($id,$data){
		return $this->db->update('shop', $data, array('id' => $id));
	}
	////////////// shop details //////////////
	public function get_shop_details($id){
		$this->db->select('shop.*,locations.location');
		$this->db->from('shop');
		$this->db->join('locations','locations.id=shop.location_id');
		$this->db->where('shop.id',$id);
		return $this->db->get()->row();
	}
	////////////// area based on location_id //////////////
	public function get_area($location_id)
	{
		$this->db->select('*');
		$this->db->from('area');
		$this->db->where('area.location_id',$location_id);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
/////////////// sports details ///////////////////	
	public function get_sports(){
		$this->db->select('*');
		$this->db->from('sports');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	////////////// remove sports  //////////////
	public function delete_sports($id){
		$this->db->delete('shop_sports', array('shop_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// shop sports list //////////////
	public function get_shop_sports($id){
		$this->db->select('GROUP_CONCAT(sports_id) as sports_id');
		$this->db->from('shop_sports');
		$this->db->where('shop_id',$id);  
		return $this->db->get()->row();
	}
	/////// update status /////////
	public function update_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
	/////////////// shop offer details ///////////////////
	public function get_shop_offer($id){
		$this->db->select('*');
		$this->db->from('shop_offer');
		$this->db->where('shop_id',$id);       
		return $this->db->get()->result();
	}
    /////////////// update details ///////////////////
	public function update_offer($id,$data){
		return $this->db->update('shop_offer', $data, array('id' => $id));
	}
	/////////////// shop details ///////////////////
	public function get_offer_details($id){
		$this->db->select('*');
		$this->db->from('shop_offer');
		$this->db->where('id',$id);       
		return $this->db->get()->row();
	}
	////////////// remove offer  //////////////
	public function delete_offer($id){
		$this->db->delete('shop_offer', array('shop_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// remove shop //////////////
	public function delete_shop($id){
		$this->db->delete('shop', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// remove shop offer //////////////
	public function delete_shop_offer($id){
		$this->db->delete('shop_offer', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	//////////////////////////City List///////////////////////////////
	public function get_location_list(){
		$this->db->select('id,location');
		$this->db->from('locations');
		return $this->db->get()->result_array();
	}
	////////////////// check shop phone number already exist //////////////////////
	public function get_phone_check($phone_number){
		$this->db->select('*');
		$this->db->from('shop');
		$this->db->where('phone',$phone_number);
		return $this->db->get()->result();
	}
}

