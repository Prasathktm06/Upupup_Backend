<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Users extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('users_model');
		$this->load->library('csvreader');
		date_default_timezone_set("Asia/Kolkata");
		if(isset($_SESSION['signed_in'])==TRUE ){

		}else{
			redirect('acl/user/sign_in');
		}
	}
	public function index() {

		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_users')) {	redirect('acl');
		}else{

			$this->load->template('list');
		}
	}


	public function usersTable(){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_users');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_users');
		$table=$this->users_model->get_usersTable($edit,$delete);
		echo json_encode($table);
	}

	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_users')) {
			redirect('acl');
		}
		if($this->input->post()){

			$this->form_validation->set_rules('user',	'User',	'required');
			$this->form_validation->set_rules('phone',	'Phone',	'required|is_unique[venue.phone]');

			$this->form_validation->set_rules('sports[]',	'Sports',	'required|numeric');
			$this->form_validation->set_rules('area[]',	'Area',	'required|numeric');

			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("users/add");

			}else{
				$path="pics/";
        		$image=$this->common->file_upload_image($path);
        		if(!$image){
        			$this->session->set_flashdata('error-msg',"Image Failed");
        			redirect('users');
        		}

				 $data=array(
						'name'=>$this->input->post('user'),
						'phone_no'=>$this->input->post('phone'),
						'address'=>$this->input->post('address'),
						'image'=>$image
				);
				$user_id=$this->users_model->add_users($data);
			 	$sports=$this->input->post('sports[]');
			 	if (!empty($sports)) {

				foreach ($sports as $val){
					$data=array(
					'user_id'=>$user_id,
					'sports_id'=>$val
			);
					$this->users_model->add_users_sports($data);
				}
			}
				$area=$this->input->post('area[]');
				if (!empty($area)) {
				foreach ($area as $val){
					$data=array('user_id'=>$user_id,
							'area_id'=>$val
					);
					$this->users_model->add_users_area($data);
				}
			}
				$co_sports=$this->input->post('co-sports[]');
				$coplayers=$this->input->post('coplayers[]');
				if(!empty($coplayers)&&!empty($co_sports)){
				 foreach ($co_sports as $key=>$val){
					foreach ($coplayers as $key2=> $val2){
						if($key==$key2){
							foreach ($val2 as $val3){
								$data=array(
										'user_id'=>$user_id,
										'co_player'=>$val3,
										'sports_id'=>$val
								);
								$this->users_model->add_coplayer($data);
							}
						}
					}
				}
			}

				$this->session->set_flashdata('success-msg'," User has been added!");
				redirect('users');
			}
			//print_r($_POST);

		}else{
			$data['sports']=$this->users_model->get_details('','sports');
			$data['location']=$this->users_model->get_details('','locations');
			$data['co_players']=$this->users_model->get_details('','users');
			$data['status']=$this->users_model->get_details('','status');
			//print_r($data);exit;
			$this->load->template('add',$data);
		}
	}

	public function edit($id,$data=''){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_users')) {	redirect('acl');
		}
		if($this->input->post()){
			$this->form_validation->set_rules('user',	'User',	'required');
			$this->form_validation->set_rules('phone',	'Phone',	'required');

			$this->form_validation->set_rules('sports[]',	'Sports',	'required|numeric');
			$this->form_validation->set_rules('area[]',	'Area',	'required|numeric');

			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("users/add");
			}else{
				if($_FILES["file"]["tmp_name"]!=""){
				$path="pics/";
        		$image=$this->common->file_upload_image($path);

        		$data['image']=$image;
				}


				$data['name']=$this->input->post('user');
				$data['phone_no']=$this->input->post('phone');
				$data['address']=$this->input->post('address');
				$data['email']=$this->input->post('email');

				$this->users_model->update_users($id,$data);
				$this->users_model->delete($id,'user_area');
				$this->users_model->delete($id,'user_sports');
				$sports=$this->input->post('sports[]');
				if (!empty($sports)) {
				$sports_delete=$this->users_model->delete_preference($id,"user_sports");
				foreach ($sports as $val){
					$data=array('user_id'=>$id,
							'sports_id'=>$val
					);
					$this->users_model->add_users_sports($data);
				}
			}
				$area=$this->input->post('area[]');
				if (!empty($area)) {
					$area_delete=$this->users_model->delete_preference($id,"user_area");
				foreach ($area as $val){
					$data=array('user_id'=>$id,
							'area_id'=>$val
					);
					$this->users_model->add_users_area($data);
				}
			}
				$this->session->set_flashdata('success-msg'," User has been edited!");
				redirect('users');
			}
		}else{
			$data['data']=$this->users_model->get_data($id);
			$data['sports']=$this->users_model->get_details('','sports');
			$data['location']=$this->users_model->get_details('','locations');
			$data['co_players']=$this->users_model->get_details('','users');
			$data['area']=$this->users_model->get_details('','area','location_id');
			$data['status']=$this->users_model->get_details('','status');
			//print_r($data);exit;
			$this->load->template('edit',$data);
		}
	}


	public function _edit_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);

		$count = $this->db->where($field,$str)->where('id !=',$id)->get('users')->num_rows();

		$this->form_validation->set_message('_edit_unique', 'Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	public function delete($user){
	if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_users')) {	redirect('acl');
		}
		$this->users_model->delete_users($user,"users");
		$this->session->set_flashdata('success-msg',' User Deleted!');
		redirect("users");
	}
	public function coplayers($user,$sports){

		$table=$this->users_model->get_coplayers($user,$sports);
		//echo "<pre>";print_r($table);exit();
		echo json_encode($table);
	}
	public function coplayers_rating(){
	$data=array(
			'rating'=>$this->input->post('rating')

	);
		$res=$this->users_model->update_coplayers_rating($this->input->post('co'),$this->input->post('co_sports'),$data);
		$id=$this->input->post('edit');
		redirect("users/edit/$id");
	}

	public function delete_coplayers($co,$sports){

		$res=$this->users_model->delete_coplayers($co,$sports);
		$this->session->set_flashdata('success-msg',' Co-Player Deleted!');
		redirect("users");
	}
	public function add_co_player()
	{
		if($this->input->post()){
			$data=array(
					'user_id'=>$this->input->post('user'),
					'co_player'=>$this->input->post('co_players'),
					'rating'=>$this->input->post('rating'),
					'sports_id'=>$this->input->post('sports_id'),
				);
			$this->users_model->add_coplayer($data);
			$this->session->set_flashdata('success-msg',' Co-Player Added!');
			$user=$this->input->post(user);
			redirect("users/edit/$user");
		}
	}

	public function bulk()
	{
		if($_FILES){
			$path="pics/csv/";
        	$file_name=$this->common->file_upload_csv($path);
        	//print_r($file_name);exit;
        	$result = $this->csvreader->parse_file($path.$file_name);
        	//print_r($result);exit;
        	foreach ($result as $key => $value) {
        		$users[]= array('name' => $value['name'],
        					'phone_no'=>$value['phone'],
        					'area'=>explode('*', $value['area']),
        					'sports'=>explode('*', $value['sports'])
        			);

}
foreach ($users as $key => $value) {
	foreach ($value['area'] as $key2 => $value2) {
		$area[]=$this->users_model->get_area_id($value2);
	}
}
foreach ($users as $key => $value) {
	foreach ($value['sports'] as $key2 => $value2) {
		$sports[]=$this->users_model->get_sport_id($value2);
	}
}
foreach ($users as $key => $value) {
	foreach ($value['area'] as $key2 => $value2) {
		$users[$key]['area']=$area;
	}
}
foreach ($users as $key => $value) {
	foreach ($value['sports'] as $key2 => $value2) {
		$users[$key]['sports']=$sports;
	}
}


        			foreach ($users as $key => $value) {
        				$data= array('name' =>  $value['name'],
        							'phone_no'=>$value['phone_no']
        					);
        				$user_id=$this->users_model->add_users($data);
        				foreach ($value['area'] as $key2 => $value2) {
        					$data2 = array('user_id' => $user_id,
        								'area_id'=>$value2->id
        					 );
        					$this->users_model->add_users_area($data2);
        				}
        				foreach ($value['sports'] as $key3 => $value3) {
        					$data2 = array('user_id' => $user_id,
        								'sports_id'=>$value3->id
        					 );
        					$this->users_model->add_users_sports($data2);
        				}
        			}
        			redirect('users');


		}else{
		$this->load->template('bulk.php');
	}
	}
	//////////////////////////////User Profile///////////////////////////////////////////////////
	public function profile($user_id){
		if($this->input->post()){
            $booking_id=$this->input->post('booking_id');
            $data['booking'] 	= $this->users_model->get_user_booking($booking_id,$user_id);
            
            $data['user_data'] 	=$this->users_model->get_data($user_id);
            $data['my_account'] 	=$this->users_model->get_myaccount($user_id);
        	$data['buy_coin'] 	=$this->users_model->get_buycoins($user_id);
        	$data['refund'] 	=$this->users_model->get_refund($user_id);
        	$data['booking_bonus'] 	=$this->users_model->get_booking_bonus($user_id);
        	$data['install_bonus'] 	=$this->users_model->get_install_bonus($user_id);
        	$data['ref_bk_bonus'] 	=$this->users_model->get_ref_bk_bonus($user_id);
        	$data['user_channel'] 	=$this->users_model->get_user_channel($user_id);
			$sports_list 		=explode(',', $data['user_data']->sports);
			foreach ($sports_list as $key => $value) {
				$condition1 	= array('id' => $value, );
				$sports[] 		= $this->users_model->get_item_data($condition1,'sports','sports');
			}

			if(!empty($sports))
				$data['sports_data']= implode(",", $sports);
			else
				$data['sports_data']= '';

			if(!empty($data['user_data']))
				$area_list 			=explode(',', $data['user_data']->area);
			else
				$area_list=[];

			foreach ($area_list as $key2 => $value2) {
				$condition2 	= array('id' => $value2, );
				$areas[] 		= $this->users_model->get_item_data($condition2,'area','area');
			}
			if(!empty($areas))
			$data['area_data'] 	= implode(",", $areas);
			else
				$data['area_data'] 	= '';
			$location 			= $data['user_data']->location;
			$condition3 			= array('id' => $location, );
			$data['city'] 	= $this->users_model->get_item_data($condition3,'location','locations');
        }else{
        	$data['user_data'] 	=$this->users_model->get_data($user_id);
        	$data['my_account'] 	=$this->users_model->get_myaccount($user_id);
        	$data['buy_coin'] 	=$this->users_model->get_buycoins($user_id);
        	$data['refund'] 	=$this->users_model->get_refund($user_id);
        	$data['booking_bonus'] 	=$this->users_model->get_booking_bonus($user_id);
        	$data['install_bonus'] 	=$this->users_model->get_install_bonus($user_id);
        	$data['ref_bk_bonus'] 	=$this->users_model->get_ref_bk_bonus($user_id);
        	$data['user_channel'] 	=$this->users_model->get_user_channel($user_id);
        	//echo "<pre>";print_r($data);
			$sports_list 		=explode(',', $data['user_data']->sports);
			foreach ($sports_list as $key => $value) {
				$condition1 	= array('id' => $value, );
				$sports[] 		= $this->users_model->get_item_data($condition1,'sports','sports');
			}

			if(!empty($sports))
				$data['sports_data']= implode(",", $sports);
			else
				$data['sports_data']= '';

			if(!empty($data['user_data']))
				$area_list 			=explode(',', $data['user_data']->area);
			else
				$area_list=[];

			foreach ($area_list as $key2 => $value2) {
				$condition2 	= array('id' => $value2, );
				$areas[] 		= $this->users_model->get_item_data($condition2,'area','area');
			}
			if(!empty($areas))
			$data['area_data'] 	= implode(",", $areas);
			else
				$data['area_data'] 	= '';
			$location 			= $data['user_data']->location;
			$condition3 			= array('id' => $location, );
			$data['city'] 	= $this->users_model->get_item_data($condition3,'location','locations');	
	    }
		
		//echo "<pre>";print_r($data);exit();
		$this->load->template('profile',$data);
	}
	////////////////////////////// Refund Amount///////////////////////////////////////////////////
	public function refund($user_id){
	  $booking_id=$this->input->post('booking_id');
	  $refund_amount=$this->input->post('refund_amount');
	  $reason=$this->input->post('reason');
	  $up_coin= $this->users_model->get_up_coin();
	  foreach($up_coin as $row) {
             $coin = $row->coin;
             }
	  $refund_coin=$refund_amount*$coin;
	  $check_refund= $this->users_model->get_refund_check($booking_id);
	  if(empty($check_refund)){
	      $myaccount_check= $this->users_model->get_myaccount_check($user_id);
	  	if(!empty($myaccount_check)){
	  		foreach($myaccount_check as $row) {
             $id = $row->id;
             $up_coin = $row->up_coin;
             $bonus_coin = $row->bonus_coin;
             $total = $row->total;
             }
             $cal_upcoin=$up_coin+$refund_coin;
             $cal_total=$total+$refund_coin;

             $insert_data=array('up_coin'=>$cal_upcoin,
         						'total'=>$cal_total,
         					);
            $this->users_model->update($insert_data,'my_account',$id);
	  	}else{
	  		$data= array(
		          'users_id'=>$user_id,
		          'up_coin'=>$refund_coin,
		          'bonus_coin'=>0,
		          'total'=>$refund_coin,
		          'added_date'=>date('Y-m-d h:i:sa'),
		        			);
	  		$myac=$this->users_model->add_my_account_details($data);
	  	}
			  	$data= array(
			      'booking_id' =>  $booking_id,
		          'users_id'=>$user_id,
		          'amount'=>$refund_amount,
		          'coin'=>$refund_coin,
		          'reason'=>$reason,
		          'date'=>date('Y-m-d'),
		          'status'=>1,
		          'added_date'=>date('Y-m-d h:i:sa'),
		        			);
		      $refund_id=$this->users_model->add_refund_details($data);
		      $this->session->set_flashdata('success-msg','Amount Refunded');
			  redirect("users/profile/$user_id");
	  }else{
	  		$this->session->set_flashdata('error-msg','Al-ready amount refunded');
			redirect("users/profile/$user_id");
	  }
	  
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
}
