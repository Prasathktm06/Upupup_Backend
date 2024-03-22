<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Venue

</h1>

</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">List</h3>
<?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_venue')) { ?>
            <a href="<?php echo base_url('venue/venue_add') ?>" class="btn btn-sm pull-right btn-warning">Add Venue <i class="fa fa-plus"></i></a>
            <?php } ?>
            </div>
            
            <div class="box-body">
              <table id="example1" class="table table-bordered table-hover">
                 <thead>
                      <tr>
                          <th class="text-center" style="width: 5%">#</th>
                          <th class="text-center" style="width: 15%">Venue</th>
                          <th class="text-center" style="width: 15%">Location</th>
                          <th class="text-center" style="width: 15%">Area</th>
                          <th class="text-center" style="width: 15%">Phone</th>
                          <th class="text-center" style="width: 15%">Status</th>
                          <th class="text-center" style="width: 15%">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php $i=1; foreach ($list as $key => $value) { ?>
                                        <tr>
                                            <td class="text-center" style="width: 5%"><?=$i?></td>
                                            <td class="text-center" style="width: 15%"><?=$value['venue']?></td>
                                            <td class="text-center" style="width: 15%"><?=$value['location']?></td>
                                            <td class="text-center" style="width: 15%"><?=$value['area']?></td>
                                            <td class="text-center" style="width: 15%"><?=$value['phone']?></td>
                                            <td class="text-center" style="width: 15%">
                                              <?php if($value['status']==1){?>
                                               <a href="<?=base_url()?>venue/change_status/<?=$value['id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                              <?php } else{?>
                                                        
                                                <a href="<?=base_url()?>venue/change_status/<?=$value['id']?>/<?=$value['status']?>/<?=$value['venue_id']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                              <?php } ?>
                                            </td>
                                            <td class="text-center" style="width: 15%">
                                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_venue')) { ?>
                                                  <a href="<?=base_url()?>venue/venue_edit/<?=$value['id']?>" class=" btn btn-small" title="Edit"><i class="fa fa-pencil"></i></a>
                                              <?php } ?>
                                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_venue')) { ?>
                                                  <a href="<?=base_url()?>venue/delete/<?=$value['id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                              <?php } ?>
                                            </td>
                                              
                                        </tr>
                      <?php $i++;} ?>   
                 </tbody>
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
    
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true,
        "iDisplayLength": 15,
       "aLengthMenu": [[15, 25, 50, 100], [ 15, 25, 50, 100]]
      
    });
  });


</script>
 
  


