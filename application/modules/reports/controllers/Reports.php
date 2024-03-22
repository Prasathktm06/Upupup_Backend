<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Reports extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url'));
        $this->load->model('reports_model');
        $this->load->library('excel');
        if(isset($_SESSION['signed_in'])==TRUE ){

        }else{
            redirect('acl/user/sign_in');
        }

    }
    ////////////////////////////////////App Users List//////////////////////////////////////
    public function users() {
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $city           = $this->input->post('city');
                $area           = $this->input->post('area');
                //echo "<pre>";print_r($this->input->post());exit();

                $data['list']       = $this->reports_model->get_users_list($city,$area);
                //echo "<pre>";print_r($data);exit();
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['area']=implode(' , ', $this->reports_model->get_user_areas($value['id'],$area));
                    $data['list'][$key]['sports']=implode(' , ', $this->reports_model->get_user_sports($value['id']));
                    $data['list'][$key]['user_channel']=implode(' , ', $this->reports_model->get_user_channel($value['id']));
                }
            }else{
                $data['list']       = $this->reports_model->get_users_list();
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['area']=implode(' , ', $this->reports_model->get_user_areas($value['id']));
                    $data['list'][$key]['sports']=implode(' , ', $this->reports_model->get_user_sports($value['id']));
                    $data['list'][$key]['user_channel']=implode(' , ', $this->reports_model->get_user_channel($value['id']));
                }
            }
            $data['heading']    ="App Users";
            $data['location']    = $this->reports_model->location_list();
            //echo "<pre>";print_r($data);exit();
            $this->load->template('users',$data);
        }
    }
    //////////////////////////////Users Download//////////////////////////////////////////
    public function users_excel()
    {
        $list=$_SESSION['users'];
        foreach ($list as $key => $value) {
            $users[$key]=array($value['location'],$value['area'],$value['name'],$value['phone_no'],$value['email']);
             if ($value['user_channel']==2) {
                $user_channel = "Vendor App";
            }else{
                $user_channel = "User App";
            };
        array_push($users[$key],$user_channel);
        }
        $head = array('0' => 'City',
                      '1' => 'Areas',
                      '2' => 'User Name',
                      '3' => 'Phone Number',
                      '4' => 'Email ID', 
                      '5' => 'User Channel',);
        array_unshift($users , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');

        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:E1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        $this->excel->getActiveSheet()->fromArray($users);

        $filename='users.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    ////////////////////////////////////Booking List////////////////////////////////////////////
    public function upupup_booking($venue="") {
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {

                //echo "<pre>";print_r($this->input->post());exit();
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $venue  = $this->input->post('venue');
                $sports = $this->input->post('sports');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                //echo "<pre>";print_r($date);
                //echo "<pre>";print_r($enddate);
                /*$time = $this->input->post('time');*/


                $data['list']       = $this->reports_model->get_booking_list($city,$area,$venue,$sports,$date,$enddate);
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);
                    $data['list'][$key]['service_charge'] = $this->reports_model->get_booked_service_charge($value['booking_id']);
                }
                foreach ($data['list'] as $key3 => $value3) {
                    $data['list'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                }
                foreach ($data['list'] as $key2 => $value2) {
                    $data['list'][$key2]['duration']    =count($value2['booked_slots']);
                    $data['list'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                    $data['list'][$key2]['charges']  =implode("\n",$value2['service_charge']);
                }
            }else{
                $data['list']       = $this->reports_model->get_booking_list();

                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);
                    $data['list'][$key]['service_charge'] = $this->reports_model->get_booked_service_charge($value['booking_id']);

                }//echo "<pre>";print_r($data);exit();
                foreach ($data['list'] as $key3 => $value3) {
                    $data['list'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                }
                foreach ($data['list'] as $key2 => $value2) {
                    $data['list'][$key2]['duration']    =count($value2['booked_slots']);
                    $data['list'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                    $data['list'][$key2]['charges']  =implode("\n",$value2['service_charge']);
                }
                //echo "<pre>";print_r($data);exit();
            }

            $data['heading']     ="Bookings Through User App";
            $data['location']    = $this->reports_model->location_list();
            //$data['venue']       = $this->reports_model->get_venues();
            // $data['sports']      = $this->reports_model->get_sports();
            //echo "<pre>";print_r($data);exit(); 
            $this->load->template('upupupbooking',$data);
        }
    }
    ////////////////////////////Booking Download//////////////////////////////////////////////
    public function upupup_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['list'];
        foreach ($list as $key => $value) {
            $booking[$key]=array($value['booking_id'],$value['name'],$value['phone_no'],$value['email'],$value['location'],$value['area'],$value['venue'],$value['court'],$value['total_capacity'],$value['sports'],date( ' d M Y ',strtotime($value['time'])),date( ' h:i:s ',strtotime($value['time'])),date( ' d M Y ',strtotime($value['date'])),$value['time_slots'],$value['duration'].' Hours',$value['court_cost'],$value['court_cost']*$value['total_capacity'],($value['court_cost']*$value['total_capacity'])-($value['offer_value']+$value['coupon_value']));

            if ($value['payment_mode']==1) {
                $payment_mode = "Pay Through App";
            }else if ($value['payment_mode']==0) {
                $payment_mode = "Pay At Venue";
            }else if ($value['payment_mode']==2) {
                $payment_mode = "Failed";
            };
            array_push($booking[$key],$payment_mode);
            array_push($booking[$key],$value['cost'],$value['transaction_id']);
            if ($value['charges']!=NULL) {
                $service_charge=$value['charges'];
            }else{
                $service_charge="0";
            };
            array_push($booking[$key],$service_charge);
            if ($value['payment_mode']==0) {
                $pay_venue=$value['bal'];
            }else{
                $pay_venue="";
            };
            array_push($booking[$key],$pay_venue,$value['offer_value']);
            if ($value['percentage']=="Yes") {
                $coupon_value=$value['coupon_value'].'%';
            }else if ($value['percentage']=="No") {
                $coupon_value=$value['currency'].' '.$value['coupon_value'];
            }else{
                $coupon_value=array();
            };
            array_push($booking[$key],$coupon_value);
            array_push($booking[$key],$value['description']);
        }
        $head = array('0' => 'Booking ID',
        '1' => 'User Name',
        '2' => 'User Phone No.',
        '3' => 'User Email ID',
        '4' => 'City',
        '5' => 'Area',
        '6' => 'Venue Name',
        '7' => 'Court Name',
        '8' => 'Capacity',
        '9' => 'Sports Playing',
        '10' => 'Date of Booking',
        '11' => 'Time of Booking',
        '12' => 'Date of Playing',
        '13' => 'Time of Playing',
        '14' => 'Hours of Playing',
        '15' => 'Per Slot Price',
        '16' => 'Total Amount',
        '17' => 'Payable Amount',
        '18' => 'Mode of Payment',
        '19' => 'Pay.Gat. Paid Amt.',
        '20' => 'Pay.Gat. Transaction ID',
        '21' => 'Service Charge',
        '22' => 'Pay At Venue Amount',
        '23' => 'Offer Amount',
        '24' => 'Coupon Amount',
        '25' => 'Coupon Name',

        );
        array_unshift($booking , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:Z1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:Z1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($booking);

        $filename='upupupbooking.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
                      ////////////////////////////////////Vendors Booking List////////////////////////////////////////////
    public function vendors_booking($venue="") {

            if ($this->input->post('submit')) {

                //echo "<pre>";print_r($this->input->post());exit();
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $venue  = $this->input->post('venue');
                $sports = $this->input->post('sports');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                


                $data['vendor']       = $this->reports_model->get_vendorbooking_list($city,$area,$venue,$sports,$date,$enddate);
                foreach ($data['vendor'] as $key => $value) {
                    $data['vendor'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);
                }
                foreach ($data['vendor'] as $key3 => $value3) {
                    $data['vendor'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                }
                foreach ($data['vendor'] as $key2 => $value2) {
                    $data['vendor'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                    $data['vendor'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                }
            }else{
                $data['vendor']       = $this->reports_model->get_vendorbooking_list();

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
                //echo "<pre>";print_r($data['vendor']);exit();
            }

            $data['heading']     ="Bookings Through Vendors App";
            $data['location']    = $this->reports_model->location_list();
            //$data['venue']       = $this->reports_model->get_venues();
            // $data['sports']      = $this->reports_model->get_sports();
        //echo "<pre>";print_r($data);exit(); 
            $this->load->template('vendorsbooking',$data);
       
    }
       //////////////////////////// Vendor Booking Download//////////////////////////////////////////////
    public function vendors_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['vendor'];
        foreach ($list as $key => $value) {
            $booking[$key]=array($value['booking_id'],$value['name'],$value['phone_no'],$value['email'],$value['location'],$value['area'],$value['venue'],$value['court'],$value['booked_capacity'],$value['sports'],date( ' d M Y ',strtotime($value['time'])),date( ' h:i:s ',strtotime($value['time'])),date( ' d M Y ',strtotime($value['date'])),$value['time_slots'],$value['duration']/60 .' Hours',$value['court_cost'],$value['court_cost']*$value['booked_capacity']);

            if ($value['payment_mode']==1) {
                $payment_mode = "Pay At Venue";
            }else if ($value['payment_mode']==0) {
                $payment_mode = "Pay At Venue";
            }else if ($value['payment_mode']==2) {
                $payment_mode = "Failed";
            };
            array_push($booking[$key],$payment_mode);
            
            array_push($booking[$key],$value['cost']);
            if ($value['offer_amount']!=0) {
                $offer_value=$value['offer_amount']*$value['booked_capacity'];
            }else if ($value['offer_percentage']!=0) {
                $offer_value=(($value['court_cost']*$value['booked_capacity'])*$value['offer_percentage'])/100;
            }else  {
                $offer_value = "";
            };
            array_push($booking[$key],$offer_value);
            array_push($booking[$key],$value['role_name']);
            array_push($booking[$key],$value['mgr_phone']);
        }
        $head = array('0' => 'Booking ID',
        '1' => 'User Name',
        '2' => 'User Phone No.',
        '3' => 'User Email ID',
        '4' => 'City',
        '5' => 'Area',
        '6' => 'Venue Name',
        '7' => 'Court Name',
        '8' => 'Capacity',
        '9' => 'Sports Playing',
        '10' => 'Date of Booking',
        '11' => 'Time of Booking',
        '12' => 'Date of Playing',
        '13' => 'Time of Playing',
        '14' => 'Hours of Playing',
        '15' => 'Per Slot Price',
        '16' => 'Total Amount',
        '17' => 'Mode of Payment',
        '18' => 'Pay At Venue Amount',
        '19' => 'Offer Amount',
        '20' => 'Booked By',
        '21' => 'Manager Number',
        );
        array_unshift($booking , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($booking);

        $filename='vendor_booking.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    ////////////////////////////////////Cancel Booking List////////////////////////////////////////////
    public function cancel_booking($venue="") {

            if ($this->input->post('submit')) {

                //echo "<pre>";print_r($this->input->post());exit();
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $venue  = $this->input->post('venue');
                $sports = $this->input->post('sports');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                


                $data['books']       = $this->reports_model->get_cancelbooking_list($city,$area,$venue,$sports,$date,$enddate);
                foreach ($data['books'] as $key => $value) {
                    $data['vendor'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);
                }
                foreach ($data['books'] as $key3 => $value3) {
                    $data['books'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                }
                foreach ($data['books'] as $key2 => $value2) {
                    $data['books'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                    $data['books'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                }
                //echo "<pre>";print_r($data['books']);exit();
            }else{
                $data['books']       = $this->reports_model->get_cancelbooking_list();

                foreach ($data['books'] as $key => $value) {
                    $data['books'][$key]['booked_slots'] = $this->reports_model->get_booked_slots($value['booking_id']);

                }//echo "<pre>";print_r($data);exit();
                foreach ($data['books'] as $key3 => $value3) {
                    $data['books'][$key3]['total_capacity']  = $this->reports_model->get_booked_capacity($value3['booking_id'])->book_capacity;

                }
                foreach ($data['books'] as $key2 => $value2) {
                    $data['books'][$key2]['duration']    =count($value2['booked_slots'])*$value2['intervel'];
                    $data['books'][$key2]['time_slots']  =implode("\n",$value2['booked_slots']);
                }
                //echo "<pre>";print_r($data['books']);exit();
            }

            $data['heading']     ="Cancel Booking By Vendors";
            $data['location']    = $this->reports_model->location_list();
            //$data['venue']       = $this->reports_model->get_venues();
            // $data['sports']      = $this->reports_model->get_sports();
            //echo "<pre>";print_r($data);exit(); 
            $this->load->template('cancel_booking',$data);
       
    }

        //////////////////////////// Cancel Booking Download//////////////////////////////////////////////
    public function cancelbook_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['books'];
        foreach ($list as $key => $value) {
            $booking[$key]=array($value['booking_id'],$value['name'],$value['phone_no'],$value['email'],$value['location'],$value['area'],$value['venue'],$value['court'],$value['booked_capacity'],$value['sports'],date( ' d M Y ',strtotime($value['time'])),date( ' h:i:s ',strtotime($value['time'])),date( ' d M Y ',strtotime($value['date'])),$value['time_slots'],$value['duration']/60 .' Hours',$value['court_cost'],$value['court_cost']*$value['booked_capacity']);
 
            if ($value['payment_mode']==3) {
                $payment_mode = "Cancel";
            }else if ($value['payment_mode']==0) {
                $payment_mode = "Pay At Venue";
            }else if ($value['payment_mode']==2) {
                $payment_mode = "Failed";
            };
            array_push($booking[$key],$payment_mode);
            
           array_push($booking[$key],$value['cost']);
            if ($value['offer_amount']!=0) {
                $offer_value=$value['offer_amount']*$value['booked_capacity'];
            }else if ($value['offer_percentage']!=0) {
                $offer_value=(($value['court_cost']*$value['booked_capacity'])*$value['offer_percentage'])/100;
            }else  {
                $offer_value = "";
            };
            array_push($booking[$key],$offer_value);

            array_push($booking[$key],$value['role_name']);
            array_push($booking[$key],$value['cm_phone']);
            array_push($booking[$key],date( ' d M Y ',strtotime($value['cancel_date'])));
            array_push($booking[$key],date( ' h:i:s A ',strtotime($value['cancel_date'])));
        }
        $head = array('0' => 'Booking ID',
        '1' => 'User Name',
        '2' => 'User Phone No.',
        '3' => 'User Email ID',
        '4' => 'City',
        '5' => 'Area',
        '6' => 'Venue Name',
        '7' => 'Court Name',
        '8' => 'Capacity',
        '9' => 'Sports Playing',
        '10' => 'Date of Booking',
        '11' => 'Time of Booking',
        '12' => 'Date of Playing',
        '13' => 'Time of Playing',
        '14' => 'Hours of Playing',
        '15' => 'Per Slot Price',
        '16' => 'Total Amount',
        '17' => 'Mode of Payment',
        '18' => 'Pay At Venue Amount',
        '19' => 'Offer Amount',
        '20' => 'Cancel By',
        '21' => 'Manger Number',
        '22' => 'Cancel Date',
        '23' => 'Cancel Time',
        );
        array_unshift($booking , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($booking);

        $filename='cancel_booking.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    ////////////////////////////Court////////////////////////////////////////////////////
    public function court_list(){
        $venue  = $this->input->post('venue');
        $data['court']      =$this->reports_model->get_court($venue);
        $data['sports']     =$this->reports_model->get_sports($venue);
        //echo "<pre>";print_r($data);exit();
        if ($data) {
            echo json_encode($data);
        }
    }
    ////////////////////////////////////Back end Users List//////////////////////////////////////
    public function backend_users() {
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $name               = $this->input->post('name');
                $email              = $this->input->post('email');
                $role               = $this->input->post('role');

                $data['list']       = $this->reports_model->get_backend_users_list($name,$email,$role);
                foreach ($data['list'] as $key => $value) {
                    
                   $data['list'][$key]['court']=implode(',', $this->reports_model->get_backend_users_court($value['user_id']));
                }
            }else{
                $data['list']       = $this->reports_model->get_backend_users_list();
                foreach ($data['list'] as $key => $value) {
                    
                   $data['list'][$key]['court']=implode(',', $this->reports_model->get_backend_users_court($value['user_id']));
                }
            }
            $data['heading']    ="Backend Users";
            $data['users']      = $this->reports_model->get_backend_users_data();
            $data['users_email']      = $this->reports_model->get_backend_users_email();
            //echo "<pre>";print_r($data['list']);exit();
            $data['roles']      = $this->reports_model->get_roles();
            $this->load->template('admin_users',$data);
        }
    }
    /////////////////////////////////////////Back end user Excel//////////////////////////////
    public function backend_users_excel()
    {
        $list=$_SESSION['backend_users'];
        foreach ($list as $key => $value) {
            $users[$key]=array($value['name'],$value['phone'],$value['email'],$value['role_name'],$value['venue'],$value['court']);
        }
        $head = array('0' => 'User Name',
                      '1' => 'Phone No.',
                      '2' => 'Email ID',
                      '3' => 'Role',
                      '4' => 'Venue Managed',
                      '5' => 'court',);
        array_unshift($users , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:F1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        $this->excel->getActiveSheet()->fromArray($users);

        $filename='backend_users.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    /////////////////////////////Report Sending//////////////////////////////////////////////////
    public function report_send(){
        $this->load->library('email');
        $this->email->initialize(array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.sendgrid.net',
            'smtp_user' => 'nikhildas',
            'smtp_pass' => 'appzoc-1',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'crlf'      => "\r\n",
            'newline'   => "\r\n",
        ));

        $venue_managers=$this->reports_model->get_venue_managers();
        foreach ($venue_managers as $key => $venue) {
            $files  = glob('report_send/*'); // get all file names
            $dir    = 'report_send';
            //echo "<pre>";print_r($files);exit();
            foreach($files as $file){ // iterate files
                if(is_file($file))
                    unlink($dir.'/'.$file);
                    //unlink($file); // delete file
            };

            $date           = date("Y-m-d");
            $booking        = $this->reports_model->get_booking_list("","","","","","",$venue['venue_id'],"","","",$date);
            $this->load->library('excel');
            foreach ($booking as $key => $value) {
                $users[$key]=array($value['venue'],$value['venue_phone'],$value['court'],$value['name'],$value['phone_no'],$value['cost'],date( ' d M Y ',strtotime($value['date'])),$value['payment_id'],date( ' d M Y h:i A',strtotime($value['time'])));
            }
            //echo "<pre>";print_r($users);exit();
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'Venue');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Venue Phone');
            $this->excel->getActiveSheet()->setCellValue('C1', 'Court');
            $this->excel->getActiveSheet()->setCellValue('D1', 'User');
            $this->excel->getActiveSheet()->setCellValue('E1', 'User Phone');
            $this->excel->getActiveSheet()->setCellValue('F1', 'Amount Paid');
            $this->excel->getActiveSheet()->setCellValue('G1', 'Booked Date');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Payment Mode');
            $this->excel->getActiveSheet()->setCellValue('I1', 'Payment Id');
            $this->excel->getActiveSheet()->setCellValue('J1', 'Date Of booking');

            // read data to active sheet
            $this->excel->getActiveSheet()->fromArray($users);

            $filename='backend_users.xls'; //save our workbook as this file name

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

            //force user to download the Excel file without writing it to server's HD
            //$objWriter->save('php://output');
            $objWriter->save(str_replace(__FILE__,$_SERVER['DOCUMENT_ROOT'] .'/upupup/report_send/report.xlsx',__FILE__));
            $report = glob('report_send/*');
            //echo "<pre>";print_r($report);exit();
            //$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $user       =$venue['name'];
            $email_id   =$venue['email'];
            $subject  = "UpUpUp Report";
            $message  = "Dear ".$user.",<br> ";
            $message .= "Please Find The Attachement.<br>";
            $message .="\r\n\r\n";
            $message .="Regards,";
            $message .="\r\n";
            $message .="UpUpUp.";
            //$message .= $content."\r\n\r\n";
            $this->email->attach($report[0]);
            $this->email->from('amritha@in.appzoc.com', 'UpUpUp');
            $this->email->to('amritha@in.appzoc.com', 'UpUpUp');
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();

        }//echo "<pre>";print_r($venue);exit();
    }
    //////////////////////////////////////Hosting List/////////////////////////////////////////
    public function hosting_list(){
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                $time   = $this->input->post('time');
                $data['list']       = $this->reports_model->get_hosting_list($city,$area,$date,$enddate,$time);
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['co_players']=implode(',', $this->reports_model->get_co_players_list($value['id']));
                }
            }else{
                $data['list']       = $this->reports_model->get_hosting_list();
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['co_players']=implode(',', $this->reports_model->get_co_players_list($value['id']));
                }
            }
            $data['heading']     ="Hosting";
            $data['location']    = $this->reports_model->location_list();
            $this->load->template('hosting',$data);
        }
    }
    ///////////////////////////////////////Hosting Download////////////////////////////////////////
    public function hosting_excel()
    {
        $list=$_SESSION['hosting'];
        foreach ($list as $key => $value) {
            $hosting[$key]=array($value['name'],$value['phone_no'],$value['email'],$value['location'],$value['area'],$value['sports'],(date( ' d M Y ',strtotime($value['date']))),$value['time'],$value['co_players']);
        }
        $head = array('0' => 'User Name',
                      '1' => 'User Phone No.',
                      '2' => 'User Email ID',
                      '3' => 'City',
                      '4' => 'Area',
                      '5' => 'Sports',
                      '6' => 'Planned to Play Date',
                      '7' => 'Time Zone',
                      '8' => 'Co-Players', );
        array_unshift($hosting , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:I1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($hosting);

        $filename='hosting.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    ///////////////////////////////////Venue List///////////////////////////////////////////////////
    public function venue_list(){
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $venue  = $this->input->post('venue');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                
                $data['list']       = $this->reports_model->get_venue_list($city,$area,$venue,$date,$enddate);
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['court']=implode(',', $this->reports_model->get_court_list($value['venue_id']));
                    $data['list'][$key]['sports']=implode(',', $this->reports_model->get_sports_list($value['venue_id']));
                    $data['list'][$key]['court_list']=$this->reports_model->get_court($value['venue_id']);
                }

                foreach ($data['list'] as $key2 => $value2) {
                    if ($value2['court_list']) {
                        foreach ($value2['court_list'] as $key3 => $value3) {
                            $data['list'][$key2]['court_list'][$key3]['booked_slots']=$this->reports_model->court_time_booked($value3['id']);
                            $data['list'][$key2]['court_list'][$key3]['booked_slots_hours']=(count($this->reports_model->court_time_booked($value3['id']))*$value3['intervel'])/60;
                            //$data['list'][$key2]['total_hours_booked']=(count($this->reports_model->court_time_booked($value3['id']))*$value3['intervel'])/60;
                        }
                    }else{
                        $data['list'][$key2]['court_list'][0]['booked_slots_hours']="";
                    }
                }
                foreach ($data['list'] as $key4 => $value4) {
                    $data['list'][$key4]['hour_list']=array();
                    foreach ($value4['court_list'] as $key5 => $value5) {
                        array_push($data['list'][$key4]['hour_list'], $value5['booked_slots_hours']);
                    }
                    $data['list'][$key4]['total_hours']=round(array_sum($data['list'][$key4]['hour_list']));
                }
            }else{
                $data['list']       = $this->reports_model->get_venue_list();
      //echo "<pre>";print_r($data);exit();
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['court']=implode(',', $this->reports_model->get_court_list($value['venue_id']));
                    $data['list'][$key]['sports']=implode(',', $this->reports_model->get_sports_list($value['venue_id']));
                    $data['list'][$key]['court_list']=$this->reports_model->get_court($value['venue_id']);
                }

                foreach ($data['list'] as $key2 => $value2) {
                    if ($value2['court_list']) {
                        foreach ($value2['court_list'] as $key3 => $value3) {
                            $data['list'][$key2]['court_list'][$key3]['booked_slots']=$this->reports_model->court_time_booked($value3['id']);
                            $data['list'][$key2]['court_list'][$key3]['booked_slots_hours']=(count($this->reports_model->court_time_booked($value3['id']))*$value3['intervel'])/60;
                            //$data['list'][$key2]['total_hours_booked']=(count($this->reports_model->court_time_booked($value3['id']))*$value3['intervel'])/60;
                        }
                    }else{
                        $data['list'][$key2]['court_list'][0]['booked_slots_hours']="";
                    }
                }
                foreach ($data['list'] as $key4 => $value4) {
                    $data['list'][$key4]['hour_list']=array();
                    foreach ($value4['court_list'] as $key5 => $value5) {
                        array_push($data['list'][$key4]['hour_list'], $value5['booked_slots_hours']);
                    }
                    $data['list'][$key4]['total_hours']=round(array_sum($data['list'][$key4]['hour_list']));
                }
            }

            //echo "<pre>";print_r($data);exit();
            $data['heading']     ="Venue Utilization";
            $data['location']    = $this->reports_model->location_list();
            $this->load->template('venue_utilization',$data);
        }
    }
    ///////////////////////////////////////Venue Excel//////////////////////////////////////////////////
    public function venue_excel()
    {
        $list=$_SESSION['venue_list'];
        foreach ($list as $key => $value) {
            $venue[$key]=array($value['location'],$value['area'],$value['venue'],$value['court'],$value['sports'],$value['total_hours']);
        }
        $head = array('0' => 'City',
                      '1' => 'Area',
                      '2' => 'Venue Name',
                      '3' => 'Courts',
                      '4' => 'Sports',
                      '5' => 'Total Hours Booked', );
        array_unshift($venue , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:F1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($venue);

        $filename='venue.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    ////////////////////////////////////////////Roles///////////////////////////////////////////////////
    public function roles(){
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $role       = $this->input->post('role');
                $permission = $this->input->post('permission');
                //$date       = $this->input->post('date');
                //$enddate       = $this->input->post('enddate');
                $data['list']= $this->reports_model->get_roles_list($role);
                //echo "<pre>";print_r($this->input->post());exit();
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['permissions']=implode(',', $this->reports_model->get_permission_list($value['role_id'],$permission));
                }
            }else{
                $data['list']       = $this->reports_model->get_roles_list();
                foreach ($data['list'] as $key => $value) {
                    $data['list'][$key]['permissions']=implode(',', $this->reports_model->get_permission_list($value['role_id']));
                }
            }
            $data['heading']    ="Roles";
            $data['roles']      = $this->reports_model->get_roles_list();
            $data['permission'] = $this->reports_model->get_permission();
            //echo "<pre>";print_r($data);exit();
            $this->load->template('roles',$data);
        }
    }
    ///////////////////////////////////////Roles Excel//////////////////////////////////////////////////
    public function roles_excel()
    {
        $list=$_SESSION['roles_list'];
        foreach ($list as $key => $value) {
            $roles[$key]=array($value['name'],$value['permissions'],date( ' d-M-Y ',strtotime($value['added_date'])));
        }
        $head = array('0' => 'Role Name',
                      '1' => 'Permissions',
                      '2' => 'Created on', );
        array_unshift($roles , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:C1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($roles);

        $filename='roles.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    ////////////////////////////////////////General Notification////////////////////////////////////////////
    public function general_notification(){
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                $data['list']   = $this->reports_model->notification_list("General",$city,$area,$date,$enddate);
            }else{
                $data['list']   = $this->reports_model->notification_list("General");
            }
            $data['heading']    ="General Notification";
            $data['location']   = $this->reports_model->location_list();
            //echo "<pre>";print_r($data);exit();
            $this->load->template('general_notification',$data);
        }
    }
    ////////////////////////////////General Notification Excel//////////////////////////////////////////
    public function general_excel()
    {
        $list=$_SESSION['general_list'];
        foreach ($list as $key => $value) {
            $general[$key]=array($value['city'],$value['area'],$value['sports']);
            if ($value['type']=="sms") {
                $sms="SMS";
            }else{
                $sms="";
            };
            array_push($general[$key],$sms);
            if ($value['type']=="notification") {
                $push="Push";
            }else{
                $push="";
            };
            array_push($general[$key],$push);
            if ($value['type']=="both") {
                $both="Both";
            }else{
                $both="";
            };
            array_push($general[$key],$both);
            array_push($general[$key],date( ' d-M-Y ',strtotime($value['send_date'])));
            array_push($general[$key],date( ' h:i:s ',strtotime($value['send_date'])));
            array_push($general[$key],$value['message']);

        }
        //echo "<pre>";print_r($general);exit();
        $head = array('0' => 'City',
                      '1' => 'Area',
                      '2' => 'Sports',
                      '3' => 'SMS',
                      '4' => 'Push',
                      '5' => 'Both',
                      '6' => 'Send On Date',
                      '7' => 'Send On Time',
                      '8' => 'Message', );
        array_unshift($general , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:I1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($general);

        $filename='general.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    ////////////////////////////////////////Offer Notification////////////////////////////////////////////
    public function offer_notification(){
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                $data['list']       = $this->reports_model->notification_list("Offer",$city,$area,$date,$enddate);
            }else{
                $data['list']       = $this->reports_model->notification_list("Offer");
            }
            $data['heading']    ="Offer Notification";
            $data['location']   = $this->reports_model->location_list();
            //echo "<pre>";print_r($data);exit();   
            $this->load->template('offer_notification',$data);
        }
    }
    ////////////////////////////////Offer Notification Excel//////////////////////////////////////////
    public function offer_excel()
    {
        $list=$_SESSION['offer_list'];
        foreach ($list as $key => $value) {
            $offer[$key]=array($value['city'],$value['area'],$value['sports'],$value['percentage']."%",date( ' d-M-Y ',strtotime($value['start'])),date( ' d-M-Y ',strtotime($value['end'])));
            if ($value['type']=="sms") {
                $sms="SMS";
            }else{
                $sms="";
            };
            array_push($offer[$key],$sms);
            if ($value['type']=="notification") {
                $push="Push";
            }else{
                $push="";
            };
            array_push($offer[$key],$push);
            if ($value['type']=="both") {
                $both="Both";
            }else{
                $both="";
            };
            array_push($offer[$key],$both);
            array_push($offer[$key],date( ' d-M-Y ',strtotime($value['send_date'])));
            array_push($offer[$key],date( ' h:i:s ',strtotime($value['send_date'])));
            array_push($offer[$key],$value['message']);

        }
        //echo "<pre>";print_r($offer);exit();
        $head = array('0' => 'City',
                      '1' => 'Area',
                      '2' => 'Sports',
                      '3' => 'Offer %',
                      '4' => 'Offer Valid From',
                      '5' => 'Offer Valid Till',
                      '6' => 'SMS',
                      '7' => 'Push',
                      '8' => 'Both',
                      '9' => 'Send On Date',
                      '10' => 'Send On Time',
                      '11' => 'Message', );
        array_unshift($offer , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:L1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:L1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($offer);

        $filename='offer.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    //////////////////////////////Area List//////////////////////////////////////////////////
    public function area_list(){
        $city   = $this->input->post('city');
        $data['area']   = $this->reports_model->area_list($city);

        //echo "<pre>";print_r($data);exit();
        if ($data) {
            echo json_encode($data);
        }
    }
    /////////////////////////////////////////Venue list/////////////////////////////////////////////////
    public function venue_list_data(){
        $area   = $this->input->post('area');
        $data['venue']   = $this->reports_model->get_venues($area);

        //echo "<pre>";print_r($data);exit();
        if ($data) {
            echo json_encode($data);
        }
    }
    ////////////////////////////////////////Coupons Notification////////////////////////////////////////////
    public function coupons(){
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {
                $city           = $this->input->post('city');
                $area           = $this->input->post('area');
                $date           = $this->input->post('date');
                $coupon_code    = $this->input->post('coupon_code');
                $data['list']   = $this->reports_model->coupons_list($city,$area,$date,$coupon_code);
            }else{
                $data['list']   = $this->reports_model->coupons_list();
            }
            $data['coupon_code']   = $this->reports_model->coupon_code_list();
            $data['location']   = $this->reports_model->location_list();
            $data['heading']    ="Coupons";
            //echo "<pre>";print_r($data);exit();
            $this->load->template('coupons',$data);
        }
    }
    ////////////////////////////////Coupon Excel////////////////////////////////////////////
    public function coupon_excel()
    {
        $list=$_SESSION['coupons'];
        foreach ($list as $key => $value) {
            $coupon[$key]=array($value['location'],$value['area']);
            if ($value['percentage']=='Yes') {
                $coupon_value=$value['coupon_value']."%";
            }else if ($value['percentage']=='No') {
                $coupon_value=$value['currency'].$value['coupon_value'];
            }else{
                $coupon_value="";
            }
            array_push($coupon[$key],$coupon_value);
            array_push($coupon[$key],$value['coupon_code'],$value['name'],$value['phone_no'],date( ' d M Y ',strtotime($value['time'])),date( ' d M Y ',strtotime($value['valid_from']))."-".date( ' d M Y ',strtotime($value['valid_to'])));

        }
        //echo "<pre>";print_r($coupon);exit();
        $head = array('0' => 'City',
                      '1' => 'Area',
                      '2' => 'Coupon Value',
                      '3' => 'Coupon Code',
                      '4' => 'Used By',
                      '5' => 'User Phone No',
                      '6' => 'Used On Date',
                      '7' => 'Coupon Validity Period', );
        array_unshift($coupon , $head);
        //echo "<pre>";print_r($users);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:H1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($coupon);

        $filename='coupon.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
   
    ////////////////////////////////////////////////////////////////////////////////////////////////

     //////////////////////////////////// Offer Report ////////////////////////////////////////////
    public function offer_report($venue="") {
        if(!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) {
        }else{
            if ($this->input->post('submit')) {

                //echo "<pre>";print_r($this->input->post());exit();
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $venue  = $this->input->post('venue');
                $sports = $this->input->post('sports');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                /*$time = $this->input->post('time');*/


                $data['offer']       = $this->reports_model->get_offer_list($city,$area,$venue,$sports,$date,$enddate);
                foreach ($data['offer'] as $key => $value) {
                    $data['offer'][$key]['booked_id']=implode(',', $this->reports_model->get_offer_bklist($value['id']));
                    $data['offer'][$key]['days']=implode(',', $this->reports_model->get_days_list($value['id']));
                    $data['offer'][$key]['booking_count']=implode(',', $this->reports_model->get_bookscount_list($value['id']));
                }
                
            }else{
                $data['offer']       = $this->reports_model->get_offer_list();
                foreach ($data['offer'] as $key => $value) {
                    $data['offer'][$key]['booked_id']=implode(',', $this->reports_model->get_offer_bklist($value['id']));
                    $data['offer'][$key]['days']=implode(',', $this->reports_model->get_days_list($value['id']));
                    $data['offer'][$key]['booking_count']=implode(',', $this->reports_model->get_bookscount_list($value['id']));
                }
               
                //echo "<pre>";print_r($data);exit();
            }

            $data['heading']     ="Offer";
            $data['location']    = $this->reports_model->location_list();
            //$data['venue']       = $this->reports_model->get_venues();
            // $data['sports']      = $this->reports_model->get_sports();
            //echo "<pre>";print_r($data);exit(); 
            $this->load->template('offer_report',$data);
        }
    }
 //////////////////////////// Offer List//////////////////////////////////////////////
    public function bookedoffer_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['offer'];
        foreach ($list as $key => $value) {
            $offer[$key]=array($value['location'],$value['area'],$value['venue'],$value['court'],$value['sports'],$value['offer_value']);
        $dates_range = date( ' d M Y ',strtotime($value['start'])) . "-" . date( ' d M Y ',strtotime($value['end']));
        $times_range = date( ' h:i:s A ',strtotime($value['start_time'])) . "-" . date( ' h:i:s A ',strtotime($value['end_time']));
        array_push($offer[$key],$dates_range,$times_range,$value['days'],$value['booking_count'],$value['booked_id']);
        }
        $head = array('0' => 'City',
        '1' => 'Area',
        '2' => 'Venue',
        '3' => 'Court',
        '4' => 'Sports',
        '5' => 'Offer Rate',
        '6' => 'Offer Validity Dates',
        '7' => 'Offer Validity Time',
        '8' => 'Offer Validity Days',
        '9' => 'no. of bookings',
        '10' => 'Booking Ids',
        );
        array_unshift($offer , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($offer);

        $filename='offer_report.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
        //////////////////////////////////// Hot offer Report start ////////////////////////////////////////////
    public function hotoffer_report($venue="") {

            if ($this->input->post('submit')) {


                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $venue  = $this->input->post('venue');
                $sports = $this->input->post('sports');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');

                $data['hotoffer']       = $this->reports_model->get_hotoffer_list($city,$area,$venue,$sports,$date,$enddate);
               
            }else{
                $data['hotoffer']       = $this->reports_model->get_hotoffer_list();
            }

            $data['heading']     ="Hot offer Report";
            $data['location']    = $this->reports_model->location_list(); 
            $this->load->template('hotoffer_report',$data);
       
    }
    //////////////////////////////////// Hot offer Report end ////////////////////////////////////////////
    
    //////////////////////////// Hot Offer List//////////////////////////////////////////////
    public function hotoffer_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['hotoffer'];
        foreach ($list as $key => $value) {
            $hotoffer[$key]=array($value['location'],$value['area'],$value['venue'],$value['sports'],$value['court'],date( ' h:i:s A ',strtotime($value['court_time'])));
            $hot_percentage = $value['precentage'] ."%";
            $booking_date = date( ' d M Y ',strtotime($value['date']));
        array_push($hotoffer[$key],$hot_percentage,$booking_date,$value['booking_id']);
        }
        $head = array('0' => 'City',
        '1' => 'Area',
        '2' => 'Venue Name',
        '3' => 'Sports Name',
        '4' => 'Court Name',
        '5' => 'Slot',
        '6' => 'Hot Offer %',
        '7' => 'Date of Booking',
        '8' => 'Booking ID'
        );
        array_unshift($hotoffer , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($hotoffer);

        $filename='hot_offer_report.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    
 //////////////////////////////////// Refer a friend Report start ////////////////////////////////////////////
    public function refer_friend_report($venue="") {

            if ($this->input->post('submit')) {


                $city   = $this->input->post('city');

                $data['refer']       = $this->reports_model->get_refer_list($city);
                foreach ($data['refer'] as $key => $value) {
                    $data['refer'][$key]['install_counts']=implode(',', $this->reports_model->get_referal_install_count($value['id']));
                    $data['refer'][$key]['install_bonus_coins']=implode(',', $this->reports_model->get_referal_install_boncoins($value['id']));
                    $data['refer'][$key]['referal_booking_count']=implode(',', $this->reports_model->get_referal_booking_count($value['id']));
                    $data['refer'][$key]['referal_booking_coin']=implode(',', $this->reports_model->get_referal_booking_coin($value['id']));
                    $data['refer'][$key]['referal_user_city']=implode(',', $this->reports_model->get_referal_user_city($value['id']));
                }
               
            }else{
                $data['refer']       = $this->reports_model->get_refer_list();
                foreach ($data['refer'] as $key => $value) {
                    $data['refer'][$key]['install_counts']=implode(',', $this->reports_model->get_referal_install_count($value['id']));
                    $data['refer'][$key]['install_bonus_coins']=implode(',', $this->reports_model->get_referal_install_boncoins($value['id']));
                    $data['refer'][$key]['referal_booking_count']=implode(',', $this->reports_model->get_referal_booking_count($value['id']));
                    $data['refer'][$key]['referal_booking_coin']=implode(',', $this->reports_model->get_referal_booking_coin($value['id']));
                    $data['refer'][$key]['referal_user_city']=implode(',', $this->reports_model->get_referal_user_city($value['id']));
                }
            }
            //echo "<pre>";print_r($data);exit(); 

            $data['heading']     ="Refer a Friend Report";
            $data['location']    = $this->reports_model->location_list(); 
            $this->load->template('refer_friend',$data);
       
    }
    //////////////////////////////////// Refer a friend Report end ////////////////////////////////////////////
//////////////////////////// refer a friend List//////////////////////////////////////////////
    public function refer_friend_excel()
    {
        //echo "<pre>";print_r($_SESSION['refer']);exit();
        $list=$_SESSION['refer'];
        foreach ($list as $key => $value) {
            $refer[$key]=array($value['phone_no'],$value['install_counts'],$value['install_bonus_coins'],$value['referal_booking_count'],$value['referal_booking_coin'],$value['referal_user_city']);
        array_push($refer[$key]);
        }
        $head = array('0' => 'User no.',
        '1' => 'No. of installation',
        '2' => 'Bonus on installation',
        '3' => 'Number of referral booking',
        '4' => 'Bonus on referral booking',
        '5' => 'City/ area of installation',
        );
        array_unshift($refer , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($refer);

        $filename='refer_a_friend_report.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
//////////////////////////////////// UPcoin purchase Report start ////////////////////////////////////////////
    public function upcoin_report($venue="") {

            if ($this->input->post('submit')) {


                $city   = $this->input->post('city');

                $datas['upcoin']       = $this->reports_model->get_upcoin_list($city);
                $datas['book_coin']       = $this->reports_model->get_bookcoin_list($city);
                $data['upcoin'] = array_merge ($datas['upcoin'],$datas['book_coin']);
                usort( $data['upcoin'], function( $a, $b ){
                        if($a['added_date'] == $b['added_date'] ) {
                            return 0;
                        }
                    return ($a['added_date'] > $b['added_date'] ) ? -1 : 1;
                    });
                foreach ($data['upcoin'] as $key => $value) {
                    $data['upcoin'][$key]['coupon_status']=implode(',', $this->reports_model->get_booking_coupon_status($value['coupon_id']));
                    $data['upcoin'][$key]['coupon_value']=implode(',', $this->reports_model->get_booking_coupon_value($value['coupon_id']));
                    $data['upcoin'][$key]['upupup_share']=implode(',', $this->reports_model->get_upupup_share_value($value['venue_id']));
                }
                
               
            }else{
                $datas['upcoin']       = $this->reports_model->get_upcoin_list();
                $datas['book_coin']       = $this->reports_model->get_bookcoin_list();
                $data['upcoin'] = array_merge ($datas['upcoin'],$datas['book_coin']);
                usort( $data['upcoin'], function( $a, $b ){
                        if($a['added_date'] == $b['added_date'] ) {
                            return 0;
                        }
                    return ($a['added_date'] > $b['added_date'] ) ? -1 : 1;
                    });
                foreach ($data['upcoin'] as $key => $value) {
                    $data['upcoin'][$key]['coupon_status']=implode(',', $this->reports_model->get_booking_coupon_status($value['coupon_id']));
                    $data['upcoin'][$key]['coupon_value']=implode(',', $this->reports_model->get_booking_coupon_value($value['coupon_id']));
                    $data['upcoin'][$key]['upupup_share']=implode(',', $this->reports_model->get_upupup_share_value($value['venue_id']));
                }
                
            }
            //echo "<pre>";print_r($data);exit(); 

            $data['heading']     ="UPcoin v/s Booking report";
            $data['location']    = $this->reports_model->location_list(); 
            $this->load->template('upcoin',$data);
       
    }
    //////////////////////////////////// UPcoin purchase Report end ////////////////////////////////////////////
    
//////////////////////////// refer a friend List//////////////////////////////////////////////
    public function upcoin_vs_booking_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['upcoin'];
        foreach ($list as $key => $value) {
            $upcoin[$key]=array($value['location'],$value['phone_no'],$value['rupee']);
            $bonus="";
            if ($value['coin']!=0) {
                $bonus=$value['coin']-$value['rupee'];
            }
        array_push($upcoin[$key],$bonus,$value['booking_id'],$value['paid_amount']);
        $coupon_amt="";
            if ($value['coupon_status']=="Yes") {
                $coupon_amt=($value['coupon_value']/100)*$value['total_amount'];
            }else{
                if($value['coupon_status']=="No"){
                  $coupon_amt=$value['coupon_value'];  
                }
            }
        array_push($upcoin[$key],$coupon_amt);
        $total_amt="";
            if ($value['coupon_status']=="Yes") {
                $total_amt=(($value['coupon_value']/100)*$value['total_amount'])+$value['paid_amount'];
            }else{
                if($value['coupon_status']=="No"){
                  $total_amt=$value['paid_amount']+$value['coupon_value'];  
                }
            }
        array_push($upcoin[$key],$total_amt,$value['upupup_share']);
        $share_amount="";
            if($value['upupup_share']!=NULL && $value['paid_amount']!=NULL){
                if($value['coupon_status']=="Yes"){
                    $share_amount=((($value['coupon_value']/100)*$value['total_amount'])+$value['paid_amount'])*($value['upupup_share']/100);
                }else{
                    if($value['coupon_status']=="No"){
                        $share_amount=($value['paid_amount']+$value['coupon_value'])*($value['upupup_share']/100);  
                    }else{
                        $share_amount=$value['paid_amount']*($value['upupup_share']/100);
                    } 
                }
            }
        array_push($upcoin[$key],$share_amount);

        }
        $head = array('0' => 'City',
        '1' => 'User Mobile No.',
        '2' => 'UPcoin purchase',
        '3' => 'Bonus',
        '4' => 'Booking ID using UPCons',
        '5' => 'Paid Amount',
        '6' => 'Coupon amount',
        '7' => 'Total amount',
        '8' => 'upUPUP Share %',
        '9' => 'upUPUP Share Amount',
        );
        array_unshift($upcoin , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($upcoin);

        $filename='upcoin_vs_booking_report.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
    
    //////////////////////////////////// booking refund start ////////////////////////////////////////////
    public function refund_report($venue="") {

            if ($this->input->post('submit')) {


                $city   = $this->input->post('city');
                $data['refund']       = $this->reports_model->get_refund_list($city);
                
               
            }else{
                $data['refund']       = $this->reports_model->get_refund_list();

                
            }
            //echo "<pre>";print_r($data);exit(); 

            $data['heading']     ="Booking Refund Report";
            $data['location']    = $this->reports_model->location_list(); 
            $this->load->template('refund_booking',$data);
       
    }
    //////////////////////////////////// booking refund end ////////////////////////////////////////////
//////////////////////////// booking refund excel start//////////////////////////////////////////////
    public function refund_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['refund'];
        foreach ($list as $key => $value) {
            $refer[$key]=array($value['location'],$value['booking_id'],$value['phone_no'],$value['name'],$value['amount'],date( 'd/m/Y',strtotime($value['date'])),$value['reason']);
        array_push($refer[$key]);
        }
        $head = array('0' => 'City',
        '1' => 'Booking ID',
        '2' => 'User No.',
        '3' => 'User name',
        '4' => 'Refund Amount',
        '5' => 'Refund Date',
        '6' => 'Reason for Refund',
        );
        array_unshift($refer , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:V1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:V1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($refer);

        $filename='booking_refund.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
//////////////////////////// booking refund excel end //////////////////////////////////////////////
//////////////////////////////////// Trainers Report start ////////////////////////////////////////////
    public function trainer_report() {

            if ($this->input->post('submit')) {
                $city   = $this->input->post('city');
                $area   = $this->input->post('area');
                $sports   = $this->input->post('sports');
                $date   = $this->input->post('date');
                $enddate   = $this->input->post('enddate');
                if(!empty($date) && !empty($enddate)){
                    $start_date=$date." ".date('H:i:s', strtotime('00:00:01'));
                    $start_date=date('Y-m-d H:i:s', strtotime($start_date));
                    $end_date=$enddate." ".date('H:i:s', strtotime('23:59:59'));
                    $end_date=date('Y-m-d H:i:s', strtotime($end_date));     
                }

                $data['trainer']       = $this->reports_model->get_trainers_list($city,$area,$sports,$start_date,$end_date);
                foreach ($data['trainer'] as $key => $value) {
                    $data['trainer'][$key]['followers']=implode(',', $this->reports_model->get_trainer_followers($value['id']));
                    $data['trainer'][$key]['sports']=implode(' , ', $this->reports_model->get_trainer_sports($value['id']));
                }
            }else{
                $data['trainer']       = $this->reports_model->get_trainers_list();
                foreach ($data['trainer'] as $key => $value) {
                    $data['trainer'][$key]['followers']=implode(',', $this->reports_model->get_trainer_followers($value['id']));
                    $data['trainer'][$key]['sports']=implode(' , ', $this->reports_model->get_trainer_sports($value['id']));
                }
            }
            //echo "<pre>";print_r($data);exit();

            $data['heading']     ="Trainers Report";
            $data['location']    = $this->reports_model->location_list();
            $data['sports']    = $this->reports_model->sport_list();
            $this->load->template('trainers_report',$data);
       
    }
//////////////////////////////////// Trainers Report end //////////////////////////////////////////// 
//////////////////////////// Trainers excel start//////////////////////////////////////////////
    public function trainers_excel()
    {
        //echo "<pre>";print_r($_SESSION);exit();
        $list=$_SESSION['trainer'];
        foreach ($list as $key => $value) {
            $trainer[$key]=array($value['name'],$value['location'],$value['phone'],$value['sports'],date( ' d M Y h:i:s A',strtotime($value['added_date'])),$value['followers']);
        array_push($trainer[$key]);
        }
        $head = array('0' => 'Name',
        '1' => 'City',
        '2' => 'Phone',
        '3' => 'Sports',
        '4' => 'Enrollment Date Time',
        '5' => 'Followers',
        );
        array_unshift($trainer , $head);
        //echo "<pre>";print_r($rs);exit();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()
                    ->getStyle('A1:F1')
                    ->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'BDD6EE')
                            )
                        )
                    );
        // read data to active sheet
        $this->excel->getActiveSheet()->fromArray($trainer);

        $filename='trainer.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

    }
//////////////////////////// Trainers excel end //////////////////////////////////////////////   
}?>