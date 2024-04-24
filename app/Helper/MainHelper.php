<?php

use App\{
    User, Logs, Info, 
    Profession, Top_Profession,
    Provider, Co_Provider, Instructor, 
    Course, Profile_Requests, Review, 
    Provider_Permission,
    Section, Article, Video, Quiz,
    Quiz_Item, Quiz_Score,
    Course_Progress,
    Instructor_Permissions, Handout,
    Instructor_Resume,
    Course_Rating, CoursePerformance, System_Rating_Report, 
    
    My_Cart, Voucher, Notification,
    Purchase, Purchase_Item,
    Image_Intervention,

    Webinar, Webinar_Session, Webinar_Series,
    Webinar_Instructor_Permission, Webinar_Progress,
    Webinar_Performance, Webinar_Rating, Webinar_Attendance, 

    SuperadminPermission,
};
use Illuminate\Support\Facades\{Auth};

if (! function_exists('_credits')) {

    function _credits()
    {
        $total = User::where("status", "!=", "delete")->get()->count();
        if( $total > (5) ){
                        
            return 5;
        }

        return $total;
    }
}

if (! function_exists('_color_wheel')) {

    function _color_wheel($index)
    {
        $colors = [
            "info",
            "success",
            "warning",
            "danger",
            "dark",
        ];

        return $colors[$index];
    }
}

if (! function_exists('_months')) {

    function _months()
    {
        $months = ["January", "February", "March", "April", "May", 
        "June", "July", "August", "September", "October", 
        "November", "December"];

        return $months;
    }
}

if (! function_exists('_top_superadmin_professions')) {

    function _top_superadmin_professions()
    {
        $professions = Top_Profession::select("profession_id as id", "profession")->take(10)->get();
        if($professions){
            $professions = array_map(function($ppr){
                $url = Profession::select("url")->find($ppr['id']);
                $ppr["url"] = $url->url;
                return $ppr;
            }, $professions->toArray());
        }   

        return $professions;
    }
}


if (! function_exists('_professions')) {

    function _professions()
    {
        $professions = Profession::select("id", "title as profession", "cpd_requirements as units", "url")->get()->toArray();
        $professions = array_map(function($pro){
            $courses = Course::whereRaw("JSON_CONTAINS(profession_id, '\"$pro[id]\"')")->where([
                "prc_status" => "approved",
                "deleted_at" => null,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get()->count();

            if($courses > 0){
                $pro["total_courses"] = $courses;
            }

            $webinars = Webinar::whereRaw("JSON_CONTAINS(profession_id, '\"$pro[id]\"')")->where([
                "prc_status" => "approved",
                "deleted_at" => null,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get()->count();

            if($webinars > 0){
                $pro["total_webinars"] = $webinars;
            }

            $pro["totals"] = ($courses ? $courses : 0) + ($webinars ? $webinars : 0);
            return $pro;
        }, $professions);

        $professions = array_filter($professions, function($pro){
            if(array_key_exists("total_courses", $pro)){
                return $pro;
            }

            if(array_key_exists("total_webinars", $pro)){
                return $pro;
            }
        });
        
        return array_values($professions);
    }
}

if (! function_exists('_all_professions')) {

    function _all_professions()
    {
        $professions = Profession::select("id", "title as profession", "cpd_requirements as units", "url")->get()->toArray();
        
        
        return $professions;
    }
}

if (! function_exists('_get_profession')) {

    function _get_profession($id)
    {
        return Profession::select("id", "title as profession")->find($id);
    }
}

if (! function_exists('_get_all_superadmin')) {

    function _get_all_superadmin()
    {
        return User::where('superadmin','active')->get();
    }
}

if (! function_exists('_logs')) {

    function _logs(string $module, string $activity)
    {
        $log = new Logs;
        $log->module = $module;
        $log->activity = $activity;
        $log->by = Auth::user()->id ?? 0;
        $log->created_at = date("Y-m-d H:i:s");
        $log->save();
    }
}

if (! function_exists('_get_time_difference')) {

    function _get_time_difference(string $date)
    {
        $datetime = new DateTime($date);
        $interval = date_create('now')->diff( $datetime );
        $suffix = ( $interval->invert ? ' ago' : '' );
        if ( $v = $interval->y >= 1 ) return pluralize( $interval->y, 'year' ) . $suffix;
        if ( $v = $interval->m >= 1 ) return pluralize( $interval->m, 'month' ) . $suffix;
        if ( $v = $interval->d >= 1 ) return pluralize( $interval->d, 'day' ) . $suffix;
        if ( $v = $interval->h >= 1 ) return pluralize( $interval->h, 'hour' ) . $suffix;
        if ( $v = $interval->i >= 1 ) return pluralize( $interval->i, 'minute' ) . $suffix;
        return pluralize( $interval->s, 'second' ) . $suffix;
    }

    function pluralize( $count, $text )
    {
        return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
    }
}

if (! function_exists('_to_sql_operator')) {
   
    function _to_sql_operator($keyword, $lookup) : string
    {
        switch ($keyword) {
            case 'like':
                return ' LIKE "%'.$lookup.'%"';
            break;

            case 'nlike':
                return ' NOT LIKE "%'.$lookup.'%"';
            break;

            default:
                return $keyword."'".$lookup."'";
            break;
        }
    }
}

/**
 * This fetches all providers the current authenticated user is associated with
 * 
 */
if (! function_exists('_providers')) {

    function _providers()
    {
        $provider_ids[] = Co_Provider::select("provider_id as id")->where("user_id", "=", Auth::user()->id)->where("status", "=", "active")->get();
        $provider_ids[] = Instructor::select("provider_id as id")->where("user_id", "=", Auth::user()->id)->where("status", "=", "active")->get();
        $prov = [];
        foreach( $provider_ids as $id_group){
            foreach($id_group as $id){
                $prov[] =$id->id;
            }
        }
        $providers = [];
        if(count($prov)){
            $providers = Provider::whereIn("id", $prov)->get();
        }
        return $providers;
    }
}

/**
 * This fetches all courses of the provider
 * 
 */
if (! function_exists('_provider_courses')) {

    function _provider_courses($provider_id, $count = false)
    {
        if($count){
            $courses = Course::select("id")->where([
                "provider_id" => $provider_id,
                "prc_status" => "approved",
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

            return $courses->count();
        }else{
            $courses = Course::where([
                "provider_id" => $provider_id,
                "prc_status" => "approved",
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

            return $courses;    
        }
        
    }
}

/**
 * This fetches all webinars of the provider
 * 
 */
if (! function_exists('_provider_webinars')) {

    function _provider_webinars($provider_id, $count = false)
    {
        if($count){
            $webinars = Webinar::select("id")->where([
                "provider_id" => $provider_id,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

            return $webinars->count();
        }else{
            $webinars = Webinar::where([
                "provider_id" => $provider_id,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

            return $webinars;    
        }
        
    }
}

/**
 * This fetches all courses of the provider
 * 
 */
if (! function_exists('_provider_webinars')) {

    function _provider_webinars($provider_id, $count = false)
    {
        if($count){
            $webinars = Webinar::select("id")->where([
                "provider_id" => $provider_id,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

            return $webinars->count();
        }else{
            $webinars = Webinar::where([
                "provider_id" => $provider_id,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

            return $webinars;    
        }
    }
}

/**
 * This fetches all instructors of the provider
 * 
 */
if (! function_exists('_provider_instructors')) {

    function _provider_instructors($provider_id, $count = false)
    {
        if($count){
            $instructors = Instructor::select("id")->where([
                "provider_id" => $provider_id,
            ])->whereIn("status", ["active", "inactive"])->get();
    
            return $instructors->count();
        }else{
            $instructors = Instructor::with("profile")->where([
                "provider_id" => $provider_id,
            ])->whereIn("status", ["active", "inactive"])->get();
    
            return $instructors;
        }
    }
}

/**
 * This fetches all profession of the provider
 * 
 */
if (! function_exists('_provider_professions')) {

    function _provider_professions($provider_id)
    {
        $professions = [];

        $provider = Provider::select("profession_id")->find($provider_id);
        if($provider){
            $professions = Profession::select("title", "cpd_requirements as cpd")
                ->whereIn("id", json_decode($provider->profession_id))->get();
        }

        return $professions;
    }
}

/**
 * This checks if the current in session provider details and current authenticated user
 * is a co-provider or provider admin itself
 * 
 */
if (! function_exists('_is_co_provider')) {

    function _is_co_provider($provider_id)
    {
        $find = Co_Provider::select("id")->where([
            "user_id" => Auth::user()->id,
            "status" => "active",
            "provider_id" => $provider_id
        ])->first();
        
        return $find ?? false;
    }

    function _is_co_provider_check($user_id,$provider_id)
    {
        $find = Co_Provider::select("id")->where([
            "user_id" => $user_id,
            "status" => "active",
            "provider_id" => $provider_id
        ])->first();
        
        return $find ?? false;
    }
}

/**
 * This checks if the current in session provider details and current authenticated user
 * is a co-provider or provider admin itself
 * 
 */
if (! function_exists('_is_provider_instructor')) {

    function _is_provider_instructor($user_id, $provider_id)
    {
        $find = Instructor::select("id")->where([
            "user_id" => $user_id,
            "status" => "active",
            "provider_id" => $provider_id
        ])->first();
        
        return $find ? true : false;
    }
}

/**
 * This fetches the current session provider
 * 
 */
if (! function_exists('_current_provider')) {

    function _current_provider()
    {
        $provider = Provider::find(Session::get('session_provider_id')->id);
        if($provider){
            $provider->request = Profile_Requests::select("status")->where([
                "type" => "provider",
                "data_id" => $provider->id,
            ])->orderBy("created_at", "desc")->first();
        }
        return $provider;
    }
}

/**
 * This fetches the current session provider
 * 
 */
if (! function_exists('_current_course')) {

    function _current_course()
    {
        $course = Course::find(Session::get('session_course_id'));
        return $course;
    }
}


/**
 * This fetches the current session provider
 * 
 */
if (! function_exists('_current_webinar')) {

    function _current_webinar()
    {
        $webinar = Webinar::find(Session::get('session_webinar_id'));
        return $webinar;
    }
}

/**
 * This fetches current checked/done in course creation 
 * 
 */
if (! function_exists('_course_creation_check')) {

    function _course_creation_check($module)
    {
        if(_current_course()){
            $id = _current_course()->id;
            $course = Course::find($id);

            switch ($module) {
                case 'course_details':
                        if($course->title && $course->profession_id && $course->headline && $course->description && $course->session_start && $course->session_end && $course->language){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'attract_enrollments':
                        if($course->course_poster != null && $course->course_video != null && ($course->objectives != null && count(json_decode($course->objectives)) > 0) && ($course->requirements != null && count(json_decode($course->requirements)) > 0) && ($course->target_students != null && count(json_decode($course->target_students)) > 0)){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'instructors':
                        if($course->instructor_id){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'video_content':
                        $sections = Section::where([
                            "type"=>"course",
                            "data_id"=>$id,
                            "deleted_at"=>null
                        ])->get();
                        $proceed = true;
                        if($sections && $sections->count() > 0){
                            $video_total = 0;
                            $quiz_total = 0;
                            foreach ($sections as $key => $sec) {
                                if($sec->sequences!=null){

                                    $sequences = json_decode($sec->sequences);
                                    foreach ($sequences as $key => $seq) {

                                        if($seq->type == "quiz"){
                                            $quiz = Quiz::where([
                                                "id" => $seq->id,
                                                "deleted_at" => null
                                            ])->first();

                                            if($quiz){
                                                $items = Quiz_Item::where([
                                                    "quiz_id" => $quiz->id,
                                                    "deleted_at" => null
                                                ])->get()->count();
                                                
                                                if($items==0){
                                                    $proceed = false;
                                                    break;
                                                }

                                                $quiz_total++;
                                            }else{
                                                $proceed = false;
                                                break;
                                            }
                                        }

                                        if($seq->type == "video"){
                                            $video = Video::where([
                                                "id" => $seq->id,
                                                "deleted_at" => null,
                                            ])->first();

                                            if($video && $video->cdn_url != null){
                                            }else{
                                                $proceed = false;
                                                break;
                                            }

                                            $video_total++;
                                        }
                                    }
                                }else{
                                    $proceed = false;
                                    break;
                                }
                            }

                            if($video_total==0||$quiz_total==0){
                                $proceed = false;
                                break;
                            }
                        }else{ $proceed = false; }
                        return $proceed;
                    break;
    
                case 'handouts':
                        if($course->allow_handouts===0){
                            return true;
                        }

                        if($course->allow_handouts==1){
                            $handouts = Handout::where([
                                "type" => "course",
                                "data_id" => $id,
                                "deleted_at" => null
                            ])->get();
                            if(! $handouts->isEmpty()){
                                return true;
                            }
                        }

                        return false;
                    break;
    
                case 'grading':
                        if($course->assessment != null){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'submit_accreditation':
                        $accreditor = $course->submit_accreditation_evaluation!=null ? json_decode($course->submit_accreditation_evaluation) : null;
                        if($accreditor==null || $accreditor->evaluation==false){
                            return false;
                        }
                        return true;
                    break;
    
                case 'publish':
                        if($course->prc_status == "approved" && $course->approved_at != null && $course->published_at != null && $course->program_accreditation_no != null){
                            return true;
                        }
                        return false;
                    break;
    
                case 'accreditor_account':
                    # code...
                    break;

                case 'preview_live':
                    if($course->course_poster != null && ($course->objectives != null && count(json_decode($course->objectives)) > 0) && ($course->requirements != null && count(json_decode($course->requirements)) > 0) && ($course->target_students != null && count(json_decode($course->target_students)) > 0)){
                        $sections = Section::where([
                            "type"=>"course",
                            "data_id"=>$id,
                            "deleted_at"=>null
                        ])->get();
                        
                        $proceed = false;
                        if($sections && $sections->count() > 0){
                            foreach ($sections as $key => $sec) {
                                if($sec->sequences!=null){
                                    $proceed = true;
                                }
                            }
                        }

                        return $proceed;
                    }

                    return false;
                break;
            }
        }
    }
}

/**
 * This fetches current checked/done in course creation 
 * 
 */
if (! function_exists('_webinar_creation_check')) {

    function _webinar_creation_check($module)
    {
        if(_current_webinar()){
            $id = _current_webinar()->id;
            $webinar = Webinar::find($id);

            switch ($module) {
                case 'webinar_details':
                        $schedule = Webinar_Session::where("webinar_id", "=", $id)->get();
                        if($webinar->title && $webinar->headline && $webinar->description && $webinar->language && $schedule->count() > 0){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'attract_enrollments':
                        if($webinar->webinar_poster != null && $webinar->webinar_video != null && ($webinar->objectives != null && count(json_decode($webinar->objectives)) > 0) && ($webinar->requirements != null && count(json_decode($webinar->requirements)) > 0) && ($webinar->target_students != null && count(json_decode($webinar->target_students)) > 0)){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'instructors':
                        if($webinar->instructor_id){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'video_content':
                        $sections = Section::where([
                            "type"=>"webinar",
                            "data_id"=>$id,
                            "deleted_at"=>null
                        ])->get();
                        $proceed = true;
                        if($sections && $sections->count() > 0){
                            $video_total = 0;
                            $quiz_total = 0;
                            foreach ($sections as $key => $sec) {
                                if($sec->sequences!=null){

                                    $sequences = json_decode($sec->sequences);
                                    foreach ($sequences as $key => $seq) {

                                        if($seq->type == "quiz"){
                                            $quiz = Quiz::where([
                                                "id" => $seq->id,
                                                "deleted_at" => null
                                            ])->first();

                                            if($quiz){
                                                $items = Quiz_Item::where([
                                                    "quiz_id" => $quiz->id,
                                                    "deleted_at" => null
                                                ])->get()->count();
                                                
                                                if($items==0){
                                                    $proceed = false;
                                                    break;
                                                }
                                                $quiz_total++;
                                            }else{
                                                $proceed = false;
                                                break;
                                            }
                                        }

                                        if($seq->type == "video"){
                                            $video = Video::where([
                                                "id" => $seq->id,
                                                "deleted_at" => null,
                                            ])->first();

                                            if($video && $video->title != null){
                                            }else{
                                                $proceed = false;
                                                break;
                                            }
                                            $video_total++;
                                        }
                                    }
                                }else{
                                    $proceed = false;
                                    break;
                                }
                            }

                            if($video_total==0||$quiz_total==0){
                                $proceed = false;
                                break;
                            }
                        }else{ $proceed = false; }
                        return $proceed;
                    break;
    
                case 'handouts':
                        if($webinar->allow_handouts===0){
                            return true;
                        }

                        if($webinar->allow_handouts==1){
                            $handouts = Handout::where([
                                "type" => "webinar",
                                "data_id" => $id,
                                "deleted_at" => null
                            ])->get();;
                            if(! $handouts->isEmpty()){
                                return true;
                            }
                        }

                        return false;
                    break;
    
                case 'grading':
                        if($webinar->assessment != null){
                            return true;
                        }else{
                            return false;
                        }
                    break;
    
                case 'links':
                    $sessions = Webinar_Session::where("webinar_id", "=", $webinar->id)->get();
                    if($sessions->count() == 0){
                        return false;
                    }

                    foreach($sessions as $session){
                        if($session->link==null){
                            return false;
                        }
                    }
                    
                    return true;
                break;

                case 'submit_accreditation':
                        if($webinar->offering_units=="without"){
                            return true;
                        }

                        $accreditor = $webinar->submit_accreditation_evaluation!=null ? json_decode($webinar->submit_accreditation_evaluation) : null;
                        if($accreditor==null || $accreditor->evaluation==false){
                            return false;
                        }
                        
                        return true;
                    break;
    
                case 'publish':
                        if($webinar->offering_units=="without"){
                            if($webinar->published_at != null){
                                return true;
                            }
                        }else{
                            if($webinar->prc_status == "approved" && $webinar->approved_at != null && $webinar->published_at != null && $webinar->accreditation != null){
                                return true;
                            }
                        }
                        return false;
                    break;
    
                case 'accreditor_account':
                    # code...
                    break;

                case 'preview_live':
                    if($webinar->webinar_poster != null && ($webinar->objectives != null && count(json_decode($webinar->objectives)) > 0) && ($webinar->requirements != null && count(json_decode($webinar->requirements)) > 0) && ($webinar->target_students != null && count(json_decode($webinar->target_students)) > 0)){
                        $sections = Section::where([
                            "type"=>"webinar",
                            "data_id"=>$id,
                            "deleted_at"=>null
                        ])->get();
                        
                        $proceed = false;
                        if($sections && $sections->count() > 0){
                            foreach ($sections as $key => $sec) {
                                if($sec->sequences!=null){
                                    $proceed = true;
                                }
                            }
                        }

                        return $proceed;
                    }

                    return false;
                break;
            }
        }
    }
}

/**
 * This fetches current checked/done in course creation 
 * 
 */
if (! function_exists('_course_progress')) {

    function _course_progress($id)
    {
        $progress = 0;
        $course = Course::find($id);
        if($course){
            $modules = [
                "course_details", "attract_enrollments", "instructors",
                "video_content", "handouts", "grading", "submit_accreditation", "publish"
            ];
            foreach($modules as $module){
                switch ($module) {
                    case 'course_details':
                            if($course->title && $course->profession_id && $course->headline && $course->description && $course->session_start && $course->session_end && $course->language){
                                $progress++;
                            }
                        break;
        
                    case 'attract_enrollments':
                            if($course->course_poster != null && $course->course_video != null && ($course->objectives != null && count(json_decode($course->objectives)) > 0) && ($course->requirements != null && count(json_decode($course->requirements)) > 0) && ($course->target_students != null && count(json_decode($course->target_students)) > 0)){
                                $progress++;
                            }
                        break;
        
                    case 'instructors':
                            if($course->instructor_id){
                                $progress++;
                            }
                        break;
        
                    case 'video_content':
                            $sections = Section::where([
                                "type"=>"course",
                                "data_id"=>$id,
                                "deleted_at"=>null
                            ])->get();
                            $proceed = 1;
                            if($sections && $sections->count() > 0){
        
                                foreach ($sections as $key => $sec) {
                                    if($sec->sequences!=null){
        
                                        $sequences = json_decode($sec->sequences);
                                        foreach ($sequences as $key => $seq) {
        
                                            if($seq->type == "quiz"){
                                                $quiz = Quiz::where([
                                                    "id" => $seq->id,
                                                    "deleted_at" => null
                                                ])->first();
        
                                                if($quiz){
                                                    $items = Quiz_Item::where([
                                                        "quiz_id" => $quiz->id,
                                                        "deleted_at" => null
                                                    ])->get()->count();
                                                    
                                                    if($items==0){
                                                        $proceed = 0;
                                                        break;
                                                    }
                                                }else{
                                                    $proceed = 0;
                                                    break;
                                                }
                                            }
        
                                            if($seq->type == "video"){
                                                $video = Video::where([
                                                    "id" => $seq->id,
                                                    "deleted_at" => null,
                                                ])->first();
        
                                                if($video && $video->cdn_url != null){
                                                }else{
                                                    $proceed = 0;
                                                    break;
                                                }
                                            }
                                        }
                                    }else{
                                        $proceed = 0;
                                        break;
                                    }
                                }
        
                            }else{ $proceed = 0; }
                            $progress+=$proceed;
                        break;
        
                    case 'handouts':
                            if($course->allow_handouts===0){
                                $progress++;
                            }
        
                            if($course->allow_handouts==1){
                                $handouts = Handout::where([
                                    "type" => "course",
                                    "data_id" => $id,
                                    "deleted_at" => null
                                ])->get();;
                                if(! $handouts->isEmpty()){
                                    $progress++;
                                }
                            }
                        break;
        
                    case 'grading':
                            if($course->assessment != null){
                                $progress++;
                            }
                        break;
        
                    case 'submit_accreditation':
                            $accreditor = $course->submit_accreditation_evaluation!=null ? json_decode($course->submit_accreditation_evaluation) : null;
                            if($accreditor==null || $accreditor->evaluation==false){
                            }else{
                                $progress++;
                            }
                        break;
        
                    case 'publish':
                            if($course->prc_status == "approved" && $course->approved_at != null && $course->published_at != null && $course->program_accreditation_no != null){
                                $progress++;
                            }
                        break;
                }
            }
        }

        $total = 0;
        $total = $progress==0 ? 0 : $progress / 8;
        return $total*100;
    }
}

/**
 * This fetches current checked/done in course creation 
 * 
 */
if (! function_exists('_webinar_progress')) {

    function _webinar_progress($id)
    {
        $progress = 0;
        $webinar = Webinar::find($id);
        if($webinar){
            $modules = [
                "webinar_details", "attract_enrollments", "instructors",
                "content", "handouts", "grading", "submit_accreditation", "publish"
            ];

            foreach($modules as $module){
                switch ($module) {
                    case 'webinar_details':
                            $schedule = Webinar_Session::where("webinar_id", "=", $id)->get();
                            if($webinar->title && $webinar->headline && $webinar->description && $webinar->language && $schedule->count() > 0){
                                $progress++;
                            }
                        break;
        
                    case 'attract_enrollments':
                            if($webinar->webinar_poster != null && $webinar->webinar_video != null && ($webinar->objectives != null && count(json_decode($webinar->objectives)) > 0) && ($webinar->requirements != null && count(json_decode($webinar->requirements)) > 0) && ($webinar->target_students != null && count(json_decode($webinar->target_students)) > 0)){
                                $progress++;
                            }
                        break;
        
                    case 'instructors':
                            if($webinar->instructor_id){
                                $progress++;
                            }
                        break;
        
                    case 'content':
                            $sections = Section::where([
                                "type"=>"webinar",
                                "data_id"=>$id,
                                "deleted_at"=>null
                            ])->get();
                            $proceed = 1;
                            if($sections && $sections->count() > 0){
        
                                foreach ($sections as $key => $sec) {
                                    if($sec->sequences!=null){
        
                                        $sequences = json_decode($sec->sequences);
                                        foreach ($sequences as $key => $seq) {
        
                                            if($seq->type == "quiz"){
                                                $quiz = Quiz::where([
                                                    "id" => $seq->id,
                                                    "deleted_at" => null
                                                ])->first();
        
                                                if($quiz){
                                                    $items = Quiz_Item::where([
                                                        "quiz_id" => $quiz->id,
                                                        "deleted_at" => null
                                                    ])->get()->count();
                                                    
                                                    if($items==0){
                                                        $proceed = 0;
                                                        break;
                                                    }
                                                }else{
                                                    $proceed = 0;
                                                    break;
                                                }
                                            }
        
                                            if($seq->type == "video"){
                                                $video = Video::where([
                                                    "id" => $seq->id,
                                                    "deleted_at" => null,
                                                ])->first();
        
                                                if($video){
                                                }else{
                                                    $proceed = 0;
                                                    break;
                                                }
                                            }
                                        }
                                    }else{
                                        $proceed = 0;
                                        break;
                                    }
                                }
        
                            }else{ $proceed = 0; }
                            $progress+=$proceed;
                        break;
        
                    case 'handouts':
                            if($webinar->allow_handouts===0){
                                $progress++;
                            }
        
                            if($webinar->allow_handouts==1){
                                $handouts = Handout::where([
                                    "type" => "webinar",
                                    "data_id" => $id,
                                    "deleted_at" => null
                                ])->get();;
                                if(! $handouts->isEmpty()){
                                    $progress++;
                                }
                            }
                        break;
        
                    case 'grading':
                            if($webinar->assessment != null){
                                $progress++;
                            }
                        break;
        
                    case 'submit_accreditation':
                            if($webinar->offering_units!="without"){
                                $accreditor = $webinar->submit_accreditation_evaluation!=null ? json_decode($webinar->submit_accreditation_evaluation) : null;
                                if($accreditor==null || $accreditor->evaluation==false){
                                    if($webinar->offering=="without"){
                                        $progress++;
                                    }
                                }else{
                                    $progress++;
                                }
                            }else{
                                $progress++;
                            }
                        break;
        
                    case 'publish':
                            if($webinar->offering_units!="without"){
                                if($webinar->prc_status == "approved" && $webinar->approved_at != null && $webinar->published_at != null && $webinar->accreditation != null){
                                    $progress++;
                                }
                            }else{
                                if($webinar->published_at != null){
                                    $progress++;
                                }
                            }
                        break;
                }
            }
        }

        $total = 0;
        $total = $progress==0 ? 0 : $progress / 8;
        return $total*100;
    }
}

if (! function_exists('_webinar_content_parts_length')) {

    function _webinar_content_parts_length($id)
    {
        $result = [];
        $total_length = 0;
        $total_parts = 0;
        $total_article = 0;
        $total_quiz = 0;
        $remainder = 0;

        $sections = Section::where([
            "type"=>"webinar",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        switch ($seq->type) {
                            case 'article':
                                $article = Article::find($seq->id);
                                if($article && $article->deleted_at==null){
                                    $explode = explode(".",$article->reading_time);
                                    $convert = $explode[0];
                                    if(isset($explode[1])){
                                        $remainder += $explode[1];
                                    }

                                    $total_length += (int)$convert;
                                    $total_parts += 1;
                                    $total_article += 1;
                                }
                                break;

                            case 'quiz':
                                $quiz = Quiz::find($seq->id);
                                if($quiz && $quiz->deleted_at==null){
                                    $items = Quiz_Item::where([
                                        "quiz_id" => $quiz->id,
                                        "deleted_at" => null
                                    ])->first();

                                    if($items){
                                        $explode = explode(".",$quiz->reading_time);
                                        $convert = $explode[0];
                                        if(isset($explode[1])){
                                            $remainder += $explode[1];
                                        }
    
                                        $total_length += (int)$convert;
                                        $total_parts += 1;
                                        $total_quiz += 1;
                                    }
                                }
                                break;
                        }
                    }
                }
            }

            $quotient = ($remainder >= 60) ? ($remainder / 60) : 1;
            $total_length = floor($quotient+$total_length);
        }

        return [
            "parts" => $total_parts,
            "length" => $total_length,
            "quiz" => $total_quiz,
            "article" => $total_article,
        ];
    } 
}


if (! function_exists('_course_content_parts_length')) {

    function _course_content_parts_length($id)
    {
        $result = [];
        $total_length = 0;
        $total_parts = 0;
        $remainder = 0;

        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        switch ($seq->type) {
                            case 'video':
                                $video = Video::find($seq->id);
                                if($video && $video->deleted_at==null && $video->cdn_url!=null){
                                    $explode = explode(":",$video->length);
                                    $convert = $explode[0];
                                    $remainder += $explode[1];

                                    $total_length += (int)$convert;
                                    $total_parts += 1;
                                }
                                break;

                            case 'article':
                                $article = Article::find($seq->id);
                                if($article && $article->deleted_at==null){
                                    $explode = explode(".",$article->reading_time);
                                    $convert = $explode[0];
                                    if(isset($explode[1])){
                                        $remainder += $explode[1];
                                    }

                                    $total_length += (int)$convert;
                                    $total_parts += 1;
                                }
                                break;

                            case 'quiz':
                                $quiz = Quiz::find($seq->id);
                                if($quiz && $quiz->deleted_at==null){
                                    $items = Quiz_Item::where([
                                        "quiz_id" => $quiz->id,
                                        "deleted_at" => null
                                    ])->first();

                                    if($items){
                                        $explode = explode(".",$quiz->reading_time);
                                        $convert = $explode[0];
                                        if(isset($explode[1])){
                                            $remainder += $explode[1];
                                        }
    
                                        $total_length += (int)$convert;
                                        $total_parts += 1;
                                    }
                                }
                                break;
                        }
                    }
                }
            }

            $quotient = ($remainder >= 60) ? ($remainder / 60) : 1;
            $total_length = floor($quotient+$total_length);
        }

        return [
            "parts" => $total_parts,
            "length" => $total_length
        ];
    } 
}

if (! function_exists('_course_total_video_length')) {

    function _course_total_video_length($id)
    {
        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            $total_time = 0;

            $accu_hours = 0;
            $accu_minutes = 0;
            $accu_seconds = 0;
            
            $remainder = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "video"){
                            $video = Video::find($seq->id);
                            if($video && $video->deleted_at==null && $video->cdn_url!=null){

                                $exploded_video_time = explode(":", $video->length);
                                if(count($exploded_video_time) == 3){
                                    $accu_hours += $exploded_video_time[0];
                                    $accu_minutes += $exploded_video_time[1];
                                    $accu_seconds += $exploded_video_time[2];
                                }else{
                                    $accu_minutes += $exploded_video_time[0];
                                    $accu_seconds += $exploded_video_time[1];
                                }
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }

            $minutes_from_seconds = ($accu_seconds >= 60) ? floor($accu_seconds / 60) : 0;
            $total_minutes = $minutes_from_seconds+$accu_minutes;
            $total_seconds = $accu_seconds > 0 ? $accu_seconds - ($minutes_from_seconds * 60) : 0;

            $divided_minutes = $total_minutes / 60;
            $dm_whole = floor($divided_minutes);
            $dm_decimal = $divided_minutes - $dm_whole;

            $minutes = $dm_decimal * 60;
            $hours = $dm_whole + $accu_hours;

            $hours = $hours<10&&$hours>=0 ? sprintf("%02d", $hours) : $hours;
            $minutes = $minutes<10&&$minutes>=0 ? sprintf("%02d", $minutes) : $minutes;
            $seconds = $total_seconds<10&&$total_seconds>=0 ? sprintf("%02d", $total_seconds) : $total_seconds;

            return "$hours:$minutes:$seconds";
        }

        return "00:00:00";
    }
}

if (! function_exists('_course_total_time_length')) {

    function _course_total_time_length($id)
    {
        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        if($sections && $sections->count() > 0){
            
            $total_course_length = 0;
            $remainder = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "video"){
                            $video = Video::find($seq->id);
                            if($video && $video->deleted_at==null && $video->cdn_url!=null){
                                
                                $explode = explode(":",$video->length);
                                $convert = $explode[0];
                                $remainder += $explode[1];
    
                                $total_course_length += (int)$convert;
                            }
                        }else if($seq->type == "article"){
                            $article = Article::find($seq->id);
                            if($article && $article->deleted_at==null){
                                $computed = _estimated_reading_time($article->description);
                                $explode = explode(".",$computed);
                                $convert = $explode[0];
                                $remainder += $explode[1];
    
                                $total_course_length += (int)$convert;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
            $quotient = ($remainder >= 60) ? floor($remainder / 60) : 0;
            $total_minutes = $quotient+$total_course_length;
            $divided_minutes = $total_minutes / 60;
            $dm_whole = floor($divided_minutes);
            $dm_decimal = $divided_minutes - $dm_whole;
            $seconds = ($remainder < 60) ? $remainder : 0;
            $minutes = $dm_decimal * 60;
            $hours = $dm_whole;
            $hours = $hours<10&&$hours>=0 ? sprintf("%02d", $hours) : $hours;
            $minutes = $minutes<10&&$minutes>=0 ? sprintf("%02d", $minutes) : $minutes;
            $seconds = $seconds<10&&$seconds>=0 ? sprintf("%02d", $seconds) : $seconds;
            
    
            if($hours == "00" && $minutes == "00" && $seconds == "00"){
                return "Not defined";
            }else{
                if($hours == "00" && $minutes !== "00" && $seconds !== "00"){
                    return "$minutes min(s) $seconds s";
                }elseif($hours == "00" && $minutes == "00" && $seconds !== "00"){
                    return "$seconds s";
                }elseif($hours == "00" && $minutes !== "00" && $seconds !== "00"){
                    return "$minutes min(s) $seconds s";
                }elseif($hours !== "00" && $minutes == "00" && $seconds !== "00"){
                    return "$hours hr(s) $seconds s";
                }elseif($hours !== "00" && $minutes !== "00" && $seconds == "00"){
                    return "$hours hr(s) $minutes min(s)";
                }else{
                    return "$hours hr(s) $minutes min(s) $seconds s";
                }
                
            }
            
        }
    
        return "00:00:00";
    }
    }

if (! function_exists('_course_total_videos')) {

    function _course_total_videos($id)
    {
        $total = 0;
        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "video"){
                            $video = Video::find($seq->id);
                            if($video && $video->deleted_at==null && $video->cdn_url!=null){
                                $total += 1;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
        }

        return $total;
    }
}

if (! function_exists('_course_total_quizzes')) {

    function _course_total_quizzes($id)
    {
        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            $total_quizzes = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "quiz"){
                            $quiz = Quiz::find($seq->id);
                            if($quiz && $quiz->deleted_at==null){
                                $total_quizzes += 1;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
            return $total_quizzes;
        }
        return 0;
    }
}

if (! function_exists('_webinar_total_quizzes')) {

    function _webinar_total_quizzes($id)
    {
        $sections = Section::where([
            "type"=>"webinar",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            $total_quizzes = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "quiz"){
                            $quiz = Quiz::find($seq->id);
                            if($quiz && $quiz->deleted_at==null){
                                $total_quizzes += 1;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
            return $total_quizzes;
        }
        return 0;
    }
}

if (! function_exists('_webinar_total_article')) {

    function _webinar_total_article($id)
    {
        $sections = Section::where([
            "type"=>"webinar",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            $total_article = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "article"){
                            $article = Article::find($seq->id);
                            if($article && $article->deleted_at==null){
                                $total_article += 1;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
            return $total_article;
        }
        return 0;
    }
}

if (! function_exists('_course_total_article')) {

    function _course_total_article($id)
    {
        $sections = Section::where([
            "type"=>"course",
            "data_id"=>$id,
            "deleted_at"=>null
        ])->get();
        
        if($sections && $sections->count() > 0){
            $total_article = 0;
            foreach ($sections as $key => $sec) {
                if($sec->sequences!=null){
                    $sequences = json_decode($sec->sequences);
                    foreach ($sequences as $key => $seq) {
                        if($seq->type == "article"){
                            $article = Article::find($seq->id);
                            if($article && $article->deleted_at==null){
                                $total_article += 1;
                            }
                        }else{
                            continue;
                        }
                    }
                }
            }
            return $total_article;
        }
        return 0;
    }
}

if (! function_exists('_course_total_handout')) {

    function _course_total_handout($id)
    {
        $handouts = Handout::where([
            "type" => "course",
            "data_id" => $id,
            "deleted_at" => null
        ])->get();
        
        return $handouts;
    }
}

/**
 * This saves information of profile request used by either Provider or Instructor
 * 
 */
if (! function_exists('_profile_request')) {

    function _profile_request(array $data_array)
    {
        // default status is in-review
        $find = Profile_Requests::where([
            "type" => $data_array["type"],
            "data_id" => $data_array["data_id"],
            // "status" => "in-review"
            "status" => "approve"
        ])->orderBy("created_at", "desc")->first();

        if($find){
            Profile_Requests::where([
                "type" => $data_array["type"],
                "data_id" => $data_array["data_id"],
                // "status" => "in-review"
                "status" => "approve"
            ])->orderBy("created_at", "desc")->take(1)
            ->update($data_array);
            return true;
        }else{
            Profile_Requests::insert(array_merge($data_array, ["created_at"=>date("Y-m-d H:i:s")]));
            return true;
        }

        return false;
    }
}

/**
 * This counts the number of incomplete details in PRC Resume
 * 
 */
if (! function_exists('_count_instructor_resume')) {

    function _count_instructor_resume($user_id)
    {
        $counter = 0;

        $resume = Instructor_Resume::where([
            "user_id" => $user_id,
            "provider_id" => null,
            "deleted_at" => null,
        ])->first();
        
        if($resume){
            if($resume->image==null){
                $counter++;
            }

            if($resume->nickname==null){
                $counter++;
            }

            if($resume->pic_identifications==null){
                $counter++;
            }

            if($resume->professions==null){
                $counter++;
            }

            if($resume->residence_address==null){
                $counter++;
            }

            if($resume->business_address==null){
                $counter++;
            }

            if($resume->mobile_number==null){
                $counter++;
            }

            if($resume->landline_number==null){
                $counter++;
            }

            if($resume->nationality==null){
                $counter++;
            }

            if($resume->major_competency_areas==null){
                $counter++;
            }

            if($resume->conducted_programs==null){
                $counter++;
            }

            if($resume->attended_programs==null){
                $counter++;
            }

            if($resume->major_awards==null){
                $counter++;
            }

            if($resume->college_background==null){
                $counter++;
            }

            if($resume->post_graduate_background==null){
                $counter++;
            }

            if($resume->work_experience==null){
                $counter++;
            }

            if($resume->aipo_membership==null){
                $counter++;
            }

            if($resume->other_affiliations==null){
                $counter++;
            }
        }else{
            $counter=18;
        }

        return $counter;
    }
}

if (! function_exists('_courses_enrolled')) {

    function _courses_enrolled()
    {
        return [];
    }
}

if (! function_exists('_courses_associated')) {

    function _courses_associated()
    {
        $courses = Course::with("provider")
        ->where("prc_status", "=", "approved")
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereRaw('JSON_CONTAINS(instructor_id, \'"'.Auth::user()->id.'"\' )')->get();

        return $courses;
    }
}

if (! function_exists('_courses_associated_inst')) {

    function _courses_associated_inst($id)
    {
        $courses = Course::with("provider")
        ->where("prc_status", "=", "approved")
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereRaw("JSON_CONTAINS(instructor_id, '\"{$id}\"')")->get();

        return $courses;
    }
}

if (! function_exists('_webinars_associated_inst')) {

    function _webinars_associated_inst($id)
    {
        $webinars = Webinar::with("provider")
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereRaw("JSON_CONTAINS(instructor_id, '\"{$id}\"')")->get();

        return $webinars;
    }
}

if (! function_exists('_providers_associated_inst')) {

    function _providers_associated_inst($id)
    {
        $providers = Instructor::where([
            "user_id" => $id,
            "status" => "active",
            "deleted_at" => null,
        ])->get();

        return $providers;
    }
}

if (! function_exists('_provider_courses_associated')) {

    function _provider_courses_associated($provider_id)
    {
        $courses = Course::where([
            "provider_id" => $provider_id
        ])->where("prc_status", "=", "approved")
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])
        ->whereRaw('JSON_CONTAINS(instructor_id, \'"'.Auth::user()->id.'"\' )')->get();

        return $courses;
    }
}

if (! function_exists('_category_courses')) {
    function _category_courses($profession_id)
    {
        $courses = Course::where("prc_status", "=", "approved")
        ->whereIn("fast_cpd_status", ["published", "live", "ended"])->whereRaw('JSON_CONTAINS(profession_id, \'"'.$profession_id.'"\' )')->get();

        return $courses;
    }
}

if (! function_exists('_category_provider')) {

    function _category_provider($provider_id)
    {
        return Provider::select("name")->find($provider_id);
    }
}

if (! function_exists('_get_rating')) {

    function _get_rating($course_rating)
    {
        $rate =  Review::select("rating")->where("course_id", $course_rating)->first();
        return $rate;
    }
}

if (! function_exists('_get_course')) {

    function _get_course($course_id)
    {
        return Course::find($course_id);
    }
}

/**
 * This is the generation of permission 
 * from ui/uix to db structure
 * 
 */
if (! function_exists('_generate_permissions')) {

    function _generate_permissions($permissions, $is_admin)
    {
        $modules = [];

        if(in_array("create_courses", $permissions) || in_array("edit_courses", $permissions) || $is_admin == true){
            $modules["courses"][] = "view";
        }

        if(in_array("create_courses", $permissions) || $is_admin == true){
            $modules["courses"][] = "create";
        }

        if(in_array("edit_courses", $permissions) || $is_admin == true){
            $modules["courses"][] = "edit";
        }

        if(in_array("create_webinars", $permissions) || in_array("edit_webinars", $permissions) || $is_admin == true){
            $modules["webinars"][] = "view";
        }

        if(in_array("create_webinars", $permissions) || $is_admin == true){
            $modules["webinars"][] = "create";
        }

        if(in_array("edit_webinars", $permissions) || $is_admin == true){
            $modules["webinars"][] = "edit";
        }

        if(in_array("view_overview", $permissions) || $is_admin == true){
            $modules["overview"][] = "view";
        }

        if(in_array("view_revenue", $permissions) || $is_admin == true){
            $modules["revenue"][] = "view";
        }

        if(in_array("view_traffic_conversion", $permissions) || $is_admin == true){
            $modules["traffic_conversion"][] = "view";
        }

        if(in_array("view_review", $permissions) || $is_admin == true){
            $modules["review"][] = "view";
        }

        if(in_array("edit_provider_profile", $permissions) || $is_admin == true){
            $modules["provider_profile"][] = "view";
            $modules["provider_profile"][] = "edit";
        }

        if(in_array("manage_instructors", $permissions) || $is_admin == true){
            $modules["instructors"][] = "view";
            $modules["instructors"][] = "create";
            $modules["instructors"][] = "edit";
            $modules["instructors"][] = "delete";
        }

        if(in_array("manage_users", $permissions) || $is_admin == true){
            $modules["users"][] = "view";
            $modules["users"][] = "create";
            $modules["users"][] = "edit";
            $modules["users"][] = "delete";
        }


        $table_row = [];
        foreach ($modules as $key => $row) {
            $table_row[] = [
                "module_name" => $key,
                "view" => in_array("view", $row) ? 1 : 0,
                "create" => in_array("create", $row) ? 1 : 0,
                "edit" => in_array("edit", $row) ? 1 : 0,
                "delete" => in_array("delete", $row) ? 1 : 0,
            ];
        }

        return $table_row;
    }
}

/**
 * This is the reverse generation of permission 
 * from db to ui/uix structure
 * 
 */
if (! function_exists('_reverse_generate_permissions')) {

    function _reverse_generate_permissions($user_id, $provider_id)
    {
        $permissions = Provider_Permission::select("module_name as module", "view", "create", "edit", "delete")
        ->where([
            "user_id" => $user_id,
            "provider_id" => $provider_id,
        ])->get();

        $modules = [];
        foreach ($permissions as $key => $row) {
            switch ($row->module) {
                case 'courses':
                        if($row->create == 1 && $row->view == 1){
                            $modules[] = "create_courses";
                        }

                        if($row->edit == 1 && $row->view == 1){
                            $modules[] = "edit_courses";
                        }
                    break;

                case 'webinars':
                        if($row->create == 1 && $row->view == 1){
                            $modules[] = "create_webinars";
                        }

                        if($row->edit == 1 && $row->view == 1){
                            $modules[] = "edit_webinars";
                        }
                    break;

                case 'overview':
                        if($row->view == 1){
                            $modules[] = "view_overview";
                        }
                    break;

                case 'review':
                        if($row->view == 1){
                            $modules[] = "view_review";
                        }
                    break;

                case 'revenue':
                        if($row->view == 1){
                            $modules[] = "view_revenue";
                        }
                    break;

                case 'traffic_conversion':
                        if($row->view == 1){
                            $modules[] = "view_traffic_conversion";
                        }
                    break;

                case 'provider_profile':
                        if($row->view == 1 && $row->edit == 1){
                            $modules[] = "edit_provider_profile";
                        }
                    break;

                case 'instructors':
                        if($row->view == 1 && $row->create == 1 && $row->edit == 1 && $row->delete == 1){
                            $modules[] = "manage_instructors";
                        }
                    break;

                case 'users':
                        if($row->view == 1 && $row->create == 1 && $row->edit == 1 && $row->delete == 1){
                            $modules[] = "manage_users";
                        }
                    break;
            }
        }

        return $modules;
    }
}

/**
 * This is the permission the current user that entered provider portal
 * It catches admin, instructor and co-admin of provider
 * 
 */
if (! function_exists('_my_provider_permission')) {

    function _my_provider_permission($module, $action)
    {
        if(Auth::check() && _current_provider()){
            $details = [
                "user_id" => Auth::user()->id,
                "provider_id" => _current_provider()->id,
            ];

            /**
             * Check if Co-Admin
             * 
             */
            $findCOP = Co_Provider::where(array_merge($details, ["status"=>"active"]))->first();
            if($findCOP){
                if($findCOP->role == "admin"){
                    return true;
                }else{
                    $providerPerms = Provider_Permission::select("view", "create", "edit", "delete")
                        ->where(array_merge($details, ["module_name"=>$module]))->first();

                    if($providerPerms){
                        $providerPerms = $providerPerms->toArray();

                        return $providerPerms[$action] == 1 ? true : false;
                    }
                }
            }
            
            if($module == "courses"){
                /**
                 * Check if Instructor 
                 * 
                 */
                $findInst = Instructor::where(array_merge($details, ["status"=>"active"]))->first();
                if($findInst){
                    return true;
                }
            }

            if($module == "webinars"){
                /**
                 * Check if Instructor 
                 * 
                 */
                $findInst = Instructor::where(array_merge($details, ["status"=>"active"]))->first();
                if($findInst){
                    return true;
                }
            }
        }

        return false;
    }
}

/**
 * This is the permission the current user as instructor that entered provider portal
 * 
 */
if (! function_exists('_my_course_permission')) {

    function _my_course_permission($module)
    {
        if(Auth::check() && _current_provider() && _current_course()){
            $details = [
                "user_id" => Auth::user()->id,
                "course_id" => _current_course()->id,
            ];

            $details1 = [
                "user_id" => Auth::user()->id,
                "provider_id" => _current_provider()->id,
                "module_name" => "courses",
            ];

            /**
             * Course Details, Attract Enrollments, Instructors, Video & Content, Handouts, Grading, Accreditation, Publish
             * 
             */

            $permission = Instructor_Permissions::where($details)->first();
            $admin = Provider_Permission::where($details1)->first();
            
            if($admin){
                return true;
            }

            if($permission){

                switch ($module) {
                    case 'course_details':
                        if($permission->course_details==1){
                            return true;
                        }
                        break;

                    case 'attract_enrollments':
                        if($permission->attract_enrollments==1){
                            return true;
                        }
                        break;

                    case 'instructors':
                        if($permission->instructors==1){
                            return true;
                        }
                        break;

                    case 'video_content':
                        if($permission->video_and_content==1){
                            return true;
                        }
                        break;

                    case 'handouts':
                        if($permission->handouts==1){
                            return true;
                        }
                        break;

                    case 'grading':
                        if($permission->grading_and_assessment==1){
                            return true;
                        }
                        break;

                    case 'accreditation':
                        if($permission->submit_for_accreditation==1){
                            return true;
                        }
                        break;

                    case 'publish':
                        if($permission->price_and_publish==1){
                            return true;
                        }
                        break;
                }
            }
        }

        return false;
    }
}


/**
 * This is the permission the current user as instructor that entered provider portal
 * 
 */
if (! function_exists('_course_creation_restricted')) {

    function _course_creation_restricted($module)
    {
        if(Auth::check() && _current_provider() && _current_course()){

            if($module=="publish"){
                if(_course_creation_check("publish") && _current_course()->fast_cpd_status!="draft"){
                    return false;
                }
            }

            /**
             * If the Publish Course is submitted and completed
             */
            if($module=="accreditation"){
                if(_course_creation_check("submit_accreditation") && _current_course()->published_at!=null){
                    return false;
                }
            }

            if(_course_creation_check("submit_accreditation")){
                if($module=="accreditation" || $module=="publish"){
                }else{

                    return false;
                }
            }
        }

        return true;
    }
}

/**
 * This is the permission the current user as instructor that entered provider portal
 * 
 */
if (! function_exists('_my_webinar_permission')) {

    function _my_webinar_permission($module)
    {
        if(Auth::check() && _current_provider() && _current_webinar()){
            $details = [
                "user_id" => Auth::user()->id,
                "webinar_id" => _current_webinar()->id,
            ];

            $details1 = [
                "user_id" => Auth::user()->id,
                "provider_id" => _current_provider()->id,
                "module_name" => "webinars",
            ];

            /**
             * Course Details, Attract Enrollments, Instructors, Video & Content, Handouts, Grading, Accreditation, Publish
             * 
             */

            $permission = Webinar_Instructor_Permission::where($details)->first();
            $admin = Provider_Permission::where($details1)->first();
            
            if($admin){
                if($module=="accreditation"){
                    if(in_array(_current_webinar()->offering_units, ["with", "both"])){
                        return true;
                    }
                }else{
                    return true;
                }
            }

            if($permission){

                switch ($module) {
                    case 'webinar_details':
                        if($permission->webinar_details==1){
                            return true;
                        }
                        break;

                    case 'attract_enrollments':
                        if($permission->attract_enrollments==1){
                            return true;
                        }
                        break;

                    case 'instructors':
                        if($permission->instructors==1){
                            return true;
                        }
                        break;

                    case 'video_content':
                        if($permission->video_and_content==1){
                            return true;
                        }
                        break;

                    case 'handouts':
                        if($permission->handouts==1){
                            return true;
                        }
                        break;

                    case 'grading':
                        if($permission->grading_and_assessment==1){
                            return true;
                        }
                        break;

                    case 'links':
                        if($permission->webinar_details==1){
                            return true;
                        }
                        break;

                    case 'accreditation':
                        if($permission->submit_for_accreditation==1){
                            if(in_array(_current_webinar()->offering_units, ["with", "both"])){
                                return true;
                            }
                        }
                        break;

                    case 'publish':
                        if($permission->price_and_publish==1){
                            return true;
                        }
                        break;
                }
            }

            if(_current_provider()->created_by == Auth::user()->id){ 
                if($module=="accreditation"){
                    if(in_array(_current_webinar()->offering_units, ["with", "both"])){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }

        return false;
    }
}


/**
 * This is the permission the current user as instructor that entered provider portal
 * 
 */
if (! function_exists('_webinar_creation_restricted')) {

    function _webinar_creation_restricted($module)
    {
        if(Auth::check() && _current_provider() && _current_webinar()){

            if($module=="publish"){
                if(_webinar_creation_check("publish") && _current_webinar()->fast_cpd_status!="draft"){
                    return false;
                }
            }

            /**
             * If the Publish Course is submitted and completed
             */
            if($module=="accreditation"){
                if(_current_webinar()->offering_units=="without"){
                    return false;
                }

                if(_webinar_creation_check("submit_accreditation") && _current_webinar()->published_at!=null){
                    return false;
                }
            }

            if(_current_webinar()->offering_units != "without" && _webinar_creation_check("submit_accreditation")){
                if($module=="accreditation" || $module=="publish"){
                }else{

                    return false;
                }
            }
        }

        return true;
    }
}

if (! function_exists('_get_instructors')) {

    function _get_instructors($provider_id)
    {
        $intructors = Instructor::select("users.name", "instructors.id as instructor_id", "users.id", "instructors.status as status")->distinct('users.name')
        ->where(["instructors.provider_id" => $provider_id, "instructors.deleted_at" => null])
        ->where("instructors.status","!=","delete")
        ->leftJoin("users","instructors.user_id","users.id")
        ->where("users.status","!=","delete")
        ->where("users.deleted_at",null)
        ->get();
  
       
        return $intructors;
    }
}

if (! function_exists('_get_auth_role')) {

    function _get_auth_role()
    {
        $user_id = Auth::user()->id;
        $provider_id = Auth::user()->provider_id;
        $as_provider = User::where("id", $user_id)
                            ->where("provider_id", $provider_id)
                            ->get();
        $as_co_provider = _is_co_provider_check($user_id,$provider_id);

        $as_instructor = Instructor::where("user_id", $user_id)
                        ->where("provider_id", $provider_id)
                        ->get();

        if(count($as_provider))
        {
            return "provider";
        }else if ($as_co_provider){
            return "co-provider";
        }else if(count($as_instructor)){
            return "instructor";
        }
  
       
    }
}

if (! function_exists('_top_professions')) {

    function _top_professions()
    {
        /**
         * Temporary data
         * 
         */

        $system_rated = System_Rating_Report::select("data_id as id")->where([
            "type" => "profession",
            "week" => date('W'),
            "month" => date('F'),
            "year" => date('Y'),
        ])->get();

        if($system_rated->count() > 0){
            return Profession::select("id", "title as profession", "cpd_requirements as units")->whereIn("id", $system_rated)->get()->toArray();
        }else{
            $courses = Course::select("id", "profession_id")->where([
                "prc_status" => "approved",
                "deleted_at" => null,
            ])->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();
            
            /**
             * 
             *  [
             *   "profession",
             *   "course_count",
             *  ]
             */
            $collection = [];

            foreach ($courses as $key => $cr) {
                $array_professions = json_decode($cr->profession_id);

                foreach($array_professions as $pr){
                    $found = array_key_exists($pr, $collection);
                    if($found){
                        $collection[$pr]["course_count"] = $collection[$pr]["course_count"] + 1;
                    }else{
                        $collection[$pr] = [
                            "profession" => $pr,
                            "course_count" => 1,
                        ];
                    }
                }
            }
            $collection = collect($collection);
            $collection = $collection->sortByDesc("course_count")->values()->pluck("profession")->take(5);

            return Profession::select("id", "title as profession", "cpd_requirements as units")->whereIn("id", $collection)->get()->toArray();
        }

        return [];
    }
}

if (! function_exists('_top_courses')) {

    function _top_courses()
    {
        /**
         * Temporary data
         * 
         */

        $system_rated = System_Rating_Report::select("data_id as id")->where([
            "type" => "course",
            "week" => date('W'),
            "month" => date('F'),
            "year" => date('Y'),
        ])->get();

        if($system_rated->count() > 0){
            return $system_rated;
        }

        return [];
    }
}

if (! function_exists('_newest_courses')) {

    function _newest_courses()
    {
        /**
         * Temporary data
         * 
         */
        $newest = Course::select("id")->whereIn("fast_cpd_status", ["published", "live"])->where([
            "prc_status" => "approved",
            "deleted_at" => null,
        ])->take(20)->orderBy('published_at', 'desc')->get();

        if($newest->count() > 0){
            return $newest;
        }

        return [];
    }
}

if (! function_exists('_more_courses')) {

    function _more_courses()
    {
        // $courses = Course::skip(0)->take(3)->get()->toArray();
        return [];
    }
}

if (! function_exists('_get_video_and_content_part')) {

    function _get_video_and_content_part($course_id, $section_id)
    {
        $sections = Section::find($section_id);
        $contents = array();
        if($sections){
            $articles = Article::where("section_id",$section_id)->where("deleted_at",null)->get();
            $videos = Video::where("section_id",$section_id)->where("deleted_at",null)->get();
            $quizes = Quiz::where("section_id",$section_id)->get();
          
            foreach($articles as $article){
                array_push($contents, (object)[
                    "article_id" => $article->id,
                    "article_title" => $article->title,
                    "article_description" => $article->description,
                    "sequence_number" => $article->sequence_number
                ]); 
            }

            foreach($videos as $video){
                array_push($contents, (object)[
                    "video_id" => $video->id,
                    "video_url" => $video->cdn_url,
                    "video_filename" => $video->filename,
                    "sequence_number" => $video->sequence_number,
                    "video_size" => $video->size,
                    "video_length" => $video->length,
                    "video_poster" => $video->poster,
                    "video_thumbnail" => $video->thumbnail,
                    "video_resolution" => $video->resolution,
                ]); 
            }

            foreach($quizes as $quiz){
                array_push($contents, (object)[
                    "quizes_id" => $quiz->id,
                    "quizes_title" => $quiz->question_title,
                    "quizes_question" => $quiz->question,
                    "quizes_explanation" => $quiz->explanation,
                    "quizes_answer" => $quiz->answer,
                    "quizes_choice_1" => $quiz->choice_1,
                    "quizes_choice_2" => $quiz->choice_2,
                    "quizes_choice_3" => $quiz->choice_3,
                    "quizes_question" => $quiz->question,
                    "sequence_number" => $quiz->sequence_number
                ]); 
            }
            usort($contents, function($first, $second){
                return $first->sequence_number > $second->sequence_number;
            });

            return $contents;
        }
        else{
            return $contents;
        }
      
    }
}

if (! function_exists('_user_type')) {

    function _user_type($id)
    {
        $user = User::find($id);
        if($user->provider_id == null && $user->instructor != "active"){     
            return "student";
        }
        else if($user->provider_id != null && $user->instructor != "active"){     
            return "provider";
        }
        else if($user->instructor == "active" && $user->provider_id == null){     
            return "instructor";
        }
        else{
            return "provider_instructor";
        }
    }
}

/**
 * Returning estimated reading time of any text html values
 * 
 */
if (! function_exists('_estimated_reading_time')) {
    function _estimated_reading_time($content) {
        $mycontent = $content;
        $word = str_word_count(strip_tags($mycontent));
        $m = floor($word / 180);
        $s = floor($word % 180 / (180 / 60));
        $est = "{$m}.{$s}";

        return $est;
    }
}

/**
 * Returning oure text html values
 * 
 */
if (! function_exists('_strip_design_text')) {
    function _strip_design_text($content) {
        $mycontent = $content;
        $word = strip_tags($mycontent);

        return $word;
    }
}

/**
 * Check if has a current in-progress/incomplete live course
 * 
 */
if (! function_exists('_get_latest_progress_live_course')) {
    function _get_latest_progress_live_course() {
        $progress = null;
        if(Auth::check()){
            $purchase_items = Purchase_Item::select("data_id as id")->where([
                "user_id" => Auth::user()->id,
                "payment_status" => "paid",
                "type" => "course",
                "fast_status" => "incomplete",
            ])->get();      

            if($purchase_items->count()>0){
                $courses = Course::select("id", "title", "url", "course_poster")->whereIn("id", $purchase_items)->whereIn("fast_cpd_status", ["live", "ended"])->get();
                if($courses && $courses->count() > 0){
                    $courses = $courses->toArray();
                    $courses = array_map(function($course){
                        $course["course_poster"] = _get_image("course", $course["id"])["small"];

                        $latest_date_update = Course_Progress::select("updated_at")->where([
                            "course_id" => $course["id"],
                            "deleted_at" => null,
                        ])->orderBy("updated_at", "desc")->first();
        
                        $course["progress"] = _get_live_course_progress($course["id"]);
                        $course["latest_update"] = $latest_date_update->updated_at ?? null;
        
                        return $course;
                    }, $courses);
        
                    $courses = collect($courses);
                    return $courses->sortByDesc("latest_update")->take(3);
                }
            }
        }
        
        return $progress;
    }
}

if(! function_exists('_get_live_course_progress')){
    function _get_live_course_progress($course_id){
        
        $total_items = 0;
        $total_progress = 0;

        $sections = Section::select("id","sequences")->where([
            "type"=>"course", "data_id"=> $course_id,
            "deleted_at" => null,
        ])->get();

        if($sections->count()>0){
            foreach($sections as $sc){
                if($sc->sequences){
                    $sequence = json_decode($sc->sequences);

                    foreach($sequence as $sq){
                        switch ($sq->type) {
                            case 'video':
                                $video = Video::where([
                                    "id"=>$sq->id, 
                                    "section_id"=>$sc->id, 
                                    "deleted_at"=>null,
                                ])->first();
                                
                                if($video){
                                    $total_items++;

                                    $progress = Course_Progress::where([
                                        "course_id" => $course_id,
                                        "user_id" => Auth::user()->id,
                                        "section_id" => $sc->id,
                                        "type" => $sq->type,
                                        "data_id" => $sq->id,
                                    ])->first();

                                    if($progress && in_array($progress->status, ["completed", "passed"])){
                                        $total_progress++;
                                    }
                                }
                                break;
        
                            case 'article':
                                $article = Article::where([
                                    "id"=>$sq->id, 
                                    "section_id"=>$sc->id, 
                                    "deleted_at"=>null,
                                ])->first();
                                
                                if($article){
                                    $total_items++;

                                    $progress = Course_Progress::where([
                                        "course_id" => $course_id,
                                        "user_id" => Auth::user()->id,
                                        "section_id" => $sc->id,
                                        "type" => $sq->type,
                                        "data_id" => $sq->id,
                                    ])->first();

                                    if($progress && in_array($progress->status, ["completed", "passed"])){
                                        $total_progress++;
                                    }
                                }
                                break;
        
                            case 'quiz':
                                $quiz = Quiz::where([
                                    "id"=>$sq->id, 
                                    "section_id"=>$sc->id, 
                                    "deleted_at"=>null,
                                ])->first();
                                
                                if($quiz){
                                    $total_items++;

                                    $progress = Course_Progress::where([
                                        "course_id" => $course_id,
                                        "user_id" => Auth::user()->id,
                                        "section_id" => $sc->id,
                                        "type" => $sq->type,
                                        "data_id" => $sq->id,
                                    ])->first();

                                    if($progress && in_array($progress->status, ["completed", "passed"])){
                                        $total_progress++;
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
        $total = $total_progress==0 ? 0 : $total_progress / $total_items;
        return $total*100;
    }
}

if(! function_exists('_get_live_course_feedback')){
    function _get_live_course_feedback($course_id, $user_id, $bool){
        
        $rating = Course_Rating::where([
            "user_id" => $user_id,
            "course_id" => $course_id,
        ])->orderBy("created_at", "desc")->first();
        $performance = CoursePerformance::where([
            "user_id" => $user_id,
            "course_id" => $course_id,
        ])->orderBy("created_at", "desc")->first();

        if($rating && $performance){
            if($bool){
                return true;
            }
            
            return [
                "rating" => $rating,
                "performance" => $performance,
            ];
        }else{
            return null;
        }
    }
}

if(! function_exists('_get_avg_rating')){
    function _get_avg_rating($type, $id){
        switch($type){
            case "course":
                $ratings = Course_Rating::select("rating")->where("course_id", "=", $id)->groupBy("user_id")->get();

                if($ratings){
                    $total_count = $ratings->count();
                    $total_ratings = $ratings->sum("rating");
                    $total_average = $total_ratings > 0 ? $total_ratings / $total_count : 0;

                    return number_format($total_average, 2, ".", "");
                }
            break;

            case "webinar":
                $ratings = Webinar_Rating::select("rating")->where("webinar_id", "=", $id)->groupBy("user_id")->get();

                if($ratings){
                    $total_count = $ratings->count();
                    $total_ratings = $ratings->sum("rating");
                    $total_average = $total_ratings > 0 ? $total_ratings / $total_count : 0;

                    return number_format($total_average, 2, ".", "");
                }
            break;
        }

        return 0;
    }
}

if(! function_exists('_get_image')){
    function _get_image($type, $id){
        switch($type){
            case "course":
                $course = Course::select("course_poster")->find($id);
                if($course && $course->course_poster){
                    $images = Image_Intervention::where([
                        "type" => "course",
                        "data_id" => $id
                    ])->first();

                    if($images){
                        return [
                            "original" => $images->original_size ?? asset("img/sample/poster-sample.png"),
                            "medium" => $images->medium_size ?? asset("img/sample/poster-sample.png"),
                            "small" => $images->small_size ?? asset("img/sample/poster-sample.png"),
                        ];
                    }

                    return [
                        "original" => $course->course_poster,
                        "medium" => $course->course_poster,
                        "small" => $course->course_poster,
                    ];
                }
            break;

            case "webinar":
                $webinar = Webinar::select("webinar_poster")->find($id);
                if($webinar && $webinar->webinar_poster){
                    $images = Image_Intervention::where([
                        "type" => "webinar",
                        "data_id" => $id
                    ])->first();

                    if($images){
                        return [
                            "original" => $images->original_size ?? asset("img/sample/poster-sample.png"),
                            "medium" => $images->medium_size ?? asset("img/sample/poster-sample.png"),
                            "small" => $images->small_size ?? asset("img/sample/poster-sample.png"),
                        ];
                    }

                    return [
                        "original" => $webinar->webinar_poster,
                        "medium" => $webinar->webinar_poster,
                        "small" => $webinar->webinar_poster,
                    ];
                }
            break;
        }

        return [
            "original" => asset("img/sample/poster-sample.png"),
            "medium" => asset("img/sample/poster-sample.png"),
            "small" => asset("img/sample/poster-sample.png"),
        ];
    }

}


if(! function_exists('_schedule_details')){
    function _schedule_details($id,$event){
        if($event =="series"){
            $web_series = Webinar_Series::where("webinar_id", $id)
                    ->get();
            $dates = [];
            $overall_minutes = [];
            $duration = 0;
            $date_form  = "";
            $date_to = "";
            foreach($web_series as $series_key => $sched){
                $schedule_dates = Webinar_Session::whereIn("id", json_decode($sched->sessions))->orderBy('session_date')->get();
                $duration += count($schedule_dates);

                $date_start = json_decode($schedule_dates)[0]->session_date;
                $date_end=json_decode($schedule_dates)[count($schedule_dates)-1]->session_date;
                $minutes = 0;
                if($date_start == $date_end){
                    $dates[] = date("M d,y",strtotime($date_start));
                }else{
                    $dates[] = date("M d,y",strtotime($date_start)) ." to ".date("M d,y",strtotime($date_end));
                }
                foreach($schedule_dates as $session_key => $schedule){
                    if($date_form == ""){
                        $date_form = date("M d, Y", strtotime($schedule->session_date));
                    }
                    foreach(json_decode($schedule->sessions) as $time_sched){
                        $start = date("H:i", strtotime($time_sched->start));
                        $end = date("H:i", strtotime($time_sched->end));
                        $time_difference = strtotime($end) - strtotime($start);
                        $minutes += $time_difference / 60;
                    }

                    if($series_key == (count($web_series)- 1) && $session_key == (count($schedule_dates)- 1) ){
                        $date_to = date("M d, Y", strtotime($schedule->session_date));
                    }
                }
                $overall_minutes[] = $minutes;
                $times_program_to_be_conducted= count($web_series);
            }
        }else{
            $dates = [];
            $overall_minutes = [];
            $duration = 0;
            $schedule_dates = Webinar_Session::where("webinar_id", $id)->orderBy('session_date')->get();
            $duration += count($schedule_dates);
            $date_form = date("M d, Y", strtotime($schedule_dates[0]->session_date));
            $date_to = date("M d, Y", strtotime($schedule_dates[count($schedule_dates)-1]->session_date));
            foreach($schedule_dates as $session_key => $schedule){
                $dates[] = date("M d,y",strtotime($schedule->session_date));
                $minutes = 0;
                foreach(json_decode($schedule->sessions) as $time_sched){
                    $start = date("H:i", strtotime($time_sched->start));
                    $end = date("H:i", strtotime($time_sched->end));
                    $time_difference = strtotime($end) - strtotime($start);
                    $minutes += $time_difference / 60;
                }
                $overall_minutes[] = $minutes;
            }
            
            $times_program_to_be_conducted= count($schedule_dates);

        }
        
        $average_minutes = array_sum($overall_minutes)/count($overall_minutes);
        $hourse_ave_mins = $average_minutes/60;
        $string_date = "";
        foreach($dates as $date){
            $string_date .= $date." | ";
        }
        if($event =="series"){ $time_allotment= $overall_minutes; }else{ $time_allotment= array($average_minutes);  }
        return $data = array(
            'times_program_to_be_conducted' => $times_program_to_be_conducted,
            'date_to_be_offered' => $string_date,
            'duration' => $duration,
            'hours' => $hourse_ave_mins. " hr(s)",
            'start_date'=> $date_form,
            'end_date' => $date_to,
            'time_allotment' =>$time_allotment,
        );
    }
}


if(! function_exists('_schedule_details_perDay')){
    function _schedule_details_perDay($id,$event){
        if($event =="series"){
            $web_series = Webinar_Series::where("webinar_id", $id)
                    ->get();
            $dates = [];
            $overall_minutes = [];
            $duration = 0;
            $date_form  = "";
            $date_to = "";
            foreach($web_series as $series_key => $sched){
                $schedule_dates = Webinar_Session::whereIn("id", json_decode($sched->sessions))->orderBy('session_date')->get();
                $duration += count($schedule_dates);

                $date_start = json_decode($schedule_dates)[0]->session_date;
                $date_end=json_decode($schedule_dates)[count($schedule_dates)-1]->session_date;
                
                if($date_start == $date_end){
                    $dates[] = date("M d,y",strtotime($date_start));
                }else{
                    $dates[] = date("M d,y",strtotime($date_start)) ." to ".date("M d,y",strtotime($date_end));
                }
                foreach($schedule_dates as $session_key => $schedule){
                    $minutes = 0;
                    if($date_form == ""){
                        $date_form = date("M d, Y", strtotime($schedule->session_date));
                    }
                    foreach(json_decode($schedule->sessions) as $time_sched){
                        $start = date("H:i", strtotime($time_sched->start));
                        $end = date("H:i", strtotime($time_sched->end));
                        $time_difference = strtotime($end) - strtotime($start);
                        $minutes += $time_difference / 60;
                    }

                    if($series_key == (count($web_series)- 1) && $session_key == (count($schedule_dates)- 1) ){
                        $date_to = date("M d, Y", strtotime($schedule->session_date));
                    }
                    $overall_minutes[] = $minutes;
                }
                $times_program_to_be_conducted= count($web_series);
            }
        }else{
            $dates = [];
            $overall_minutes = [];
            $duration = 0;
            $schedule_dates = Webinar_Session::where("webinar_id", $id)->orderBy('session_date')->get();
            $duration += count($schedule_dates);
            $date_form = date("M d, Y", strtotime($schedule_dates[0]->session_date));
            $date_to = date("M d, Y", strtotime($schedule_dates[count($schedule_dates)-1]->session_date));
            foreach($schedule_dates as $session_key => $schedule){
                $dates[] = date("M d,y",strtotime($schedule->session_date));
                $minutes = 0;
                foreach(json_decode($schedule->sessions) as $time_sched){
                    $start = date("H:i", strtotime($time_sched->start));
                    $end = date("H:i", strtotime($time_sched->end));
                    $time_difference = strtotime($end) - strtotime($start);
                    $minutes += $time_difference / 60;
                }
                $overall_minutes[] = $minutes;
            }
            
            $times_program_to_be_conducted= count($schedule_dates);

        }
        
        $average_minutes = array_sum($overall_minutes)/count($overall_minutes);
        $hourse_ave_mins = $average_minutes/60;
        $string_date = "";
        foreach($dates as $date){
            $string_date .= $date." | ";
        }
        if($event =="series"){ $time_allotment= $overall_minutes; }else{ $time_allotment= array($average_minutes);  }
        return $data = array(
            'times_program_to_be_conducted' => $times_program_to_be_conducted,
            'date_to_be_offered' => $string_date,
            'duration' => $duration,
            'hours' => $hourse_ave_mins. " hr(s)",
            'start_date'=> $date_form,
            'end_date' => $date_to,
            'time_allotment' =>$time_allotment,
        );
    }
}

/**
 * Webinar Page to Live Webinar Main Helper
 */

if(! function_exists('_webinar_schedule')){
    function _webinar_schedule($id, $event){
        switch ($event) {
			case 'day':
				$sessions = Webinar_Session::select(
					"id", "webinar_id", "session_date", "sessions"
				)->where([
					"webinar_id" => $id,
					"deleted_at" => null,
				])->orderBy("session_date")->get();

				return $sessions->count() > 0 ? array_map(function($sess){
					$sess["session_date"] = date("F d, Y", strtotime($sess["session_date"]));
					$sess["sessions"] = json_decode($sess["sessions"]);

					return $sess;
				}, $sessions->toArray()) : [];
			break;

			case 'series':
				$series = Webinar_Series::select(
					"id", "webinar_id", "series_order", "sessions"
				)->where([
					"webinar_id" => $id,
					"deleted_at" => null,
				])->where("sessions", "!=", null)->orderBy("series_order")->get();

				if($series){
					$series_collection = [];
					foreach ($series as $key => $ser) {
						$sessions = Webinar_Session::select(
							"id", "webinar_id", "session_date", "sessions"
						)->whereIn("id", json_decode($ser->sessions))
						->orderBy("session_date")->get();
		
						if($sessions->count() > 0){
							$series_collection[] = [
								"id" => $ser->id,
								"series_id" => $ser->id,
								"series_order" => $ser->series_order,
								"sessions" => array_map(function($sess){
									$sess["session_date"] = date("F d, Y", strtotime($sess["session_date"]));
									$sess["sessions"] = json_decode($sess["sessions"]);
				
									return $sess;
								}, $sessions->toArray()),
							];
						}
					}

					return $series_collection;
				}
				return [];
			break;
		}        
    }
}

if(! function_exists('_webinar_total')){
    function _webinar_total($id, $event){
		$quiz_total = 0;
		$article_total = 0;
		$handout_total = 0;

		$sections = Section::where([
			"type" => "webinar",
			"data_id" => $id,
			"deleted_at" => null,
		])->get();

		$handouts = Handout::where([
			"type" => "webinar",
			"data_id" => $id,
			"deleted_at" => null,
		])->get();

		if($sections){
			foreach($sections as $section){
				if($sequence = $section->sequences){
					$rearannged = [];
					foreach (json_decode($sequence) as $seq) {
						switch ($seq->type) {
							case 'article':
								$article = Article::where([
									"id" =>  $seq->id,
									"deleted_at" => null,
								])->first();
	
								if($article){
									$article_total++;
								}
							break;
	
							case 'quiz':
								$quiz = Quiz::where([
									"id" =>  $seq->id,
									"deleted_at" => null,
								])->first();
								
								if($quiz){
									$quiz_items = Quiz_Item::where([
										"quiz_id" => $quiz->id,
										"deleted_at" => null
									])->get()->count();
									$quiz_total++;
								}
							break;
						}
					}
	
					$section['arranged_parts'] = $rearannged;
				}
			}
		}

		if($handouts){
			$handout_total = $handouts->count();
		}

		return [
			"quiz" => $quiz_total,
			"article" => $article_total,
			"handout" => $handout_total,
		];
	}
}

if(! function_exists('_webinar_sections')){
    function _webinar_sections($id, $event, $schedule)
	{
		switch ($event) {
			case 'day':
				$section = Section::where([
					"type" => "webinar",
					"data_id" => $id,
					"deleted_at" => null
				])->first();

				if($section){
					if($sequence = $section->sequences){
						$rearannged = [];
						$number_of_parts = 0;
						foreach (json_decode($sequence) as $seq) {
							switch ($seq->type) {
								case 'video':
									$video = Video::where([
										"id" =>  $seq->id,
										"deleted_at" => null,
									])->first();
		
									if($video){
										$number_of_parts++;
										$rearannged[] = [
											"type" => $seq->type,
											"title" => $video->title,
											"minute" => 0
										];
									}
								break;
        
                                case 'article':
									$article = Article::where([
										"id" =>  $seq->id,
										"deleted_at" => null,
									])->first();
		
									if($article){
										$number_of_parts++;
										$rearannged[] = [
											"type" => $seq->type,
											"title" => $article->title,
											"minute" => $article->reading_time
										];
									}
								break;
		
								case 'quiz':
									$quiz = Quiz::where([
										"id" =>  $seq->id,
										"deleted_at" => null,
									])->first();
									
									if($quiz){
										$quiz_items = Quiz_Item::where([
											"quiz_id" => $quiz->id,
											"deleted_at" => null
										])->get()->count();
										
										$number_of_parts++;
										$rearannged[] = [
											"type" => $seq->type,
											"title" => $quiz->title,
											"minute" => $quiz_items
										];
									}
								break;
							}
						}

						$section->arranged_parts = $rearannged;
						$section->number_of_parts = $number_of_parts;

						return $section;
					}
				}
				break;

			case 'series':
				$section_arrangement = [];
                $number_of_parts = 0;
                
                $sections = Section::where([
                    "type" => "webinar",
                    "data_id" => $id,
                    "deleted_at" => null
                ])->get();

                if($sections){
                    foreach($sections as $section){
                        if($sequence = $section->sequences){
                            $rearannged = [];
                            foreach (json_decode($sequence) as $seq) {
                                switch ($seq->type) {
                                    case 'video':
                                        $video = Video::where([
                                            "id" =>  $seq->id,
                                            "deleted_at" => null,
                                        ])->first();
            
                                        if($video){
                                            $number_of_parts++;
                                            $rearannged[] = [
                                                "type" => $seq->type,
                                                "title" => $video->title,
                                                "minute" => 0
                                            ];
                                        }
                                    break;
                                    
                                    case 'article':
                                        $article = Article::where([
                                            "id" =>  $seq->id,
                                            "deleted_at" => null,
                                        ])->first();
            
                                        if($article){
                                            $number_of_parts++;
                                            $rearannged[] = [
                                                "type" => $seq->type,
                                                "title" => $article->title,
                                                "minute" => $article->reading_time
                                            ];
                                        }
                                    break;
            
                                    case 'quiz':
                                        $quiz = Quiz::where([
                                            "id" =>  $seq->id,
                                            "deleted_at" => null,
                                        ])->first();
                                        
                                        if($quiz){
                                            $quiz_items = Quiz_Item::where([
                                                "quiz_id" => $quiz->id,
                                                "deleted_at" => null
                                            ])->get()->count();
                                            
                                            $number_of_parts++;
                                            $rearannged[] = [
                                                "type" => $seq->type,
                                                "title" => $quiz->title,
                                                "minute" => $quiz_items
                                            ];
                                        }
                                    break;
                                }
                            }
    
                            $section['arranged_parts'] = $rearannged;
                        }
    
                        $section_arrangement[] = $section;
                    }
                }

				return ["data" => $section_arrangement, "total_parts" => $number_of_parts];
				break;
		}

		return null;
	}
}

if(! function_exists('_webinar_session')){
    function _webinar_session($webinar){
        $session = Webinar_Session::select(
            "id", "session_date", "sessions", "link"
        )->whereDate("session_date", "=", date("Y-m-d"))
        ->where([
            "webinar_id" => $webinar->id,
            "deleted_at" => null,
        ])->first();

        if($session){
            switch($webinar->event){
                case "day":
                    $session->can_leave_a_rating = true;
                break;

                case "series":
                    $session->can_leave_a_rating = false;
                    $series_of = Webinar_Series::whereRaw("JSON_CONTAINS(sessions, '{$session->id}')")->first();
                    if($series_of){
                        $sessions_of_series = json_decode($series_of->sessions);
                        if($session->id == end($sessions_of_series)){
                            $session->can_leave_a_rating = true;
                        }
                    }
                break;
            }
        }
        
        return $session;
    }   
}

if(! function_exists('_get_live_webinar_progress')){
    function _get_live_webinar_progress($webinar){
        $webinar_id = $webinar->id;
        $total_items = 0;
        $total_progress = 0;

        switch($webinar->event){
            case "day":
                $sections = Section::where([
                    "type" => "webinar",
                    "data_id" => $webinar->id,
                    "deleted_at" => null,
                ])->get();
            break;

            case "series":
                $schedule = _webinar_schedule($webinar->id, $webinar->event);
                $current = _webinar_session($webinar);
                $session_section_order = 0;
                foreach($schedule as $sched){
                    if($sched["sessions"]){
                        foreach($sched["sessions"] as $key => $sess){
                            if($sess["id"] == $current->id){
                                $session_section_order = $key+1;
                            }
                        }
                    }
                }   

                $sections = Section::where([
                    "type" => "webinar",
                    "data_id" => $webinar->id,
                    "section_number" => $session_section_order,
                    "deleted_at" => null,
                ])->get();
            break;
        }

        if($sections->count()>0){
            foreach($sections as $sc){
                if($sc->sequences){
                    $sequence = json_decode($sc->sequences);

                    foreach($sequence as $sq){
                        switch ($sq->type) {
                            case 'article':
                                $article = Article::where([
                                    "id"=>$sq->id, 
                                    "section_id"=>$sc->id, 
                                    "deleted_at"=>null,
                                ])->first();
                                
                                if($article){
                                    $total_items++;

                                    $progress = Webinar_Progress::where([
                                        "webinar_id" => $webinar_id,
                                        "user_id" => Auth::user()->id,
                                        "section_id" => $sc->id,
                                        "type" => $sq->type,
                                        "data_id" => $sq->id,
                                    ])->first();

                                    if($progress && in_array($progress->status, ["completed", "passed"])){
                                        $total_progress++;
                                    }
                                }
                                break;
        
                            case 'quiz':
                                $quiz = Quiz::where([
                                    "id"=>$sq->id, 
                                    "section_id"=>$sc->id, 
                                    "deleted_at"=>null,
                                ])->first();
                                
                                if($quiz){
                                    $total_items++;

                                    $progress = Webinar_Progress::where([
                                        "webinar_id" => $webinar_id,
                                        "user_id" => Auth::user()->id,
                                        "section_id" => $sc->id,
                                        "type" => $sq->type,
                                        "data_id" => $sq->id,
                                    ])->first();

                                    if($progress && in_array($progress->status, ["completed", "passed"])){
                                        $total_progress++;
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
        $total = $total_progress==0 ? 0 : $total_progress / $total_items;
        return $total*100;
    }
}


if(! function_exists('_get_live_webinar_feedback')){
    function _get_live_webinar_feedback($webinar_id, $user_id, $bool){
        
        $rating = Webinar_Rating::where([
            "user_id" => $user_id,
            "webinar_id" => $webinar_id,
        ])->orderBy("created_at", "desc")->first();
        $performance = Webinar_Performance::where([
            "user_id" => $user_id,
            "webinar_id" => $webinar_id,
        ])->orderBy("created_at", "desc")->first();

        if($rating && $performance){
            if($bool){
                return true;
            }
            
            return [
                "rating" => $rating,
                "performance" => $performance,
            ];
        }else{
            return null;
        }
    }
}

if(! function_exists('_get_live_webinar_attendance')){
    function _get_live_webinar_attendance($purchase_item, $user_id){
        if($purchase_item["schedule_type"]=="day"){
            $session = Webinar_Session::select("id", "session_date")->find($purchase_item["schedule_id"]);

            $attendance = Webinar_Attendance::where([
                "session_id" => $session->id,
                "user_id" => $user_id, 
                "webinar_id" => $purchase_item["data_id"], 
            ])->first();
            if($attendance && ($attendance->session_in && $attendance->session_out)){
                return true;
            }
        }else{
            $series = Webinar_Series::select("sessions")->find($purchase_item["schedule_id"]);
            $sessions = Webinar_Session::select("id", "session_date")->whereIn("id", json_decode($series->sessions))
            ->orderBy("session_date")->get();

            $completed = true;
            foreach($sessions as $session){
                $attendance = Webinar_Attendance::where([
                    "session_id" => $session->id,
                    "user_id" => $user_id, 
                    "webinar_id" => $purchase_item["data_id"], 
                ])->first();
                if($attendance && ($attendance->session_in && $attendance->session_out)){
                }else{
                   $completed = false;
                }
            }

            return $completed;
        }

        return false;
    }
}

if(! function_exists('_get_webinar_available_schedule')){
    function _get_webinar_available_schedule($webinar, $purchase_item){
        if($webinar->event=="day"){
            $session = Webinar_Session::select("session_date")->find($purchase_item->schedule_id);
            return $session->session_date;
        }else{
            $series = Webinar_Series::select("sessions")->find($purchase_item->schedule_id);
            $session = Webinar_Session::select("session_date")->whereIn("id", json_decode($series->sessions))
            ->orderBy("session_date", "desc")->first();

            return $session->session_date;
        }

        return null;
    }
}


if(! function_exists('_can_view_superadmin')){
    function _can_view_superadmin($module){
        if(Auth::check() && Auth::user()->superadmin == "active"){
            $superadmin_ = SuperadminPermission::where([
                "user_id" => Auth::user()->id,
                "module_name" => $module,
            ])->first();

            if($superadmin_){
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('_webinar_courses')) {

    function _webinar_courses($provider_id)
    {
        
        $webinars = Webinar::where([
            "provider_id" => $provider_id,
            "prc_status" => "approved",
            "fast_cpd_status" => "ended",
        ])->get();

        return $webinars;    
        
        
    }
}