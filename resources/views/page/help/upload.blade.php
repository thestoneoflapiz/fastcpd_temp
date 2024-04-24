@extends('template.master')
@section('title', 'Help & System Settings')

@section('styles')
<link href="{{asset('plugins/custom/uppy/dist/uppy.min.css')}}" rel="stylesheet" type="text/css" />
<style>
	.hidden{display:none;}
</style>
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col">
            <div class="alert alert-light alert-elevate fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                <div class="alert-text">
					Company logos requirements are: <br/>
					1. Image files of JPEG, JPG, & PNG. <br/>
					2. Minimum and Maximum of 1 logo per upload.<br/>
                </div>
                <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="btn btn-secondary btn-icon" onclick="window.location.href='/help'" data-toggle="kt-tooltip" data-placement="top" title="Go Back to Help"><i class="fa fa-arrow-left"></i></button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
							Upload Logo #1 (1:1 aspect ratio)
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-uppy" id="kt_uppy_1">
                        <div class="kt-uppy__dashboard"></div>
                        <div class="kt-uppy__progress"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
							Upload Logo #2 (16:9 aspect ratio)
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-uppy" id="kt_uppy_2">
                        <div class="kt-uppy__dashboard"></div>
                        <div class="kt-uppy__progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('plugins/custom/uppy/dist/uppy.min.js')}}" type="text/javascript"></script>
<script>
"use strict";

// Class definition
var KTUppy = function () {
	// const Tus = Uppy.Tus;
	const XHRUpload = Uppy.XHRUpload;
	const ProgressBar = Uppy.ProgressBar;
	const StatusBar = Uppy.StatusBar;
	const FileInput = Uppy.FileInput;
	const Informer = Uppy.Informer;

	// to get uppy companions working, please refer to the official documentation here: https://uppy.io/docs/companion/
	const Transloadit = Uppy.Transloadit;
	const Dashboard = Uppy.Dashboard;
	// const Dropbox = Uppy.Dropbox;
	const GoogleDrive = Uppy.GoogleDrive;
	const Instagram = Uppy.Instagram;
	// const Webcam = Uppy.Webcam;

	// Private functions
	var initUppy1 = function(){
		var id = '#kt_uppy_1';

		var options = {
			proudlyDisplayPoweredByUppy: false,
			target: id,
			inline: true,
			replaceTargetContent: true,
			showProgressDetails: true,
			note: 'Accepting Image files ONLY [jpeg, jpg, png].',
			height: 470,
			metaFields: [
				{ id: 'logo1', name: 'Logo1', resize:'500' },
			],
			browserBackButtonClose: true
		}

		var uppyDashboard = Uppy.Core({ 
			autoProceed: true,
			restrictions: {
				maxFileSize: 1000000000000, //1000000 =  1mb
				maxNumberOfFiles: 1,
				minNumberOfFiles: 1,
				allowedFileTypes: ['.jpg', '.jpeg', '.png', '.PNG', '.JPG', '.JPEG']
			} 
		});

		uppyDashboard.use(Dashboard, options);  
		uppyDashboard.use(XHRUpload, { endpoint: '/help/action/upload?pc=logo1' });
		uppyDashboard.use(GoogleDrive, { auth: {key: '3a76b863b8d5403ba13699a6c9edddbc'}, target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		// uppyDashboard.use(Dropbox, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		uppyDashboard.use(Instagram, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		// uppyDashboard.use(Webcam, { target: Dashboard });
        // uppyDashboard.use(Transloadit, {
        //     params: {
        //         auth:{
        //             key: '3a76b863b8d5403ba13699a6c9edddbc',
        //         },
        //         "steps": {
        //             ":original": {
        //                 "robot": "/upload/handle"
        //             },
        //             "filter": {
        //                 "use": ":original",
        //                 "robot": "/file/filter",
        //                 "accepts": [
        //                     [
        //                     "${file.mime}",
        //                     "regex",
        //                     "image"
        //                     ]
        //                 ],
        //                 "error_on_decline": true
        //             },
        //             "convert_image_png": {
        //                 "use": "filter",
        //                 "robot": "/image/resize",
        //                 "format": "png",
        //                 "imagemagick_stack": "v2.0.7"
        //             },
        //             "resize_image": {
        //                 "use": "convert_image_png",
        //                 "robot": "/image/resize",
        //                 "resize_strategy": "fit",
        //                 "width": 100,
        //                 "height": 100,
        //                 "imagemagick_stack": "v2.0.7"
        //             },
        //             "compress_image": {
        //                 "use": "resize_image",
        //                 "robot": "/image/optimize",
        //                 "progressive": true
        //             },
        //             "export": {
        //                 "use": [
        //                     "convert_image_png",
        //                     "resize_image",
        //                     "compress_image"
        //                 ],
        //                 "robot": "/s3/store",
        //                 "bucket": "{{env( 'AWS_BUCKET', 'enrichapps')}}",
        //                 "key": "{{env( 'AWS_ACCESS_KEY_ID', 'enrichapps')}}",
        //                 "secret": "{{env( 'AWS_SECRET_ACCESS_KEY', 'enrichapps')}}"
        //             }
        //         }
        //     },
        //     waitForEncoding: true
        // });
		uppyDashboard.on('complete', function(res) {
			$("#success > .alert-text").empty();
			$("#duplicate > .alert-text").empty();
			$("#possible_duplicate > .alert-text").empty();
			$("#success").css('display', 'none').removeClass("show");
			$("#duplicate").css('display', 'none').removeClass("show");
			$("#possible_duplicate").css('display', 'none').removeClass("show");

			var success = res.successful;
			var fail = res.failed;

			if(success.length > 0){
				success.forEach((e, inx) => {
					var response = e.response;
					var body = response.body;
					toastr.success("Success!", "CSV Imported!");
				});
			}else{
				fail.forEach((e, inx) => {
					var response = e.response;
					var body = response.body;
				});
			}
		});
	}

    var initUppy2 = function(){
		var id = '#kt_uppy_2';

		var options = {
			proudlyDisplayPoweredByUppy: false,
			target: id,
			inline: true,
			replaceTargetContent: true,
			showProgressDetails: true,
			note: 'Accepting Image files ONLY [jpeg, jpg, png].',
			height: 470,
			metaFields: [
				{ id: 'logo2', name: 'Logo2', resize:'500' },
			],
			browserBackButtonClose: true
		}

		var uppyDashboard = Uppy.Core({ 
			autoProceed: true,
			restrictions: {
				maxFileSize: 1000000000000, //1000000 =  1mb
				maxNumberOfFiles: 1,
				minNumberOfFiles: 1,
				allowedFileTypes: ['.jpg', '.jpeg', '.png', '.PNG', '.JPG', '.JPEG']
			} 
		});

		uppyDashboard.use(Dashboard, options);  
		uppyDashboard.use(XHRUpload, { endpoint: '/help/action/upload?pc=logo2' });
		uppyDashboard.use(GoogleDrive, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		// uppyDashboard.use(Dropbox, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		uppyDashboard.use(Instagram, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		// uppyDashboard.use(Webcam, { target: Dashboard });

		uppyDashboard.on('complete', function(res) {
			$("#success > .alert-text").empty();
			$("#duplicate > .alert-text").empty();
			$("#possible_duplicate > .alert-text").empty();
			$("#success").css('display', 'none').removeClass("show");
			$("#duplicate").css('display', 'none').removeClass("show");
			$("#possible_duplicate").css('display', 'none').removeClass("show");

			var success = res.successful;
			var fail = res.failed;

			if(success.length > 0){
				success.forEach((e, inx) => {
					var response = e.response;
					var body = response.body;
					toastr.success("Success!", "CSV Imported!");
				});
			}else{
				fail.forEach((e, inx) => {
					var response = e.response;
					var body = response.body;
				});
			}
		});
	}

	return {
		// public functions
		init: function() {
			initUppy1();
			initUppy2();

			// swal.fire({
			// 	"title": "Notice", 
			// 	"html": "Uppy demos uses <b>https://master.tus.io/files/</b> URL for resumable upload examples and your uploaded files will be temporarely stored in <b>tus.io</b> servers.", 
			// 	"type": "info",
			// 	"buttonsStyling": false,
			// 	"confirmButtonClass": "btn btn-brand kt-btn kt-btn--wide",
			// 	"confirmButtonText": "Ok, I understand",
			// 	"onClose": function(e) {
			// 		console.log('on close event fired!');
			// 	}
			// });
		}
	};
}();

KTUtil.ready(function() {	
	KTUppy.init();
});
</script>
@endsection
