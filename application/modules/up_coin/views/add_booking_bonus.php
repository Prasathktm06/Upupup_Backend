 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
   
    function check_booking_setting(){
        var city = document.getElementById('city').value;
        var start_date = document.getElementById('datepicker2').value;
        //alert(city);
        //alert(start_date);
        var url_new="up_coin/booking_bonus_exists";
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
          <h1>Booking Bonus Settings</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                               <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("up_coin/add_booking_bonus_setting")?>" method="post" id="venue_form">
                                                  <div class="box-body">
                                                   
                                                     <div class="form-group">
                                                            <label class="col-sm-2 control-label">City </label>
                                                            <div class="col-sm-6">
                                                                  <select name="city" id="city" class="form-control select2"  data-placeholder="Select a City" style="width: 100%;"  required="required" >
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
                                                                                <input name="edate" type="text" class="form-control " id="datepicker3"   required="required" onChange="check_booking_setting()">
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
                                                            <label for="lon" class="col-sm-2 control-label">Coin</label>
                                                            <div class="col-sm-6">
                                                            <input value="" type="number" name="coin" id="coin" class="form-control"  placeholder="Coin"  value="1" max="100000" min="1"  required="required">
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

