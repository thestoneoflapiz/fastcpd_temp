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
						<b>COMPLETION REPORT ON CPD PROGRAM</b>
					</td>
					
				</tr>
			</table>
		</div>
	
		<div class="row" >
			<div class="center font-l" style="padding:15px;" >
				<b>CPD COUNCIL of 
					<?php 
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
					?>
				</b>
			</div>
		</div>
		<div class="row">
			<table width="100%">
				<tr>
					<td colspan="3" class="bold" style="background-color:#D9D9D9">
						Part I. General Information
					</td>
				</tr>
				<tr>
					<td colspan="3" class="font-s " >
						Name of Provider: <?= $general_info->provider_name ?>
					</td>
	
				</tr>
				<tr>
					<td class="font-s " width="50%">
						Accreditation No.: <?= $general_info->provider_accreditation_no ?>
					</td>
					<td class="font-s " colspan="2" width="50%">
						Expiry Date: <?= date("F j, Y",strtotime($general_info->provider_accreditation_expiration)) ?>
					</td>
	
				</tr>
				<tr>
					<td class="font-s " width="50%">
						Contact Person: <?= $general_info->fullname ?>
					</td>
					<td class="font-s " colspan="2" width="50%">
						Designation:
					</td>
	
				</tr>
				<tr>
					<td colspan="3" class="font-s " style="border-bottom:none;">
						<div style="padding-right:10px">Contact No.: <?= $general_info->provider_contact_no ?> </div>
					</td>
				</tr>
			</table>
	
			<table width="100%">
				<tr>
					<td colspan="3" class="bold" style="background-color:#D9D9D9">
						Part II. Program Accreditation
					</td>
				</tr>
				<tr>
					<td colspan="3" class="font-s bold" >
						
						Title of the Program: <?= $courses->title ?>
					</td>
				</tr>
				<tr>
					<td class="font-s " width="50%">
						Program Accreditation No.: <?= $courses->program_accreditation_no ?>
					</td>
					<td class="font-s " colspan="2" width="50%">
						Date of Accreditation: 
					</td>
				</tr>
				<tr>
					<td class="font-s " width="50%">
						Date Started: <?= date("F j, Y",strtotime($webinar_sessions->session_date)) ?>
					</td>
					<td class="font-s " colspan="2" width="50%">
						Date Completed: <?= date("F j, Y",strtotime($webinar_sessions->session_date)) ?>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="font-s" >
						Venue: Online - FastCPD.com
					</td>
				</tr>
				<tr>
					<td class="font-s " width="50%">
						Total Number of Participants: <?= $total_applicants->applicant_count ?>
					</td>
					<td class="font-s " colspan="2" width="50%">
						Date Applied: <?= date("F j, Y",strtotime($courses->approved_at)) ?>
					</td>
				</tr>
			
				<tr>
					<td colspan="3" class="font-s" style="border-bottom:none;">
						<p>Executive Summary:</p><br/>
						<p>
							<?= $general_info->provider_name ?> has created the program course title <?= $courses->title ?> in the online platform FastCPD.com that was published since <?= date("F j, Y",strtotime($webinar_sessions->session_date)) ?>. 
							For the month of <?= date("F",strtotime($webinar_sessions->session_date)) ?> of the year <?= date("Y",strtotime($webinar_sessions->session_date)) ?>, a total of <?= $completion_rep->participants ?> 
							enrolled in the online course containing ( <?= $total_hours_webinar ?> hours and <?= $total_minutes_webinar ?> minutes or <?= ($total_hours_webinar*60) + $total_minutes_webinar ?> minutes ) of
							 video with <?= $total_handouts->total_handouts ?> handouts containing supplemental information materials for the full understanding of the topic.
						</p>
						<br/>
						<p>
							The course was divided into the following sections and parts:
						</p>
						<div style="margin-left:20px;">
							<ol>
								@foreach($section_details as $key => $details)
									<li>
										Module - <?=ucwords(strtolower($courses->title))?> 
										- <?=$details["videos_count"]?> lectures, <?=$details["quizzes_count"]?> quizzes, <?=$details["articles_count"]?> articles
									</li>
								@endforeach
							</ol>
						</div>
						
						<br/>
						@if($total_quizzes != 0)
						<p>
							<!-- if there are quizzes -->
							For the assessment and grading of each professional, the course had <?= $total_quizzes ?> test(s) that was/were 
							accomplished by each professional with a pass or fail grading system. The required rate for a passing mark 
							was <?= $courses->pass_percentage * 100 ?>%.
						</p>
						@endif
						<br/>
						<p>
							Attached are the necessary documents in compliance of the completion report.
						</p>
						<br/><br/><br/><br/><br/>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="font-s">
						<p>
							Proceedings (This part must include the following: relevant information, issues and concerns, records of 
							discussion during the open forum, among others.)
						</p>
						<br/><br/><br/><br/><br/>
					</td>
				</tr>
			</table>
			<table width ="100%">
				<tr>
					<td colspan="3" style="background-color:#D9D9D9">
						Part III. Acknowledgment
					</td>
				</tr>
				<tr>
					<td colspan="3" class="font-s" style="border-bottom:none;">
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I hereby certify that the above information written by me are true and correct to the best of my knowledge and belief. 
						I further authorize PRC and other agencies to investigate the authenticity of all the documents presented.</p><br/>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I am agreeing to the PRC Privacy Notice and giving my consent to the collection and processing of my personal data in accordance thereto.</p><br/>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="font-s center" style="border-top:none;">
						<?php 
							if($general_info->signature != null && getImageSize($general_info->signature)){
						?>
							<div class="center" style="width:50%;position:absolute;margin-top:20px;">
								<img src="<?= $general_info->signature ?>" style="max-height:60px;" />
							</div>
						<?php } else { echo "<br/><br/>"; } ?>
						<div class="center" style="margin-top:-20px;" >
							
							<span><?= strtoupper($general_info->fullname) ?></span><br/>
							<span class="bt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature Over Printed Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/><br/>
							<br/>
							<span>Owner</span><br/>
							<span class="bt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/><br/>
							<br/>
							<span><?= date("F j, Y") ?></span><br/>
							<span class="bt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" style="margin-bottom:20px" >
				<tr>
					<td colspan="3" class="center" style="background-color:#D9D9D9">
						PROCEDURE FOR THE SUBMISSION OF COMPLETION REPORT
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 " style="vertical-align:top">
						Step 1. 
					</td>
					<td class="no-border2">
						Secure Completion Report Form at Regulations Division of any of the PRC Regional Offices, or 
					download at PRC website (www.prc.gov.ph).
					</td>
				</tr>
				<tr class="no-border left">
					<td class="no-border2" style="vertical-align:top">
						Step 2.
					</td>
					<td class="no-border2">
						Fill-out Completion Report Form and comply the required documents. (Please provide one (1) set for 
					receiving copy.)
					</td>
				</tr>
				<tr class="no-border2">
					<td class="no-border2" style="vertical-align:top">
						Step 3.
					</td>
					<td class="no-border2">
						Proceed to Regulation Division of any of the PRC Regional Offices for submission.
					</td>
				</tr>
				<tr>
					<td colspan="2" class="center" style="background-color:#D9D9D9">
						CHECKLIST OF REQUIREMENTS
					</td>
				</tr>
				<tr>
					<td colspan="2" class="center" >
						SUPPORTING DOCUMENTS
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
							Hard and electronic copies of registration and/or attendance sheets (preferably in excel format); 
						Registration Sheets must show the name of participants and guests, PRC License Number (if 
						applicable), contact details and signature while the attendance sheet shall include the name of 
						participants, license numbers, expiry date and signature
					</td>
				</tr>
			
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
							Actual program of activities with the list and profile of lecturers/resource persons and information about 
						any deviation from the approved program
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						lecture materials 
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						Summary of evaluation of resource persons in tabular form
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						Summary of evaluation of learning of the participants
					</td>
				</tr>
	
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						Financial Report
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						Relevant photographs 
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						Souvenir magazine, if available
					</td>
				</tr>
				<tr class=" no-border left">
					<td class="no-border2 center" style="vertical-align:top">
						[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
					</td>
					<td class="no-border2">
						Others <u></u>
					</td>
				</tr>
			</table>
			<div class="container border bold" style="background-color:#D9D9D9;margin-bottom:300px;">
				<b>Note:</b>
				<ul>
					<li>The Completion Report must be submitted within 30 calendar days after the CPD program offering and must include the Monitor’s Report.</li>
					<li>The CPD Council shall have the right to specify additional requirements if deemed necessary and appropriate<li>
				</ul>
			</div>
			<pagebreak>
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
								<b>ATTENDANCE SHEET</b>
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
							?></b>
					</div>
				</div>
				<div class="row">
					<table width="100%">
						<tr>
							<td class="font-s" colspan="5">Title of the Program: <?= $courses->title ?></td>
						</tr> 
						<tr>
							<td class="font-s" colspan="2">Date: <?= date("F d, Y",strtotime($webinar_sessions->session_date)) ?></td>
							<td class="font-s" colspan="3">Venue: FastCPD Online</td>
						</tr>
						<tr>
							<td class="font-s" colspan="3">Topic/s:</td>
							<td class="font-s">Time:</td>
							<td class="font-s">Room: <br/> Online</td>
						</tr>
						<tr>
							<td class="bold font_s" width="2%">No.</td>
							<td class="bold font_s" width="25%">Name</td>
							<td class="bold font_s" width="23%">Signature</td>
							<td class="bold font_s" width="25%">PRC LICENSE NO.</td>
							<td class="bold font_s" width="25%">EXPIRY DATE<br/>(DD/MM/YY)</td>
						</tr>
					
						<?php foreach($attendance as $key => $attend) { ?>
						<tr>
							<td class="font-s">
								<?=$key+1?>
							</td>
							<td class="font-s"><?= $attend->pariticipant_name ?></td>
							<td class="font-s center">
								<?php 
					
								if($attend->signature != null && getImageSize($attend->signature)){
									echo "
									<img src=".$attend->signature." style='max-height:50px;' />
									";
								}
							?>
							</td>
							<td class="font-s">
								<?php 
								$count = count(json_decode($attend->prc_no));
							
								if($count > 1){
									foreach(json_decode($courses->profession_id) as $prof){
										foreach(json_decode($attend->prc_no) as $participant){
											if($prof == $participant->id){
												echo $participant->prc_no;
											}
										}
									}	
								}else{
									foreach(json_decode($attend->prc_no) as $participant){
											echo $participant->prc_no;
									}	
								}
									
								?>
							</td>
							<td class="font-s">
								<?php 
								$count = count(json_decode($attend->prc_no));
						
								if($count > 1){
									foreach(json_decode($courses->profession_id) as $prof){
										foreach(json_decode($attend->prc_no) as $participant){
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
									foreach(json_decode($attend->prc_no) as $participant){
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
						
							<td colspan="3" style="border-bottom:none;">Certified Correct by:</td>
							<td colspan="2" style="border-bottom:none;">Concurred by:</td>
						</tr>
						<tr>
							<td class="center pad-top" colspan="3" style="border-top:none;border-bottom:none;">
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
							<td class="center pad-top" colspan="2" style="border-top:none;border-bottom:none;">
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
							<td colspan="3" style="border-top:none;">Date & Time: <?= date("F j, Y") ?></td>
							<td colspan="2" style="border-top:none;">Date & Time: <?= date("F j, Y") ?></td>
						</tr>
					</table>
				</div>
			</div>
			<pagebreak>
			<div class="container">
				<p style="font-size:22px;">Supporting Documents:</p>
				<ol type="A">
					<li class="bold">
						Hard and electronic copies of registration and/or attendance sheets (preferably in excel format); 
						Registration Sheets must show the name of participants and guests, PRC License Number (if applicable), contact 
						details and signature while the attendance sheet shall include the name of participants, license numbers, expiry 
						date and signature <br/><br/>
						<i style="font-weight: normal;">*Please see attached files</i> <br/><br/>
					</li>
					
					<li class="bold">
						Actual program of activities with the list and profile of lecturers/resource persons and information about any 
						deviation from the approved program. <br/>
						<table width="100%">
							<tr>
								<td colspan="2" class="bold font_s" width="70%">Sections & Parts</td>
								<td class="bold font_s"  width="30%">Mode of Learning</td>
							</tr>
							<?= $counter = 1; ?>
							@foreach($section_content as $index => $contents)
								@foreach($contents as $key => $content)
									<tr>
										<td colspan="2" class="font_s" width="70%">Section <?= $counter ?> - <?= $key ?></td>
										<td class="font_s" width="30%"></td>
									</tr>
									@foreach($content as $part)
										<tr>
											<td class="font_s" width="15%">Part <?= $part["part_number"] ?></td>
											<td class="font_s " width="55%"> <?= $part["part_title"] ?> </td>
											<td class="font_s " width="30%"><?= ucwords($part["part_type"]) ?></td>
										</tr>
									@endforeach
								@endforeach
								<?php $counter++; ?>
							@endforeach
							<tr>
								<td class="font_s" width="15%"></td>
								<td class="font_s" width="55%">-END-</td>
								<td class="font_s" width="30%"></td>
							</tr>
						</table>
					</li>
					<li>
						<b>Lecture materials</b> <br/><br/>
						Lecture materials used in the online course can be accessed here:<br/>
						<p style="padding-left:20px;">
							Course Link: https://www.fastcpd.com/course/<?= $courses->url ?> <br/>
							<?php $accreditor = json_decode($courses->submit_accreditation_evaluation) ?>
							Email: <?=$accreditor->email?> <br/>
							Password: <?=$accreditor->password?> <br/>
						</p>
					</li>
				</ol>
			</div>
		</div>
	</div>
