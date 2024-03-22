<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Matches extends CI_controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('matches_model');
		
	}
	
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_matches')) {
			redirect('acl');
		}else{
			
			$this->load->template('list');
		}
	}
	
	public function matchesTable(){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_matches');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_matches');
		$table=$this->matches_model->get_matchesTable($edit,$delete);
		echo json_encode($table);
	}
	
	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_matches')) {
			redirect('acl');
		}
		 if($this->input->post()){
		// 	//print_r($_POST);exit;
			//print_r( $this->input->post('phone'));exit;
			/*$notification=$this->notification->push_notification($users,$message);exit();*/
			//$this->form_validation->set_rules('name',	'Name',	'required|is_unique[matches.match_name]');
			$this->form_validation->set_rules('date',	'Date',	'required');
			$this->form_validation->set_rules('time',	'Time',	'required');
			$this->form_validation->set_rules('sports',	'Sports',	'required|numeric');
			$this->form_validation->set_rules('host',	'Hosted By',	'required|numeric');
			$this->form_validation->set_rules('area',	'Area',	'required|numeric');
			
			
		
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("matches/add");
			}else{
				$sports=$this->matches_model->get_sports($this->input->post('sports'));
				$data=array(
						'match_name'=>$sports->sports,
						'sports_id'=>$this->input->post('sports'),
						'area_id'=>$this->input->post('area'),
						'user_id'=>$this->input->post('host'),
						'no_players'=>$this->input->post('no_players'),
						'date'=>$this->input->post('date'),
						'time'=>$this->input->post('time'),
						'description'=>$this->input->post('description'),
						
				);
				
				$match_id=$this->matches_model->add_matches($data);
				$players=$this->input->post('players');
				foreach ($players as $key => $value) {
						$data=array(
						'match_id'=>$match_id,
						'user_id'=>$value,
						'status'=>3
					);
				$this->matches_model->add_matches_players($data);
				}
			
				
				$this->session->set_flashdata('success-msg','New Match Added!');
				redirect('matches');
			}
		
		}else{
			$data['venue']= $this->matches_model->get_details('','venue');
			$data['sports']= $this->matches_model->get_details('','sports');
			$data['location']= $this->matches_model->get_details('','locations');
			$data['users']= $this->matches_model->get_details('','users');
			$data['status']= $this->matches_model->get_details('','status');
			$this->load->template('add',$data);
		} 
	}
	
	public function edit($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_matches')) {
			redirect('acl');
		}
		if($this->input->post()){
		
		//	$this->form_validation->set_rules('name',	'Name',	'required|callback__matches_unique[match_name.'.$id.']');
			$this->form_validation->set_rules('date',	'Date',	'required');
			$this->form_validation->set_rules('time',	'Time',	'required');
			$this->form_validation->set_rules('sports',	'Sports',	'required|numeric');
			//$this->form_validation->set_rules('host',	'Hosted By',	'required|numeric');
			$this->form_validation->set_rules('area',	'Area',	'required|numeric');
			
				
			
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error-msg',preg_replace("/\r|\n/", '<br/>', strip_tags(validation_errors())));
				redirect("matches/edit/$id");
			}else{
				//echo "<pre>";print_r($this->input->post());exit;
			$sports=$this->matches_model->get_sports($this->input->post('sports'));
				$data=array(
						'match_name'=>$sports->sports,
						'sports_id'=>$this->input->post('sports'),
						'area_id'=>$this->input->post('area'),
						//'user_id'=>$this->input->post('host'),
						'no_players'=>$this->input->post('no_players'),
						'date'=>date_format(date_create($this->input->post('date')),"Y-m-d"),
						'time'=>$this->input->post('time'),
						'time2'=>$this->input->post('time2'),
						'description'=>$this->input->post('description')
				);
				$this->matches_model->update_matches($id,$data);
				$this->session->set_flashdata('success-msg','Match Edited!');
				redirect('matches');
			}
	
		}else{
			$data['data']= $this->matches_model->get_data($id);
			//print_r($data);exit;
			$data['sports']= $this->matches_model->get_details('','sports');
			$data['location']= $this->matches_model->get_details('','locations');
			$location_id= $this->matches_model->get_details($data['data']->area_id,'area','id');
			$data['area']= $this->matches_model->get_details($location_id[0]->location_id,'area','location_id');
			$data['data']->location=$location_id[0]->location_id;
			$data['users']= $this->matches_model->get_details('','users');
			$data['status']= $this->matches_model->get_details('','status');
			$data['players']= $this->matches_model->get_players_byMatch($id);
			//print_r($data['players']);exit;
			$this->load->template('matches/edit',$data);
		}
	}
	


	public function _matches_unique($str, $id)
	{
		
		sscanf($id, '%[^.].%[^.]', $field,$id);
		
		$count = $this->db->where($field,$str)->where('id !=',$id)->get('matches')->num_rows();
		
		$this->form_validation->set_message('_edit_unique', 'Match Field already exists.');
		return ($count>=1) ? FALSE:TRUE;
	} 
	public function delete($id)
	{
	if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_matches')) {
			redirect('acl');
		}	
		$this->matches_model->delete($id);
		$this->session->set_flashdata('success-msg','Matches deleted!');
				redirect('matches');
	}

	//Ajax

	public function players($match_id)
	{
		$table=$this->matches_model->get_playersTable($match_id);
		echo json_encode($table);
	}

	public function player_status()
	{
		if ($this->input->post()) {
			//print_r($_POST);exit;
			$status=$this->input->post('status');
			$match_id=$this->input->post('match_players');
			
			$data=array(
					
					'status'=>$status
				);	
			$this->matches_model->update_matches_players($data,$match_id);
		}	
		redirect("matches/");	
	}

	public function matches_players_delete($id)
	{
		
	    $this->matches_model->matches_players_delete($id);
		
		redirect("matches/");	
	}
	public function add_player_status()
	{
		$user=$this->input->post('players');
		$status=$this->input->post('status');
		$match_id=$this->input->post('match_id');

		$data=array(
				'match_id'=>$match_id,
				'user_id'=>$user,
				'status'=>$status
			);

		$this->matches_model->add_matches_players($data);
		$this->session->set_flashdata('success-msg','Player Added!');
		redirect("matches/edit/$match_id");	
	}

}

