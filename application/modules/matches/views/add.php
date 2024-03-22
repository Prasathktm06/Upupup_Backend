<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
Matches

</h1>


</section>
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">
<div class="box-header">
<h3 class="box-title">Add</h3>
</div>

<div class="box-body">
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Match</a></li>
              
              </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              
<form class="form-horizontal" action="<?=base_url("matches/add/")?>" method="post">
<div class="box-body">

<!-- <div class="form-group">
<label for="name" class="col-sm-2 control-label">Match Name</label>
<div class="col-sm-6">
<input type="text" name="name" id="name" class="form-control"  placeholder="Name " value="" required="required">
</div>
</div> -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Date:</label>
                <div class="col-sm-6">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input name="date"  type="text" class="form-control pull-right" id="datepicker">
                    </div>
                </div>
            </div> 
            <!-- <div class="form-group">
                <div class="input-group date" id="datepicker">
                    <input type="text" class="form-control" />  <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
                </div>
            </div> -->
              <div class="form-group">
<label for="name" class="col-sm-2 control-label">Time</label>
<div class="col-sm-6">
Morning:<input type="radio" name="time" class="flat-red" value="morn">
Afternoon: <input type="radio" name="time" class="flat-red" value="after">
Evening:  <input type="radio" name="time" class="flat-red" value="even">
Night:  <input type="radio" name="time" class="flat-red" value="night">
</div>
</div>

 <div class="form-group">
     <label class="col-sm-2 control-label">Sports</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="sports" id="sports" style="width: 100%;">
          <?php foreach ($sports as $val):?>
         <option value="<?= $val->id?>"><?= $val->sports?></option>
        <?php endforeach;?>
      </select>
     </div>
    </div>
     <div class="form-group">
     <label class="col-sm-2 control-label">Hosted By</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="host" id="host" style="width: 100%;">
          <?php foreach ($users as $val):?>
         <option value="<?= $val->id?>"><?= $val->name?></option>
        <?php endforeach;?>
      </select>
     </div>
    </div>
    
<div class="form-group">
     <label class="col-sm-2 control-label">City</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="location" id="location" style="width: 100%;">
      <option selected disabled>Select a Location</option>
          <?php foreach ($location as $val):?>
         <option value="<?= $val->id?>"><?= $val->location?></option>
        <?php endforeach;?>
      </select>
     </div>
    </div>
 <div class="form-group">
     <label class="col-sm-2 control-label">Area</label>
     <div class="col-sm-6">
      <select class="form-control select2" name="area" id="area" style="width: 100%;">
         <option value=""></option>
      </select>
     </div>
    </div>      
   <div class="form-group">
<label for="no_players" class="col-sm-2 control-label">Number of Players</label>
<div class="col-sm-6">
<input type="number" name="no_players" id="no_players" class="form-control"  placeholder="Number " value="" >
</div>
</div>   
<div class="form-group">
<label for="description" class="col-sm-2 control-label">Description</label>
<div class="col-sm-6">
<input type="text" name="description" id="description" class="form-control"  placeholder="Description " value="" >
</div>
</div>  
 
</div>  

<div class="box-footer">
<a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
<a href="#tab_2" id="tab2" class="btn btn-warning pull-right" data-toggle="tab">Players</a>
</div>

</div>
           <div class="tab-pane" id="tab_2">
           

<div class="box-body">
 <div class="form-group">
                <label class="col-sm-2 control-label">Players</label>
                <div class="col-sm-6">
                <select name="players[]" class="form-control select2" multiple="multiple" data-placeholder="Select a Player" style="width: 100%;">
                  <?php foreach ($users as $val):?>
                  <option value="<?= $val->id?>"><?=$val->name?></option>
                  <?php endforeach;?>
                </select>
                </div>
              </div>
</div>
<div class="box-footer">
<a href="#tab_1"  id="tab2" data-toggle="tab" class="btn btn-default">Matches</a>
<button type="submit" class="btn btn-warning pull-right">Submit</button>
</div>
</form>
</div>
</div>
</div>

</div>
</div>
</div>
</div>

</section>

</div>
<!-- <script type="text/javascript">
 /* $(function () {
            $('#datepicker').datepicker({
                minDate : moment()
            });
            $('#datepicker').datepicker({
              autoclose: true
            });
        });*/
        $('#datepicker').datepicker({
            var date = new Date();
        var currentMonth = date.getMonth(); // current month
        var currentDate = date.getDate(); // current date
        var currentYear = date.getFullYear(); //this year
               minDate: new Date(currentYear, currentMonth, currentDate)
            });
        

</script> -->


