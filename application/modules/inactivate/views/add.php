<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">

function showDiv(elem){
var sportcount=$('select option:selected').length;


if(sportcount > 1){
      $("#court option").remove();
      document.getElementById('courtstec').style.display = "none";
}else{
if(sportcount == 1){
      var sports = $( "#sports" ).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>inactivate/court_listin/<?=$venue ?>",
            data: {sports: sports},
            dataType: 'json',
            
            success:function (data) { 
                   $('#court').append($('<option></option>'));
                    $.each(data['court'],function(element,val) {
                    $('#court').append($('<option>', { 
                            value: val.courts_id,
                            text : val.court_name 
                        }));
                    }); 
                },
          });

   document.getElementById('courtstec').style.display = "inline";
}   
}

}
</script>
<div class="content-wrapper">
     <section class="content-header">
          <h1>In-activate</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                               <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("inactivate/add_inactive/$venue")?>" method="post" id="venue_form">
                                                  <div class="box-body">
                                                   
                                                     <div class="form-group">
                                                            <label class="col-sm-2 control-label">Sports</label>
                                                            <div class="col-sm-6">
                                                                 <select name="sports[]" id="sports" class="form-control select2" multiple="multiple" data-placeholder="Select a Sports" style="width: 100%;" onchange="showDiv(this)" required >
                                                                      <?php foreach ($sports as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $venue_sports->sports_id))) echo "selected"; ?> value="<?=$val->id?>"><?=$val->sports?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>

                                                       <div id="courtstec" style="display:visible;">
                                                          <div class="form-group" >
                                                            <label class="col-sm-2 control-label">Court</label>
                                                            <div class="col-sm-6">
                                                                <select class="form-control select2" name="court[]" multiple="multiple" id="court" style="width: 100%;"  >
                                                       
                                                                </select>
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
                                                                                <input name="day" type="text" class="form-control " id="datepicker2" required="required" >
                                                                           </div>
                                                                      </div>
                                                                       <label class="col-sm-1 control-label">To Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="days" type="text" class="form-control " id="datepicker3" required="required" >
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
                                                                                <input required  type="text" class="form-control timepicker" name="stime">
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
                                                                                <input required  type="text" class="form-control timepicker" name="etime">
                                                                              </div>
                                                                                  
                                                                          </div>
                                                                     
                                                                      </div>
                                                                 </div>
                                                               

                                                          <br>       
                                                       <div class="form-group">
                                                           <div class="form-check form-check-inline">
                                                                  <div class="col-sm-1">
                                                                  </div>
                                                                  <input type="hidden" name="sun" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="sun" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Sun</label>
                                                                  </div>
                                                                  <input type="hidden" name="mon" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="mon" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Mon</label>
                                                                  </div>
                                                                  <input type="hidden" name="tue" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="tue" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Tue</label>
                                                                  </div>
                                                                  <input type="hidden" name="wed" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="wed" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Wed</label>
                                                                  </div>
                                                                  <input type="hidden" name="thu" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="thu" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Thu</label>
                                                                  </div>
                                                                  <input type="hidden" name="fri" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="fri" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Fri</label>
                                                                  </div>
                                                                  <input type="hidden" name="sat" value="0">  
                                                                  <div class="col-sm-1">
                                                                  <input type="checkbox" name="sat" value="1" checked>
                                                                    <label class="form-check-label" for="inlineCheckbox1">&nbsp;&nbsp;Sat </label>
                                                                  </div>
                                                                
  
  
</div>   
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="desc" class="col-sm-2 control-label">Reason</label>
                                                            <div class="col-sm-6">
                    
                                                                 <textarea id="reason" name="reason" class="form-control" rows="1" placeholder="Reason for inactivation" maxlength="256"></textarea>
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
 var x=1;
$( document ).ready(function() {

  $('#datepicker2').datepicker({
minDate: '0',
 onSelect: function(dateStr) 
        {         
             var start_date= $("#datepicker3").val(dateStr);
            $('#datepicker3').datepicker('option', 'minDate', dateStr);
        }

});
 $('#datepicker3').datepicker({
minDate: '0',
});
$('.add-more').on('click',function(){
   if(x < 3){
  var div='<div class="bootstrap-timepicker"><div class="form-group"> <label class="col-sm-2 control-label">Time</label><div class="col-sm-6"> <div class="input-group ">  <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>      <input value="" type="text" class="form-control timepicker" name="start_time[]"> <input value="" type="text" class="form-control timepicker" name="end_time[]"></div></div>   <button type="button" id="" class="btn btn-default btn-sm fa fa-minus minus-button " style="margin-left:143px;margin-top:-58px"></button>        </div>   </div>';
    $("#add-more-div").append(div);
    $(".timepicker").timepicker({
      showInputs: false
    });
    x++;
  }
});

  var _URL = window.URL || window.webkitURL;
$("[name=file]").change(function (e) {
  console.log('asdsad');
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
 $(this).parent().parent().remove();

});
</script>
<script type="text/javascript">
$( window ).on( "load", function() {
$('#reservation2').daterangepicker({

    "minDate": "<?=date('m/d/Y')?>"
}, function(start, end, label) {
 // console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
});
});
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
