<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Court extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('court_model');

	}
	public function index()
	{

		$data=array(
				'user_id'=>$this->session->userdata('user_id')
			);
		$data['all_venue']=$this->court_model->get_venue();
		$this->load->template('list',$data);
	}
		public function get_courtTable_manager($user_id)
		{
			$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_court');
			$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_court');
			$table=$this->court_model->get_courtTable_manager($edit,$delete,$user_id);
			echo json_encode($table);
		}

		public function courtTable($venue){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_court');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_court');
		$table=$this->court_model->get_courtTable($edit,$delete,$venue);
		echo json_encode($table);
	}


	public function add($id,$court_manager='')
	{
		if($this->input->post()){


			$this->form_validation->set_rules('court',	'Court',	'required|callback__edit_unique[court.'.$id.']');
			$this->form_validation->set_rules('sports',	'Sports',	'required');
			//$this->form_validation->set_rules('start[]',	'Time',	'required');
			$this->form_validation->set_rules('cost',	'Cost',	'required');
			//$this->form_validation->set_rules('end[]',	'Time',	'required');
			$this->form_validation->set_rules('intervel',	'Intervel',	'required');
			$this->form_validation->set_rules('capacity',	'Capacity',	'required');
			if($this->form_validation->run() == FALSE) {
					$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
					redirect("venue/venue_edit/$id");
			}else{
				$weeks=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
				foreach ($this->input->post('week') as $key => $value) {
					$selected_weeks[]=$key;
				}

				$data=array(
						'court'=>$this->input->post('court'),
						'sports_id'=>$this->input->post('sports'),
						'cost'=>$this->input->post('cost'),
						'intervel'=>$this->input->post('intervel'),
						'capacity'=>$this->input->post('capacity'),
						'status'=>$this->input->post('status'),
                        'added_date'=>date('Y-m-d h:i:sa')
					);


				$court_id=$this->court_model->add($data,'court');
				$data=array('venue_id' => $id,
							'court_id'=>$court_id
					 );
				if($this->court_model->add($data,'venue_court'));
				foreach ($weeks as $key => $value) {
					if (!in_array($value, $selected_weeks)) {
						if($value!=""){
							$data=array(
						'court_id'=>$court_id,
						'week'=>$value
					);

					$this->court_model->add($data,'court_time');
					}
				}
			}
				$venue=$this->court_model->get_venue_details($id);
				//print_r($this->input->post('week'));
				foreach ($this->input->post('week') as $key => $value) {
					foreach ($value['start'] as $key2 => $value1) {
						$time=$value1.'-'.$value['end'][$key2];
                        $startt=$value1;
						$endt=$value['end'][$key2];
                        $slotfor=$value['slotfor'][$key2];
						$starttime= date("H:i:s", strtotime($startt));
                        $endtime= date("H:i:s", strtotime($endt));
                        $intervel=$this->input->post('intervel');
                        $count=0;
                        $ti=$starttime;
                        $time_difference=$endtime-$starttime;
                         $timeintra_limit=60/$intervel;
                        $time_difference=$time_difference*$timeintra_limit;
                        for($i=0;$i<$time_difference;$i++){
                        $ti = date('H:i:s', strtotime($ti.'+'.$intervel.' minutes'));
                        $count=$count+1;	
                        }
                        $difference=$count-1;
						
						$data=array(
						'court_id'=>$court_id,
						'week'=>$key,
                        'slotfor'=>$slotfor
					);
	if(!(date("H:i:s", strtotime($value1))>=date("H:i:s", strtotime($value['end'][$key2])) ||date("H:i:s", strtotime($value1))<date("H:i:s", strtotime($venue->morning))||date("H:i:s", strtotime($value['end'][$key2]))>date("H:i:s", strtotime($venue->evening)))){
						$court_time_id=$this->court_model->add($data,'court_time');
                        $intervel=$this->input->post('intervel');
					 /* split times start*/
                        for($m=0;$m<=$difference;$m++)
				          {
				           $times = date('H:i:s', strtotime($starttime.'+'.$m*$intervel.' minutes'));
                        $dategdf=1;                
					    $data=array(
						'court_time_id'=>$court_time_id,
						'court_id'=>$court_id,
						'time'=>$times,
						'date'=>$dategdf,
			            'added_date'=>date('Y-m-d h:i:sa')
					  );
                        //print_r($data);
					    $this->court_model->add($data,'court_time_intervel');

					}
                                        /* split times end*/
					}else{
						$week_check=$this->court_model->week_check($key,$court_id);
						if(empty($week_check)){
							$data=array(
						'court_id'=>$court_id,
						'week'=>$key
					);
							$this->court_model->add($data,'court_time');
						}
					}
				}

				}
				
				if($court_manager!=''){

					$data=array(
							'user_id'=>$this->session->userdata('user_id'),
							'court_id'=>$court_id
						);

					$this->court_model->add($data,'court_manager_courts');
				}
				$this->session->set_flashdata('success-msg',"Court Added");
					redirect("venue/venue_edit/$id?court=1");
			}
		}else{

			$data['sports']=$this->court_model->get_sports($id);
			$data['venue']=$this->court_model->get_venue_details($id);

			$data['key']=$court_manager;
			$this->load->template('add',$data);
		}
	}

		public function edit($court_id,$venue_id='')
	{
		$error=1;
		if($this->input->post()){

			$this->form_validation->set_rules('court',	'Court',	'required|callback__edit_unique[court.'.$court_id.'.'.$venue_id.']');
			$this->form_validation->set_rules('sports',	'Sports',	'required');

			if($this->form_validation->run() == FALSE) {
					$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
					redirect("court/edit/$court_id/$venue_id");
			}else{
				

				$data=array(
						'court'=>$this->input->post('court'),
						'sports_id'=>$this->input->post('sports'),
						'cost'=>$this->input->post('cost'),
						'intervel'=>$this->input->post('intervel'),
						'capacity'=>$this->input->post('capacity'),
						'status'=>$this->input->post('status'),
						'added_date'=>date('Y-m-d h:i:sa')
					);

				$this->court_model->update($data,'court',$court_id);
				

			
				

				if($error)
				$this->session->set_flashdata('success-msg',"Court Edited");
				else
				$this->session->set_flashdata('success-msg',"Court Edited. <br><span style='background:#B71C1C;'>Some Slots are not added.</span>");

					redirect("venue/venue_edit/$venue_id?court=1");
			}
		}else{
			$week=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
			$data['sports']=$this->court_model->get_sports($venue_id);
			$data['court']=$this->court_model->get_court($court_id);
			$data['courtmon']=$this->court_model->get_courtmon($court_id);
			$data['courttue']=$this->court_model->get_courttue($court_id);
			$data['courtwed']=$this->court_model->get_courtwed($court_id);
			$data['courtthu']=$this->court_model->get_courtthu($court_id);
			$data['courtfri']=$this->court_model->get_courtfri($court_id);
			$data['courtsat']=$this->court_model->get_courtsat($court_id);
			$data['courtsun']=$this->court_model->get_courtsun($court_id);

			$data['ct']=$this->court_model->get_court_time($court_id);
			//print_r($data['ct']);exit;
			$data['venue']=$this->court_model->get_venue_details($venue_id);
		//	print_r($data['venue']);exit;
			foreach ($week as $key => $value) {
			foreach ($data['ct'] as $key2 => $value2) {
					if($value==$value2->week){

						$data['court_time'][$value][]=array(
							'id'=>$value2->id,
							'week'=>$value2->week,
                            'slotfor'=>$value2->slotfor,
							'time'=>$time=explode('-',$value2->time )
							);

					}

			}
		}
		unset($data['ct']);
//print_r($data);exit;
			$this->load->template('edit',$data);
		}
	}


public function edit_time($court_id,$venue_id='')
	{
     $slotfor=$this->input->post('slotfor');
     $slot_time=date("H:i:s", strtotime($this->input->post('slottime')));
     $week=$this->input->post('week');

     $data=array(
			'court_id'=>$court_id,
			'week'=>$week,
			'slotfor'=>$slotfor,
			);
        $court_time_id=$this->court_model->add_time('court_time',$data);	
         $date=1;
        $data=array(
			'court_time_id'=>$court_time_id,
			'court_id'=>$court_id,
			'time'=>$slot_time,
			'date'=>$date,
			'added_date'=>date('Y-m-d h:i:sa')
			);
     $this->court_model->add_time('court_time_intervel',$data);
     redirect("court/edit/$court_id/$venue_id");
						
	}
	public function delete($id,$venue)
	{
		$this->court_model->delete($id,'court');
                $this->court_model->delete_court_time($id);
	        $this->court_model->delete_court_times($id);
	        $this->court_model->delete_venue_court($id);
		$this->session->set_flashdata('success-msg',"Court Deleted");
		redirect("venue/venue_edit/$venue?court=1");
	}
       public function slot_delete($slot_id,$venue,$court_id)
	{

		$this->court_model->delete_slot($slot_id);
		$this->session->set_flashdata('success-msg',' slot Deleted!');
                redirect("court/edit/$court_id/$venue");

	}


	public function change_status($id,$status="",$venue_id="")
	{
		if($id){
			if($status){
				$insert_data=array('status'=>'0');
			}else{
				$insert_data=array('status'=>'1');
			}
			$result=$this->court_model->update($insert_data,'court',$id);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect("venue/venue_edit/$venue_id?court=1");
			}
		}
	}
	public function _edit_unique($str,$id,$venue_id='')
	{
		sscanf($id, '%[^.].%[^.].%[^.]', $field,$id,$venue_id);

		$count =$this->court_model->court_unique($str,$id,$venue_id);

		//print_r(!empty($count) ? FALSE:TRUE);exit;
		$this->form_validation->set_message('_edit_unique', 'Court already exists.');
		return (!empty($count)) ? FALSE:TRUE;
	}

}
