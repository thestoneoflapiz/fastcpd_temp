<?php

namespace App\Http\Controllers\Verify;

use App\{
    User, Provider, Course, Instructor, 
    Profession, Purchase, Purchase_Item,
    Quiz, Video, Article, Section, Handout,
    Course_Rating, CoursePerformance,
    Certificate,
    Webinar, Webinar_Rating, Webinar_Performance,
    Webinar_Session, Webinar_Series,
};

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\Support\Facades\Hash;

use Response;
use Session;
use URL;

class CertificateController extends Controller
{
    function index(){

        return view("page/public/verify/certificate/index");
    }

    function verify(Request $request)
    {  
        $certificate = Certificate::select(
            "type", "data_id", "url", "qr_link", "user_id", "updated_at"
        )->where([
            "certificate_code" => $request->code,
            "deleted_at" => null
        ])->first();

        if($certificate){
            if($certificate->type=="course"){
                $course = Course::select(
                    "id", "provider_id",
                    "title", "url", "profession_id", "instructor_id",
                    "course_poster as poster", "headline", "description", 
                    "objectives", "requirements", "target_students",
                    "assessment", "pass_percentage", 
                    "session_start", "session_end",
                    "fast_cpd_status"
                )->find($certificate->data_id);         
    
                if($course){
                    $provider = Provider::select(
                        "id", "url", "name", "logo", "headline", "about",
                        "website", "facebook", "linkedin",
                        "accreditation_number", "accreditation_expiration_date", "status"
                    )->find($course->provider_id);            
                    
                    $user = User::select(
                        "name", "image", "username"
                    )->find($certificate->user_id);
    
                    $purchase_item = Purchase_Item::select("credited_cpd_units")->where([
                        "user_id" => $certificate->user_id,
                        "type" => $certificate->type,
                        "data_id" => $certificate->data_id,
                        "fast_status" => "complete"
                    ])->first();
    
                    $data = [
                        "code" => $request->code,
                        "certificate" => $certificate,
                        "provider" => $provider,
                        "data" => $course,
                        "user" => $user,
                        "credited_cpd_units" => $purchase_item ? ($purchase_item->credited_cpd_units ? json_decode($purchase_item->credited_cpd_units) : []) : [],
                    ];
    
                    return view("page/public/verify/certificate/page", $data);
                }
    
                Session::flash("warning", "Course not found!");
            }else{
                $webinar = Webinar::select(
                    "id", "provider_id",
                    "title", "url", "profession_id", "instructor_id",
                    "webinar_poster as poster", "headline", "description", 
                    "objectives", "requirements", "target_students",
                    "assessment", "pass_percentage", 
                    "fast_cpd_status"
                )->find($certificate->data_id);         
    
                if($webinar){
                    $provider = Provider::select(
                        "id", "url", "name", "logo", "headline", "about",
                        "website", "facebook", "linkedin",
                        "accreditation_number", "accreditation_expiration_date", "status"
                    )->find($webinar->provider_id);            
                    
                    $user = User::select(
                        "name", "image", "username"
                    )->find($certificate->user_id);
    
                    $purchase_item = Purchase_Item::select("credited_cpd_units")->where([
                        "user_id" => $certificate->user_id,
                        "type" => $certificate->type,
                        "data_id" => $certificate->data_id,
                        "fast_status" => "complete"
                    ])->first();
    
                    $data = [
                        "code" => $request->code,
                        "certificate" => $certificate,
                        "provider" => $provider,
                        "data" => $webinar,
                        "user" => $user,
                        "credited_cpd_units" => $purchase_item ? ($purchase_item->credited_cpd_units ? json_decode($purchase_item->credited_cpd_units) : []) : [],
                    ];
    
                    return view("page/public/verify/certificate/page", $data);
                }
    
                Session::flash("warning", "Webinar not found!");
            }
        }

        Session::flash("warning", "Certificate not found!");
        return redirect("/verify");
    }

    function pdf(Request $request)
    {
        $certificate_code = $request->code;
        $certificate = Certificate::select(
            "id", "certificate_code", "type", "data_id", "user_id", "created_at", "url", "qr_link"
        )->where("certificate_code",$certificate_code)->first();

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
            return redirect("/");
        }
    }

    function search(Request $request) : JsonResponse
    {   
        $code = $request->data;

        $certificate = Certificate::select("certificate_code")->where("certificate_code", "=", $code)->first();
        if($certificate){
        return response()->json([
                "redirect" => true,
                "link" => URL::to("/verify/{$code}")
            ], 200);
        }


        $url_explode = explode("/", $code);
        $code = count($url_explode) > 0 && count($url_explode) <= 5 ? end($url_explode) : null;
        $certificate = Certificate::select("certificate_code")->where("certificate_code", "=", $code)->first();
        if($certificate){
            return response()->json([
                "redirect" => true,
                "link" => URL::to("/verify/{$code}")
            ], 200);
        }

        return response()->json([
            "message" => "URL or code not found!"
        ], 422);
    }
}
