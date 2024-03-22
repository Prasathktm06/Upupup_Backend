 
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
	Dear  <?=$data['owner_name']  ?>,<br><br>
	<?php if($data['owner_name']==$data['assigned_mgr_name']){ ?> You have reassigned the following venues to <?php }else{ ?> <?=$data['assigned_mgr_name']  ?>( <?=$data['assigned_mgr_phone'] ?> ) reassigned the following venues to <?php } ?> <?=$data['mgr_role']  ?> ( <?=$data['mgr_name']  ?> / <?=$data['mgr_phone']  ?> )  <br>
	venues : <?=$data['venue_names']  ?>

	<br><br>

	Cheers, <br>
	Team upUPUP <br>
	Lets Play Again <br><br><br><br><br>
	</div>
</div>