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

class ImportController extends Controller
{

    function index(Request $request){
       
        $data = [];
        switch ($request->rc) {
            case 'users':
                $data = [
                    "type"=> $request->rc,
                    "column"=> 5,
                ];
                break;
            
            default:
                return redirect()->route('error',["type"=>"404"]);
                break;
        }
        
        return view('page.upload.import')->with("data",$data);
    }

    function action(Request $request){
        $file = $request->files;
        $error = 0;

        switch ($request->rc) {
            case 'users':

                $config = ["column" => 5];

                foreach ($file as $key => $c) {
                    if($key==0){
                        $csv = fopen($c[0], "r");
                        $collect = [];
                        while ($data = fgetcsv($csv, 1000, ",")) {
                            if(count($data) != $config["column"]){
                                
                                return response()->json(["status"=>400, "type"=>"error", "msg"=>"Column count exceeded or missing, Required column is {$config["column"]}."], 400);
                                break;
                            }

                            $collect[] = array(
                                "first_name"=> $data[0],
                                "last_name"=> $data[1],
                                "email"=> $data[2],
                                "contact"=> $data[3],
                                "position"=> $data[4],
                            );
                        }

                        $save = $this->save("users", $collect);
                        return response()->json($save, $save["status"]);
                    }
                    break;
                }
                
                break;
            
            default:
                return response()->json(["status"=>400, "type"=>"warning", "msg"=>"Request does not exist."], 400);
                break;
        }
    }

    function save($type, $data){

        switch ($type) {
            case 'users':
                $success = 0;
                $success_msg = "Successfully Imported Entries: <br/>";
                $duplicate = "Duplicate Entries: <br/>";
                $count_dup = 0;
                $pos_duplicate = "Possible Duplicate Entries: <br/>";
                $count_pos = 0;
        
                foreach ($data as $key => $value) {
                    $row = $key+1;
                    $copy = User::where($value)->first();

                    if($copy){
                        $duplicate .= "Row#{$row}: {$value["first_name"]}<br/>";
                        $count_dup++;
                    }else{
                        $pos = User::where([
                            "first_name"=>$value["first_name"],
                            "last_name"=>$value["last_name"],
                        ])->first();

                        if($pos){
                            $pos_duplicate .= "Row#{$row}: {$value["first_name"]}<br/>";
                            $count_pos++;
                        }else{
                            $unique = User::where("email","=",$value["email"])->first();
                            if($unique){
                                $duplicate .= "Row#{$row}: {$value["first_name"]} EMAIL ALREADY EXIST!<br/>";
                                $count_dup++;
                            }else{
                                User::insert($value);
                                $success_msg .= "Row#{$row}: {$value["first_name"]}<br/>";
                                $success++;
                            }
                        }
                    }
                }
        
                $return_msg = array(
                    "success"=> $success ? $success_msg : null,
                    "duplicate"=> $count_dup ? $duplicate : null,
                    "possible"=> $count_pos ? $pos_duplicate : null
                );

                if($count_dup || $count_pos){
                    return ["status"=>400, "type"=>"info", "msg"=>"CSV file imported", "support"=>$return_msg];
                }else{
                    return ["status"=>200];
                }
                break;
            
            default:
                return ["status"=>400, "type"=>"warning", "msg"=>"Save on Request function does not exist."];
                break;
        }

    }
    
}