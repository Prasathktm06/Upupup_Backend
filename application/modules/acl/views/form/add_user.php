<script type="text/javascript">

     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

     function readURL1(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blah1')
                   .attr('src', e.target.result)
                   .width(200)
                   .height(200);
           };

           reader.readAsDataURL(input.files[0]);
       }
     }

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#role').change(function(){
            $("#court option").remove();
            var url_new="acl/user/court_manager_test";
            var role = document.getElementById('role').value;
            //alert(role);
            var base_url="<?php echo base_url(); ?>" ;

            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {role: role},
                success:function (res) {
                    if (res == 'court_manager') {
                        $('.court_div').show();
                   }else{
                        $('.court_div').hide();
                   }
                },
            });
        });
    });

</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>User</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Add</h3>
                    </div>

                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <?php if($venue!==""){ ?>
                                <li class="active"><a href="#tab_1" data-toggle="tab">New</a></li>
                                <li class=""><a href="#tab_2" data-toggle="tab">Existing</a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <form class="form-horizontal" action="<?php echo base_url('acl/user/add/') ?><?=$venue?>" method="post" enctype='multipart/form-data'>
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
                                                    <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
                                                <div class="col-sm-6">
                                                    <input type="password" name="password" class="form-control" id="inputEmail3" placeholder="Password" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Phone Number</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-phone"></i>
                                                        </div>
                                                        <input value="" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999 ', '+9999999999']" data-mask>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Select Role</label>
                                                <div class="col-sm-6">
                                                    <select class="form-control select2" name="roles" id="role" required="">
                                                        <option value="">Choose Role..</option>
                                                        <?php foreach($roles as $role): ?>
                                                            <option value="<?php echo $role->role_id; ?>"><?php echo $role->name;?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php if($venue){?>
                                            <div class="form-group court_div" style="display: none">
                                                <label class="col-sm-2 control-label">Select Court</label>
                                                <div class="col-sm-6">
                                                    <select class="form-control select2" name="court[]" multiple="" style="width: 100%;">
                                                        <option value="">Choose Court..</option>
                                                        <?php foreach($court as $item): ?>
                                                            <option value="<?=$item['court_id']?>"><?=$item['court']?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group" >
                                                <label for="sports " class="col-sm-2 control-label">Image Upload </label>
                                                <div class="col-sm-6">
                                                    <input type='file' onchange="readURL(this);" name="file" accept="image/*" />
                                                    <img id="blah"   />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                            <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <form class="form-horizontal" action="<?php echo base_url('acl/user/add_venue_user/') ?><?=$venue?>" method="post" >
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Users</label>
                                                <div class="col-sm-6">
                                                    <select name="user" class="form-control select2"  data-placeholder="Select a User" style="width: 100%;">
                                                        <?php foreach ($users as $key=> $val):?>
                                                        <option  value="<?=$val->user_id?>"><?=$val->user?>,   <?=$val->role?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <input type="hidden" name="venue" value="<?=$venue?>">
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
                    </div>
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
<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>
