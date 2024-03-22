<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Upupup extends CI_controller {

	public function __construct() {
        parent::__construct();		
      
    }
    public function venues($id=''){
      
        $this->load->view('deeplink');
    }
    public function matches($id=''){
     
        $this->load->view('deeplink');
    }
    public function notification($id=''){
        
        $this->load->view('deeplink');
    }
    public function home($id=''){
        
        $this->load->view('deeplink');
    }
}