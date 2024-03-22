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
<h3 class="box-title">Edit</h3>

</div>

<div class="box-body">
<form id="form-image" class="form-horizontal" action='<?=base_url("sports/edit/$id")?>' method="post" enctype="multipart/form-data">
<div class="box-body">
<div class="form-group">
<label for="sports" class="col-sm-2 control-label">Sports</label>

<div class="col-sm-6">
<input type="text" name="sports" id="sports" class="form-control"  placeholder="Sports Name" value="<?= $sports[0]->sports?>" required="required">
</div>
</div>
   
<div class="form-group" >
           <label for="sports" class="col-sm-2 control-label">Image Upload </label>
           <div class="col-sm-6">
                <input type='file' onchange="readURL(this);" name="file"  accept="image/x-png,image/gif,image/jpeg"/ required>
                <img id="blah"   alt="image" src="<?=$sports[0]->image?>" width="200" height="100" />                
           </div>
           <h6 class="" style="margin-left: 105px;margin-top: -9px">
                                                       Max size: 5mb <br>
                                                       Res: 250*250 <br>

                                                       </h6>
      </div>
<div class="box-footer">
<a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
<button type="submit" class="btn btn-warning pull-right" >Submit</button>
</div>
</form>
</div>

</div>

</div>
</div>


</section>

</div>


<script type="text/javascript">
  var _URL = window.URL || window.webkitURL;

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
        $("[name=file]").change(function (e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function () {
          console.log(this.width);
            if((this.width!=250 || this.height!=250) ){
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


