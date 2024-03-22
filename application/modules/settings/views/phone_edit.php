<div class="content-wrapper">
     <section class="content-header">
          <h1>Email </h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Edit</h3>
                         </div>
                         <div class="box-body">
                              <?php foreach ($details as $key => $value) { ?>
                              <form class="form-horizontal" action="<?=base_url()?>settings/phone_edit/<?=$value['id']?>" method="post">
                                   <div class="box-body">
                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Phone</label>
                                                  <div class="col-sm-6">
                                                       <input type="number" name="phone" class="form-control"  placeholder="Phone"  required="required"  value="<?=$value['phone']?>">
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




