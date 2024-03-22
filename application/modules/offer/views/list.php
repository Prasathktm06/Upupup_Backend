<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Offer
</h1>


</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">List</h3>

<?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_offer')) { ?>
            <a href="<?php echo base_url('offer/add') ?>" class="btn btn-sm pull-right btn-warning">Add Offer <i class="fa fa-plus"></i></a>
            <?php } ?>
 </div>
            
            <div class="box-body">
              <table id="datatable-offer" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Offer</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                 <th>Action</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                   <th>#</th>
                  <th>Offer</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                 <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
  </div>
     
     
    </section>
  
  </div>
 
  


