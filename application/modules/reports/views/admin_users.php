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
          <h1>Report</h1>
          <div class="col-md-8" >
             <?php if (count($list)>0) { ?>
                <a href="<?=base_url();?>reports/backend_users_excel" data-toggle="tooltip"  class="btn-info btn-demo pull-right btn-warning" style="margin-top: -29px;margin-right: -358px;background-color: #12c5f5;">Download  <i class="fa fa-download "></i></a>
              <?php } ?>
                <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button> 
                <?php $_SESSION['backend_users']=$list; ?> 
          </div>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title"><?=$heading?></h3>
                         </div>
            
                         <div class="box-body">
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <!-- <th class="text-center">Image</th> -->
                                             <th class="text-center">User Name</th>
                                             <th class="text-center">Phone No.</th>
                                             <th class="text-center">Email ID</th>
                                             <th class="text-center">Role</th>
                                             <th class="text-center">Venue Managed</th>
                                             <th class="text-center">Court Managed</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($list as $key => $value) { ?>
                                        <tr>
                                             <td class="text-center"><?=$i?></td>
                                             <!-- <td class="text-center">
                                                  <?php if ($value['image']=="") { ?>
                                                       <img src="<?=base_url();?>assets/img/user_default.png" alt="avatar" class="img-circle" width="100px" height="100px">
                                                  <?php }else{ ?>
                                                       <img src="<?=$value['image']?>" alt="avatar" class="img-circle" width="100px" height="100px">
                                                  <?php } ?>
                                             </td>  -->
                                             <td class="text-center"><?=$value['name']?></td>
                                             <td class="text-center"><?=$value['phone']?></td>
                                             <td class="text-center"><?=$value['email']?></td>
                                             <td class="text-center"><?=$value['role_name']?></td>
                                             <td class="text-center">
                                                  <?php if ($value['slug']=="venue_manager" || $value['slug']=="court_manager" || $value['slug']=="venue_owner") { 
                                                       echo $value['venue'];
                                                  }?>
                                             </td>
                                             <td class="text-center"><?=$value['court']?></td>
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
                         <form role="form" method="post" action="<?=base_url();?>reports/backend_users">
                              <div class="box-body">
                                   <div class="form-group">
                                        <label for="exampleInputPassword1">Username</label>
                                        <select class="form-control select2" style="width: 100%;" name="name" id="name">
                                             <option></option>
                                             <?php foreach ($users as $key3 => $value3) { ?>
                                                  <option value="<?=$value3['name']?>"><?=$value3['name']?></option>
                                             <?php } ?>
                                        </select>
                                   </div>

                                   <div class="form-group">
                                        <label for="exampleInputPassword1">Email</label>
                                        <select class="form-control select2" style="width: 100%;" name="email" id="email">
                                             <option></option>
                                             <?php foreach ($users_email as $key4 => $value4) { ?>
                                                  <option value="<?=$value4['email']?>"><?=$value4['email']?></option>
                                             <?php } ?>
                                        </select>
                                   </div>

                                   <div class="form-group">
                                        <label for="exampleInputPassword1">Role</label>
                                        <select class="form-control select2" style="width: 100%;" name="role" id="role">
                                             <option></option>
                                             <?php foreach ($roles as $key2 => $value2) { ?>
                                                  <option value="<?=$value2['role_id']?>"><?=$value2['name']?></option>
                                             <?php } ?>
                                        </select>
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
$(document).ready(function() {
  $(".datepicker").datepicker( {
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years"
});
});
</script>
  


