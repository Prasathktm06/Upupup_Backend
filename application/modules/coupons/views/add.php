<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
     $(function() {
          $('input[name="type"]').on('click', function() {
               if ($(this).val() == 'No') {
                    $('#currency').show();
                    $("select").prop('required',true);
               }
               else if ($(this).val() == 'Yes')  {
                    $('#currency').hide();
                    $("select").prop('required',false);
                    //$('#currency').removeAttr('required');
                    //document.getElementById("currency").removeAttribute("required");
               }
          });
     });
</script>

<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<div class="content-wrapper">
     <section class="content-header">
          <h1>Coupon</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>

                         <div class="box-body">
                              <form class="form-horizontal" action="<?=base_url('coupons/add')?>" method="post">
                                   <div class="box-body">
                                        <div class="form-group">
                                             <label for="sports" class="col-sm-2 control-label">Percentage</label>
                                             <div class="col-sm-6">
                                                  <input type="radio" name="type" value="Yes" checked="checked"> Yes
                                                  <input type="radio" name="type" value="No"> No
                                             </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">City</label>
                                            <div class="col-sm-6">
                                             <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                             <option selected disabled>Select a City</option>
                                             <?php foreach ($locations as $val):?>
                                               <option value="<?= $val->id?>"><?= $val->location?></option>
                                               <?php endforeach;?>
                                             </select>
                                            </div>
                                           </div>
                                       
                                           <div class="form-group">
                                            <label class="col-sm-2 control-label">Area</label>
                                            <div class="col-sm-6">
                                             <select id="area" class="form-control select2" name="area" style="width: 100%;">
                                             </select>
                                            </div>
                                           </div>
                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Coupon Value</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="coupon_value" class="form-control"  placeholder="Coupon Value"  required="required" onkeypress="return isNumber(event)">
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Coupon Code</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="coupon_code" class="form-control"  placeholder="Coupon Code"  required="required" >
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Description<span class="text-danger">(Maximum description length should be 300)</span></label>
                                                  <div class="col-sm-6">
                                                       <input type="text" name="description" class="form-control"  placeholder="Description"  required="required" maxlength="300" required>
                                                  </div>

                                        </div>

                                        <div class="form-group" id="currency" style="display: none">
                                             <label class="col-sm-2 control-label">Select Currency</label>
                                             <div class="col-sm-6">
                                                  <select class="form-control select2" name="currency"  style="width: 100%;" >
                                                      
                                                       <option value="INR">INR</option>
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
                                                <input name="start" type="text" class="form-control " id="datepicker2"   required="required" value="<?=$value['valid_from']?>">
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
                                                        <input name="end" type="text" class="form-control " id="datepicker3"  required="required" value="<?=$value['valid_to']?>">
                                                    </div>
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