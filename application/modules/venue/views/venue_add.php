<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Venue

</h1>


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
              <li class="active"><a href="#tab_1" data-toggle="tab">Venue</a></li>



            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("venue/venue_add/")?>" method="post">
<div class="box-body">

<div class="form-group">
<label for="venue" class="col-sm-2 control-label">Venue</label>
<div class="col-sm-6">
<input type="text" name="venue" id="venue" class="form-control"  placeholder="Venue Name" value="" required="required">
</div>
</div>
<div class="bootstrap-timepicker">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Opening</label>
                   <div class="col-sm-6">
                  <div class="input-group ">
                   <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                      </div>
                    <input required value="" type="text" class="form-control timepicker" name="morn" id="morn">

                    </div>
                  </div>
                </div>

              </div>
      <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Closing</label>
                   <div class="col-sm-6">
                  <div class="input-group ">
                  <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                      </div>
                    <input required value="" type="text" class="form-control timepicker" name="even" id="even">

                    </div>
                  </div>
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
      <label class="col-sm-2 control-label">Phone Number</label>
  <div class="col-sm-6">
    <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-phone"></i>
        </div>
     <input value="" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999999 ', '+9999999999999']" data-mask>
    </div>
  </div>
 </div>

   <div class="form-group">
      <label for="address" class="col-sm-2 control-label">Address</label>
      <div class="col-sm-6">
      <textarea id="address" name="address" class="form-control" rows="3" placeholder="Address ..." maxlength="100"></textarea>
     </div>
  </div>
   <div class="form-group">
      <label for="desc" class="col-sm-2 control-label">Description</label>
      <div class="col-sm-6">
      <textarea id="desc" name="desc" class="form-control" rows="3" placeholder="Description ..." maxlength="256"></textarea>
     </div>
  </div>
  <div class="form-group">
  <label for="lat" class="col-sm-2 control-label">Latitude</label>
  <div class="col-sm-6">
  <input value="" type="number" name="lat" id="lat" class="form-control"  placeholder="Latitude" value="1" step="any">
  </div>
  </div>

  <div class="form-group">
  <label for="lon" class="col-sm-2 control-label">Longitude</label>
  <div class="col-sm-6">
  <input value="" type="number" name="lon" id="lon" class="form-control"  placeholder="Longitude" value="1" step="any">
  </div>
  </div>
   <div class="form-group">
  <label for="lon" class="col-sm-2 control-label">Pay at Venue %</label>
  <div class="col-sm-6">
  <input value="" type="number" name="amount" id="" class="form-control"  placeholder="Percentage" value="1" max="100" min="0">
  </div>
  </div>
   <div class="form-group" >
           <label for="sports" class="col-sm-2 control-label">Image Upload </label>
           <div class="col-sm-6">
                <input type='file' onchange="readURL(this);" name="file" accept="image/*"/>
                <img id="blah"   alt="image" />

           </div>
      </div>

<br>
<h6 class="" style="margin-left: 105px;margin-top: -9px">
 max size: 5mb <br>
 extension: jpg,png,jpeg <br>
  
</h6>
<div class="form-group">
   <label for="lat" class="col-sm-2 control-label"></label>
   <div id="map" style="width: 500px; height: 250px;background-color: grey; "></div>
   <script>
         function initMap() {
           var uluru = {lat: 20.5937, lng:78.9629 };
           var map = new google.maps.Map(document.getElementById('map'), {
             zoom: 4,
             center: uluru
           });
           var marker = new google.maps.Marker({
             position: uluru,
             map: map
           });
           google.maps.event.addListener(map, "click", function (e) {


             var latLng = e.latLng;
             marker.setPosition(latLng);
             var lat= latLng.lat();
             var lon =latLng.lng();
             $('#lat').val(lat);
             $('#lon').val(lon);


         });
      }
   </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_UkF5H840Ww7fN581ySV_l0gqIU4cwZ4&callback=initMap">
    </script>
</div>
 <div class="form-group">
  <label for="lon" class="col-sm-2 control-label"></label>
  <div class="col-sm-6 well">

  <strong style="padding-left: 78px;">Booking</strong> <input id="book" type="radio" name="book" value="book" class="flat-red " checked>
  <strong style="padding-left: 177px;">Callback</strong> <input id="call" type="radio" name="book" value="call" class="flat-red " >
  </div>
  </div>
                            <div class="form-group">
     <label class="col-sm-2 control-label">Status </label>
     <div class="col-sm-6">
      <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                 <option  value="0">Inactive</option>
                 <option  value="1">Active</option>
              </select>
     </div>
    </div>
</div>



<div class="box-footer">
<a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
<a href="" class="btn btn-warning pull-right" id="add-venue-tab_2" data-toggle="tab">Next</a>
</div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">

<div class="box-body">
 <div class="form-group">
                <label class="col-sm-2 control-label">Facilities</label>
                <div class="col-sm-6">
                <select name="speciality[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Facility" style="width: 100%;">
                  <?php foreach ($speciality as $val):?>
                  <option  value="<?=$val->id?>"><?=$val->facility?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
</div>
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
