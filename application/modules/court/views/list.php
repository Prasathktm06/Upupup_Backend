<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Court

</h1>



</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">List</h3>
<?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_court')) { ?>
            <button type="button" class="btn btn-sm pull-right btn-warning" data-toggle="modal" data-target="#myModal2">Add Court <i class="fa fa-plus"></i></button>
		   
		        <?php } ?>
            </div>
            <input type="hidden" id="user_id" value="<?=$user_id?>">
            <div class="box-body">
              <table id="datatable-court-manager" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sport</th>
                  <th>Venue</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sport</th>
                  <th>Venue  </th>
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
 
  



<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select a venue:</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
                <label class="col-sm-2 control-label">Venue</label>
                <div class="col-sm-6">
                <select name="venue" class="form-control select2"  data-placeholder="Select a Sport" style="width: 100%;">
                  <?php foreach ($all_venue as $val):?>
                  <option value="<?= $val->id?>"><?=$val->venue?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" name='save'>Save</button>
      </div>
    </div>

  </div>
</div>  
<script type="text/javascript">
  $('[name=save]').on('click',function(){
  var venue=  $('[name=venue] :selected').val();
    window.location.href = "<?=base_url('court/add/')?>"+venue+'/RoEVWd2HFurx8AeKp4uwCg';
  });

</script>