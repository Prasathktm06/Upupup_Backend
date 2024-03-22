<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Matches extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/matches_model");
		$this->load->library('notification');
		date_default_timezone_set('Asia/Kolkata');
	}


	public function index_get($user_id)
	{
		$data = $this->matches_model->get_hosted_matches($user_id);
		$user=$this->matches_model->get_user($user_id);
		//print_r($data);exit;
		if(!empty($data)){
		foreach ($data as $key => $value) {
			if(new DateTime($value['dateTime']) < new DateTime(date("Y-m-d H:i:s"))){
					unset($data[$key]);
			}else{

				$status=$this->matches_model->get_hosted_matches_user_status($value['id'],$user_id);
				if(empty($status)){
					$data[$key]['status']="Request";
				}else{
					$data[$key]['status']=$status[0]->status;
				}
				$data[$key]['co_player']=($this->matches_model->get_coplayer2($user_id,$value['hostedBy_id']) ? true : false);
				$data[$key]['count']=$this->matches_model->get_match_count($value['id'])->count;
				$data[$key]['phone_no']=$user->phone_no;
				$data[$key]['user_image']=$user->image;
			}
		}
		//print_r($data);exit;
		if ($data) {
			foreach ($data as $key9 => $part) {
				$sort[$key9] = strtotime($part['date']." ".$part['time2']);
		 	}
		 	array_multisort($sort, SORT_ASC, $data);
		 	$data1 = array_map("unserialize", array_unique(array_map("serialize", $data)));
		 	$data = array_values($data1);

	 		$result=array(
				'ErrorCode'=>0,
				'Data'=> $data,
				'Message'=>""
			);
		}else{
			$result=array(
					'ErrorCode'=>1,
					'Data'=> '',
					'Message'=>"No Matches"
			);
		}

	}else{
			$result=array(
					'ErrorCode'=>1,
					'Data'=> '',
					'Message'=>"No Matches"
			);
		}
		return $this->response($result,200);
	}
	///////////////////////////////////////////////////////////////////////////////////
	public function add_post(){


		 	$user_id=		$this->input->post('user_id');
		 	$sport_id=		$this->input->post('sports_id');
		 	$area_id=		$this->input->post('area_id');
		 	$date  =		$this->input->post('date');
		 	$no_players=	$this->input->post('no_players');
		 	$description=	$this->input->post('description');

		 if($this->input->post('time')=="Morning"){
		 	$time2="12:00:00";
		 }elseif ($this->input->post('time')=="Afternoon"){
		 	$time2="16:00:00";
		 }elseif($this->input->post('time')=="Evening"){
		 	$time2="19:00:00";
		 }elseif($this->input->post('time')=="Night"){
		 	$time2="23:59:00";
		 }

		 	$data =array(
		 			'match_name'=>$this->input->post('match_name'),
		 			'user_id' =>$this->input->post('user_id'),
		 			'sports_id'=>$this->input->post('sports_id'),
		 			'area_id' =>$this->input->post('area_id'),
		 			'date'	=> $this->input->post('date'),
		 			'time'	=> $this->input->post('time'),
		 			'time2'=>$time2,
		 			'no_players'=>$this->input->post('no_players'),
		 			'description' => $this->input->post('description'),

		 	);
		$res 			= $this->matches_model->insert($data,'matches');
		$match_details  = $this->matches_model->match_details($res);
		$match_details[0]->status = "Request";

		if ($res) {
			$users 		= $this->matches_model->users_list_area($area_id,$sport_id,$user_id);
			$area_name 	= $this->matches_model->area_name($area_id)->area;
			$message 	="New Match Hosted in ".$area_name;
			$title 	 	="Match Hosted";
				@define( 'API_ACCESS_KEY', 'AIzaSyCPa6mk0dqh9reJ5_SwIC0Zab5AJpl2MuY' );
  			foreach ($users as $key => $value) {

  			$co_player=$this->matches_model->get_coplayer2($value['id'],$user_id);
  			$match_details[0]->coplayer=$co_player;
			$data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'type'   => 1 ),
                                'status'=> $co_player,
                                'type'  => "GENERAL",
                                'match' =>$match_details,
                                            );
			//$notification= $this->notification->push_notification($users,$message,$title,$data);

            //$registrationIds = array( $_GET['id'] );
            // prep the bundle

            $registrationId = $value['device_id'];
            $msg = array
                (
                'alert'         => $title,
                "title"         => $title,
                //"subtitle"      => "App",
                "message"       => $message,
                "tickerText"    => "fdfd",
                "vibrate"       => 1,
                "sound"         => 1,
                "content-available"   => 1
            );

            $fields = array
                (
                "to"        => $registrationId,
                "priority"  => "high",
                //"notification" => $msg,
                "data"      => $data,
            );


                //echo '<pre>';print_r($fields);exit();
            $headers = array
                (
                "Authorization: key=" . API_ACCESS_KEY,
                "Content-Type: application/json"
            );

            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
            //echo $result;
		}
		$data=array(
			'match_id'=>$res,
			'user_id'=>$this->input->post('user_id'),
			'status'=>4
			);
		$this->matches_model->insert($data,'matches_players');

		 if($res){
		 	$result=array(
		 			'ErrorCode'=>0,
		 			'Data'=>'',
		 			'Message'=>"Match Hosted!"
		 	);

		 }else{
		 	$result=array(
		 			'ErrorCode'=>1,
		 			'Data'=>"",
		 			'Message'=>"Failed"
		 	);

		 }
		 	return $this->response($result,200);
		}

	}
	///////////////////////////////////////////////////////////////////////////////////
	public function upcoming_matches_get($user_id){
		date_default_timezone_set("Asia/Kolkata");
//print_r(date('H:i:s'));exit;
		$match=[];
		$res=[];
			$area=$this->matches_model->get_user_area($user_id);
		  foreach ($area as $key => $val){
            $match[$key]=$this->matches_model->get_upcoming_matches($val->id,date("Y-m-d"));
        }

        $new=[];
     if(!empty($match)){
        foreach ($match as $key => $value) {
        	foreach ($value as $key2 => $value2) {
        		if($value2->date==date('Y-m-d')){
        			if(date('H:i:s',strtotime($value2->time2))>=date('H:i:s')){
        					$new[]=array(
        			'id'=>$value2->id,
        			'match_name'=>$value2->match_name,
        			'hostedBy'=>$value2->hostedBy,
        			'area'=>$value2->area,
        			'date'=>$value2->date,
        			'time'=>$value2->time,
        			'user_phone'=>$value2->phone_no,
        			'sports'=>$value2->sports,
        			'sports_image'=>$value2->sports_image,
        			'users_image'=>$value2->user_image,
        			'hostedBy_id'=>$value2->hostedBy_id,
        			);
        		}
        		}else{
				$new[]=array(
        			'id'=>$value2->id,
        			'match_name'=>$value2->match_name,
        			'hostedBy'=>$value2->hostedBy,
        			'area'=>$value2->area,
        			'date'=>$value2->date,
        			'time'=>$value2->time,
        			'user_phone'=>$value2->phone_no,
        			'sports'=>$value2->sports,
        			'sports_image'=>$value2->sports_image,
        			'users_image'=>$value2->user_image,
        			'hostedBy_id'=>$value2->hostedBy_id,
        			);
        		}

        	}
        }

        foreach ($new as $key => $value) {
        	$result=$this->matches_model->get_matches_players($value['id'],$user_id);
        		if(!empty($result)){
        	$result->coplayer= ($this->matches_model->get_coplayer2($user_id,$value['hostedBy_id']) ? true : false);
        	$res[]=$result;

        	}

        }
        //print_r($res);exit();
if(!empty($res)){
    foreach ($res as $key => $part) {
		$sort[$key] = strtotime($part->date." ".$part->time2);
	 }
	 array_multisort($sort, SORT_ASC, $res);
	}


		if(!empty($res)){
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$res,
				'Message'=>""
		);

		}else {
			$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"No Data Found"
			);

		}
		return $this->response($result,200);
	}else{
		$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"No Data Found"
			);
		return $this->response($result,200);
	}
 }
 	//////////////////////////////////////////////////////////////////////////////////////
	public function past_matches_get($user_id){
				$area=$this->matches_model->get_user_area($user_id);
		  foreach ($area as $key => $val){
            $match[$key]=$this->matches_model->get_past_matches($val->id,date("Y-m-d"));
        }
        $new=[];
    if(!empty($match)){
        foreach ($match as $key => $value) {
        	foreach ($value as $key2 => $value2) {
        		if($value2->date==date('Y-m-d')){
        			if(date('H:i:s',strtotime($value2->time2))<date('H:i:s')){
        					$new[]=array(
        			'id'=>$value2->id,
        			'match_name'=>$value2->match_name,
        			'hostedBy'=>$value2->hostedBy,
        			'area'=>$value2->area,
        			'date'=>$value2->date,
        			'time'=>$value2->time,
        			'user_phone'=>$value2->phone_no,
        			'sports'=>$value2->sports,

        			'sports_image'=>$value2->sports_image,
        			'users_image'=>$value2->user_image,
        			'hostedBy_id'=>$value2->hostedBy_id
        			);
        		}
        		}else{
				$new[]=array(
        			'id'=>$value2->id,
        			'match_name'=>$value2->match_name,
        			'hostedBy'=>$value2->hostedBy,
        			'area'=>$value2->area,
        			'date'=>$value2->date,
        			'time'=>$value2->time,
        			'user_phone'=>$value2->phone_no,
        			'sports'=>$value2->sports,
        			'sports_image'=>$value2->sports_image,
        			'users_image'=>$value2->user_image,
        			'hostedBy_id'=>$value2->hostedBy_id
        			);
        		}

        	}
        }

        foreach ($new as $key => $value) {
        //	print_r($value);
        	$result=$this->matches_model->get_matches_players($value['id'],$user_id);
        		if(!empty($result)){
        	$result->coplayer= ($this->matches_model->get_coplayer2($user_id,$value['hostedBy_id']) ? true : false);
        	$res[]=$result;

        	}

        }
        foreach ($res as $key => $part) {
	$sort[$key] = strtotime($part->date." ".$part->time2);
	 }
	 array_multisort($sort, SORT_ASC, $res);
      //print_r($res);exit;
		if(!empty($res)){
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$res,
				'Message'=>""
		);

		}else {
			$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"No Data Found"
			);

		}
		return $this->response($result,200);
	}else{
		$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"No Data Found"
			);
		return $this->response($result,200);
	}
  }
	//////////////////////////////////////////////////////////////////////////
	public function request_match_post(){
		if($this->input->post('user_id')&&$this->input->post('match_id')){
		$user 		=$this->input->post('user_id');
		$match 		=$this->input->post('match_id');

		$match_data = $this->matches_model->match_details($match);
		if(empty($match_data)){
			$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"Sorry you can not request for this match"
			);
			return $this->response($result,200);
		}else{
			foreach ($match_data as $key => $value) {
				if(date('Y:m:d',strtotime($value->date))==date('Y:m:d')){
					if(strtotime($value->time2)<strtotime(date('H:i:s'))){

						$result=array(
								'ErrorCode'=>1,
								'Data'=>"",
								'Message'=>"Sorry you can not request for this match"
						);
						return $this->response($result,200);
					}
				}
			}
		}

		if($match)
		$hosted_id 	= $match_data[0]->user_id;
		$hosted_name= $match_data[0]->hosted_by;
		$match_name = $match_data[0]->match_name;
		$users[] 	= $this->matches_model->users_list($hosted_id);
		$request_user = $this->matches_model->user_name($user)->name;
		$match_data[0]->status = "Hosted";
		$status=$this->matches_model->get_coplayer2($user,$hosted_id);
		$match_data[0]->coplayer=$status;
		if ($users) {
			$title 	 	="New match request from ".$request_user;
			$message 	="New match request from ".$request_user." for ".$match_name;
			$data_push 	=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'type'   => 2 ),
                                'status'=> "true",
                                'type'  => "GENERAL",
                                'match_details' =>$match_data,
                                            );
			//echo "<pre>";print_r($data);exit();
			$notification= $this->notification->push_notification($users,$message,$title,$data_push);
		}
		//echo "<pre>";print_r($data);exit();
		$data =array(
				'match_id'=>$match,
				'user_id' =>$user,
				'status' =>1

		);
		$data = $this->matches_model->insert($data,'matches_players');
		$result=array(
				'ErrorCode'=>0,
				'Data'=>"",
				'Message'=>"Success"
		);
		return $this->response($result,200);
	}else{
		$result=array(
				'ErrorCode'=>1,
				'Data'=>"",
				'Message'=>"Fields Missing"
		);
		return $this->response($result,200);
	}
	}
	////////////////////////////////////////////////////////////////////////////
	public function pending_match_post(){
		if($this->input->post('user_id') &&$this->input->post('match_id')){
		$user=$this->input->post('user_id');
		$match=$this->input->post('match_id');

		$data = $this->matches_model->get_pending_request($user,$match);
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}else{
		$result=array(
				'ErrorCode'=>1,
				'Data'=>'',
				'Message'=>"Fields Missing"
		);
		return $this->response($result,200);
	}
  }
	///////////////////////////////////////////////////////////////////////////////
	public function update_match_status_post(){

		if($this->input->post('match_id')&&$this->input->post('co_player')&&$this->input->post('status')){
			$match=$this->input->post('match_id');
			$co_player=$this->input->post('co_player');
			$status1=$this->input->post('status');
			$users[] 	= $this->matches_model->users_list($co_player);

			$match_data = $this->matches_model->match_details($match);
			if(empty($match_data)){
				$result=array(
						'ErrorCode'=>1,
						'Data'=>"",
						'Message'=>"Sorry! you can't accept this player"
				);
				return $this->response($result,200);
			}
			if(date('Y:m:d',strtotime($match_data[0]->date))==date('Y:m:d')){

				if(strtotime($match_data[0]->time2)<strtotime(date('H:i:s'))){

					$result=array(
							'ErrorCode'=>1,
							'Data'=>"",
							'Message'=>"Hosted time is past, can't accept this player"
					);
					return $this->response($result,200);
				}
			}
			//exit;
			if(date('Y:m:d',strtotime($match_data[0]->date))<date('Y:m:d')){
				$result=array(
						'ErrorCode'=>1,
						'Data'=>"",
						'Message'=>"Hosted date is past, can't accept this player"
				);
				return $this->response($result,200);
			}
			$hosted_name= $match_data[0]->hosted_by;
			$status=$this->matches_model->get_coplayer2($match_data[0]->user_id,$co_player);
			$match_data[0]->coplayer=$status;
			//$match_name = $match_data[0]->match_name;
			if ($status==2) {
				if (!empty($users)) {
					$match_data[0]->status = "Accepted";
					$title 	 	="Match request accepted";
					$message 	="Your match request has been accepted by ".$hosted_name;
					$data_push 	=array('result' 		=> array('message'=> $message,
		                                                 		 'title'  => $title,
		                                                 		 'type'   => 2 ),
		                                'status'		=> "true",
		                                'type'  		=> "GENERAL",
		                                'match_details' =>$match_data,
		                                            );
					//echo "<pre>";print_r($data_push);exit();
					$notification= $this->notification->push_notification($users,$message,$title,$data_push);
				}
			}else if ($status==5) {
				if ($users) {
					$match_data[0]->status = "Rejected";
					$title 	 	="Match request rejected";
					$message 	="Your match request has been rejected by ".$hosted_name;
					$data_push 	=array('result' 		=> array('message'=> $message,
		                                                 		 'title'  => $title,
		                                                 		 'type'   => 2 ),
		                                'status'		=> "true",
		                                'type'  		=> "GENERAL",
		                                'match_details' =>$match_data,
		                                            );
					//echo "<pre>";print_r($data_push);exit();
					$notification= $this->notification->push_notification($users,$message,$title,$data_push);
				}
			}
			$this->matches_model->update_match_status($match,$co_player,$status1);
			$data=$this->matches_model->get_match($match);

			$coplayer_exits=$this->matches_model->get_coplayer($data->user_id,$co_player,$data->sports_id);
		//	print_r($coplayer_exits);
			if(empty($coplayer_exits)){
				$data3=array(
					'user_id'=>$data->user_id,
					'co_player'=>$co_player,
					'sports_id'=>$data->sports_id,
					'rating'=>0
					);
			$this->matches_model->insert_coplayer($data3);
			$data2=array(
					'user_id'=>$co_player,
					'co_player'=>$data->user_id,
					'sports_id'=>$data->sports_id,
					'rating'=>0
					);
			$this->matches_model->insert_coplayer($data2);
			}

			//
			//echo "<pre>";print_r($match_data);exit();
			$result=array(
					'ErrorCode'=>0,
					'Data'=>"",
					'Message'=>"Success"
			);


			return $this->response($result,200);
		}else{
			$result=array(
					'ErrorCode'=>1,
					'Data'=>"",
					'Message'=>"Fields Missing"
			);


			return $this->response($result,200);
		}
	}
	/////////////////////////////////////////////////////////////////////////////////

	public function get_match_details_post()
	{
		if($this->input->post('user_id')&&$this->input->post('match_id')){
			$user_id=$this->input->post('user_id');
			$match_id=$this->input->post('match_id');
			$status=array('pending_request'=>'1','acc_req'=>'2','rej_req'=>'5');

			foreach ($status as $key => $value) {

					$data[$key] = count($this->matches_model->get_match_details($user_id,$match_id,$value));

			}
			$data['details']=$this->matches_model->get_match_details($user_id,$match_id);
			//print_r($data['details']);exit;
			if(!empty($data['details'])){
			$data['info']=$data['details'][0]->description;
			foreach ($data['details'] as $key => $value) {
				if($value->status_id==2 ){
				$data['players'][]=array(
						'name'=>$value->name,
						'id'=>$value->user_id,
						'image'=>$value->image,
						'phone'=>$value->phone_no
					);
				//print_r($data['players']);exit;
			}/*else if($value->status_id!=2 ){
				$data['players']=array();
			}*/
		}

			//print_r($data);exit;
		}else{
			$data['info']="";
			$data['players']=array();

		}
		$data['match']=$this->matches_model->match_details2($match_id);
			$status=$this->matches_model->get_matches_players($match_id,$user_id);
	        	if(empty($status)){
	        	$data['status']="Request";
	        	}else{
	        		$data['status']=$status->status;
	        	}
	        	$data['co_player']= ($this->matches_model->get_coplayer2($user_id,$data['match'][0]->user_id) ? true : false);
			unset($data['details']);
		//	print_r($data);exit;
			$result=array(
					'ErrorCode'=>0,
					'Data'=>$data,
					'Message'=>"Success"
			);
			return $this->response($result,200);
		}else{
			$result=array(
					'ErrorCode'=>1,
					'Data'=>'',
					'Message'=>"Fields Missing"
			);
			return $this->response($result,200);
		}
	}

	public function pushNotify_matches($area_id)
	{

		//$users= $this->matches_model->get_area($area_id);
		//foreach ($users as $key => $value) {
			$msg='{
  "to": "$value->device_id",
  "data": {
    "result": {
      "message": "Flash sale on bigshopper. Grab offers uptto 50%",
      "title": "Ultimate Bigshopper - Offer",
      "image": "images\/ads\/ad_01.png"
    },
    "status": "true",
    "type": "GENERAL"
  },
  "notification": {
    "body": "Ultimate Bigshopper - Offer",
    "title": "Ultimate Bigshopper"
  }
}';
		$notification=$this->notification->push_notification();
		}
	//}



}
