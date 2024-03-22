 
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
	<div class="sub" style="background-color:hotpink;height:100%;color:white;padding-left:1%">
		<img src="https://partnerup.upupup.in/pics/venue/upupup.png" alt="logo" style="height:100px;margin-left:40%"><br>
	Dear  <?=$data['ow_name']  ?>,<br><br>
	<?php if($data['mgr_name']==$data['ow_name']){ ?> You have added <?php }else{ ?> <?=$data['mgr_name']  ?> has added <?php } ?> <?=$data['name']  ?>(<?=$data['phone']  ?>) as <?=$data['role']  ?>  of <?=$data['court_name']  ?>

	<br><br>

	Enjoy your Stay here!! <br><br>
	Cheers, <br>
	Team upUPUP <br>
	Lets Play Again <br><br><br><br><br>
	</div>
</div>
