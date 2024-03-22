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
class Places_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}

	public function get_location($id=''){
		$this->db->select('*');
		$this->db->from('locations');
		if($id!=='')
			$this->db->where('id',$id);
			return $this->db->get()->result();
	}

	public function update_location($id,$data){
		return $this->db->update('locations', $data, array('id' => $id));
	}

	public function add_location($data){
		$row	= $this->db->insert('locations',$data);
		$id 	= $this->db->insert_id();
		$q 		= $this->db->get_where('locations', array('id' => $id));
		return $q->row();
	}

	public function get_locationTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,locations.*,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete,status',false);
		$this->db->from('locations , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('location',$dir);
		}

		$where = "(locations.location LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('locations');
		$where = "(locations.location LIKE '%".$value."%')";

		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('locations');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function get_area($id=''){
		$this->db->select('*');
		$this->db->from('area');
		if($id!=='')
			$this->db->where('id',$id);
			return $this->db->get()->result();
	}

	public function add_area($data){
		if($this->db->insert('area', $data))
			return true;
			else
				return false;
	}

	public function update_area($id,$data){
		return $this->db->update('area', $data, array('id' => $id));
	}

	public function get_areaTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,area.*,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete,area.status,locations.location',false);
		$this->db->from('area , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->join('locations','locations.id=area.location_id','left');
		if($column==1)
		{
			$this->db->order_by('area',$dir);
		}
		if($column==2)
		{
			$this->db->order_by('location',$dir);
		}

		$where = "(area.area LIKE '%".$value."%' OR locations.location LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('area');
		$where = "(area.area LIKE '%".$value."%' OR locations.location LIKE '%".$value."%')";
		$this->db->join('locations','locations.id=area.location_id','left');
		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('area');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

public function delete($id,$table,$where)
{
	$this->db->delete($table, array($where => $id));
		return ($this->db->affected_rows() == 1);
}
	/////////////////////////City Exist//////////////////////////////////////////////
	public function city_exist($location){
		$this->db->select('*');
		$this->db->from('locations');
		$this->db->where('location',$location);
		return $this->db->get()->row();
	}
	/////////////////////////Area Exist//////////////////////////////////////////////
	public function area_exist($condition){
		$this->db->select('*');
		$this->db->from('area');
		$this->db->where($condition);
		return $this->db->get()->row();
	}
	////////////////////////////Area List//////////////////////////////////////////
	public function city_areas($id){
		$this->db->select('*');
		$this->db->from('area');
		$this->db->where('location_id',$id);
		return $this->db->get()->result_array();
	}
	///////////////////////City Active Or Not//////////////////////////////////
	public function area_details($id){
		$this->db->select('*');
		$this->db->from('area');
		$this->db->where('id',$id);
		return $this->db->get()->row();
	}

	public function location_active($id){
		$this->db->select('*');
		$this->db->from('locations');
		$this->db->where('id',$id);
		$this->db->where('status',1);
		return $this->db->get()->row();
	}
	///////////////////////////////////////////////////////////////////////

}
