<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Role
      
      </h1>
     
    </section>
       <?php if(validation_errors()): ?> 
          <div class="bg-danger text-danger">
          <?= validation_errors(); ?>
          </div>
         <?php endif; ?>
  <?php //print_r($perm_name);exit;?>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Edit</h3>
            </div>
            
            <form class="form-horizontal" action="<?php echo base_url("acl/role/edit/$role->role_id") ?>" method="post">
            <div class="box-body">
           		 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>	  	
                  <div class="col-sm-6">
                    <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="<?= set_value('name') ? set_value('name') : $role->name; ?>">
                  </div>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Slug</label>
                  <div class="col-sm-6">
                    <input type="text" name="slug" class="form-control" id="inputEmail3" placeholder="Slug" value="<?= set_value('slug') ? set_value('slug') : $role->slug; ?>" readonly>
                  </div>
                </div> 
             <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-6">
                    <textarea  name="description" class="form-control" id="inputEmail3" placeholder="Description"><?= set_value('description') ? set_value('description') : $role->description; ?></textarea>
                  </div>
             </div> 
              
              <div class="form-group">
                <label class="col-sm-2 control-label">Permissions <span class="required">*</span></label>
                <div class="col-sm-6 well">
                <div>
         <?php foreach ($group as $key=>$val){?>
  		<p><strong><?= $key?>:</strong></p>
  		<?php foreach ($val as $key2=>$val2){?>
  		<?php $selected = explode(',', $role->perms);?>
  		<input <?php if(in_array($val2->perm_id, $selected)) echo "checked"; ?> type="checkbox" class="minimal-red"  value="<?= $val2->perm_id?>" name="perms[]">
        <?= $val2->name?>
        <?php }}?>
         
                </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
                <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-warning pull-right">Submit</button>
              </div>
            </form>
            </div>
            </div>
            </div>
            </section>
            </div>
            
<script>
$(function() {
	var $autofills = $('.autofill');
	
	$autofills.each(function(key, value) {
		var source = $(value).attr('data-source'),
		output = $(value).attr('data-output');
		
		$('input[name=' + source + ']').keyup(function() {
			$('input[name=' + output + ']').val(this.value.toLowerCase().replace(/\s/g, '_').replace(/[^a-z0-9_\-]/g, ''));
			console.log(this.value.toLowerCase().replace(/\s/g, '_').replace(/[^a-z0-9_\-]/g, ''));
		});
	});
});
</script>