<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\{
    User, Course, Password, Provider, Co_Provider as COP,
	Instructor, Profession, Profile_Requests, Section, 
	Video, Quiz, Quiz_Item, Article, Handout,
	Voucher, My_Cart, Purchase, Purchase_Item,

	Webinar, Webinar_Session, Webinar_Schedule,
};
use Illuminate\Support\Facades\{Storage, Mail, Auth, DB};
use Illuminate\Http\{Request, JsonResponse};

use Response;
use Session;

class CourseController extends Controller
{
    function GetCourses(Request $request) : JsonResponse{
        if($request->id){
            $data = Course::where([
                "prc_status" => "approved",
            ])->whereIn("status", ["published", "live", "ended"])
            ->whereRaw('JSON_CONTAINS(profession_id, \'"'.$request->id.'"\' )')->get();
            return response()->json(["status" => 200 , "data" => $data]);
        }

        return response()->json(["status" => 200 ]);
    } 

    function page(Request $request){ 
        $url = $request->url;
		$course = Course::with("provider")->where([
			"url" => $url,
			"prc_status" => "approved",
			"deleted_at" => null,
		])->whereIn("fast_cpd_status", ["published", "live", "ended"])->first();

		if ($course) {

			$course->avg_course_rating = _get_avg_rating("course", $course->id);
			$sections = Section::where([
				"type"=>"course", "data_id"=> $course->id,
				"deleted_at" => null,
			])->where("sequences", "!=", null)->get();
			
			$sections = array_map(function($sec){
				$sequence = json_decode($sec['sequences']);
				$rearannged = [];
				foreach ($sequence as $key => $seq) {
					switch ($seq->type) {
						case 'video':
							$video = Video::where([
								"id" =>  $seq->id,
								"deleted_at" => null,
							])->first();
							
							if($video){
								$rearannged[] = [
									"type" => $seq->type,
									"title" => $video->title,
									"minute" => $video->length
								];
							}

							break;

						case 'article':
							$article = Article::where([
								"id" =>  $seq->id,
								"deleted_at" => null,
							])->first();

							if($article){
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
								
								$rearannged[] = [
									"type" => $seq->type,
									"title" => $quiz->title,
									"minute" => $quiz_items
								];
							}
							
							break;
					}
				}

				$sec['arranged_parts'] = $rearannged;

				return $sec;
			}, $sections->toArray());

			$instructors = User::whereIn("id", ($course->instructor_id ? json_decode($course->instructor_id) : []))->get();
			$accreditation = array_map(function($acc){

				$profession = Profession::select("title")->find($acc->id);
				$acc->title = $profession->title;
				return $acc;
			}, $course->accreditation ? json_decode($course->accreditation) : []);

			$voucher = false;
			if(Auth::check()){
				$db_my_cart = My_Cart::where([
					"user_id" => Auth::user()->id,
					// "course_id" => $course->id,
					"status" => "active"
				])->first();

				if($db_my_cart){
					if($db_my_cart->voucher){
						$voucher = true;
						$course['discount'] = [
							"voucher_code" => $db_my_cart->voucher,
							"channel" => $db_my_cart->channel,
							"discount" => $db_my_cart->discount
						];

						if($discount_vars = $course['discount']){
							$discount = (($discount_vars["discount"]/100) * $course['price']);
							$course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
						}
					}else{
						$course['discount'] = null;
					}
				}
			}else{
				$my_cart_in_session = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
				$my_cart_selected = array_filter($my_cart_in_session, function($mcis) use($course){
					if($mcis["data_id"]==$course->id){
						return $mcis;
					}
				});
				$my_cart_selected = array_values($my_cart_selected);

				if($my_cart_selected && array_key_exists(0, $my_cart_selected)){
					$my_cart_selected = $my_cart_selected[0];

					if($my_cart_selected['voucher']){
						$voucher = true;
						$course['discount'] = [
							"voucher_code" => $my_cart_selected['voucher'],
							"channel" => $my_cart_selected['channel'],
							"discount" => $my_cart_selected['discount']
						];
	
						if($discount_vars = $course['discount']){
							$discount = (($discount_vars["discount"]/100) * $course['price']);
							$course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
						}
					}else{
						$course['discount'] = null;
					}
					
				}
			}
 
			if(!$voucher && $fast_discount = _get_fast_discount("course", $course["id"])){
				$course['discount'] = $fast_discount->toArray();
				if($discount_vars = $course['discount']){
					$discount = (($discount_vars["discount"]/100) * $course['price']);
					$course['discounted_price'] = number_format($course['price'] - $discount, 2, '.', ',');
				}
			}

			$profession_info = _get_profession(json_decode($course->profession_id)[0]);
			$data = [
				"course" => $course,
				"course_head_title"=> $course->title." - ".$profession_info->profession." | FastCPD",
				"accreditation" => $accreditation,
				"sections" => $sections,
				"instructors" => $instructors,
				"part" => _course_content_parts_length($course->id),
				"total" => [
					"video" => _course_total_video_length($course->id),
					"quiz" => _course_total_quizzes($course->id),
					"article" => _course_total_article($course->id),
					"handout" => $course->allow_handouts === 1 ? _course_total_handout($course->id)->count() : 0,
				],
				"slots" => $this->check_slots($course["id"], $course["target_number_students"]),
				"add_to_cart_button" => _get_session_cart("course", $course->id),
			];

			return view('page/course/page', $data);
		}
		return view('template/errors/404');
	}
	
	function check_slots($course_id, $limit)
    {
        $free_slots = true;
		$remaining = 0;
		
        if($limit > 0){
			$exising_purchase = Purchase_Item::select("id")->where([
				"type" => "course",
				"data_id" => $course_id,
			])->whereIn("payment_status", [
				"paid", "waiting", "pending"
			])->get();

			if($exising_purchase && ($exising_purchase->count() >= $limit)){
				$free_slots = false;
			}else{
				if($exising_purchase->count() <= $limit){
					$remaining = $limit - $exising_purchase->count();
				}
			}
        }

        return [
            "slots" => $free_slots,
            "remaining" =>  $remaining,
        ];
    }

	function voucher_add(Request $request) : JsonResponse
	{
		$course_id = $request->course_id;
		$voucher_code = $request->voucher_code;

		$fast_discount = _get_fast_discount("course", $course_id);
        $voucher = Voucher::where([
            "voucher_code" => $voucher_code,
			"status" => "active"
		]);

		if(Auth::check()){
			$voucher = $voucher->first();
		}else{
			$voucher = $voucher->whereIn("type", ["manual_apply", "auto_applied"])->first();
		}
		
		$course = Course::select(
			"id", "title", "url", 
			"price", "accreditation", "provider_id", 
			"profession_id", "instructor_id", "course_poster as poster", 
			"session_start", "session_end", "language",
			"published_at"
		)->where([
			"prc_status" => "approved",
			"deleted_at" => null,
			"id" => $course_id,
		])->whereIn("fast_cpd_status", ["published", "live"])->first()->toArray();

		if($course){			
			if($voucher){
				if($voucher->data_id && $voucher->channel != "promoter_promo"){
					$data_ids = json_decode($voucher->data_id);
					$courses = $data_ids->courses;

					if(!in_array($course_id, $courses)){
						return response()->json([
							"message" => "The voucher code is not valid for this course!"
						], 422);
					}
				}

				if($voucher->channel=="fast_promo"){
					$provider = Provider::select("allow_marketing")->find($course["provider_id"]);
					if($provider && $provider->allow_marketing==0){
						return response()->json([
							"message" => "Voucher is not applicable for this course!"
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
				}else if($voucher->channel=="promoter"){
					$provider = Provider::select("allow_marketing")->find($course["provider_id"]);
					if($provider && $provider->allow_marketing==0){
						return response()->json([
							"message" => "Voucher is not applicable for this course!"
						], 422);
					}

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

				$accreditation = array_map(function($acc){
					$proffesion = Profession::select("title")->find($acc->id);
					$acc->title = $proffesion->title;
	
					return $acc;
				}, $course["accreditation"] ? json_decode($course["accreditation"]) : []);
				$course["accreditation"] = $accreditation;

				$discounted_price = 0;
				if($voucher->discount){
					$discount_val = (($voucher->discount/100) * $course["price"]);
					$discounted_price = ($course["price"] - $discount_val);
				}

				if(Auth::check()){
					Session::forget("my_cart_in_session");
					$dmc = My_Cart::where([
						"user_id" => Auth::user()->id,
						"type" => "course",
						"data_id" => $course_id,
						"status" => "active",
					])->first();

					if($dmc){
						/**
						 * update added voucher
						 * 
						 */
						$dmc->voucher = $voucher->voucher_code;
						$dmc->discount = $voucher->discount;
						$dmc->channel = $voucher->channel;
						
						$discount = [
							"voucher_code" => $dmc->voucher,
							"channel" => $dmc->channel,
							"discount" => $dmc->discount,
							"price" => $dmc->price,
							"discounted_price" => $discounted_price,
						];
						
						if($dmc->save()){
							return response()->json([
								"update" => true,
								"discount" => $discount,
							], 200);
						}
					}else{
						My_Cart::insert([
							"user_id" => Auth::user()->id,
							"type" => "course",
							"data_id" => $course_id,
							"accreditation" => json_encode($accreditation),
							"price" => $course["price"],
							"discounted_price" => $discounted_price,
							"discount" => $voucher->discount ?? null,
							"voucher" => $voucher->voucher_code ?? null,
							"channel" => $voucher->channel ?? "fast_promo",
							"total_amount" => $discounted_price > 0 ? $discounted_price : $course["price"],
							"created_at" => date("Y-m-d H:i:s"),
							"updated_at" => date("Y-m-d H:i:s"),
						]);

						return response()->json([
							"update" => true,
							"reload" => true,
						]);	
					}
				}else{
					$mcis = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];

					$new_mcis = [];
					$my_cart_selected = null;
					foreach($mcis as $my){
						if($course_id==$my["data_id"]){
							$my["channel"] = $voucher->channel;
							$my["voucher"] = $voucher->voucher_code;
							$my["discount"] = $voucher->discount;
							$my["discounted_price"] = $discounted_price;
							$my_cart_selected = $my;
						}

						$new_mcis[] = $my;
					}
					
					if($my_cart_selected){
						$discount = [
							"voucher_code" => $my_cart_selected['voucher'],
							"channel" => $my_cart_selected['channel'],
							"discount" => $my_cart_selected['discount'],
							"price" => $course['price'],
							"discounted_price" => $discounted_price,
							"total_amount" => $discounted_price,
						];

						Session::put("my_cart_in_session", $new_mcis);
						return response()->json([
							"update" => true,
							"discount" => $discount
						]);		
					}else{
						// add to session				
						$course['data_id'] = $course["id"];
						$course['type'] = "course";
						$course['poster'] = $course["poster"];
						$course['discount'] = $voucher->discount;
						$course['voucher'] = $voucher->voucher_code;
						$course['channel'] = $voucher->channel;
						$course['discounted_price'] = $discounted_price;
						$course['total_amount'] = $discounted_price;

						Session::push("my_cart_in_session", $course);
						return response()->json([
							"update" => true,
							"reload" => true,
						]);	
					}
				}

				return response()->json(null, 200);
			}

			return response()->json([
				"message" => "Unable to apply voucher code! It's either expired or doesn't exist!"
			], 422);
		}

		return response()->json([
			"message" => "This course is unavailable for purchase anymore!"
		], 422);
	}

	function voucher_remove(Request $request) : JsonResponse
	{
        $course_id = $request->course_id;
		$voucher_code = $request->voucher_code;

        if(Auth::check()){
            Session::forget("my_cart_in_session");
            $dmc = My_Cart::select("data_id as id", "price", "channel", "voucher", "discount")->where([
				"user_id" => Auth::user()->id,
				"type" => "course",
				"data_id" => $course_id,
                "voucher" => $voucher_code,
                "status" => "active",
            ])->first();

			if($dmc){
				$discount = _get_fast_discount("course", $dmc->id);
				if($discount){
					$dmc->channel = $discount->channel;
					$dmc->voucher = $discount->voucher_code;
					$dmc->discount = $discount->discount;
				}else{
					$dmc->channel = "fast_promo";
					$dmc->voucher = null;
					$dmc->discount = null;
				}

				$discounted_price = 0;
				if($dmc->discount){
					$discount_val = (($dmc->discount/100) * $dmc->price);
					$discounted_price = number_format($dmc->price - $discount_val, 2, '.', ',');
				}
				
				$discount = [
					"voucher_code" => $dmc->voucher,
					"channel" => $dmc->channel,
					"discount" => $dmc->discount,
					"price" => $dmc->price,
					"discounted_price" => $discounted_price,
				];

				$dmc->save();
				
				return response()->json([
					"update" => true,
					"discount" => $discount
				]);	
			}            
        }else{
            /**
             * clean session remove voucher, discount, and change channel
             * 
             */

			$mcis = Session::has("my_cart_in_session") ? Session::get("my_cart_in_session") : [];
			
			$new_mcis = [];
			$my_cart_selected = null;

			foreach($mcis as $my){
				if($course_id==$my["id"]){
					$discount = _get_fast_discount("course", $my["id"]);
					if($discount){
						$my["channel"] = $discount->channel;
						$my["voucher"] = $discount->voucher_code;
						$my["discount"] = $discount->discount;
	
						$dm = ($my["discount"] / 100) * $my["price"];
						$dp = $my["price"] - $dm;
						$my["discounted_price"] = number_format($dp, 2, '.', ',');
					}else{
						$my["channel"] = "fast_promo";
						$my["voucher"] = null;
						$my["discount"] = null;
						$my["discounted_price"] = 0;
					}

					$my_cart_selected = $my;
				}

				$new_mcis[] = $my;
			}
			
			if($my_cart_selected){
				$discount = [
					"voucher_code" => $my_cart_selected['voucher'],
					"channel" => $my_cart_selected['channel'],
					"discount" => $my_cart_selected['discount'],
					"price" => $my_cart_selected['price'],
					"discounted_price" => 0,
				];

				if($my_cart_selected['discount']){
					$discount_val = (($my_cart_selected['discount']/100) * $my_cart_selected['price']);
					$discount['discounted_price'] = number_format($my_cart_selected['price'] - $discount_val, 2, '.', ',');
				}

				Session::put("my_cart_in_session", $new_mcis);
				return response()->json([
					"update" => true,
					"discount" => $discount
				]);		
			}
		}
		
		return response()->json(null, 200);
    }

	function buy_now(Request $request) : JsonResponse
	{
		$data_id = $request->course_id;
		$type = "course";

		$course = Course::select(
			"id", "url", "accreditation", "price", "provider_id", "course_poster"
		)->where([
			"id" => $data_id,
			"prc_status" => "approved",
			"deleted_at" => null,
		])->whereIn("fast_cpd_status", ["published", "live"])->first();

		if(!$course){
			return response()->json([
				"message" => "This item is not available for purchase!"
			], 422);
		}

		$discounted_price = 0;
		$discount = _get_fast_discount("course", $data_id);
		if($discount){
			$discount_value = (($discount->discount/100) * $course->price);
			$discounted_price = ($course->price - $discount_value);
		}

		if(Auth::check()){

			$data_record = [
				"user_id" => Auth::user()->id,
				"type" => $type,
				"data_id" => $data_id,
				"accreditation" => $course->accreditation, 
				"price" => $course->price, 
				"discounted_price" => $discounted_price,
				"discount" => $discount ? $discount->discount : null,
				"voucher" => $discount ? $discount->voucher_code : null,
				"channel" => $discount ? $discount->channel : "fast_promo",
				"total_amount" => $discounted_price ? $discounted_price : $course->price, 
				"status" => "active", 
				"created_at" => date("Y-m-d H:i:s"),
				"updated_at" => date("Y-m-d H:i:s"),
			];

			$_purchase_status =  _has_purchased_item($type, $data_id);
			if($_purchase_status["purchased"]){
				return response()->json([
					"message" => $_purchase_status["message"] ?? "Unable to add to cart! Please check your cart or <b>\"My Items\"</b>"
				], 422);
			}
				
			$check_my_cart = My_Cart::where([
				"type" => $type,
				"data_id" => $data_id,
				"user_id" => Auth::user()->id,
				"status" => "active", 
			])->first();

			if($check_my_cart){
				return response()->json([
					"message" => "This item is already added to your cart!"
				], 422);
			}
			
			My_Cart::insert($data_record);
			return response()->json([]);
		}else{
			$data_record = [
				"type" => $type,
				"data_id" => $data_id,
				"accreditation" => $course->accreditation ? array_map(function($acc){
					$proffesion = Profession::select("title")->find($acc->id);
					$acc->title = $proffesion->title;
	
					return $acc;
				}, json_decode($course->accreditation)) : [], 
				"title" => $course->title, 
				"poster" => $course->course_poster, 
				"price" => $course->price, 
				"discounted_price" => $discounted_price,
				"discount" => $discount ? $discount->discount : null,
				"voucher" => $discount ? $discount->voucher_code : null,
				"channel" => $discount ? $discount->channel : "fast_promo",
				"total_amount" => $discounted_price ? $discounted_price : $course->price, 
				"status" => "active", 
				"created_at" => date("Y-m-d H:i:s"),
				"updated_at" => date("Y-m-d H:i:s"),
			];

			if(Session::has("my_cart_in_session")){
                $found = false;

                $session_cart_items = Session::get("my_cart_in_session");
                foreach($session_cart_items as $sci){
                    if($sci["data_id"] == $data_id && $sci["type"] == $type){
                        $found = true;
                    }else{
                        $cart_items[] = $sci;
                    }
                }
                
                if(!$found){
                    $cart_items = array_merge($cart_items, [$data_record]);
					Session::put("my_cart_in_session", $cart_items);
				}

				return response()->json([
					"message" => "This item is already added to your cart!"
				], 422);
            }else{
                /**     
                 * session does not exist yet
                 * 
                 */
                
                $cart_items = $data_record;
                Session::push("my_cart_in_session", $cart_items);
				return response()->json([]);
			}
		}
    }
}
