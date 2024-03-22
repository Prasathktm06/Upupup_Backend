<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Common {

	function htmlspecialchars($string) {
		if (isset($string)) {
			$result = htmlspecialchars($string);
		}else{
			$result = "";
		}

		return $result;
	}

function sms($phone,$msg) {
        $url = "https://app.smsbits.in/api/user?id=OTk3Mjg2NjY1Ng==&senderid=upUPUP&to=$phone&msg=$msg&port=TA";
            
        $fields = array();

          //open connection
          $ch = curl_init();

          //set the url, number of POST vars, POST data
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, count($fields));
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

          /*$result = curl_exec($ch);

          curl_close($ch);*/
          $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
          // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
          $curlresponse = curl_exec($ch); // execute
          if(curl_errno($ch)){
          //echo 'curl error : '. curl_error($ch);
        }
          if (empty($ret)) {
              // some kind of an error happened
              die(curl_error($ch));
              curl_close($ch); // close cURL handler
          } else {
          $info = curl_getinfo($ch);
          curl_close($ch); // close cURL handler
        //echo "";
        //return $ret;
        //echo $curlresponse; echo "Message Sent Succesfully" ;
        //return $curlresponse;
    }

  }
	//////////////////////////////////////////////////////////////////////
	function file_upload_image($path)
     {
          $err_msg='';
          $target_dir = FCPATH.$path;
         $ext = pathinfo($_FILES["file"]["name"]);
         $date = new DateTime();
      
      
          $fileName=$date->getTimestamp().'.'.$ext['extension'];

          $target_file = $target_dir .  $fileName;
          //print_r($target_file);exit;
          $uploadOk = 1;
          $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
          $check = getimagesize($_FILES["file"]["tmp_name"]);
         // print_r($check);exit;
          if($check !== false) {
               //    echo "File is an image - " . $check["mime"] . ".";
               $uploadOk = 1;
          } else {
               $uploadOk = 0;
          }

          // Check if file already exists
          /*if (file_exists($target_file)) {
            //   echo "Sorry, file already exists.";
              $uploadOk = 0;
          }*/

          // Check file size
          if ($_FILES["file"]["size"] > 5000000) {
              $err_msg+=  "Sorry, your file is too large.<br>";
              $uploadOk = 0;
          }
          // if ($check[0]>=1920 && $check[1]>=1080) {
          //     $err_msg+= "Sorry, your file is too large.<br>";
          //     $uploadOk = 0;
          // }
          // Allow certain file formats
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" && $imageFileType != "csv" && $imageFileType != "xlsx") {
            $err_msg+=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
          }
          // Check if $uploadOk is set to 0 by an error
          if ($uploadOk == 0) {

             return false;
          // if everything is ok, try to upload file
          } else {
              if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                
                  
              } else {
                  $err_msg+=  "Sorry, there was an error uploading your file.";
                  //return $err_msg;
              }
          }//echo "<pre>";print_r($_FILES["file"]);exit();
          
           return  base_url().$path.$fileName;
     }
     ////////////////////////////////////////////////////////////////////
     function file_upload_csv($path)
     {
          $target_dir = FCPATH.$path;
          $target_file = $target_dir . basename($_FILES["file"]["name"]);
          $uploadOk = 1;
          $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            //$check = getimagesize($_FILES["file"]["tmp_name"]);
            /*  if($check !== false) {
              //    echo "File is an image - " . $check["mime"] . ".";
                  $uploadOk = 1;
              } else {

                  $uploadOk = 0;
              }*/

          // Check if file already exists
          /*if (file_exists($target_file)) {
            //   echo "Sorry, file already exists.";
              $uploadOk = 0;
          }*/
          // Check file size
          /*if ($_FILES["file"]["size"] > 500000) {
              echo "Sorry, your file is too large.";
              $uploadOk = 0;
          }*/
          // Allow certain file formats
          /*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" && $imageFileType != "csv" ) {
            //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
          }*/
          // Check if $uploadOk is set to 0 by an error
          if ($uploadOk == 0) {
               return false;
               // if everything is ok, try to upload file
          } else {
               if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    // echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
               } else {
                    // echo "Sorry, there was an error uploading your file.";
               }
          }


          return  $_FILES["file"]["name"];
     }
     ///////////////////////////////////////////////////////////////////////////////
public function email($email,$subject,$message)
{
          $subject=$subject;
          //$subject="some text";
          $message=$message;
          $to_email = $email;
          //Load email library
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'ssl://smtp.sendgrid.net';
          $config['smtp_port']    = '465';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'nikhildas';
          $config['smtp_pass']    = 'appzoc-1';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('projects@in.appzoc.com','UPUpUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $this->email->message($message);
          $this->email->send();
}

}
