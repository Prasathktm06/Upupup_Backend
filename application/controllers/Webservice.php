<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
	// Allow from any origin

    if (isset($_SERVER['HTTP_ORIGIN'])) {

       header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

       header('Access-Control-Allow-Credentials: true');

       header('Access-Control-Max-Age: 86400');    // cache for 1 day

   }


    // Access-Control headers are received during OPTIONS requests

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {


        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))

            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");        


        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))

            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");


        exit(0);

    }

    class Webservice extends CI_Controller {

        public function __construct()
        {
            parent::__construct();
            date_default_timezone_set('Asia/Kolkata');
            $this->load->database();
            $this->load->model('webservice_model');
        }

        public function getAllUser(){

            echo 'webservice';

            //$data=$this->webservice_model->get_users($this->input->post('phone_no'));
            $data= $this->webservice_model->get_users($this->input->post('phone_no'));

            $result=array(
                'ErrorCode'=>0,
                'Data'=>$data,
                'Message'=>"");

            return $this->response($result,200);   
        }

    }

?>