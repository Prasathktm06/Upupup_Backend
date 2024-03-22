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
		<p style=""> Congrats..!  <?=$data['user_name']?> (Mob: <?=$data['user_phone']?>)</p>
    <h3 style="">New booking is confirmed!</h3>

		<p style=""><?=date('dS F Y', strtotime($data['booking_date']))?> | Booking ID : [<?=$data['booking_id']?>]</p>
</center>
	</div>
	<div class="col-md-12 " style="margin-bottom: 25px;">
		

<div style="padding-left:36%;padding-top:10px">
			<?=date("g:i a", strtotime($data['court_time']))?> | <?=date('l',strtotime($data['booking_date']))?> 
							| <?=$data['sports']?>  | <?=$data['court_name']?> <br> | <?=$data['venue_name']?>
							| <?=$data['area']?>
		</div>
    <br>
		<div class="col-md-10" style="position: absolute;  margin-top: -10%;margin-left: 27%;" id="wt">
<p></p>
		</div>
		<div style="float: left;">
			<img style=" width: 194px; height: 72px;margin-left:6%;padding-right:10%;" id="venue_image" src="<?=$data['image']?>" height="50" width="50" >
</div>
	</div>

	<div class="col-md-12" style="margin-bottom: 25px;">
		<div class="col-md-12" style="padding:2%">
			<table style="width: 100%;background-color: pink;" >
				<tr style="border-style:dotted">
					<td>1</td>
					<td>Total Amount</td>
					<td>:</td>
					<td> <?=@$data['offer_deduction']+$data['cost']?>.00</td>
				</tr>
				<tr style="border-style:dotted">
					<td>2</td>
					<td>Offer name</td>
					<td>:</td>
					<td> <?=@$data['offer_name']?> </td>
				</tr>
				<tr style="border-style:dotted">
					<td>3</td>
					<td>Offer Deduction</td>
					<td>:</td>
					<td> <?=@$data['offer_deduction']?>.00 </td>
				</tr>
				<tr style="border-style:dotted">
					<td>4</td>
					<td>Payable Amount</td>
					<td>:</td>
					<?php if((int)$data['mode']==1){ ?>
					<td> <?=$data['cost']?>.00 </td>
					 <?php }else{ ?>
					<td> <?=$data['cost']?>.00 </td>
					 <?php } ?>
				</tr>

				<tr style="border-style:dotted">
					<td>5</td>
					<td>Amount Paid at upUPUP</td>
					<td>:</td>
					<td> 0.00 </td>
				</tr>
				<tr style="border-style:dotted">
					<td>6</td>
					<td>Amount Paid at Venue</td>
					<td>:</td>
					<?php if((int)$data['mode']==1){ ?>
					<td> <?=$data['cost']?>.00 </td>
					 <?php }else{ ?>
					<td> <?=$data['cost']?>.00 </td>
					 <?php } ?>
				</tr>

			</table>
		</div>
	</div>

</div>
