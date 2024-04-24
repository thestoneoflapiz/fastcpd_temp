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
.pad-side{padding-left:100px;padding-right:100px;}
.image-logo{height:80;width:80px;padding:5px;}
.image-signature{width:120px;height:80px}
.image-icon{height:15px;width:15px;}
.image-id{height:250px;width:250px;}
.user-image{height:150px;width:150px;}
.no-border {border: none;padding:15px}
.no-border2 {border: none !important}

table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
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
<?php foreach(json_decode($part_1['course_profession_id']) as $course_profession_id ){ 
    $profession_details_head = _get_profession($course_profession_id);?>
    <div class="container" class="margin-top:-50px">
        <div class="row">
            <table width="100%">
                <tr>
                    <td width="15%" class = "center" rowspan="2">
                        <img class="image-logo" src="<?= $prc_logo ?>">
                    </td>
                    <td width="85%" class="center pad-side font-s "  >
                        <b>Professional Regulation Commission</b>
                    </td>
                </tr>
                <tr>
                    <td width="85%" class="center pad-side font-m" style="padding:20px;background-color:#DBE5F1">
                        <b>APPLICATION FOR ACCREDITATION OF CPD PROGRAM</b>
                    </td>

                </tr>
            </table>
        </div>

        <div class="row" >
            <div class="center font-l" style="padding:15px;" >
                <b>CPD COUNCIL of/for <?= $profession_details_head->profession ?></b>
            </div>
        </div>
        <div class="row">
            <table width="100%">
                <tr>
                    <td colspan="3" style="background-color:#D9D9D9">
                        Part I. General Information
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="font-s " >
                    Name of Provider: <?= $part_1['name_provider'] ? $part_1['name_provider'] : "" ?>

                    </td>

                </tr>
                <tr>
                    <td class="font-s " width="50%">
                        Accreditation No.: <?= $part_1['accreditation_no'] ?? "" ?>
                    </td>
                    <td class="font-s " colspan="2" width="50%">
                        Expiration Date: <?= date("M d, Y", strtotime($part_1['expire_date'])) ?? "" ?>
                    </td>

                </tr>
                <tr>
                    <td class="font-s " width="50%">
                        Contact Person: <?= $part_1['contact_person'] ?? "" ?>
                    </td>
                    <td class="font-s " colspan="2" width="50%">
                        Designation: <?= $part_1['designation'] ?? "" ?>
                    </td>

                </tr>
                <tr>
                    <td class="font-s " width="50%">
                        <div style="padding-right:10px">Contact No.: <?= $part_1['contact_number'] ?? "" ?></div><div style="padding-right:25px">E-mail Add: <?= $part_1['email_add'] ?? "" ?></div>
                    </td>
                    <td class="font-s " colspan="2" width="50%">
                        Date of Application: <?= date("M d, Y") ?>
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class="font-s">
                        Proposed Program: <?= $part_1['title_of_program'] ?? "" ?>

                    </td>

                </tr>
                <tr>
                    <td colspan="3" class="font-s center">
                        
                        <table class="no-border" width = "100%">
                            <tr class="no-border">
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Conference</td>
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Seminar</td>
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box_checked.png"> Online Learing</td>
                            </tr>
                            <tr class="no-border">
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Convention</td>
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Workshop</td>
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Educational/Study tour</td>
                            </tr>
                            <tr class="no-border">
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Forum</td>
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Training Program</td>
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Others:____________</td>
                            </tr>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class="font-s">
                        Title of the Program: <?= $part_1['title_of_program'] ?? "" ?>
                    </td>
                </tr>
                <tr>
                    <td class="font-s " width="50%">
                        Date to be offered: <?=  $part_1['date_offered'] ?? " " ?>
                    </td>
                    <td class="font-s " width="25%">
                        Duration: <?= $part_1['duration'] ?? "" ?>
                    </td>
                    <td class="font-s " width="25%">
                        Time : <?= $part_1['time'] ?? "0" ?>
                    </td>
                </tr>
                <tr>
                    <td class="font-s " width="50%">
                        Place / Venue: <?= $part_1['place_venue'] ?? "" ?>
                    </td>
                    <td class="font-s " colspan="2" width="50%">
                        No. of times program to be conducted: <?= $part_1['times_program'] ?? "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="font-s">
                        Course Description: <?= $part_1['course_description'] ?? "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="font-s">
                        Objectives:<br/>
                        <?php
                            if($part_1['objectives']){
                                foreach($part_1['objectives'] as $objective){
                                    echo " - ". $objective."<br/>" ;
                                }
                            }
                            
                        ?>
                        
                    </td>
                </tr>
                
                <tr>
                    <td class="font-s " width="60%">
                        Target Participants / No.:  <?= $part_1['target_number_students'] ?><br/>
                    </td>
                    <td class="font-s " colspan="2" width="40%">
                        Registration Fee to be collected: Php <?= number_format($part_1['registration_fee'] ?? "0" ,2); ?>
                    </td>
                </tr>
                
            </table>

            <table width="100%">
                <tr>
                    <td colspan="2" style="background-color:#D9D9D9">
                        Part II. Acknowledgement
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="font-s center">
                        <p class="left">I HEREBY CERTIFY that the above information written by me are true and correct to the best of my knowledge and belief. 
                            I further authorize PRC and other agencies to investigate the authenticity of all the documents presented.</p><br/>
                        <p>I am agreeing to the PRC Privacy Notice and give my consent to the collection and processing of my personal data in accordance thereto.</p><br/>

                        <div class="center" >
                            <img class="image-signature" src="<?= $part_1['signature'] ?? "No uploaded" ?>"><br/>
                            <u><?= $part_1['contact_person'] ?? "None assigned" ?></u><br/>
                            Signature Over Printed Name<br/>
                            <u><?= $part_1['designation'] ?? "" ?></u><br/>
                            Position<br/>
                            <u><?= date("M d, Y") ?></u><br/>
                            Date<br/>
                        </div>
                    </td>
                </tr>
            </table>
            <table width ="100%">
                <tr>
                    <td colspan="2" style="background-color:#D9D9D9">
                        Part III. Action Taken
                    </td>
                </tr>
                <tr>
                    <td class="font-s left">
                        <b>Regulation Division:</b><br/>
                        Assessed by&nbsp;: ____________________________________<br/>
                        Processed by:____________________________________<br/>
                        Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:____________________________________<br/>
                    </td>
                    <td class="font-s left">
                        <b>Cash Division:</b><br/>
                        Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:______________O.R No.:______________<br/>
                        O.R.No./Date :____________________________________<br/>
                        Issued by&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:_____________________________________<br/>
                    </td>
                </tr>
            </table>

            <table width="100%" >
                <tr>
                    <td colspan="2" style="background-color:#D9D9D9">
                        Part IV.  Action taken by the CPD Council
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="font-s center">
                        <p><u><b>ACTION TAKEN BY THE CPD COUNCIL</b></u></p><br/>
                        
                        <table class="no-border" width = "70%%" style="margin-bottom:15px"  >
                            <tr class="no-border">
                                <td class="no-border2 left"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Approved for________Credit Units</td>
                                <td class="no-border2 left"> Accreditation No. _______________</td>
                            </tr>
                            <tr class="no-border">
                                <td class="no-border2 left" colspan="2"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Disapproved</td>
                            </tr>
                            <tr class="no-border">
                                <td class="no-border2 left" colspan="2"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Deferred pending compliance ______________________________________</td>
                            </tr>
                        </table>

                        <table class="no-border2 center" width = "90%">
                            
                            <tr class="no-border2">
                                <td colspan="2">____________________________________<br/>Chairperson<br/>
                                </td>
                            </tr>

                            <tr class="no-border2 center">
                                <td class="no-border2" >____________________________________<br/>Member<br/>
                                </td>
                                <td class="no-border2 " >____________________________________<br/>Member<br/>
                                </td>
                            </tr>
                            <tr class="no-border2 center">
                                <td class="no-border2" colspan="2">____________________________________<br/>Date<br/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="container right print-friendly-div" style="font-size:8px;margin-top:3px">
                <b>
                    CPDD-02<br/>
                    Rev. 04<br/>
                    June 29, 2020<br/>
                    Page 1 of 2
                </b> 
            </div>
            
            <pagebreak> 
            <table width="100%" style="margin-bottom:20px" >
                <tr>
                    <td colspan="2" class="center" style="background-color:#D9D9D9">
                        PROCEDURE FOR ACCREDITATION OF CPD PROGRAM 
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 " style="vertical-align:top">
                        Step 1. 
                    <td/>
                    <td class="no-border2">
                        Secure Application Form at Regulation Division of any of the PRC Regional Offices, or download at 
                        PRC website (www.prc.gov.ph).
                    </td>
                </tr>
                <tr class="no-border left">
                    <td class="no-border2" style="vertical-align:top">
                        Step 2.
                    </td>
                    <td class="no-border2">
                        Fill-out Application Form and attach supporting documents listed hereunder. <b>fastened</b> and <b>in a  
                folder</b>. <br/>
                Provide one (1) set for receiving copy.

                    </td>
                </tr>
                <tr class="no-border2">
                    <td class="no-border2" style="vertical-align:top">
                        Step 3.
                    </td>
                    <td class="no-border2">
                        Proceed to Regulations Division of any of the PRC Regional Offices for checking and assessment
                    </td>
                </tr>

                <tr class="no-border2">
                    <td class="no-border2" style="vertical-align:top">
                        Step 4.
                    </td>
                    <td class="no-border2">
                        If the assessment is favorable, pay prescribed fee of One Thousand Pesos (P 1,000.00) per program 
                        offering. Government agencies and instrumentalities offering CPD Programs free of charge, do not 
                        have to pay a fee. If not favorable, go back to Step 3.
                    </td>
                </tr>

                <tr class="no-border2">
                    <td class="no-border2" style="vertical-align:top">
                        Step 5.
                    </td>
                    <td class="no-border2">
                        Submit Application Form with attached supporting documents and photocopy of official receipt to 
                        Regulations Division of any of the PRC Regional Offices, at least fifteen (15) working days prior to 
                        offering.
                    </td>
                </tr>

                <tr class="no-border2">
                    <td class="no-border2" style="vertical-align:top">
                        Step 6.
                    </td>
                    <td class="no-border2">
                        Follow-up the application ten (10) working days after submission at CPD Division (Central Office),
                        telephone numbers (+632) 8810-84-15 (PRC-PICC), or email at cpdd.applications@gmail.com/cpdd@prc.gov.ph
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
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Instructional Design as prescribed by the relevant Board. 
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Program of Activities showing time/duration of topics/workshop and resource persons with position and 
                        office, and evaluation period. 

                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Evaluation method or tool that measures the learning gained by the participants specific and 
                    appropriate to course objectives set 
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Resume of resource persons relevant to CPD program applied for.
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Photo copy of valid Professional Identification Card of resource persons if registered professional. 
                    Otherwise, submit photocopy of government-issued or company Identification Card.

                    </td>
                </tr>

                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Valid Special Temporary Permit if the resource person is a foreigner and if engagement is more 
                        than three (3) days or there is physical contact with patients in the case of medical and allied 
                        professions.
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Breakdown of expenses for the conduct of the CPD program.
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;<img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">&nbsp;]
                    <td/>
                    <td class="no-border2">
                        For Online Learning, Declaration of Minimum Technical Requirements (e.g. Operating System, 
                    Processor, Memory, Browser, Internet Connection, etc.) 
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center" >
                        Additional Requirements:
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Short brown envelope for the Certificate of Accreditation 
                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                    <td/>
                    <td class="no-border2">
                        One (1) set of metered documentary stamps worth Twenty-Five Pesos (P 25.00) each to be affixed to 
                        the Certificate of Accreditation. (Available at PRC Customer Service and PRC Regional Offices)

                    </td>
                </tr>
                <tr class=" no-border left">
                    <td class="no-border2 center" style="vertical-align:top">
                        [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                    <td/>
                    <td class="no-border2">
                        Soft copy of the Application including supporting attachments in PDF format saved in flash drive. 
                    </td>
                </tr>
            </table>
            <div class="container border" style="background-color:#D9D9D9;margin-bottom:300px;">
                <b>Note:</b>
                <ul>
                    <li>Application for accreditation should be filed 15 working days before the offering of the program/training.</li>
                    <li>Representative/s filing application/s for accreditation and claiming the Certificate of Accreditation in behalf 
                        of the applicant must present a letter of authorization and valid identification cards of both the authorized 
                        signatory and the representative.</li>
                    <li>The period for processing the application is 10 working days, subject to the stipulations in these guidelines. </li>
                    <li>If additional requirement/s is/are needed, a period of another 10 working days is given to submit the same. Failure 
                        to comply within the period shall be construed as abandonment of application and the prescribed fee shall be forfeited 
                        in favor of the government.</li>
                    <li>The CPD Council shall have the right to specify additional requirements if deemed necessary and appropriate. </li>
                </ul>
            </div>
            <div class="container right" style="font-size:8px;">
                <b>
                    CPDD-02<br/>
                    Rev. 04<br/>
                    June 29, 2020<br/>
                    Page 2 of 2
                </b> 
            </div>
            <pagebreak>
            <div class="container">
                <p style="font-size:22px;">Supporting Documents:</p>
                <p>FastCPD will be the delivery platform of the program course. FastCPD is an online video-on-demand learning course platform 
                    specifically tailored to professionals in the Philippines who want to earn CPD units conveniently. The website is built to 
                    comply with PRC Resolution 2019-1207 requirements indicated in section 2 and R.A. No. 10173 (Data Privacy Act of 2012) with 
                    a certification issued by the National Privacy Commission with registration No. PIC-002-711-2019, secured with SSL certificates, 
                    and equipped with online payments secured with PCI DSS Level 1 compliance. The following is the process of purchase and delivery 
                    for professionals:</p>
                    <ol>
                        <li>Professionals visit www.fastcpd.com </li>
                        <li>They will select our course <b><?= $part_1['title_of_program'] ?? " No course assigned" ?></b> and proceed to checkout</li>
                        <li>To complete the payment, they can select multiple payment methods such as credit card, bank deposit, GCash, GrabPay, and more </li>
                        <li>Once payment is complete, they will have access to the course containing the educational content provided below</li>
                        <li>After professionals have completed the course and has passed the required passing mark on test assessments, they will be provided 
                            with a digital copy of their CPD certificate of completion</li>
                    </ol> 
                <p>To access the live course, you can view this with the following details:</p>
                <div class="container" style="padding-left:40px;">
                    Email: <b><?= $support_docs_data['accreditor_email'] ?? "" ?></b><br/>
                    Password: <b><?= $support_docs_data['accreditor_pass'] ?? "" ?></b><br/>
                    Sample Certificate Link: https://www.fastcpd.com/data/pdf/user/webinar_certificate/<b><?= $support_docs_data['course_id'] ?? "" ?></b><br/><br/>

                </div>
                <div class="container" style="padding-left:20px;">
                    <b>A. Instructional Design as provided by the relevent board</b><br/>
                    <i>*Please see attached files</i><br/>
                    <b>B. Program of Activities showing time/duration of topics/workshop and resource persons with position and office, and evaluation period.  </b><br/>
                    <p>The program course will have <?= count($support_docs_data['sections']) ?> sections with <?= $support_docs_data['parts_count'] ?> parts 
                    containing <?= $module_type=="webinar" ? $support_docs_data['video_count']:$support_docs_data['total_video_length'] ?> <?=$module_type=="webinar" ? "lectures" : "- minute videos"?>, <?= $support_docs_data['quiz_count'] ?> quizzes and 
                    <?= $support_docs_data['article_count'] ?> articles. The course will also provide <?= $support_docs_data['handout_count'] ?> downloadable handouts for supplemental 
                    materials in learning. Each section has a learning objective to accomplish stated below:</p>
                </div>

                <table class="center_div print-friendly">
                    <tr>
                        <td colspan="2" width="80%">
                            <b>Sections & Parts</b>
                        </td>
                        <td width="20%">
                            <b>Mode of Learning</b>
                        </td>
                    </tr>
                    <?php foreach($support_docs_data['sections'] as $section){ ?>
                        <tr>
                            <td colspan="2" >
                                Section  <b><?= $section['number'] ?? "" ?> - <?= $module_type=="webinar" ? "Webinar Content" : $section['title'] ?? "" ?> </b>
                            </td>
                            <td >
                                
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" >
                                Objective: <b><?= $section['objective'] ?? "" ?> </b>
                            </td>
                            <td >
                                
                            </td>
                        </tr>
                        <?php foreach($section['parts'] as $key => $part) { ?>
                        <tr>
                            <td width="10%">
                                Part <b><?= $key+1 ?? "" ?></b>
                            </td>
                            <td width="70%">
                                <b><?= $part['title'] ?? "" ?></b>
                            </td>
                            <td width="20%">
                                <b>
                                @if($part["type"]=="video" && $module_type=="webinar")
                                lecture
                                @else
                                <?= $part['type'] ?? "" ?>
                                @endif
                                </b>
                            </td>
                        </tr>
                        <?php }?>
                    <?php }?>
                    
                </table>
                <div class="container" style="padding-left:20px;">
                    <b>C. Evaluation method or tool that measures the learning gained by the participants specific and appropriate to course objectives set </b><br/>
                    <p>We will have <?= $support_docs_data['quiz_count'] ?> tests with a required passing mark of <?= $support_docs_data['pass_percentage']*100 ?>% in order to receive a completion certificate after finishing the 
                        program course. The following tests will be conducted in order to ensure understanding of the topic:</p>
                </div>
                <?php if($support_docs_data['quiz_count']){ ?>
                    <table width="80%" class="center_div print-friendly">
                        <tr>
                            <td width="10%"><b>Test #</b></td>
                            <td width="60%"><b>Test Title</b></td>
                            <td width="30%"><b>No. of Questions</b></td>
                        </tr>
                        <?php $test_counter = 0; ?>
                        <?php foreach($support_docs_data['sections'] as $key => $section){ 
                            foreach($section['parts'] as $part){ 
                                if($part['type'] == "quiz") {
                                    $test_counter += 1;  ?>
                                    <tr>
                                        <td width="10%" class="center"><?= $test_counter ?></td>
                                        <td width="60%"><?=$part['title']?></td>
                                        <td width="30%"><?= count($part['items']) ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        
                    </table>
                <?php }else{ ?>
                    <div class="container center" style="padding-left:20px;">
                        <i> * No test assessments to be made </i><br/>
                    </div>
                <?php } ?>
                <div class="container" style="padding-left:20px;">
                    <b>D. Resume of resource persons relevant to CPD program applied for.  </b><br/>
                    <p><i>*Please see attached files*</i></p>
                    <b>E. Photo copy of valid Professional Identification Card of resource persons if 
                        registered professional. Otherwise, submit photo copy of government-issued or company identification card.  </b><br/>
                        <?php foreach($support_docs_data['instructors'] as $instructor){ ?>
                            <?= $instructor['name']?? "No instructor assigned" ?><br/>
                            <?php if(count(json_decode($instructor['prc_id']))){
                                foreach(json_decode($instructor['prc_id']) as $prc_id){ ?>
                                    <img class="image-id" src="<?= $prc_id ?? "No uploaded" ?>"><br/>
                                    
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <b>F. For Online Learning, Declaration of Minimum Technical Requirements (e.g. Operating System, Processor, Memory, Browser, 
                        Internet Connection, etc.)</b>
                    <p>FastCPD can be accessed by any computer, laptop, or mobile phone with a web browser such as Google Chrome, Mozilla Firefox, 
                        and Internet Explorer. FastCPD’s platform is built as a web application like Youtube which means no installation has to be 
                        made unlike using Zoom.</p>
                    <p>An internet connection is required to view and watch the videos at all times. We recommend a standard mobile internet or a 
                        home wifi speed of at least 5 mbps to ensure stable non-buffering videos while watching.</p>
                    <b>G. Feedback mechanism for all users </b>
                    <p>Professionals who have completed the course will be required to provide their feedback of the program course. They will provide 
                        the following information of each course:</p>
                    <ol>
                        <li>An overall rating from 1 star to 5 stars with choice increments of 0.5 in between.</li>
                        <li>Comment feedback and explanation of their provided rating</li>
                        <li>A “Yes”, “No”, and “Not Sure” feedback of the following:
                            <ol type="a">
                                <li>if they have learned valuable information</li>
                                <li>if the explanations of the concepts are clear</li>
                                <li>if the instructor’s delivery was engaging</li>
                                <li>if there are enough opportunities to apply what they have learned</li>
                                <li>if the course delivered more than their expectations</li>
                                <li>if the instructor(s) was knowledgeable of the topics</li>

                            </ol>
                        </li>
                    </ol>

                </div>
            </div>

        </div>
        <pagebreak>                          
        <div class="row" >
            <?php  
            $resume_count=count($support_docs_data['instructor_resumes'] ?? "[]");
            $resume_counter = 0;
            foreach($support_docs_data['instructor_resumes'] as $key => $instructor){
                $resume_counter += 1;
                $professions = json_decode($instructor->professions);?>
            <table width="100%" style="margin-bottom:10px">
                <tr>
                    <td width="15%" class = "center" rowspan="2">
                        <img class="image-logo" src="<?= $prc_logo ?>">
                    </td>
                    <td width="85%" class="center pad-side font-s"  >
                        <b>Professional Regulation Commission</b>
                    </td>
                </tr>
                <tr>
                    <td width="85%" class="center pad-side font-s" style="padding:20px;background-color:#DBE5F1">
                        <b>RESUME OF RESOURCE PERSON</b>
                    </td>

                </tr>
            </table>
            <table class="no-border center_div" width="100%" style="margin-bottom:5px">
                <tr>
                    <td class="no-border center" width="80%" colspan="3"><b>CPD COUNCIL OF/FOR 
                        <?php foreach ($professions as $profession){
                            $prof_details =_get_profession($profession->id); ?>
                            <?= $prof_details->profession." " ?></b>  
                        <?php } ?>
                    </td>
                    <td width="15%" rowspan="2" class="center" style="border:1px solid"><i><img class="user-image" src="<?= $instructor->image ?? "No uploaded" ?>"></i></td>

                </tr>
                <tr class="no-border" >
                    <td class="no-border" width="25%"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box_checked.png"> Principal </td>
                    <td class="no-border" width="25%"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Alternate </td>
                    <td class="no-border" width="25%"><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> Substitute </td>
                </tr>
            </table>
        </div>
        <div class="container">
            <table width="100%" class="print-friendly">
                <tr>
                    <td colspan="3" style="background-color:#CCCCCC">
                        Part I. Personal Circumstances
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Name: <?= $instructor->name ?? "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="pad-down" width="60%">
                        Residence Address: <?= $instructor->residence_address ?? "" ?>
                    </td>
                    <td class="pad-down" width="40%" rowspan="2">
                        <p class="center">Contact Details </p> 
                        <p>Landline No.: <?= $instructor->landline_number ?? "" ?></p>
                        <p>Mobile No.1: <?= $instructor->mobile_number ?? "" ?></p>
                        <p>Mobile No.2:</p>
                        <p>Email Add.:<?= $instructor->email ?? "" ?></p>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="pad-down" width="60%">
                        Business Address: <?= $instructor->business_address ?? "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Nationality/Citizenship: <?= $instructor->nationality ?? "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p><i>Note</i>: The CPD Council shall be informed of any change/s on resource person/s at least 10 days before the CPD program offering. 
                            Substitute resource person may submit this duly accomplished form three (3) days from the completion of the CPD program. </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="background-color:#CCCCCC">
                        Part II. Track Record 
                    </td>
                    
                </tr>
                <?php $mca_count = json_decode($instructor->major_competency_areas) ? count(json_decode($instructor->major_competency_areas)) : 0 ?>
                <tr>
                    <td rowspan="<?= $mca_count +1 ?>" width="20%">
                        Major Areas Specialization 
    
                    </td>
                    
                    <td class="center" width="40%">
                        Specialization
                    </td>
                    <td class="center" width="40%">
                        Sub-Specialization
                    </td>
                    
                </tr>
                <?php 
                if(json_decode($instructor->major_competency_areas)){

                foreach(json_decode($instructor->major_competency_areas) as $special) { ?>
                    <tr>
                        
                        <td class="center">
                        <?= $special->specialization ?>
                        </td>
                        <td class="center">
                        <?= $special->sub_specialization ?>
                        </td>
                        
                    </tr>
                <?php }} ?>
            </table>
            <table width="100%" class="print-friendly">
                <tr>
                    <td colspan="2" class="center">
                        Relevant Seminars/Training Programs Conducted in the last five (5) years 
                    </td>
                    <td colspan="2" class="center">
                        Relevant Seminars/Training Programs Attended in the last five (5) years
                    </td>

                </tr>
                <tr >
                    <td width="10%" class="center">
                        Date
                    </td>
                    <td width="40%" class="center">
                        Title of the Program
                    </td>
                    <td width="10%" class="center">
                        Date
                    </td>
                    <td width="40%" class="center">
                        Title of the Program
                    </td>

                </tr>
                <?php 
                if(json_decode($instructor->attended_programs) != null){
                    $attended = json_decode($instructor->attended_programs);
                }else{
                    $attended = [];
                }
                if(json_decode($instructor->conducted_programs) != null){
                    $conducted = json_decode($instructor->conducted_programs);
                }else{
                    $conducted =[];
                }
                $count = 0;
                if(count($attended) >= count($conducted) ){
                    $count = count($attended); 
                }else{
                    $count = count($conducted);
                }

                if($count != 0){
                
                for($i=0;$i<=$count;$i++){
                ?>
                <tr>
                    <td width="15%">
                        <?= $conducted[$i]->conducted_date ?? "&nbsp;" ?>
                    </td>
                    <td width="35%">
                        <?= $conducted[$i]->conducted_title ?? "&nbsp;" ?>
                    </td>
                    <td width="15%">
                        <?= $attended[$i]->attended_date ?? "&nbsp;" ?>
                    </td>
                    <td width="35%">
                        <?= $attended[$i]->attended_title ?? "&nbsp;" ?>
                    </td>
                    

                </tr>
                <?php }}else{ ?>
                <tr>
                    <td width="15%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td width="35%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td width="15%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td width="35%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    

                </tr>    
                <?php } ?>

            </table>

            <table width="100%" style="margin-bottom:15px;" class="print-friendly">
                <tr>
                    <td colspan="3" class="center">
                        Major Achievements, Citations, Recognition and Awards 
                    </td>

                </tr>
                <tr >
                    <td width="10%" class="center">
                        Date
                    </td>
                    <td width="45%" class="center">
                        Title 
                    </td>
                    <td width="45%" class="center">
                        Awarding Body
                    </td>

                </tr>
                <?php if(json_decode($instructor->major_awards) != null){
                    foreach(json_decode($instructor->major_awards) as $awards){ ?>
                <tr>
                    <td width="10%">
                        <?= $awards->major_date ?? " " ?>
                    </td>
                    <td width="45%">
                        <?= $awards->major_title ?? " " ?>
                    </td>
                    <td width="45%">
                        <?= $awards->major_award ?? " " ?>
                    </td>

                </tr>
                <?php }}else{ ?>
                <tr>
                    <td width="10%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td width="45%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td width="45%">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>

                </tr>
                <?php } ?>
            </table>

            <table width="100%" style="margin-bottom:15px" class="print-friendly">
                <tr>
                    <td colspan="5" style="background-color:#CCCCCC">
                        Part III. Education and Employment 
                    </td>
                </tr>
                <tr>
                    <td class="center" width="12%">
                        Educational Background
                    </td>
                    <td class="center" width="22%">
                        Name of School/University
                    </td>
                    <td class="center" width="30%">
                        Address
                    </td>
                    <td class="center" width="18%">
                        Inclusive Dates
                    </td>
                    <td class="center" width="18%">
                        Degree Earned
                    </td>
                </tr>
                <tr>
                    <td rowspan="<?= json_decode($instructor->college_background) ? count(json_decode($instructor->college_background)) : "1" ?>">
                        College
                    </td>

                    <?php $college_background_counter = 0; 
                    if(json_decode($instructor->college_background)){?>
                    <?php foreach(json_decode($instructor->college_background) as $college){ 
                        if($college_background_counter != 0){ ?> 
                            <tr> 
                        <?php } ?>
                                <td class="center">
                                <?= $college->college_university ?? " " ?>
                                </td>
                                <td class="center">
                                <?= $college->college_university_address ?? " " ?>
                                </td>
                                <td class="center">
                                <?= $college->college_date ?? " " ?>
                                </td>
                                <td class="center">
                                <?= $college->college_degree ?? " " ?>
                                </td>
                        <?php if($college_background_counter != 0){ ?>
                            </tr>
                        <?php }  $college_background_counter += 1;?>
                    <?php }
                    }else{  ?>
                    <tr>
                        <td class="center">
                        </td>
                        <td class="center">
                        </td>
                        <td class="center">
                        </td>
                        <td class="center">
                        </td>
                    </tr>
                    <?php } ?>
                </tr>
                
                <tr>
                    <td rowspan="<?= json_decode($instructor->post_graduate_background) ? count(json_decode($instructor->post_graduate_background)) : '1'?>">
                        Post-Graduate
                    </td>
                    <?php $counter = 0; 
                    if(json_decode($instructor->post_graduate_background)){ ?>

                    <?php foreach(json_decode($instructor->post_graduate_background) as $college){ 
                        if($counter != 0){ ?> 
                            <tr> 
                        <?php } ?>
                                <td class="center">
                                <?= $college->post_grad_university ?? " " ?>
                                </td>
                                <td class="center">
                                <?= $college->post_grad_university_address ?? " " ?>
                                </td>
                                <td class="center">
                                <?= $college->post_grad_date ?? " " ?>
                                </td>
                                <td class="center">
                                <?= $college->post_grad_degree ?? " " ?>
                                </td>
                        <?php if($counter != 0){ ?>
                            </tr>
                        <?php }  $counter += 1;?>
                    <?php }}else{ ?>
                        <tr> 
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                        </tr> 
                    <?php }?>
                </tr>
                <tr>
                    <?php $iwe = json_decode($instructor->work_experience) ? count(json_decode($instructor->work_experience)) : 0; ?>
                    <td rowspan="<?= $iwe + 2;?>">
                        Work Experience: Five (5) most recent Position 
                    </td>
                    <td class="center">
                        Position
                    </td>
                    <td class="center" colspan="2">
                        Agency/Company
                    </td>
                    <td class="center">
                        Inclusive Dates
                    </td>
                </tr>
                <?php if(json_decode($instructor->work_experience)){?>
                <?php foreach(json_decode($instructor->work_experience) as $experience){?>
                    <tr> 
                        <td class="center">
                        <?= $experience->work_position ?? " " ?>
                        </td>
                        <td class="center" colspan="2">
                        <?= $experience->work_company ?? " " ?>
                        </td>
                        <td class="center">
                        <?= $experience->work_date ?? " " ?>
                        </td>
                    </tr>
                <?php }}else{  ?>
                    <tr> 
                        <td class="center">
                        </td>
                        <td class="center" colspan="2">
                        </td>
                        <td class="center">
                        </td>
                    </tr>
                <?php } ?>
                
            </table>

            <table width="100%" style="margin-bottom:15px" class="print-friendly print-friendly-div">
                <tr>
                    <td colspan="8" style="background-color:#CCCCCC">
                        Part IV. Other Relevant Information 
                    </td>
                </tr>
                <?php $prof_id_details = json_decode($instructor->professions); 
                    
                    $prof_det = _get_profession($prof_id_details[0]->id);

                    if(count($prof_id_details) < 2 ){
                        $row_count = 2;
                    }else{
                        $row_count = count($prof_id_details);
                    }
                ?>

                <tr>
                    <td class="center" rowspan="<?= $row_count ?>" width = "10%">
                        Profession/s
                    </td>
                    <td class="center"  width = "15%">
                        <?= $prof_det->profession ?? ""?>
                    </td>
                    <td class="center" rowspan="<?= $row_count ?>" width = "10%">
                        License No. 
                    </td>
                    <td class="center" width = "15%">
                        <?= $prof_id_details[0]->prc_no ?? "" ?>
                    </td>
                    <td class="center" rowspan="<?= $row_count ?>" width = "10%"> 
                        Issued on: 
                    </td>

                    <td class="center" width = "15%">
                    <?= $prof_id_details[0]->issued_on ?? "" ?>
                    </td>
                    <td class="center" rowspan="<?= $row_count ?>" width = "10%">
                        Valid until: 
                    </td>
                    <td class="center" width = "15%">
                        <?= $prof_id_details[0]->expiration_date ?? "" ?>
                    </td>
                </tr>
                <?php
                    if(count($prof_id_details) > 1 ){
                    
                        for($i=1 ;$i < count($prof_id_details);$i++){
                            $prof_det = _get_profession($prof_id_details[$i]->id);
                ?> 

                <tr>
                    <td class="center" >
                        <?= $prof_det->profession ?? ""?>
                    </td>
                    <td class="center" >
                        <?= $prof_id_details[$i]->prc_no ?? "" ?>
                    </td>

                    <td class="center" >
                        <?= $prof_id_details[$i]->issued_on ?? "" ?>
                    </td>
                    <td class="center" >
                        <?= $prof_id_details[$i]->expiration_date ?? "" ?>
                    </td>
                </tr>
                <?php } }else{?>
                    <tr>
                    <td class="center" >
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" >
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" >
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" >
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td class="center" width="27%" colspan="2">
                        AIPO Membership
                    </td>
                    <td class="center" width="27%" colspan="3">
                        National/Chapter 
                    </td>
                    <td class="center" width="27%" colspan="2">
                        Position
                    </td>
                    <td class="center" width="19%">
                        Date
                    </td>
                </tr>
                <?php if(json_decode($instructor->aipo_membership) != null ){
                foreach(json_decode($instructor->aipo_membership) as $aipo_membership){?>
                <tr>
                    <td class="center" width="27%" colspan="2">
                    <?= $aipo_membership->aipo_membership ?? "" ?>
                    </td>
                    <td class="center" width="27%" colspan="3">
                    <?= $aipo_membership->aipo_national_chapter ?? "" ?>
                    </td>
                    <td class="center" width="27%" colspan="2">
                    <?= $aipo_membership->aipo_position ?? "" ?>
                    </td>
                    <td class="center" width="19%">
                    <?= $aipo_membership->aipo_date ?? "" ?>
                    </td>
                </tr>
                <?php } }?>
                <tr>
                    <td class="center" width="27%" colspan="2">
                    &nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" width="27%" colspan="3">
                    &nbsp;&nbsp;&nbsp; 
                    </td>
                    <td class="center" width="27%" colspan="2">
                    &nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" width="19%">
                    &nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="center" width="27%" colspan="2">
                        Other Major Affiliations (Professional, Civic
                    </td>
                    <td class="center" width="27%" colspan="3">
                        National/Chapter 
                    </td>
                    <td class="center" width="27%" colspan="2">
                        Position
                    </td>
                    <td class="center" width="19%">
                        Date
                    </td>
                </tr>
                <?php if(json_decode($instructor->other_affiliations) != null ){
                foreach(json_decode($instructor->other_affiliations) as $other_affiliation){?>
                    <tr>
                        <td class="center" width="27%" colspan="2">
                        <?= $other_affiliation->other_membership ?? "" ?>
                        </td>
                        <td class="center" width="27%" colspan="3">
                        <?= $other_affiliation->other_national_chapter ?? "" ?>
                        </td>
                        <td class="center" width="27%" colspan="2">
                        <?= $other_affiliation->other_position ?? "" ?>
                        </td>
                        <td class="center" width="19%">
                        <?= $other_affiliation->other_date ?? "" ?>
                        </td>
                    </tr>
                <?php }} ?>
                <tr>
                    <td class="center" width="27%" colspan="2">
                    &nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" width="27%" colspan="3">
                    &nbsp;&nbsp;&nbsp; 
                    </td>
                    <td class="center" width="27%" colspan="2">
                    &nbsp;&nbsp;&nbsp;
                    </td>
                    <td class="center" width="19%">
                    &nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="center" colspan="4">
                    I hereby  certify that the  above  information written by me are true and correct to the best of knowledge and belief. I 
                    further authorize PRC and other agencies to investigate the authenticity of the documents presented. <br/>

                    I am agreeing to the PRC Privacy Notice and giving my consent to the collection and processing of my personal data in accordance thereto. <br/><br/>
                    <img class="image-signature" src="<?= $support_docs_data['instructors'][$key]->signature?? "No uploaded" ?>"><br/>
                    <u><?= $instructor->name ?? "" ?></u><br/>

                    Signature Over Printed Name<br/>
                    <u><?= date("M d, Y") ?></u><br/>
                    Date

                    </td>
                    <td class="center" colspan="4" >
                        <?php foreach(json_decode($instructor->pic_identifications) as $pic_image){ ?>
                        <img class="image-id" src="<?= $pic_image ?? "" ?>">
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <?php if($resume_count == $resume_counter) {?>
                <pagebreak orientation="L">
            <?php }else{?>
                <pagebreak>  
            <?php } ?>                     

        </div>
        <?php } ?>
    </div>
    <div class="container">
        <div class="row">
            <table width="100%">
                <tr>
                    <td width="15%" class = "center" rowspan="2">
                        <img class="image-logo" src="<?= $prc_logo ?>">
                    </td>
                    <td width="85%" class="center pad-side font-s"  >
                        <b>Professional Regulation Commission</b>
                    </td>
                </tr>
                <tr>
                    <td width="85%" class="center pad-side font-s" style="padding:20px;background-color:#DBE5F1">
                        <b>INSTRUCTIONAL DESIGN </b>
                    </td>

                </tr>
            </table>
        </div>
        <div class="row center" style="margin:20px;">
            <b>CPD COUNCIL OF/FOR <?= $profession_details_head->profession ?></b>
        </div>
        <div class="row left" style="margin:20px;">
            <b>PROGRAM TITLE: </b> <?= $part_1['title_of_program'] ?? "" ?><br/>
            <b>PROGRAM DESCRIPTION: </b ><span></span><?= Str::limit($part_1['course_description'], 525, " . . .") ?? "" ?> <br/>
        </div>
        <b>PROGRAM OBJECTIVES/LEARNING OUTCOMES: </b><br/>
        <table class="center">
            <tr>
                <td>
                    <b>Specific Objectives of the Program</b> 
                </td>
                <td>
                    <b>Learning Outcomes per Topic  </b>
                </td>
                <td>
                    <b>Topics To Be Discussed / Resource Person</b>
                </td>
                <td>
                    <b>Time Allotment For Each Topic</b>
                </td>
                <td>
                    <b>Teaching Methods and Aids Needed For Each Topic </b>
                </td>
                <td>
                    <b>Evaluation Method or Tools To Be Used to Measure the Program Objectives</b>
                </td>
            </tr>
            <?php foreach($instructional_design as $idesign){  ?>
                <tr>
                    <td>
                        @foreach(json_decode($idesign->objectives) as $objective)
                        <?= $objective ?> <br/>
                        @endforeach
                    </td>
                    <td>
                        <?= $idesign->section_objective ?>
                    </td>
                    <td>
                        @foreach(json_decode($idesign->instructors) as $instructor)
                        <?= $instructor ?> <br/>
                        @endforeach
                    </td>
                    <td>
                        <?= $idesign->video_length ." mins"  ?> 
                    </td>
                    <td>
                        <?= $idesign->video_counter?> <?=$module_type=="webinar" ? "Lecture(s)" : "Video Course(s)"?><br/>
                        <?= $idesign->article_counter?> Article(s)
                    </td>
                    <td>
                        <?php if($idesign->evaluation_quiz_count != "No Assessment to be made"){ ?>
                            <?= $idesign->evaluation_quiz_count ?> Quiz of 
                            <?= $idesign->evaluation_question_count?> questions
                        <?php }else{  ?> 
                            No Assessment to be made
                        <?php }?>
                    </td>
                </tr>
            <?php }?>
            
        </table>
        <table class="no-border2" width = "100%">
            <tr>
                <td class="no-border2" width = "20%">&nbsp;</td>
                <td class="no-border2" width = "20%">&nbsp;</td>
                <td class="no-border2 font-xs" width = "15%">Attach Program of activities and Resume of Resource Person</td>
                <td class="no-border2" width = "15%">&nbsp;</td>
                <td class="no-border2" width = "15%">&nbsp;</td>
                <td class="no-border2 font-xs" width = "15%">Attach Evaluation Tool.</td>
            </tr>
        </table>

        <table class="no-border2" width="100%">
            <tr>
                <td class="no-border2 left" colspan="2"><b>REMARKS:</b></td>
            </tr>
            <tr>
                <td class="no-border2 left" colspan="2"><hr></td>
            </tr>
            <tr>
                <td class="no-border2 left" colspan="2"><hr></td>
            </tr>
            <tr>
                <td class="no-border left">Prepared by: __________________		</td>
                <td class="no-border right">Date :_____________________</td>
            </tr>
            <tr>
                <td class="no-border2 left" colspan="2"><b>TO BE DETERMINED BY THE CPD COUNCIL: </b></td>
            </tr>
            <tr>
                <td class="no-border left"><b>I.</b> PROGRAM LEVEL: 
                <img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> BASIC
                <img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> ADVANCED
                <img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> HIGHLY ADVANCED
                </td>
                <td class="no-border right"><b>II.</b> APPROVED CREDIT UNITS: ______________________</td>
            </tr>
            
        </table>
    </div>
    </pagebreak>
    <pagebreak orientation="P">
    <div class="container">
        <div class="right font-s"> <b>ANNEX "A"</b> </div>
        TITLE OF ACTIVITY: <b><u><?= $part_1['title_of_program'] ?? "" ?></u></b><br/>
        TYPE OF ACTIVITY: 
        <table class="print-friendly print-friendly-div">
            <tr>
                <td><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box_checked.png"> SEMINAR/WORKSHOP/FORUM</td>
                <td><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> LEARNING SESSION IN THE CONVENTION</td>
                <td><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> EDUCATIONAL TOUR</td>
                <td><img class="image-icon" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/check-box.png"> OTHER(Please specify)<br/>______________________</td>
            </tr>
        </table>
        DATE: <u><b><?= date("F d, Y")?></b></u><br/>
        VENUE: <u><b><?= $part_1['venue'] ?></b></u><br/>
        TARGET NO. OF PARTICIPANTS:  <u><b><?= $part_1['target_number_students'] ?></b></u><br/><br/>
        <b>BREAKDOWN OF EXPENSES:</b>
        <table class="table table-bordered" width="100%">
            <tr>
                <th width="40%">
                    EXPENSE ITEMS
                </th>
                <th width="40%">
                    DETAILS OF THE EXPENSES
                </th>
                <th width="20%">
                    AMOUNT
                </th>
            </tr>
            <tr>
                <td>
                    1. Venue
                </td>
                <td>{{ $expenses->venue_details ?? '' }}</td>
                <td>{{ number_format($expenses->venue_cost) ?? '0' }} </td>
            </tr>
            <tr>
                <td>
                    2. Meals
                </td>
                <td>{{ $expenses->meal_details ?? '' }}</td>
                <td>{{ number_format($expenses->meal_cost) ?? '0' }} </td>
            </tr>
            <tr>
                <td>
                    3. Honoraria
                    <ul >
                        <li>Speaker (or panel of experts)</li>
                        <li>Facilitator</li>
                        <li>Moderator/Master of Ceremony</li>
                        <li>Secretariat</li>
                    </ul>
                </td>
                <td>{{ $expenses->honoraria_details ?? '' }}</td>
                <td>{{ number_format($expenses->honoraria_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    4. Itemized Materials <div class="small-text">( e.g handbook/handouts, Certificates, pencil and papers, seminar kits, ink for printers)</div>
                </td>
                <td>{{ $expenses->item_materials_details ?? '' }}</td>
                <td>{{ number_format($expenses->item_materials_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    5. Advertising expenses
                </td>
                <td>{{ $expenses->advertising_expenses_details ?? '' }}</td>
                <td>{{ number_format($expenses->advertising_expenses_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    6. Transportation 
                    <ul>
                        <li>Speaker/s</li>
                        <li>Staff</li>
                    </ul>
                </td>
                <td>{{ $expenses->transportation_details ?? '' }}</td>
                <td>{{ number_format($expenses->transportation_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    7. Accommodation <br/><div class="small-text">(for the speaker)</div>
                </td>
                <td>{{ $expenses->accommodation_details ?? '' }}</td>
                <td>{{ number_format($expenses->accommodation_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    8. Processing Fee (Accreditation Fee)
                </td>
                <td>{{ $expenses->process_fee_details ?? '' }}</td>
                <td>{{ number_format($expenses->process_fee_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    9. Supplies and Equipment
                </td>
                <td>{{ $expenses->supplies_equip_details ?? '' }}</td>
                <td>{{ number_format($expenses->supplies_equip_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    10. Laboratory
                </td>
                <td>{{ $expenses->laboratory_details ?? '' }}</td>
                <td>{{ number_format($expenses->laboratory_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    11. VAT (12%)
                </td>
                <td>{{ $expenses->vat_details ?? '' }}</td>
                <td>{{ number_format($expenses->vat_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    12. Entrance fees <div class="small-text">(for museum, heritage/historical sites, cultural centers, exhibits, geographical sites, other sites etc.)</div>
                </td>
                <td>{{ $expenses->entrance_fee_details ?? '' }}</td>
                <td>{{ number_format($expenses->entrance_fee_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    13. Tour guide/Facilitator's fee
                </td>
                <td>{{ $expenses->facilitator_fee_details ?? '' }}</td>
                <td>{{ number_format($expenses->facilitator_fee_cost) ?? '0' }}</td>
            </tr>
            <tr>
                <td>
                    14. Miscellaneous (Please specify)
                </td>
                <td>{{ $expenses->misc_details ?? '' }}</td>
                <td>{{ number_format($expenses->misc_cost) ?? '0' }}</td>
            </tr>
        </table><br/>
        <b>TOTAL EXPENSES: <b><u> PHP {{ number_format($expenses->total_breakdown) ?? '0' }}</u></b>
    </div>

    <pagebreak orientation="P">
    </pagebreak>
<?php } ?>

