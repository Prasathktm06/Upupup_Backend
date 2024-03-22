 
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
	Hi upUPUP,<br><br>
	<?=$data['assigned_mgr_name']  ?> (<?=$data['assigned_mgr_phone']  ?>) has re-assigned the following venues to <?=$data['mgr_role']  ?> ( <?=$data['mgr_name']  ?> / <?=$data['mgr_phone']  ?> ) <br>
	venues : <?=$data['venue_names']  ?>

	<br><br>

	Cheers, <br>
	Team upUPUP <br>
	Lets Play Again <br><br><br><br><br>
	</div>
</div>
