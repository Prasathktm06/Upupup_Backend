 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>Trainer Advertisement 2</h1>


</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Edit</h3>

</div>

<div class="box-body">
<div class="nav-tabs-custom">

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("advertisement/edit_venue_adv/$advertisement->id")?>" method="post">
                        <div class="box-body">
                                <div class="form-group">
                                    <label for="trainer" class="col-sm-2 control-label">City</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="city" id="city" class="form-control"  placeholder="City" value="<?=$advertisement->location?>" required="required">
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="sports" class="col-sm-2 control-label">Profile Image </label>
                                    <div class="col-sm-6">
                                        <input type='file' onchange="readURL(this);" name="file" accept="image/*"/  id="profile_image">
                                        <img id="blah"   alt="image" src="<?php echo($advertisement->image)?>" widht="100" height="100" accept="image/*"/>
                                    </div>
                                </div>
                                <h6 class="" style="margin-left: 105px;margin-top: -9px">
                                Max size: 5mb <br>
                                Extensions: jpg,png,jpeg <br>
                                </h6>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status </label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                            <option <?php if($advertisement->status==1)echo "selected";  ?> value="1">Active</option>
                                            <option <?php if($advertisement->status==0)echo "selected"; ?> value="0">Inactive</option>
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

</div>

</div>

</div>
</div>


</section>

</div>
<script type="text/javascript">
  var _URL = window.URL || window.webkitURL;
$("[name=file]").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
         // console.log(this.width);
            // if((this.width!=900 || this.height!=450) && (this.width!=350 || this.height!=175)&& (this.width!=500 && this.height!=250)){
            //  $("[name=file]").val('');
            //  var msg=" Wrong Resolution !";
            //   new PNotify({
            //           title: 'Failed',
            //           text: msg,
            //           type: 'error',
            //           delay : 2000
            //       });
            // }
        };
        img.src = _URL.createObjectURL(file);
    }
});
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
