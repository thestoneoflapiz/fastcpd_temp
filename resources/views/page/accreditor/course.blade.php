@extends('template.master_accreditor')
@section('styles') 
<link href="{{asset('css/course-cards-popover.css')}}" rel="stylesheet" type="text/css" />
<style>
    .nothing-note{font-style: italic;color:#959cb6;}
    .back{font-weight:bold;font-size:12px;margin-top:-20px;text-indent:30px;}
    .black-banner{background-color: #172830 !important; line-height:1.3; width:100vw;position: relative;margin-left:-50vw;left:50%;}
    .gray-banner{background-color: rgba(23, 40, 48, 0.9) !important; line-height:1.3; width:100vw;position: relative;margin-left:-50vw;left:50%;z-index:2!important;}
    .white-color{color:#fff !important;}
    .title{font-size:2.5em !important;}
    .top{padding-top:30px;}
    .bottom{padding-bottom:30px;}
    .headline{font-size:18px;font-weight:400 !important;}
    .third{font-size:16px !important;}
    .br{margin-bottom:5px;}
    .provider{font-size:16px;font-weight:500 !important;}
    .limit-size{max-height:510px;overflow:hidden;}
    .img-circular{width:100px;height:100px;background-size:cover;display:inline-block;background-position:center;border-radius:100px;-webkit-border-radius:100px;-moz-border-radius:100px;}
    .rate-size{font-size:32px;}
    .limit-requirements{max-height:200px;overflow:hidden;} 
    .limit-description{max-height:500px;overflow:hidden;}
    .limit-target{max-height:200px;overflow:hidden;}
    .limit-reviews{max-height:530px;overflow:hidden;}
    .limit-objectives{max-height:300px;overflow:hidden;}
    body{overflow:scroll;}
    .m-top{margin-top:-270px;}
    .big_header{display:none;position:fixed;width:100%;height:300px;background-color:#084B8A;color:white;z-index:2;transition: all 0.3s ease;}
    .sticky_header{top:5px;height:160px;padding:10px;}
    .panel_top{top:-250};
    .cta_z_index{z-index:2!important;}
    .cta_position{position:fixed;top:50;right:10;z-index:2!important;transition: all 0.3s ease;}

    .accordion.accordion-toggle-plus .card .card-header .card-title{color:#646c9a !important;}
    .kt-infobox .kt-infobox__body .accordion .card .card-body{color:#646c9a !important;font-size:1rem;}

    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}

    .carousel-control-prev, .carousel-control-next{width:5%;}
    .carousel-control-prev:hover{background-image: linear-gradient(to left, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-next:hover{background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-prev-icon{background-image: url({{asset('img/carousel/prev.svg')}})}
    .carousel-control-next-icon{background-image: url({{asset('img/carousel/next.svg')}})}
    .carousel-inner{overflow:visible !important;}

    /* Images */
    .kt-widget.kt-widget--user-profile-2 .kt-widget__head .kt-widget__media .kt-widget__img{border-radius:3px;}
    .kt-widget.kt-widget--user-profile-2 .kt-widget__head .kt-widget__media .kt-widget__pic{border-radius:3px;}
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}

    /** preview video */
    .preview-course-video{width:100%;}
    .course-poster-img > h5{position:absolute;font-weight:600;left:0;right:0;bottom:10;cursor:pointer;}
    .kt-widget19 .kt-widget19__pic .kt-widget19__shadow{top:0}
</style>
@endsection
@section('content')
<input type="hidden" value="{{ $course->provider->id ?? 0 }}" name="provider_id"/>
<input type="hidden" value="{{ $course->id ?? 0 }}" name="course_id"/>
<div class="kt-container kt-grid__item kt-grid__item--fluid col-12">

    <!-- Black banner before scroll -->
    <div class="kt-portlet black-banner" id="default-screen-banner">
        <div class="kt-portlet__body">
            <div class="kt-widget kt-widget--user-profile-3 col-12 col-md-8 col-lg-8 col-xl-8">
                <div class="kt-widget__top">
                    <div class="kt-widget__content bottom">
                        <button class="btn-label-warning btn btn-lg">FOR PROFESSIONAL VIEW ONLY</button> <br/>
                        <div class="col-12">
                            <div class="kt-widget__head top">
                                <span class="kt-widget__title title white-color">{{ $course->title }}</span>
                            </div>
                            <div class="kt-widget__head headline white-color br">
                            {{ $course->headline }}
                            </div>
                        </div>
                        <div class="kt-font-md third br col-12">
                            <!-- <span class="rating kt-padding-r-10" data-rating="4.5"></span>  -->
                            <!-- <span class="white-color kt-padding-r-10 kt-font-lg" id="rating">4.5</span> 
                            <span class="white-color kt-padding-r-20 kt-font-lg" id="enrolled">(1,219)</span>  -->
                            <!-- <span class="white-color kt-font-lg" id="units"><i class="flaticon-medal"></i> CPD Units {{ $course->total_unit_amounts ?? 0 }} </span>  -->
                        </div>
                        <div class="kt-font-lg br col-12">
                            <span class="white-color third kt-padding-r-5">Created by</span>
                            <span class="white-color provider kt-padding-r-5">{{ $course->provider->name ?? 'Undefined'}}</span>
                            <span class="white-color provider kt-padding-r-5">&#x25CF;</span>
                            <span class="white-color provider kt-padding-r-10">{{ $course->program_accreditation_no ?? 0 }}</span> 
                        </div>
                        <div class="kt-font-lg br col-12">
                        <span class="white-color third kt-padding-r-10">Get until {{ date("F d, Y", strtotime($course->session_end)) }}</span>
                            <span class="white-color third"><i class="flaticon2-chat-1"></i> {{ ucwords($course->language) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sticky banner -->
    <div class="kt-portlet gray-banner big_header" id="header">
        <div class="kt-portlet__body">
            <div class="kt-widget kt-widget--user-profile-3 col-12">
                <div class="kt-widget__top">
                    <div class="kt-widget__content bottom">
                        <div class="kt-widget__head top">
                            <span class="kt-widget__title white-color kt-font-xl">{{ $course->title }}</span>
                        </div>
                        <div class="kt-font-md col-12">
                            <span class="rating kt-padding-r-10" data-rating="4.5"></span> 
                            <span class="white-color kt-padding-r-10 kt-font-lg" id="rating">4.5</span> 
                            <span class="white-color kt-padding-r-20 kt-font-lg" id="enrolled">(1,219)</span> 
                            <span class="white-color kt-font-lg" id="units"><i class="flaticon-medal"></i> {{ $course->total_unit_amounts }} CPD Units</span> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" data-sticky-container>
        <div class="col-12 col-md-12 col-lg-8 col-xl-9" style="position:relative;">
            <div class="kt-portlet">
                <div class="kt-portlet__body" id="section-objectives">
                    <div class="kt-infobox">
                        <div class="kt-infobox__header">
                            <h2 class="kt-infobox__title">What you'll learn?</h2>
                        </div>
                        <div class="kt-infobox__body">
                            <div class="row">
                                @foreach(json_decode($course->objectives) as $objective)
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="flaticon2-check-mark"></i> &nbsp;&nbsp;
                                        </div>
                                        <div class="col-11">
                                            <span style="text-indent:1em;">
                                                {{ $objective }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kt-space-10"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot footer-objectives">
                    <div class="kt-chat__input kt-align-center">
                        <span class="btn btn-clean btn-label-band see-objectives">See more</span>
                    </div>
                </div>
            </div>

            <!-- begin:: Content -->
            <div class="kt-portlet">
                <div class="kt-portlet__body" id="section-description">
                    <div class="kt-infobox">
                        <div class="kt-infobox__header">
                            <h2 class="kt-infobox__title">Description</h2>
                        </div>
                        <div class="kt-infobox__section">
                            <?=html_entity_decode($course->description) ?? "No Description yet"?>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot footer-description">
                    <div class="kt-chat__input kt-align-center">
                        <span class="btn btn-clean btn-label-band see-description">See more</span>
                    </div>
                </div>
            </div>
            <!-- end:: Content -->

            
            <!-- begin:: Content -->
            <div class="kt-portlet">
                <div class="kt-portlet__body" id="section-requirements">
                    <div class="kt-infobox">
                        <div class="kt-infobox__header">
                            <h2 class="kt-infobox__title">Requirements</h2>
                        </div>
                        @if($course->requirements == null)
                        <div class="kt-infobox__body">
                            <div class="kt-infobox__section">
                                <h3 class="kt-infobox__subtitle">There are no requirements to take this course</h3>
                            </div>
                        </div>
                        @else
                        <div class="kt-infobox__body">
                            <div class="kt-infobox__section">
                                <ul>
                                @foreach(json_decode($course->requirements) as $requirement)
                                    <li class="kt-font-lg kt-font-dark">{{ $requirement }}</li>
                                @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="kt-portlet__foot footer-requirements">
                    <div class="kt-chat__input kt-align-center">
                        <span class="btn btn-clean btn-label-band see-requirements">See more requirements</span>
                    </div>
                </div>
            </div>
            <!-- end:: Content -->

            <!-- begin:: Content -->
            <div class="kt-portlet">
                <div class="kt-portlet__body" id="section-target">
                    <div class="kt-infobox">
                        <div class="kt-infobox__header">
                            <h2 class="kt-infobox__title">Who this course is for:</h2>
                        </div>
                        @if($course->target_students == null)
                        <div class="kt-infobox__body">
                            <div class="kt-infobox__section">
                                <h3 class="kt-infobox__subtitle">Anyone is encouraged to take this course</h3>
                            </div>
                        </div>
                        @else
                        <div class="kt-infobox__body">
                            <div class="kt-infobox__section">
                                <ul>
                                @foreach(json_decode($course->target_students) as $target)
                                    <li class="kt-font-lg kt-font-dark">{{ $target }}</li>
                                @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="kt-portlet__foot footer-target">
                    <div class="kt-chat__input kt-align-center">
                        <span class="btn btn-clean btn-label-band see-target">See more</span>
                    </div>
                </div>
            </div>
            <!-- end:: Content -->
            <!-- profile provider::start -->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="kt-infobox">
                        <div class="row">
                            <div class="col-xl-2 col-md-3 col-sm-4 col-12">
                                <div class="image_container">
                                    <img alt="FastCPD Provider Logo <?=$course->provider->name ?? ""?>" src="{{ $course->provider->logo ?? asset('img/sample/noimage.png') }}" class="image"/>
                                </div>
                            </div> 
                            <div class="col-xl-10 col-md-9 col-sm-8 col-12">
                                <h3 class="kt-font-bolder">{{ $course->provider->name ?? 'Not found' }} <i class="flaticon2-correct kt-font-success"></i></h3>

                                <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">{{ number_format(_provider_courses($course->provider->id, true)) }}</span>
                                <span  style="font-size:1rem;">&nbsp;Courses</span>
                                &nbsp; &nbsp;
                                <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">{{ number_format(_provider_instructors($course->provider->id, true)) }}</span>
                                <span  style="font-size:1rem;">&nbsp;Instructors</span>
                                <br/>
                                <br/>

                                <p>{{ $course->provider->headline }}</p>    
                                <br/>
                                <br/>
                                <a href="javascript:toastr.info('This is an Accreditor preview!');" class="btn btn-label-info btn-md kt-font-bolder">VIEW PROVIDER</a>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <!-- profile provider::end -->
            @if($instructors->count() > 0)
            <hr/>
           
            <div class="row">
                <div class="col-xl-12 col-12">
                    <!--begin::Portlet-->
                    <div class="kt-portlet__body" id="course-instructors">                    
                        <div id="course-instructors-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                            <br/>
                            <div id="course-instructors-carousel-inner" class="carousel-inner"></div>
                            <a id="course-instructors-carousel-control-prev" class="carousel-control-prev" href="#course-instructors-carousel-indicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a id="course-instructors-carousel-control-next" class="carousel-control-next" href="#course-instructors-carousel-indicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
            @endif
        </div>
        <div class="col-12 col-md-4 col-lg-4 col-xl-3 cta_z_index panel_top" id="default-screen-flag">
            <div class="kt-portlet">
                <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                    <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill course_image">
                        <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides course-poster-img" style="text-align:center; min-height: 200px; border:5px solid white; background-image: url({{ $course->course_poster }})">
                            <div class="kt-widget19__shadow"></div>
                            <h5 class="kt-widget19__info kt-font-light btn-label-warning" style="margin-top:150px;font-weight:bold;">
                                <button class="btn-label-warning btn btn-lg">FOR PROFESSIONAL VIEW ONLY</button>
                            </h5>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget19__wrapper">
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="text-align:center;">
                                    <p>
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($course->price) }}</span>&nbsp;&nbsp;&nbsp; 
                                        <!-- <span style="text-decoration: line-through;font-size:18px">&#8369;500</span> &nbsp;&nbsp;&nbsp; 
                                        <span style="font-size:18px">20% off</span> -->
                                    </p>
                                </div>
                            </div>
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="line-height:1.2;">
                                    <p><b>This course includes:</b></p>
                                    <p>
                                        <i class="flaticon-medal kt-font-warning"></i>
                                        <span>Instant CPD Certificate on completion</span>
                                    </p>
                                    <p>
                                        <i class="fa fa-video kt-font-info"></i>
                                        <span>{{ $total["video"] }} of educational videos</span>
                                    </p>
                                    <p>
                                        <i class="flaticon2-list-1 kt-font-success"></i>
                                        <span>{{ $total["quiz"] }} Quiz{{ $total["quiz"] > 1 ? "zes" : "" }}</span>
                                    </p>
                                    <p>
                                        <i class="la la-newspaper-o kt-font-danger"></i>
                                        <span>{{ $total["article"] }} Article{{ $total["article"] > 1 ? "s" : "" }}</span>
                                    </p>
                                    <p>
                                        <i class="la la-file-text"></i>
                                        <span>{{ $total["handout"] }} Downloadable handout{{ $total["handout"] > 1 ? "s" : "" }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 col-lg-4 col-xl-3 cta_position" id="sticky" style="display:none;">
            <div class="kt-portlet sticky" data-sticky="true" data-margin-top="100px" data-sticky-for="800" data-sticky-class="kt-sticky">
                <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                    <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill course_image">
                        <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides course-poster-img" style="text-align:center; min-height: 200px; border:5px solid white; background-image: url({{ $course->course_poster }})">
                            <div class="kt-widget19__shadow"></div>
                            <h5 class="kt-widget19__info kt-font-light btn-label-warning" style="margin-top:150px;font-weight:bold;">
                                <button class="btn-label-warning btn btn-lg">FOR PROFESSIONAL VIEW ONLY</button>
                            </h5>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget19__wrapper">
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="text-align:center;">
                                    <p>
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($course->price) }}</span>&nbsp;&nbsp;&nbsp; 
                                        <!-- <span style="text-decoration: line-through;font-size:18px">&#8369;500</span> &nbsp;&nbsp;&nbsp; 
                                        <span style="font-size:18px">20% off</span> -->
                                    </p>
                                </div>
                            </div>
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="line-height:1.2;">
                                    <p><b>This course includes:</b></p>
                                    <p>
                                        <i class="flaticon-medal kt-font-warning"></i>
                                        <span>Instant CPD Certificate on completion</span>
                                    </p>
                                    <p>
                                        <i class="fa fa-video kt-font-info"></i>
                                        <span>{{ $total["video"] }} of educational videos</span>
                                    </p>
                                    <p>
                                        <i class="flaticon2-list-1 kt-font-success"></i>
                                        <span>{{ $total["quiz"] }} Quiz{{ $total["quiz"] > 1 ? "zes" : "" }}</span>
                                    </p>
                                    <p>
                                        <i class="la la-newspaper-o kt-font-danger"></i>
                                        <span>{{ $total["article"] }} Article{{ $total["article"] > 1 ? "s" : "" }}</span>
                                    </p>
                                    <p>
                                        <i class="la la-file-text"></i>
                                        <span>{{ $total["handout"] }} Downloadable handout{{ $total["handout"] > 1 ? "s" : "" }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-8 col-md-7 col-lg-6 col-xl-6" id="small-screen-flag" style="display:none;">
            <div class="kt-portlet">
                <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                    <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill course_image">
                        <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides course-poster-img" style="text-align:center; min-height: 200px; border:5px solid white; background-image: url({{ $course->course_poster }})">
                            <div class="kt-widget19__shadow"></div>
                            <h5 class="kt-widget19__info kt-font-light btn-label-warning" style="margin-top:150px;font-weight:bold;">
                                <button class="btn-label-warning btn btn-lg">FOR PROFESSIONAL VIEW ONLY</button>
                            </h5>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget19__wrapper">
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="text-align:center;">
                                    <p>
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($course->price) }}</span>&nbsp;&nbsp;&nbsp; 
                                        <!-- <span style="text-decoration: line-through;font-size:18px">&#8369;500</span> &nbsp;&nbsp;&nbsp; 
                                        <span style="font-size:18px">20% off</span> -->
                                    </p>
                                </div>
                            </div>
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="line-height:1.2;">
                                    <p><b>This course includes:</b></p>
                                    <p>
                                        <i class="flaticon-medal kt-font-warning"></i>
                                        <span>Instant CPD Certificate on completion</span>
                                    </p>
                                    <p>
                                        <i class="fa fa-video kt-font-info"></i>
                                        <span>{{ $total["video"] }} of educational videos</span>
                                    </p>
                                    <p>
                                        <i class="flaticon2-list-1 kt-font-success"></i>
                                        <span>{{ $total["quiz"] }} Quiz{{ $total["quiz"] > 1 ? "zes" : "" }}</span>
                                    </p>
                                    <p>
                                        <i class="la la-newspaper-o kt-font-danger"></i>
                                        <span>{{ $total["article"] }} Article{{ $total["article"] > 1 ? "s" : "" }}</span>
                                    </p>
                                    <p>
                                        <i class="la la-file-text"></i>
                                        <span>{{ $total["handout"] }} Downloadable handout{{ $total["handout"] > 1 ? "s" : "" }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PREVIEW MODAL -->
<div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="preview-modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="preview-modal_body"></div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script>
jQuery(document).ready(function () {
    //sticky banner
    $(window).scroll(function () {
        var height = $("#default-screen-banner").innerHeight();
        if ($(this).scrollTop() > height) {
            //if scroll reached end of banner, sticky flag displays

            $("#header").addClass("sticky_header");
            $("#header").show();
            $("#default-screen-banner").hide();
            $("#default-screen-flag").hide();

            if ($(window).width() <= 1020) {
                $("#small-screen-flag").show();
                $("#sticky").hide();
            } else {
                $("#small-screen-flag").hide();
                $("#sticky").show();
            }
        } else if (
            $(this).scrollTop() == 0 &&
            $("#header").hasClass("sticky_header")
        ) {
            //if scrolled up, the black banner will be displayed again
            $("#header").removeClass("sticky_header");
            $("#header").hide();
            $("#default-screen-banner").show();
            $("#sticky").hide();
            $("#default-screen-flag").show(); //CTA panel will be seen in the black banner
        }
    });

    $(".input_coupon").hide();
});

//Apply coupon
$(".apply_coupon").click(function () {
    $(this).hide();
    $(".input_coupon").show();
});

//Expand All or Collapse All
$(".expand-collapse").click(function () {
    if ($(".expand-collapse").html() == "Expand All") {
        $(".expand-collapse").html("Collapse All");
        $(".card-title").addClass("collapsed");
        $(".collapse").addClass("show");
    } else {
        $(".expand-collapse").html("Expand All");
        $(".card-title").removeClass("collapsed");
        $(".collapse").removeClass("show");
    }
});

//Remove maximum height assigned
$(".see-requirements").click(function () {
    $(".footer-requirements").hide();
    $("#section-requirements").removeClass("limit-requirements");
});

$(".see-description").click(function () {
    $(".footer-description").hide();
    $("#section-description").removeClass("limit-description");
});

$(".see-target").click(function () {
    $(".footer-target").hide();
    $("#section-target").removeClass("limit-target");
});

$(".see-sections").click(function () {
    $(".footer").hide();
    $("#section-body").removeClass("limit-size");
});

$(".see-reviews").click(function () {
    $(".footer-reviews").hide();
    $("#section-reviews").removeClass("limit-reviews");
});

$(".see-objectives").click(function () {
    $(".footer-objectives").hide();
    $("#section-objectives").removeClass("limit-objectives");
});

//Check if data reached the maximum height or not
//Requirement
if ($("#section-requirements").height() < 200) {
    $(".footer-requirements").hide();
    $("#section-requirements").removeClass("limit-requirements");
} else {
    $(".footer-requirements").show();
    $("#section-requirements").addClass("limit-requirements");
}

//Description
if ($("#section-description").height() < 500) {
    $(".footer-description").hide();
    $("#section-description").removeClass("limit-description");
} else {
    $(".footer-description").show();
    $("#section-description").addClass("limit-description");
}

//Target student
if ($("#section-target").height() < 200) {
    $(".footer-target").hide();
    $("#section-target").removeClass("limit-target");
} else {
    $(".footer-target").show();
    $("#section-target").addClass("limit-target");
}

//Section
if ($("#section-body").height() < 510) {
    $(".footer").hide();
    $("#section-body").removeClass("limit-size");
} else {
    $(".footer").show();
    $("#section-body").addClass("limit-size");
}

//Reviews
if ($("#section-reviews").height() < 530) {
    $(".footer-reviews").hide();
    $("#section-reviews").removeClass("limit-reviews");
} else {
    $(".footer-reviews").show();
    $("#section-reviews").addClass("limit-reviews");
}

//Objectives
if ($("#section-objectives").height() < 300) {
    $(".footer-objectives").hide();
    $("#section-objectives").removeClass("limit-objectives");
} else {
    $(".footer-objectives").show();
    $("#section-objectives").addClass("limit-objectives");
}


////////////////////////////////////////////////////////COURSE PAGE

var status_ = ["success", "danger", "warning", "info"];
var window_width = $(window).width();
var card_to_break = window_width < 1020 ? 1 : 2;
var card_to_take = window_width < 1020 ? (window_width < 740 ? 1 : 2) : 3;

var provider_course_page = 0;
var provider_course_total_page = 0;
var provider_course_slide = true;

var instructor_course_page = 0;
var instructor_course_total_page = 0;
var instructor_course_slide = true;

var course_instructors_page = 0;
var course_instructors_total_page = 0;
var course_instructors_slide = true;

jQuery(document).ready(function () {
    renderCourseInstructors({
        page: course_instructors_page,
        break: card_to_break,
        take: card_to_take,
        id: "course-instructors",
        slide: course_instructors_slide,
    });

    $(`#course-instructors-carousel-control-prev`).click(function () {
        course_instructors_slide = true;
        course_instructors_page -= 1;

        if (course_instructors_total_page > 1) {
            $(`#course-instructors-carousel-control-next`).show();
        }

        if (course_instructors_page == 0) {
            $(`#course-instructors-carousel-control-prev`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderCourseInstructors({
            page: course_instructors_page,
            break: card_to_break,
            take: card_to_take,
            id: "course-instructors",
            slide: course_instructors_slide,
        });
    });

    $(`#course-instructors-carousel-control-next`).click(function () {
        course_instructors_slide = false;
        course_instructors_page += 1;

        $(`#course-instructors-carousel-control-prev`).show();

        if (course_instructors_page == course_instructors_total_page - 1) {
            $(`#course-instructors-carousel-control-next`).hide();
        }

        /**
         * Submit Ajax
         *
         */
        renderCourseInstructors({
            page: course_instructors_page,
            break: card_to_break,
            take: card_to_take,
            id: "course-instructors",
            slide: course_instructors_slide,
        });
    });
});

function renderCourseInstructors(carousel) {
    $.ajax({
        url: "/api/course/instructor/list",
        data: {
            course: $(`input[name="course_id"]`).val(),
            page: carousel.page,
            take: carousel.take,
        },
        success: function (response) {
            if (response.hasOwnProperty("data")) {
                var data = response.data;
                var total = data.length;

                var num_loop = Math.floor(total / carousel.take);
                var remainder =
                    total < 1
                        ? 0
                        : total < carousel.take
                        ? 0
                        : total % carousel.take;
                var totalPages =
                    (num_loop < 1 ? 1 : num_loop) + (remainder ? 1 : 0);
                course_instructors_total_page = totalPages;

                renderInstructorWithCarousel(
                    { data: data, total: total, totalPages: totalPages },
                    carousel
                );
            }
        },
        error: function (response) {
            console.log("Something went wrong! Please try again later.");
        },
    });
}


function renderInstructorWithCarousel(response, carousel) {
    var data = response.data;

    var inner = $(`#${carousel.id}-carousel-inner`);
    inner
        .hide("slide", { direction: carousel.slide ? "right" : "left" }, 350)
        .html(``);

    var carousel_prev = $(`#${carousel.id}-carousel-control-prev`);
    var carousel_next = $(`#${carousel.id}-carousel-control-next`);

    if (carousel.page == 0) {
        carousel_prev.hide();
    } else {
        carousel_prev.show();
    }
    if (response.totalPages == 1 || response.totalPages - 1 == carousel.page) {
        carousel_next.hide();
    } else {
        carousel_next.show();
    }

    if (data.length == 0) {
        $(`#${carousel.id} > div.centered`).show(
            "slide",
            { direction: carousel.slide ? "right" : "left" },
            350
        );
        return;
    } else {
        $(`#${carousel.id} > div.centered`).hide(
            "slide",
            { direction: carousel.slide ? "right" : "left" },
            350
        );
    }

    var carousel_item = document.createElement("div");
    $(carousel_item)
        .addClass("carousel-item active")
        .attr("id", `${carousel.id}-carousel-item`)
        .html('<div class="row"></div>');
    inner.append(carousel_item);

    var control = 0;
    data.forEach((row, index) => {
        var wrapper = $("<div />");
        var main = $("<div />");

        $(wrapper)
            .addClass("col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12")
            .attr("id", `${carousel.id}-course-card-${row.id}`)
            .appendTo(`div#${carousel.id}-carousel-item > div.row`);

        $(main)
            .addClass("kt-portlet kt-portlet--height-fluid")
            .appendTo(`#${carousel.id}-course-card-${row.id}`);

        if (carousel.break == control) {
            control = 0;
        }

        control++;
        var main_head = $("<div />")
            .addClass("kt-portlet__head kt-portlet__head--noborder")
            .html(
                `<div class="kt-portlet__head-label"><h3 class="kt-portlet__head-title"></h3></div>`
            );

        var random_ = Math.floor(Math.random() * 4);
        var website_link = ``;
        if (row.website) {
            website_link = `<a href="${row.website}" target="_blank" class="btn btn-icon btn-google">\
                            <i class="flaticon2-world"></i>\
                        </a>`;
        }

        var facebook_link = ``;
        if (row.facebook) {
            facebook_link = `<a href="${row.facebook}" target="_blank" class="btn btn-icon btn-facebook">\
                            <i class="socicon-facebook"></i>\
                        </a>`;
        }

        var linkedin_link = ``;
        if (row.linkedin) {
            linkedin_link = `<a href="${row.linked}" target="_blank" class="btn btn-icon btn-twitter">\
                            <i class="socicon-linkedin"></i>\
                        </a>`;
        }

        var main_body = $("<div />").addClass("kt-portlet__body")
            .html(`<div class="kt-widget kt-widget--user-profile-2">
        <div class="kt-widget__head">
            <div class="kt-widget__media">
                ${
                    row.image
                        ? `<img class="kt-widget__img" src="${row.image}" alt="image">`
                        : `<div class="kt-widget__pic kt-widget__pic--${
                              status_[random_]
                          } kt-font-${status_[random_]} kt-font-boldest">${(
                              row.name[0] + row.name[1]
                          ).toUpperCase()}</div>`
                }
            </div>
            <div class="kt-widget__info">
                <a href="javascript:;" class="kt-widget__username">${
                    row.name
                }</a>
                <span class="kt-widget__desc kt-font-bold">Instructor</span>
            </div>
        </div>
        <div class="kt-widget__body">
            <div class="kt-widget__section" style="height:150px;">${
                row.headline ? (row.headline.length > 180 ? (row.headline.substr(0, 180))+'...' : row.headline) : ''
            }</div>
            <div class="kt-widget__item">
                <div class="kt-widget__contact">
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">${row.total_courses.toLocaleString()}</span>&nbsp; &nbsp; Courses</span>
                    <span  style="font-size:1rem;"><span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">${row.total_providers.toLocaleString()}</span>&nbsp; &nbsp; Assoc. Providers</span>
                </div> <br/>
                <div class="kt-widget__action row justify-content-center">${
                    website_link +
                    `&nbsp; &nbsp;` +
                    facebook_link +
                    `&nbsp; &nbsp;` +
                    linkedin_link
                }</div>
            </div>
        </div>
        <div class="kt-widget__footer">
            <button type="button" class="btn btn-label-info btn-lg btn-upper" onclick="toastr.info('This is an Accreditor preview!')">view profile</button>
        </div>
    </div>`);

        main.append(main_head).append(main_body);
        wrapper.append(main);
    });

    $(inner).show(
        "slide",
        { direction: !carousel.slide ? "right" : "left" },
        350
    );
}
</script>

@endsection



