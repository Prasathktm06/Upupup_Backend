<style>
.button {
  border-radius: 4px;
  background-color: #999;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 15px;
  padding: 4px;
  width: 111px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
  margin-left: 40%;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 25px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}
</style>
<div class="content-wrapper" style="background-color: #405159">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 style="color: white">
        Dashboard

      </h1>


    </section>


    <section class="content">
          <div class="row">
              <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $app_users->count;?></h3>

              <p>App Users</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?= base_url('users');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

                <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $venue->count?></h3>

              <p>Venues</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?= base_url('venue')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $matches->count;?></h3>

              <p>Matches</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="<?= base_url('matches')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
           <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $booking->count;?></h3>

              <p>Bookings</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?= base_url('booking')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
           <div class="col-lg-12 col-xs-12">
         <div class="box box-success">
            <div class="box-header">
              <i class="fa fa-comments-o"></i>

              <h3 class="box-title">Feedback</h3>

              <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                <div class="btn-group" data-toggle="btn-toggle">

                </div>
              </div>
            </div>
            <div class="box-body chat" id="chat-box" style="background-color: #6a7c84; color: white">
              <?php if(!empty($feedback)){ ?>
              <?php foreach($feedback as $key => $value){?>
              <div class="item">
                 
                <img src="<?php if($value->image!='') echo $value->image; else echo base_url('assets/img/user_default.png');?>" alt="user image" class="online">
          <p class="message" style="word-wrap:break-word">
              
                    <small class="text-muted pull-right" style="color: white;"><i class="fa fa-clock-o"></i><?= date( ' d M Y h:i:s A',strtotime($value->time))?></small>
                    <a href='<?= base_url("users/profile/$value->id")?>' class="name" style="color:#FFC107;"><?= $value->name;?>
                  </a>
                  <?= $value->feedback;?>
                </p>

                <!-- /.attachment -->
              </div>
              <?php }?>
                <button class="button" onclick="location.href='<?=base_url()?>feedback';"><span> View </span></button>
            <?php }else{ ?>
                <span>No Feedback </span>
            <?php } ?>




               <!-- <div class="box-footer" style="background-color: #6a7c84">
               <form action=" //echo base_url('acl/feedback')" method="post">
              <div class="input-group">
                <input class="form-control" placeholder="Type message...">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i></button>
                </div>
              </div>
              </form>
            </div> -->
              <!-- /.item -->
              <!-- chat item -->

              <!-- /.item -->
              <!-- chat item -->

              <!-- /.item -->

            </div>
            <!-- /.chat -->

          </div>
          </div>
          <!-- /.box (chat box) -->

   </section>
 </div>
