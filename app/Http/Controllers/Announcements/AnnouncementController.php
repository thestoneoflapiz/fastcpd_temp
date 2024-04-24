<?php

namespace App\Http\Controllers\Announcements;

use App\{User, Provider, Co_Provider, Instructor, Course, SuperadminPermission, Announcement};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use App\Mail\{SuperadminInvitation};

use Response; 
use Session;


class AnnouncementController extends Controller
{

    function getAnnouncement(Request $request) : JsonResponse
    {
        $logged_user = Auth::user()->id ?? "";
        $date_time_today = date("Y-m-d H:i:s");
        
        $this->updateAnnouncement($date_time_today);
        if(!$logged_user){
            if($request->page == "course"){
                $announcement = Announcement::where('start_date',"<=",$date_time_today)
                ->where('end_date',">=",$date_time_today)
                ->where('target_audience',"course")
                ->where('status',"!=","deleted")
                ->where('status',"!=","expired")
                ->where('status',"!=","inactive")
                ->get();
                
                if(count($announcement)){
                    if($this->announcement_check($announcement[0]->id)){
                        return response()->json([], 200);
                    }else{
                        $announce = Announcement::find($announcement[0]->id);
                        $announce->status = "active";
                        $announce->save();
                        return response()->json([
                            "id"=> $announcement[0]->id,
                            "message"=> $announcement[0]->message,
                            "banner_state"=>$announcement[0]->banner_state,
                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                            "end_date"=> $announcement[0]->end_date
                        ], 200);
                    }
                    
                }else{
                    $announcement = Announcement::where('target_audience',"course")
                    ->where('status',"active")
                    ->get();
                    if(count($announcement)){
                        if($this->announcement_check($announcement[0]->id)){
                            return response()->json([], 200);
                            
                        }else{
                            return response()->json([
                                "id"=> $announcement[0]->id,
                                "message"=> $announcement[0]->message,
                                "banner_state"=>$announcement[0]->banner_state,
                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                "end_date"=> $announcement[0]->end_date
                            ], 200);
                        }
                    }else{
                        
                        return response()->json([], 200);
                    }
                    return response()->json([], 200);
                }

            }else{
                $announcement = Announcement::where('start_date',"<=",$date_time_today)
                ->where('end_date',">=",$date_time_today)
                ->where('target_audience',"general")
                ->where('status',"!=","deleted")
                ->where('status',"!=","expired")
                ->where('status',"!=","inactive")
                ->get();
                
                if(count($announcement)){
                    $this->announcement_check($announcement[0]->id);
                    $announce = Announcement::find($announcement[0]->id);
                    $announce->status = "active";
                    $announce->save();
                    if($this->announcement_check($announcement[0]->id)){
                            return response()->json([], 200);
                            
                    }else{
                        return response()->json([
                            "id"=> $announcement[0]->id,
                            "message"=> $announcement[0]->message,
                            "banner_state"=>$announcement[0]->banner_state,
                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                            "end_date"=> $announcement[0]->end_date
                        ], 200);
                    }
                }else{
                    $announcement = Announcement::where('target_audience',"general")
                    ->where('status',"active")
                    ->get();
                    if(count($announcement)){
                        $this->announcement_check($announcement[0]->id);
                        if($this->announcement_check($announcement[0]->id)){
                            return response()->json([], 200);
                        }else{
                            return response()->json([
                                "id"=> $announcement[0]->id,
                                "message"=> $announcement[0]->message,
                                "banner_state"=>$announcement[0]->banner_state,
                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                "end_date"=> $announcement[0]->end_date
                            ], 200);
                        }
                    }else{
                        
                        return response()->json([], 200);
                    }
                    return response()->json([], 200);
                }
                
            }
            
            
        }elseif($logged_user){
            if($request->page == "course"){
                $announcement = Announcement::where('start_date',"<=",$date_time_today)
                ->where('end_date',">=",$date_time_today)
                ->where('target_audience',"course")
                ->where('status',"!=","deleted")
                ->where('status',"!=","expired")
                ->where('status',"!=","inactive")
                ->get();
                if(count($announcement)){
                    $announce = Announcement::find($announcement[0]->id);
                    $announce->status = "active";
                    $announce->save();
                    if($this->announcement_check($announcement[0]->id)){
                        $announcement = Announcement::where('target_audience',"students")
                        ->where('status',"active")
                        ->get();
                        if(count($announcement)){
                            $this->announcement_check($announcement[0]->id);
                            if($this->announcement_check($announcement[0]->id)){
                                return response()->json([], 200);
                            }else{
                                return response()->json([
                                    "id"=> $announcement[0]->id,
                                    "message"=> $announcement[0]->message,
                                    "banner_state"=>$announcement[0]->banner_state,
                                    "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                    "end_date"=> $announcement[0]->end_date
                                ], 200);
                            }
                        }else{
                            
                            $announcement = Announcement::where('start_date',"<=",$date_time_today)
                            ->where('end_date',">=",$date_time_today)
                            ->where('target_audience',"general")
                            ->where('status',"!=","deleted")
                            ->where('status',"!=","expired")
                            ->where('status',"!=","inactive")
                            ->get();
                            if(count($announcement)){
                                $this->announcement_check($announcement[0]->id);
                                $announce = Announcement::find($announcement[0]->id);
                                $announce->status = "active";
                                $announce->save();
                                if($this->announcement_check($announcement[0]->id)){
                                    return response()->json([], 200);
                                }else{
                                    return response()->json([
                                        "id"=> $announcement[0]->id,
                                        "message"=> $announcement[0]->message,
                                        "banner_state"=>$announcement[0]->banner_state,
                                        "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                        "end_date"=> $announcement[0]->end_date
                                    ], 200);
                                }
                            }else{
                                $announcement = Announcement::where('target_audience',"general")
                                ->where('status',"active")
                                ->get();
                                if(count($announcement)){
                                    $this->announcement_check($announcement[0]->id);
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{
                                    
                                    return response()->json([], 200);
                                }
                                
                                return response()->json([], 200);
                            }
                        }
                        return response()->json([], 200);
                        
                    }else{
                        return response()->json([
                            "id"=> $announcement[0]->id,
                            "message"=> $announcement[0]->message,
                            "banner_state"=>$announcement[0]->banner_state,
                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                            "end_date"=> $announcement[0]->end_date
                        ], 200);
                    }
                }else{
                    $announcement = Announcement::where('target_audience',"course")
                    ->where('status',"active")
                    ->get();
                    if(count($announcement)){
                        if($this->announcement_check($announcement[0]->id)){
                            return response()->json([], 200);
                        }else{
                            return response()->json([
                                "id"=> $announcement[0]->id,
                                "message"=> $announcement[0]->message,
                                "banner_state"=>$announcement[0]->banner_state,
                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                "end_date"=> $announcement[0]->end_date
                            ], 200);
                        }
                    }else{
                        $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                ->where('end_date',">=",$date_time_today)
                                ->where('target_audience',"students")
                                ->where('status',"!=","deleted")
                                ->where('status',"!=","expired")
                                ->where('status',"!=","inactive")
                                ->get();
                        if(count($announcement)){
                            $this->announcement_check($announcement[0]->id);
                            $announce = Announcement::find($announcement[0]->id);
                            $announce->status = "active";
                            $announce->save();
                            if($this->announcement_check($announcement[0]->id)){
                                return response()->json([], 200);
                            }else{
                                return response()->json([
                                    "id"=> $announcement[0]->id,
                                    "message"=> $announcement[0]->message,
                                    "banner_state"=>$announcement[0]->banner_state,
                                    "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                    "end_date"=> $announcement[0]->end_date
                                ], 200);
                            }
                        }else{
                            $announcement = Announcement::where('target_audience',"students")
                            ->where('status',"active")
                            ->get();
                            if(count($announcement)){
                                $this->announcement_check($announcement[0]->id);
                                if($this->announcement_check($announcement[0]->id)){
                                    return response()->json([], 200);
                                }else{
                                    return response()->json([
                                        "id"=> $announcement[0]->id,
                                        "message"=> $announcement[0]->message,
                                        "banner_state"=>$announcement[0]->banner_state,
                                        "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                        "end_date"=> $announcement[0]->end_date
                                    ], 200);
                                }
                            }else{
                                
                                $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                ->where('end_date',">=",$date_time_today)
                                ->where('target_audience',"general")
                                ->where('status',"!=","deleted")
                                ->where('status',"!=","expired")
                                ->where('status',"!=","inactive")
                                ->get();
                                if(count($announcement)){
                                    $this->announcement_check($announcement[0]->id);
                                    $announce = Announcement::find($announcement[0]->id);
                                    $announce->status = "active";
                                    $announce->save();
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{
                                    $announcement = Announcement::where('target_audience',"general")
                                    ->where('status',"active")
                                    ->get();
                                    if(count($announcement)){
                                        $this->announcement_check($announcement[0]->id);
                                        if($this->announcement_check($announcement[0]->id)){
                                            return response()->json([], 200);
                                        }else{
                                            return response()->json([
                                                "id"=> $announcement[0]->id,
                                                "message"=> $announcement[0]->message,
                                                "banner_state"=>$announcement[0]->banner_state,
                                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                                "end_date"=> $announcement[0]->end_date
                                            ], 200);
                                        }
                                    }else{

                                        return response()->json([], 200);
                                    }
                                    
                                    return response()->json([], 200);
                                }
                            }

                        }
                        
                        
                        return response()->json([], 200);
                    }
                    return response()->json([], 200);
                }

            }else{
                switch (_get_auth_role()) {
                    case "provider":
                        if($request->page == "provider"){
                            $announcement = Announcement::where('start_date',"<=",$date_time_today)
                            ->where('end_date',">=",$date_time_today)
                            ->where('target_audience',"provider")
                            ->where('status',"!=","deleted")
                            ->where('status',"!=","expired")
                            ->where('status',"!=","inactive")
                            
                            ->get();
                            if(count($announcement)){
                                $announce = Announcement::find($announcement[0]->id);
                                $announce->status = "active";
                                $announce->save();
                                if($this->announcement_check($announcement[0]->id)){
                                    return response()->json([], 200);
                                }else{
                                    return response()->json([
                                        "id"=> $announcement[0]->id,
                                        "message"=> $announcement[0]->message,
                                        "banner_state"=>$announcement[0]->banner_state,
                                        "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                        "end_date"=> $announcement[0]->end_date
                                    ], 200);
                                }
                            }else{
                                // return response()->json([
                                //     "message"=> "YOU'LL RECEIVE 10% DISCOUNT FOR YOU FIRST PURCHASE AT FAST CPD JUST USE VOUCHER CODE FIRST10",
                                //     "banner_state"=>"dark",
                                //     "color"=>"white",
                                //     "end_date"=> ""
                                // ], 200);
                                $announcement = Announcement::where('target_audience',"provider")
                                ->where('status',"active")
                                ->get();
                                if(count($announcement)){
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{
                                    $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                    ->where('end_date',">=",$date_time_today)
                                    ->where('target_audience',"general")
                                    ->where('status',"!=","deleted")
                                    ->where('status',"!=","expired")
                                    ->where('status',"!=","inactive")
                                    ->get();
                                    if(count($announcement)){
                                        $this->announcement_check($announcement[0]->id);
                                        $announce = Announcement::find($announcement[0]->id);
                                        $announce->status = "active";
                                        $announce->save();
                                        if($this->announcement_check($announcement[0]->id)){
                                            return response()->json([], 200);
                                        }else{
                                            return response()->json([
                                                "id"=> $announcement[0]->id,
                                                "message"=> $announcement[0]->message,
                                                "banner_state"=>$announcement[0]->banner_state,
                                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                                "end_date"=> $announcement[0]->end_date
                                            ], 200);
                                        }
                                    }else{
                                        $announcement = Announcement::where('target_audience',"general")
                                        ->where('status',"active")
                                        ->get();
                                        if(count($announcement)){
                                            $this->announcement_check($announcement[0]->id);
                                            if($this->announcement_check($announcement[0]->id)){
                                                return response()->json([], 200);
                                            }else{
                                                return response()->json([
                                                    "id"=> $announcement[0]->id,
                                                    "message"=> $announcement[0]->message,
                                                    "banner_state"=>$announcement[0]->banner_state,
                                                    "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                                    "end_date"=> $announcement[0]->end_date
                                                ], 200);
                                            }
                                        }else{

                                            return response()->json([], 200);
                                        }
                                        
                                        return response()->json([], 200);
                                    }
                                    
                                    return response()->json([], 200);
                                }
                                return response()->json([], 200);
                            }
                        }else{
                            $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                            ->where('end_date',">=",$date_time_today)
                                            ->where('target_audience',"general")
                                            ->where('status',"!=","deleted")
                                            ->where('status',"!=","expired")
                                            ->where('status',"!=","inactive")
                                            ->get();

                            if(count($announcement)){
                                $this->announcement_check($announcement[0]->id);
                                $announce = Announcement::find($announcement[0]->id);
                                $announce->status = "active";
                                $announce->save();
                                if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);

                                }else{
                                    return response()->json([
                                        "id"=> $announcement[0]->id,
                                        "message"=> $announcement[0]->message,
                                        "banner_state"=>$announcement[0]->banner_state,
                                        "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                        "end_date"=> $announcement[0]->end_date
                                    ], 200);
                                }
                            }else{
                                $announcement = Announcement::where('target_audience',"general")
                                ->where('status',"active")
                                ->get();
                                if(count($announcement)){
                                    $this->announcement_check($announcement[0]->id);
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{

                                    return response()->json([], 200);
                                }
                                return response()->json([], 200);
                            }
                            return response()->json([], 200);
                        }
                        break;
                    case "instructor":
                        if($request->page == "provider"){
                            $announcement = Announcement::where('start_date',"<=",$date_time_today)
                            ->where('end_date',">=",$date_time_today)
                            ->where('target_audience',"instructor")
                            ->where('status',"!=","deleted")
                            ->where('status',"!=","expired")
                            ->where('status',"!=","inactive")
                            ->get();
        
                            if(count($announcement)){
                                $announce = Announcement::find($announcement[0]->id);
                                $announce->status = "active";
                                $announce->save();
                                if($this->announcement_check($announcement[0]->id)){
                                    return response()->json([], 200);
                                }else{
                                    return response()->json([
                                        "id"=> $announcement[0]->id,
                                        "message"=> $announcement[0]->message,
                                        "banner_state"=>$announcement[0]->banner_state,
                                        "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                        "end_date"=> $announcement[0]->end_date
                                    ], 200);
                                }
                            }else{
                                $announcement = Announcement::where('target_audience',"instructor")
                                                ->where('status',"active")
                                                ->get();
                                if(count($announcement)){
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{
                                    $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                            ->where('end_date',">=",$date_time_today)
                                            ->where('target_audience',"general")
                                            ->where('status',"!=","deleted")
                                            ->where('status',"!=","expired")
                                            ->where('status',"!=","inactive")
                                            ->get();

                                    if(count($announcement)){
                                        $this->announcement_check($announcement[0]->id);
                                        $announce = Announcement::find($announcement[0]->id);
                                        $announce->status = "active";
                                        $announce->save();
                                        if($this->announcement_check($announcement[0]->id)){
                                                return response()->json([], 200);

                                        }else{
                                            return response()->json([
                                                "id"=> $announcement[0]->id,
                                                "message"=> $announcement[0]->message,
                                                "banner_state"=>$announcement[0]->banner_state,
                                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                                "end_date"=> $announcement[0]->end_date
                                            ], 200);
                                        }
                                    }else{
                                        $announcement = Announcement::where('target_audience',"general")
                                        ->where('status',"active")
                                        ->get();
                                        if(count($announcement)){
                                            $this->announcement_check($announcement[0]->id);
                                            if($this->announcement_check($announcement[0]->id)){
                                                return response()->json([], 200);
                                            }else{
                                                return response()->json([
                                                    "id"=> $announcement[0]->id,
                                                    "message"=> $announcement[0]->message,
                                                    "banner_state"=>$announcement[0]->banner_state,
                                                    "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                                    "end_date"=> $announcement[0]->end_date
                                                ], 200);
                                            }
                                        }else{

                                            return response()->json([], 200);
                                        }
                                        return response()->json([], 200);
                                    }
                                    return response()->json([], 200);
                                }
                                return response()->json([], 200);
                            }
                        }else{
                            $announcement = Announcement::where('target_audience',"general")
                                ->where('status',"active")
                                ->get();
                                if(count($announcement)){
                                    $this->announcement_check($announcement[0]->id);
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{
                                    
                                    return response()->json([], 200);
                                }
                                return response()->json([], 200);

                        }
                        break;

                    default:
                        $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                ->where('end_date',">=",$date_time_today)
                                ->where('target_audience',"students")
                                ->where('status',"!=","deleted")
                                ->where('status',"!=","expired")
                                ->where('status',"!=","inactive")
                                ->get();
                        if(count($announcement)){
                            $this->announcement_check($announcement[0]->id);
                            $announce = Announcement::find($announcement[0]->id);
                            $announce->status = "active";
                            $announce->save();
                            if($this->announcement_check($announcement[0]->id)){
                                return response()->json([], 200);
                            }else{
                                return response()->json([
                                    "id"=> $announcement[0]->id,
                                    "message"=> $announcement[0]->message,
                                    "banner_state"=>$announcement[0]->banner_state,
                                    "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                    "end_date"=> $announcement[0]->end_date
                                ], 200);
                            }
                        }else{
                            $announcement = Announcement::where('target_audience',"students")
                            ->where('status',"active")
                            ->get();
                            if(count($announcement)){
                                $this->announcement_check($announcement[0]->id);
                                if($this->announcement_check($announcement[0]->id)){
                                    return response()->json([], 200);
                                }else{
                                    return response()->json([
                                        "id"=> $announcement[0]->id,
                                        "message"=> $announcement[0]->message,
                                        "banner_state"=>$announcement[0]->banner_state,
                                        "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                        "end_date"=> $announcement[0]->end_date
                                    ], 200);
                                }
                            }else{
                                
                                $announcement = Announcement::where('start_date',"<=",$date_time_today)
                                ->where('end_date',">=",$date_time_today)
                                ->where('target_audience',"general")
                                ->where('status',"!=","deleted")
                                ->where('status',"!=","expired")
                                ->where('status',"!=","inactive")
                                ->get();
                                if(count($announcement)){
                                    $this->announcement_check($announcement[0]->id);
                                    $announce = Announcement::find($announcement[0]->id);
                                    $announce->status = "active";
                                    $announce->save();
                                    if($this->announcement_check($announcement[0]->id)){
                                        return response()->json([], 200);
                                    }else{
                                        return response()->json([
                                            "id"=> $announcement[0]->id,
                                            "message"=> $announcement[0]->message,
                                            "banner_state"=>$announcement[0]->banner_state,
                                            "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                            "end_date"=> $announcement[0]->end_date
                                        ], 200);
                                    }
                                }else{
                                    $announcement = Announcement::where('target_audience',"general")
                                    ->where('status',"active")
                                    ->get();
                                    if(count($announcement)){
                                        $this->announcement_check($announcement[0]->id);
                                        if($this->announcement_check($announcement[0]->id)){
                                            return response()->json([], 200);
                                        }else{
                                            return response()->json([
                                                "id"=> $announcement[0]->id,
                                                "message"=> $announcement[0]->message,
                                                "banner_state"=>$announcement[0]->banner_state,
                                                "color"=>$this->getBannerrDesign($announcement[0]->banner_state),
                                                "end_date"=> $announcement[0]->end_date
                                            ], 200);
                                        }
                                    }else{

                                        return response()->json([], 200);
                                    }
                                    
                                    return response()->json([], 200);
                                }
                            }

                        }
                }

            }
            
        }

        return response()->json([], 422);
    }
    function getBannerrDesign($state)
    {
        if($state == "warning" || $state == "light"){
            return "black";
        }else{
            return "white";
        }
    }

    function updateAnnouncement($today)
    {
        $active_announcements = Announcement::where('start_date',"!=",null)
                    ->where('end_date',"!=",null)
                    ->where('status', "active")
                    ->get();
        foreach($active_announcements as $announcement){
            if($announcement->start_date <= $today && $announcement->end_date <= $today){
                $announce = Announcement::find($announcement->id);
                $announce->status = "expired";
                $announce->save();
            }else{
            }
        }
    }

    function closed_announcements(Request $request)  : JsonResponse
    {
        // $request->session()->flush("announcements_closed");
        // Session::flush();
        $data_array = [];
        // dd($request->flush("announcements_closed"));
        // dd(Session::get("announcements_closed"));
        if(Session::has("announcements_closed")){
            $session_array = Session::get("announcements_closed");
            // dd($session_array);
            array_push($session_array, $request->id);
            // dd($session_array);
            Session::put("announcements_closed", $session_array);
            

        }else{
            Session::push("announcements_closed", $request->id);
            
            
        }
        Session::save();
        // dd(Session::get("announcements_closed"));

        return response()->json([]);


    }
    function announcement_check($id)
    {
        if(Session::has("announcements_closed")){
            if(array_search($id,Session::get("announcements_closed")) === false){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }


}
