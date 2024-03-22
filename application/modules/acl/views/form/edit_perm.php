<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permission
      
      </h1>
   
    </section>

 
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Edit</h3>
            </div>
            <!-- /.box-header -->
              <form class="form-horizontal" href="<?php echo base_url("acl/perm/edit/$perm->perm_id") ?>" method="post">
            <div class="box-body">
           		 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>

                  <div class="col-sm-6">
                    <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="<?= set_value('name') ? set_value('name') : $perm->name; ?>">
                  </div>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Slug</label>

                  <div class="col-sm-6">
                    <input readonly type="text" name="slug" class="form-control" id="inputEmail3" placeholder="Name" value="<?= set_value('slug') ? set_value('slug') : $perm->slug; ?>">
                  </div>
                </div> 
             <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Description</label>

                  <div class="col-sm-6">
                    <textarea name="description" class="form-control" id="inputEmail3" placeholder="" value=""><?= set_value('description') ? set_value('description') : $perm->description; ?></textarea>
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