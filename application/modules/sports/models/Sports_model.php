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
class Sports_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	
	public function get_sports($id=''){
		$this->db->select('*');
		$this->db->from('sports');
		if($id!=='')
			$this->db->where('id',$id);
		return $this->db->get()->result();
	}
	
	public function update_sports($id,$data){
		return $this->db->update('sports', $data, array('id' => $id));
	}
	
	public function add_sports($data){
		if($this->db->insert('sports', $data))
			return true;
			else
				return false;
	}
	
	public function get_sportsTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,sports.*,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);
		$this->db->from('sports , (SELECT @s:='.$_GET['start'].') AS s');
		
		if($column==1)
		{
			$this->db->order_by('sports',$dir);
		}
		
		$where = "(sports.sports LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();
		
		
		$this->db->from('sports');
		$where = "(sports.sports LIKE '%".$value."%')";
		
		$this->db->where($where);
		
		$query = $this->db->get();
		
		
		$this->db->from('sports');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();
		
		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();
		
		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];
		
		return $result;
	}
	
public function delete($id)
{
	if($this->db->delete('sports',array('id'=>$id))){
		return true;
	}else{
		return false;
	}
}
/////////////////////////Sports Exist//////////////////////////////////////////////
	public function sports_exist($sports){
		$this->db->select('*');
		$this->db->from('sports');
		$this->db->where('sports',$sports);
		return $this->db->get()->result();
	}
	
	
}

