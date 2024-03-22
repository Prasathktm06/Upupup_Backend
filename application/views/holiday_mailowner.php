 
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
	<div class="sub" style="background-color:hotpink;height:300px;color:white;padding-left:1%">
		<img src="https://partnerup.upupup.in/pics/venue/upupup.png" alt="logo" style="height:100px;margin-left:40%"><br>
	Dear <?=$data['name']  ?>,<br><br>
	<?php if($data['name']==$data['uname']){ ?> You have added holiday on <?php }else{ ?> <?=$data['role']  ?>  <?=$data['phone']  ?> has added holiday on <?php } ?> <?=$data['date']  ?> at <?=$data['venue_name']  ?>, <?=$data['area']  ?>  <br><br>

	Reason : <?=$data['description']  ?> <br><br>

	Cheers, <br><br>
	Team upUPUP <br>
	Lets Play Again <br>
	</div>
</div>
