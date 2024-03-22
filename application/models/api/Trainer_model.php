<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Trainer_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
////////////////////////// Trainer check phone ///////////////////////////////
 public function get_trainer_check($phone)
	{
		$this->db->select("*");
		$this->db->from("trainers");
		$this->db->where('phone',$phone);
		return $this->db->get()->result();
	}
//////////////////////////// add data to table /////////////////////////////
    public function add_data($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
//////////////////////////// user area details /////////////////////////////
	public function get_user_area($user_id){
		$this->db->select('area.area,area.id');
		$this->db->from('user_area');
		$this->db->join('area','area.id=user_area.area_id');
		$this->db->where("user_area.user_id",$user_id);
		$this->db->where('area.status',1);
		return $this->db->get()->result();
	}
/////////////////////// trainers list ///////////////////////////
	public function get_trainer_list($user_id,$location_ids){
            $this->db->distinct('trainers.id');
	       	$this->db->select('trainers.*');
			$this->db->from('trainers');
			$this->db->join('trainers_area','trainers_area.trainers_id=trainers.id');
			$this->db->join('user_area','trainers_area.area_id=user_area.area_id');
			$this->db->join('user_sports','user_sports.user_id=user_area.user_id');
			$this->db->where("user_area.user_id",$user_id);
			$this->db->where("trainers.location_id",$location_ids);
			$this->db->where("trainers.status",1);
			return $this->db->get()->result();

	       	/*$this->db->select('trainers.*');
			$this->db->from('trainers');
			$this->db->join('trainers_sports','trainers_sports.trainers_id=trainers.id');
			$this->db->join('user_sports','user_sports.sports_id=trainers_sports.sports_id');
			$this->db->where("user_sports.user_id",$user_id);
			$this->db->where("trainers.location_id",$location_ids);
			$this->db->where("trainers.status",1);
	         foreach($result1 as $value=>$val)
        	{
        	    $trainer_id=  $val->id;
        	    
        	   $this->db->where("trainers.id !=",$trainer_id);
        	}
			$result2= $this->db->get()->result();

			return array_merge_recursive($result1, $result2);*/	

	}
////////////////////////// location details ///////////////////////////////
 public function get_trainer_location($location_id)
	{
		$this->db->select("location");
		$this->db->from("locations");
		$this->db->where('id',$location_id);
		return $this->db->get()->result();
	}
////////////////////////// trainer sports details ///////////////////////////////
 public function get_trainer_sports($id)
	{
		$this->db->select("sports.id,sports.sports,sports.image");
		$this->db->from("trainers_sports");
		$this->db->join('sports','sports.id=trainers_sports.sports_id');
		$this->db->where('trainers_sports.trainers_id',$id);
		return $this->db->get()->result();
	}
////////////////////////// trainer area details ///////////////////////////////
 public function get_trainer_areas($id)
	{
		$this->db->select("area.id,area.area");
		$this->db->from("trainers_area");
		$this->db->join('area','area.id=trainers_area.area_id');
		$this->db->where('trainers_area.trainers_id',$id);
		return $this->db->get()->result();
	}
/////////////////////// trainers unfilter list ///////////////////////////
	public function get_trainer_unfilter($user_id,$location_ids){
            $this->db->distinct('trainers.id');
	       	$this->db->select('trainers.*');
			$this->db->from('trainers');
			$this->db->join('trainers_area','trainers_area.trainers_id=trainers.id');
			$this->db->join('user_area','trainers_area.area_id=user_area.area_id');
			$this->db->join('user_sports','user_sports.user_id=user_area.user_id');
			$this->db->where("user_area.user_id",$user_id);
			$this->db->where("trainers.location_id",$location_ids);
			$this->db->where("trainers.status",1);
			$result1= $this->db->get()->result();

	       	$this->db->select('*');
			$this->db->from('trainers');
			$this->db->where("status",1);
			$this->db->where("location_id",$location_ids);
	         foreach($result1 as $value=>$val)
        	{
        	    $trainer_id=  $val->id;
        	    
        	   $this->db->where("id !=",$trainer_id);
        	}	
            return $this->db->get()->result();
	}
////////////////////////// shop advertisement details ///////////////////////////////
 public function get_shop_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_shop");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// trainer advertisement details ///////////////////////////////
 public function get_trainer_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_trainers");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// venue advertisement details ///////////////////////////////
 public function get_venue_adv_lists($location_ids)
	{
		$this->db->select("id,image");
		$this->db->from("advertisement_venue");
		$this->db->where('status',1);
		$this->db->where('location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// program details ///////////////////////////////
 public function get_programs_lists($location_ids)
	{
		$this->db->select("trainers_program.*,trainers.name AS trainer_name");
		$this->db->from("trainers_program");
		$this->db->join('trainers','trainers.id=trainers_program.trainers_id');
		$this->db->where('trainers_program.status',1);
		$this->db->where("trainers_program.start_date >=",date('d-m-Y'));
		$this->db->where('trainers_program.location_id',$location_ids);
		return $this->db->get()->result();
	}
////////////////////////// trainer program details ///////////////////////////////
 public function get_trainer_programs($id)
	{
		$this->db->select("trainers_program.*,trainers.name AS trainer_name");
		$this->db->from("trainers_program");
		$this->db->join('trainers','trainers.id=trainers_program.trainers_id');
		$this->db->where('trainers_program.status',1);
		$this->db->where("trainers_program.start_date >=",date('d-m-Y'));
		$this->db->where('trainers_program.trainers_id',$id);
		return $this->db->get()->result();
	}
////////////////////////// trainer followers count ///////////////////////////////
 public function get_trainer_followers($id)
	{
		$this->db->select("count(id) as count");
		$this->db->from("trainers_followers");
		$this->db->where('trainers_id',$id);
		return $this->db->get()->result();
	}
////////////////////////// following status ///////////////////////////////
 public function get_following_status($id,$user_id)
	{
		$this->db->select("*");
		$this->db->from("trainers_followers");
		$this->db->where('trainers_id',$id);
		$this->db->where('followers_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////// trainer testimonials ///////////////////////////////
 public function get_testimonial_trainers($id)
	{
		$this->db->select("*");
		$this->db->from("trainers_testimonial");
		$this->db->where('trainers_id',$id);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
public function delete_following($follow_id)
	{
		$this->db->delete('trainers_followers',array('id'=>$follow_id));
	}
//////////////////////// user details ////////////////////////////////
	public function get_user_details($user_id){
		$this->db->select('name,phone_no');
		$this->db->from('users');
		$this->db->where('id',$user_id);
		return $this->db->get()->result();
	}
	
}
