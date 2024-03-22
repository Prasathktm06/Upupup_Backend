 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>

<div class="content-wrapper">
     <section class="content-header">
          <h1>Add Notification Settings</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                               <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("hot_offer/add_notification_settings")?>" method="post" id="venue_form">
                                                  <div class="box-body">
                                                   
                                                     <div class="form-group">
                                                            <label class="col-sm-2 control-label">Hot Offer Setting</label>
                                                            <div class="col-sm-6">
                                                                  <select name="city" id="city" class="form-control select2"  data-placeholder="Select a Setting" style="width: 100%;" required="required" >
                                                                      <?php foreach ($city as $val):?>
                                                                      <option <?php if(in_array($val->id,explode(',', $venue_location->location_id)))  ?> value="<?=$val->id?>"><?=$val->location?></option>
                                                                      <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                       </div>

                                                

                                                         <div class="form-group">
                                                           <label class="col-sm-2 control-label">Notification Setting</label>
                                                           <div class="col-sm-6">
                                                            <select class="form-control select2" name="not_setting" id="not_setting" style="width: 100%;" >
                                                                     <?php foreach ($not_setting as $val):?>
                                                                      <option  value="<?=$val->id?>"><?=$val->name?></option>
                                                                      <?php endforeach;?>
                                                             </select>
                                                           </div>
                                                          </div>
                                                          <div class="form-group">
                                                           <label class="col-sm-2 control-label">Time1</label>
                                                           <div class="col-sm-6">
                                                             <div class="bootstrap-timepicker">
                                                                             
                                                                            <div class="input-group ">
                                                                                <div class="input-group-addon">
                                                                                      <i class="fa fa-clock-o"></i>
                                                                                </div>
                                                                                <input required  type="text" class="form-control timepicker" name="time1">
                                                                              </div>
                                                                                  
                                                                          </div>
                                                           </div>
                                                          </div>

                                                           <div class="form-group">
                                                           <label class="col-sm-2 control-label">Time2</label>
                                                           <div class="col-sm-6">
                                                             <div class="bootstrap-timepicker">
                                                                             
                                                                            <div class="input-group ">
                                                                                <div class="input-group-addon">
                                                                                      <i class="fa fa-clock-o"></i>
                                                                                </div>
                                                                                <input required  type="text" class="form-control timepicker" name="time2">
                                                                              </div>
                                                                                  
                                                                          </div>
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



