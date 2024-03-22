 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
     $(function() {
          $('input[name="install_status"]').on('click', function() {
               if ($(this).val() == 0) {
                    $('#currency1').hide();
               }
               else if ($(this).val() == 1)  {
                    $('#currency1').show();
                    //$('#currency').removeAttr('required');
                    //document.getElementById("currency").removeAttribute("required");
               }
          });
     });
</script>
<script type="text/javascript">
     $(function() {
          $('input[name="install_status"]').on('click', function() {
               if ($(this).val() == 0) {
                    $('#currency2').hide();
               }
               else if ($(this).val() == 1)  {
                    $('#currency2').show();
                    //$('#currency').removeAttr('required');
                    //document.getElementById("currency").removeAttribute("required");
               }
          });
     });
</script>
<script type="text/javascript">
     $(function() {
          $('input[name="booking_status"]').on('click', function() {
               if ($(this).val() == 0) {
                    $('#book_bonus').hide();
               }
               else if ($(this).val() == 1)  {
                    $('#book_bonus').show();
                    //$('#currency').removeAttr('required');
                    //document.getElementById("currency").removeAttribute("required");
               }
          });
     });
</script>
<script type="text/javascript">
   
    function check_booking_setting(){
        var city = document.getElementById('city').value;
        var start_date = document.getElementById('datepicker2').value;
        var end_date = document.getElementById('datepicker3').value;
        var url_new="refer_friend/check_booking_setting";
        var base_url="<?php echo base_url(); ?>" ; 
                $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {city: city,start_date: start_date,end_date: end_date},
            success:function (res) {
                
                if(res==0){
                   //alert("zero"); 
                   $('#check_set').hide();
                   document.getElementById('buttonY').checked = true;
                   
                }else{
                  //alert("not zero");
                  $('#check_set').show();
                  
                  document.getElementById('buttonX').checked = true;
                }
            },
        });
        

    }
</script>
<script type="text/javascript">
   
    function check_refer_setting(){
        var city = document.getElementById('city').value;
        var start_date = document.getElementById('datepicker2').value;
        //alert(city);
        //alert(start_date);
        var url_new="refer_friend/check_refer_exist";
        var base_url="<?php echo base_url(); ?>" ; 
                $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {city: city,start_date: start_date},
            success:function (res) {
                
                if(res==0){
                   //alert("zero"); 
                   $('#inactive_set').hide();
                   document.getElementById("buttonX").value = res;
                  document.getElementById("buttonY").value = res;
                   document.getElementById('buttonY').checked = true;
                   
                }else{
                  alert("Al-ready one Active Setting");
                  $('#inactive_set').show();
                  document.getElementById("buttonY").value = 99999;
                  document.getElementById("buttonX").value = res;
                  document.getElementById('buttony').checked = true;
                }
            },
        });
        

    }
</script>
<div class="content-wrapper">
     <section class="content-header">
          <h1>Add Refer a Friend Setting</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                               <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("refer_friend/add_refer_friend_setting")?>" method="post" id="venue_form">
                                                  <div class="box-body">
                                                   
                                                     <div class="form-group">
                                                            <label class="col-sm-2 control-label">City </label>
                                                            <div class="col-sm-6">
                                                                  <select name="city" id="city" class="form-control select2"  data-placeholder="Select a City" style="width: 100%;"  required="required" onChange="check_booking_setting()">
                                                                      <?php foreach ($city as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $venue_location->location_id)))  ?> value="<?=$val->id?>"><?=$val->location?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>

                                                      

                                                       <div class="form-group">
                                                         <label class="col-sm-2 control-label">Start Date </label>
                                                                      <div class="col-sm-6">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="sdate" type="text" class="form-control " id="datepicker2"   required="required" >
                                                                           </div>
                                                                      </div>
                                                                      
                                                                      
                                                        </div>
                                                         <div class="form-group">
                                                         <label class="col-sm-2 control-label">End Date </label>
                                                                      <div class="col-sm-6">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="edate" type="text" class="form-control " id="datepicker3"   required="required" onChange="check_refer_setting()">
                                                                           </div>
                                                                      </div>
                                                                      
                                                                      
                                                        </div>
                                                        <div id="inactive_set" style="display: none;">
                                                            
                                                            <div class="form-group">
                                                                <label for="sports" class="col-sm-2 control-label">Do You want to In-activate the Active setting</label>
                                                                <div class="col-sm-6">
                                                                    <input type="radio" name="status_change"  id='buttonX' > Yes
                                                                    <input type="radio" name="status_change"  id='buttonY' checked> No
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sports" class="col-sm-2 control-label">Installation Bonus Applicable</label>
                                                            <div class="col-sm-6">
                                                                <input type="radio" name="install_status" value=1 checked="checked"> Yes
                                                                <input type="radio" name="install_status" value=0> No
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="currency1" >
                                                            <label for="lon" class="col-sm-2 control-label">Installation Number</label>
                                                            <div class="col-sm-6">
                                                            <input value="" type="number" name="install_no" id="install_no" class="form-control"  placeholder="Installation Number" value="1" max="100000" min="1" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="currency2" >
                                                            <label for="lon" class="col-sm-2 control-label">Installation Bonus Coin</label>
                                                            <div class="col-sm-6">
                                                            <input value="" type="number" name="install_bonus" id="install_bonus" class="form-control"  placeholder="Installation Bonus Coin" value="1" max="100000" min="1" >
                                                            </div>
                                                        </div>
                                                        <div id="check_set">
                                                        <div class="form-group">
                                                            <label for="sports" class="col-sm-2 control-label">Booking Bonus Applicable</label>
                                                            <div class="col-sm-6">
                                                                <input type="radio" name="booking_status" value=1 id='buttonX' checked> Yes
                                                                <input type="radio" name="booking_status" value=0 id='buttonY'checked> No
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="book_bonus" >
                                                            <label for="lon" class="col-sm-2 control-label">Booking Bonus Coin</label>
                                                            <div class="col-sm-6">
                                                            <input value="" type="number" name="booking_bonus" id="booking_bonus" class="form-control"  placeholder="Booking Bonus Coin" value="1" max="100000" min="1" >
                                                            </div>
                                                        </div>
                                                         </div>
                                                         <div class="form-group">
                                                           <label class="col-sm-2 control-label">Status </label>
                                                           <div class="col-sm-6">
                                                            <select class="form-control select2" name="status" id="status" style="width: 100%;" >
                                                                       <option  value="0">Inactive</option>
                                                                    </select>
                                                           </div>
                                                          </div>

                                                       
     
                                                  </div>

                                                  <div class="box-footer">
                                                       <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                                       <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                                  </div>
                                             </form>
                                              <div class="box-body">
                            <div id="venue_count" style="font-size: 20px;color: #ef0012;">
                            </div>  
                         </div>
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


});

</script>

