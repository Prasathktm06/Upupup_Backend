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
class Charges_model extends CI_model {
	
	
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
	////////////// service charge list //////////////
	public function get_service_charges()
	{
		$this->db->select('service_charge.*,locations.location');
		$this->db->from('service_charge');
		$this->db->join('locations','locations.id=service_charge.location_id');
		$this->db->order_by('service_charge.id','desc');
		return $this->db->get()->result_array();
	}
	/////// update status /////////
	public function update_status($data,$table,$location_id)
	{
		return $this->db->update($table, $data, array('location_id' => $location_id));
	}
	////////////// add data  //////////////
	public function insert_data($data,$table){
		if($this->db->insert($table, $data))
			return $this->db->insert_id() ;
			else
				return false;
	}
	////////////// service charge details //////////////
	public function get_charge_details($id)
	{
		$this->db->select('service_charge.*,locations.location');
		$this->db->from('service_charge');
		$this->db->join('locations','locations.id=service_charge.location_id');
		$this->db->where('service_charge.id',$id);
		return $this->db->get()->row();
	}
/////////////// update service charge details ///////////////////
	public function update_charges($id,$data){
		return $this->db->update('service_charge', $data, array('id' => $id));
	}
///////////////////////// delete  service charges ////////////////////////////
	public function delete_charge($id){
		$this->db->delete('service_charge', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// location data //////////////
	public function get_location_data($id)
	{
		$this->db->select('location_id');
		$this->db->from('service_charge');
		$this->db->where('id',$id);
		return $this->db->get()->result();
	}
/////// update status /////////
	public function update_charge_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}

}

