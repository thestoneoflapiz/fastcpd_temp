@extends('template.master_provider')
@section('title', _current_provider()->name)
@section('styles')
<link href="{{ asset('css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
<style>
    #course_wizard_warp{display:none;}
    .select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single{width: 60vw !important;}
    .required{color:#fd397a;}
    .hidden{display:none;}
    .recenter{text-align:center;margin:10rem auto 0px auto;}
    span.suggestions-url{font-size: 1rem;}
    a.suggestion-text:hover{background-color:#e3e3e5;cursor:pointer;}
</style>
@endsection 
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        @if(_my_provider_permission("courses", "create"))
        <div class="col-xl-12" id="course_tab">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                <div class="row justify-content-center" style="height:80vh">
                        <div class="col-6 recenter">
                            <i class="flaticon-presentation" style="font-size:5em;"></i>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <h2>Create your first course!</h2>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <button type="button" class="btn btn-info" data-toggle="kt-tooltip" data-placement="top" title="Click here to Create" onclick="wizardShow()">&nbsp;<i class="fa fa-plus"></i><span class="hidden--sm">Create Course</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-xl-12" id="course_tab">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="flaticon-presentation" style="font-size:2em;"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            It seems like the Provider hasn't created any courses yet. Try visiting again later!
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xl-12" id="course_wizard_warp">
            <!-- begin:: Content -->
            <div class="kt-portlet">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-grid kt-wizard-v1 kt-wizard-v1--white" id="course_wizard" data-ktwizard-state="step-first">
                        <div class="kt-grid__item">

                            <!--begin: Form Wizard Nav -->
                            <div class="kt-wizard-v1__nav">

                                <!--doc: Remove "kt-wizard-v1__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                                <div class="kt-wizard-v1__nav-items kt-wizard-v1__nav-items--clickable">
                                    <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
                                        <div class="kt-wizard-v1__nav-body">
                                            <div class="kt-wizard-v1__nav-icon">
                                                <i class="flaticon-customer"></i>
                                            </div>
                                            <div class="kt-wizard-v1__nav-label">
                                                Step 1. Course Title
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v1__nav-item" data-ktwizard-type="step">
                                        <div class="kt-wizard-v1__nav-body">
                                            <div class="kt-wizard-v1__nav-icon">
                                                <i class="flaticon-list"></i>
                                            </div>
                                            <div class="kt-wizard-v1__nav-label">
                                                Step 2. Target Professions
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--end: Form Wizard Nav -->
                        </div>
                        <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">

                            <!--begin: Form Wizard Form-->
                            <form class="kt-form" id="kt_form">

                                <!--begin: Form Wizard Step 1-->
                                <div class="kt-wizard-v1__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
                                    <div class="kt-heading kt-heading--md">
                                        About your Course
                                        <small class="required hidden"><br/>Please choose a different Course URL.</small>
                                    </div>
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v1__form">
                                            <div class="form-group">
                                                <label>Course Title</label>
                                                <input type="text" class="form-control" name="title" placeholder="What to Learn in this Course?">
                                                <span class="form-text text-muted">Please provide a working title. You can change details <b>later</b>.</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Course URL</label>
                                                <input type="text" class="form-control" name="course_url" placeholder="what-to-learn-in-this-course">
                                                <span class="form-text text-muted">Please provide a <b>user-friendly</b> url to help you <b>boost</b> your course.</span>
                                                <span class="suggestions-url"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Form Wizard Step 1-->

                                <!--begin: Form Wizard Step 2-->
                                <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                                    <div class="kt-heading kt-heading--md">
                                        Target Professions
                                    </div>
                                    <div class="kt-form__section kt-form__section--first">
                                        <div class="kt-wizard-v1__form">
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <select class="form-control kt-select2" id="profession" name="profession[]" multiple="multiple">
                                                        @foreach($data['provider_professions'] as $pro)
                                                        <option value="{{ $pro->id }}">{{ $pro->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="form-text text-muted">Please select your course's target profession</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Form Wizard Step 2-->

                                <!--begin: Form Actions -->
                                <div class="kt-form__actions">
                                    <a href="javascript:cancelWizard();"class="btn btn-danger btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                                        Cancel
                                    </a>
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                        Previous
                                    </button>
                                    <button class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
                                        Submit
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
            </div>
            <!-- end:: Content -->
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script>
    var window_width = $(window).width();
    if(window_width < 600){
        $(".hidden--sm").hide();
    }
    
    $(document).ready(function(){
        CourseWizard.init();
    });

    function cancelWizard(){
        $("#course_tab").slideDown(1000);
        $("#course_wizard_warp").slideUp(500);
    }

    function wizardShow(){
        $("#course_tab").slideUp(500);
        $("#course_wizard_warp").slideDown(1000);
    }

    $('input[name="course_url"]').keyup(function(event){
        var value = event.target.value;
        $('input[name="course_url"]').val(value.replace(/[^a-zA-Z-0-9]/g, '').toLowerCase());
    });

    $('#profession').select2({
        placeholder: "You can multiple-select Professions"
    });

    $(`input[name="title"]`).change(function(event){
        var current_value = event.target.value;
        if(current_value.length > 80){
            return;
        }
        
        $.ajax({
            url: '/provider/course/url/suggestions/',
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
                    $(`input[name="course_url"]`).val(suggest_value);
                });
            }, 
            error: function(){
                toastr.warning("Unable to give url suggestions!");
            }
        });
    });

    var CourseWizard = function () {
        // Base elements
        var wizardEl;
        var formEl;
        var validator;
        var wizard;

        // Private functions
        var initWizard = function () {
            // Initialize form wizard
            wizard = new KTWizard('course_wizard', {
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
                setTimeout(function() {
                    KTUtil.scrollTop();
                }, 500);
            });
        }

        var initValidation = function() {
            validator = formEl.validate({
                // Validate only visible fields
                ignore: ":hidden",

                // Validation rules
                rules: {
                    //= Step 1
                    title: {
                        required: true,
                        maxlength: 80,
                    },
                    course_url: {
                        required: true
                    },
                    //= Step 2
                    "profession[]": {
                        required: true
                    },
                },

                // Display error
                invalidHandler: function(event, validator) {
                    KTUtil.scrollTop();

                    swal.fire({
                        "title": "",
                        "text": "You missed some equired fields in your current progress.",
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
                    KTApp.progress(btn);
                    formEl.ajaxSubmit({
                        method: "POST",
                        url: "/provider/course/add",
                        data: {
                            provider_id: <?=_current_provider()->id;?>,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function() {
                            $(".required").addClass("hidden");

                            KTApp.unprogress(btn);
                            swal.fire({
                                "title": "",
                                "text": "Course has been saved!",
                                "type": "success",
                                "confirmButtonClass": "btn btn-secondary"
                            });

                            setTimeout(() => {
                                window.location="/course/management/details";
                            }, 1500);
                        },
                        error: function(response) {
                            const body = response.responseJSON;
                            if(body.hasOwnProperty("errors")){
                                if( body.errors.hasOwnProperty("course_url")){
                                    KTApp.unprogress(btn);
                                    swal.fire({
                                        "title": "Course failed to save!",
                                        "text": "The Course URL you provided is already taken. Please choose a different input.",
                                        "type": "error",
                                        "confirmButtonClass": "btn btn-danger"
                                    }); 

                                    $('input[name="course_url"]').val(null);
                                    $(".required").removeClass("hidden");
                                }

                                return;
                            }

                            KTApp.unprogress(btn);
                            swal.fire({
                                "title": "Course failed to save!",
                                "text": "Something went wrong! Please try again later.",
                                "type": "error",
                                "confirmButtonClass": "btn btn-danger"
                            });
                        }
                    });
                }
            });
        }

        return {
            // public functions
            init: function() {
                wizardEl = KTUtil.get('course_wizard');
                formEl = $('#kt_form');

                initWizard();
                initValidation();
                initSubmit();
            }
        };
    }();
</script>
@endsection
