<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Referfriend extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/Referfriend_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");

	}


/////////////////////////// up_coin page details data fetching start /////////////////////////////////////////

public function refer_friend_post()
{
    
    $user_id=$this->input->post('user_id');
    $location_id=$this->input->post('location_id');
    $user_refer=$this->Referfriend_model->get_check_user_refer($user_id);
    if(!empty($user_refer)){

    	 foreach($user_refer as $row) {
                    $referal_id = $row->referal_id;
                 }

    }else{
    		$this->load->helper('string');
    		$refer_random= random_string('alnum', 6);
    		$referal_id = $refer_random.$user_id;
    		$data=array(
            'users_id'=>$user_id,
            'referal_id'=>$referal_id,
            'link'=>"",
            'status'=>1,
            'added_date'=>date('Y-m-d H:i:s'),
            );
    $add_user_referal=$this->Referfriend_model->add_user_referal('user_referal',$data);
    }
    $refer_details=$this->Referfriend_model->get_refer_details($user_id);
    if(!empty($refer_details)){
    	foreach($refer_details as $row) {
                    $install_coin = $row->install_coin;
                 }
             }else{
                    $install_coin = 0;
             }
    $refer_counts=$this->Referfriend_model->get_refer_counts($user_id);
    if(!empty($refer_counts)){
        foreach($refer_counts as $row) {
                    $install_count = $row->install_count;
                 }
             }else{
                    $install_count = 0;
             }
    $refer_setting=$this->Referfriend_model->get_refer_setting($location_id);
    if(!empty($refer_setting)){
    	foreach($refer_setting as $row) {
                    $set_count = $row->install_count;
                    $set_coin = $row->install_bonus_coin;
                 }
        $description=" Refer upUPUP  to your friends and earn ".$set_coin." UPcoins on every  ".$set_count." installs. T&C apply.";
    }else{
    	$description="";
    }
    $refer_book_setting=$this->Referfriend_model->get_refer_book_setting($location_id);
    if(!empty($refer_book_setting) && !empty($refer_setting)){
    	foreach($refer_book_setting as $row) {
                    $set_book_coin = $row->booking_bonus_coin;
                 }
         $description=" Refer upUPUP  to your friends and earn ".$set_coin." UPcoins on every  ".$set_count." installs. Also earn ".$set_book_coin." bonus UPcoins for every first booking done by them. T&C apply. ";
    }else{
    		$set_book_coin = 0;
    }
    
    $referal_data=array(
                'referal_coin'=>(int)$install_coin,
                'referal_count'=>(int)$install_count,
                'referal_id'=>$referal_id,
                'tandc'=>"",
                'description'=>$description,
        );
    if(!empty($referal_data)){
    			$result=array(
                        'errorCode'=>1,
                        'data'=>$referal_data,
                        'message'=>"sucess"
                        );
                return $this->response($result,200);
            }else{

            	$result=array(
                        'errorCode'=>0,
                        'data'=>[],
                        'message'=>"empty"
                        );
                return $this->response($result,200);
            }
}


//////////////////////////////////////// user referal data insertion start ///////////////////////////////////////
public function friends_referal_post()
{
    
    $installed_user_id=$this->input->post('installed_user_id');
    $referal_id=$this->input->post('referal_id');
    $refer_check=$this->Referfriend_model->get_refer_check($referal_id);
    foreach($refer_check as $row) {
                    $user_id = $row->users_id;
                 }
    $installation_check=$this->Referfriend_model->get_installation_check($installed_user_id);
    
    if($user_id != $installed_user_id && empty($installation_check)){

                $user_location=$this->Referfriend_model->get_user_location($user_id);
                foreach($user_location as $row) {
                                $location_id = $row->location_id;
                             }
                $referal_data=array(
                            'users_id'=>(int)$user_id,
                            'installed_user_id'=>(int)$installed_user_id,
                            'referral_id'=>$referal_id,
                            'date'=>date('Y-m-d'),
                            'added_date'=>date('Y-m-d H:i:s'),
                    );
                $add_friend_refer=$this->Referfriend_model->add_friend_refer('refer_friend_bonus',$referal_data);
                $check_refer_count=$this->Referfriend_model->get_check_refer_count($user_id);
                if(!empty($check_refer_count)){
                    foreach($check_refer_count as $row) {
                                $id = $row->id;
                                $referal_count = $row->referal_count;
                             }
                        $referal_count=$referal_count+1;
                        $update_data= array('referal_count' => $referal_count);

                $this->Referfriend_model->update($update_data,'user_referal_count',$id);
                $refer_setting=$this->Referfriend_model->get_refer_setting($location_id);
                if(!empty($refer_setting)){
                    foreach($refer_setting as $row) {
                                $set_id = $row->id;
                                $set_count = $row->install_count;
                                $set_coin = $row->install_bonus_coin;
                             }
                    if ($set_count == $referal_count) {
                       
                       $my_account=$this->Referfriend_model->get_my_account($user_id);
                       if(!empty($my_account)){

                        foreach($my_account as $row) {
                                $up_coin = $row->up_coin;
                                $bonus_coin = $row->bonus_coin;
                                $total = $row->total;
                             }
                    $bonus_coin=$bonus_coin+$set_coin;
                    $total=$total+$set_coin;
                            $update_data= array('bonus_coin' => $bonus_coin,
                                                'total' => $total);
                    $this->Referfriend_model->update_my_account($update_data,'my_account',$user_id);

                    }else{
                                $up_coin = 0;
                                $bonus_coin = 0;
                                $total = 0;
                                
                                            $bonus_coin=$bonus_coin+$set_coin;
						                    $total = $total+$set_coin;

						                    $data=array(
													'users_id'=>$user_id,
													'up_coin'=>$up_coin,
													'bonus_coin'=>$bonus_coin,
													'total'=>$total,
													'added_date'=>date('Y-m-d H:i:s'),
													);

										      $add=$this->Referfriend_model->add_refer_bonusset('my_account',$data);
                    }
                    
                    $referal_bonus_data=array(
                            'users_id'=>(int)$user_id,
                            'refer_bonus_setting_id'=>(int)$set_id,
                            'install_count'=>(int)$set_count,
                            'install_coin'=>(int)$set_coin,
                            'date'=>date('Y-m-d'),
                            'added_date'=>date('Y-m-d H:i:s'),
                    );
                    $add_refer_bonus=$this->Referfriend_model->add_refer_bonusset('referal_bonus',$referal_bonus_data);
                    $referal_count=0;
                    $update_data= array('referal_count' => $referal_count);

                    $this->Referfriend_model->update($update_data,'user_referal_count',$id);

                    }
                }
                }else{
                    
                                    $referal_data=array(
                                        'user_id'=>(int)$user_id,
                                        'referal_count'=>1,
                                    );
                                $id=$this->Referfriend_model->add_friend_refer('user_referal_count',$referal_data);
                                $referal_count=1;
                   $refer_setting=$this->Referfriend_model->get_refer_setting($location_id);
                   if(!empty($refer_setting)){
                    foreach($refer_setting as $row) {
                                $set_id = $row->id;
                                $set_count = $row->install_count;
                                $set_coin = $row->install_bonus_coin;
                             }
                    if ($set_count == $referal_count) {
                       
                       $my_account=$this->Referfriend_model->get_my_account($user_id);
                       if(!empty($my_account)){

                        foreach($my_account as $row) {
                                $up_coin = $row->up_coin;
                                $bonus_coin = $row->bonus_coin;
                                $total = $row->total;
                             }
                            $bonus_coin=$bonus_coin+$set_coin;
                            $total=$total+$set_coin;
                            $update_data= array('bonus_coin' => $bonus_coin,
                                                'total' => $total);
                            $this->Referfriend_model->update_my_account($update_data,'my_account',$user_id);

                    }else{
                                $up_coin = 0;
                                $bonus_coin = 0;
                                $total = 0;
						                    $bonus_coin=$bonus_coin+$set_coin;
						                    $total = $total+$set_coin;

						                    $data=array(
													'users_id'=>$user_id,
													'up_coin'=>$up_coin,
													'bonus_coin'=>$bonus_coin,
													'total'=>$total,
													'added_date'=>date('Y-m-d H:i:s'),
													);

										      $add=$this->Referfriend_model->add_refer_bonusset('my_account',$data);
                    }
                    
                    $referal_bonus_data=array(
                            'users_id'=>(int)$user_id,
                            'refer_bonus_setting_id'=>(int)$set_id,
                            'install_count'=>(int)$set_count,
                            'install_coin'=>(int)$set_coin,
                            'date'=>date('Y-m-d'),
                            'added_date'=>date('Y-m-d H:i:s'),
                    );
                    $add_refer_bonus=$this->Referfriend_model->add_refer_bonusset('referal_bonus',$referal_bonus_data);
                    $referal_count=0;
                    $update_data= array('referal_count' => $referal_count);

                    $this->Referfriend_model->update($update_data,'user_referal_count',$id);

                    }
                }
                }
                
                    $result=array(
                                    'errorCode'=>1,
                                    'data'=>[],
                                    'message'=>"sucess"
                                    );
                            return $this->response($result,200); 
    }else{

        $result=array(
                                    'errorCode'=>0,
                                    'data'=>[],
                                    'message'=>"failed"
                                    );
                            return $this->response($result,200);
    }
    
}
//////////////////////////////////////// user referal data insertion end ///////////////////////////////////////


}