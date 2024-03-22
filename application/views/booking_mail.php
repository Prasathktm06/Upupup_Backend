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
    <h3 style="">Your booking is confirmed!</h3>

		<p style=""><?=date('dS F Y', strtotime($data['booking']->date))?> | Booking ID : [<?=$data['booking']->booking_id?>]</p>
</center>
	</div>
	<div class="col-md-12 " style="margin-bottom: 25px;">
		<div style="float: left;">
			<img style=" width: 194px; height: 72px;margin-left:6%;padding-right:10%;" id="venue_image" src="<?=$data['booking']->image?>" height="50" width="50" >
</div>
<div style="padding-left:6%;padding-top:120px">
			<?=date('dS F Y', strtotime($data['booking']->date))?> | <?=date('l',strtotime($data['booking']->date))?> |<?=$data['end_time']?> <br>
							| <?=$data['booking']->sports?> | <?=$data['booking']->court?><br><?=$data['booking']->venue?>
							<?=$data['booking']->area?>
		</div>
    <br>
		<div class="col-md-10" style="position: absolute;  margin-top: -10%;margin-left: 27%;" id="wt">
<p></p>
		</div>
	</div>

	<div class="col-md-12" style="margin-bottom: 25px;">
		<div class="col-md-12" style="padding:2%">
			<table style="width: 100%;background-color: pink;" >
				<tr style="border-style:dotted">
					<td>1</td>
					<td>Total Amount</td>
					<td>:</td>
					<td> <?=$data['booking']->price?></td>
				</tr>
				<tr style="border-style:dotted">
					<td>2</td>
					<td>Coupon Code</td>
					<td>:</td>
					<?php if(@$data['coupon']->coupon_code!=0 || @$data['coupon']->coupon_code!=''){ ?>
					<td> <?=@$data['coupon']->coupon_code?> </td>
					<?php }else{ ?>
					<td> no coupon </td>
					<?php } ?>
				</tr>
				<tr style="border-style:dotted">
					<td>3</td>
					<td>Coupon Deduction</td>
					<td>:</td>
					<?php if(@$data['coupon']->percentage=="No"){ ?>
					<td> <?php  if(@$data['coupon']->coupon_value!='') echo @$data['coupon']->coupon_value." ".@$data['coupon']->currency?> </td>
					<?php }else{?>
						<td><?php if(@$data['coupon']->coupon_value!='') echo @$data['coupon']->coupon_value."%"?> </td>
				<?php	}  ?>
				</tr>
				<tr style="border-style:dotted">
					<td>4</td>
					<td>Offer name</td>
					<td>:</td>
					<td> <?=@$data['booking']->offer?> </td>
				</tr>
				<tr style="border-style:dotted">
					<td>5</td>
					<td>Offer Deduction</td>
					<td>:</td>
					<td> <?=@$data['booking']->offer_value?> </td>
				</tr>
				<tr style="border-style:dotted">
					<td>6</td>
					<td>Payable Amount</td>
					<td>:</td>
					<?php if((int)$data['mode']==1){ ?>
					<td> <?=$data['booking']->cost?> </td>
					 <?php }else{ ?>
					<td> <?=$data['booking']->cost+$data['booking']->bal;?> </td>
					 <?php } ?>
				</tr>

				<tr style="border-style:dotted">
					<td>7</td>
					<td>Amount Paid at upUPUP</td>
					<td>:</td>
					<td> <?=$data['booking']->cost?></td>
				</tr>

				<tr style="border-style:dotted">
					<td>8</td>
					<td>Balance to be Paid at Venue</td>
					<td>:</td>
					<?php if((int)$data['mode']==1){ ?>
					<td> 0</td>
					<?php }else{ ?>
					<td> <?=$data['booking']->bal?></td>
					<?php } ?>
				</tr>
			</table>
		</div>
	</div>

</div>
