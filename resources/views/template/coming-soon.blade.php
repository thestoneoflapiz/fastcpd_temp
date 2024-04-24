<html lang="en">
<?php
// 301 Moved Permanently
if(App::environment('production') && $_SERVER['HTTP_HOST'] == "fastcpd.com"){
	header("Location: https://www.fastcpd.com", true, 301);
	exit();
}
?> 
	<!-- begin::Head -->
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-177782529-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-177782529-1');
		</script>
		<base href="../../../">
		<meta charset="utf-8" />
		<title>Fast CPD</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<!--begin::Page Custom Styles(used by this page) -->
		<link href="{{asset('/css/pages/error/error-3.css')}}" rel="stylesheet" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{asset('/css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/css/skins/header/menu/light.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />

        <style>
            .kt-error-v3 .kt-error_container .kt-error_number > h1 {font-size:7rem;margin-left:4rem;margin-top:7rem;font-weight:500;-webkit-text-stroke-width:0.3rem;-moz-text-stroke-width:0.3rem;text-stroke-width:0.3rem;color:#A3DCF0;-webkit-text-stroke-color:#fff;text-stroke-color:#fff;}
        </style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v3" style="background-image: url({{asset('media/error/bg3.jpg')}});">
				<div class="kt-error_container">
					<span class="kt-error_number">
						<h1>Coming Soon!</h1>
					</span>
					<p class="kt-error_title kt-font-light">
                        Get CPD units at home and on your own schedule<br/>
						For now, visit our <a href="https://www.facebook.com/fastcpd">Facebook page</a>
					</p>
				</div>
			</div>
		</div>
	</body>