<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>Offer</h1>


</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Edit</h3>

</div>

<div class="box-body">
    <form class="form-horizontal" action="<?=base_url("shop/offer_edit/$offer->id/$offer->shop_id")?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                                        <div class="form-group">
                                             <label for="name" class="col-sm-2 control-label">Offer Name</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="name" class="form-control"  placeholder="Program Name" value="<?=$offer->name?>" required="required">
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <label for="venue" class="col-sm-2 control-label">Percentage</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="amount" class="form-control"  placeholder="Percentage" value="<?=$offer->amount?>" required="required">
                                             </div>
                                        </div>
                                       <div class="form-group">
                                                         <label class="col-sm-2 control-label">From Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="day" type="text" class="form-control " id="datepicker2" value="<?=  date('d-m-Y', strtotime($offer->start_date)) ?>" required="required" >
                                                                           </div>
                                                                      </div>
                                                                       <label class="col-sm-1 control-label">To Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="days" type="text" class="form-control " id="datepicker3" value="<?=  date('d-m-Y', strtotime($offer->end_date)) ?>" required="required" >
                                                                           </div>
                                                                      </div>
                                                                      
                                        </div>

                                                       <div class="form-group">
                                                            <label class="col-sm-2 control-label">Status </label>
                                                            <div class="col-sm-6">
                                                                 <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                                                      <option <?php if($offer->status==1)echo "selected";  ?> value="1">Active</option>
                                                                      <option <?php if($offer->status==0)echo "selected"; ?> value="0">Inactive</option>
                                                                 </select>
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
    minDate:$("#datepicker2").val(),
    maxDate:$("#datepicker2").val()

    });
$('#datepicker3').datepicker({
    minDate: $("#datepicker3").val(), 
    });
});
$('[name=upload]').on('click',function(){
  $('#exampleInputFile').click();
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
<script type="text/javascript">
 var x=1;
$( document ).ready(function() {

  $('#datepicker2').datepicker({
      autoclose: true
    });
$('.add-more').on('click',function(){
   if(x < 3){
  var div='<div class="bootstrap-timepicker"><div class="form-group"> <label class="col-sm-2 control-label">Time</label><div class="col-sm-6"> <div class="input-group ">  <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>      <input value="" type="text" class="form-control timepicker" name="start_time[]"> <input value="" type="text" class="form-control timepicker" name="end_time[]"></div></div>   <button type="button" id="" class="btn btn-default btn-sm fa fa-minus minus-button " style="margin-left:111px;margin-top:10px"></button>        </div>   </div>';
    $("#add-more-div").append(div);
    $(".timepicker").timepicker({
      showInputs: false
    });
    x++;
  }
});
var _URL = window.URL || window.webkitURL;
$("[name=file]").change(function (e) {

  var file, img;
  if ((file = this.files[0])) {
      img = new Image();
      img.onload = function () {
       // console.log(this.width);
          if(this.width!=900 || this.height!=450){
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
$(document).on('click', '.minus-button', function(e) {
  x=x-1;
  $(this).parent().remove();
 //$(this).parent().parent().remove();

});
$( window ).on( "load", function() {
$('#reservation2').daterangepicker({

    "minDate": "<?=date('m/d/Y')?>"
}, function(start, end, label) {

});
});
</script>
