
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
          <h1>Hot Image Slider</h1>
                       <a href="<?php echo base_url('hot_offer_slider/add_slider') ?>" class="btn btn-sm pull-right btn-warning" style="margin-top: -29px;margin-right: -230px;">Add slide images <i class="fa fa-plus"></i></a>

          <div class="col-md-8" >
              
              
               <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'view_hot_slider_images')) { ?>
            <a href="<?php echo base_url('hot_offer_slider/add_slider') ?>" class="btn btn-sm pull-right btn-warning" style="margin-top: -29px;margin-right: -200px;">Add slide images <i class="fa fa-plus"></i></a>
            
           

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
                                              <th class="text-center" style="width: 15%">City</th>
                                              <th class="text-center" style="width: 15%">Date</th>
                                              <th class="text-center" style="width: 15%">Life</th>
                                              <th class="text-center" style="width: 15%">Percentage</th>
                                              <th class="text-center" style="width: 15%">status</th>
                                              <th class="text-center" style="width: 15%">Action</th>
                                              
                                          </tr>
                                      </thead>
                                     <tbody>
                                        
                                                            <tr>
                                                                <td class="text-center" style="width: 5%">1</td>
                                                                <td class="text-center" style="width: 15%">2</td>
                                                                <td class="text-center" style="width: 15%">3</td>
                                                                 <td class="text-center">&nbsp;</td>
                                                                <td class="text-center" style="width: 15%">4</td>
                                                                <td class="text-center" style="width: 15%">5</td>
                                                               <td class="text-center" style="width: 15%">6</td>
                                                                <td class="text-center" style="width: 15%"><a href="#" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>    
                                                                
                                                                  
                                                            </tr>
                                       
                                     </tbody>
                                    <tfoot>
                                    <tr>
                                              <th class="text-center" style="width: 5%">#</th>
                                              <th class="text-center" style="width: 15%">Name</th>
                                              <th class="text-center" style="width: 15%">City</th>
                                              <th class="text-center" style="width: 15%">Date</th>
                                              <th class="text-center" style="width: 15%">Life</th>
                                              <th class="text-center" style="width: 15%">Percentage</th>
                                              <th class="text-center" style="width: 15%">status</th>
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
                        <form role="form" method="post" action="<?=base_url();?>hot_offer/add_filter">
                            <div class="box-body">

                                <div class="form-group year" >
                                    <label for="">Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="day" type="text" class="form-control datepicker" id="datepicker" >
                                    </div>
                                </div>
                                
                                
                                
                                <div class="form-group " >
                                    <label for=""> City</label>
                                    <div class="input-group">
                                         <select class="select2" style="width: 250px;" name="city" id="city" >
                                              <option></option>
                                               <?php foreach ($city as $val):?>
                                                   <option <?php if(in_array($val->id,explode(',', $venue_location->location_id)))  ?> value="<?=$val->id?>"><?=$val->location?></option>
                                                <?php endforeach;?>
                                         </select>
                                    </div>
                               </div>

                              <div class="form-group">
                                    <label for="exampleInputPassword1">Percentage</label>
                                    <input value="" type="number" name="percentage" id="percentage" class="form-control"  placeholder="Hot Offer %"  onChange="venue_list_count()" value="1" max="100" min="1" >
                                </div>
                                


                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                

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
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "scrollX" : true,
        "scrollCollapse" : true
      
    });
    
   
  });


</script>
















