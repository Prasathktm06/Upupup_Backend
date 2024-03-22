<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
                <?php if (count($list) >0) { ?>
                <a href="<?=base_url();?>reports/coupon_excel" data-toggle="tooltip"  class="btn-info btn-demo pull-right btn-warning" style="margin-top: -29px;margin-right: -358px;background-color: #12c5f5;">Download  <i class="fa fa-download "></i></a>
                <?php } ?>
                <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button>
                
          </div>
          <?php $_SESSION['coupons']=$list; ?> 
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
                                                  <th class="text-center">Coupon Value</th>
                                                  <th class="text-center">Coupon Code</th>
                                                  <th class="text-center">Used By</th>
                                                  <th class="text-center">User Phone No</th>
                                                  <th class="text-center">Used On Date</th>
                                                  <th class="text-center">Booking Id</th>
                                                  <th class="text-center">Coupon Validity Period</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php $i=1; foreach ($list as $key => $value) { ?>
                                             <tr>
                                                  <td class="text-center"><?=$i?></td>
                                                  <td class="text-center"><?=$value['location']?></td>
                                                  <td class="text-center"><?=$value['area']?></td>
                                                  <td class="text-center">
                                                    <?php if ($value['percentage']=='Yes') { ?>
                                                        <?=$value['coupon_value']?>%
                                                    <?php }else if ($value['percentage']=='No') { ?>
                                                        <?=$value['coupon_value']?>
                                                    <?php }?>
                                                  </td>
                                                  <td class="text-center"><?=$value['coupon_code']?></td>
                                                  <td class="text-center"><?=$value['name']?></td>
                                                  <td class="text-center"><?=$value['phone_no']?></td>
                                                  <td class="text-center">
                                                       <?php if($value['time']!=NULL){?>
                                                       <?=date( ' d M Y ',strtotime($value['time']))?>
                                                       <?php }?>
                                                  </td>
                                                  <td class="text-center"><?=$value['booking_id']?></td>
                                                  <td class="text-center">
                                                      <?=date( ' d M Y ',strtotime($value['valid_from']))?> - <?=date( ' d M Y ',strtotime($value['valid_to']))?>
                                                  </td>
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
                        <form role="form" method="post" action="<?=base_url();?>reports/coupons">
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


                                <div class="form-group " >
                                    <label for=""> Coupon Code</label>
                                    <div class="input-group">
                                         <select class="select2" style="width: 250px;" name="coupon_code" id="coupon_code" >
                                              <option></option>
                                              <?php foreach ($coupon_code as $key2 => $code) { ?>
                                                   <option value="<?=$code['coupon_code']?>"><?=$code['coupon_code']?></option>
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
  


