<?php

namespace App\Http\Controllers\Data;

use App\{
    User, Profession, Provider, Course,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use URL;

class SitemapController extends Controller 
{
    function index(){
        return response()->view('page.sitemap.index')->header('Content-Type', 'text/xml');
    }

    function professions(){
        $data = []; // link, timestamp, priority
        $professions = Profession::select("url")->get();

        foreach($professions as $profession){
            $data[] = [
                "link" => URL::to("/courses/{$profession->url}"),
                "timestamp" => date("c"),
                "priority" => "0.5"
            ];
        }
        return response()->view('page.sitemap.submap', ["display_urls"=>$data])->header('Content-Type', 'text/xml');
    }

    function courses(){
        $data = []; // link, timestamp, priority
        $courses = Course::select("url")->whereIn("fast_cpd_status", ["published", "live", "ended"])->get();

        foreach($courses as $course){
            $data[] = [
                "link" => URL::to("/course/{$course->url}"),
                "timestamp" => date("c"),
                "priority" => "0.9"
            ];
        }
        return response()->view('page.sitemap.submap', ["display_urls"=>$data])->header('Content-Type', 'text/xml'); 
    }

    function providers(){
        $data = []; // link, timestamp, priority
        $providers = Provider::select("url")->where("status", "=", "approved")->get();

        foreach($providers as $provider){
            $data[] = [
                "link" => URL::to("/provider/{$provider->url}"),
                "timestamp" => date("c"),
                "priority" => "0.8"
            ];
        }
        return response()->view('page.sitemap.submap', ["display_urls"=>$data])->header('Content-Type', 'text/xml');
    }

    function users(){
        $data = []; // link, timestamp, priority
        $users = User::select("username","instructor")->where([
            "status" => "active",
            "accreditor" => "none",
            "superadmin" => "none",
        ])->where("username", "!=", null)
        ->where("email_verified_at", "!=", null)->get();

        foreach($users as $user){
            $url = $user->instructor=="active"?"instructor":"user";
            $data[] = [
                "link" => URL::to("/{$url}/{$user->username}"),
                "timestamp" => date("c"),
                "priority" => "0.8"
            ];
        }
        return response()->view('page.sitemap.submap', ["display_urls"=>$data])->header('Content-Type', 'text/xml');
    }
}
