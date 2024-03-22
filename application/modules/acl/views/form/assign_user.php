  <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3></h3>
              </div>

             
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Assign User - <?= $user->name; ?></h2>
             
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                     

<form action="<?php echo base_url() ?>acl/user/assign/<?php echo $user->user_id ?> " method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">


		
		
		<?php if(validation_errors()): ?>
		<div class="alert alert-error">
			<?= validation_errors(); ?>
		</div>
		<?php endif; ?>
		
		<div class="form-group">
     		 <label for="roles[]"class="control-label col-md-3 col-sm-3 col-xs-12">Role <span class="required">*</span></label>
       <div class="col-md-6 col-sm-6 col-xs-12">
     <select name="roles[]"  class="form-control " required>
					<?php foreach($role_list as $role): ?>
					<option value="<?= $role->role_id; ?>" <?= ($role->set) ? 'selected="selected"' : NULL; ?>><?= $role->name; ?></option>
					<?php endforeach; ?>
				</select>

			</div>
		</div>
			
		 <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <button type="submit" class="btn btn-success">Submit</button>
      <?= anchor('acl/user', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
  </div>

	
</form>
</div>
</div>
</div>
</div>
</div>
</div>
