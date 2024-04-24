<?php

namespace App\Http\Controllers\Webinar;

use App\Http\Controllers\Controller;
use App\{
	User, Password, Provider, Co_Provider as COP,
	Image_Intervention, Course,
	Instructor, Profession, Profile_Requests, Section, 
	Video, Quiz, Quiz_Item, Article, Handout,
    Voucher, My_Cart, Purchase, Purchase_Item,
    Webinar, Webinar_Series, Webinar_Session, 
};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Http\{Request, JsonResponse};

use Response;
use Session;

class WebinarController extends Controller
{
    function page(Request $request){
        $url = $request->url;
		$webinar = Webinar::select(
			"id", "provider_id", "profession_id", "instructor_id",
			"url", "title", "headline", "description",
			"event", "offering_units", "prices",
			"webinar_poster_id", "webinar_poster", "webinar_video",
			"objectives", "requirements", "target_students",
			"accreditation", "allow_handouts", 
			"assessment", "language", "type", "target_number_students",
			"fast_cpd_status"
		)->where([
			"url" => $url,
			"deleted_at" => null,
		])->whereIn("fast_cpd_status", ["published", "live", "ended"])->first();

		if ($webinar) {
			$webinar->prices = json_decode($webinar->prices);
			$webinar_posters = Image_Intervention::find($webinar->webinar_poster_id);
			$schedule = _webinar_schedule($webinar->id, $webinar->event);
			$total = _webinar_total($webinar->id, $webinar->event);
			$sections =_webinar_sections($webinar->id, $webinar->event, $schedule);
			$provider = $this->webinar_provider($webinar->provider_id);
			$accreditation = $this->webinar_accreditation($webinar->accreditation);
 
			$fast_discount = _get_fast_discount("webinar", $webinar->id);
			if($fast_discount){
				$prices = json_encode($webinar->prices);

				$discounted_price = null;
				if($webinar->offering_units=="without"){
                    if(($price = $webinar->prices->without) > 0){
						$discount_value = (($fast_discount->discount/100) * $price);
						$discounted_price = number_format($price - $discount_value, 2, '.', ',');
                    }
                }else if($webinar->offering_units=="with"){
                    if(($price = $webinar->prices->with) > 0){
						$discount_value = (($fast_discount->discount/100) * $price);
						$discounted_price = number_format($price - $discount_value, 2, '.', ',');
					}
				}else{
					if(($price = $webinar->prices->without) > 0){
						$discount_value = (($fast_discount->discount/100) * $price);
						$discounted_price["without"] = $price - $discount_value;
					}else{
						$discounted_price["without"] = 0;
					}
					
					if(($price = $webinar->prices->with) > 0){
						$discount_value = (($fast_discount->discount/100) * $price);
						$discounted_price["with"] = $price - $discount_value;
					}
				}
				
				$fast_discount->discounted_price = $discounted_price;
			}
			if($webinar->event == "day"){
				$data = [
					"provider" => $provider,
					"webinar" => $webinar,
					"posters" => $webinar_posters,
					"schedule" => $schedule,
					"sections" => $sections,
					"total" => $total,
					"accreditation" => $accreditation,
					"add_to_cart_button" => _get_session_cart("webinar", $webinar->id),
					"purchase_status" => _has_purchased_item("webinar", $webinar->id),
					"discount" => $fast_discount,
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
					"add_to_cart_button" => _get_session_cart("webinar", $webinar->id),
					"purchase_status" => _has_purchased_item("webinar", $webinar->id),
					"discount" => $fast_discount,
				];
			}

			return view('page/webinar/page', $data);
		}

		return view('template/errors/404');
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

	function voucher_add(Request $request) : JsonResponse
	{
		$webinar_id = $request->webinar_id;
		$voucher_code = $request->voucher_code;

        $voucher = Voucher::select("discount", "voucher_code")->where([
            "voucher_code" => $voucher_code,
			"status" => "active"
		]);

		if(Auth::check()){
			$voucher = $voucher->first();
		}else{
			$voucher = $voucher->whereIn("type", ["manual_apply", "auto_applied"])->first();
		}
		
		$webinar = Webinar::select(
			"id", "title", "url", 
			"offering_units", "prices", "accreditation", "provider_id"
		)->where([
			"deleted_at" => null,
			"id" => $webinar_id,
		])->whereIn("fast_cpd_status", ["published", "live"])->first()->toArray();

		if($webinar){			
			if($voucher){
				if($voucher->data_id){
					$data_ids = json_decode($voucher->data_id);
					$webinars = $data_ids->webinars;

					if(!in_array($webinar_id, $webinars)){
						return response()->json([
							"message" => "The voucher code is not valid for this webinar!"
						], 422);
					}
				}

				if($voucher->channel=="fast_promo"){
					$provider = Provider::select("allow_marketing")->find($webinar["provider_id"]);
					if($provider && $provider->allow_marketing==0){
						return response()->json([
							"message" => "Voucher is not applicable for this webinar!"
						], 422);
					}

					if($voucher->type=="rc_once_applied"){
						if(!_referer_voucher($voucher_code)){
							return response()->json([
								"message" => "This voucher is proprietary and for the exclusive FastCPD referer only"
							], 422);
						}
						
						if($referer_voucher = _referer_discount($voucher_code)){
							if($referer_voucher->discount==0){
								return response()->json([
									"message" => "Unable to redeem rewards! Please check your status at Account Profile & Settings > <b>Referral Code</b>."
								], 422);
							}
									
							$voucher->voucher_code = $referer_voucher->voucher_code;
							$voucher->discount = $referer_voucher->discount;
							$voucher->channel = $referer_voucher->channel;
						}else{
							return response()->json([
								"message" => "Invalid referer voucher! It's either expired, or blocked."
							], 422);
						}
					}
				}

				$prices = json_decode($webinar["prices"]);
				$voucher->offering_units = $webinar["offering_units"];
				if($webinar["offering_units"] != "both"){
					$chosen_price = $webinar["offering_units"] == "without" ? $prices->without : $prices->with;
					$voucher->price = number_format($chosen_price,2,".",",");
					$discount_value = ($voucher->discount/100) * $chosen_price;
					$voucher->discounted_price = number_format(($chosen_price - $discount_value), 2, ".", ",");
				}else{
					$voucher->prices = $prices;
					if(($price = $prices->without) > 0){
						$discount_value = (($voucher->discount/100) * $price);
						$voucher->discounted_price_without = number_format(($price - $discount_value), 2, ".", ",");
					}else{
						$voucher->discounted_price_without = 0;
					}
					
					if(($price = $prices->with) > 0){
						$discount_value = (($voucher->discount/100) * $price);
						$voucher->discounted_price_with = number_format(($price - $discount_value), 2, ".", ",");
					}
				}

				return response()->json($voucher, 200);
			}

			return response()->json([
				"message" => "Unable to apply voucher code! It's either expired or doesn't exist!"
			], 422);
		}

		return response()->json([
			"message" => "This webinar is unavailable for purchase anymore!"
		], 422);
	}

	function voucher_remove(Request $request) : JsonResponse
	{
        $webinar_id = $request->webinar_id;
		$voucher_code = $request->voucher_code;

		$webinar = Webinar::select("id", "offering_units", "prices")->find($webinar_id);
		$fast_discount = _get_fast_discount("webinar", $webinar_id);
		// remove from voucher from cart if exists
		if(Auth::check()){
            Session::forget("my_cart_in_session");
			$in_my_cart = My_Cart::where([
				"user_id" => Auth::user()->id,
				"type" => "webinar", 
				"data_id" => $webinar_id,
				"voucher" => $voucher_code,
				"status" => "active"
			])->first();

			if($in_my_cart){
				if($fast_discount){
					$discount_value = ($fast_discount->discount/100) - $in_my_cart->price;
					$discounted_price = $in_my_cart->price - $discount_value;

					$in_my_cart->discounted_price = $discounted_price;
					$in_my_cart->discount = $fast_discount->discount;
					$in_my_cart->voucher = $fast_discount->voucher_code;
					$in_my_cart->channel = "fast_promo";
					$in_my_cart->total_amount = $discounted_price;					
				}else{
					$in_my_cart->discounted_price = null;
					$in_my_cart->discount = null;
					$in_my_cart->voucher = null;
					$in_my_cart->channel = "fast_promo";
					$in_my_cart->total_amount = $in_my_cart->price;
				}

				$in_my_cart->save();
			}
		}else{
			$mcis = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
			$new_mcis = [];

			foreach($mcis as $my){
				if(
					$my["type"] == "webinar" &&
					$my["data_id"] == $webinar_id && 
					$my["voucher"] == $voucher_code && 
					$my["status"] == "active"
				){
					if($fast_discount){
						$discount_value = ($fast_discount->discount/100) - $my["price"];
						$discounted_price = $my["price"] - $discount_value;

						$my["discounted_price"] = $discounted_price;
						$my["discount"] = $fast_discount->discount;
						$my["voucher"] = $fast_discount->voucher_code;
						$my["channel"] = "fast_promo";
						$my["total_amount"] = $discounted_price;					
					}else{
						$my["discounted_price"] = null;
						$my["discount"] = null;
						$my["voucher"] = null;
						$my["channel"] = "fast_promo";
						$my["total_amount"] = $my["price"];
					}
				}

				$new_mcis[] = $my;
			}

			if(count($new_mcis) > 0){
				Session::put("my_cart_in_session", $new_mcis);
			}
		}

		$prices = json_decode($webinar->prices);
		$chosen_price = $webinar->offering_units == "without" ? $prices->without : $prices->with;
		$discount_value = ($fast_discount->discount/100) * $chosen_price;

		return response()->json([
			"offering_units" => $webinar->offering_units,
			"discount" => $fast_discount ? $fast_discount->discount : null,
			"voucher_code" => $fast_discount ? $fast_discount->voucher_code : null,
			"price" => number_format($chosen_price,2,".",","),
			"discounted_price" => number_format(($chosen_price - $discount_value), 2, ".", ","),
		], 200);
	}
} 