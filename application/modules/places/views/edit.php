<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

<h1>

City



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

<form class="form-horizontal" action="<?=base_url("places/location_edit/$id")?>" method="post">

<div class="box-body">

<div class="form-group">

<label for="location" class="col-sm-2 control-label">City</label>



<div class="col-sm-6">

<input type="text" name="location" id="location" class="form-control"  placeholder="City Name" value="<?= $location[0]->location?>" required="required">

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









