<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 api
 */

class Upcoin_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
////////////////////////// my account details ///////////////////////////////
 public function get_my_account($user_id)
	{
		$this->db->select("up_coin,bonus_coin,total");
		$this->db->from("my_account");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}
////////////////////////// last refund coin details ///////////////////////////////
 public function get_last_refund($user_id)
	{
		$this->db->select("id,amount,coin,booking_id");
		$this->db->from("booking_refund");
		$this->db->where('users_id',$user_id);
		$this->db->order_by('id','desc');
		$this->db->limit(1); 
		return $this->db->get()->result();
	}
	////////////////////////// last transaction details ///////////////////////////////
 public function get_last_transaction($user_id)
	{
	    date_default_timezone_set("Asia/Kolkata");
        $date_limit=date('Y-m-d', strtotime('-30 days'));
		$this->db->select("id,amount,coin,reason,date,added_date");
		$this->db->from("booking_refund");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','desc');
		$this->db->limit(1); 
		$result1= $this->db->get()->result();
        //echo "<pre>";print_r($result1);
		if(!empty($result1)){
		
			foreach($result1 as $row)
		   {
		       $id1 = $row->id;
		       $amount1 = $row->amount;
		       $coin1 = $row->coin;
		       $reason1 = $row->reason;
		       $date1 = $row->date;
		       $added_date1 = $row->added_date;
		   }

		   $data1=array(
      			'id'=>$id1,
      			'amount'=>$amount1,
      			'coin'=>$coin1,
      			'reason'=>$reason1,
      			'date'=>$date1,
      			'type'=>"booking_refund",
      			'title'=>"Refunded  Upcoins",
      			);
		}else{
		    $data1=[];
			$added_date1 = "2000-01-01 01:01:01pm";
		}
		
		$this->db->select("id,buycoin_setting_id,payment_id,rupee,coin,date,added_date");
		$this->db->from("buy_coin");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','desc');
		$this->db->limit(1); 
		$result2= $this->db->get()->result();
        //echo "<pre>";print_r($result2);
		if(!empty($result2)){
			foreach($result2 as $row)
		   {
		       $id2 = $row->id;
		       $buycoin_setting_id2 = $row->buycoin_setting_id;
		       $payment_id2 = $row->payment_id;
		       $rupee2 = $row->rupee;
		       $coin2 = $row->coin;
		       $date2 = $row->date;
		       $added_date2 = $row->added_date;
		   }

		   $data2=array(
      			'id'=>$id2,
      			'buycoin_setting_id'=>$buycoin_setting_id2,
      			'payment_id'=>$payment_id2,
      			'rupee'=>$rupee2,
      			'coin'=>$coin2,
      			'date'=>$date2,
      			'type'=>"buy_coin",
      			'title'=>"UPcoins purchased",
      			);
		}else{
            $data2=[];
			$added_date2 = "2000-01-01 01:01:01pm";
		}

		$this->db->select("id,booking_id,booking_bonus_setting_id,bonus_coin,date,added_date");
		$this->db->from("booking_bonus");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','desc');
		$this->db->limit(1); 
		$result3= $this->db->get()->result();
        //echo "<pre>";print_r($result3);
		if(!empty($result3)){
			foreach($result3 as $row)
		   {
		       $id3 = $row->id;
		       $booking_id3 = $row->booking_id;
		       $booking_bonus_setting_id3 = $row->booking_bonus_setting_id;
		       $bonus_coin3 = $row->bonus_coin;
		       $date3 = $row->date;
		       $added_date3 = $row->added_date;
		   }

		   $data3=array(
      			'id'=>$id3,
      			'booking_id'=>$booking_id3,
      			'booking_bonus_setting_id'=>$booking_bonus_setting_id3,
      			'bonus_coin'=>$bonus_coin3,
      			'date'=>$date3,
      			'type'=>"booking_bonus",
      			'title'=>"Booking bonus UPcoins",
      			);
		}else{
            $data3=[];
			$added_date3 = "2000-01-01 01:01:01pm";
		}

		$this->db->select("id,booking_id,refer_bonus_setting_id,bonus_coin,date,added_date");
		$this->db->from("referal_booking_bonus");
		$this->db->where('user_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','desc');
		$this->db->limit(1); 
		$result6= $this->db->get()->result();
        //echo "<pre>";print_r($result3);
		if(!empty($result6)){
			foreach($result6 as $row)
		   {
		       $id6 = $row->id;
		       $booking_id6 = $row->booking_id;
		       $refer_bonus_setting_id6 = $row->refer_bonus_setting_id;
		       $bonus_coin6 = $row->bonus_coin;
		       $date6 = $row->date;
		       $added_date6 = $row->added_date;
		   }

		   $data6=array(
      			'id'=>$id6,
      			'booking_id'=>$booking_id6,
      			'refer_bonus_setting_id'=>$refer_bonus_setting_id6,
      			'bonus_coin'=>$bonus_coin6,
      			'date'=>$date6,
      			'type'=>"booking_bonus",
      			'title'=>"Booking bonus UPcoins",
      			);
		}else{
            $data6=[];
			$added_date6 = "2000-01-01 01:01:01pm";
		}

		$this->db->select("id,refer_bonus_setting_id,install_count,install_coin,date,added_date");
		$this->db->from("referal_bonus");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','desc');
		$this->db->limit(1); 
		$result4= $this->db->get()->result();
        //echo "<pre>";print_r($result4);
		if(!empty($result4)){
			foreach($result4 as $row)
		   {
		       $id4 = $row->id;
		       $refer_bonus_setting_id4 = $row->refer_bonus_setting_id;
		       $install_count4 = $row->install_count;
		       $install_coin4 = $row->install_coin;
		       $date4 = $row->date;
		       $added_date4 = $row->added_date;
		   }
		   $data4=array(
      			'id'=>$id4,
      			'refer_bonus_setting_id'=>$refer_bonus_setting_id4,
      			'install_count'=>$install_count4,
      			'install_coin'=>$install_coin4,
      			'date'=>$date4,
      			'type'=>"referal_bonus",
      			'title'=>"Referral bonus UPcoins",
      			);
		}else{
            $data4=[];
			$added_date4 = "2000-01-01 01:01:01pm";
		}
		
		$this->db->select("booking_payment_mode.id,booking_payment_mode.booking_id,booking_payment_mode.payment_mode_id,booking_payment_mode.rupee,booking_payment_mode.coin,booking_payment_mode.date,booking_payment_mode.added_date,venue_booking.time");
		$this->db->from("booking_payment_mode");
		$this->db->join('venue_booking','venue_booking.booking_id=booking_payment_mode.booking_id');
		$this->db->join('payment_mode','payment_mode.id=booking_payment_mode.payment_mode_id');
		$this->db->where('venue_booking.user_id',$user_id);
		$this->db->where('booking_payment_mode.date >=',$date_limit);
		$this->db->order_by('booking_payment_mode.added_date','desc');
		$this->db->limit(1); 
		$result5= $this->db->get()->result();
        //echo "<pre>";print_r($result5);
		if(!empty($result5)){
			foreach($result5 as $row)
		   {
		       $id5 = $row->id;
		       $booking_id5 = $row->booking_id;
		       $payment_mode_id5 = $row->payment_mode_id;
		       $rupee5 = $row->rupee;
		       $coin5 = $row->coin;
		       $date5 = $row->date;
		       $added_date5 = $row->time;
		   }
		   $data5=array(
      			'id'=>$id5,
      			'booking_id'=>$booking_id5,
      			'payment_mode_id'=>$payment_mode_id5,
      			'rupee'=>$rupee5,
      			'coin'=>$coin5,
      			'date'=>$date5,
      			'type'=>"booking",
      			'title'=>"Booking",
      			);
		}else{
            $data5=[];
			$added_date5 = "2000-01-01 01:01:01pm";
		}
		
		if(empty($result1) && empty($result2) && empty($result3) && empty($result4) && empty($result5) && empty($result6)){
			return $data1;
		}
		if( ($added_date1 >= $added_date2) && ($added_date1>= $added_date3) && ($added_date1>= $added_date4) && ($added_date1>= $added_date5) && ($added_date1>= $added_date6)){
			return $data1;
		}elseif (($added_date2 >= $added_date1) && ($added_date2 >= $added_date3) && ($added_date2 >= $added_date4) && ($added_date2 >= $added_date5) && ($added_date2 >= $added_date6)) {
			return $data2;
		}elseif (($added_date3 >= $added_date1) && ($added_date3 >= $added_date2) && ($added_date3 >= $added_date4) && ($added_date3 >= $added_date5) && ($added_date3 >= $added_date6)) {
			return $data3;
		}elseif (($added_date4 >= $added_date1) && ($added_date4 >= $added_date2) && ($added_date4 >= $added_date3) && ($added_date4 >= $added_date5) && ($added_date4 >= $added_date6)) {
			return $data4;
		}elseif (($added_date5 >= $added_date1) && ($added_date5 >= $added_date2) && ($added_date5 >= $added_date3) && ($added_date5 >= $added_date4) && ($added_date5 >= $added_date6)) {
			return $data5;
		}elseif (($added_date6 >= $added_date1) && ($added_date6 >= $added_date2) && ($added_date6 >= $added_date3) && ($added_date6 >= $added_date4) && ($added_date6 >= $added_date5)) {
		  return $data6;  
		}else{
			return $data7=[];  
		}

	}
////////////////////////// Active buy coin details ///////////////////////////////
 public function get_buycoin_setting($location_id)
	{
		$this->db->select("id,location_id,start_date,end_date,rupee,coin");
		$this->db->from("buycoin_setting");
		$this->db->where('location_id',$location_id);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
/////////////////////// transaction history details /////////////////////////////
 public function get_transaction($user_id)
	{
	    date_default_timezone_set("Asia/Kolkata");
        $date_limit=date('Y-m-d', strtotime('-30 days'));
		$this->db->select("id,amount,coin,reason,date,added_date");
		$this->db->from("booking_refund");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','desc');
		$result1= $this->db->get()->result();

		if(!empty($result1)){
		
			for ($hist = 0; $hist < count($result1); $hist++) {

                    $id1 = $result1[$hist]->id;
		       		$amount1 = $result1[$hist]->amount;
		       		$coin1 = $result1[$hist]->coin;
		       		$reason1 = $result1[$hist]->reason;
		       		$date1 = $result1[$hist]->date;
		       		$added_date1 = $result1[$hist]->added_date;
                    $histors=[];
                    $histors=array(
		      			'id'=>$id1,
		      			'rupee'=>$amount1,
		      			'coin'=>$coin1,
		      			'reason'=>$reason1,
		      			'date'=>$date1,
		      			'type'=>"booking_refund",
		      			'title'=>"Refunded  Upcoins",
		      			'added_date'=>$added_date1,
		      			);

                $transaction1[$hist]=$histors;


                }

		   
		}else{
		    $transaction1=[];
		}	

		$this->db->select("id,buycoin_setting_id,payment_id,rupee,coin,date,added_date");
		$this->db->from("buy_coin");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','asc');
		$result2= $this->db->get()->result();

		if(!empty($result2)){

		   for ($hist = 0; $hist < count($result2); $hist++) {

                   $id2 = $result2[$hist]->id;
			       $buycoin_setting_id2 = $result2[$hist]->buycoin_setting_id;
			       $payment_id2 = $result2[$hist]->payment_id;
			       $rupee2 = $result2[$hist]->rupee;
			       $coin2 = $result2[$hist]->coin;
			       $date2 = $result2[$hist]->date;
			       $added_date2 = $result2[$hist]->added_date;
                   $histors=[];
                   $histors=array(
		      			'id'=>$id2,
		      			'rupee'=>$rupee2,
		      			'coin'=>$coin2,
		      			'date'=>$date2,
		      			'type'=>"buy_coin",
		      			'title'=>"UPcoins purchased",
		      			'added_date'=>$added_date2,
		      			);

                $transaction2[$hist]=$histors;


                }

		}else{
            $transaction2=[];
		}

		$trans_merg1 = array_merge_recursive($transaction1, $transaction2);

		$this->db->select("id,booking_id,booking_bonus_setting_id,bonus_coin,date,added_date");
		$this->db->from("booking_bonus");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','asc'); 
		$result3= $this->db->get()->result();

		if(!empty($result3)){
		for ($hist = 0; $hist < count($result3); $hist++) {

		       $id3 = $result3[$hist]->id;
		       $booking_id3 = $result3[$hist]->booking_id;
		       $booking_bonus_setting_id3 = $result3[$hist]->booking_bonus_setting_id;
		       $bonus_coin3 = $result3[$hist]->bonus_coin;
		       $date3 = $result3[$hist]->date;
		       $added_date3 = $result3[$hist]->added_date;

		       $histors=[];
                   $histors=array(
		      			'id'=>$id3,
		      			'booking_id'=>$booking_id3,
		      			'coin'=>$bonus_coin3,
		      			'date'=>$date3,
		      			'type'=>"booking_bonus",
		      			'title'=>"Booking bonus UPcoins",
		      			'added_date'=>$added_date3,
		      			);

                $transaction3[$hist]=$histors;
		   }

		}else{
            $transaction3=[];
		}
		$trans_merg2 = array_merge_recursive($trans_merg1, $transaction3);
		
		$this->db->select("id,refer_bonus_setting_id,install_count,install_coin,date,added_date");
		$this->db->from("referal_bonus");
		$this->db->where('users_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','asc'); 
		$result4= $this->db->get()->result();

		if(!empty($result4)){
		for ($hist = 0; $hist < count($result4); $hist++) {

		       $id4 = $result4[$hist]->id;
		       $refer_bonus_setting_id4 = $result4[$hist]->refer_bonus_setting_id;
		       $install_count4 = $result4[$hist]->install_count;
		       $install_coin4 = $result4[$hist]->install_coin;
		       $date4 = $result4[$hist]->date;
		       $added_date4 = $result4[$hist]->added_date;

		       $histors=[];
                   $histors=array(
		      			'id'=>$id4,
		      			'coin'=>$install_coin4,
		      			'date'=>$date4,
		      			'type'=>"referal_bonus",
		      			'title'=>"Referral bonus UPcoins",
		      			'added_date'=>$added_date4,
		      			);
		        $transaction4[$hist]=$histors;
		   }
		   
		}else{
            $transaction4=[];
		}
		$trans_merg3 = array_merge_recursive($trans_merg2, $transaction4);

		$this->db->select("booking_payment_mode.id,booking_payment_mode.booking_id,booking_payment_mode.payment_mode_id,booking_payment_mode.rupee,booking_payment_mode.coin,booking_payment_mode.date,booking_payment_mode.added_date,venue_booking.time");
		$this->db->from("booking_payment_mode");
		$this->db->join('venue_booking','venue_booking.booking_id=booking_payment_mode.booking_id');
		$this->db->join('payment_mode','payment_mode.id=booking_payment_mode.payment_mode_id');
		$this->db->where('venue_booking.user_id',$user_id);
		$this->db->where('booking_payment_mode.date >=',$date_limit);
		$this->db->order_by('booking_payment_mode.added_date','asc'); 
		$result5= $this->db->get()->result();

		if(!empty($result5)){
		for ($hist = 0; $hist < count($result5); $hist++) {

		       $id5 = $result5[$hist]->id;
		       $booking_id5 = $result5[$hist]->booking_id;
		       $payment_mode_id5 = $result5[$hist]->payment_mode_id;
		       $rupee5 = $result5[$hist]->rupee;
		       $coin5 = $result5[$hist]->coin;
		       $date5 = $result5[$hist]->date;
		       $added_date5 = $result5[$hist]->time;

		       $histors=[];
                   $histors=array(
		      			'id'=>$id5,
		      			'booking_id'=>$booking_id5,
		      			'rupee'=>$rupee5,
		      			'coin'=>$coin5,
		      			'date'=>$date5,
		      			'type'=>"booking",
		      			'title'=>"Booking",
		      			'payment_mode_id'=>$payment_mode_id5,
		      			'added_date'=>$added_date5,
		      			);
		        $transaction5[$hist]=$histors;
		   }
		}else{
            $transaction5=[];
		}

		$trans_merg4 = array_merge_recursive($trans_merg3, $transaction5);

		$this->db->select("id,booking_id,refer_bonus_setting_id,bonus_coin,date,added_date");
		$this->db->from("referal_booking_bonus");
		$this->db->where('user_id',$user_id);
		$this->db->where('date >=',$date_limit);
		$this->db->order_by('added_date','asc'); 
		$result6= $this->db->get()->result();

		if(!empty($result6)){
		for ($hist = 0; $hist < count($result6); $hist++) {

		       $id6 = $result6[$hist]->id;
		       $booking_id6 = $result6[$hist]->booking_id;
		       $refer_bonus_setting_id6 = $result6[$hist]->refer_bonus_setting_id;
		       $bonus_coin6 = $result6[$hist]->bonus_coin;
		       $date6 = $result6[$hist]->date;
		       $added_date6 = $result6[$hist]->added_date;

		       $histors=[];
                   $histors=array(
		      			'id'=>$id6,
		      			'booking_id'=>$booking_id6,
		      			'coin'=>$bonus_coin6,
		      			'date'=>$date6,
		      			'type'=>"booking_bonus",
		      			'title'=>"Booking bonus UPcoins",
		      			'added_date'=>$added_date6,
		      			);

                $transaction6[$hist]=$histors;
		   }

		}else{
            $transaction6=[];
		}
		$trans_merg5 = array_merge_recursive($trans_merg4, $transaction6);
		return $trans_merg5;


	}

////////////////////////// up coin settings details ///////////////////////////////
 public function get_up_coin_settings()
	{
		$this->db->select("id,coin,rupee");
		$this->db->from("upcoin_setting");
		$this->db->where('status',1);
		$this->db->limit(1);
		return $this->db->get()->result();
	}
//////////////////////////// add buy coin payment details start /////////////////////////////
    public function add_buy_coin_payment($table,$data)
	{
		if($this->db->insert($table,$data)){
			return $this->db->insert_id() ;
		}else{
			return false;
		}
	}
//////////////////////////// add buy coin payment details end /////////////////////////////
////////////////////////// Fetch my account details ///////////////////////////////
 public function get_my_account_details($users_id)
	{
		$this->db->select("id,users_id,up_coin,bonus_coin,total");
		$this->db->from("my_account");
		$this->db->where('users_id',$users_id);
		return $this->db->get()->result();
	}
////////////////////////// upcoin global conversion rate ///////////////////////////////
 public function get_upcoin_gbvalue()
	{
		$this->db->select("id,coin,rupee");
		$this->db->from("upcoin_setting");
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
////////////////////////// inital booking bonus  ///////////////////////////////
 public function get_intial_booking($location_id)
	{
		$this->db->select("coin");
		$this->db->from("booking_bonus_setting");
		$this->db->where('location_id',$location_id);
		$this->db->where('status',1);
		return $this->db->get()->result();
	}
////////////////////////////////// my account //////////////////////////////////
	public function update($data,$table,$id)
	{
		return $this->db->update($table, $data, array('id' => $id));
	}
////////////////////////// users detail based on user_id ///////////////////////////////
 public function get_user_details($users_id)
	{
		$this->db->select("id,name,phone_no,device_id,email");
		$this->db->from("users");
		$this->db->where('id',$users_id);
		return $this->db->get()->result();
	}
////////////////////////// upupup buy coin active email /////////////////////////////
	public function get_upupupemail(){
        $this->db->select('email');
		$this->db->from('upupup_email');
		$this->db->where("buy_coin",1);
		return $this->db->get()->result();
	}
////////////////////////// location name /////////////////////////////
	public function get_location($location_id){
        $this->db->select('location');
		$this->db->from('locations');
		$this->db->where("id",$location_id);
		return $this->db->get()->result();
	}
/////////////////// user details ////////////////////////////
	public function buy_coin_data($users_id)
	{
		$this->db->select('users.device_id,users.id,users.phone_no,users.name,users.email');
		$this->db->from('users');
		$this->db->where('users.id',$users_id);
		return $this->db->get()->row_array();
	}
////////////////////////// inital booking bonus  ///////////////////////////////
 public function get_check_init_book($user_id)
	{
		$this->db->select("id");
		$this->db->from("booking_bonus");
		$this->db->where('users_id',$user_id);
		return $this->db->get()->result();
	}

}
