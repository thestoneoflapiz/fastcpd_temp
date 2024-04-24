<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>Boilerplate</title>
		<meta name="description" content="Updates and statistics">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
		<!--end::Fonts -->

		<link href="{{asset('css/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('plugins/general/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />

		<link href="{{asset('css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('css/skins/aside/dark.min.css')}}" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="{{asset('img/system/icon-1.png')}}" />

		<style>
			.toast-title{color:white !important; font-weight:500;}
		</style>
	</head>
	<!-- end::Head -->

	<body class="kt-page--loading">
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
					<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="kt-portlet kt-portlet--height-fluid">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">
                                                    Company Information
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <form class="kt-form kt-form--label-left" id="add_form">
                                                <div class="kt-portlet__body">
                                                    <div class="kt-form__content">
                                                        <div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="add_form_msg">
                                                            <div class="kt-alert__icon">
                                                                <i class="fa fa-exclamation-triangle"></i>
                                                            </div>
                                                            <div class="kt-alert__text">&nbsp; Sorry! You have to complete the form requirements first!</div>
                                                            <div class="kt-alert__close">
                                                                <button type="button" class="close" data-close="alert" aria-label="Close">
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-3 col-sm-12">System <text class="required">*</text></label>
                                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                                            <div class="kt-typeahead">
                                                                <input class="form-control" id="system" type="text" name="system" placeholder="Enrich Apps Boilerplate System">
                                                            </div>
                                                            <span class="form-text text-muted">Please enter the project's name</span>
                                                        </div>
                                                    </div>
                                                    <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-3 col-sm-12">Name <text class="required">*</text></label>
                                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                                            <div class="kt-typeahead">
                                                                <input class="form-control" id="name" type="text" name="name" placeholder="Enrich Apps">
                                                            </div>
                                                            <span class="form-text text-muted">Please enter the company's name</span>
                                                        </div>
                                                    </div>
                                                    <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-3 col-sm-12">Address <text class="required">*</text></label>
                                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                                            <div class="kt-typeahead">
                                                                <input class="form-control" id="address" type="text" name="address" placeholder="30 Cabatuan St., corner Dome, Quezon City, MM, PH ">
                                                            </div>
                                                            <span class="form-text text-muted">Please enter company's address</span>
                                                        </div>
                                                    </div>
                                                    <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-3 col-sm-12">Email Address <text class="required">*</text></label>
                                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                                            <div class="kt-typeahead">
                                                                <input class="form-control" id="email" type="text" name="email" placeholder="noreply.mycompany@example.com">
                                                            </div>
                                                            <span class="form-text text-muted">Please enter a unique email-address</span>
                                                        </div>
                                                    </div>
                                                    <div class="kt-form__seperator kt-form__seperator--dashed kt-form__seperator--space"></div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-3 col-sm-12">Contact No. <text class="required">*</text></label>
                                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                                            <div class="kt-typeahead">
                                                                <input class="form-control" id="contact" type="text" name="contact" placeholder="+63 999-9999-999">
                                                            </div>
                                                            <span class="form-text text-muted">Please enter a contact number</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="kt-portlet__foot">
                                                    <div class=" ">
                                                        <div class="row">
                                                            <div class="col-lg-9 ml-lg-auto">
                                                                <button id="submit_form" class="btn btn-success">Add User</button>
                                                                <button type="reset" class="btn btn-secondary">Clear</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
					<!-- begin:: Footer -->
					<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
						<div class="kt-container  kt-container--fluid ">
							<div class="kt-footer__copyright">
								<a href="javascript:;" class="kt-link">&copy;FastCPD 2020</a>
							</div>
                            <div class="kt-footer__menu" style="text-align:right;">
								<a href="/legal/terms-of-service" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link">Terms of Service</a>
								<a href="/legal/privacy-policy" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link">Privacy Policy</a>
								<a href="https://www.facebook.com/FastCPD" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link">Help</a>
							</div>
						</div>
					</div>

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>
		<!-- end::Scrolltop -->
		<script src="{{asset('plugins/general/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/inputmask/dist/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/inputmask/dist/inputmask/inputmask.date.extensions.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/inputmask/dist/inputmask/inputmask.numeric.extensions.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-validation/dist/jquery.validate.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/jquery-validation/dist/additional-methods.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/js/global/integration/plugins/jquery-validation.init.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
		<script src="{{asset('plugins/general/sticky-js/dist/sticky.min.js')}}" type="text/javascript"></script>

		<!--end:: Vendor Plugins -->
		<script src="{{asset('js/scripts.bundle.min.js')}}" type="text/javascript"></script>

		<script src="{{asset('plugins/custom/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>

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
        <script>
            function goBack(){
                window.location="/users";
            }

            var FormDesign = function () {
                // Private functions
                var input_masks = function () {
                
                    // phone number format
                    $("#contact").inputmask("mask", {
                        "mask": "+63 999-999-9999",
                    });       
                }

                // Private functions
                var validator;

                var input_widgets = function() {
                    $('#role').on('role:change', function(){
                        validator.element($(this)); // validate element
                    });
                }

                var input_validations = function () {
                    validator = $( "#add_form" ).validate({
                        // define validation rules
                        rules: {
                            system: {
                                required: true,
                            },
                            name: {
                                required: true,
                            },
                            address: {
                                required: true,
                            },
                            email: {
                                required: true,
                                email: true,
                            },
                            contact: {
                                required: true,
                            },
                        },
                        
                        //display error alert on form submit  
                        invalidHandler: function(event, validator) {             
                            var alert = $('#add_form_msg');
                            $('kt-alert__text').html('&nbsp; &nbsp; Oh snap! Change a few things up and try submitting again.');
                            alert.removeClass('kt-hidden').show();
                            KTUtil.scrollTop();
                        },

                        submitHandler: function (form) {

                            $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                            var submit_form = $.ajax({
                                url: '/info/action',
                                type: 'POST',
                                data: {
                                    system: $('#system').val(),
                                    name: $('#name').val(),
                                    address: $('#address').val(),
                                    email: $('#email').val(),
                                    contact: $('#contact').val(),
                                    _token: "{{ csrf_token() }}",
                                },
                                success: function(response){
                                    if(response==200){
                                        location.reload();
                                    }else{
                                        $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                        toastr.error('Error!', response);
                                    }
                                },
                                error: function(){
                                    $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                                    toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                                }
                            });
                        }
                    });       
                }

                return {
                    // public functions
                    init: function() {
                        input_masks(); 
                        input_widgets(); 
                        input_validations(); 
                    }
                };
            }();

            jQuery(document).ready(function() {
                FormDesign.init();
            });
        </script>
	</body>
</html>