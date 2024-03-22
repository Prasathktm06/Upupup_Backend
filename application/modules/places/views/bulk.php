<div class="content-wrapper">
     <section class="content-header">
          <h1>City</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-6">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Bulk Upload</h3>
                         </div>

                         <div class="box-body">
                              <form action="<?= base_url('places/bulk_upload_city')?>" method="post" enctype="multipart/form-data">
                                   <div class="form-group">
                                        <label for="venue" class="col-sm-5 control-label">Excel File!</label>
                                        <div class="col-sm-4 fileUpload btn btn-info btn-sm">
                                             <span>Upload</span>
                                             <input name="file" type="file" id="exampleInputFile" class="upload"  >
                                        </div>
                                   </div>
                                   
                                   <div class="form-group">
                                        <label class="col-sm-5 control-label"></label>
                                        <div class="col-sm-5">
                                             <input type="submit" class="btn btn-default" value="Submit">
                                        </div>
                                   </div>
                              </form>
                              <a class="pull-right text-success" download href="<?=base_url();?>files/city.xlsx">Download sample</a>
                         </div>
                    </div>
               </div>
               <div class="col-xs-6">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title text-warning bg-warning">Warning</h3>
                         </div>
                         <div class="box-body">
                              <ul>
                                   <li>Upload Excel file.</li>
                                   <li>Do not use comma's </li>
                              </ul>
                         </div>
                    </div>
               </div>
          </div>
     </section>
</div>




