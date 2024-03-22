<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Role
      
      </h1>		
      
     
    </section>

   
    <section class="content">
      <div class="row">
        <div class="col-md-12">
        
          <div class="box">
          
            <div class="box-header with-border">
               <div class="title_right">
        <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_role')) { ?>
        <a href="<?php echo base_url() ?>acl/role/add" class="btn btn-sm pull-right btn-warning">Add Role <i class="fa fa-plus"></i></a>
        <?php } ?>
      </div>
      
              <h3 class="box-title">List</h3>
    </div>
  <div class="box-body">

                <table id="acl_role" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Slug</th>
                      <th>Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>


                  <tbody>
                    <?php if(is_array($role_list)) foreach($role_list as $key=>$role): ?>
                    <tr>
	                    <td><?= ++$key; ?></td>
                      <td><?= $role->name; ?></td>
	                    <td><?= $role->slug; ?></td>			
						<td><?= $role->description; ?></td>
						<td>
							<?= ($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_role')) ? anchor('acl/role/edit/' . $role->role_id, '<i class="fa fa-pencil"></i> ', array('class' => 'btn btn-small','title'=>'Edit')) : NULL; ?>
							<?= ($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_role')) ? anchor('acl/role/del/' . $role->role_id, '<i  class="fa fa-trash"></i> ', array('class' => ' btn-small delete','title'=>'Delete')) : NULL; ?>
						</td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
     </div>
          </div>

    </div>
    </div>
</section>
  </div>


