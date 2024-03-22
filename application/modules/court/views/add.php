<style type="text/css">
   .add-more {
   margin-top: -55px;
   margin-left: 589px;
   }

</style>
<div class="content-wrapper">
   <section class="content-header">
      <h1>Court </h1>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-xs-12">
            <div class="box">
               <div class="box-header">
                  <h3 class="box-title">Add</h3>
               </div>
               <div class="box-body">
                  <form class="form-horizontal" action="" method="post" >
                     <div class="box-body">
                        <div class="form-group">
                           <label for="court" class="col-sm-2 control-label">Court</label>
                           <div class="col-sm-6">
                              <input type="text" name="court" id="court" class="form-control" placeholder="Name" value="" required="required">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="court" class="col-sm-2 control-label">Cost</label>
                           <div class="col-sm-6">
                              <input type="number" name="cost" class="form-control" placeholder="Cost" value="" required="required" min="10">
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-2 control-label">Sports</label>
                           <div class="col-sm-6">
                              <select class="form-control select2" name="sports" id="sports" style="width: 100%;" required>
                                 <?php foreach ($sports as $val):?>
                                 <option value="<?=$val->id?>" ><?= $val->sports?></option>
                                 <?php endforeach;?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="court" class="col-sm-2 control-label">Intervel</label>
                           <div class="col-sm-6">
                              <input type="number" name="intervel" class="form-control" placeholder="Intervel" value="" required="required" min="5">
                              
                           </div>Mins
                        </div>
                        <div class="form-group">
                           <label for="court" class="col-sm-2 control-label">Capacity</label>
                           <div class="col-sm-6">
                              <input type="number" name="capacity" class="form-control" placeholder="Capacity" value="1" required="required" min="1">
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-2 control-label">Status </label>
                           <div class="col-sm-6">
                              <select class="form-control select2" name="status" id="status" style="width: 100%;" required>
                                 <option  value="0">Inactive</option>
                                 <option  value="1">Active</option>
                              </select>
                           </div>
                        </div>
                       
                        <div style="" class="">
                           <?php $weeks=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
                              foreach ($weeks as $key => $value) {  $i=0; ?>
                           <fieldset>
                              <legend ><?= $value?>:</legend>
                              <div class="box-body well col-sm-8 div-add-more" id="<?=$value?>" style="margin-left: 10%">
 
                                 <div class="row">
                                    <h3 style="color: #795548" class=" text-center">Sessions</h3>
                                    <button type="button" id="" class="btn btn-danger btn-sm fa fa-plus add-more" ></button>
                                    
                                    <div id="monday">
<div class="form-group">
                           <label class="col-sm-2 control-label">Slot Categorey</label>
                           <div class="col-sm-6">
                              <select class="form-control select2" name="week[<?=$value?>][slotfor][]]" id="slotfor" style="width: 100%;" required>
                                 <option  value="0">upUPUP Time</option>
                                 <option  value="1">Venue Time</option>
                                 <option  value="2">Member Time</option>
                              </select>
                           </div>
                        </div>
                                       <div class="col-md-6">
                                          <div class="bootstrap-timepicker">
                                             <div class="form-group">
                                                <label class="col-sm-2 control-label">Start</label>
                                                <div class="col-sm-4">
                                                   <div class="input-group ">
                                                      <div class="input-group-addon">   <i class="fa fa-clock-o"></i>
                                                      </div>
                                                      <input  type="text" class="form-control timepicker_start" name="week[<?=$value?>][start][]]" style="width: 117px">

                                                   </div>

                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="bootstrap-timepicker">
                                             <div class="form-group">
                                                <label class="col-sm-1">End</label>
                                                <div class="col-sm-4">
                                                   <div class="input-group ">
                                                      <div class="input-group-addon">
                                                         <i class="fa fa-clock-o"></i>
                                                      </div>
                                                      <input value="" type="text" class="form-control timepicker_end" name="week[<?=$value?>][end][]]" style="width: 117px">
                                                   </div>

                                                   <button type="button" id="" class="btn btn-default btn-sm fa fa-minus minus-button " style="margin-left:159px;margin-top:-58px"></button>
                                                  <div class="error text-warning"></div><br>
                                                </div>

                                             </div>
                                          </div>

                                       </div>

                                    </div>
                                 </div>
                              </div>
                           </fieldset>
                           <?php } ?>
                        </div>
                        <!-- <button type="button" id="add-more" class="btn btn-danger btn-sm fa fa-plus" style="margin-left:700px;"></button> -->
                     </div>
                     <div class="box-footer">
                        <a href="javascript:window.history.go(-1)" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-warning pull-right">Submit</button>
                     </div>
                  </form>
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
          if(x < 100){ //max input box allowed
              //text box increment
               var div='<div><div class="form-group"><label class="col-sm-2 control-label">Slot Categorey</label><div class="col-sm-6"><select class="form-control select2" name="week['+div_id+'][slotfor][]" id="slotfor" style="width: 100%;" required><option  value="0">upUPUP Time</option><option  value="1">Venue Time</option><option  value="2">Member Time</option></select></div></div><div class="col-md-6"> <div class="bootstrap-timepicker">  <div class="form-group"><label class="col-sm-2 control-label">Start</label><div class="col-sm-4"> <div class="input-group "> <div class="input-group-addon"> <i class="fa fa-clock-o"></i>  </div><input value="" type="text" class="form-control timepicker_start" name="week['+div_id+'][start][]" style="width: 117px"></div></div> </div></div></div><div class="col-md-6"><div class="bootstrap-timepicker"><div class="form-group"><label class="col-sm-1">End</label> <div class="col-sm-4"> <div class="input-group "> <div class="input-group-addon">  <i class="fa fa-clock-o"></i> </div>  <input value="" type="text" class="form-control timepicker_end" name="week['+div_id+'][end][]" style="width: 117px">  </div></div</div><button type="button" id="" class="btn btn-default btn-sm fa fa-minus minus-button " style="margin-left:159px;margin-top:-58px"></button>  </div> </div></div>';
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
<!--
   <div class="bootstrap-timepicker"><div class="form-group"><label class="col-sm-2 control-label">Start</label><div class="col-sm-4 "> <div class="input-group ">  <div class="input-group-addon"><i class="fa fa-clock-o"></i></div> <input value="" type="text" class="form-control timepicker" name="week['+div_id+'][start][]"> </div> </div> <label class="col-sm-1 ">End</label><div class="col-sm-4 "> <div class="input-group ">  <div class="input-group-addon"><i class="fa fa-clock-o"></i></div> <input value="" type="text" class="form-control timepicker" name="week['+div_id+'][end][]"> </div> </div>  </div></div> -->
