<div class="content-wrapper">
     <section class="content-header">
          <h1>Feedback</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                              <h3 class="box-title">List</h3>
                         </div>

                         <div class="box-body">
                            <div class="table-responsive">
                              <table id="example1" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th>#</th>
                                             <th>Feedback</th>
                                             <th>User</th>
                                             <th>Phone No</th>
                                             <th>Date</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $i=1; foreach ($table as $key => $value) { ?>
                                        <tr>
                                             <td><?=$i?></td>
                                             <td style="word-break:break-all"><?=$value['feedback']?></td>
                                             <td><?=$value['name']?></td>
                                             <td><?=$value['phone_no']?></td>
                                             <td><?= date( ' d M Y h:i:s A',strtotime($value['time']))?></td>
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
