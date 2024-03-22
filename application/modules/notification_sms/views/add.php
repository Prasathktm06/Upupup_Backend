<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Offer

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
<form class="form-horizontal" action="<?=base_url("offer/add/$venue_offer")?>" method="post" enctype="multipart/form-data">
<div class="box-body">

<div class="form-group">
<label for="sports" class="col-sm-2 control-label">Offer</label>
<div class="col-sm-6">
<input type="text" name="offer" class="form-control"  placeholder="Name" value="" required="required">
</div>
</div>
<div class="form-group">
<label for="sports" class="col-sm-2 control-label">%</label>
<div class="col-sm-6">
<input type="number" name="percentage" min="1" max="100" class="form-control"  placeholder="Name" value="" required="required">
</div>
</div>
 <div class="form-group">
             <label class="col-sm-2 control-label">Court</label>
             <div class="col-sm-6">
              <select class="form-control select2" name="court[]" id="court" style="width: 100%;" required multiple="">
                  <?php foreach ($court as $val):?>
                 <option  value="<?= $val->id?>" ><?= $val->court?></option>
                <?php endforeach;?>
              </select>
             </div>
            </div> 

     <div class="form-group">
                <label class="col-sm-2 control-label">Date:</label>
        <div class="col-sm-6">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                   <input name="date" type="text" class="form-control pull-right" id="reservation">
                </div>
                </div>
                <!-- /.input group -->
              </div>
          

  <?php if($venue_offer==""){ ?>
  <div class="form-group">
     <label class="col-sm-2 control-label">Select a Venue</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="venue" id="venue" style="width: 100%;">
      
      <?php foreach ($venue as $val):?>
        <option value="<?= $val->id?>"><?= $val->venue?></option>
        <?php endforeach;?>
     </select>
     </div>
  </div>
<?php } ?>
<div class="form-group">
<label for="venue" class="col-sm-2 control-label">Image Upload!</label>
<div class="col-sm-1 fileUpload btn btn-info btn-sm">
 <span>Image</span>
 <input name="file" type="file" id="exampleInputFile" class="upload" accept="image/x-png,image/gif,image/jpeg">
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
 
 
</section>

</div>
<script type="text/javascript">
$( document ).ready(function() {
  $('#datepicker2').datepicker({
      autoclose: true
    });
});
</script>




