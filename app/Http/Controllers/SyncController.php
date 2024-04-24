<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, DB};
use App\Http\Controllers\Features\{
    Household, CompanyInformation, BarangayInformation, LuponMembers, Cases, DocumentInformation,
    BusinessNature, CertificateAndClearance, CurfewLogs
};

use Response;

class SyncController extends Controller
{
    function sync(Request $request) : JsonResponse
    {
        if(isset($request->data) && isset($request->feature)){ 
            switch ($request->feature) {
                case 'household':
                    $feature = new Household();
                    $reponse = $feature->household($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'company_info':
                    $feature = new CompanyInformation();
                    $reponse = $feature->company_info($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'barangay_info':
                    $feature = new BarangayInformation();
                    $reponse = $feature->barangay_info($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'lupon_members':
                    $feature = new LuponMembers();
                    $reponse = $feature->lupon_members($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'cases':
                    $feature = new Cases();
                    $reponse = $feature->cases($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'document_information':
                    $feature = new DocumentInformation();
                    $reponse = $feature->document_information($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'business_nature':
                    $feature = new BusinessNature();
                    $reponse = $feature->business_nature($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'certificate_and_clearance':
                    $feature = new CertificateAndClearance();
                    $reponse = $feature->certificate_and_clearance($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;

                case 'curfew_logs':
                    $feature = new CurfewLogs();
                    $reponse = $feature->curfew_logs($request->data);
                    if($reponse){
                        return response()->json($reponse);
                    }
                break;
            }

            return response()->json('Fail');
        }else{
            return response()->json('Fail');
        }
    }
}
