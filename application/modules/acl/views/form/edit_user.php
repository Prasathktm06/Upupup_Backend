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
        var sel = $("#role");
        $("#court option").remove();
        var url_new="acl/user/court_manager_test";
        var role1 = document.getElementById('role').value;
        //alert(role);
        var base_url="<?php echo base_url(); ?>" ;

        $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {role: role1},
            success:function (res) {
                if (res == 'court_manager') {
                    $('.court_div').show();
               }else{
                    $('.court_div').hide();
               }
            },
        });
    sel.change(function(data){
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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit</h3>
                    </div>
                    <form class="form-horizontal" href="<?php echo base_url() ?>acl/user/edit/<?php echo $profile[0]->user_id ?>" method="post" enctype='multipart/form-data'>
                        <div class="box-body">
               		        <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-6">
                                    <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="<?= $profile[0]->name; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" value="<?= $profile[0]->email;?>">
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
                                        <input value="<?=$profile[0]->phone?>" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999 ', '+9999999999']" data-mask>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Select Role</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="roles" id="role" readonly >
                                        <option value="<?= $user_roles[0]->role_id;?>"><?=$user_roles[0]->name?></option>
           					            <!--<?php foreach($roles as $role): ?>
            				                <?php if($user_roles[0]->name!=$role->name) : ?>
           				                        <option value="<?php echo $role->role_id; ?>"><?php echo $role->name;?></option>
            				                <?php endif ;?>
            				            <?php endforeach;?>-->
                                    </select>
                                </div>
                            </div>
                            
                            <?php if($venue_id){?>
                            <div class="form-group court_div" style="display: none">
                                <label class="col-sm-2 control-label">Select Court</label>
                                <div class="col-sm-6">
                                    <select class="form-control select2" name="court[]" multiple="" style="width: 100%;">
                                        <option value="">Choose Court..</option>
                                        <?php foreach($court as $item): ?>
                                            <!-- <option value="<?=$item['court_id']?>"><?=$item['court']?></option> -->
                                            <option value="<?=$item['court_id']?>" <?=in_array($item['court_id'], $court_assigned) ? 'selected' : ''?>><?=$item['court']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-group" >
                                <label for="sports " class="col-sm-2 control-label">Image Upload </label>
                                <div class="col-sm-6">
                                    <input type='file' onchange="readURL(this);" name="file" accept="image/*" />
                                    <?php if ($profile[0]->image!='0') {
                                        $image_new=$profile[0]->image;
                                    }else{
                                        $image_new=base_url('pics/No_image.svg');
                                    }?>
                                    <img id="blah"   alt="image" src= "<?=$image_new?>" width="100" height="100"/>
                                    <input type="hidden" name="img_old" value="<?=$profile[0]->image?>">
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
