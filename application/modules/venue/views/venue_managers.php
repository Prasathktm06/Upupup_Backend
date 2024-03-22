<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script type="text/javascript">
     $(function() {
          $('input[name="type"]').on('click', function() {
               if ($(this).val() == 'Year') {
                    $('.year').show();
                    $('.date_data').hide();
               }
               else if ($(this).val() == 'Date')  {
                    $('.year').hide();
                    $('.date_data').show();
               }
          });
     });
</script>
<div class="content-wrapper">
     <section class="content-header">
          <h1>List</h1>
               <a href="<?php echo base_url('venue/add_manager') ?>" class="btn btn-sm pull-right btn-warning">Add<i class="fa fa-plus"></i></a>
          
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">Venue Managers</h3>
                         </div>
            
                         <div class="box-body">
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <th class="text-center">User Name</th>
                                             <th class="text-center">Phone No.</th>
                                             <th class="text-center">Email ID</th>
                                             <th class="text-center">Role</th>
                                             <th class="text-center">Venue Managed</th>
                                             <th class="text-center">Courts Managed</th>
                                             <th class="text-center">Status</th>
                                             <th class="text-center">Actions</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($managers as $key => $value) { ?>
                                        <tr>
                                             <td class="text-center"><?=$i?></td>
                                             <td class="text-center"><?=$value['name']?></td>
                                             <td class="text-center"><?=$value['phone']?></td>
                                             <td class="text-center"><?=$value['email']?></td>
                                             <td class="text-center"><?=$value['rolename']?></td>
                                             <td class="text-center">
                                                  <?php if ($value['slug']=="venue_manager" ) { 
                                                       echo $value['venue'];
                                                  }?>
                                             </td>
                                             <td class="text-center">
                                                  <?php if ($value['slug']=="court_manager" ) { 
                                                       echo $value['courts'];
                                                  }?>
                                             </td>
                                             <td class="text-center">
                                             <?php if($value['status']){?>
                                                       <a href="<?=base_url()?>venue/user_change_status/<?=$value['user_id']?>/<?=$value['status']?>" type="button" class="btn btn-success">Active</i></a>
                                                  <?php } else{?>
                                                       <a href="<?=base_url()?>venue/user_change_status/<?=$value['user_id']?>/<?=$value['status']?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                  <?php } ?>
                                             </td>
                                             <td class="text-center">
                                                  <a href="<?=base_url()?>venue/edit_manager/<?=$value['user_id']?>" class="btn btn-small" title="Edit" ><i class="fa fa-pencil"></i> </a> 
                                                  <a href="<?=base_url()?>venue/delete_venue_users/<?=$value['user_id']?>/<?=$value['venue_id']?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a>
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
      "autoWidth": false
    });
  });

</script>
  


