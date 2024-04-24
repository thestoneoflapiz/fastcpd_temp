@extends('template.master_form')
@section('styles')
<link href="{{ asset('css/pages/wizard/wizard-3.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/cropper.css' )}}" rel="stylesheet" type="text/css" />
<style>
    .required{color:red;}
    .box{padding:10px;border:1px solid #e2e5ec;border-radius:5px; min-height:120px}
    .small{font-size:12px;}
    .large{font-size:20px;}
    .bold{font-weight:600;}

    span.suggestions-url{font-size: 1rem;}
    a.suggestion-text:hover{background-color:#e3e3e5;cursor:pointer;}
    /**
    * Icons on Headline
    * 
    */
    .icon-image{height:80px;}
</style>
@endsection
@section('metas')
<meta name = "description" content = "Earn income by creating and selling online CPD courses and take advantage of our PRC accreditation tools. Build your influence and your brand."> 
<meta property="og:title" content="Become a Provider | FastCPD" />
<meta property="og:url" content="<?=URL::to('/provider/register')?>" />
<meta property="og:type" content="fastcpd_com">
<meta property="og:description" content="Earn income by creating and selling online CPD courses and take advantage of our PRC accreditation tools. Build your influence and your brand." />
<meta property="og:image" content="https://fastcpd.com/img/sample/poster-sample.png" />
<meta property="og:site_name" content="FastCPD">
@endsection
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Become a Provider
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="btn btn-secondary btn-icon" onclick="window.location='/'" data-toggle="kt-tooltip" data-placement="top" title="Go Back"><i class="fa fa-arrow-left"></i></button>
                    </li>
                </ul>
            </div>
        </div>
        @if(Auth::user()->email_verified_at!=null)
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="register_wizard" data-ktwizard-state="step-first">
                <div class="kt-grid__item">

                    <!--begin: Form Wizard Nav -->
                    <div class="kt-wizard-v3__nav">

                        <!--doc: Remove "kt-wizard-v3__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                        <div class="kt-wizard-v3__nav-items kt-wizard-v3__nav-items--clickable">
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>1</span> Let's Get Started
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>2</span> Getting to know you
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
                                <div class="kt-wizard-v3__nav-body">
                                    <div class="kt-wizard-v3__nav-label">
                                        <span>3</span> Accreditation Details
                                    </div>
                                    <div class="kt-wizard-v3__nav-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end: Form Wizard Nav -->
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">

                    <!--begin: Form Wizard Form-->
                    <form class="kt-form" id="kt_form">
                
                        <!--begin: Form Wizard Step 1-->
                        <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                            <div class="row justify-content-center">
                                <div class="col-12" style="text-align:center;">
                                    <h3>Become a FastCPD provider to start offering online courses to 1,000,000 professionals today</h3>
                                    <div class="kt-space-30"></div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-12 col-4" align="center">
                                            <img src="{{ asset('img/system/profits.png') }}" class="icon-image">
                                        </div>
                                        <div class="col-xl-8 col-md-12 col-8">
                                            <p class="bold">Offer courses 24/7 and earn passive income on each enrollment</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-12 col-4" align="center">
                                            <img src="{{ asset('img/system/digital-campaign.png') }}" class="icon-image">
                                        </div>
                                        <div class="col-xl-8 col-md-12 col-8">
                                            <p class="bold">Easily upload video content, make quizzes, publish your course and instant certificate issurance</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-12 col-4" align="center">
                                            <img src="{{ asset('img/system/cms.png') }}" class="icon-image">
                                        </div>
                                        <div class="col-xl-8 col-md-12 col-8">
                                            <p class="bold">Tools for evaluators for fast CPD accreditation of your courses</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v3__form">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-6 col-md-6">
                                            <label>Provider Logo <text class="required">*</text></label>
                                            <div class="form-group row" style="justify-content:center;">
                                                <div class="kt-avatar kt-avatar--outline" id="provider_logo">
                                                    <div class="kt-avatar__holder" style="background-image: url({{ asset('img/sample/noimage.png') }})"></div>
                                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                                        <i class="fa fa-pen"></i>
                                                        <input type="file" name="logo" accept="image/*">
                                                    </label>
                                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                                        <i class="fa fa-times"></i>
                                                    </span> 
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>What's your organization? <text class="required">*</text></label>
                                                <input class="form-control" id="organization" type="text" name="organization" placeholder="The Fast CPD Inc.">
                                                <span class="form-text text-muted">Please enter the provider's name</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Provider URL <text class="required">*</text></label>
                                                <input class="form-control" id="seo_url" type="text" name="seo_url" placeholder="my-provider-since1998">
                                                <span class="form-text text-muted">Please enter a <b>user-friendly</b> url to help you <b>boost</b> your provider's page.</span>
                                                <span class="suggestions-url"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 1-->

                        <!--begin: Form Wizard Step 2-->
                        <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v3__form">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label>Target Profession <text class="required">*</text></label>
                                                <select class="form-control kt-select2" id="profession" name="profession" multiple="multiple" style="width:100%;">
                                                    @foreach(_all_professions() as $pro)
                                                    <option value="{{ $pro['id'] }}">{{ $pro['profession'] }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-text text-muted">Please select your provider's target profession</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Company Email <text class="required">*</text></label>
                                                <input class="form-control" id="email" type="text" name="email" placeholder="my-provider@example.com">
                                                <span class="form-text text-muted">Please enter your unique email-address</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Contact No. <text class="required">*</text></label>
                                                <input class="form-control" id="contact" type="text" name="contact" placeholder="+63 9**-****-***">
                                                <span class="form-text text-muted">Please enter your unique contact number</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 2-->

                        <!--begin: Form Wizard Step 3-->
                        <div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">
                                We want to partner with accredited providers or people who are already planning to provide online courses for professionals in our country
                            </div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v3__form">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Do you have an accreditation number?</label>
                                                <input class="form-control" id="accreditation" type="text" name="accreditation" placeholder="YYYY-000">
                                                <span class="form-text text-muted">Please enter your unique accreditation number</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div class="form-group">
                                                <label>Accreditation Expiration Date</label>
                                                <input type="text" class="form-control" name="accreditation_expiration_date" id="accreditation_expiration_date" placeholder="Select Expiration Date" />
                                                <span class="form-text text-muted">Please select the expiration of accreditation</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 3-->
                        <!--begin: Form Actions -->
                        <div class="kt-form__actions">
                            <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                Previous
                            </button>
                            <button class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit" id="submit_form">
                                Register Provider Details
                            </button>
                            <button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                                Next
                            </button>
                        </div>

                        <!--end: Form Actions -->
                    </form>

                    <!--end: Form Wizard Form-->
                </div>
            </div>
        </div>
        @else
        <div class="kt-portlet__body" style="text-align:center">
            <h3>Sorry!<br>You're email isn't verified yet. Please check your email's inbox!</h3>
        </div>
        @endif
    </div>
</div>
<div class="modal fade" id="cropper_modal" tabindex="-1" role="dialog" aria-labelledby="profile_image_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <img id="profile_image" height="400">
                    </div>
                    <div class="col-md-4">
                        <div class="preview"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" id="crop_submit">CROP</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/cropper.js')}}" type="text/javascript"></script>
<script>
    var uploaded_image = null;

    var Profile = function () {
	// Base elements
        var profile;
    
        var initUserForm = function() {
            profile = new KTAvatar('provider_logo');
        }

        return {
            // public functions
            init: function() {
                initUserForm();
            }
        };
    }();

    var KTWizard3 = function () {
        // Base elements
        var wizardEl;
        var formEl;
        var validator;
        var wizard;

        // Private functions
        var initWizard = function () {
            // Initialize form wizard
            wizard = new KTWizard('register_wizard', {
                startStep: 1, // initial active step number
                clickableSteps: true  // allow step clicking
            });

            // Validation before going to next page
            wizard.on('beforeNext', function(wizardObj) {
                if (validator.form() !== true) {
                    wizardObj.stop();  // don't go to the next step
                }
            });

            wizard.on('beforePrev', function(wizardObj) {
                if (validator.form() !== true) {
                    wizardObj.stop();  // don't go to the next step
                }
            });

            // Change event
            wizard.on('change', function(wizard) {
                KTUtil.scrollTop();
            });
        }

        var input_masks = function () {
           
           // phone number format
           $("#contact").inputmask("mask", {
               "mask": "+63 999-999-9999",
           });        
       }

        $.validator.addMethod( "minDate", function( dateInput, element ) {
            dateGiven = new Date(dateInput);
            dateMinimum = new Date(Date.now() + 12096e5);

            return this.optional( element ) || dateGiven > dateMinimum;
        }, "Please choose a date at least 2 weeks from now" );

        var initValidation = function() {
            validator = formEl.validate({
                // Validate only visible fields
                ignore: ":hidden",

                // Validation rules
                rules: {
                    //= Step 1
                    organization: {
                        required: true
                    },
                    logo: {
                        required: true
                    },
                    seo_url: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                    },

                    //= Step 2
                    profession: {
                        required: true
                    },
                    contact: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },

                    //= Step 3
                    accreditation_expiration_date: {
                        date: "mm-dd-yyyy",
                        minDate: true,
                    },
                },

                // Display error
                invalidHandler: function(event, validator) {
                    KTUtil.scrollTop();

                    swal.fire({
                        "title": "",
                        "text": "There are some errors in your submission. Please correct them.",
                        "type": "error",
                        "confirmButtonClass": "btn btn-secondary"
                    });
                },

                // Submit valid form
                submitHandler: function (form) {

                }
            });
        }

        var initSubmit = function() {
            var btn = formEl.find('[data-ktwizard-type="action-submit"]');

            btn.on('click', function(e) {
                e.preventDefault();

                if (validator.form()) {
                    var $submit = $("#submit_form");
                   $submit.addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                    formEl.ajaxSubmit({
                        method: "POST",
                        url: "/provider/register/action",
                        data: {
                            provider_logo: uploaded_image,
                            seo_url: $('#seo_url').val(),
                            profession: $('#profession').val(),
                            name: $('#organization').val(),
                            accreditation: $('#accreditation').val(),
                            accreditation_expiration_date: $('#accreditation_expiration_date').val(),
                            email: $('#email').val(),
                            contact: $('#contact').val(),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            var id = response.id;
                            $(".required").addClass("hidden");
                           $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled');
                                if(response.status==200){
                                    toastr.success('Success!', 'Registration for Provider is sent! Please wait for the approval for posting publicly. Thank you!');
                                    setTimeout(() => {
                                        window.location=`/provider/session/${id}`;
                                    }, 1000);
                                }else{
                                    toastr.error('Error!', response.message);
                                }
                        },
                        error: function(response) {
                            $submit.removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false);
                            const body = response.responseJSON;
                            if(body && body.hasOwnProperty("errors")){
                                const errors = body.errors;
                                var alert = $('#form_msg');

                                if(errors.hasOwnProperty("seo_url")){
                                    toastr.warning('Warning!', '<b>Provider URL</b> has already been taken <br/>');
                                }

                                if(errors.hasOwnProperty("organization")){
                                    toastr.warning('Warning!', '<b>Provider Name</b> has already been taken <br/>');
                                }

                                if(errors.hasOwnProperty("email")){
                                    toastr.warning('Warning!', '<b>Provider Email</b> has already been taken <br/>');
                                }

                                if(errors.hasOwnProperty("contact")){
                                    toastr.warning('Warning!', '<b>Provider Contact</b> has already been taken <br/>');
                                }

                                if(errors.hasOwnProperty("accreditation")){
                                    toastr.warning('Warning!', '<b>Provider Accreditation Number</b> has already been taken <br/>');
                                }

                                $('.kt-alert__text').html(html);
                                alert.removeClass('kt-hidden').show();
                                KTUtil.scrollTop();
                                // toastr.warning('Warning!', 'Some fields are already taken!');

                            }else{
                                toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                            }

                        }
                    });
                }
            });
        }

        return {
            // public functions
            init: function() {
                wizardEl = KTUtil.get('register_wizard');
                formEl = $('#kt_form');

                input_masks();
                initWizard();
                initValidation();
                initSubmit();
            }
        };
    }();
    
    jQuery(document).ready(function() {
        Profile.init();
        KTWizard3.init();

        $("#accreditation_expiration_date").datepicker({
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
        });

        $('#profession').select2({
            placeholder: "You can multiple-select Professions"
        });

        $('#seo_url').keyup(function(event){
            var value = event.target.value;
            $('#seo_url').val(value.replace(/([~!@#$%^&*()+=`{}\[\]\|\\:;'<>,\/? ])+/g, ''));
        });
    });

    $('.kt-avatar__cancel').click(function(){
        uploaded_image = null;
    });
    
    var cropper_modal = $('#cropper_modal');
    var image = document.getElementById('profile_image');
    var cropper;

    $("input[type=file]").change(function(e){
        var files = e.target.files;
        if (files && files.length > 0) {
            var file = files[0];
            var reader = new FileReader();
        
            reader.onloadend = function () {
                $('#profile_image').attr('src', reader.result);
                $('#cropper_modal').modal({backdrop: 'static', keyboard: false, show:true});
            }

            if (file) {
                reader.readAsDataURL(file);
            } 
        }
    });

    cropper_modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 2,
            zoomable: false,
            maxHeight: 100,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    
    $("#crop_submit").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {
                var base64data = reader.result;	
                uploaded_image = base64data;
                cropper_modal.modal('hide');
                $('.kt-avatar__holder').css("background-image", "url("+base64data+")");
            }
        });
    });

    $(`input[name="organization"]`).change(function(event){
        var current_value = event.target.value;

        $.ajax({
            url: '/provider/url/suggestions/',
            data: { title: current_value },
            success: function(response){
                var suggestions = response.data;
                var span_comp = $(`span.suggestions-url`);

                var compiles = ``;
                suggestions.forEach((value, index) => {
                    if(index==1){compiles += `, &nbsp;<b><a class="suggestion-text">${value}</a></b>, OR &nbsp;`;}
                    else{ compiles += `<b><a class="suggestion-text">${value}</a></b>`; }
                });

                span_comp.empty().append(`<b class="kt-font-warning strong">Suggestions:</b> <br/>${compiles}`);

                $(`a.suggestion-text`).click(function(e){
                    var suggest_value = e.target.innerHTML;
                    $(`input[name="seo_url"]`).val(suggest_value);
                });
            }, 
            error: function(){
                toastr.warning("Unable to give url suggestions!");
            }
        });
    });
</script>    
@endsection
