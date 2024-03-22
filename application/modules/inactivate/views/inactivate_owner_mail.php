 
<style type="text/css">
	.main{
		height: 100%;
    background-color: pink;
    width: 50%;
    margin-left: 23%;
    padding: 2%;
	}
</style>
<div class="main" style="padding:8%;border-style:double;">
	<div class="sub" style="background-color:hotpink;height:400px;color:white;padding-left:1%">
		<img src="https://partnerup.upupup.in/pics/venue/upupup.png" alt="logo" style="height:100px;margin-left:40%"><br>
	Dear <?=$data['owner_name']  ?>,<br><br>
	Following courts of <?=$data['venue_name']  ?> has  been inactivated by upUPUP <br>
    
    Courts : <?=$data['court_name']  ?> <br>

    Sports : <?=$data['sport_name']  ?> <br>

	Valid from <?=$data['start_date'] ?>:<?=$data['start_time'] ?> TO <?=$data['end_date'] ?>:<?=$data['end_time'] ?> <br><br>
	Reason  : <?=$data['reason']  ?> 
	<br><br>
	Cheers, <br><br>
	Team UPupup <br>
	Lets Play Again <br><br>
	</div>
</div>