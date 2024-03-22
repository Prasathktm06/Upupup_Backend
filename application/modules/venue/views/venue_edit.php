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
          <h1>Venue</h1>
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
                                        <li <?php if(!isset($_GET['day'])&&!isset($_GET['share'])&&!isset($_GET['gallery'])&&!isset($_GET['venue_rating'])&&!isset($_GET['court'])&&!isset($_GET['inactive'])&&!isset($_GET['facility']) &&!isset($_GET['sport']) &&!isset($_GET['offer']) &&!isset($_GET['hot_offer']) &&!isset($_GET['tab'])==6) echo "class=active"  ?>><a href="#tab_1" data-toggle="tab">Venue</a></li>
                                        <li <?php if(isset($_GET['court'])) echo "class=active"  ?>><a href="#tab_2" data-toggle="tab">Court</a></li>
                                        <li <?php if(isset($_GET['facility'])) echo "class=active"  ?> ><a href="#tab_3" data-toggle="tab">Facilities</a></li>
                                        <li <?php if(isset($_GET['sport'])) echo "class=active"  ?> ><a href="#tab_4" data-toggle="tab">Sports</a></li>
                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_offers')) { ?>
                                          <li <?php if(isset($_GET['offer'])) echo "class=active"  ?>><a href="#tab_5" data-toggle="tab">Offers</a></li>
                                        <?php } ?>
                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_offer')) { ?>
                                          <li <?php if(isset($_GET['hot_offer'])) echo "class=active"  ?>><a href="#tab_12" data-toggle="tab">Hot Offer</a></li>
                                        <?php } ?> 
                                        <li <?php if(isset($_GET['tab'])==6) echo "class=active"  ?>><a href="#tab_6" data-toggle="tab">Managers</a></li>
                                        <li><a href="#tab_7" data-toggle="tab">Booking</a></li>
                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_inactive')) { ?>
                                          <li <?php if(isset($_GET['inactive'])) echo "class=active" ?>><a href="#tab_10" data-toggle="tab">In-activate</a></li>
                                        <?php } ?>
                                        
                                        <li <?php if(isset($_GET['day'])) echo "class=active" ?>><a href="#tab_8" data-toggle="tab">Holidays</a></li>
                                        <li <?php if(isset($_GET['gallery'])) echo "class=active" ?>><a href="#tab_9" data-toggle="tab">Gallery</a></li>
                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_share')) { ?>
                                          <li <?php if(isset($_GET['share'])) echo "class=active" ?>><a href="#tab_11" data-toggle="tab">Share</a></li>
                                        <?php } ?>
                                         <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_share')) { ?>
                                          <li <?php if(isset($_GET['venue_rating'])) echo "class=active" ?>><a href="#tab_14" data-toggle="tab">Rating</a></li>
                                        <?php } ?>
                                   </ul>
                                   <div class="tab-content">
                                        <div <?php if(!isset($_GET['day'])&&!isset($_GET['share'])&&!isset($_GET['venue_rating'])&&!isset($_GET['gallery'])&&!isset($_GET['court']) &&!isset($_GET['facility']) &&!isset($_GET['sport']) &&!isset($_GET['inactive']) &&!isset($_GET['offer']) &&!isset($_GET['hot_offer']) &&!isset($_GET['tab'])==6 ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_1">
                                             <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("venue/venue_edit/$venue->id")?>" method="post" id="venue_form">
<?php foreach ($bookings as $val):?>
<?php $abc="$val->date" ?>                                                                 
<?php $book.='"'.$abc.'"'?> 
<?php if(end($bookings) === $val)
    {
       $book.='';
    }else{
$book.=',';
}?>

<?php endforeach;?>

<?php $books='['.$book.']' ?>

                                                  <div class="box-body">
                                                       <div class="form-group">
                                                            <label for="venue" class="col-sm-2 control-label">Venue</label>
                                                            <div class="col-sm-6">
                                                                 <input type="text" name="venue" id="venue" class="form-control"  placeholder="Venue Name" value="<?= $venue->venue;?>" required="required">
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
                                                                           <input required value="<?=$venue->morning?>" type="text" class="form-control timepicker" name="morn">
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
                                                                           <input required value="<?=$venue->evening?>" type="text" class="form-control timepicker" name="even" id="even">
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">City</label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                                                      <option selected disabled>Select a city</option>
                                                                      <?php foreach ($locations as $val):?>
                                                                      <option <?php if($location->id==$val->id) echo "selected"?> value="<?= $val->id?>"><?= $val->location?></option>
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
                                                                      <option <?php if($venue->area_id==$val->id) echo "selected"?> value="<?= $val->id?>"><?= $val->area?></option>
                                                                      <?php endforeach;?>
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
                                                                      <input value="<?=$venue->phone?>" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999999 ', '+9999999999999']" data-mask>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="address" class="col-sm-2 control-label">Address</label>
                                                            <div class="col-sm-6">
                                                                 <textarea id="address" name="address" class="form-control" rows="3" placeholder="Address ..." maxlength="100"><?=$venue->address;?></textarea>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="desc" class="col-sm-2 control-label">Description</label>
                                                            <div class="col-sm-6">
                                                                 <textarea id="desc" name="desc" class="form-control" rows="3" placeholder="Address ..." maxlength="256"><?=$venue->description;?></textarea>
                                                            </div>
                                                       </div>
                                                       <div class="form-group" >
                                                            <label for="sports" class="col-sm-2 control-label">Image Upload </label>
                                                            <div class="col-sm-6">
                                                                 <input type='file' onchange="readURL(this);" name="file" accept="image/*"/  id="venue-image">
                                                                 <?php if ($venue->image) {
                                                                   $image=$venue->image;
                                                                 }else{
                                                                  $image=base_url('pics/unnamed.png');
                                                                 }

                                                                 ?>
                                                                 <img id="blah"   alt="image" src="<?=$image?>" widht="100" height="100" accept="image/*"/>
                                                            </div>
                                                       </div>
                                                       <h6 class="" style="margin-left: 105px;margin-top: -9px">
                                                       Max size: 5mb <br>
                                                       Extensions: jpg,png,jpeg <br>

                                                       </h6>
                                                       <div class="form-group">
                                                            <label for="lat" class="col-sm-2 control-label">Latitude</label>
                                                            <div class="col-sm-6">
                                                                 <input value="<?=$venue->lat;?>" type="number" name="lat" id="lat" class="form-control"  placeholder="Latitude" value="" required="required" step="any">
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="lon" class="col-sm-2 control-label">Longitude</label>
                                                            <div class="col-sm-6">
                                                                 <input value="<?=$venue->lon;?>" type="number" name="lon" id="lon" class="form-control"  placeholder="Longitude" value="" required="required" step="any">
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="lon" class="col-sm-2 control-label">Pay at Venue %</label>
                                                            <div class="col-sm-6">
                                                                 <input  type="number" name="amount" id="" class="form-control"  placeholder="Percentage" value="<?=$venue->amount?>" max="100" min="0">
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="lat" class="col-sm-2 control-label"></label>
                                                            <div id="map" style="width: 500px; height: 250px;background-color: grey; "></div>

                                                       </div>
                                                       <div class="form-group">
                                                            <label for="lon" class="col-sm-2 control-label"></label>
                                                            <div class="col-sm-6 well">
                                                                 <strong style="padding-left: 78px;">Booking</strong> <input id="book" type="radio" name="book" value="book" class="flat-red " <?php if($venue->book_status=="book") echo "checked" ?> >
                                                                 <strong style="padding-left: 177px;">Callback</strong> <input id="call" type="radio" name="book" value="call" class="flat-red " <?php if($venue->book_status=="call") echo "checked" ?> >
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Status </label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                                                      <option <?php if($venue->status==1)echo "selected";  ?> value="1">Active</option>
                                                                      <option <?php if($venue->status==0)echo "selected"; ?> value="0">Inactive</option>
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
                                        <div <?php if(isset($_GET['court'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_2">
                                             <div class="box-body">
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_court')) { ?>
                                                  <a href="<?php echo base_url("court/add/$venue->id") ?>" class="btn btn-sm pull-right btn-warning">Add Court <i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                  <br><br>
                                                  <table id="datatable-court" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                 <th>#</th>
                                                                 <th>Court</th>
                                                                 <th>Sport</th>
                                                                 <th>Status</th>
                                                                 <th>Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                       </tbody>
                                                       <tfoot>
                                                            <tr>
                                                                 <th>#</th>
                                                                 <th>Court</th>
                                                                 <th>Sport</th>
                                                                 <th>Status</th>
                                                                 <th>Action</th>
                                                            </tr>
                                                       </tfoot>
                                                  </table>
                                             </div>
                                        </div>

                                        <div <?php if(isset($_GET['facility']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?>  class="tab-pane" id="tab_3">
                                             <form class="form-horizontal" action="<?=base_url("venue/edit_venue_speciality/$venue->id")?>" method="post">
                                                  <div class="box-body">
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Facilities</label>
                                                            <div class="col-sm-6">
                                                                 <select name="speciality[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Facility" style="width: 100%;">
                                                                      <?php foreach ($speciality as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $venue->facility))) echo "selected"; ?> value="<?=$val->id?>"><?=$val->facility?></option>
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
                                        <div <?php if(isset($_GET['sport']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?>  class="tab-pane" id="tab_4">
                                             <form class="form-horizontal" action="<?=base_url("venue/edit_venue_sports/$venue->id")?>" method="post">
                                                  <div class="box-body">
                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Sports</label>
                                                            <div class="col-sm-6">
                                                                 <select name="sports[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Sports" style="width: 100%;">
                                                                      <?php foreach ($sports as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $venue_sports->sports_id))) echo "selected"; ?> value="<?=$val->id?>"><?=$val->sports?></option>
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

                                        <div <?php if(isset($_GET['offer']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_5">
                                             <div class="box-body">
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_offer')) { ?>
                                                  <a href="<?php echo base_url("offer/add/$venue->id") ?>" class="btn btn-sm pull-right btn-warning">Add Offer <i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                  <br><br>
                                                  <table id="example5" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                 <th class="text-center" style="width: 5%">#</th>
                                                                 <th class="text-center">Offer</th>
                                                                 <th class="text-center">Offer Value</th>
                                                                 <th class="text-center">Sports</th>
                                                                 <th class="text-center">Start Date</th>
                                                                 <th class="text-center">Start Time</th>
                                                                 <th class="text-center">End Date</th>
                                                                 <th class="text-center">End Time</th>
                                                                 <th class="text-center">Status</th>
                                                                 <th class="text-center">Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                         <?php $i=1; foreach ($offer as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value['offer']?></td>
                                            <td class="text-center">
                                               <?php if ($value['amount']!=0) { ?>
                                                   <?= $value['amount']?>
                                                <?php }else if ($value['percentage']!=0) { ?>
                                                   <?=$value['percentage'] ?>%
                                                <?php }else { ?>
                                                    
                                                <?php }?> 
                                            </td>
                                            <td class="text-center"><?=$value['offer_sportslist']?></td>
                                            <td class="text-center"><?=date( ' d M Y ',strtotime($value['start']))?></td>
                                            <td class="text-center"> <?=date( ' h:i:s A ',strtotime($value['start_time']))?></td>
                                            <td class="text-center"><?=date( ' d M Y ',strtotime($value['end']))?></td>
                                            <td class="text-center"> <?=date( ' h:i:s A ',strtotime($value['end_time']))?></td>
                                            <td class="text-center">
                                              <?php if($value['status']==1){?>
                                               <a href="<?=base_url()?>offer/offer_status/<?=$value['id']?>/<?=$value['status']?>/<?=$value['venue_id']?>" type="button" class="btn btn-success">Active</i></a>
                                              <?php } else{?>
                                                        
                                                <a href="<?=base_url()?>offer/offer_status/<?=$value['id']?>/<?=$value['status']?>/<?=$value['venue_id']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                              <?php } ?>
                                            </td>
                                            <td class="text-center">
                                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_offers')) { ?>
                                                  <a href="<?=base_url()?>offer/edit/<?=$value['id']?>/<?=$value['venue_id']?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                              <?php } ?>
                                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_offers')) { ?>
                                                  <a href="<?=base_url()?>offer/delete/<?=$value['id']?>/<?=$value['venue_id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                              <?php } ?>
                                            </td>
    
                                        </tr>
                                        <?php $i++;} ?>
                                                       </tbody>
                                                       <tfoot>
                                                            <tr>
                                                                 <th class="text-center" style="width: 5%">#</th>
                                                                 <th class="text-center">Offer</th>
                                                                 <th class="text-center">Offer Value</th>
                                                                 <th class="text-center">Sports</th>
                                                                 <th class="text-center">Start Date</th>
                                                                 <th class="text-center">Start Time</th>
                                                                 <th class="text-center">End Date</th>
                                                                 <th class="text-center">End Time</th>
                                                                 <th class="text-center">Status</th>
                                                                 <th class="text-center">Action</th>
                                                            </tr>
                                                       </tfoot>
                                                  </table>
                                             </div>
                                        </div>

                                         <!-- hot offer tab starting -->

                                        <div <?php if(isset($_GET['hot_offer']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_12">
                                             <div class="box-body">
                                                 
                                                  <table id="example7" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                  <th class="text-center" style="width: 5%">#</th>
                                                                  <th class="text-center" style="width: 15%">Date</th>
                                                                  <th class="text-center" style="width: 15%">Percentage</th>
                                                                  <th class="text-center" style="width: 15%">Slot Time</th>
                                                                  <th class="text-center" style="width: 15%">Sports</th>
                                                                  <th class="text-center" style="width: 15%">Courts</th>
                                                                  <th class="text-center" style="width: 15%">Status</th>
                                                                  
                                                                  
                                                                  
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                         <?php $i=1; foreach ($hot_offer as $key => $value) { ?>
                                                          <tr>
                                                              <td class="text-center" style="width: 5%"><?=$i?></td>
                                                              <td class="text-center" style="width: 15%"><?=date( ' d M Y ',strtotime($value['hot_date']))?></td>
                                                              <td class="text-center" style="width: 15%"><?=$value['hot_percentage']?>%</td>
                                                              <td class="text-center" style="width: 15%"><?=$value['hot_offer_slots']?></td>
                                                              <td class="text-center" style="width: 15%"><?=$value['hot_offer_sports']?></td>
                                                              <td class="text-center" style="width: 15%"><?=$value['hot_offer_courts']?></td>
                                                                <td class="text-center" style="width: 15%">
                                                                 <?php if($value['status']==1){?>
                                                                     <a href="<?=base_url()?>hot_offer/offer_status/<?=$value['hot_id']?>/<?=$value['status']?>/<?=$value['venue_id']?>" type="button" class="btn btn-success">Active</i></a>
                                                                    <?php } else{?>
                                                                              
                                                                      <a href="<?=base_url()?>hot_offer/offer_status/<?=$value['hot_id']?>/<?=$value['status']?>/<?=$value['venue_id']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                                    <?php } ?>
                                                              </td>
                                                             
                                                              
                      
                                                          </tr>
                                                        <?php $i++;} ?>
                                                       </tbody>
                                                       <tfoot>
                                                            <tr>
                                                                  <th class="text-center" style="width: 5%">#</th>
                                                                  <th class="text-center" style="width: 15%">Date</th>
                                                                  <th class="text-center" style="width: 15%">Percentage</th>
                                                                  <th class="text-center" style="width: 15%">Slot Time</th>
                                                                  <th class="text-center" style="width: 15%">Sports</th>
                                                                  <th class="text-center" style="width: 15%">Courts</th>
                                                                  <th class="text-center" style="width: 15%">Status</th>
                                                                  
                                                                  
                                                                  
                                                            </tr>
                                                       </tfoot>
                                                  </table>
                                             </div>
                                        </div>

                                        <!-- hot offer tab ending -->

                                        <div  <?php if(isset($_GET['tab'])==6) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?>  class="tab-pane" id="tab_6">
                                             <div class="box-body">
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_venue_user')) { ?>
                                                  <a href="<?php echo base_url("acl/user/add/$venue->id") ?>" class="btn btn-sm pull-right btn-warning">Add Venue User <i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                  <br><br>
                                                  <table id="datatable-venue-users" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                                 <th>#</th>
                                                                 <th>Name</th>
                                                                 <th>Email</th>
                                                                 <th>Role</th>
                                                                 <th >Action</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                       </tbody>
                                                       <tfoot>
                                                            <tr>
                                                                 <th>#</th>
                                                                 <th>Name</th>
                                                                 <th>Email</th>
                                                                 <th>Role</th>
                                                                 <th >Action</th>
                                                            </tr>
                                                       </tfoot>
                                                  </table>
                                             </div>
                                        </div>
<div class="tab-pane" id="tab_7">
                                          <section class="content">
                                                <div class="row">
                                                     <div class="col-xs-12">
                                                          <div class="box">
                                                  
                                                               <div class="box-body">
                                                                    <ul class="nav nav-tabs" role="tablist">
                                                                         <li role="presentation" class="active " style="width: 33.33%;"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">upUPUP Booking</a></li>
                                                                         <li role="presentation" class="text-center" style="width: 33.33%;"><a href="#vendors" aria-controls="home" role="tab" data-toggle="tab">Vendors Booking</a></li>
                                                                         <li role="presentation" style="width: 33.33%;" class="text-center"><a href="#offer" aria-controls="profile" role="tab" data-toggle="tab">Cancel Bookings</a></li>
                                                                    </ul>

                                                                    <div class="tab-content ">
                                                                         <div role="tabpanel" class="tab-pane active " id="general">
                                                                                  <div class="box-body">
                                                                                        <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                  <br><br>
                                                  <table id="example1" class="table table-bordered table-hover" style="width: 100%">
                                                       <thead>
                                                            <tr>
                                                            <th class="text-center" style="width: 5%">#</th>
                                                            <th class="text-center">Booking ID</th>
                                                            <th class="text-center">User Name</th>
                                                            <th class="text-center">User Phone No.</th>
                                                            <th class="text-center">User Email ID</th>
                                                            <th class="text-center">City</th>
                                                            <th class="text-center">Area</th>
                                                            <th class="text-center">Venue Name</th>
                                                            <th class="text-center">Court Name</th>
                                                            <!--<th class="text-center">Court Capacity</th>-->
                                                            <th class="text-center">Sports Playing</th>
                                                            <th class="text-center">Date of Booking</th>
                                                            <th class="text-center">Time of Booking</th>
                                                            <th class="text-center">Date of Playing</th>
                                                            <th class="text-center">Time of Playing</th>
                                                            <th class="text-center">Hours of Playing</th>
                                                            <th class="text-center">Per Slot Price</th>
                                                            <th class="text-center">Booked Capacity</th>
                                                            <th class="text-center">Total Amount</th>
                                                            <th class="text-center">Mode of Payment</th>
                                                            <th class="text-center">Pay.Gat. Paid Amount.</th>
                                                            <th class="text-center">Payable  Amount.</th>
                                                            <th class="text-center">Pay.Gat. Transaction ID</th>
                                                            <th class="text-center">Pay.Gat. Payment ID</th>
                                                            <th class="text-center">Pay At Venue Amount</th>
                                                            <th class="text-center">Offer Amount</th>
                                                            <th class="text-center">Coupon Amount</th>
                                                            <th class="text-center">Coupon Name</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                       <?php $i=1; foreach ($list as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value['booking_id']?></td>
                                            <td class="text-center"><?=$value['name']?></td>
                                            <td class="text-center"><?=$value['phone_no']?></td>
                                            <td class="text-center"><?=$value['email']?></td>
                                            <td class="text-center"><?=$value['location']?></td>
                                            <td class="text-center"><?=$value['area']?></td>
                                            <td class="text-center"><?=$value['venue']?></td>
                                            <td class="text-center"><?=$value['court']?></td>
                                            <!--<td class="text-center"><?=$value['capacity']?></td>-->
                                            <td class="text-center"><?=$value['sports']?></td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' h:i:s A ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['date']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['date']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center"><?=$value['time_slots']?></td>
                                            <td class="text-center"><?=number_format((float)($value['duration']/60),2, '.', '')?> Hours</td>
                                            <td class="text-center"><?=$value['court_cost']?></td>
                                            <td class="text-center"><?=$value['total_capacity']?></td>
                                            <!--<?=($value['court_cost']*count($value['booked_slots']))*$value['total_capacity']?>-->
                                            <td class="text-center"><?=$value['court_cost']*$value['total_capacity']?></td>
                                            <td class="text-center">
                                                <?php if ($value['payment_mode']==1) { ?>
                                                    Pay Through App
                                                <?php }else if ($value['payment_mode']==0) { ?>
                                                    Pay At Venue
                                                <?php }else if ($value['payment_mode']==2) { ?>
                                                    Failed
                                                <?php }?> 
                                            </td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                             <td class="text-center">
                                             <?php if ($value['payment_mode']==0) { 
                                                   echo  $value['cost']+$value['bal'];
                                                }else{
                                                    echo  $value['cost'];
                                                }?>    
                                            </td>
                                            <td class="text-center"><?=$value['transaction_id']?></td>
                                            <td class="text-center"><?=$value['payment_id']?></td>
                                            <td class="text-center">
                                                <?php if ($value['payment_mode']==0) { 
                                                    echo $value['bal'];
                                                }?> 
                                            </td>
                                            <td class="text-center"><?=$value['offer_amount']?></td>  
                                            <td class="text-center">
                                                <?php if ($value['percentage']=="Yes") { ?>
                                                  <?=$value['court_cost']*$value['total_capacity']-$value['cost']?>
                                                <?php }else if ($value['percentage']=="No") { ?>
                                                  <?=$value['court_cost']*$value['total_capacity']-$value['cost']?>
                                                <?php }?>
                                            </td>   
                                            <td class="text-center"><?=$value['description']?> </td>    
                                        </tr>
                                        <?php $i++;} ?>
                                                       </tbody>
                                                       <tfoot>
                                                            <tr>
                                                            <th class="text-center" style="width: 5%">#</th>
                                                            <th class="text-center">Booking ID</th>
                                                            <th class="text-center">User Name</th>
                                                            <th class="text-center">User Phone No.</th>
                                                            <th class="text-center">User Email ID</th>
                                                            <th class="text-center">City</th>
                                                            <th class="text-center">Area</th>
                                                            <th class="text-center">Venue Name</th>
                                                            <th class="text-center">Court Name</th>
                                                            <!--<th class="text-center">Court Capacity</th>-->
                                                            <th class="text-center">Sports Playing</th>
                                                            <th class="text-center">Date of Booking</th>
                                                            <th class="text-center">Time of Booking</th>
                                                            <th class="text-center">Date of Playing</th>
                                                            <th class="text-center">Time of Playing</th>
                                                            <th class="text-center">Hours of Playing</th>
                                                            <th class="text-center">Per Slot Price</th>
                                                            <th class="text-center">Booked Capacity</th>
                                                            <th class="text-center">Total Amount</th>
                                                            <th class="text-center">Mode of Payment</th>
                                                            <th class="text-center">Pay.Gat. Paid Amount.</th>
                                                            <th class="text-center">Payable  Amount.</th>
                                                            <th class="text-center">Pay.Gat. Transaction ID</th>
                                                            <th class="text-center">Pay At Venue Amount</th>
                                                            <th class="text-center">Offer Amount</th>
                                                            <th class="text-center">Coupon Amount</th>
                                                            <th class="text-center">Coupon Name</th>
                                                            </tr>
                                                       </tfoot>
                                                  </table>
                                                                                   </div>   
                                                                         </div>
                                                                         <div role="tabpanel" class="tab-pane" id="vendors">
<div class="box-body">
                                                                                        <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                                                        <br><br>
                                                                                        <table id="example2" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">#</th>
                                            <th class="text-center">Booking ID</th>
                                            <th class="text-center">User Name</th>
                                            <th class="text-center">User Phone No.</th>
                                            <th class="text-center">User Email ID</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Area</th>
                                            <th class="text-center">Venue Name</th>
                                            <th class="text-center">Court Name</th>
                                            <!--<th class="text-center">Court Capacity</th>-->
                                            <th class="text-center">Sports Playing</th>
                                            <th class="text-center">Date of Booking</th>
                                            <th class="text-center">Time of Booking</th>
                                            <th class="text-center">Date of Playing</th>
                                            <th class="text-center">Time of Playing</th>
                                            <th class="text-center">Hours of Playing</th>
                                            <th class="text-center">Per Slot Price</th>
                                            <th class="text-center">Booked Capacity</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Booked By</th>
                                            <th class="text-center">Manager Number</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($vendor as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value['booking_id']?></td>
                                            <td class="text-center"><?=$value['name']?></td>
                                            <td class="text-center"><?=$value['phone_no']?></td>
                                            <td class="text-center"><?=$value['email']?></td>
                                            <td class="text-center"><?=$value['location']?></td>
                                            <td class="text-center"><?=$value['area']?></td>
                                            <td class="text-center"><?=$value['venue']?></td>
                                            <td class="text-center"><?=$value['court']?></td>
                                            <!--<td class="text-center"><?=$value['capacity']?></td>-->
                                            <td class="text-center"><?=$value['sports']?></td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' h:i:s A ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['date']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['date']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center"><?=$value['time_slots']?></td>
                                            <td class="text-center"><?=number_format((float)($value['duration']/60),2, '.', '')?> Hours</td>
                                            <td class="text-center"><?=$value['court_cost']?></td>
                                            <td class="text-center"><?=$value['total_capacity']?></td>
                                            <!--<?=($value['court_cost']*count($value['booked_slots']))*$value['total_capacity']?>-->
                                            <td class="text-center"><?=$value['court_cost']*$value['total_capacity']?></td>
                                            <td class="text-center">
                                                <?php if ($value['payment_mode']==1) { ?>
                                                   Pay At Venue
                                                <?php }else if ($value['payment_mode']==0) { ?>
                                                    Pay At Venue
                                                <?php }else if ($value['payment_mode']==2) { ?>
                                                    Failed
                                                <?php }?> 
                                            </td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                            <td class="text-center">
                                               <?php if ($value['offer_amount']!=0) { ?>
                                                   <?= $value['offer_amount']*$value['total_capacity']?>
                                                <?php }else if ($value['offer_percentage']!=0) { ?>
                                                   <?= (($value['court_cost']*$value['total_capacity'])*$value['offer_percentage'])/100?>
                                                <?php }?>
                                            </td>   
                                            <td class="text-center"><?=$value['role_name']?> </td>  
                                            <td class="text-center"><?=$value['mgr_phone']?> </td>    
                                        </tr>
                                        <?php $i++;} ?>
                                   </tbody>
                                   <tfoot>
                                        <tr>
                                            <th class="text-center" style="width: 5%">#</th>
                                            <th class="text-center">Booking ID</th>
                                            <th class="text-center">User Name</th>
                                            <th class="text-center">User Phone No.</th>
                                            <th class="text-center">User Email ID</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Area</th>
                                            <th class="text-center">Venue Name</th>
                                            <th class="text-center">Court Name</th>
                                            <!--<th class="text-center">Court Capacity</th>-->
                                            <th class="text-center">Sports Playing</th>
                                            <th class="text-center">Date of Booking</th>
                                            <th class="text-center">Time of Booking</th>
                                            <th class="text-center">Date of Playing</th>
                                            <th class="text-center">Time of Playing</th>
                                            <th class="text-center">Hours of Playing</th>
                                            <th class="text-center">Per Slot Price</th>
                                            <th class="text-center">Booked Capacity</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Booked By</th>
                                            <th class="text-center">Manager Number</th>
                                        </tr>
                                   </tfoot>
                              </table>
                                                                                   </div> 
                                                                         </div>
                                                                         <div role="tabpanel" class="tab-pane" id="offer">
                                                                         <div class="box-body">
                                                                                        <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                                                        <br><br>
                                                                                        <table id="example3" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">#</th>
                                            <th class="text-center">Booking ID</th>
                                            <th class="text-center">User Name</th>
                                            <th class="text-center">User Phone No.</th>
                                            <th class="text-center">User Email ID</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Area</th>
                                            <th class="text-center">Venue Name</th>
                                            <th class="text-center">Court Name</th>
                                            <!--<th class="text-center">Court Capacity</th>-->
                                            <th class="text-center">Sports Playing</th>
                                            <th class="text-center">Date of Booking</th>
                                            <th class="text-center">Time of Booking</th>
                                            <th class="text-center">Date of Playing</th>
                                            <th class="text-center">Time of Playing</th>
                                            <th class="text-center">Hours of Playing</th>
                                            <th class="text-center">Per Slot Price</th>
                                            <th class="text-center">Booked Capacity</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Payable Amount</th>
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Cancel By</th>
                                            <th class="text-center">Manger Number</th>
                                            <th class="text-center">Cancel Date</th>
                                            <th class="text-center">Cancel Time</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($bookcan as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value['booking_id']?></td>
                                            <td class="text-center"><?=$value['name']?></td>
                                            <td class="text-center"><?=$value['phone_no']?></td>
                                            <td class="text-center"><?=$value['email']?></td>
                                            <td class="text-center"><?=$value['location']?></td>
                                            <td class="text-center"><?=$value['area']?></td>
                                            <td class="text-center"><?=$value['venue']?></td>
                                            <td class="text-center"><?=$value['court']?></td>
                                            <!--<td class="text-center"><?=$value['capacity']?></td>-->
                                            <td class="text-center"><?=$value['sports']?></td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' h:i:s A ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['date']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['date']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center"><?=$value['time_slots']?></td>
                                            <td class="text-center"><?=number_format((float)($value['duration']/60),2, '.', '')?> Hours</td>
                                            <td class="text-center"><?=$value['court_cost']?></td>
                                            <td class="text-center"><?=$value['booked_capacity']?></td>
                                            <!--<?=($value['court_cost']*count($value['booked_slots']))*$value['total_capacity']?>-->
                                            <td class="text-center"><?=$value['court_cost']*$value['booked_capacity']?></td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                            <td class="text-center">
                                                <?php if ($value['payment_mode']==3) { ?>
                                                   Cancel
                                                <?php }else if ($value['payment_mode']==0) { ?>
                                                    Pay At Venue
                                                <?php }else if ($value['payment_mode']==2) { ?>
                                                    Failed
                                                <?php }?> 
                                            </td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                            <td class="text-center">
                                               <?php if ($value['offer_amount']!=0) { ?>
                                                   <?= $value['offer_amount']*$value['total_capacity']?>
                                                <?php }else if ($value['offer_percentage']!=0) { ?>
                                                   <?= (($value['court_cost']*$value['total_capacity'])*$value['offer_percentage'])/100?>
                                                <?php }?>
                                            </td>  
                                            <td class="text-center"><?=$value['role_name']?> </td> 
                                            <td class="text-center"><?=$value['cm_phone']?></td>
                                            <td class="text-center"><?=date( ' d M Y ',strtotime($value['cancel_date']))?></td>
                                            <td class="text-center"><?=date( ' h:i:s A ',strtotime($value['cancel_date']))?></td>    
                                        </tr>
                                        <?php $i++;} ?>
                                   </tbody>
                                   <tfoot>
                                        <tr>
                                            <th class="text-center" style="width: 5%">#</th>
                                            <th class="text-center">Booking ID</th>
                                            <th class="text-center">User Name</th>
                                            <th class="text-center">User Phone No.</th>
                                            <th class="text-center">User Email ID</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Area</th>
                                            <th class="text-center">Venue Name</th>
                                            <th class="text-center">Court Name</th>
                                            <!--<th class="text-center">Court Capacity</th>-->
                                            <th class="text-center">Sports Playing</th>
                                            <th class="text-center">Date of Booking</th>
                                            <th class="text-center">Time of Booking</th>
                                            <th class="text-center">Date of Playing</th>
                                            <th class="text-center">Time of Playing</th>
                                            <th class="text-center">Hours of Playing</th>
                                            <th class="text-center">Per Slot Price</th>
                                            <th class="text-center">Booked Capacity</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Payable Amount</th>
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Cancel By</th>
                                            <th class="text-center">Manger Number</th>
                                            <th class="text-center">Cancel Date</th>
                                            <th class="text-center">Cancel Time</th>
                                        </tr>
                                   </tfoot>
                              </table>
                                                                                   </div>
                                                                         </div>
                                                                    </div>
                                                               </div>
                                                          </div>
                                                     </div>
                                                </div>
                                          </section>    
                                        </div>


                                       <div <?php if(isset($_GET['inactive']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_10">
                                             <div class="box-body">

                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_inactive')) { ?>
                                                  <a href="<?php echo base_url("inactivate/add/$venue->id") ?>" class="btn btn-sm pull-right btn-warning">Add<i class="fa fa-plus"></i></a>
                                                  <?php } ?>
                                                  <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                  <br><br>
                                                  <table id="example6" class="table table-bordered table-hover" style="width: 100%">
                                                     <thead>
                                                          <tr>
                                                              <th class="text-center" style="width: 5%">#</th>
                                                              <th class="text-center">Court Name</th>
                                                              <th class="text-center">Start Date</th>
                                                              <th class="text-center">End Date</th>
                                                              <th class="text-center">Start Time</th>
                                                              <th class="text-center">End Time</th>
                                                              <th class="text-center">Reason</th>
                                                              <th class="text-center" >Action</th>
                                                          </tr>
                                                     </thead>
                                                     <tbody>
                                                          <?php $i=1; foreach ($inactive_court as $key => $value) { ?>
                                                          <tr>
                                                              <td class="text-center" style="width: 5%"><?=$i?></td>
                                                              <td class="text-center"><?=$value['court']?></td>
                                                              <td class="text-center"><?=date( ' d M Y ',strtotime($value['sdate']))?></td>
                                                              <td class="text-center"><?=date( ' d M Y ',strtotime($value['edate']))?></td>
                                                              <td class="text-center"><?=date( ' h:i:s A ',strtotime($value['stime']))?></td>
                                                              <td class="text-center"><?=date( ' h:i:s A ',strtotime($value['etime']))?></td>
                                                              <td class="text-center"><?=$value['description']?></td>
                                                              <td class="text-center">
                                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_inactive')) { ?>
                                                                 <a href="<?=base_url()?>inactivate/inactivate_edit/<?=$value['id']."/".$venue->id?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                                <?php } ?>
                                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_inactive')) { ?>
                                                                 <a href="<?=base_url()?>inactivate/inactivate_delete/<?=$value['id']."/".$venue->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                                <?php } ?>
                                                                
                                                                
                                                              </td>
                                                               
                                                          </tr>
                                                          <?php $i++;} ?>
                                                     </tbody>
                                                     <tfoot>
                                                          <tr>
                                                              <th class="text-center" style="width: 5%">#</th>
                                                              <th class="text-center">Court Name</th>
                                                              <th class="text-center">Start Date</th>
                                                              <th class="text-center">End Date</th>
                                                              <th class="text-center">Start Time</th>
                                                              <th class="text-center">End Time</th>
                                                              <th class="text-center">Reason</th>
                                                              <th class="text-center" >Action</th>
                                                          </tr>
                                                     </tfoot>
                                                </table>
                                  
                                             </div>
                                        </div>

                                        <div <?php if(isset($_GET['day']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_8">
 <input type="hidden" name="venues" value="<?=$venue->id?>" >
<div id="resultDiv"></div>
                                             <div class="box-body">
                                                  <div class="block full">
                                                       <div class="block-title">
                                                            <h3 style="padding-left: 40%"><u></u></h3>
                                                            <div id="mmp"></div>
                                                            <form action="" method="get">
                                                                 <div class="modal fade" id="myModal2" role="dialog">
                                                                      <div class="modal-dialog">
                                                                           <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                     <h4 class="modal-title">Description</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                     <textarea name="image-desc" rows="3" cols="70%"></textarea>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                     <input id="holiday-btn"  type="submit" class="btn btn-default" data-dismiss="modal" value="Submit" required="">
                                                                                </div>
                                                                           </div>
                                                                      </div>
                                                                 </div>

                                                                 <div class="form-group">
                                                                      <div class="col-sm-3">
                                                                           <label class="col-sm-3 control-label">From:</label>
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="day" type="text" class="form-control " id="datepicker8" required="required" >
                                                                           </div>
                                                                      </div>
                                                                      <div class="col-sm-3">
                                                                           <label class="col-sm-2 control-label">To:</label>
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="days" type="text" class="form-control " id="datepicker9" required="required" >
                                                                           </div>
                                                                      </div>
                                                                      <input type="button" class=" btn btn-warning" value="Add" data-toggle="modal" data-target="#myModal2">
                                                                 </div>
                                                            </form>
                                                            <table class ="datatable-holiday" class="table table-bordered table-hover" style="width: 100%">
                                                                 <thead>
                                                                      <tr>
                                                                           <th>#</th>
                                                                           <th>Holiday</th>
                                                                           <th>Description</th>
                                                                           <th>Action</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                      <?php foreach ($holidays as $key => $value) {?>
                                                                      <tr>
                                                                           <td><?=++$key?></td>
                                                                           <td style="padding: 20px;padding-left: 0px"><?=date( ' d M Y ',strtotime($value->date))?></td>
                                                                           <td><?= $value->description?></td>
                                                                           <td><a href="<?=base_url()?>venue/holiday_delete/<?=$value->id."/".$venue->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                                                      </tr>
                                                                      <?php } ?>
                                                                 </tbody>
                                                                 <tfoot>
                                                                      <tr>
                                                                           <th>#</th>
                                                                           <th>Holiday</th>
                                                                           <th>Description</th>
                                                                           <th >Action</th>
                                                                      </tr>
                                                                 </tfoot>
                                                            </table><br>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>

                                        <div <?php if (isset($_GET['gallery'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_9" >
                                             <form action="<?=base_url("venue/add_gallery")?>" method="post" enctype="multipart/form-data">
                                                  <div class="col-sm-1 fileUpload btn btn-info btn-sm pull-right">
                                                       <input type="hidden" name="venue" value="<?=$venue->id?>" >
                                                       <span>Upload </span>
                                                       <input name="file" type="file" id="exampleInputFile" class="upload" accept="image/*" onchange="this.form.submit(); $('#myModal').modal('show');">
                                                  </div><br>
                                                  <h6 class="pull-right" style="margin-left: 105px;margin-top: -9px">
                                                  Max size: 5mb <br>
                                                  Extensions: jpg,png,jpeg <br>
                                                
                                                  </h6>
                                             </form> <br><br>
                                             <div class="box-body ">
                                                  <div id="gallery" >

                                                    <?php if(!empty($gallery)){ ?>
                                                       <?php foreach ($gallery as $key => $value) { ?>
                                                       <div class="gallery ">
                                                            <img class="close-btn" src="http://new4.fjcdn.com/pictures/Which+close+button+should+i+use+i+have+this+two_e08deb_5442440.png" alt="Fjords" style="width: 25px;height: 25px; position: absolute;margin-top:  -12px;margin-left: -9px; " />
                                                            <a  class="gallery-a" target="_blank"  id="<?=$value->id?>">
                                                            <img class="picture img-thumbnail" src="<?=$value->image?>" alt="photo" width="300" height="200">
                                                            <div class="desc"><input type="text" name="image-desc" value="<?=$value->description?>"   style="border: none;border-color: transparent;width:150px" placeholder="add description..."></div>
                                                            </a>
                                                       </div>
                                                       <?php }}?>
                                                  </div>
                                             </div>
                                        </div>
                                   <div <?php if(isset($_GET['share'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="tab_11" >
                                           <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_share')) { ?>
                                           <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("venue/add_share")?>" method="post" id="venue_form">

                                                  <div class="box-body">
                                                       <input type="hidden" name="venue_id" id="venue_id" value="<?= $venue->id?>">
                                                      <div class="form-group">
                                                        <label for="lon" class="col-sm-2 control-label">Share %</label>
                                                        <div class="col-sm-6">
                                                          <input value="" type="number" step="0.01" name="share" id="share" class="form-control"  placeholder="Percentage" >
                                                        </div>
                                                      </div>
                                                       
                                                      <div class="form-group">
                                                        <label for="lon" class="col-sm-2 control-label"></label>
                                                        <div class="col-sm-6 well">
                                                          <strong style="padding-left: 50px;">User App Share</strong> <input id="user_app" type="radio" name="user_app" value="user_app" class="flat-red " checked>
                                                          <strong style="padding-left: 50px;">Vendor App Share</strong> <input id="vendor_app" type="radio" name="user_app" value="vendor_app" class="flat-red " >
                                                        </div>
                                                      </div>
                                                      
                                                  </div>

                                                  <div class="box-footer">
                                                    <div class="form-group">
                                                        <div class="col-sm-2 ">
                                                           
                                                        </div>
                                                        <div class="col-sm-1 ">
                                                           <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                                        </div>
                                                        <div class="col-sm-2 ">
                                                           
                                                        </div>
                                                         <div class="col-sm-1 ">
                                                       <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                                        </div>
                                                      </div>
                                                      
                                                      
                                                  </div>
                                             </form>
                                        <?php } ?>
                                            <table id="example4" class="table table-bordered table-hover" style="width: 60%;margin-left: 10%;margin-right: 20%">
                                                <thead>
                                                    <tr>
                                                      <th>Share</th>
                                                      <th>Percentage</th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   <?php $forwhom="user_app"; ?>
                                                  <?php foreach ($user_app as $key => $value) {?>
                                                    <tr>
                                                        <td style="padding: 20px;padding-left: 0px"><?= "User App Share" ?></td>
                                                        <td><?= $value->share ?></td>
                                                        <td><?php if($value->status){?>
                                                       <a href="<?=base_url()?>venue/share_change_status/<?=$value->venue_id?>/<?=$value->id?>/<?=$value->status?>/<?= $forwhom ?>" type="button" class="btn btn-success">Active</i></a>
                                                  <?php } else{?>
                                                       <a href="<?=base_url()?>venue/share_change_status/<?=$value->venue_id?>/<?=$value->id?>/<?=$value->status?>/<?= $forwhom ?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                  <?php } ?></td>
                                                         <td>
                                                         <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_share')) { ?>
                                                  <a href="<?=base_url()?>venue/userapp_share_edit/<?=$value->venue_id."/".$value->id?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                  <?php } ?>
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_share')) { ?>
                                                  <a href="<?=base_url()?>venue/userapp_share_delete/<?=$value->venue_id."/".$value->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                  <?php } ?>
                                                         </td>
                                                    </tr>
                                                  <?php } ?>
                                                  <?php $forwhom="vendor_app"; ?>
                                                  <?php foreach ($vendor_app as $key => $value) {?>
                                                    <tr>
                                                        <td style="padding: 20px;padding-left: 0px"><?= "Vendor App Share" ?></td>
                                                        <td><?= $value->share ?></td>
                                                        <td><?php if($value->status){?>
                                                          
                                                       <a href="<?=base_url()?>venue/share_change_status/<?=$value->venue_id?>/<?=$value->id?>/<?=$value->status?>/<?= $forwhom ?>" type="button" class="btn btn-success">Active</i></a>
                                                  <?php } else{?>
                                                        
                                                       <a href="<?=base_url()?>venue/share_change_status/<?=$value->venue_id?>/<?=$value->id?>/<?=$value->status?>/<?= $forwhom ?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                  <?php } ?></td>
                                                       <td>
                                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_share')) { ?>
                                                  <a href="<?=base_url()?>venue/vendorapp_share_edit/<?=$value->venue_id."/".$value->id?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                  <?php } ?>
                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_share')) { ?>
                                                  <a href="<?=base_url()?>venue/vendorapp_share_delete/<?=$value->venue_id."/".$value->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                  <?php } ?>
                                                      </td>
                                                    </tr>
                                                  <?php } ?>
                                                </tbody>
                                                
                                              </table><br>
                                        </div> 
                                        
                                        <!-- venue rating tab starting -->

                                        <div <?php if(isset($_GET['venue_rating']) ) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> class="tab-pane" id="tab_14">
                                             <div class="box-body">
                                                 
                                                  <table id="example8" class="table table-bordered table-hover" style="width: 50%">
                                                       <thead>
                                                            <tr>
                                                                  <th class="text-center" style="width: 5%">#</th>
                                                                  <th class="text-center" style="width: 15%">Rating</th>
                                                                  <th class="text-center" style="width: 15%">Reason</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                         <?php $i=1; foreach ($venue_rating as $key => $value) { ?>
                                                          <tr>
                                                              <td class="text-center" style="width: 5%"><?=$i?></td>
                                                              <td class="text-center" style="width: 15%"><?=$value['rating']?></td>
                                                              <td class="text-center" style="width: 15%"><?=$value['reason']?></td>
                                                             
                                                          </tr>
                                                        <?php $i++;} ?>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        </div>

                                        <!-- venue rating tab ending -->
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
   $(document).ready(function () {
  
var venues =$('[name=venues]').val(); 
      $.ajax({
      type: "POST",
      url: "<?=base_url('venue/bookeddays')?>",
      data: {venues:venues},
 dataType: "JSON",
     success: function (jsonStr) {

                          $("#resultDiv").text(JSON.stringify(jsonStr[1].date));
                       }
});
if(venues==venues){
       var array = <?php echo json_encode($books); ?>;

$('#datepicker').datepicker({
minDate: 'day',
    beforeShowDay: function(date){
        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        return [ array.indexOf(string) == -1 ]
    }
});
 $('#datepicker1').datepicker({
minDate: 'day',
    beforeShowDay: function(date){
        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        return [ array.indexOf(string) == -1 ]
    }
});
}else{

$('#datepicker').datepicker({
minDate: '0'
});
 $('#datepicker1').datepicker({
minDate: '0'
}); 
}  
});


</script>
<script type="text/javascript">
  $(document).ready(function () {
  
var venues =$('[name=venues]').val(); 
      $.ajax({
      type: "POST",
      url: "<?=base_url('venue/bookeddays')?>",
      data: {venues:venues},
      success: function(result){
        if (result !=0) {
      $('#datepicker').datepicker({
        minDate: '0',
        beforeShowDay: function (dt) {

            var bDisable = result[dt];

            if (bDisable)

               return [false, 'event', 'Booking Exist'];

            else

               return [true, 'event', ''];

        }
    });
    $('#datepicker1').datepicker({
        minDate: '0',
        beforeShowDay: function (dt) {

            var bDisable = result[dt];

            if (bDisable)

               return [false, 'event', 'Booking Exist'];

            else

               return [true, 'event', ''];

        }
    });
    }else{
        $('#datepicker').datepicker({
        minDate: '0'
    });
    $('#datepicker1').datepicker({
        minDate: '0'
       
    });
    }
     
      
  
}});
});
</script>


<script type="text/javascript">

$(document).ready(function() {


  var _URL = window.URL || window.webkitURL;
$("#venue-image").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
         // console.log(this.width);
            //   if((this.width!=900 || this.height!=450) && (this.width!=350 || this.height!=175)&& (this.width!=500 && this.height!=250)){
            //  $("[name=file]").val('');
            // var msg=" Wrong Resolution !";
            //  new PNotify({
            //          title: 'Failed',
            //          text: msg,
            //          type: 'error',
            //          delay : 2000
            //      });
            // }
        };
        img.src = _URL.createObjectURL(file);
    }
});


/*$('[name=morn]').on('change',function(){
  console.log($(this).val());
  $('[name=even]').timepicker({
   minTime: "01:00 AM",
     showInputs: false
 });

});*/
$('#venue_form :checkbox').change(function() {

      console.log('dsfd');

});


$("#holiday-btn").on('click',function(){
  var day = $('[name=day]').val();
  var days = $('[name=days]').val();
  var venue = $('#venue_id').val();
  var desc = $('[name=image-desc]').val();
  //console.log(day);
  if(day!='' && days!=''){

$.ajax({
  type: "POST",
  url: "<?=base_url('venue/holiday')?>",
  data: {day:day,days:days,venue:venue,desc:desc},
  success: function(resultData){
    //console.log(resultData);
    if (resultData=="booking") {
      var msg=" Booking Exist  !";
      new PNotify({
              title: 'Success',
              text: msg,
              type: 'error',
              delay : 2000
          });
    }else{
             if (resultData=="alreadyadded") {
            var msg=" Holiday al-ready added !";
            new PNotify({
                    title: 'Success',
                    text: msg,
                    type: 'error',
                    delay : 2000
                });
          }else{
             var msg=" Holiday Added !";
            new PNotify({
                    title: 'Success',
                    text: msg,
                    type: 'success',
                    delay : 2000
                });
          }
    }
    $('.datatable-holiday').load(document.URL +  ' .datatable-holiday');
$('.datatable-inactive').load(document.URL +  ' .datatable-inactive');
  },
  dataType: 'text'
});
}
});

 
 
 



    $(".datatable-holiday").DataTable( {dateFormat: 'yyyy-mm-dd'});
 
   $(".close-btn").on('click',function(event){
     event.stopPropagation();
var image=$(this).next().attr('id');
$(this).parent().remove();

      $.ajax({
    url: "<?=base_url()?>ajax/delete_venue_image/"+image,
   context: document.body
}).done(function() {

});

   });

    $('[name=image-desc]').on('change',function(){
       var desc=$(this).val();
       var id=$(this).closest('a').attr('id');
            $.ajax({
    url: "<?=base_url()?>ajax/add_venue_image_desc/"+desc+"/"+id,
    beforeSend: function( xhr ) {
   console.log('send');
  },
   context: document.body
}).done(function() {
 //$('#gallery').load(document.URL +  ' #gallery');
});
    });
/*   (function() {

    // how many milliseconds is a long press?
    var longpress = 750 ;
    // holds the start time
    var start;

 jQuery( ".picture" ).on( 'click', function( event ) {
       event.stopPropagation();

    } );
    jQuery( ".picture" ).on( 'mousedown', function( event ) {

        start = new Date().getTime();
    } );

    jQuery( ".picture" ).on( 'mouseleave', function( event ) {

        start = 0;
    } );

    jQuery( ".picture" ).on( 'mouseup', function( event ) {
       event.stopPropagation();
        if ( new Date().getTime() >= ( start + longpress )  ) {
           $('.gallery').css('-webkit-animation','shake 0.3s ease-in-out 0.1s infinite alternate')
       $('.close-btn').css('visibility','visible');
        } else {

        }
    } );
    $('body').on('click',function(event){
      $('.close-btn').css('visibility','hidden');
     $('.gallery').css('-webkit-animation','');
    });

}());*/

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
<script>
  function initMap() {
    var uluru = {lat: <?=$venue->lat;?>, lng: <?=$venue->lon;?>};
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
