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
class Payment extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/place_model");

	}

	public function authentication_get()
{
$url = 'https://www.instamojo.com/oauth2/token/';
$data = array('client_id'  => 'XuiPv8W640UvdI8fN3nvH7VxTkXB8vGPwRmCbyNy',
       'client_secret'  => '4109pSY2abLpxnFOmdoKWHaBZ8GKjSeN4MlCBXF1aysJajqIuIiSVuvkzm0QYGEVGy78EoLqFvWS6EusRILe5wdevoocw9pjrBaLLgSIMLXw74BQwVvGPOT1pDX72P4m',
       'grant_type'  => 'client_credentials'); // use key 'http' even if you send the request to https://...
$options = array('http' => array('header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' =>http_build_query($data) ) );
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
$result=array(
'ErrorCode'=>1,
'Data'=>'',
'Message'=>"Error"
);
}else{
$result=array(
'ErrorCode'=>0,
'Data'=>json_decode($result),
'Message'=>""
);
}

return $this->response($result,200);
}

	///////////////////////////Payment Request/////////////////////////////////////////////////
	public function payment_request_post()
	{ 
		$id 		= $this->input->post('id');
		$access_token = $this->input->post('access_token');
		$url 		= 'https://test.instamojo.com/v2/gateway/orders/id:'.$id;
		$auth 		= "Bearer ".$access_token;
		$payload 	= array('id'=> $id,);
		$headers 	= array('Authorization'=> $auth,
							'Content-type'=> 'application/x-www-form-urlencoded');

		$options 	= array( 'https' => array('headers' => $headers,'method' => 'GET' ));
		//echo "<pre>";print_r($options);exit();
		$context = stream_context_create($options);
		//echo "<pre>";print_r(file_get_contents($url, true, $context));exit();
		$result = file_get_contents($url, false, $context);
		//echo "<pre>";print_r($result);exit();
		if ($result === FALSE) {
			$result=array(
				'ErrorCode'=>1,
				'Data'=>'',
				'Message'=>"Error"
			);
		}else{
			$result=array(
				'ErrorCode'=>0,
				'Data'=>json_decode($result),
				'Message'=>""
			);
		}

		return $this->response($result,200);
	}
	////////////////////////////////////////////////////////////////////



	public function payment_request1_post()
	{
		$id=trim($this->input->post('id'));
		$access_token=trim($this->input->post('access_token'));
		//print_r($id);print_r($access_token);exit;
	/*	$url = 'https://test.instamojo.com/v2/gateway/orders/id:b00045584bf54ab48e234bc005753712';
		$headers 	= array('Authorization'=> 'Bearer JWM1q9ThXodrdJMFxffsvSPzo7jHQz');
		$options = array('https' => array('header' => $headers, 'method' => 'GET'));
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
				if ($result === FALSE) {
			$result=array(
				'ErrorCode'=>1,
				'Data'=>'',
				'Message'=>"Error"
			);
		}else{
			$result=array(
				'ErrorCode'=>0,
				'Data'=>json_decode($result),
				'Message'=>""
			);
		} */
/*		$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization:Bearer $access_token"
  )
);*/

//$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
//$file = file_get_contents("https://www.instamojo.com/v2/gateway/orders/id:$id", false, $context);
//$data=array('response'=>$file);
	//return $this->response(json_decode($file),200);

   //print("\nJSON sent:\n");
   //print($fields);

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, "https://www.instamojo.com/v2/gateway/orders/id:$id/");
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                                                  "Authorization: Bearer $access_token"));
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       curl_setopt($ch, CURLOPT_HEADER, FALSE);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

       $response = curl_exec($ch);
       if(curl_error($ch))
        {
            echo 'error:' . curl_error($ch);
        }
       curl_close($ch);

       	return $this->response(json_decode($response),200);
	}
	
    public function payment_success_post()
	{
		$json_output = json_decode(file_get_contents('php://input'));

		$paymentId=$json_output->paymentId;
		$merchantTransactionId=$json_output->merchantTransactionId;
		$Amount=$json_output->Amount;
		$Status=$json_output->Status;
		$paymentMode=$json_output->paymentMode;
		$customerEmail=$json_output->customerEmail;
		$customerPhone=$json_output->customerPhone;
		$customerName=$json_output->customerName;
		$udf1=$json_output->udf1;
		$udf2=$json_output->udf2;
		$udf3=$json_output->udf3;
		$udf4=$json_output->udf4;
		$udf5=$json_output->udf5;
		$productInfo=$json_output->productInfo;
		$additionalCharges=$json_output->additionalCharges;
		$split_info=$json_output->split_info;
		$error_message=$json_output->error_message;
		$notificationId=$json_output->notificationId;
		$hash=$json_output->hash;
        //$message="";
		//$message="paymentId : ".$paymentId."  merchantTransactionId : ".$merchantTransactionId."  Amount : ".$Amount."  Status : ".$Status."  paymentMode :  ".$paymentMode."  customerEmail :  ".$customerEmail."  customerPhone  : ".$customerPhone."  customerName :  ".$customerName."  udf1 :  ".$udf1."  udf2 :  ".$udf2."  udf3 :  ".$udf3."  udf4 :  ".$udf4."  udf5 :  ".$udf5."  productInfo :  ".$productInfo."  additionalCharges  :  ".$additionalCharges."  split_info :  ".$split_info."  error_message  :  ".$error_message."  notificationId :  ".$notificationId." hash :  ".$hash;
        //$jinson="9947993936";
        //$this->common->sms(str_replace(' ', '', $jinson),urlencode($message));
		//$to_email = 'jinson.gooseberry@gmail.com';
		//$subject = 'payment success5';
		//$headers = 'From: jaziya.gooseberry@gmail.com';
		//mail($to_email,$subject,$message,$headers);
		$result="success";
		return $this->response($result,200);
	}
	
	public function payment_failed_post()
	{
		$json_output = json_decode(file_get_contents('php://input'));

		$paymentId=$json_output->paymentId;
		$merchantTransactionId=$json_output->merchantTransactionId;
		$Amount=$json_output->Amount;
		$Status=$json_output->Status;
		$paymentMode=$json_output->paymentMode;
		$customerEmail=$json_output->customerEmail;
		$customerPhone=$json_output->customerPhone;
		$customerName=$json_output->customerName;
		$udf1=$json_output->udf1;
		$udf2=$json_output->udf2;
		$udf3=$json_output->udf3;
		$udf4=$json_output->udf4;
		$udf5=$json_output->udf5;
		$productInfo=$json_output->productInfo;
		$additionalCharges=$json_output->additionalCharges;
		$split_info=$json_output->split_info;
		$error_message=$json_output->error_message;
		$notificationId=$json_output->notificationId;
		$hash=$json_output->hash;

		//$message="paymentId : ".$paymentId."  merchantTransactionId  :  ".$merchantTransactionId." Amount :  ".$Amount."  Status :  ".$Status."  paymentMode :  ".$paymentMode."  customerEmail :  ".$customerEmail."  customerPhone :  ".$customerPhone."  customerName :  ".$customerName."  udf1 :  ".$udf1." udf2 : ".$udf2."  udf3  : ".$udf3."  udf4  :  ".$udf4."  udf5 :  ".$udf5."  productInfo :  ".$productInfo."  additionalCharges :  ".$additionalCharges."  split_info  :  ".$split_info."  error_message  :  ".$error_message."  notificationId :  ".$notificationId."  hash  :  ".$hash;
   
		//$to_email = 'jinson.gooseberry@gmail.com';
		//$subject = 'payment fail2';
		//$headers = 'From: jaziya.gooseberry@gmail.com';
		//mail($to_email,$subject,$message,$headers);
		$result="success";
		return $this->response($result,200);
	}
	
//////////////////// payumoney webhook 	start /////////////////////////
	public function payu_money_success_post()
	{
		$json_output = json_decode(file_get_contents('php://input'));

		$paymentId=$json_output->paymentId;
		$merchantTransactionId=$json_output->merchantTransactionId;
		$Amount=$json_output->Amount;
		$Status=$json_output->Status;
		$paymentMode=$json_output->paymentMode;
		$customerEmail=$json_output->customerEmail;
		$customerPhone=$json_output->customerPhone;
		$customerName=$json_output->customerName;
		$udf1=$json_output->udf1;
		$udf2=$json_output->udf2;
		$udf3=$json_output->udf3;
		$udf4=$json_output->udf4;
		$udf5=$json_output->udf5;
		$productInfo=$json_output->productInfo;
		$additionalCharges=$json_output->additionalCharges;
		$split_info=$json_output->split_info;
		$error_message=$json_output->error_message;
		$notificationId=$json_output->notificationId;
		$hash=$json_output->hash;

		    $data=array(
          			'transaction_id'=>$merchantTransactionId,
      				'payment_id'=>$paymentId,
      				'payment_mode'=>1,
      			);

		$this->place_model->update_booking($data,$merchantTransactionId);
	/*	$message=" Web hook paymentId : ".$paymentId."  merchantTransactionId  :  ".$merchantTransactionId;

		$jinson="9847338781";
		$this->common->sms(str_replace(' ', '', $jinson),urlencode($message));		*/
		
		$result="success";
		return $this->response($result,200);
	}
//////////////////// payumoney webhook 	end /////////////////////////


}

