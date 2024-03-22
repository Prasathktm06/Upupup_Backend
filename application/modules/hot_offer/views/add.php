 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
   
    function venue_list_count(){
        var city = document.getElementById('city').value;
        var dates = document.getElementById('datepicker2').value;
        var percentage = document.getElementById('percentage').value;
        //var sports = selectObject.value;
        //alert(city);
        //alert(dates);
        //alert(percentage);
        var url_new="hot_offer/venue_list_count";  
        var base_url="<?php echo base_url(); ?>" ;
        $.ajax({
            type:"POST",
            url:base_url+url_new,
            dataType: 'json',
            data: {city: city,dates: dates,percentage: percentage},
            success:function (res) {
                if (res) {
                    var div = document.getElementById('venue_count');
                    div.innerHTML = res;
                }
            },
        });
        //alert(value);
    }
</script>
<div class="content-wrapper">
     <section class="content-header">
          <h1>Add Hot Offer Settings</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-8">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                               <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("hot_offer/add_settings")?>" method="post" id="venue_form">
                                                  <div class="box-body">
                                                   
                                                     <div class="form-group">
                                                            <label class="col-sm-2 control-label">City </label>
                                                            <div class="col-sm-6">
                                                                  <select name="city[]" id="city" class="form-control select2"  data-placeholder="Select a City" style="width: 100%;"  onChange="venue_list_count()" required="required" >
                                                                      <?php foreach ($city as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $venue_location->location_id)))  ?> value="<?=$val->id?>"><?=$val->location?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>

                                                      

                                                       <div class="form-group">
                                                         <label class="col-sm-2 control-label">Date </label>
                                                                      <div class="col-sm-6">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="day" type="text" class="form-control " id="datepicker2"  onChange="venue_list_count()" required="required">
                                                                           </div>
                                                                      </div>
                                                                      
                                                                      
                                                                 </div>
                                                      <div class="form-group">
                                                          <label for="sports" class="col-sm-2 control-label">Name </label>
                                                             <div class="col-sm-6">
                                                                  <input type="text" name="name" class="form-control"  placeholder="Name" value="" required="required">
                                                             </div>
                                                      </div>

                                                        

                                                        <div class="form-group">
                                                            <label for="lon" class="col-sm-2 control-label">Hot Offer %</label>
                                                            <div class="col-sm-6">
                                                            <input value="" type="number" name="percentage" id="percentage" class="form-control"  placeholder="Hot Offer %"  onChange="venue_list_count()" value="1" max="100" min="1"  required="required">
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
              <div class="col-xs-4 text-center" >
                <div class="box" style="height: 430px;">
                         <div class="box-header">
                             
                         </div>
                         <div class="box-body">
                            <div id="venue_count" style="font-size: 20px;color: #ef0012;">
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
 

});


});

</script>

