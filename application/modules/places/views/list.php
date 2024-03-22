<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        City
      
      </h1>
      
    </section>
<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List</h3>
               <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_location')) { ?> 
            <a href="<?php echo base_url('places/location_add') ?>" class="btn btn-sm pull-right btn-warning">Add City <i class="fa fa-plus"></i></a>
            <a href="<?php echo base_url('places/bulk_upload_city') ?>" class="btn btn-sm pull-right btn-info" style="margin-right: 4px">Bulk Upload <i class="fa fa-upload"></i></a>
            <?php } ?>
            </div>
            
            <div class="box-body">
              <table id="datatable-locations" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>City</th>
                  <th>Status</th>
                  <th>Action</th>
                 
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>City</th>
                  <th>Status</th>
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
 
  


