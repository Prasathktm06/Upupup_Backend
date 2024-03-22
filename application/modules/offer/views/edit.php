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
<h3 class="box-title">Edit</h3>

</div>

<div class="box-body">
<form class="form-horizontal" action="<?=base_url("offer/offer_edit/$details->id/$venue")?>" method="post" enctype="multipart/form-data">
<div class="box-body">
<?php //$details->start."-".$details->end; exit; ?>
<div class="form-group">
<label for="sports" class="col-sm-2 control-label">Offer</label>
<div class="col-sm-6">
<input type="text" name="offer" class="form-control"  placeholder="Offer" value="<?= $details->offer ?>" required="required" readonly>
</div>
</div>
<?php
if($details->amount !=0){
 $amountcheck = 'checked';
 $percentagecheck = 'unchecked';
 $amovisible='visible'; 
 $pervisible='none';
}
if($details->percentage !=0){
 $percentagecheck = 'checked';
$amountcheck = 'unchecked';
$amovisible='none'; 
 $pervisible='visible'; 
}

  ?>
  <div class="form-group">
                                                            <label class="col-sm-2 control-label">Court</label>
                                                            <div class="col-sm-6">
                                                                
                                                                      <?php foreach ($court as $val):?>
                                                                      <div class="col-sm-3 ">
                                                                      <input type="text" name="courtoff[]" value="<?=$val->court?>" readonly>
                                                                      </div>
                                                                      <?php endforeach;?>

                                                                
                                                            </div>
                                                       </div>
 <?php foreach ($court as $val):?>
                                                                      
                                                                      <input type="hidden" name="court[]" value="<?=$val->id?>" readonly>
                                                                     
                                                                      <?php endforeach;?>
                                                    
<div class="form-group" name="radiowithtexbox">
                                                <label for="lon" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-6 ">

                                                <strong style="padding-left: 50px;padding-right: 20px;">Percentage</strong><input type="Radio" name="radio2text" value="Radiobutton1" 
onclick="javascript:radioWithText('one')"  checked="<?= $percentagecheck ?>" <?= $amountcheck?>  />




                                                
                                                </div>
                                                 <label for="lon" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-6 ">

                                              <div id="one" style="display:<?= $pervisible?>;">
     <label for="sports" class="col-sm-4 control-label">%</label>
<input type="hidden" name="percentage" value="0">
      <div class="col-sm-3">
        <input type="number" name="percentage" min="1" max="100" class="form-control"   value="<?= $details->percentage ?>" readonly>
      </div>
</div>

                                                </div>
                                                


                                                </div>
 
 
                                       <div class="form-group">
                                                         <label class="col-sm-2 control-label">From Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="day" type="text" class="form-control " id="datepicker2"  value="<?=  date('d-m-Y', strtotime($details->start)) ?>" readonly>
                                                                           </div>
                                                                      </div>
                                                                       <label class="col-sm-1 control-label">To Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="days" type="text" class="form-control " id="datepicker3"  value="<?=  date('d-m-Y', strtotime($details->end)) ?>"  >
                                                                           </div>
                                                                      </div>
                                                                      
                                                                 </div>
                                            <div class="form-group">
                                                                    <label class="col-sm-2 control-label">From Time :</label>
                                                                      <div class="col-sm-2">
                                                                          <div class="bootstrap-timepicker">
                                                                             
                                                                            <div class="input-group ">
                                                                                <div class="input-group-addon">
                                                                                      <i class="fa fa-clock-o"></i>
                                                                                </div>
                                                                                <input   type="text"  name="stime" value="<?= date("g:i a", strtotime($details->start_time));?>" readonly>
                                                                              </div>
                                                                                  
                                                                          </div>
                                                                     
                                                                      </div>
                                                                      <label class="col-sm-1 control-label">To Time</label>
                                                                      <div class="col-sm-2">
                                                                          <div class="bootstrap-timepicker">
                                                                             
                                                                            <div class="input-group ">
                                                                                <div class="input-group-addon">
                                                                                      <i class="fa fa-clock-o"></i>
                                                                                </div>
                                                                                <input   type="text" class="form-control timepicker" name="etime" value="<?= date("g:i a", strtotime($details->end_time));?>">
                                                                              </div>
                                                                                  
                                                                          </div>
                                                                     
                                                                      </div>
                                                                 </div>
 <?php
                                                           $i=0;
 while(count($days) > $i)

        {

switch(json_encode($days[$i]))
            {
            case '{"day":"Sun"}':
                    $checkedSun = 'checked';
                    break;

            case '{"day":"Mon"}':
                    $checkedMon = 'checked';
                    break;
            case '{"day":"Tue"}':
                    $checkedTue = 'checked';
                    break;
            case '{"day":"Wed"}':
                    $checkedWed = 'checked';
                    break;
            case '{"day":"Thu"}':
                    $checkedThu = 'checked';
                    break;
            case '{"day":"Fri"}':
                    $checkedFri = 'checked';
                    break;
            case '{"day":"Sat"}':
                    $checkedSat = 'checked';
                    break;

               
            }

        $i+=1;
    } 
                                                          ?>       
                                                       <div class="form-group">
                                                           <div class="form-check form-check-inline">
                                                                  <div class="col-sm-1">
                                                                  </div>
                                                                  <input type="hidden" name="sun" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="sun" value="1"  <?=$checkedSun?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Sun</label>
                                                                  </div>
                                                                  <input type="hidden" name="mon" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="mon" value="1" <?= $checkedMon ?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Mon</label>
                                                                  </div>
                                                                  <input type="hidden" name="tue" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="tue" value="1" <?= $checkedTue ?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Tue</label>
                                                                  </div>
                                                                  <input type="hidden" name="wed" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="wed" value="1" <?= $checkedWed ?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Wed</label>
                                                                  </div>
                                                                  <input type="hidden" name="thu" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="thu" value="1" <?= $checkedThu ?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Thu</label>
                                                                  </div>
                                                                  <input type="hidden" name="fri" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="fri" value="1" <?= $checkedFri ?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Fri</label>
                                                                  </div>
                                                                  <input type="hidden" name="sat" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="sat" value="1" <?= $checkedSat ?> >
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Sat </label>
                                                                  </div>
                                                                
  
  
</div>   
                                                       </div>

<input type="hidden" name="venue" value="<?=$details->venue_id ?>">
 




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
