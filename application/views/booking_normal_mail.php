<style type="text/css">

.col{
	padding-top: 6%;
	padding-left: 40%
}
#venue_image{
	width: 10px;
    height: 10px;
    margin-bottom: 4px;
}
.col2{
	padding-top: 2%;
}
.congrats{
	padding-left: 5%;
}
#wt{
	position: absolute;  margin-top: -10%;margin-left: 27%;
}


</style>
<link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
<div class="row" style="border-style: solid;border-color: black;border-width: 5px;margin: 10%;margin-top: 0;font-family: 'Copperplate / Copperplate Gothic Light, sans-serif';" >
	<div class="col-md-12 text-center">
		<center>
		<img src="<?=base_url('pics/venue/upupup.png')?>" style="width: 120px;">
</center>
	</div>
	<div class="col-md-12 text-center">
		<center>
		<p style=""> Congrats..! <?=$data['user']['name']?> (Mob: <?=$data['user']['phone_no']?>)<?php if($data['co_players']!='') echo ','.$data['co_players']; ?> .</p>
    <h3 style="">New booking is confirmed!</h3>

		<p style=""><?=date('dS F Y', strtotime($data['booking']->date))?> | Booking ID : [<?=$data['booking']->booking_id?>]</p>
</center>
	</div>
	<div class="col-md-12 " style="margin-bottom: 25px;">
		<div class="col-md-4">
			<img style=" width: 194px; height: 72px;margin-left:6%;" id="venue_image" src="<?=$data['booking']->image?>" height="50" width="50" >
			<?php if($data['share_location']==1){?>
			<a href="<?=$data['map_link']?>"><img src="<?=base_url('pics/venue/location_icon.png')?>" style="width: 40px;"></a>
			<?php } ?>
		</div>
		<div class="col-md-8 text-center">
		    <p style="text-align: center;"><?=date('dS F Y', strtotime($data['booking']->date))?> | <?=date('l',strtotime($data['booking']->date))?> |<?=$data['end_time']?> <br>
							<?php if($data['no_players']!=""){ ?>| <?=$data['no_players']?> <?php } ?>| <?=$data['booking']->sports?> | <?=$data['booking']->court?><br><?=$data['booking']->venue?>
							<?=$data['booking']->area?> </p>
			
		</div>
	
	</div>
	

	
	<div class="col-md-10" style="position: absolute;  margin-top: -10%;margin-left: 27%;" id="wt">
<p></p>
		</div>


	

	<div class="col-md-12" style="margin-bottom: 25px;">
		<div class="col-md-12" style="padding:2%">
			<table style="width: 100%;background-color: pink;" >
				<tr style="border-style:dotted">
					<td>1</td>
					<td>Total Amount</td>
					<td>:</td>
					<td></td>
					<td> <?=$data['booking']->price?>.00</td>
				</tr>
				<tr style="border-style:dotted">
					<td>2</td>
					<td>Coupon/Offer/ Hot Offer</td>
					<td>:</td>
					<td></td>
					<td> <?=$data['booking']->price - $data['booking']->cost + $data['service_charge'] ?>.00</td>
				</tr>
				<tr style="border-style:dotted">
					<td>3</td>
					<td>Sevice Charge</td>
					<td>:</td>
					<td></td>
					<td> <?=$data['service_charge'] ?>.00</td>
				</tr>
				<tr style="border-style:dotted">
					<td>4</td>
					<td>Payable Amount</td>
					<td>:</td>
					<td></td>
					<td> <?=$data['booking']->cost?>.00</td>
				</tr>
				<tr style="border-style:dotted">
					<td>5</td>
					<td>Upcoins Redemeed</td>
					<td>:</td>
					<td></td>
					<td> <?=$data['coin']?>.00</td>
				</tr>
				<tr style="border-style:dotted">
					<td>6</td>
					<td>Online Payment</td>
					<td>:</td>
					<td></td>
					<td> <?=$data['rupee']?>.00</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-md-12 text-center">

		<p style="text-align: center;">www.upupup.in</p>
		<p style="text-align: center;">upupuphelp@gmail.com</p>
		<p style="text-align: center;">Contact upUPUP: 7034733338</p>

	</div>

</div>
