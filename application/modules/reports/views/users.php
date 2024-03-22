<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/popup.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
<script type="text/javascript">
     $(document).ready(function(){
        $('#city').change(function(){
            $("#area option").remove();
            var url_new="reports/area_list";
            var city = document.getElementById("city").value;
            //console.log(city);
            var base_url="<?php echo base_url(); ?>" ;
            
            $.ajax({
                type:"POST",
                url:base_url+url_new,
                dataType: 'json',
                data: {city: city},
                success:function (res) {
                    $('#area').append($('<option></option>'));
                    $.each(res['area'],function(element,val) {
                        $('#area').append($('<option>', { 
                            value: val.id,
                            text : val.area 
                        }));
                    }); 
                },
            });
        });
    });
</script>

<div class="content-wrapper">
     <section class="content-header">
          <h1>Report</h1>
          <div class="col-md-8" >
            <?php if (count($list)>0) { ?>
               <a href="<?=base_url();?>reports/users_excel" data-toggle="tooltip"  class="btn-info btn-demo pull-right btn-warning" style="margin-top: -29px;margin-right: -358px;background-color: #12c5f5;">Download  <i class="fa fa-download "></i></a> 
            <?php } ?>
               <button type="button" class="btn btn-info btn-demo pull-right" data-toggle="modal" data-target="#myModal2" style="margin-top: -29px;margin-right: -230px;background-color: #d47b25;">Filter</button>
                
                <?php $_SESSION['users']=$list; ?> 
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
                              <div class="table-responsive">
                                   <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                             <tr>
                                                  <th class="text-center">#</th>
                                                  <th class="text-center">City</th>
                                                  <th class="text-center">Sports</th>
                                                  <th class="text-center">Areas</th>
                                                  <th class="text-center">User Name</th>
                                                  <th class="text-center">Phone Number</th>
                                                  <th class="text-center">Email ID</th>
                                                  <th class="text-center">User Channel</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php $i=1; foreach ($list as $key => $value) { ?>
                                             <tr>
                                                  <td class="text-center"><?=$i?></td>
                                                  <td class="text-center"><?=$value['location']?></td>
                                                  <td class="text-center"><?=$value['sports']?></td>
                                                  <td class="text-center"><?=$value['area']?></td>
                                                  <td class="text-center"><?=$value['name']?></td>
                                                  <td class="text-center"><?=$value['phone_no']?></td>
                                                  <td class="text-center"><?=$value['email']?></td>
                                                  <td class="text-center"><?php if($value['user_channel']==2){ ?>Vendor App <?php }else{ ?> User App <?php } ?></td>
                                             </tr>
                                             <?php $i++;} ?>
                                        </tbody>
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
                         <form role="form" method="post" action="<?=base_url();?>reports/users">
                              <div class="box-body">
                                   <div class="form-group " >
                                        <label for=""> City</label>
                                        <div class="input-group">
                                             <select class="select2" style="width: 250px;" name="city" id="city" required="required">
                                                  <option></option>
                                                  <?php foreach ($location as $key => $value) { ?>
                                                       <option value="<?=$value['id']?>"><?=$value['location']?></option>
                                                  <?php } ?>
                                             </select>
                                        </div>
                                   </div>

                                   <div class="form-group " >
                                        <label for="">Area</label>
                                        <div class="input-group">
                                             <select class="select2" style="width: 250px;" name="area" id="area" >
                                                <option value=""></option>
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
 <script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollX" : true,
      "scrollCollapse" : true
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
  


