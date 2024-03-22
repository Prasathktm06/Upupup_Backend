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
class Advertisement_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	
	////////////// shop advertisement list //////////////
	public function get_shop_adv(){
		$this->db->select('advertisement_shop.*,locations.location');
		$this->db->from('advertisement_shop');
		$this->db->join('locations','locations.id=advertisement_shop.location_id');
		$this->db->order_by('advertisement_shop.id','desc');
		return $this->db->get()->result_array();
	}
	////////////// add Advertisement  //////////////
	public function insert_advertisement($data,$table){
		if($this->db->insert($table, $data))
			return $this->db->insert_id() ;
			else
				return false;
	}
	/////// update advertisement  /////////
	public function update_adv($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
	/////////////// Advertisement details ///////////////////
	public function get_adv_details($id,$table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('id',$id);       
		return $this->db->get()->row();
	}
	/////////////// Trainer Advertisement details ///////////////////
	public function get_trainer_details($id){
		$this->db->select('advertisement_trainers.*,locations.location');
		$this->db->from('advertisement_trainers');
		$this->db->join('locations','locations.id=advertisement_trainers.location_id');
		$this->db->where('advertisement_trainers.id',$id);       
		return $this->db->get()->row();
	}
///////////////////////// delete advertisement ////////////////////////////
	public function delete_adv($id,$table){
		$this->db->delete($table, array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// trainer advertisement list //////////////
	public function get_trainer_adv(){
		$this->db->select('advertisement_trainers.*,locations.location');
		$this->db->from('advertisement_trainers');
		$this->db->join('locations','locations.id=advertisement_trainers.location_id');
		$this->db->order_by('id','desc');
		return $this->db->get()->result_array();
	}
	////////////// venue advertisement list //////////////
	public function get_venue_adv(){
		$this->db->select('advertisement_venue.*,locations.location');
		$this->db->from('advertisement_venue');
		$this->db->join('locations','locations.id=advertisement_venue.location_id');
		$this->db->order_by('id','desc');
		return $this->db->get()->result_array();
	}
	////////////// location list  //////////////
	public function get_location(){
		$this->db->select('*');
		$this->db->from('locations');
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
	/////////////// Trainer Advertisement details ///////////////////
	public function get_venue_details($id){
		$this->db->select('advertisement_venue.*,locations.location');
		$this->db->from('advertisement_venue');
		$this->db->join('locations','locations.id=advertisement_venue.location_id');
		$this->db->where('advertisement_venue.id',$id);       
		return $this->db->get()->row();
	}
	/////////////// Shop Advertisement details ///////////////////
	public function get_shop_details($id){
		$this->db->select('advertisement_shop.*,locations.location');
		$this->db->from('advertisement_shop');
		$this->db->join('locations','locations.id=advertisement_shop.location_id');
		$this->db->where('advertisement_shop.id',$id);       
		return $this->db->get()->row();
	}
	////////////// shop advertisement2 list //////////////
	public function get_shops_adv(){
		$this->db->select('advertisement_shops.*,locations.location');
		$this->db->from('advertisement_shops');
		$this->db->join('locations','locations.id=advertisement_shops.location_id');
		$this->db->order_by('advertisement_shops.id','desc');
		return $this->db->get()->result_array();
	}
	/////////////// Shop Advertisement 2 details ///////////////////
	public function get_shops_details($id){
		$this->db->select('advertisement_shops.*,locations.location');
		$this->db->from('advertisement_shops');
		$this->db->join('locations','locations.id=advertisement_shops.location_id');
		$this->db->where('advertisement_shops.id',$id);       
		return $this->db->get()->row();
	}
}

