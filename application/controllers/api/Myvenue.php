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
class Myvenue extends REST_Controller {

  function __construct()
  {

    parent::__construct();

    // Configure limits on our controller methods
    // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
    $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
    $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    $this->load->model("api/myvenue_model");
    $this->load->model("api/dashboard_model");
    $this->load->library("notification");
    date_default_timezone_set("Asia/Kolkata");
  }
//fetch venues corresponding to user id 
public function venuelist_post(){
         $user_id=$this->input->post('user_id');

      $data=$this->myvenue_model->get_venuelist($user_id);

        if($data){ 
        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($result,200);
          }

  }
//sports id selection for inactive courts on based on user details
public function sportslist_post(){
         $venue_id=$this->input->post('venue_id');
         $user_id=$this->input->post('user_id');
         $user_role=$this->input->post('user_role');
         $role="Court Manager";
         if($user_role!=$role){
          $data=$this->myvenue_model->get_sportsdetails($venue_id);
        }else{
          $data=$this->myvenue_model->get_sportslist($user_id);
        }

           if($data){

        return $this->response($data,200);
          }else{
            
          $result=array(
        
        "not exist"
         );
         return $this->response($result,200);
          }

  }
//court details 
public function courtlist_post(){
         $venue_id=$this->input->post('venue_id');
         $sports_id=$this->input->post('sports_id');
         $user_id=$this->input->post('user_id');
         $user_role=$this->input->post('user_role');
         $role="Court Manager";
         if($user_role!=$role){
          $data=$this->myvenue_model->get_courtlist($venue_id,$sports_id);
        }else{
          $data=$this->myvenue_model->get_courtlists($user_id,$sports_id);
        }

           if($data){

        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($result,200);
          }

  }

//all slot values of upupup time and venue time
public function slot_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $nameOfDay =date('l', strtotime($date));
         if(date('Y-m-d')==$date){
         $data=$this->myvenue_model->get_slot($court_id,$nameOfDay);  
         }else{
   $data=$this->myvenue_model->get_slots($court_id,$nameOfDay);
         }
     
   return $this->response($data,200);
         
  }

//all slot values of upupup time 
public function upupupslot_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $nameOfDay =date('l', strtotime($date));
         $data=$this->myvenue_model->get_upupupslot($court_id,$nameOfDay);
   return $this->response($data,200);
         
  }

//all slot values of  venue time
public function venueslot_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $nameOfDay =date('l', strtotime($date));
         $data=$this->myvenue_model->get_venueslot($court_id,$nameOfDay);
   return $this->response($data,200);
         
  }
//all slot values of  venue time
public function openslot_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $nameOfDay =date('l', strtotime($date));
         $data=$this->myvenue_model->get_openslot($court_id,$nameOfDay,$date);
   return $this->response($data,200);
         
  }
//all slot values of  member time
public function memberslot_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $nameOfDay =date('l', strtotime($date));
         $data=$this->myvenue_model->get_memberslot($court_id,$nameOfDay);
   return $this->response($data,200);
         
  }

//court offer details on selected date
public function offer_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $nameOfDay =date('D', strtotime($date));
         $data=$this->myvenue_model->get_offer($court_id,$date,$nameOfDay);
   return $this->response($data,200);
         
  }

//court upupup booking details on selected date
public function upupupbooking_post(){
     $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
   $vendor="vendor";
   $data=$this->myvenue_model->get_upupupbooking($venue_id,$sports_id,$court_id,$date,$vendor);
    
   return $this->response($data,200);
         
  }

//court venue booking details on selected date
public function venuebooking_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
   $vendor="vendor";
         $data=$this->myvenue_model->get_venuebooking($venue_id,$sports_id,$court_id,$date,$vendor);
         
   return $this->response($data,200);
         
  }

//court inactive details on selected date
public function inactive_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
   $nameOfDay =date('D', strtotime($date));
   $data=$this->myvenue_model->get_inactive($venue_id,$court_id,$date,$nameOfDay);
   return $this->response($data,200);
         
  }
  
//////////////// for all court and sports details based on venue_id start ////////////////// 
  public function myvenue_courts_post(){
    $venue_id=$this->input->post('venue_id');
    $user_id=$this->input->post('user_id');
    //$sports=$this->myvenue_model->get_my_sports($venue_id);
    $user_role=$this->input->post('user_role');
    $role="Court Manager";
         if($user_role!=$role){
          $sports=$this->myvenue_model->get_sportsdetails($venue_id);
        }else{
          $sports=$this->myvenue_model->get_sportslist($user_id);
        }
    for ($m = 0; $m < count($sports); $m++) {
      $sports_id=(int)$sports[$m]->id;
      $sports_name=$sports[$m]->sports;
      $sports_image=$sports[$m]->image;
      $venue_data[]=array(
          'sports_id'=>(int)$sports_id,
          'sports_name'=>$sports_name,
          'sports_image'=>$sports_image
          );
      //$court=$this->myvenue_model->get_my_courts($venue_id,$sports_id);
      if($user_role!=$role){
          $court=$this->myvenue_model->get_courtlist($venue_id,$sports_id);
        }else{
          $court=$this->myvenue_model->get_courtlists($user_id,$sports_id);
        }
      for ($n = 0; $n < count($court); $n++) {
          $court_id=$court[$n]->court_id;
          $court_name=$court[$n]->court;
          $court_cost=$court[$n]->cost;
          $court_data=[];
          $court_data=array(
            'id'=>$court_id,
            'name'=>$court_name,
            'cost'=>$court_cost
            );
          $venue_data[$m]['courtList'][$n]=$court_data;
         }
    }
    
      if(!empty($venue_data)){
          $result=array(
                'errorCode'=>1,
                'data'=>$venue_data,
                'message'=>"success"
              );
          }else{
          $result=array(
                'errorCode'=>0,
                'data'=>[],
                'message'=>"no_sports"
              );
          }
    return $this->response($result,200);
  }
//////////////// for all court and sports details based on venue_id end //////////////////

////////////slot details based on venue_id,court_id,sports_id,user_id start ///////////////// 


////////////slot details based on venue_id,court_id,sports_id,user_id end ///////////////// 

//check booking on slection of slot for booking
public function booking_post(){
         $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $date=$this->input->post('date');
         $time=$this->input->post('slot_time');
                  $datas=$this->myvenue_model->get_booking($venue_id,$sports_id,$court_id,$date,$time);
         foreach($datas as $row) {
   $capsum = $row->capacity;
}
          $tempcap=$this->myvenue_model->get_tempbooking($court_id,$date,$time);
          foreach($tempcap as $row) {
           $tempcapa = $row->tempsum;
          }
    $capacity=$capsum+$tempcapa;
    //////////// no booking in court_book and venue_book 
  if($capacity!=0){ 
             return $this->response($capacity,200);
          }else{
            
          $result="available";
            return $this->response($result,200);
          }  
         

         
  }

//booking slot from venue
public function vendorbooking_post(){
     $venue_id=$this->input->post('venue_id');
   $sports_id=$this->input->post('sports_id');
   $court_id=$this->input->post('court_id');
   $manager_id=$this->input->post('user_id');
   $bookingcost=$this->input->post('cost');
   ///////// court cost:price ////////////
   $price=$this->input->post('price');
   $date=$this->input->post('date');
   /////if offer exist then offer variable contains the offer_id ;else no_offer //////////
   $offer=$this->input->post('offer');
   /////0:percentage;1:amount;2:hot_offer;-1:no_offer //////////
   $offer_type=$this->input->post('offer_type');
   $offer_value=$this->input->post('offer_value');
   $time=$this->input->post('court_time');
   $capacity=$this->input->post('capacity');
   $total_capacity=$this->input->post('total_capacity');
   $remaining_capacity=$this->input->post('remaining_capacity');
   $name=$this->input->post('name');
   $phone=$this->input->post('phone');
   $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->myvenue_model->get_vendorusers($manager_id,$vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->myvenue_model->get_vendorcheck($manager_id,$vendor_phone); 
      foreach($vendor_mgrs as $row) {
             $vdr_name = $row->name;
             }
        if($vdr_name==""){
          $result="vendor_deleted";
    return $this->response($result,200);
        }else{
          $result="vendor_inactive";
    return $this->response($result,200);
        }
    }
      if($offer!="no_offer" && $offer_type==1){
        $value=$capacity*$offer_value;
      }else{
        if($offer!="no_offer" && $offer_type==0){
          $percentage="%";
          $value=$capacity*$price-$bookingcost;
        }else{
         if($offer!="no_offer" && $offer_type==2){
          $percentage="%";
          $value=$capacity*$price-$bookingcost;
        }else{
          $value=0;
        }
        }
      }

   $booking_id=  new DateTime();
   $booking_id=$venue_id+$booking_id->getTimestamp();
  

          $datas=$this->myvenue_model->get_booking($venue_id,$sports_id,$court_id,$date,$time);
          foreach($datas as $row) {
           $cap = $row->capacity;
          }
         $tempcap=$this->myvenue_model->get_tempbooking($court_id,$date,$time);
          foreach($tempcap as $row) {
           $tempcap = $row->tempsum;
          }
         $captotal=$cap+$tempcap;
         $tc=$captotal+$capacity;
          if($captotal==$total_capacity ){
            $result="booked";
            return $this->response($result,200);
          }else{
            if($tc>$total_capacity){
             $result="selected capacity not available";
            return $this->response($result,200);
            }else{

             /*user creation start*/
         $data=$this->myvenue_model->get_usercheck($phone);
          if($data){
                                       /////////////////////////////////////////////////////////////////////////////////
            foreach($data as $row) {
           $user_id = $row->id;
          }

        ////////////////////////////////////////////////////////////////////////////////////
          }else{
           $data=array(
      'phone_no'=>$phone,
      'status'=>'1',
      );
           $add=$this->myvenue_model->add_user('users',$data);
           $data=$this->myvenue_model->get_usercheck($phone);
           foreach($data as $row) {
           $user_id = $row->id;
          }
          /* area id selection based on venue id start */
        
         $data=$this->myvenue_model->get_area($venue_id);
         foreach($data as $row) {
           $area_id = $row->area_id;
          }
        /* area id selection based on venue id end */
         $areacounti=$this->myvenue_model->get_userareacount($user_id);
      foreach($areacounti as $row) {
           $area_counts = $row->area_count;
          }
      if($area_counts<4){
        /*add user area start*/
        $data=array(
      'user_id'=>$user_id,
      'area_id'=>$area_id,
      );
        $this->myvenue_model->add_user('user_area',$data);
        /*add user area end*/
      }
              /* check user sports count if less than 8 then only add user sports start */   
      $sportscounti=$this->myvenue_model->get_usersportscount($user_id);
      foreach($sportscounti as $row) {
           $sports_counts = $row->sports_count;
          }
      if($sports_counts<8){
        /*add user sports start*/
        $data=array(
      'user_id'=>$user_id,
      'sports_id'=>$sports_id,
      );
        $this->myvenue_model->add_user('user_sports',$data);
        /*add user sports end*/
      }
    /* check user sports count if less than 8 then only add user sports end */
           //return $this->response($user_id,200);
          }
        /*user creation end */ 

        

        /*add user channel start*/
        $channel=2;
        $data=array(
      'channel_id'=>$channel,
      'user_id'=>$user_id,
      );
        $this->myvenue_model->add_user('user_channel',$data);
        /*add user channel end*/

        $data=array(
      'user_id'=>$user_id,
      'sports_id'=>$sports_id,
      'date'=>$date,
      'court_id'=>$court_id,
      'venue_id'=>$venue_id,
      'cost'=>$bookingcost,
      'booking_id'=>$booking_id,
      'payment_mode'=>'1',
                        'payment_id'=>'vendor',
      'coupon_id'=>'0',
      'offer_value'=>$value,
      'price'=>$price,
      'bal'=>'0',
        'time'=>date('Y-m-d H:i:s'),
      );
        $add=$this->myvenue_model->add_booking('venue_booking',$data);
        
        /*add venue players start*/
        $data=array(
      'user_id'=>$user_id,
      'booking_id'=>$booking_id,
      'court_id'=>$court_id,
      'date'=>$date,
      );
        $this->myvenue_model->add_user('venue_players',$data);
        /*add venue players end*/
          
          /////// add hot offer details to booking_offer(normal booking ) start //////////
          if($offer!= "no_offer" && $offer_type !=2){
            $data=array(
      'booking_id'=>$booking_id,
      'offer_id'=>$offer,
      );
      $this->myvenue_model->add_booking('booking_offer',$data);
          }
/////// add hot offer details to booking_offer(normal booking ) end //////////
/////// add hot offer details to booking_hot_offer start //////////
          if($offer!= "no_offer" && $offer_type ==2){
            $data=array(
            'booking_id'=>$booking_id,
            'hot_offer_id'=>$offer,
            );
      $this->myvenue_model->add_booking('booking_hot_offer',$data);
          }
/////// add hot offer details to booking_hot_offer end //////////
          $data=array(
          'booking_id'=>$booking_id,
          'court_time'=>$time,
          'court_id'=>$court_id,
          'capacity'=>$capacity,
          'date'=>$date
        );
      $this->myvenue_model->add_booking('venue_booking_time',$data);
               $data=array(
          'booking_id'=>$booking_id,
          'user_id'=>$manager_id,
        );
      $this->myvenue_model->add_booking('booked_manager',$data);
      
      /*insertion response start*/
         if($add){
               $data=$this->myvenue_model->get_sports($sports_id);
           foreach($data as $row) {
             $sports_name = $row->sports;
             }
            $data=$this->myvenue_model->get_court($court_id);
           foreach($data as $row) {
             $court_name = $row->court;
             }
             $data=$this->myvenue_model->get_venue($venue_id);
           foreach($data as $row) {
             $venue_name = $row->venue;
             $ar_id = $row->area_id;
             }
             $data=$this->myvenue_model->get_venueownerph($venue_id);
           foreach($data as $row) {
             $vendor_phone = $row->phone;
             }
             $data=$this->myvenue_model->get_venuearea($ar_id);
           foreach($data as $row) {
             $area_name = $row->area;
             }
             $data=$this->myvenue_model->get_databookmg($manager_id);
           foreach($data as $row) {
             $bkmgr_name = $row->name;
             $bkmgr_role = $row->role_name;
             $bkmgr_phone = $row->phone;
             }
            $userdata=$this->myvenue_model->get_userdetails($user_id);
           foreach($userdata as $row) {
             $u_name = $row->name;

             if($u_name=="upupup user"){
              $user_name="";
             }else{
              $user_name=$u_name;
             }
             
             }
            $adfc=1;
            if($capacity!=$adfc){
             $message="".$bkmgr_role." has booked ".$sports_name." for you at ".$court_name." of  ".$venue_name.",".$area_name." on ".date('dS F  Y', strtotime($date)).", ".date('l',strtotime($date))." , ".date("g:i a", strtotime($time))." for ".$capacity." players [Booking ID :".$booking_id."]  Amount to be paid at venue is Rs.".$bookingcost."/- \r\n For more sports https://play.google.com/store/apps/details?id=com.planetpriorities.upupup";

          $messagev="You have booked for ".$phone.", ".$sports_name." at ".$court_name." of ".$venue_name.",".$area_name." on ".date('dS F  Y', strtotime($date)).", ".date('l',strtotime($date))." , ".date("g:i a", strtotime($time))." for ".$capacity." players [Booking ID :".$booking_id."]  Amount to be received is Rs.".$bookingcost."/-";
          $this->common->sms(str_replace(' ', '', $phone),urlencode($message));
          $this->common->sms(str_replace(' ', '', $vendor_phone),urlencode($messagev));
                
                  $data=$this->myvenue_model->get_upupupsms();
           foreach($data as $row) {
             $upup_phone = $row->phone;
             $messageu="".$bkmgr_role."(".$bkmgr_phone."/".$bkmgr_name.") has booked ".$sports_name." at ".$court_name." of ".$venue_name.",".$area_name." on ".date('dS F  Y', strtotime($date)).", ".date('l',strtotime($date))." , ".date("g:i a", strtotime($time))." for ".$user_name." ".$phone." [Booking ID :".$booking_id."]  Amount to be paid at venue is Rs.".$bookingcost."/-";
             $this->common->sms(str_replace(' ', '', $upup_phone),urlencode($messageu));
             }  
            }else{
            $message="".$bkmgr_role." has booked ".$sports_name." for you at ".$court_name." of  ".$venue_name.",".$area_name." on ".date('dS F  Y', strtotime($date)).", ".date('l',strtotime($date))." , ".date("g:i a", strtotime($time))." [Booking ID :".$booking_id."]  Amount to be paid at venue is Rs.".$bookingcost."/- \r\n For more sports https://play.google.com/store/apps/details?id=com.planetpriorities.upupup";

          $messagev="You have booked for ".$phone.", ".$sports_name." at ".$court_name." of ".$venue_name.",".$area_name." on ".date('dS F  Y', strtotime($date)).", ".date('l',strtotime($date))." , ".date("g:i a", strtotime($time))." [Booking ID :".$booking_id."]  Amount to be received is Rs.".$bookingcost."/-";
          $this->common->sms(str_replace(' ', '', $phone),urlencode($message));
          $this->common->sms(str_replace(' ', '', $vendor_phone),urlencode($messagev));
                
                  $data=$this->myvenue_model->get_upupupsms();
           foreach($data as $row) {
             $upup_phone = $row->phone;
             $messageu="".$bkmgr_role."(".$bkmgr_phone."/".$bkmgr_name.") has booked ".$sports_name." at ".$court_name." of ".$venue_name.",".$area_name." on ".date('dS F  Y', strtotime($date)).", ".date('l',strtotime($date))." , ".date("g:i a", strtotime($time))." for ".$user_name." ".$phone." [Booking ID :".$booking_id."]  Amount to be paid at venue is Rs.".$bookingcost."/-";
             $this->common->sms(str_replace(' ', '', $upup_phone),urlencode($messageu));
             }  
            }
            $booked_user_details=$this->myvenue_model->users_bookdata($user_id);
            $title ="New Venue Booked from vendor";
      $data_push =array('result' => array('message'=> $message,
                                     'title'  => $title,
                                     'type'   => 6),
                                     'status'=> "true",
                                     'type'  => "GENERAL",
                                     'venue_id'=>$venue_id
                                                 );

      $notification= $this->notification->push_notification(array($booked_user_details),$message,$title,$data_push);
            $venue_image=$this->myvenue_model->get_venueimage($venue_id);
             foreach($venue_image as $row) {
              $image = $row->image;
             }
             if($offer!="no_offer"){
             $offername=$this->myvenue_model->get_offername($offer);
              foreach($offername as $row) {
              $offer_name = $row->offer;
             }  
             }else{
              $offer_name='';
             }

             /* email for user start*/ 
            $data=$this->myvenue_model->get_useremail($user_id);
             foreach($data as $row) {
              $user_email = $row->email;
             }
            if($user_email!=''){
             $offer_deduction=($price*$capacity)-$bookingcost;
             $data['data']=array(
                  'user_phone'=>$phone,
                  'user_name'=>$user_name,
                  'booking_date'=>$date,
                  'booking_id'=>$booking_id,
                  'court_time'=>$time,
                  'image'=>$image,
                  'sports'=>$sports_name,
                  'court_name'=>$court_name,
                  'venue_name'=>$venue_name,
                  'area'=>$area_name,
                  'offer_name'=>$offer_name,
                  'offer_value'=>$value,
                  'cost'=>$bookingcost,
                  'price'=>$price,
                  'offer_deduction'=>$offer_deduction,
                  );



          $to_email = $user_email;
          $subject='Venue Booked';
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
          $message = $this->load->view('vendor_booking_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
        /* email for user end */
        /* email for managers start */ 
        $vendoremail=$this->myvenue_model->get_vendoremail($venue_id);
        foreach($vendoremail as $row) {
              $vendor_email = $row->email;

              $offer_deduction=($price*$capacity)-$bookingcost;
             $data['data']=array(
                  'user_phone'=>$phone,
                  'user_name'=>$user_name,
                  'booking_date'=>$date,
                  'booking_id'=>$booking_id,
                  'court_time'=>$time,
                  'image'=>$image,
                  'sports'=>$sports_name,
                  'court_name'=>$court_name,
                  'venue_name'=>$venue_name,
                  'area'=>$area_name,
                  'offer_name'=>$offer_name,
                  'offer_value'=>$value,
                  'cost'=>$bookingcost,
                  'price'=>$price,
                  'offer_deduction'=>$offer_deduction,
                  );



          $to_email = $vendor_email;
          $subject='Venue Booked';
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
          $message = $this->load->view('vendor_bookingupup_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
             }
        /* email for managers end */ 
        /* email for upupup start */     
        $upupupemail=$this->myvenue_model->get_upupupemail();
         foreach($upupupemail as $row) {
              $upupup_email = $row->email;
              $offer_deduction=($price*$capacity)-$bookingcost;
             $data['data']=array(
                  'user_phone'=>$phone,
                  'user_name'=>$user_name,
                  'booking_date'=>$date,
                  'booking_id'=>$booking_id,
                  'court_time'=>$time,
                  'image'=>$image,
                  'sports'=>$sports_name,
                  'court_name'=>$court_name,
                  'venue_name'=>$venue_name,
                  'area'=>$area_name,
                  'offer_name'=>$offer_name,
                  'offer_value'=>$value,
                  'cost'=>$bookingcost,
                  'price'=>$price,
                  'offer_deduction'=>$offer_deduction,
                  );



          $to_email = $upupup_email;
          $subject='Venue Booked';
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
          $message = $this->load->view('vendor_bookingupup_mail',$data,true);
          $this->email->message($message);
          $this->email->send();

             }
        /* email for upupup end */    
          
          $result="success";
          return $this->response($result,200);
         }else{
          $result="error";
          return $this->response($result,200);
         }
            /*insertion response end*/
            }
          }      
         
  }
//check booking on slection of slot for booking
public function slotopen_post(){
   $court_id=$this->input->post('court_id');
   $slot_id=$this->input->post('slot_id');
   $date=$this->input->post('date');
     $time=$this->input->post('slot_time');
     $venue_id=$this->input->post('venue_id');
     $manager_id=$this->input->post('user_id');
     $nameOfDay =date('l', strtotime($date));
     /*everyday value is zero 0 for selected date otherwise all*/
     $everyday=$this->input->post('everyday');
      $vendor_phone=$this->input->post('vendor_phone');
   $vendor_mgr=$this->myvenue_model->get_vendorusers($manager_id,$vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->myvenue_model->get_vendorcheck($manager_id,$vendor_phone); 
      foreach($vendor_mgrs as $row) {
             $vdr_name = $row->name;
             }
        if($vdr_name==""){
          $result="vendor_deleted";
    return $this->response($result,200);
        }else{
          $result="vendor_inactive";
    return $this->response($result,200);
        }
    }
     if($everyday==0){
      /*upupup slot value is 0*/
      $slotfor=0;
      $data=array(
      'court_id'=>$court_id,
      'week'=>$nameOfDay,
      'slotfor'=>$slotfor,
      );
        $court_time_id=$this->myvenue_model->add_time('court_time',$data);  

        $data=array(
              'court_time_id'=>$court_time_id,
              'court_id'=>$court_id,
              'time'=>$time,
              'date'=>$date,
              'added_date'=>date('Y-m-d H:i:s')
              );
        $this->myvenue_model->add_time('court_time_intervel',$data);
        /* fetching details of venue */
      $venue_details=$this->myvenue_model->get_venuedetails($venue_id);
      foreach($venue_details as $row) {
             $venue_name = $row->venue;
             }
       /* fetching details of venue */
      $court_details=$this->myvenue_model->get_courtdetails($court_id);
      foreach($court_details as $row) {
             $court_name = $row->court;
             $sports_name = $row->sports;
             }

      /* fetching details of manager*/ 
      $mgr_details=$this->myvenue_model->get_managerdata($manager_id);
           foreach($mgr_details as $row) {
             $mgr_name = $row->name;
             $mgr_email = $row->email;
             $mgr_phone = $row->phone;
             $mgr_role = $row->role_name;
             }
      /* fetching details of venue owner */
      $owner_details=$this->myvenue_model->get_ownerdata($venue_id);
           foreach($owner_details as $row) {
             $owner_name = $row->name;
             $owner_email = $row->email;
             $owner_phone = $row->phone;

        $data['data']=array(
              'owner_name'=>$owner_name,
              'role_name'=>$mgr_role,
              'mgr_name'=>$mgr_name,
              'mgr_phone'=>$mgr_phone,
              'venue_name'=>$venue_name,
              'day_name'=>$nameOfDay,
              'court'=>$court_name,
              'sports'=>$sports_name,
              'open_time'=>$time,
              'date'=>$date,
        
              );
          $to_email = $owner_email;
          $subject='Slot Open for a Single day';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($this->load->view('slotopen_singleve_mail',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);

             }
                $upupupemail=$this->myvenue_model->get_upunbkemail();
          foreach($upupupemail as $row) {
             $upupup_email = $row->email;
             $data['data']=array(
                  'owner_name'=>$owner_name,
                  'role_name'=>$mgr_role,
                  'mgr_name'=>$mgr_name,
                  'mgr_phone'=>$mgr_phone,
                  'venue_name'=>$venue_name,
                  'day_name'=>$nameOfDay,
                  'court'=>$court_name,
                  'sports'=>$sports_name,
                  'open_time'=>$time,
                  'date'=>$date,
            
                  );
          $to_email = $upupup_email;
          $subject='Slot Open for a Single day';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($this->load->view('slotopen_singleup_mail',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
             }
        $result="success";
        return $this->response($result,200);  
     }else{
      $this->myvenue_model->delete_slot_time($slot_id);
      $slotfor=0;
        $data=array(
      'court_id'=>$court_id,
      'week'=>$nameOfDay,
      'slotfor'=>$slotfor,
      );
        $court_time_id=$this->myvenue_model->add_time('court_time',$data);  
        $da=1;
        $data=array(
              'court_time_id'=>$court_time_id,
              'court_id'=>$court_id,
              'time'=>$time,
              'date'=>$da,
              'added_date'=>date('Y-m-d H:i:s')
              );
        $this->myvenue_model->add_time('court_time_intervel',$data);
        /* fetching details of venue */
      $venue_details=$this->myvenue_model->get_venuedetails($venue_id);
      foreach($venue_details as $row) {
             $venue_name = $row->venue;
             }
       /* fetching details of venue */
      $court_details=$this->myvenue_model->get_courtdetails($court_id);
      foreach($court_details as $row) {
             $court_name = $row->court;
             $sports_name = $row->sports;
             }

      /* fetching details of manager*/ 
      $mgr_details=$this->myvenue_model->get_managerdata($manager_id);
           foreach($mgr_details as $row) {
             $mgr_name = $row->name;
             $mgr_email = $row->email;
             $mgr_phone = $row->phone;
             $mgr_role = $row->role_name;
             }
      /* fetching details of venue owner */
      $owner_details=$this->myvenue_model->get_ownerdata($venue_id);
           foreach($owner_details as $row) {
             $owner_name = $row->name;
             $owner_email = $row->email;
             $owner_phone = $row->phone;

        $data['data']=array(
              'owner_name'=>$owner_name,
              'role_name'=>$mgr_role,
              'mgr_name'=>$mgr_name,
              'mgr_phone'=>$mgr_phone,
              'venue_name'=>$venue_name,
              'day_name'=>$nameOfDay,
              'court'=>$court_name,
              'sports'=>$sports_name,
              'open_time'=>$time,
              'date'=>$date,
        
              );
          $to_email = $owner_email;
          $subject='Slot Open for all '.$nameOfDay;
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($this->load->view('slotopen_owner_mail',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);

             }
                $upupupemail=$this->myvenue_model->get_upunbkemail();
          foreach($upupupemail as $row) {
             $upupup_email = $row->email;
             $data['data']=array(
                  'owner_name'=>$owner_name,
                  'role_name'=>$mgr_role,
                  'mgr_name'=>$mgr_name,
                  'mgr_phone'=>$mgr_phone,
                  'venue_name'=>$venue_name,
                  'day_name'=>$nameOfDay,
                  'court'=>$court_name,
                  'sports'=>$sports_name,
                  'open_time'=>$time,
                  'date'=>$date,
            
                  );
          $to_email = $upupup_email;
          $subject='Slot Open for all '.$nameOfDay;
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($this->load->view('slotopen_upup_mail',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
             }
        $result="success";
        return $this->response($result,200);  
     }



}
//cancel vendors booking from vendor app
public function cancelbooking_post(){
  $booking_id=$this->input->post('booking_id');
  $user_id=$this->input->post('user_id');
  $payment_mode=3;
   $data=array(
          'booking_id'=>$booking_id,
          'user_id'=>$user_id,
          'cancel_date'=>date('Y-m-d H:i:s'),
        );
  $cancel=$this->dashboard_model->add_cancelbooking('booking_cancel',$data);
  $cancelbk=$this->dashboard_model->update_cancelbook($payment_mode,$booking_id);

 

  if($cancelbk){
  $manager_role=$this->dashboard_model->get_managerrole($user_id);
  foreach($manager_role as $row) {
           $role_name = $row->name;
       }
  $books=$this->dashboard_model->get_bookdata($booking_id);
  foreach($books as $row) {
           $user_name = $row->user_name;
           $user_phone = $row->phone_no;
           $user_email = $row->user_email;
           $venue_name = $row->venue;
           $venue_id = $row->venue_id;
           $area = $row->area;
           $booking_date = $row->date;
           $booking_time = $row->court_time;
           $sports = $row->sports;
           $court = $row->court;
       }
    
    $message=$venue_name." ".$area." has cancelled your booking of ".date('dS F  Y', strtotime($booking_date))." ,".date('l',strtotime($booking_date))." ,".date("g:i a", strtotime($booking_time)).". [Booking ID: ".$booking_id."] for ".$sports." in ".$court." ";
    $this->common->sms(str_replace(' ', '',$user_phone),urlencode($message));
/* email for upupup start */  
        $upupupemail=$this->dashboard_model->get_upupupemail();
             foreach($upupupemail as $row) {
        $upupup_email = $row->email;
        if($upupup_email!=''){
        $data['data']=array(
              'role'=>$role_name,
              'venue_name'=>$venue_name,
              'area'=>$area,
              'booking_date'=>date('dS F  Y', strtotime($booking_date)),
              'booking_time'=>date("g:i a", strtotime($booking_time)),
              'booking_day'=>date('l',strtotime($booking_date)),
              'booking_id'=>$booking_id,
              'court'=>$court,
              'sports'=>$sports,
              'user_name'=>$user_name,
              'user_phone'=>$user_phone,
              'ow_name'=>$ow_name,
              'ow_phone'=>$ow_phone,
        
              );
          $to_email = $upupup_email;
          $subject='Booking cancelled !!';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($this->load->view('cancel_mailup',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
              
            }
       }
        /* email for upupup end */  
        /* email for venue owner start */  
        
       $owner_data=$this->dashboard_model->get_ownerdata($venue_id);
      foreach($owner_data as $row) {
             $ow_name = $row->name;
             $ow_email = $row->email;
             $ow_phone = $row->phone;
             
             $data['data']=array(
                  'role'=>$role_name,
                  'venue_name'=>$venue_name,
                  'area'=>$area,
                  'booking_date'=>date('dS F  Y', strtotime($booking_date)),
                  'booking_time'=>date("g:i a", strtotime($booking_time)),
                  'booking_day'=>date('l',strtotime($booking_date)),
                  'booking_id'=>$booking_id,
                  'court'=>$court,
                  'sports'=>$sports,
                  'user_name'=>$user_name,
                  'user_phone'=>$user_phone,
                  'ow_name'=>$ow_name,
                  'ow_phone'=>$ow_phone,
            
                  );
          $to_email = $ow_email;
          $subject='Booking cancelled !!';
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
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($this->load->view('cancel_mailow',$data,true));
          $this->email->send();
          $this->email->clear(TRUE);
      }
      
      /* email for venue owner end */  

    $result="success";
    return $this->response($result,200);
  }else{
    $result="error";
    return $this->response($result,200);
  }
}


///////////////////// slot details start /////////////////////////////////////////////////
public function myslot_post(){
    $user_id=$this->input->post('user_id');
    $court_id=$this->input->post('court_id');
    $sports_id=$this->input->post('sports_id');     
    $date=$this->input->post('date');
    $nameOfDay =date('l', strtotime($date));
    $holiday=$this->myvenue_model->get_mycourt_holiday($court_id,$date);

    if(!empty($holiday)){
                           
                  $result=array(
                    'errorCode'=>0,
                    'data'=>[],
                    'message'=>"Holiday"
                 );
              return $this->response($result,200);
            }else{
    //////////////////////////// slot details  start //////////////////////////////// 
    $court[court]=$this->myvenue_model->get_myslot($court_id,$nameOfDay,$date);
          foreach($court[court] as $key => $value) {
            $slot_id=$value->slot_id;
            $slotfor=$value->slotfor;
            $courts_id=(int)$value->courts_id;
            $slot_time=$value->time;
            $court_cost=$value->court_cost;
            $single_slot=$this->myvenue_model->get_mysingle_day($court_id,$nameOfDay,$date,$slot_time);
            if(!empty($single_slot)){
            foreach($single_slot as $key5 => $value5) {
              $slot_id=$value5->slot_id;
              $slotfor=$value5->slotfor;
            }
            }
            $booking=$this->myvenue_model->get_mybookings($court_id,$slot_time,$date);
            foreach($booking as $key1 => $value1) {
              $capacity=(int)$value1->capacity; 
            }
            $temp_booking=$this->myvenue_model->get_mytemp_bookings($court_id,$slot_time,$date);
            foreach($temp_booking as $key2 => $value2) {
              $temp_capacity=(int)$value2->tempsum; 
            }
            $booked_capacity=$capacity+$temp_capacity;
            $courts_capacity=$this->myvenue_model->get_mycourt_capacity($court_id);
            foreach($courts_capacity as $key3 => $value3) {
              $court_capacity=(int)$value3->total_capacity; 
            }
            $remaining_capacity=$court_capacity-$booked_capacity;
            if($booked_capacity!=0){
            $vendor="vendor";
            $upupup_booking=$this->myvenue_model->get_myupupup_bookings($court_id,$sports_id,$slot_time,$date,$vendor);
            if(!empty($upupup_booking)){
              foreach($upupup_booking as $key6 => $value6) {
              $upupup_cap=(int)$value6->upupup_cap; 
            }  
            }else{
               $upupup_cap=0; 
            }
            $vendor_booking=$this->myvenue_model->get_myvendor_bookings($court_id,$sports_id,$slot_time,$date,$vendor);
            foreach($vendor_booking as $key7 => $value7) {
              $vendor_cap=(int)$value7->vendor_cap; 
            }

            $upupup_booking=$upupup_cap;
            $vendor_booking=$vendor_cap;
            }else{
            
            $upupup_booking=0;
            $vendor_booking=0;  
            }
            $hot_offer=$this->myvenue_model->get_myhot_slot($court_id,$slot_id,$date);
            if(!empty($hot_offer)){
            foreach($hot_offer as $key4 => $value4) {
              $hot_id=(int)$value4->id;
              $hot_percentage=(int)$value4->precentage; 
            }
            $has_offer=True;
            $offer_type=2;
            $offer_value=(int)$hot_percentage;
            $offer_id=(int)$hot_id;

            }else{
            $dayname =date('D', strtotime($date));
            //return $this->response($dayname,200);
            $normal_offer=$this->myvenue_model->get_mynormal_offer($court_id,$slot_time,$date,$dayname);
            //////// if normal offer exist start ////////////
            if(!empty($normal_offer)){

              foreach($normal_offer as $key8 => $value8) {
                $off_id=(int)$value8->id;
                $off_amount=(int)$value8->amount;
                $off_percentage=(int)$value8->percentage; 
              }

                if($off_amount!=0){
                  $has_offer=True;
                  $offer_type=1;
                  $offer_value=(int)$off_amount;
                  $offer_id=(int)$off_id; 
                }else{
                  if($off_percentage!=0){
                  $has_offer=True;
                  $offer_type=0;
                  $offer_value=(int)$off_percentage;
                  $offer_id=(int)$off_id; 
                } 
                }
            }else{

                  $has_offer=False;
                  $offer_type=-1;
                  $offer_value=-1;
                  $offer_id=-1;


            }
            //////// if normal offer exist end ////////////
            }
            $dayname =date('D', strtotime($date));
            $inactive=$this->myvenue_model->get_myinactive_court($court_id,$slot_time,$date,$dayname);
            if(empty($inactive)){
            $slots[]=array(
              'id'=>$slot_id,
              'time'=>$value->time,
              'slotFor'=>(int)$slotfor,
              'date'=>"",
              'day'=>"",
              'status'=>"",
              'court'=>null,
              'sports'=>null,
              'venue'=>null,
              'slot_cost'=>(int)$court_cost,
              'slot_calculated_cost'=>0,
              'total_capacity'=>(int)$court_capacity,
              'remaining_capacity'=>(int)$remaining_capacity,
              'upupup_booking'=>(int)$upupup_booking,
              'vendor_booking'=>(int)$vendor_booking,
              'has_offer'=>$has_offer,
              'offer_type'=>$offer_type,
              'offer_value'=>$offer_value,
              'offer_id'=>$offer_id,
              );
            
            $cou=$slots;
            }
            
            
          }

          if(!empty($cou)){
          $result=array(
                'errorCode'=>1,
                'data'=>$cou,
                'message'=>"Success"
              );
          }else{
          $result=array(
                'errorCode'=>0,
                'data'=>[],
                'message'=>"No_available_slots"
              );
          }
          
          return $this->response($result,200);
    //////////////////////////// slot details  end ////////////////////////////////         
            } 
  }
///////////////////// slot details end /////////////////////////////////////////////////
 
 
///////////////////// slot details start /////////////////////////////////////////////////
public function myslot_demo_get(){
    $user_id=563;
    $court_id=829;
    $sports_id=116;     
    $date=date('Y-m-d');
    $nameOfDay =date('l', strtotime($date));
    $holiday=$this->myvenue_model->get_mycourt_holiday($court_id,$date);

    if(!empty($holiday)){
                           
                  $result=array(
                    'errorCode'=>0,
                    'data'=>[],
                    'message'=>"Holiday"
                 );
              return $this->response($result,200);
            }else{
    //////////////////////////// slot details  start //////////////////////////////// 
    $court[court]=$this->myvenue_model->get_myslot($court_id,$nameOfDay,$date);
          foreach($court[court] as $key => $value) {
            $slot_id=$value->slot_id;
            $slotfor=$value->slotfor;
            $courts_id=(int)$value->courts_id;
            $slot_time=$value->time;
            $court_cost=$value->court_cost;
            $single_slot=$this->myvenue_model->get_mysingle_day($court_id,$nameOfDay,$date,$slot_time);
            if(!empty($single_slot)){
            foreach($single_slot as $key5 => $value5) {
              $slot_id=$value5->slot_id;
              $slotfor=$value5->slotfor;
            }
            }
            $booking=$this->myvenue_model->get_mybookings($court_id,$slot_time,$date);
            foreach($booking as $key1 => $value1) {
              $capacity=(int)$value1->capacity; 
            }
            $temp_booking=$this->myvenue_model->get_mytemp_bookings($court_id,$slot_time,$date);
            foreach($temp_booking as $key2 => $value2) {
              $temp_capacity=(int)$value2->tempsum; 
            }
            $booked_capacity=$capacity+$temp_capacity;
            $courts_capacity=$this->myvenue_model->get_mycourt_capacity($court_id);
            foreach($courts_capacity as $key3 => $value3) {
              $court_capacity=(int)$value3->total_capacity; 
            }
            $remaining_capacity=$court_capacity-$booked_capacity;
            if($booked_capacity!=0){
            $vendor="vendor";
            $upupup_booking=$this->myvenue_model->get_myupupup_bookings($court_id,$sports_id,$slot_time,$date,$vendor);
            //echo "<pre>";print_r($upupup_booking);
            if(!empty($upupup_booking)){
              foreach($upupup_booking as $key6 => $value6) {
              $upupup_cap=(int)$value6->upupup_cap; 
            }  
            }else{
               $upupup_cap=0; 
            }
            
            $vendor_booking=$this->myvenue_model->get_myvendor_bookings($court_id,$sports_id,$slot_time,$date,$vendor);
            foreach($vendor_booking as $key7 => $value7) {
              $vendor_cap=(int)$value7->vendor_cap; 
            }

            $upupup_booking=$upupup_cap;
            $vendor_booking=$vendor_cap;
            }else{
            
            $upupup_booking=0;
            $vendor_booking=0;  
            }
            $hot_offer=$this->myvenue_model->get_myhot_slot($court_id,$slot_id,$date);
            if(!empty($hot_offer)){
            foreach($hot_offer as $key4 => $value4) {
              $hot_id=(int)$value4->id;
              $hot_percentage=(int)$value4->precentage; 
            }
            $has_offer=True;
            $offer_type=2;
            $offer_value=(int)$hot_percentage;
            $offer_id=(int)$hot_id;

            }else{
            $dayname =date('D', strtotime($date));
            //return $this->response($dayname,200);
            $normal_offer=$this->myvenue_model->get_mynormal_offer($court_id,$slot_time,$date,$dayname);
            //////// if normal offer exist start ////////////
            if(!empty($normal_offer)){

              foreach($normal_offer as $key8 => $value8) {
                $off_id=(int)$value8->id;
                $off_amount=(int)$value8->amount;
                $off_percentage=(int)$value8->percentage; 
              }

                if($off_amount!=0){
                  $has_offer=True;
                  $offer_type=1;
                  $offer_value=(int)$off_amount;
                  $offer_id=(int)$off_id; 
                }else{
                  if($off_percentage!=0){
                  $has_offer=True;
                  $offer_type=0;
                  $offer_value=(int)$off_percentage;
                  $offer_id=(int)$off_id; 
                } 
                }
            }else{

                  $has_offer=False;
                  $offer_type=-1;
                  $offer_value=-1;
                  $offer_id=-1;


            }
            //////// if normal offer exist end ////////////
            }
            $dayname =date('D', strtotime($date));
            $inactive=$this->myvenue_model->get_myinactive_court($court_id,$slot_time,$date,$dayname);
            if(empty($inactive)){
            $slots[]=array(
              'id'=>$slot_id,
              'time'=>$value->time,
              'slotFor'=>(int)$slotfor,
              'date'=>"",
              'day'=>"",
              'status'=>"",
              'court'=>null,
              'sports'=>null,
              'venue'=>null,
              'slot_cost'=>(int)$court_cost,
              'slot_calculated_cost'=>0,
              'total_capacity'=>(int)$court_capacity,
              'remaining_capacity'=>(int)$remaining_capacity,
              'upupup_booking'=>(int)$upupup_booking,
              'vendor_booking'=>(int)$vendor_booking,
              'has_offer'=>$has_offer,
              'offer_type'=>$offer_type,
              'offer_value'=>$offer_value,
              'offer_id'=>$offer_id,
              );
            
            $cou=$slots;
            }
            
            
          }

          if(!empty($cou)){
          $result=array(
                'errorCode'=>1,
                'data'=>$cou,
                'message'=>"Success"
              );
          }else{
          $result=array(
                'errorCode'=>0,
                'data'=>[],
                'message'=>"No_available_slots"
              );
          }
          
          return $this->response($result,200);
    //////////////////////////// slot details  end ////////////////////////////////         
            } 
  }
///////////////////// slot details end /////////////////////////////////////////////////
  
}


















