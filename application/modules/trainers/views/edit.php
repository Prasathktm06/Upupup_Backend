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
          <h1>Trainers & Coaches</h1>
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
                                        <li <?php if(!isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas']) && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class=active"  ?>><a href="#tab_1" data-toggle="tab">Trainers & Coaches</a></li>
                                        <li <?php if(isset($_GET['areas']) &&!isset($_GET['gallery']) && !isset($_GET['sports'])  && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class=active" ?>><a href="#tab_3" data-toggle="tab">Areas</a></li>
                                        <li <?php if(isset($_GET['sports']) && !isset($_GET['gallery']) && !isset($_GET['areas']) && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class=active" ?>><a href="#tab_4" data-toggle="tab">Sports</a></li>
                                        <li <?php if(isset($_GET['programs']) && !isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas']) && !isset($_GET['testimonial'])) echo "class=active" ?>><a href="#tab_5" data-toggle="tab">Programs</a></li>
                                        <li <?php if(isset($_GET['testimonial']) && !isset($_GET['programs']) && !isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas'])) echo "class=active" ?>><a href="#tab_6" data-toggle="tab">Testimonials</a></li>
                                        <li <?php if(isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas']) && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class=active" ?>><a href="#tab_2" data-toggle="tab">Training Image</a></li>
                                        
                                   </ul>
                                   <div class="tab-content">
                                        <div <?php if(!isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas']) && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_1">
                                             <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("trainers/trainers_edit/$trainer->id")?>" method="post" id="venue_form">

                                                  <div class="box-body">
                                                      <div class="form-group">
                                                          <label for="trainer" class="col-sm-2 control-label">Name</label>
                                                          <div class="col-sm-6">
                                                              <input type="text" name="trainer"  class="form-control"  placeholder="Trainer Name" value="<?=$trainer->name?>" required="required">
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label for="age" class="col-sm-2 control-label">Age</label>
                                                          <div class="col-sm-6">
                                                              <input type="text" name="age"  class="form-control"  placeholder="Trainer Age" value="<?=$trainer->age?>" required="required">
                                                          </div>
                                                      </div>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">City</label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="city" id="city" style="width: 100%;">
                                                                      <option selected disabled>Select a city</option>
                                                                      <?php foreach ($locations as $val):?>
                                                                      <option <?php if($trainer->location_id==$val->id) echo "selected"?> value="<?= $val->id?>"><?= $val->location?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>
                                                        <div class="form-group">
                                                            <label for="address" class="col-sm-2 control-label">Address</label>
                                                            <div class="col-sm-6">
                                                              <textarea  name="address" class="form-control" rows="3" placeholder="Address ..." ><?=$trainer->address;?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Phone Number</label>
                                                            <div class="col-sm-6">
                                                              <div class="input-group">
                                                                  <div class="input-group-addon">
                                                                      <i class="fa fa-phone"></i>
                                                                  </div>
                                                                  <input value="<?=$trainer->phone;?>" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999999 ', '+9999999999999']" data-mask required="required">
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="venue" class="col-sm-2 control-label">Experience</label>
                                                            <div class="col-sm-6">
                                                                <textarea  name="experience" class="form-control" rows="3" placeholder="Trainer Experience" ><?=$trainer->experience;?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="address" class="col-sm-2 control-label">Certifications</label>
                                                            <div class="col-sm-6">
                                                              <textarea  name="certifications" class="form-control" rows="3" placeholder="Certifications " ><?=$trainer->certifications;?></textarea>
                                                            </div>
                                                        </div>
                                                       <div class="form-group">
                                                            <label for="venue" class="col-sm-2 control-label">Availability</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="availability"  class="form-control"  placeholder="Availability" value="<?=$trainer->availability;?>" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="trainer" class="col-sm-2 control-label">Speciality</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="speciality"  class="form-control"  placeholder="Speciality" value="<?=$trainer->speciality;?>" >
                                                            </div>
                                                        </div>
                                                       <div class="form-group" >
                                                            <label for="sports" class="col-sm-2 control-label">Profile Image </label>
                                                            <div class="col-sm-6">
                                                                 <input type='file' onchange="readURL(this);" name="file" accept="image/*"/  id="profile_image">
                                                                 <?php if ($trainer->profile_image) {
                                                                   $image=$trainer->profile_image;
                                                                 }else{
                                                                  $image=base_url('pics/unnamed.png');
                                                                 }

                                                                 ?>
                                                                 <img id="blah"   alt="image" src="<?=$image?>" widht="100" height="100" accept="image/*"/>
                                                            </div>
                                                       </div>
                                                       <h6 class="" style="margin-left: 105px;margin-top: -9px">
                                                       Resolution: 200px*200px <br>
                                                       Max size: 5mb <br>
                                                       Extensions: jpg,png,jpeg <br>

                                                       </h6>
                                                        <div class="form-group">
                                                            <label for="address" class="col-sm-2 control-label">Achievements</label>
                                                            <div class="col-sm-6">
                                                              <textarea  name="achievements" class="form-control" rows="3" placeholder="Trainer Achievements " ><?=$trainer->achievement;?></textarea>
                                                            </div>
                                                        </div>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Status </label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="status"  style="width: 100%;" required>
                                                                      <option <?php if($trainer->status==1)echo "selected";  ?> value="1">Active</option>
                                                                      <option <?php if($trainer->status==0)echo "selected"; ?> value="0">Inactive</option>
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
                                        <div <?php if(isset($_GET['areas']) && !isset($_GET['gallery']) && !isset($_GET['sports'])  && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?>  class="tab-pane" id="tab_3">
                                             <form class="form-horizontal" action="<?=base_url("trainers/add_areas/$trainer->id")?>" method="post">
                                                  <div class="box-body">
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Areas</label>
                                                            <div class="col-sm-6">
                                                                 <select name="areas[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Areas" style="width: 100%;">
                                                                      <?php foreach ($areas as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $trainer_areas->area_id))) echo "selected"; ?> value="<?=$val->id?>"><?=$val->area?></option>
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
                                        <div <?php if(isset($_GET['sports']) && !isset($_GET['gallery']) && !isset($_GET['areas']) && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?>  class="tab-pane" id="tab_4">
                                             <form class="form-horizontal" action="<?=base_url("trainers/add_sports/$trainer->id")?>" method="post">
                                                  <div class="box-body">
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Sports</label>
                                                            <div class="col-sm-6">
                                                                 <select name="sports[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Sports" style="width: 100%;">
                                                                      <?php foreach ($sports as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $trainer_sports->sports_id))) echo "selected"; ?> value="<?=$val->id?>"><?=$val->sports?></option>
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


                                        <div <?php if(isset($_GET['programs']) && !isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas']) && !isset($_GET['testimonial'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_5">
                                             <div class="box-body">
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_program')) { ?>
                                                  <a href="<?php echo base_url("trainers/programs/$trainer->id") ?>" class="btn btn-sm pull-right btn-warning">Add Program<i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="trainer_id" value="<?= $trainer->id?>">
                                                  <br><br>
                                                  <table id="example1" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                 <th class="text-center" style="width: 5%">#</th>
                                                                 <th class="text-center">Name</th>
                                                                 <th class="text-center">Venue Name</th>
                                                                 <th class="text-center">City</th>
                                                                 <th class="text-center">Fees</th>
                                                                 <th class="text-center">Start Time</th>
                                                                 <th class="text-center">End Time</th>
                                                                 <th class="text-center">Start Date</th>
                                                                 <th class="text-center">End Date</th>
                                                                 <th class="text-center">Description</th>
                                                                 <th class="text-center">Status</th>
                                                                 <th class="text-center">Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                         <?php $i=1; foreach ($programs as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value->name?></td>
                                            <td class="text-center"><?=$value->venue_name?></td>
                                            <td class="text-center"><?=$value->location?></td>
                                            <td class="text-center"><?=$value->fees?></td>
                                            <td class="text-center"> <?=date( ' h:i:s A ',strtotime($value->start_time))?></td>
                                            <td class="text-center"><?=date( ' h:i:s A ',strtotime($value->end_time))?></td>
                                            <td class="text-center"> <?=date( ' d M Y ',strtotime($value->start_date))?></td>
                                            <td class="text-center" ><?=date( ' d M Y ',strtotime($value->end_date))?></td>
                                            <td class="text-center"><?=$value->description?></td>
                                            <td class="text-center">
                                                  <?php if($value->status==1){?>
                                                      <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_program')) { ?>
                                                            <a href="<?=base_url()?>trainers/program_status/<?=$value->id?>/<?=$value->status?>/<?=$value->trainers_id?>" type="button" class="btn btn-success">Active</i></a>
                                                      <?php }?>
                                                  <?php } else{?>
                                                      <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_program')) { ?>   
                                                            <a href="<?=base_url()?>trainers/program_status/<?=$value->id?>/<?=$value->status?>/<?=$value->trainers_id?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                      <?php }?>
                                                  <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_program')) { ?>
                                                      <a href="<?=base_url()?>trainers/program_edit/<?=$value->id?>/<?=$value->trainers_id?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_program')) { ?>
                                                      <a href="<?=base_url()?>trainers/program_delete/<?=$value->id?>/<?=$value->trainers_id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                <?php } ?>
                                            </td>
    
                                        </tr>
                                        <?php $i++;} ?>
                                                       </tbody>

                                                  </table>
                                             </div>
                                        </div>

                                        <div <?php if(isset($_GET['testimonial']) && !isset($_GET['programs']) && !isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_6">
                                             <div class="box-body">
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_trainers')) { ?>
                                                  <a href="<?php echo base_url("trainers/testimonial/$trainer->id") ?>" class="btn btn-sm pull-right btn-warning">Add Testimonial<i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="trainer_id" value="<?= $trainer->id?>">
                                                  <br><br>
                                                  <table id="example2" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                 <th class="text-center" style="width: 5%">#</th>
                                                                 <th class="text-center" style="width: 15%">Name</th>
                                                                 <th class="text-center" style="width: 15%">Content</th>
                                                                 <th class="text-center" style="width: 15%">status</th>
                                                                 <th class="text-center" style="width: 15%">Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                         <?php $i=1; foreach ($testimonial as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center" style="width: 15%"><?=$value->name?></td>
                                            <td class="text-center" style="width: 15%"><?=$value->testimonial?></td>
                                            <td class="text-center" style="width: 15%">
                                                  <?php if($value->status==1){?>
                                                      <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_trainers')) { ?>
                                                            <a href="<?=base_url()?>trainers/testimonial_status/<?=$value->id?>/<?=$value->status?>/<?=$value->trainers_id?>" type="button" class="btn btn-success">Active</i></a>
                                                      <?php }?>
                                                  <?php } else{?>
                                                      <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_trainers')) { ?>   
                                                            <a href="<?=base_url()?>trainers/testimonial_status/<?=$value->id?>/<?=$value->status?>/<?=$value->trainers_id?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                      <?php }?>
                                                  <?php } ?>
                                            </td>
                                            <td class="text-center" style="width: 15%">
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_trainers')) { ?>
                                                      <a href="<?=base_url()?>trainers/testimonial_edit/<?=$value->id?>/<?=$value->trainers_id?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_trainers')) { ?>
                                                      <a href="<?=base_url()?>trainers/testimonial_delete/<?=$value->id?>/<?=$value->trainers_id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                <?php } ?>
                                            </td>
    
                                        </tr>
                                        <?php $i++;} ?>
                                                       </tbody>

                                                  </table>
                                             </div>
                                        </div>
                                        
                                        
                                        <div <?php if (isset($_GET['gallery']) && !isset($_GET['sports']) && !isset($_GET['areas']) && !isset($_GET['programs']) && !isset($_GET['testimonial'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_2" >
                                             <form action="<?=base_url("trainers/add_training_image")?>" method="post" enctype="multipart/form-data">
                                                  <div class="col-sm-1 fileUpload btn btn-info btn-sm pull-right">
                                                      
                                                       <input type="hidden" name="trainer" value="<?=$trainer->id?>" >
                                                       <span>Upload </span>
                                                       <input name="file" type="file" id="exampleInputFile" class="upload" accept="image/*" onchange="this.form.submit(); $('#myModal').modal('show');">
                                                  </div><br>
                                                  <h6 class="pull-right" style="margin-left: 105px;margin-top: -9px">
                                                  Resolution: 300px*200px <br>
                                                  Max size: 5mb <br>
                                                  Extensions: jpg,png,jpeg <br>
                                                
                                                  </h6>
                                             </form> <br><br>
                                             <div class="box-body ">
                                                  <div id="gallery" >

                                                    <?php if(!empty($trainer->training_image)){ ?>
                                                      
                                                       <div class="gallery ">
                                                           <img class="close-btn" src="https://upupup.in/partnerup/pics/icons/close_button_gallery.png" alt="Fjords" style="width: 25px;height: 25px; position: absolute;margin-top:  -12px;margin-left: -9px; " />
                                                            <a  class="gallery-a" target="_blank"  id="<?=$trainer->id?>">
                                                            <img class="picture img-thumbnail" src="<?=$trainer->training_image?>" alt="photo" width="300" height="200">
                                                       </div>
                                                       <?php }?>
                                                  </div>
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
                        .width(200)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }

        }
    $("#profile_image").change(function (e) {
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
                 console.log("Foo Executed!");
            }
            
        };
        img.src = _URL.createObjectURL(file);
    }
});

    $("#exampleInputFile").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
          console.log(this.width);
            if((this.width!=300 || this.height!=200) ){
             $("[name=file]").val('');
             var msg=" Wrong Resolution !";
              new PNotify({
                      title: 'Failed',
                      text: msg,
                      type: 'error',
                      delay : 2000
                  });
                 console.log("Foo Executed!");
            }
            
        };
        img.src = _URL.createObjectURL(file);
    }
});


$(".close-btn").on('click',function(event){

      event.stopPropagation();
      var image=$(this).next().attr('id');
      $(this).parent().remove();

      $.ajax({
        url: "<?=base_url()?>ajax/delete_training_image/"+image,
        context: document.body
        }).done(function() {

        });

   });
</script>

