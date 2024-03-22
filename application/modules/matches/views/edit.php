<div class="content-wrapper">
    <section class="content-header">
        <h1>Matches</h1>
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
                                <li class="active"><a href="#tab_1" data-toggle="tab">Match</a></li>
                                <li><a data-custom-value="<?= $data->id?>" href="#tab_2" id="matches-players" data-toggle="tab">Players</a></li> 
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <form class="form-horizontal" action="<?=base_url("matches/edit/$data->id")?>" method="post">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Date</label>
                                            <div class="col-sm-6">
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input name="date" type="text" class="form-control "  id="datepicker" value="<?=date('m/d/Y',strtotime($data->date))?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Time</label>
                                            <div class="col-sm-6">
                                            Morning:<input id="t1" type="radio" name="time" class="flat-red time" value="Morning" <?php if($data->time=="Morning") echo "checked" ?>>
                                            Afternoon: <input id="t2" type="radio" name="time" class="flat-red time" value="Afternoon" <?php if($data->time=="Afternoon") echo "checked" ?>>
                                            Evening:  <input id="t3" type="radio" name="time" class="flat-red time" value="Evening" <?php if($data->time=="Evening") echo "checked" ?>>
                                            Night: <input id="t4" type="radio" name="time" class="flat-red time" value="Night" <?php if($data->time=="Night") echo "checked" ?>>
                                            <input type="hidden" name="time2"  value="" id="time2">
                                            
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Sports</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="sports" id="sports" style="width: 100%;">
                                                <?php foreach ($sports as $val):?>
                                                    <option <?php if($data->sports_id== $val->id) echo "selected";?> value="<?= $val->id?>"><?= $val->sports?></option>
                                          	    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label class="col-sm-2 control-label">Hosted By</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="host" id="host" style="width: 100%;" required>
                                                    <?php foreach ($players as $val):?>
                                                    <option <?php if($data->user_id== $val->id) echo "selected";?> value="<?= $val->id?>"><?= $val->name?></option>
                                              	    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div> -->

                                        <div class="form-group">
                                            <label for="description" class="col-sm-2 control-label">Hosted By</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="host" id="host" class="form-control"  placeholder="Hosted By " value="<?= $data->name;?>" readonly>
                                            </div>
                                        </div>
    
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">City</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                                <option selected disabled>Select a location</option>
                                                <?php foreach ($location as $val):?>
                                                <option <?php if($val->id==$data->location) echo "selected"; ?> value="<?= $val->id?>"><?= $val->location?></option>
                                          	     <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div> 

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Area</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="area" id="area" style="width: 100%;">
                                                    <?php foreach ($area as $key => $value) {?>
                                                    <option <?php if($value->id==$data->area_id) echo "selected"; ?> value="<?= $value->id?>"><?=$value->area?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>
                                        </div>	  

                                        <div class="form-group">
                                            <label for="no_players" class="col-sm-2 control-label">Number of Players</label>
                                            <div class="col-sm-6">
                                                <input type="number" name="no_players" id="no_players" class="form-control"  placeholder="Number " value="<?= $data->no_players;?>" >
                                            </div>
                                        </div>   
                                        <div class="form-group">
                                            <label for="description" class="col-sm-2 control-label">Description</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="description" id="description" class="form-control"  placeholder="Description " value="<?= $data->description;?>">
                                            </div>
                                        </div>  <!-- </div> -->	

                                        <div class="box-footer">
                                            <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                                            <button type="submit" class="btn btn-warning pull-right">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <!-- <h1 class="text-danger">Work in progress!</h1> -->
                                    <div class="box" id="matchPlayerTable">
                                        <div class="box-header">
                                            <h3 class="box-title">Players</h3>
                                            <!-- <a data-toggle="modal" data-target="#myModal3" class="btn btn-sm pull-right btn-warning">Add Players <i class="fa fa-plus"></i></a> -->  
                                        </div>
                                        <div class="box-body">
                                            <table id="datatable-match-players" class="table table-bordered table-hover" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Users</th>
                                                        <th>Status</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                           
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Users</th>
                                                        <th>Status</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <input id="match-id" name="match_id" type="hidden" value="<?= $data->id?>">

                                    <div class="modal fade" id="myModal2" role="dialog">
                                        <form action="<?=base_url('matches/player_status')?>" method="post">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Status</h4>
                                                    </div>
                                       
                                                    <div class="modal-body">
                                                        <select name="status" class="form-control select2 "  data-placeholder="Select a Player" style="width: 100%;">
                                	                        <?php foreach ($status as $key => $value) {?>
                                	                		    <option value="<?= $value->id?>"><?= $value->status;?></option>
                                	                        <?php  } ?>
                                                        </select>
                                                        <input type="hidden" name="match_players" id="match-players-id">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal fade" id="myModal3" role="dialog">
                                        <form action="<?=base_url('matches/add_player_status')?>" method="post">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Players</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Player</label>
                                                            <div class="col-sm-6">
                                                                <select class="form-control select2" name="players" id="playeres" style="width: 100%;" required>
                                                                    <?php foreach ($users as $val):?>
                                                                        <option  value="<?= $val->id?>"> <?= $val->name?> </option>
                                                                    <?php endforeach;?>
                                                                 </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Status</label>
                                                            <div class="col-sm-6">
                                                                <select name="status" class="form-control select2 "  data-placeholder="Select a Player" style="width: 100%;">
                                                                    <?php foreach ($status as $key => $value) {?>
                                                                        <option value="<?= $value->id?>"><?= $value->status;?></option>
                                                                    <?php  } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="match_id" id="match_id" value="<?= $data->id?>">
                                                    </div>
                                                    <br>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
$(document).ready(function() {
  $('form').submit(function() {
    var time=$('input:radio[name=time]:checked').val();
    var time2;
    if(time=="Morning")
        time2='12:00:00';
    else if(time=="Afternoon")
        time2='16:00:00';
    else if(time=="Evening")
        time2='19:00:00';
    else if(time=="Night")
       time2='23:59:00';
       
    $('#time2').val(time2);   
    
    return true; 
});


});
</script>
