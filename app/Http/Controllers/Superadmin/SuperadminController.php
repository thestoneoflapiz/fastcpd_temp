<?php

namespace App\Http\Controllers\Superadmin;

use App\{
    User, Provider, Co_Provider, Instructor, 
    Course, Webinar, 
    Section, Vide, Quiz, Article, Quiz_Item,
    Handout, 
    Webinar_Series, Webinar_Session, 
    Image_Intervention,
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};

use Response; 
use Session;

class SuperadminController extends Controller
{

    function view_page(Request $request)
    {
        switch ($request->model) {
            case 'webinar':
                return $this->webinar_page($request->id);
                break;
        }
    }

    function webinar_page($id){        

		$webinar = Webinar::select(
			"id", "provider_id", "profession_id", "instructor_id",
			"url", "title", "headline", "description",
			"event", "offering_units", "prices",
			"webinar_poster_id", "webinar_poster", "webinar_video",
			"objectives", "requirements", "target_students",
			"accreditation", "allow_handouts", 
			"assessment", "language", "type", "target_number_students"
		)->where([
            "deleted_at" => null,
            "id" => $id,
		])->first();

		if ($webinar) {
			$webinar->prices = json_decode($webinar->prices);
			$webinar_posters = Image_Intervention::find($webinar->webinar_poster_id);
			$schedule = _webinar_schedule($webinar->id, $webinar->event);
			$total = _webinar_total($webinar->id, $webinar->event);
			$sections =_webinar_sections($webinar->id, $webinar->event, $schedule);
			$provider = $this->webinar_provider($webinar->provider_id);
			$accreditation = $this->webinar_accreditation($webinar->accreditation);

			if($webinar->event == "day"){
				$data = [
					"provider" => $provider,
					"webinar" => $webinar,
					"posters" => $webinar_posters,
					"schedule" => $schedule,
					"sections" => $sections,
					"total" => $total,
					"accreditation" => $accreditation,
				];
			}else{
				$data = [
					"provider" => $provider,
					"webinar" => $webinar,
					"posters" => $webinar_posters,
					"schedule" => $schedule ? $schedule[0]["sessions"] : [], 
					"schedules" => $schedule,
					"sections" => $sections,
					"total" => $total,
					"accreditation" => $accreditation,
				];
			}
            return view("page/webinar/superadmin/page", $data);
		}
    }

    function webinar_provider($id){
		$provider = Provider::find($id);
		if($provider){
			$provider->webinar_total = Webinar::where("provider_id", "=", $id)
			->whereIn("fast_cpd_status", ["approved", "published", "live", "ended"])->get()->count();

			$provider->course_total = Course::where("provider_id", "=", $id)
			->whereIn("fast_cpd_status", ["approved", "published", "live", "ended"])->get()->count();

			$provider->instructor_total = Instructor::where([
				"provider_id" => $id,
				"status" => "active"
			])->get()->count();
		}

		return $provider;
	}

	function webinar_accreditation($accreditation){
		$accreditation_new = [];
		if($accreditation){
			$accreditation = json_decode($accreditation);
			foreach ($accreditation as $acc) {
				$profession = _get_profession($acc->id);
				$accreditation_new[] = [
					"id" => $profession->id,
					"profession" => $profession->profession,
					"units" => $acc->units,
					"program_no" => $acc->program_no
				];
			}
		}

		return $accreditation_new;
	}
}