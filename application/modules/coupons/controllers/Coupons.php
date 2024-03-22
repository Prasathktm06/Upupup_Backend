<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Coupons extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('coupons_model');

	}
	public function index() {

		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {


		}else{
			$data['table'] 	= $this->coupons_model->get_coupan_list();
			$today 			= date("Y-m-d");
			foreach ($data['table'] as $key => $value) {
				if($value['valid_to'] < $today)
				{
					$data['table'][$key]['expire_status']="Expire";
				}else{
					$data['table'][$key]['expire_status']="Valid";
				}
				
				$data['table'][$key]['area']=implode(',', $this->coupons_model->get_coupon_area($value['area_id']));
				$data['table'][$key]['city']=implode(',', $this->coupons_model->get_coupon_city($value['area_id']));
			}
			//echo "<pre>";print_r($data);exit();
			$this->load->template('list',$data);
		}
	}


	/*public function offerTable(){
		$edit 	= $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_offer');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_offer');
		$table 	= $this->coupons_model->get_offerTable($edit,$delete);
		echo json_encode($table);
	}*/
	//////////////////////////////////////////Add Function//////////////////////////////////////////
	public function add(){
		if($this->input->post()){
			//echo "<pre>";print_r($this->input->post());exit();
			//$date=explode('-', $this->input->post('date'));
			$data=array(
					'coupon_value'	=>$this->input->post('coupon_value'),
					'coupon_code'	=>$this->input->post('coupon_code'),
					'percentage'	=>$this->input->post('type'),
					'currency'		=>$this->input->post('currency'),
					'area_id'		=>$this->input->post('area'),
					'valid_from'	=>date("Y-m-d", strtotime($this->input->post('start'))),
					'valid_to'		=>date("Y-m-d", strtotime($this->input->post('end'))),
					'description' 	=>$this->input->post('description'),
					);
			//echo "<pre>";print_r($data);exit();
			$result 	=$this->coupons_model->add_coupon($data);
			if ($result) {
				$this->session->set_flashdata('success-msg','New Coupon has been added!');
				redirect('coupons');
			}
		}else{
		    $data['locations']= $this->coupons_model->get_location();
			$this->load->template('add',$data);
		}
	}
	////////////////////////////////////////////Edit Function//////////////////////////////////
	public function edit($coupon_id){
		if($this->input->post()){
			//$date=explode('-', $this->input->post('date'));
			$data=array(
					'coupon_value'	=>$this->input->post('coupon_value'),
					'coupon_code'	=>$this->input->post('coupon_code'),
						'percentage'	=>$this->input->post('type'),
							'currency'		=>$this->input->post('currency'),
							'area_id'		=>$this->input->post('area'),
					'valid_from'	=>date("Y-m-d", strtotime($this->input->post('start'))),
					'valid_to'		=>date("Y-m-d", strtotime($this->input->post('end'))),
					'description' 	=>$this->input->post('description'),
					);
			$result=$this->coupons_model->update_coupon($data,$coupon_id);
			if ($result) {
				$this->session->set_flashdata('success-msg',' Coupon has been edited!');
				redirect('coupons');
			}
		}else{
			$data['details']=$this->coupons_model->get_details($coupon_id);
			$data['locations']= $this->coupons_model->get_location();
			$data['area']=$this->coupons_model->get_venue_area($data['details'][0]['location_id']);
			$data['details'][0]['date']=$data['details'][0]['valid_from']-$data['details'][0]['valid_to'];
			//echo "<pre>";print_r($data);exit();
			$this->load->template('edit',$data);
		}
	}
	///////////////////////////////////////////Delete Function///////////////////////////////////////
	public function delete($coupon_id){
		$result=$this->coupons_model->delete_data($coupon_id);
		if ($result) {
			$this->session->set_flashdata('success-msg',' Coupon has been deleted!');
			redirect('coupons');
		}
	}
	//////////////////////////////////////Change Status///////////////////////////////////////////
	public function change_status($coupon_id,$status=null)
	{
		if($coupon_id){

			if($status){
				$insert_data=array('status'=>'0');
			}else{
				$insert_data=array('status'=>'1');
			}
			$result=$this->coupons_model->update_coupon($insert_data,$coupon_id);
			if($result){
				$this->session->set_flashdata('success-msg',' Status has been changed!');
				redirect('coupons');
			}
		}
	}
	//////////////////////////////////////////////////////////////

}
