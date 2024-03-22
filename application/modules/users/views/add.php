<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Users

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
              <li class="active"><a href="#tab_1" data-toggle="tab">Users</a></li>
              <li></li>
			  </ul>
<form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("users/add/")?>" method="post" >
<div class="tab-content">

<div class="tab-pane active" id="tab_1">

<div class="box-body">
<div class="form-group">
<label for="venue" class="col-sm-2 control-label">Users</label>
<div class="col-sm-6">
<input type="text" name="user" id="user" class="form-control"  placeholder=" Name" value="" required="required">
</div>
</div>

  <div class="form-group">
      <label class="col-sm-2 control-label">Phone Number</label>
 	<div class="col-sm-6">
    <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-phone"></i>
        </div>
     <input value="" id="phone" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999 ', '+9999999999']" data-mask>
    </div>
	</div>
 </div>
 
 	 <div class="form-group">
      <label for="address" class="col-sm-2 control-label">Address</label>
      <div class="col-sm-6">
      <textarea id="address" name="address" class="form-control" rows="3" placeholder="Address ..."></textarea>
     </div> 
	</div>
	
	 <div class="form-group">
                <label class="col-sm-2 control-label">Sports</label>
                <div class="col-sm-6">
                <select name="sports[]" id="user-add-sports" class="form-control select2" multiple="multiple" data-placeholder="Select a Sport" style="width: 100%;">
                  <?php foreach ($sports as $val):?>
                  <option  value="<?=$val->id?>"><?=$val->sports?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
              
     <div class="form-group">
     <label class="col-sm-2 control-label">City</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="location" id="location" style="width: 100%;">
      <option selected disabled value=""></option>
          <?php foreach ($location as $val):?>
         <option  value="<?= $val->id?>"><?= $val->location?></option>
      	<?php endforeach;?>
      </select>
     </div>
    </div>
     <div class="form-group">
     <label class="col-sm-2 control-label">Area</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="area[]" id="area" style="width: 100%;" multiple="multiple">
         <option value=""></option>
      </select>
     </div>
    </div>	   
	

  
  <div class="form-group">
  <label for="lon" class="col-sm-2 control-label">Image Upload!</label>
  <div class="col-sm-6">
  <img  class="img-circle" id="upupup_image" src="<?=base_url('pics/venue/upupup.png')?>" style="width: 200px;height: 97px;margin-left: 30%">
  
   <input name="file" type="file" id="exampleInputFile" class="upload" accept="image/x-png,image/gif,image/jpeg" style="display: none;">

  </div>
  </div>


</div>

<div class="box-footer">
<a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
<a href="#tab_2" id="add-user-tab_2" class="btn btn-warning pull-right" data-toggle="tab">Co Players</a>
</div>

</div>
<div class="tab-pane" id="tab_2">
<div class="box-body" id="div-add-more">
 <div class="form-group" >
                <label class="col-sm-2 control-label">Sports</label>
                <div class="col-sm-6">
                <select name="co-sports[]" class="form-control select2" id="add-user-sports" data-placeholder="Select a Sport" style="width: 100%;">
                 <option  value="" selected disabled>Select a Sport</option>
                  <?php foreach ($sports as $val):?>
                  <option value="<?=$val->id?>"><?=$val->sports?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
               <div class="form-group" >
                <label class="col-sm-2 control-label">Users</label>
                <div class="col-sm-6">
                <select name="coplayers[0][]" class="form-control select2" id="add-user-sports" multiple="multiple"  style="width: 100%;">
                  <?php foreach ($co_players as $val):?>
                  <option value="<?=$val->id?>"><?=$val->name?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
    
</div>
<button type="button" id="add-more" class="btn btn-danger btn-sm fa fa-plus" style="margin-left:700px;"></button>
<div class="box-footer">
<a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
<button type="submit" class="btn btn-warning pull-right">Submit</button>
</div>
              </div>
              
            </div>
            </form>
          </div>

</div>

</div>

</div>
</div>


</section>

</div>

<script type="text/javascript">
var x=1;
$(document).ready(function() {
$('#add-more').click(function(e){ //on add input button click
       e.preventDefault();
       if(x < 10){ //max input box allowed
           //text box increment
           var div=' <div class="form-group" ><label class="col-sm-2 control-label">Sports</label>   <div class="col-sm-6">           <select name="co-sports[]" class="form-control sport-select"  data-placeholder="Select a Sport" style="width: 100%;">            <option  value="" selected disabled>Select a Sport</option>             <?php foreach ($sports as $val):?>          <option value="<?=$val->id?>"><?=$val->sports?></option>        <?php endforeach;?>        </select>          </div>         </div>          <div class="form-group" >           <label class="col-sm-2 control-label">Users</label>           <div class="col-sm-6">           <select name="coplayers['+x+'][]" class="form-control player-select" id="add-user-sports" multiple="multiple" Select a Sport" style="width: 100%;">       <?php foreach ($co_players as $val):?>         <option value="<?=$val->id?>"><?=$val->name?></option>       <?php endforeach;?>         </select>         </div>      </div>';
                  $('#div-add-more').append(div); //add input box
                  x++;
       }
       $("body .sport-select").select2({});
       $("body .player-select").select2({});
});
});
</script>


