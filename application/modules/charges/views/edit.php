<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>Service Charges</h1>


</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Edit</h3>

</div>

<div class="box-body">
    <form class="form-horizontal" action="<?=base_url("charges/edit/$charge->id")?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                                        <div class="form-group">
                                             <label for="name" class="col-sm-2 control-label">City</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="city" class="form-control"  placeholder="City" value="<?php echo $charge->location ?>" required="required">
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <label for="venue" class="col-sm-2 control-label">Amount</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="amount" class="form-control"  placeholder="Amount" value="<?php echo $charge->amount ?>" required="required">
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
