<!DOCTYPE html>
<html lang="en">

<!--================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 2.2
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================ -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="description" content="UPupup is the in-progress brainchild of Planet Priorities, unleashing into the cosmic world of Sports; after their, now matured, advertisement film production house, Peraka Media [www.perakamedia.com]. ">
  <meta name="keywords" content="UPupup, admin template, dashboard template, flat admin template, responsive admin template,">

    <meta property="og:title" content="UPupup" />  
    <meta property="og:url" content="http://upupup.in/" />
    <meta property="og:image" content="<?=base_url()?>pics/unnamed.png" />

  <title> Login  </title>
 <link rel="shortcut icon" href="<?=base_url()?>pics/unnamed.png" type="image/png">
  <!-- Favicons-->
  
  <!-- For iPhone -->
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="<?=base_url()?>pics/unnamed.png">
  <!-- For Windows Phone -->


  <!-- CORE CSS-->
  
  <link href="<?= base_url('assets/css/login-materialize.css')?>" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="<?= base_url('assets/css/login-style.css')?>" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    
    <link href="<?= base_url('assets/css/login-style-horizontal.css')?>" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="<?= base_url('assets/css/login-page-center.css')?>" type="text/css" rel="stylesheet" media="screen,projection">

  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
  <link href="<?= base_url('assets/css/login-prism.css')?>" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="<?= base_url('assets/css/login-perfect-scrollbar.css')?>" type="text/css" rel="stylesheet" media="screen,projection">
   
</head>

<body class="cyan">
  <!-- Start Page Loading -->
  <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div>
  <!-- End Page Loading -->



  <div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">
    <?php if(validation_errors()): ?> 
          <div class="alert alert-danger" style="color: #E9EDEF;
    background-color: rgba(231,76,60,0.88);
    border-color: rgba(231,76,60,0.88); text-align: center;">
          <?= validation_errors(); ?>
          </div>
         <?php endif; ?>
      <form class="login-form" method="post" action="<?= base_url('acl/user/sign_in'); ?>" >
        <div class="row">
          <div class="input-field col s12 center">
          
            <p class="center login-form-text"> <h4 style="color:#ea9f31">  <a href="//upupup.in"><img src="<?=base_url('pics/venue/upupup.png')?>" alt="" class="circle responsive-img valign profile-image-login" style="width: 200px"></a></h4> </p>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="username" type="email" name="email" required="">
            <label for="username" class="center-align">Username</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" type="password" name="password" required="">
            <label for="password">Password</label>
          </div>
        </div>
        <!-- <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">Remember me</label>
          </div>
        </div> -->
        <div class="row">
          <div class="input-field col s12">
            <button type="submit" class="btn waves-effect waves-light col s12">Login</button>
          </div>
        </div>
    <div class="row">
          <div class="input-field col s6 m6 l6">
            <p class="margin medium-small"><a></a></p>
          </div>
          <div class="input-field col s6 m6 l6">
              <p class="margin right-align medium-small"><a href="lost_password">Forgot password ?</a></p>
          </div>          
        </div> 

      </form>
    </div>
  </div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


  <!-- ================================================
    Scripts
    ================================================ -->

  <!-- jQuery Library -->
  <script type="text/javascript" src="<?= base_url('assets/js/jquery-1.11.2.min.js')?>"></script>
  <!--materialize js-->
  <script type="text/javascript" src="<?= base_url('assets/js/login-materialize.js')?>"></script>
  <!--prism-->
  <script type="text/javascript" src="<?= base_url('assets/js/login-prism.js')?>"></script>
  <!--scrollbar-->
  <script type="text/javascript" src="<?= base_url('assets/js/login-perfect-scrollbar.min.js')?>"></script>

  <!--plugins.js - Some Specific JS codes for Plugin Settings-->
  <script type="text/javascript" src="<?= base_url('assets/js/login-plugins.js')?>"></script>
<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>