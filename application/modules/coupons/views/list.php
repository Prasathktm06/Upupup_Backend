<div class="content-wrapper">
     <section class="content-header">
          <h1>Coupons</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">List</h3>
                              <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_offer')) { ?>
	                              <a href="<?php echo base_url('coupons/add') ?>" class="btn btn-sm pull-right btn-warning">Add Coupon <i class="fa fa-plus"></i></a>
	                         <?php } ?>
                         </div>
            
                         <div class="box-body">
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <th class="text-center">Coupon</th>
                                             <th class="text-center">City</th>
                                             <th class="text-center">Area</th>
                                             <th class="text-center">Code</th>
                                             <th class="text-center">Description</th>
                                             <th class="text-center">Valid From</th>
                                             <th class="text-center">Valid To</th>
                                             <th class="text-center">Expire Status</th>
                                             <th class="text-center">Action</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($table as $key => $value) { ?>
                                        <tr>
                                             <td class="text-center"><?=$i?></td>
                                             <td class="text-center">
                                                  <?php if ($value['percentage']=="Yes") { ?>
                                                  <?=$value['coupon_value']?>%
                                                  <?php }else if ($value['percentage']=="No") { ?>
                                                  <?=$value['currency']?> <?=$value['coupon_value']?>
                                                  <?php }?>
                                             </td>
                                             <?php if ($value['coupon_value']=='Yes') { ?>
                                                  <td class="text-center"><?=$value['coupon_value']?>%</td>
                                             <?php }else if ($value['coupon_value']=='No') { ?>
                                                  <td class="text-center"><?=$value['currency']?><?=$value['coupon_value']?></td>
                                             <?php }?>
                                             <td class="text-center"><?=$value['city']?></td>
                                             <td class="text-center"><?=$value['area']?></td>
                                             <td class="text-center"><?=$value['coupon_code']?></td>
                                             <td class="text-center"><?=$value['description']?></td>
                                             <td class="text-center"><?=date( ' d M Y ',strtotime($value['valid_from']))?></td>
                                             <td class="text-center"><?=date( ' d M Y ',strtotime($value['valid_to']))?></td>
                                             <td class="text-center">
                                                  <?php if ($value['expire_status']=="Invalid") { ?>
                                                       <span  class="text-danger "><?=$value['expire_status']?></span>
                                                  <?php }else{ ?>
                                                       <span class="text-success "><?=$value['expire_status']?></span>
                                                  <?php } ?>
                                                  </td>
                                             <td class="text-center">
                                                  <a href="<?=base_url()?>coupons/edit/<?=$value['coupon_id']?>" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a>
                                                  <a href="<?=base_url()?>coupons/delete/<?=$value['coupon_id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
                                                  <?php if($value['status']){?>
                                                       <a href="<?=base_url()?>coupons/change_status/<?=$value['coupon_id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                                  <?php } else{?>
                                                       <a href="<?=base_url()?>coupons/change_status/<?=$value['coupon_id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
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
       $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
      "scrollY" : true,
        "scrollCollapse" : true
      
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "scrollX" : true,
      "scrollY" : true,
        "scrollCollapse" : true
      
    });
  });
</script>
  


