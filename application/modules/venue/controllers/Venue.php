<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Venue extends CI_controller {

private $acl_table;
public function __construct() {
	date_default_timezone_set('Asia/Kolkata');
	
	parent::__construct();
	$this->load->library('form_validation');
	$this->load->helper(array('url'));
	$this->load->model('venue_model');
	$this->load->library('csvreader');
	$this->load->model('reports/reports_model');
	$this->acl_conf = (object)$this->config->item('acl');
	$this->acl_table =& $this->acl_conf->table;
	if(isset($_SESSION['signed_in'])==TRUE ){
	}else{
		redirect('acl/user/sign_in');
	}
}

	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_venue')) {
			redirect('acl');
		}else{

			$venue_manager="";
		$role=$this->venue_model->get_role($this->session->userdata('user_id'));
		//print_r($role);exit;
		if($role->venue_users==1){
			$venue_manager=$this->session->userdata('user_id');
		}
                if($role->venue_users==3){
				$venue_manager=$this->session->userdata('user_id');
			}
		$data['list']=$this->venue_model->get_venuedetails($venue_manager);
		//echo "<pre>";print_r($data);exit();
		$this->load->template('list',$data);
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////
	public function venueTable(){
		$venue_manager="";
		$role=$this->venue_model->get_role($this->session->userdata('user_id'));
		//print_r($role);exit;
		if($role->venue_users==1){
			$venue_manager=$this->session->userdata('user_id');
		}
                if($role->venue_users==3){
				$venue_manager=$this->session->userdata('user_id');
			}
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_venue');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_venue');
		$table=$this->venue_model->get_venueTable($edit,$delete,$venue_manager);
		echo json_encode($table);
	}
	///////////////////////////////////////////////////////////////////////////////////////////////
	public function venue_add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_venue')) {
			redirect('acl');
		}
		if($this->input->post()){

			$this->form_validation->set_rules('venue',	'Venue',	'required|is_unique[venue.venue]');
			//$this->form_validation->set_rules('location',	'location',	'required');
			//$this->form_validation->set_rules('area',	'area',	'required');
			//$this->form_validation->set_rules('phone',	'Phone Number',	'required');
			$this->form_validation->set_rules('address',	'address',	'required');
			$this->form_validation->set_rules('lat',	'lat',	'required');
			$this->form_validation->set_rules('lon',	'lon',	'required');
			$this->form_validation->set_rules('speciality[]',	'lon',	'required');
			///$this->form_validation->set_rules('hostedBy',	'HostedBy',	'required');
			$this->form_validation->set_rules('book',	'Book',	'required');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("venue/venue_add");
			}else{
				$path="pics/venue/";
        		$image=$this->common->file_upload_image($path);
        		$morn=$this->input->post('morn');
        		$even=$this->input->post('even');
				$data=array(
						'venue'=>$this->input->post('venue'),
						'morning'=>$morn,
						'evening'=>$even,
						'area_id'=>$this->input->post('area'),
						'phone'=>$this->input->post('phone'),
						'address'=>$this->input->post('address'),
						'description'=>$this->input->post('desc'),
						'lat'=>$this->input->post('lat'),
						'lon'=>$this->input->post('lon'),
						'image'=>str_replace(" ","%20",$image),
						'amount'=>$this->input->post('amount'),
						'book_status'=>$this->input->post('book'),
						'hostedBy'=>$this->session->userdata('user_id'),
						'status'=>$this->input->post('status')
				);
				$venue_id=$this->venue_model->add($data,'venue');
				$speciality=$this->input->post('speciality');
				$sports=$this->input->post('sports');
				$court=$this->input->post('court');
				if(!empty($speciality)){
					foreach ($speciality as $key => $value) {
						$data=array(
								'venue_id'=>$venue_id,
								'facility_id'=>$value
							);
						$this->venue_model->add($data,'venue_facilities');
					}
				}


				foreach ($sports as $key => $value) {
					$data=array(
						'venue_id'=>$venue_id,
						'sports_id'=>$value
						);
					$this->venue_model->add($data,'venue_sports');
				}
				$data=array(
						'user_id'=>$this->session->userdata('user_id'),
						'venue_id'=>$venue_id
					);
				$this->venue_model->add($data,'venue_manager');
				$this->session->set_flashdata('success-msg','New Venue Added.<br>Please specify court in venue edit!');
				redirect('venue');
			}
		}else{
			$data['locations']= $this->venue_model->get_location();
			$data['speciality']=$this->venue_model->get_speciality();
			$data['sports']=$this->venue_model->get_sports();
			$data['court']=$this->venue_model->get_court();
			$data['users']=$this->venue_model->get_users();

			$this->load->template('venue/venue_add',$data);
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////
	public function venue_edit($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_venue')) {
			redirect('acl');
		}
		if($this->input->post()){
			//print_r( $this->input->post('phone'));exit;
			$this->form_validation->set_rules('venue',	'Venue',	'required|callback__venue_unique[venue.'.$id.']');
			$this->form_validation->set_rules('location',	'location',	'required');
			$this->form_validation->set_rules('area',	'area',	'required');

			//$this->form_validation->set_rules('phone',	'phone',	'required');
			$this->form_validation->set_rules('address',	'address',	'required');
			$this->form_validation->set_rules('desc',	'Description',	'required');
			$this->form_validation->set_rules('lat',	'lat',	'required');
			$this->form_validation->set_rules('lon',	'lon',	'required');
			//$this->form_validation->set_rules('court[]',	'Court',	'required');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("venue/venue_edit/$id");
			}else{
				if($_FILES["file"]["tmp_name"]!=""){
				$path="pics/";
        		$image=$this->common->file_upload_image($path);
        		
        		$data['image']=str_replace(" ","%20",$image);

				}

				$data['venue']=$this->input->post('venue');
				$data['morning']=$this->input->post('morn');
				$data['evening']=$this->input->post('even');
				$data['area_id']=$this->input->post('area');
				$data['phone']=$this->input->post('phone');
				$data['address']=$this->input->post('address');
				$data['description']=$this->input->post('desc');
				$data['amount']=$this->input->post('amount');
				$data['lat']=$this->input->post('lat');
				$data['lon']=$this->input->post('lon');
				$data['status']=$this->input->post('status');
				$data['book_status']=$this->input->post('book');

				$this->venue_model->update_venue($id,$data);
				/*$this->venue_model->delete_venue_court($id);
				$court=$this->input->post('court');
				foreach ($court as $key => $value) {
					$data=array(
						'venue_id'=>$id,
						'court_id'=>$value
						);
					$this->venue_model->add($data,'venue_court');
				}*/
				$this->session->set_flashdata('success-msg','Venue Edited!');
				redirect("venue/venue_edit/$id");
			}

		}else{
       		$data['holidays']= $this->venue_model->get_holidays($id);
       		//print_r($data['holidays']);exit;
			$data['locations']= $this->venue_model->get_location();
			$data['venue']= $this->venue_model->get_venue_details($id);
			//	print_r($data['venue']);
			$locations_id=$this->venue_model->get_venue_location($data['venue']->area_id);
			//print_r($locations_id);
			$data['location']=$locations_id;
			//	print_r($locations_id);exit;
			$data['area']=$this->venue_model->get_venue_area($locations_id->id);
			$data['speciality']=$this->venue_model->get_speciality();
			$data['sports']=$this->venue_model->get_sports();
			$data['venue_sports']=$this->venue_model->get_venue_sports($id);
			//print_r($data['venue_sports']);exit;
			$data['court']=$this->venue_model->get_court();
			$data['gallery']=$this->venue_model->get_image($id);
                        $data['bookings']=$this->venue_model->get_venue_book($id);
                        
                        $data['inactive']=$this->venue_model->get_inactive_court($id);
                        $data['share']=$this->venue_model->get_userapp_share($id);
                        $data['venue_rating']=$this->venue_model->get_venue_rating($id);
                        //echo "<pre>";print_r($data['venue_rating']);exit();
                        $data['user_app']=$this->venue_model->get_userapp_share($id);
			$data['vendor_app']=$this->venue_model->get_vendorapp_share($id);
                        $data['offer']=$this->venue_model->get_venue_offer($id);
                                        foreach ($data['offer'] as $key => $value) {
				                $data['offer'][$key]['days_off']	= $this->venue_model->get_venue_offerdays($value['id']);
				                $data['offer'][$key]['offer_court']	= $this->venue_model->get_venue_offercourt($value['id']);
					        $data['offer'][$key]['offer_sports']	= $this->venue_model->get_venue_offersports($value['id']);
		
						}
		    			foreach ($data['offer'] as $key2 => $value2) {
                                               $data['offer'][$key2]['offer_daypar']  =implode(",",$value2['days_off']);
                                               $data['offer'][$key2]['offer_courtlist']  =implode(",",$value2['offer_court']);
                                               $data['offer'][$key2]['offer_sportslist']  =implode(",",$value2['offer_sports']);
                                               }
                          $data['inactive_court']=$this->venue_model->get_venue_inactive($id);
                          $data['hot_offer']=$this->venue_model->get_venue_hot_offer($id);
            			foreach ($data['hot_offer'] as $key6 => $value6) {
				    	$data['hot_offer'][$key6]['sports']	= $this->venue_model->get_hot_sports($value6['hot_id']);
				    	$data['hot_offer'][$key6]['courts']	= $this->venue_model->get_hot_courts($value6['hot_id']);
				   	$data['hot_offer'][$key6]['slots']	= $this->venue_model->get_hot_slots($value6['hot_id']);
		
					}
		
				foreach ($data['hot_offer'] as $key7 => $value7) {
                    			$data['hot_offer'][$key7]['hot_offer_sports']  =implode(",",$value7['sports']);
                    			$data['hot_offer'][$key7]['hot_offer_courts']  =implode(", ",$value7['courts']);
                    			$data['hot_offer'][$key7]['hot_offer_slots']  =implode(", ",$value7['slots']);
                    
                     }
                         //echo "<pre>";print_r($data['inactive_court']);exit();
                        //echo "<pre>";print_r($data['user_app']);
                        //echo "<pre>";print_r($data['vendor_app']);exit();
			//$data['vendor_app']=$this->venue_model->get_vendorapp_share($id);
			//print_r($data['inactive']);exit;
                   /*booking from upupup app start */
                        $data['list'] 		= $this->reports_model->get_booking_list('','',$id);
		
						foreach ($data['list'] as $key => $value) {
							$data['list'][$key]['booked_slots']	= $this->reports_model->get_booked_slots($value['booking_id']);
		
						}//echo "<pre>";print_r($data);exit();
						foreach ($data['list'] as $key3 => $value3) {
							$data['list'][$key3]['total_capacity']	= $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;
		
						}
						foreach ($data['list'] as $key2 => $value2) {
							$data['list'][$key2]['duration']	=count($value2['booked_slots'])*$value2['intervel'];
							$data['list'][$key2]['time_slots'] 	=implode("\n",$value2['booked_slots']);
						}
		/*booking from upupup app end */ 	
                /*booking from vendors app start */ 
                         $data['vendor']       = $this->reports_model->get_vendorbooking_list('','',$id);
                                               foreach ($data['vendor'] as $key => $value) {
                                               $data['vendor'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);

                                               }//echo "<pre>";print_r($data);exit();
                                               foreach ($data['vendor'] as $key3 => $value3) {
                                               $data['vendor'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                                               }
                                               foreach ($data['vendor'] as $key2 => $value2) {
                                               $data['vendor'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                                               $data['vendor'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                                               }
                /*booking from vendors app end */
                /* Cancel booking from vendors app start */
                 $data['bookcan']       = $this->reports_model->get_cancelbooking_list('','',$id);
                                              foreach ($data['bookcan'] as $key => $value) {
                                              $data['bookcan'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);

                                              }//echo "<pre>";print_r($data);exit();
                                              foreach ($data['bookcan'] as $key3 => $value3) {
                                              $data['bookcan'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                                              }
                                              foreach ($data['bookcan'] as $key2 => $value2) {
                                              $data['bookcan'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                                              $data['bookcan'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                                              }
              /* Cancel booking from vendors app end */
			$this->load->template('venue/venue_edit',$data);
		}
	}
	////////////////////////////////Speciality	///////////////////////////////////////////////////
	public function speciality(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

		}else{
			$this->load->template('speciality_list');
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////
	public function speciality_add(){
		if($this->input->post()){
			$this->form_validation->set_rules('speciality',	'Speciality',	'required|is_unique[facilities.facility]');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("venue/speciality_add");
			}else{
				$data=array(
						'facility'=>$this->input->post('speciality')
				);
				$this->venue_model->add($data,'facilities');
				$this->session->set_flashdata('success-msg','New Facility Added!');
				redirect('venue/speciality');
			}

		}else{
			$this->load->template('venue/speciality_add');
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	public function speciality_edit($id){
		if($this->input->post()){
			$this->form_validation->set_rules('speciality',	'Speciality',	'required|callback__facility_unique[facility.'.$id.']');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("venue/speciality_edit/$id");
			}else{
				$data=array(
						'facility'=>$this->input->post('speciality')
				);
				$this->venue_model->update_speciality($id,$data);
				$this->session->set_flashdata('success-msg','Facility Edited!');
				redirect('venue/speciality');
			}

		}else{
			$data['speciality']=$this->venue_model->get_speciality($id);
			$data['id']=$id;
			$this->load->template('venue/speciality_edit',$data);
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	public function speciality_delete($id){
		$this->venue_model->delete_speciality($id);
		$this->session->set_flashdata('success-msg','Facility Deleted!');
				redirect("venue/speciality");
	}
	////////////////////////////////////////////////////////////////////////////////////////////
	public function specialityTable(){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_facility');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_facility');
		$table=$this->venue_model->get_specialityTable($edit,$delete);
		echo json_encode($table);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	public function edit_venue_speciality($id){
		if($this->input->post()){
			$this->venue_model->delete_venue_speciality($id);
			$speciality=$this->input->post('speciality');
			foreach ($speciality as $val){
				$data=array(
					'venue_id'=> $id,
					'facility_id'=>$val
				);
				$this->venue_model->add($data,'venue_facilities');
			}

		}
		$this->session->set_flashdata('success-msg','Venue Facility Edited!');
		redirect("venue/venue_edit/$id?facility=1");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
	public function edit_venue_sports($id){
		if($this->input->post()){
			$this->venue_model->delete_venue_sports($id);
			$sports=$this->input->post('sports');
			foreach ($sports as $val){
				$data=array(
						'venue_id'=> $id,
						'sports_id'=>$val
				);
				$this->venue_model->add($data,'venue_sports');
			}
		}
		$this->session->set_flashdata('success-msg','Venue Sports Edited!');
		redirect("venue/venue_edit/$id?sport=1");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////
	public function _facility_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);

		$count = $this->db->where($field,$str)->where('id !=',$id)->get('facilities')->num_rows();
		$this->form_validation->set_message('_edit_unique', 'Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////
	public function _venue_unique($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $field,$id);

		$count = $this->db->where($field,$str)->where('id !=',$id)->get('venue')->num_rows();

		$this->form_validation->set_message('_edit_unique', 'Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	///////////////////////////////////////////////////////////////////////////////////////////
	public function delete($id)
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_venue')) {
			redirect('acl');
		}
		$this->venue_model->delete($id);
		$this->session->set_flashdata('success-msg','Venue Deleted!');
				redirect("venue/");
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	public function bulk()
	{
		$this->load->library('excelphp');
		if($_FILES){

			$path="pics/csv/";
        	$file_name=$this->common->file_upload_csv($path);
        	//print_r($file_name);exit;
        	$result = $this->excelphp->parse_file($path.$file_name);
        	//print_r($result);exit;
        	foreach ($result['values'] as $key => $value) {

        		$venue=$this->venue_model->get_venue($value['venue']);
        		$area_id=$this->venue_model->get_area($value['area']);
        			$facility_id=$this->venue_model->get_facility($value['facilities']);
        			$role=$this->venue_model->get_role2($value['role']);
        		//	print_r($role);exit;
        			$sports_id=$this->venue_model->get_sport($value['sports']);
        		if($venue==''){


        			$data=array(
						'venue'=>$value['venue'],
						'morning'=>$value['opening'],
						'evening'=>$value['closing'],
						'area_id'=>$area_id->id,
						'phone'=>$value['phone'],
						'address'=>$value['address'],
						'description'=>$value['description'],
						'lat'=>$value['lat'],
						'lon'=>$value['lon'],
						'book_status'=>$value['book_status'],
						'amount'=>$value['pay_at_venue'],


				);

        			$venue_id=$this->venue_model->add($data,'venue');
        			$data=array(
        					'venue_id'=>$venue_id,
        					'sports_id'=>$sports_id->id

        				);
        			$this->venue_model->add($data,'venue_sports');
        			$data=array(
        					'name'=>$value['name'],
        					'email'=>$value['email'],
        					'password'=>$value['password'],
        					'status'=>1
        				);
        			$user_id=$this->venue_model->add($data,'user');
        			//print_r($user_id);exit;
        			$data=array(
        					'user_id'=>$user_id,
        					'role_id'=>$role->role_id
        				);
        			$this->venue_model->add($data,'user_role');
        			$data=array(
        					'user_id'=>$user_id,
        					'venue_id'=>$venue_id
        				);
        			$this->venue_model->add($data,'venue_manager');
        			if(!empty($facility_id)){
        			$data=array(
        					'venue_id'=>$venue_id,
        					'facility_id'=>$facility_id->id
        				);
        			$this->venue_model->add($data,'venue_facilities');
        		}
        			$data=array(
        					'court'=>$value['court'],
        					'cost'=>$value['cost'],
        					'intervel'=>$value['interval'],
        					'sports_id'=>$sports_id->id
        				);
        			$court_id=$this->venue_model->add($data,'court');
        			$data=array(
        					'court_id'=>$court_id,
        					'time'=>$value['court_time'],
        					'week'=>$value['week']

        				);
        				$this->venue_model->add($data,'court_time');
        			$data=array(
        					'venue_id'=>$venue_id,
        					'court_id'=>$court_id

        				);
        			$this->venue_model->add($data,'venue_court');

        		}else{

        			$sports_id=$this->venue_model->get_sport($value['sports']);
        			$court_sports=$this->venue_model->get_sport($value['court_sport']);
        			if(!empty($facility_id)){
        			$data=array(
        					'venue_id'=>$venue->id,
        					'facility_id'=>$facility_id->id
        				);

        			$this->venue_model->add($data,'venue_facilities');
        		}
        			if(!empty($sports_id)){
        			$data=array(
        					'venue_id'=>$venue->id,
        					'sports_id'=>$sports_id->id

        				);
        			$this->venue_model->add($data,'venue_sports');
        			}
        			$court_id=$this->venue_model->get_court2($value['court'],$venue->id);
        			if(empty($court_id)){
        			$data=array(
        					'court'=>$value['court'],
        					'cost'=>$value['cost'],
        					'sports_id'=>$court_sports->id
        				);
        			$court_id=$this->venue_model->add($data,'court');
        			$data=array(
        					'venue_id'=>$venue->id,
        					'court_id'=>$court_id

        				);
        			$this->venue_model->add($data,'venue_court');
        		}else{
        			$court_id=$court_id->id;
        		}
        				if($value['court_time']!=''){
        				$data=array(
        					'court_id'=>$court_id,
        					'time'=>$value['court_time'],
        					'week'=>$value['week']

        				);
        				$this->venue_model->add($data,'court_time');
        			}


        		}
        	}
        	redirect("venue/");

        }else{
		$this->load->template('bulk.php');
	}
	}
	///////////////////////////////////////////////////////////////////////////////
	public function holiday()
	{
                         $venue_id=$this->input->post('venue');
		$da=$this->input->post('day');
		$dat=$this->input->post('days');
		$description=$this->input->post('desc');
                $diff = abs(strtotime($dat) - strtotime($da));
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                for($i=0;$i<=$days;$i++){
                	$date = strftime("%Y-%m-%d", strtotime("$da +$i day"));
                    $check=$this->venue_model->check_booking($venue_id,$date);

                     if($check)
                     {
                      $ksc=1;
                     }else{
                       if($ksc==1){
              	         $ksc=1;
                           }else{
              	            $ksc=0;
                             }
                          }
                }
                if($ksc==0){
                 for ($j=0; $j <= $days ; $j++) { 
                 	$date = strftime("%Y-%m-%d", strtotime("$da +$j day"));
                 	$checks=$this->venue_model->check_holiday($venue_id,$date);
                 	if($checks)
                     {
                      $ktc=1;
                     }else{
                       if($ktc==1){
              	         $ktc=1;
                           }else{
              	            $ktc=0;
                             }
                          }
                 }
                 if($ktc==0){
                     for ($k=0; $k <= $days; $k++) { 
                     $date = strftime("%Y-%m-%d", strtotime("$da +$k day"));
                     $data=array(
				             'venue_id'=>$venue_id,
				             'date'=>$date, 
				             'description'=>$description
			               );	
                      $che=$this->venue_model->add_holidays($data);
                      if($checks)
                     {
                      $ktt=1;
                     }else{
                       if($ktt==1){
              	         $ktt=1;
                           }else{
              	            $ktt=0;
                             }
                          }
                     }
                     if ($ktt==1) {
                     	echo "success";
                     }else{
                     	echo "fail";
                     }
                 }else{
                 	echo "alreadyadded";
                 }
                }else{
                	echo "booking";
                }
	}
        public function bookeddays()
	{
                 $venue_id=$this->input->post('venues') ;
		$data=$this->venue_model->check_bookeddays($venue_id);
		$abc =json_encode($data);
		  //$this->load->library('email');

              //$this->email->from('jinsonjose007@gmail.com', 'testing');
              //$this->email->to('jinson.gooseberry@gmail.com');
              //$this->email->subject('added holiday');
               //$this->email->message('Hi upUPUP,'."\n\n".' '.$abc.' ');
              //$this->email->send();

	}
	///////////////////////////////////////////////////////////////////////////////////////////////////
	public function holiday_delete($id,$venue)
	{

		$this->venue_model->delete_holidays($id);
		$this->session->set_flashdata('success-msg',' Holiday Deleted!');
		redirect("venue/venue_edit/$venue?day=1");

	}
	public function venueUsersTable($venue_id)
	{
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_venue_user');
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_venue_user');
			$table=$this->venue_model->get_venueUsersTable($edit,$delete,$venue_id);
			echo json_encode($table);
	}

	public function add_gallery($path='')
	{
		$id=$this->input->post('venue') ;
		$imagedetails = getimagesize($_FILES['file']['tmp_name']);
		$width = $imagedetails[0];
		$height = $imagedetails[1];

		if(false){
	        $this->session->set_flashdata('error-msg','Sorry wrong resolution!');
			redirect("venue/venue_edit/$id?gallery=1");
	    }else{
	    	$path="pics/venue/";
	    	$image=$this->common->file_upload_image($path);
	    	$data=array('venue_id' =>$this->input->post('venue') ,
	    				'image'=>$image
	    	 		);
			$this->venue_model->add_gallery($data);
			$this->session->set_flashdata('success-msg','Image Added Successfully!');
			redirect("venue/venue_edit/$id?gallery=1");
	    }
	}
	//////////////////////////////////////////////////////////////////////////
	public function upload(){

	//	print_r($data);
	}
	public function change_status($id,$status="")
	{ //print_r($status);exit();
		if($id){
			if($status){
				$insert_data=array('status'=>'0');
			}else{
				$insert_data=array('status'=>'1');
			}
			$result=$this->venue_model->update_venue($id,$insert_data);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('venue');
			}
		}
	}
	////////////////////////////////Venue Managers///////////////////////////////////////////////////
	public function venue_managers(){
		/*if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

		}else{
			$this->load->template('speciality_list');
		}*/
		$data['managers']=$this->venue_model->venue_managers();
		foreach ($data['managers'] as $key => $value) {
			$data['managers'][$key]['courts']=implode(',',$this->venue_model->court_assigned($value['user_id']));
		}
		//echo "<pre>";print_r($data);exit();
		$this->load->template('venue_managers',$data);
	}
	///////////////////////////////////Venue Manager Add///////////////////////////////////
	public function add_manager(){
		/*$data['roles']=$this->acl_model->get_all_roles2();
		$data['venue']=$this->venue_model->get_venues();
		$this->load->template('add_user',$data);*/

		$this->form_validation->set_rules('name','Name','trim|required|max_length[70]');
		$this->form_validation->set_rules('email','Email','trim|strtolower|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('roles','Roles ','required');
		$this->form_validation->set_rules('venue','Venue ','required');

		if($this->form_validation->run() == FALSE) {
			$data['roles']=$this->acl_model->get_all_roles2();
			$data['venue']=$this->venue_model->get_venues();

         	$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
			$this->load->template('add_user', $data, FALSE, 'back');
		}else {
			$path="pics/users/";
        	$image=$this->common->file_upload_image($path);
			$data = array(
				'name'		=> $this->input->post('name'),
				'email'		=> $this->input->post('email'),
				'password'	=> hash('sha512', $this->input->post('password')),
				'phone'		=> $this->input->post('phone'),
				'image'		=>	$image
			);
			$roles = $this->input->post('roles');

			if($id=$this->acl_model->add_user($data) ) {
				$data=array(
							'user_id'=>$id,
							'venue_id'=>$this->input->post('venue')
						);
				$this->acl_model->add_venue_manager($data);
				if ($this->input->post('court')) {
					$court = $this->input->post('court');
					foreach ($court as $key5 => $value5) {
						$court_array = array('user_id' 	=> $id,
											 'court_id' => $value5, );
						$res=$this->acl_model->court_manager_court($court_array);
					}
				}

				if($this->acl_model->add_user_role($id,$roles)){
					$this->session->set_flashdata('success-msg','New User has been added!');
					$message="Email: ".$this->input->post('email')."<br>"."Password:".$this->input->post('password')."<br>Login credentials <a href='http://app.appzoc.com/upupup/'>UPUpUP</a>";
					$subject='Congratulations';
          			//$subject="some text";
		          	$message=$message;
		          	$to_email = $this->input->post('email')	;
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

					redirect('venue/venue_managers');
				}
			}
			else {
				show_error('Failed to add user.');
			}
		}
	}
	/////////////////////Court List///////////////////////////////////////////////////////
	public function court_list(){
		$venue 	= $this->input->post('venue');
		$user_id 	= $this->input->post('user_id');
		$data['court']		=$this->venue_model->get_court_venue($venue,$status="1");
		$data['court_assigned']=$this->acl_model->court_assigned($user_id);
		//echo "<pre>";print_r($data);exit();
		if ($data) {
			echo json_encode($data);
		}
	}
	/////////////////////////////Change Status//////////////////////////////////////////////////
	public function user_change_status($id,$status="")
	{
		if($id){
			if($status){
				$insert_data=array('status'=>'0');
			}else{
				$insert_data=array('status'=>'1');
			}
			$result=$this->acl_model->edit_user($id,$insert_data);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('venue/venue_managers');
			}
		}
	}
	/////////////////////////Delete Venue user////////////////////////////////////////
	public function delete_venue_users($user_id,$venue="")
	{
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_venue_user')) {
		redirect('acl');
		}
		if ($venue) {
			$this->acl_model->delete_venue_users($user_id,$venue);
		}

		$this->venue_model->venue_user_delete($user_id);
		redirect('venue/venue_managers');
	}
	///////////////////////////Edit Manager//////////////////////////////////////////////////
	public function edit_manager($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_user')&& !$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_venue_user')) {
			redirect('acl');
		}
		if($this->input->post()){
	      	$this->form_validation->set_rules('name',		'Name',	'required');
		  	$this->form_validation->set_rules('email',	'Email',	'trim|strtolower|required|valid_email|callback__check_user_email['.$id.'.email]');

			if($this->form_validation->run() == FALSE) {

				$this->session->set_flashdata('error-msg',trim(validation_errors()));
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect('venue/edit_manager/'.$id);
			}else{
				if (!empty($_FILES["file"]["name"])) {
					$path="pics/users/";
        			$image=$this->common->file_upload_image($path);
				}else{
        			$image=$this->input->post('img_old');
        		}
				$data=array(
					'name'   	=>$this->input->post('name'),
					'email'  	=>$this->input->post('email'),
					'phone'		=> $this->input->post('phone'),
					'image'		=>	$image
				);
    			$venue_id =$this->input->post('venue');
    			if ($venue_id) {
    				$venue_id_exist=$this->venue_model->venu_manager_venue($id);
    				if ($venue_id_exist) {
    					$venue_manager_update=$this->venue_model->venue_manager_update($id,array('venue_id' => $venue_id, ));
    				}else{
    					$data_venue=array(
							'user_id'=>$id,
							'venue_id'=>$venue_id
						);
						$this->acl_model->add_venue_manager($data_venue);
    				}
    			}


				$role_data=array(
					'role_id' 	=>$this->input->post('roles'),
				);
				if ($venue_id) {
					$res1=$this->acl_model->court_manager_court_delete($id);
					if ($this->input->post('court')) {
						$court = $this->input->post('court');
						foreach ($court as $key5 => $value5) {
							$court_array = array('user_id' 	=> $id,
												 'court_id' => $value5, );
							$res=$this->acl_model->court_manager_court($court_array);
						}
					}
				}
				$this->acl_model->update_user_role($role_data,$id);
				if($this->input->post('password')!=""){
					$password=hash('sha512',$this->input->post('password'));
					$data['password'] = $password;
				}
				$this->acl_model->edit_user($id,$data);

				$this->session->set_flashdata('success-msg',"User has been updated");
				redirect('venue/venue_managers');
			}
		}else{
			$data['profile']= $this->acl_model->get_user_by('user_id',$id);
			//$data['roles'] = $this->acl_model->get_all_roles();
			$data['user_roles'] = $this->acl_model->get_user_roles($id);
			$data['roles']=$this->acl_model->get_all_roles2();
			$data['venue']=$this->venue_model->get_venues();
			$data['court_assigned']=$this->acl_model->court_assigned($id);
			$data['venue_id']=$this->venue_model->venu_manager_venue($id);
			$data['role_id']=$this->venue_model->venu_manager_role($id);
			//echo "<pre>";print_r($data);exit();
			$this->load->template('edit_user',$data);
		}
	}
	/////////////////////////////////////////////////////////////////////////////////
	public function _check_user_email($str, $id)
	{
		sscanf($id, '%[^.].%[^.]', $id, $field);
		$count = $this->db->where($field,$str)->where('user_id !=',$id)->get($this->acl_table['user'])->num_rows();

		$this->form_validation->set_message('_check_user_email', 'Entered Email Already Exists.');
		return ($count>=1) ? FALSE:TRUE;
	}
	///////////////////////// Share start ////////////////////////////////////////////
   	public function add_share()
	{
        $venue_id=$this->input->post('venue_id');
        $share=$this->input->post('share');
        $user_app=$this->input->post('user_app');
        //echo "<pre>";print_r($venue_id);
        //echo "<pre>";print_r($share);
        //echo "<pre>";print_r($user_app);exit();

        if($user_app == "user_app"){
          $data = array(
				'venue_id'	=> $venue_id,
				'share'		=> $share,
				'status'	=> '1',
				
			);
          $this->venue_model->insert_userapp_share($data);
          $this->session->set_flashdata('success-msg','User App Share Added Successfully!');
		  redirect("venue/venue_edit/$venue_id?share=1");
        }else{
           if($user_app == "vendor_app"){
             $data = array(
				'venue_id'	=> $venue_id,
				'share'		=> $share,
				'status'	=> '1',
				
			);
          $this->venue_model->insert_vendorapp_share($data);
          $this->session->set_flashdata('success-msg','Vendor App Share Added Successfully!');
		  redirect("venue/venue_edit/$venue_id?share=1");
           }	
        }
	}
	///////////////////////// Share end ////////////////////////////////////////////
       //////////////////////////////////////Change Status///////////////////////////////////////////
	public function share_change_status($venue_id,$id,$status="",$forwhom)
	{ 

		if($forwhom=="user_app"){
		if($id){
			if($status){
				$data=array('status'=>'0');
			}else{
				$data=array('status'=>'1');
			}
			$this->venue_model->update_usershare($id,$data);
			$this->session->set_flashdata('success-msg','Status has been changed!');
				redirect("venue/venue_edit/$venue_id?share=1");
		}	
	}else{
		if($forwhom=="vendor_app"){
		if($id){
			if($status){
				$data=array('status'=>'0');
			}else{
				$data=array('status'=>'1');
			}
			$this->venue_model->update_vendorshare($id,$data);
			$this->session->set_flashdata('success-msg','Status has been changed!');
				redirect("venue/venue_edit/$venue_id?share=1");
		}	
		}
		
	}
	}
        //////////////////////////////// User app Share Edit//////////////////////////////////////////////
public function userapp_share_edit($venue_id,$id)
	{
		
		//echo "<pre>";print_r($venue_id);
		//echo "<pre>";print_r($id);exit();
        $share_value=$this->venue_model->get_userapp_details($venue_id,$id);
        $data['shares']=$share_value;
        //echo "<pre>";print_r($data['userapp']);exit();
		$this->load->template('edit_userapp_share',$data);
	}
//////////////////////////////// Vendor app Share Edit//////////////////////////////////////////////
public function vendorapp_share_edit($venue_id,$id)
	{
		
		$share_value=$this->venue_model->get_vendorapp_details($venue_id,$id);
		$data['shares']=$share_value;
        //echo "<pre>";print_r($data['vendor_app']);exit();

		$this->load->template('edit_vendorapp_share',$data);
	}
///////////////////////// Update User app share ////////////////////////////////////////////
   	public function share_userapp_edit()
	{
        $venue_id=$this->input->post('venue_id');
        $id=$this->input->post('userapp_share_id');
        $share=$this->input->post('share');
        $data=array('share'=>$share);
        $this->venue_model->update_userapp_share($id,$data);
	$this->session->set_flashdata('success-msg','User App Share Value Updated!');
        redirect("venue/venue_edit/$venue_id?share=1");
	}
///////////////////////// Update vendor app share ////////////////////////////////////////////
   	public function share_vendorapp_edit()
	{
        $venue_id=$this->input->post('venue_id');
        $id=$this->input->post('vendorapp_share_id');
        $share=$this->input->post('share');
        $data=array('share'=>$share);
        $this->venue_model->update_vendorapp_share($id,$data);
	    $this->session->set_flashdata('success-msg','Vendor App Share Value Updated!');
        redirect("venue/venue_edit/$venue_id?share=1");
	}
///////////////////////// Delete user app share ////////////////////////////////////////////
   	public function userapp_share_delete($venue_id,$id)
	{
        $this->venue_model->delete_userapp_share($id);
	    $this->session->set_flashdata('success-msg','User App Share Value Deleted!');
        redirect("venue/venue_edit/$venue_id?share=1");
	}
///////////////////////// Delete vendor app share ////////////////////////////////////////////
   	public function vendorapp_share_delete($venue_id,$id)
	{
        $this->venue_model->delete_vendorapp_share($id);
	    $this->session->set_flashdata('success-msg','Vendor App Share Value Deleted!');
        redirect("venue/venue_edit/$venue_id?share=1");
	}

}
