<?php $segment1 = count(Request::segments()) == 1 ? '' : Request::segments()[1];?>
<?php
// 301 Moved Permanently
if(App::environment('production') && $_SERVER['HTTP_HOST'] == "fastcpd.com"){
	header("Location: https://www.fastcpd.com" . $_SERVER['REQUEST_URI'], true, 301);
	exit();
}
?> 
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
		<link href="{{asset('css/skins/header/base/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/header/menu/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />

		<style>
			.toast-title{color:white !important; font-weight:500;}
			.kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__heading, .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link{background-color:#2A7DE9 !important;}
			.kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__heading .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__heading .kt-menu__link-icon svg g [fill], .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__link .kt-menu__link-icon svg g [fill]{fill:#fff !important;}
            .kt-aside-menu .kt-menu__nav > .kt-menu__item .kt-menu__submenu .kt-menu__item.kt-menu__item--active > .kt-menu__heading, .kt-aside-menu .kt-menu__nav > .kt-menu__item .kt-menu__submenu .kt-menu__item.kt-menu__item--active > .kt-menu__link{background-color:#2A7DE9 !important;}
            .static-label{color:#A2A3B7;padding:10px;font-weight:600;font-size:16px;border-bottom:1px solid #484856;}
			.select2-container--default .select2-results__option[aria-selected=true]{background-color:#C5CCDA; color:white; font-weight: 600}
			.public-footer{padding:2.5rem 15rem 0 8.5rem;}
			@media (max-width: 700px) {
				.public-footer{padding:2rem 0 0 0;}
				.public-footer .kt-container .kt-footer__menu .row .col-12{text-align:center;}
			}
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
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
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
				<a href="/superadmin">
					<img alt="Logo" src="{{asset('img/system/logo-6.png')}}" width="160"/>
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
					<!-- begin:: Aside -->
					<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
						<div class="kt-aside__brand-logo">
							<a href="/superadmin">
								<img alt="Logo" src="{{asset('img/system/logo-6.png')}}" width="160"/>
							</a>
						</div>
						<div class="kt-aside__brand-tools">
							<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
								<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
											<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
										</g>
									</svg></span>
								<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<polygon points="0 0 24 0 24 24 0 24" />
											<path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
											<path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
										</g>
									</svg></span>
							</button>
						</div>
					</div>
					<!-- end:: Aside -->
					<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
						<div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
							<ul class="kt-menu__nav ">
								<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && count(Request::segments())==1 ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
									<a href="/superadmin" class="kt-menu__link ">
										<span class="kt-menu__link-icon">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"/>
													<path d="M5.5,4 L9.5,4 C10.3284271,4 11,4.67157288 11,5.5 L11,6.5 C11,7.32842712 10.3284271,8 9.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,5.5 C4,4.67157288 4.67157288,4 5.5,4 Z M14.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,17.5 C13,16.6715729 13.6715729,16 14.5,16 Z" fill="#000000"/>
													<path d="M5.5,10 L9.5,10 C10.3284271,10 11,10.6715729 11,11.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,12.5 C20,13.3284271 19.3284271,14 18.5,14 L14.5,14 C13.6715729,14 13,13.3284271 13,12.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z" fill="#000000" opacity="0.3"/>
												</g>
											</svg>
										</span>
										<span class="kt-menu__link-text">Dashboard</span>
									</a>
                                </li>
								@if(_can_view_superadmin("announcement"))
                                <li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'announcements' ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
									<!-- <a href="#" class="kt-menu__link "> -->
									<a href="/superadmin/announcements" class="kt-menu__link ">
										<span class="kt-menu__link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M14.486222,18 L12.7974954,21.0565532 C12.530414,21.5399639 11.9220198,21.7153335 11.4386091,21.4482521 C11.2977127,21.3704077 11.1776907,21.2597005 11.0887419,21.1255379 L9.01653358,18 L5,18 C3.34314575,18 2,16.6568542 2,15 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,16.6568542 20.6568542,18 19,18 L14.486222,18 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M6,7 L15,7 C15.5522847,7 16,7.44771525 16,8 C16,8.55228475 15.5522847,9 15,9 L6,9 C5.44771525,9 5,8.55228475 5,8 C5,7.44771525 5.44771525,7 6,7 Z M6,11 L11,11 C11.5522847,11 12,11.4477153 12,12 C12,12.5522847 11.5522847,13 11,13 L6,13 C5.44771525,13 5,12.5522847 5,12 C5,11.4477153 5.44771525,11 6,11 Z" fill="#000000" opacity="0.3"/>
                                                </g>
                                            </svg>
										</span>
										<span class="kt-menu__link-text">Announcements</span>
									</a>
                                </li>
								@endif
								@if(_can_view_superadmin("verification"))
                                <li class="kt-menu__item  kt-menu__item--submenu {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' ? 'kt-menu__item--open' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"/>
                                                    <polygon fill="#000000" opacity="0.3" points="11.3333333 18 16 11.4 13.6666667 11.4 13.6666667 7 9 13.6 11.3333333 13.6"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span class="kt-menu__link-text">Verifications</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Verifications</span></span></li>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'courses' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/courses" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Courses</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'webinars' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/webinars" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Webinars</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'instructors' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/instructors" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Instructors</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'providers' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/providers" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Providers</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'vouchers' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/vouchers" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Vouchers</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'dragonpay' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/dragonpay" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">DragonPay</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'verification' && Request::segments()[2] == 'payout' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/verification/payout" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Payables</span> </a>
                                        </ul>
                                    </div>
                                </li>
								@endif
								@if(_can_view_superadmin("purchase_setting"))
                                <li class="kt-menu__item  kt-menu__item--submenu  {{ Request::segments()[0] == 'superadmin' && $segment1 == 'settings' ? 'kt-menu__item--open' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z" fill="#000000"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span class="kt-menu__link-text">Purchase Settings</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Purchase Settings</span></span></li>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'settings' && Request::segments()[2] == 'vouchers' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/settings/vouchers" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Site-Wide Vouchers</span> </a>
											<li class="kt-menu__item"><a href="javascript:;" class="kt-menu__link" data-toggle="modal" data-target="#payment-method-modal"> <span></span></i><span class="kt-menu__link-text">Payment Methods</span> </a>
                                        </ul>
                                    </div>
                                </li>
								@endif
								@if(_can_view_superadmin("report"))
                                <li class="kt-menu__item  kt-menu__item--submenu {{ Request::segments()[0] == 'superadmin' && $segment1 == 'report' ? 'kt-menu__item--open' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                                    <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                                    <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                                                    <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span class="kt-menu__link-text">Reports</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Reports</span></span></li>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'report' && Request::segments()[2] == 'courses' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/report/courses" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Courses</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'report' && Request::segments()[2] == 'purchases' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/report/purchases" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Purchases</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'report' && Request::segments()[2] == 'items' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/report/items" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Purchase Items</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'report' && Request::segments()[2] == 'payouts' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/report/payouts" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Payables</span> </a>
                                        </ul>
                                    </div>
                                </li>
								@endif
								@if(_can_view_superadmin("user"))
                                <li class="kt-menu__item  kt-menu__item--submenu {{ Request::segments()[0] == 'superadmin' && ( $segment1 == 'providers' || $segment1 == 'users' || $segment1 == 'professionals' )? 'kt-menu__item--open' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                    <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span class="kt-menu__link-text">Users</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Users</span></span></li>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'providers' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/providers" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Providers</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'professionals' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/professionals" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Professionals</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'promoters' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/promoters" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Promoters</span> </a>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'users' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/users" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Superadmin List</span> </a>
                                        </ul>
                                    </div>
                                </li>
								@endif
								@if(_can_view_superadmin("settings"))
								<li class="kt-menu__item  kt-menu__item--submenu {{ Request::segments()[0] == 'superadmin' && ( $segment1 == 'top_professions' )? 'kt-menu__item--open' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-icon">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"/>
													<path d="M11,20 L11,17 C11,16.4477153 11.4477153,16 12,16 C12.5522847,16 13,16.4477153 13,17 L13,20 L15.5,20 C15.7761424,20 16,20.2238576 16,20.5 C16,20.7761424 15.7761424,21 15.5,21 L8.5,21 C8.22385763,21 8,20.7761424 8,20.5 C8,20.2238576 8.22385763,20 8.5,20 L11,20 Z" fill="#000000" opacity="0.3"/>
													<path d="M3,5 L21,5 C21.5522847,5 22,5.44771525 22,6 L22,16 C22,16.5522847 21.5522847,17 21,17 L3,17 C2.44771525,17 2,16.5522847 2,16 L2,6 C2,5.44771525 2.44771525,5 3,5 Z M4.5,8 C4.22385763,8 4,8.22385763 4,8.5 C4,8.77614237 4.22385763,9 4.5,9 L13.5,9 C13.7761424,9 14,8.77614237 14,8.5 C14,8.22385763 13.7761424,8 13.5,8 L4.5,8 Z M4.5,10 C4.22385763,10 4,10.2238576 4,10.5 C4,10.7761424 4.22385763,11 4.5,11 L7.5,11 C7.77614237,11 8,10.7761424 8,10.5 C8,10.2238576 7.77614237,10 7.5,10 L4.5,10 Z" fill="#000000"/>
												</g>
											</svg>
                                        </span>
                                        <span class="kt-menu__link-text">Site Settings</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Site Settings</span></span></li>
											<li class="kt-menu__item {{ Request::segments()[0] == 'superadmin' && $segment1 == 'top_professions' ? 'kt-menu__item--active' : ''}}"><a href="/superadmin/top_professions" class="kt-menu__link kt-menu__toggle"> <span></span></i><span class="kt-menu__link-text">Top Professions</span> </a>
                                        </ul>
                                    </div>
                                </li>
								@endif
							</ul>
						</div>
					</div>
					<!-- end:: Aside Menu -->
				</div>
				<!-- end:: Aside -->

				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

						<!-- begin:: Header Menu -->
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
								<ul class="kt-menu__nav ">
									<li class="kt-menu__item  kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><b style="font-size:20px;">@yield('title')</b></li>
								</ul>
							</div>
						</div>
						<!-- end:: Header Menu -->
						
						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">
							@include("template.relative.notification")

							<!--begin: User Bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
									<div class="kt-header__topbar-user">
										<span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
										<span class="kt-header__topbar-username kt-hidden-mobile"><?=ucwords(Auth::user()->name);?></span>
										<img class="kt-hidden" alt="Pic" src="{{asset('media/users/300_25.jpg')}}" />
										<span class="kt-badge kt-badge--username kt-badge--unified-info kt-badge--lg kt-badge--rounded kt-badge--bold"><?=ucwords(Auth::user()->name[0]);?></span>
									</div>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
									<!--begin: Navigation -->
									<div class="kt-notification">
										<a href="/superadmin/account" class="kt-notification__item">
											<div class="kt-notification__item-icon">
												<i class="flaticon2-calendar-3 kt-font-info"></i>
											</div>
											<div class="kt-notification__item-details">
												<div class="kt-notification__item-title kt-font-bold">
													Account Profile & Settings
												</div>
												<div class="kt-notification__item-time">
													Manage your account and settings
												</div>
											</div>
										</a>
										<div class="kt-notification__custom kt-space-between">
											<a href="/signout" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
										</div>
									</div>
									<!--end: Navigation -->
								</div>
							</div>
							<!--end: User Bar -->
						</div>
						<!-- end:: Header Topbar -->
					</div>

					<!-- end:: Header -->
                    
                    @yield('content')
				</div>
			</div>
		</div>

		<!-- payment-method-modal -->
		<!-- begin:payment-method-modal -->
		<div class="modal fade" id="payment-method-modal" tabindex="-1" role="dialog" aria-labelledby="payment-method-modal-label" aria-hidden="true">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header" style="border-bottom:none;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" id="resend_inv_body" style="text-align:center;">
						<h3><small>Hide & Show payment methods in payment gateway</small></h3>
						<div class="kt-space-20"></div>
						<div class="kt-space-30"></div>
							<div class="kt-checkbox-list">
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
									<input type="checkbox"> Credit Card
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
									<input type="checkbox"> E-Wallet
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
									<input type="checkbox"> Online Banking
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
									<input type="checkbox"> Bank Deposit OTC
									<span></span>
								</label>
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
									<input type="checkbox"> Payment Centers
									<span></span>
								</label>
							</div>
						<div class="kt-space-30"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Submit</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<!-- end:payment-method-modal -->


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
			getNotifications();
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