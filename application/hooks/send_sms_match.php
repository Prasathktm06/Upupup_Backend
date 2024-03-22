<?php 
date_default_timezone_set('Asia/Kolkata');
define( 'API_ACCESS_KEY', 'AIzaSyDizDEOR5fqzk7-SrrGEg7s26ooVq0n_n8' );
$date=date('Y-m-d');
$time=date('H:i:s');

$servername = "localhost";
$username = "migrate2_jinson";
$password = "jinsonjose007";
$db='migrate2_partnerup_db';

$data=array('result'=>'Match','type'=>'remainder');
$conn = new mysqli($servername, $username, $password,$db);
$sql = "SELECT venue_booking_time.*,venue_booking.`venue_id` FROM venue_booking_time JOIN venue_booking ON venue_booking.`booking_id`=venue_booking_time.`booking_id` WHERE venue_booking_time.date= '$date' && venue_booking.payment_mode!='3' && venue_booking.payment_mode!='2'";

$result = $conn->query($sql);
//echo mysqli_connect_error();
 while($row = @mysqli_fetch_assoc($result)) {
    $to_time = strtotime("$date $time");
    if($row[date]==$date){
        $from_time = strtotime("$date $row[court_time]");   
    }else{
        $from_time = strtotime("$date $time");   
    }
   
    $diff= round(abs($to_time - $from_time) / 60,2);
    if((int)$diff==59 && $time < $row[court_time]){
        $sql1 = "SELECT users.* FROM venue_players LEFT JOIN users ON users.id=venue_players.user_id WHERE  venue_players.booking_id= '$row[booking_id]'";
        $res = $conn->query($sql1);
        while($row2 = @mysqli_fetch_assoc($res)) {
    
            $registrationIds = $row2['device_id'];
            $msg = array
            (
            'alert'         => "Reminder",
            "title"         => "Reminder",
            //"subtitle"      => "App",
            "message"       => "Remainder",
            "tickerText"    => "fdfd",
            "venue_id"      =>$row['venue_id'],
            'type'=>7,
            "vibrate"       => 1,
            "sound"         => 1,
            "content-available"   => 1
        );

        $fields = array
            (
            "to"        => $registrationIds,
            "priority"  => "high",
            //"notification" => $msg,
            "data"      => array('result'=>array('venue_id'=>$row['venue_id'],'type'=>7)),
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
        }
        
       


    
    
    
  
    

 
 

    }

 }
