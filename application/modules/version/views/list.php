<div class="content-wrapper">
     <section class="content-header">
          <h1>Versions</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">List</h3>
                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_version')) { ?>
	                              <a href="<?php echo base_url('version/add') ?>" class="btn btn-sm pull-right btn-warning">Add Version <i class="fa fa-plus"></i></a>
	                         <?php } ?>
                         </div>
            
                         <div class="box-body">
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <th class="text-center">Platform</th>
                                             <th class="text-center">Identifier</th>
                                             <th class="text-center">Version Name</th>
                                             <th class="text-center">Version Code</th>
                                             <th class="text-center">Mandatory Or Not</th>
                                             <th class="text-center">Action</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($version as $key => $value) { ?>
                                        <tr>
                                             <td class="text-center"><?=$i?></td>
                                             <td class="text-center"><?=$value['platform']?></td>
                                             <td class="text-center"><?=$value['identifier']?></td>
                                             <td class="text-center"><?=$value['version_name']?></td>
                                             <td class="text-center"><?=$value['version_code']?></td>
                                             <td class="text-center">
                                                  <?php if ($value['optional']=="True") {
                                                       echo "Mandatory";
                                                  }elseif ($value['optional']=="False") {
                                                       echo "Optional";
                                                  }?></td>
                                             <td class="text-center">
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_version')) { ?>
                                                  <a href="<?=base_url()?>version/edit/<?=$value['version_id']?>" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a>
                                                <?php } ?>
                                                <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_version')) { ?>
                                                  <a href="<?=base_url()?>version/delete/<?=$value['version_id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                <?php } ?>  
                                             </td>
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
     </section>
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
      "scrollX" : true,
        "scrollCollapse" : true
      
    });
  });
</script> 
  


