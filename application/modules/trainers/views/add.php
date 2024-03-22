 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>Trainers & Coaches</h1>


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

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("trainers/trainers_add/")?>" method="post">
<div class="box-body">

<div class="form-group">
    <label for="trainer" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-6">
        <input type="text" name="trainer" id="trainer" class="form-control"  placeholder="Trainer Name" value="" required="required">
    </div>
</div>
<div class="form-group">
    <label for="age" class="col-sm-2 control-label">Age</label>
    <div class="col-sm-6">
        <input type="text" name="age"  class="form-control"  placeholder="Trainer Age" value="" >
    </div>
</div>

                        <div class="form-group">
                             <label class="col-sm-2 control-label">City</label>
                             <div class="col-sm-6">
                                  <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                    <option selected disabled>Select a City</option>
                                      <?php foreach ($locations as $val):?>
                                          <option value="<?= $val->id?>"><?= $val->location?></option>
                                      <?php endforeach;?>
                                  </select>
                             </div>
                        </div>

                        <div class="form-group">
                              <label class="col-sm-2 control-label">Area</label>
                              <div class="col-sm-6">
                                  <select id="area" class="form-control select2" name="area" style="width: 100%;">
                                  </select>
                              </div>
                        </div>
<div class="form-group">
    <label for="address" class="col-sm-2 control-label">Address</label>
    <div class="col-sm-6">
      <textarea  name="address" class="form-control" rows="3" placeholder="Address ..." ></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Phone Number</label>
    <div class="col-sm-6">
      <div class="input-group">
          <div class="input-group-addon">
              <i class="fa fa-phone"></i>
          </div>
          <input value="" name="phone"  id="phone" class="form-control" type="text" data-inputmask="'mask': ['9999999999999 ', '+9999999999999']" data-mask >
      </div>
    </div>
</div>
<div class="form-group">
    <label for="venue" class="col-sm-2 control-label">Experience</label>
    <div class="col-sm-6">
        <textarea  name="experience" class="form-control" rows="3" placeholder="Trainer Experience" ></textarea>
    </div>
</div>
<div class="form-group">
    <label for="address" class="col-sm-2 control-label">Certifications</label>
    <div class="col-sm-6">
      <textarea  name="certifications" class="form-control" rows="3" placeholder="Certifications " ></textarea>
    </div>
</div>
<div class="form-group">
    <label for="venue" class="col-sm-2 control-label">Availability</label>
    <div class="col-sm-6">
        <input type="text" name="availability"  class="form-control"  placeholder="Availability" value="" >
    </div>
</div>
<div class="form-group">
    <label for="trainer" class="col-sm-2 control-label">Speciality</label>
    <div class="col-sm-6">
        <input type="text" name="speciality"  class="form-control"  placeholder="Speciality" value="" >
    </div>
</div>
<div class="form-group" >
      <label for="sports" class="col-sm-2 control-label">Profile Image </label>
      <div class="col-sm-6">
          <input type='file' onchange="readURL(this);" name="file" accept="image/*"/>
          <img id="blah"   alt="image" />

      </div>
</div>
<br>
<h6 class="" style="margin-left: 105px;margin-top: -9px">
 Resolution: 200px*200px <br>
 max size: 5mb <br>
 extension: jpg,png,jpeg <br>
  
</h6>
<div class="form-group">
    <label for="address" class="col-sm-2 control-label">Achievements</label>
    <div class="col-sm-6">
      <textarea  name="achievements" class="form-control" rows="3" placeholder="Trainer Achievements " ></textarea>
    </div>
</div>

</div>



<div class="box-footer">
<a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
<a href="" class="btn btn-warning pull-right" id="add-trainer-tab_2" data-toggle="tab">Next</a>
</div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">

<div class="box-body">
 <div class="form-group">
                <label class="col-sm-2 control-label">Sports</label>
                <div class="col-sm-6">
                <select name="sports[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Sport" style="width: 100%;" required="">
                  <?php foreach ($sports as $val):?>
                  <option  value="<?=$val->id?>"><?=$val->sports?></option>
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
<script type="text/javascript">
  var _URL = window.URL || window.webkitURL;

   function readURL(input) {
        // console.log(input.files);
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(250)
                        .height(250);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    $("[name=file]").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
          console.log(this.width);
            if((this.width!=200 || this.height!=200) ){
             $("[name=file]").val('');
             var msg=" Wrong Resolution !";
              new PNotify({
                      title: 'Failed',
                      text: msg,
                      type: 'error',
                      delay : 2000
                  });
            }
        };
        img.src = _URL.createObjectURL(file);
    }
});
</script>
