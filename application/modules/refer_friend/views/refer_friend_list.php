
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style>
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>


<!-- //////////////////////////////////////////////////////////////////////////////////////////////////// -->
<div class="content-wrapper">
     <section class="content-header">
          <h1>Refer a Friend Settings</h1>
                       

          <div class="col-md-6" >
              
              
             <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_refer')) { ?>   
            <a href="<?php echo base_url('refer_friend/add_refer_friend') ?>" class="btn btn-sm pull-right btn-warning" style="margin-top: -29px;margin-right: -200px;">Add Setting <i class="fa fa-plus"></i></a>
            <?php }?>

          </div>
          <div class="col-md-1" >
           <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button>

          </div>
                
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">List</h3>
                         </div>
            
                         <div class="box-body">
                              <hr>
                              <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                          <tr>
                                              <th class="text-center" style="width: 5%">#</th>
                                              <th class="text-center" style="width: 10%">City</th>
                                              <th class="text-center" style="width: 10%">Start Date</th>
                                              <th class="text-center" style="width: 10%">End Date</th>
                                              <th class="text-center" style="width: 10%">Install Status</th>
                                              <th class="text-center" style="width: 10%">Install Count</th>
                                              <th class="text-center" style="width: 10%">Install Bonus Coin</th>
                                              <th class="text-center" style="width: 15%">Booking Bonus Status</th>
                                              <th class="text-center" style="width: 10%">Booking Bonus Coin</th>
                                              <th class="text-center" style="width: 10%">status</th>
                                              
                                          </tr>
                                      </thead>
                                     <tbody>

                                       <?php $i=1; foreach ($list as $key => $value) { ?>
                                                            <tr>
                                                                <td class="text-center" style="width: 5%"><?=$i?></td>
                                                                <td class="text-center" style="width: 10%"><?=$value['location']?></td>
                                                                <td class="text-center" style="width: 10%">
                                                                        <?php if($value['start_date']!=NULL){?>
                                                                            <?=date( ' d M Y ',strtotime($value['start_date']))?>
                                                                         <?php }?>
                                                                </td>
                                                                <td class="text-center" style="width: 10%">
                                                                        <?php if($value['end_date']!=NULL){?>
                                                                            <?=date( ' d M Y ',strtotime($value['end_date']))?>
                                                                         <?php }?>
                                                                </td>
                                                                <td class="text-center" style="width: 10%">
                                                                    <?php if($value['install_status']==1){?>
                                                                            Active
                                                                         <?php }else{?>
                                                                            In-active
                                                                          <?php } ?>
                                                                </td>
                                                                <td class="text-center" style="width: 10%">
                                                                  <?php if($value['install_count']!=0){?>
                                                                            <?=$value['install_count']?>
                                                                         <?php }else{?>
                                                                            In-active
                                                                          <?php } ?>
                                                                </td>
                                                                <td class="text-center" style="width: 10%">
                                                                  <?php if($value['install_bonus_coin']!=0){?>
                                                                            <?=$value['install_bonus_coin']?>
                                                                         <?php }else{?>
                                                                            In-active
                                                                          <?php } ?>
                                                                </td>
                                                                <td class="text-center" style="width: 15%">
                                                                        <?php if($value['booking_bonus_status']==1){?>
                                                                            Active
                                                                         <?php }else{?>
                                                                            In-active
                                                                          <?php } ?>
                                                                </td>
                                                                <td class="text-center" style="width: 10%">
                                                                  <?php if($value['booking_bonus_coin']!=0){?>
                                                                            <?=$value['booking_bonus_coin']?>
                                                                         <?php }else{?>
                                                                            In-active
                                                                          <?php } ?>
                                                                </td>
                                                               <td class="text-center" style="width: 10%">
                                                                  <?php if($value['status']==1){?>
                                                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_up_coin')) { ?>
                                                                            <a href="<?=base_url()?>refer_friend/change_refer_friend_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                                                         <?php }?>
                                                                  <?php } else{?>
                                                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_up_coin')) { ?>     
                                                                            <a href="<?=base_url()?>refer_friend/change_refer_friend_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                                         <?php }?>
                                                                  <?php } ?>
                                                                </td>
                                                                  
                                                            </tr>
                                          <?php $i++;} ?>   
                                        
                                       
                                     </tbody>
                                    <tfoot>
                                    <tr>
                                              <th class="text-center" style="width: 5%">#</th>
                                              <th class="text-center" style="width: 10%">City</th>
                                              <th class="text-center" style="width: 10%">Start Date</th>
                                              <th class="text-center" style="width: 10%">End Date</th>
                                              <th class="text-center" style="width: 10%">Install Status</th>
                                              <th class="text-center" style="width: 10%">Install Count</th>
                                              <th class="text-center" style="width: 10%">Install Bonus Coin</th>
                                              <th class="text-center" style="width: 15%">Booking Bonus Status</th>
                                              <th class="text-center" style="width: 10%">Booking Bonus Coin</th>
                                              <th class="text-center" style="width: 10%">status</th>
                                             
                                          </tr>
                                    </tfoot>
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
                        <form role="form" method="post" action="<?=base_url();?>refer_friend">
                            <div class="box-body">
                                <div class="form-group " >
                                    <label for=""> City</label>
                                    <div class="input-group">
                                         <select class="select2" style="width: 250px;" name="city" id="city" >
                                              <option value="">All City</option>
                                               <?php foreach ($city as $val):?>
                                                   <option value="<?=$val->id?>"><?=$val->location?></option>
                                              <?php endforeach;?>
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
















