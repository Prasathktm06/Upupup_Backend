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
                              <h3 class="box-title">Add</h3>
                         </div>

                         <div class="box-body">
                              <form class="form-horizontal" action="<?=base_url('faq/add')?>" method="post">
                                   <div class="box-body">
                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Question</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="question" class="form-control"  placeholder="Question"  required="required" >
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Answer</label>
                                             <div class="col-sm-6">
                                                  <textarea  name="answer" class="form-control"  placeholder="Answer"  required="required" rows="5"></textarea>  
                                             </div>
                                        </div>
                                          <div class="form-group">
                           <label class="col-sm-2 control-label">Status </label>
                           <div class="col-sm-6">
                              <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
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

<script type="text/javascript">
$( document ).ready(function() {
  $('#datepicker2').datepicker({
      autoclose: true
    });
});
</script>




