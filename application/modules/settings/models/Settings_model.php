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
class Settings_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	///////////////////////////////////////////////////////////////////////////////////
	public function email_list(){
		$this->db->select('*');
		$this->db->from('upupup_email');
		return $this->db->get()->result_array();
	}
	////////////////////////////////////////////////////////////////////////
	public function phone_list(){
		$this->db->select('*');
		$this->db->from('upupup_phone');
		return $this->db->get()->result_array();
	}
	//////////////////////////////////////////////////////////////////////////
	public function email_insert($insert_array){
		$row =$this->db->insert('upupup_email',$insert_array);
		return $row;
	}
	////////////////////////////////////////////////////////////////
	public function phone_insert($insert_array){
		$row =$this->db->insert('upupup_phone',$insert_array);
		return $row;
	}
	///////////////////////////Update Data///////////////////////////
	public function update_data($data,$id,$table){
		return $this->db->update($table, $data, array('id' => $id));
	}
	///////////////////////////Item Details/////////////////////////////////
	public function get_details($id,$table){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('id',$id);
		return $this->db->get()->result_array();
	}
	//////////////////////////Item Delete//////////////////////////////
	public function delete_data($id,$table){
		$this->db->where('id',$id);
		$row=$this->db->delete($table);
		return $row;	
	}
	///////////////////////////////////////////////////////////////////////////
}

