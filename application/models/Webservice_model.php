<?php 
    defined('BASEPATH') || exit('No direct script access allowed');

    class Webservice_model extends CI_Model {

        public function __construct()
	    {
		    parent::__construct();
	    }
	
	    public function get_users($phone){
            echo 'webservice1';
		    $this->db->select('users.*,otp.otp');
		    $this->db->from('users');
		    $this->db->join('otp','otp.user_id=users.id','left');
		    $this->db->where('users.phone_no',$phone);
            echo $this->db->get()->row();
		    return $this->db->get()->row();
	    }
    }

?>