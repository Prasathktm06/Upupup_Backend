<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Notification Setting
</h1>


</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">List</h3>

<?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_hot_offer_notification')) { ?>
            <a href="<?php echo base_url('hot_offer/add_notification') ?>" class="btn btn-sm pull-right btn-warning">Add Setting<i class="fa fa-plus"></i></a>
            <?php } ?>
 </div>
            
            <div class="box-body">
              
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                      <tr>
                          <th class="text-center" style="width: 5%">#</th>
                          <th class="text-center" style="width: 15%">City</th>
                          <th class="text-center" style="width: 15%">Notification Type</th>
                          <th class="text-center" style="width: 15%">Time1</th>
                          <th class="text-center" style="width: 15%">Time2</th>
                          <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_hot_offer_notification')) { ?>
                          <th class="text-center" style="width: 15%">Status</th>
                          <?php } ?>
                          
                          <th class="text-center" style="width: 15%"><?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_hot_offer_notification') ||$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_hot_offer_notification') ) { ?>Action<?php } ?></th>
                          
                          
                      </tr>
                  </thead>
                 <tbody>
                      <?php $i=1; foreach ($list as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center" style="width: 15%"><?=$value['location']?></td>
                                            <td class="text-center" style="width: 15%"><?=$value['notification_name']?></td>
                                            <td class="text-center" style="width: 15%"><?=date( ' h:i:s A ',strtotime($value['time1']))?></td>
                                            <td class="text-center" style="width: 15%"><?=date( ' h:i:s A ',strtotime($value['time2']))?></td>
                                            <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_hot_offer_notification')) { ?>
                                           <td class="text-center" style="width: 15%">
                                              <?php if($value['status']==1){?>
                                              
                                               <a href="<?=base_url()?>hot_offer/change_not_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                               
                                              <?php } else{?>
                                                        
                                                <a href="<?=base_url()?>hot_offer/change_not_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                              <?php } ?>
                                            </td>   
                                            <?php } ?>
                                            
                                            <td class="text-center" style="width: 15%">
                                             <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_hot_offer_notification')) { ?>
                                                  <a href="<?=base_url()?>hot_offer/edit_not_setting/<?=$value['id']?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                              <?php } ?>
                                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_hot_offer_notification')) { ?>
                                                  <a href="<?=base_url()?>hot_offer/delete_not_setting/<?=$value['id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                              <?php } ?>
                                             
                                            </td>
                                             
                                              
                                        </tr>
                      <?php $i++;} ?>   
                 </tbody>
                <tfoot>
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
  </div>
     
     
    </section>
  
  </div>
  <script>
  $(function () {
    $("#example1").DataTable();
  });


</script>
  
 
  


