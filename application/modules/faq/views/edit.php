     <div class="content-wrapper">
     <section class="content-header">
          <h1>FAQ</h1>
          <!-- <ol class="breadcrumb">
               <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="#">Tables</a></li>
               <li class="active">Simple</li>
          </ol> -->
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
                              <form class="form-horizontal" action="<?=base_url()?>faq/edit/<?=$value['faq_id']?>" method="post">
                                   <div class="box-body">
                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Question</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="question" class="form-control"  placeholder="Question"  required="required" value="<?=$value['question']?>" >
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Answer</label>
                                             <div class="col-sm-6">
                                                  <textarea  name="answer" class="form-control"  placeholder="Answer"  required="required" rows="5"><?=$value['answer']?></textarea>  
                                             </div>
                                        </div>
                                                          <div class="form-group">
                           <label class="col-sm-2 control-label">Status </label>
                           <div class="col-sm-6">
                              <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                 <option <?php if($value['status']==0) echo "selected"; ?> value="0">Inactive</option>
                                 <option <?php if($value['status']==1) echo "selected"; ?> value="1">Active</option>
                              </select>
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




