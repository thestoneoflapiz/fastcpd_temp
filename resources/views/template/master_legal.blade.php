<?php
// 301 Moved Permanently
if(App::environment('production') && $_SERVER['HTTP_HOST'] == "fastcpd.com"){
	header("Location: https://www.fastcpd.com" . $_SERVER['REQUEST_URI'], true, 301);
	exit();
}
?> 
<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

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
    <base href="../../../">
    <meta charset="utf-8" />
    <title>Legal</title>
    <meta name="description" content="Support center license example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{asset('css/pages/support-center/license.css')}}" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->

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
    <link href="{{asset('plugins/custom/uppy/dist/uppy.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/custom/jkanban/dist/jkanban.min.css')}}" rel="stylesheet" type="text/css" />

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
        .kt-nav.kt-nav--v4--success .kt-nav__item.active .kt-nav__link{
            border-left: 3px solid #5867dd;
        }
        .kt-nav.kt-nav--v4--success .kt-nav__item:hover:not(.kt-nav__item--disabled):not(.kt-nav__item--sub):not(.kt-nav__item--active) > .kt-nav__link .kt-nav__link-text{
            color: #5d78ff;
        }
	</style>
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
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header-mobile--fixed kt-page--loading">
    @if(App::environment('production'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZVCFKJ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    <div class="kt-content  kt-content--fit-top  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <!-- begin:: hero -->
        <div class="kt-sc-license" style="background-image: url('{{asset('media/bg/bg-9.jpg')}}')">
            <div class="kt-container ">
                <div class="kt-sc__top">
                    <h3 class="kt-sc__title">
                        License Information
                    </h3>
                </div>
            </div>
        </div>

        <!-- end:: hero -->
        <div class="kt-negative-spacing--7"></div>

        <!-- begin:: infobox -->
        <div class="kt-grid__item">
            <div class="kt-container ">
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-infobox">
                            <div class="kt-infobox__body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <ul class="kt-nav kt-nav--bold kt-nav--fit kt-nav--v4 kt-nav--v4--success" role="tablist">
                                            <?= Request::segment(2) == 'terms-of-service' ? '<li class="kt-nav__item active">' : '<li class="kt-nav__item">' ?>
                                                <a class="kt-nav__link" href="/legal/terms-of-service" role="tab">
                                                    <span class="kt-nav__link-text">Terms of Service</span>
                                                </a>
                                            </li>
                                            <?= Request::segment(2) == 'privacy-policy' ? '<li class="kt-nav__item active">' : '<li class="kt-nav__item">' ?>
                                                <a class="kt-nav__link" href="legal/privacy-policy" role="tab">
                                                    <span class="kt-nav__link-text">Privacy Policy</span>
                                                </a>
                                            </li>
                                            <?= Request::segment(2) == 'instructor-terms' ? '<li class="kt-nav__item active">' : '<li class="kt-nav__item">' ?>
                                                <a class="kt-nav__link" href="/legal/instructor-terms" role="tab">
                                                    <span class="kt-nav__link-text">Instructor Terms</span>
                                                </a>
                                            </li>
                                            <?= Request::segment(2) == 'refund-policy' ? '<li class="kt-nav__item active">' : '<li class="kt-nav__item">' ?>
                                                <a class="kt-nav__link" href="/legal/refund-policy" role="tab">
                                                    <span class="kt-nav__link-text">Refund Policy</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="tab-content">
                                            @yield('content')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end:: infobox -->
        <!-- begin:: iconbox -->
        <div class="kt-grid__item">
            <div class="kt-container ">
                <div class="row">
                    <div class="col-lg-4">
                        <a href="#" class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate-slow">
                            <div class="kt-portlet__body">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                                            </g>
                                        </svg> </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            Getting Started
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Windows 10 automatically downloads and installs updates.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a href="#" class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate-slower">
                            <div class="kt-portlet__body">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3" />
                                                <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000" />
                                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000" />
                                            </g>
                                        </svg> </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            Tutorials
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Windows 10 automatically downloads and installs updates.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a href="#" class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate">
                            <div class="kt-portlet__body">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg> </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            User Guide
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Windows 10 automatically downloads and installs updates.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- end:: iconbox -->

    <!-- end:: Content -->
    </div>

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
    <script src="{{asset('plugins/general/dompurify/dist/purify.js')}}" type="text/javascript"></script>

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
    <script src="{{asset('plugins/custom/uppy/dist/uppy.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/custom/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/custom/tinymce/themes/silver/theme.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/custom/tinymce/themes/mobile/theme.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/custom/jkanban/dist/jkanban.min.js')}}" type="text/javascript"></script>

    <!--end:: Vendor Plugins for custom pages -->

    <!--end::Global Theme Bundle -->
</body>
    
