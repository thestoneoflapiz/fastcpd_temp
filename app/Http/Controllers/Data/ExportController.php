<?php

namespace App\Http\Controllers\Data;

use App\{User, Role};

use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Guard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Hash, Auth, DB};
use App\Mail\{PublicVerificationEmail, PublicResetEmail};

use Response;

class ExportController extends Controller
{
    function export_users(Request $request){
        $file = "UsersList.csv";
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
                $user["email_verified_at"],
                $user["status"],
            ];
        }, $record->toArray());

        $data = array(
            "file" => $file,
            "headers" => $headers,
            "records" => $records,
        );

        $this->toCSV($data);
    }

    function toCSV($resources){
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$resources["file"]);
        header('Pragma: no-cache');
        header('Expires:0');
        
        $file = fopen('php://output', 'w+');
        fputcsv($file, $resources["headers"]);
        foreach ($resources["records"] as $key => $log)
        {
            fputcsv($file, $log);
        }
        fclose($file);
    }
}