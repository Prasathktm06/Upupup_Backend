 
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
	<div class="sub" style="background-color:hotpink;height:390px;color:white;padding-left:1%">
		<img src="https://partnerup.upupup.in/pics/venue/upupup.png" alt="logo" style="height:100px;margin-left:40%"><br>
	Hi <?=$data['owner_name']  ?>,<br><br>
	<?=$data['role_name']  ?> <?=$data['mgr_name']  ?> [ <?=$data['mgr_phone']  ?> ] of <?=$data['venue_name']  ?>  has unblocked the below slots for<?=$data['day_name'] ?> (<?= date('dS F  Y', strtotime($data['date']))?>)<br>

    Sports     : <?=$data['sports']  ?> <br>
    Court Name : <?=$data['court']  ?> <br>
    Slot time  : <?=date("g:i a", strtotime($data['open_time']))?> <br><br>
    Cheers, <br><br>
    Team upUPUP <br><br>

	
	</div>
</div>