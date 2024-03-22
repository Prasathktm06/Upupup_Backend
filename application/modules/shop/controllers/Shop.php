<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Shop extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('shop_model');
	
		
	}
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) {
			redirect('acl');
		}else{
			if($this->input->post()){
					$location_id=$this->input->post('city');
					$data['list']=$this->shop_model->get_shop($location_id);
					$data['locations']= $this->shop_model->get_location_list();
					$this->load->template('list',$data);
			}else{
					$data['list']=$this->shop_model->get_shop();
					$data['locations']= $this->shop_model->get_location_list();
					$this->load->template('list',$data);
			}
		}
	}
	///////////////////////////////////////// add shops start //////////////////////////////////////////////////////
	public function add(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_shop')) {
			redirect('acl');
		}
		if($this->input->post()){
				$path="pics/shop/";
	        	$image=$this->common->file_upload_image($path);
	        	if(empty($image))
        	    {
	        	$data=array(
							'name'=>$this->input->post('name'),
							'phone'=>(int)$this->input->post('phone'),
							'location_id'=>$this->input->post('location'),
							'area_id'=>$this->input->post('area'),
							'address'=>$this->input->post('address'),
							'timing'=>$this->input->post('timing'),
							'major_brands'=>$this->input->post('major_brands'),
							'brand_shop'=>$this->input->post('brand_shop'),
							'lat'=>$this->input->post('lat'),
							'lon'=>$this->input->post('lon'),
							'status'=>$this->input->post('status'),
							'added_date'=>date('Y-m-d H:i:s'),
					);
					$id=$this->shop_model->insert_data($data,'shop');
						$sports=$this->input->post('sports');
							foreach ($sports as $val){
								$data=array(
										'shop_id'=> $id,
										'sports_id'=>$val,
										'status'=>1,
										'added_date'=>date('Y-m-d H:i:s'),
								);
								$this->shop_model->insert_data($data,'shop_sports');
							}
			        if($id){
						$this->session->set_flashdata('success-msg',"New Shop has been added!");
			    			redirect("shop");
					}else{
						$this->session->set_flashdata('error-msg','Shop not added!');
						redirect("shop");		
					}
        	    }else{
	        	$data=array(
							'name'=>$this->input->post('name'),
							'phone'=>(int)$this->input->post('phone'),
							'location_id'=>$this->input->post('location'),
							'area_id'=>$this->input->post('area'),
							'address'=>$this->input->post('address'),
							'timing'=>$this->input->post('timing'),
							'major_brands'=>$this->input->post('major_brands'),
							'brand_shop'=>$this->input->post('brand_shop'),
							'lat'=>$this->input->post('lat'),
							'lon'=>$this->input->post('lon'),
							'image'=>str_replace(" ","%20",$image),
							'status'=>$this->input->post('status'),
							'added_date'=>date('Y-m-d H:i:s'),
					);
					$id=$this->shop_model->insert_data($data,'shop');
						$sports=$this->input->post('sports');
							foreach ($sports as $val){
								$data=array(
										'shop_id'=> $id,
										'sports_id'=>$val,
										'status'=>1,
										'added_date'=>date('Y-m-d H:i:s'),
								);
								$this->shop_model->insert_data($data,'shop_sports');
							}
			        if($id){
						$this->session->set_flashdata('success-msg',"New Shop has been added!");
			    			redirect("shop");
					}else{
						$this->session->set_flashdata('error-msg','Shop not added!');
						redirect("shop");		
					}    
        	    }

		}else{
			$data['locations']= $this->shop_model->get_location();
			$data['sports']=$this->shop_model->get_sports();
			$this->load->template('add',$data);
		}
	}
	/////////////////////////////////////////  add shops end//////////////////////////////////////////////
	//////////////////////////////////  edit start /////////////////////////////////////////////////////
	public function edit($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) {
			redirect('acl');
		}
		if($this->input->post()){
			$path="pics/shop/";
        	$image=$this->common->file_upload_image($path);
        	if(empty($image))
        	{
	        	$data=array(
							'name'=>$this->input->post('name'),
							'phone'=>(int)$this->input->post('phone'),
							'location_id'=>$this->input->post('location'),
							'area_id'=>$this->input->post('area'),
							'address'=>$this->input->post('address'),
							'timing'=>$this->input->post('timing'),
							'major_brands'=>$this->input->post('major_brands'),
							'brand_shop'=>$this->input->post('brand_shop'),
							'lat'=>$this->input->post('lat'),
							'lon'=>$this->input->post('lon'),
							'status'=>$this->input->post('status'),
					);
					//echo "<pre>";print_r($data);
			        $this->shop_model->update($id,$data);
					$this->session->set_flashdata('success-msg','Shops Edited!');
					redirect("shop");	
        	}else{
	        	$data=array(
							'name'=>$this->input->post('name'),
							'phone'=>(int)$this->input->post('phone'),
							'location_id'=>$this->input->post('location'),
							'area_id'=>$this->input->post('area'),
							'address'=>$this->input->post('address'),
							'timing'=>$this->input->post('timing'),
							'major_brands'=>$this->input->post('major_brands'),
							'brand_shop'=>$this->input->post('brand_shop'),
							'lat'=>$this->input->post('lat'),
							'lon'=>$this->input->post('lon'),
							'image'=>str_replace(" ","%20",$image),
							'status'=>$this->input->post('status'),
					);
					//echo "<pre>";print_r($data);
			        $this->shop_model->update($id,$data);
					$this->session->set_flashdata('success-msg','Shops Edited!');
					redirect("shop");
        	}
		}else{
			$data['locations']= $this->shop_model->get_location();
			$data['shop']= $this->shop_model->get_shop_details($id);
			$data['area']= $this->shop_model->get_area($data['shop']->location_id);
			$data['sports']=$this->shop_model->get_sports();
			$data['shop_sports']=$this->shop_model->get_shop_sports($id);
			$data['shop_offer']=$this->shop_model->get_shop_offer($id);
		    $this->load->template('edit',$data);
		}
	}
	//////////////////////////////////  edit end /////////////////////////////////////////////////////
	//////////////////////////////////////    add sports start////////////////////////////////////////////////
	public function add_sports($id){
		if($this->input->post()){
			$this->shop_model->delete_sports($id);
			$sports=$this->input->post('sports');
			foreach ($sports as $val){
				$data=array(
						'shop_id'=> $id,
						'sports_id'=>$val,
						'status'=>1,
						'added_date'=>date('Y-m-d H:i:s'),
				);
				$this->shop_model->insert_data($data,'shop_sports');
			}
		}
		$this->session->set_flashdata('success-msg','Sports Added!');
		redirect("shop/edit/$id?sports=1");
	}	
	////////////////////////////////////// add sports end////////////////////////////////////////////////
    ////////////////////// change  shop status start ////////////////////////////////////////////
	public function change_status($id,$status)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->shop_model->update_status($insert_data,'shop',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->shop_model->update_status($insert_data,'shop',$id);
		}
		
	    $id=$trainers_id;	
		if($result){
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("shop");
		}
		
	}
	////////////////////// change  shop status end ////////////////////////////////////////////
	///////////////////////////////////////// add offer start //////////////////////////////////////////////////////
	public function add_offer(){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_shop')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
        				'shop_id'=>$this->input->post('shop_id'),
						'name'=>$this->input->post('name'),
						'amount'=>$this->input->post('amount'),
						'start_date'=>$this->input->post('day'),
						'end_date'=>$this->input->post('days'),
						'status'=>$this->input->post('status'),
						'added_date'=>date('Y-m-d H:i:s'),
				);
				$add=$this->shop_model->insert_data($data,'shop_offer');
		        if($add){
		        	$id=$this->input->post('shop_id');
					$this->session->set_flashdata('success-msg',"New Offer has been added!");
		    			redirect("shop/edit/$id?offer=1");
				}else{
					$this->session->set_flashdata('error-msg','Offer not added!');
					redirect("shop/edit/$id?offer=1");	
				}

		}else{
			$this->load->template('add_offer',$data);
		}
	}
	////////////////////////////////////////// add offer end/////////////////////////////////////////////
	//////////////////////////////////  offer edit start /////////////////////////////////////////////////////
	public function offer_edit($id,$shop_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) {
			redirect('acl');
		}
		if($this->input->post()){
        	$data=array(
						'name'=>$this->input->post('name'),
						'amount'=>$this->input->post('amount'),
						'start_date'=>$this->input->post('day'),
						'end_date'=>$this->input->post('days'),
						'status'=>$this->input->post('status'),
				);
		        $this->shop_model->update_offer($id,$data);
				$this->session->set_flashdata('success-msg','Offer Edited!');
				$id=$shop_id;
				redirect("shop/edit/$id?offer=1");	
		}else{
            $data['offer']=$this->shop_model->get_offer_details($id);
		    $this->load->template('edit_offer',$data);
		}
	}
	//////////////////////////////////  offer edit end /////////////////////////////////////////////////////
    ////////////////////// change  offer status start ////////////////////////////////////////////
	public function offer_status($id,$status,$shop_id)
	{
		if($status==1){
			$insert_data=array('status'=>0);
			$result=$this->shop_model->update_status($insert_data,'shop_offer',$id);
		}else{
        	$insert_data=array('status'=>1);
        	$result=$this->shop_model->update_status($insert_data,'shop_offer',$id);
		}
		
	    $id=$trainers_id;	
		if($result){
			$id=$shop_id;
			$this->session->set_flashdata('success-msg',' Status has been changed!');
			redirect("shop/edit/$id?offer=1");
		}
		
	}
	////////////////////// change  offer status end ////////////////////////////////////////////
	//////////////////////////////////  shop delete start /////////////////////////////////////////////////////
	public function delete_shop($id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_shop')) {
			redirect('acl');
		}

		$this->shop_model->delete_sports($id);
		$this->shop_model->delete_offer($id);
		$data=$this->shop_model->delete_shop($id);
 		if($data){
				$this->session->set_flashdata('success-msg','Shop has been  Deleted!');
				redirect("shop");
		}else{
				$this->session->set_flashdata('error-msg','Shop not deleted!');
				redirect("shop");
		}
	}
	//////////////////////////////////  shop delete end /////////////////////////////////////////////////////
	//////////////////////////////////  shop offer delete start /////////////////////////////////////////////////////
	public function offer_delete($id,$shop_id){
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_shop')) {
			redirect('acl');
		}
		$data=$this->shop_model->delete_shop_offer($id);
		$id=$shop_id;
 		if($data){
				$this->session->set_flashdata('success-msg','Offer has been  Deleted!');
				redirect("shop/edit/$id?offer=1");
		}else{
				$this->session->set_flashdata('error-msg','Offer not deleted!');
				redirect("shop/edit/$id?offer=1");
		}
	}
	//////////////////////////////////  shop offer delete end /////////////////////////////////////////////////////
}


