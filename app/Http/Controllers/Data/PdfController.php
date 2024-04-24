<?php

namespace App\Http\Controllers\Data;

use App\{User, Provider, Co_Provider, Profile_Requests, Profession,Course,
    Section,Video,Quiz,Article,Quiz_Item,Course_Progress,Handout,Instructor,Instructor_Resume, Instructional_Design,
    CompletionReport, Course_Rating, CoursePerformance, Purchase_Item,Payout,Review,Certificate, Webinar,Webinar_Series,Webinar_Session,Webinar_Rating,
    Purchase, Webinar_Progress};

use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Hash, Auth, DB};
use App\Mail\{PublicVerificationEmail, PublicResetEmail};
use App\Exports\attendanceSheet;
use Session;
use Response;
use URL;
use Excel;

class PdfController extends Controller
{
    function print_users(Request $request){
        $title = "Users List";
        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];
        $record = User::select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name,
            users.email_verified_at, users.added_by, users.status, users.id, 
            users.position, users.contact, users.email, users.role, roles.role as role_name"
        ))->leftJoin("roles", "users.role", "roles.id")->leftJoin("users as added", "users.added_by", "added.id")
            ->where('users.status', '!=', 'delete')->get();


        $records = array_map(function($user){
            return [
                $user["id"],
                $user["name"],
                $user["position"],
                $user["role_name"],
                $user["contact"],
                $user["email"],
                $user["email_verified_at"] ? "Verified" : "Not Verified",
                $user["status"],
            ];
        }, $record->toArray());

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);


        $data = array(
            "title" => $title,
            "headers" => $headers,
            "records" => $records,
        );

        $mpdf->SetTitle("Users List of ".date("Y-m-d"));
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>Users List</b></div>');
        $mpdf->WriteHTML(view('template/pdf/default', $data));
        $filename = "UserList.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_provider_users(Request $request){
        $title = "Users List";
        $headers = ["#", "Name", "Role", "Contact", "Email","Status"];
        $record = User::select("a.id", "a.name", "b.role", "a.email", "a.contact", "b.status")
        ->from("users as a")->leftJoin("co_providers as b", "a.id", "b.user_id")
        ->where('a.status', '!=', 'delete')->where('b.status', '!=', 'delete')
        ->where("b.provider_id", "=", _current_provider()->id)->get();

        $records = array_map(function($user){
            return [
                $user["id"],
                $user["name"],
                $user["role"],
                $user["contact"],
                $user["email"],
                $user["status"],
            ];
        }, $record->toArray());

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);


        $data = array(
            "title" => $title,
            "headers" => $headers,
            "records" => $records,
        );

        $mpdf->SetTitle("Users List of ".date("Y-m-d"));
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>Users List</b></div>');
        $mpdf->WriteHTML(view('template/pdf/default', $data));
        $filename = _current_provider()->id."ProviderUserList.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_provider_instructors(Request $request){
        $title = "Instructors List";
        $headers = ["#", "Name", "Contact", "Email","Status"];
        $record = User::select("a.id", "a.name", "a.email", "a.contact", "b.status")
        ->from("users as a")->leftJoin("instructors as b", "a.id", "b.user_id")
        ->where('a.status', '!=', 'delete')->where('b.status', '!=', 'delete')
        ->where("b.provider_id", "=", _current_provider()->id)->get();

        $records = array_map(function($user){
            return [
                $user["id"],
                $user["name"],
                $user["contact"],
                $user["email"],
                $user["status"],
            ];
        }, $record->toArray());

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);


        $data = array(
            "title" => $title,
            "headers" => $headers,
            "records" => $records,
        );

        $mpdf->SetTitle("Instructors List of ".date("Y-m-d"));
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>Instructors List</b></div>');
        $mpdf->WriteHTML(view('template/pdf/default', $data));
        $filename = _current_provider()->id."ProviderInstructorList.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_request(Request $request){
        $mpdf = new \Mpdf\Mpdf([
            "format" => "Letter",
            'orientation' => "P",
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);

        switch ($request->module) {
            case 'provider':
                $title = "Submitted Provider Profile Information";

                $request_profile = Profile_Requests::where([
                    "data_id" => $request->id ?? _current_provider()->id,
                    "type" => "provider",
                ])->where("status", "!=", "approved")->orderBy("created_at", "desc")->first();

                if($request_profile){
                    $professions = Profession::select("title", "cpd_requirements as cpd")
                    ->whereIn("id", json_decode($request_profile->professions))->get();
                    
                    $data = ["data"=>$request_profile, "type"=>"provider", "professions"=>$professions];
                    $filename = $request->id ?? _current_provider()->id."ProviderProfile.pdf";
                }else{
                    $request->session()->flash('warning', "Unable to produce PDF file!");
                    return redirect()->route('home');
                }

                break;

            case 'profile':
                $title = "Submitted Profile Information";
                $request_profile = Profile_Requests::where([
                    "data_id" => Auth::user()->id,
                    "type" => "instructor",
                ])->where("status", "!=", "approved")->orderBy("created_at", "desc")->first();

                if($request_profile){
                    $professions = json_decode($request_profile->professions);
                    $professions = array_map(function($pr){
                        $pro = Profession::select("title")->find($pr->id);
                        $pr->title = $pro->title;
                        return $pr;
                    }, $professions);

                    $data = ["data"=>$request_profile, "type"=>"profile", "professions"=>$professions];
            
                    $filename = Auth::user()->id."Profile.pdf";
                }else{
                    $request->session()->flash('warning', "Unable to produce PDF file!");
                    return redirect()->route('home');
                }

                break;
            
            default:
                $request->session()->flash('warning', "Unable to produce PDF file!");
                return redirect()->route('home');
                break;
        }

        $mpdf->SetTitle($title);
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>'.$title.'</b></div>');
        $mpdf->WriteHTML(view('template/pdf/profile', $data));

        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_superadmin(Request $request){
        $title = "Superadmin Users List";
        $headers = ["Name", "Email", "Permissions","Status"];
        $record = User::with("superadmin_permissions")->where("superadmin", "!=", "none")->where('status', '=', 'active')->get();


        
        
        $records = array_map(function($user){

            $permissions = "";
            foreach ($user['superadmin_permissions'] as $key => $perm) {
                if($perm['view'] && $perm['create']){
                    $permissions .= " {$perm['module_name']} ".(count($user['superadmin_permissions']) == $key+1 ? "" : " |");
                }
            }

            return [
                $user["name"],
                $user["email"],
                $permissions,
                $user["superadmin"],
            ];
        }, $record->toArray());

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);

        $data = array(
            "title" => $title,
            "headers" => $headers,
            "records" => $records,
        );

        $mpdf->SetTitle("Superadmin Users List of ".date("Y-m-d"));
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>Superadmin Users List</b></div>');
        $mpdf->WriteHTML(view('template/pdf/default', $data));
        $filename = "SuperadminUserList.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_professionals(Request $request){
        $title = "Professional Users List";
        $headers = ["Name", "Contact", "Email", "Roles", "Professions","Status", "Date Created"];
        $record = User::where("superadmin", "=", "none")->where('status', '=', 'active')->get();
        
        $records = array_map(function($user){
            $user['user_roles'] = "";

            if($user['provider_id'] != null){
                $user['user_roles'] .= "provider |";
            }

            if(Co_Provider::where("user_id", "=", $user['id'])->first()){
                $user['user_roles'] .= "co-provider |";
            }

            if($user['instructor'] != "none"){
                $user['user_roles'] .= "instructor |";
            }

            if($user['accreditor'] != "none"){
                $user['user_roles'] .= "accreditor";
            }else{
                $user['user_roles'] .= "professional";
            }

            $professions = "";
            $decoded = json_decode($user['professions']);
            foreach ($decoded as $key => $pro) {
                $profession = Profession::select("title")->find($pro->id);
                $professions .= "{$profession->title} - {$pro->prc_no} ".(count($decoded) == $key+1 ? "" : "|");
            }

            return [
                $user["name"],
                $user["contact"],
                $user["email"],
                $user['user_roles'],
                $professions,
                $user["status"],
                date("M.d,'y", strtotime($user["created_at"])),
            ];
        }, $record->toArray());

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);

        $data = array(
            "title" => $title,
            "headers" => $headers,
            "records" => $records,
        );

        $mpdf->SetTitle("Professional Users List of ".date("Y-m-d"));
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>Professional Users List</b></div>');
        $mpdf->WriteHTML(view('template/pdf/default', $data));
        $filename = "ProfessionalUserList.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_providers(Request $request){
        $title = "Provider List";
        $headers = ["Name", "Contact", "Email", "Status", "Date Created"];
        $record = Provider::where("status", "!=", "")->get();
        
        $records = array_map(function($user){
            return [
                $user["name"],
                $user["contact"],
                $user["email"],
                $user["status"],
                date("M.d,'y", strtotime($user["created_at"])),
            ];
        }, $record->toArray());

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans'
        ]);

        $data = array(
            "title" => $title,
            "headers" => $headers,
            "records" => $records,
        );

        $mpdf->SetTitle("Provider List of ".date("Y-m-d"));
        $mpdf->SetHTMLHeader('<div style="width:100%;padding:15px 0px 0px 0px;font-size:20px;"><b>Provider List</b></div>');
        $mpdf->WriteHTML(view('template/pdf/default', $data));
        $filename = "ProviderList.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_certificate(Request $request){
        $course = Course::select("providers.logo","providers.name","providers.accreditation_number",
                        "courses.title","courses.headline","courses.headline")
                        ->where("courses.id", $request->id)
                        ->leftJoin("providers","providers.id","courses.provider_id")
                        ->first();

        $provider_logo= $course->logo ?? "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/icon-1.png";
        $name = "JUAN DELA CRUZ";
        $prc_license = "4324321431";

        $title = $course->title;
        $headline = $course->headline;
        $acc_number= "52342311";

        $cpd_units = "1.5";
        $date = date("M d, Y");

        $provider_name= $course->name;
        $provider_prc_number = $course->accreditation_number;

        $qr_link = "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/users/websiteQRCode_noFrame.png";
        $verification_link = "https://www.fastcpd.com/verify/sample";
        $cert_number ="CPD-0123";

        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];

        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'baskerville' => [
                    'R' => 'BASKE9.ttf',
                    'I' => 'BASKE9.ttf',
                    'B' => 'BASKE9.ttf',
                ],
            ]
        ]);

        
        $data = array(
            "title" => $title,
            "headline" => $headline,
            "acc_number" => $acc_number,
            "headers" => $headers,
            "records" => $records,
            "provider_logo" => $provider_logo,
            "provider_name" => $provider_name,
            "provider_prc_number" => $provider_prc_number,
            "verification_link" => $verification_link,
            "cert_number" => $cert_number,
            "qr_link" => $qr_link,
            "cpd_units" => $cpd_units,
            "date" => $date,
            "name" => $name,
            "prc_license" => $prc_license
        );  

        $mpdf->SetTitle("Certificate");
        $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/certificate', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');


        $filename = "Certificate.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_webinar_certificate(Request $request){
        $course = Webinar::select("providers.logo","providers.name","providers.accreditation_number",
                        "webinars.title","webinars.headline","webinars.headline")
                        ->where("webinars.id", $request->id)
                        ->leftJoin("providers","providers.id","webinars.provider_id")
                        ->first();

        $provider_logo= $course->logo ?? "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/icon-1.png";
        $name = "JUAN DELA CRUZ";
        $prc_license = "4324321431";

        $title = $course->title;
        $headline = $course->headline;
        $acc_number= "52342311";

        $cpd_units = "1.5";
        $date = date("M d, Y");

        $provider_name= $course->name;
        $provider_prc_number = $course->accreditation_number;

        $qr_link = "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/users/websiteQRCode_noFrame.png";
        $verification_link = "https://www.fastcpd.com/verify/sample";
        $cert_number ="CPD-0123";

        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];

        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => 'L',
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'baskerville' => [
                    'R' => 'BASKE9.ttf',
                    'I' => 'BASKE9.ttf',
                    'B' => 'BASKE9.ttf',
                ],
            ]
        ]);

        
        $data = array(
            "title" => $title,
            "headline" => $headline,
            "acc_number" => $acc_number,
            "headers" => $headers,
            "records" => $records,
            "provider_logo" => $provider_logo,
            "provider_name" => $provider_name,
            "provider_prc_number" => $provider_prc_number,
            "verification_link" => $verification_link,
            "cert_number" => $cert_number,
            "qr_link" => $qr_link,
            "cpd_units" => $cpd_units,
            "date" => $date,
            "name" => $name,
            "prc_license" => $prc_license
        );  

        $mpdf->SetTitle("Certificate");
        $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/certificate', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');


        $filename = "Certificate.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }


    function print_application_form(Request $request){
        $course = Course::select("courses.profession_id", "courses.instructor_id", "providers.name", "providers.accreditation_number", "courses.session_end"
        ,"courses.session_start","courses.session_end","courses.title","courses.description","courses.objectives","courses.target_students"
        ,"courses.price","courses.submit_accreditation_evaluation","courses.provider_id","courses.pass_percentage","courses.id","courses.target_number_students","courses.expenses_breakdown"
        ,"providers.accreditation_expiration_date")
                        ->where("courses.id", $request->id)
                        ->leftJoin("providers","providers.id","courses.provider_id")
                        ->first();
        $user = User::where("provider_id",$course->provider_id)->first();
        $section_data = $this->get_sections($request->id);
        $handouts = Handout::where([
            "type" => "course",
            "data_id" => $request->id,
            "deleted_at" => null
        ])->get();
        $instructor_info= User::whereIn("id", json_decode($course->instructor_id))->get();
        $instructor_resume= Instructor_Resume::whereIn("user_id", json_decode($course->instructor_id))->get();
        $prc_logo= "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/prc_logo.png";
        $instructional_design = Instructional_Design::where([
            "type" => "course",
            "data_id" => $request->id
        ])->get();
        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];
        $data_part1 = array(
            "name_provider" => $course->name,
            "accreditation_no" => $course->accreditation_number,
            "expire_date" => $course->accreditation_expiration_date,
            "contact_person" => $user->name,
            "designation" => "Owner",
            "contact_number" => $user->contact,
            "email_add" => $user->email,
            "signature" => $user->signature,
            "date_of_application" => $course->session_start,
            "title_of_program" => $course->title,
            "date_offered" => date("M d, Y", strtotime($course->session_start )) ." to ".date("M d, Y", strtotime($course->session_end )),
            "start_date" => $course->session_start,
            "end_date" => $course->session_end,
            "duration" => "1 Year",
            "time" => _course_total_time_length($course->id),
            "place_venue" => "Online - www.fastcpd.com",
            "times_program" => "Online",
            "course_description" => _strip_design_text($course->description),
            "objectives" => json_decode($course->objectives),
            "target_perticipants" => json_decode($course->target_students),
            "registration_fee" => $course->price,
            "course_profession_id" => $course->profession_id,
            "target_number_students" => $course->target_number_students,
            "venue" => "https//www.fastcpd.com"
        );
        $accreditor_account = json_decode($course->submit_accreditation_evaluation);
        $support_docs_data = array(
            "course_id" =>  $course->id,
            "accreditor_email" => $accreditor_account->email,
            "accreditor_pass" => $accreditor_account->password,
            "expire_date" => $course->session_end,
            "sections" => $section_data['section'],
            "video_count" => $section_data['video_count'],
            "article_count" => $section_data['article_count'],
            "quiz_count" => $section_data['quiz_count'],
            "parts_count" => $section_data['parts_count'],
            "total_video_length" => $section_data['total_video_length'],
            "handout_count" => count($handouts),
            "pass_percentage" => $course->pass_percentage,
            "instructors" => $instructor_info,
            "instructor_resumes"=> $instructor_resume,
        );
        $data = array(
            "part_1" => $data_part1,
            "support_docs_data" => $support_docs_data,
            "prc_logo" => $prc_logo,
            "instructional_design" => $instructional_design
        );  
        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => 'Legal',
            "falseBoldWeight" => 9,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 5,
            'margin_bottom' => 0,
            'margin_header' => 0,
            // "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
        ]);

      

        $data = array(
            "part_1" => $data_part1,
            "support_docs_data" => $support_docs_data,
            "prc_logo" => $prc_logo,
            "instructional_design" => $instructional_design,
            "expenses" => json_decode($course->expenses_breakdown),
            "module_type" => "course"
        ); 

        $mpdf->SetTitle("Application Form, Resume and Instructional");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/application_form_portrait', $data));
        // $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');


        $filename = "Application Form, Resume and Instructional.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }


    function print_webinar_application_form(Request $request){
        $course = Webinar::select("webinars.profession_id", "webinars.instructor_id", "webinars.event", "providers.name", "providers.accreditation_number", "webinars.title","webinars.description","webinars.objectives","webinars.target_students"
        ,"webinars.prices","webinars.submit_accreditation_evaluation","webinars.provider_id","webinars.pass_percentage","webinars.id","webinars.target_number_students","webinars.expenses_breakdown")
                        ->where("webinars.id", $request->id)
                        ->leftJoin("providers","providers.id","webinars.provider_id")
                        ->first();
        $schedule_details = _schedule_details($request->id,$course->event);
        // dd($schedule_details);
        $user = User::where("provider_id",$course->provider_id)->first();
        $section_data = $this->get_webinar_sections($request->id);
        $handouts = Handout::where([
            "type" => "webinar",
            "data_id" => $request->id,
            "deleted_at" => null
        ])->get();
        $instructor_info= User::whereIn("id", json_decode($course->instructor_id))->get();
        $instructor_resume= Instructor_Resume::whereIn("user_id", json_decode($course->instructor_id))->get();
        $prc_logo= "https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/prc_logo.png";
        $instructional_design = Instructional_Design::where([
            "type" => "webinar",
            "data_id" => $request->id
        ])->get();
        $headers = ["#", "Name", "Position", "Role", "Contact", "Email", "Verified", "Status"];
        $data_part1 = array(
            "name_provider" => $course->name,
            "accreditation_no" => $course->accreditation_number,
            "expire_date" => $schedule_details['end_date'],
            "contact_person" => $user->name,
            "designation" => "Owner",
            "contact_number" => $user->contact,
            "email_add" => $user->email,
            "signature" => $user->signature,
            "date_of_application" => $schedule_details['start_date'],
            "title_of_program" => $course->title,
            "date_offered" => $schedule_details['date_to_be_offered'],
            "start_date" => $schedule_details['start_date'],
            "end_date" => $schedule_details['end_date'],
            "duration" => $schedule_details['duration'] . " days",
            "time" => $schedule_details['hours'],
            "place_venue" => "Zoom",
            "times_program" => $schedule_details['times_program_to_be_conducted'],
            "course_description" => _strip_design_text($course->description),
            "objectives" => json_decode($course->objectives),
            "target_perticipants" => json_decode($course->target_students),
            "registration_fee" => json_decode($course->prices)->with,
            "course_profession_id" => $course->profession_id,
            "target_number_students" => $course->target_number_students,
            "venue" => "Zoom"
        );
        $accreditor_account = json_decode($course->submit_accreditation_evaluation);
        $support_docs_data = array(
            "course_id" =>  $course->id,
            "accreditor_email" => $accreditor_account->email,
            "accreditor_pass" => $accreditor_account->password,
            "expire_date" => $schedule_details['end_date'],
            "sections" => $section_data['section'],
            "video_count" => $section_data['video_count'],
            "article_count" => $section_data['article_count'],
            "quiz_count" => $section_data['quiz_count'],
            "parts_count" => $section_data['parts_count'],
            "total_video_length" => $section_data['total_video_length'],
            "handout_count" => count($handouts),
            "pass_percentage" => $course->pass_percentage,
            "instructors" => $instructor_info,
            "instructor_resumes"=> $instructor_resume,
        );
        $data = array(
            "part_1" => $data_part1,
            "support_docs_data" => $support_docs_data,
            "prc_logo" => $prc_logo,
            "instructional_design" => $instructional_design
        );  
        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => 'Legal',
            "falseBoldWeight" => 9,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 5,
            'margin_bottom' => 0,
            'margin_header' => 0,
            // "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
        ]);

      

        $data = array(
            "part_1" => $data_part1,
            "support_docs_data" => $support_docs_data,
            "prc_logo" => $prc_logo,
            "instructional_design" => $instructional_design,
            "expenses" => json_decode($course->expenses_breakdown),
            "module_type" => "webinar"
        ); 

        $mpdf->SetTitle("Application Form, Resume and Instructional");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/application_form_portrait', $data));
        // $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');


        $filename = "Application Form, Resume and Instructional.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function get_webinar_sections($course){
        $sections =  Section::where([
            "type"=>"webinar", "data_id"=> $course,
            "deleted_at" => null,
        ])->get();

        $section_content_info = [];
        $section_content_rotation = [];
        $rotation = 0;
        $recurring_progress = 0;
        $video_count = 0;
        $article_count = 0;
        $quiz_count = 0;
        $hours_videos_count = 0;
        $parts_count = 0;

        foreach ($sections as $key => $section) {
            $detailed_sequence = [];
            $total_time = 0;

            if($section->sequences){
                $sequence = json_decode($section->sequences);

                foreach ($sequence as $key => $seq) {
                    switch ($seq->type) {
                        case 'video':
                            $video = Video::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $video_count = $video_count + 1;
                            if($video){ 
                                $hours_videos_count += floatval(str_replace(":", ".", $video->length));

                                $video_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "section_id" => $section->id,
                                    "type" => "video",
                                    "data_id" => $video->id,
                                    "deleted_at" => null,
                                ])->first();
                                $video_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $video->id,
                                    "type" => "video",
                                    "sequence_number"=>$video->sequence_number,
                                    "title" => $video->title,
                                    "source" => $video->cdn_url,
                                    "filename" => $video->filename,
                                    "poster" => $video->poster,
                                    "thumbnail" => array(
                                        "0"=>[
                                            "src" => $video->poster,
                                            "style" => [
                                                "width"=>"200px",
                                                "left"=>"-55px"
                                            ]
                                        ]),
                                    "current_play_time" => $video_progress->played_time ?? 0,
                                    "video_length" => floatval(str_replace(":", ".", $video->length)),
                                    "complete" => $video_progress ? ($video_progress->status=="completed" ? true : false) : false,
                                ];
                                $section_content_rotation[] = $video_data;
                                $detailed_sequence[] = $video_data;

                                $recurring_progress += ($video_progress ? ($video_progress->status=="completed" ? 1 : 0) : 0);
                            }
                            break;

                        case 'article':

                            $article = Article::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $article_count = $article_count + 1;
                            if($article){
                                $total_time += floatval($article->reading_time);

                                $article_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "section_id" => $section->id,
                                    "type" => "article",
                                    "data_id" => $article->id,
                                    "deleted_at" => null,
                                ])->first();
                                $article_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $article->id,
                                    "sequence_number" => $article->sequence_number,
                                    "type" => "article",
                                    "title" => $article->title,
                                    "body" => $article->description,
                                    "reading_time" => floatval($article->reading_time),
                                    "complete" =>  $article_progress ? ($article_progress->status=="completed" ? true : false) : false,
                                ];
                                $section_content_rotation[] = $article_data;
                                $detailed_sequence[] = $article_data;

                                $recurring_progress += ($article_progress ? ($article_progress->status=="completed" ? 1 : 0) : 0);
                            }
                            break;

                        case 'quiz':

                            $quiz = Quiz::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $quiz_count = $quiz_count + 1;
                            if($quiz){ 
                                $quiz_items = Quiz_Item::select("id", "question", "choices", "answer")->where([
                                    "quiz_id" => $quiz->id,
                                    "deleted_at" => null,
                                ])->get()->toArray();

                                if(count($quiz_items) > 0 ){
                                    $quiz_progress = Course_Progress::where([
                                        "course_id" => $section->data_id,
                                        "section_id" => $section->id,
                                        "type" => "quiz",
                                        "data_id" => $quiz->id,
                                        "deleted_at" => null,
                                    ])->first();
                                    $quiz_data = [
                                        "section_id" => $section->id,
                                        "rotation" => $rotation,
                                        "id" => $quiz->id,
                                        "type" => "quiz",
                                        "sequence_number"=>$quiz->sequence_number,
                                        "title" => $quiz->title,
                                        "reading_time" => 0,
                                        "items" => $quiz_items,
                                        "complete" => $quiz_progress ? ($quiz_progress->status == "passed" ? true : false) : false,
                                        "status" => $quiz_progress->status ?? "none", // in-progress, completed, failed, passed
                                        "overall" => $quiz_progress->quiz_overall ?? [],
                                    ];
                                    $section_content_rotation[] = $quiz_data;
                                    $detailed_sequence[] = $quiz_data;

                                    $recurring_progress += ($quiz_progress ? ($quiz_progress->status=="passed" ? 1 : 0) : 0);
                                }
                            }
                            break;
                    }

                    $rotation++;
                }
                $parts_count += count($detailed_sequence);
                $section_content_info[] = [
                    "id" => $section->id,
                    "title" => $section->name,
                    "number"=> $section->section_number,
                    "section_number"=> $section->sequence_number,
                    "objective"=>$section->objective,
                    "total_time" =>  number_format($total_time, 2, '.', ','),
                    "parts" => $detailed_sequence,
                    "complete" => false,
                ];
            }
        }
        return $data = array(
            "section" => $section_content_info,
            "video_count" => $video_count,
            "article_count" => $article_count,
            "quiz_count" => $quiz_count,
            "total_video_length" => $hours_videos_count,
            "parts_count"=> $parts_count
        );
    }

    function get_sections($course){
        $sections =  Section::where([
            "type"=>"course", "data_id"=> $course,
            "deleted_at" => null,
        ])->get();

        $section_content_info = [];
        $section_content_rotation = [];
        $rotation = 0;
        $recurring_progress = 0;
        $video_count = 0;
        $article_count = 0;
        $quiz_count = 0;
        $hours_videos_count = 0;
        $parts_count = 0;

        foreach ($sections as $key => $section) {
            $detailed_sequence = [];
            $total_time = 0;

            if($section->sequences){
                $sequence = json_decode($section->sequences);

                foreach ($sequence as $key => $seq) {
                    switch ($seq->type) {
                        case 'video':
                            $video = Video::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $video_count = $video_count + 1;
                            if($video){ 
                                $hours_videos_count += floatval(str_replace(":", ".", $video->length));

                                $video_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "section_id" => $section->id,
                                    "type" => "video",
                                    "data_id" => $video->id,
                                    "deleted_at" => null,
                                ])->first();
                                $video_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $video->id,
                                    "type" => "video",
                                    "sequence_number"=>$video->sequence_number,
                                    "title" => $video->title,
                                    "source" => $video->cdn_url,
                                    "filename" => $video->filename,
                                    "poster" => $video->poster,
                                    "thumbnail" => array(
                                        "0"=>[
                                            "src" => $video->poster,
                                            "style" => [
                                                "width"=>"200px",
                                                "left"=>"-55px"
                                            ]
                                        ]),
                                    "current_play_time" => $video_progress->played_time ?? 0,
                                    "video_length" => floatval(str_replace(":", ".", $video->length)),
                                    "complete" => $video_progress ? ($video_progress->status=="completed" ? true : false) : false,
                                ];
                                $section_content_rotation[] = $video_data;
                                $detailed_sequence[] = $video_data;

                                $recurring_progress += ($video_progress ? ($video_progress->status=="completed" ? 1 : 0) : 0);
                            }
                            break;

                        case 'article':

                            $article = Article::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $article_count = $article_count + 1;
                            if($article){
                                $total_time += floatval($article->reading_time);

                                $article_progress = Course_Progress::where([
                                    "course_id" => $section->data_id,
                                    "section_id" => $section->id,
                                    "type" => "article",
                                    "data_id" => $article->id,
                                    "deleted_at" => null,
                                ])->first();
                                $article_data = [
                                    "section_id" => $section->id,
                                    "rotation" => $rotation,
                                    "id" => $article->id,
                                    "sequence_number" => $article->sequence_number,
                                    "type" => "article",
                                    "title" => $article->title,
                                    "body" => $article->description,
                                    "reading_time" => floatval($article->reading_time),
                                    "complete" =>  $article_progress ? ($article_progress->status=="completed" ? true : false) : false,
                                ];
                                $section_content_rotation[] = $article_data;
                                $detailed_sequence[] = $article_data;

                                $recurring_progress += ($article_progress ? ($article_progress->status=="completed" ? 1 : 0) : 0);
                            }
                            break;

                        case 'quiz':

                            $quiz = Quiz::where([
                                "id"=>$seq->id, 
                                "section_id"=>$section->id, 
                                "deleted_at"=>null,
                            ])->first();
                            $quiz_count = $quiz_count + 1;
                            if($quiz){ 
                                $quiz_items = Quiz_Item::select("id", "question", "choices", "answer")->where([
                                    "quiz_id" => $quiz->id,
                                    "deleted_at" => null,
                                ])->get()->toArray();

                                if(count($quiz_items) > 0 ){
                                    $quiz_progress = Course_Progress::where([
                                        "course_id" => $section->data_id,
                                        "section_id" => $section->id,
                                        "type" => "quiz",
                                        "data_id" => $quiz->id,
                                        "deleted_at" => null,
                                    ])->first();
                                    $quiz_data = [
                                        "section_id" => $section->id,
                                        "rotation" => $rotation,
                                        "id" => $quiz->id,
                                        "type" => "quiz",
                                        "sequence_number"=>$quiz->sequence_number,
                                        "title" => $quiz->title,
                                        "reading_time" => 0,
                                        "items" => $quiz_items,
                                        "complete" => $quiz_progress ? ($quiz_progress->status == "passed" ? true : false) : false,
                                        "status" => $quiz_progress->status ?? "none", // in-progress, completed, failed, passed
                                        "overall" => $quiz_progress->quiz_overall ?? [],
                                    ];
                                    $section_content_rotation[] = $quiz_data;
                                    $detailed_sequence[] = $quiz_data;

                                    $recurring_progress += ($quiz_progress ? ($quiz_progress->status=="passed" ? 1 : 0) : 0);
                                }
                            }
                            break;
                    }

                    $rotation++;
                }
                $parts_count += count($detailed_sequence);
                $section_content_info[] = [
                    "id" => $section->id,
                    "title" => $section->name,
                    "number"=> $section->section_number,
                    "section_number"=> $section->sequence_number,
                    "objective"=>$section->objective,
                    "total_time" =>  number_format($total_time, 2, '.', ','),
                    "parts" => $detailed_sequence,
                    "complete" => false,
                ];
            }
        }
        return $data = array(
            "section" => $section_content_info,
            "video_count" => $video_count,
            "article_count" => $article_count,
            "quiz_count" => $quiz_count,
            "total_video_length" => $hours_videos_count,
            "parts_count"=> $parts_count
        );
    }

    function print_completion_report(Request $request){
     
        $completion_rep = CompletionReport::select(
            DB::raw("completion_reports.data_id as data_id"),
            DB::raw("completion_reports.type as type"),
            DB::raw("completion_reports.provider_id as provider_id"),
            DB::raw("courses.title as course_title"),
            DB::raw("courses.url as url"),
            DB::raw("courses.profession_id as profession_id"),
            DB::raw("courses.instructor_id as instructor_id"),
            DB::raw("completion_reports.created_at as created_at"),
            "completion_reports.completion_date as completion_date",
            "completion_reports.participants as participants"
        )
        ->where("completion_reports.id",$request->recordID)
        ->leftJoin("courses","courses.id","completion_reports.data_id")->first(); 

        $professions = Profession::select("title")
            ->whereIn("id",json_decode($completion_rep->profession_id))->get();

        $providers = Provider::select(
            "users.name as fullname","users.signature",
            "providers.name as provider_name",
            "providers.accreditation_number as provider_accreditation_no",
            "providers.contact as provider_contact_no",
            "providers.accreditation_expiration_date as provider_accreditation_expiration"
        )
        ->where("providers.id",$completion_rep->provider_id)
        ->leftJoin("users","users.provider_id","providers.id")->first();
                            
        $courses = Course::where("id",$completion_rep->data_id)->first();
        $total_applicants = Purchase_Item::select(
            DB::raw("count(id) as applicant_count")
        )->where("data_id",$completion_rep->data_id)
        ->where("fast_status","complete")
        ->whereMonth("purchase_items.updated_at",date("m",strtotime($completion_rep->completion_date)))
        ->whereYear("purchase_items.updated_at",date("Y",strtotime($completion_rep->completion_date)))
        ->first();
            
        $total_handouts = Handout::select(
                            DB::raw("count(id) as total_handouts")
                        )->where([
                            "type" => "course",
                            "data_id" => $completion_rep->data_id,
                            "deleted_at" => null
                        ])->first();
        $section_details = Section::where(["type"=>"course", "data_id"=>$completion_rep->data_id,"deleted_at" => null])->get();
        $total_quizzes = 0;
        $sec_details = array();
        $total_video_length = 0;
        $hour = 0;
        $minutes = 0;
        $seconds = 0;
        foreach($section_details as $section){
            $videos_count = Video::select(DB::raw("count(id) as videos_count"))->where("section_id",$section->id)->where("deleted_at",null)->first();
            $quiz = Quiz::select(DB::raw("count(id) as quizzes_count"))->where("section_id",$section->id)->where("deleted_at",null)->first();
            //$quizzes_count = Quiz_Item::select(DB::raw("count(id) as quizzes_count"))->where("quiz_id",$quiz->id)->first();
            $articles_count = Article::select(DB::raw("count(id) as articles_count"))->where("section_id",$section->id)->where("deleted_at",null)->first();
            $video_lengths = Video::select("length")->where("section_id",$section->id)->where("deleted_at",null)->get();
            foreach($video_lengths as $vid_length){
                if($vid_length->length != null && $vid_length->length != "" ){
                    $explode_length = explode(':',$vid_length->length);
                    $time_ = count($explode_length);
                    
                    if($time_ == 3){
                        $hour = $hour + $explode_length[0];
                        $minutes = $minutes + $explode_length[1];
                        $seconds = $seconds + $explode_length[2];
                    }else{
                        $minutes = $minutes + ($explode_length[0] ?? 0);
                        $seconds = $seconds +( $explode_length[1] ?? 0);
                    }
                }else{
                    $minutes += 0;
                    $seconds += 0;
                }
            }
            // $time = _course_total_time_length($section_id);
            array_push($sec_details,[
                "section_name" => $section->name,
                "videos_count" => $videos_count->videos_count,
                "quizzes_count" => $quiz->quizzes_count,
                "articles_count" => $articles_count->articles_count,
            ]);
            $total_quizzes += $quiz->quizzes_count;

        }
        $total_minutes = $minutes + ($seconds/60);
        $total_hours = $hour + ($total_minutes/60);
        $total_minutes += ($hour * 60);
       // $total_minutes = fmod($total_minutes,60);

        $instructors = Instructor::select(
                                    "users.name as instructor_name",
                                    "users.professions as prc_no"
                                )
                                ->whereIn("instructors.user_id",json_decode($courses->instructor_id))
                                ->leftJoin("users","users.id","instructors.user_id")->get();

        $participants = Purchase_Item::select(
            "users.id as participant_id","users.name as pariticipant_name","users.professions as prc_no","users.contact as contact_no","users.signature","users.email as email","purchase_items.data_id as data_id"
        )
        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
        ->leftJoin("users","users.id","purchase_items.user_id")
        ->where("purchase_items.data_id",$completion_rep->data_id)
        ->where("purchases.payment_status","paid")
        ->whereMonth("purchases.payment_at",date("m",strtotime($completion_rep->completion_date)))
        ->whereYear("purchases.payment_at",date("Y",strtotime($completion_rep->completion_date)))->get();

        $participant_att_quiz = Purchase_Item::select(
            "users.id as participant_id","users.name as pariticipant_name","users.professions as prc_no","users.contact as contact_no","users.signature","users.email as email","purchase_items.data_id as data_id"
        )
        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
        ->leftJoin("users","users.id","purchase_items.user_id")
        ->where("purchase_items.data_id",$completion_rep->data_id)
        ->where("purchases.payment_status","paid")
        ->whereMonth("purchase_items.updated_at",date("m",strtotime($completion_rep->completion_date)))
        ->whereYear("purchase_items.updated_at",date("Y",strtotime($completion_rep->completion_date)))
        ->where("purchase_items.fast_status","complete")->get();

        $partipants_with_quiz = array_map(function($participant) use($request){
            $total_scores = 0;
            $total_number_items = 0;
            $sections = Section::where(["type"=>"course", "data_id"=>$participant["data_id"],"deleted_at" => null])->get();
            foreach($sections as $section){
                $quiz_progress = Course_Progress::select("quiz_overall as overall")->where([
                    "section_id"=>$section->id,
                    "course_id"=>$participant["data_id"],
                    "user_id"=>$participant["participant_id"],
                    "type"=>"quiz",
                    "deleted_at" => null,
                ])->whereIn("status", ["completed", "passed"])->get();

                foreach($quiz_progress as $quiz){
                    if($quiz && $quiz->overall){
                        $overall = json_decode($quiz->overall);
                        $total_scores += $overall->total;
                        $total_number_items += $overall->items;
                    }
                }
            }

            $percentage = $total_scores != 0 ? (($total_scores / $total_number_items) * 100) : 0;

            return[
                "participant_id" => $participant["participant_id"],
                "pariticipant_name" => $participant["pariticipant_name"],
                "signature" => $participant["signature"],
                "prc_no" => $participant["prc_no"],
                "course_id" => $participant["data_id"],
                "score_percentage" => number_format($percentage,2,".",",")
            ];
           
        },$participant_att_quiz->toArray());

      
        $course_performances = Course_Rating::select(
            "course_ratings.id as course_rating_id",
            "users.name as username_feedback",
            "users.professions as prc_no",
            "course_ratings.rating as course_rating",
            "course_ratings.remarks as course_remark",
            "course_performances.valuable_information",
            "course_performances.concepts_clear",
            "course_performances.instructor_delivery",
            "course_performances.opportunities",
            "course_performances.expectations",
            "course_performances.knowledgeable"
        )
        ->leftJoin("course_performances", function($join) use($completion_rep){
            $join->on("course_performances.user_id","=","course_ratings.user_id");
            $join->on("course_performances.course_id","=",DB::raw($completion_rep->data_id));
        })
        ->leftJoin("users","users.id","course_ratings.user_id")
        ->where("course_ratings.course_id", $completion_rep->data_id)
        ->where("course_performances.course_id", $completion_rep->data_id)

        ->whereMonth("course_ratings.created_at",date("m",strtotime($completion_rep->completion_date)))
        ->whereYear("course_ratings.created_at",date("Y",strtotime($completion_rep->completion_date)))
        ->groupBy("course_ratings.user_id")
        ->get();

        $sections_list = Section::where(["type"=>"course", "data_id"=>$completion_rep->data_id,"deleted_at" => null])
                                ->orderBy("section_number","asc")->get();
        $section_content = array();
        foreach($sections_list as $section){
            $part_content = array();
            foreach(json_decode($section->sequences) as $sequence){
                switch($sequence->type){
                    case "video": 
                        $part_title = Video::select("title")->where("id",$sequence->id)->where("deleted_at",null)->first();
                        $part_title = $part_title->title;
                    break;
                    case "quiz": 
                        $part_title = Quiz::select("title")->where("id",$sequence->id)->where("deleted_at",null)->first();
                        $part_title = $part_title->title;
                    break;
                    case "article": 
                        $part_title = Article::select("title")->where("id",$sequence->id)->where("deleted_at",null)->first();
                        $part_title = $part_title->title;
                    break;
                    default: 
                        $part_title = "None";
                    break;
                }
                array_push($part_content,[
                    "part_number" => $sequence->part,
                    "part_title" => $part_title,
                    "part_type" => $sequence->type
                ]);
            }
            array_push($section_content,[
               $section->name => $part_content
            ]);
        }
       
        $data = array(
            "completion_rep" => $completion_rep,
            "professions" => $professions,
            "general_info" => $providers,
            "courses" => $courses,
            "total_applicants" => $total_applicants,
            "total_handouts" => $total_handouts,
            "total_quizzes" => $total_quizzes,
            "section_details" => $sec_details,
            "instructors" => $instructors,
            "participants" => $partipants_with_quiz,
            "course_performances" => $course_performances,
            "section_content" => $section_content,
            "total_minutes" => $total_minutes,
            "total_hours" => $total_hours,
            "attendance" => $participant_att_quiz,
            "registration" => $participants
        ); 
     
        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]); 

        $mpdf->SetTitle("Completion-Report-".date("F-Y",strtotime($completion_rep->completion_date)));
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/provider/completion_report', $data));
        $mpdf->AddPage('L','','','','',5,8,10,5,5,0);
        $mpdf->WriteHTML(view('template/provider/completion_report_tables', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');

        $filename = "Completion-Report-".date("F-Y",strtotime($completion_rep->completion_date)).".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }

    function print_revenue(Request $request){
        $provider_id = _current_provider();
        $revenues = Payout::where("data_id",$provider_id->id)->where("type","provider")->orderBy("date_to","asc")->get();
        $provider = Provider::where("id",$provider_id->id)->first();

        $data = [
            "revenues" => $revenues,
            "provider" => $provider
        ];

        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle($provider->name." Revenue");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/revenue', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');


        $filename = str_replace("/", "-", $provider->name); 
        $filename = preg_replace('/\s+/', '',  $filename);
        $filename = $filename.".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }

    }

    function print_revenue_month(Request $request){
     
        $provider_id = _current_provider();
       
        $current_purchase = Purchase_Item::select(
                            "purchase_items.id as purchase_id","purchase_items.created_at as purchase_date",
                            "purchases.updated_at as payment_date","users.name as customer",
                            "courses.title as course","purchase_items.voucher as coupon_code","purchase_items.price as original_price",
                            "purchase_items.channel as channel","purchase_items.total_amount as price_paid",
                            "purchase_items.discount as discount","purchase_items.fast_revenue as fast_revenue",
                            "purchase_items.provider_revenue as provider_revenue"
                        )
                        ->whereMonth("purchases.updated_at",date("m"))
                        ->whereYear("purchases.updated_at",date("Y"))
                        ->where("purchases.payment_status","paid")
                        //->where("purchase_items.fast_status","complete")
                        ->where("providers.id", $provider_id->id)
                        ->leftJoin("purchases","purchase_items.purchase_id","purchases.id")
                        ->leftJoin("courses","courses.id","purchase_items.data_id")
                        ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                        ->leftJoin("users","users.id","purchase_items.user_id")
                        ->leftJoin("providers",function($join){
                            $join->on("providers.id","webinars.provider_id");
                            $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                            $join->orOn("providers.id","courses.provider_id");
                            $join->on("purchase_items.type","=",DB::raw("'course'"));
                        })
                        ->get();
                        
        $provider = Provider::where("id",$provider_id->id)->first();

        $data = [
            "revenues" => $current_purchase,
            "provider" => $provider
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' =>$request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle($provider->name." Revenue");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/revenue_monthly', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');

        $filename = str_replace("/", "-", $provider->name); 
        $filename = preg_replace('/\s+/', '',  $filename);
        $filename = $filename.".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }

    }

    function print_rating(Request $request){
     
       
        $provider_id = _current_provider();
        $get_reviews = Course_Rating::select("course_ratings.id as review_id",
                                        "course_ratings.created_at as feedback_date",
                                        "courses.title as course",
                                        "users.name as customer",
                                        "course_ratings.rating as rating",
                                        "course_ratings.remarks as feedback"
                                    )->where("courses.provider_id",$provider_id->id)
                                    ->whereMonth("course_ratings.created_at",date("m"))
                                    ->whereYear("course_ratings.created_at",date("Y"))
                                    ->leftJoin("courses","courses.id","course_ratings.course_id")
                                    ->leftJoin("users","users.id","course_ratings.user_id")->get();
        $provider = Provider::where("id",$provider_id->id)->first();

        $data = [
            "reviews" => $get_reviews,
            "provider" => $provider
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle($provider->name."-Reviews-".date("F-Y"));
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/reviews', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');

        $filename = str_replace("/", "-", $provider->name); 
        $filename = preg_replace('/\s+/', '',  $filename);
       
        $filename = $filename."-Reviews-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }


    }

    function print_user_certificate(Request $request){
        $proceed = false;

        if("verify" == $request->segment(1)){
            $certificate_code = $request->segment(2);
        }else{
            $certificate_code = $request->certificate_code;
        }

        $certificate = Certificate::select(
            "id", "certificate_code", "type", "data_id", "user_id", "created_at", "url", "qr_link"
        )->where("certificate_code",$certificate_code)->first();
        if(Auth::check()){
            if($certificate){
                $user_id = $certificate->user_id;
                if($certificate->type=="webinar"){
                    $proceed=true;
                }else{
                    if(( $profession_user = Auth::user()->professions) && Auth::user()->contact){
                        foreach(json_decode($profession_user) as $pruser){
                            if($pruser->prc_no && $pruser->expiration_date){
                                $proceed=true;
                            }
                        }
                    }
                }
            }
        }

        if(!$proceed){
            Session::flash("info", "Please submit your profession with license and expiration date & contact number before generating your certificate! Thank you");
            return redirect("/profile/personal");
        }

        if($certificate->type=="course"){
            $data_record = Course::find($certificate->data_id);
        }else{
            $data_record = Webinar::find($certificate->data_id);
        }

        if($data_record->accreditation){
            $data_record->accreditation = array_map(function($acc){
                $acc->profession = _get_profession($acc->id)->profession;
                return $acc;
            }, json_decode($data_record->accreditation));
        }

        $provider = Provider::select(
            "providers.id", "providers.url", "providers.name", "providers.accreditation_number", "providers.logo", "users.signature"
        )->leftJoin("users", "users.id", "=", "providers.created_by")->where("providers.id", "=", $data_record->provider_id)->first();

        $user_data = User::select(
            "id", "username", "name", "professions"
        )->find($certificate->user_id);
        
        $mpdf = new \Mpdf\Mpdf([
            "format" =>"A4",
            'orientation' =>  "L",
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'baskerville' => [
                    'R' => 'BASKE9.ttf',
                    'I' => 'BASKE9.ttf',
                    'B' => 'BASKE9.ttf',
                ],
            ]
        ]);
       
        $data = [
            "verification_link" => config('app.link')."/verify/".$certificate_code,
            "certificate" => $certificate,
            "provider" => $provider,
            "data" => $data_record,
            "user" => $user_data,
        ];

        $mpdf->SetTitle("Certificate");
        $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/certificate/dynamic', $data));

        $filename = "{$user_data->username}-{$data_record->id}-{$certificate->type}.pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }
    function attendance_sheet(Request $request){
        //dd($request->recordID);
        $completion = CompletionReport::where("id",$request->recordID)->first();
        if(!isset($request->webinar)){
            if($completion->type == "course"){
                $title = Course::where("id",$completion->data_id)->first();
                $provider = Provider::where("id",$completion->provider_id)->first();
            }else{
                $title = Webinar::where("id",$completion->data_id)->first();
                $provider = Provider::where("id",$completion->provider_id)->first();
            }
            return Excel::download(new attendanceSheet($request->recordID), $title->title.' - Attendance Sheet - '.date("F-Y",strtotime($completion->completion_date)).'.xlsx');
        }else{
            $title = Webinar::where("id",$request->recordID)->first();
            $provider = Provider::where("id",_current_provider()->id)->first();

            $webinar_sessions = Webinar_Session::where("webinar_id",$request->recordID)->first();
            return Excel::download(new attendanceSheet($request->recordID), $title->title.' - Attendance Sheet - '.date("F-Y",strtotime($webinar_sessions->session_date)).'.xlsx');
        }
        
        
       
    }
    function print_webinar_rating(Request $request){
        $provider_id = _current_provider();
        $get_reviews = Webinar_Rating::select("webinar_ratings.id as review_id",
                                        "webinar_ratings.created_at as feedback_date",
                                        "webinars.title as webinar",
                                        "users.name as customer",
                                        "webinar_ratings.rating as rating",
                                        "webinar_ratings.remarks as feedback"
                                    )->where("webinars.provider_id",$provider_id->id)
                                    ->whereMonth("webinar_ratings.created_at",date("m"))
                                    ->whereYear("webinar_ratings.created_at",date("Y"))
                                    ->leftJoin("webinars","webinars.id","webinar_ratings.webinar_id")
                                    ->leftJoin("users","users.id","webinar_ratings.user_id")->get();
        $provider = Provider::where("id",$provider_id->id)->first();

        $data = [
            "reviews" => $get_reviews,
            "provider" => $provider,
            "type" => "Webinar"
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle($provider->name."-Webinar Reviews-".date("F-Y"));
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/reviews', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');

        $filename = str_replace("/", "-", $provider->name); 
        $filename = preg_replace('/\s+/', '',  $filename);
       
        $filename = $filename."-Reviews-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }


    }
    function print_courses_report(Request $request){
        $type = $request->segment(6);
        $providers = $request->segment(7);

        $provider = explode(",",$providers);
        if($provider[0] == 0){
            if($type == "video-on-demand"){
                $courses = Provider::select(
                                "providers.name as provider_name",
                                "providers.id as provider_id",
                                "courses.title as course_title",
                                "courses.published_at as course_published_at",
                                "courses.session_start as course_session_start",
                                "courses.price as course_price",
                                "courses.id as course_id",
                                "courses.accreditation as accreditation",
                                "courses.instructor_id as course_instructor"
                            )
                            ->where("courses.fast_cpd_status","live")->orWhere("courses.fast_cpd_status","published")
                            ->leftJoin("courses","providers.id","courses.provider_id");
                           
                            $provider_courses = $courses->get();
                            $data = $this->video_demand_filter($provider_courses);
            }else{
                $courses = Provider::select(
                                "providers.name as provider_name",
                                "providers.id as provider_id",
                                "webinars.title as course_title",
                                "webinars.published_at as course_published_at",
                                "webinars.prices as course_price",
                                "webinars.id as course_id",
                                "webinars.accreditation as accreditation",
                                "webinars.instructor_id as course_instructor"
                            )
                            ->where("webinars.fast_cpd_status","live")->orWhere("webinars.fast_cpd_status","published")
                            ->leftJoin("webinars","providers.id","webinars.provider_id");
                          
                            $provider_courses = $courses->get();

                            $data = $this->webinar_filter($provider_courses);           
            }
        }else{
            
            if($type == "video-on-demand"){
                $courses = Provider::select(
                                "providers.name as provider_name",
                                "providers.id as provider_id",
                                "courses.title as course_title",
                                "courses.published_at as course_published_at",
                                "courses.session_start as course_session_start",
                                "courses.price as course_price",
                                "courses.id as course_id",
                                "courses.accreditation as accreditation",
                                "courses.instructor_id as course_instructor"
                            )
                            ->whereIn("providers.id",$provider)
                            ->where(function($query){
                                $query->where("courses.fast_cpd_status","live")->orWhere("courses.fast_cpd_status","published");
                            })
                            ->leftJoin("courses","providers.id","courses.provider_id");
                          
                            $provider_courses = $courses->get();
                            $data = $this->video_demand_filter($provider_courses);
            }else{
                $courses = Provider::select(
                                "providers.name as provider_name",
                                "providers.id as provider_id",
                                "webinars.title as course_title",
                                "webinars.published_at as course_published_at",
                                "webinars.prices as course_price",
                                "webinars.id as course_id",
                                "webinars.accreditation as accreditation",
                                "webinars.instructor_id as course_instructor"
                            )
                            ->whereIn("providers.id",$provider)
                            ->where(function($query){
                                $query->where("webinars.fast_cpd_status","live")->orWhere("webinars.fast_cpd_status","published");
                            })
                            ->leftJoin("webinars","providers.id","webinars.provider_id");
                           
                            $provider_courses = $courses->get();

                            $data = $this->webinar_filter($provider_courses);   
            }
        }

        $data = [
            "data" => $data,
            "type" => $type
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle("-Reports Courses-");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/superadmin/reports/courses', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');
       
        $filename ="-Reports Courses-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("course.index");
        }
    }
    function print_purchases_report(Request $request){
        $start_date = date("Y-m-d",strtotime($request->segment(6)));
        $end_date = date("Y-m-d",strtotime($request->segment(7)));
        $methods = $request->segment(8);

        $method = explode(",",$methods);
   
        if($method[0] == 0){
            $purchases = Purchase::select("purchases.id as purchase_id","purchases.reference_number as transaction_code","purchases.total_amount as purchased_price",
                                            "purchases.created_at as purchased_date","purchases.updated_at as payment_date",
                                            "purchases.total_discount as discount","purchases.payment_method as payment_method","users.name as purchased_by")
                                ->whereDate("purchases.updated_at",">=",$start_date)->whereDate("purchases.updated_at","<=",$end_date)
                                ->where("purchases.payment_status","paid")
                                ->leftJoin("users","users.id","purchases.user_id");
        }else{
            $methods = array();
            foreach($method as $payment_method)
            {
                if($payment_method == "1"){
                    $arr = array("card");
                   $methods = array_merge($methods,$arr);
                }
                if($payment_method == "2"){
                    $arr = array("gcash","grab_pay");
                    $methods = array_merge($methods,$arr);
                }
                if($payment_method == "3"){
                    $arr = array("bdo","bpib","cbc","lbpa","mayb","mbtc","psb","rcbc","rsb","ubpb","ucpb");
                    $methods = array_merge($methods,$arr);
                }
                if($payment_method == "4"){
                    $arr = array("aub","bdrx","cbcx","ewxb","lbxb","mbxb","pnxb","sbcb","rcxb","rsbb","ubxb","ucxb");
                    $methods = array_merge($methods,$arr);
                }
                if($payment_method == "5"){
                    $arr = array("smr","bayd","cebl","mlh");
                    $methods = array_merge($methods,$arr);
                }
            }
            $purchases = Purchase::select("purchases.id as purchase_id","purchases.reference_number as transaction_code","purchases.total_amount as purchased_price",
                                                "purchases.created_at as purchased_date","purchases.updated_at as payment_date",
                                                "purchases.total_discount as discount","purchases.payment_method as payment_method","users.name as purchased_by")
                                    ->whereDate("purchases.updated_at",">=",$start_date)->whereDate("purchases.updated_at","<=",$end_date)
                                    ->whereIn("purchases.payment_method",$methods)
                                    ->where("purchases.payment_status","paid")
                                    ->leftJoin("users","users.id","purchases.user_id");
        }
        $purchases_ = $purchases->get();

        $data = array_map(function($purchase){

            $purchase_items = Purchase_Item::select(DB::raw("sum(price) as original_price"),DB::raw("count(id) as items"))->where("purchase_id",$purchase['purchase_id'])->first();
    
            return[
                "id" => $purchase['purchase_id'],
                "transaction_code" => $purchase['transaction_code'],
                "purchased_by" => $purchase['purchased_by'],
                "purchased_date" => date("M. d, 'y",strtotime($purchase['purchased_date'])),
                "payment_date" => date("M. d, 'y", strtotime($purchase['payment_date'])),
                "original_price" => $purchase_items->original_price,
                "purchased_price" => $purchase['purchased_price'],
                "payment_method" => $purchase["payment_method"],
                "items" => $purchase_items->items,
            ];
        },$purchases_->toArray());

        $data = [
            "data" => $data,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "methods" => $method
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle("-Reports Purchases-");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/superadmin/reports/purchases', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');
       
        $filename ="-Reports Purchases-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("purchase.index");
        }

    }
    function print_payouts_report(Request $request){
        $months = $request->segment(6);
        $year =$request->segment(7);
        $user_types = $request->segment(8);

        $user_type = explode(",",$user_types);
        $month = explode(",",$months);
       
        if($user_type[0] =="0"){
            
            $payouts = Payout::select("payouts.id as payoutID","providers.name as provider_name","users.name as full_name","payouts.notes",
                                "payouts.status",
                                "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                                "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
                                ->whereIn(DB::raw("MONTH(date_to)"),$month)
                                ->where(DB::raw("YEAR(date_to)"),$year)
                                ->leftJoin("providers","providers.id","payouts.data_id")
                                ->leftJoin("users","users.provider_id","providers.id");
                               
                             
        }else{
            $payouts = Payout::select("payouts.id as payoutID","providers.name as provider_name","users.name as full_name","payouts.notes",
                                "payouts.status",
                                "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                                "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
                                DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
                            ->whereIn(DB::raw("MONTH(date_to)"),$month)
                            ->where(DB::raw("YEAR(date_to)"),$year)
                            ->whereIn("payouts.type",$user_type)
                            ->leftJoin("providers","providers.id","payouts.data_id")
                            ->leftJoin("users","users.provider_id","providers.id");
        }
        $payouts = $payouts->get();

        $data= array_map(function($payout) use($request){  
          
            $month_year = date("F Y",strtotime($payout['date_from']."+1 month"));
          
            $expected_date =  date("F j, Y",strtotime($month_year."first friday"));
            if($payout["type"] == "provider"){
                $amount = $payout['provider_revenue'];
                $company_name = $payout['provider_name'];
                $fullname = $payout['full_name'];
            }else if($payout["type"] == "fast"){
                $amount = $payout['fast_revenue'];
                $company_name = "Fast CPD";
                $fullname = "Fast CPD";
            }else{
                $amount = $payout['promoter_revenue'];
                $company_name = "Promoter";
                $fullname = "Promoter";
            }
                $data = [
                    "id" => uniqid(),
                    "payout_id" => $payout['payoutID'],
                    "provider_id" => $payout['data_id'],
                    "month" => date("F",strtotime($payout['date_to'])),
                    "year" => date("Y",strtotime($payout['date_to'])),
                    "type" =>ucwords($payout["type"]),
                    "provider_name" =>$company_name,
                    "fast_cpd" => "Fast CPD",
                    "full_name" => $fullname,
                    "amount" => $amount,
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("F j, Y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "status" => $payout['status'],
                    "notes" => $payout['notes'],
    
                ];
         
            return $data;
        },$payouts->toArray());

        $data = [
            "data" => $data
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle("-Reports Payouts-");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/superadmin/reports/payouts', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');
       
        $filename ="-Reports Payouts-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("payout.index");
        }
    }
    function print_purchase_items_report(Request $request){
        $start_date = date("Y-m-d",strtotime($request->segment(6)));
        $end_date = date("Y-m-d",strtotime($request->segment(7)));
        $providers = $request->segment(8);
       
        if($providers[0] == 0 || $providers == "0"){
            $purchase_items = Purchase_Item::select(
                                                "providers.name as provider_name","providers.id as provider_id","courses.title as course_name","webinars.title as webinar_name",
                                                "purchase_items.channel","purchase_items.price as original_price","purchase_items.total_amount as price_paid",
                                                "purchase_items.provider_revenue","purchase_items.fast_revenue","purchase_items.promoter_revenue","purchase_items.id as itemID",
                                                "purchase_items.type","purchase_items.voucher as coupon_code","purchase_items.discount","purchases.reference_number as transaction_code","users.name as purchased_by",
                                                "purchase_items.updated_at as purchased_date"
                                            )
                                            ->where("purchase_items.payment_status","paid")
                                            ->whereDate("purchase_items.updated_at",">=",$start_date)->whereDate("purchase_items.updated_at","<=",$end_date)
                                            ->leftJoin("users","users.id","purchase_items.user_id")
                                            ->leftJoin("courses","courses.id","purchase_items.data_id")
                                            ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                                            ->leftJoin("providers",function($join){
                                                $join->on("providers.id","webinars.provider_id");
                                                $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                                $join->orOn("providers.id","courses.provider_id");
                                                $join->on("purchase_items.type","=",DB::raw("'course'"));
                                            });
        }else{
            $purchase_items = Purchase_Item::select(
                                "providers.name as provider_name","providers.id as provider_id","courses.title as course_name","webinars.title as webinar_name",
                                "purchase_items.channel","purchase_items.price as original_price","purchase_items.total_amount as price_paid",
                                "purchase_items.provider_revenue","purchase_items.fast_revenue","purchase_items.promoter_revenue","purchase_items.id as itemID",
                                "purchase_items.type","purchase_items.voucher as coupon_code","purchase_items.discount","purchases.reference_number as transaction_code","users.name as purchased_by",
                                "purchase_items.updated_at as purchased_date"
                            )
                            ->where("purchase_items.payment_status","paid")
                            ->whereIn("providers.id",$providers)
                            ->whereDate("purchase_items.updated_at",">=",$start_date)->whereDate("purchase_items.updated_at","<=",$end_date)
                            ->leftJoin("users","users.id","purchase_items.user_id")
                            ->leftJoin("courses","courses.id","purchase_items.data_id")
                            ->leftJoin("webinars","webinars.id","purchase_items.data_id")
                            ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
                            ->leftJoin("providers",function($join){
                                $join->on("providers.id","webinars.provider_id");
                                $join->on("purchase_items.type","=",DB::raw("'webinar'"));
                                $join->orOn("providers.id","courses.provider_id");
                                $join->on("purchase_items.type","=",DB::raw("'course'"));
                            });
        }
        $purchase_items = $purchase_items->get();

        $data = array_map(function($item){

          
                if($item['coupon_code'] == null && $item["discount"] == null ){
                    $channel = "";
                }else if($item['channel'] == "fast_promo"){
                    $channel = "Fast Promo";
                }else if($item['channel'] == "provider_promo"){
                    $channel = "Provider Promo";
                }else if($item['channel'] == "promoter_promo"){
                    $channel = "Promoter Promo";
                }else{
                    $channel = "Refund";
                }
                $percent = _get_revenue_percent($item['provider_id'],$item['channel'],$item['type']);
               
                return[
                    "id" => $item['itemID'],
                    "transaction_code" => $item['transaction_code'],
                    "purchased_by" => $item['purchased_by'],
                    "purchased_date" => date("M. j 'y",strtotime($item['purchased_date'])),
                    "provider" => $item['provider_name'],
                    "type" => $item['type'],
                    "course" => $item['type'] == "course" ? $item['course_name'] : $item["webinar_name"],
                    "type" => ucwords($item['type']),
                    "channel" => ucwords($channel),
                    "voucher" => $item['coupon_code'],
                    "original_price" => $item['original_price'],
                    "price_paid" => $item['price_paid'],
                    "provider_revenue" => number_format($item['provider_revenue'],2),
                    "fast_revenue" => number_format($item['fast_revenue'],2),
                    "affiliate_revenue" => number_format($item['promoter_revenue'],2),
                    "provider_comm" => $percent["provider"]."%",
                    "fast_comm" => $percent['fast']."%",
                    "affiliate_comm" => $percent['promoter']."%",
                ];
        

        },$purchase_items->toArray());
        $data = [
            "data" => $data,
            "start_date" => $start_date,
            "end_date" => $end_date
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle("-Reports Purchase Items-");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/superadmin/reports/purchase_items', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');
       
        $filename ="-Reports Purchase Items-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("payout.index");
        }
    }
    function print_verification_payouts(Request $request){
        $payouts = Payout::select("payouts.id as payoutID","payouts.notes","providers.name as provider_name","users.name as full_name","payouts.status",
                        "payouts.provider_revenue","payouts.fast_revenue","payouts.promoter_revenue",
                        "payouts.date_to","payouts.type","payouts.data_id","providers.id as provider_id",
                        DB::raw("CAST(DATE_FORMAT(payouts.date_from,'%m') AS UNSIGNED) as month"),"payouts.date_from",
                        DB::raw("CAST(DATE_FORMAT(payouts.date_to,'%Y') AS UNSIGNED) as year"))
                        ->leftJoin("providers","providers.id","payouts.data_id")
                        ->leftJoin("users","users.provider_id","providers.id")->orderBy("year","desc")->orderBy("month","desc")->get();

        $data= array_map(function($payout){  
            
            $month_year = date("F Y",strtotime($payout['date_from']."+1 month"));
            
            $expected_date =  date("F j, Y",strtotime($month_year."first friday"));
            if($payout["type"] == "provider"){
                $amount = $payout['provider_revenue'];
                $company_name = $payout['provider_name'];
                $fullname = $payout['full_name'];
            }else if($payout["type"] == "fast"){
                $amount = $payout['fast_revenue'];
                $company_name = "Fast CPD";
                $fullname = "Fast CPD";
            }else{
                $amount = $payout['promoter_revenue'];
                $company_name = "Promoter";
                $fullname = "Promoter";
            }
          
                $data = [
                    "id" => $payout['payoutID'],
                    "payout_id" => $payout['payoutID'],
                    "provider_id" => $payout['data_id'],
                    "month" => date("F",strtotime($payout['date_to'])),
                    "year" => date("Y",strtotime($payout['date_to'])),
                    "type" =>ucwords($payout["type"]),
                    "provider_name" =>$company_name,
                    "fast_cpd" => "Fast CPD",
                    "full_name" => $fullname,
                    "amount" => $amount,
                    "status" => $payout['status'],
                    "expected_payment_date" => date("j",strtotime($expected_date)) >= 8 ? date("F j, Y",strtotime("-7 day",strtotime($expected_date))) : $expected_date,
                    "notes" => $payout['notes'],
    
                ];
            
            return $data;
        },$payouts->toArray());

        $data = [
            "data" => $data,
        ];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'arial',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]);
        $mpdf->charset_in='utf-8';
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetTitle("-Verfication Payouts-");
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/pdf/superadmin/verification/payouts', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');
       
        $filename ="-Verfication Payouts-".date("F-Y").".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("payout.index");
        }
    }
    function webinar_filter($courses)
    {
        $data = array_map(function($provider){
           
            $instructor_list = array();
            $profession_list = array();
            $total_enrollees = Purchase_Item::where("data_id",$provider['course_id'])->where("type","webinar")
                                            ->where("payment_status","paid")
                                            ->count();
            if($provider['course_instructor']){
                $instructors = User::select("users.name as instructor_name")
                                    ->whereIn("instructors.id",json_decode($provider['course_instructor']))
                                    ->leftJoin("instructors","instructors.user_id","users.id")
                                    ->get();
                if($instructors){
                    foreach($instructors as $instructor){
                        array_push($instructor_list,[
                            "name" => $instructor->instructor_name,
                            "url" => "/instructor/".$instructor->instructor_name
                        ]);
                    }
                }
            }
            if($provider['accreditation']){
                $accreditation = json_decode($provider['accreditation']);
                $profession = Profession::where("id",$accreditation[0]->id)->first();
                array_push($profession_list,[
                    "profession" => $profession->title,
                    "units" => $accreditation[0]->units
                ]);
            }
            
           
            $session = Webinar_Session::where("webinar_id",$provider['course_id'])->first();
            $session_start = $session->session_date;
           
            
            return[
                'id' => $provider['course_id'],
                "provider_name" => $provider['provider_name'],
                "course_title" => $provider["course_title"],
                "course_published_at" => date("M. d, 'y",strtotime($provider["course_published_at"])),
                "start_date" =>  date("M. d, 'y",strtotime($session_start)),
                "price_with" => $provider["course_price"] != null ? json_decode($provider["course_price"])->with : 0.00,
                "price_without" => $provider["course_price"] != null ? json_decode($provider["course_price"])->without : 0.00,
                "enrollees_total" => $total_enrollees,
                "cpd_units" => $profession_list,
                "instructors" => $instructor_list
            ];
        },$courses->toArray());

        return $data;
    }
    function video_demand_filter($courses){
        $data = array_map(function($provider){
            $instructor_list = array();
            $profession_list = array();
            $total_enrollees = Purchase_Item::where("data_id",$provider['course_id'])->where("type","course")
                                            ->where("payment_status","paid")
                                            ->count();
            if($provider['course_instructor']){
                $instructors = User::select("users.name as instructor_name")
                                    ->whereIn("instructors.id",json_decode($provider['course_instructor']))
                                    ->leftJoin("instructors","instructors.user_id","users.id")
                                    ->get();
                if($instructors){
                    foreach($instructors as $instructor){
                        array_push($instructor_list,[
                            "name" => $instructor->instructor_name,
                            "url" => "/instructor/".$instructor->instructor_name
                        ]);
                    }
                }
            }
            if($provider['accreditation']){
                $accreditation = json_decode($provider['accreditation']);
                $profession = Profession::where("id",$accreditation[0]->id)->first();
                array_push($profession_list,[
                    "profession" => $profession->title,
                    "units" => $accreditation[0]->units
                ]);
            }
            
            
            
            return[
                'id' => $provider['course_id'],
                "provider_name" => $provider['provider_name'],
                "course_title" => $provider["course_title"],
                "course_published_at" => date("M. d, 'y",strtotime($provider["course_published_at"])),
                "course_session_start" =>  date("M. d, 'y",strtotime($provider["course_session_start"])),
                "course_price" => $provider["course_price"] != null ? $provider["course_price"] : 0.00,
                "enrollees_total" => $total_enrollees,
                "cpd_units" => $profession_list,
                "instructors" => $instructor_list
            ];
        },$courses->toArray());

        return $data;
    }

    function print_webinar_completion_report(Request $request){

        $webinar_sessions = Webinar_Session::where("id",$request->recordID)->first();

        $webinars = Webinar::select(
                        "webinars.provider_id as provider_id","webinars.title as course_title",
                        "webinars.url as url", "webinars.profession_id as profession_id",
                        "webinars.instructor_id as instructor_id"

                    )->
                    where("id",$webinar_sessions->webinar_id)->first();
                  
        // $completion_rep = CompletionReport::select(
        //     DB::raw("completion_reports.data_id as data_id"),
        //     DB::raw("completion_reports.type as type"),
        //     DB::raw("completion_reports.provider_id as provider_id"),
        //     DB::raw("courses.title as course_title"),
        //     DB::raw("courses.url as url"),
        //     DB::raw("courses.profession_id as profession_id"),
        //     DB::raw("courses.instructor_id as instructor_id"),
        //     DB::raw("completion_reports.created_at as created_at"),
        //     "completion_reports.completion_date as completion_date",
        //     "completion_reports.participants as participants"
        // )
        // ->where("completion_reports.id",$request->recordID)
        // ->leftJoin("courses","courses.id","completion_reports.data_id")->first(); 

        $professions = Profession::select("title")
            ->whereIn("id",json_decode($webinars->profession_id))->get();
      
        $providers = Provider::select(
            "users.name as fullname","users.signature",
            "providers.name as provider_name",
            "providers.accreditation_number as provider_accreditation_no",
            "providers.contact as provider_contact_no",
            "providers.accreditation_expiration_date as provider_accreditation_expiration"
        )
        ->where("providers.id",$webinars->provider_id)
        ->leftJoin("users","users.provider_id","providers.id")->first();
                            
        $courses = Webinar::where("id",$webinar_sessions->webinar_id)->first();
        $total_applicants = Purchase_Item::select(
            DB::raw("count(id) as applicant_count")
        )->where("data_id",$webinar_sessions->webinar_id)
        ->where("fast_status","complete")
        // ->whereMonth("purchase_items.updated_at",date("m",strtotime($completion_rep->completion_date)))
        // ->whereYear("purchase_items.updated_at",date("Y",strtotime($completion_rep->completion_date)))
        ->first();
           
        $total_handouts = Handout::select(
                            DB::raw("count(id) as total_handouts")
                        )->where([
                            "type" => "webinar",
                            "data_id" => $webinar_sessions->webinar_id,
                            "deleted_at" => null
                        ])->first();
        $section_details = Section::where(["type"=>"webinar", "data_id"=>$webinar_sessions->webinar_id,"deleted_at" => null])->get();
        $total_quizzes = 0;
        $sec_details = array();
        $total_video_length = 0;
        $hour = 0;
        $minutes = 0;
        $seconds = 0;
        foreach($section_details as $section){
            $videos_count = Video::select(DB::raw("count(id) as videos_count"))->where("section_id",$section->id)->where("deleted_at",null)->first();
            $quiz = Quiz::select(DB::raw("count(id) as quizzes_count"))->where("section_id",$section->id)->where("deleted_at",null)->first();
            //$quizzes_count = Quiz_Item::select(DB::raw("count(id) as quizzes_count"))->where("quiz_id",$quiz->id)->first();
            $articles_count = Article::select(DB::raw("count(id) as articles_count"))->where("section_id",$section->id)->where("deleted_at",null)->first();
            $video_lengths = Video::select("length")->where("section_id",$section->id)->where("deleted_at",null)->get();
            foreach($video_lengths as $vid_length){
                if($vid_length->length != null && $vid_length->length != "" ){
                    $explode_length = explode(':',$vid_length->length);
                    $time_ = count($explode_length);
                    
                    if($time_ == 3){
                        $hour = $hour + $explode_length[0];
                        $minutes = $minutes + $explode_length[1];
                        $seconds = $seconds + $explode_length[2];
                    }else{
                        $minutes = $minutes + ($explode_length[0] ?? 0);
                        $seconds = $seconds +( $explode_length[1] ?? 0);
                    }
                }else{
                    $minutes += 0;
                    $seconds += 0;
                }
            }
            // $time = _course_total_time_length($section_id);
            array_push($sec_details,[
                "section_name" => $section->name,
                "videos_count" => $videos_count->videos_count,
                "quizzes_count" => $quiz->quizzes_count,
                "articles_count" => $articles_count->articles_count,
            ]);
            $total_quizzes += $quiz->quizzes_count;

        }
        $total_minutes = $minutes + ($seconds/60);
        $total_hours = $hour + ($total_minutes/60);
        $total_minutes += ($hour * 60);
       // $total_minutes = fmod($total_minutes,60);

        $instructors = Instructor::select(
                                    "users.name as instructor_name",
                                    "users.professions as prc_no"
                                )
                                ->whereIn("instructors.user_id",json_decode($courses->instructor_id))
                                ->leftJoin("users","users.id","instructors.user_id")->get();

        $participants = Purchase_Item::select(
            "users.id as participant_id","users.name as pariticipant_name","users.professions as prc_no","users.contact as contact_no","users.signature","users.email as email","purchase_items.data_id as data_id"
        )
        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
        ->leftJoin("users","users.id","purchase_items.user_id")
        ->where("purchase_items.type","webinar")
        ->where("purchase_items.data_id",$webinar_sessions->webinar_id)
        ->where("purchases.payment_status","paid")
        // ->whereMonth("purchases.payment_at",date("m",strtotime($completion_rep->completion_date)))
        // ->whereYear("purchases.payment_at",date("Y",strtotime($completion_rep->completion_date)))
        ->get();

        $participant_att_quiz = Purchase_Item::select(
            "users.id as participant_id","users.name as pariticipant_name","users.professions as prc_no","users.contact as contact_no","users.signature","users.email as email","purchase_items.data_id as data_id"
        )
        ->leftJoin("purchases","purchases.id","purchase_items.purchase_id")
        ->leftJoin("users","users.id","purchase_items.user_id")
        ->where("purchase_items.data_id",$webinar_sessions->webinar_id)
        ->where("purchases.payment_status","paid")
        // ->whereMonth("purchase_items.updated_at",date("m",strtotime($completion_rep->completion_date)))
        // ->whereYear("purchase_items.updated_at",date("Y",strtotime($completion_rep->completion_date)))
        ->where("purchase_items.fast_status","complete")->get();

        $partipants_with_quiz = array_map(function($participant) use($request){
            $total_scores = 0;
            $total_number_items = 0;
            $sections = Section::where(["type"=>"webinar", "data_id"=>$participant["data_id"],"deleted_at" => null])->get();
            foreach($sections as $section){
                $quiz_progress = Webinar_Progress::select("quiz_overall as overall")->where([
                    "section_id"=>$section->id,
                    "webinar_id"=>$participant["data_id"],
                    "user_id"=>$participant["participant_id"],
                    "type"=>"quiz",
                    "deleted_at" => null,
                ])->whereIn("status", ["completed", "passed"])->get();

                foreach($quiz_progress as $quiz){
                    if($quiz && $quiz->overall){
                        $overall = json_decode($quiz->overall);
                        $total_scores += $overall->total;
                        $total_number_items += $overall->items;
                    }
                }
            }

            $percentage = $total_scores != 0 ? (($total_scores / $total_number_items) * 100) : 0;

            return[
                "participant_id" => $participant["participant_id"],
                "pariticipant_name" => $participant["pariticipant_name"],
                "signature" => $participant["signature"],
                "prc_no" => $participant["prc_no"],
                "course_id" => $participant["data_id"],
                "score_percentage" => number_format($percentage,2,".",",")
            ];
           
        },$participant_att_quiz->toArray());

      
        $course_performances = Webinar_Rating::select(
            "webinar_ratings.id as course_rating_id",
            "users.name as username_feedback",
            "users.professions as prc_no",
            "webinar_ratings.rating as course_rating",
            "webinar_ratings.remarks as course_remark",
            "webinar_performances.valuable_information",
            "webinar_performances.concepts_clear",
            "webinar_performances.instructor_delivery",
            "webinar_performances.opportunities",
            "webinar_performances.expectations",
            "webinar_performances.knowledgeable"
        )
        ->leftJoin("webinar_performances", function($join) use($webinar_sessions){
            $join->on("webinar_performances.user_id","=","webinar_ratings.user_id");
            $join->on("webinar_performances.webinar_id","=",DB::raw($webinar_sessions->webinar_id));
        })
        ->leftJoin("users","users.id","webinar_ratings.user_id")
        ->where("webinar_ratings.webinar_id", $webinar_sessions->webinar_id)
        ->where("webinar_performances.webinar_id", $webinar_sessions->webinar_id)
        // ->whereMonth("webinar_ratings.created_at",date("m",strtotime($completion_rep->completion_date)))
        // ->whereYear("webinar_ratings.created_at",date("Y",strtotime($completion_rep->completion_date)))
        ->groupBy("webinar_ratings.user_id")
        ->get();

        $sections_list = Section::where(["type"=>"webinar", "data_id"=>$webinar_sessions->webinar_id,"deleted_at" => null])
                                ->orderBy("section_number","asc")->get();
        $section_content = array();
        foreach($sections_list as $section){
            $part_content = array();
            foreach(json_decode($section->sequences) as $sequence){
                switch($sequence->type){
                    case "video": 
                        $part_title = Video::select("title")->where("id",$sequence->id)->where("deleted_at",null)->first();
                        $part_title = $part_title->title;
                    break;
                    case "quiz": 
                        $part_title = Quiz::select("title")->where("id",$sequence->id)->where("deleted_at",null)->first();
                        $part_title = $part_title->title;
                    break;
                    case "article": 
                        $part_title = Article::select("title")->where("id",$sequence->id)->where("deleted_at",null)->first();
                        $part_title = $part_title->title;
                    break;
                    default: 
                        $part_title = "None";
                    break;
                }
                array_push($part_content,[
                    "part_number" => $sequence->part,
                    "part_title" => $part_title,
                    "part_type" => $sequence->type == "video" ? "zoom class/Webinar" : $sequence->type
                ]);
            }
            array_push($section_content,[
               $section->name => $part_content
            ]);
        }
        $total_hours_webinar = 0;
        foreach(json_decode($webinar_sessions->sessions) as $web){
           $total_hours_webinar = date("H",strtotime($web->end)) - date("H",strtotime($web->start));
           $total_minutes_webinar = date("i",strtotime($web->end)) - date("i",strtotime($web->start));
        }
     
        $data = array(
            "completion_rep" => $webinars,
            "webinar_sessions" => $webinar_sessions,
            "total_hours_webinar" => $total_hours_webinar,
            "total_minutes_webinar" => $total_minutes_webinar,
            "professions" => $professions,
            "general_info" => $providers,
            "courses" => $courses,
            "total_applicants" => $total_applicants,
            "total_handouts" => $total_handouts,
            "total_quizzes" => $total_quizzes,
            "section_details" => $sec_details,
            "instructors" => $instructors,
            "participants" => $partipants_with_quiz,
            "course_performances" => $course_performances,
            "section_content" => $section_content,
            "total_minutes" => $total_minutes,
            "total_hours" => $total_hours,
            "attendance" => $participant_att_quiz,
            "registration" => $participants
        ); 


     
        $records = [];
        $mpdf = new \Mpdf\Mpdf([
            "format" => $request->page_size,
            'orientation' => $request->page_orientation,
            'margin_left' => 5,
            'margin_right' => 8,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 0,
            "setAutoTopMargin" => "stretch",
            'margin_footer' => 0,
            'default_font' => 'dejavusans',
            'fontDir' => [
                public_path('fonts/'),
            ],
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'I' => 'ARIALNI.TTF',
                    'B' => 'ArialCEBold.ttf',
                ],
            ]
        ]); 
// dd($data);
        $mpdf->SetTitle("Completion-Report-".date("F-Y",strtotime( $webinar_sessions->session_date)));
        // $mpdf->SetDefaultBodyCSS('background', "url('media/bg/cert_template.jpg')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        $mpdf->WriteHTML(view('template/provider/webinar_completion_report', $data));
        $mpdf->AddPage('L','','','','',5,8,10,5,5,0);
        $mpdf->WriteHTML(view('template/provider/webinar_completion_report_tables', $data));
        $mpdf->imageVars['myimage'] = asset('img/sample/sample.png');

        $filename = "Completion-Report-".date("F-Y",strtotime($webinar_sessions->session_date)).".pdf";
        $path = storage_path('app/' . $filename);
        $saved = $mpdf->Output($path, 'F');
        $exist = Storage::disk('local')->exists($filename);

        if ($exist) {
            return Storage::response($filename);
        } else {
            $request->session()->flash("error", "File Record not exported! Please try again later.");
            return redirect()->route("users.list");
        }
    }
}