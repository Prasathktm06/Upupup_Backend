<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Users extends REST_Controller {

	function __construct()
	{

		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/users_model");
		date_default_timezone_set("Asia/Kolkata");
	}

	public function skill_get($user_id){
		$cur_date=date("Y-m-d");
		$cur_time=date("H:i:s");
		$data=$this->users_model->get_skills($user_id);
		foreach ($data as $key => $value) {
			@$value->rating=round($this->users_model->get_status($user_id,$value->id)->rating);
			@$value->user_rate=$this->users_model->user_rate($user_id,$value->id);
		//	print_r(@$value->user_rate);
			if (@empty($value->rating)) {
				$value->rating_status='true';
			}else{
				$value->rating_status='false';
			}
			$value->count=($this->users_model->get_match_played_user($user_id,$value->id));
			foreach ($value->count as $key2 => $value2) {
			     if(date('Y:m:d',strtotime($value2->date))==date('Y:m:d')){

             if(date('H:i:s',strtotime($value2->court_time))>date('H:i:s')){
                unset($value->count[$key2]);
              }
           }
			}

      $value->count=array_values($value->count);
      $value->count=count($value->count);

		}


		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}
	///////////////////////////////////////////////////////////////////
	/*public function skill_get($user_id){
		$data=$this->users_model->get_skills($user_id);

		foreach ($data as $key => $value) {
			$status=$this->users_model->get_status($user_id,$value->id);
			@$value->rating=(int)$value->rating;
			if(!empty($status))
				$value->rating_status='false';
			else
				$value->rating_status='true';

				$value->count=$this->users_model->get_match_played_user($user_id,$value->id)->count;
		}



		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}*/
	///////////////////////////////////////////////////////////////////
	public function update_post(){

		if($this->input->post('area_id')&& $this->input->post('sport_id')&&  $this->input->post('user_id')){
			$area_id=json_decode($this->input->post('area_id'));
			$region_id=$this->input->post('region_id');
			$sport_id=json_decode($this->input->post('sport_id'));
			$user_id=$this->input->post('user_id');
			//print_r($area_id);exit;
			$this->users_model->delete_user_preference($user_id,'user_area');
			foreach ($area_id as $val){
				$data= array(
						'user_id'=>$user_id,
						'area_id'=>$val
				);
				$this->users_model->insert_user_preference($data,'user_area');
			}

			$this->users_model->delete_user_preference($user_id,'user_sports');
			foreach ($sport_id as $val){
				$data= array(
						'user_id'=>$user_id,
						'sports_id'=>$val
				);
				$res=$this->users_model->insert_user_preference($data,'user_sports');
			}

			if($res){
				$result=array(
						'ErrorCode'=>0,
						'Data'=>$data,
						'Message'=>"Inserted"
				);
				return $this->response($result,200);
			}else{
				$result=array(
						'ErrorCode'=>0,
						'Data'=>$data,
						'Message'=>"Failed"
				);
				return $this->response($result,200);
			}
		}else{
			$result=array(
						'ErrorCode'=>1,
						'Data'=>'',
						'Message'=>"Fields Missing"
				);
				return $this->response($result,200);
		}

	}

	public function co_players_get($user_id){
		$co_player=$this->users_model->get_co_players2($user_id);

		foreach ($co_player as $key => $value) {
		$co_player_match=	$this->users_model->get_match_count_coplayer($value->co_player_id);
		$user_match=$this->users_model->get_match_count_coplayer($user_id);

		$sports=explode(',',$value->sport);
		$sport_id=explode(',',$value->sports_id);
		$sport_image=explode(',',$value->sports_image);
		foreach ($sports as $key2 => $value2) {
			$value->co_player_sports[]= array('sports_name' => $value2,
			'sports'=>$sport_id[$key2],
			'sports_image'=>$sport_image[$key2]
		 	);
		}
		$final_array = array();
		$first_count=count($user_match);
		$second_count=count($co_player_match);
		if($first_count>=$second_count){
			$start=$user_match;
			$end=$co_player_match;
		}else {
			$start=$co_player_match;
			$end=$user_match;
		}

		foreach($start as $key3=>$val){
				if(in_array($val,$end)){
						$final_array[] = $val;
				}
		}
			$new_array=[];

			foreach ($final_array as $key3 => $value3) {

				if(date('Y:m:d',strtotime($value3->date))==date('Y:m:d')){
					if(date('H:i:s',strtotime($value3->court_time))<=date('H:i:s')){
						$new_array[]=$value3;
					}
				}else{
					$new_array[]=$value3;
				}

			}

			$match_count=count($new_array);
			$value->matches_played=$match_count;
			unset($value->sport,$value->sports_id,$value->sports_image);

}

		$result=array(
				'ErrorCode'=>0,
				'Data'=>$co_player,
				'Message'=>""
		);
		return $this->response($result,200);
	}
	////////////////////////////////////////////////////////////
	public function co_players_details_post(){
		date_default_timezone_set("Asia/Kolkata");
		if($this->input->post('user_id')&&$this->input->post('co_player')){
			$user=$this->input->post('user_id');
			$co_player=$this->input->post('co_player');
			//$sports_count=$this->users_model->get_sports_count($user,$co_player);
			$booking=$this->users_model->get_booking($user);
			//print_r( $booking);
			foreach ($booking as $key => $value) {
				$venue_player=$this->users_model->get_venue_player($value->id,$co_player);
			}

			$cur_date=date("Y-m-d");
			$cur_time=date("H:i:s");
			$data=$this->users_model->get_co_players_details($co_player);

			foreach ($data as $key => $value) {
				$value->rating=round($this->users_model->get_co_players_rating($value->sports_id,$value->user_id)->rating);


				//$match_count=count($this->users_model->get_match_played_rate($user,$value->sports_id,$co_player,$cur_date,$cur_time));
				$user_matches_count=$this->users_model->get_match_played_user_count($user,$value->sports_id);
				$coplayer_matches_count=$this->users_model->get_match_played_user_count($co_player,$value->sports_id);
// 				print_r($user_matches_count);
// 			print_r($coplayer_matches_count);
// exit;

				$final_array = array();
			 	$first_count=count($user_matches_count);
				$second_count=count($coplayer_matches_count);
				if($first_count>=$second_count){
					$start=$user_matches_count;
					$end=$coplayer_matches_count;
				}else {
					$start=$coplayer_matches_count;
					$end=$user_matches_count;
				}
				// print_r($start);
				// print_r($end);
				// exit;
				foreach($start as $key=>$val){
				    if(in_array($val,$end)){
				        $final_array[] = $val;
				    }
				}
				$new_array=[];
				//print_r($final_array);
				foreach ($final_array as $key3 => $value3) {

					if(date('Y:m:d',strtotime($value3->date))==date('Y:m:d')){
						if(date('H:i:s',strtotime($value3->court_time))<=date('H:i:s')){
							$new_array[]=$value3;
						}
					}else{
						$new_array[]=$value3;
					}

				}
 $match_count=count($new_array);
 //print_r($final_array);
 //echo $match_count;echo "<br>";
// echo $value->sports_id;
// echo "<br>";
				if (isset($match_count)) {
					$value->match_played=$match_count;
					$value->rating_status_data=($this->users_model->get_co_players_rating_status($value->sports_id,$user,$co_player));
					//print_r($value->rating_status_data);
					foreach ($value->rating_status_data as $rkey => $rate) {
						if(date('Y:m:d',strtotime($rate->date))==date('Y:m:d')){

					if(date('H:i:s',strtotime($rate->court_time))>date('H:i:s')){
									unset($value->rating_status_data[$rkey]);
						}
					}
					}

					$value->rating_status_data=count($value->rating_status_data);

					if ($value->match_played>0) {
	//print_r($value->rating_status_data);
						if ($value->rating_status_data==0) {
							$value->rating_status="False";
						}else{
							$value->rating_status="True";
						}
					}else{
						$value->rating_status="False";
					}
				}else{
					$value->match_played=0;
					$value->rating_status="False";
				}
			}
//exit;
			//print_r($data);exit();
			$count=count($data);

			$result=array( 'ErrorCode'=>0, 'Data'=>$data, 'Message'=>"" );

			return $this->response($result,200);
		}else{
			$result=array( 'ErrorCode'=>1,
							'Data'=>'',
			 				'Message'=>"Fields Missing" );

			return $this->response($result,200);
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////
	public function rate_co_players_post(){
		if($this->input->post('user_id') && $this->input->post('co_player_id') && $this->input->post('sports_id')){
			$user_id 	=$this->input->post('user_id');
			$co_player 	=$this->input->post('co_player_id');
			$rate 		=$this->input->post('rate');
			$sports 	=$this->input->post('sports_id');


			$result 	=$this->users_model->get_co_player_rating($user_id,$co_player,$sports);
			//print_r($result);exit;
			if ($result) {
				$booking=$this->users_model->get_booking_coplayer($sports,$user_id,$co_player);
				//print_r($booking);exit;
				foreach ($booking as $key => $value) {
					$data= array('rating' =>$rate );
					$cndtn= array('booking_id' => $value->booking_id ,
								  'user_id' => $co_player);
					$this->users_model->update_venue_player_rating($data,$cndtn);
				}

				$data=array(
					'rating'=>$rate
				);
				/*$condition1= array('user_id' => $user_id,
						  		'co_player' => $co_player,
						  		'sports_id' => $sports,
						  		'rating'=>$rate);
				$insert_rate_coplayer=$this->users_model->insert_rate_coplayer($condition1);*/
				$condition= array('user_id' => $user_id,
						  		'co_player' => $co_player,
						  		'sports_id' => $sports);

				$update_rate=$this->users_model->coplayer_rating_update($data,$condition);
				$data 	=$this->users_model->rate_co_players_details($user_id,$co_player,$sports,$data);
				$coplayer_rating =$this->users_model->get_coplayer_rating($co_player,$sports);
			}else{
				$booking=$this->users_model->get_booking_coplayer($sports,$user_id,$co_player);
				//print_r($booking);exit;
				foreach ($booking as $key => $value) {
					$data= array('rating' =>$rate );
					$cndtn= array('booking_id' => $value->booking_id ,
								  'user_id' => $co_player);
					$this->users_model->update_venue_player_rating($data,$cndtn);
				}
				$data=array(
						'rating'=>$rate
				);
				/*$condition= array('user_id' => $user_id,
						  		'co_player' => $co_player,
						  		'sports_id' => $sports,);
				$update_rate=$this->users_model->coplayer_rating_update($data,$condition);*/
				$condition1= array('user_id' => $user_id,
						  		'co_player' => $co_player,
						  		'sports_id' => $sports,
						  		'rating'=>$rate);
				$insert_rate_coplayer=$this->users_model->insert_rate_coplayer($condition1);
				$coplayer_rating=$this->users_model->get_coplayer_rating($co_player,$sports);
			}
			//print_r($coplayer_rating);exit();
			if ($coplayer_rating) {
				$id=$this->users_model->get_co_players_rating_status($sports,$user_id,$co_player);
				foreach ($id as $rkey => $rate) {
						if(date('Y:m:d',strtotime($rate->date))==date('Y:m:d')){

					if(date('H:i:s',strtotime($rate->court_time))>date('H:i:s')){
									unset($id[$rkey]);
						}
					}
					}
					$id=array_values($id);
				foreach ($id as $key => $value) {
					$data=array(
						'status'=>1
					);
					$this->users_model->update_booking_rating($data,$value->id);
				}//exit;
				$result=array(
						'ErrorCode'=>0,
						'Data'=>round($coplayer_rating->avg),
						'Message'=>"Rating Success"
					);
			}else{
				$result=array(
					'ErrorCode'=>0,
					'Data'=>"",
					'Message'=>"Rating Failed"
				);
			}

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

	public function rate_post(){
		if($this->input->post('user_id')&&$this->input->post('sports_id')){
			$user_id=$this->input->post('user_id');
			$sports_id=$this->input->post('sports_id');
			$rate=$this->input->post('rate');

			$data=array(
					'user_id'=>$user_id,
					'co_player'=>$user_id,
					'sports_id'=>$sports_id,
					'rating'=>$rate
				);
			//$this->users_model->get_status($user_id,$value->id)->rating;
			$data=$this->users_model->insert_rate_coplayer($data);
			//$user_rating=$this->users_model->get_coplayer_rating($user_id,$sports_id);
			$user_rating=round($this->users_model->get_status($user_id,$sports_id)->rating);
			//print_r($user_rating);exit();
			if($data){
			$result=array(
				'ErrorCode'=>0,
				'Data'=>$user_rating,
				'Message'=>"Success"
		);
		}else{
			$result=array(
				'ErrorCode'=>1,
				'Data'=>"",
				'Message'=>"Failed"
				);
		}


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

	public function area_post(){

		$user_id=$this->input->post('user_id');
		$rate=$this->input->post('sports_id');
		$this->users_model->delete_user_area($user_id);
		$result=array(
				'ErrorCode'=>0,
				'Data'=>'',
				'Message'=>""
		);
		return $this->response($result,200);

	}

	public function update_name_post(){
		if($this->input->post('user_id')&&$this->input->post('name')){
		$user_id=$this->input->post('user_id');
		$name=$this->input->post('name');
		$data=array(
			'name'=>$name
			);
		$this->users_model->update_name($user_id,$data);
		$result=array(
				'ErrorCode'=>0,
				'Data'=>'',
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

	public function get_otp_get($user){
		$data=$this->users_model->get_otp($user);
		$result=array(
				'ErrorCode'=>0,
				'Data'=>$data,
				'Message'=>""
		);
		return $this->response($result,200);
	}

	//////////////////////////////////////////User Update/////////////////////////////////
	public function update_profile_post(){
		if($this->input->post()){
			$user_id 	=$this->input->post('user_id');
			$name 		=$this->input->post('name');
			//$address 	=$this->input->post('address');
			$email 		=$this->input->post('email');
			//echo "<pre>";print_r($_FILES);exit();
			if (!empty($_FILES)) {
				$path 		="pics/";
				$image 		=$this->common->file_upload_image($path);
				$data=array(
						'name' 		=>$name,
						//'address' 	=>$address,
						'email' 	=>$email,
						'image' 	=>$image,
				);
			}else{
				$data=array(
						'name' 		=>$name,
						//'address' 	=>$address,
						'email' 	=>$email,
				);
			}
			$this->users_model->update_name($user_id,$data);
			$data_img=$this->users_model->user_details($user_id)->image;
			$result=array(
						'ErrorCode'=>0,
						'Data'=>$data_img,
						'Message'=>"Success"
					);
			return $this->response($result,200);
	 	}else{
	 		$result=array(
						'ErrorCode'=>1,
						'Data'=>'',
						'Message'=>"User Not Updated"
			);
			return $this->response($result,200);
	 	}
	}
	///////////////////////////User Profile///////////////////////////////////////////
	public function user_profile_get($user_id){
		$data=$this->users_model->user_details($user_id);
		if($data){
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
						'Message'=>"Failed"
			);
			return $this->response($result,200);
	 	}
	}
	//////////////////////////////////////////////////////////////////////////////////
	/////////////////////////// update device_id start //////////////////////////////
	public function update_deviceid_post(){
		if($this->input->post('user_id')&&$this->input->post('device_id')){
		$user_id=$this->input->post('user_id');
		$device_id=$this->input->post('device_id');
		$data=array(
			'device_id'=>$device_id
			);
		$this->users_model->update_name($user_id,$data);
		$result=array(
				'errorCode'=>1,
				'data'=>0,
				'message'=>"Success"
		);
		return $this->response($result,200);
	 }else{
	 	$result=array(
				'errorCode'=>0,
				'data'=>0,
				'message'=>"Failed"
		);
		return $this->response($result,200);
	 }
	}
    ///////////////////////// update device_id end ///////////////////////////////////	
	

}
