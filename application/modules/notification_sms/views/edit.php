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
<form class="form-horizontal" action="<?=base_url("offer/edit/$details->id/")?>" method="post" enctype="multipart/form-data">
<div class="box-body">
<?php //$details->start."-".$details->end; exit; ?>
<div class="form-group">
<label for="sports" class="col-sm-2 control-label">Offer</label>
<div class="col-sm-6">
<input type="text" name="offer" class="form-control"  placeholder="Name" value="<?=$details->offer?>" required="required">
</div>
</div>
<div class="form-group">
<label for="sports" class="col-sm-2 control-label">%</label>
<div class="col-sm-6">
<input type="number" name="percentage" min="1" max="100" class="form-control"  placeholder="Name" value="<?=$details->percentage?>" required="required">
</div>
</div>
 <div class="form-group">
             <label class="col-sm-2 control-label">Court</label>
             <div class="col-sm-6">
              <select class="form-control select2" name="court[]" id="court" style="width: 100%;" required multiple="">
                  <?php foreach ($court as $val):?>
                 <option <?php if(in_array($val->id,explode(',',$offer_court->court_id))) echo "selected";?> value="<?= $val->id?>" ><?= $val->court?></option>
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
                   <input name="date" type="text" class="form-control pull-right" id="reservation"
                   value="<?= $details->start." - ".$details->end?>">
                </div>
                </div>
                <!-- /.input group -->
              </div>
<div class="form-group">
<label for="sports" class="col-sm-2 control-label">Image</label>
<div class="col-sm-6">
<input type="button" name="upload" class="form-control" value="Image" style="background-image: url('<?= $details->image?>'); background-size: 100%;background-repeat: no-repeat; width: 150px;height: 94px">
 <input name="file" type="file" id="exampleInputFile" class="upload" accept="image/x-png,image/gif,image/jpeg" style="display: none;">
</div>
</div>


          
<input type="hidden" name="venue" value="<?=$details->venue_id ?>">

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
$('[name=upload]').on('click',function(){
	$('#exampleInputFile').click();
});
</script>




