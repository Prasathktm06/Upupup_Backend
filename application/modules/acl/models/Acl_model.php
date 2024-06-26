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
class ACL_model extends CI_model {
	//protected $table = "user";
    //protected $primary_key = "user_id";
    //protected $fields = array("user_id", "name", "email", "password");
	
	/**
	 * acl config shortcut
	 *
	 * @var object
	 */
	private $_config;
	
	/**
	 * constructor
	 *
	 * @author William Duyck <fuzzyfox0@gmail.com>
	 */
	public function __construct() {
		parent::__construct();
		
		$this->_config = (object)$this->config->item('acl');
	}
	
	/*
	| -------------------------------------------------------------------
	|  user specific methods
	| -------------------------------------------------------------------
	*/
	
	/**
	 * get all users details
	 *
	 * @return	array	an array of CodeIgniter row objects for user
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_all_users() {
		$users = $this->db->get($this->_config->table['user']);
		return ($users->num_rows() > 0) ? $users->result() : FALSE;
	}
	
	/**
	 * get specific user details by constraint
	 *
	 * @param	string	$field	the field to constrain by
	 * @param	mixed	$value	the required value of field
	 * @return	array	an array of CodeIgniter row objects for user
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_user_by($field, $value,$status="") {
		$this->db->where($field, $value);
		if ($status) {
			$this->db->where('status', $status);
		}
		return $this->get_all_users();
	}
	
	/**
	 * get user details
	 *
	 * @param	int		$user_id	the unique identifier for the user to get
	 * @return	object	a CodeIgniter row object for user
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_user($user_id) {
		$user = $this->get_user_by('user_id', $user_id);
		return ($user !== FALSE) ? $user[0] : FALSE;
	}
	
	/**
	 * add user to database
	 *
	 * @param	assoc_array	$data	associative array of data to add into `user` table
	 * @return	boolean		TRUE/FALSE - whether or not addition was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function add_user($data) {
		$this->db->insert($this->_config->table['user'], $data);
	//	return ($this->db->affected_rows() == 1);
		return $this->db->insert_id();
	}
	
	/**
	 * delete user from database
	 *
	 * @param	int		$user_id	the unique identifier for the user to get
	 * @return	boolean	TRUE/FALSE - whether or not the deletion was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function del_user($user_id) {
		$this->db->delete($this->_config->table['user'], array('user_id' => $user_id));
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * update user details
	 *
	 * @param	int			$user_id	the unique identifier for the user to get
	 * @param	assoc_array	$data		the new data for the user
	 * @return	boolean		TRUE/FALSE - whether or not the changes were successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function edit_user($user_id, $data) {
		return $this->db->update($this->_config->table['user'], $data, array('user_id' => $user_id));
//		return ($this->db->affected_rows() == 1);
	}
	
	/*
	| -------------------------------------------------------------------
	|  user role relations
	| -------------------------------------------------------------------
	*/
	
	/**
	 * get users roles
	 *
	 * @param	string	$user_id	the unique identifier for the user
	 * @return	array	array of CodeIgniter row objects containing the user roles
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_user_roles($user_id) {
		$this->db->select($this->_config->table['role'] . '.*')
			->from($this->_config->table['user_role'])
			->where('user_id', $user_id)
			->join($this->_config->table['role'], $this->_config->table['role'] . '.role_id = ' . $this->_config->table['user_role'] . '.role_id', 'inner');
		
		$role = $this->db->get();
		return ($role->num_rows() > 0) ? $role->result() : FALSE;
	}
	
	/**
	 * assign user to role
	 *
	 * @param	int		$user_id	the unique identifier for the user to assign
	 * @param	int		$role_id	the unique identifier for the role to assign
	 * @return	boolean	TRUE/FALSE - whether or not the assignment was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function add_user_role($user_id, $role_id) {
		$this->db->insert($this->_config->table['user_role'], array(
			'user_id' => $user_id,
			'role_id' => $role_id
		));
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * remove user from role
	 *
	 * @param	int		$user_id	the unique identifier for the user
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	boolean	TRUE/FALSE - whether or not the removal was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function del_user_role($user_id, $role_id) {
		$this->db->delete($this->_config->table['user_role'], array(
			'user_id' => $user_id,
			'role_id' => $role_id
		));
		return ($this->db->affected_rows() == 1);
	}
	
	public function edit_user_roles($user_id, $role_array) {
		// bulk delete permissions for the role
		$this->db->delete($this->_config->table['user_role'], array('user_id' => $user_id));
		
		// assume permissions all fail to set
		$rtn = TRUE;
		
		// add permissions provided in array
		foreach($role_array as $item => $role_id) {
			if(!$this->add_user_role($user_id, $role_id)) {
				$rtn = FALSE;
			}
		}
		
		// return TRUE if all permissions set
		return $rtn;
	}
	
	/*
	| -------------------------------------------------------------------
	|  role specific methods
	| -------------------------------------------------------------------
	*/
	
	/**
	 * get all roles details
	 *
	 * @return	array	an array of CodeIgniter row objects for role
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_all_roles() {
		$roles = $this->db->get($this->_config->table['role']);
		return ($roles->num_rows() > 0) ? $roles->result() : FALSE;
	}
	
	/**
	 * get roles by constraint
	 *
	 * @param	string	$field	the field to constrain
	 * @param	mixed	$value	the required value of field
	 * @return	object	a CodeIgniter row object for role
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_role_by($field, $value) {
		$this->db->where($field, $value);
		return $this->get_all_roles();
	}
	
	/**
	 * get details of a role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	object	a CodeIgniter row object for role
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 *
	 * @todo	return permissions associated w/ role as well
	 */
	public function get_role($role_id) {
		$role = $this->get_role_by('role_id', $role_id);
		return ($role !== FALSE) ? $role[0] : FALSE;
	}
	
	/**
	 * add new role to database
	 *
	 * @param	string	$data		the new roles data	
	 * @return	boolean	TRUE/FALSE - whether addition was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function add_role($data) {
		$this->db->insert($this->_config->table['role'], $data);
		return ($this->db->insert_id() );
	}
	
	/**
	 * remove role from database
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	boolean	TRUE/FALSE - whether addition was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function del_role($role_id) {
		$this->db->delete($this->_config->table['role'], array('role_id' => $role_id));
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * update a roles data
	 *
	 * @param	int			$role_id	the unique identifier for the role
	 * @param	assoc_array	$data		the new roles data	
	 * @return	boolean	TRUE/FALSE - whether update was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function edit_role($role_id, $data) {
		return $this->db->update($this->_config->table['role'], $data, array('role_id' => $role_id));
//		return ($this->db->affected_rows() == 1);
	}
	
	/*
	| -------------------------------------------------------------------
	|  role permission relations
	| -------------------------------------------------------------------
	*/
	
	/**
	 * get permission a role has
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	array	array of CodeIgniter row objects for role permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_role_perms($role_id) {
		$this->db->select($this->_config->table['perm'] . '.*')
		->from($this->_config->table['role_perm'])
		->where('role_id', $role_id)
		->join($this->_config->table['perm'], $this->_config->table['perm'] . '.perm_id = ' . $this->_config->table['role_perm'] . '.perm_id');
		
		$perms = $this->db->get();
		return ($perms->num_rows() > 0) ? $perms->result() : FALSE;
	}
	
	public function get_role_perms_list($role_id) {
		$this->db->select('GROUP_CONCAT(perm.perm_id) as perm')
		->from($this->_config->table['role_perm'])
		->where('role_id', $role_id)
		->join($this->_config->table['perm'], $this->_config->table['perm'] . '.perm_id = ' . $this->_config->table['role_perm'] . '.perm_id')
		->group_by('role_id');
		
		$perms = $this->db->get();
		$re = $perms->row();
		return ($perms->num_rows() > 0) ? $re->perm : FALSE;
	}
	/**
	 * add permission to role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @param	int		$perm_id	the unique identifier for the permission
	 * @return	boolean	TRUE/FALSE - whether or not addition was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function add_role_perm($role_id, $perm_id) {
		$this->db->insert($this->_config->table['role_perm'], array(
			'role_id' => $role_id,
			'perm_id' => $perm_id
		));
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * remove permission from role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @param	int		$perm_id	the unique identifier for the permission
	 * @return	boolean	TRUE/FALSE - whether or not removal was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function del_role_perm($role_id, $perm_id) {
		$this->db->delete($this->_config->table['role_perm'], array(
			'role_id' => $role_id,
			'perm_id' => $perm_id
		));
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * Edit role permissions
	 *
	 * Essensially this method assigns permissions to a role. This method will return FALSE 
	 * if **ANY** of the assignments fail.
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @param	array	$perm_array	an array of identifiers for the permissions to assign
	 * @return	boolean	TRUE/FALSE - whether or not **ALL** assignments were successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 *
	 * @todo	rework to check for changes rather than bulk remove then add permissions each time
	 * @todo	add in some better error reporting to detail which assignemnts fail and why
	 */
	public function edit_role_perms($role_id, $perm_array) {
		// bulk delete permissions for the role
		$this->db->delete($this->_config->table['role_perm'], array('role_id' => $role_id));
		
		// assume permissions all fail to set
		$rtn = TRUE;
		
		// add permissions provided in array
		foreach($perm_array as $item => $perm_id) {
			if(!$this->add_role_perm($role_id, $perm_id)) {
				$rtn = FALSE;
			}
		}
		
		// return TRUE if all permissions set
		return $rtn;
	}
	
	/*
	| -------------------------------------------------------------------
	|  permission specific methods
	| -------------------------------------------------------------------
	*/
	
	/**
	 * get all permissions
	 *
	 * @return	array	an array of CodeIgniter row objects for permission
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_all_perms() {
		$perms = $this->db->get($this->_config->table['perm']);
		return ($perms->num_rows() > 0) ? $perms->result() : FALSE;
	}
	
	/**
	 * get permission by constraint
	 *
	 * @param	string	$field	the field to constrain
	 * @param	mixed	$value	the value field should be
	 * @return	array	an array of CodeIgniter row objects for permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_perm_by($field, $value) {
		$this->db->where($field, $value);
		return $this->get_all_perms();
	}
	
	/**
	 * get a specific permissions
	 *
	 * @param	int	$perm_id	the unique identifier for the permission (id not value)
	 * @return	object	a CodeIgniter row object for permission
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function get_perm($perm_id) {
		$perm = $this->get_perm_by('perm_id', $perm_id);
		return ($perm !== FALSE) ? $perm[0] : FALSE;
	}
	
	/**
	 * add a permission
	 *
	 * @param	assoc_array	$data	the new permissions data
	 * @return	boolean		TRUE/FALSE - whether addition was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function add_perm($data) {
		$this->db->insert($this->_config->table['perm'], $data);
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * delete a permission
	 *
	 * @param	int		$perm_id	the unique identifier for the permission (id not value)
	 * @return	boolean	TRUE/FALSE - whether addition was successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function del_perm($perm_id) {
		$this->db->delete($this->_config->table['perm'], array('perm_id' => $perm_id));
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * update a permission
	 *
	 * @param	int			$perm_id	the unique identifier for the permission (id not value)
	 * @param	assoc_array	$data		the new data for permission
	 * @return	boolean		TRUE/FALSE - whether or not update successful
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 */
	public function edit_perm($perm_id, $data) {
		return $this->db->update($this->_config->table['perm'], $data, array('perm_id' => $perm_id));
//		return ($this->db->affected_rows() == 1);
	}
	
	/*
	| -------------------------------------------------------------------
	|  user permission relation
	| -------------------------------------------------------------------
	*/
	
	/**
	 * get a users permissions based off those in users roles
	 *
	 * @param	int		$user_id	the unique identifier for the user
	 * @return	array	an array of CodeIgniter row objects for permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 *
	 * @todo	refactor code to use complex sql **instead** of rest of model, and multiple sql calls.
	 */
	public function get_user_perms($user_id) {
		// hold on tight... this is a complicated one... and will be 
		// rolled into a single sql query if possible at a later date w/ diff logic. (might be possible)
		$rtn = array();
		
		// get users roles
		$role_list = $this->get_user_roles($user_id);
		
		// check role(s) set
		// for each role get its perms and add them to return array
		if(is_array($role_list)) foreach($role_list as $role) {
			// get role perms
			$perm_list = $this->get_role_perms($role->role_id);
			
			// check perms assigned to role
			if(is_array($perm_list)) foreach($perm_list as $perm) {
				$rtn[] = $perm;
			}
		}
		
		// return permission value total and return
		return $rtn;
	}
	
	/*
	| -------------------------------------------------------------------
	|  helper/utility methods for acl usage
	| -------------------------------------------------------------------
	*/
	
	/**
	 * user permission check
	 *
	 * Checks a user has the required permission.
	 *
	 * @param	string	$user_id	the user to check permission on
	 * @param	string	$slug		the permission required
	 * @return	boolean	TRUE/FALSE - whether or not user has permission
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 *
	 * @todo	add ability to accept arrays of permission slugs
	 */
	public function user_has_perm($user_id, $slug) {
		$user_perms = $this->get_user_perms($user_id);
		
		// chek the user has some permissions
		// loop through users permissions and check for the slug
		if(is_array($user_perms)) foreach($user_perms as $perm) {
			// if slug is found then return TRUE
			if($perm->slug == $slug) {
				return TRUE;
			}
		}
		
		// if we get here the user has no permissions
		return FALSE;
	}
	
	/**
	 * user role check
	 *
	 * @param	int		$user_id	the unique identifier for the uer
	 * @param	string	$slug		the role required
	 * @return	boolean	TRUE/FALSE - whether or not the user has role
	 * @author	William Duyck <fuzzyfox0@gmail.com>
	 * 
	 * @todo	add ability to accept arrays of role slugs
	 */
	public function user_has_role($user_id, $slug) {
		$user_roles = $this->get_user_roles($user_id);
		
		if(is_array($user_roles)) foreach($role_list as $role) {
			if($role->slug == $slug) {
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	///jinson
	
	public function get_number_check($phone){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('phone',$phone);
		return $this->db->get()->result();
	}
	
	public function get_perm_name(){
		$this->db->select('*');
		$this->db->from('perm_name');
		return $this->db->get()->result();
	}
	
	public function get_perm_group($par){
		$this->db->select('name,perm_id');
		$this->db->from('perm');
		$this->db->where('parent_id',$par);
		return $this->db->get()->result();
	}
	
	public function delete_perm_name($id){
		$this->db->delete('perm_name', array('id' => $id));
		$this->db->delete('perm', array('parent_id' => $id));
	}
	
	public function add_perm_name($data){
		$this->db->insert('perm_name', $data);
		return $this->db->insert_id() ;
	}

	public function get_role_id($role)
	{
		$this->db->select('role_id');
		$this->db->from('role');
		$this->db->where('name',$role);
		return $this->db->get()->row();
	}
	public function email_exits($email){
		$this->db->select('email');
		$this->db->from('user');
		$this->db->where('email',$email);
		return $this->db->get()->row();
	}
	
	public function total_count($table)
	{
		$this->db->select('count(id) as count');
		$this->db->from($table);
		return $this->db->get()->row();
	}
	public function feedback()
	{
		$this->db->select('feedback,users.name,users.image,feedback.time,users.id');
		$this->db->from('feedback');
		$this->db->join('users','users.id=feedback.user_id');
		$this->db->order_by('feedback.id','DESC');
		$this->db->limit(5,0);
		return $this->db->get()->result();
	}
	public function update_venue_users($data,$id)
	{
		$this->db->update('role', $data, array('role_id' => $id));
	}
	public function get_all_roles2()
	{
		$this->db->select('*');
		$this->db->from('role');
		$this->db->where('venue_users',1);
		$this->db->or_where('venue_users',2);
		$this->db->or_where('venue_users',3);
		return $this->db->get()->result();
	}
	public function get_all_users2()
	{
		$this->db->select('user.name as user,role.name as role,user.user_id');
		$this->db->from('user');
		$this->db->join('user_role','user_role.user_id=user.user_id','left');
		$this->db->join('role','user_role.role_id=role.role_id','left');
		$this->db->where('role.venue_users',3);
		$query1=$this->db->get()->result();

                $this->db->select('user.name as user,role.name as role,user.user_id');
		$this->db->from('user');
		$this->db->join('user_role','user_role.user_id=user.user_id','left');
		$this->db->join('role','user_role.role_id=role.role_id','left');
		$this->db->where('role.venue_users',1);
		$query2=$this->db->get()->result();
                
                return array_merge($query1,$query2);
	}
	public function add_venue_manager($data){
		$this->db->insert('venue_manager', $data);
		return $this->db->insert_id() ;
	}
	public function delete_venue_users($user_id,$venue)
	{
		$this->db->delete('venue_manager', array('user_id' => $user_id,'venue_id'=>$venue));
	}


	//Varun
	
	///////////////////update User Role/////////////////////////////////////////
	public function update_user_role($data,$user_id){
		return $this->db->update('user_role', $data, array('user_id' => $user_id));
	}
	///////////////////////////////////////Court list////////////////////////////////////
	public function get_all_court($venue)
	{
		$this->db->select('court.id as court_id,court.court');
		$this->db->from('venue_court');
		$this->db->join('court','court.id=venue_court.court_id','left');
		$this->db->where('venue_court.venue_id',$venue);
		return $this->db->get()->result_array();
	}
	///////////////////////////////////////////Role Data//////////////////////////////
	public function get_slug($role)
	{
		$this->db->select('slug');
		$this->db->from('role');
		$this->db->where('role_id',$role);
		return $this->db->get()->row();
	}
	/////////////////////////////////////////////////////////////////////////////////
	public function court_manager_court($data){
		$this->db->insert('court_manager_courts', $data);
		return $this->db->insert_id() ;
	}
	//////////////////Court Assigned/////////////////////////////////////////
	public function court_assigned($id)
	{
		$this->db->select('court_id');
		$this->db->from('court_manager_courts');
		$this->db->where('user_id',$id);
		$query 	= $this->db->get();

		$row1	= $query->result_array();
		if ($row1) {
			foreach($row1 as $row)
		    {
		        $array[] = $row['court_id']; 
		    }
		}else{
			$array[]="";
		}
		
		return $array;	
	}
	/////////////////////////////Delete Court Assigned////////////////////////////////
	public function court_manager_court_delete($user_id)
	{
		$this->db->delete('court_manager_courts', array('user_id' => $user_id));
	}
	
	public function get_role_by_user($id)
	{
		$this->db->select('*');
		$this->db->from('role');
		$this->db->join('user_role','user_role.role_id=role.role_id');
		$this->db->where('user_role.user_id',$id);
		return $this->db->get()->row();
	}
	public function get_venue_manager($venue,$user)
	{
		$this->db->select('*');
		$this->db->from('venue_manager');
		$this->db->where('venue_manager.user_id',$user);
		$this->db->where('venue_manager.venue_id',$venue);
		return $this->db->get()->result();
	}
        public function get_usercheck($roles){
		$this->db->select('venue_users');
		$this->db->from('role');
		$this->db->where('role_id',$roles);
		return $this->db->get()->result();
	}
	public function get_userrole($id)
	{
		$this->db->select('role.venue_users');
		$this->db->from('role');
		$this->db->join('user_role','user_role.role_id=role.role_id');
		$this->db->where('user_role.user_id',$id);
		return $this->db->get()->result();
	}
	public function get_userdatas($id)
	{
		$this->db->select('name,email,phone');
		$this->db->from('user');
		$this->db->where('user_id',$id);
		return $this->db->get()->result();
	}
	
	
	
}

/* End of file acl_model.php */
/* Location: ./application/models/acl_model.php */