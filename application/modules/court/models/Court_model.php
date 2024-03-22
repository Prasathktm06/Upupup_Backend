<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Court_model extends CI_model {


	public function __construct() {
		parent::__construct();

		$this->_config = (object)$this->config->item('acl');
	}

	public function get_details($table){
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_courtTable_manager($delete,$edit,$user_id)
	{
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,court.id,court.court,sports.sports,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete,court.status,venue.id as venue_id,venue.venue',false);
		$this->db->from('court_manager_courts , (SELECT @s:='.$_GET['start'].') AS s');

		$this->db->join('court','court_manager_courts.court_id=court.id','left');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('venue_court','venue_court.court_id=court_manager_courts.court_id');
			$this->db->join('venue','venue.id=venue_court.venue_id');

		$this->db->where('court_manager_courts.user_id',$user_id);

		if($column==1)
		{
			$this->db->order_by('court',$dir);
		}
		if($column==2)
		{
			$this->db->order_by('sports',$dir);
		}


		$where = "(court.court LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' )";
		$this->db->where($where);

		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('court_manager_courts');
		$where = "(court.court LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' )";

		$this->db->where($where);
		$this->db->join('court','court_manager_courts.court_id=court.id','left');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('venue_court','venue_court.court_id=court_manager_courts.court_id');
			$this->db->join('venue','venue.id=venue_court.venue_id');

		$query = $this->db->get();


		$this->db->from('court_manager_courts');
		$this->db->join('court','court_manager_courts.court_id=court.id','left');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('venue_court','venue_court.court_id=court_manager_courts.court_id');
			$this->db->join('venue','venue.id=venue_court.venue_id');

		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}
	public function get_courtTable($edit,$delete,$venue_id){
		$column = $_GET['order'][0]['column'];
		$dir = $_GET['order'][0]['dir'];
		$value = $_GET['search']['value'];
		$this->db->select('@s:=@s+1 serial_number,court.id,court.court,sports.sports,"'.$edit.'" as perm_edit,"'.$delete.'" as perm_delete,court.status',false);
		$this->db->from('court , (SELECT @s:='.$_GET['start'].') AS s');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('venue_court.venue_id',$venue_id);

		if($column==1)
		{
			$this->db->order_by('court',$dir);
		}
		if($column==2)
		{
			$this->db->order_by('sports',$dir);
		}


		$where = "(court.court LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' )";
		$this->db->where($where);

		$this->db->limit($_GET['length'],$_GET['start']);
		$query = $this->db->get();
		$result['data'] = $query->result();


		$this->db->from('court');
		$where = "(court.court LIKE '%".$value."%' OR sports.sports LIKE '%".$value."%' )";

		$this->db->where($where);
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('venue_court.venue_id',$venue_id);
		$query = $this->db->get();


		$this->db->from('court');
		$this->db->join('sports','sports.id=court.sports_id');
		$this->db->join('venue_court','venue_court.court_id=court.id');
		$this->db->where('venue_court.venue_id',$venue_id);
		$query1 = $this->db->get();

		$result['recordsFiltered'] = $query->num_rows();
		$result['recordsTotal'] = $query1->num_rows();

		$result['start'] = $_GET['start'];
		$result['length'] = $_GET['length'];

		return $result;
	}

	public function get_sports($id)
	{
		$this->db->select('sports.id,sports.sports');
		$this->db->from('venue_sports');
		$this->db->join('sports','sports.id=venue_sports.sports_id');
		$this->db->where('venue_sports.venue_id',$id);
		return $this->db->get()->result();
	}

	public function add($data,$table)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}

	public function get_court($id)
	{
		$this->db->select('court.*,venue_court.venue_id');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id','left');
		$this->db->where('venue_court.court_id',$id);
		return $this->db->get()->row();
	}

	public function delete($id,$table)
	{
		$this->db->delete($table,array('id'=>$id));
	}
        public function delete_slot($slot_id)
	{
		$this->db->delete('court_time_intervel', array('id' => $slot_id));
		return ($this->db->affected_rows() == 1);
	}
	public function get_court_time($id)
	{
		$this->db->select('*');
		$this->db->from('court_time');
		$this->db->where('court_id',$id);
		$this->db->where('week!=',"Holiday");
		$this->db->order_by('id','asc');
		return $this->db->get()->result();
	}
	public function delete_court_time($id)
	{
		$this->db->delete('court_time',array('court_id'=>$id));
	}
        public function delete_court_times($id)
	{
		$this->db->delete('court_time_intervel',array('court_id'=>$id));
	}
	    public function delete_venue_court($id)
	{
		$this->db->delete('venue_court',array('court_id'=>$id));
	}
	public function get_all_sports()
	{
		$this->db->select('*');
		$this->db->from('sports');
		//$this->db->where('id',$id);
		//$this->db->group_by('week');
		return $this->db->get()->result();
	}
		public function court_unique($court,$id,$venue_id)
	{
		$this->db->select('court');
		$this->db->from('court');
		$this->db->join('venue_court','venue_court.court_id=court.id','left');

		$this->db->where('court.court',$court);

		if($venue_id!=''){
			$this->db->where('court.id!=',$id);
			$this->db->where('venue_court.venue_id',$venue_id);
		}else{
			$this->db->where('venue_court.venue_id',$id);
		}

		return $this->db->get()->result();
	}

	public function get_venue_details($venue)
	{
		$this->db->select('*');
		$this->db->from('venue');


		$this->db->where('id',$venue);
		return $this->db->get()->row();
	}
	public function get_venue()
	{
		$this->db->select('*');
		$this->db->from('venue');

		return $this->db->get()->result();
	}
	public function week_check($week,$id)
	{
		$this->db->select('id');
		$this->db->from('court_time');
		$this->db->where('week',$week);
		$this->db->where('court_id',$id);
		return $this->db->get()->result();
	}
	public function check_court_time($id,$week,$time)
	{
		$this->db->select('id');
		$this->db->from('court_time');
		$this->db->where('week',$week);
		$this->db->where('court_id',$id);
		$this->db->where('time',$time);

		return $this->db->get()->result();
	}

           public function get_courtmon($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Monday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Monday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);
	}
        
             public function get_courttue($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Tuesday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Tuesday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);
	}
	public function get_courtwed($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Wednesday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Wednesday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);


	}
	public function get_courtthu($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Thursday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Thursday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);
	}
	public function get_courtfri($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Friday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Friday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);
	}
	public function get_courtsat($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Saturday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Saturday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);
	}
	public function get_courtsun($court_id)
	{
		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Sunday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date",1);
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query1= $this->db->get()->result();

		$this->db->select('court_time_intervel.id AS slot_id,court_time.slotfor AS slotfor,court_time_intervel.time AS time,DATE_FORMAT(court_time_intervel.date, "%d-%m-%Y") as date');
		$this->db->from('court');
		$this->db->join('court_time','court_time.court_id=court.id');
		$this->db->join('court_time_intervel','court_time_intervel.court_time_id=court_time.id');
		$this->db->where("court.id",$court_id);
		$this->db->where("court_time.week",'Sunday');
		$this->db->where("court_time.court_id",$court_id);
		$this->db->where("court_time_intervel.court_id",$court_id);
		$this->db->where("court_time_intervel.date !=",1);
		$this->db->where("court_time_intervel.date >=",date('Y-m-d'));
		$this->db->where("court.status",1);
        $this->db->order_by('court_time_intervel.time','asc');
		$query2= $this->db->get()->result();

		return array_merge($query1, $query2);
	}
//insert slot time 
public function add_time($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}

}
