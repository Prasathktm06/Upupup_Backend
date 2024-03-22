<style type="text/css">

    .add-more {
    margin-top: -55px;
    margin-left: 589px;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Court</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit</h3>
                    </div>
                <?php // print_r(array_reverse($court_time));exit; ?>
                    <div class="box-body">
                        <form class="form-horizontal" action="<?=base_url("court/edit/$court->id/")?><?=$court->venue_id?>" method="post">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="court" class="col-sm-2 control-label">Court</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="court" id="court" class="form-control"  placeholder="Location Name" value="<?= $court->court?>" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="court" class="col-sm-2 control-label">Cost</label>
                                    <div class="col-sm-6">
                                        <input type="number" name="cost" class="form-control"  placeholder="Cost" value="<?= $court->cost ?>" required="required" min="10">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Sports</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" name="sports" id="sports" style="width: 100%;" required>
                                            <?php foreach ($sports as $val):?>
                                                <option <?php if($court->sports_id==$val->id) echo "selected"?>  value="<?= $val->id?>" ><?= $val->sports?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="court" class="col-sm-2 control-label">Interval</label>
                                    <div class="col-sm-6">
                                        <input type="number" name="intervel" class="form-control"  placeholder="Cost" value="<?= $court->intervel ?>" required="required" min="5" readonly>
                                    </div>Mins<!--<p class="text-danger">Add Intervel in minutues</p>-->
                                </div>
                                
                                <div class="form-group">
                                    <label for="court" class="col-sm-2 control-label">Capacity</label>
                                    <div class="col-sm-6">
                                        <input type="number" name="capacity" class="form-control" placeholder="Capacity" value="<?= $court->capacity ?>" required="required" min="1">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status </label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                            <option <?php if($court->status==1) echo "selected"; ?> value="1">Active</option>
                                            <option  <?php if($court->status==0) echo "selected"; ?>  value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
 </div>

                            <div class="box-footer">
                                <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-warning pull-right">Submit</button>
                            </div>
                        </form>
                                
                                    <fieldset>
                                        <legend >Monday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                    <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Monday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                    <table class ="datatable-mon" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>slotfor</th>
                                        <th>time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courtmon as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                           <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                        <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                        <th>slotfor</th>
                                        <th>time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>

                                    <fieldset>
                                        <legend >Tuesday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                    <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Tuesday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                   <table class ="datatable-tue" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                         <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courttue as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                        <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>

                                   <fieldset>
                                        <legend >Wednesday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                       <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Wednesday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                     <table class ="datatable-wed" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courtwed as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                        <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                         <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>

                                    <fieldset>
                                        <legend >Thursday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                       <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Thursday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                    <table class ="datatable-thu" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                         <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courtthu as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                        <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>

                                   <fieldset>
                                        <legend >Friday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                       <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Friday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                    <table class ="datatable-fri" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                         <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courtfri as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                       <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>
                                      <fieldset>
                                        <legend >Saturday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                      <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Saturday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                     <table class ="datatable-sat" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courtsat as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                        <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>

                                      <fieldset>
                                        <legend >Sunday:</legend>
                                        <div class="box-body well col-sm-11 div-add-more" id="" style="margin-left: 3%">
                                         <div class="row">
                                         
                                     
                                       <form class="form-horizontal" action="<?=base_url("court/edit_time/$court->id/")?><?=$court->venue_id?>" method="post">
                                                                
                                    <div class="form-group">
                                    <input type="hidden" name="week" value="Sunday">
                                    <label class="col-sm-2 control-label">Slot Categorey :</label>
                                        <div class="col-sm-4">                                  
                                            <select class="form-control select2" name="slotfor" id="slotfor" style="width: 100%;" required>
                                             <option  value="0">upUPUP Time</option>
                                             <option  value="1">Venue Time</option>
                                             <option  value="2">Member Time</option>
                                            </select>
                                        </div>
                                    <label class="col-sm-1 control-label">Time</label>
                                    <div class="col-sm-3">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                        <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                        </div>
                                                        <input  type="text" class="form-control timepicker" name="slottime" style="width: 117px" >
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning ">ADD</button>
                                    </div>
                                    </form>
           
                                    </div>
                                    </div>
                                    </fieldset>
                                     <table class ="datatable-sun" class="table table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                       <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($courtsun as $key => $value) {?>
                                        <tr>
                                        <td><?=++$key?></td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->slotfor==0){$slotfor="upUPUP Time";}else{if($value->slotfor==1){$slotfor="Venue Time"; }else{$slotfor="Member Time";}}?><?= $slotfor?></td>
                                        <td><?= date("g:i a", strtotime($value->time))?> </td>
                                        <td style="padding: 20px;padding-left: 0px"><?php
                                        if($value->date==1){$upup="";}else{$upup=$value->date;}?><?= $upup?></td>
                                        <td><a href="<?=base_url()?>court/slot_delete/<?=$value->slot_id?>/<?=$court->venue_id?>/<?=$court->id?>" class=" btn-small delete" title="Delete"><i class="fa fa-trash"></i> </a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>#</th>
                                        <th>Slotfor</th>
                                        <th>Time</th>
                                        <th>Single day upupup slot</th>
                                        <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    </table><br>
                                   
                           
                                                            
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
   var x=1;
   $(document).ready(function() {

   $('.add-more').click(function(e){ //on add input button click
          e.preventDefault();
          console.log($(this).parent().parent());
          var parent= $(this).parent().parent();
          var div_id=$(this).parent().parent().attr('id');
          console.log(div_id);
          if(x < 30){ //max input box allowed
              //text box increment
               var div='<div><div class="form-group"><label class="col-sm-2 control-label">Slot Categorey</label><div class="col-sm-6"><select class="form-control select2" name="week['+div_id+'][slotfor][]" id="slotfor" style="width: 100%;" required><option  value="0">upUPUP Time</option><option  value="1">Venue Time</option><option  value="2">Member Time</option></select></div></div><div class="col-md-6"> <div class="bootstrap-timepicker">  <div class="form-group"><label class="col-sm-2 control-label">Start</label><div class="col-sm-4"> <div class="input-group "> <div class="input-group-addon"> <i class="fa fa-clock-o"></i>  </div><input value="" type="text" class="form-control timepicker_start" name="week['+div_id+'][start][]" style="width: 117px"></div></div> </div></div></div><button type="button" id="" class="btn btn-default btn-sm fa fa-minus minus-button " style="margin-left:159px;margin-top:-58px"></button>  </div> </div></div>';
                     parent.append(div); //add input box
                     x++;

          }
         $(".timepicker_start").timepicker({
         showInputs: false,
         defaultTime:'<?=$venue->morning?>',
         maxHours:20
       });
         $(".timepicker_end").timepicker({
         showInputs: false,
         defaultTime:'<?=$venue->evening?>',
         maxHours:20
       });

   });

   $(document).on('click', '.minus-button', function(e) {
    x=x-1;
    console.log($(this).parent().parent().parent().parent().parent().remove());

   });
   });
   $( window ).on( "load", function() {
       $(".timepicker_start").timepicker({
         showInputs: false,
         defaultTime:'<?=$venue->morning?>',
         maxHours:20
       });
           $(".timepicker_end").timepicker({
         showInputs: false,
         defaultTime:'<?=$venue->evening?>',
         maxHours:20
       });
     });
     $('.timepicker_end').on('change',function(){
      //console.log($("input[name='week[Monday][start]']"));
      var start_time = $(this).parent().parent().parent().parent().parent().parent().closest('div').find('.timepicker_start');
      var end_time =$(this);
    if(start_time.val()==end_time.val()){
     //$(this).parent().parent().parent().parent().parent().find('.error').append('Same');
    }else{
    // $(this).parent().parent().parent().parent().parent().find('.error').html('');
    }

     });

</script>
<script type="text/javascript">

$(document).ready(function() {

$(".datatable-mon").DataTable( {dateFormat: 'yyyy-mm-dd'});
$(".datatable-tue").DataTable( {dateFormat: 'yyyy-mm-dd'});
$(".datatable-wed").DataTable( {dateFormat: 'yyyy-mm-dd'});
$(".datatable-thu").DataTable( {dateFormat: 'yyyy-mm-dd'});
$(".datatable-fri").DataTable( {dateFormat: 'yyyy-mm-dd'});
$(".datatable-sat").DataTable( {dateFormat: 'yyyy-mm-dd'});
$(".datatable-sun").DataTable( {dateFormat: 'yyyy-mm-dd'});

});


</script>
