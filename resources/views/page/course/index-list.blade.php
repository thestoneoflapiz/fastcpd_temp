@extends('template.master_provider')
@section('title', _current_provider()->name)
@section('styles')
<link href="{{ asset('css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
<style>
    #course_wizard_warp{display:none;}
    .bigger-spin{transform:scale(1.5); margin:10px;}
    .select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single{width: 60vw !important;}
    .required{color:#fd397a;}
    .hidden{display:none !important;}
    .col-pads{padding:5px;}
    .margin-auto{margin:auto;}

    .manage-course{position:absolute;width:100%;height:100%;z-index:1;background-color:rgba(62, 73, 107, 0.5);border-radius:5px;display:none;align-items:center;justify-content:center;text-align:center;}
    .manage-course > h3{color:white;font-weight:600;cursor: pointer;}
    span.suggestions-url{font-size: 1rem;}
    a.suggestion-text:hover{background-color:#e3e3e5;cursor:pointer;}
</style>
@endsection
@section('metas')
<meta name="description" content="Online marketplace where professionals can earn CPD units with video courses on their own schedule. PRC courses for accountancy, nursing, teachers, and more.">
@endsection
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12" id="course_tab">
            <div class="kt-portlet">
				@if(_my_provider_permission("courses", "create"))
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Courses
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-info" data-toggle="kt-tooltip" data-placement="top" title="Click here to Create" onclick="wizardShow()">&nbsp;<i class="fa fa-plus"></i><span class="hidden--sm">Create Course</span></button>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label row" style="padding:5px 0px 5px 0px;">
                        <div class="col-xl-8 col-md-8 col-sm-8 col-11 col-pads">
                            <div class="input-group">
                                <input type="text" name="searches" class="form-control" placeholder="Search Course" aria-describedby="basic-addon2">
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-search"></i></span></div>
                            </div>
                        </div>
                            <div class="col-xl-4 col-md-4 col-sm-4 col-11 col-pads">
                            <select class="form-control" name="orders">
                                <option value="newest">Newest</option>
                                <option value="oldest">Oldest</option>
                                <option value="from_a">A-Z</option>
                                <option value="from_z">Z-A</option>
                                <option value="fast_cpd_status">Status</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="loading" style="display:none;">
                <div class="kt-portlet">
                    <div class="kt-portlet__body" style="align-items:center;">
                        <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info bigger-spin"></div>
                    </div>
                </div>
            </div>

            <div id="all_courses"></div> 

            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin: Pagination-->
                    <div class="kt-pagination kt-pagination--brand">
                        <ul class="kt-pagination__links"></ul>
                        <div class="kt-pagination__toolbar">
                            <select class="form-control kt-font-brand" name="records" style="width: 50px">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                            <span class="pagination__desc">
                                Displaying 0 of 0 Courses
                            </span>
                        </div>
                    </div>
                    <!--end: Pagination-->
                </div>
            </div>
        </div>
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
                                        <small class="required hidden"><br/>Please choose a different Course URL.</small>
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
<script defer src="{{asset('js/jquery.star-rating-svg.min.js')}}" type="text/javascript"></script>
<script>
    var permission = <?=_my_provider_permission("courses", "edit") == true ? 1 :0 ?>;
    var status_icon = {
        draft: {
            icon: 'flaticon2-pen',
            color: 'info',
        },
        approved: {
            icon: 'flaticon2-correct',
            color: 'success',
        },
        live: {
            icon: 'flaticon2-correct',
            color: 'success',
        },
        published: {
            icon: 'flaticon2-correct',
            color: 'success',
        },
        'in-review': {
            icon: 'flaticon2-hourglass-1',
            color: 'warning',
        },
        closed: {
            icon: 'flaticon2-gear',
            color: 'danger',
        },
        canceled: {
            icon: 'flaticon2-gear',
            color: 'danger',
        },
        ended: {
            icon: 'flaticon2-calendar-2',
            color: 'danger',
        },
    };
    
    var pagination = {
        page: 0,
        take: 5,
        order: "newest",
        search: null,
    };

    var startPage = 0;
    var incremSlide = 4;

    $(document).ready(function(){
        fetchProviderCourses(pagination);
        CourseWizard.init();

        /**
         * Events on Search, Order, Page, and Take of Pagination
         *
         */
        // on change no. of records to take
        $('select[name="records"]').change(function(event){
            var value = event.target.value;
            pagination.take = value;

            fetchProviderCourses(pagination);
        });

        // on change course order
        $('select[name="orders"]').change(function(event){
            var value = event.target.value;
            pagination.order = value;

            fetchProviderCourses(pagination);
        });

        // on keypress enter search course title
        $('input[name="searches"]').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var value = event.target.value;
                pagination.search = value;

                fetchProviderCourses(pagination);
            }
        });
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
        $('input[name="course_url"]').val(value.replace(/[^a-zA-Z-]/g, '').toLowerCase());
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

    function fetchProviderCourses(pagination){
        $("#all_courses").slideUp(250);
        $("#loading").slideDown(500);

        $.ajax({
            url: "course/api/list",
            data: {
                provider_id: <?=_current_provider()->id?>,
                pagination: pagination,
            },
            success:function(response){
                var courses = response.data;
                
                renderPagination(response, pagination);
                renderCourseRow(response);
                
                setTimeout(() => {
                    $("#all_courses").slideDown(750);
                    $("#loading").slideUp(500);
                }, 1000);
            },
            error:function(){

            },
        });
    }

    function renderPagination(response, pagination){
        var filter = response.data.length;
        var total = response.total; // total records

        var num_loop = parseInt(total < 1 ? 0 : (total / pagination.take));
        var remainder = num_loop < 1 ? 0 : (total / pagination.take);
        var totalPages = (num_loop < 1 ? 0 : num_loop) + (remainder <= num_loop ? 0 : 1); 

        $("span.pagination__desc").html(`Displaying ${filter} of ${total} Courses`);
        var pagination_links = $('ul.kt-pagination__links').empty();
        
        /**
         * Render selection of pages
         *
         */
        var limit = totalPages -1;
        var display = 0;
        for (let i = 0; i < totalPages; i++) {
            pagination_links.append(`<li `+(i==pagination.page ? `class="kt-pagination__link--active page-li"` : `class="page-li"`)+`><a onclick="pageRender(${i})">${i+1}</a></li>`);
            
            if(startPage <= i && display < 5){
                display++;
            }else{
                var diff = limit - i;
                if(diff < 5 && display < 5){
                }else{
                    $("ul.kt-pagination__links li.page-li").eq(i).hide();
                }
            }
        }

        if(totalPages > 5){
            var prev_multi_link = $('<li />').addClass('kt-pagination__link--first').append(`<a href="javascript:;"><i class="fa fa-angle-double-left kt-font-brand"></i></a>`)
                .click(function(){
                    startPage=0;
                    slide(totalPages, pagination);
                });
            var prev_step_link = $('<li />').addClass('kt-pagination__link--next').append(`<a href="javascript:;"><i class="fa fa-angle-left kt-font-brand"></i></a>`)
                .click(function(){
                    startPage-=6;
                    slide(totalPages, pagination);
                });
            
            var next_multi_link = $('<li />').addClass('kt-pagination__link--last').append(`<a href="javascript:;"><i class="fa fa-angle-double-right kt-font-brand"></i></a>`)
            .click(function(){
                startPage = totalPages;
                slide(totalPages, pagination);
            });
            var next_step_link = $('<li />').addClass('kt-pagination__link--prev').append(`<a href="javascript:;"><i class="fa fa-angle-right kt-font-brand"></i></a>`)
            .click(function(){
                startPage+=5;
                slide(totalPages, pagination);
            });

            pagination_links.prepend(prev_step_link).prepend(prev_multi_link).append(next_step_link).append(next_multi_link);
        }
    }
    
    function pageRender(page){
        pagination.page = page;
        startPage = page;

        fetchProviderCourses(pagination);
    }

    function slide(total, pagination){
        var pagination_links = $('ul.kt-pagination__links li.page-li').hide();
        
        /**
         * Render selection of pages
         *
         */

        var limit = total - 1;
        startPage = startPage < 0 ? 0 : startPage;
        startPage = startPage > limit ? limit : startPage;

        var display = 0;
        for (let i = 0; i < total; i++) {
            pagination_links.eq(i).show();
            
            if(startPage <= i && display < 5){
                display++;
            }else{
                var diff = limit - i;
                if(diff < 5 && display < 5){
                }else{
                    pagination_links.eq(i).hide();
                }
            }
        }
    }

    function renderCourseRow(response){
        var group = $("#all_courses").empty();
        response.data.forEach((course, index) => {
            var container = $(`<div />`).addClass(`course-container`).css(`position`, `relative`).attr(`data-target`, `#manage-course-${index}`).hover(function(){
                $(`#manage-course-${index}`).slideDown(200).css("display", "flex");
            }, function(){
                $(`#manage-course-${index}`).slideUp(200);
            });
            
            var row = $(`<div />`).addClass(`kt-portlet course-row`);
            var body = $(`<div />`).addClass(`kt-portlet__body`);
            var profile = $(`<div />`).addClass(`kt-widget kt-widget--user-profile-3`);
            var top = $(`<div />`).addClass(`kt-widget__top`);
            var image = $(`<div />`).addClass(`kt-widget__media`).append(`<img  alt="FastCPD Courses ${course.title}" src="`+(course.course_poster ? course.course_poster : `{{ asset('img/sample/noimage.png') }}`)+`" alt="image">`);

            if(jQuery.inArray(course.fast_cpd_status, ['approved', 'published', 'live', 'cancelled', 'ended']) >= 0){
                var hover = $(`<div />`).addClass(`manage-course`).attr(`id`, `manage-course-${index}`).append(`<h3 onclick="alert('Performance View of Certain Course')">View Performance</h3></div>`);
                var contents = $(`<div />`).addClass(`kt-widget__content row margin-auto`).append(`\
                <div class="col-xl-43 col-md-4">\
                    <div class="kt-widget__head"><a href="javascript:;" class="kt-widget__username">${course.title}</a> </div>\
                    <div class="kt-widget__subhead">
                        ${course.program_accreditation_no}<br/>CPD Units <b>${course.total_unit_amounts}</b> &nbsp; &#9679; &nbsp; Price <b>₱${course.price.toLocaleString({ minimumFractionDigits: 2,})}</b><br/><a href="#"><i class="${status_icon[course.fast_cpd_status].icon} kt-font-${status_icon[course.fast_cpd_status].color}"></i>${course.fast_cpd_status.toUpperCase()}</a>\
                    </div>\
                </div>\
                <div class="col-xl-8 col-md-8">\
                    <div class="row">\
                        <div class="kt-widget__info col-xl-4 col-md-4"><h4 style="width:100%">₱${course.total_earning_month} </h4>Earned This Month</div>\
                        <div class="kt-widget__info col-xl-4 col-md-4"><h4 style="width:100%">${course.total_enrollment_month}</h4>Enrollments This Month</div>\
                        <div class="kt-widget__info col-xl-4 col-md-4"><h4 style="width:100%"><span id="course-rating-${course.id}"></span></h4></div>\
                    </div>\
                </div>`);
            }else{
                if(course.fast_cpd_status == "in-review"){
                    var hover = permission == 1 ? $(`<div />`).addClass(`manage-course`).attr(`id`, `manage-course-${index}`).append(`<h3 onclick="toastr.info('Access Denied! This course is currently in-review by the FastCPD Management')">Manage Course</h3></div>`) : $(`<div />`);
                }else{
                    var hover = permission == 1 ? $(`<div />`).addClass(`manage-course`).attr(`id`, `manage-course-${index}`).append(`<h3 onclick="window.open('/provider/course/${course.id}')">Manage Course</h3></div>`) : $(`<div />`);
                }
                var contents = $(`<div />`).addClass(`kt-widget__content row margin-auto`).append(`\
                <div class="col-xl-6 col-md-6">\
                    <div class="kt-widget__head"><a href="javascript:;" class="kt-widget__username">${course.title}</a> </div>\
                    <div class="kt-widget__subhead">
                        PRC Status&nbsp;<a href="#"><i class="${status_icon[course.prc_status].icon} kt-font-${status_icon[course.prc_status].color}"></i>${course.prc_status.toUpperCase()}</a><br/>\
                        FastCPD Status&nbsp;<a href="#"><i class="${status_icon[course.fast_cpd_status].icon} kt-font-${status_icon[course.fast_cpd_status].color}"></i>${course.fast_cpd_status.toUpperCase()}</a>\
                    </div>\
                </div>\
                <div class="col-xl-6 col-md-6">\
                    <div class="kt-widget__info">\
                        <div class="kt-widget__progress">\
                            <div class="kt-widget__text">Progress</div>\
                            <div class="progress" style="height: 5px;width: 100%;"><div class="progress-bar kt-bg-${course.total_progress < 50 ? (course.total_progress < 30 ? 'danger' : 'warning'): 'success'}" role="progressbar" style="width:${course.total_progress}%;" aria-valuenow="${course.total_progress}" aria-valuemin="0" aria-valuemax="100"></div></div>\
                            <div class="kt-widget__stats">${course.total_progress}%</div>\
                        </div>\
                    </div>\
                </div>`);
            }

            top.append(image).append(contents);
            profile.append(top);
            body.append(profile);
            row.append(body);
            container.append(hover).append(row).appendTo(group);
            $(`#course-rating-${course.id}`).starRating({
                readOnly: true,
                starShape: "rounded",
                starSize: 15,
                initialRating: course.total_course_rating,
            });
        });
    }
</script>
@endsection
 