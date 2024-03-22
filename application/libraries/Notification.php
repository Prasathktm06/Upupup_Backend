<?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Notification
    {
         function __construct() {
            define( 'API_ACCESS_KEY', 'AIzaSyDizDEOR5fqzk7-SrrGEg7s26ooVq0n_n8' );
        }
    	function push_notification($registrationIds,$message,$title,$data) {

            
            foreach ($registrationIds as $key => $value) {
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
            // curl_setopt( $ch,CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
            curl_setopt( $ch,CURLOPT_URL, "https://fcm.googleapis.com/fcm/send" );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
            //echo $result;

        }//echo "<pre>";print_r($result);exit();
        //return true;
    	}

    }
