<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style type="text/css">
    .bootstrap-timepicker-widget.dropdown-menu {
        margin-left: 69px;
    }
</style>
<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
<script type="text/javascript">
     $(function() {
          $('input[name="type"]').on('click', function() {
               if ($(this).val() == 'Year') {
                    $('.year').show();
                    $('.date_data').hide();
               }
               else if ($(this).val() == 'Date')  {
                    $('.year').hide();
                    $('.date_data').show();
               }
          });
     });

     $(function() {
          $('input[name="venue_data"]').on('click', function() {
               if ($(this).val() == 'Court') {
                    $('.court_div').show();
                    $('.sports_div').hide();
               }
               else if ($(this).val() == 'Sports')  {
                    $('.court_div').hide();
                    $('.sports_div').show();
               }
          });
     });
</script>


<!-- ////////////////////////////////////////////////////////////////////////////////////////////////// -->
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

<script type="text/javascript">
     $(document).ready(function(){
        $('#area').change(function(){
            $("#venue option").remove();
            var url_new="reports/venue_list_data";
            var area = document.getElementById("area").value;
            //console.log(city);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {area: area},
                success:function (res) { 
                    $('#venue').append($('<option></option>'));
                    $.each(res['venue'],function(element,val) {
                        $('#venue').append($('<option>', { 
                            value: val.venue_id,
                            text : val.venue 
                        }));
                    }); 
                },
            });
        });
    });
</script>

<script type="text/javascript"> 
    $(document).ready(function(){
        $('#venue').change(function(){
            //$("#court option").remove();
            $("#sports option").remove();
            var url_new="reports/court_list";
            var venue = document.getElementById('venue').value;
            //alert(venue);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {venue: venue},
                success:function (res) {
                    /*$('#court').append($('<option></option>'));
                    $.each(res['court'],function(element,val) {
                        $('#court').append($('<option>', { 
                            value: val.id,
                            text : val.court 
                        }));
                    }); */
                    $('#sports').append($('<option></option>'));
                    $.each(res['sports'],function(element,val) {
                        $('#sports').append($('<option>', { 
                            value: val.sports_id,
                            text : val.sports 
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
                <?php if (count($books)>0) { ?>
                <a href="<?php echo base_url();?>reports/cancelbook_excel" data-toggle="tooltip"  class="btn-info btn-demo pull-right btn-warning" style="margin-top: -29px;margin-right: -358px;background-color: #12c5f5;">Download  <i class="fa fa-download "></i></a>
                <?php } ?>
                <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button>
          </div>
                <?php $_SESSION['books']=$books; ?>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title"><?=$heading?></h3>
                         </div>
            
                         <div class="box-body">
                              <hr>
                              <div class="table-responsive">
                              <table id="example2" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">#</th>
                                            <th class="text-center">Booking ID</th>
                                            <th class="text-center">User Name</th>
                                            <th class="text-center">User Phone No.</th>
                                            <th class="text-center">User Email ID</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Area</th>
                                            <th class="text-center">Venue Name</th>
                                            <th class="text-center">Court Name</th>
                                            <!--<th class="text-center">Court Capacity</th>-->
                                            <th class="text-center">Sports Playing</th>
                                            <th class="text-center">Date of Booking</th>
                                            <th class="text-center">Time of Booking</th>
                                            <th class="text-center">Date of Playing</th>
                                            <th class="text-center">Time of Playing</th>
                                            <th class="text-center">Hours of Playing</th>
                                            <th class="text-center">Per Slot Price</th>
                                            <th class="text-center">Booked Capacity</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Payable Amount</th>
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Cancel By</th>
                                            <th class="text-center">Manger Number</th>
                                            <th class="text-center">Cancel Date</th>
                                            <th class="text-center">Cancel Time</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($books as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value['booking_id']?></td>
                                            <td class="text-center"><?=$value['name']?></td>
                                            <td class="text-center"><?=$value['phone_no']?></td>
                                            <td class="text-center"><?=$value['email']?></td>
                                            <td class="text-center"><?=$value['location']?></td>
                                            <td class="text-center"><?=$value['area']?></td>
                                            <td class="text-center"><?=$value['venue']?></td>
                                            <td class="text-center"><?=$value['court']?></td>
                                            <!--<td class="text-center"><?=$value['capacity']?></td>-->
                                            <td class="text-center"><?=$value['sports']?></td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['time']!=NULL){?>
                                                    <?=date( ' h:i:s A ',strtotime($value['time']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($value['date']!=NULL){?>
                                                    <?=date( ' d M Y ',strtotime($value['date']))?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center"><?=$value['time_slots']?></td>
                                            <td class="text-center"><?=number_format((float)($value['duration']/60),2, '.', '')?> Hours</td>
                                            <td class="text-center"><?=$value['court_cost']?></td>
                                            <td class="text-center"><?=$value['booked_capacity']?></td>
                                            <!--<?=($value['court_cost']*count($value['booked_slots']))*$value['total_capacity']?>-->
                                            <td class="text-center"><?=$value['court_cost']*$value['booked_capacity']?></td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                            <td class="text-center">
                                                <?php if ($value['payment_mode']==3) { ?>
                                                   Cancel
                                                <?php }else if ($value['payment_mode']==0) { ?>
                                                    Pay At Venue
                                                <?php }else if ($value['payment_mode']==2) { ?>
                                                    Failed
                                                <?php }?> 
                                            </td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                            <td class="text-center">
                                               <?php if ($value['offer_amount']!=0) { ?>
                                                   <?= $value['offer_amount']*$value['booked_capacity']?>
                                                <?php }else if ($value['offer_percentage']!=0) { ?>
                                                   <?= (($value['court_cost']*$value['booked_capacity'])*$value['offer_percentage'])/100?>
                                                <?php }?>
                                            </td>
                                            <td class="text-center"><?=$value['role_name']?> </td>
                                            <td class="text-center"><?=$value['cm_phone']?></td>
                                            <td class="text-center"><?=date( ' d M Y ',strtotime($value['cancel_date']))?></td>
                                            <td class="text-center"><?=date( ' h:i:s A ',strtotime($value['cancel_date']))?></td>    
                                        </tr>
                                        <?php $i++;} ?>
                                   </tbody>
                                   <!-- <tfoot>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <th class="text-center">Coupon</th>
                                             <th class="text-center">Valid From</th>
                                             <th class="text-center">Valid To</th>
                                             <th class="text-center">Action</th>
                                        </tr>
                                   </tfoot> -->
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
                        <form role="form" method="post" action="<?=base_url();?>reports/cancel_booking">
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

                               <div class="form-group">
                                    <label for="exampleInputPassword1">Venue</label>
                                    <select class="form-control select2" style="width: 100%;" name="venue" id="venue">
                                    </select>
                                </div>

                                <div class="form-group sports_div" >
                                    <label for="exampleInputPassword1">Sports</label>
                                    <select class="form-control select2" style="width: 100%;" name="sports" id="sports">
                                    
                                    </select>
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

                       <!--        <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker" name="time" id="timepicker" >
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

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
<script src="<?= base_url()?>assets/plugins/select2/select2.full.min.js"></script>
 <script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
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
  


