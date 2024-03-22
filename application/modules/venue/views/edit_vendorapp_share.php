<div class="content-wrapper">
     <section class="content-header">
          <h1>Vendor App Share</h1>
     </section>
                                                   
             
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Edit</h3>
                         </div>
                         <div class="box-body">
                           <form  enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("venue/share_vendorapp_edit")?>" method="post" id="venue_form">

                            <div class="box-body">
                                <input type="hidden" name="vendorapp_share_id" id="vendorapp_share_id" value="<?= $shares->id?>">
                                <input type="hidden" name="venue_id" id="venue_id" value="<?= $shares->venue_id?>">
                                    <div class="form-group">
                                      <label for="lon" class="col-sm-2 control-label">Share %</label>
                                      <div class="col-sm-6">
                                        <input value="<?= $shares->share;?>" type="number" step="0.01" name="share" id="share" class="form-control"  placeholder="Percentage" >
                                      </div>
                                    </div>
                                                       
                            <div class="form-group">
                              <label for="lon" class="col-sm-2 control-label"></label>
                              <div class="col-sm-6 well">
                                <strong style="padding-left: 78px;">Vendor App Share</strong> <input id="vendor_app" type="radio" name="vendor_app" value="vendor_app" class="flat-red " checked>
                                
                              </div>
                            </div>
                                                      
                            </div>

                            <div class="box-footer">
                                <div class="form-group">
                                  <div class="col-sm-2 ">
                                                           
                                  </div>
                                  <div class="col-sm-1 ">
                                    <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                  </div>
                                  <div class="col-sm-2 ">
                                                           
                                    </div>
                                    <div class="col-sm-1 ">
                                    <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                    </div>
                                </div>
                                                      
                                                      
                            </div>
                          </form>    
                         </div>
                    </div>
               </div>
          </div>
     </section>
</div>


