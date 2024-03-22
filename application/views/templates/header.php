<!--Jinson Jose jinsonjose007@gmail.com-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta property="og:title" content="upUPUP" />
    
    <meta property="og:url" content="http://upupup.in/" />
    <meta property="og:image" content="<?=base_url()?>pics/unnamed.png" />
    <title>upUPUP</title>
    <link rel="shortcut icon" href="<?=base_url()?>pics/unnamed.png" type="image/png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/iCheck/all.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap-fileinput.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/custom.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/fullcalendar.css">
    <script src="<?= base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script>
        var base_url = '<?php echo base_url(); ?>';
    </script>
</head>

<body class="sidebar-mini skin-yellow" style="padding:0px">
    <div class="wrapper">
        <header class="main-header">
            <a href="<?=base_url('acl') ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">UP</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>upUPUP</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <?php if($this->session->userdata('image')=="" || $this->session->userdata('image')=="0" ){
                    $image="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/Circle-icons-profle.svg/768px-Circle-icons-profle.svg.png";
                }else{
                    $image=$this->session->userdata('image');
                }?>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?=$image ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?=  $this->session->userdata('name'); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?=$image; ?>" class="img-circle" alt="User Image">
                                    <p>
                                        <?=  $this->session->userdata('name'); ?>
                                        <small><?= $this->session->userdata('role'); ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                         <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'profile')){?>
                                        <a href="<?= base_url()?>acl/user/edit/<?php echo $this->session->userdata('user_id')?>" class="btn btn-default btn-flat">Profile</a>
                                        <?php } ?>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?= base_url("acl/user/sign_out")?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?=$image; ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?=  $this->session->userdata('name'); ?></p>
                        <a ><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_user') || $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_role')|| $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_perm')){ ?>
                    <li class=" treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i>
                            <span>User Mangament</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_user')) { ?><li ><a href="<?=base_url('acl/user')?>"><i class="fa fa-circle-o"></i>Users</a></li><?php }?>
                            <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_role')) { ?><li><a href="<?=base_url('acl/role')?>"><i class="fa fa-circle-o"></i>Roles</a></li><?php }?>
                            <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_perm')) { ?><li><a href="<?=base_url('acl/perm')?>"><i class="fa fa-circle-o"></i>Permissions</a></li><?php }?>
                            <!-- <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'access_acl')) { ?><li><a href="<?=base_url('acl/user/bulk')?>"><i class="fa fa-circle-o"></i>Bulk Upload</a></li><?php }?> -->
                        </ul>
                    </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_users')) { ?>
                        <li class=" treeview">
                            <a href="">
                                <i class="fa fa-user"></i>
                                <span>App Users</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li ><a href="<?=base_url('users')?>"><i class="fa fa-circle-o"></i>Users</a></li>
                                <!-- <li><a href="<?=base_url('users/bulk')?>"><i class="fa fa-circle-o"></i>Bulk Upload</a></li> </li>-->
                            </ul>
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_venue')) { ?>
                        <li class=" treeview">
                            <a href="#">
                                <i class="fa  fa-paw"></i>
                                <span>Venue</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_venue')) { ?>
                                    <li><a href="<?=base_url('venue')?>"><i class="fa fa-circle-o"></i>Venue</a></li>
                                <?php }?>
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_facility')) { ?>
                                    <li><a href="<?=base_url('venue/speciality')?>"><i class="fa fa-circle-o"></i> Facilities</a></li>
                                <?php }?>
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_facility')) { ?>
                                    <li><a href="<?=base_url('venue/bulk')?>"><i class="fa fa-circle-o"></i>Bulk Upload</a></li>
                                <?php }?>
                                <?php $role=$this->acl_model->get_role_by_user($this->session->userdata('user_id'));
                                    if($role->slug=="admin"){?>
                                    <li><a href="<?=base_url('venue/venue_managers')?>"><i class="fa fa-circle-o"></i>Venue Managers</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }?>
                     <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_offer')) { ?>
                        <li class=" treeview">
                            <a href="#">
                                <i class="fa  fa-paw"></i>
                                <span>Hot Offer</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_offer')) { ?>
                                    <li><a href="<?=base_url('hot_offer')?>"><i class="fa fa-circle-o"></i>Hot Offer Settings</a></li>
                                <?php }?>
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_offer_notification')) { ?>
                                    <li><a href="<?=base_url('hot_offer/notification')?>"><i class="fa fa-circle-o"></i>Hot Offer Notification</a></li>
                                <?php }?>
                                
                            </ul>
                        </li>
                    <?php }?>
                    
                    
                       
                    
                    
                    <!--
                     <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_slider_images')) { ?>
                        <li class=" treeview">
                            <a href="#">
                                <i class="fa  fa-paw"></i>
                                <span>Hot Slider Images  </span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            
                           <ul class="treeview-menu">
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_slider_images')) { ?>
                                    <li><a href="<?=base_url('hot_offer_slider')?>"><i class="fa fa-circle-o"></i>Add  Slider Images </a></li>
                                <?php }?>
                        </ul>
                            
                        </li>
                    <?php }?>-->
                    
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_up_coin')) { ?>
                    <li class=" treeview">
                            <a href="#">
                                <i class="fa  fa-paw"></i>
                                <span>UP Coin</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            
                           <ul class="treeview-menu">
                                     <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_up_coin')) { ?>
                                    <li><a href="<?=base_url('up_coin')?>"><i class="fa fa-circle-o"></i>UP Coin Setting</a></li>
                                    <?php }?>
                                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_up_coin')) { ?>
                                    <li><a href="<?=base_url('up_coin/buycoin')?>"><i class="fa fa-circle-o"></i>Buy Coin Setting</a></li>
                                    <?php }?>
                                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_up_coin')) { ?>
                                    <li><a href="<?=base_url('up_coin/booking_bonus')?>"><i class="fa fa-circle-o"></i>Booking Bonus Setting</a></li>
                                    <?php }?>
                               
                           </ul>
                            
                    </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_trainers')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('trainers')?>">
                                <i class="fa fa-soccer-ball-o"></i> <span>Trainers & Coaches</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_program')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('programs')?>">
                                <i class="fa fa-soccer-ball-o"></i> <span>Programs</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_shop')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('shop')?>">
                                <i class="fa fa-soccer-ball-o"></i> <span>Shops</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
                    
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) { ?>
                    <li class=" treeview">
                            <a href="#">
                                <i class="fa  fa-paw"></i>
                                <span>Advertisement</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            
                           <ul class="treeview-menu">
                                     <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) { ?>
                                    <li><a href="<?=base_url('advertisement')?>"><i class="fa fa-circle-o"></i>Shop Advertisement 1</a></li>
                                    <?php }?>
                                     <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) { ?>
                                    <li><a href="<?=base_url('advertisement/shops')?>"><i class="fa fa-circle-o"></i>Shop Advertisement 2</a></li>
                                    <?php }?>
                                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) { ?>
                                    <li><a href="<?=base_url('advertisement/trainer')?>"><i class="fa fa-circle-o"></i>Trainer Advertisement 1</a></li>
                                    <?php }?>
                                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_advertisement')) { ?>
                                    <li><a href="<?=base_url('advertisement/venue')?>"><i class="fa fa-circle-o"></i>Trainer Advertisement 2</a></li>
                                    <?php }?>
                               
                           </ul>
                            
                    </li>
                    <?php }?> 
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_refer')) { ?>
                        <li class=" treeview">
                            <a href="#">
                                <i class="fa  fa-paw"></i>
                                <span>Refer A Friend</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                           <ul class="treeview-menu">
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_refer')) { ?>
                                    <li><a href="<?=base_url('refer_friend')?>"><i class="fa fa-circle-o"></i>Refer A Friend Setting </a></li>
                                <?php }?>
                        </ul>
                            
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_service_charge')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('charges')?>">
                                <i class="fa fa-soccer-ball-o"></i> <span>Service Charges</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>                    
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_matches')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('matches')?>">
                                <i class="fa  fa-gamepad"></i> <span>Hosted Matches</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
                    <?php $role=$this->acl_model->get_role_by_user($this->session->userdata('user_id'));
                    if($role->venue_users==2){?>
                        <li class=" treeview">
                            <a href="<?=base_url("court")?>">
                            <i class="fa  fa-opera"></i> <span>Court</span>
                            <span class="pull-right-container">
                            </span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_sports')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('sports')?>">
                                <i class="fa fa-soccer-ball-o"></i> <span>Sports</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_location') || $this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_area'))  { ?>
                        <li class=" treeview">
                            <a href="#">
                                <i class="fa fa-map-marker"></i> <span>Location</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_area')) { ?>
                                    <li><a href="<?=base_url('places/location')?>"><i class="fa fa-circle-o"></i>City</a></li>
                                <?php }?>
                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_area')) { ?>
                                    <li><a href="<?=base_url('places/area')?>"><i class="fa fa-circle-o"></i>Areas</a></li>
                                <?php }?>
                            </ul>
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_offers')) { ?>
                        <!--    <li class=" treeview">
                            <a href="<?php //echo base_url('offer')?>">
                                <i class="fa fa-money"></i> <span>Offers</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li> -->
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_coupons')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('coupons')?>">
                                <i class="fa fa-certificate"></i> <span>Coupons</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
               
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_booking')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('booking')?>">
                                <i class="fa fa-cog"></i>
                                <span>Booking History</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_notification')) { ?>
                        <li class=" treeview">
                            <a href="">
                                <i class="fa fa-bell"></i>
                                <span>Notification</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li ><a href="<?=base_url('notification_sms/')?>"><i class="fa fa-circle-o"></i>Notification</a></li>
                                <li><a href="<?=base_url('notification_sms/history')?>"><i class="fa fa-circle-o"></i>Notification History</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_reports')) { ?>
                        <li class=" treeview">
                            <a href="">
                                <i class="fa fa-bar-chart "></i> <span>Reports</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li ><a href="<?=base_url('reports/users')?>"><i class="fa fa-circle-o"></i>App Users</a></li>
                                <li><a href="<?=base_url('reports/backend_users')?>"><i class="fa fa-circle-o"></i>Backend Users</a></li>
                                <li><a href="<?=base_url('reports/upupup_booking')?>"><i class="fa fa-circle-o"></i>upUPUP Bookings</a></li>
                                <li><a href="<?=base_url('reports/vendors_booking')?>"><i class="fa fa-circle-o"></i>Vendor Bookings</a></li>
                                <li><a href="<?=base_url('reports/cancel_booking')?>"><i class="fa fa-circle-o"></i>Cancel Bookings</a></li>
                                <li><a href="<?=base_url('reports/hosting_list')?>"><i class="fa fa-circle-o"></i>Hosting</a></li>
                                <li><a href="<?=base_url('reports/venue_list')?>"><i class="fa fa-circle-o"></i>Venue Utilization</a></li>
                                <li><a href="<?=base_url('reports/roles')?>"><i class="fa fa-circle-o"></i>Roles</a></li>
                                <li><a href="<?=base_url('reports/general_notification')?>"><i class="fa fa-circle-o"></i>General Notification</a></li>
                                <li><a href="<?=base_url('reports/offer_notification')?>"><i class="fa fa-circle-o"></i>Offer Notification</a></li>
                                <li><a href="<?=base_url('reports/coupons')?>"><i class="fa fa-circle-o"></i>Coupons</a></li>
                                <li><a href="<?=base_url('reports/offer_report')?>"><i class="fa fa-circle-o"></i>Offer Report</a></li>
                                <li><a href="<?=base_url('reports/hotoffer_report')?>"><i class="fa fa-circle-o"></i>Hot Offer Report</a></li>
                                <li><a href="<?=base_url('reports/refer_friend_report')?>"><i class="fa fa-circle-o"></i>Refer a Friend Report</a></li>
                                <li><a href="<?=base_url('reports/upcoin_report')?>"><i class="fa fa-circle-o"></i>UPcoin v/s Booking report</a></li>
                                <li><a href="<?=base_url('reports/refund_report')?>"><i class="fa fa-circle-o"></i>Refund Booking</a></li>
                                <li><a href="<?=base_url('reports/trainer_report')?>"><i class="fa fa-circle-o"></i>Trainers Report</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_settings')) { ?>
                        <li class=" treeview">
                            <a href="">
                                <i class="fa fa-cog"></i> <span>Settings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <!-- <li ><a href="<?=base_url('settings/')?>"><i class="fa fa-circle-o"></i>Data Backup</a></li> --> 
                                <li><a href="<?=base_url('settings/contact_data?email=1')?>"><i class="fa fa-circle-o"></i>Contact Data</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_faq')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('faq')?>">
                                <i class="fa fa-question"></i> <span>FAQ</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_feedback')) { ?>
                        <li class=" treeview">
                            <a href="<?=base_url('feedback')?>">
                                <i class="fa fa-comments-o"></i> <span>Feedback</span>
                                <span class="pull-right-container"></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_version')) { ?>
                    <li class=" treeview">
                        <a href="<?=base_url('version')?>">
                            <i class="fa fa-tag"></i> <span>Versions</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                    <?php }?>
                </ul>
            </section>
        </aside>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <img src="<?=base_url('assets/img/balls.svg')?>" id="gif" style="position: absolute;    margin-left: 200px;    width: 200px;    margin-top: 200px;">
            </div>
        </div>
