@extends('template.master')
@section('title', 'Import Records')

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
					Import of {{ucwords($data['type'] ?? '[undefined]')}} requirements are: <br/>
					1. Import only accepts CSV files. <br/>
					2. Minimum and Maximum of {{ucwords($data['column'] ?? '[undefined]') }} columns.<br/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
							Upload your CSV File:
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
                            Import Results
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
					<div class="alert alert-success fade hidden" role="alert" id="success">
						<div class="alert-icon"><i class="flaticon-warning"></i></div>
						<div class="alert-text"></div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
					<div class="alert alert-danger fade hidden" role="alert" id="duplicate">
						<div class="alert-icon"><i class="flaticon-warning"></i></div>
						<div class="alert-text"></div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
					<div class="alert alert-warning fade hidden" role="alert" id="possible_duplicate">
						<div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
						<div class="alert-text"></div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
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
	const Dashboard = Uppy.Dashboard;
	// const Dropbox = Uppy.Dropbox;
	// const GoogleDrive = Uppy.GoogleDrive;
	// const Instagram = Uppy.Instagram;
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
			note: 'Accepting CSV files ONLY.',
			height: 470,
			metaFields: [
				{ id: 'name', name: 'Name', placeholder: 'file name' },
			],
			browserBackButtonClose: true
		}

		var uppyDashboard = Uppy.Core({ 
			autoProceed: true,
			restrictions: {
				maxFileSize: 1000000000000, //1000000 =  1mb
				maxNumberOfFiles: 1,
				minNumberOfFiles: 1,
				allowedFileTypes: ['.csv', '.CSV']
			} 
		});

		uppyDashboard.use(Dashboard, options);  
		uppyDashboard.use(XHRUpload, { endpoint: '/data/import/action?rc='+'{{$data["type"] ?? "default"}}' });
		// uppyDashboard.use(GoogleDrive, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		// uppyDashboard.use(Dropbox, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
		// uppyDashboard.use(Instagram, { target: Dashboard, companionUrl: 'https://companion.uppy.io' });
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

					switch (body.type) {
						case "warning":
							toastr.warning("Warning!", body.msg);
							break;
					
						case "info":
							toastr.info("Pay Attention!", body.msg);
							if(body.hasOwnProperty("support")){
								var support = body.support;
								if(support.success){
									$("#success > .alert-text").empty().html(support.success);
									$("#success").css('display', 'flex').addClass("show");
								}

								if(support.duplicate){
									$("#duplicate > .alert-text").empty().html(support.duplicate);
									$("#duplicate").css('display', 'flex').addClass("show");
								}

								if(support.possible){
									$("#possible_duplicate > .alert-text").empty().html(support.possible);
									$("#possible_duplicate").css('display', 'flex').addClass("show");
								}
							}
							break;
						default:
							toastr.error("Error!", body.msg);
							break;
					}
				});
			}
		});
	}

	return {
		// public functions
		init: function() {
			initUppy1();

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
