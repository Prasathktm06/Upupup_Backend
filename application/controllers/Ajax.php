<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ajax extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		
	}
	public function area($id) {
		if($id!=''){
			$this->db->select("*");
			$this->db->from("area");
			$this->db->where("location_id",$id);
			echo json_encode($this->db->get()->result()); 
		}
	}
	
	public function co_players(){
		if($this->input->post()){
			$user=$this->input->post('user');
			
			$sports=$this->input->post('sports');
			$this->db->select("DISTINCT (users.id),users.name,users.rating");
			$this->db->from("co_player");
			$this->db->join("sports","sports.id=co_player.sports_id");
			$this->db->join("users","users.id=co_player.co_player");
			$this->db->where('co_player.user_id',$user);
			$this->db->where('co_player.sports_id',$sports);
			echo json_encode($this->db->get()->result());
		}
	}

	public function delete_venue_image($image)
	{
			$this->db->delete('venue_gallery',array('id'=>$image));
	}
	public function add_venue_image_desc($desc,$id)
	{
			return $this->db->update('venue_gallery', array('description' => $desc ), array('id' => $id));
	}
	public function add_temp_image()
	{
		$path="pics/temp/";
		$target_dir = FCPATH.$path;
		$target_file = $target_dir . basename($_FILES[0]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		  $check = getimagesize($_FILES[0]["tmp_name"]);
		    if($check !== false) {
		   
		        $uploadOk = 1;
		    } else {
		       
		        $uploadOk = 0;
		    }

		if ($_FILES[0]["size"] > 5000000) {
		    echo "Sorry, your file is too large.";
		    $uploadOk = 0;
		}

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" && $imageFileType != "csv" ) {

		    $uploadOk = 0;
		}

		if ($uploadOk == 0) {
		   return false;

		} else {
		    if (move_uploaded_file($_FILES[0]["tmp_name"], $target_file)) {
		     
		    } else {
		      
		    }
		}
		echo  json_encode(array('data' => base_url().$path.$_FILES[0]["name"]) );

	}
	public function delete_training_image($image)
	{
			return $this->db->update('trainers', array('training_image' => "" ), array('id' => $image));
	}
			
	
}