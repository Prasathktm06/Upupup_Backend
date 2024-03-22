<div class="content-wrapper">
     <section class="content-header">
          <h1>FAQ</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">List</h3>
                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_offer')) { ?>
	                              <a href="<?php echo base_url('faq/add') ?>" class="btn btn-sm pull-right btn-warning">Add <i class="fa fa-plus"></i></a>
	                         <?php } ?>
                         </div>
            
                         <div class="box-body">
                            <div class="table-responsive">
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <th class="text-center">Question</th>
                                             <th class="text-center">Answer</th>
                                             <th class="text-center">Added Date</th>
                                             <th class="text-center">Status</th>
                                             <th class="text-center">Action</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($table as $key => $value) { ?>
                                        <tr>
                                             <td class="text-center"><?=$i?></td>
                                             <td class="text-center"><?=$value['question']?></td>
                                             <td class="text-center"><?=$value['answer']?></td>
                                             <td class="text-center"><?= date( ' d M Y h:i:s A',strtotime($value['added_on']))?></td>
                                             <td class="text-center">
                                                  <?php if($value['status']){?>
                                                       <a href="<?=base_url()?>faq/change_status/<?=$value['faq_id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                                  <?php } else{?>
                                                       <a href="<?=base_url()?>faq/change_status/<?=$value['faq_id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                  <?php } ?>
                                             </td>
                                             <td class="text-center">
                                                  <a href="<?=base_url()?>faq/edit/<?=$value['faq_id']?>" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a>
                                                  <a href="<?=base_url()?>faq/delete/<?=$value['faq_id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
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