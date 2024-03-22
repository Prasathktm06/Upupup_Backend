
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
          <h1>UP Coin Settings</h1>
                       

          <div class="col-md-8" >
              
              
               
            
             <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_up_coin')) { ?>
                                    <a href="<?php echo base_url('up_coin/add_upcoin') ?>" class="btn btn-sm pull-right btn-warning" style="margin-top: -29px;margin-right: -200px;">Add Setting <i class="fa fa-plus"></i></a>
                                    <?php }?>
           

           
          
          

          
          
          
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
                                              <th class="text-center" style="width: 15%">Rupee</th>
                                              <th class="text-center" style="width: 15%">Coin</th>
                                              <th class="text-center" style="width: 15%">status</th>
                                              
                                          </tr>
                                      </thead>
                                     <tbody>

                                       <?php $i=1; foreach ($list as $key => $value) { ?>
                                                            <tr>
                                                                <td class="text-center" style="width: 5%"><?=$i?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['rupee']?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['coin']?></td>
                                                               <td class="text-center" style="width: 15%">
                                                                  <?php if($value['status']==1){?>
                                                                        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_up_coin')) { ?>
                                                                            <a href="<?=base_url()?>up_coin/change_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                                                        <?php }?>
                                                                  <?php } else{?>
                                                                         <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_up_coin')) { ?>   
                                                                                <a href="<?=base_url()?>up_coin/change_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                                        <?php }?>
                                                                  <?php } ?>
                                                                </td>
                                                                  
                                                            </tr>
                                          <?php $i++;} ?>   
                                        
                                       
                                     </tbody>
                                    <tfoot>
                                    <tr>
                                              <th class="text-center" style="width: 5%">#</th>
                                              <th class="text-center" style="width: 15%">Rupee</th>
                                              <th class="text-center" style="width: 15%">Coin</th>
                                              <th class="text-center" style="width: 15%">status</th>
                                             
                                          </tr>
                                    </tfoot>
                                  </table>
                              </div>
                         </div>
                    </div>
             </div>
         </div>
     </section>

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
















