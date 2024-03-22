<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>


<div class="content-wrapper">
     <section class="content-header">
          <h1>Service Charges</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                              <form class="form-horizontal" action="<?=base_url("charges/add")?>" method="post" enctype="multipart/form-data">
                                   <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">City </label>
                                                <div class="col-sm-6">
                                                    <select name="city" id="city" class="form-control select2"  data-placeholder="Select a City" style="width: 100%;"  required="required" >
                                                        <?php foreach ($locations as $val):?>
                                                            <option <?php if(in_array($val->id,explode(',', $venue_location->location_id)))  ?> value="<?=$val->id?>"><?=$val->location?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                             <label for="venue" class="col-sm-2 control-label">Amount</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="amount" class="form-control"  placeholder="Amount" value="" required="required">
                                             </div>
                                        </div>

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
