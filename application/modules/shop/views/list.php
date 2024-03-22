
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
          <h1>Shops</h1>
          <div class="col-md-8" >
               <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_shop')) { ?>
            <a href="<?php echo base_url('shop/add') ?>" class="btn btn-sm pull-right btn-warning" style="margin-top: -29px;margin-right: -230px;">Add Shop<i class="fa fa-plus"></i></a>
            <?php } ?>
                <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -358px;background-color: #d47b25;">Filter</button>
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
                                              <th class="text-center" style="width: 15%">Name</th>
                                              <th class="text-center" style="width: 15%">Location</th>
                                              <th class="text-center" style="width: 15%">Area</th>
                                              <th class="text-center" style="width: 15%">Timing</th>
                                              <th class="text-center" style="width: 15%">Phone</th>
                                              <th class="text-center" style="width: 15%">Status</th>
                                              <th class="text-center" style="width: 15%">Action</th>
                                              
                                          </tr>
                                      </thead>
                                     <tbody>
                                          <?php $i=1; foreach ($list as $key => $value) { ?>
                                                            <tr>
                                                                <td class="text-center" style="width: 5%"><?=$i?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['name']?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['location']?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['area']?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['timing']?></td>
                                                                <td class="text-center" style="width: 15%"><?=$value['phone']?></td>
                                                                <td class="text-center" style="width: 15%">
                                                                      <?php if($value['status']==1){?>
                                                                          <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) { ?>
                                                                                <a href="<?=base_url()?>shop/change_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                                                          <?php }?>
                                                                      <?php } else{?>
                                                                          <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) { ?>   
                                                                                <a href="<?=base_url()?>shop/change_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                                          <?php }?>
                                                                      <?php } ?>
                                                                </td>
                                                                <td class="text-center" style="width: 15%">
                                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_shop')) { ?>
                                                                      <a href="<?=base_url()?>shop/edit/<?=$value['id']?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                                                  <?php } ?>
                                                                  <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_shop')) { ?>
                                                                      <a href="<?=base_url()?>shop/delete_shop/<?=$value['id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                                  <?php } ?>
                                                                </td>
                                                                  
                                                            </tr>
                                          <?php $i++;} ?>   
                                     </tbody>
                                    <tfoot>
                                    <tr>
                                              <th class="text-center" style="width: 5%">#</th>
                                              <th class="text-center" style="width: 15%">Name</th>
                                              <th class="text-center" style="width: 15%">Location</th>
                                              <th class="text-center" style="width: 15%">Area</th>
                                              <th class="text-center" style="width: 15%">Timing</th>
                                              <th class="text-center" style="width: 15%">Phone</th>
                                              <th class="text-center" style="width: 15%">Status</th>
                                              <th class="text-center" style="width: 15%">Action</th>
                                             
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
                        <form role="form" method="post" action="<?=base_url();?>shop">
                            <div class="box-body">
                                <div class="form-group " >
                                    <label for=""> City</label>
                                    <div class="input-group">
                                         <select class="select2" style="width: 250px;" name="city" id="city" >
                                              <option></option>
                                              <?php foreach ($locations as $key => $value) { ?>
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
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true
      
    });
    
   
  });


</script>
















