<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Offer extends REST_Controller {

  function __construct()
  {

    parent::__construct();

    // Configure limits on our controller methods
    // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
    $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
    $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    $this->load->model("api/offer_model");
    date_default_timezone_set("Asia/Kolkata");

  }
//insert datas off offer
public function index_post(){
    $offer=$this->input->post('offer');
      $count=$this->input->post('count');
      $offer_name=$this->input->post('offer_name');
      $offer_value=$this->input->post('offer_value');
      $offer_type=$this->input->post('offer_type');
      $vendor_phone=$this->input->post('vendor_phone');
      //return $this->response($offer,200);
      //check that offer value type is rupees or percentage
      $json_output = json_decode($offer,TRUE );
       for($of=0;$of<$count;$of++){
        $manager_id=$json_output[$of][user_id];
      }
      //return $this->response($manager_id,200);
      
      //return $this->response($vendor_phone,200);
   $vendor_mgr=$this->offer_model->get_vendorusers($manager_id,$vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->offer_model->get_vendorcheck($manager_id,$vendor_phone); 
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
      $sunda=0;
      $monda=0;
      $tuesda=0;
      $wednesda=0;
      $thursda=0;
      $frida=0;
      $saturda=0;
      $missmatch=0;
      

      $price=0;
      $precentage=0;
      if($offer_type=="rupees"){
    //////////// offer value is amount start ///////////////    
        $price=$offer_value;
        $exceed=0;
      ////////// Checking offer amount apply courts value is grater than or equal to 15 start/////////////////
      for($u=0;$u<$count;$u++){
    $court_id=$json_output[$u][court];
    $court_cost=$json_output[$u][cost];
    $court_names=$json_output[$u][court_name];
    $balance_amt=$court_cost-$offer_value;
    if($balance_amt < 15){
         $exceed=1;
         $court_nume=$court_names;
    }else{

    //////// else case start ////////
      if($exceed==1){
      $exceed=1;  
      }else{
        $exceed=0;  
      }
    //////// else case end ////////
    }
      }
      /////////////////Checking offer amount apply courts value is grater than or equal to 15 end/////////////////
      //////////// offer value is amount start /////////////// 
      }else{
        $precentage=$offer_value;
        $exceed=0;
      ////////// Checking offer amount apply courts value is grater than or equal to 15 start/////////////////
      for($u=0;$u<$count;$u++){
    $court_id=$json_output[$u][court];
    $court_cost=$json_output[$u][cost];
    $court_names=$json_output[$u][court_name];
    $tot_amt=$court_cost*($offer_value/100);
    $balance_amt=$court_cost-$tot_amt;
    if($balance_amt < 15){
         $exceed=1;
         $court_nume=$court_names;
    }else{

    //////// else case start ////////
      if($exceed==1){
      $exceed=1;  
      }else{
        $exceed=0;  
      }
    //////// else case end ////////
    }
      }
      /////////////////Checking offer amount apply courts value is grater than or equal to 15 end/////////////////
      }
      if($exceed==1){
        if($offer_type=="rupees"){
          $result=$court_nume." price can't be less than Rs.15/- . Kindly change the offer percentage ";
      return $this->response($result,200);
        }else{
          $result=$court_nume." price can't be less than Rs.15/- . Kindly change the offer percentage ";
      return $this->response($result,200);
        }
        
      }else{
        //start for loop1
      for($i=0;$i<$count;$i++){
        $user_id=$json_output[$i][user_id];
    $venue_id=$json_output[$i][venue_id];
    $court_id=$json_output[$i][court];
    $sdate=$json_output[$i][from_date];
    $edate=$json_output[$i][to_date];
    $stime=$json_output[$i][from_time];
    $etime=$json_output[$i][to_time];
    $sun=$json_output[$i][sun];
    $mon=$json_output[$i][mon];
    $tue=$json_output[$i][tue];
    $wed=$json_output[$i][wed];
    $thu=$json_output[$i][thu];
    $fri=$json_output[$i][fri];
    $sat=$json_output[$i][sat];
    $name=$json_output[$i][name];
    $email=$json_output[$i][email];
    $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
        $timedifference=$etime-$stime;
        
        if($count==0 || $datedifference==0){
         $totallooping=1*$datedifference+1*$count;  
     }else{
      $totallooping=($datedifference+1)*$count; 
     }
//return $this->response($totallooping,200);
        $sunday="Sun";
        $monday="Mon";
        $tuesday="Tue";
        $wednesday="Wed";
        $thursday="Thu";
        $friday="Fri";
        $saturday="Sat";
        $statu=1;


    //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
     

 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){

        //for loop3
        for($k=0;$k<$timedifference;$k++)
        {
        $time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
        $checkoffer=$this->offer_model->get_offeradded($venue_id,$court_id,$date,$time,$nameOfDay);   
        if($checkoffer)
              {
                 $alreadyadded=1;
        }else{
          if($alreadyadded==1){
            $alreadyadded=1;
          }else{
            $alreadyadded=0;
          }
          
          }
      }//end of for loop3 
      }else{
        $missmatch=$missmatch+1;
      } 

      
      if($sun!=0 && $sunday==$nameOfDay){
        $sunda=1;
      }elseif ($mon!=0 && $monday==$nameOfDay) {
        $monda=1;
      }elseif ($tue!=0 && $tuesday==$nameOfDay) {
        $tuesda=1;
      }elseif ($wed!=0 && $wednesday==$nameOfDay) {
        $wednesda=1;
      }elseif ($thu!=0 && $thursday==$nameOfDay) {
        $thursda=1;
      }elseif ($fri!=0 && $friday==$nameOfDay) {
        $frida=1;
      }elseif ($sat!=0 && $saturday==$nameOfDay) {
       $saturda=1;
      }else{
        
      }




            }//end of for loop2
        
      }//end of for loop1

    //if already offer added
//return $this->response($missmatch,200); 
     if($alreadyadded==1){
    $result="already added";
    return $this->response($result,200);
    }else{

    if($missmatch==$totallooping){
    $result="missmatch";
    return $this->response($result,200);
  }else{
    $data=array(
          'offer'=>$offer_name,
      'venue_id'=>$venue_id,
      'amount'=>$price,
      'percentage'=>$precentage,
      'start'=>$sdate,
      'end'=>$edate,
            'start_time'=>$stime,
      'end_time'=>$etime,
      'added_date'=>date('Y-m-d h:i:sa'),
      'status'=>$statu
      );
        //return $this->response($data,200);
        $adds=$this->offer_model->insert_offer($data);
        $offer_id=$adds;
        $manage_courtname=[];
    //start for loop1a
      for($i=0;$i<$count;$i++){
        $user_id=$json_output[$i][user_id];
    $venue_id=$json_output[$i][venue_id];
    $court_id=$json_output[$i][court];
    $sdate=$json_output[$i][from_date];
    $edate=$json_output[$i][to_date];
    $stime=$json_output[$i][from_time];
    $etime=$json_output[$i][to_time];
    $sun=$json_output[$i][sun];
    $mon=$json_output[$i][mon];
    $tue=$json_output[$i][tue];
    $wed=$json_output[$i][wed];
    $thu=$json_output[$i][thu];
    $fri=$json_output[$i][fri];
    $sat=$json_output[$i][sat];
    $name=$json_output[$i][name];
    $email=$json_output[$i][email];
    $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
        $timedifference=$etime-$stime;

        $sunday="Sun";
        $monday="Mon";
        $tuesday="Tue";
        $wednesday="Wed";
        $thursday="Thu";
        $friday="Fri";
        $saturday="Sat";
        

        $data=array(
          'court_id'=>$court_id,
      'offer_id'=>$offer_id
      );
        //return $this->response($data,200);
        $adds=$this->offer_model->insert_offercourt($data);

        
    //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
            $offerdayname = date('l',strtotime($date));

  $davidoff="";
 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){
        //for loop3
        for($k=0;$k<$timedifference;$k++)
        {
        $time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));  
        $datas=array(
              'offer_id'=>$offer_id,
              'time'=>$time,
              'day'=>$nameOfDay
              ); 
       $addtime=$this->offer_model->insert_offertime($datas);
       if($addtime){
        $offertime=1;
       }else{
        if($offertime==1){
          $offertime=1;
        }else{
          $offertime=0;
        }
       }
      }//end of for loop3
      $davidoff=$davidoff.$offerdayname;  
      }


            }//end of for loop2
            $court_sname=$this->offer_model->get_courtname($court_id);
            $manage_courtname = array_merge($manage_courtname, $court_sname);

      }//end of for loop1a
    }
    
  }
      
    

  if($offertime==1){
  $user_role=$this->offer_model->get_vendorrole($user_id);
  foreach($user_role as $row) {
           $role_name = $row->name;
       }
    $user_phone=$this->offer_model->get_userphone($user_id);
  foreach($user_phone as $row) {
           $vuser_phone = $row->phone;
           $vuser_name = $row->name;
       }
     $venue_sname=$this->offer_model->get_venuename($venue_id);     
     foreach($venue_sname as $row) {
           $venue_name = $row->venue;
       }
    $start_offdate=date('dS F Y', strtotime($sdate));
    $end_offdate=date('dS F Y', strtotime($edate));
    $start_offtime=date('h:i A',strtotime($stime));
    $end_offtime=date('h:i A', strtotime($etime));
    $kcu_courtname="";
    foreach($manage_courtname as $row) {
           $court_name = $row->court;
           $kcf_courtname=''.$kcu_courtname.''.$court_name.''.",".'';
           $kcu_courtname=$kcf_courtname;
       }
    /*email for upupup start*/ 
    $upupupemail=$this->offer_model->get_upupupemail();
    foreach($upupupemail as $row) {
        $upupup_email = $row->email;
        if($upupup_email!=''){
        $data['data']=array(
             'role'=>$role_name,
             'phone'=>$vuser_phone,
             'vowner'=>$vuser_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'start_date'=>$start_offdate,
             'end_date'=>$end_offdate,
             'start_time'=>$start_offtime,
             'end_time'=>$end_offtime,
             'sunday'=>$sunda,
             'monday'=>$monda,
             'tuesday'=>$tuesda,
             'wednesday'=>$wednesda,
             'thursday'=>$thursda,
             'friday'=>$frida,
             'saturday'=>$saturda,
             'offer_value'=>$offer_value,
             'offer_name'=>$offer_name,
             'offer_type'=>$offer_type,
             );
          $to_email = $upupup_email;
          $subject='New Offer Added';
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
          $message = $this->load->view('offer_upupup_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for upupup end */

    /*email for venue owner start */
    $owneremail=$this->offer_model->get_owner($venue_id);
    foreach($owneremail as $row) {
        $owner_email = $row->email;
        $owner_name = $row->name;
        if($owner_email!=''){
        $data['data']=array(
             'role'=>$role_name,
             'phone'=>$vuser_phone,
             'vowner'=>$vuser_name,
             'owner_name'=>$owner_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'start_date'=>$start_offdate,
             'end_date'=>$end_offdate,
             'start_time'=>$start_offtime,
             'end_time'=>$end_offtime,
             'sunday'=>$sunda,
             'monday'=>$monda,
             'tuesday'=>$tuesda,
             'wednesday'=>$wednesda,
             'thursday'=>$thursda,
             'friday'=>$frida,
             'saturday'=>$saturda,
             'offer_value'=>$offer_value,
             'offer_name'=>$offer_name,
             'offer_type'=>$offer_type,
             );
          $to_email = $owner_email;
          $subject='New Offer Added';
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
          $message = $this->load->view('offer_owner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for venue owner end */

  $result="insert";
  return $this->response($result,200);
}else{
  
  $result="error";
    return $this->response($result,200);    
}
      }

    

      

     
}

public function offerlist_post(){
         $venue_id=$this->input->post('venue_id');

      $data=$this->offer_model->get_offerlist($venue_id);

           if($data){

        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($data,200);
          }

  }
public function offerdetails_post(){
         $id=$this->input->post('id');

      $data=$this->offer_model->get_offerdetails($id);

           if($data){

        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($result,200);
          }

  }

public function offerdays_post(){
         $id=$this->input->post('id');

  $data=$this->offer_model->get_offerdays($id);

           if($data){

        return $this->response($data,200);
          }else{
            
          $result="not exist";
         return $this->response($result,200);
          }

  }
public function offerdelete_post(){
         $id=$this->input->post('id');
         $vendor_phone=$this->input->post('vendor_phone');
        $manager_id=$this->input->post('user_id');
        $vendor_mgr=$this->offer_model->get_vendorusers($manager_id,$vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->offer_model->get_vendorcheck($manager_id,$vendor_phone); 
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
	  $insert_data=array('status'=>0);
    $data=$this->offer_model->update_status($insert_data,'offer',$id);	
      
           if($data){

        $result="success";
         return $this->response($result,200);
          }else{
            
          $result="error";
         return $this->response($result,200);
          }

  }
//update datas off offer
public function offerchange_post(){
       $offer=$this->input->post('offer_edit');
      $count=$this->input->post('count');
      $offer_name=$this->input->post('offer_name');
      $offer_value=$this->input->post('offer_value');
      $offer_type=$this->input->post('offer_type');
      $offer_id=$this->input->post('offer_id');
      //return $this->response($offer,200);
            $json_output = json_decode($offer,TRUE );
      for($v=0;$v<$count;$v++){
        $manager_id=$json_output[$v][user_id];
      }
      $vendor_phone=$this->input->post('vendor_phone');
      $vendor_mgr=$this->offer_model->get_vendorusers($manager_id,$vendor_phone);
   foreach($vendor_mgr as $row) {
             $vendor_name = $row->name;
             }
    if($vendor_name==""){
     $vendor_mgrs=$this->offer_model->get_vendorcheck($manager_id,$vendor_phone); 
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
      //check that offer value type is rupees or percentage
      $price=0;
      $precentage=0;
      if($offer_type=="rupees"){
        $price=$offer_value;
      }else{
        $precentage=$offer_value;
      }
$missmatch=0;
      //start for loop1
      for($i=0;$i<$count;$i++){
        $user_id=$json_output[$i][user_id];
    $venue_id=$json_output[$i][venue_id];
    $court_id=$json_output[$i][court];
    $sdate=$json_output[$i][from_date];
    $edate=$json_output[$i][to_date];
    $stime=$json_output[$i][from_time];
    $etime=$json_output[$i][to_time];
    $sun=$json_output[$i][sun];
    $mon=$json_output[$i][mon];
    $tue=$json_output[$i][tue];
    $wed=$json_output[$i][wed];
    $thu=$json_output[$i][thu];
    $fri=$json_output[$i][fri];
    $sat=$json_output[$i][sat];
    $name=$json_output[$i][name];
    $email=$json_output[$i][email];
    $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
        $timedifference=$etime-$stime;
        if($count==0 || $datedifference==0){
         $totallooping=1*$datedifference+1*$count;  
     }else{
      $totallooping=($datedifference+1)*$count; 
     }
        $sunday="Sun";
        $monday="Mon";
        $tuesday="Tue";
        $wednesday="Wed";
        $thursday="Thu";
        $friday="Fri";
        $saturday="Sat";
        $statu=1;


    //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
     

 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){

        //for loop3
        for($k=0;$k<$timedifference;$k++)
        {
        $time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
        $checkoffer=$this->offer_model->get_offeraddedu($venue_id,$court_id,$date,$time,$nameOfDay,$offer_id);   
        if($checkoffer)
              {
                 $alreadyadded=1;
        }else{
          if($alreadyadded==1){
            $alreadyadded=1;
          }else{
            $alreadyadded=0;
          }
          
          }
      }//end of for loop3 
      }else{
        $missmatch=$missmatch+1;
      } 






            }//end of for loop2
        
      }//end of for loop1

    //if already offer added 
     if($alreadyadded==1){
    $result="already added";
    return $this->response($result,200);
    }else{

    if($missmatch==$totallooping){
    $result="missmatch";
    return $this->response($result,200);
  }else{

    $datas=$this->offer_model->delete_offercourtu($offer_id);
      $datast=$this->offer_model->delete_offertimeu($offer_id);
    $data=array(
          'offer'=>$offer_name,
      'venue_id'=>$venue_id,
      'amount'=>$price,
      'percentage'=>$precentage,
      'start'=>$sdate,
      'end'=>$edate,
            'start_time'=>$stime,
      'end_time'=>$etime,
      'added_date'=>date('Y-m-d h:i:sa'),
      'status'=>$statu
      );
      //return $this->response($data,200);
  $adds=$this->offer_model->update_offer($data,$offer_id);
    $manage_courtname=[];
    //start for loop1a
      for($i=0;$i<$count;$i++){
        $user_id=$json_output[$i][user_id];
    $venue_id=$json_output[$i][venue_id];
    $court_id=$json_output[$i][court];
    $sdate=$json_output[$i][from_date];
    $edate=$json_output[$i][to_date];
    $stime=$json_output[$i][from_time];
    $etime=$json_output[$i][to_time];
    $sun=$json_output[$i][sun];
    $mon=$json_output[$i][mon];
    $tue=$json_output[$i][tue];
    $wed=$json_output[$i][wed];
    $thu=$json_output[$i][thu];
    $fri=$json_output[$i][fri];
    $sat=$json_output[$i][sat];
    $name=$json_output[$i][name];
    $email=$json_output[$i][email];
    $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
        $timedifference=$etime-$stime;

        $sunday="Sun";
        $monday="Mon";
        $tuesday="Tue";
        $wednesday="Wed";
        $thursday="Thu";
        $friday="Fri";
        $saturday="Sat";
        

        $data=array(
          'court_id'=>$court_id,
      'offer_id'=>$offer_id
      );
        //return $this->response($data,200);
        $adds=$this->offer_model->insert_offercourt($data);

        
    //checks all date from start to end for loop2
        $davidoff="";
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
            $offerdayname = date('l',strtotime($date));

 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){
        //for loop3
        for($k=0;$k<$timedifference;$k++)
        {
        $time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));  
        $datas=array(
              'offer_id'=>$offer_id,
              'time'=>$time,
              'day'=>$nameOfDay
              ); 
       $addtime=$this->offer_model->insert_offertime($datas);
       if($addtime){
        $offertime=1;
       }else{
        if($offertime==1){
          $offertime=1;
        }else{
          $offertime=0;
        }
       }
      }//end of for loop3
       
       $davidoff=$davidoff.$offerdayname; 
      }
                  if($sun!=0 && $sunday==$nameOfDay){
                    $sunda=1;
                  }elseif ($mon!=0 && $monday==$nameOfDay) {
                    $monda=1;
                  }elseif ($tue!=0 && $tuesday==$nameOfDay) {
                    $tuesda=1;
                  }elseif ($wed!=0 && $wednesday==$nameOfDay) {
                    $wednesda=1;
                  }elseif ($thu!=0 && $thursday==$nameOfDay) {
                    $thursda=1;
                  }elseif ($fri!=0 && $friday==$nameOfDay) {
                    $frida=1;
                  }elseif ($sat!=0 && $saturday==$nameOfDay) {
                   $saturda=1;
                  }else{
                    
                  }

            }//end of for loop2
        
            $court_sname=$this->offer_model->get_courtname($court_id);
            $manage_courtname = array_merge($manage_courtname, $court_sname);
      }//end of for loop1a
    }
    
  }
      
    

  if($offertime==1){

        $user_role=$this->offer_model->get_vendorrole($user_id);
  foreach($user_role as $row) {
           $role_name = $row->name;
       }
    $user_phone=$this->offer_model->get_userphone($user_id);
  foreach($user_phone as $row) {
           $vuser_phone = $row->phone;
           $vuser_name = $row->name;
       }
     $venue_sname=$this->offer_model->get_venuename($venue_id);     
     foreach($venue_sname as $row) {
           $venue_name = $row->venue;
       }
     $loc_name=$this->offer_model->get_locname($venue_id);     
     foreach($loc_name as $row) {
           $location_name = $row->location;
       }
    $start_offdate=date('dS F Y', strtotime($sdate));
    $end_offdate=date('dS F Y', strtotime($edate));
    $start_offtime=date('h:i a',strtotime($stime));
    $end_offtime=date('h:i a', strtotime($etime));
    $kcu_courtname="";
    foreach($manage_courtname as $row) {
           $court_name = $row->court;
           $kcf_courtname=''.$kcu_courtname.''.$court_name.''.",".'';
           $kcu_courtname=$kcf_courtname;
       }

        /*email for venue owner start */
    $owneremail=$this->offer_model->get_owner($venue_id);
    foreach($owneremail as $row) {
        $owner_email = $row->email;
        $owner_name = $row->name;
        if($owner_email!=''){
        $data['data']=array(
             'role'=>$role_name,
             'phone'=>$vuser_phone,
             'vowner'=>$vuser_name,
             'owner_name'=>$owner_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'start_date'=>$start_offdate,
             'end_date'=>$end_offdate,
             'start_time'=>$start_offtime,
             'end_time'=>$end_offtime,
             'sunday'=>$sunda,
             'monday'=>$monda,
             'tuesday'=>$tuesda,
             'wednesday'=>$wednesda,
             'thursday'=>$thursda,
             'friday'=>$frida,
             'saturday'=>$saturda,
             'offer_value'=>$offer_value,
             'offer_name'=>$offer_name,
             'offer_type'=>$offer_type,
             'current_date'=>date('Y-m-d'),
             'current_time'=>date('h:i:sa'),
             'location_name'=>$location_name,
             );
          
          $to_email = $owner_email;
          $subject='Offer Updated';
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
          $message = $this->load->view('new_offer_edit',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for venue owner end */
     
              /*email for upupup start*/ 
    $upupupemail=$this->offer_model->get_upupupemail();
    foreach($upupupemail as $row) {
        $upupup_email = $row->email;
        if($upupup_email!=''){
        $data['data']=array(
             'role'=>$role_name,
             'phone'=>$vuser_phone,
             'vowner'=>$vuser_name,
             'owner_name'=>$owner_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'start_date'=>$start_offdate,
             'end_date'=>$end_offdate,
             'start_time'=>$start_offtime,
             'end_time'=>$end_offtime,
             'sunday'=>$sunda,
             'monday'=>$monda,
             'tuesday'=>$tuesda,
             'wednesday'=>$wednesda,
             'thursday'=>$thursda,
             'friday'=>$frida,
             'saturday'=>$saturda,
             'offer_value'=>$offer_value,
             'offer_name'=>$offer_name,
             'offer_type'=>$offer_type,
             'current_date'=>date('Y-m-d'),
             'current_time'=>date('h:i:sa'),
             'location_name'=>$location_name,
             );
          $to_email = $upupup_email;
          $subject='Offer Updated';
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
          $message = $this->load->view('new_offer_edit_upupup',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for upupup end */
  $result="updated";
  return $this->response($result,200);
}else{
  
  $result="error";
    return $this->response($result,200);    
}
}



}
