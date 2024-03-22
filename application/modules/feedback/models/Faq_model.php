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
class Faq_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}

	/////////////////////////////////////Coupan List///////////////////////////////////////////////
	public function get_faq_list(){
		$this->db->select('*');
		$this->db->from('faq');
		return $this->db->get()->result_array();
	}
	/////////////////////////////////Insert Function///////////////////////////////////////////////
	public function add_data($insert_array){
		$row =$this->db->insert('faq',$insert_array);
		return $row;
	}
	/////////////////////////////////Item Details//////////////////////////////////////////////////
	public function get_details($faq_id){
		$this->db->select('*');
		$this->db->from('faq');
		$this->db->where('faq_id',$faq_id);
		return $this->db->get()->result_array();
	}
	/////////////////////////////////Update function/////////////////////////////////////////////
	public function update_data($data,$coupon_id){
		return $this->db->update('faq', $data, array('faq_id' => $coupon_id));
	}
	////////////////////////////////Data Delete//////////////////////////////////////////////
	public function delete_data($faq_id){
		$this->db->where('faq_id',$faq_id);
		$row=$this->db->delete('faq');
		return $row;	
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	public function get_offerTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,offer.*,venue.venue,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);
		$this->db->join("venue","offer.venue_id=venue.id");
		$this->db->from('offer , (SELECT @s:='.$_GET['start'].') AS s');
		
		if($column==1)
		{
			$this->db->order_by('offer',$dir);
		}
		
		$where = "(offer.offer LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();
		
		
		$this->db->from('offer');
		$where = "(offer.offer LIKE '%".$value."%')";
		
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
	

	
	
}

