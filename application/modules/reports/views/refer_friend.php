<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style type="text/css">
    .bootstrap-timepicker-widget.dropdown-menu {
        margin-left: 69px;
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
                <?php if (count($refer)>0) { ?>
                <a href="<?php echo base_url();?>reports/refer_friend_excel" data-toggle="tooltip"  class="btn-info btn-demo pull-right btn-warning" style="margin-top: -29px;margin-right: -358px;background-color: #12c5f5;">Download  <i class="fa fa-download "></i></a>
                <?php } ?>
                <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button>
          </div>
                <?php $_SESSION['refer']=$refer; ?>
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
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">#</th>
                                            <th class="text-center">User no.</th>
                                            <th class="text-center">No. of installation</th>
                                            <th class="text-center">Bonus on installation</th>
                                            <th class="text-center">Number of referral booking</th>
                                            <th class="text-center">Bonus on referral booking</th>
                                            <th class="text-center">City/ area of installation</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($refer as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center"><?=$value['phone_no']?></td>
                                            <td class="text-center"><?=$value['install_counts']?></td>
                                            <td class="text-center"><?=$value['install_bonus_coins']?></td>
                                            <td class="text-center"><?=$value['referal_booking_count']?></td>
                                            <td class="text-center"><?=$value['referal_booking_coin']?></td>
                                            <td class="text-center"><?=$value['referal_user_city']?></td>
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
                        <form role="form" method="post" action="<?=base_url();?>reports/refer_friend_report">
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
  


