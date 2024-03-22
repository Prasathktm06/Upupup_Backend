<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>

<div class="content-wrapper">
     <section class="content-header">
          <h1>Testiominals</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                              <form class="form-horizontal" action="<?=base_url("trainers/add_testimonials")?>" method="post" enctype="multipart/form-data">
                                   <div class="box-body">
                                       <input type="hidden" name="trainer_id" id="trainer_id" value="<?= $this->uri->segment(3) ?>">
                                        <div class="form-group">
                                             <label for="name" class="col-sm-2 control-label">Name</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="name" class="form-control"  placeholder="Person Name" value="" required="required">
                                             </div>
                                        </div>

                                        <div class="form-group">
                                              <label for="description" class="col-sm-2 control-label">Description</label>
                                              <div class="col-sm-6">
                                                    <textarea id="description" name="description" class="form-control" rows="10" placeholder="Description" ></textarea>
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
<script type="text/javascript" language="JavaScript">
function radioWithText(d) {
    document.getElementById('one').style.display = "none";
    document.getElementById('two').style.display = "none";
    document.getElementById(d).style.display='inline'; 
}
</script>
<script type="text/javascript">
 var x=1;
$( document ).ready(function() {

  $('#datepicker2').datepicker({
minDate: '0',
 onSelect: function(dateStr) 
        {         
             var start_date= $("#datepicker3").val(dateStr);
            $('#datepicker3').datepicker('option', 'minDate', dateStr);
        }

});
 $('#datepicker3').datepicker({
minDate: '0',
});


});

</script>
<script type="text/javascript">

     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

     function readURL1(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blah1')
                   .attr('src', e.target.result)
                   .width(200)
                   .height(200);
           };

           reader.readAsDataURL(input.files[0]);
       }
     }
</script>
