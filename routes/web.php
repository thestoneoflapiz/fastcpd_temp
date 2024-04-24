<?php

use App\{
	User, Course, Password, Provider, Co_Provider as COP,
	Instructor, Profession, Profile_Requests, Section, 
	Video, Quiz, Quiz_Item, Article, Handout, Notification, Announcement,
	Webinar, Webinar_Session, Webinar_Instructor_Permission,
	Webinar_Rating, Webinar_Performance, Webinar_Progress, Webinar_Attendance
};
use Illuminate\Http\{Request};
use Illuminate\Support\Facades\{Auth};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/webinar/sample', function () {
	$data = [
		"head_title" => "Safeguarding Our Stakeholders — LLAVE REVIEW AND TRAINING CENTER, INC.",
	];
	return view('page/webinar/page', $data);
});

Route::get('/invitation', function () {
	return view('email/superadmin/invitation');
});

Route::get('/legal/terms-of-service', function () {
	return view('/page/legal/terms_of_service');
});
Route::get('/legal/privacy-policy', function () {
	return view('/page/legal/privacy_policy');
});
Route::get('/legal/instructor-terms', function () {
	return view('/page/legal/instructor_terms');
});
Route::get('/legal/refund-policy', function () {
	return view('/page/legal/refund_policy');
});
// ========================================== PROMOTER ================================================
Route::domain(\config("app.subdomain"))->group(function () {

	Route::get('/', function () {
		if(Auth::guard('promoters')->user()){
			return redirect()->route('promoter.dashboard');
		}else{
			return view('landing/promoter/index');
		}
		
	})->name('promoter.home');

	Route::namespace('Auth')->prefix('auth')->name('auth.')->group(function () {
		Route::namespace('Promoter')->prefix('promoter')->name('promoter.')->group(function(){
			Route::get('verify/{id}', 'AuthPromoterController@verify')->name('promoter.verify');
			Route::post('verify/password', 'AuthPromoterController@password')->name('verify.password');

			Route::get('login/', 'AuthPromoterController@login')->name('promoter.login');

			Route::get('signout/', 'AuthPromoterController@signout')->name('promoter.signout');
		});

	});

	Route::namespace('Commission')->prefix('commission')->name('commission.')->group(function () {
		Route::get('/', 'CommissionController@index')->name('commission.index');
	});
	
	Route::namespace('Payout_request')->prefix('payout_request')->name('payout_request.')->group(function () {
		Route::get('/', 'PayoutRequestController@index')->name('payout_request.index');
	});

	Route::namespace('Payout_setting')->prefix('payout_setting')->name('payout_setting.')->group(function () {
		Route::get('/', 'PayoutSettingController@index')->name('payout_setting.index');
	});

	Route::namespace('Marketing_tool')->prefix('marketing_tool')->name('marketing_tool.')->group(function () {
		Route::get('/', 'MarketingToolController@index')->name('marketing_tool.index');
	});
	Route::get('dashboard', function () {
		if(Auth::guard('promoters')->user()){
			$data = array(
				"voucher" => _get_promoter_voucher(Auth::guard('promoters')->user()->id),
				"current_balance" => _get_balance(Auth::guard('promoters')->user()->id),
				"commission_list" => _get_commission_logs(Auth::guard('promoters')->user()->id),
				"payout_transaction" => _get_payout_transaction(),
			);
			return view('page/promoter/dashboard/index',$data);
		}else{
			
			return redirect()->route('promoter.home');
		}
		
	})->name('promoter.dashboard');


	Route::namespace('Api')->prefix('api')->name('api.')->group(function () {
		Route::get('commission/list', 'CommissionController@list')->name('commission.list');
		Route::get('payout_request/list', 'PayoutRequestController@list')->name('payout_request.list');
		Route::get('session/payout', 'PayoutRequestController@query')->name('payout_request.query');
		Route::get('confirm_payout', 'PayoutRequestController@confirm_payout')->name('payout_request.confirm_payout');
		Route::get('session', 'CommissionController@query')->name('query');
		Route::get('update_payout_setting', 'PayoutSettingController@update')->name('payout_setting.update');
		Route::get('update_link', 'MarketingToolController@update')->name('payout_setting.update');
		
	});

	// Route::group(['middleware' => ['auth']], function() {
	// 	Route::get('dashboard', function () {
			
	// 		return view('page/promoter/dashboard/index');
	// 	})->name('promoter.dashboard');
		
	// 	/**
	// 	 * Routes for Authentication & Verification
	// 	 * 
	// 	 */
	// });

});

// ============================================ END ===================================================
Route::get('success', function(Request $request){
	$data = [];

	if($request->type=="reset"){
		$data = ["title"=>"Yey!","support"=>"Successfully changed your password!"];
	}

	if($request->type=="pass.request"){
		$data = ["title"=>"Yey!","support"=>"Request sent!", "support1"=>"Instructions have been sent to your email."];
		
	}

	if($request->type=="public.verify"){
		$data = ["title"=>"Yey!","support"=>"You've been verified!", "support1"=>"Please wait until the you're activated."];
	}

	if($request->type=="signup"){
		$data = ["title"=>"Yey!","support"=>"You've been registered!", "support1"=>"Instructions have been sent to your email."];
	}

	return view('template/auth/success')->with($data);

})->name('success');

Route::get('error', function(Request $request){
	if($request->type=="expired"){
		return view('template/auth/fail')->with(["title"=>"OH NO!","msg"=>"Your verification has already expired. Sorry."]);
	}

	if($request->type=="inv"){
		return view('template/auth/fail')->with(["title"=>"OH NO!","msg"=>"Your invitation has already expired. Sorry."]);
	}

	if($request->type=="404"){
		return view('template.errors.404');
	}

	return view('template/auth/fail');
})->name('error');

Route::get('auth/create/password', function(Request $request){
	return view('auth/password')->with(["url"=>"/auth/verify/password", "email"=> $request->email ?? "Unknown"]);
})->name('auth.verify.form');	

Route::get('auth/reset/password/{id}', function(Request $request){
	$user = User::find($request->id);

	if($user && Password::where(["email"=>$user->email,"status"=>"active"])->first()){

		$request->session()->put('user', $user->id);
		return view('auth/password')->with(["url"=>"/auth/reset", "email"=> $user->email]);
	}else{
		return view('template/auth/fail')->with(["title"=>"OH NO!","msg"=>"Your password reset is already expired. Sorry."]);
	}
});	

Route::get('/', function () {
	if(Auth::check() && Auth::user()->accreditor != "none"){
		return redirect()->route('accreditor.home');
	}

	if(Auth::check() && Auth::user()->superadmin != "none"){
		return redirect()->route('superadmin.home');
	}
	
	return view('landing/index');
})->name('home');

Route::namespace('Course')->name('course.')->group(function(){
	Route::get('/profession_courses/{id}', 'CourseController@GetCourses');
});

/**
 * Routes for Authentication & Verification
 * 
 */
Route::namespace('Auth')->name('auth.')->group(function () {
	Route::get('auth/user', 'PublicUserController@check_user_profile')->name('check_user_profile');
	Route::get('signin', 'LoginController@signin')->name('signin');
	Route::post('public/signup', 'PublicUserController@signup')->name('public.signup');
	Route::post('public/reset', 'PublicUserController@reset')->name('public.reset');

	Route::post('auth/reset', 'PublicUserController@password_reset')->name('reset');
	Route::get('auth/verify/{id}', 'VerificationController@verify')->name('verify');
	Route::post('auth/verify/password', 'VerificationController@password_verify')->name('verify.password');

	Route::get('auth/public/verify/{id}', 'PublicUserController@verify')->name('public.verify');
	Route::get('auth/social/login', 'ThirdPartyAuthentication@login')->name('third.party.login');
	Route::get('auth/social/register', 'ThirdPartyAuthentication@register')->name('third.party.register');
	Route::post('auth/social/register/action', 'ThirdPartyAuthentication@register_action')->name('third.party.register.action');
});

Route::group(['middleware' => ['auth']], function() {
	/** 
	 * Routes grouped for Superadmin Environment
	 * 
	 */
	Route::group(['middleware' => ['superadmin']], function() {
		Route::namespace('Superadmin')->prefix('superadmin')->name('superadmin.')->group(function(){
			Route::get('/', function(Request $request){
				return view('page/superadmin/index');
			})->name('home');
	
			/**
			 * View
			 */
			Route::get('view/{model}/{id}', 'SuperadminController@view_page')->name('page.view');

			/**
			 * Account
			 * 
			 */
			Route::prefix('account')->name('account.')->group(function(){
				Route::get('/', 'AccountController@index')->name('index');
				Route::post('/save', 'AccountController@save')->name('save');
				Route::post('/password', 'AccountController@change_password')->name('change_password');
			});

			/**
			 * Verification
			 * 
			 */
			Route::prefix('verification')->name('verify.')->group(function(){
				
				/**
				 * Course
				 */
				Route::get('courses', function(Request $request){
					return view('page/superadmin/verification/course/index');
				})->name('course.index');
				Route::get('courses/api/list', 'CourseController@verification_list')->name('course.list');
				Route::post('courses/api/update_data', 'CourseController@update_course')->name('update.course');
				/**
				 * Webinar
				 */
				Route::get('webinars', 'WebinarController@verification_index')->name('webinar.index');
				Route::get('webinars/api/list', 'WebinarController@verification_list')->name('webinar.list');
				Route::get('webinars/api/session', 'WebinarController@verification_session')->name('webinar.session');
				Route::post('webinars/api/approve', 'WebinarController@verification_approve')->name('webinar.approve');
				Route::post('webinars/api/draft', 'WebinarController@verification_draft')->name('webinar.draft');
				Route::get('webinars/api/contacts', 'WebinarController@verification_contacts')->name('webinar.contacts');
				Route::get('webinars/api/sessions/list', 'WebinarController@verification_session_list')->name('webinar.sessions.list');
				Route::post('webinars/api/sessions/save', 'WebinarController@verification_session_save')->name('webinar.sessions.save');

				Route::get('instructors', function(Request $request){
					return view('page/superadmin/verification/instructor/index');
				})->name('instructor.index');
				Route::get('instructors/api/list', 'InstructorController@verification_list')->name('instructor.list');
				Route::post('instructors/api/update_data', 'InstructorController@update_instructor')->name('update.instructor');
	
				Route::get('providers', function(Request $request){
					return view('page/superadmin/verification/provider/index');
				})->name('provider.index');
				Route::get('providers/api/list', 'ProviderController@verification_list')->name('provider.list');
				Route::post('providers/api/approve', 'ProviderController@approve')->name('approve.provider');
				Route::post('providers/api/reject', 'ProviderController@reject')->name('reject.provider');
	
				Route::get('vouchers', function(Request $request){
					return view('page/superadmin/verification/voucher/index');
				})->name('voucher.index');
				Route::get('vouchers/api/list', 'VoucherController@verification_list')->name('voucher.list');
				Route::get('vouchers/api/session', 'VoucherController@verification_session')->name('voucher.session');
				Route::get('vouchers/reject', 'VoucherController@verification_reject')->name('voucher.reject');
				Route::get('vouchers/approve', 'VoucherController@verification_approve')->name('voucher.approve');

				Route::get('dragonpay', function(Request $request){
					$request->session()->forget("dragonpay_session");
					return view('page/superadmin/verification/dragonpay/index');
				})->name('dragonpay.index');
				Route::get('dragonpay/api/list', 'DragonPayController@list')->name('dragonpay.list');
				Route::get('dragonpay/api/session', 'DragonPayController@session')->name('dragonpay.session');
				Route::get('dragonpay/api/purchase/record', 'DragonPayController@view_purchase_record')->name('dragonpay.purchase.record.view');
				Route::post('dragonpay/change', 'DragonPayController@set_payment_status')->name('dragonpay.change');
	
				Route::get('payout', function(Request $request){
					return view('page/superadmin/verification/payout/index');
				})->name('payout.index');
				Route::get('payout/api/list', 'PayoutController@verification_list')->name('payout.list');
				Route::get('payout/api/payout-details', 'PayoutController@payout_details')->name('payout.payout_details');
				Route::post('payout/update', 'PayoutController@update_payouts')->name('payout.update_payouts');
			});
	
			Route::prefix('settings')->name('settings.')->group(function(){
				Route::get('vouchers', 'VoucherController@settings_page')->name('voucher.index');
				Route::get('vouchers/api/list', 'VoucherController@settings_list')->name('voucher.list');
				Route::get('vouchers/api/session', 'VoucherController@settings_session')->name('voucher.session');
				Route::get('vouchers/form', 'VoucherController@settings_form')->name('voucher.form');
				Route::get('vouchers/save', 'VoucherController@settings_save')->name('voucher.save');
				Route::get('vouchers/delete/{id}', 'VoucherController@settings_delete')->name('voucher.delete');
				Route::get('vouchers/unique', 'VoucherController@unique_')->name('voucher.unique');
			});
			
			Route::prefix('report')->name('report.')->group(function(){
				Route::get('courses', function(Request $request){
					return view('page/superadmin/report/course/index');
				})->name('course.index');
				Route::get('/courses/api/list', 'CourseController@report_list')->name('courses.list');
				Route::get('/courses/api/provider-list', 'CourseController@provider_list')->name('providers.list');
				Route::get('/courses/api/filter', 'CourseController@filter_list')->name('filter.list');
	
				Route::get('purchases', function(Request $request){
					return view('page/superadmin/report/purchase/index');
				})->name('purchase.index');
				Route::get('purchases/api/list', 'PurchaseController@report_purchase_list')->name('purchase.list');
	
				Route::get('items', function(Request $request){
					return view('page/superadmin/report/purchase_item/index');
				})->name('purchase_items.index');
				Route::get('items/api/list', 'PurchaseController@report_purchase_item_list')->name('purchase_items.list');
	
				Route::get('payouts', function(Request $request){
					return view('page/superadmin/report/payout/index');
				})->name('payout.index');
				Route::get('payouts/api/list', 'PayoutController@report_list')->name('payout.list');
				Route::get('payouts/items/api/list', 'PayoutController@report_items_list')->name('payout.items.list');
			});
	
			Route::get('providers', function(Request $request){
				return view('page/superadmin/provider/index');
			})->name('providers.index');
			Route::get('providers/api/list', 'ProviderController@list')->name('providers.list');
			Route::post('providers/reject', 'ProviderController@reject')->name('providers.reject');
			Route::post('providers/approve', 'ProviderController@approve')->name('providers.approve');
			Route::get('providers/list-details', 'ProviderController@list_details')->name('providers.list_details');
	
			Route::get('professionals', function(Request $request){
				return view('page/superadmin/professional/index');
			})->name('professionals.index');
			Route::get('professionals/api/list', 'ProfessionalController@list')->name('professionals.list');
	
			Route::get('users', function(Request $request){
				return view('page/superadmin/user/index');
			})->name('users.index');
			Route::get('users/api/list', 'UserController@list')->name('users.list');
			
			Route::get('users/add', function(Request $request){
				return view('page/superadmin/user/add');
			})->name('users.add');
			Route::post('users/add/action', 'UserController@add')->name('users.add');
			Route::get('users/permissions', 'UserController@permissions')->name('users.permissions');
			Route::post('users/permissions/action', 'UserController@permission_action')->name('users.permissions.action');

			Route::get('announcements', function(Request $request){
				$request->session()->forget("announcement_query");
				return view('page/superadmin/announcement/index');
			})->name('announcements.index');
			Route::get('announcement/add', function(Request $request){
				return view('page/superadmin/announcement/add');
			})->name('announcements.add');
			Route::get('announcements/api/list', 'AnnouncementController@list')->name('announcements.list');
			Route::get('announcements/api/session', 'AnnouncementController@announcement_query_session')->name('announcements.session');
			Route::get('announcements/delete/{id}', 'AnnouncementController@delete')->name('announcements.delete');
			Route::get('announcements/change_status/{id}', 'AnnouncementController@change_status')->name('announcements.change_status');
			Route::post('announcement/save', 'AnnouncementController@save')->name('announcements.save');
			Route::get('announcements/edit/{id}', function(Request $request){
				$announcement = Announcement::find($request->id);
				return view('page/superadmin/announcement/edit')->with(['announcement'=> $announcement]);
			})->name('edit'); 

			Route::get('top_professions', 'ProfessionalController@top_page')->name('profession.top');
			Route::get('top_professions/save', 'ProfessionalController@top_pr_save')->name('profession.top.save');

			Route::get('promoters', function(Request $request){
				return view('page/superadmin/promoter/index');
			})->name('promoter.index');
			Route::get('promoter/add', function(Request $request){
				return view('page/superadmin/promoter/add');
			})->name('promoters.add');
			Route::post('promoter/save', 'PromoterController@save')->name('promoter.save');
			Route::get('promoter/api/list', 'PromoterController@promoter_list')->name('promoter.list');
			Route::get('promoter/api/session', 'PromoterController@promoter_session')->name('promoter.session');
			Route::get('promoter/delete/{id}', 'PromoterController@delete')->name('promoter.delete');
			Route::get('promoter/edit/{id}', 'PromoterController@edit')->name('promoter.edit');
			Route::post('promoter/edit_save', 'PromoterController@edit_save')->name('promoter.edit_save');
		});

		/**
		 * PDF Print Data
		 * 
		 */
		Route::namespace('Data')->name('data.')->group(function(){
			Route::name('print.')->group(function(){
				Route::get('/data/pdf/superadmin/users/superadmin', 'PdfController@print_superadmin')->name('superadmin.users'); 
				Route::get('/data/pdf/superadmin/users/professionals', 'PdfController@print_professionals')->name('superadmin.professionals'); 
				Route::get('/data/pdf/superadmin/users/providers', 'PdfController@print_providers')->name('superadmin.providers');
				Route::get('/data/pdf/superadmin/reports/courses/{type}/{provider}', 'PdfController@print_courses_report')->name('superadmin.print_courses_report');
				Route::get('/data/pdf/superadmin/reports/purchases/{date_from}/{date_to}/{methods}', 'PdfController@print_purchases_report')->name('superadmin.print_purchases_report');
				Route::get('/data/pdf/superadmin/reports/payouts/{month}/{year}/{user_type}', 'PdfController@print_payouts_report')->name('superadmin.print_payouts_report');
				Route::get('/data/pdf/superadmin/reports/purchase-items/{date_from}/{date_to}/{provider}', 'PdfController@print_purchase_items_report')->name('superadmin.print_purchase_items_report');
				Route::get('/data/pdf/superadmin/verification/payouts', 'PdfController@print_verification_payouts')->name('superadmin.print_verification_payouts');
			});
		});
	});


	/**
	 * Routes grouped for Accreditors
	 * 
	 */
	Route::group(['middleware' => ['accreditor']], function() {
		Route::namespace('Accreditor')->name('accreditor.')->prefix('accreditation')->group(function () {
			Route::get('home', 'AccreditorController@index')->name('home');
			Route::get('webinar/preview/{url}',"AccreditorController@webinar_live")->name('webinar.live');
			Route::get('webinar/{url}',"AccreditorController@webinar_page")->name('webinar');
			Route::post('webinar/review/submit', 'AccreditorController@submit_review')->name('review.submit');

			Route::get('course/{url}', "AccreditorController@course_page")->name('course');
			Route::post('/course/review/submit', 'AccreditorController@submit_review')->name('review.submit');
			Route::get('/course/preview/{url}', 'AccreditorController@live_course_preview')->name('live.course');
		});
	});

	/**
	 * Routes grouped for Users
	 * 
	 */
	Route::group(['middleware' => ['user']], function() {

		Route::namespace('Checkout')->name('checkout.')->prefix('checkout')->group(function(){
			Route::get('/', 'CheckoutController@index')->name('index'); 
			
			Route::namespace('PayMongo')->name('paymongo.')->prefix('pmongo')->group(function(){
				/**
				 * card
				 * 
				 */
				Route::get('card-payment/form', 'CardController@payment_form')->name('payment.form.card'); 
				Route::post('card-payment/process', 'CardController@payment_process')->name('payment.process.card');  
				Route::post('card-payment/completion', 'CardController@payment_completion')->name('payment.completion.card'); 

				/**
				 * e-wallet
				 *  
				 */
				Route::get('e-wallet-payment/form/{method}', 'EWalletController@payment_form')->name('payment.form.e-wallet'); 
				Route::post('e-wallet-payment/process', 'EWalletController@payment_process')->name('payment.process.e-wallet'); 
				Route::post('e-wallet-payment/process/cancel', 'EWalletController@pp_cancel')->name('payment.process.e-wallet.cancel');  
			});

			Route::namespace('DragonPay')->name('dragonpay.')->prefix('pdragon')->group(function(){
				Route::post('payment/process', 'MainController@payment_process')->name('payment.process');  
				Route::post('payment/process/continue', 'MainController@pp_continue')->name('payment.process.continue');  
				Route::post('payment/process/cancel', 'MainController@pp_cancel')->name('payment.process.cancel');  
			});

			Route::get('payment/failed', function(){
				return view("page.cart.payment_forms.failed", ["reference_number" => false]);
			})->name('payment.failed'); 
		}); 

		Route::namespace('User')->name('users.')->group(function(){
			Route::get('/profile/settings', "ProfileController@overview")->name('profile.settings');
			Route::get('/profile/overview/list', "ProfileController@fetch_overview_list")->name('profile.overview.list');
			Route::get('/profile/webinar/view/schedule', "ProfileController@webinar_view_schedule")->name('profile.webinar.view.schedule');
			Route::post('/user/close/account', 'ProfileController@close_account')->name('user.account.close');
			Route::post('/password/action', 'ProfileController@password')->name('password.action');
			Route::post('/personal/action', 'ProfileController@personal')->name('password.personal');
			Route::post('/personal/signature/upload', "ProfileController@signature_upload")->name('profile.personal.signature');
			Route::post('/account/action', 'ProfileController@account')->name('password.account');
			Route::post('/personal/register/action/pdf', 'ProfileController@pdf')->name('instructor.reg.pdf');
			Route::post('/personal/register/action/images', 'ProfileController@images')->name('instructor.reg.images');
			Route::post('/association/remove/course', 'ProfileController@remove_course')->name('association.remove.course');
		
			Route::get('/users', function (Request $request) {
				$request->session()->forget('user_query');
				return view('page/users/list')->with(["totalusers" => _credits()]);
			})->name('list'); 

			Route::get('/users/add', function (Request $request) {
				if(_credits()==(5)){
					$request->session()->flash('info', 'Sorry! User credits are all used!');
					return redirect()->route("users.list");
				}

				return view('page/users/add')->with('roles', _roles());
			})->name('users.add');
						
			Route::post('/users/add/action', 'UserController@store')->name('add.action');
			Route::get('/api/users', 'UserController@index')->name('api.list'); 
			Route::get('/api/session', 'UserController@query')->name('query'); 

			Route::get('/user/change/{id}', 'UserController@change')->name('change'); 
			Route::get('/user/delete/{id}', 'UserController@delete')->name('delete'); 
			Route::get('/user/edit/{id}', function(Request $request){
				$user = User::find($request->id);
				$name = $user ? $user->first_name.' '.$user->last_name : "";
				return view('page/users/edit')->with(['roles'=>_roles(), 'id'=>$request->id, 'name'=>$name, 'user'=>$user]);
			})->name('edit'); 
			Route::post('/users/edit/action', 'UserController@edit')->name('edit.action');

			// LECTURE AREA
			Route::get('/lecture', function(Request $request){
				return view('page/lecture/index');
			})->name('edit'); 

			//	Notifications Page
			Route::get('/notifications', function(Request $request){
				return view('page/notifications/notifications');
			})->name('notifications'); 
		});

		Route::namespace('Data')->name('data.')->group(function(){
			Route::name('print.')->group(function(){
				Route::get('/data/pdf/users', 'PdfController@print_users')->name('users'); 
				Route::get('/data/pdf/provider/users', 'PdfController@print_provider_users')->name('provider.users'); 
				Route::get('/data/pdf/provider/instructors', 'PdfController@print_provider_instructors')->name('provider.instructors'); 
				Route::get('/data/pdf/provider/promotions', 'PdfController@promotions')->name('provider.promotions'); 
				Route::get('/data/request/{module}', 'PdfController@print_request')->name('request.info'); 
				Route::get('/data/pdf/user/certificate/{id}', 'PdfController@print_certificate')->name('print.certificate');
				Route::get('/data/pdf/provider/completion', 'PdfController@print_completion_report')->name('print.completion_report');
				Route::get('/data/pdf/provider/webinar-completion', 'PdfController@print_webinar_completion_report')->name('print.webinar_completion_report');
				Route::get('/data/pdf/provider/attendance', 'PdfController@attendance_sheet')->name('attendance_sheet');
				Route::get('/data/pdf/user/webinar_certificate/{id}', 'PdfController@print_webinar_certificate')->name('print.webinar_certificate');
				Route::get('/data/pdf/user/application_form/{id}', 'PdfController@print_application_form')->name('print.application_form');
				Route::get('/data/pdf/user/webinar_application_form/{id}', 'PdfController@print_webinar_application_form')->name('print.webinar_application_form');

				Route::get('/data/pdf/provider/revenue', 'PdfController@print_revenue')->name('print.print_revenue');
				Route::get('/data/pdf/provider/rating/month', 'PdfController@print_rating')->name('print.print_rating');
				Route::get('/data/pdf/provider/overview/revenue/month', 'PdfController@print_revenue_month')->name('print.print_revenue_month');
				Route::get('/data/pdf/provider/webinar-rating/month', 'PdfController@print_webinar_rating')->name('print.print_webinar_rating');

				/**
				 * Live Course Certificate 
				 * generate only if user has completed the course requirements in live
				 * 
				 */
				Route::get('/data/pdf/certificate', 'PdfController@print_user_certificate')->name('print.print_user_certificate');
			});
			Route::name('csv.')->group(function(){
				Route::get('/data/csv/users', 'ExportController@export_users')->name('users'); 
			});
			Route::name('import.')->group(function(){
				Route::get('/data/import', 'ImportController@index')->name('index'); 
				Route::post('/data/import/action', 'ImportController@action')->name('action'); 
			});

			Route::name('upload.')->group(function(){
				Route::post('/data/upload', 'UploadController@index')->name('index'); 
			});
		});

		Route::namespace('Help')->name('help.')->group(function(){
			Route::get('/help', function (Request $request) {
				return view('page/help/index');
			})->name('index');
			Route::get('/help/edit', function (Request $request) {
				return view('page/help/edit');
			})->name('edit');
			Route::get('/help/logo', function (Request $request) {
				return view('page/help/upload');
			})->name('logo');
		});
		
		Route::get('/logs', 'LogsController@logs')->name('logs');
		Route::get('/api/logs', 'LogsController@getLogs')->name('logs.api');
		Route::get('/api/session-logs', 'LogsController@logs_query')->name('session.logs'); 
		
		Route::namespace('Provider')->prefix('provider')->name('provider.')->group(function(){
			Route::get('/register', function(){
				if(Auth::user()->provider_id != NULL){
					return redirect()->route('provider.courses');
				}

				return view('page/provider/register_pro')->with('course_head_title', 'Become a FastCPD Provider | FastCPD'); //register.blade.php
			})->name('register');
			Route::post('/register/action', 'ProviderController@register')->name('register.action');
			Route::get('/url/suggestions', 'ProviderController@suggestions')->name('suggestions.url');
			Route::post('/register/action/pdf', 'ProviderController@pdf')->name('register.pdf');
			Route::get('/session/{id}', 'ProviderController@session')->name('session');

			/**
			 * Provider Portal
			 * 
			 */
			Route::group(['middleware' => ['provider']], function() {
				/**
				 * begin: Webinars
				 * 
				 */
				Route::get('/webinars', 'WebinarController@index')->name('webinars');
					Route::post('/webinar/add', 'WebinarController@add')->name('add');
					Route::get('/webinar/url/suggestions/', 'WebinarController@suggestions')->name('suggestions.url');
					Route::get('/webinar/api/list', 'WebinarController@list')->name('list');
					Route::get('/webinar/convert', 'WebinarController@convert')->name('convert');
					Route::get('/webinar/{id}', function(Request $request){
						$webinar = Webinar::where("provider_id", "=", _current_provider()->id)->find($request->id);
						$instructor_ids = $webinar->instructor_id ? json_decode($webinar->instructor_id) : [];
						if(($webinar && in_array(Auth::user()->id, $instructor_ids)) || _is_co_provider_check(Auth::user()->id, _current_provider()->id)){
							Session::forget("webinar_id");
							Session::forget("session_webinar_id");
							Session::put("webinar_id",$request->id);
							Session::put("session_webinar_id",$request->id);
							
							if($request->has("view")){
								return redirect("/webinar/performance");
							}
							if(_my_webinar_permission("webinar_details") && _webinar_creation_restricted("webinar_details")){
								return redirect()->route('webinar.management.details');
							}
							if(_my_webinar_permission("attract_enrollments") && _webinar_creation_restricted("attract_enrollments")){
								return redirect()->route('webinar.management.attract');
							}
							if(_my_webinar_permission("instructors") && _webinar_creation_restricted("instructors")){
								return redirect()->route('webinar.management.instructors');
							}
							if(_my_webinar_permission("video_content") && _webinar_creation_restricted("video_content")){
								return redirect()->route('webinar.management.content');
							}
							if(_my_webinar_permission("handouts") && _webinar_creation_restricted("handouts")){
								return redirect()->route('webinar.management.handouts');
							}
							if(_my_webinar_permission("grading") && _webinar_creation_restricted("grading")){
								return redirect()->route('webinar.management.assessment');
							}
							if(_my_webinar_permission("links") && _webinar_creation_restricted("links")){
								return redirect()->route('webinar.management.links');
							}
							if(_my_webinar_permission("accreditation") && _webinar_creation_restricted("accreditation")){
								return redirect()->route('webinar.management.accreditation');
							}
							if(_my_webinar_permission("publish") && _webinar_creation_restricted("publish")){
								return redirect()->route('webinar.management.publish');
							}
							if($webinar->fast_cpd_status == "in-review"){
								Session::flash("info", "Access denied! This webinar is currently in-review by the FastCPD Management");
								return redirect("/provider/webinars");
							}

							Session::flash("info", "Access denied! You have no permission to access webinars!");
							return redirect("/provider/webinars");
						}
					})->name('session');
				/**
				 * end: Webinars
				 * 
				 */

				/**
				 * begin: Courses
				 * 
				 */
				Route::get('/courses', function(){
					if(_my_provider_permission("courses", "view")){
						$provider = Session::get("session_provider_id");
						$provider_professions = array_map(function($id){
							return Profession::find($id);
						}, json_decode(_current_provider()->profession_id));
						
						$courses = [];
						if($provider){
							$courses = Course::where("provider_id", "=", $provider->id)->get();
						}
						
						if(count($courses) > 0){
							return view('page/course/index-list')->with("data", ["courses"=>$courses,"provider_professions" => $provider_professions,"provider_head_title"=>_current_provider()->name." - CPD Accredited Providers | FastCPD"]);
						}

						return view('page/course/index')->with("data", ["provider_professions" => $provider_professions,"provider_head_title"=>_current_provider()->name." - CPD Accredited Providers | FastCPD"]);
					}else{
						return view('template/errors/404');
					}
				})->name('courses');
				Route::post('/course/add', 'CourseController@add')->name('course.add');
				Route::get('/course/url/suggestions/', 'CourseController@suggestions')->name('course.suggestions.url');
				Route::get('/course/api/list', 'CourseController@list')->name('course.list');
				Route::get('/course/{id}', function(Request $request){
					
					$course = Course::where("provider_id", "=", _current_provider()->id)->find($request->id);
					$instructor_ids = $course->instructor_id ? json_decode($course->instructor_id) : [];
					if(($course && in_array(Auth::user()->id, $instructor_ids)) || _is_co_provider_check(Auth::user()->id, _current_provider()->id)){
						Session::forget("course_id");
						Session::forget("session_course_id");
						Session::put("course_id",$request->id);
						Session::put("session_course_id",$request->id);
						if(_my_course_permission("course_details") && _course_creation_restricted("course_details")){
							return redirect()->route('course.course_details');
						}
						if(_my_course_permission("attract_enrollments") && _course_creation_restricted("attract_enrollments")){
							return redirect()->route('course.attract_enrollments');
						}
						if(_my_course_permission("instructors") && _course_creation_restricted("instructors")){
							return redirect()->route('course.instructors');
						}
						if(_my_course_permission("video_content") && _course_creation_restricted("video_content")){
							return redirect("/course/management/content");
						}
						if(_my_course_permission("handouts") && _course_creation_restricted("handouts")){
							return redirect()->route('course.handouts');
						}
						if(_my_course_permission("grading") && _course_creation_restricted("grading")){
							return redirect()->route('course.grading_and_assessment');
						}
						if(_my_course_permission("accreditation") && _course_creation_restricted("accreditation")){
							return redirect()->route('course.submit_for_accreditation');
						}
						if(_my_course_permission("publish") && _course_creation_restricted("publish")){
							return redirect()->route('course.price_and_publish');
						}
						if($course->fast_cpd_status == "in-review"){
							Session::flash("info", "Access denied! This course is currently in-review by the FastCPD Management");
							return redirect("/provider/courses");
						}
					}
				})->name('course.session');

				/**
				 * begin: Overview
				 * 
				 */
				Route::get('/overview', function(){
					if(_my_provider_permission("overview", "view")){
						return view('page/performance/overview');
					}else{
						return view('template/errors/404');
					}
				})->name('overview');
				Route::get('/overview/enrollment/api/list', 'OverviewController@enrollment_list')->name('overview.enroll.list');
				Route::get('/overview/rating/api/list', 'OverviewController@rating_list')->name('overview.rating.list');
				Route::get('/revenue/month/api/overviewlist', 'OverviewController@currentMonthList')->name('overview.current.month.list');
				Route::get('/overview/webinar-rating/api/list', 'OverviewController@webinar_rating_list')->name('overview.webinar_rating_list.list');
				/**
				 * begin: Revenue
				 * 
				 */
				Route::get('/revenue', function(Request $request){
					$request->session()->forget('revenue_query');
					if(_my_provider_permission("revenue", "view")){
						return view('page/performance/revenue');
					}else{
						return view('template/errors/404');
					}
				})->name('revenue');
				Route::get('/revenue/api/session', 'RevenueController@query')->name('revenue.query'); 
				Route::get('/revenue/api/list', 'RevenueController@list')->name('revenue.list');
				Route::get('/revenue/monthly/{payout_id}/{name}', function(Request $request){
					if(_my_provider_permission("revenue", "view")){
						return view('page/performance/revenue/month')->with("data", ["reported_date"=>$request->name,"payout_id"=>$request->payout_id]);
					}else{
						return view('template/errors/404');
					}
				})->name('revenue');
				Route::get('/revenue-monthly/api/getDetails', 'RevenueController@getMonthlyDetails')->name('revenue.monthlyDetails'); 
				Route::get('/revenue/month/api/list', 'RevenueController@monthlist')->name('revenue.month.list');
				Route::get('/revenue/api/lastsixmonths', 'RevenueController@lastSixMonths')->name('revenue.lastsixmonths.graph');
				Route::get('/revenue/api/totalearnings', 'RevenueController@totalearnings')->name('revenue.api.totalearnings');
				Route::get('/revenue/api/promotionactivity', 'RevenueController@promotionActivity')->name('revenue.api.promotionactivity');
				Route::get('/revenue/api/earningsbycourse', 'RevenueController@earningsByCourse')->name('revenue.api.earningsbycourse');

				/**
				 * begin: Review
				 * 
				 */
				Route::get('/review', function(){
					if(_my_provider_permission("review", "view")){
						return view('page/performance/review');
					}else{
						return view('template/errors/404');
					} 
				})->name('review');
				Route::get('/review/api/showreviews', 'OverviewController@showReviews')->name('review.api.showReviews');
				Route::get('/review/api/checkProgressReview', 'OverviewController@checkProgressReview')->name('review.api.checkProgressReview');
				Route::get('/review/api/savedRatingsRemarks', 'OverviewController@savedRatingsRemarks')->name('review.api.savedRatingsRemarks');
				Route::get('/review/api/savedPerformance', 'OverviewController@savedPerformance')->name('review.api.savedPerformance');

				/**
				 * begin: Traffic & Conversion
				 * 
				 */
				Route::get('/traffic', function(){
					if(_my_provider_permission("traffic_conversion", "view")){
						return view('page/performance/traffic');
					}else{
						return view('template/errors/404');
					}
				})->name('traffic');

				/**
				 * begin: PRC Completion Report
				 * 
				 */
				Route::get('/prc/completion', function(){
					if(_my_provider_permission("prc_completion", "view")){
						return view('page/performance/prc/index');
					}else{
						return view('template/errors/404');
					}
				})->name('prc.completion');
				Route::get('/prc/completion/api/list', 'PRCController@list')->name('prc.completion.list');
				Route::get('/prc/completion/api/webinar-list', 'PRCController@webinarList')->name('prc.completion.webinarList');
				Route::get('/prc/completion/api/setMonthYearList', 'PRCController@setMonthYearList')->name('prc.completion.setMonthYearList');
				/**
				 * begin: Promotions
				 * 
				 */
				Route::get('/promotions', 'PromotionController@index')->name('promotions');
				Route::get('/promotions/api/list', 'PromotionController@list')->name('promotion.api.list');
				Route::get('/promotions/api/session', 'PromotionController@session')->name('promotion.api.session');
				Route::get('/promotions/form', 'PromotionController@form')->name('promotion.form');
				Route::get('/promotions/save', 'PromotionController@save')->name('promotion.save');
				Route::get('/promotions/delete/{id}', 'PromotionController@delete')->name('promotion.delete');
				Route::get('/promotions/unique', 'PromotionController@unique_')->name('promotion.unique');
				Route::post('/promotion/join', 'PromotionController@join')->name('promotion.join');

				/**
				 * begin: Provider Profile
				 * 
				 */
				Route::get('/profile', function(){
					if(_my_provider_permission("provider_profile", "view")){
						return view('page/organization/provider/index');
					}else{
						return view('template/errors/404');
					}
				})->name('profile');
				Route::post('/profile/action', 'ProviderController@profile')->name('profile.action');

				/**
				 * begin: Provider Instructors
				 * 
				 */
				Route::get('/instructors', function(Request $request){
					$request->session()->forget('inst_query');
					if(_my_provider_permission("instructors", "view")){
						$total = Instructor::select("user_id as id", "status")->where("provider_id", "=", _current_provider()->id)
						->where("status", "!=", "delete")->get();

						$access = COP::select("role")->where([
							"user_id" => Auth::user()->id,
							"provider_id" => _current_provider()->id,
							"status" => "active"
						])->first();

						$data = [
							"totalinstructors" => count(array_filter($total->toArray(), function($active){
								if($active["status"] == "active"){
									return $active;
								}
							})),
							"pendinginstructors" => count(array_filter($total->toArray(), function($pending){
								if($pending["status"] == "pending"){
									return $pending;
								}
							})),
							"access" => $access->role,
						];

						return view('page/organization/instructors/index')->with("data", $data);
					}else{
						return view('template/errors/404');
					}
				})->name('instructors');
				Route::get('/instructor/invite', function(){
					if(_my_provider_permission("instructors", "view") == true && _my_provider_permission("instructors", "create") == true){
						
						return view('page/organization/instructors/instructor')->with("type", "add");
					}else{
						return view('template/errors/404');
					}
				})->name('instructor.invite');
				Route::get('/instructor/api/list', 'InstructorController@instructors')->name('instructor.list');
				Route::get('/instructor/api/search', 'InstructorController@search')->name('instructor.search');
				Route::post('/instructor/resend', 'InstructorController@resend_invitation')->name('instructor.resend');
				Route::post('/instructor/invite/action', 'InstructorController@invite')->name('instructor.invite.action');
				Route::get('/instructor/change/{id}', 'InstructorController@change')->name('instructor.status.action');
				Route::get('/instructor/delete/{id}', 'InstructorController@delete')->name('instructor.delete.action');

				/**
				 * begin: Provider Users
				 * 
				 */
				Route::get('/users', function(Request $request){
					$request->session()->forget('user_query');
					if(_my_provider_permission("users", "view")){
						$total = COP::select("user_id as id", "role", "status")->where("provider_id", "=", _current_provider()->id)
						->where("status", "!=", "delete")->get();

						$access = COP::select("role")->where([
							"user_id" => Auth::user()->id,
							"provider_id" => _current_provider()->id,
							"status" => "active"
						])->first();

						$data = [
							"totalusers" => $total->count(),
							"pendingusers" => count(array_filter($total->toArray(), function($pending){
								if($pending["status"] == "pending"){
									return $pending;
								}
							})),
							"access" => $access->role,
						];

						return view('page/organization/users/index')->with("data", $data);
					}else{
						return view('template/errors/404');
					}
				})->name('users');
				Route::get('/user/api/list', 'UserController@users')->name('user.list');
				Route::get('/user/api/search', 'UserController@search')->name('user.search');
				Route::post('/user/resend', 'UserController@resend_invitation')->name('user.resend');
				Route::get('/user/invite', function(){
					if(_my_provider_permission("users", "view") == true && _my_provider_permission("users", "create") == true){
						return view('page/organization/users/user')->with("type", "add");
					}else{
						return view('template/errors/404');
					}
				})->name('user.invite');
				Route::post('/user/invite/action', 'UserController@invite')->name('user.invite.action');
				Route::get('/user/permission', 'UserController@change_permission')->name('user.permission.action');
				Route::get('/user/change/{id}', 'UserController@change')->name('user.status.action');
				Route::get('/user/delete/{id}', 'UserController@delete')->name('user.delete.action');
				Route::post('/nonuser/invite/action', 'UserController@nonuser_invite')->name('nonuser.invite.action');
			});
		});
		
		/**
		 * Webinar
		 * 
		 */
		Route::namespace("Webinar")->prefix("webinar")->name("webinar.")->group(function() {
			/**
			 * Webinar Creation
			 */
			Route::prefix("management")->name("management.")->group(function() {
				/**
				 * Details
				 */
				Route::get("/details", "DetailController@index")->name("details");
				Route::post("/details/info", "DetailController@save_webinar_info")->name("details.info");
				Route::post("/details/schedule", "DetailController@save_webinar_schedule")->name("details.schedule");
				/**
				 * Attract Enrollments
				 */
				Route::get('/attract', "AttractEnrollmentController@index")->name('attract'); 
				Route::post('/attract/store', 'AttractEnrollmentController@store')->name('attract.store');
				Route::post('/attract/poster/upload', 'AttractEnrollmentController@upload_webinar_poster')->name('attract.poster.upload');
				Route::get('/attract/poster/remove', 'AttractEnrollmentController@remove_webinar_poster')->name('attract.poster.remove');
				Route::post('/attract/video/upload', 'AttractEnrollmentController@upload_webinar_video')->name('attract.video.upload');
				Route::get('/attract/video/remove', 'AttractEnrollmentController@remove_webinar_video')->name('attract.video.remove');
				/**
				 * Instructors
				 */
				Route::get('/instructors', 'InstructorController@index')->name('instructors'); 
				Route::get('/instructor/list', 'InstructorController@list')->name('instructor.list'); 
				Route::get('/instructor/list/filter', 'InstructorController@list_filter')->name('instructor.list.filter'); 
				Route::post('/instructor/permission', 'InstructorController@permission')->name('instructor.permission');
				Route::post('/instructor/udpate', 'InstructorController@udpate')->name('instructor.udpate');
				/**
				 * Video & Content
				 */
				Route::get('/content', 'VideoContentController@index')->name('content');
				Route::get('/content/sections', 'VideoContentController@content_sections')->name('content.sections');
				Route::post('/content/section/store', 'VideoContentController@store_section')->name('content.section,store');
				Route::post('/content/video/store', 'VideoContentController@store_video')->name('content.video.store');
				Route::post('/content/article/store', 'VideoContentController@store_article')->name('content.article.store');
				Route::post('/content/quiz/store', 'VideoContentController@store_quiz')->name('content.quiz.store');
				Route::post('/content/quiz/item/store', 'VideoContentController@store_quiz_item')->name('content.quiz.item.store');
				Route::post('/content/textarea/upload', 'VideoContentController@upload_textarea_image')->name('content.textarea.upload');
				Route::get('/content/remove/part', 'VideoContentController@remove_part')->name('content.part.remove');
				/**
				 * Handouts
				 */
				Route::get('/handouts', 'HandoutController@index')->name('handouts');
				Route::get('/handout/allow', 'HandoutController@allow')->name('handout.allow'); 
				Route::get('/handout/list', 'HandoutController@list')->name('handout.list'); 
				Route::post('/handout/store', 'HandoutController@store')->name('handout.store');
				Route::post('/handout/remove', 'HandoutController@remove')->name('handout.remove');
				Route::post('/handout/upload', 'HandoutController@upload')->name('handout.upload');
				/**
				 * Grading & Assessment
				 */
				Route::get('/grading', 'GradingAssessmentController@index')->name('grading');
				Route::post('/grading/store','GradingAssessmentController@store')->name('grading.store');
				/**
				 * Webinar Links
				 */
				Route::get('/links', 'PublishController@index_links')->name('links');
				Route::post('/links/store','PublishController@store_links')->name('links.store');
				/**
				 * PRC Accreditation
				 */
				Route::get('/accreditation','AccreditationController@index')->name('accreditation');
				Route::get('/is/prc-acc','AccreditationController@can_submit_accreditation')->name('submit.accreditation.can'); 
				Route::post('/accreditation/store','AccreditationController@store_accreditation')->name('accreditation.store');
				Route::post('instructional_design/store','AccreditationController@store_instructional')->name('id.store');
				Route::post('expenses_breakdown/store','AccreditationController@store_expense_breakdown')->name('eb.store');
				Route::get('cpdas/{id}', 'AccreditationController@view_cpdas')->name('cpdas.view');
				/**
				 * Publish Webinar
				 */
				Route::get('/publish', 'PublishController@index')->name('publish');
				Route::post('/publish/store','PublishController@store')->name('publish.store');
				/**
				 * Submit Review
				 */
				Route::get('/review','PublishController@submit_review')->name('submit.review');
			});

			/**
			 * Performance
			 */
			Route::get('/performance','PerformanceController@index')->name('performance');
			Route::get('/performance/reports','PerformanceController@reports')->name('performance.reports');
			Route::post('/performance/manual','PerformanceController@manual_attendance')->name('performance.manual.attendance');
		});

		/**
		 * Course
		 * 
		 */
		Route::namespace("Course")->name('course.')->group(function(){
			Route::get('/course/management/details','CourseManagement@details')->name('course_details'); 
			Route::post('/course_management/course_details/store', 'CourseManagement@StoreCourseDetails')->name('course_details.store');
			/**
			 * 
			 * COURSE CREATION : ATTRACT ENROLLMENTS -- BEGIN
			 * 
			 */
			Route::get('/course/management/attract', function(){
				if(_my_course_permission("attract_enrollments") && _course_creation_restricted("attract_enrollments")){
					return view('page/course_creation/attract_enrollees');
				}
				
				return view('template/errors/404');
			})->name('attract_enrollments'); 
			Route::post('/course_management/attract_enrollments/store', 'CourseManagement@StoreAttractEnrollments')->name('attract_enrollments.store');
			Route::get('/course/management/attract/remove', 'CourseManagement@removeCoursePV')->name('attract.remove');
			Route::namespace("Course_Creation")->name('course.creation.')->group(function(){
				Route::post('/course/management/poster/upload', 'AttractEnrollmentController@upload_course_poster')->name('attract.poster.upload');
				Route::get('/course/management/poster/remove', 'AttractEnrollmentController@remove_course_poster')->name('attract.poster.remove');
				Route::post('/course/management/attract/video/upload', 'AttractEnrollmentController@upload_course_video')->name('attract.video.upload');
				Route::get('/course/management/attract/video/remove', 'AttractEnrollmentController@remove_course_video')->name('attract.video.remove');
			});
			/**
			 * 
			 * COURSE CREATION : ATTRACT ENROLLMENTS -- END
			 * 
			 */
			Route::get('/course/management/instructors', function(Request $request){
				$request->session()->forget('filter_instructor');
				$data = [
					'course_instructor_ids' => json_decode(_current_course()->instructor_id),
				];

				if(_my_course_permission("instructors") && _course_creation_restricted("instructors")){
					return view('page/courseManagement/instructors', $data);
				}
				
				return view('template/errors/404');
			})->name('instructors'); 
			Route::get('/course_management/api/instructors', 'CourseManagement@paginatedQuery')->name('api.list'); 
			Route::get('/course_management/api/instructors_filter', 'CourseManagement@FilterInstructor')->name('api.filter_instructor'); 
			Route::post('/course_management/instructors/action', 'CourseManagement@InstructorPermission')->name('instructor.action');
			Route::post('/course_management/instructors/update', 'CourseManagement@UpdatePermission')->name('instructor.update');
			/**
			 * 
			 * COURSE CREATION : VIDEO AND CONTENT -- BEGIN
			 * 
			 */
			Route::namespace("Course_Creation")->name('course.creation.')->group(function(){
				Route::get('/course/management/content', 'VideoContentController@index')->name('content');
				Route::post('/course/management/content/video/upload', 'VideoContentController@video_upload')->name('content.video.upload');
			});

			Route::get('/course/management/content/sections', 'CourseManagement@content_sections')->name('content.sections');
			/**
			 * Video & Content
			 * 
			 */
			Route::get('/course/management/content/preview/{id}', 'CourseManagement@preview_video')->name('content.preview.video');
			Route::post('/course_management/video_and_content/storeSection', 'CourseManagement@StoreVideoContentSection')->name('video_and_content.store_section');
			// removal of part in section  & section
			Route::get('/course/management/content/remove/section', 'CourseManagement@remove_section')->name('content.remove.section');
			Route::get('/course/management/content/remove/video', 'CourseManagement@section_video_remove')->name('content.section.video.remove');
			Route::get('/course/management/content/refresh/video', 'CourseManagement@section_video_refresh')->name('content.section.video.refresh');
			Route::get('/course/management/content/cancel/video', 'CourseManagement@section_video_cancel_upload')->name('content.section.video.cancel.upload');
			Route::get('/course/management/content/remove/part', 'CourseManagement@remove_part')->name('content.remove.part');
			Route::post('/course/management/content/video/store', 'CourseManagement@store_video')->name('content.store.video');
			Route::post('/course/management/content/article/store', 'CourseManagement@store_article')->name('content.store.article');
			Route::post('/course/management/content/textarea/upload', 'CourseManagement@upload_textarea_image')->name('content.upload.textarea');
			Route::post('/course/management/content/quiz/store', 'CourseManagement@store_quiz')->name('content.store.quiz');
			Route::post('/course/management/content/quiz/item/store', 'CourseManagement@store_quiz_item')->name('content.store.quiz.item');
			/**
			 * 
			 * COURSE CREATION : VIDEO AND CONTENT -- END
			 * 
			 */
			Route::get('/course/management/handouts', function(){
				if(_my_course_permission("handouts") && _course_creation_restricted("handouts")){
					return view('page/courseManagement/handouts');
				}
				
				return view('template/errors/404');
			})->name('handouts'); // ok
			Route::get('/course_management/allow/handouts', 'CourseManagement@AllowHandouts')->name('allow.handouts'); 
			Route::get('/course_management/api/handouts', 'CourseManagement@ShowHandouts')->name('api.handouts'); 
			Route::post('/course_management/handouts/store', 'CourseManagement@StoreHandouts')->name('handouts.store');
			Route::post('/course/management/handouts/remove', 'CourseManagement@RemoveHandouts')->name('handouts.remove');
			Route::get('/course_management/api/disclaimer', 'CourseManagement@disclaimer')->name('api.disclaimer'); 
			Route::get('/course_management/api/disclaimer_session', 'CourseManagement@disclaimer_session')->name('session.disclaimer'); 
			Route::get('/course/management/grading', function(){
				if(_my_course_permission("grading") && _course_creation_restricted("grading")){
					return view('page/courseManagement/gradingAssessment');
				}
				return view('template/errors/404');
			})->name('grading_and_assessment'); // ok
			Route::post('/course_management/grading_and_assessment/store','CourseManagement@StoreGradingAssessment')->name('grading_and_assessment.store');
			/**
			 * Publishing your course
			 * 
			 */
			Route::get('/course/management/submit_accreditation','CourseManagement@submit_accreditation')->name('submit_for_accreditation'); // ok
			Route::get('/course/management/is/prc-acc','CourseManagement@can_submit_accreditation')->name('submit.accreditation.can'); 
			Route::post('/course_management/submit_for_accreditation/store','CourseManagement@StoreAccreditation')->name('accreditation.store');
			Route::post('/course_management/submit_for_accreditation/session','CourseManagement@SessionAccreditation')->name('accreditation.session');
			Route::post('/course_management/instructional_design/store','CourseManagement@StoreInstructional')->name('instructional.store');
			Route::post('/course_management/expenses_breakdown/store','CourseManagement@StoreExpenseBreakdown')->name('instructional.store');
			Route::get('/course_management/cpdas/{id}', 'CourseManagement@cpdas')->name('view.cpdas');
			Route::get('/course/management/publish', function(){
				if(_my_course_permission("publish")){

					$accreditation = _current_course()->accreditation ? json_decode(_current_course()->accreditation) : [];
					$accreditation = array_map(function($acc){
						$profession = Profession::select("title")->find($acc->id);
						$acc->title = $profession->title ?? "Not found";

						return $acc;
					}, $accreditation);

					$data["professions"] = $accreditation;

					return view('page/courseManagement/pricePublish', $data);
				}
				
				return view('template/errors/404');
			})->name('price_and_publish'); // pending
			Route::post('/course_management/price_and_publish/store','CourseManagement@StorePricePublish')->name('price_publish.store');
			Route::post("/course_management/upload_video","CourseManagement@uploadVideo")->name("upload_video_action"); 
			Route::post("/course_management/upload/video/content","CourseManagement@section_video_upload")->name("section.video.upload"); 
			Route::post("/course_management/upload/async/video/content","CourseManagement@asyncUploadSP_Video")->name("upload_content-video_action-async"); 
			Route::post("/course_management/upload/file","CourseManagement@uploadFile")->name("upload.file"); 
			Route::get('/course_management/api/submit_review','CourseManagement@submitForReview')->name('submit_review');
			Route::get('/course/management/check/live','LiveController@check_live')->name('check.live');
			/**
			 * Live Course
			 * 
			 */
			Route::get('/course/live/{url}', 'LiveController@live')->name('live');
			Route::get('/course/preview/{url}', 'LiveController@preview')->name('preview');
			Route::get('/course/live/api/sections', 'LiveController@get_sections')->name('api.live.sections');
			Route::get('/course/live/api/grade_requirements', 'LiveController@get_grade_requirements')->name('api.live.grade_requirements');
			Route::post('/course/live/quiz/grade', 'LiveController@get_overall_quiz_grade')->name('api.live.get_overall_quiz_grade');
			Route::post('/course/live/progress', 'LiveController@progress_save')->name('live.save.progress');
			
		});
		
		Route::prefix('profile')->group(function(){
			Route::get('/register', function(){
				return view('page/profile/register');
			})->name('profile.register');


			Route::get('/personal', function(){
				$professions = json_decode(Auth::user()->professions);
				$ids = $professions ? array_map(function($pro){
					return $pro->id;
				}, $professions) : [];

				$profession_user = $professions ? array_map(function($pro){
					$name = _get_profession($pro->id);
					$pro->name = $name['profession'];
					return $pro;
				}, $professions) : [];

				return view('page/profile/personal')->with("data", ["profession_ids" => $ids, "professions_user" => $profession_user]);
			})->name('profile.personal');

			Route::get('/account', function(){
				return view('page/profile/account');
			})->name('profile.account');

			Route::get('/password', function(){
				return view('page/profile/password');
			})->name('profile.password');

			Route::get('/association', function(){
				return view('page/profile/association');
			})->name('profile.association');

			Route::get('/privacy', function(){
				return view('page/profile/privacy');
			})->name('profile.privacy');

			/**
			 * REFERRAL_CODE:: BEGIN
			 */
			Route::namespace('User')->group(function(){
				Route::prefix('referral')->name('referral.')->group(function(){
					Route::get('/', 'ReferralController@index')->name('index');
					Route::get('/generate/code', 'ReferralController@generate_code')->name('generate.code');
					Route::get('/join', 'ReferralController@join_referer')->name('referer.join');
				});
			});
			/**
			 * REFERRAL_CODE:: END
			 */
		});

		Route::namespace('Instructor')->prefix('instructor')->name('instructor.')->group(function(){
			Route::post('/register/action', 'InstructorController@register')->name('register.action');
			Route::post('/resume/action', 'InstructorController@resume_save')->name('resume.action');
			Route::post('/register/complete_register', 'InstructorController@complete_register')->name('complete_register');
			Route::get('/register', function(){
				if(in_array(Auth::user()->instructor, ["active", "inactive", "in-review", "pending"])){
					Session::flash('error', "You're already registered as an Instructor!");
					return redirect()->route('home');
				}

				$professions = json_decode(Auth::user()->professions);
				$ids = array_map(function($pro){
					return $pro->id;
				}, $professions);

				$profession_user = array_map(function($pro){
					$name = _get_profession($pro->id);
					$pro->name = $name['profession'];
					return $pro;
				}, $professions);

				// return view('page/profile/instructor_register')->with("data", ["profession_ids" => $ids, "professions_user" => $profession_user]);
				return view('page/profile/register_instructor')->with("data", ["profession_ids" => $ids, "professions_user" => $profession_user]);
			})->name('register');

			Route::get('/resume', 'InstructorController@resume_form')->name('resume');
			Route::post('/resume/update', 'InstructorController@resume_update')->name('resume.update');

			Route::get('/api/list', 'InstructorController@instructors')->name('api.list');
		});
	});

	Route::namespace('Auth')->name('auth')->group(function () {
		Route::get('/signout', 'LoginController@signout')->name('signout');
	});
});

/**
 * Public Profile
 * 
 */

Route::get('provider/{name}', function(Request $request){
	$name = $request->name;
	$provider = Provider::where("url","=", $name)->first();
	
	if($provider){
		if($provider->status == "approved"){
			return view('page/public/provider_profile')->with("provider",$provider);
		}

		if(Auth::check() && _is_co_provider($provider->id)){
			return view('page/public/provider_profile')->with("provider",$provider);
		}

		if(Auth::check() && Auth::user()->superadmin == "active"){
			return view('page/public/provider_profile')->with("provider",$provider);
		}
	}
	return view('template/errors/404');
})->name('public.profile.provider');

Route::get('provider/preview/{id}/{name}', function(Request $request){
	$name = $request->name;
	$provider = Profile_Requests::where("id","=", $request->id)->first();
	
	if($provider){
		if($provider->status == "active"){
			return view('page/public/provider_profile')->with("provider",$provider);
		}

		if(Auth::check() && _is_co_provider($provider->id)){
			return view('page/public/provider_profile')->with("provider",$provider);
		}

		if(Auth::check() && Auth::user()->superadmin == "active"){
			return view('page/public/provider_profile')->with("provider",$provider);
		}
	}
	return view('template/errors/404');

})->name('public.profile.provider');

Route::get('user/{name}', function(Request $request){
	$name = $request->name;
	$user = User::where("username","=", $name)->first();
	if ($user) {
		if($user->status == "active"){
			if($user->instructor == "active"){
				return redirect("/instructor/{$request->name}");
			}

			return view('page/public/user_profile')->with("user",$user);
		}

		if(Auth::check() && Auth::user()->id == $user->id){
			return view('page/public/user_profile')->with("user",$user);
		}
	}
	return view('template/errors/404');
})->name('public.profile.user');

Route::get('instructor/{name}', function(Request $request){
	$name = $request->name;
	$user = User::where("username","=", $name)->first();
	if ($user) {
		if($user->instructor == "active"){
			if($user->status == "active"){
				$data =array(
					'user'=>$user,
					'course_head_title'=> $user->name. " - ".$name." | Instructors | FastCPD",
					'headline' => $user->headline
				);
				return view('page/public/user_profile',$data);
			}
	
			if(Auth::check() && Auth::user()->id == $user->id){
				return view('page/public/user_profile')->with("user",$user);
			}
		}else{
			return redirect("/user/{$request->name}");
		}
	}
	return view('template/errors/404');
})->name('public.profile.instructor');

Route::get('instructor/preview/{req_id}/{name}', function(Request $request){
	$name = $request->name;
	$id = $request->req_id;
	$user = Profile_Requests::where("id","=", $id)->first();
	if ($user) {
		return view('page/public/user_profile')->with("user",$user);
	}
	return view('template/errors/404');
})->name('public.profile.instructor');

Route::namespace('Provider')->prefix('provider')->name('provider.')->group(function(){
	Route::get('user/accept/{url}/{id}', 'UserController@accept_user_invitation')->name('provider.user.accept');
	Route::get('instructor/accept/{url}/{id}', 'UserController@accept_inst_invitation')->name('provider.inst.accept');
});

Route::namespace('Api')->prefix('api')->name('api.')->group(function(){
	Route::get('courses/view/units', 'CourseController@view_units')->name('api.course.view.units');
	Route::get('courses/profession', 'CourseController@by_profession')->name('api.profession');
	Route::get('courses/in-progress', 'CourseController@in_progress_courses')->name('api.in_progress.courses');
	Route::get('courses/top', 'CourseController@top_courses')->name('api.top.courses');
	Route::get('courses/newest', 'CourseController@newest_courses')->name('api.newest.courses');
	Route::get('courses/category', 'CourseController@category')->name('api.category.courses');
	Route::get('courses/provider', 'CourseController@provider_courses')->name('api.provider.courses');

	Route::get('course/instructor/list', 'CourseController@instructor_list')->name('api.course.instructors.list');
	Route::get('courses/instructor', 'CourseController@instructors_courses')->name('api.instructor.courses');

	Route::get('course/preview', 'CourseController@request_course_preview')->name('api.preview.course');

	Route::get('provider/instructors', 'ProviderController@provider_instructors')->name('api.provider.instructor.list');
	Route::get('provider/courses', 'ProviderController@provider_courses')->name('api.provider.courses.list');
	Route::get('provider/webinars', 'ProviderController@provider_webinars')->name('api.provider.webinars.list');

	Route::get('instructor/providers', 'InstructorController@instructor_provider')->name('api.instructor.provider.list');
	Route::get('instructor/courses', 'InstructorController@instructor_courses')->name('api.instructor.courses.list');
	Route::get('instructor/webinars', 'InstructorController@instructor_webinars')->name('api.instructor.webinars.list');

	Route::get('user/associated/providers', 'ProfileController@user_assoc_providers')->name('api.user.assoc.provider.list');
	Route::get('user/associated/courses', 'ProfileController@user_assoc_courses')->name('api.user.assoc.courses.list');
	/**
	 * 
	 * Superadmin
	 */
	Route::get('voucher/courses', 'VoucherController@voucher_courses')->name('api.voucher.courses.list');
	Route::get('voucher/search', 'VoucherController@search_voucher')->name('api.voucher.search');
	/**
	 * Webinar
	 */
	Route::get('webinar/check_slots', 'WebinarController@check_slots')->name('api.webinar.slots');
	Route::get('webinars/newest', 'WebinarController@newest_webinars')->name('api.newest.webinars');
	Route::get('webinars/provider', 'WebinarController@provider_webinars')->name('api.provider.webinars');
	Route::get('webinars/instructors', 'WebinarController@instructor_list')->name('api.webinars.instructors.list');
	/**
	 * Category
	 */
	Route::get('category/providers', 'CategoryController@providers')->name('api.category.providers');
	Route::get('category/webinars', 'CategoryController@webinars')->name('api.category.webinars');
	Route::get('category/list', 'CategoryController@list')->name('api.category.list');
});

Route::namespace('Courses')->name('courses.')->group(function(){
	/**
	 * Category Page
	 * 
	 */
	Route::get('/courses/{url}', function (Request $request) {
		$url = $request->url;
		$profession = Profession::where("url", "=", $url)->first();
		if($profession){
			$data = array(
				"profession" => $profession,
				"course_head_title" => ($profession->title ?? "Profession")." - CPD Accredited | FastCPD",
			);
	
			return view('page/category/index',$data);
		}
		return view('template.errors.404');
		
	})->name('index');
});

Route::group(['middleware' => ['auth']], function() {
	Route::namespace('Webinar')->name('webinar.')->group(function(){
		Route::get('/webinar/live/api/sections', 'LiveController@get_sections')->name('api.live.sections');
		Route::get('/webinar/live/api/grade_requirements', 'LiveController@get_grade_requirements')->name('api.live.grade_requirements');
		Route::post('/webinar/live/quiz/grade', 'LiveController@get_overall_quiz_grade')->name('api.live.get_overall_quiz_grade');
		Route::post('/webinar/live/progress', 'LiveController@progress_save')->name('live.save.progress');
		Route::post('/webinar/live/attendance', 'LiveController@attendance_save')->name('live.save.attendance');
		Route::get('/webinar/live/{url}', 'LiveController@index')->name("live");
	});
	
});

Route::namespace('Webinar')->name('webinar.')->group(function(){
	Route::get('/webinar/{url}', 'WebinarController@page')->name("page");
	Route::get('/webinar/voucher/add', 'WebinarController@voucher_add')->name('voucher.add');
	Route::get('/webinar/voucher/remove', 'WebinarController@voucher_remove')->name('voucher.remove');
});

Route::namespace('Course')->name('course.public.')->group(function(){

	/**
	 * Course Page
	 * 
	 */
	Route::get('/course/buy_now', 'CourseController@buy_now')->name('buy_now');
	Route::get('/course/{url}', 'CourseController@page')->name('page');
	Route::get('/course/voucher/add', 'CourseController@voucher_add')->name('voucher.add');
	Route::get('/course/voucher/remove', 'CourseController@voucher_remove')->name('voucher.remove');
});

Route::namespace('MyCart')->name('mycart.')->prefix('cart')->group(function(){
	Route::get('/', 'MainController@index')->name('index');

	/**
	 * Store to Cart
	 * 
	 */
	
	Route::get('add', 'MainController@add')->name('add');
	Route::get('remove', 'MainController@remove')->name('remove');
	Route::get('voucher/remove', 'MainController@voucher_remove')->name('voucher.remove');
	Route::get('voucher/add', 'MainController@voucher_add')->name('voucher.add');
});

Route::namespace('Notification')->name('notification')->prefix('notification')->group(function(){
	
	Route::get('getNotif', 'NotificationController@getNotif')->name('get.notif');
	Route::get('getUnseenedNotif', 'NotificationController@getUnseenedNotif')->name('get.unseened.notif');
	Route::get('/redirect/{id}', 'NotificationController@redirect')->name('redirect.notif');
	Route::get('/email_sample', 'NotificationController@email_sample')->name('redirect.notif');
	Route::get('/redirect_email/{module}/{id}', 'NotificationController@redirect_email')->name('redirect.email');
});

Route::namespace('WebHooks')->name('webhooks')->prefix('hooks')->group(function(){
	Route::post('/pmongo/payment', 'PayMongoController@catch_hook')->name('paymongo.hook');
	Route::get('/pmongo/payment/card', 'PayMongoController@catch_card_hook')->name('paymongo.card.hook');
});

Route::group(['middleware' => ['auth']], function() {
	Route::namespace('Callback')->name('callback')->prefix('callback')->group(function(){
		Route::get('/pdragon', 'DragonPayController@default_callback')->name('dragonpay.callback.default');
	});
});

Route::name("payment_errors")->prefix("payment/error")->group(function(){
	Route::get("{method}", function(){
		return view("page.cart.payment_forms.failed", ["reference_number" => false]);
	})->name("pmongo.error.ewallet");
});

Route::namespace('Verify')->name('verify')->prefix("verify")->group(function(){
	Route::get('/', 'CertificateController@index')->name('public.verify.certificate.index');
	Route::get('/search', 'CertificateController@search')->name('public.verify.certificate.search');
	Route::get('/view/pdf/{code}', 'CertificateController@pdf')->name('public.verify.certificate.pdf');
	Route::get('/{code}', 'CertificateController@verify')->name('public.verify.certificate.verify');
});

Route::namespace('Announcements')->name('announcements')->prefix("announcements")->group(function(){
	Route::get('api/get_announcement', 'AnnouncementController@getAnnouncement')->name('announcements.list');
	Route::get('api/closed_announcements', 'AnnouncementController@closed_announcements')->name('announcements.closed_announcements');
});

if(App::environment('production')){
	Route::namespace('Data')->name('data')->group(function(){
		Route::get('/sitemap.xml', 'SitemapController@index')->name('data.sitemap.index');
		Route::get('/sitemap.xml/pages', 'SitemapController@pages')->name('data.sitemap.pages');
		Route::get('/sitemap.xml/professions', 'SitemapController@professions')->name('data.sitemap.professions');
		Route::get('/sitemap.xml/courses', 'SitemapController@courses')->name('data.sitemap.courses');
		Route::get('/sitemap.xml/providers', 'SitemapController@providers')->name('data.sitemap.providers');
		Route::get('/sitemap.xml/users', 'SitemapController@users')->name('data.sitemap.users');
	});
}

Route::namespace('Referral')->name('referer.')->prefix("referer")->group(function(){
	Route::get('/signup', 'ReferralController@signup_index')->name('signup');
	Route::get('/{code}', 'ReferralController@index')->name('index');
});
