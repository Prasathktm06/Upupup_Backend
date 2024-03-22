<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Inactive extends REST_Controller {

	function __construct()
	{

		parent::__construct();

		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->model("api/inactive_model");

	}
//sports id selection for inactive courts on based venue
public function index_post(){
	       $venue_id=$this->input->post('venue_id');
		$data=$this->inactive_model->get_sports($venue_id);

           if($data){

		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }

	}
//court details selection for inactive courts on based venue and sports
public function sports_post(){
	       $venue_id=$this->input->post('venue_id');
           $sports_id=$this->input->post('sports_id');

			$data=$this->inactive_model->get_courts($venue_id,$sports_id);

           if($data){

		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }

	}
//court list when multiple sports selected in vendors app
public function inactivecourts_post(){
	       $sportslist=$this->input->post('sportslist');
	       $count=$this->input->post('count');
	       $json_output = json_decode($sportslist,TRUE );
               $final_array=[];
	       for($i=0;$i<$count;$i++){
	            $venue_id=$json_output[$i][venue_id];
	            $sports_id= $json_output[$i][sports_id];
                    $result=$this->inactive_model->get_courtlist($venue_id,$sports_id);
		    $final_array = array_merge($final_array,$result);
                                
			}
	      return $this->response($final_array,200); 
	}

//check booking exist on selection of date and time of court inactivation
public function bookingexist_post(){
               $final_array=[];
	       $bookingexist=$this->input->post('bookingexist');

	       $count=$this->input->post('count');

		   $json_output = json_decode($bookingexist,TRUE );

		   		for($i=0;$i<$count;$i++){
				$venue_id=$json_output[$i][venue_id];
                                $sports_id=$json_output[$i][sports_id];
				$court_id=$json_output[$i][court];
				$sdate=$json_output[$i][from_date];
				$edate=$json_output[$i][to_date];
				$stime=$json_output[$i][from_time];
				$etime=$json_output[$i][to_time];

                $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
                $timedifference=$etime-$stime;
                
                for ($j=0; $j <=$datedifference ; $j++) 
                { 
                $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
                 
		                 for($k=0;$k<=$timedifference;$k++)
		                 {
		                    $time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
		                    $timet=$this->inactive_model->get_bookingexist($venue_id,$court_id,$date,$time);
                                    $final_array = array_merge($final_array,$timet);

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
				
			}


           if($mdt==2){

		    return $this->response($final_array,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }
	}


//insertion data of inactive court
public function inactivatecourt_post(){
	       $inactivate=$this->input->post('inactivate');

	       $count=$this->input->post('count');

		   $json_output = json_decode($inactivate,TRUE );
            $f=0;
			for($i=0;$i<$count;$i++){
				$user_id=$json_output[$i][user_id];
				$venue_id=$json_output[$i][venue_id];
				$court_id=$json_output[$i][court];
				$sdate=$json_output[$i][from_date];
				$edate=$json_output[$i][to_date];
				$stime=$json_output[$i][from_time];
				$etime=$json_output[$i][to_time];
				$sun=$json_output[$i][sun];
				$mon=$json_output[$i][mon];
				$tue=$json_output[$i][tue];
				$wed=$json_output[$i][wed];
				$thu=$json_output[$i][thu];
				$fri=$json_output[$i][fri];
				$sat=$json_output[$i][sat];
				$name=$json_output[$i][name];
				$email=$json_output[$i][email];
                $description=$json_output[$i][description];
				
                $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
                $timedifference=$etime-$stime;
                $curre=date('Y-m-d h:i:sa');

                $sunday="Sun";
                $monday="Mon";
                $tuesday="Tue";
                $wednesday="Wed";
                $thursday="Thu";
                $friday="Fri";
                $saturday="Sat";
                $data=array(
			         'venue_id'=>$venue_id,
			         'court_id'=>$court_id,
			         'sdate'=>$sdate,
			         'edate'=>$edate,
                                 'stime'=>$stime,
			         'etime'=>$etime,
			         'description'=>$description,
                                 'added_date'=>$curre
			        );
				$adds=$this->inactive_model->insert_inactivecourt($data);
                                if($adds){
					$inactive_court_id=$adds;
				}else{
					$result="error";
				}

                for ($j=0; $j <=$datedifference ; $j++) { 
                $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
                $nameOfDay = date('D', strtotime($date));
                
                if($sun!=0 && $sunday==$nameOfDay)
				{
				for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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

        for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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

        for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
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

        for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
					if($addtime){
                    $rest=0;
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
        for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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

        for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							}	 
						}
					}else{
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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

        for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$timet=$this->inactive_model->get_inactivecourttime($venue_id,$court_id,$date,$time,$nameOfDay);   
				if($timet)
	            {
					foreach ($timet as $row){
					$id=$row['id'];
					}
					$data=$this->inactive_model->delete_inactivatecourttime($id);
					if($data){
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
					if($addtime){
						if($addtime){
                        $rest=1;
						}else{
							if($rest==1){
                            $rest=1;
                            $f=1;
							}else{
							$rest=0;	 
							} 
						}
					}else{
					$result="error";
					return $this->response($result,200);
					}	
					}else{
					$result="error";
					return $this->response($result,200);
					}
				}else{
					$datas=array(
			        'inactive_court_id'=>$inactive_court_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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

$d=$this->inactive_model->delete_emptytimes();	
			if($rest==1){
					$result="updated";
				    return $this->response($result,200);
						}else{
                              if($f==0){
                              	$result="miss match";
                              	
				    			return $this->response($result,200);
                              }else{
                              	if($rest==0){
                              	$result="insert";
				    			return $this->response($result,200);	
                              	}else{
                              	 $result="error";
				    			return $this->response($result,200);	
                              	}
                              }
							}

		
	
}
//inactive courtlist based on venue
public function inactivecourtlist_post(){
    $venue_id=$this->input->post('venue_id');

	$data=$this->inactive_model->get_inactivecourtlist($venue_id);

           if($data){


		
      	     
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }
	}
//inactive courtlist based on venue,sports filter
public function sportsfilter_post(){
    $venue_id=$this->input->post('venue_id');
    $sports_id=$this->input->post('sports_id');

	$data=$this->inactive_model->get_sportsfilter($venue_id,$sports_id);

           if($data){


		
      	     
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }
	}

	//courtdetails based on venue_id
public function courtdetails_post(){
    $venue_id=$this->input->post('venue_id');

	$data=$this->inactive_model->get_courtdetails($venue_id);

           if($data){


		
      	     
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }
	}



//inactive courtlist based on venue,sports filter
public function courtfilter_post(){
    $venue_id=$this->input->post('venue_id');
    $court_id=$this->input->post('court_id');

	$data=$this->inactive_model->get_courtfilter($venue_id,$court_id);

           if($data){

		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }
	}

//inactive single court details
public function singlecourt_post(){
    $inactive_id=$this->input->post('inactive_id');

	$data=$this->inactive_model->singlecourt($inactive_id);

           if($data){


		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }
	}

//insertion data of inactive court
public function inactivateupdate_post(){
	       $inactive_id=$this->input->post('inactive_id');
	       $venue_id=$this->input->post('venue_id');
	       $court_id=$this->input->post('court_id');
	       $sdate=$this->input->post('sdate');
	       $edate=$this->input->post('edate');
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
	       $sunday="Sun";
           $monday="Mon";
           $tuesday="Tue";
           $wednesday="Wed";
           $thursday="Thu";
           $friday="Fri";
           $saturday="Sat";
           $curre=date('Y-m-d h:i:sa');

           $datedifference = ceil(abs(strtotime($edate)- strtotime($sdate)) /86400);
           $timedifference=$etime-$stime;

           $data=array(
			         'venue_id'=>$venue_id,
			         'court_id'=>$court_id,
			         'sdate'=>$sdate,
			         'edate'=>$edate,
			         'stime'=>$stime,
			         'etime'=>$etime,
			         'description'=>$description,
                                 'added_date'=>$curre
			        );

           $adds=$this->inactive_model->update_inactivate($data,$inactive_id);
           $test=$this->inactive_model->delete_inactivatecourtupdate($inactive_id);


          if($adds){
        for ($j=0; $j <=$datedifference ; $j++) { 
        $date = strftime("%Y-%m-%d", strtotime("$sdate +$j day"));
        $nameOfDay = date('D', strtotime($date));
        
        if($sun!=0 && $sunday==$nameOfDay)
		{
				for($k=0;$k<$timedifference;$k++)
				{
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
				$time = date('H:i:s', strtotime($stime.'+'.$k.' hour'));
				$datas=array(
			        'inactive_court_id'=>$inactive_id,
			        'time'=>$time,
			        'day'=>$nameOfDay
			        );  
					$addtime=$this->inactive_model->insert_inactivecourttime($datas);
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
        $result="updated";
        return $this->response($result,200);	
    }else{
    	$result="error";
        return $this->response($result,200);
    }



        }else{

        $result="error";
        return $this->response($result,200);
        }	
	
}

//delete single inactive court
public function singlecourtdelete_post(){
	      $inactive_id=$this->input->post('inactive_id');
		
	      $data=$this->inactive_model->delete_singlecourt($inactive_id);
              $d=$this->inactive_model->delete_timings();
                if($data){ 
                     $result="success";
		    return $this->response($result,200);
	        }else{
	  	      
	  	    $result="fail";
		     return $this->response($result,200);
	        }     

	}

//sports image based on court_id
public function sportsimage_post(){
        $courts_id=$this->input->post('court_id');


			$data=$this->inactive_model->sportsimage($courts_id);


           if($data){


		
      	     
		    return $this->response($data,200);
	        }else{
	  	      
	  	    $result="not exist";
		     return $this->response($result,200);
	        }

	}


       





}
