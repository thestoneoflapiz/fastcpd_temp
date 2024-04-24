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
		<meta name="google-signin-client_id" content="141741110601-c1gshj30hv0ignh0bhoq77laimfvd84m.apps.googleusercontent.com">
		<meta charset="utf-8" />
		<title>@yield("title")</title>
        @yield('metas')
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700&display=swap">
		
		<!--end::Fonts -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<!--begin:: Vendor Plugins -->
		<link media="all" href="{{asset('plugins/general/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/animate.css/animate.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('plugins/general/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" rel="preload" onload="this.rel='stylesheet'" as="style" href="{{asset('plugins/general/socicon/css/socicon.css')}}" type="text/css" />
		<link media="all" rel="preload" onload="this.rel='stylesheet'" as="style" href="{{asset('plugins/general/plugins/line-awesome/css/line-awesome.min.css')}}" type="text/css" />
		<link media="all" rel="preload" onload="this.rel='stylesheet'" as="style" href="{{asset('plugins/general/plugins/flaticon/flaticon.css')}}" type="text/css" />
		<link media="all" rel="preload" onload="this.rel='stylesheet'" as="style" href="{{asset('plugins/general/plugins/flaticon2/flaticon.css')}}" type="text/css" />
		<link media="all" rel="preload" onload="this.rel='stylesheet'" as="style" href="{{asset('plugins/general/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link media="all" href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />

		<!--begin:: Vendor Plugins for custom pages -->
		<link media="all" href="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('css/star-rating-svg.css')}}" rel="stylesheet" type="text/css" />
		<!--end:: Vendor Plugins for custom pages -->
		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) --> 
		<link media="all" href="{{asset('css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('css/skins/header/menu/light.min.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link media="all" href="{{asset('css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />

		<style>
			html {scroll-behavior: smooth;}
			.toast-title{color:white !important; font-weight:500;}
			.kt-aside-menu, .kt-aside-menu-wrapper{background-color:#2f2f39 !important;}
			.kt-aside__brand {background-color:#272733 !important;}
			.kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__heading, .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link{background-color:#2A7DE9 !important;}
			.kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__heading .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__heading .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__link .kt-menu__link-icon svg g [fill]{fill:#fff !important;}
			.select2-container--default .select2-results__option[aria-selected=true]{background-color:#C5CCDA; color:white; font-weight: 600;}
			svg {position:relative;}
			/* .kt-badge {position:absolute;top:15;right:2;padding:5px;font-size:11px;} */
			.kt-footer{background-color:#0C273D !important;color:white !important;}
			.socicons-footer{color:white !important;font-size:1.3rem !important;}
			.facebook-circle-footer{padding:0.8rem !important;border-radius:100%;background-color:#466BA2;}
			.twitter-circle-footer{padding:0.8rem !important;border-radius:100%;background-color:#64ABCA;}
			.instagram-circle-footer{padding:0.8rem !important;border-radius:100%;background-color:#23282C;}
			.linkedin-circle-footer{padding:0.8rem !important;border-radius:100%;background-color:#3D87FB;}
			.public-footer{padding:2.5rem 15rem 0 8.5rem;}
			@media (max-width: 700px) {
				.public-footer{padding:2rem 0 0 0;}
				.public-footer .kt-container .kt-footer__menu .row .col-12{text-align:center;}
			}
			#login_google > div,#signup_google > div{width:100% !important;border-radius:0.25rem !important;}
			.public-footer{padding:2.5rem 15rem 0 8.5rem;}
			@media (max-width: 700px) {
				.public-footer{padding:2rem 0 0 0;}
			}
		</style>

		<!-- begin::Styles from other pages -->
		@yield('styles')
		<!-- end::Styles from other pages -->
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
		<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/a736832b1ec98eb799f0ba6eb/8ce32fe338315c4dfe37b4113.js");</script>
		@endif
	</head>
	<!-- end::Head -->

	<!-- <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading kt-aside--minimize"> -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--solid kt-page--loading">
		@if(App::environment('production'))
		<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f72e3ce5ddae23e"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZVCFKJ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		@endif
		<script>
        window.fbAsyncInit = function() {
          FB.init({
            xfbml            : true,
            version          : 'v8.0'
          });
        };

        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

		<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v8.0&appId=457992691824447&autoLogAppEvents=1" nonce="dwYwn6kA"></script>
		<!-- Your Chat Plugin code -->
		<div class="fb-customerchat" attribution=setup_tool page_id="112355463782773" theme_color="#2a7de9"></div>
		<!-- Load Facebook SDK for JavaScript END-->
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="/"><img alt="Logo" src="{{asset('img/system/logo-6.png')}}" width="160"/></a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
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
                                <a href="/">
                                    <img alt="Logo" src="{{asset('img/system/logo-1.png')}}" width="160" />
                                </a>
                            </div>
						</div>
					</div>

                    <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                        @yield('content')
                    </div>

                    @include("template.relative.publicfooter")
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
		<script src="{{asset('plugins/general/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/js/global/integration/plugins/bootstrap-notify.init.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/jquery-validation/dist/jquery.validate.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/jquery-validation/dist/additional-methods.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/js/global/integration/plugins/jquery-validation.init.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/inputmask/dist/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/inputmask/dist/inputmask/inputmask.date.extensions.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('plugins/general/inputmask/dist/inputmask/inputmask.numeric.extensions.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
		<!--end:: Vendor Plugins --> 
		<script defer src="{{asset('js/scripts.bundle.min.js')}}" type="text/javascript"></script>
		<!--begin:: Vendor Plugins for custom pages -->
		<script defer src="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
		<script defer src="{{asset('js/jquery.star-rating-svg.min.js')}}" type="text/javascript"></script>
		<script src="https://apis.google.com/js/platform.js?onload=GOOGLEinit" async defer></script>
		<!--end:: Vendor Plugins for custom pages -->
		<!--end::Global Theme Bundle -->
		<!--begin::Page Scripts(used by this page) -->
		<script src="{{asset('js/common.js')}}" type="text/javascript"></script>
		<script src="{{asset('js/authentication/common.js')}}" type="text/javascript"></script>
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
		</script>
	</body>
</html>