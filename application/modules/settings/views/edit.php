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
                              <?php foreach ($details as $key => $value) { ?>
                              <form class="form-horizontal" action="<?=base_url()?>coupons/edit/<?=$value['coupon_id']?>" method="post">
                                   <div class="box-body">
                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Coupon Value</label>
                                                  <div class="col-sm-6">
                                                       <input type="text" name="coupon_value" class="form-control"  placeholder="Coupon Value"  required="required" onkeypress="return isNumber(event)" value="<?=$value['coupon_value']?>">
                                                  </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Coupon Code</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="coupon_code" class="form-control"  placeholder="Coupon Code"  required="required" value="<?=$value['coupon_code']?>">
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Description<span class="text-danger">(Maximum description length should be 300)</span></label>
                                                  <div class="col-sm-6">
                                                       <input type="text" name="description" class="form-control"  placeholder="Description"  required="required" maxlength="300" required value="<?=$value['description']?>">
                                                  </div>

                                        </div>

                                        <div class="form-group">
                                             <label class="col-sm-2 control-label">Date:</label>
                                             <div class="col-sm-6">
                                                  <div class="input-group date">
                                                       <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                       </div>
                                                       <input name="date" type="text" class="form-control pull-right" id="reservation" required value="<?=$value['date']?>">
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="box-footer">
                                        <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                        <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                   </div>
                              </form>
                              <?php } ?>
                         </div> 
                    </div>
               </div>
          </div>
     </section>
</div>




