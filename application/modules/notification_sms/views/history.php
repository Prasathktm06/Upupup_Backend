<style type="text/css">
     .img-thumbnail {
    width: 65px;
    height: 80px;
}
</style>
<div class="content-wrapper">
     <section class="content-header">
          <h1>Notifications And SMS</h1>
     </section>
     <section class="content">
          <div class="row">
               <div class="col-xs-12">
                    <div class="box">
                         <div class="box-header">
                         </div>

                         <div class="box-body">
                              <ul class="nav nav-tabs" role="tablist">
                                   <li role="presentation" class="active " style="width: 50%;"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">General Notifications</a></li>
                                   <li role="presentation" style="width: 50%;" class="text-center"><a href="#offer" aria-controls="profile" role="tab" data-toggle="tab">Offer Notifications</a></li>
                              </ul>

                              <div class="tab-content ">
                                   <div role="tabpanel" class="tab-pane active " id="general">
                                        <table id="example1" class="table table-bordered table-hover">
                                             <thead>
                                                  <tr>
                                                       <th class="text-center" style="width: 5%">#</th>
                                                       <th class="text-center">Title</th>
                                                       <th class="text-center">Message</th>
                                                       <th class="text-center">City</th>
                                                       <th class="text-center">Area</th>

                                                       <th class="text-center">Sports</th>
                                                       <th class="text-center">Image</th>
                                                       <th class="text-center">Type</th>
                                                       <th class="text-center">Notification/SMS</th>
                                                       <th class="text-center">Send Date</th>

                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php $i=1; foreach ($general as $key => $value) { ?>
                                                  <tr>
                                                       <td class="text-center" style="width: 5%"><?=$i?></td>
                                                       <td class="text-center"><?=$value['title']?></td>
                                                       <td class="text-center"><?=$value['message']?></td>
                                                       <td class="text-center"><?=$value['city']?></td>
                                                       <td class="text-center"><?=$value['area']?></td>
                                                    
                                                       <td class="text-center"><?=$value['sports']?></td>
                                                       <td class="text-center">
                                                            <?php if ($value['image']) { ?>
                                                                 <img src="<?=$value['image']?>" class="img-thumbnail" alt="" >
                                                            <?php } ?>
                                                       </td>
                                                       <td class="text-center"><?=$value['send_type']?></td>
                                                       <td class="text-center"><?=ucfirst($value['type'])?></td>
                                                       <td class="text-center">
                                                          <?php if($value['send_date']!=NULL){?>
                                                              <?=date( ' d M Y ',strtotime($value['send_date']))?>
                                                          <?php }?>
                                                      </td>
                                                  </tr>
                                                  <?php $i++;} ?>
                                             </tbody>
                                        </table>
                                   </div>
                                   <div role="tabpanel" class="tab-pane" id="offer">
                                        <table id="example2" class="table table-bordered table-hover">
                                             <thead>
                                                  <tr>
                                                       <th class="text-center" style="width: 5%">#</th>
                                                       <th class="text-center">Title</th>
                                                       <th class="text-center">Message</th>
                                                       <th class="text-center">City</th>
                                                       <th class="text-center">Area</th>
                                                       <th class="text-center">Offer</th>
                                                       <th class="text-center">Image</th>
                                                       <th class="text-center">Type</th>
                                                       <th class="text-center">Notification/SMS</th>
                                                       <th class="text-center">Send Date</th>

                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php $i=1; foreach ($offer as $key => $value) { ?>
                                                  <tr>
                                                       <td class="text-center" style="width: 5%"><?=$i?></td>
                                                       <td class="text-center"><?=$value['title']?></td>
                                                       <td class="text-center"><?=$value['message']?></td>
                                                       <td class="text-center"><?=$value['city']?></td>
                                                       <td class="text-center"><?=$value['area']?></td>
                                                       <td class="text-center"><?=$value['offer']?></td>
                                                       <td class="text-center">
                                                            <?php if ($value['image']) { ?>
                                                                 <img src="<?=$value['image']?>" class="img-thumbnail" alt="" >
                                                            <?php } ?>
                                                       </td>
                                                       <td class="text-center"><?=$value['send_type']?></td>
                                                       <td class="text-center"><?=ucfirst($value['type'])?></td>
                                                       <td class="text-center">
                                                          <?php if($value['send_date']!=NULL){?>
                                                              <?=date( ' d M Y ',strtotime($value['send_date']))?>
                                                          <?php }?>
                                                      </td>
                                                  </tr>
                                                  <?php $i++;} ?>
                                             </tbody>
                                        </table>
                                   </div>
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
    $("#example2").DataTable();
    $('#example3').DataTable({
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
