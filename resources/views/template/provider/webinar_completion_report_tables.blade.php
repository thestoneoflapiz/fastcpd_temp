<style>
	html, body{font-family: baskerville}
	.right{text-align: right;}
	.left{text-align: left;}
	.center{text-align: center;}
	.center_div{margin: auto;width: 90%;}
	.justify{text-align:justify;}
	.border-bottom{border-bottom:1px solid grey;}
	.bold{font-weight:bold;}
	.footer-right{float:right; width:35%;}
	.footer-left{float:left; width:35%;}
	.font-xl{font-size: 25px;}
	.font-l{font-size: 22px;}
	.font-m{font-size: 17px; }
	.font-s{font-size: 14px;}
	.font-xs{font-size: 10px;}
	small{font-size:10px;}
	.container{font-family:baskerville;}
	.pad-down{padding-bottom:3px;}
	.pad-down-x2{padding-bottom:45px;}
	.pad-top{padding-top:5px;}
	.pad-side{padding-left:100px;padding-right:100px;}
	.image-logo{height:80;width:80px;padding:5px;}
	.image-icon{height:15px;width:15px;}
	.user-image{height:150px;width:150px;}
	.no-border {border: none;padding:15px}
	.no-border2 {border: none;}
	.bold{font-weight:bold;}
	.bt{border-top: 1px solid black;}
	
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	  padding:2px;
	}
	.border {
		border: 1px solid black;
	}
	.vertical-align {
		vertical-align: top;
	}
	row {
		padding: 0px !important;
		margin: 0px !important; 
	}
	
	.row > div {
		margin: 0px !important;
		padding: 0px !important;
	}
	table.print-friendly tr td, table.print-friendly tr th {
		page-break-inside: avoid;
	}
	.print-friendly-div {
		page-break-inside: avoid;
	}
	</style>
	<div class="container" class="margin-top:-50px">
		<div class="row">

			<div class="container">
				
				<ol type="A" start="3">
					
					<li>
						<b>Summary of evaluation of resource persons in tabular form</b>
						<br/><br/>
						
						<br/><br/>
						<table width="100%">
							<tr>
								<td class="bold font_s center" width="12%">Participant</td>
								<td class="bold font_s center"  width="8%">PRC No.</td>
								<td class="bold font_s center" width="14%">Rating(1-5, 5 highest)</td>
								<td class="bold font_s center"  width="28%">Comment</td>
								<td class="bold font_s center" width="8%">Are you learning valueble information?</td>
								<td class="bold font_s center"  width="8%">Are the explanation of the concepts clear?</td>
								<td class="bold font_s center" width="8%">Is the instructor's delivery engaging?</td>
								<td class="bold font_s center"  width="8%">Are there enough opportunities to apply what you're learning?</td>
								<td class="bold font_s center" width="8%">Is the course delivering in your expectations?</td>
								<td class="bold font_s center"  width="8%">Is the instructor knowledgeable about the topic?</td>
							</tr>
							
							@foreach($course_performances as $perform)
							<?php $prc_no = json_decode($perform->prc_no) ?? null; ?>
							<tr>
								<td class="font_s center"  width="10%"><?= $perform->username_feedback ?></td>
								<td class="font_s center"  width="10%"><?= $prc_no[0]->prc_no ?? null ?></td>
								<td class="font_s center"  width="10%"><?= $perform->course_rating ?></td>
								<td class="font_s"  width="10%"><?= $perform->course_remark ?></td>
								<td class="font_s center"  width="10%"><?= ucwords($perform->valuable_information) ?></td>
								<td class="font_s center"  width="10%"><?= ucwords($perform->concepts_clear) ?></td>
								<td class="font_s center"  width="10%"><?= ucwords($perform->instructor_delivery) ?></td>
								<td class="font_s center"  width="10%"><?= ucwords($perform->opportunities) ?></td>
								<td class="font_s center"  width="10%"><?= ucwords($perform->expectations) ?></td>
								<td class="font_s center"  width="10%"><?= ucwords($perform->knowledgeable) ?></td>
							</tr>
							@endforeach
						</table>
					</li>
					<li>
						<b>Summary of evaluation of learning of the participants</b>
						<br/><br/>
						The participants were required to take quizzes during the course to assess the learning outcomes by participating 
						in the online course. The following are the final grading percentages of the participants
						<br/><br/>
						<table width="100%">
							<tr>
								<td class="bold font_s" width="34%">Full Name</td>
								<td class="bold font_s"  width="33%">PRC No.</td>
								<td class="bold font_s" width="33%">Overall Percentage</td>
							</tr>
							@foreach($participants as $participant)
							<?php $prc_no = json_decode($participant["prc_no"]) ?>
							
							<tr>
								<td class="font_s center"  width="34%"><?= $participant["pariticipant_name"] ?></td>
								<td class="font_s center"  width="33%"><?= $prc_no[0]->prc_no ?></td>
								<td class="font_s center"  width="33%"><?= $participant["score_percentage"] ?>%</td>
							</tr>
							@endforeach
						</table>
					</li>
				</ol>
			</div>
		</div>
	</div>


	<pagebreak orientation="L">
	<div class="container" class="margin-top:-50px">
		<div class="row">
			<table width="100%">
				<tr>
					<td width="15%" class = "center" rowspan="2">
						<img class="image-logo" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Professional_Regulation_Commission_%28PRC%29.svg/1200px-Professional_Regulation_Commission_%28PRC%29.svg.png">
					</td>
					<td width="85%" class="center pad-side font-s "  >
						<b>Professional Regulation Commission</b>
					</td>
				</tr>
				<tr>
					<td width="85%" class="center pad-side font-m" style="padding:20px;background-color:#DBE5F1">
						<b>REGISTRATION SHEET</b>
					</td>
	
				</tr>
			</table>
		</div>
	
		<div class="row" >
			<div class="center font-l" style="padding:15px;" >
				<b>CPD COUNCIL OF/FOR <?php 
					$prof_count = count($professions);
					$prof = "";
						foreach($professions as $index => $profession){
							if($index == 0){
								$prof = $profession->title;
							}else if($index+1 == $prof_count){
								$prof = $prof." and ".$profession->title;
							}else{
								$prof = ", ".$profession->title;
							}

						} 
						echo $prof;
					?> </b>
			</div>
		</div>
		<div class="row">
			<table width="100%">
				<tr>
					<td colspan="7">Title of the Program: <?= $courses->title ?></td>
				</tr> 
				<tr>
					<td colspan="4">Date: <?= date("F 01, Y",strtotime($completion_rep->completion_date)) ?> - <?= date("F t, Y",strtotime($completion_rep->completion_date)) ?></td>
					<td colspan="3">Venue: FastCPD Online</td>
				</tr>
				<tr>
					<td class="bold" width="2%">NO.</td>
					<td class="bold" width="18%">NAME</td>
					<td class="bold" width="16%">SIGNATURE</td>
					<td class="bold" width="16%">MOBILE PHONE NUMBER</td>
					<td class="bold" width="16%">EMAIL ADDRESS</td>
					<td class="bold" width="16%">PRC LICENSE NO.</td>
					<td class="bold" width="16%">EXPIRY DATE<br/>(DD/MM/YY)</td>
				</tr>
				
				<?php foreach($registration as $key => $register) { ?>
				<tr>
					<td>
						<?=$key+1;?>
					</td>
					<td><?= $register->pariticipant_name ?></td>
					<td class="center">
						<?php 
					
							if($register->signature != null && getImageSize($register->signature)){
								echo "
								<img src=".$register->signature." style='max-height:50px;' />
								";
							}
						?>
					</td>
					<td><?= $register->contact_no ?></td>
					<td><?= $register->email ?></td>
					<td >
				
						<?php 
							$count = count(json_decode($register->prc_no));
						
							if($count > 1){
								foreach(json_decode($courses->profession_id) as $prof){
									foreach(json_decode($attend->prc_no) as $participant){
										if($prof == $participant->id){
											echo $participant->prc_no;
										}
									}
								}	
							}else{
								foreach(json_decode($register->prc_no) as $participant){
										echo $participant->prc_no;
								}	
							}
								
						?>
					</td>
					<td >
						<?php 
							$count = count(json_decode($register->prc_no));
						
							if($count > 1){
								foreach(json_decode($courses->profession_id) as $prof){
									foreach(json_decode($register->prc_no) as $participant){
									
										if($prof == $participant->id){
											if(isset($participant->expiration_date)){
												if($participant->expiration_date != null){
													echo date("d/m/y",strtotime($participant->expiration_date));
												}
											}
										}
									}
								}	
							}else{
								foreach(json_decode($register->prc_no) as $participant){
									if(isset($participant->expiration_date)){
										if($participant->expiration_date != null){
											echo date("d/m/y",strtotime($participant->expiration_date));
										}
									}
										
								}	
							}
						
						?>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="4">Certified Correct by:</td>
					<td colspan="3">Concurred by:</td>
				</tr>
				<tr>
					<td class="center pad-top" colspan="4" style="border-bottom:none;">
						<?php 
							if($general_info->signature != null && getImageSize($general_info->signature)){
						?>
							<div class="center" style="width:50%;position:absolute;margin-top:20px;">
								<img src="<?= $general_info->signature ?>" style="max-height:60px;" />
							</div>
						<?php } else { echo "<br/><br/>"; } ?>
						<div class="center" style="margin-top:-25px;" >
							<span ><?= strtoupper($general_info->fullname) ?></span><br/>
							<span style="border-top:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							(Signature Over Printed Name)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
							<span>CPD PRovider's Authorized Representative</span>
						</div>
					</td>
					<td class="center pad-top" colspan="3" style="border-bottom:none;">
						<?php 
							if($general_info->signature != null && getImageSize($general_info->signature)){
						?>
							<div class="center" style="width:50%;position:absolute;margin-top:20px;">
								<img src="<?= $general_info->signature ?>" style="max-height:60px;" />
							</div>
						<?php } else { echo "<br/><br/>"; } ?>
						<div class="center" style="margin-top:-25px;" >
							<span ><?= strtoupper($general_info->fullname) ?></span><br/>
							<span style="border-top:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							(Signature Over Printed Name)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
							<span>CPD PRovider's Authorized Representative</span>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="border-top:none;">Date & Time: <?= date("F j, Y") ?></td>
					<td colspan="3" style="border-top:none;">Date & Time: <?= date("F j, Y") ?></td>
				</tr>
			</table>
		</div>
	</div>
