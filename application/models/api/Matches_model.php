<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Matches_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
	}


	///////////////////////////////////////////////////////////////////////////////////
	public function get_hosted_matches_sports($sports_id,$area_id){
		date_default_timezone_set("Asia/Kolkata");
		//$time=time("h:i:s");print_r($time);exit();
		$this->db->distinct();
		$this->db->select('matches.id,matches.match_name,area.area,users.name as hostedBy,matches.date,matches.time,sports.image as sports_image,users.image as user_image,users.phone_no,users.id as hostedBy_id,matches.time2');
		$this->db->from('matches');
		$this->db->join('area','area.id=matches.area_id','left');
		$this->db->join('users','users.id=matches.user_id','left');
		$this->db->join('sports','sports.id=matches.sports_id');
		//$this->db->order_by('matches.date','DESC');
		//$this->db->order_by('matches.time2','ASC');
		$this->db->where('matches.date >=',date("Y-m-d"));
		//$this->db->where('matches.time2 >=',date("h:i:s"));
		$this->db->where('matches.area_id',$area_id);
		$this->db->where('matches.sports_id',$sports_id);
		return $this->db->get()->result();
	}
	//////////////////////////////////////////////////////////////////////////
	public function get_user_sports($user_id)
	{
		$this->db->select('sports_id');
		$this->db->from('user_sports');
		$this->db->where('user_id',$user_id);

		return $this->db->get()->result();
	}
	public function get_request_matches($user_id)
	{
		$this->db->select('id,match_id');
		$this->db->from('matches_players');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
	public function insert($data,$table){
		if($this->db->insert($table, $data))
			return  $this->db->insert_id();
			else
				return false;
	}


	public function get_past_matches($area_id,$date){
		 $this->db->select('matches.id,match_name,users.name as hostedBy,sports.sports,area.area,matches.date,matches.time,sports.image as sports_image,users.image as user_image,users.phone_no,users.id as hostedBy_id,matches.time2');
        $this->db->from('matches');

        $this->db->join('users','users.id = matches.user_id','left');
        $this->db->join('area','area.id   = matches.area_id','left');
       // $this->db->join('status','status.id   = matches_players.status','left');
        $this->db->join('sports','sports.id   = matches.sports_id','left');
        $this->db->order_by('matches.date','DESC');
         //$this->db->where('date(matches.time2) <',date('H:m:i'));
        $this->db->where('matches.date <=',$date);
        $this->db->where('matches.area_id',$area_id);

		return $this->db->get()->result();
	}
	public function get_upcoming_matches($area_id,$date){
		$this->db->distinct();
		 $this->db->select('matches.id,match_name,users.name as hostedBy,sports.sports,area.area,matches.date,matches.time,sports.image as sports_image,users.image as user_image,users.phone_no,users.id as hostedBy_id,matches.time2');
        $this->db->from('matches');

        $this->db->join('users','users.id = matches.user_id','left');
        $this->db->join('area','area.id   = matches.area_id','left');
       // $this->db->join('status','status.id   = matches_players.status','left');
        $this->db->join('sports','sports.id   = matches.sports_id','left');
        $this->db->order_by('matches.date','DESC');

        $this->db->where('date(matches.date) >=',$date);
       /* if($date)
         $this->db->where('date(matches.time2) >=',date('H:m:i'));*/
        $this->db->where('matches.area_id',$area_id);

		return $this->db->get()->result();
	}

	public function request_match($user,$match){
		if($this->db->insert('matches_players', $data))
			return true;
			else
				return false;
	}

	public function get_pending_request($user_id,$match){
		$this->db->select('users.name,users.id,users.image,status.status,users.phone_no');
		$this->db->from('matches_players');
		//$this->db->join('sports','sports.id = matches.sports_id');
		$this->db->join('status','status.id = matches_players.status');
		$this->db->join('users','users.id = matches_players.user_id');
			//$this->db->where('matches.user_id',$user_id);
			$this->db->where('matches_players.match_id',$match);
			//$this->db->where('matches_players.user_id',$user_id);
			$this->db->where('status.id',1);
			return $this->db->get()->result();
	}

	public function update_match_status($match,$coplayer,$status){
		return $this->db->update('matches_players', array('status'=>$status), array('user_id' => $coplayer,'match_id'=>$match));
	}

	public function get_user_area($user){
		$this->db->select("area.id");
		$this->db->from("area");
		$this->db->join('user_area','area.id=user_area.area_id');
		$this->db->where('user_area.user_id',$user);
		return $this->db->get()->result();
	}

	public function get_match_details($user='',$match_id,$status=''){
		$this->db->select("matches.match_name,matches.id,status.status,area,area,users.name,users.id as user_id,status.id as status_id,users.image,matches.description,users.phone_no");
		$this->db->from("matches_players");
		$this->db->join('matches','matches.id=matches_players.match_id');
		$this->db->join('status','status.id=matches_players.status');
		$this->db->join('area','area.id=matches.area_id');
		$this->db->join('users','users.id=matches_players.user_id');

		$this->db->where('matches_players.match_id',$match_id);
		if ($status) {
			$this->db->where('matches_players.status',$status);
		}
		//$this->db->where('matches_players.status !=',4);

		return $this->db->get()->result();
	}


	public function get_matches($value='')
	{
		$this->db->select('id,match_id');
		$this->db->from('matches_players');
		$this->db->where('user_id',$user_id);
		return $this->db->get()->result();
	}
	public function get_matches_players($match,$user_id)
	{
		$this->db->distinct();
		$this->db->select('matches.id,match_name,users.name as hostedBy,sports.sports,area.area,matches.date,matches.time,sports.image sports_image,status.status,users.image as user_image,users.phone_no,users.id as hostedBy_id,matches.time2');
		$this->db->from('matches_players');
		$this->db->join('status','status.id=matches_players.status');
		$this->db->join('matches','matches.id=matches_players.match_id');
		$this->db->join('area','area.id=matches.area_id');
		$this->db->join('users','users.id=matches.user_id');
		$this->db->join('sports','sports.id=matches.sports_id');
		$this->db->where('matches_players.user_id',$user_id);
		$this->db->where('matches_players.match_id',$match);
		$this->db->where('status.id !=',3);
		//$this->db->where('status.id !=',1);
		return $this->db->get()->row();
	}
	public function get_matches_players2($match,$user_id)
	{
		$this->db->distinct();
		$this->db->select('matches.id,match_name,users.name as hostedBy,sports.sports,area.area,matches.date,matches.time,sports.image as sports_image,status.status,users.image as user_image,users.phone_no');
		$this->db->from('matches_players');
		$this->db->join('status','status.id=matches_players.status');
		$this->db->join('matches','matches.id=matches_players.match_id');
		$this->db->join('area','area.id=matches.area_id');
		$this->db->join('users','users.id=matches.user_id');
		$this->db->join('sports','sports.id=matches.sports_id');
		$this->db->where('matches_players.user_id',$user_id);
		$this->db->where('matches_players.match_id',$match);
			$this->db->where('matches_players.status !=',3);
		$this->db->where('matches_players.status !=',1);
		return $this->db->get()->row();
	}

	public function insert_coplayer($data)
	{
		if($this->db->insert('co_player', $data))
			return true;
			else
				return false;
	}

	public function get_match($id)
	{
		$this->db->select('sports_id,user_id');
		$this->db->from('matches');
		$this->db->where('id',$id);
		return $this->db->get()->row();
	}
	public function get_coplayer($user,$coplayer,$sport)
	{
		$this->db->select('id');
		$this->db->from('co_player');
		$this->db->where('user_id',$user);
		$this->db->where('co_player',$coplayer);
		$this->db->where('sports_id',$sport);
		return $this->db->get()->row();
	}

	public function get_area($area_id)
	{
		$this->db->select('user_area.user_id,users.device_id');
		$this->db->from('user_area');
		$this->db->where('area_id',$area_id);
		$this->db->join('users','users.id=user_area.user_id');
		return $this->db->get()->result();
	}

	/////////////////////////////////////Users Under Same Area/////////////////////////////////////
	public function users_list_area($area_id,$sports_id,$user_id)
	{
		$this->db->select('users.device_id,users.id');
		$this->db->from('user_sports');
		$this->db->join('user_area','user_area.user_id=user_sports.user_id');
		$this->db->join('users','users.id=user_sports.user_id');
		$this->db->where('user_area.area_id',$area_id);
		$this->db->where('user_sports.sports_id',$sports_id);
		$this->db->where('users.id!=',$user_id);
		$this->db->group_by('user_sports.user_id');
		//$this->db->group_by('users.id');
		return $this->db->get()->result_array();
	}
	////////////////////////////////////Area Name//////////////////////////////////////////
	public function area_name($area_id)
	{
		$this->db->select('area');
		$this->db->from('area');
		$this->db->where('id',$area_id);
		return $this->db->get()->row();
	}
	/////////////////////////////////Match Details///////////////////////////////////////
	public function match_details($match_id){
		$this->db->select("matches.match_name,matches.id as match_id,matches.date,matches.time,users.name as hosted_by,users.image as user_image,users.phone_no,area.area,sports.image sports_image,matches.user_id as user_id,matches.time2");
		$this->db->from("matches");
		$this->db->join('users','users.id=matches.user_id','left');
		$this->db->join('area','area.id=matches.area_id','left');
		$this->db->join('sports','sports.id=matches.sports_id','left');
		/*$this->db->join('status','status.id=matches_players.status');
		$this->db->join('area','area.id=matches.area_id');
		$this->db->join('users','users.id=matches_players.user_id');*/
		$this->db->where('date(matches.date)>=',date('Y:m:d'));
		$this->db->where('matches.id',$match_id);


		return $this->db->get()->result();
	}
	////////////////////////////////////Users List////////////////////////////////////
	public function users_list($user_id)
	{
		$this->db->select('users.device_id,users.id');
		$this->db->from('users');
		$this->db->where('users.id',$user_id);
		return $this->db->get()->row_array();
	}
	//////////////////////////////////User Name/////////////////////////////////////////////
	public function user_name($user_id)
	{
		$this->db->select('users.name');
		$this->db->from('users');
		$this->db->where('id',$user_id);
		return $this->db->get()->row();
	}
	/////////////////////////////////////////////////////////////////////////////////////////
	public function get_coplayer2($user,$coplayer)
	{
		$this->db->select('id,user_id');
		$this->db->from('co_player');
		$this->db->where('user_id',$user);
		$this->db->where('co_player',$coplayer);

		$res= $this->db->get()->row();


		if($res)
			return true;
		else{
			if($user==$coplayer){
				return true;
			}else{
				return false;
			}
		}

		}

		public function get_hosted_matches_user_status($match,$user)
		{
			$this->db->select('status.status');
			$this->db->from('matches_players');
			$this->db->join('status','status.id=matches_players.status','left');
			$this->db->where('match_id',$match);
			$this->db->where('user_id',$user);
			return $this->db->get()->result();
		}
		public function get_user($user_id)
		{
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('id',$user_id);
			return $this->db->get()->row();
		}

		////////////////////////////////////////////////////////////////////////////
		public function match_details2($match_id){
			$this->db->select("matches.match_name,matches.id as match_id,matches.date,matches.time,users.name as hosted_by,users.image as user_image,users.phone_no,area.area,sports.image sports_image,matches.user_id as user_id,matches.time2");
			$this->db->from("matches");
			$this->db->join('users','users.id=matches.user_id');
			$this->db->join('area','area.id=matches.area_id');
			$this->db->join('sports','sports.id=matches.sports_id');
			/*$this->db->join('status','status.id=matches_players.status');
			$this->db->join('area','area.id=matches.area_id');
			$this->db->join('users','users.id=matches_players.user_id');*/
			//$this->db->where('date(matches.date)>=',date('Y:m:d'));
			$this->db->where('matches.id',$match_id);


			return $this->db->get()->result();
		}
	///////////////////////////////////////////////////////////////////////////////////
	public function get_hosted_matches($user_id){

		$query = $this->db->query("SELECT matches.id,matches.match_name,matches.time,matches.time2,matches.date,matches.area_id,sports.sports,sports.image as sports_image,users.name as hostedBy,area.area ,CONCAT(matches.date,' ',matches.time2) as dateTime , matches.user_id as hostedBy_id FROM matches LEFT JOIN sports ON sports.id=matches.sports_id LEFT JOIN users ON users.id=matches.user_id LEFT JOIN area ON area.id=matches.area_id
			WHERE area_id IN (SELECT area_id FROM user_area WHERE user_id=$user_id) AND sports_id IN (SELECT sports_id FROM user_sports WHERE user_id=$user_id) ");
		return $row = $query->result_array();


	}
	public function get_match_count($match)
	{
		$this->db->select('count(id) as count ');
		$this->db->from('matches_players');
		$this->db->where('match_id',$match);
		$this->db->where('status',1);
		$res= $this->db->get()->row();
		if(empty($res))
			return  (object) array('count'=>0);
		else
			return $res;
	}

}/**/
