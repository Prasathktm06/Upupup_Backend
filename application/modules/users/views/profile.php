<div class="content-wrapper">
    <section class="content-header">
        <h1>User</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Profile</h3>
                    </div>

                    <div class="box-body">
                        <div class="col-md-3">
                            <img src="<?=$user_data->image?>" class="img-circle" alt="" width="236" height="236">
                        </div>
                        <div class="col-md-9">
                            <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("users/add/")?>" method="post" >
                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">User Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="user" id="user" class="form-control"  placeholder="User Name" value="<?=$user_data->name?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="user" id="user" class="form-control"  placeholder=" Address" value="<?=$user_data->address?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">Phone No</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="user" id="user" class="form-control"  placeholder=" Phone No" value="<?=$user_data->phone_no?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">Email Id</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="email" id="email" class="form-control"  placeholder="Email Id" value="<?=$user_data->email?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">City</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="user" id="user" class="form-control"  placeholder=" City" value="<?=$city?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">Areas</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="user" id="user" class="form-control"  placeholder=" Areas" value="<?=$area_data?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">Sports</label>
                                    <div class="col-sm-8">
                                    <textarea class="form-control"  placeholder=" Sports" readonly><?=$sports_data?></textarea>
                                        <!-- <input type="text" name="user" id="user" class="form-control"  placeholder=" Sports" value="<?=$sports_data?>" readonly> -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="venue" class="col-sm-2 control-label">User Channel</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="user_channel" id="user_channel" class="form-control"  placeholder="User Channel" value="<?php if($user_channel->channel_id==2){ ?>Vendor App <?php }else{ ?> User App <?php } ?>" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">My Account</h3>
                    </div>

                    <div class="box-body">
                        
                        <div class="col-md-12">
                            <?php if(empty($my_account)){?>
                            <h6>user has no UPcoin account</h6>
                            <?php }else{?>
                                    <div class="col-md-6">
                                        <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("users/add/")?>" method="post" >
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Purchased UPcoins Balance</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="UPcoin" value="<?=$my_account->up_coin?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Bonus UPcoin Balance</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Bonus Coin" value="<?=$my_account->bonus_coin?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Upcoin Balance</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Total Coins" value="<?=$my_account->total?>" readonly>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-6">
                                        <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("users/add/")?>" method="post" >
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Purchased Upcoins</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Purchased coins" value="<?=$buy_coin->purchased_coin?>" readonly>
                                                </div>
                                            </div>
                                        <?php if(empty($install_bonus->install_bonus)){?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Installation Bonus</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Installation Bonus" value="0" readonly>
                                                </div>
                                            </div>
                                        <?php }else{ ?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Installation Bonus</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Installation Bonus" value="<?=$install_bonus->install_bonus?>" readonly>
                                                </div>
                                            </div>

                                        <?php } ?>
                                            
                                        <?php if(empty($booking_bonus->booking_bonus) && empty($ref_bk_bonus->refbk_bonus)){?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Booking Bonus</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Bonus Coins" value="0" readonly>
                                                </div>
                                            </div>
                                        <?php }elseif(empty($ref_bk_bonus->refbk_bonus)){ ?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Booking Bonus</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Bonus Coins" value="<?=$booking_bonus->booking_bonus?>" readonly>
                                                </div>
                                            </div>

                                        <?php }elseif(empty($booking_bonus->booking_bonus)){ ?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Booking Bonus</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Bonus Coins" value="<?=$ref_bk_bonus->refbk_bonus?>" readonly>
                                                </div>
                                            </div>
                                        <?php }else{?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Booking Bonus</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Bonus Coins" value="<?=$booking_bonus->booking_bonus+$ref_bk_bonus->refbk_bonus?>" readonly>
                                                </div>
                                            </div>
                                        <?php } ?>
                                           
                                        <?php if(empty($refund->refund_coin)){?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Refund UPcoins</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Refund Coins " value=0 readonly>
                                                </div>
                                            </div>
                                        <?php }else{ ?>
                                            <div class="form-group">
                                                <label for="venue" class="col-sm-3 control-label">Total Refund UPcoins</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="user" id="user" class="form-control"  placeholder="Refund Coins " value="<?=$refund->refund_coin?>" readonly>
                                                </div>
                                            </div>

                                        <?php } ?>

                                           
                                        </form>
                                    </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Booking Refund</h3>
                    </div>

                    <div class="box-body">
                        <form class="form-horizontal" action="<?=base_url("users/profile/$user_data->id")?>" method="post">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="speciality" class="col-sm-2 control-label">Booking Id</label>

                                    <div class="col-sm-6">
                                    <input type="text" name="booking_id" id="booking_id" class="form-control"  placeholder="Type Booking Id" value="" required="required">
                                    </div>
                                </div>

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
        <?php  if(!empty($booking)){  ?>

            <?php foreach ($booking as $key => $value) { ?>
                    <section class="">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                   

                                    <div class="box-body">
                                        <div class="col-md-3">
                                            
                                        </div>
                                        <div class="col-md-9">
                                            <form enctype='multipart/form-data' class="form-horizontal" action="<?=base_url("users/add/")?>" method="post" >
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Venue Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Venue Name" value="<?=$value['venue']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Court Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Court Name" value="<?=$value['court']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Venue Area</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Venue Area" value="<?=$value['area']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Sports Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Sports" value="<?=$value['sports']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Booking Date</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Booking Date" value="<?=date( ' d M Y ',strtotime($value['date']))?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Payment Id</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Payment Id" value="<?=$value['payment_id']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Paid Amount</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Paid Amount" value="<?=$value['cost']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Offer Amount</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Offer Amount" value="<?=$value['offer_value']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Total Slot Price</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Total Slot Price" value="<?=$value['price']?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="venue" class="col-sm-2 control-label">Slot Time</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="user" id="user" class="form-control"  placeholder="Slot Time" value="<?=date( ' h:i:s A ',strtotime($value['court_time']))?>" readonly>
                                                    </div>
                                                </div>
                                                
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Refund Amount</h3>
                                    </div>
                                    <div class="box-body">
                                        <form class="form-horizontal" action="<?=base_url("users/refund/$user_data->id")?>" method="post">
                                            <input type="hidden" name="booking_id" id="booking_id" value="<?=$value['booking_id']?>">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="speciality" class="col-sm-2 control-label">Refund Amount</label>

                                                    <div class="col-sm-6">
                                                    <input type="text" name="refund_amount" id="refund_amount" class="form-control"  placeholder="Refund Amount" value="" required="required">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="desc" class="col-sm-2 control-label">Reason</label>
                                                    <div class="col-sm-6">
                                                    <textarea id="reason" name="reason" class="form-control" rows="3" placeholder="Reason for refund....[include booking id]" maxlength="1000"></textarea>
                                                    </div>
                                                </div>

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
            <?php } ?>

    <?php  } ?>                                
    
</div>
