<style type="text/css">
	.main{
		height: 65%;
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
	<?=$data['role']  ?>( <?=$data['mgr_phone']  ?>)  of <?=$data['venue_name']  ?> , <?=$data['area']  ?> has cancelled the booking of <?=$data['booking_date']  ?> ,<?=$data['booking_time']  ?> ,<?=$data['booking_day'] ?>.[Booking ID:<?=$data['booking_id'] ?>] for <?=$data['sports']  ?> in <?=$data['court']  ?><br><br>

	Player Name   : <?=$data['user_name']  ?> <br>
	Player Number : <?=$data['user_phone']  ?> <br><br>

	Cheers,<br><br>
	Team upUPUP <br><br>
	Lets Play Again<br><br>

	<br><br>
	</div>
</div>