<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Upcoin extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/Upcoin_model");
		$this->load->library("notification");
		date_default_timezone_set("Asia/Kolkata");

	}


/////////////////////////// up_coin page details data fetching start /////////////////////////////////////////

public function up_coin_post()
{
    
    $user_id=$this->input->post('user_id');
    $location_id=$this->input->post('location_id');
    //$user_id=12;
    //$location_id=27;
    $my_account=$this->Upcoin_model->get_my_account($user_id);
        
        if(!empty($my_account)){

            foreach($my_account as $row) {
                    $up_coin = $row->up_coin;
                    $bonus_coin = $row->bonus_coin;
                    $total = $row->total;
                 }

        }else{
                    $up_coin = 0;
                    $bonus_coin = 0;
                    $total = 0;
        }
    $check_init_book=$this->Upcoin_model->get_check_init_book($user_id);
        if(empty($check_init_book)){
                $intial_booking=$this->Upcoin_model->get_intial_booking($location_id);
    
            if(!empty($intial_booking)){

                    foreach($intial_booking as $row) {

                        $booking_bonus = $row->coin;
                    }

            }else{
                    $booking_bonus = 0;
            }
        }else{
                    $booking_bonus = 0;
        }
    $gbvalue=$this->Upcoin_model->get_upcoin_gbvalue();
    
    foreach($gbvalue as $row) {
                    $set_id = $row->id;
                    $set_coin = $row->coin;
                    $set_rupee = $row->rupee;
                 }
                 
    $last_refund=$this->Upcoin_model->get_last_refund($user_id);

        if(!empty($last_refund)){

            foreach($last_refund as $row) {
                $last_refund_coin = $row->coin;
                $last_booking_id = $row->booking_id;
                $last_refund_amount = $row->amount;
                $description="The refund of your booking ID#[".$last_booking_id."], Rs.".$last_refund_amount."/-  equivalent to ".$last_refund_coin." UPcoins has been added to the Purchased UPcoins";
                }

        }else{
            $description="The refund of your booking ID#[";
        }
    $buycoin_setting=$this->Upcoin_model->get_buycoin_setting($location_id); 
    $last_transaction=$this->Upcoin_model->get_last_transaction($user_id);
    //echo "<pre>";print_r($last_transaction);
    $transaction_type=$last_transaction['type'];
     for ($m = 0; $m < 1; $m++) { 
        $buycoin=[];
        $buycoin=array(
                    'purchased_coins'=>(int)$up_coin,
                    'bonus_coins'=>(int)$bonus_coin,
                    'refund_bonus_amount'=>$last_refund_amount,
                    'description'=>$description,
                    'set_coin'=>$set_coin,
                    'set_rupee'=>$set_rupee,
                    'initial_booking'=>$booking_bonus,
                    );
            if(!empty($buycoin_setting)){
               for ($buy = 0; $buy < count($buycoin_setting); $buy++) {

                    $id = $buycoin_setting[$buy]->id;
                    $rupee = $buycoin_setting[$buy]->rupee;
                    $coin = $buycoin_setting[$buy]->coin;
                    $buys=[];
                    $buys=array(
                        'id'=>(int)$id,
                        'rupee'=>(int)$rupee,
                        'coin'=>(int)$coin,
                    );

                $buycoin['buy_coins'][$buy]=$buys;


                } 
            }else{
              $buys=[];
              $buycoin['buy_coins']=$buys;
            }
            

            if($transaction_type=="booking_refund"){
                    $id=$last_transaction['id'];
                    $amount=$last_transaction['amount'];
                    $coin=$last_transaction['coin'];
                    $reason=$last_transaction['reason'];
                    $date=$last_transaction['date'];
                    $type=$last_transaction['type'];
                    $title=$last_transaction['title'];

                    $transaction=[];
                    $transaction=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>1,
                        'coins'=>(int)$coin,
                        'rupees'=>(int)$amount,
                        'description'=>$reason,
                        'date'=>$date,
                    );
                 $buycoin['last_transaction'][0]=$transaction;
                }elseif ($transaction_type=="buy_coin") {

                    $id=$last_transaction['id'];
                    $buycoin_setting_id=$last_transaction['buycoin_setting_id'];
                    $payment_id=$last_transaction['payment_id'];
                    $rupee=$last_transaction['rupee'];
                    $coin=$last_transaction['coin'];
                    $date=$last_transaction['date'];
                    $type=$last_transaction['type'];
                    $title=$last_transaction['title'];

                    $transaction=[];
                    $transaction=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>1,
                        'coins'=>(int)$coin,
                        'rupees'=>(int)$rupee,
                        'description'=>"",
                        'date'=>$date,
                    );
                 $buycoin['last_transaction'][0]=$transaction;
                }elseif ($transaction_type=="booking_bonus") {
                   
                    $id=$last_transaction['id'];
                    $booking_id=$last_transaction['booking_id'];
                    $booking_bonus_setting_id=$last_transaction['booking_bonus_setting_id'];
                    $bonus_coin=$last_transaction['bonus_coin'];
                    $date=$last_transaction['date'];
                    $type=$last_transaction['type'];
                    $title=$last_transaction['title'];

                    $transaction=[];
                    $transaction=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>2,
                        'coins'=>(int)$bonus_coin,
                        'rupees'=>0,
                        'description'=>$booking_id,
                        'date'=>$date,
                    );
                 $buycoin['last_transaction'][0]=$transaction;
                }elseif ($transaction_type=="referal_bonus") {
                    $id=$last_transaction['id'];
                    $refer_bonus_setting_id=$last_transaction['refer_bonus_setting_id'];
                    $install_count=$last_transaction['install_count'];
                    $install_coin=$last_transaction['install_coin'];
                    $date=$last_transaction['date'];
                    $type=$last_transaction['type'];
                    $title=$last_transaction['title'];

                    $transaction=[];
                    $transaction=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>2,
                        'coins'=>(int)$install_coin,
                        'rupees'=>0,
                        'description'=>"",
                        'date'=>$date,
                    );
                 $buycoin['last_transaction'][0]=$transaction;
                }elseif ($transaction_type=="booking") {
                    $id=$last_transaction['id'];
                    $booking_id=$last_transaction['booking_id'];
                    $payment_mode_id=$last_transaction['payment_mode_id'];
                    $rupee=$last_transaction['rupee'];
                    $coin=$last_transaction['coin'];
                    $date=$last_transaction['date'];
                    $type=$last_transaction['type'];
                    $title=$last_transaction['title'];

                    $transaction=[];
                    $transaction=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>(int)$payment_mode_id,
                        'coins'=>(int)$coin,
                        'rupees'=>(int)$rupee,
                        'description'=>"$booking_id",
                        'date'=>$date,
                    );
                 $buycoin['last_transaction'][0]=$transaction;
                }else{
                   $transaction=[];
                   $buycoin['last_transaction']=$transaction; 
                }
     }
        if(!empty($buycoin)){
            $result=array(
                        'errorCode'=>1,
                        'data'=>$buycoin,
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

/////////////////////////// up_coin page details data fetching end /////////////////////////////////////////


///////////////////////////////// up coin transaction history start////////////////////////////////
public function transaction_history_post()
{

    $user_id=$this->input->post('user_id');
    $location_id=$this->input->post('location_id');
    $transaction=$this->Upcoin_model->get_transaction($user_id);
    usort( $transaction, function( $a, $b ){
        if($a['added_date'] == $b['added_date']) {
            return 0;
        }
        return ($a['added_date'] > $b['added_date']) ? -1 : 1;
    });

        for ($tran = 0; $tran < count($transaction); $tran++) { 

            $transaction_type=$transaction[$tran]['type'];
            if($transaction_type=="booking_refund"){

                    $id=$transaction[$tran]['id'];
                    $rupee=$transaction[$tran]['rupee'];
                    $coin=$transaction[$tran]['coin'];
                    $reason=$transaction[$tran]['reason'];
                    $date=$transaction[$tran]['date'];
                    $type=$transaction[$tran]['type'];
                    $title=$transaction[$tran]['title'];

                    $transa=[];
                    $transa=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>1,
                        'coins'=>(int)$coin,
                        'rupees'=>(int)$rupee,
                        'description'=>$reason,
                        'date'=>$date,
                    );
                 $transactions[$tran]=$transa;
                }elseif ($transaction_type=="buy_coin") {

                    $id=$transaction[$tran]['id'];
                    $payment_id=$transaction[$tran]['payment_id'];
                    $rupee=$transaction[$tran]['rupee'];
                    $coin=$transaction[$tran]['coin'];
                    $date=$transaction[$tran]['date'];
                    $type=$transaction[$tran]['type'];
                    $title=$transaction[$tran]['title'];
                    $desc="amount ₹".$rupee."/- deducted ";
                    $transa=[];
                    $transa=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>1,
                        'coins'=>(int)$coin,
                        'rupees'=>(int)$rupee,
                        'description'=>$desc,
                        'date'=>$date,
                    );
                 $transactions[$tran]=$transa;
                }elseif ($transaction_type=="booking_bonus") {
                   
                    $id=$transaction[$tran]['id'];
                    $booking_id=$transaction[$tran]['booking_id'];
                    $bonus_coin=$transaction[$tran]['coin'];
                    $date=$transaction[$tran]['date'];
                    $type=$transaction[$tran]['type'];
                    $title=$transaction[$tran]['title'];

                    $transa=[];
                    $transa=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>2,
                        'coins'=>(int)$bonus_coin,
                        'rupees'=>0,
                        'description'=>$booking_id,
                        'date'=>$date,
                    );
                 $transactions[$tran]=$transa;
                }elseif ($transaction_type=="referal_bonus") {
                    $id=$transaction[$tran]['id'];
                    $install_coin=$transaction[$tran]['coin'];
                    $date=$transaction[$tran]['date'];
                    $type=$transaction[$tran]['type'];
                    $title=$transaction[$tran]['title'];

                    $transa=[];
                    $transa=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>2,
                        'coins'=>(int)$install_coin,
                        'rupees'=>0,
                        'description'=>"",
                        'date'=>$date,
                    );
                 $transactions[$tran]=$transa;
                }elseif ($transaction_type=="booking") {
                    $id=$transaction[$tran]['id'];
                    $booking_id=$transaction[$tran]['booking_id'];
                    $payment_mode_id=$transaction[$tran]['payment_mode_id'];
                    $rupee=$transaction[$tran]['rupee'];
                    $coin=$transaction[$tran]['coin'];
                    $date=$transaction[$tran]['date'];
                    $type=$transaction[$tran]['type'];
                    $title=$transaction[$tran]['title'];
                    $desc="booking ID [".$booking_id."]"; 
                    $transa=[];
                    $transa=array(
                        'id'=>(int)$id,
                        'transaction_type'=>$type,
                        'title'=>$title,
                        'payment_type'=>(int)$payment_mode_id,
                        'coins'=>(int)$coin,
                        'rupees'=>(int)$rupee,
                        'description'=>$desc,
                        'date'=>$date,
                    );
                 $transactions[$tran]=$transa;
                }

        }
    
    if(!empty($transactions)){
        $transactions=array_slice($transactions, 0, 10);
            $result=array(
                        'errorCode'=>1,
                        'data'=>$transactions,
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

///////////////////////////////// up coin transaction history end ////////////////////////////////

///////////////////////////////// up coin settings start ////////////////////////////////
public function up_coin_settings_post()
{
    $user_id=$this->input->post('user_id');
    $my_account=$this->Upcoin_model->get_my_account($user_id);
    $up_coin_settings=$this->Upcoin_model->get_up_coin_settings();
    if(!empty($my_account)){

        foreach($my_account as $row) {

                $up_coin = $row->up_coin;
                $bonus_coin = $row->bonus_coin;
                $total = $row->total;
          } 

          
    }else{
        
                $up_coin = 0;
                $bonus_coin = 0;
                $total = 0;
       
    }
 
        if(!empty($up_coin_settings)){
            foreach($up_coin_settings as $row) {

                $id = $row->id;
                $coin = $row->coin;
                $rupee = $row->rupee;
          }
        $up_set=array(
                'up_coin'=>(int)$up_coin,
                'bonus_coin'=>(int)$bonus_coin,
                'total'=>(int)$total,
                'conversion_id'=>(int)$id,
                'conversion_coin'=>(int)$coin,
                'conversion_rupee'=>(int)$rupee,
        );
            $result=array(
                        'errorCode'=>1,
                        'data'=>$up_set,
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

///////////////////////////////// up coin settings end ////////////////////////////////

///////////////////////////////// buy coin purchase start ////////////////////////////////
public function buy_coin_payment_post()
{
    $users_id=$this->input->post('user_id');
    $buycoin_setting_id=$this->input->post('buycoin_id');
    $location_id=$this->input->post('location_id');
    $payment_id=$this->input->post('payment_id');
    $rupee=$this->input->post('rupee');
    $coin=$this->input->post('coin');
    $data=array(
            'users_id'=>$users_id,
            'buycoin_setting_id'=>$buycoin_setting_id,
            'city_id'=>$location_id,
            'payment_id'=>$payment_id,
            'rupee'=>$rupee,
            'coin'=>$coin,
            'date'=>date('Y-m-d'),
            'status'=>1,
            'added_date'=>date('Y-m-d H:i:s'),
            );
    $add_buycoin=$this->Upcoin_model->add_buy_coin_payment('buy_coin',$data);
    $my_account_details=$this->Upcoin_model->get_my_account_details($users_id);
    if(!empty($my_account_details)){

         foreach($my_account_details as $row) {

                $id = $row->id;
                $up_coin = $row->up_coin;
                $bonus_coin = $row->bonus_coin;
                $total = $row->total;
          }
    $my_upcoin=$up_coin+$coin;
    $my_total=$total+$coin;
    $update_data= array('up_coin' => $my_upcoin,
                        'total' => $my_total);

    $this->Upcoin_model->update($update_data,'my_account',$id);

    }else{

       $data=array(
            'users_id'=>$users_id,
            'up_coin'=>$coin,
            'bonus_coin'=>0,
            'total'=>$coin,
            'added_date'=>date('Y-m-d H:i:s'),
            );
    $this->Upcoin_model->add_buy_coin_payment('my_account',$data); 
    }

    $user_details=$this->Upcoin_model->get_user_details($users_id);
    foreach($user_details as $row) {

                $u_id = $row->id;
                $u_name = $row->name;
                $u_phone_no = $row->phone_no;
                $u_device_id = $row->device_id;
                $u_email = $row->email;
          }
   
    $location=$this->Upcoin_model->get_location($location_id);
    foreach($location as $row) {

                $u_location = $row->location;
          }
    if($rupee==$coin){
      $message="Hurray!!! You just earned ".$coin." UPcoins, Now, enjoy lightening booking \r\n\n Cheerio, \r\n Team upUPUP";  
    }else{
        $bonus=$coin-$rupee;
        $message="Hurray!!! You just earned  ".$rupee." UPcoins  with a bonus of ".$bonus." UPcoins. Now, enjoy quick booking. \r\n\n Cheerio, \r\n Team upUPUP ";
    }
    
    //$this->common->sms(str_replace(' ', '', $u_phone_no),urlencode($message));
    
    $buy_coin_data=$this->Upcoin_model->buy_coin_data($users_id);
    $title ="UPcoin Purchased";
    $data_push =array('result' => array('message'=> $message,
                                     'title'  => $title,
                                     'type'   => 10),
                                     'status'=> "true",
                                     'type'  => "GENERAL",
                                     'venue_id'=>$coin
                                                 );

    $notification= $this->notification->push_notification(array($buy_coin_data),$message,$title,$data_push);
    
    $upupupemail=$this->Upcoin_model->get_upupupemail();
    foreach($upupupemail as $row) {

                $up_email = $row->email;
                
               $data['data']=array(
      'date'=>date('Y-m-d'),
      'purchase_id'=>$payment_id,
      'user_name'=>$u_name,
      'phone_no'=>$u_phone_no,
      'city'=>$u_location,
      'amount'=>$rupee,
      'coin'=>$rupee,
      'bonus'=>$bonus,
      'total_coin'=>$coin,

      );
    
          $to_email = $up_email;
          $subject='UP Coin purchased';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('booking@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('buycoin_mail',$data,true);
          $this->email->message($message);
          $this->email->send();


          }
    
              
    
    if(!empty($add_buycoin)){
        $result=array(
                'errorCode'=>1,
                'data'=>$add_buycoin,
                'message'=>"sucess"
            );
    return $this->response($result,200);
    }else{
        $result=array(
                'errorCode'=>0,
                'data'=>[],
                'message'=>"fail"
            );
    return $this->response($result,200);
    }
      
}
///////////////////////////////// buy coin purchase end ////////////////////////////////

///////////////////////////////// buy coin purchase start ////////////////////////////////
public function buy_coin_payment_demo_get()
{
    $users_id=39;
    $buycoin_setting_id=10;
    $location_id=27;
    $payment_id="MOJO8802005A44308755";
    $rupee=100;
    $coin=110;
    $data=array(
            'users_id'=>$users_id,
            'buycoin_setting_id'=>$buycoin_setting_id,
            'city_id'=>$location_id,
            'payment_id'=>$payment_id,
            'rupee'=>$rupee,
            'coin'=>$coin,
            'date'=>date('Y-m-d'),
            'status'=>1,
            'added_date'=>date('Y-m-d H:i:s'),
            );
    $add_buycoin=$this->Upcoin_model->add_buy_coin_payment('buy_coin',$data);
    $my_account_details=$this->Upcoin_model->get_my_account_details($users_id);
    if(!empty($my_account_details)){

         foreach($my_account_details as $row) {

                $id = $row->id;
                $up_coin = $row->up_coin;
                $bonus_coin = $row->bonus_coin;
                $total = $row->total;
          }
    $my_upcoin=$up_coin+$coin;
    $my_total=$total+$coin;
    $update_data= array('up_coin' => $my_upcoin,
                        'total' => $my_total);

    $this->Upcoin_model->update($update_data,'my_account',$id);

    }else{

       $data=array(
            'users_id'=>$users_id,
            'up_coin'=>$coin,
            'bonus_coin'=>0,
            'total'=>$coin,
            'added_date'=>date('Y-m-d H:i:s'),
            );
    $add_buycoin=$this->Upcoin_model->add_buy_coin_payment('my_account',$data); 
    }

    $user_details=$this->Upcoin_model->get_user_details($users_id);
    foreach($user_details as $row) {

                $u_id = $row->id;
                $u_name = $row->name;
                $u_phone_no = $row->phone_no;
                $u_device_id = $row->device_id;
                $u_email = $row->email;
          }
   
    $location=$this->Upcoin_model->get_location($location_id);
    foreach($location as $row) {

                $u_location = $row->location;
          }
    if($rupee==$coin){
      $message="Hurray!!! You just earned ".$coin." up coins, Now, enjoy lightening booking \r\n\n Cheerio, \r\n Team upUPUP";  
    }else{
        $bonus=$coin-$rupee;
        $message="Hurray!!! You just earned  ".$rupee." up coins  with a bonus of ".$bonus." upcoins. Now, enjoy quick booking. \r\n\n Cheerio, \r\n Team upUPUP ";
    }
    
    $this->common->sms(str_replace(' ', '', $u_phone_no),urlencode($message));
    
    $upupupemail=$this->Upcoin_model->get_upupupemail();
    foreach($upupupemail as $row) {

                $up_email = $row->email;
                
               $data['data']=array(
      'date'=>date('Y-m-d'),
      'purchase_id'=>$payment_id,
      'user_name'=>$u_name,
      'phone_no'=>$u_phone_no,
      'city'=>$u_location,
      'amount'=>$rupee,
      'coin'=>$rupee,
      'bonus'=>$bonus,
      'total_coin'=>$coin,

      );
    
          $to_email = $up_email;
          $subject='UP Coin purchased';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('booking@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('buycoin_mail',$data,true);
          $this->email->message($message);
          $this->email->send();


          }
    $buy_coin_data=$this->Upcoin_model->buy_coin_data($users_id);
    $title ="UP Coin Purchased";
    $data_push =array('result' => array('message'=> $message,
                                     'title'  => $title,
                                     'type'   => 10),
                                     'status'=> "true",
                                     'type'  => "GENERAL",
                                     'venue_id'=>$coin
                                                 );

    $notification= $this->notification->push_notification(array($buy_coin_data),$message,$title,$data_push);
              
    if(!empty($add_buycoin)){
        $result=array(
                'errorCode'=>1,
                'data'=>$add_buycoin,
                'message'=>"sucess"
            );
    return $this->response($result,200);
    }else{
        $result=array(
                'errorCode'=>0,
                'data'=>[],
                'message'=>"fail"
            );
    return $this->response($result,200);
    }
      
     
}
///////////////////////////////// buy coin purchase end ////////////////////////////////
///////////////////////// booking_id generation start //////////////////////////////////
public function booking_post()
    {

        $user_id=$this->input->post('user_id');
        //$user_id=12;
        $booking_id=  new DateTime();
        $booking_id=$user_id+$booking_id->getTimestamp();

        if(!empty($booking_id)){

            $result=array(
                'ErrorCode'=>1,
                'Data'=>"$booking_id",
                'Message'=>"booking id"
        );

            return $this->response($result,200);
        }else{

            $result=array(
                'ErrorCode'=>0,
                'Data'=>"",
                'Message'=>"empty"
        );

            return $this->response($result,200);
        }



}
///////////////////////// booking_id generation end //////////////////////////////////



}