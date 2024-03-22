<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">

function showDiv(elem){
var sportcount=$('select option:selected').length;

if(sportcount > 2){
      $("#court option").remove();
      document.getElementById('courtstec').style.display = "none";
}else{
if(sportcount == 2){
      var venue = "<?=$venue_offer ?>";
      var sports = $( "#sports" ).val();
      $("#court option").remove();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>offer/court_list/<?=$venue_offer ?>",
            dataType: 'json',
            data: {sports: sports},
           
            success:function (data) { 
                    $('#court').append($('<option></option>'));
                    $.each(data['court'],function(element,val) {
                    $('#court').append($('<option>', { 
                            value: val.courts_id,
                            text : val.court_name 
                        }));
                    }); 
                },
          });

   document.getElementById('courtstec').style.display = "inline";
}   
}

}
</script>

<div class="content-wrapper">
     <section class="content-header">
          <h1>Offer</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Add</h3>
                         </div>
                         <div class="box-body">
                              <form class="form-horizontal" action="<?=base_url("shop/add_offer")?>" method="post" enctype="multipart/form-data">
                                   <div class="box-body">
                                       <input type="hidden" name="shop_id" id="shop_id" value="<?= $this->uri->segment(3) ?>">
                                        <div class="form-group">
                                             <label for="name" class="col-sm-2 control-label">Offer Name</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="name" class="form-control"  placeholder="Offer Name" value="" required="required">
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <label for="venue" class="col-sm-2 control-label">Percentage</label>
                                             <div class="col-sm-6">
                                                  <input type="text" name="amount" class="form-control"  placeholder="Percentage" value="" required="required">
                                             </div>
                                        </div>

                                       <div class="form-group">
                                                         <label class="col-sm-2 control-label">From Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="day" type="text" class="form-control " id="datepicker2" required="required" >
                                                                           </div>
                                                                      </div>
                                                                       <label class="col-sm-1 control-label">To Date:</label>
                                                                      <div class="col-sm-2">
                                                                          
                                                                           <div class="input-group date">
                                                                                <div class="input-group-addon">
                                                                                     <i class="fa fa-calendar"></i>
                                                                                </div>
                                                                                <input name="days" type="text" class="form-control " id="datepicker3" required="required" >
                                                                           </div>
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
