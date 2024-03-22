<div class="content-wrapper">
    <section class="content-header">
        <h1>Booking History</h1>
    </section>
<section class="content">
                                                <div class="row">
                                                     <div class="col-xs-12">
                                                          <div class="box">
                                                  
                                                               <div class="box-body">
                                                                    <ul class="nav nav-tabs" role="tablist">
                                                                         <li role="presentation" class="active " style="width: 33.33%;"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">upUPUP Booking</a></li>
                                                                         <li role="presentation" class="text-center" style="width: 33.33%;"><a href="#vendors" aria-controls="home" role="tab" data-toggle="tab">Vendors Booking</a></li>
                                                                         <li role="presentation" style="width: 33.33%;" class="text-center"><a href="#offer" aria-controls="profile" role="tab" data-toggle="tab">Cancel Bookings</a></li>
                                                                    </ul>

                                                                    <div class="tab-content ">
                                                                         <div role="tabpanel" class="tab-pane active " id="general">
                                                                                  <div class="box-body">
                                                                                        <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                                                        <br><br>
                                                                                         <table id="example1" class="table table-bordered table-hover" style="width: 100%">
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
                                                                                                    <th class="text-center">Mode of Payment</th>
                                                                                                    <th class="text-center">Pay.Gat. Paid Amount.</th>
                                                                                                    <th class="text-center">Payable  Amount.</th>
                                                                                                    <th class="text-center">Pay.Gat. Transaction ID</th>
                                                                                                    <th class="text-center">Pay.Gat. Payment ID</th>
                                                                                                    <th class="text-center">Service Charge</th>
                                                                                                    <th class="text-center">Pay At Venue Amount</th>
                                                                                                    <th class="text-center">Offer Amount</th>
                                                                                                    <th class="text-center">Coupon Amount</th>
                                                                                                    <th class="text-center">Coupon Name</th>
                                                                                                    </tr>
                                                                                               </thead>
                                                                                               <tbody>
                                                                                               <?php $i=1; foreach ($list as $key => $value) { ?>
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
                                                                                    <td class="text-center"><?=$value['total_capacity']?></td>
                                                                                    <!--<?=($value['court_cost']*count($value['booked_slots']))*$value['total_capacity']?>-->
                                                                                    <td class="text-center"><?=$value['court_cost']*$value['total_capacity']?></td>
                                                                                    <td class="text-center">
                                                                                        <?php if ($value['payment_mode']==1) { ?>
                                                                                            Pay Through App
                                                                                        <?php }else if ($value['payment_mode']==0) { ?>
                                                                                            Pay At Venue
                                                                                        <?php }else if ($value['payment_mode']==2) { ?>
                                                                                            Failed
                                                                                        <?php }?> 
                                                                                    </td>
                                                                                    <td class="text-center"><?=$value['cost']?></td>
                                                                                     <td class="text-center">
                                                                                     <?php if ($value['payment_mode']==0) { 
                                                                                           echo  $value['cost']+$value['bal'];
                                                                                        }else{
                                                                                            echo  $value['cost'];
                                                                                        }?>    
                                                                                    </td>
                                                                                    <td class="text-center"><?=$value['transaction_id']?></td>
                                                                                    <td class="text-center"><?=$value['payment_id']?></td>
                                                                                    <td class="text-center">
                                                                                        <?php if ($value['charges']!=NULL) { ?>
                                                                                           <?php echo $value['charges'];?>
                                                                                        <?php }else{ ?>
                                                                                            <?php echo 0;?>
                                                                                        <?php }?> 
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php if ($value['payment_mode']==0) { 
                                                                                            echo $value['bal'];
                                                                                        }?> 
                                                                                    </td>
                                                                                    <td class="text-center"><?=$value['offer_value']?></td>  
                                                                                    <td class="text-center">
                                                                                        <?php if ($value['percentage']=="Yes") { ?>
                                                                                            <?php if ($value['charges']!=NULL) { ?>
                                                                                               <?=$value['court_cost']*$value['total_capacity']-$value['cost'] + (int)$value['charges']?>
                                                                                            <?php }else{ ?>
                                                                                                <?=$value['court_cost']*$value['total_capacity']-$value['cost']?>
                                                                                            <?php }?>
                                                                                          
                                                                                        <?php }else if ($value['percentage']=="No") { ?>
                                                                                            <?php if ($value['charges']!=NULL) { ?>
                                                                                               <?=$value['court_cost']*$value['total_capacity']-$value['cost'] + (int)$value['charges']?>
                                                                                            <?php }else{ ?>
                                                                                                <?=$value['court_cost']*$value['total_capacity']-$value['cost']?>
                                                                                            <?php }?>
                                                                                        <?php }?>
                                                                                    </td>   
                                                                                    <td class="text-center"><?=$value['description']?> </td>    
                                                                                </tr>
                                                                                <?php $i++;} ?>
                                                                                               </tbody>
                                                                                               <tfoot>
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
                                                                                                    <th class="text-center">Mode of Payment</th>
                                                                                                    <th class="text-center">Pay.Gat. Paid Amount.</th>
                                                                                                    <th class="text-center">Payable  Amount.</th>
                                                                                                    <th class="text-center">Pay.Gat. Transaction ID</th>
                                                                                                    <th class="text-center">Service Charge</th>
                                                                                                    <th class="text-center">Pay At Venue Amount</th>
                                                                                                    <th class="text-center">Offer Amount</th>
                                                                                                    <th class="text-center">Coupon Amount</th>
                                                                                                    <th class="text-center">Coupon Name</th>
                                                                                                    </tr>
                                                                                               </tfoot>
                                                                                          </table>
                                                                                   </div>   
                                                                         </div>
                                                                         <div role="tabpanel" class="tab-pane" id="vendors">
<div class="box-body">
                                                                                        <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                                                        <br><br>
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
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Booked By</th>
                                            <th class="text-center">Manager Number</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($vendor as $key => $value) { ?>
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
                                            <td class="text-center"><?=$value['total_capacity']?></td>
                                            <!--<?=($value['court_cost']*count($value['booked_slots']))*$value['total_capacity']?>-->
                                            <td class="text-center"><?=$value['court_cost']*$value['total_capacity']?></td>
                                            <td class="text-center">
                                                <?php if ($value['payment_mode']==1) { ?>
                                                   Pay At Venue
                                                <?php }else if ($value['payment_mode']==0) { ?>
                                                    Pay At Venue
                                                <?php }else if ($value['payment_mode']==2) { ?>
                                                    Failed
                                                <?php }?> 
                                            </td>
                                            <td class="text-center"><?=$value['cost']?></td>
                                            <td class="text-center">
                                               <?php if ($value['offer_amount']!=0) { ?>
                                                   <?= $value['offer_amount']*$value['total_capacity']?>
                                                <?php }else if ($value['offer_percentage']!=0) { ?>
                                                   <?= (($value['court_cost']*$value['total_capacity'])*$value['offer_percentage'])/100?>
                                                <?php }?>
                                            </td>   
                                            <td class="text-center"><?=$value['role_name']?> </td>  
                                            <td class="text-center"><?=$value['mgr_phone']?> </td>    
                                        </tr>
                                        <?php $i++;} ?>
                                   </tbody>
                                   <tfoot>
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
                                            <th class="text-center">Mode of Payment</th>
                                            <th class="text-center">Pay At Venue Amount</th>
                                            <th class="text-center">Offer Amount</th>
                                            <th class="text-center">Booked By</th>
                                            <th class="text-center">Manager Number</th>
                                        </tr>
                                   </tfoot>
                              </table>
                                                                                   </div> 
                                                                         </div>
                                                                         <div role="tabpanel" class="tab-pane" id="offer">
                                                                         <div class="box-body">
                                                                                        <input type="hidden" id="venue_id" value="<?= $venue->id?>">
                                                                                        <br><br>
                                                                                        <table id="example3" class="table table-bordered table-hover">
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
                                            <th class="text-center">Manager Number</th>
                                            <th class="text-center">Cancel Date</th>
                                            <th class="text-center">Cancel Time</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($bookcan as $key => $value) { ?>
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
                                                   <?= $value['offer_amount']*$value['total_capacity']?>
                                                <?php }else if ($value['offer_percentage']!=0) { ?>
                                                   <?= (($value['court_cost']*$value['total_capacity'])*$value['offer_percentage'])/100?>
                                                <?php }?>
                                            </td>  
                                            <td class="text-center"><?=$value['role_name']?> </td>
                                            <td class="text-center"><?=$value['cm_phone']?></td>
                                            <td class="text-center"><?=date( ' d M Y ',strtotime($value['cancel_date']))?></td>
                                            <td class="text-center"><?=date( ' h:i:s A ',strtotime($value['cancel_date']))?></td>    
                                        </tr>
                                        <?php $i++;} ?>
                                   </tbody>
                                   <tfoot>
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
                                            <th class="text-center">Manager Number</th>
                                            <th class="text-center">Cancel Date</th>
                                            <th class="text-center">Cancel Time</th>
                                        </tr>
                                   </tfoot>
                              </table>
                                                                                   </div>
                                                                         </div>
                                                                    </div>
                                                               </div>
                                                          </div>
                                                     </div>
                                                </div>
                                          </section>
</div>
 
<script>
  $(function () {
    
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true
      
    });
$('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true
      
    });
$('#example3').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true
      
    });
  });


</script>  


