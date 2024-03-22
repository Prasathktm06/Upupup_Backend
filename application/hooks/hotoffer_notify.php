<?php
    
date_default_timezone_set('Asia/Kolkata');
define( 'API_ACCESS_KEY', 'AIzaSyDizDEOR5fqzk7-SrrGEg7s26ooVq0n_n8' );

$servername = "localhost";
$username = "migrate2_jinson";
$password = "jinsonjose007";
$db='migrate2_partnerup_db';
$date=date('Y-m-d');
$timet=date('H:i:s');
$second= date('Y-m-d', strtotime($date. ' + 1 days')); 
$conn = new mysqli($servername, $username, $password,$db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$locations = "SELECT id,location  FROM locations WHERE status = 1";
$result_location = $conn->query($locations);
while($row= mysqli_fetch_assoc($result_location)) {
        $location_id = $row['id'];
        $location = $row['location'];
        $notify_set = "SELECT id,location_id,hot_not_setting_id,time1,time2  FROM hot_offer_notification WHERE status = 1 && location_id = '$location_id' ";
        $set_result = $conn->query($notify_set);
        while($row1= mysqli_fetch_assoc($set_result)) {
                $set_id = $row1['id'];
                $set_location_id = $row1['location_id'];
                $set_notify_set_id = $row1['hot_not_setting_id'];
                $set_time1 = $row1['time1'];
                $set_time2 = $row1['time2'];
/////////////////////////////////////////////////////////////// check time 1 start ////////////////////////////////////////////////////////////////////////////////////////////////////
                if($set_time1 == $timet){


                    $sql4 = "SELECT id,hot_not_setting_id FROM hot_offer_notification  WHERE location_id = '$location_id' && status=1";
                    $result4 = $conn->query($sql4);
                    while($row4 = mysqli_fetch_assoc($result4)) {
                        $setting_id=$row4['hot_not_setting_id'];
                        }
                    if(empty($setting_id)){
                        $setting_id=3;
                        }




                    if($setting_id==1){
                /////////////////////////////////////////// setting id 1 start////////////////////////////////////////////
                 $hot_data = "SELECT hot_offer.id,hot_offer.name,hot_offer.venue_id,MAX(hot_offer.precentage) AS precentage  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time<'$set_time2' && court_time_intervel.time > '$set_time1' ";
                    $result_hotoff = $conn->query($hot_data);
                    
                    
                        while($row2= mysqli_fetch_assoc($result_hotoff)) {
                            $hot_id = $row2['id'];
                            $hot_name = $row2['name'];
                            $hot_venue_id = $row2['venue_id'];
                            $hot_precentage = $row2['precentage'];
                            
                           if(!empty($row2['id'])){

                            ///////////////////////////////////////////////////////////////////////////////

                            $venue_data = "SELECT venue.venue,area.area FROM venue JOIN area ON area.`id`=venue.`area_id` WHERE venue.id= '$hot_venue_id'";
                            $venue_details = $conn->query($venue_data);
                            while($row5 = mysqli_fetch_assoc($venue_details)) {
                                $area_name=$row5['area'];
                            }

                            $hot_venue = "SELECT DISTINCT venue.id,venue.venue  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time<'$set_time2' && court_time_intervel.time > '$set_time1' ";
                        $result_hotvenue = $conn->query($hot_venue);
                        $venue_name="";
                        $venue_count=0;
                        $count_venue=count($result_hotvenue);
                        while($row11 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_count++;
                                $venue_nm=$row11['venue'];
                                $venue_names=''.$venue_name.''.$venue_nm.'';

                                
                                    $venue_seperation=''.$venue_names.''.";".'';
                                    $venue_name=$venue_seperation;   
                                
                            }
                            
                        $venue_namejp="";
                        $venue_countjp=0;
                        $count_venuejp=count($result_hotvenue);
                        while($row12 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_countjp++;
                                $venue_nmjp=$row12['venue'];
                                $venue_namesjp=''.$venue_namejp.''.$venue_nmjp.'';

                                
                                    $venue_seperationjp=''.$venue_namesjp.''.",".'';
                                    $venue_namejp=$venue_seperationjp;   
                                
                            }
                            
                            $abt=json_decode($result_hotvenue,TRUE );
                           $message="Upto ".$hot_precentage."% off @ ".$venue_name."";
                           $title="upUPUP Hot-Offer";

                           $user_data = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` WHERE area.location_id = '$location_id' && users.status=1 ORDER BY users.id ASC"; 
                            $user_result = $conn->query($user_data);
                             while($row7 = mysqli_fetch_assoc($user_result)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];
                                    $data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'venues'  => $venue_namejp,
                                                 'percentage'  => $hot_precentage,
                                                 'type'   => 8 ),
                                                 'status'=> "true",
                                                 'type'  => "GENERAL"
                                            );
                                        
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
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        //"notification" => $msg,
                                        "data"      => $data,
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


                            ///////////////////////////////////////////////////////////////////////////////
                                 
                            }
                            

                            
                        }
                /////////////////////////////////////////// setting id 1 end////////////////////////////////////////////
                    }elseif($setting_id==2){
                        
                         $hot_data = "SELECT hot_offer.id,hot_offer.name,hot_offer.venue_id,MAX(hot_offer.precentage) AS precentage  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time<'$set_time2' && court_time_intervel.time > '$set_time1' ";
                    $result_hotoff = $conn->query($hot_data);

                /////////////////////////////////////////// setting id 2 start////////////////////////////////////////////
                        while($row2= mysqli_fetch_assoc($result_hotoff)) {
                            $hot_id = $row2['id'];
                            $hot_name = $row2['name'];
                            $hot_venue_id = $row2['venue_id'];
                            $hot_precentage = $row2['precentage'];

                            ////////////////////////////////////////////////////////////////////////////////////////////////
                            
                            if(!empty($row2['id'])){
                            $sports_data = "SELECT DISTINCT sports_id FROM hot_offer_court  WHERE hot_offer_id= '$hot_id'";
                            $sports_details = $conn->query($sports_data);
                            while($row8 = mysqli_fetch_assoc($sports_details)) {
                                $hot_sports_id=$row8['sports_id'];
                            }
                            
                            $venue_data = "SELECT venue.venue,area.area,area.id FROM venue JOIN area ON area.`id`=venue.`area_id` WHERE venue.id= '$hot_venue_id'";
                            $venue_details = $conn->query($venue_data);
                            while($row5 = mysqli_fetch_assoc($venue_data)) {
                                $area_name=$row5['area'];
                                $area_id=$row5['id'];
                            }

                           $hot_venue = "SELECT DISTINCT venue.id,venue.venue  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time<'$set_time2' && court_time_intervel.time > '$set_time1' ";
                        $result_hotvenue = $conn->query($hot_venue);
                        $venue_name="";
                        $venue_count=0;
                        $count_venue=count($result_hotvenue);
                        while($row11 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_count++;
                                $venue_nm=$row11['venue'];
                                $venue_names=''.$venue_name.''.$venue_nm.'';

                                    $venue_seperation=''.$venue_names.''.";".'';
                                    $venue_name=$venue_seperation;   
                               
                            }
                        $venue_namejp="";
                        $venue_countjp=0;
                        $count_venuejp=count($result_hotvenue);
                        while($row12 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_countjp++;
                                $venue_nmjp=$row12['venue'];
                                $venue_namesjp=''.$venue_namejp.''.$venue_nmjp.'';

                                
                                    $venue_seperationjp=''.$venue_namesjp.''.",".'';
                                    $venue_namejp=$venue_seperationjp;   
                                
                            }    
                           $abt=json_decode($result_hotvenue,TRUE ); 
                           $message="Upto ".$hot_precentage."% off @ ".$venue_name."";
                           $title="upUPUP Hot-Offer";

                           $user_data = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` JOIN user_sports ON user_sports.`user_id`=users.`id` WHERE area.location_id = '$location_id' && users.status=1 && user_sports.sports_id='$hot_sports_id' ORDER BY users.id ASC"; 
                            $user_result = $conn->query($user_data);

                             while($row7 = mysqli_fetch_assoc($user_result)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];

                                        $data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'venues'  => $venue_namejp,
                                                 'percentage'  => $hot_precentage,
                                                 'type'   => 8 ),
                                                 'status'=> "true",
                                                 'type'  => "GENERAL"
                                            );
                                        
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
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        //"notification" => $msg,
                                        "data"      => $data,
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
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////////////

                            
                        }
                /////////////////////////////////////////// setting id 2 end////////////////////////////////////////////
                    }else{
                        
                         $hot_data = "SELECT hot_offer.id,hot_offer.name,hot_offer.venue_id,MAX(hot_offer.precentage) AS precentage  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time > '$set_time1' ";
                    $result_hotoff = $conn->query($hot_data);
                /////////////////////////////////////////// setting id 3 start////////////////////////////////////////////
                        while($row2= mysqli_fetch_assoc($result_hotoff)) {
                            $hot_id = $row2['id'];
                            $hot_name = $row2['name'];
                            $hot_venue_id = $row2['venue_id'];
                            $hot_precentage = $row2['precentage'];
                            ///////////////////////////////////////////////////////////////////////////////////////////
                            if(!empty($row2['id'])){
                            $sports_data = "SELECT DISTINCT sports_id FROM hot_offer_court  WHERE hot_offer_id= '$hot_id'";
                            $sports_details = $conn->query($sports_data);
                            while($row8 = mysqli_fetch_assoc($sports_details)) {
                                $hot_sports_id=$row8['sports_id'];
                            }
                            
                            $venue_data = "SELECT venue.venue,area.area,area.id FROM venue JOIN area ON area.`id`=venue.`area_id` WHERE venue.id= '$hot_venue_id'";
                            $venue_details = $conn->query($venue_data);
                            while($row5 = mysqli_fetch_assoc($venue_data)) {
                                $area_name=$row5['area'];
                                $area_id=$row5['id'];
                            }

                           $hot_venue = "SELECT DISTINCT venue.id,venue.venue  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time<'$set_time2' && court_time_intervel.time > '$set_time1' ";
                        $result_hotvenue = $conn->query($hot_venue);
                        $venue_name="";
                        $venue_count=0;
                        $count_venue=count($result_hotvenue);
                        while($row11 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_count++;
                                $venue_nm=$row11['venue'];
                                $venue_names=''.$venue_name.''.$venue_nm.'';

                                    $venue_seperation=''.$venue_names.''.";".'';
                                    $venue_name=$venue_seperation;   
                                
                            }
                        
                        $venue_namejp="";
                        $venue_countjp=0;
                        $count_venuejp=count($result_hotvenue);
                        while($row12 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_countjp++;
                                $venue_nmjp=$row12['venue'];
                                $venue_namesjp=''.$venue_namejp.''.$venue_nmjp.'';

                                
                                    $venue_seperationjp=''.$venue_namesjp.''.",".'';
                                    $venue_namejp=$venue_seperationjp;   
                                
                            } 
                            
                           $abt=json_decode($result_hotvenue,TRUE );
                           $message="Upto ".$hot_precentage."% off @ ".$venue_name."";
                           $title="upUPUP Hot-Offer";

                           $user_data = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` JOIN user_sports ON user_sports.`user_id`=users.`id` WHERE area.location_id = '$location_id' && users.status=1 && user_sports.sports_id='$sports_id' && area.`id`='$area_id' ORDER BY users.id ASC"; 
                            $user_result = $conn->query($user_data);

                             while($row7 = mysqli_fetch_assoc($user_result)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];

                                       $data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'venues'  => $venue_namejp,
                                                 'percentage'  => $hot_precentage,
                                                 'type'   => 8 ),
                                                 'status'=> "true",
                                                 'type'  => "GENERAL"
                                            );
                                        
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
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        //"notification" => $msg,
                                        "data"      => $data,
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
                            }
                            //////////////////////////////////////////////////////////////////////////////////////////
 
                            
                        }
                /////////////////////////////////////////// setting id 3 end////////////////////////////////////////////
                    }

                }
/////////////////////////////////////////////////////////////// check time 1 end ////////////////////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////// check time 2 start ////////////////////////////////////////////////////////////////////////////////////////////////////
                if($set_time2 == $timet){


                    $sql4 = "SELECT id,hot_not_setting_id FROM hot_offer_notification  WHERE location_id = '$location_id' && status=1";
                    $result4 = $conn->query($sql4);
                    while($row4 = mysqli_fetch_assoc($result4)) {
                        $setting_id=$row4['hot_not_setting_id'];
                        }
                    if(empty($setting_id)){
                        $setting_id=3;
                        }




                    if($setting_id==1){
                /////////////////////////////////////////// setting id 1 start////////////////////////////////////////////
                 $hot_data = "SELECT hot_offer.id,hot_offer.name,hot_offer.venue_id,MAX(hot_offer.precentage) AS precentage  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE (area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time>'$set_time2')||(area.location_id = '$location_id' && hot_offer.date='$second' && court_time_intervel.time<'$set_time1') ";
                    $result_hotoff = $conn->query($hot_data);
                    
                    
                        while($row2= mysqli_fetch_assoc($result_hotoff)) {
                            $hot_id = $row2['id'];
                            $hot_name = $row2['name'];
                            $hot_venue_id = $row2['venue_id'];
                            $hot_precentage = $row2['precentage'];
                            
                           if(!empty($row2['id'])){

                            ///////////////////////////////////////////////////////////////////////////////

                            $venue_data = "SELECT venue.venue,area.area FROM venue JOIN area ON area.`id`=venue.`area_id` WHERE venue.id= '$hot_venue_id'";
                            $venue_details = $conn->query($venue_data);
                            while($row5 = mysqli_fetch_assoc($venue_details)) {
                                $area_name=$row5['area'];
                            }

                              $hot_venue = "SELECT DISTINCT venue.id,venue.venue  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE (area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time>'$set_time2')||(area.location_id = '$location_id' && hot_offer.date='$second' && court_time_intervel.time<'$set_time1') ";
                        $result_hotvenue = $conn->query($hot_venue);
                        $venue_name="";
                        $venue_count=0;
                        $count_venue=count($result_hotvenue);
                        while($row11 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_count++;
                                $venue_nm=$row11['venue'];
                                $venue_names=''.$venue_name.''.$venue_nm.'';

                                    $venue_seperation=''.$venue_names.''.";".'';
                                    $venue_name=$venue_seperation;   
                                
                            }
                        $venue_namejp="";
                        $venue_countjp=0;
                        $count_venuejp=count($result_hotvenue);
                        while($row12 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_countjp++;
                                $venue_nmjp=$row12['venue'];
                                $venue_namesjp=''.$venue_namejp.''.$venue_nmjp.'';

                                
                                    $venue_seperationjp=''.$venue_namesjp.''.",".'';
                                    $venue_namejp=$venue_seperationjp;   
                                
                            }    
                          
                            $abt=json_decode($result_hotvenue,TRUE );
                           $message="Upto ".$hot_precentage."% off @ ".$venue_name."";
                           $title="upUPUP Hot-Offer";

                           $user_data = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` WHERE area.location_id = '$location_id' && users.status=1 ORDER BY users.id ASC"; 
                            $user_result = $conn->query($user_data);

                             while($row7 = mysqli_fetch_assoc($user_result)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];

                                       $data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'venues'  => $venue_namejp,
                                                 'percentage'  => $hot_precentage,
                                                 'type'   => 8 ),
                                                 'status'=> "true",
                                                 'type'  => "GENERAL"
                                            );
                                        
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
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        //"notification" => $msg,
                                        "data"      => $data,
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


                            ///////////////////////////////////////////////////////////////////////////////
                                 
                            }
                            

                            
                        }
                /////////////////////////////////////////// setting id 1 end////////////////////////////////////////////
                    }elseif($setting_id==2){
                        
                         $hot_data = "SELECT hot_offer.id,hot_offer.name,hot_offer.venue_id,MAX(hot_offer.precentage) AS precentage  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE (area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time>'$set_time2')||(area.location_id = '$location_id' && hot_offer.date='$second' && court_time_intervel.time<'$set_time1') ";
                    $result_hotoff = $conn->query($hot_data);

                /////////////////////////////////////////// setting id 2 start////////////////////////////////////////////
                        while($row2= mysqli_fetch_assoc($result_hotoff)) {
                            $hot_id = $row2['id'];
                            $hot_name = $row2['name'];
                            $hot_venue_id = $row2['venue_id'];
                            $hot_precentage = $row2['precentage'];

                            ////////////////////////////////////////////////////////////////////////////////////////////////
                            
                            if(!empty($row2['id'])){
                            $sports_data = "SELECT DISTINCT sports_id FROM hot_offer_court  WHERE hot_offer_id= '$hot_id'";
                            $sports_details = $conn->query($sports_data);
                            while($row8 = mysqli_fetch_assoc($sports_details)) {
                                $hot_sports_id=$row8['sports_id'];
                            }
                            
                            $venue_data = "SELECT venue.venue,area.area,area.id FROM venue JOIN area ON area.`id`=venue.`area_id` WHERE venue.id= '$hot_venue_id'";
                            $venue_details = $conn->query($venue_data);
                            while($row5 = mysqli_fetch_assoc($venue_data)) {
                                $area_name=$row5['area'];
                                $area_id=$row5['id'];
                            }

                           $hot_venue = "SELECT DISTINCT venue.id,venue.venue  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE (area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time>'$set_time2')||(area.location_id = '$location_id' && hot_offer.date='$second' && court_time_intervel.time<'$set_time1') ";
                        $result_hotvenue = $conn->query($hot_venue);
                        $venue_name="";
                        $venue_count=0;
                        $count_venue=count($result_hotvenue);
                        while($row11 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_count++;
                                $venue_nm=$row11['venue'];
                                $venue_names=''.$venue_name.''.$venue_nm.'';

                                    $venue_seperation=''.$venue_names.''.";".'';
                                    $venue_name=$venue_seperation;   
                               
                            }
                        $venue_namejp="";
                        $venue_countjp=0;
                        $count_venuejp=count($result_hotvenue);
                        while($row12 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_countjp++;
                                $venue_nmjp=$row12['venue'];
                                $venue_namesjp=''.$venue_namejp.''.$venue_nmjp.'';

                                
                                    $venue_seperationjp=''.$venue_namesjp.''.",".'';
                                    $venue_namejp=$venue_seperationjp;   
                                
                            } 
                            
                           $abt=json_decode($result_hotvenue,TRUE );
                           $message="Upto ".$hot_precentage."% off @ ".$venue_name."";
                           $title="upUPUP Hot-Offer";

                           $user_data = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` JOIN user_sports ON user_sports.`user_id`=users.`id` WHERE area.location_id = '$location_id' && users.status=1 && user_sports.sports_id='$hot_sports_id' ORDER BY users.id ASC"; 
                            $user_result = $conn->query($user_data);

                             while($row7 = mysqli_fetch_assoc($user_result)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];

                                    $data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'venues'  => $venue_namejp,
                                                 'percentage'  => $hot_precentage,
                                                 'type'   => 8 ),
                                                 'status'=> "true",
                                                 'type'  => "GENERAL"
                                            );
                                        
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
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        //"notification" => $msg,
                                        "data"      => $data,
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
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////////////

                            
                        }
                /////////////////////////////////////////// setting id 2 end////////////////////////////////////////////
                    }else{
                        
                         $hot_data = "SELECT hot_offer.id,hot_offer.name,hot_offer.venue_id,MAX(hot_offer.precentage) AS precentage  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE (area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time>'$set_time2')||(area.location_id = '$location_id' && hot_offer.date='$second' && court_time_intervel.time<'$set_time1') ";
                    $result_hotoff = $conn->query($hot_data);
                /////////////////////////////////////////// setting id 3 start////////////////////////////////////////////
                        while($row2= mysqli_fetch_assoc($result_hotoff)) {
                            $hot_id = $row2['id'];
                            $hot_name = $row2['name'];
                            $hot_venue_id = $row2['venue_id'];
                            $hot_precentage = $row2['precentage'];
                            ///////////////////////////////////////////////////////////////////////////////////////////
                            if(!empty($row2['id'])){
                            $sports_data = "SELECT DISTINCT sports_id FROM hot_offer_court  WHERE hot_offer_id= '$hot_id'";
                            $sports_details = $conn->query($sports_data);
                            while($row8 = mysqli_fetch_assoc($sports_details)) {
                                $hot_sports_id=$row8['sports_id'];
                            }
                            
                            $venue_data = "SELECT venue.venue,area.area,area.id FROM venue JOIN area ON area.`id`=venue.`area_id` WHERE venue.id= '$hot_venue_id'";
                            $venue_details = $conn->query($venue_data);
                            while($row5 = mysqli_fetch_assoc($venue_data)) {
                                $area_name=$row5['area'];
                                $area_id=$row5['id'];
                            }

                           $hot_venue = "SELECT DISTINCT venue.id,venue.venue  
                        FROM court_time_intervel 
                        JOIN hot_offer_slot ON hot_offer_slot.`court_time_intervel_id`=court_time_intervel.`id` 
                        JOIN hot_offer_court ON hot_offer_court.`id`=hot_offer_slot.`hot_offer_court_id` 
                        JOIN hot_offer ON hot_offer.`id`=hot_offer_court.`hot_offer_id` 
                        JOIN venue ON venue.`id`=hot_offer.`venue_id` 
                        JOIN area ON area.`id`=venue.`area_id` 
                        WHERE (area.location_id = '$location_id' && hot_offer.date='$date' && court_time_intervel.time>'$timet' && court_time_intervel.time>'$set_time2')||(area.location_id = '$location_id' && hot_offer.date='$second' && court_time_intervel.time<'$set_time1') ";
                        $result_hotvenue = $conn->query($hot_venue);
                        $venue_name="";
                        $venue_count=0;
                        $count_venue=count($result_hotvenue);
                        while($row11 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_count++;
                                $venue_nm=$row11['venue'];
                                $venue_names=''.$venue_name.''.$venue_nm.'';

                                    $venue_seperation=''.$venue_names.''.";".'';
                                    $venue_name=$venue_seperation;   
                                
                            }
                        $venue_namejp="";
                        $venue_countjp=0;
                        $count_venuejp=count($result_hotvenue);
                        while($row12 = mysqli_fetch_assoc($result_hotvenue)) {
                                $venue_countjp++;
                                $venue_nmjp=$row12['venue'];
                                $venue_namesjp=''.$venue_namejp.''.$venue_nmjp.'';

                                
                                    $venue_seperationjp=''.$venue_namesjp.''.",".'';
                                    $venue_namejp=$venue_seperationjp;   
                                
                            } 
                            
                           $abt=json_decode($result_hotvenue,TRUE );
                           $message="Upto ".$hot_precentage."% off @ ".$venue_name."";
                           $title="upUPUP Hot-Offer";

                           $user_data = "SELECT DISTINCT users.`id`,users.`device_id`,users.`phone_no`  FROM users JOIN user_area ON user_area.`user_id`=users.`id` JOIN area ON area.`id`=user_area.`area_id` JOIN user_sports ON user_sports.`user_id`=users.`id` WHERE area.location_id = '$location_id' && users.status=1 && user_sports.sports_id='$sports_id' && area.`id`='$area_id' ORDER BY users.id ASC"; 
                            $user_result = $conn->query($user_data);

                             while($row7 = mysqli_fetch_assoc($user_result)) {
                                $user_id=$row7['id'];
                                $device_id=$row7['device_id'];

                                    $data 		=array('result' => array('message'=> $message,
                                                 'title'  => $title,
                                                 'venues'  => $venue_namejp,
                                                 'percentage'  => $hot_precentage,
                                                 'type'   => 8 ),
                                                 'status'=> "true",
                                                 'type'  => "GENERAL"
                                            );
                                        
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
                                        "to"        => $device_id,
                                        "priority"  => "high",
                                        //"notification" => $msg,
                                        "data"      => $data,
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
                            }
                            //////////////////////////////////////////////////////////////////////////////////////////
 
                            
                        }
                /////////////////////////////////////////// setting id 3 end////////////////////////////////////////////
                    }

                }
/////////////////////////////////////////////////////////////// check time 1 end ////////////////////////////////////////////////////////////////////////////////////////////////////

        }

    }


