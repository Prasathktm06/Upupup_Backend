<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


class Shop extends REST_Controller {

    function __construct()
    {

        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("api/shop_model");
        $this->load->library("notification");
        date_default_timezone_set("Asia/Kolkata");

    }


/////////////////////////// shop list start /////////////////////////////////////////

    public function shop_list_post()
    {
      $user_id=$this->input->post('user_id');
      $location_ids=$this->input->post('location_id');
//////////////////////////////// area and sports based filter start ////////////////////////////
      $filter_lists=$this->shop_model->get_shop_list($user_id,$location_ids);
            $filter_list=[];
                foreach($filter_lists as $row) {
                    $id = $row->id;
                    $name = $row->name;
                    $location_id = $row->location_id;
                    $area_id = $row->area_id;
                    $address = $row->address;
                    $timing = $row->timing;
                    $major_brands = $row->major_brands;
                    $brand_shop = $row->brand_shop;
                    $phone = $row->phone;
                    $lat = $row->lat;
                    $lon = $row->lon;
                    $image = $row->image;
                $shop_location=$this->shop_model->get_shop_location($location_id);
                    foreach($shop_location as $row) {
                       $location= $row->location;
                    }

                $shop_area=$this->shop_model->get_shop_area($area_id);
                    foreach($shop_area as $row) {
                       $area= $row->area;
                    }
                $shop_offers=$this->shop_model->get_shop_offers($id);
                    $off_amount=0;
                    foreach($shop_offers as $row) {
                           if($off_amount<$row->amount)
                           {
                                $off_amount=$off_amount+$row->amount;
                           }

                    }
                $shop_sports=$this->shop_model->get_shop_sports($id);
                    $sports=[];
                    foreach($shop_sports as $row) {
                            $sports[]=array(
                                    'id'=>$row->id,
                                    'sports'=>$row->sports,
                                    'image'=>$row->image,
                            );
                    }
                    $filter_list[]=array(
                            'id'=>(int)$id,
                            'name'=>$name,
                            'location_id'=>(int)$location_id,
                            'location'=>$location,
                            'area_id'=>(int)$area_id,
                            'area'=>$area,
                            'address'=>$address,
                            'timing'=>$timing,
                            'major_brands'=>$major_brands,
                            'brand_shop'=>$brand_shop,
                            'phone'=>$phone,
                            'lat'=>$lat,
                            'lon'=>$lon,
                            'image'=>$image,
                            'offer_amount'=>$off_amount,
                            'sports'=>$sports,
                    );
                }
//////////////////////////////// area and sports based filter end ////////////////////////////
//////////////////////////////// shop unfilter list start ////////////////////////////
      $unfilter_lists=$this->shop_model->get_shop_unfilter($user_id,$location_ids);
            $unfilter_list=[];
                foreach($unfilter_lists as $row) {
                    $id = $row->id;
                    $name = $row->name;
                    $location_id = $row->location_id;
                    $area_id = $row->area_id;
                    $address = $row->address;
                    $timing = $row->timing;
                    $major_brands = $row->major_brands;
                    $brand_shop = $row->brand_shop;
                    $phone = $row->phone;
                    $lat = $row->lat;
                    $lon = $row->lon;
                    $image = $row->image;
                $shop_location=$this->shop_model->get_shop_location($location_id);
                    foreach($shop_location as $row) {
                       $location= $row->location;
                    }

                $shop_area=$this->shop_model->get_shop_area($area_id);
                    foreach($shop_area as $row) {
                       $area= $row->area;
                    }
                $shop_offers=$this->shop_model->get_shop_offers($id);
                    $off_amount=0;
                    foreach($shop_offers as $row) {
                           if($off_amount<$row->amount)
                           {
                                $off_amount=$off_amount+$row->amount;
                           }

                    }
                $shop_sports=$this->shop_model->get_shop_sports($id);
                    $sports=[];
                    foreach($shop_sports as $row) {
                            $sports[]=array(
                                    'id'=>$row->id,
                                    'sports'=>$row->sports,
                                    'image'=>$row->image,
                            );
                    }
                    $unfilter_list[]=array(
                            'id'=>(int)$id,
                            'name'=>$name,
                            'location_id'=>(int)$location_id,
                            'location'=>$location,
                            'area_id'=>(int)$area_id,
                            'area'=>$area,
                            'address'=>$address,
                            'timing'=>$timing,
                            'major_brands'=>$major_brands,
                            'brand_shop'=>$brand_shop,
                            'phone'=>$phone,
                            'lat'=>$lat,
                            'lon'=>$lon,
                            'image'=>$image,
                            'offer_amount'=>$off_amount,
                            'sports'=>$sports,
                    );
                }
//////////////////////////////// shop unfilter list end ////////////////////////////
////////////////////////////////////////////////////// shop advertisement 1 list start ////////////////////////////////////////////////////////////////

        $shop_adv_lists=$this->shop_model->get_shop_adv_lists($location_ids);
            $shop_adv_list=[];
                foreach($shop_adv_lists as $row) {
                        $shop_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// shop advertisement 1 list end //////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// shops advertisement 2 list start ////////////////////////////////////////////////////////////////

        $shops_adv_lists=$this->shop_model->get_shops_adv_lists($location_ids);
            $shops_adv_list=[];
                foreach($shops_adv_lists as $row) {
                        $shops_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// shops advertisement 2 list end //////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// trainer advertisement list start ////////////////////////////////////////////////////////////////

        $trainer_adv_lists=$this->shop_model->get_trainer_adv_lists($location_ids);
            $trainer_adv_list=[];
                foreach($trainer_adv_lists as $row) {
                        $trainer_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// trainer advertisement list end //////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// venue advertisement list start ////////////////////////////////////////////////////////////////

        $venue_adv_lists=$this->shop_model->get_venue_adv_lists($location_ids);
            $venue_adv_list=[];
                foreach($venue_adv_lists as $row) {
                        $venue_adv_list[]=array(
                                'id'=>(int)$row->id,
                                'image'=>$row->image,
                        );
                } 
///////////////////////////////////////////////////// venue advertisement list end //////////////////////////////////////////////////////////////////
                    $shop=array(
                            'shops'=>$filter_list,
                            'other_shops'=>$unfilter_list,
                            'shop_advertisement'=>[],
                            'trainer_advertisement'=>$shop_adv_list,
                            'venue_advertisement'=>$shops_adv_list,
                    );
         if(!empty($shop))
         {
                    $result=array(
                            'errorCode'=>1,
                            'data'=>$shop,
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
/////////////////////////// shop list end /////////////////////////////////////////
////////////////////////// shop enquiry on call start ////////////////////////////
public function shop_enquiry_post(){
         
        $user_id=$this->input->post('user_id');
        $phone=$this->input->post('phone');
        $shop_id=$this->input->post('shop_id');
        $user=$this->shop_model->get_user_details($user_id);
        if(!empty($user)){

            foreach($user as $row) {
              $user_name = $row->name;
              $user_phone = $row->phone_no;
          }
              if( $user_name=="upUPUP User"){

                $message="An upUPUP user has an enquiry about your shop. \nDetails: \nmob:".$user_phone." \nupUPUP- Let's Play again";

              }else{

                $message="An upUPUP user has an enquiry about your shop. \nDetails: \n".$user_name.", \nmob:".$user_phone." \nupUPUP- Let's Play again";

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
////////////////////////// shop enquiry on call start ////////////////////////////
}