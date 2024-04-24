<?php $show = true; ?>
<html lang="en">

	<!-- begin::Head -->
	<head>
		@if(App::environment('production'))
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PZVCFKJ');</script>
		<!-- End Google Tag Manager -->
		@endif
		<meta charset="utf-8" />
		<title>Fast CPD</title>
		<meta name="description" content="Updates and statistics">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
		<!--end::Fonts -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<!--begin:: Vendor Plugins -->
		<link href="{{asset('plugins/general/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/tether/dist/css/tether.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/ion-rangeslider/css/ion.rangeSlider.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/nouislider/distribute/nouislider.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/owl.carousel/dist/assets/owl.carousel.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/dropzone/dist/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/quill/dist/quill.snow.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/@yaireo/tagify/dist/tagify.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/bootstrap-markdown/css/bootstrap-markdown.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/animate.css/animate.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/dual-listbox/dist/dual-listbox.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/morris.js/morris.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/plugins/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/plugins/flaticon/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/plugins/flaticon2/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />

		<!--begin:: Vendor Plugins for custom pages -->
		<link href="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/core/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/daygrid/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/list/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/@fullcalendar/timegrid/main.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-autofill-bs4/css/autoFill.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/jstree/dist/themes/default/style.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/jqvmap/dist/jqvmap.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/custom/jkanban/dist/jkanban.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/star-rating-svg.css')}}" rel="stylesheet" type="text/css" />
		<!--end:: Vendor Plugins for custom pages -->
		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{asset('css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/header/menu/light.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />

		<style>
			html {scroll-behavior: smooth;}
			.toast-title{color:white !important; font-weight:500;}
			.kt-aside-menu, .kt-aside-menu-wrapper{background-color:#2f2f39 !important;}
			.kt-aside__brand {background-color:#272733 !important;}
			.kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__heading, .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link{background-color:#2A7DE9 !important;}
			.kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__heading .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__heading .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__link .kt-menu__link-icon svg g [fill]{fill:#fff !important;}
			.static-label{color:#A2A3B7;padding:10px;font-weight:600;font-size:16px;border-bottom:1px solid #484856;}
			.select2-container--default .select2-results__option[aria-selected=true]{background-color:#C5CCDA; color:white; font-weight: 600;}
			.kt-mycart__item:hover{background-color:#E9EDEF;}
			.cart-icon{position:relative;}
			.cart-badge {position:absolute;top:15;right:2;padding:5px;font-size:11px;}
		</style>

		<!-- begin::Styles from other pages -->
		@yield('styles')
		<!-- end::Styles from other pages -->
		@if(App::environment('production'))
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window, document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '336127034471576');
			fbq('track', 'PageView');
		</script>
		<noscript>
			<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=336127034471576&ev=PageView&noscript=1"/>
		</noscript>
		@endif
	</head>
	<!-- end::Head -->

	<!-- <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading kt-aside--minimize"> -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--solid kt-page--loading">
		@if(App::environment('production'))
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZVCFKJ"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		@endif
		<!-- begin:: Page -->
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="/"><img alt="Logo" src="{{asset('img/system/logo-6.png')}}" width="160"/></a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">
						<!-- begin:: Header Menu -->
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                            <div class="kt-header-logo">
                                <a href="<?=str_contains(url()->current(), 'auth/social/register') ? "#" : "/"?>"> 
                                    <img alt="Logo" src="{{asset('img/system/logo-1.png')}}" width="160" />
                                </a>
                            </div>
						</div>
						<!-- end:: Header Menu -->
					</div>

					<!-- end:: Header -->
					<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
							<div class="row">
								@yield('content')
							</div>
						</div>
					</div>

					<!-- begin:: Footer -->
					<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
						<div class="kt-container  kt-container--fluid ">
							<div class="kt-footer__copyright">
								<a href="javascript:;" class="kt-link">&copy;FastCPD 2020</a>
							</div>
							<div class="kt-footer__menu" style="text-align:right;">
								<a href="/legal/terms-of-service" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link">Terms of Service</a>
								<a href="/legal/privacy-policy" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link">Privacy Policy</a>
								<a href="https://www.facebook.com/FastCPD" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link">Help</a>
							</div>
						</div>
					</div>

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- end:: Page -->
		
		<!--begin::Modal-->
		<div class="modal fade" id="close_account" tabindex="-1" role="dialog" aria-labelledby="close_label" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header" style="text-align:center;">
						<h5 class="modal-title" id="close_label" style="width:100%;">Close Account<br/><small>Close your account permanently</small></h5>
					</div>
					<div class="modal-body">
						<p>Are you sure to close your account? You will lose access to all of your courses and profiles <b>forever</b>.</p>
						<input type="password" class="form-control" id="confirm_close_account"/>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-danger" onclick="closeAccountPassword()">Close Account</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-m" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Log In to Your FastCPD Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="la la-remove"></span>
                        </button>
                    </div>
                    <form class="kt-form" id="signin" method="GET" action="/signin">
                        <div class="modal-body">
                            <div class="form-group row kt-margin-t-20">
                                <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                                    <a href="{{url('/redirect')}}" class="btn btn-default btn-brand" style="width:100%;">
                                        <i class="flaticon-facebook-letter-logo"></i><span style="font-weight:bold;">Continue with Facebook</span>
                                    </a>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;">
                                    <button type="button" class="btn btn-default btn-brand" data-dismiss="modal" style="width:100%;">
                                    <i class="flaticon-letter-g"></i><span style="font-weight:bold;">Continue with Google</span>
                                    </button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom:10px;text-align:center;">
                                    OR
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Email" autocomplete="off" name="email" id="email"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon2-send"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="password" class="form-control" placeholder="Password" name="password" id="password"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon2-safe"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-9 col-sm-12" style="padding-bottom:10px;padding-left:10px;">
                                    <label class="kt-checkbox">
                                        <input type="checkbox" name="remember"> Keep me logged in
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <button id="signin_submit" class="btn btn-info btn-elevate kt-login__btn-primary" style="width:100%;font-weight:bold;color:white;">Log In</button>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center;">
                            or <a href="javascript:;" id="kt_login_forgot" class="kt-login__link" data-toggle="modal" data-target="#forgot_password_modal"> Forgot Password ?</a>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                            <label class="col-form-label col-lg-12 col-sm-12">Don't have an account? <a href="#" id="signup_link" data-toggle="modal" data-target="#signup_modal">Sign Up</a></label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		<div class="modal fade" id="signup_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-m" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Sign up and start earning CPD units!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="la la-remove"></span>
                        </button>
                    </div>
                    <form class="kt-form" action="" id="signup">
                        <div class="modal-body">
                            <div class="form-group row kt-margin-t-20">
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Full Name" autocomplete="off" name="name" id="name"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon2-user"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Email" name="upemail" autocomplete="off" id="upemail"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon2-send"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="password" class="form-control" placeholder="Password" name="uppassword" id="uppassword"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon2-safe"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="password" class="form-control" placeholder="Confirm Password" name="rpassword" id="rpassword"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon2-shield"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-9 col-sm-12 form-group" style="padding-left:10px;">
                                    <input type="checkbox" style="float:left;width:5%;" checked>
                                    <span style="float:right;width:95%;font-size:12px;">I want to get notified for exclusive deals, sales promotions, and recommended courses.</span> 
                                </div>
								<div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-top:-10px;margin-bottom:5px;">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="PRC Number" autocomplete="off" name="prc_no" id="prc_no"/>
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="flaticon-medal"></i></span>
                                        </span>
                                    </div>
									<span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
									<select class="form-control kt-selectpicker" style="width:100%;" id="profession" name="profession">
										@foreach(_all_professions() as $pro)
										<option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
										@endforeach
									</select>
									<span class="form-text text-muted">Knowing your profession helps us provide you with better offerings.</span>
								</div>
							<div class="col-lg-12 col-md-12 col-sm-12 form-group kt-login__actions">
								<button id="signup_submit" class="btn btn-info btn-elevate kt-login__btn-primary" style="width:100%;">Sign Up</button>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group" style="text-align:center;margin-bottom:-10px;margin-top:-30px;">
                                <label class="col-form-label col-lg-12 col-sm-12" style="font-size:10px;">By signing up, you agree to our <a href="">Terms of Service</a> and <a href="">Privacy Policy</a>.</label>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                            <label class="col-form-label col-lg-12 col-sm-12">Already have an account? <a href="" id="login_link" data-toggle="modal" data-target="#login_modal">Log In</a></label>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
		<div class="modal fade" id="forgot_password_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-m" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Forgot Password?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="la la-remove"></span>
                        </button>
                    </div>
                    <form class="kt-form" action="" id="forgot_password">
                        <div class="modal-body">
							<div class="kt-login__forgot">
								<div class="kt-login__head" style="margin-bottom:10px;margin-left:5px;">
									<div class="kt-login__desc">Enter your email to reset your password:</div>
								</div>
								<form class="kt-form" action="">
									<div class="col-lg-12 col-md-12 col-sm-12 form-group" style="margin-bottom:5px;" style="padding-bottom:10px;">
										<div class="kt-input-icon kt-input-icon--left">
											<input type="text" class="form-control" placeholder="Email" autocomplete="off" name="email" id="email"/>
											<span class="kt-input-icon__icon kt-input-icon__icon--left">
												<span><i class="flaticon2-send"></i></span>
											</span>
										</div>
										<span class="form-text text-muted"></span>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12" style="margin-left:130px;margin-top:10px;">
										<button id="forgot_submit" class="btn btn-info btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
										<button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
									</div>
								</form>
							</div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                            <label class="col-form-label col-lg-12 col-sm-12">Don't have an account? <a href="#" id="signup_forgot_link" data-toggle="modal" data-target="#signup_modal">Sign Up</a></label>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
		<!--end::Modal-->

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>
		<!-- end::Scrolltop -->

		@yield('top_scripts')

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#25cfa8",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#25cfa8",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->

		<!--begin:: Vendor Plugins -->
		<script src="{{asset('plugins/general/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/popper.js/dist/umd/popper.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/moment/min/moment.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/moment/locale/fr.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/wnumb/wNumb.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/block-ui/jquery.blockUI.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-datepicker/js/locales/bootstrap-datepicker.fr.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-datepicker.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-datetime-picker/js/locales/bootstrap-datetimepicker.fr.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-timepicker.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-maxlength/src/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/plugins/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-select/dist/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-switch.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/ion-rangeslider/js/ion.rangeSlider.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/corejs-typeahead/dist/typeahead.bundle.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/handlebars/dist/handlebars.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/inputmask/dist/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/inputmask/dist/inputmask/inputmask.date.extensions.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/inputmask/dist/inputmask/inputmask.numeric.extensions.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/nouislider/distribute/nouislider.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/owl.carousel/dist/owl.carousel.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/autosize/dist/autosize.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/clipboard/dist/clipboard.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/dropzone/dist/dropzone.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/dropzone.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/quill/dist/quill.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/@yaireo/tagify/dist/tagify.polyfills.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/@yaireo/tagify/dist/tagify.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/summernote/dist/summernote.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/markdown/lib/markdown.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-markdown/js/bootstrap-markdown.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-markdown.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-notify.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-validation/dist/jquery.validate.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-validation/dist/additional-methods.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/jquery-validation.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/dual-listbox/dist/dual-listbox.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/raphael/raphael.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/morris.js/morris.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/chart.js/dist/Chart.bundle.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/plugins/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/plugins/jquery-idletimer/idle-timer.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/waypoints/lib/jquery.waypoints.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/counterup/jquery.counterup.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/es6-promise-polyfill/promise.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/sweetalert2.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery.repeater/src/lib.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery.repeater/src/jquery.input.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery.repeater/src/repeater.js')}}" type="text/javascript"></script>
		<!-- This script sanitizes the assets of metronic in which removes customized functions and attributes: do not uncomment! If necessary, copy paste it in your page/view  -->
		<!-- <script src="{{asset('plugins/general/dompurify/dist/purify.js')}}" type="text/javascript"></script> -->

		<!--end:: Vendor Plugins -->
		<script src="{{asset('js/scripts.bundle.min.js')}}" type="text/javascript"></script>

		<!--begin:: Vendor Plugins for custom pages -->
		<script src="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/@fullcalendar/core/main.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/@fullcalendar/daygrid/main.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/@fullcalendar/google-calendar/main.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/@fullcalendar/interaction/main.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/@fullcalendar/list/main.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/@fullcalendar/timegrid/main.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/gmaps/gmaps.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/dist/es5/jquery.flot.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/source/jquery.flot.resize.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/source/jquery.flot.categories.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/source/jquery.flot.pie.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/source/jquery.flot.stack.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/source/jquery.flot.crosshair.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/flot/source/jquery.flot.axislabels.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net/js/jquery.dataTables.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-bs4/js/dataTables.bootstrap4.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/js/global/integration/plugins/datatables.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-autofill/js/dataTables.autoFill.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-autofill-bs4/js/autoFill.bootstrap4.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jszip/dist/jszip.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/pdfmake/build/pdfmake.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/pdfmake/build/vfs_fonts.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-buttons/js/buttons.colVis.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-buttons/js/buttons.flash.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-buttons/js/buttons.html5.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-buttons/js/buttons.print.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-colreorder/js/dataTables.colReorder.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-keytable/js/dataTables.keyTable.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-responsive/js/dataTables.responsive.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-rowgroup/js/dataTables.rowGroup.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-rowreorder/js/dataTables.rowReorder.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-scroller/js/dataTables.scroller.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/datatables.net-select/js/dataTables.select.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jstree/dist/jstree.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jqvmap/dist/jquery.vmap.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jqvmap/dist/maps/jquery.vmap.world.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jqvmap/dist/maps/jquery.vmap.russia.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jqvmap/dist/maps/jquery.vmap.usa.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jqvmap/dist/maps/jquery.vmap.germany.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jqvmap/dist/maps/jquery.vmap.europe.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/tinymce/themes/silver/theme.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/tinymce/themes/mobile/theme.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/custom/jkanban/dist/jkanban.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/jquery.star-rating-svg.min.js')}}" type="text/javascript"></script>

		<!--end:: Vendor Plugins for custom pages -->

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="{{asset('js/pages/dashboard.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/common.js')}}" type="text/javascript"></script>
		<!--end::Page Scripts -->


		<!-- begin::Scripts from other pages -->
		@yield('scripts')
		<!-- end::Scripts from other pages -->

		<!-- toastr -->
		<script>
			 $(document).ready(function(){
				$('#name').keyup(function(event){
					var value = event.target.value;
					$('#name').val(value.replace(/[^a-zA-Z,. ]/g, ''));
				});
			});

			$("#signin_submit").click(function(e) { 
				e.preventDefault();
				var btn = $(this);
				var form = $("#signin");

				form.validate({
					rules: {
						email: {
							required: true,
							email: true
						},
						password: {
							required: true
						}
					}
				});

				if (!form.valid()) {
					return;
				}

				btn.addClass(
					"kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
				).attr("disabled", true);

				form.ajaxSubmit({
					url: "/signin",
					success: function(response, status, xhr, $form) {
						if(response.status=='401'){
							setTimeout(function() {
								btn.removeClass(
									"kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
								).attr("disabled", false);

								showErrorMsg(
									form,
									"danger",
									response.msg,
								);
							}, 800);
						
						KTUtil.scrollTop();
						}else{
							window.location.href="/";
						}
					}
				});
			});

			$("#signup_submit").click(function() {
				// e.preventDefault();

				var btn = $(this);
				var form = $("#signup");
			
				form.validate({
					rules: {
						first_name: {
							required: true
						},
						middle_name: {
							required: true
						},
						last_name: {
							required: true
						},
						upemail: {
							required: true,
							email: true
						},
						uppassword: {
							required: true
						},
						rpassword: {
							required: true,
							equalTo: "#uppassword",
						},
						profession: {
							required: true
						},
						prc_no: {
							required: true
						},
						
					}
				});

				if (!form.valid()) {
					return;
				}

				btn.addClass(
					"kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
				).attr("disabled", true);

				form.ajaxSubmit({
					url: "/public/signup",
					method: "post",
					data: {
						_token: "{{ csrf_token() }}",
					},
					success: function(response, status, xhr, $form) {

						if(response.status==200){
							toastr.success("Success!", "You've been registered! Instructions have been sent to your email");
							setTimeout(() => {
								location.reload();
							}, 1000);
						}else{
							setTimeout(function() {
								btn.removeClass(
									"kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
								).attr("disabled", false);

								showErrorMsg(
									form,
									'danger',
									response.msg,
								);
							}, 800);

							KTUtil.scrollTop();
						}
					}
				});
			});

			$("#forgot_submit").click(function(e) {
				e.preventDefault();

				var btn = $(this);
				var form = $("#forgot_password");

				form.validate({
					rules: {
						email: {
							required: true,
							email: true
						}
					}
				});

				if (!form.valid()) {
					return;
				}

				btn.addClass(
					"kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
				).attr("disabled", true);

				form.ajaxSubmit({
					url: "/public/reset",
					method: "post",
					data: {
						_token: "{{ csrf_token() }}",
					},
					success: function(response, status, xhr, $form) {
						if(response.status == 200){
							toastr.success("Success!", "Request Sent! Instructions have been sent to your email");
							setTimeout(() => {
								location.reload();
							}, 1000);
						}else{    
							setTimeout(function() {
								btn.removeClass(
									"kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light"
								).attr("disabled", false); // remove
								form.clearForm(); // clear form
								form.validate().resetForm(); // reset validation states

								showErrorMsg(
									form,
									'danger',
									response.msg,
								);
							}, 800);

							KTUtil.scrollTop();
						}
					}
				});
			});

			var showErrorMsg = function(form, type, msg) {
				var alert = $(
					'<div class="alert alert-' +
						type +
						' alert-dismissible" role="alert">\
					<div class="alert-text">' +
						msg +
						'</div>\
					<div class="alert-close">\
						<i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
					</div>\
				</div>'
				);

				form.find(".alert").remove();
				alert.prependTo(form);
				//alert.animateClass('fadeIn animated');
				KTUtil.animateClass(alert[0], "fadeIn animated");
				alert.find("span").html(msg);
			};

			$('#login_link').click(function(){
				$('#signup_modal').modal('hide');
			});
			$('#signup_link').click(function(){
				$('#login_modal').modal('hide');
			});
			$('#kt_login_forgot').click(function(){
				$('#login_modal').modal('hide');
			});
			$('#signup_forgot_link').click(function(){
				$('#forgot_password_modal').modal('hide');
			});
			toastr.options = {
				"closeButton": true,
				"debug": false,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-top-center",
				"preventDuplicates": false,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
			
			var success = "{{ Session::has('success') ? Session::get('success') : null }}";
			var error = "{{ Session::has('error') ? Session::get('error') : null }}"
			var warning = "{{ Session::has('warning') ? Session::get('warning') : null }}";
			var info = "{{ Session::has('info') ? Session::get('info') : null }}";
			if(success){
				toastr.success("Success!", success);
			}

			if(error){
				toastr.error("Error!", error);
			}

			if(warning){
				toastr.warning("Warning!", warning);
			}

			if(info){
				toastr.info("Pay Attention!", info);
			}
			
			function closeAccountPassword(){
				alert("Sorry! No function yet!");
			}
		</script>
	</body>
</html>