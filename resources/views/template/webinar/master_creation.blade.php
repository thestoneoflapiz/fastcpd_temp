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
		<link href="{{asset('plugins/custom/uppy/dist/uppy.min.css')}}" rel="stylesheet" type="text/css" />

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
			.select2-container--default .select2-results__option[aria-selected=true]{background-color:#C5CCDA; color:white; font-weight: 600;}
            .kt-header{background-color:#307DE9 !important;}
            .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item > .kt-menu__link .kt-menu__link-text{color:white;}
            .kt-header-menu .kt-menu__nav > .kt-menu__item:hover:not(.kt-menu__item--here):not(.kt-menu__item--active) > .kt-menu__link .kt-menu__link-text, .kt-header-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--hover:not(.kt-menu__item--here):not(.kt-menu__item--active) > .kt-menu__link .kt-menu__link-text{color:white;}
            .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--here > .kt-menu__link .kt-menu__link-text, .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link .kt-menu__link-text, .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item:hover > .kt-menu__link .kt-menu__link-text{color:white;}
            .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--here > .kt-menu__link, .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--active > .kt-menu__link, .kt-header .kt-header-menu .kt-menu__nav > .kt-menu__item:hover > .kt-menu__link{background-color:rgba(77, 89, 149, 0.42);}

            .whiten{color:white !important;}
            
            /* 
             * Side Menu
             *
             */
            #submit_review{width:100%;font-size:18px;font-weight:700;padding:20px;}
            span.form-control{color:#8a9198;border:none;padding:inherit;height:auto;}
            .head {font-weight: bold;color: #343a40;}
            .body {font-weight: normal;color: #343a40;}
            .first-div {margin-top: 20px;}
            .next-div {margin-top: 30px;}
            .fa-angle-right {font-size: 19px;}
            .fa-check-circle {color: #2A7DE9;}
            .small-padding-right {padding-right: 5px;}
            .required {color: red;}
            .circle-icon {color: #2A7DE9;}
            .button {border: none;padding: 22px 52px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;margin: 4px 2px;transition-duration: 0.4s;cursor: pointer;border-radius: 3px;}
            .article_padding {padding: 25px;}
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
				<a href="/provider/webinars" class="whiten"><i class="fa fa-arrow-left"></i> &nbsp; Back to Webinar</a> &nbsp; &nbsp;
                <a href="javascript:;" class="whiten">| &nbsp; <text  style="font-weight:600;">{{ _current_webinar()->title ?? "New Webinar" }}</text></a> &nbsp; &nbsp;
                <a href="javascript:;" class="whiten">| &nbsp; {{ ucwords(_current_webinar()->prc_status) ?? "Draft" }}</a> &nbsp; &nbsp;
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
					<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed">
						<!-- begin:: Header Menu -->
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default">
								<ul class="kt-menu__nav ">
                                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="/provider/webinars" class="kt-menu__link whiten">
											<span class="kt-menu__link-text"><i class="fa fa-arrow-left"></i> &nbsp; Back to Webinar</span>
										</a>
									</li>
                                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="javascript:;" class="kt-menu__link whiten">
											<span class="kt-menu__link-text" style="font-weight:700;font-size:16px;">{{ _current_webinar()->title ?? "New Webinar" }}</span>
										</a>
									</li>
                                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
										<a href="javascript:;" class="kt-menu__link whiten">
											<span class="kt-menu__link-text">{{ ucwords(_current_webinar()->prc_status) ?? "Draft" }}</span>
										</a>
									</li>
								</ul>
							</div> 
						</div>
						<!-- emd:: Header Menu -->
					</div>

					<!-- end:: Header -->
					<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                            <div class="row">
                                <!-- SideMenu:Start -->
                                <div class="col-lg-2 col-md-2 col-sm-3">
                                    <div class="first-div">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h5 class="kt-portlet__head-title">
                                                    <span class="head">About your webinar</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="kt-notification-v2">
                                                <a href="<?=_my_webinar_permission('webinar_details') ? '/webinar/management/details' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item ">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'details' ? '<i class="fa fa-angle-right" style="font-size:19px;"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("webinar_details") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Webinar Details
                                                        </div>

                                                    </div>
                                                </a>
                                                <a href="<?=_my_webinar_permission('attract_enrollments') && _webinar_creation_restricted('attract_enrollments') ? '/webinar/management/attract' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'attract' ? '<i class="fa fa-angle-right"></i>' : '<i class="la la-circle circle-icon" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("attract_enrollments") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Attract Enrollments
                                                        </div>

                                                    </div>
                                                </a>
                                                <a href="<?=_my_webinar_permission('instructors') && _webinar_creation_restricted('instructors') ? '/webinar/management/instructors' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'instructors' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("instructors") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Instructors
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="next-div">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h5 class="kt-portlet__head-title">
                                                    <span class="head">Create your content</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="kt-notification-v2">
                                                <a href="<?=_my_webinar_permission('video_content') && _webinar_creation_restricted('video_content') ? '/webinar/management/content' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'content' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("video_content") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Webinar & Content
                                                        </div>

                                                    </div>
                                                </a>
                                                <a href="<?=_my_webinar_permission('handouts') && _webinar_creation_restricted('handouts') ? '/webinar/management/handouts' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'handouts' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("handouts") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Handouts
                                                        </div>

                                                    </div>
                                                </a>
                                                <a href="<?=_my_webinar_permission('grading') && _webinar_creation_restricted('grading') ? '/webinar/management/grading' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'grading' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("grading")  ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Grading & Assessment
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="next-div">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h5 class="kt-portlet__head-title">
                                                    <span class="head">Publishing your webinar</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="kt-notification-v2">
                                                <a href="<?=_my_webinar_permission('links') && _webinar_creation_restricted('links') ? '/webinar/management/links' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'links' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("links") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Webinar Links
                                                        </div>

                                                    </div>
                                                </a>
												@if(_current_webinar()->offering_units!="without" && _current_provider()->status=="approved")
                                                <a href="<?=_my_webinar_permission('accreditation') && _webinar_creation_restricted('accreditation') ? '/webinar/management/accreditation' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'accreditation' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("submit_accreditation") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            PRC Accreditation
                                                        </div>

                                                    </div>
                                                </a>
												@endif
                                                <a href="<?=_my_webinar_permission('publish') && _webinar_creation_restricted('publish') ? '/webinar/management/publish' : 'javascript:toastr.warning(\'You have no rights to access this module\');'?>" class="kt-notification-v2__item">
                                                    <div class="">
                                                        <?= Request::segment(3) == 'publish' ? '<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-right" style="color:white;"></i>' ?>
                                                    </div>
                                                    <div class="kt-notification-v2__itek-wrapper">

                                                        <div class="kt-notification-v2__item-title body">
                                                            <span class="small-padding-right"><?= _webinar_creation_check("publish") ? '<i class="fa fa-check-circle circle-icon" style="font-size:15px;"></i>' : '<i class="la la-circle circle-icon" style="font-size:18px;"></i>' ?></span>
                                                            Publish Webinar
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="next-div">
                                        <button id="submit_review" class="btn btn-danger">
                                            Submit for<br/>Review
                                        </button>
                                    </div>
                                </div>
                                <!-- SideMenu:End -->
                                
                                <div class="col-lg-10 col-md-10 col-sm-9">
									<div class="alert alert-dark <?=_current_provider()->status=="approved" ? "kt-hidden" : ""?>" role="alert">
										<div class="alert-text">
											<h4 class="alert-heading">The more you know!</h4>
											<p style="margin-bottom:0;">Publishing a <b>webinar</b> while <b>provider is unaffiliated</b> will be shown as <b>promotional content</b> on our site</p>
										</div>
									</div>
                                    @yield('content')
                                </div>
                            </div>		
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end:: Page -->

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>
		<!-- end::Scrolltop -->

		<!-- begin:modal for: submit review -->
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="submit-review-modal" tabindex="-1" role="dialog" aria-labelledby="submit-review-modal-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="submit-review-modal-label">List of Incomplete Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div>
					<div class="modal-body">
						<p style="text-align:center;">You're trying to <b>submit your webinar for review</b> but you still have incomplete details. Please complete your details first: <i>See the list below</i></p>
						<ul class="error-list">
						</u>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- end:modal for: submit review  -->

		<!-- begin:modal for: submit review -->
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="check-live-modal" tabindex="-1" role="dialog" aria-labelledby="check-live-modal-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="check-live-modal-label">List of Incomplete Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div>
					<div class="modal-body">
						<p style="text-align:center;">You're trying to <b>preview webinar </b>but you still have incomplete details. Please complete your details first: <i>See the list below</i></p>
						<ul class="webinar-creation-list">
						</u>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div>
		<!-- end:modal for: submit review  -->

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
			$("#submit_review").on("click",function(){

				toastr.info("Please wait, we're submitting your webinar...");
				$.ajax({
					url: '/webinar/management/review',
					method: 'get',
					success: function(response) {
						if (response.status == 200) {
							toastr.success('Success!',  response.message);
							setTimeout(() => {
								window.location="/provider/webinars";
							}, 1500);
						}else{
							var for_li_append = ``;
							var errors = response.message;
							errors.forEach(text => {
								for_li_append += `<li>${text}</li>`;
							});
							
							$(`ul.error-list`).empty().append(for_li_append);
							$(`#submit-review-modal`).modal(`show`);
							toastr.warning('Webinar Incomplete!');
						}
					}
				});
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
		</script>
	</body>
</html>