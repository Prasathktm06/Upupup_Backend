<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Trainer extends REST_Controller {

    function __construct()
    {

        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("api/trainer_model");
        $this->load->library("notification");
        date_default_timezone_set("Asia/Kolkata");

    }


/////////////////////////// add trainer start /////////////////////////////////////////

    public function trainer_post()
    {
         $user_id=$this->input->post('user_id');
         $name=$this->input->post('user_name');
         $age=$this->input->post('user_age');
         $phone=$this->input->post('user_phone');
         $address=$this->input->post('user_address');
         $experience=$this->input->post('user_experiance');
         $location_id=$this->input->post('location_id');
         $trainer_check=$this->trainer_model->get_trainer_check($phone);
         if(empty($trainer_check))
         {
            $data=array(
                        'name'=>$name,
                        'age'=>$age,
                        'location_id'=>$location_id,
                        'address'=>$address,
                        'phone'=>$phone,
                        'experience'=>$experience,
                        'certifications'=>"",
                        'availability'=>"",
                        'profile_image'=>"",
                        'training_image'=>"",
                        'achievement'=>"",
                        'status'=>0,
                        'added_date'=>date('Y-m-d H:i:s'),
                );
            $trainer_id=$this->trainer_model->add_data('trainers',$data);
            $sports=json_decode($this->input->post('sports'));
                foreach ($sports as $val){
                    $data = array(
                            'trainers_id' =>$trainer_id,
                            'sports_id' =>$val,
                            'status' =>1,
                            'added_date' =>  date('Y-m-d H:i:s'),
                    );
                    $this->trainer_model->add_data('trainers_sports',$data);
                }
                if(!empty($trainer_id)){
                    $result=array(
                            'errorCode'=>1,
                            'data'=>$trainer_id,
                            'message'=>"success"
                            );
                    return $this->response($result,200);
                }else{
                    $result=array(
                            'errorCode'=>0,
                            'data'=>0,
                            'message'=>"empty"
                            );
                    return $this->response($result,200);     
                }
         }else{
                $result=array(
                        'errorCode'=>0,
                        'data'=>0,
                        'message'=>"Phone Number Already Registered"
                        );
                return $this->response($result,200); 
         }
    }


//////////////////////////////////////// add trainer start ///////////////////////////////////////
/////////////////////////// trainer list details start /////////////////////////////////////////

    public function trainer_list_post()
    {
         $user_id=$this->input->post('user_id');
         $location_ids=$this->input->post('location_id');
///////////////////////////////////////////////// sports and area based filter list of trainers start ////////////////////////////////////////////////
         $filter_lists=$this->trainer_model->get_trainer_list($user_id,$location_ids);
                $filter_list=[];
                foreach($filter_lists as $row) {
                    $id = $row->id;
                    $name = $row->name;
                    $location_id = $row->location_id;
                    $address = $row->address;
                    $phone = $row->phone;
                    $experience = $row->experience;
                    $certifications = $row->certifications;
                    $availability = $row->availability;
                    $speciality = $row->speciality;
                    $profile_image = $row->profile_image;
                    $training_image = $row->training_image;
                    $achievement = $row->achievement;
                $trainer_location=$this->trainer_model->get_trainer_location($location_id);
                    foreach($trainer_location as $row) {
                       $location= $row->location;
                    }
                $trainer_sports=$this->trainer_model->get_trainer_sports($id);
                    $sports=[];
                    foreach($trainer_sports as $row) {
                       $sports_id= $row->id;
                       $sports_name= $row->sports;
                       $sports_image= $row->image;
                            $sports[]=array(
                                    'id'=>$sports_id,
                                    'sports'=>$sports_name,
                                    'image'=>$sports_image,
                            );
                    }
                $trainer_areas=$this->trainer_model->get_trainer_areas($id);
                    $area=[];
                    foreach($trainer_areas as $row) {
                       $area_id= $row->id;
                       $area_name= $row->area;
                            $area[]=array(
                                    'id'=>$area_id,
                                    'area'=>$area_name,
                            );
                    }
                $trainer_programs=$this->trainer_model->get_trainer_programs($id);
                    $trainer_program=[];
                    foreach($trainer_programs as $row) {
                        $trainer_program[]=array(
                                'id'=>(int)$row->id,
                                'program_name'=>$row->name,
                                'trainer_id'=>(int)$row->trainers_id,
                                'trainer_name'=>$row->trainer_name,
                                'venue_name'=>$row->venue_name,
                                'latitude'=>$row->lat,
                                'longitude'=>$row->lon,
                                'fees'=>$row->fees,
                                'start_time'=>$row->start_time,
                                'end_time'=>$row->end_time,
                                'start_date'=>$row->start_date,
                                'end_date'=>$row->end_date,
                                'description'=>$row->description,
                        );
                    }
                $trainer_followers=$this->trainer_model->get_trainer_followers($id);
                    foreach($trainer_followers as $row) {
                        $follower_count=$row->count;
                        }
                $following_status=$this->trainer_model->get_following_status($id,$user_id);
                    if(!empty($following_status))
                    {
                        $follow_status=1;
                    }else{
                        $follow_status=0;
                    }
                $testimonial_trainers=$this->trainer_model->get_testimonial_trainers($id);
                    $testimonial_trainer=[];
                    foreach($testimonial_trainers as $row) {

                            $testimonial_trainer[]=array(
                                    'id'=>(int)$row->id,
                                    'name'=>$row->name,
                                    'testimonial'=>$row->testimonial,
                            );
                    }

                    $filter_list[]=array(
                            'id'=>(int)$id,
                            'name'=>$name,
                            'address'=>$address,
                            'phone'=>$phone,
                            'experience'=>$experience,
                            'certifications'=>$certifications,
                            'availability'=>$availability,
                            'speciality'=>$speciality,
                            'profile_image'=>$profile_image,
                            'training_image'=>$training_image,
                            'achievement'=>$achievement,
                            'location'=>$location,
                            'location_id'=>(int)$location_id,
                            'total_followers'=>(int)$follower_count,
                            'follow_status'=>(int)$follow_status,
                            'sports'=>$sports,
                            'area'=>$area,
                            'programs'=>$trainer_program,
                            'testimonial'=>$testimonial_trainer,
                    );
                 }
///////////////////////////////////////////////// sports and area based filter list of trainers end ////////////////////////////////////////////////
///////////////////////////////////////////////// other trainers list not included in filter list start ////////////////////////////////////////////////

        $unfilter_lists=$this->trainer_model->get_trainer_unfilter($user_id,$location_ids);
                $unfilter_list=[];
                foreach($unfilter_lists as $row) {
                    $id = $row->id;
                    $name = $row->name;
                    $location_id = $row->location_id;
                    $address = $row->address;
                    $phone = $row->phone;
                    $experience = $row->experience;
                    $certifications = $row->certifications;
                    $availability = $row->availability;
                    $speciality = $row->speciality;
                    $profile_image = $row->profile_image;
                    $training_image = $row->training_image;
                    $achievement = $row->achievement;
                $trainer_location_un=$this->trainer_model->get_trainer_location($location_id);
                    foreach($trainer_location_un as $row) {
                       $location= $row->location;
                    }
                $trainer_sports_un=$this->trainer_model->get_trainer_sports($id);
                    $sports=[];
                    foreach($trainer_sports_un as $row) {
                       $sports_id= $row->id;
                       $sports_name= $row->sports;
                       $sports_image= $row->image;
                            $sports[]=array(
                                    'id'=>$sports_id,
                                    'sports'=>$sports_name,
                                    'image'=>$sports_image,
                            );
                    }
                $trainer_areas_un=$this->trainer_model->get_trainer_areas($id);
                    $area=[];
                    foreach($trainer_areas_un as $row) {
                       $area_id= $row->id;
                       $area_name= $row->area;
                            $area[]=array(
                                    'id'=>$area_id,
                                    'area'=>$area_name,
                            );
                    }
                $other_programs=$this->trainer_model->get_trainer_programs($id);
                    $other_program=[];
                    foreach($other_programs as $row) {
                        $other_program[]=array(
                                'id'=>(int)$row->id,
                                'program_name'=>$row->name,
                                'trainer_id'=>(int)$row->trainers_id,
                                'trainer_name'=>$row->trainer_name,
                                'venue_name'=>$row->venue_name,
                                'latitude'=>$row->lat,
                                'longitude'=>$row->lon,
                                'fees'=>$row->fees,
                                'start_time'=>$row->start_time,
                                'end_time'=>$row->end_time,
                                'start_date'=>$row->start_date,
                                'end_date'=>$row->end_date,
                                'description'=>$row->description,
                        );
                    }
                $trainer_following=$this->trainer_model->get_trainer_followers($id);
                    foreach($trainer_following as $row) {
                        $follower_counts=$row->count;
                        }
                $following_statuses=$this->trainer_model->get_following_status($id,$user_id);
                    if(!empty($following_statuses))
                    {
                        $follow_statuses=1;
                    }else{
                        $follow_statuses=0;
                    }
                $testimonials=$this->trainer_model->get_testimonial_trainers($id);
                    $testimonial=[];
                    foreach($testimonials as $row) {

                            $testimonial[]=array(
                                    'id'=>(int)$row->id,
                                    'name'=>$row->name,
                                    'testimonial'=>$row->testimonial,
                            );
                    }

                    $unfilter_list[]=array(
                            'id'=>(int)$id,
                            'name'=>$name,
                            'address'=>$address,
                            'phone'=>$phone,
                            'experience'=>$experience,
                            'certifications'=>$certifications,
                            'availability'=>$availability,
                            'speciality'=>$speciality,
                            'profile_image'=>$profile_image,
                            'training_image'=>$training_image,
                            'achievement'=>$achievement,
                            'location'=>$location,
                            'location_id'=>(int)$location_id,
                            'total_followers'=>(int)$follower_counts,
                            'follow_status'=>(int)$follow_statuses,
                            'sports'=>$sports,
                            'area'=>$area,
                            'programs'=>$other_program,
                            'testimonial'=>$testimonial,
                    );
                 }

///////////////////////////////////////////////// other trainers list not included in filter list end ////////////////////////////////////////////////
////////////////////////////////////////////////////// shop advertisement list start ////////////////////////////////////////////////////////////////

        $shop_adv_lists=$this->trainer_model->get_shop_adv_lists($location_ids);
            $shop_adv_list=[];
                foreach($shop_adv_lists as $row) {
                        $shop_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// shop advertisement list end //////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// trainer advertisement list start ////////////////////////////////////////////////////////////////

        $trainer_adv_lists=$this->trainer_model->get_trainer_adv_lists($location_ids);
            $trainer_adv_list=[];
                foreach($trainer_adv_lists as $row) {
                        $trainer_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// trainer advertisement list end //////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// venue advertisement list start ////////////////////////////////////////////////////////////////

        $venue_adv_lists=$this->trainer_model->get_venue_adv_lists($location_ids);
            $venue_adv_list=[];
                foreach($venue_adv_lists as $row) {
                        $venue_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// venue advertisement list end //////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// programs list start ////////////////////////////////////////////////////////////////

        $programs_lists=$this->trainer_model->get_programs_lists($location_ids);
            $programs_list=[];
                foreach($programs_lists as $row) {                   
                        $programs_list[]=array(
                                'id'=>(int)$row->id,
                                'program_name'=>$row->name,
                                'trainer_id'=>(int)$row->trainers_id,
                                'trainer_name'=>$row->trainer_name,
                                'venue_name'=>$row->venue_name,
                                'latitude'=>$row->lat,
                                'longitude'=>$row->lon,
                                'fees'=>$row->fees,
                                'start_time'=>$row->start_time,
                                'end_time'=>$row->end_time,
                                'start_date'=>$row->start_date,
                                'end_date'=>$row->end_date,
                                'description'=>$row->description,
                        );
                } 
///////////////////////////////////////////////////// programs list end //////////////////////////////////////////////////////////////////
                    $trainer=array(
                            'trainers'=>$filter_list,
                            'other_trainers'=>$unfilter_list,
                            'shop_advertisement'=>[],
                            'trainer_advertisement'=>$trainer_adv_list,
                            'venue_advertisement'=>$venue_adv_list,
                            'programs'=>[],
                    );
         if(!empty($trainer))
         {
                    $result=array(
                            'errorCode'=>1,
                            'data'=>$trainer,
                            'message'=>"success"
                            );
                    return $this->response($result,200);
         }else{
                    $result=array(
                            'errorCode'=>0,
                            'data'=>0,
                            'message'=>"empty"
                            );
                    return $this->response($result,200);
         }
    }


/////////////////////////// trainer list details end /////////////////////////////////////////
/////////////////////////// trainer following details start /////////////////////////////////////////

    public function following_post()
    {
         $user_id=$this->input->post('user_id');
         $trainer_id=$this->input->post('trainer_id');
         $status=$this->input->post('status');
         if($status==1)
         {
            $following_status=$this->trainer_model->get_following_status($user_id,$trainer_id);
            if(empty($following_status)) {
                    $data = array(
                            'trainers_id' =>$trainer_id,
                            'followers_id' =>$user_id,
                    );
                    $add=$this->trainer_model->add_data('trainers_followers',$data);
                    $trainer_followers=$this->trainer_model->get_trainer_followers($trainer_id);
                    foreach($trainer_followers as $row) {
                        $follower_count=$row->count;
                        }
                        if(!empty($add)){
                            $result=array(
                                    'errorCode'=>1,
                                    'data'=>(int)$follower_count,
                                    'message'=>"success"
                                    );
                            return $this->response($result,200);
                        }else{
                            $result=array(
                                    'errorCode'=>0,
                                    'data'=>(int)$follower_count,
                                    'message'=>"fail"
                                    );
                            return $this->response($result,200);     
                        }
            }else{
                    $result=array(
                            'errorCode'=>0,
                            'data'=>(int)$follower_count,
                            'message'=>"Al ready rated"
                            );
                    return $this->response($result,200);  
            }
         }else{
            $following_status=$this->trainer_model->get_following_status($trainer_id,$user_id);
                foreach($following_status as $row) {
                        $follow_id=$row->id;
                        $this->trainer_model->delete_following($follow_id);
                        }
                    $trainer_followers=$this->trainer_model->get_trainer_followers($trainer_id);
                    foreach($trainer_followers as $row) {
                        $follower_count=$row->count;
                        }
            if(!empty($following_status)){
                            $result=array(
                                    'errorCode'=>1,
                                    'data'=>(int)$follower_count,
                                    'message'=>"success"
                                    );
                            return $this->response($result,200);
            }else{
                            $result=array(
                                    'errorCode'=>1,
                                    'data'=>(int)$follower_count,
                                    'message'=>"success"
                                    );
                            return $this->response($result,200);   
            }
         }
    }
/////////////////////////// trainer following details end /////////////////////////////////////////
////////////////////////// trainer enquiry on call start ////////////////////////////
public function trainer_enquiry_post(){
         
        $user_id=$this->input->post('user_id');
        $phone=$this->input->post('phone');
        $trainer_id=$this->input->post('trainer_id');
        $user=$this->trainer_model->get_user_details($user_id);
        if(!empty($user)){

            foreach($user as $row) {
              $user_name = $row->name;
              $user_phone = $row->phone_no;
          }
              if( $user_name=="upUPUP User"){

                $message="An upUPUP user has an enquiry about your training. \nDetails: \nmob:".$user_phone." \nupUPUP- Let's Play again";

              }else{

                $message="An upUPUP user has an enquiry about your training. \nDetails: \n".$user_name.", \nmob:".$user_phone." \nupUPUP- Let's Play again";

              }
            $this->common->sms(str_replace(' ', '', $phone),urlencode($message)); 
                    $result=array(
                            'errorCode'=>1,
                            'data'=>0,
                            'message'=>"success"
                            );
                    return $this->response($result,200);
        }else{
                    $result=array(
                            'errorCode'=>0,
                            'data'=>0,
                            'message'=>"fail"
                            );
                    return $this->response($result,200);  
        }
        
        
    }
////////////////////////// trainer enquiry on call end ////////////////////////////
/////////////////////////// programs list start /////////////////////////////////////////

    public function program_list_post()
    {
         $user_id=$this->input->post('user_id');

////////////////////////////////////////////////////// programs list start ////////////////////////////////////////////////////////////////

        $programs_lists=$this->trainer_model->get_programs_lists();
            $programs_list=[];
                foreach($programs_lists as $row) {                   
                        $programs_list[]=array(
                                'id'=>(int)$row->id,
                                'program_name'=>$row->name,
                                'trainer_id'=>(int)$row->trainers_id,
                                'trainer_name'=>$row->trainer_name,
                                'venue_name'=>$row->venue_name,
                                'latitude'=>$row->lat,
                                'longitude'=>$row->lon,
                                'fees'=>$row->fees,
                                'start_time'=>$row->start_time,
                                'end_time'=>$row->end_time,
                                'start_date'=>$row->start_date,
                                'end_date'=>$row->end_date,
                                'description'=>$row->description,
                        );
                } 
///////////////////////////////////////////////////// programs list end //////////////////////////////////////////////////////////////////

         if(!empty($programs_list))
         {
                    $result=array(
                            'errorCode'=>1,
                            'data'=>[],
                            'message'=>"success"
                            );
                    return $this->response($result,200);
         }else{
                    $result=array(
                            'errorCode'=>0,
                            'data'=>0,
                            'message'=>"empty"
                            );
                    return $this->response($result,200);
         }
    }


/////////////////////////// programs list end /////////////////////////////////////////


}