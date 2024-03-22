<div class="content-wrapper">
    <section class="content-header">
        <h1>App User</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Users</a></li>
                                
			                </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <form enctype='multipart/form-data' class="form-horizontal" action='<?=base_url("users/edit/$data->id")?>' method="post">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-2 control-label">User</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder=" Name" value="<?=$data->name?>" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-6">
                                                    <input type="email" name="email" id="email" class="form-control"  placeholder=" Email" value="<?=$data->email?>" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Phone Number</label>
 	                                            <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-phone"></i>
                                                        </div>
                                                        <input value="<?=$data->phone_no?>" name="phone" type="text" class="form-control" data-inputmask="'mask': ['9999999999 ', '+9999999999']" data-mask readonly>
                                                    </div>
	                                            </div>
                                            </div>
 	                                        <div class="form-group">
                                                <label for="address" class="col-sm-2 control-label">Address</label>
                                                <div class="col-sm-6">
                                                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Address ..."><?= $data->address?></textarea>
                                                </div>
	                                        </div>
	                                        <div class="form-group">
                                                <label class="col-sm-2 control-label">Sports</label>
                                                <div class="col-sm-6">
                                                    <select name="sports[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Sport" style="width: 100%;">
                                                    <?php foreach ($sports as $val):?>
                                                        <option <?php if(in_array($val->id, explode(',', $data->sports))) echo "selected";?> value="<?=$val->id?>"><?=$val->sports?></option>
                                                    <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">City</label>
                                                <div class="col-sm-6">
                                                    <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                                    <?php foreach ($location as $val):?>
                                                        <option  value="<?= $val->id?>"><?= $val->location?></option>
      	                                            <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Area</label>
                                                <div class="col-sm-6">
                                                    <select class="form-control select2" name="area[]" id="area" style="width: 100%;" multiple="multiple">
                                                    <?php foreach ($area as $key=>$val):?>
                                                        <option <?php if(in_array($val->id, explode(',', $data->area))) echo "selected";?> value="<?=  $val->id?>"><?=$val->area?></option>
                                                    <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="lon" class="col-sm-2 control-label">Image Upload!</label>
                                                <div class="col-sm-6 center-block">
                                                    <?php
                                                        if ($data->image) {
                                                            $image=$data->image;
                                                        }else{
                                                            $image='http://app.appzoc.com/upupup/pics/No_image.svg';
                                                        }
                                                    ?>
                                                    <img  class="img-circle " id="upupup_image" src="<?=$image?>" style="width: 200px;height: 97px;margin-left: 30%">
                                                    <input name="file" type="file" id="exampleInputFile" class="upload" accept="image/*" style="display: none;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                            <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Sports</label>
                                            <div class="col-sm-6">
                                                <select name="sports" id="users-sports" class="form-control select2 users-coPlayers"  style="width: 100%;">
                                                    <option value="" selected disabled>Select a Sport</option>
                                                    <?php foreach ($sports as $val):?>
                                                        <option value="<?=$val->id?>"><?=$val->sports?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <input id="user_id" name="user_id" type="hidden" value="<?= $data->id?>">
                                    </div>
                                    <div class="modal fade" id="myModal2" role="dialog">
                                        <form action="<?=base_url('users/coplayers_rating')?>" method="post">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Rating</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="number" class="form-control" name="rating" >
                                                        <input type="hidden" name="co" id="co">
                                                        <input type="hidden" name="co_sports" id="co_sports">
                                                        <input type="hidden" name="edit" value="<?=$data->id?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div><br>
                                    <div class="box" id="coPlayerTable">
                                        <div class="box-header">
                                            <h3 class="box-title">Co Players</h3>
                                            <!-- <a data-toggle="modal" data-target="#myModal2"  class="btn btn-sm pull-right btn-warning">Add Co-Players <i class="fa fa-plus"></i></a> -->
                                        </div>
                                        <div class="box-body">
                                            <table id="datatable-coplayers" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Users</th>
                                                        <th>Rating</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Users</th>
                                                        <th>Rating</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="myModal3" role="dialog">
    <form action="<?=base_url('users/add_co_player')?>" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Status</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="venue" class="col-sm-2 control-label">User</label>
                        <div class="col-sm-6">
                            <select name="co_players" class="form-control select2 "  data-placeholder="Select a Player" style="width: 100%;">
                            <?php foreach ($co_players as $key => $value) {?>
                                <option value="<?= $value->id?>"><?= $value->name;?></option>
                            <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="user" value="<?= $data->id?>">
                    <input type="hidden" name="sports_id" id="users-edit-sports_id"><br><br>
                    <div class="form-group">
                        <label for="venue" class="col-sm-2 control-label">Rating</label>
                        <div class="col-sm-6">
                            <input type="number" name="rating" id="user-edit-rating" class="form-control"  placeholder=" Rating"  required="required">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
