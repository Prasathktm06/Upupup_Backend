<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

<h1>

Area



</h1>




</section>

<section class="content">

<div class="row">

<div class="col-xs-12">

<div class="box">

<div class="box-header">

<h3 class="box-title">Edit</h3>



</div>



<div class="box-body">

<form class="form-horizontal" action="<?=base_url("places/area_edit/$id")?>" method="post">

<div class="box-body">

<div class="form-group">

<label for="area" class="col-sm-2 control-label">Area</label>



<div class="col-sm-6">

<input type="text" name="area" id="area" class="form-control"  placeholder="Area Name" value="<?= $area[0]->area?>" required="required">

</div>

</div>



<div class="form-group">

<label class="col-sm-2 control-label">Locations</label>

<div class="col-sm-6">

<select name="location" class="form-control select2" style="width: 100%;" >

<?php foreach ($location as $val):?>

                  <option value="<?=$val->id?>"><?=$val->location?></option>

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

 

 

</section>



</div>









