<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Offer extends CI_controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('url'));
		$this->load->model('offer_model');
		$this->load->library('notification');
		if(isset($_SESSION['signed_in'])==TRUE ){
			
		}else{
			redirect('acl/user/sign_in');
		}
			
	}
	public function index() {
		
		if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {

			
		}else{
						
			$this->load->template('list');
		}
	}
        
	
		
	public function offerTable($venue_id=''){
		$edit = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_offer');
		$delete = $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_offer');
		$table=$this->offer_model->get_offerTable($edit,$delete,$venue_id);
		echo json_encode($table);
	}
	
	public function add($venue_id=''){
           $data['venue']=$this->offer_model->get_venue();
			$data['venue_offer']=$venue_id;
			$data['sports']=$this->offer_model->get_venue_sports($venue_id);
			$data['court']=$this->offer_model->get_venue_court($venue_id);
		
	                //echo "<pre>";print_r($data['sports']);exit();
			$this->load->template('add',$data);
	}
    public function add_offer($venue_id=''){
		if($this->input->post()){

            $sports=$this->input->post('sports');
           $court=$this->input->post('court');
	    	$offer=$this->input->post('offer');
	   	 	$percentage=$this->input->post('percentage');
	    	$amount=$this->input->post('amount');
            $type=$this->input->post('radio2text');
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
            $status=$this->input->post('status');
            $starttime= date("H:i:s", strtotime($stime));
            $endtime= date("H:i:s", strtotime($etime));
            $count_sports=sizeof($sports);
	    
	    $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
            $timedifference=$endtime-$starttime;
            $exceed=0;
                        $courtlst=0;
                for($dc=0;$dc<$count_sports;$dc++){
                 $sports_id=$sports[$dc];
                 $court_datas=$this->offer_model->get_courtofspt($sports_id,$venue_id);
                 if(!empty($court_datas)){
                  $courtlst=1;
                 }else{
                  if($courtlst==1){
                     $courtlst=1;
                  }else{
                    $courtlst=0;
                  }
                 }
                }

            if($courtlst==1 || $count_sports==0){
            if($court == Null){
            /////////////////////// court value if null start////////////////////////////
            	$courtst=[];
                for($r=0;$r<$count_sports;$r++){
         	     $sports_id=$sports[$r];
            	$final_array=$this->offer_model->get_courtlistsof($sports_id,$venue_id);
            	$courtst = array_merge($courtst,$final_array);
            	}
            	$courtst = array_values($courtst);
            	$courtof_court = json_decode(json_encode($courtst), True); 
            	$count_court=sizeof($courtof_court);
            	//////////////////////////////////////////////////////////////////
               if($type =="Radiobutton1"){
      	          $pre=$percentage;
                  $price=0;
                /////////////// checking offer value is grater than Rs 15 start ///////////////

                   for($k=0;$k<$count_court;$k++){
         				$court_id=$courtof_court[$k][id];
         				$court_nameof=$courtof_court[$k][court];
         				$court_costof=$courtof_court[$k][cost];
         				$tot_amt=$court_costof *($percentage/100);
						$balance_amt=$court_costof-$tot_amt;
						if($balance_amt < 15){
         					$exceed=1;
         					$court_nume=$court_nameof;
							}else{

							//////// else case start ////////
							if($exceed==1){
								$exceed=1;	
							}else{
								$exceed=0;	
							}
							//////// else case end ////////
					}
         	    /////////////// checking offer value is grater than Rs 15 end ///////////////
            		}

              	}else{
             		$pre=0;
      	     		$price=$amount;
      	     		for($k=0;$k<$count_court;$k++){
         				$court_id=$courtof_court[$k][id];
         				$court_nameof=$courtof_court[$k][court];
         				$court_costof=$courtof_court[$k][cost];
						$balance_amt=$court_costof-$amount;
						if($balance_amt < 15){
         					$exceed=1;
         					$court_nume=$court_nameof;
							}else{

							//////// else case start ////////
							if($exceed==1){
								$exceed=1;	
							}else{
								$exceed=0;	
							}
							//////// else case end ////////
					}
         	    /////////////// checking offer value is grater than Rs 15 end ///////////////
            		}
           			}
           	 /////////////////////// court value if null end////////////////////////////     
			}else{
			 /////////////////////// court value if not null start////////////////////////////
            $count_court=sizeof($court);
   				//////////////////////////////////////////////////////////////////
               if($type =="Radiobutton1"){
      	          $pre=$percentage;
                  $price=0;
                /////////////// checking offer value is grater than Rs 15 start ///////////////

                   for($k=0;$k<$count_court;$k++){
         				$court_id=$court[$k];
         				$court_dataoff=$this->offer_model->get_courtdataoff($court_id);
         				$cou_court = json_decode(json_encode($court_dataoff), True); 
         				
            	        $cout_court=sizeof($cou_court);
            	        for($p=0;$p<$cout_court;$p++){
         				$court_nameof=$cou_court[$p][court];
         				$court_costof=$cou_court[$p][cost];
         			   } 


         				$tot_amt=$court_costof *($percentage/100);
						$balance_amt=$court_costof-$tot_amt;
						if($balance_amt < 15){
         					$exceed=1;
         					$court_nume=$court_nameof;
							}else{

							//////// else case start ////////
							if($exceed==1){
								$exceed=1;	
							}else{
								$exceed=0;	
							}
							//////// else case end ////////
					}
         	    /////////////// checking offer value is grater than Rs 15 end ///////////////
            		}

              	}else{
             		$pre=0;
      	     		$price=$amount;
      	     		for($k=0;$k<$count_court;$k++){
         				$court_id=$court[$k];
         				$court_dataoff=$this->offer_model->get_courtdataoff($court_id);
         				$cou_court = json_decode(json_encode($court_dataoff), True); 
         				
            	        $cout_court=sizeof($cou_court);
            	        for($p=0;$p<$cout_court;$p++){
         				$court_nameof=$cou_court[$p][court];
         				$court_costof=$cou_court[$p][cost];
         			   } 
						$balance_amt=$court_costof-$amount;
						if($balance_amt < 15){
         					$exceed=1;
         					$court_nume=$court_nameof;
							}else{

							//////// else case start ////////
							if($exceed==1){
								$exceed=1;	
							}else{
								$exceed=0;	
							}
							//////// else case end ////////
					}
         	    /////////////// checking offer value is grater than Rs 15 end ///////////////
            		}
           			}     
        	 /////////////////////// court value if not  null end ////////////////////////////
            }
           if($exceed==1){

      	//////////////////////////////////////////////////////////////////
                  if($type =="Radiobutton1"){
                  	$display_msg=$court_nume." price cant be less than Rs.15, kindly amend the offer percentage";
                   $this->session->set_flashdata('error-msg',$display_msg);
    				redirect("venue/venue_edit/$venue_id?offer=1");
					}else{
					$display_msg=$court_nume." price cant be less than Rs.15, kindly amend the offer amount";
             		$this->session->set_flashdata('error-msg',$display_msg);
    				redirect("venue/venue_edit/$venue_id?offer=1");
           			} 
        //////////////////////////////////////////////////////////////////
              }else{
        /////////////////////////////////////////////////////////////////
             if($court == Null){
            	$courts=[];
            	for($r=0;$r<$count_sports;$r++){
         	     $sports_id=$sports[$r];
            	$final_array=$this->offer_model->get_courtlistsof($sports_id,$venue_id);
            	$courts = array_merge($courts,$final_array);
            	}
            	$courts = array_values($courts);
            	$court = json_decode(json_encode($courts), True); 
            	$count_court=sizeof($court);
        $missmatch=0;
        if($count_court==0 || $datedifference==0){
         $totallooping=1*$datedifference+1*$count_court;	
     }else{
     	$totallooping=($datedifference+1)*$count_court;	
     }
         if($type =="Radiobutton1"){
      	    $pre=$percentage;
            $price=0;
         }else{
        $pre=0;
      	$price=$amount;
      }      

            $sunday="Sun";
            $monday="Mon";
            $tuesday="Tue";
            $wednesday="Wed";
            $thursday="Thu";
            $friday="Fri";
            $saturday="Sat";
//for loop1
         for($i=0;$i<$count_court;$i++){
         	$court_id=$court[$i][id];
            //echo "<pre>";print_r($court_id);exit();

    //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
     

 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){

				//for loop3
				for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				$checkoffer=$this->offer_model->get_offerexist($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($checkoffer)
	            {
                 $alreadyadded=1;
				}else{
					if($alreadyadded==1){
						$alreadyadded=1;
					}else{
						$alreadyadded=0;
					}
					
			    }
			}//end of for loop3	
			}else{
				$missmatch=$missmatch+1;
			} 






            }//end of for loop2
         } //end of for loop 1

      if($alreadyadded==1){
		$this->session->set_flashdata('error-msg',"already added");
		 redirect("offer/add/$venue_id?offer=1");
		}else{
          if($missmatch==$totallooping){

		$this->session->set_flashdata('error-msg',"Selected Dates & Days Miss Match");
		 redirect("offer/add/$venue_id?offer=1");
	}else{

		$data=array(
        	'offer'=>$offer,
			'venue_id'=>$venue_id,
			'amount'=>$price,
			'percentage'=>$pre,
			'start'=>$sdate,
			'end'=>$edate,
            'start_time'=>$starttime,
			'end_time'=>$endtime,
			'added_date'=>date('Y-m-d h:i:sa'),
			'status'=>$status
			);
		$adds=$this->offer_model->insert_offer($data);
        $offer_id=$adds;
        for($k=0;$k<$count_court;$k++){
         	$court_id=$court[$k][id];
         	$data=array(
        	'court_id'=>$court_id,
			'offer_id'=>$offer_id
			);
         $adds=$this->offer_model->insert_offercourt($data);
             //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));


 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){
        //for loop3
				for($m=0;$m<$timedifference;$m++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$m.' hour'));  
				$datas=array(
			        'offer_id'=>$offer_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        ); 
			 $addtime=$this->offer_model->insert_offertime($datas);
			 if($addtime){
			 	$offertime=1;
			 }else{
			 	if($offertime==1){
			 		$offertime=1;
			 	}else{
			 		$offertime=0;
			 	}
			 }
			}//end of for loop3
				
			}


            }//end of for loop2
         }

	}
		}

		if($offertime==1){
	$this->session->set_flashdata('success-msg',"New Offer has been added!");
    redirect("venue/venue_edit/$venue_id?offer=1");
}else{
	
	$this->session->set_flashdata('error-msg','Offer not added!');
				redirect("venue/venue_edit/$venue_id?offer=1");		
} 
            }else{
            $count_court=sizeof($court);
        $missmatch=0;
        if($count_court==0 || $datedifference==0){
         $totallooping=1*$datedifference+1*$count_court;	
     }else{
     	$totallooping=($datedifference+1)*$count_court;	
     }
         if($type =="Radiobutton1"){
      	    $pre=$percentage;
            $price=0;
         }else{
        $pre=0;
      	$price=$amount;
      }      

            $sunday="Sun";
            $monday="Mon";
            $tuesday="Tue";
            $wednesday="Wed";
            $thursday="Thu";
            $friday="Fri";
            $saturday="Sat";
//for loop1
         for($i=0;$i<$count_court;$i++){
         	$court_id=$court[$i];
            //echo "<pre>";print_r($court_id);exit();

    //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
     

 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){

				//for loop3
				for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				$checkoffer=$this->offer_model->get_offerexist($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($checkoffer)
	            {
                 $alreadyadded=1;
				}else{
					if($alreadyadded==1){
						$alreadyadded=1;
					}else{
						$alreadyadded=0;
					}
					
			    }
			}//end of for loop3	
			}else{
				$missmatch=$missmatch+1;
			} 






            }//end of for loop2
         } //end of for loop 1

      if($alreadyadded==1){
		$this->session->set_flashdata('error-msg',"already added");
		 redirect("offer/add/$venue_id?offer=1");
		}else{
          if($missmatch==$totallooping){

		$this->session->set_flashdata('error-msg',"Selected Dates & Days Miss Match");
		 redirect("offer/add/$venue_id?offer=1");
	}else{

		$data=array(
        	'offer'=>$offer,
			'venue_id'=>$venue_id,
			'amount'=>$price,
			'percentage'=>$pre,
			'start'=>$sdate,
			'end'=>$edate,
            'start_time'=>$starttime,
			'end_time'=>$endtime,
			'added_date'=>date('Y-m-d h:i:sa'),
			'status'=>$status
			);
		$adds=$this->offer_model->insert_offer($data);
        $offer_id=$adds;
        for($k=0;$k<$count_court;$k++){
         	$court_id=$court[$k];
         	$data=array(
        	'court_id'=>$court_id,
			'offer_id'=>$offer_id
			);
         $adds=$this->offer_model->insert_offercourt($data);
             //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));


 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){
        //for loop3
				for($m=0;$m<$timedifference;$m++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$m.' hour'));  
				$datas=array(
			        'offer_id'=>$offer_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        ); 
			 $addtime=$this->offer_model->insert_offertime($datas);
			 if($addtime){
			 	$offertime=1;
			 }else{
			 	if($offertime==1){
			 		$offertime=1;
			 	}else{
			 		$offertime=0;
			 	}
			 }
			}//end of for loop3
				
			}


            }//end of for loop2
         }

	}
		}

		if($offertime==1){
	$this->session->set_flashdata('success-msg',"New Offer has been added!");
    redirect("venue/venue_edit/$venue_id?offer=1");
}else{
	
	$this->session->set_flashdata('error-msg','Offer not added!');
				redirect("venue/venue_edit/$venue_id?offer=1");		
}
	        	
            }
        ////////////////////////////////////////////////////////////////
              	}
            
            }else{
           $this->session->set_flashdata('error-msg','Offer not added!');
                redirect("venue/venue_edit/$venue_id?offer=1");  
         }  
			
		}
	
	}

	       
public function edit($id,$venue_id=''){
		
		$data['details']=$this->offer_model->get_offerdetails($id);
                $data['court']=$this->offer_model->get_offercourt($id);
                $data['days']=$this->offer_model->get_offerdays($id);
                $data['venue']=$venue_id;
                $data['offer']=$id;

		$this->load->template('edit',$data);
	}


public function offer_edit($id,$venue_id=''){
             $court=$this->input->post('court');
	     $offer=$this->input->post('offer');
	    $percentage=$this->input->post('percentage');
	    $amount=$this->input->post('amount');
            $type=$this->input->post('radio2text');
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
            $status=1;
            $starttime= date("H:i:s", strtotime($stime));
            $endtime= date("H:i:s", strtotime($etime));
            $count_court=sizeof($court);
            $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
            $timedifference=$endtime-$starttime;

             $missmatch=0;
        if($count_court==0 || $datedifference==0){
         $totallooping=1*$datedifference+1*$count_court;	
     }else{
     	$totallooping=($datedifference+1)*$count_court;	
     }
         if($type =="Radiobutton1"){
      	    $pre=$percentage;
            $price=0;
         }else{
        $pre=0;
      	$price=$amount;
      }


            $sunday="Sun";
            $monday="Mon";
            $tuesday="Tue";
            $wednesday="Wed";
            $thursday="Thu";
            $friday="Fri";
            $saturday="Sat";
//for loop1
         for($i=0;$i<$count_court;$i++){
         	$court_id=$court[$i];


    //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));
     

 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){

				//for loop3
				for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$k.' hour'));
				$checkoffer=$this->offer_model->get_offerexists($venue_id,$court_id,$date,$time,$nameOfDay,$id);   
				if($checkoffer)
	            {
                 $alreadyadded=1;
				}else{
					if($alreadyadded==1){
						$alreadyadded=1;
					}else{
						$alreadyadded=0;
					}
					
			    }
			}//end of for loop3	
			}else{
				$missmatch=$missmatch+1;
			} 






            }//end of for loop2
         } //end of for loop 1

       if($alreadyadded==1){
		$this->session->set_flashdata('error-msg',"already added");
		 redirect("offer/edit/$id/$venue_id?offer=1");
		}else{
          if($missmatch==$totallooping){

		$this->session->set_flashdata('error-msg',"Selected Dates & Days Miss Match");
		 redirect("offer/edit/$id/$venue_id?offer=1");
	}else{
        $datast=$this->offer_model->delete_offertime($id);
		$data=array(
        	'offer'=>$offer,
			'venue_id'=>$venue_id,
			'amount'=>$price,
			'percentage'=>$pre,
			'start'=>$sdate,
			'end'=>$edate,
                        'start_time'=>$starttime,
			'end_time'=>$endtime,
			'added_date'=>date('Y-m-d h:i:sa'),
			'status'=>$status
			);
		$adds=$this->offer_model->update_offer($data,$id);

       
        for($k=0;$k<$count_court;$k++){
         	$court_id=$court[$k];
         	
             //checks all date from start to end for loop2
        for ($j=0; $j <=$datedifference ; $j++) { 
            $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
            $nameOfDay = date('D', strtotime($date));


 if(($sun!=0 && $sunday==$nameOfDay) || ($mon!=0 && $monday==$nameOfDay) || ($tue!=0 && $tuesday==$nameOfDay) || ($wed!=0 && $wednesday==$nameOfDay) || ($thu!=0 && $thursday==$nameOfDay) || ($fri!=0 && $friday==$nameOfDay) || ($sat!=0 && $saturday==$nameOfDay)){
        //for loop3
				for($m=0;$m<$timedifference;$m++)
				{
				$time = date('H:i:s', strtotime($starttime.'+'.$m.' hour'));  
				$datas=array(
			        'offer_id'=>$id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        ); 
			 $addtime=$this->offer_model->insert_offertime($datas);
			 if($addtime){
			 	$offertime=1;
			 }else{
			 	if($offertime==1){
			 		$offertime=1;
			 	}else{
			 		$offertime=0;
			 	}
			 }
			}//end of for loop3
				
			}


            }//end of for loop2
         }

	}
		}

		if($offertime==1){
	$this->session->set_flashdata('success-msg',"Offer Updated!");
    redirect("venue/venue_edit/$venue_id?offer=1");
}else{
	
	$this->session->set_flashdata('error-msg','Offer not added!');
				redirect("offer/edit/$id/$venue_id?offer=1");		
}




            

	}
	
	
	public function delete($id,$venue_id="")
	{	//print_r($venue_id);exit();
		$data=$this->offer_model->delete_offer($id);
            $datas=$this->offer_model->delete_offercourt($id);
            $datast=$this->offer_model->delete_offertime($id);
 if($data){
$this->session->set_flashdata('success-msg','Offer has been  Deleted!');
				redirect("venue/venue_edit/$venue_id?offer=1");
}else{
$this->session->set_flashdata('error-msg','Offer not deleted!');
				redirect("venue/venue_edit/$venue_id?offer=1");	
}
		
	}

	public function _edit_unique($str,$id,$venue_id='')
	{
		sscanf($id, '%[^.].%[^.].%[^.]', $field,$id,$venue_id);
		//print_r($str);
		//print_r($venue_id);
		$count =$this->offer_model->offer_unique($str,$id,$venue_id);
		/*print_r($venue_id);
		print_r($count);exit;*/
		//print_r(!empty($count) ? FALSE:TRUE);exit;
		$this->form_validation->set_message('_edit_unique', 'Offer already exists.');
		return (!empty($count)) ? FALSE:TRUE;
	}
	/////////////////////////Change Status/////////////////////////////////////
	public function offer_status($id,$status,$venue_id)
	{ 
		
        if($status ==1){
        	$data=array('status'=>'0');
        }else{
        	$data=array('status'=>'1');
        }
            $this->offer_model->update_offerdata($id,$data);
			$this->session->set_flashdata('success-msg','Status has been changed!');
			redirect("venue/venue_edit/$venue_id?offer=1");	
	
	}
public function sports_list(){
     $sports=$this->input->post('sports');
     echo "<pre>";print_r($sports);exit();      
	}
          ////////////////////////////Court////////////////////////////////////////////////////
    public function court_list($venue_id=''){
        $sports  = $this->input->post('sports');
        $sports_id=$sports[0];
        //print_r($venue_id);exit();
        $data['court']  =$this->offer_model->get_courtlistoff($venue_id,$sports_id);
       //print_r($data['court']);exit();
        if ($data) {
            echo json_encode($data);
        }
    }
	////////////////////////////////////////////////////////////////////////////
	
}

