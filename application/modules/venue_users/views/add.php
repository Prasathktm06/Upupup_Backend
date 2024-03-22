<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User
      
      </h1>
    
    </section>

 
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add</h3>
            </div>
            <!-- /.box-header -->
              <form class="form-horizontal" action="<?php echo base_url('acl/user/add') ?>" method="post">
            <div class="box-body">
               <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>

                  <div class="col-sm-6">
                    <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="">
                  </div>
                </div> 
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-6">
                    <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Name" value="">
                  </div>
                </div> 
             <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Password</label>

                  <div class="col-sm-6">
                    <input type="password" name="password" class="form-control" id="inputEmail3" placeholder="Password" value="">
                  </div>
                </div> 
                 <div class="form-group">
              
                  <label class="col-sm-2 control-label">Select</label>
                   <div class="col-sm-6">
                  <select class="form-control" name="roles">
                  <option value="">Choose Role..</option>
            <?php foreach($roles as $role): ?>
            <option value="<?php echo $role->role_id; ?>"><?php echo $role->name;?></option>
            <?php endforeach;?>
                  </select>
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