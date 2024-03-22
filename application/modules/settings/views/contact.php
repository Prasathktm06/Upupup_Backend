<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript"> 
    
function phone(id,stat,feild){
     var url_new="settings/phone_status"+"/"+id+"/"+stat+"/"+feild;
     var base_url="<?php echo base_url(); ?>" ;
     //alert(id1);alert(id2);alert(id3);
     $.ajax({
          type:"GET",
          url:base_url+url_new,
          
          //dataType : 'text',
          success:function(data){
               if (data=="Success") {
                    $('#offer').load(document.URL +  ' #offer');
                     var msg="<?php echo $this->session->flashdata('success-msg') ?>";
                    new PNotify({
                         title: 'Status has been changed!',
                         text: msg,
                         type: 'success',
                         delay : 100
                    });  
               }
               //location.reload();
               //console.log(data);
          }
     });
}
    
 function email(id,stat,feild){
     var url_new="settings/email_status"+"/"+id+"/"+stat+"/"+feild;
     var base_url="<?php echo base_url(); ?>" ;
     //alert(id1);alert(id2);alert(id3);
     $.ajax({
          type:"GET",
          url:base_url+url_new,
          
          //dataType : 'text',
          success:function(data){
               if (data=="Success") {
                    $('#general').load(document.URL +  ' #general');
                     var msg="<?php echo $this->session->flashdata('success-msg') ?>";
                    new PNotify({
                         title: 'Status has been changed!',
                         text: msg,
                         type: 'success',
                         delay : 100
                    });  
               }
               location.reload();
               //console.log(data);
          }
     });
}    
</script>

<div class="content-wrapper">
     <section class="content-header">
          <h1>Email And Phone Number</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <!-- <h3 class="box-title">List</h3> -->
                              <!-- <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_offer')) { ?>
                                   <a href="<?php echo base_url('offer/add') ?>" class="btn btn-sm pull-right btn-warning">Add Offer <i class="fa fa-plus"></i></a>
                              <?php } ?> -->
                         </div>
            
                         <div class="box-body">
                              <ul class="nav nav-tabs" role="tablist">
                                   <li role="presentation" <?php if(isset($_GET['email'])) echo "class=active" ?> style="width: 50%;"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">Email</a></li>
                                   <li <?php if(isset($_GET['phone'])) echo "class=active" ?> role="presentation" style="width: 50%;" class="text-center"><a href="#offer" aria-controls="profile" role="tab" data-toggle="tab">Phone</a></li>
                              </ul>

                              <div class="tab-content ">
                                   <div role="tabpanel" <?php if(isset($_GET['email'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="general">
                                        <form class="form-horizontal" action="<?=base_url('settings/email_add')?>" method="post" >
                                             <div class="box-body">
                                                  <div class="form-group" >
                                                       <label for="sports" class="col-sm-1 ">Email Id </label>
                                                       <div class="col-sm-6">
                                                            <input type="email" name="email" class="form-control"  placeholder="Email Id"  required="required" >
                                                            <button type="submit" class="btn btn-warning pull-right" style="margin-top: -7%;margin-right: -23%;">Submit</button>
                                                       </div>
                                                  </div>
                                             </div>
                                             <!-- <div class="box-footer">
                                                  <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                                  <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                             </div> -->
                                        </form>
                                        <table id="example1" class="table table-bordered table-hover">
                                           <thead>
                                                <tr>
                                                     <th class="text-center">#</th>
                                                     <th class="text-center">Email Id</th>
                                                     <th class="text-center">Feedback</th>
                                                     <th class="text-center">Booking</th>
                                                     <th class="text-center">Hosting</th>
                                                     <!--<th class="text-center">Reminders</th>-->
                                                     <th class="text-center">Offers</th>
                                                     <th class="text-center">Notifications</th>
                                                     <th class="text-center">Buy Coin</th>
                                                     <th class="text-center">Holiday</th>
                                                     <th class="text-center">Share Venue</th>
                                                     <th class="text-center">Vendors Message</th>
                                                     <th class="text-center">In-active Court</th>
                                                     <th class="text-center">Non Vendors</th>
                                                    <th class="text-center">Un-Block</th>
                                                    <th class="text-center">Assign Manager</th>
                                                     <th class="text-center">Action</th>
                                                </tr>
                                           </thead>
                                           <tbody>
                                                <?php $i=1; foreach ($email as $key => $value) { ?>
                                                <tr>
                                                     <td class="text-center"><?=$i?></td>
                                                     <td class="text-center"><?=$value['email']?></td>
                                                     <td class="text-center">
                                                            <?php if($value['feedback']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['feedback']?>,'feedback')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['feedback']?>,'feedback')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['booking']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['booking']?>,'booking')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['booking']?>,'booking')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['hosting']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['hosting']?>,'hosting')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['hosting']?>,'hosting')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <!--<td class="text-center">
                                                            <?php if($value['reminders']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['reminders']?>,'reminders')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['reminders']?>,'reminders')">Inactive</button>
                                                          <?php } ?>
                                                      </td> -->
                                                      <td class="text-center">
                                                            <?php if($value['offers']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['offers']?>,'offers')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['offers']?>,'offers')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['notifications']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['notifications']?>,'notifications')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['notifications']?>,'notifications')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['buy_coin']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['buy_coin']?>,'buy_coin')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['buy_coin']?>,'buy_coin')">Inactive</button>
                                                          <?php } ?>
                                                      </td>  
                                                      
                                                       <td class="text-center">
                                                            <?php if($value['holiday']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['holiday']?>,'holiday')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['holiday']?>,'holiday')">Inactive</button>
                                                          <?php } ?>
                                                      </td>
                                                      <td class="text-center">
                                                            <?php if($value['share_venue']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['share_venue']?>,'share_venue')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['share_venue']?>,'share_venue')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['message']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['message']?>,'message')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['message']?>,'message')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['inactive_court']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['inactive_court']?>,'inactive_court')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['inactive_court']?>,'inactive_court')">Inactive</button>
                                                          <?php } ?>
                                                      </td>
                                                      <td class="text-center">
                                                            <?php if($value['non_vendors']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['non_vendors']?>,'non_vendors')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['non_vendors']?>,'non_vendors')">Inactive</button>
                                                          <?php } ?>
                                                      </td>
                                                          <td class="text-center">
                                                            <?php if($value['unblock']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['unblock']?>,'unblock')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['unblock']?>,'unblock')">Inactive</button>
                                                          <?php } ?>
                                                      </td>
                                                      <td class="text-center">
                                                            <?php if($value['manager']){?>
                                                               <button  class="btn btn-success" id="status" onclick="email(<?=$value['id']?>,<?=$value['manager']?>,'manager')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="email(<?=$value['id']?>,<?=$value['manager']?>,'manager')">Inactive</button>
                                                          <?php } ?>
                                                      </td>
                                                     <td class="text-center">
                                                          <a href="<?=base_url()?>settings/email_edit/<?=$value['id']?>" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a>
                                                          <a href="<?=base_url()?>settings/email_delete/<?=$value['id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                          
                                                     </td> 
                                                </tr>
                                                <?php $i++;} ?>
                                           </tbody>
                                        </table>
                                   </div>
                                   <div role="tabpanel" <?php if(isset($_GET['phone'])) echo "class='tab-pane active' " ;  echo "class=tab-pane" ?> id="offer">
                                        <form class="form-horizontal" action="<?=base_url('settings/phone_add')?>" method="post" >
                                             <div class="box-body">
                                                  <div class="form-group" >
                                                       <label for="sports" class="col-sm-1 ">Phone No </label>
                                                       <div class="col-sm-6">
                                                            <input type="number" name="phone" class="form-control"  placeholder="Phone No"  required="required" maxlength="10">
                                                            <button type="submit" class="btn btn-warning pull-right" style="margin-top: -7%;margin-right: -23%;">Submit</button>
                                                       </div>
                                                  </div>
                                             </div>
                                             <!-- <div class="box-footer">
                                                  <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                                  <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                             </div> -->
                                        </form>
                                        <table id="example2" class="table table-bordered table-hover">
                                           <thead>
                                                <tr>
                                                     <th class="text-center">#</th>
                                                     <th class="text-center">Phone No</th>
                                                     <th class="text-center">Feedback</th>
                                                     <th class="text-center">Booking</th>
                                                     <th class="text-center">Hosting</th>
                                                     <!--<th class="text-center">Reminders</th>-->
                                                     <th class="text-center">Offers</th>
                                                     <th class="text-center">Notifications</th>
                                                     <th class="text-center">Help</th>
                                                     <th class="text-center">Action</th>
                                                </tr>
                                           </thead>
                                           <tbody>
                                                <?php $i=1; foreach ($phone as $key => $value) { ?>
                                                <tr>
                                                     <td class="text-center"><?=$i?></td>
                                                     <td class="text-center"><?=$value['phone']?></td>
                                                     <td class="text-center">
                                                          <?php if($value['feedback']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['feedback']?>,'feedback')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['feedback']?>,'feedback')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['booking']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['booking']?>,'booking')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['booking']?>,'booking')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['hosting']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['hosting']?>,'hosting')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['hosting']?>,'hosting')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                     <!-- <td class="text-center">
                                                            <?php if($value['reminders']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['reminders']?>,'reminders')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['reminders']?>,'reminders')">Inactive</button>
                                                          <?php } ?>
                                                      </td>--> 
                                                      <td class="text-center">
                                                            <?php if($value['offers']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['offers']?>,'offers')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['offers']?>,'offers')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['notifications']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['notifications']?>,'notifications')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['notifications']?>,'notifications')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                      <td class="text-center">
                                                            <?php if($value['help']){?>
                                                               <button  class="btn btn-success" id="status" onclick="phone(<?=$value['id']?>,<?=$value['help']?>,'help')">Active</button>
                                                          <?php } else{?>
                                                               <button  class="btn btn-danger" id="status" onclick="phone(<?=$value['id']?>,<?=$value['help']?>,'help')">Inactive</button>
                                                          <?php } ?>
                                                      </td> 
                                                     <td class="text-center">
                                                          <a href="<?=base_url()?>settings/phone_edit/<?=$value['id']?>" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a>
                                                          <a href="<?=base_url()?>settings/phone_delete/<?=$value['id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                          
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


  });


</script>