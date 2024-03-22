<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Inactivate extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('Inactivate_model');
		
	}
	public function index() {
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_venue')) {
			redirect('acl');
		}else{
			$this->load->template('list');
		}
	}

	public function inactivateTable($venue_id=''){

		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_inactive');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_inactive');
		$table=$this->Inactivate_model->get_inactiveTable($edit,$delete,$venue_id);
		echo json_encode($table);
	}
      public function inactivate_delete($id,$venue_id)
	{
		//print_r($venue_id);exit;
		$this->Inactivate_model->delete($id,$venue_id);
		$this->session->set_flashdata('success-msg','Inactivate Time  Deleted!');
				redirect("venue/venue_edit/$venue_id?inactive=1");
	}
        public function add($venue_id=''){

		$data['court']=$this->Inactivate_model->get_venue_court($venue_id);
                $data['sports']=$this->Inactivate_model->get_venue_sports($venue_id);
                $data['reason']=$this->Inactivate_model->get_inactive_reason();
                $data['venue']=$venue_id;
		$this->load->template('add',$data);

	}
    public function add_inactive($venue_id=''){
		if($this->input->post()){

            $sports=$this->input->post('sports');
	    $court=$this->input->post('court');
            
            $stdate=$this->input->post('day');
            $etdate=$this->input->post('days');
            $sdate=date("Y-m-d", strtotime($stdate));
            $edate=date("Y-m-d", strtotime($etdate));
            $stime=$this->input->post('stime');
            $etime=$this->input->post('etime');
            $sun=$this->input->post('sun');
            $mon=$this->input->post('mon');
            $tue=$this->input->post('tue');
            $wed=$this->input->post('wed');
            $thu=$this->input->post('thu');
            $fri=$this->input->post('fri');
            $sat=$this->input->post('sat');
            $reason=$this->input->post('reason');
            $starttime= date("H:i:s", strtotime($stime));
            $endtime= date("H:i:s", strtotime($etime));
			$count_sports=sizeof($sports);
			$datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
            $timedifference=$endtime-$starttime;


            $sunday="Sun";
            $monday="Mon";
            $tuesday="Tue";
            $wednesday="Wed";
            $thursday="Thu";
            $friday="Fri";
            $saturday="Sat";
            $court_manage=[];
            $sports_manage=[];

             if($court == Null){
            	$courts=[];
            	for($r=0;$r<$count_sports;$r++){
         	     $sports_id=$sports[$r];
                     
            	$final_array=$this->Inactivate_model->get_courtlistsin($sports_id,$venue_id);
            	$courts = array_merge($courts,$final_array);
            	}
            	$courts = array_values($courts);
            	$court = json_decode(json_encode($courts), True);
                //echo "<pre>";print_r($court);exit(); 
            	$count_court=sizeof($court);
      
            /////////////////////////////////////////////////////////
				for($j=0;$j<$count_court;$j++){
					$court_id=$court[$j][id];

					for($k=0;$k<=$datedifference;$k++){
						$date = strftime("%Y-%m-%d", strtotime("$sdate +$k day"));
						

						for($l=0;$l<$timedifference;$l++){
							$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
							$timet=$this->Inactivate_model->get_bookingexist($venue_id,$court_id,$date,$time);

							 if($timet)
				                     {
				                      $mdt=2;
				                     }else{
				                       if($mdt==2){
				              	         $mdt=2;
				                           }else{
				              	            $mdt=1;
				                             }
				                          }

						}
					}
            $court_sname=$this->Inactivate_model->get_courtname($court_id);
            $court_manage = array_merge($court_manage, $court_sname);
            $sports_sname=$this->Inactivate_model->get_sportsname($court_id);
            $sports_manage = array_merge($sports_manage, $sports_sname);
				}

				if($mdt==2){

		     $this->session->set_flashdata('error-msg',"booking exist");
			redirect("venue/venue_edit/$venue_id?inactive=1");
	        }else{


	  	      
	  	      for($j=0;$j<$count_court;$j++){
					$court_id=$court[$j][id];

					$data=array(
			         'venue_id'=>$venue_id,
			         'court_id'=>$court_id,
			         'sdate'=>$sdate,
			         'edate'=>$edate,
                     'stime'=>$starttime,
			         'etime'=>$endtime,
			         'description'=>$reason,
                                 'added_date'=>date('Y-m-d h:i:sa')
			        );
			       
				$adds=$this->Inactivate_model->insert_inactivecourt($data);
				 if($adds){
					$inactive_court_id=$adds;
				}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");	
				}

					for($k=0;$k<=$datedifference;$k++){
						$date = strftime("%Y-%m-%d", strtotime("$sdate +$k day"));
						$nameOfDay = date('D', strtotime($date));

				if($sun!=0 && $sunday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			} 

			if($mon!=0 && $monday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($tue!=0 && $tuesday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($wed!=0 && $wednesday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($thu!=0 && $thursday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($fri!=0 && $friday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($sat!=0 && $saturday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}




					}
				}
	  	  
	        }


			
///////////////////////////////////////////////////////////////////////	






            }else{ 
  
            $count_court=sizeof($court);
            /////////////////////////////////////////////////////////
				for($j=0;$j<$count_court;$j++){
					$court_id=$court[$j];

					for($k=0;$k<=$datedifference;$k++){
						$date = strftime("%Y-%m-%d", strtotime("$sdate +$k day"));
						

						for($l=0;$l<$timedifference;$l++){
							$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
							$timet=$this->Inactivate_model->get_bookingexist($venue_id,$court_id,$date,$time);

							 if($timet)
				                     {
				                      $mdt=2;
				                     }else{
				                       if($mdt==2){
				              	         $mdt=2;
				                           }else{
				              	            $mdt=1;
				                             }
				                          }

						}
					}
            $court_sname=$this->Inactivate_model->get_courtname($court_id);
            $court_manage = array_merge($court_manage, $court_sname);
            $sports_sname=$this->Inactivate_model->get_sportsname($court_id);
            $sports_manage = array_merge($sports_manage, $sports_sname);
				}

				if($mdt==2){

		     $this->session->set_flashdata('error-msg',"booking exist");
			redirect("venue/venue_edit/$venue_id?inactive=1");
	        }else{


	  	      
	  	      for($j=0;$j<$count_court;$j++){
					$court_id=$court[$j];

					$data=array(
			         'venue_id'=>$venue_id,
			         'court_id'=>$court_id,
			         'sdate'=>$sdate,
			         'edate'=>$edate,
                                  'stime'=>$starttime,
			         'etime'=>$endtime,
			         'description'=>$reason,
                                 'added_date'=>date('Y-m-d h:i:sa')
			        );
			       
				$adds=$this->Inactivate_model->insert_inactivecourt($data);
				 if($adds){
					$inactive_court_id=$adds;
				}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");	
				}

					for($k=0;$k<=$datedifference;$k++){
						$date = strftime("%Y-%m-%d", strtotime("$sdate +$k day"));
						$nameOfDay = date('D', strtotime($date));

				if($sun!=0 && $sunday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			} 

			if($mon!=0 && $monday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($tue!=0 && $tuesday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($wed!=0 && $wednesday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($thu!=0 && $thursday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($fri!=0 && $friday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}

			if($sat!=0 && $saturday==$nameOfDay)
				{
				for($l=0;$l<$timedifference;$l++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
				$timet=$this->Inactivate_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->Inactivate_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
                        $f=1;
                        
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}	
					}else{
					$this->session->set_flashdata('error-msg',"error");
			        redirect("venue/venue_edit/$venue_id?inactive=1");
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			    }
			}
			}




					}
				}
	  	  
	        }


			
///////////////////////////////////////////////////////////////////////			

	        	
            }

   
			
			
		}

		$d=$this->Inactivate_model->delete_emptytimes();
		$s=$this->Inactivate_model->delete_inempty();	
		$venue_sname=$this->Inactivate_model->get_venuename($venue_id);     
     foreach($venue_sname as $row) {
           $venue_name = $row->venue;
       }
        $kcu_courtname="";
    foreach($court_manage as $row) {
           $court_name = $row->court;
           $kcf_courtname=''.$kcu_courtname.''.$court_name.''.",".'';
           $kcu_courtname=$kcf_courtname;
       }
       $uniqueArray= array_values(array_unique($sports_manage, SORT_REGULAR));
       $kcu_sportsname="";
    foreach($uniqueArray as $row) {
           $sports_name = $row->sports;
           $kcf_sportsname=''.$kcu_sportsname.''.$sports_name.''.",".'';
           $kcu_sportsname=$kcf_sportsname;
       }
    $start_inactivedate=date('dS F Y', strtotime($stdate));
    $end_inactivedate=date('dS F Y', strtotime($etdate));
    $start_inactivetime=date('h:i A',strtotime($stime));
    $end_inactivetime=date('h:i A',strtotime($etime));

			if($rest==1){
    /*email for venue owner start*/ 
    $owneremail=$this->Inactivate_model->get_owner($venue_id);
    foreach($owneremail as $row) {
        $owner_email = $row->email;
        $owner_name = $row->name;
        if($owner_email!=''){
        $data['data']=array(
             'owner_name'=>$owner_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'sport_name'=>$kcu_sportsname,
             'start_date'=>$start_inactivedate,
             'end_date'=>$end_inactivedate,
             'start_time'=>$start_inactivetime,
             'end_time'=>$end_inactivetime,
             'sunday'=>$sun,
             'monday'=>$mon,
             'tuesday'=>$tue,
             'wednesday'=>$wed,
             'thursday'=>$thu,
             'friday'=>$fri,
             'saturday'=>$sat,
             'reason'=>$reason,
             );
          $to_email = $owner_email;
          $subject='Court Inactivated !!';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('inactivate_owner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for venue owner end */
					$result="updated";
				    return $this->response($result,200);
						}else{
      
                              if($f==0){
       /*email for venue owner start */
    $owneremail=$this->Inactivate_model->get_owner($venue_id);
    foreach($owneremail as $row) {
        $owner_email = $row->email;
        $owner_name = $row->name;
        if($owner_email!=''){
        $data['data']=array(
             'owner_name'=>$owner_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'sport_name'=>$kcu_sportsname,
             'start_date'=>$start_inactivedate,
             'end_date'=>$end_inactivedate,
             'start_time'=>$start_inactivetime,
             'end_time'=>$end_inactivetime,
             'sunday'=>$sun,
             'monday'=>$mon,
             'tuesday'=>$tue,
             'wednesday'=>$wed,
             'thursday'=>$thu,
             'friday'=>$fri,
             'saturday'=>$sat,
             'reason'=>$reason,
             );
          $to_email = $owner_email;
          $subject='Court Inactivated !!';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('inactivate_owner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for venue owner end */
                              	$this->session->set_flashdata('success-msg',"updated");
                              	redirect("venue/venue_edit/$venue_id?inactive=1");
                              }else{
                              	if($rest==0){

       /*email for venue owner start */
    $owneremail=$this->Inactivate_model->get_owner($venue_id);
    foreach($owneremail as $row) {
        $owner_email = $row->email;
        $owner_name = $row->name;
        if($owner_email!=''){
        $data['data']=array(
             'owner_name'=>$owner_name,
             'venue_name'=>$venue_name,
             'court_name'=>$kcu_courtname,
             'sport_name'=>$kcu_sportsname,
             'start_date'=>$start_inactivedate,
             'end_date'=>$end_inactivedate,
             'start_time'=>$start_inactivetime,
             'end_time'=>$end_inactivetime,
             'sunday'=>$sun,
             'monday'=>$mon,
             'tuesday'=>$tue,
             'wednesday'=>$wed,
             'thursday'=>$thu,
             'friday'=>$fri,
             'saturday'=>$sat,
             'reason'=>$reason,
             );
          $to_email = $owner_email;
          $subject='Court Inactivated !!';
          $this->load->library('email');
          $config['protocol']    = 'smtp';
          $config['smtp_host']    = 'upupup.in';
          $config['smtp_port']    = '25';
          $config['smtp_timeout'] = '7';
          $config['smtp_user']    = 'admin@upupup.in';
          $config['smtp_pass']    = '%S+1q)yiC@DW';
          $config['charset']    = 'utf-8';
          $config['newline']    = '\r\n';
          $config['mailtype'] = 'html'; // or html
          $config['validation'] = TRUE; // bool whether to validate email or not
          $this->email->initialize($config);
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('admin@upupup.in','upUPUP.');
          $this->email->to($to_email);
          $this->email->subject($subject);
          $message = $this->load->view('inactivate_owner_mail',$data,true);
          $this->email->message($message);
          $this->email->send();
              
            }
       }
     /*email for venue owner end */
                              	$this->session->set_flashdata('success-msg',"inserted");
                              	redirect("venue/venue_edit/$venue_id?inactive=1");
                              	}else{
                              	  $this->session->set_flashdata('error-msg',"error");
			                     redirect("venue/venue_edit/$venue_id?inactive=1");	
                              	}
                              }
							}

		
	}


  public function inactivate_edit($id,$venue_id)
	{
		
		$data['details']=$this->Inactivate_model->get_details($id);
                 $court_name=$this->Inactivate_model->get_incourt_name($id,$venue_id);
                 $data['days']=$this->Inactivate_model->get_inactive_days($id);
                $data['court']=$court_name;
$data['venue']=$venue_id;
$data['inactive']=$id;

		$this->load->template('edit',$data);
	}

public function edit_inactive($id='',$venue_id=''){
		if($this->input->post()){

			$court_id=$this->input->post('court');
			$stdate=$this->input->post('day');
            $etdate=$this->input->post('days');
            $sdate=date("Y-m-d", strtotime($stdate));
            $edate=date("Y-m-d", strtotime($etdate));
            $stime=$this->input->post('stime');
            $etime=$this->input->post('etime');
            $sun=$this->input->post('sun');
            $mon=$this->input->post('mon');
            $tue=$this->input->post('tue');
            $wed=$this->input->post('wed');
            $thu=$this->input->post('thu');
            $fri=$this->input->post('fri');
            $sat=$this->input->post('sat');
            $description=$this->input->post('description');
            $starttime= date("H:i:s", strtotime($stime));
            $endtime= date("H:i:s", strtotime($etime));
            $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
            $timedifference=$endtime-$starttime;

           $sunday="Sun";
           $monday="Mon";
           $tuesday="Tue";
           $wednesday="Wed";
           $thursday="Thu";
           $friday="Fri";
           $saturday="Sat";

            for($k=0;$k<=$datedifference;$k++){
						$date = strftime("%Y-%m-%d", strtotime("$sdate +$k day"));
						

						for($l=0;$l<$timedifference;$l++){
							$time = date('H:i:s', strtotime($starttime.'+'.$l.' hour'));
							$timet=$this->Inactivate_model->get_bookingexist($venue_id,$court_id,$date,$time);

							 if($timet)
				                     {
				                      $mdt=2;
				                     }else{
				                       if($mdt==2){
				              	         $mdt=2;
				                           }else{
				              	            $mdt=1;
				                             }
				                          }

						    }
					 }
		    if($mdt==2){

		     $this->session->set_flashdata('error-msg',"booking exist");
			redirect("venue/venue_edit/$venue_id?inactive=1");
	        }else{

	        $data=array(
			         'venue_id'=>$venue_id,
			         'court_id'=>$court_id,
			         'sdate'=>$sdate,
			         'edate'=>$edate,
			         'stime'=>$starttime,
			         'etime'=>$endtime,
			         'description'=>$description,
                                 'added_date'=>date('Y-m-d h:i:sa')
			        );

           $adds=$this->Inactivate_model->update_inactivate($data,$id);
           $test=$this->Inactivate_model->delete_inactivatecourtup($id);



             if($adds){
        for ($j=0; $j <=$datedifference ; $j++) { 
        $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
        $nameOfDay = date('D', strtotime($date));
        
        if($sun!=0 && $sunday==$nameOfDay)
		{
				for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}
		}

				if($mon!=0 && $monday==$nameOfDay)
		{


					for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}
			
		}
		if($tue!=0 && $tuesday==$nameOfDay)
		{


					for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}
			
		}
		if($wed!=0 && $wednesday==$nameOfDay)
		{

        	for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}
		}
		 if($thu!=0 && $thursday==$nameOfDay)
		{
        	for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}

		}
		if($fri!=0 && $friday==$nameOfDay)
		{

        	for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}
		}
		if($sat!=0 && $saturday==$nameOfDay)
		{

        	for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				
				$datas=array(
			        'inactive_court_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->Inactivate_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
                    $f=1;
					}else{
						if($rest==0){
                        $rest=0;
                        $f=1;
						}else{
						$rest=1;	 
						}	 
						}
			}
		 }




        }

        if($adds){
        $this->session->set_flashdata('success-msg',"updated");
        redirect("venue/venue_edit/$venue_id?inactive=1");	
    }else{
    	$this->session->set_flashdata('error-msg',"error");
		redirect("venue/venue_edit/$venue_id?inactive=1");
    }



        }else{

        $this->session->set_flashdata('error-msg',"try again");
		redirect("venue/venue_edit/$venue_id?inactive=1");
        }


	        	
	        }

            

		}
	}

    ////////////////////////////Court////////////////////////////////////////////////////
    public function court_listin($venue_id=''){
        //print_r($venue_id);exit();
        $sports  = $this->input->post('sports');
        $sports_id=$sports[0];
        //print_r($sports_id);exit();
        $data['court']  =$this->Inactivate_model->get_courtlistin($venue_id,$sports_id);
       //print_r($data['court']);exit();
        if ($data) {
            echo json_encode($data);
        }
    }
	
		

	
}

