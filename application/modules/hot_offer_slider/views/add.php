<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Sports

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
<form class="form-horizontal" action="<?=base_url('hot_offer_slider/slider')?>" method="post" enctype="multipart/form-data">
<div class="box-body">
<div class="form-group">
<label for="sports" class="col-sm-2 control-label"> Hot Slider</label>

<div class="col-sm-6">
<input type="text" name="slider" id="slider" class="form-control"  placeholder="slider Name" value="" required="required">
</div>
</div>
    <div class="form-group" >
           <label for="sports" class="col-sm-2 control-label">Image Upload </label>
           <div class="col-sm-6">
                <input type='file' onchange="readURL(this);" name="file"  accept="image/x-png,image/gif,image/jpeg"/ required>
                <img id="blah"   alt="image" />
                
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
	 function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(100);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>



