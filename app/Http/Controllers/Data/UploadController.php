<?php

namespace App\Http\Controllers\Data;

use App\{User, Role};

use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Hash, Auth};
use App\Mail\{PublicVerificationEmail, PublicResetEmail};

use Response;

class UploadController extends Controller
{

    function index(Request $request){
        dd($request->all());
    }

    function upload_s3(Request $request){
        // Include the SDK using the composer autoloader
        require 'vendor/autoload.php';

        $s3 = new Aws\S3\S3Client([
            'region'  => '-- your region --',
            'version' => 'latest',
            'credentials' => [
                'key'    => "-- access key id --",
                'secret' => "-- secret access key --",
            ]
        ]);

        // Send a PutObject request and get the result object.
        $key = '-- your filename --';

        $result = $s3->putObject([
            'Bucket' => '-- bucket name --',
            'Key'    => $key,
            'Body'   => 'this is the body!',
            //'SourceFile' => 'c:\samplefile.png' -- use this if you want to upload a file from a local location
        ]);

        // Print the body of the result by indexing into the result object.
        dd($result);
    }
    
}