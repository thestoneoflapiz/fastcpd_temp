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
		<link href="{{asset('plugins/general/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/plugins/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/plugins/flaticon/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/plugins/flaticon2/flaticon.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link media="all" href="{{asset('css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

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
				<a href="/">
					<img alt="Logo" src="{{ asset('img/system/logo-6.png') }}" width="160"/>
				</a>
				<a href="javascript:;" class="header-title-span">
					<span>{{ $webinar->title ?? 'Undefined' }}</span>
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="btn btn-outline-warning btn-sm btn-icon" data-toggle="modal" data-target="#selection_date_modal"><i class="fa fa-calendar-day kt-font-warning"></i></button> &nbsp;
				<button class="btn btn-outline-success btn-sm btn-icon" onclick="fetchReports()"><i class="fa fa-undo kt-font-success"></i></button>
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
											<span class="kt-menu__link-text">{{ $webinar->title ?? 'Undefined' }}</span>
										</a>
									</li>
									<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="javascript:;" class="kt-menu__link" data-toggle="modal" data-target="#selection_date_modal">
											<span class="kt-menu__link-text"><i class="fa fa-calendar-day kt-font-warning"></i> &nbsp; Select Date</span>
										</a>
									</li>
								</ul>
							</div> 
						</div>
						<!-- emd:: Header Menu -->
						
						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">
		
							<div class="kt-header__topbar-item dropdown">
								<div class="kt-header__topbar-item kt-header__topbar-item--user">
									<div class="kt-header__topbar-wrapper">
										<div class="kt-header__topbar-user" onclick="fetchReports()">
											<i class="fa fa-undo kt-font-success"></i> &nbsp; Refresh
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
		<script src="{{asset('plugins/general/jquery/dist/jquery.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/moment/min/moment.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/moment/locale/fr.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/autosize/dist/autosize.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-timepicker.init.js')}}" type="text/javascript"></script>
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
	</body>
</html>