<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permissions
      
      </h1>
      
      
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
        
          <div class="box">
          
            <div class="box-header with-border">
               <div class="title_right">
		        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_perm')) { ?> 
		        <a href="<?php echo base_url('acl/perm/add') ?>" class="btn btn-sm pull-right btn-warning">Add Permission <i class="fa fa-plus"></i></a>
		        <?php } ?>
    		</div>
            
              <h3 class="box-title">List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <table id="acl_perm" class="table table-bordered table-hover">
                  <thead>
                    <tr>	
                 <th>#</th>
                 <th class="column-title">Name</th>
                <th class="column-title">Slug</th>
                <th class="column-title">Description</th>
                <th class="column-title">Action</th>
                    </tr>
                  </thead>


      <tbody>
        <?php if (is_array($perm_list)) foreach($perm_list as $key=>$perm): ?>
        <tr>
            <td><?= ++$key ?></td>
        <td><?= $perm->name; ?></td>
        <td><?= $perm->slug; ?></td>
        <td><?= $perm->description; ?></td>
        <td>
            <?= ($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_perm')) ? anchor('acl/perm/edit/' . $perm->perm_id, '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-small','title'=>'Edit')) : null; ?>
            <?= ($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_perm')) ? anchor('acl/perm/del/' . $perm->perm_id, '<i  class="fa fa-trash"></i> ', array('class' => ' btn-small delete','title'=>'Delete')) : null; ?>
        </td>
        </tr>
        <?php endforeach; ?>
          
      </tbody>
    </table>
                  
		
              </div>
    <!--        <div class="well">
			<h4>Add User</h4>
			<p>You do not have permission to view this form</p>
		</div>-->
		<?php //endif; ?>
            </div>
          </div>
	<?php if (!$this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_perm')): ?>
		<div class="well">
			<h4>Add Permission</h4>
			<p>You do not have permission to view this form</p>
		</div>
		<?php endif; ?>
    </div>
</section>
  </div>




