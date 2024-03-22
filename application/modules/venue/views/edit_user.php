<script type="text/javascript">

     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
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
                   .width(100)
                   .height(100);
           };

           reader.readAsDataURL(input.files[0]);
       }
     }

</script>

<script type="text/javascript">
    /*$(document).ready(function(){
        $('#role').change(function(){
            var venue_users = $('#role option:selected').attr('data-value');
            if (venue_users == 1) {
                $('.venue_div').show();
                $('.court_div').hide();
            }else if (venue_users == 2){
                $('.venue_div').show();
                $('.court_div').show();
            }
        });
    });*/
$(document).ready(function(){
        var sel = $("#role");
        var venue_users = $('#role option:selected').attr('data-value');
        if (venue_users == 1) {
                $('.venue_div').show();
                $('.court_div').hide();
            }else if (venue_users == 2){
                $('.venue_div').show();
                $('.court_div').show();
            }
    sel.change(function(data){
        var venue_users = $('#role option:selected').attr('data-value');
        if (venue_users == 1) {
                $('.venue_div').show();
                $('.court_div').hide();
            }else if (venue_users == 2){
                $('.venue_div').show();
                $('.court_div').show();
            }
    });
});
</script>

<script type="text/javascript">
    /*$(document).ready(function(){
        $('#venue').change(function(){
            $("#court option").remove();
            var url_new="venue/court_list";
            var venue = document.getElementById('venue').value;
            var user_id = document.getElementById('user_id').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;

            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {venue: venue,user_id:user_id},
                success:function (res) {
                    $('#court').append($('<option></option>'));
                    $.each(res['court'],function(element,val) {
                        $('#court').append($('<option>', {
                            value: val.id,
                            text : val.court
                        }));
                    });
                },
            });
        });
    });*/
    $(document).ready(function(){
        var sel = $("#venue");
        $("#court option").remove();
            var url_new="venue/court_list";
            var venue = document.getElementById('venue').value;
            var user_id = document.getElementById('user_id').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;

            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {venue: venue,user_id:user_id},
                success:function (res) {
                    $('#court').append($('<option></option>'));
                    //console.log(res);
                    $.each(res['court'],function(element,val) {
                           
                        $('#court').append($('<option>', {
                                    value: val.id,
                                    text : val.court
                                }));

                    });
                },
            });

    sel.change(function(data){
        $("#court option").remove();
            var url_new="venue/court_list";
            var venue = document.getElementById('venue').value;
            var user_id = document.getElementById('user_id').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;

            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {venue: venue,user_id:user_id},
                success:function (res) {
                    $('#court').append($('<option></option>'));
                    $.each(res['court'],function(element,val) {
                        /*if(jQuery.inArray(val.id, res['court_assigned']) !== -1){
                            option.setAttribute('selected', true);
                        }else{
                            option.setAttribute('selected', false);
                        }*/
                        $('#court').append($('<option>', {
                            value: val.id,
                            text : val.court
                        }));
                    });
                },
            });
        });
    });

   /* $(document).ready(function(){
        $('#venue').change(function(){
            $("#court option").remove();
            var url_new="venue/court_list";
            var venue = document.getElementById('venue').value;
            var user_id = document.getElementById('user_id').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;

            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {venue: venue,user_id:user_id},
                success:function (res) {
                    $('#court').append($('<option></option>'));
                    $.each(res['court'],function(element,val) {
                        if(jQuery.inArray(val.id, res['court_assigned']) !== -1){
                            option.setAttribute('selected', true);
                        }else{
                            option.setAttribute('selected', false);
                        }
                        $('#court').append($('<option>', {
                            value: val.id,
                            text : val.court
                        }));
                    });
                },
            });
        });
    });
*/
</script>
<!-- ///////////////////////////////////////////////////////////////////////////////////////////// -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>User</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit</h3>
                    </div>

                    <div class="box-body">
                        <?php foreach ($profile as $key => $value) { ?>
                        <form class="form-horizontal" action="<?=base_url()?>venue/edit_manager/<?=$value->user_id?>" method="post" enctype='multipart/form-data'>
                            <input type="hidden" name="user_id" id="user_id" value="<?=$value->user_id?>">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="Name" value="<?=$value->name?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-6">
                                        <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" value="<?=$value->email?>">
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
                                            <input value="<?=$value->phone?>" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999 ', '+9999999999']" data-mask>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Select Role</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" name="roles" id="role">
                                            <option value="">Choose Role..</option>
                                            <?php foreach($roles as $role): ?>
                                                <option value="<?=$role->role_id?>" <?php echo isset($role_id) && $role_id== $role->role_id  ? 'selected' :'' ; ?> data-value="<?=$role->venue_users?>"><?=$role->name?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group venue_div" style="display: none">
                                    <label class="col-sm-2 control-label">Select Venue</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" name="venue" style="width: 100%;" id="venue">
                                            <option value="">Choose Venue..</option>
                                            <?php foreach($venue as $venue_data): ?>
                                                <!-- <option value="<?=$venue_data['venue_id']?>"><?=$venue_data['venue']?></option> -->
                                                <option value="<?=$venue_data['venue_id']?>" <?php echo isset($venue_id) && $venue_id== $venue_data['venue_id']  ? 'selected' :'' ; ?>><?=$venue_data['venue']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group court_div" style="display: none">
                                    <label class="col-sm-2 control-label">Select Court</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" name="court[]" multiple="" style="width: 100%;" id="court">
                                            <option value="">Choose Court..</option>
                                            <?php foreach($court as $item): ?>
                                                <!-- <option value="<?=$item['court_id']?>"><?=$item['court']?></option> -->
                                                <option value="<?=$item['court_id']?>" <?=in_array($item['court_id'], $court_assigned) ? 'selected' : ''?>><?=$item['court']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <label for="sports " class="col-sm-2 control-label">Image Upload </label>
                                    <div class="col-sm-6">
                                        <input type='file' onchange="readURL(this);" name="file" accept="image/*"/>
                                        <img id="blah"  src="<?=$value->image?>"  width="100" height="100"/>
                                        <input type="hidden" name="img_old" value="<?=$value->image?>">
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-warning pull-right">Submit</button>
                            </div>
                        </form>
                        <?php } ?>
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
