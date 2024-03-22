<style type="text/css">
     body {
        padding: 20px 0px 0;
    }
    h1.title {
        font-family: 'Roboto', sans-serif;
        font-weight: 900;
    }





    #main-container {
        margin-top: -2%;
    }
    div.gallery {
    margin: 5px;
    border: 1px solid #ccc;
    float: left;
    width: 180px;
    height: auto;
    margin-top: 24px;
    margin-left: 24px;
    -moz-box-shadow:  10px 10px 5px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    -webkit-box-shadow:  10px 10px 5px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
     box-shadow:  10px 10px 5px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

   /*  -webkit-animation: shake 0.1s ease-in-out 0.1s infinite alternate;*/
}

div.gallery:hover {
    border: 1px solid #777;


}
div.gallery img {
    width: 100%;
    height: 100px;

}

div.desc {
    padding: 15px;
    text-align: center;
}
.close-btn:hover{
   -ms-transform: rotate(50deg); /* IE 9 */
    -webkit-transform: rotate(50deg); /* Chrome, Safari, Opera */
    transform: rotate(50deg);
    cursor: pointer;
}


.gallery-a:hover{
cursor: default;
}
@-webkit-keyframes shake {
 from {
    -webkit-transform: rotate(2deg);
 }
 to {
   -webkit-transform-origin:center center;
   -webkit-transform: rotate(-2deg);
 }

}



</style>




<div class="content-wrapper">
     <section class="content-header">
          <h1>Shops</h1>
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
                                   <ul class="nav nav-tabs" >
                                        <li <?php if(!isset($_GET['sports']) && !isset($_GET['offer'])) echo "class=active"  ?>><a href="#tab_1" data-toggle="tab">Shop</a></li>
                                        <li <?php if(isset($_GET['sports']) && !isset($_GET['offer'])) echo "class=active" ?>><a href="#tab_2" data-toggle="tab">Sports</a></li>
                                        <li <?php if(!isset($_GET['sports']) && isset($_GET['offer'])) echo "class=active" ?>><a href="#tab_3" data-toggle="tab">Offer</a></li>
                                   </ul>
                                   <div class="tab-content">
                                        <div <?php if(!isset($_GET['sports']) && !isset($_GET['offer'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_1">
                                             <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("shop/edit/$shop->id")?>" method="post" id="venue_form">

                                                  <div class="box-body">
                                                      <div class="form-group">
                                                          <label for="trainer" class="col-sm-2 control-label">Shop Name</label>
                                                          <div class="col-sm-6">
                                                              <input type="text" name="name"  class="form-control"  placeholder="Shop Name" value="<?=$shop->name?>" required="required">
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label class="col-sm-2 control-label">Phone Number</label>
                                                          <div class="col-sm-6">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-phone"></i>
                                                                </div>
                                                                <input value="<?=$shop->phone?>" name="phone" type="number" class="form-control" required="required">
                                                            </div>
                                                          </div>
                                                      </div>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">City</label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                                                      <option selected disabled>Select a city</option>
                                                                      <?php foreach ($locations as $val):?>
                                                                      <option <?php if($shop->location_id==$val->id) echo "selected"?> value="<?= $val->id?>"><?= $val->location?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Area</label>
                                                            <div class="col-sm-6">
                                                                 <select id="area" class="form-control select2" name="area" style="width: 100%;">
                                                                      <option selected disabled>Select a location</option>
                                                                      <?php foreach ($area as $val):?>
                                                                      <option <?php if($shop->area_id==$val->id) echo "selected"?> value="<?= $val->id?>"><?= $val->area?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>
                                                        <div class="form-group">
                                                              <label for="address" class="col-sm-2 control-label">Address</label>
                                                              <div class="col-sm-6">
                                                                    <textarea  name="address" class="form-control" rows="3" placeholder="Address ..." ><?= $shop->address;?></textarea>
                                                              </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="venue" class="col-sm-2 control-label">Timing</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="timing"  class="form-control"  placeholder="Shop working hours" value="<?=$shop->timing?>" required="required">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                              <label for="major_brands" class="col-sm-2 control-label">Major Brands</label>
                                                              <div class="col-sm-6">
                                                                    <textarea  name="major_brands" class="form-control" rows="3" placeholder="Major Brands " ><?= $shop->major_brands;?></textarea>
                                                              </div>
                                                        </div>
                                                        <div class="form-group">
                                                              <label for="brand_shop" class="col-sm-2 control-label">Brand Shop</label>
                                                              <div class="col-sm-6">
                                                                    <textarea  name="brand_shop" class="form-control" rows="3" placeholder="Brand Shop" ><?= $shop->brand_shop;?></textarea>
                                                              </div>
                                                        </div>
                                                        <div class="form-group">
                                                              <label for="lat" class="col-sm-2 control-label">Latitude</label>
                                                              <div class="col-sm-6">
                                                                  <input value="<?=$shop->lat?>" type="number" name="lat"  class="form-control"  placeholder="Latitude" value="1" step="any">
                                                              </div>
                                                        </div>

                                                        <div class="form-group">
                                                              <label for="lon" class="col-sm-2 control-label">Longitude</label>
                                                              <div class="col-sm-6">
                                                                  <input value="<?=$shop->lon?>" type="number" name="lon"  class="form-control"  placeholder="Longitude" value="1" step="any">
                                                              </div>
                                                        </div>
                                                       <div class="form-group" >
                                                            <label for="sports" class="col-sm-2 control-label">Shop Image </label>
                                                            <div class="col-sm-6">
                                                                 <input type='file' onchange="readURL(this);" name="file" accept="image/*"/  id="profile_image">
                                                                 <?php if ($shop->image) {
                                                                   $image=$shop->image;
                                                                 }

                                                                 ?>
                                                                 <img id="blah"   alt="image" src="<?=$image?>" widht="100" height="100" accept="image/*"/>
                                                            </div>
                                                       </div>
                                                       <h6 class="" style="margin-left: 105px;margin-top: -9px">
                                                       Resolution: 900px*450px <br>
                                                       Max size: 5mb <br>
                                                       Extensions: jpg,png,jpeg <br>

                                                       </h6>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Status </label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="status"  style="width: 100%;" required>
                                                                      <option <?php if($shop->status==1)echo "selected";  ?> value="1">Active</option>
                                                                      <option <?php if($shop->status==0)echo "selected"; ?> value="0">Inactive</option>
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
                                        <div <?php if(isset($_GET['sports']) && !isset($_GET['offer'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?>  class="tab-pane" id="tab_2">
                                             <form class="form-horizontal" action="<?=base_url("shop/add_sports/$shop->id")?>" method="post">
                                                  <div class="box-body">
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Sports</label>
                                                            <div class="col-sm-6">
                                                                 <select name="sports[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Sports" style="width: 100%;">
                                                                      <?php foreach ($sports as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $shop_sports->sports_id))) echo "selected"; ?> value="<?=$val->id?>"><?=$val->sports?></option>
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


                                        <div <?php if(!isset($_GET['sports']) && isset($_GET['offer'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_3">
                                             <div class="box-body">
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_shop')) { ?>
                                                  <a href="<?php echo base_url("shop/add_offer/$shop->id") ?>" class="btn btn-sm pull-right btn-warning">Add offer<i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="shop_id" value="<?= $shop->id?>">
                                                  <br><br>
                                                  <table id="example1" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                 <th class="text-center" style="width: 5%">#</th>
                                                                 <th class="text-center">Name</th>
                                                                 <th class="text-center">Percentage</th>
                                                                 <th class="text-center">Start Date</th>
                                                                 <th class="text-center">End Date</th>
                                                                 <th class="text-center">Status</th>
                                                                 <th class="text-center">Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                         <?php $i=1; foreach ($shop_offer as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value->name?></td>
                                            <td class="text-center"><?=$value->amount?></td>
                                            <td class="text-center"><?=date( ' d M Y ',strtotime($value->start_date))?></td>
                                            <td class="text-center"> <?=date( ' d M Y ',strtotime($value->end_date))?></td>
                                            <td class="text-center">
                                                  <?php if($value->status==1){?>
                                                      <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) { ?>
                                                            <a href="<?=base_url()?>shop/offer_status/<?=$value->id?>/<?=$value->status?>/<?=$value->shop_id?>" type="button" class="btn btn-success">Active</i></a>
                                                      <?php }?>
                                                  <?php } else{?>
                                                      <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) { ?>   
                                                            <a href="<?=base_url()?>shop/offer_status/<?=$value->id?>/<?=$value->status?>/<?=$value->shop_id?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                      <?php }?>
                                                  <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) { ?>
                                                      <a href="<?=base_url()?>shop/offer_edit/<?=$value->id?>/<?=$value->shop_id?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_shop')) { ?>
                                                      <a href="<?=base_url()?>shop/offer_delete/<?=$value->id?>/<?=$value->shop_id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                <?php } ?>
                                            </td>
    
                                        </tr>
                                        <?php $i++;} ?>
                                                       </tbody>

                                                  </table>
                                             </div>
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
 var x=1;
$( document ).ready(function() {

  $('#datepicker8').datepicker({
minDate: '0',
 onSelect: function(dateStr) 
        {         
             var start_date= $("#datepicker9").val(dateStr);
            $('#datepicker9').datepicker('option', 'minDate', dateStr);
        }

});
 $('#datepicker9').datepicker({
minDate: '0',
});


});

</script>
 <script>
  $(function () {
    
    $('#example1').DataTable({
      
    "scrollX" : true,
        "scrollCollapse" : true  
    });
$('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true 
    });
$('#example3').DataTable({

    "scrollX" : true,
        "scrollCollapse" : true  
    });
$('#example5').DataTable({

    "scrollX" : true,
        "scrollCollapse" : true  
    });
$('#example6').DataTable({

    "scrollX" : true,
        "scrollCollapse" : true  
    });
$('#example7').DataTable({
      
     "scrollX" : true,
        "scrollCollapse" : true 
    });
$('#example8').DataTable({
      
     "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
      "scrollCollapse" : true, 
    });
  });


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
                        .width(300)
                        .height(150);
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
            if((this.width!=900 || this.height!=450) ){
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




