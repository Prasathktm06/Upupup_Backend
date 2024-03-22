<?php
    
date_default_timezone_set('Asia/Kolkata');
define( 'API_ACCESS_KEY', 'AIzaSyDizDEOR5fqzk7-SrrGEg7s26ooVq0n_n8' );

$servername = "localhost";
$username = "migrate2_jinson";
$password = "jinsonjose007";
$db='migrate2_partnerup_db';
$date=date('Y-m-d');
$timet=date('H:i:s');
 
$conn = new mysqli($servername, $username, $password,$db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($timet==date('H:i:s', strtotime('08:00:01'))){
    
         $to_email = 'jinson.gooseberry@gmail.com';
        $subject = 'Testing PHP Mail';
        $message = 'This mail is sent using the PHP mail function';
        $headers = 'From: jaziya.gooseberry@gmail.com';
        mail($to_email,$subject,$message,$headers);

$sql = "SELECT id,name,venue_id,MAX(precentage) AS precentage  FROM hot_offer WHERE date = '$date'";
$result = $conn->query($sql);
while($row= mysqli_fetch_assoc($result)) {
        $hot_id = $row['id'];
        $sql2 = "SELECT venue.`id`,venue.`venue`,venue.`area_id` FROM venue JOIN hot_offer ON hot_offer.`venue_id`=venue.`id` WHERE hot_offer.id = '$hot_id'";
        $result2 = $conn->query($sql2);
             while($row2 = mysqli_fetch_assoc($result2)) {
                $venue_id=$row2['id'];
                $venue_name=$row2['venue'];
                $area_id=$row2['area_id'];
             }
        $sql3 = "SELECT id,area,location_id FROM area  WHERE id = '$area_id'";
        $result3 = $conn->query($sql3);
            while($row3 = mysqli_fetch_assoc($result3)) {
                $area_name=$row3['area'];
                $location_id=$row3['location_id'];

            }
        $sql4 = "SELECT id,hot_not_setting_id FROM hot_offer_notification  WHERE location_id = '$location_id' && status=1";
        $result4 = $conn->query($sql4);
            while($row4 = mysqli_fetch_assoc($result4)) {
                $setting_id=$row4['hot_not_setting_id'];

            }
        if(empty($setting_id)){
            $setting_id=3;
            }

        $sql5 = "SELECT hot_offer_court.`sports_id`,sports.`sports`,hot_offer.`precentage` FROM hot_offer JOIN hot_offer_court ON hot_offer_court.`hot_offer_id`=hot_offer.`id` JOIN sports ON sports.`id`=hot_offer_court.`sports_id` WHERE hot_offer.id = '$hot_id' ";
        $result5 = $conn->query($sql5);
            while($row5 = mysqli_fetch_assoc($result5)) {
                $sports_id=$row5['sports_id'];
                $sports_name=$row5['sports'];
                $precentage=$row5['precentage'];
                        
                }
        $sql6 = "SELECT court_time_intervel.`id`,court_time_intervel.`time`   FROM hot_offer_slot JOIN court_time_intervel ON court_time_intervel.`id`=hot_offer_slot.`court_time_intervel_id`  WHERE hot_offer_slot.hot_offer_id = '$hot_id' ORDER BY court_time_intervel.time ASC";
        $result6 = $conn->query($sql6);
            $slot_time="";
            while($row6 = mysqli_fetch_assoc($result6)) {
                $time=date( ' h:i A ',strtotime($row6['time']));
                $slot_id=$row6['id'];
                $slot_times=''.$slot_time.''.$time.''.",".'';
                $slot_time=$slot_times;
                }

        $message="".$slot_time." Today @ ".$venue_name.",".$area_name."";
        $title="".$precentage."% Hot Offer | ".$sports_name." \n ".$message; 
                if($setting_id==1){

                    $sql7 = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` WHERE area.location_id = '$location_id' && users.status=1 ORDER BY users.id ASC";
                    $result7 = $conn->query($sql7);
                            while($row7 = mysqli_fetch_assoc($result7)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];

                                    $msg = array
                                        (
                                        'alert'         => $title,
                                        "title"         => $title,
                                        //"subtitle"      => "App",
                                        "message"       => $title,
                                        "tickerText"    => "fdfd",
                                        "venue_id"      =>$venue_id,
                                        'type'=>6,
                                        "vibrate"       => 1,
                                        "sound"         => 1,
                                        "content-available"   => 1
                                        );

                                    $fields = array
                                        (
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        "notification" => $msg,
                                        "data"      => array('result'=>array('venue_id'=>$venue_id,'type'=>6)),
                                         );


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
                                        
                                   if(!empty($device_id)){
				            	$sql10 = "INSERT INTO `hot_offer_notify_history`(`hot_offer_id`, `user_id`) VALUES ($hot_id,$user_id)";
				            	$result10 = $conn->query($sql10);
				            }     
                            }

                }elseif($setting_id==2){

                    $sql8 = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` JOIN user_sports ON user_sports.`user_id`=users.`id` WHERE area.location_id = '$location_id' && users.status=1 && user_sports.sports_id='$sports_id' ORDER BY users.id ASC";
                    $result8 = $conn->query($sql8);
                            while($row8 = mysqli_fetch_assoc($result8)) {
                                $user_id=$row8['id'];
                                $device_id=$row8['device_id'];

                                $msg = array
                                    (
                                    'alert'         => $title,
                                    "title"         => $title,
                                    //"subtitle"      => "App",
                                    "message"       => $title,
                                    "tickerText"    => "fdfd",
                                    "venue_id"      =>$venue_id,
                                    'type'=>6,
                                    "vibrate"       => 1,
                                    "sound"         => 1,
                                    "content-available"   => 1
                                    );

                                $fields = array
                                    (
                                    "to"        => $device_id,
                                    "priority"  => "high",
                                    "notification" => $msg,
                                    "data"      => array('result'=>array('venue_id'=>$venue_id,'type'=>6)),
                                    );


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
                                    
                                    if(!empty($device_id)){
				            	$sql10 = "INSERT INTO `hot_offer_notify_history`(`hot_offer_id`, `user_id`) VALUES ($hot_id,$user_id)";
				            	$result10 = $conn->query($sql10);
				            }   
                            }

                }elseif($setting_id==3){

                    $sql9 = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` JOIN user_sports ON user_sports.`user_id`=users.`id` WHERE area.location_id = '$location_id' && users.status=1 && user_sports.sports_id='$sports_id' && area.`id`='$area_id' ORDER BY users.id ASC";
                    $result9 = $conn->query($sql9);
                        while($row9 = mysqli_fetch_assoc($result9)) {
                            $user_id=$row9['id'];
                            $device_id=$row9['device_id'];

                                $msg = array
                                    (
                                    'alert'         => $title,
                                    "title"         => $title,
                                    //"subtitle"      => "App",
                                    "message"       => $title,
                                    "tickerText"    => "fdfd",
                                    "venue_id"      =>$venue_id,
                                    'type'=>6,
                                    "vibrate"       => 1,
                                    "sound"         => 1,
                                    "content-available"   => 1
                                    );

                                $fields = array
                                    (
                                    "to"        => $device_id,
                                    "priority"  => "high",
                                    "notification" => $msg,
                                    "data"      => array('result'=>array('venue_id'=>$venue_id,'type'=>6)),
                                    );


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
                                    
                                    if(!empty($device_id)){
				            	$sql10 = "INSERT INTO `hot_offer_notify_history`(`hot_offer_id`, `user_id`) VALUES ($hot_id,$user_id)";
				            	$result10 = $conn->query($sql10);
				            }   
                        }
                                                        
                }else{
                    /* no option */
                                                    
                } 
                
                

    

    
}
}
    











