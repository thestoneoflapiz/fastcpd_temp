<?php

use App\{
    User, Logs, Info, Profession, 
    Provider, Co_Provider, Instructor, 
    Course, Profile_Requests, Review, 
    Provider_Permission,
    Section, Article, Video, Quiz,
    Quiz_Item, Quiz_Score,
    Course_Progress,
    Instructor_Permissions, Handout,
    Instructor_Resume,
    Course_Rating, System_Rating_Report, Payout,
    
    My_Cart, Voucher, Notification,Certificate,
    Purchase, Purchase_Item,Promoter,

    Webinar, Webinar_Attendance, Webinar_Session, Webinar_Series,
    Webinar_Rating, Webinar_Progress, Webinar_Performance, Webinar_Instructor_Permission
};

use App\Mail\{ProviderUserInvitation, ProviderInstructorInvitation, NotificationMail, GeneralMail};

use Illuminate\Support\Facades\{Auth};

if(! function_exists('_send_notification_email')){
    function _send_notification_email($email,$module,$course_id,$data_id){
        switch($module) {
            case "instructor_course_invitation":
                $course = Course::find($course_id);
                $provider = User::where('provider_id',$course->provider_id)
                            ->where('deleted_at', null)
                            ->first();
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);
                $instructor_permissions= Instructor_Permissions::where('user_id',$data_id)
                                                            ->where('course_id',$course_id)
                                                            ->first();
                $course_details = $instructor_permissions->course_details ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $attract_enrollments = $instructor_permissions->attract_enrollments ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $instructors = $instructor_permissions->instructors ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $video_and_content = $instructor_permissions->video_and_content ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $handouts = $instructor_permissions->handouts ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $grading_and_assessment = $instructor_permissions->grading_and_assessment ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $submit_for_accreditation = $instructor_permissions->submit_for_accreditation ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $price_and_publish = $instructor_permissions->price_and_publish ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';

                $subject="You have invited an Instructor to a Course";
                $body= "<b>Hi &nbsp;". $provider->name . ",</b><br/><br/>
                Your organization has added <b>".$instructor->name." </b>to access and modify your course ".$course->title.".<br/><br/>
                Once <b>".$instructor->name." </b>
                has accepted the invitation, access to the following will be allowed:<br /><br/>
                <div class='center'><img src='https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/iconfinder_check_1930264.png' width='30'><br/><br/></div>
                - Course Details - ". $course_details ."<br/>
                - Attract Enrollments - ". $attract_enrollments ."<br/>
                - Instructors - ". $instructors ."<br/>
                - Video and Content - ". $video_and_content ."<br/>
                - Handouts - ". $handouts ."<br/>
                - Grading and Assessment - ". $grading_and_assessment ."<br/>
                - Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                - Price and Publish - ". $price_and_publish ."<br/>";
                
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                
                Mail::to($provider->email)->send(new NotificationMail($data)) ;

                $subject="You have been invited as Instructor to a Course";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                You have been given access to ".$course->title." by ".(Auth::check() ? Auth::user()->name : $user->name)."<br/><br/>
                You can now access following:<br />
                Course Details - ". $course_details ."<br/>
                Attract Enrollments - ". $attract_enrollments ."<br/>
                Instructors - ". $instructors ."<br/>
                Video and Content - ". $video_and_content ."<br/>
                Handouts - ". $handouts ."<br/>
                Grading and Assessment - ". $grading_and_assessment ."<br/>
                Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                Price and Publish - ". $price_and_publish ."<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));


              break;
            case "instructor_course_update":
                $course = Course::find($course_id);
                $provider = User::where('provider_id',$course->provider_id)
                            ->where('deleted_at', null)
                            ->first();
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);
                $instructor_permissions= Instructor_Permissions::where('user_id',$data_id)
                                                            ->where('course_id',$course_id)
                                                            ->first();
                $course_details = $instructor_permissions->course_details ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $attract_enrollments = $instructor_permissions->attract_enrollments ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $instructors = $instructor_permissions->instructors ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $video_and_content = $instructor_permissions->video_and_content ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $handouts = $instructor_permissions->handouts ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $grading_and_assessment = $instructor_permissions->grading_and_assessment ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $submit_for_accreditation = $instructor_permissions->submit_for_accreditation ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $price_and_publish = $instructor_permissions->price_and_publish ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';

                $subject="You have invited an Instructor to a Course";
                $body= "<b>Hi &nbsp;". $user->name . ",</b><br/><br/>
                Your access to <b>".$course->title." </b> has been changed by ".$provider->name.".<br/><br/>
                You can now access the following in :<br /><br/>
                - Course Details - ". $course_details ."<br/>
                - Attract Enrollments - ". $attract_enrollments ."<br/>
                - Instructors - ". $instructors ."<br/>
                - Video and Content - ". $video_and_content ."<br/>
                - Handouts - ". $handouts ."<br/>
                - Grading and Assessment - ". $grading_and_assessment ."<br/>
                - Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                - Price and Publish - ". $price_and_publish ."<br/>";
                
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
        
                );
                
                Mail::to($provider->email)->send(new NotificationMail($data)) ;

                $subject="You have been invited as Instructor to a Course";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                You have been given access to ".$course->title." by ".(Auth::check() ? Auth::user()->name : $user->name)."<br/><br/>
                You can now access following:<br />
                Course Details - ". $course_details ."<br/>
                Attract Enrollments - ". $attract_enrollments ."<br/>
                Instructors - ". $instructors ."<br/>
                Video and Content - ". $video_and_content ."<br/>
                Handouts - ". $handouts ."<br/>
                Grading and Assessment - ". $grading_and_assessment ."<br/>
                Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                Price and Publish - ". $price_and_publish ."<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));


              break;
            case "instructor_course_unassigned":
                $course = Course::find($course_id);
                $provider = User::find(Auth::user()->id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);

                $subject="You access has been removed";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                Your access to ".$course->title." has been removed by ".$provider->name.". You will not be able to access the course anymore when you visit your instructor portal.<br/><br/>
                Please contact the provider if you have any concern regarding this action.<br/><br/>
                Email us at providers@fastcpd.com if you have any concerns. Thank You!<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($email)->send(new NotificationMail($data));
              break;
            case "instructor_provider_unassigned":
                // $course = Course::find($course_id);
                $provider = Provider::find($course_id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();

                $subject="Your access has been removed";
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Your access to the provider portal for ".$provider->name." has been removed. You will not be able to access it anymore.<br/><br/>
                Please contact the provider if you have any concern regarding this action.<br/><br/>
                Email us at providers@fastcpd.com if you have any concerns. Thank You!<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($email)->send(new NotificationMail($data));
              break;
            case "course_for_review":
                $course = Course::find($course_id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);

                $subject="Your course ".$course->title." is being reviewed";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                Your course ".$course->title."  is being reviewed. <br/><br/>
                We'll notify you once we've taken a look and approve your course.<br/><br/>
                This approval is needed to ensure integrity of the educational content in our platform. <br/><br/>
                Thank you for your consideration.<br />";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));
              break;
            case "course_for_review_superadmin":
                $course = Course::find($course_id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);

                $subject="New course titled ".$course->title." needs to be reviewed";
                $body= "<b>Hi superadmin &nbsp;".$instructor->name. ",</b><br/><br/>
                New course ".$course->title."  needs to be reviewed. ";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "/superadmin/verification/courses",
                    'label_button' => "View",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));
            break;
            case "instructor_invitation_student":
                $course = Course::find($course_id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);
                $table = _get_puchase_details($course_id);
                $subject = "Your purchase was canceleld";

                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Your purchase of the following courses has been cancelled:<br/>
                 <table>
                    <tr>
                        <th>Purchases</th>
                        <th>CPD Units</th>
                        <th>Price</th>
                    </tr>
                    ".$table."
                 </table>
                 <br/><br/>These are some of the reasons why your purchase was cancelled:<br/>
                 - Payment failed or was not completed on time <br/>
                 - You closed or your connection was lost during the payment confirmation<br/>
                 - You cancelled your order manually <br/><br/>
                 If you would like to clarfiy this matter, please email us at help@fastcpd.com<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($user->email)->send(new NotificationMail($data));
              break;
            case "signup":
                $user = User::find($data_id);
                $subject = "Welcome to FastCPD!";
                $profession_link = "";
                foreach(json_decode($user->professions) as $profession){
                    $profession_info = Profession::find($profession->id);
                    $profession_link .= "<a href='".\config('app.link')."/courses/".$profession_info->url ."' >".$profession_info->title."</a><br/>";
                }
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Welcome to FastCPD, your partner in online CPD. Our mission is to make earning CPD units easy and affordable for all professionals. <br/><br/>

                <div class='center'>
                    <img width='70%'  src='https://fastcpd.s3-ap-northeast-1.amazonaws.com/promos/fast30.png'> 
                </div><br/><br/>

                To start earning CPD units just follow these steps:<br/>
                - Browse courses for you profession<br/>
                - Add them to your cart and continue with payment<br/>
                - Navigate to your 'My Items' then start watching<br/>
                - Get your CPD certificate once you finish watching<br/><br/>Start browsing CPD courses for your profession:<br/><br/>".$profession_link;
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "VIEW COURSES",
                    'base_link' => ""
                );
                Mail::to($user->email)->send(new NotificationMail($data));
              break;
            case "become_provider":
                $user = User::find($data_id);
                $subject = "You have created a Provider Organization";

                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Congratulations! You have created a provider organization. You can now start creating courses to publish and sell. <br/>
                    We recommend you do the following to improve your provider experience:<br/>
                    - Make sure to complete your provider profile and submit for review to make it publicly viewable<br/>
                    - Add instructors to help you make courses<br/>
                    - Add other members of your organization to help you make courses<br/>
                    - You can start to plan on your course topics and make your content<br/>
                    ";
                    _notification_insert(
                        "become_provider",
                        $data_id,
                        $user->provider_id,
                        "Became a Provider",
                        "You have created a provider organization. Start making courses!",
                        "/provider/session/".$user->provider_idss
                    );
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "/provider/session/".$user->provider_id,
                    'label_button' => "GO TO PROVIDER PORTAL",
                    'base_link' => ""
                );
                
                Mail::to($user->email)->send(new NotificationMail($data));

                foreach(_get_all_superadmin() as $superadmin){
                    $subject = "You have created a Provider Organization";

                    $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                    New Provider Review";
                    $data = array(
                        'subject' => $subject,
                        'body' => $body,
                        'recipient'=> $user,
                        'link_button' => "/provider/session/".$user->provider_id,
                        'label_button' => "GO TO PROVIDER PORTAL",
                        'base_link' => ""
                    );
                    
                    Mail::to($superadmin->email)->send(new NotificationMail($data));
                    _notification_insert(
                        "course_creation",
                        $superadmin->id,
                        $user->provider_id,
                        "New Provider Created",
                        "New Provider Created ",
                        "/superadmin/verification/providers"
            
                    );
        
                }

              break;
            case "provider_access_remove":
                $user = User::find($data_id);
                $course = Course::find($course_id);
                $provider = Provider::first("id",$user->provider_id);
                $subject = "Submit ".$course->title." has been approved";

                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Your access to the provider portal for ".$provider->name." has been removed. You will not be able to access it anymore. <br/><br/>

                Please contact the provider if you have any concern regarding this action.  <br/><br/>

                Email us at providers@fastcpd.com if you have any concerns. Thank You!<br/><br/>" ;
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "VIEW COURSES",
                    'base_link' => ""
                );
                Mail::to($user->email)->send(new NotificationMail($data));
              break;
              case "generate_certificate":
                $certificate = Certificate::find($data_id);
                $user = User::find($certificate->user_id);
                $course = Course::find($course_id);
                $provider = Provider::first("id",$user->provider_id);
                $profession_link = "";
                $subject = "Congratulations! Get your CPD certificate";
                foreach(json_decode($course->accreditation) as $profession){
                    $profession_info = Profession::find($profession->id);
                    $profession_link .= "<a href='".\config('app.link') . $certificate->url."' >".$profession_info->title."</a><br/>";
                }
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Congratulations! You've finished completing the course ".$course->title.". <br/><br/>

                Get your CPD certificate here.  <br/>".$profession_link ."<br/><br/>

                Your certificates can be verified by the PRC <a href='".\config('app.link') ."/verfiry/". $certificate->certificate_code."'>here</a><br/><br/>
                
                Email us at providers@fastcpd.com if you have any concerns. Thank You!<br/><br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => $certificate->url,
                    'label_button' => "VIEW CERTIFICATE",
                    'base_link' => ""
                );
                Mail::to($user->email)->send(new NotificationMail($data));
              break;
            case "payout_completed":
            
                $payout = Payout::find($data_id);
                $user = User::find("provider_id",$course_id);
                $provider = Provider::find($course_id);
                $month = date("F",strtotime($payout->date_to));
                $year = date("Y",strtotime($payout->date_to));
                $subject = "Revenue report generated for ".$month." ".$year;
                
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Your total for ".$month." ".$year." revenue is Php ".number_format($payout->provider_revenue).".<br/><br/>

                Your revenue details  can be viewed and exported in your provider portal in the performance page.";

                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "/provider/revenue",
                    'label_button' => "GO TO PERFORMANCE",
                    'base_link' => ""
                );
                Mail::to($email)->send(new NotificationMail($data));
              break;
            case "revenue_report_generate":

                $certificate = Certificate::find($data_id);
                $user = User::find($certificate->user_id);
                $course = Course::find($course_id);
                $provider = Provider::first("id",$user->provider_id);
                $profession_link = "";
                $subject = "Congratulations! Get your CPD certificate";
                foreach(json_decode($course->accreditation) as $profession){
                    $profession_info = Profession::find($profession->id);
                    $profession_link .= "<a href='".\config('app.link') . $certificate->url."' >".$profession_info->title."</a><br/>";
                }
                $body= "<b>Hi &nbsp;".$user->name. ",</b><br/><br/>
                Your total for October, 2020 revenue is Php 2,500.<br/><br/>

                Your revenue details  can be viewed and exported in your provider portal in the performance page.";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => $certificate->url,
                    'label_button' => "VIEW CERTIFICATE",
                    'base_link' => ""
                );
                Mail::to($user->email)->send(new NotificationMail($data));
            break;

            //////////////////////////////// W E B I N A R ; B E G I N //////////////////////////////
            case "instructor_webinar_invitation":
                $webinar_id = $course_id;
                $webinar = Webinar::find($webinar_id);
                $provider = User::where('provider_id',$webinar->provider_id)
                            ->where('deleted_at', null)
                            ->first();
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);
                $instructor_permissions= Webinar_Instructor_Permission::where('user_id',$data_id)
                                                            ->where('webinar_id',$webinar_id)
                                                            ->first();
                $webinar_details = $instructor_permissions->webinar_details ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $attract_enrollments = $instructor_permissions->attract_enrollments ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $instructors = $instructor_permissions->instructors ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $video_and_content = $instructor_permissions->video_and_content ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $handouts = $instructor_permissions->handouts ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $grading_and_assessment = $instructor_permissions->grading_and_assessment ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $submit_for_accreditation = $instructor_permissions->submit_for_accreditation ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $price_and_publish = $instructor_permissions->price_and_publish ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';

                $subject="You have invited an Instructor to a Webinar";
                $body= "<b>Hi &nbsp;". $provider->name . ",</b><br/><br/>
                Your organization has added <b>".$instructor->name." </b>to access and modify your webinar ".$webinar->title.".<br/><br/>
                Once <b>".$instructor->name." </b>
                has accepted the invitation, access to the following will be allowed:<br /><br/>
                <div class='center'><img src='https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/iconfinder_check_1930264.png' width='30'><br/><br/></div>
                - Webinar Details - ". $webinar_details ."<br/>
                - Attract Enrollments - ". $attract_enrollments ."<br/>
                - Instructors - ". $instructors ."<br/>
                - Video and Content - ". $video_and_content ."<br/>
                - Handouts - ". $handouts ."<br/>
                - Grading and Assessment - ". $grading_and_assessment ."<br/>
                - Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                - Price and Publish - ". $price_and_publish ."<br/>";
                
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                
                Mail::to($provider->email)->send(new NotificationMail($data)) ;

                $subject="You have been invited as Instructor to a Webinar";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                You have been given access to ".$webinar->title." by ".(Auth::check() ? Auth::user()->name : $user->name)."<br/><br/>
                You can now access following:<br />
                Webinar Details - ". $webinar_details ."<br/>
                Attract Enrollments - ". $attract_enrollments ."<br/>
                Instructors - ". $instructors ."<br/>
                Video and Content - ". $video_and_content ."<br/>
                Handouts - ". $handouts ."<br/>
                Grading and Assessment - ". $grading_and_assessment ."<br/>
                Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                Price and Publish - ". $price_and_publish ."<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));


            break;
            
            case "instructor_webinar_update":
                $webinar_id = $course_id;
                $webinar = Webinar::find($webinar_id);
                $provider = User::where('provider_id',$webinar->provider_id)
                            ->where('deleted_at', null)
                            ->first();
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);
                $instructor_permissions= Webinar_Instructor_Permission::where('user_id',$data_id)
                                                            ->where('webinar_id',$webinar_id)
                                                            ->first();
                $webinar_details = $instructor_permissions->webinar_details ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $attract_enrollments = $instructor_permissions->attract_enrollments ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $instructors = $instructor_permissions->instructors ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $video_and_content = $instructor_permissions->video_and_content ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $handouts = $instructor_permissions->handouts ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $grading_and_assessment = $instructor_permissions->grading_and_assessment ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $submit_for_accreditation = $instructor_permissions->submit_for_accreditation ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';
                $price_and_publish = $instructor_permissions->price_and_publish ? '<img style="height:15px;width:15px;" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/Icons/tick.png">':'X';

                $subject="You have invited as Instructor to a Webinar";
                $body= "<b>Hi &nbsp;". $user->name . ",</b><br/><br/>
                Your access to <b>".$webinar->title." </b> has been changed by ".$provider->name.".<br/><br/>
                You can now access the following in :<br /><br/>
                - Webinar Details - ". $webinar_details ."<br/>
                - Attract Enrollments - ". $attract_enrollments ."<br/>
                - Instructors - ". $instructors ."<br/>
                - Video and Content - ". $video_and_content ."<br/>
                - Handouts - ". $handouts ."<br/>
                - Grading and Assessment - ". $grading_and_assessment ."<br/>
                - Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                - Price and Publish - ". $price_and_publish ."<br/>";
                
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $user,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
        
                );
                
                Mail::to($provider->email)->send(new NotificationMail($data)) ;

                $subject="You have been invited an Instructor to a Webinar";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                You have been given access to ".$webinar->title." by ".(Auth::check() ? Auth::user()->name : $user->name)."<br/><br/>
                You can now access following:<br />
                Webinar Details - ". $webinar_details ."<br/>
                Attract Enrollments - ". $attract_enrollments ."<br/>
                Instructors - ". $instructors ."<br/>
                Video and Content - ". $video_and_content ."<br/>
                Handouts - ". $handouts ."<br/>
                Grading and Assessment - ". $grading_and_assessment ."<br/>
                Submit for Accreditation - ". $submit_for_accreditation ."<br/>
                Price and Publish - ". $price_and_publish ."<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));
            break;
            
            case "instructor_webinar_unassigned":
                $webinar_id = $course_id;
                $webinar = Webinar::find($webinar_id);
                $provider = User::find(Auth::user()->id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);

                $subject="You access has been removed";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                Your access to ".$webinar->title." has been removed by ".$provider->name.". You will not be able to access the webinar anymore when you visit your instructor portal.<br/><br/>
                Please contact the provider if you have any concern regarding this action.<br/><br/>
                Email us at providers@fastcpd.com if you have any concerns. Thank You!<br/>";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($email)->send(new NotificationMail($data));
            break;
            
            case "webinar_for_review":
                $webinar_id = $course_id;
                $webinar = Webinar::find($webinar_id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);

                $subject="Your webinar ".$webinar->title." is being reviewed";
                $body= "<b>Hi &nbsp;".$instructor->name. ",</b><br/><br/>
                Your webinar ".$webinar->title."  is being reviewed. <br/><br/>
                We'll notify you once we've taken a look and approve your webinar.<br/><br/>
                This approval is needed to ensure integrity of the educational content in our platform. <br/><br/>
                Thank you for your consideration.<br />";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "",
                    'label_button' => "",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));
            break;
            
            case "webinar_for_review_superadmin":
                $webinar_id = $course_id;
                $webinar = Webinar::find($webinar_id);
                $user = User::where('email', $email)
                            ->where('deleted_at', null)
                            ->first();
                $instructor= User::find($data_id);

                $subject="New webinar titled ".$webinar->title." needs to be reviewed";
                $body= "<b>Hi superadmin &nbsp;".$instructor->name. ",</b><br/><br/>
                New webinar ".$webinar->title."  needs to be reviewed. ";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $instructor,
                    'link_button' => "/superadmin/verification/webinars",
                    'label_button' => "View",
                    'base_link' => ""
                );
                Mail::to($instructor->email)->send(new NotificationMail($data));
            break;
            //////////////////////////////// W E B I N A R ; E N D //////////////////////////////

            //////////////////////////////// P R O M O T E R; S T A R T //////////////////////////////
            case "promoter_invitation":
                $promoter = Promoter::where('id', $course_id)
                            ->where('deleted_at', null)
                            ->first();

                $subject="You've been invited by FASTCPD as a promoter";
                $body= "<b>Hi &nbsp;".$promoter->name. ",</b><br/><br/>
                You have been invited as a Promoter of FASTCPD. Your voucher code is ".$data_id." having 30% discount. <br/><br/>
                Please confirm and set your password to continue by clicking the button below";
                $data = array(
                    'subject' => $subject,
                    'body' => $body,
                    'recipient'=> $email,
                    'link_button' => "/auth/promoter/verify/".$promoter->id,
                    'label_button' => "Confirm",
                    'base_link' => "promoter"
                );
                Mail::to($email)->send(new NotificationMail($data));
            break;
            //////////////////////////////// P R O M O T E R; E N D //////////////////////////////
            default:
              // code block
            break;
          }
    }
}

if(! function_exists('_send_email')){
    function _send_email($recipient,$module,$message,$sender,$course_id){
        switch($module) {
            case "share_course":

                    $data = array(
                        'subject' => $subject,
                        'body' => $body,
                        'recipient'=> $instructor,
                        'link_button' => "/superadmin/verification/courses",
                    );
                    Mail::to($recipient)->send(new GeneralMail($data));
                break;
            default:
              // code block
          }
    }
}

if(! function_exists('_get_puchase_details')){
    function _get_puchase_details($data_id){

        $transaction = Purchase::where('reference_number',$data_id)->first();
        $transaction_items = Purchase_item::where('purchase_id',$transaction->id)->get();

        $table = "";
        $total = 0;
        foreach($transaction_items as $item){
            if($item->type == "course"){
                $data_record = Course::select("id", "url", "title", "course_poster as poster")->find($item->data_id);
            }else{
                $data_record = Webinar::select("id", "url", "title", "webinar_poster as poster")->find($item->data_id);
            }

            $units = "";
            if($item->credited_cpd_units){
                foreach(json_decode($item->credited_cpd_units) as $unit){
                    $units .= $unit->title." - ".$unit->units." <br/>";
                }
            }

            if($item->discount != null){
                $price_string = "Php ".$item->total_amount."<br/><del>Php ".$item->price."</del>";
            }else{
                $price_string = "Php ".$item->total_amount."<br/>";
            }
            
            $table .= " <tr>
                            <td class='center'><img alt='FastCPD Courses Webinars ".$data_record->title."' src='".$data_record->poster."' width='150'></td>
                            <td class='left'>
                                <b>".$data_record->title."</b> <br/>".$units."
                            </td>
                            <td class='right'>".$price_string."</td>
                        </tr>";

            $total += $item->total_amount;
        }
        $table .="<tr><td colspan='3' class='right'> TOTAL: ".$total."</td></tr>";
        return $table;
    }
}

if (! function_exists('_notification_get_unseened')) {

    function _notification_get_unseened()
    {
        $notif = Notification::where('recipient', Auth::user()->id)
                            ->where('seen_at', null)
                            ->orderBy('created_at','desc')
                            ->get();
        // dd($notif);
        return $notif;
    }
}

if (! function_exists('_notification_mark_seened')) {

    function _notification_mark_seened()
    {
        $unseened_notifs = Notification::where('recipient', Auth::user()->id)
                            ->where('seen_at', null)
                            ->orderBy('created_at','desc')
                            ->get();
        foreach($unseened_notifs as $notif){
            $notif_info = Notification::find($notif->id);
            $notif_info->seen_at = date("Y-m-d H:i:s");
            $notif_info->save();
        }
    }
}

if (! function_exists('_notification_insert')) {

    function _notification_insert(string $module, string $recipient, string $data_id, string $title, string $description, string $link)
    {
        $notif = new Notification;
        $notif->module = $module;
        $notif->recipient = $recipient;
        $notif->data_id = $data_id;
        $notif->title = $title;
        $notif->description = $description;
        $notif->link = $link;
        $notif->user_id = Auth::user()->id ?? 0;
        $notif->created_at = date("Y-m-d H:i:s");
        $notif->save();
    }
}