 
<style type="text/css">
	.main{
		height: 54%;
    background-color: pink;
    width: 50%;
    margin-left: 23%;
    padding: 2%;
	}
</style>
<div class="main" style="padding:8%;border-style:double;">
	<div class="sub" style="background-color:hotpink;height:450px;color:white;padding-left:1%">
		<img src="https://partnerup.upupup.in/pics/venue/upupup.png" alt="logo" style="height:100px;margin-left:40%"><br>
	Hi upUPUP,<br><br>
	<?=$data['role']  ?> <?=$data['vowner']  ?> (<?=$data['phone']  ?>) of <?=$data['venue_name']  ?> has added offer <br>
    Offer Name : <?=$data['offer_name'] ?> <br>
	Valid from <?=$data['start_date'] ?> TO <?=$data['end_date'] ?><br>
	Time : <?=$data['start_time'] ?> TO <?=$data['end_time'] ?> <br><br>
    <?php if($data['sunday']==1){ ?> Sunday, <?php } ?><?php if($data['monday']==1){ ?> Monday, <?php } ?><?php if($data['tuesday']==1){ ?> Tuesday, <?php } ?><?php if($data['wednesday']==1){ ?> Wednesday, <?php } ?><?php if($data['thursday']==1){ ?> Thursday, <?php } ?><?php if($data['friday']==1){ ?> Friday, <?php } ?><?php if($data['saturday']==1){ ?> Saturday, <?php } ?><br>
	<?=$data['court_name']  ?> <br>
	<?php if($data['offer_type']=="rupees"){ ?> Offer Amount : Rs  <?=$data['offer_value'] ?> /- <?php }else{ ?> Offer Percentage : <?=$data['offer_value'] ?>% <?php } ?><br>
	<br><br>
	</div>
</div>