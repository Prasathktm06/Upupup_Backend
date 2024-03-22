<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Matches_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}
	public function get_data($id){
		$this->db->select("matches.match_name,users.name,users.id as user_id,matches.no_players,matches.description,area.area ,area.id as area_id,matches.date,matches.id,sports.sports,sports.id as sports_id,matches.time");
		$this->db->from('matches');
		$this->db->join("sports","sports.id=matches.sports_id","left");
		$this->db->join("area","area.id=matches.area_id","left");
		$this->db->join('users','matches.user_id=users.id');

		$this->db->where('matches.id',$id);

		 return $this->db->get()->row();

	}

	public function get_matches_players($id){
		$this->db->select("users.id,users.name");
		$this->db->from('matches_players');
		$this->db->join("users","users.id=matches_players.user_id");
		return $this->db->get()->row();
	}
	public function get_details($id='',$table,$where=''){
		$this->db->select("*");
		$this->db->from($table);
		if($id!=='')
			$this->db->where($where,$id);
			return $this->db->get()->result();
	}
	public function get_speciality($id=''){
		$this->db->select('*');
		$this->db->from('speciality');
		if($id!=='')
			$this->db->where('id',$id);
		return $this->db->get()->result();
	}
	public function get_location($id=''){
		$this->db->select('*');
		$this->db->from('locations');
		if($id!=='')
			$this->db->where('id',$id);
			return $this->db->get()->result();
	}

	public function update_speciality($id,$data){
		return $this->db->update('speciality', $data, array('id' => $id));
	}

	public function add_speciality($data){
		if($this->db->insert('speciality', $data))
			return true;
			else
				return false;
	}

	public function add_matches($data){
		if($this->db->insert('matches', $data))
			return $this->db->insert_id();
			else
				return false;
	}

	public function update_matches($id,$data){
		return $this->db->update('matches', $data, array('id' => $id));
	}

	public function get_matches_details($id){
		$this->db->select('*');
		$this->db->from('matches');
		if($id!=='')
			$this->db->where('id',$id);
			return $this->db->get()->row();
	}
	public function get_matchesTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,matches.match_name,area.area,matches.date,matches.id,sports.sports,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete,users.name,matches.time',false);
		$this->db->join("sports","sports.id=matches.sports_id","left");
		$this->db->join("area","area.id=matches.area_id","left");
		$this->db->join("users","users.id=matches.user_id","left");
		$this->db->from('matches , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('matches',$dir);
		}
		
		$where = "(matches.match_name LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR area.area LIKE '%".$value."%' OR matches.date LIKE '%".$value."%' OR matches.time LIKE '%".$value."%' )";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('matches');
		$this->db->join("sports","sports.id=matches.sports_id","left");
		$this->db->join("area","area.id=matches.area_id","left");
		$this->db->join("users","users.id=matches.user_id","left");
		$where = "(matches.match_name LIKE '%".$value."%' OR users.name LIKE '%".$value."%' OR area.area LIKE '%".$value."%' OR matches.date LIKE '%".$value."%' OR matches.time LIKE '%".$value."%' )";

		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('matches');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function get_specialityTable($edit,$delete){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,speciality.*,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete',false);
		$this->db->from('speciality , (SELECT @s:='.$_GET['start'].') AS s');

		if($column==1)
		{
			$this->db->order_by('speciality',$dir);
		}

		$where = "(speciality.speciality LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('speciality');
		$where = "(speciality.speciality LIKE '%".$value."%')";

		$this->db->where($where);

		$query = $this->db->get();


		$this->db->from('speciality');
		//$this->db->where('parent_term_id',0);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}
	public function get_players($id){
		$this->db->select('GROUP_CONCAT(users.id) as players,GROUP_CONCAT(users.name) as name');
		$this->db->from('matches_players');
		$this->db->join('users','users.id=matches_players.user_id');

			$this->db->where('matches_players.match_id',$id);
			return $this->db->get()->row();
	}

	public function delete($id)
	{
		$this->db->delete('matches', array('id' => $id));
		return ($this->db->affected_rows() == 1);
	}

	public function add_matches_players($data)
	{
		if($this->db->insert('matches_players', $data))
			return $this->db->insert_id();
			else
				return false;
	}
public function get_players_byMatch($id){
		$this->db->select('users.id ,users.name  ');
		$this->db->from('matches_players');
		$this->db->join('users','users.id=matches_players.user_id');

			$this->db->where('matches_players.match_id',$id);
			return $this->db->get()->result();
	}
public function get_playersTable($match_id){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,users.name,status.status, matches_players.id',false);
		$this->db->from('matches_players , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->join('users','users.id=matches_players.user_id');
		$this->db->join('status','status.id=matches_players.status');
		//$this->db->where('matches_players.user_id',$id);
		$this->db->where('matches_players.match_id',$match_id);
		if($column==1)
		{
			$this->db->order_by('users.name',$dir);
		}

		$where = "(users.name LIKE '%".$value."%')";
		$this->db->where($where);
		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('matches_players');
		$this->db->join('users','users.id=matches_players.user_id');
		$this->db->join('status','status.id=matches_players.status');
		$where = "(users.name LIKE '%".$value."%')";

		$this->db->where($where);
		$this->db->where('matches_players.match_id',$match_id);
		$query = $this->db->get();


		$this->db->from('matches_players');
		$this->db->join('users','users.id=matches_players.user_id');
		$this->db->join('status','status.id=matches_players.status');
		$this->db->where('matches_players.match_id',$match_id);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function update_matches_players($data,$match_id){
		 $this->db->update('matches_players', $data, array('id' => $match_id));
	}

	public function matches_players_delete($id){
		 $this->db->delete('matches_players', array('id' => $id));
			return ($this->db->affected_rows() == 1);
	}

	public function get_sports($id)
	{
		$this->db->select('sports');
		$this->db->from('sports');
		$this->db->where('id',$id);
		return $this->db->get()->row();
	}
}
