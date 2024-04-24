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
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700&display=swap">
		<!--end::Fonts -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<!--begin:: Vendor Plugins -->
		<link media="all" href="{{asset('plugins/general/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/animate.css/animate.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/plugins/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/plugins/flaticon/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/plugins/flaticon2/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link media="all" href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />

		<!--begin:: Vendor Plugins for custom pages -->
		<link media="all" href="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('css/star-rating-svg.css')}}" rel="stylesheet" type="text/css" />
		<!--end:: Vendor Plugins for custom pages -->
		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link media="all" href="{{ asset('css/skins/header/base/dark.css') }}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{ asset('css/skins/header/menu/dark.css') }}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{ asset('css/skins/brand/dark.css') }}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{ asset('css/skins/aside/dark.min.css') }}" rel="stylesheet" type="text/css" />
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
			.share-buttons button{margin-right:5px;}

			@media (max-width: 767px) {
				.header-title-span{display:none;}
			}
			@media (min-width: 768px) {
				.header-title-display{display:none;}
			}
			.header-title-span{background-color:#282f48;border-radius:4px;padding:0.65rem 1.1rem;color:white;font-weight:500;}
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
				<a href="#">
					<img alt="Logo" src="{{ asset('img/system/logo-6.png') }}" width="160"/>
				</a>
				<a href="javascript:;" class="header-title-span">
					<span>{{ $course->title ?? 'Undefined' }}</span>
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				@if(Request::segment(2) == "live")
				<a class="btn btn-sm btn-icon btn-outline-warning leave-a-rating-icon"><i class="fa fa-star kt-font-warning"></i></a> &nbsp;
				<div class="kt-header__topbar-item dropdown">
					<div class="kt-header__topbar-item kt-header__topbar-item--user">
						<div class="kt-header__topbar-wrapper">
							<div class="kt-header__topbar-user">
								<div id="live-course-progress-circle1"></div>
							</div>
						</div>
					</div>
				</div>
				@endif
				<button class="btn btn-outline-secondary btn-sm btn-icon" data-target="#share-modal" data-toggle="modal"><i class="fa fa-share-square kt-label-font-color-2"></i></button>
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
                                <a href="/">
                                    <img alt="Logo" src="{{ asset('img/system/logo-6.png') }}" width="160" />
                                </a>
                            </div>

							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
								<ul class="kt-menu__nav ">
									<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="javascript:;" class="kt-menu__link">
											<span class="kt-menu__link-text">{{ $course->title ?? 'Undefined' }}</span>
										</a>
									</li>
								</ul>
							</div> 
						</div>
						<!-- emd:: Header Menu -->
						
						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">
							@if(Request::segment(2) == "live")
							<div class="kt-header__topbar-item dropdown">
								<div class="kt-header__topbar-item kt-header__topbar-item--user">
									<div class="kt-header__topbar-wrapper">
										<div class="kt-header__topbar-user" id="leave_a_rating" >
											<i class="fa fa-star kt-font-warning"></i> &nbsp; Leave a Rating
										</div>
									</div>
								</div>
							</div>
							<div class="kt-header__topbar-item dropdown">
								<div class="kt-header__topbar-item kt-header__topbar-item--user">
									<div class="kt-header__topbar-wrapper">
										<div class="kt-header__topbar-user">
											<div id="live-course-progress-circle2"></div>
											<span id="live-course-progress-title">0 out of 0 complete</span>
										</div>
									</div>
								</div>
							</div>
							@endif
							<div class="kt-header__topbar-item dropdown">
								<div class="kt-header__topbar-item kt-header__topbar-item--user">
									<div class="kt-header__topbar-wrapper">
										<div class="kt-header__topbar-user">
											<span class="kt-header__topbar-username kt-hidden-mobile">
												&nbsp;&nbsp;<button class="btn btn-outline-secondary kt-label-font-color-2" data-target="#share-modal" data-toggle="modal"><i class="fa fa-share-square "></i>Share</button>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end:: Header Topbar -->
					</div>
					<!-- end:: Header -->
					@yield('content')
				</div>
			</div>
		</div>
		<!-- end:: Page -->
		
		<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal-label" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="share-modal-label">Share Course</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-center">
							<input class="form-control" type="text" readonly value="<?=Request::segment(3) ? (URL::to('course/'.Request::segment(3))) : ''?>" id="copy_course_url" />
						</div>
						<div class="kt-space-10"></div>
						<div class="row justify-content-center share-buttons">
							<button type="button" class="btn btn-warning" onclick="copy_('#copy_course_url')"><i class="flaticon2-copy"></i> Copy URL</button>
							<button type="button" class="btn btn-dark" data-dismiss="modal" onclick="open_send_email_modal('#copy_course_url')"><i class="flaticon2-new-email"></i> Send Email</button>
							<!-- <button type="button" class="btn btn-facebook"><i class="socicon-facebook"></i> Facebook</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="share-to-email-modal" tabindex="-1" role="dialog" aria-labelledby="share-to-email-modal-label" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="share-to-email-modal-label">Share URL to email</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div>
					<div class="modal-body">
						<div style="margin:8px;">
							<div class="form-group row">
								<label class="form-label">Message:</label>
								<textarea class="form-control" name="send_email_textarea">Hi, I just wanted to share this course to you. Try it now!</textarea>
							</div>
							<div class="form-group row">
								<label class="form-label">To:</label>
								<input class="form-control" type="text" name="send_email_to" placeholder="mypartner@****.com"/>
							</div>
							<div class="form-group row">
								<label class="form-label">From:</label>
								<input class="form-control" type="text" name="send_email_from" placeholder="myemail@****.com"/>
							</div>
							<div class="kt-space-10"></div>
							<div class="row justify-content-center share-buttons">
								<button type="button" class="btn btn-dark" data-dismiss="modal" onclick="send_email()"><i class="flaticon2-new-email"></i> Send Email</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

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
		<script src="{{asset('plugins/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/moment/min/moment.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/moment/locale/fr.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/autosize/dist/autosize.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-notify.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-validation/dist/jquery.validate.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-validation/dist/additional-methods.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/jquery-validation.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/dual-listbox/dist/dual-listbox.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/plugins/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/sweetalert2.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/owl.carousel/dist/owl.carousel.min.js')}}" type="text/javascript"></script>

		<!--end:: Vendor Plugins -->
		<script src="{{asset('js/scripts.bundle.min.js')}}" type="text/javascript"></script>

		<!--begin:: Vendor Plugins for custom pages -->
		<script src="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
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
			toastr.options = {
				"closeButton": true,
				"debug": false,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-top-center",
				"preventDuplicates": false,
				"onclick": null,
				"showDuration": "5000",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut",
				"preventDuplicates": true,
				"preventOpenDuplicates": true
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
		<script>
			jQuery(document).ready(function () {
				$.ajax({
					url: "/provider/review/api/checkProgressReview",
					data: {
						course_id: `<?= $course->id ?>`,
						
					},
					success:function(response){
						var progress = response.status;
						var rating_step = response.rating_step;
					},
					error:function(){

					},
				});
			});
		</script>
	</body>
</html>