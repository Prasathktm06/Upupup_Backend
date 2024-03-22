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
class Programs_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	
	////////////// Programs list //////////////
	public function get_programs(){
		$this->db->select('trainers_program.*,trainers.name AS trainer_name,locations.location');
		$this->db->from('trainers_program');
		$this->db->join('trainers','trainers.id=trainers_program.trainers_id');
		$this->db->join('locations','locations.id=trainers_program.location_id');
		$this->db->order_by('trainers_program.id','desc');
		return $this->db->get()->result_array();
	}
	////////////////// trainers list //////////////////////
	public function get_trainers(){
		$this->db->select('id,name');
		$this->db->from('trainers');
		$this->db->where('status',1);
		return $this->db->get()->result();

	}
	/////////// add programs /////////////////
	public function insert_programs($data)
	{
		if($this->db->insert('trainers_program', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	///////////// update program status //////////////
	public function update_program_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
/////////////// update programs details ///////////////////
	public function update_programs($id,$data){
		return $this->db->update('trainers_program', $data, array('id' => $id));
	}
	/////////////// program details ///////////////////
	public function get_program_details($id){
		$this->db->select('trainers_program.*,locations.location');
		$this->db->from('trainers_program');
		$this->db->join('locations','locations.id=trainers_program.location_id');
		$this->db->where('trainers_program.id',$id);       
		return $this->db->get()->row();
	}
///////////////////////// delete program ////////////////////////////
	public function delete_program($id){
		$this->db->delete('trainers_program', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////////// location //////////////////////
	public function get_location(){
		$this->db->select('*');
		$this->db->from('locations');
			$this->db->where('status',1);
			return $this->db->get()->result();

	}
}

