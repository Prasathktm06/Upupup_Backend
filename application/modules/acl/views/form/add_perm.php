<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permission
      
      </h1>
     
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-9">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add</h3>
               <a href="<?php echo base_url('acl/perm/add_perm_name') ?>" class="btn btn-sm pull-right btn-warning">Add Permission Name <i class="fa fa-plus"></i></a>
            </div>
           <form class="form-horizontal" action="<?php echo base_url('acl/perm/add') ?>" method="post">

            <div class="box-body">
            	<div class="form-group">
  				   <label class="col-sm-2 control-label">Permission Name</label>
    				<div class="col-sm-6">
     				 <select class="form-control select2" name="perm_name" id="perm_name" style="width: 100%;">
 					 
      					<?php foreach ($perm_name as $val):?>
      					<option value="<?= $val->id?>"><?= $val->name?></option>
      					<?php endforeach;?>
     				</select>	
     				</div>    				
     				<a class="btn btn-danger btn-sm delete" id="delete_perm_name"><i class="fa fa-trash"></i></a>  				
				</div>
           		 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-6">
                    <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="<?=set_value('name');?>">
                  </div>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Slug</label>
                  <div class="col-sm-6">
                    <input type="text" name="slug" class="form-control" id="inputEmail3" placeholder="Name" value="<?=set_value('slug');?>">
                  </div>
                </div>                
             <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-6">
                    <textarea name="description" class="form-control" id="inputEmail3" placeholder="" value=""><?=set_value('description');?></textarea>
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
         <div class="col-xs-3">
<div class="box">
<div class="box-header">
<h3 class="box-title text-warning bg-warning">Warning</h3>

</div>

<div class="box-body">
 <ul>
                  
                    <li>Deleting Permission name can have tremendous effect.</li>
                    
                  </ul>
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