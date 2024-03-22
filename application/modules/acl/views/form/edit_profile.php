
  <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Profile</h3>
              </div>
             
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Profile</h2>
             
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     
	
<form action="<?php echo base_url() ?>acl/profile" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-validation form-label-left">

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="text" name="name" required="required" class="form-control col-md-7 col-xs-12" value="<?= $profile[0]->name?>" >
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="email"  name="email" required="required" class="form-control col-md-7 col-xs-12"  value="<?= $profile[0]->email?>" >
    </div>
  </div>
 	
	 
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <button type="submit" class="btn btn-success">Submit</button>
      <?= anchor('acl/', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
  </div>

                    </form>
                  </div>
                </div>
              </div>

       <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edit Password</h2>
             
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

  <?php if($this->session->flashdata('error')): ?> 
  <div class="bg-danger text-danger text-center">
  <?= $this->session->flashdata('error'); ?>
  </div>     
  <?php endif; ?>
    
          
  
<form action="<?php echo base_url() ?>acl/edit_password/<?php echo $profile[0]->user_id ?>  " method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

 
  <div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="email">New Password<span class="required" >*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="password" id="pass" name="new-password" required="required" class="form-control col-md-7 col-xs-12"  value="" >
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="email">Confirm Password<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="password" name="confirm-password" required="required" class="form-control col-md-7 col-xs-12"  value="" data-parsley-equalto="#pass">
    </div>
  </div>
     
   
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <button type="submit" class="btn btn-success">Submit</button>
      <?= anchor('acl/', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
  </div>

            </form>
          </div>
        </div>
      </div>

        </div>
      </div>
    </div>