<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">

function showDiv(elem){
var sportcount=$('select option:selected').length;

if(sportcount > 2){
      $("#court option").remove();
      document.getElementById('courtstec').style.display = "none";
}else{
if(sportcount == 2){
      var venue = "<?=$venue_offer ?>";
      var sports = $( "#sports" ).val();
      $("#court option").remove();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>offer/court_list/<?=$venue_offer ?>",
            dataType: 'json',
            data: {sports: sports},
           
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
          <h1>Offer</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                              <form class="form-horizontal" action="<?=base_url("offer/add_offer/$venue_offer")?>" method="post" enctype="multipart/form-data">
                                   <div class="box-body">
                                        <div class="form-group">
                                             <label for="sports" class="col-sm-2 control-label">Offer</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="offer" class="form-control"  placeholder="Offer" value="" required="required">
                                             </div>
                                        </div>
                                       
 
                                          
                                           <div class="form-group" name="radiowithtexbox">
                                                <label for="lon" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-6 ">

                                                <strong style="padding-left: 50px;padding-right: 20px;">Percentage</strong><input type="Radio" name="radio2text" value="Radiobutton1" 
onclick="javascript:radioWithText('one')"   checked="checked"  />


                                                </div>
                                                 <label for="lon" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-6 ">

                                              <div id="one" style="display:visible;">
     <label for="sports" class="col-sm-4 control-label">%</label>
<input type="hidden" name="percentage" value="0">
      <div class="col-sm-3">
        <input type="number" name="percentage" min="1" max="100" class="form-control"  placeholder="Percentage" value="" required>
      </div>
</div>

                                                </div>
                                                


                                                </div>
                                        
                                         
                                                          <div class="form-group" >
                                                            <label class="col-sm-2 control-label">Sports</label>
                                                            <div class="col-sm-6">
                                                               <select name="sports[]" id="sports" class="form-control select2" multiple="multiple" data-placeholder="Select a Sports" style="width: 100%;" onchange="showDiv(this)" required>
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
                                                  <select class="form-control select2" name="court[]" multiple="multiple" id="court" style="width: 100%;"  required>
                                                       
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
                                    <label class="col-sm-2 control-label">Status </label>
                                    <div class="col-sm-6">
                                       <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                          <option  value="1">Active</option>
                                          <option value="0">Inactive</option>
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
<script type="text/javascript" language="JavaScript">
function radioWithText(d) {
    document.getElementById('one').style.display = "none";
    document.getElementById('two').style.display = "none";
    document.getElementById(d).style.display='inline'; 
}
</script>
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
