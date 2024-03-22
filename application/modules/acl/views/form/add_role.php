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
	
 
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add</h3>
            </div>
            <!-- /.box-header -->
              <form class="form-horizontal" href="<?php echo base_url('acl/role/add') ?>" method="post">
            <div class="box-body">
           		 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
    
                  <div class="col-sm-6">
                    <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="<?=set_value('name');?>">
                  </div>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Slug</label>

                  <div class="col-sm-6">
                    <input type="text" name="slug" class="form-control" id="inputEmail3" placeholder="Slug" value="<?=set_value('slug');?>">
                  </div>
                </div> 
             <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Description</label>

                  <div class="col-sm-6">
                    <input type="text" name="description" class="form-control" id="inputEmail3" placeholder="Description" value="">
                  </div>
                </div>
                  <div class="box-body">
               <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-6">Court Manager
                     <input type="radio" class="flat-red" name="venue_user" value="2">
                      <div class="col-sm-6">Venue Manager

                     <input type="radio" class="flat-red" name="venue_user" value="1">

                  </div>
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