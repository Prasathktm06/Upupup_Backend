<div class="content-wrapper">
     <section class="content-header">
          <h1>Users</h1>
     </section>

     <section class="content">
          <div class="row">
               <div class="col-md-12">
                    <div class="box">
                         <div class="box-header with-border">
                              <div class="title_right">
                                   <?php if($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'add_user')) { ?> 
                                        <a href="<?php echo base_url('acl/user/add') ?>" class="btn btn-sm pull-right btn-warning">Add User <i class="fa fa-plus"></i></a>
                                   <?php } ?>
                              </div>
                              <h3 class="box-title">List</h3>
                         </div>
                         <div class="box-body">
                              <table id="acl_user" class="table table-bordered table-hover">
                                   <thead>
                                        <tr>
                                             <th class="text-center">#</th>
                                             <th class="text-center">Name</th>
                                             <th class="text-center">Email</th>
                                             <th class="text-center">Roles</th>
                                             <th class="text-center">Status</th>
                                             <th class="text-center">Action</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php if(is_array($user_list)) foreach($user_list as $key=>$user): ?>
                                        <tr>
                                             <td class="text-center"><?= ++$key; ?></td>
                                             <td class="text-center"><?= $user->name; ?></td>			
                                             <td class="text-center"><?= $user->email; ?></td>
                                             <?php if(is_array($user->roles)) foreach($user->roles as $role): ?>
                                             <td class="text-center"><?= $role->name; ?></td>
                                             <?php endforeach; ?>
                                              <td class="text-center">
                                                  <?php if($user->status){?>
                                                       <a href="<?=base_url()?>acl/user/change_status/<?=$user->user_id?>/<?=$user->status?>" type="button" class="btn btn-success">Active</i></a>
                                                  <?php } else{?>
                                                       <a href="<?=base_url()?>acl/user/change_status/<?=$user->user_id?>/<?=$user->status?>" type="button" class="btn btn-danger">Inactive</i></a>
                                                  <?php } ?>
                                             </td>
                                             <td>

                                             <?= ($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'edit_user')) ? anchor('acl/user/edit/' . $user->user_id, '<i class="fa fa-pencil"></i> ', array('class' => 'btn btn-small','title'=>'Edit')) : NULL; ?>
                                             <?= ($this->acl_model->user_has_perm($this->session->userdata('user_id'), 'delete_user')) ? anchor('acl/user/del/' . $user->user_id , '<i class="fa fa-trash"></i> ', array('class' => ' btn-small delete','title'=>'Delete')) : NULL; ?>	
                                             </td>
                                             
                                        </tr>
                                        <?php endforeach; ?>
                                   </tbody>
                              </table>
                  
		                    <?php if(!is_array($user_list)): ?>
		                        <p class="alert alert-info">No users to find here...</p>
		                    <?php endif; ?>
                         </div>
                    </div>
               </div>
          </div>
     </section>
</div>




