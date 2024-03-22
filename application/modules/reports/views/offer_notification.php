<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
<script type="text/javascript">
     $(document).ready(function(){
        $('#city').change(function(){
            $("#area option").remove();
            var url_new="reports/area_list";
            var city = document.getElementById("city").value;
            //console.log(city);
            var base_url="<?php echo base_url(); ?>" ;

            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {city: city},
                success:function (res) {
                    $('#area').append($('<option></option>'));
                    $.each(res['area'],function(element,val) {
                        $('#area').append($('<option>', {
                            value: val.id,
                            text : val.area
                        }));
                    });
                },
            });
        });
    });
</script>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////// -->
<div class="content-wrapper">
     <section class="content-header">
          <h1>Report</h1>
          <div class="col-md-8" >
                <a href="<?=base_url();?>reports/offer_excel" data-toggle="tooltip"  class="btn-info btn-demo pull-right btn-warning" style="margin-top: -29px;margin-right: -358px;background-color: #12c5f5;">Download  <i class="fa fa-download "></i></a>
                <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button>
          </div>
          <?php $_SESSION['offer_list']=$list; ?>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title"><?=$heading?></h3>
                         </div>

                         <div class="box-body">
                              <div class="table-responsive">
                                   <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                             <tr>
                                                  <th class="text-center">#</th>
                                                  <th class="text-center">City </th>
                                                  <th class="text-center">Area</th>
                                                  <th class="text-center">Venue</th>
                                                  <th class="text-center">Sports</th>
                                                  <th class="text-center">Offer %</th>
                                                  <th class="text-center">Offer Valid From</th>
                                                  <th class="text-center">Offer Valid Till</th>
                                                  <th class="text-center">SMS</th>
                                                  <th class="text-center">Push</th>
                                                  <th class="text-center">Both</th>
                                                  <th class="text-center">Send On Date</th>
                                                  <th class="text-center">Send On Time</th>
                                                  <th class="text-center">Message</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php $i=1; foreach ($list as $key => $value) { ?>
                                             <tr>
                                                  <td class="text-center"><?=$i?></td>
                                                  <td class="text-center"><?=$value['city']?></td>
                                                  <td class="text-center"><?=$value['area']?></td>
                                                    <td class="text-center"><?=$value['venue']?></td>
                                                  <td class="text-center"><?=$value['sports']?></td>
                                                  <?php if($value['percentage']!=''){ ?>
                                                  <td class="text-center"><?=$value['percentage']?> %</td>
                                                <?php }else{ ?>
                                                <td class="text-center"> </td>
                                                <?php } ?>
                                                  <td class="text-center">
                                                    <?php if($value['start']!=NULL){?>
                                                      <?=date( ' d-M-Y ',strtotime($value['start']))?>
                                                    <?php }?>
                                                  </td>
                                                  <td class="text-center">
                                                    <?php if($value['end']!=NULL){?>
                                                      <?=date( ' d-M-Y ',strtotime($value['end']))?>
                                                    <?php }?>
                                                  </td>
                                                  <td class="text-center">
                                                       <?php if ($value['type']=="sms") {
                                                            echo "SMS";
                                                       }?>
                                                  </td>
                                                  <td class="text-center">
                                                       <?php if ($value['type']=="notification") {
                                                            echo "Push";
                                                       }?>
                                                  </td>
                                                  <td class="text-center">
                                                       <?php if ($value['type']=="both") {
                                                            echo "Both";
                                                       }?>
                                                  </td>
                                                  <td class="text-center">
                                                    <?php if($value['send_date']!=NULL){?>
                                                      <?=date( ' d-M-Y ',strtotime($value['send_date']))?>
                                                    <?php }?>
                                                  </td>
                                                  <td class="text-center">
                                                    <?php if($value['send_date']!=NULL){?>
                                                      <?=date( ' h:i:s ',strtotime($value['send_date']))?>
                                                    <?php }?>
                                                  </td>
                                                  <td class="text-center"><?=$value['message']?></td>
                                             </tr>
                                             <?php $i++;} ?>
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                    </div>
		     </div>
	     </div>
     </section>
      <div class="container demo">
        <!-- Modal -->
        <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel2">Filter</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" method="post" action="<?=base_url();?>reports/offer_notification">
                            <div class="box-body">
                                <div class="form-group " >
                                    <label for=""> City</label>
                                    <div class="input-group">
                                         <select class="select2" style="width: 250px;" name="city" id="city" >
                                              <option></option>
                                              <?php foreach ($location as $key => $value) { ?>
                                                   <option value="<?=$value['id']?>"><?=$value['location']?></option>
                                              <?php } ?>
                                         </select>
                                    </div>
                               </div>

                               <div class="form-group " >
                                    <label for="">Area</label>
                                    <div class="input-group">
                                         <select class="select2" style="width: 250px;" name="area" id="area" >

                                       </select>
                                  </div>
                               </div>

                               <div class="form-group year" >
                                    <label for="">Start Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="date" type="text" class="form-control datepicker" id="datepicker1" data-date-format='yyyy-mm-dd'>
                                    </div>
                                </div>
                                
                                <div class="form-group year" >
                                    <label for="">End Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="enddate" type="text" class="form-control datepicker" id="datepicker2" data-date-format='yyyy-mm-dd'>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary" value="submit" name="submit">Submit</button>
                            </div>
                        </form>
                    </div>

                </div><!-- modal-content -->
            </div><!-- modal-dialog -->
        </div><!-- modal -->
    </div><!-- container -->
</div>
 <script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollX" : true,
      "scrollCollapse" : true
    });
  });

</script>
<script type="text/javascript">
 var x=1;
$( document ).ready(function() {

  $('#datepicker1').datepicker({
 onSelect: function(dateStr) 
        {         
             var start_date= $("#datepicker2").val(dateStr);
            $('#datepicker2').datepicker('option', 'minDate', dateStr);
        }

});
 $('#datepicker2').datepicker({
});



});

</script> 
