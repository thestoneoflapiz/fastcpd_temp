<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="../../../">
		<meta charset="utf-8" />
		<title>Fast CPD</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<!--begin::Page Custom Styles(used by this page) -->
		<link href="{{asset('/css/pages/error/error-5.css')}}" rel="stylesheet" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{asset('/css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/css/skins/header/menu/light.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->

	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v5" style="background-image: url({{asset('media/error/bg5.jpg')}});">
				<div class="kt-error_container">
					<p class="kt-error_subtitle">
						Your request for Provider account has been blocked
					</p>
					<p class="kt-error_description">
						It's either your content is dangerous </br></br>
                        <button type="button" class="btn btn-twitter  btn-lg" onclick="window.history.back();">Go Back</button>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>