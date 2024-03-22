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
class Trainers_model extends CI_model {
	
	
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	
	////////////// trainers list //////////////
	public function get_trainers($location_id=""){
		$this->db->select('trainers.*,locations.location');
		$this->db->from('trainers');
		$this->db->join('locations','locations.id=trainers.location_id');
		if ($location_id) {
			$this->db->where('trainers.location_id',$location_id);
		}
		$this->db->order_by('trainers.id','desc');
		return $this->db->get()->result_array();
	}
	////////////////// location //////////////////////
	public function get_location(){
		$this->db->select('*');
		$this->db->from('locations');
			$this->db->where('status',1);
			return $this->db->get()->result();

	}
	/////////// add trainers /////////////////
	public function insert_trainer($data)
	{
		if($this->db->insert('trainers', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	/////////////// trainer details ///////////////////
	public function get_trainer_details($id){
		$this->db->select('trainers.*,locations.location');
		$this->db->from('trainers');
		$this->db->join('locations','locations.id=trainers.location_id');
		$this->db->where('trainers.id',$id);       
		return $this->db->get()->row();
	}
/////////////// update trainer details ///////////////////
	public function update_trainers($id,$data){
		return $this->db->update('trainers', $data, array('id' => $id));
	}
	/////// update status /////////
	public function update_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
/////////////// sports details ///////////////////	
	public function get_sports($id=''){
		$this->db->select('*');
		$this->db->from('sports');
		if($id!=='')
			$this->db->where('id',$id);
			$this->db->where('status',1);
			return $this->db->get()->result();
	}
	////////////// trainers sports list //////////////
	public function get_trainer_sports($id){
		$this->db->select('GROUP_CONCAT(sports_id) as sports_id');
		$this->db->from('trainers_sports');
		$this->db->where('trainers_id',$id);  
		return $this->db->get()->row();
	}	
	////////////// remove sports  //////////////
	public function delete_sports($id){
		$this->db->delete('trainers_sports', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// add sports  //////////////
	public function add_sports($data,$table){
		if($this->db->insert($table, $data))
			return $this->db->insert_id() ;
			else
				return false;
	}
	/////////////// trainer area details ///////////////////
	public function get_location_areas($id){
		$this->db->select('area.*');
		$this->db->from('trainers');
		$this->db->join('locations','locations.id=trainers.location_id');
		$this->db->join('area','area.location_id=locations.id');
		$this->db->where('trainers.id',$id);       
		return $this->db->get()->result();
	}
	////////////// trainers area list //////////////
	public function get_trainer_areas($id){
		$this->db->select('GROUP_CONCAT(area_id) as area_id');
		$this->db->from('trainers_area');
		$this->db->where('trainers_id',$id);  
		return $this->db->get()->row();
	}
	////////////// remove trainer areas  //////////////
	public function delete_areas($id){
		$this->db->delete('trainers_area', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	////////////// add areas  //////////////
	public function add_areas($data,$table){
		if($this->db->insert($table, $data))
			return $this->db->insert_id() ;
			else
				return false;
	}
	/////////////// trainer program details ///////////////////
	public function get_trainer_programs($id){
		$this->db->select('trainers_program.*,locations.location');
		$this->db->from('trainers_program');
		$this->db->join('locations','locations.id=trainers_program.location_id');
		$this->db->where('trainers_program.trainers_id',$id);       
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
	/////// update program status /////////
	public function update_program_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
	/////////////// program details ///////////////////
	public function get_program_details($id){
		$this->db->select('trainers_program.*,locations.location');
		$this->db->from('trainers_program');
		$this->db->join('locations','locations.id=trainers_program.location_id');
		$this->db->where('trainers_program.id',$id);       
		return $this->db->get()->row();
	}
/////////////// update programs details ///////////////////
	public function update_programs($id,$data){
		return $this->db->update('trainers_program', $data, array('id' => $id));
	}
///////////////////////// delete program ////////////////////////////
	public function delete_program($id){
		$this->db->delete('trainers_program', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
///////////////////////// delete trainer ////////////////////////////
	public function delete_trainers($id){
		$this->db->delete('trainers', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
///////////////////////// delete trainer programs////////////////////////////
	public function delete_trainer_program($id){
		$this->db->delete('trainers_program', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
///////////////////////// delete trainer area ////////////////////////////
	public function delete_trainer_area($id){
		$this->db->delete('trainers_area', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
///////////////////////// delete trainer sports ////////////////////////////
	public function delete_trainer_sport($id){
		$this->db->delete('trainers_sports', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
///////////////////////// delete trainer testimonials ////////////////////////////
	public function delete_trainer_testimonial($id){
		$this->db->delete('trainers_testimonial', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
///////////////////////// delete trainer followers ////////////////////////////
	public function delete_trainer_followers($id){
		$this->db->delete('trainers_followers', array('trainers_id' => $id));
		return ($this->db->affected_rows() == 1);
	}
/////////////// trainer testimonial details ///////////////////
	public function get_trainer_testimonial($id){
		$this->db->select('*');
		$this->db->from('trainers_testimonial');
		$this->db->where('trainers_id',$id);       
		return $this->db->get()->result();
	}
	/////////// add testimonial /////////////////
	public function insert_testimonial($data)
	{
		if($this->db->insert('trainers_testimonial', $data))
			return  $this->db->insert_id();
			else
		    return false;
	}
	/////// update testimonial status /////////
	public function update_testimonial_status($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
/////////////// update testimonials details ///////////////////
	public function update_testimonial($id,$data){
		return $this->db->update('trainers_testimonial', $data, array('id' => $id));
	}
	/////////////// testimonial details ///////////////////
	public function get_testimonial_details($id){
		$this->db->select('*');
		$this->db->from('trainers_testimonial');
		$this->db->where('id',$id);       
		return $this->db->get()->row();
	}
///////////////////////// delete  testimonials ////////////////////////////
	public function delete_testimonial($id){
		$this->db->delete('trainers_testimonial', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}
	//////////////////////////City List///////////////////////////////
	public function get_location_list(){
		$this->db->select('id,location');
		$this->db->from('locations');
		return $this->db->get()->result_array();
	}
	////////////////// check trainer phone number already exist //////////////////////
	public function get_phone_check($phone_number){
		$this->db->select('*');
		$this->db->from('trainers');
		$this->db->where('phone',$phone_number);
		return $this->db->get()->result();
	}
}

