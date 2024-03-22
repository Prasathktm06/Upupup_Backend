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
          <ol class="breadcrumb">
               <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="#">Tables</a></li>
               <li class="active">Simple</li>
          </ol>
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
                                                       <option value=""></option>
                                                       <option value="INR">INR</option>
                                                  </select>
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <label class="col-sm-2 control-label">Date:</label>
                                             <div class="col-sm-6">
                                                  <div class="input-group date">
                                                       <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                       </div>
                                                       <input name="date" type="text" class="form-control pull-right" id="reservation" required>
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
$( document ).ready(function() {
  $('#datepicker2').datepicker({
      autoclose: true
    });
});
</script>




