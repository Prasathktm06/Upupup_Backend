 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>

<div class="content-wrapper">
     <section class="content-header">
          <h1>Buy Coin Settings</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                               <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("up_coin/add_buycoin_setting")?>" method="post" id="venue_form">
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
                                                                                <input name="sdate" type="text" class="form-control " id="datepicker2"   required="required">
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
                                                                                <input name="edate" type="text" class="form-control " id="datepicker3"   required="required">
                                                                           </div>
                                                                      </div>
                                                                      
                                                                      
                                                        </div>
                                                      
                                                         <div class="form-group">
                                                            <label for="lon" class="col-sm-2 control-label">Rupee</label>
                                                            <div class="col-sm-6">
                                                            <input value="" type="number" name="rupee" id="rupee" class="form-control"  placeholder="Rupee" value="1" max="100000" min="1" required="required">
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
                                                                       <option  value="1">Active</option>
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

