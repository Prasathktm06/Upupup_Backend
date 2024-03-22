<div  style="width: 99%;">
	<div class="col-md-12 text-center">
		<center>
			<img src="<?=base_url('pics/venue/upupup.png')?>" style="width: 120px;">
		</center>
	</div>
	<div class="col-md-12" style="width: 99%;border-style: solid;border-color:#db585e;color:#000;border-width: 1px;">
	    <div style="padding-left: 15%;">
		<br>
		<p style="color:#000;display: initial;">Hai <?=$data['owner_name']  ?>,</p><br><br>
            	<div class="col-md-12" style="margin-bottom: 40px;">
            		<div class="col-md-12" style="padding-left: 2%;padding-right: 2%;">
            			<table style="width: 100%;background-color: #fff;color: #000;border-width: 10px;border-color: red;">
            				<tr style="color: #000;">
            					<td style="width: 30%;">Offer Name</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['offer_name'] ?></td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Offer Percentage</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['offer_value'] ?>%</td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Offer date</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['start_date'] ?> - <?=$data['end_date'] ?></td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Offer time</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['start_time'] ?> – <?=$data['end_time'] ?></td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Offer days</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?php if($data['sunday']==1){ ?> Sunday, <?php } ?><?php if($data['monday']==1){ ?> Monday, <?php } ?><?php if($data['tuesday']==1){ ?> Tuesday, <?php } ?><?php if($data['wednesday']==1){ ?> Wednesday, <?php } ?><?php if($data['thursday']==1){ ?> Thursday, <?php } ?><?php if($data['friday']==1){ ?> Friday, <?php } ?><?php if($data['saturday']==1){ ?> Saturday, <?php } ?></td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Courts</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['court_name']  ?></td>
            				</tr>
            				<br><br>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Venue Name</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['venue_name']  ?></td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Location</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['location_name']  ?></td>
            				</tr>
            				<br><br>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Added by</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['role']  ?> : <?=$data['vowner']  ?> (<?=$data['phone']  ?>)</td>
            				</tr>
            				<br><br><br>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Date</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['current_date']  ?></td>
            				</tr>
            				<tr style="color: #000;">
            					<td style="width: 30%;">Time</td>
            					<td style="width: 2%;">:</td>
            					<td style="text-align: left;"><?=$data['current_time']  ?></td>
            				</tr>
            			</table>
            		</div>
            	</div>
		<p style="color:#000;display: initial;">Cheers <br><br><br>
        </div>
	</div>

</div>