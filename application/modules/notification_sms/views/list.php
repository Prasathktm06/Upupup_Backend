<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript"> 
    $(document).ready(function(){
        $('#city').change(function(){
            $("#area option").remove();
            var url_new="notification_sms/area_list";
            var city = document.getElementById("city").value;
            //console.log(city);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {city: city},
                success:function (res) {
                    $('#area').append($('<option></option>'));
                    $.each(res['area'],function(element,val) {
                        $('#area').append($('<option>', { 
                            value: val.id,
                            text : val.area 
                        }));
                    }); 
                },
            });
        });
    });

    $(document).ready(function(){
        $('#city_o').change(function(){
            $("#area_o option").remove();
            var url_new="notification_sms/area_list";
            var city = document.getElementById("city_o").value;
            //console.log(city);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {city: city},
                success:function (res) {
                    $('#area_o').append($('<option></option>'));
                    $.each(res['area'],function(element,val) {
                        $('#area_o').append($('<option>', { 
                            value: val.id,
                            text : val.area 
                        }));
                    }); 
                },
            });
        });
    });

</script>

<script type="text/javascript">
     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(200)
                        .height('auto');
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
                   .height('auto');
           };

           reader.readAsDataURL(input.files[0]);
       }
     }
</script>

<script type="text/javascript"> 
    $(document).ready(function(){
        $('#venue').change(function(){
            $("#court option").remove();
            var url_new="notification_sms/court_list";
            var venue = document.getElementById('venue').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {venue: venue},
                success:function (res) {
                   // console.log(res);
                    $('#court').append($('<option>', { 
                            value: '',
                            text :  ''
                        }));
                    $.each(res['court'],function(element,val) {
                        $('#court').append($('<option>', { 
                            value: val.id,
                            text : val.court 
                        }));
                    }); 
                },
            });
        });
    });

    $(document).ready(function(){
        $('#area_o').change(function(){
            $("#venue option").remove();
            var url_new="notification_sms/venue_list";
            var area_o = document.getElementById('area_o').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {area_o: area_o},
                success:function (res) {
                    //console.log(res);
                    $('#venue').append($('<option>', { 
                            value: '',
                            text :  ''
                        }));
                    $.each(res['venue'],function(element,val) {
                        $('#venue').append($('<option>', { 
                            value: val.venue_id,
                            text : val.venue 
                        }));
                    }); 
                },
            });
        });
    });
</script>
<script type="text/javascript">
    function getComboA(selectObject) {
       $("#offer_list option").remove();
        var court = selectObject.value;
        var url_new="notification_sms/offer_list";  
        var base_url="<?php echo base_url(); ?>" ;
        $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {court: court},
            success:function (res) {
                //console.log(res);
                $('#offer_list').append($('<option>', { 
                    value: '',
                    text :  ''
                }));
                $.each(res['offer'],function(element,val) {
                    $('#offer_list').append($('<option>', { 
                        value: val.id,
                        text : val.offer 
                    }));
                }); 
            },
        });
        //alert(value);
    }

</script>

<script type="text/javascript">
    function users_list(selectObject) {
        var offer_id = selectObject.value;
        var url_new="notification_sms/users_count";  
        var base_url="<?php echo base_url(); ?>" ;
        $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {offer_id: offer_id},
            success:function (res) {
                console.log(res);
                if (res) {
                    var div = document.getElementById('user_count');
                    div.innerHTML = res;
                }
            },
        });
        //alert(value);
    }

    function users_list_general() {
        var city = document.getElementById('city').value;
        var area = document.getElementById('area').value;
        var sports = document.getElementById('sports').value;
        //var sports = selectObject.value;
        //console.log(area);
        var url_new="notification_sms/users_count_general";  
        var base_url="<?php echo base_url(); ?>" ;
        $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {city: city,area: area,sports: sports},
            success:function (res) {
                console.log(res);
                if (res) {
                    var div = document.getElementById('user_count_general');
                    div.innerHTML = res;
                }
            },
        });
        //alert(value);
    }
</script>
<div class="content-wrapper">
     <section class="content-header">
          <h1>Notifications And SMS</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <!-- <h3 class="box-title">List</h3> -->
                              <!-- <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_offer')) { ?>
                                   <a href="<?php echo base_url('offer/add') ?>" class="btn btn-sm pull-right btn-warning">Add Offer <i class="fa fa-plus"></i></a>
                              <?php } ?> -->
                         </div>
            
                         <div class="box-body">
                              <ul class="nav nav-tabs" role="tablist">
                                   <li role="presentation" class="active " style="width: 50%;"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">General Notifications</a></li>
                                   <li role="presentation" style="width: 50%;" class="text-center"><a href="#offer" aria-controls="profile" role="tab" data-toggle="tab">Offer Notifications</a></li>
                              </ul>

                              <div class="tab-content ">
                                   <div role="tabpanel" class="tab-pane active " id="general">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <form class="form-horizontal" action="<?=base_url('notification_sms/general_send')?>" method="post" enctype="multipart/form-data">
                                                     <div class="box-body">
                                                          <div class="form-group">
                                                               <label for="sports" class="col-sm-1  "></label>
                                                               <div class="col-sm-11">
                                                                    Notification<input type="radio" class="flat-red" checked name="type" value="notification">
                                                                    SMS<input type="radio" class="flat-red" name="type" value="sms">
                                                                    Both<input type="radio" class="flat-red" name="type" value="both">
                                                               </div>
                                                          </div>

                                                          <div class="form-group">
                                                               <label for="exampleInputPassword1" class="col-sm-1 ">City</label>
                                                               <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="city" id="city" onChange="users_list_general()" required="required">
                                                                         <option></option>
                                                                         <?php foreach ($location as $key => $value) { ?>
                                                                             <option value="<?=$value['id']?>"><?=$value['location']?></option>
                                                                         <?php } ?>
                                                                    </select>
                                                               </div>
                                                          </div>

                                                          <div class="form-group " >
                                                               <label for="exampleInputPassword1" class="col-sm-1">Area</label>
                                                               <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="area" id="area" onChange="users_list_general()">
                                                                         <option value=""></option>
                                                                    </select>
                                                               </div>
                                                          </div>

                                                          <div class="form-group">
                                                               <label for="exampleInputPassword1" class="col-sm-1 ">Sports</label>
                                                               <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="sports" id="sports" onChange="users_list_general()">
                                                                         <option></option>
                                                                         <?php foreach ($sports as $key2 => $value2) { ?>
                                                                             <option value="<?=$value2['id']?>"><?=$value2['sports']?></option>
                                                                         <?php } ?>
                                                                    </select>
                                                               </div>
                                                          </div>

                                                          <div class="form-group" >
                                                               <label for="sports" class="col-sm-1 ">Title </label>
                                                               <div class="col-sm-11">
                                                                    <input type="text" name="title" class="form-control"  placeholder="Title"  required="required" >
                                                               </div>
                                                          </div>

                                                          <div class="form-group" >
                                                               <label for="sports" class="col-sm-1 ">Message </label>
                                                               <div class="col-sm-11">
                                                                    <textarea name="message" class="form-control"  placeholder="Message"  required="required" ></textarea>
                                                               </div>
                                                          </div>

                                                          <div class="form-group" >
                                                               <label for="sports" class="col-sm-1 ">Image </label>
                                                               <div class="col-sm-11">
                                                                    <input type='file' onchange="readURL1(this);" name="file" required class="image" />
                                                                    <img id="blah1" src=""   /><br>
                                                                     Resolution:450x300
                                                               </div>

                                                          </div>

                                                     </div>
                                                     <div class="box-footer">
                                                          <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                                          <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                                     </div>
                                                </form>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <br/>
                                                <div id="user_count_general" style="font-size: 20px;color: #ef0012;">
                                                </div>
                                            </div>
                                        </div>
                                   </div>
                                   <div role="tabpanel" class="tab-pane" id="offer">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <form class="form-horizontal" action="<?=base_url('notification_sms/offer_send')?>" method="post" enctype="multipart/form-data">
                                                     <div class="box-body">
                                                          <div class="form-group">
                                                               <label for="sports" class="col-sm-1  "></label>
                                                               <div class="col-sm-11">
                                                                    Notification<input type="radio" class="flat-red" checked name="type" value="notification">
                                                                    SMS<input type="radio" class="flat-red" name="type" value="sms">
                                                                    Both<input type="radio" class="flat-red" name="type" value="both">
                                                               </div>
                                                          </div>

                                                            <div class="form-group">
                                                               <label for="exampleInputPassword1" class="col-sm-1 ">City</label>
                                                               <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="city" id="city_o"  required="required">
                                                                         <option></option>
                                                                         <?php foreach ($location as $key => $value) { ?>
                                                                             <option value="<?=$value['id']?>"><?=$value['location']?></option>
                                                                         <?php } ?>
                                                                    </select>
                                                               </div>
                                                          </div>

                                                          <div class="form-group " >
                                                               <label for="exampleInputPassword1" class="col-sm-1">Area</label>
                                                               <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="area" id="area_o" >
                                                                         <option value=""></option>
                                                                    </select>
                                                               </div>
                                                          </div>

                                                            <div class="form-group">
                                                               <label for="exampleInputPassword1" class="col-sm-1 ">Venue</label>
                                                               <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="venue" id="venue">
                                                                         <option></option>
                                                                         
                                                                    </select>
                                                               </div>
                                                          </div>

                                                            <div class="form-group court_div" >
                                                                <label for="exampleInputPassword1" class="col-sm-1 ">Court</label>
                                                                <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="court" id="court" onChange="getComboA(this)">
                                                                        <option value=""></option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group court_div" >
                                                                <label for="exampleInputPassword1" class="col-sm-1 ">Offers</label>
                                                                <div class="col-sm-11">
                                                                    <select class="form-control select2" style="width: 100%;" name="offer" id="offer_list" onChange="users_list(this)">
                                                                        <option value=""></option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                          <div class="form-group" >
                                                               <label for="sports" class="col-sm-1 ">Title </label>
                                                               <div class="col-sm-11">
                                                                    <input type="text" name="title" class="form-control"  placeholder="Title"  required="required" >
                                                               </div>
                                                          </div>

                                                          <div class="form-group" >
                                                               <label for="sports" class="col-sm-1 ">Message </label>
                                                               <div class="col-sm-11">
                                                                    <textarea name="message" class="form-control"  placeholder="Message"  required="required" ></textarea>
                                                               </div>
                                                          </div>

                                                          <div class="form-group" >
                                                               <label for="sports" class="col-sm-1 ">Image </label>
                                                               <div class="col-sm-11">
                                                                    <input type='file' onchange="readURL(this);" name="file" required class="image" />
                                                                    <img id="blah" src="" />
                                                                    <br>
                                                                     Resolution:450x300
                                                               </div>
                                                          </div>

                                                     </div>
                                                     <div class="box-footer">
                                                          <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                                          <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                                     </div>
                                                </form>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <br/>
                                                <div id="user_count" style="font-size: 20px;color: #ef0012;">
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
  $(document).ready(function() {


  var _URL = window.URL || window.webkitURL;
$(".image").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
         // console.log(this.width);
            if(this.width!=450 || this.height!=300){
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
});
</script>