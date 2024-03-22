<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

<h1>

Area



</h1>





</section>

<section class="content">

<div class="row">

<div class="col-xs-12">

<div class="box">

<div class="box-header">

<h3 class="box-title">List</h3>

<?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_area')) { ?>

		        <a href="<?php echo base_url('places/area_add') ?>" class="btn btn-sm pull-right btn-warning">Add Area <i class="fa fa-plus"></i></a>

		        <?php } ?>

            </div>

            

            <div class="box-body">

              <table id="datatable-area" class="table table-bordered table-hover">

                <thead>

                <tr>

                  <th>#</th>

                  <th>Area</th>
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

                  <th>Area</th>
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

 

  





