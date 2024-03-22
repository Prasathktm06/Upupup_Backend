<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


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
          <h1>Version</h1>
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
                              <form class="form-horizontal" action="<?=base_url('version/add')?>" method="post">
                                   <div class="box-body">
                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Platform </label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="platform" class="form-control"  placeholder="Platform"  required="required" >
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Identifier</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="identifier" class="form-control"  placeholder="Identifier"  required="required" >
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Version Name</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="version_name" class="form-control"  placeholder="Version Name"  required="required" >
                                             </div>
                                        </div>

                                        <div class="form-group" >
                                             <label for="sports" class="col-sm-2 control-label">Version Code</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="version_code" class="form-control"  placeholder="Version Code"  required="required" onkeypress="return isNumber(event)">
                                             </div>
                                        </div>

                                        <div class="form-group">
                                             <label for="sports" class="col-sm-2 control-label">Mandatory Or Not</label>
                                             <div class="col-sm-6">
                                                  <input type="radio" name="optional" value="True" checked="checked"> Yes
                                                  <input type="radio" name="optional" value="False"> No
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



