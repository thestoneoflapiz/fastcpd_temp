@extends('template.master_public')

@section('metas')
<meta name="description" content="{{ $course->headline }}">
<meta name="keywords" content="FastCPD,Online,Courses,Webinar,CPD,PRC,{{ $course->url }},{{ $course->name }}">
<meta name="author" content="{{ $course->provider->name }}">
<meta property="og:title" content="{{ $course->title }}" />
<meta property="og:url" content="ol<?=URL::to("/course/{$course->url}")?>" />
<meta property="og:type" content="fastcpd_com:course">
<meta property="og:description" content="{{ $course->headline }}" />
<meta property="og:image" content="{{ $course->course_poster }}" />
<meta property="og:site_name" content="FastCPD">
@endsection

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

    .remain-width{width:350px !important;}
</style>
@endsection
@section('content')
<input type="hidden" value="{{ $course->provider->id ?? 0 }}" name="provider_id"/>
<input type="hidden" value="{{ $course->id ?? 0 }}" name="course_id"/>
<input type="hidden" value="{{ $course->avg_course_rating ?? 0 }}" name="avg_course_rating"/>
<div class="kt-container">
    <!-- Black banner before scroll -->
    <div class="kt-portlet black-banner" id="default-screen-banner">
        <div class="kt-portlet__body">
            <div class="kt-widget kt-widget--user-profile-3 col-12 col-md-8 col-lg-8 col-xl-8">
                <div class="kt-widget__top">
                    <div class="kt-widget__content bottom">
                        <div class="col-12">
                            <div class="kt-widget__head top">
                                <span class="kt-widget__title title white-color">{{ $course->title }}</span>
                            </div>
                            <div class="kt-widget__head headline white-color br">
                            {{ $course->headline }}
                            </div>
                        </div>
                        <div class="kt-font-md third br col-12">
                            @if($course->avg_course_rating>0)
                            <span class="kt-padding-r-10 kt-font-lg avg-course-rating-page"></span><br/>
                            @endif
                            <!-- <span class="white-color kt-padding-r-20 kt-font-lg" id="enrolled">(1,219)</span>  -->
                            @foreach($accreditation as   $acc)
                            <span class="white-color kt-font-lg"><i class="fa fa-check"></i> {{ $acc->title }} <i class="flaticon-medal"></i> CPD Units {{ $acc->units ?? 0 }} &#x25CF; {{ $acc->program_no ?? 0 }}</span>  <br/>
                            @endforeach
                        </div>
                        <div class="kt-font-lg br col-12">
                            <span class="white-color third kt-padding-r-5">Created by</span>
                            <span class="white-color provider kt-padding-r-5">{{ $course->provider->name ?? 'Undefined'}}</span>
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
    <div class="kt-portlet gray-banner big_header" id="header">
        <div class="kt-portlet__body">
            <div class="kt-widget kt-widget--user-profile-3 col-12">
                <div class="kt-widget__top">
                    <div class="kt-widget__content bottom">
                        <div class="kt-widget__head top">
                            <span class="kt-widget__title white-color" style="font-size:2rem;opacity:unset !important;">{{ $course->title }}</span>
                        </div>
                        <div class="kt-font-md col-12">
                            @if($course->avg_course_rating>0)
                            <span class="kt-padding-r-10 kt-font-lg avg-course-rating-page"></span> 
                            @endif
                            <!-- <span class="white-color kt-padding-r-20 kt-font-lg" id="enrolled">(1,219)</span>  -->
                            @foreach($accreditation as $key => $acc)
                            <span class="white-color kt-font-lg">{{ Str::limit($acc->title, 15, "...")}} <i class="flaticon-medal"></i> CPD Units {{ $acc->units ?? 0 }} &#x25CF; {{ $acc->program_no ?? 0 }}</span> <?=count($accreditation) == $key+1 ? '' : '&nbsp; — &nbsp;'?> 
                            @endforeach
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
                                        <div class="col">
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
            <!--begin::Portlet-->
            <div class="kt-portlet ">
                <div class="kt-portlet__body">
                    <div class="kt-infobox">    
                        <div class="kt-infobox__header">
                            <h2 class="kt-infobox__title"> Course Content <a class="btn btn-clean kt-font-brand expand-collapse">Expand All</a> &nbsp;&nbsp;&nbsp;&nbsp;</h2>
                            <div style="margin-left:auto;">
                                <span>
                                <b style="font-size:1.3rem;">{{ $part["parts"] }}</b> Part{{ $part["parts"] > 1 ? "s" : "" }} &nbsp; &#9679; &nbsp; <b style="font-size:1.3rem;"><?=_course_total_video_length($course->id)?></b> 
                                </span>
                            </div>
                        </div>
                        <div class="kt-infobox__body">
                            <!--begin::Accordion-->
                            <div class="accordion accordion-light accordion-toggle-plus">
                                @foreach($sections as $sec)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $sec['id'] }}">
                                        <div class="card-title" data-toggle="collapse" data-target="#collapse{{ $sec['id'] }}" aria-expanded="false" aria-controls="collapse{{ $sec['id'] }}">{{ $sec['name'] }}</div>
                                    </div>
                                    <div id="collapse{{ $sec['id'] }}" class="collapse" aria-labelledby="heading{{ $sec['id'] }}">
                                        <div class="card-body" style="padding-left:2rem;">
                                            @foreach($sec['arranged_parts'] as $seq)
                                            <div class="row">
                                                <div class="kt-align-left col-9 col-sm-9 col-md-10">
                                                    <span>
                                                    @if($seq['type'] == "video")
                                                    <i class="flaticon2-arrow kt-font-bold kt-font-info"></i>
                                                    @elseif($seq['type'] == "quiz")
                                                    <i class="flaticon2-list-1 kt-font-bold kt-font-success"></i>
                                                    @else
                                                    <i class="flaticon2-file kt-font-bold kt-font-danger"></i>
                                                    @endif
                                                    &nbsp;{{ $seq['title'] }}</span>
                                                </div>
                                                <div class="kt-align-right col-3 col-sm-3 col-md-2">
                                                    <span class="kt-font-bold">{{ $seq['minute'] == "video" ? $seq['minute'] : str_replace(".", ":", $seq['minute']) }} {{ $seq['type'] == "quiz" ? ($seq['minute'] > 1 ? "items" : "item") : (str_replace(":", ".", $seq['minute']) > 1 ? "mins" : "min") }}</span>
                                                </div>
                                            </div>
                                            <div class="kt-space-20"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!--end::Accordion-->
                        </div>
                    </div>
                </div>
            </div>

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
                                    <li class="kt-font-lg">{{ $requirement }}</li>
                                @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="kt-portlet__foot footer-requirements">
                    <div class="kt-chat__input kt-align-center">
                        <span class="btn btn-clean btn-label-band see-requirements">See more</span>
                    </div>
                </div>
            </div>
            <!-- end:: Content -->

            <!-- begin:: Content -->
            <div class="kt-portlet">
                <div class="kt-portlet__body" id="section-target">
                    <div class="kt-infobox">
                        <div class="kt-infobox__header">
                            <h2 class="kt-infobox__title">Who is this course for?</h2>
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
                                    <li class="kt-font-lg">{{ $target }}</li>
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
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <div class="kt-infobox">
                        <div class="row">
                            <div class="col-xl-2 col-md-3 col-sm-4 col-12 kt-margin-b-20">
                                <div class="image_container">
                                    <img alt="FastCPD Provider Logo <?=$course->provider->name ?? ""?>" src="{{ $course->provider->logo ?? asset('img/sample/noimage.png') }}" class="image"/>
                                </div>
                            </div> 
                            <div class="col-xl-10 col-md-9 col-sm-8 col-12">
                                <h3 class="kt-font-bolder">{{ $course->provider->name ?? 'Not found' }} <i class="flaticon2-correct kt-font-success"></i></h3>

                                <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">{{ number_format(_provider_courses($course->provider->id, true)) }}</span>
                                <span  style="font-size:1rem;">&nbsp;Courses</span>
                                &nbsp; &nbsp;
                                <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">{{ number_format(_provider_webinars($course->provider->id, true)) }}</span>
                                <span  style="font-size:1rem;">&nbsp;Webinars</span>
                                &nbsp; &nbsp;
                                <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;">{{ number_format(_provider_instructors($course->provider->id, true)) }}</span>
                                <span  style="font-size:1rem;">&nbsp;Instructors</span>
                                <br/>
                                <br/>

                                <p>{{ $course->provider->headline }}</p>    
                                <br/>
                                <br/>
                                <a href="javascript:window.open('/provider/{{ $course->provider->url }}');" class="btn btn-label-info btn-md kt-font-bolder">VIEW PROVIDER</a>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <!-- profile provider::end -->

            <!-- more courses by the provider::start -->
            <div class="row" id="more-course-by-provider" style="display:none;">
                <div class="col-xl-12 col-12">
                    <h3>More courses from {{ $course->provider->name ?? "Not found" }}</h3>
                    <!--begin::Portlet-->
                    <div class="kt-portlet__body" id="provider-courses">                    
                        <div id="provider-courses-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                            <br/>
                            <div id="provider-courses-carousel-inner" class="carousel-inner"></div>
                            <a id="provider-courses-carousel-control-prev" class="carousel-control-prev" href="#provider-courses-carousel-indicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a id="provider-courses-carousel-control-next" class="carousel-control-next" href="#provider-courses-carousel-indicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
            <!-- more courses by the provider::end -->

            @if($instructors->count() > 0)
           
            <div class="row">
                <div class="col-xl-12 col-12">
                    <!--begin::Portlet-->
                    <div class="kt-portlet__body" id="course-instructors" style="display:none;">                    
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

            <!-- more courses by the instructors::start -->
            <div class="row" id="more-course-by-instructors" style="display:none;">
                <div class="col-xl-12 col-12">
                    <h3>More courses from the Instructors</h3>
                    <!--begin::Portlet-->
                    <div class="kt-portlet__body" id="instructor-courses">                    
                        <div id="instructor-courses-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                            <br/>
                            <div id="instructor-courses-carousel-inner" class="carousel-inner"></div>
                            <a id="instructor-courses-carousel-control-prev" class="carousel-control-prev" href="#instructor-courses-carousel-indicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a id="instructor-courses-carousel-control-next" class="carousel-control-next" href="#instructor-courses-carousel-indicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
            <!-- more courses by the instructors::end -->
            @endif

            <!-- Course Review && Feedback::begin -->
            <!-- <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Course Feedback
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-section">
                        <div class="kt-section__content">
                            <div class="row">
                                <div class="col-4 kt-align-center">
                                    <span class="rate-size kt-font-bold" id="rating">4.5</span> <br/>
                                    <span class="course_rating" data-rating="4.5"></span> <br/>
                                    <span class="kt-font-lg">Course Rating</span>
                                </div>
                                <div class="col-8">
                                    <div class="progress progress-lg">
                                        <div class="progress-bar kt-bg-primary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="kt-space-10"></div>
                                    <div class="progress progress-lg">
                                        <div class="progress-bar kt-bg-brand" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="kt-space-10"></div>
                                    <div class="progress progress-lg">
                                        <div class="progress-bar kt-bg-warning" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="kt-space-10"></div>
                                    <div class="progress progress-lg">
                                        <div class="progress-bar m-progress-lg" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Reviews
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body"  id="section-reviews">
                    <div class="kt-widget3">
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kevin Durant
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="4.5"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div><div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kevin Durant
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="4.5"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div><div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kevin Durant
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="4.5"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div><div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kevin Durant
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="4.5"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div><div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kevin Durant
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="4.5"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Lebron King James
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        1 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="3"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.Ut wisi enim ad minim veniam,quis nostrud exerci tation ullamcorper.
                                </p>
                            </div>
                        </div>
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Klay Thompson
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        3 weeks ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="4"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div> 
                        </div>
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kevin Love
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        3 weeks ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="2"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{ asset('img/system/woman.png') }}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="#" class="kt-widget3__username">
                                        Kyrie Irving
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        3 weeks ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info course_rating" data-rating="3.5"></span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot footer-reviews">
                    <div class="kt-chat__input kt-align-center">
                        <span class="btn btn-clean btn-label-band see-reviews">See more</span>
                    </div>
                </div>
            </div> -->
            <!-- Course Review && Feedback::begin -->
        </div>

        <div class="col-12 col-md-4 col-lg-4 col-xl-3 cta_z_index panel_top" id="default-screen-flag">
            <div class="kt-portlet">
                <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                    <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill course_image">
                        <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides course-poster-img" style="text-align:center; min-height: 200px; border:5px solid white; background-position:center;background-image: url({{ $course->course_poster }})">
                            <div class="kt-widget19__shadow"></div>
                            <h5 class="kt-widget19__info kt-font-light" onclick="previewCourse()">
                                <i class="fa fa-play" style="font-size:2rem;"></i> <br>
                                Preview this course
                            </h5>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget19__wrapper">
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="text-align:center;" id="course-total-div1">
                                    <p>
                                        @if($slots["remaining"]>0)remaining slots available:&nbsp;<b><?=$slots["remaining"]?></b></br>@endif
                                        @if($course->discount)
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ $course->discounted_price }}</span>&nbsp;&nbsp;
                                        <span style="text-decoration: line-through;font-size:25px">&#8369;{{ number_format($course->price, 2, '.', ',') }}</span><br/>
                                        <span style="font-size:18px;color:#961f45;" class="kt-font-bold">{{ $course->discount["discount"] }}% off</span>
                                        @else
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($course->price, 2, '.', ',') }}</span>&nbsp;&nbsp;
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(_can_view_live("course", $course->id))
                            <div class="kt-widget19__content" style="margin-top:-30px;">
                                <div class="kt-widget19__info">
                                    <button type="button" class="btn btn-info btn-lg" onclick="window.location='/course/live/{{ $course->url }}'">Go to Live Course</a>
                                </div>
                            </div>
                            @else
                            <div class="kt-widget19__content" style="margin-top:-30px;">
                                <div class="kt-widget19__info">
                                    <button type="button" class="btn btn-<?=$add_to_cart_button["status"] ?> btn-lg" style="margin-bottom:10px;" onclick="<?=$add_to_cart_button["link"] ? "window.location='{$add_to_cart_button["link"]}'" : "addToCart({type: 'course', data_id: {$course->id}}, this)"?>" disabled="disabled">{{ $add_to_cart_button["label"] }}</button>
                                    @if($add_to_cart_button["status"]=="success")
                                    <button type="button" class="btn btn-secondary btn-lg" id="buy-now1" onclick="buyNow(<?=$course->id?>)" disabled="disabled">Buy Now</a>
                                    @endif
                                </div>
                            </div>
                                @if($add_to_cart_button["status"]!="warning")
                                <div class="kt-widget19__content">
                                    <div class="kt-widget19__info">
                                        <div class="input-group input_coupon">
                                            <input type="text" class="form-control" name="apply_voucher_code1" placeholder="Enter Voucher">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="button" id="apply-voucher-button1" onclick="addVoucher(<?=$course->id?>, $('[name=\'apply_voucher_code1\']').val())">Apply</button>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="kt-align-left" id="applied-voucher-list1"></div>
                                    </div>
                                </div>
                                @endif
                            @endif
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
            <div class="kt-portlet sticky remain-width" data-sticky="true" data-margin-top="100px" data-sticky-for="800" data-sticky-class="kt-sticky">
                <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                    <div class="kt-portlet__body">
                        <div class="kt-widget19__wrapper">
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="text-align:center;" id="course-total-div2">
                                    <p>
                                        @if($slots["remaining"]>0)remaining slots available:&nbsp;<b><?=$slots["remaining"]?></b></br>@endif
                                        @if($course->discount)
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ $course->discounted_price }}</span>&nbsp;&nbsp;
                                        <span style="text-decoration: line-through;font-size:25px">&#8369;{{ number_format($course->price, 2, '.', ',') }}</span><br/>
                                        <span style="font-size:18px;color:#961f45;" class="kt-font-bold">{{ $course->discount["discount"] }}% off</span>
                                        @else
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($course->price, 2, '.', ',') }}</span>&nbsp;&nbsp;
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(_can_view_live("course", $course->id))
                            <div class="kt-widget19__content" style="margin-top:-30px;">
                                <div class="kt-widget19__info">
                                    <button type="button" class="btn btn-info btn-lg" onclick="window.location='/course/live/{{ $course->url }}'">Go to Live Course</a>
                                </div>
                            </div>
                            @else
                            <div class="kt-widget19__content" style="margin-top:-30px;">
                                <div class="kt-widget19__info">
                                    <button type="button" class="btn btn-<?=$add_to_cart_button["status"] ?> btn-lg" style="margin-bottom:10px;" onclick="<?=$add_to_cart_button["link"] ? "window.location='{$add_to_cart_button["link"]}'" : "addToCart({type: 'course', data_id: {$course->id}}, this)"?>" disabled="disabled">{{ $add_to_cart_button["label"] }}</button>
                                    @if($add_to_cart_button["status"]=="success")<button type="button" class="btn btn-secondary btn-lg" id="buy-now2" onclick="buyNow(<?=$course->id?>)" disabled="disabled">Buy Now</a>@endif  
                                </div>
                            </div>
                                @if($add_to_cart_button["status"]!="warning")
                                <div class="kt-widget19__content">
                                    <div class="kt-widget19__info">
                                        <div class="input-group input_coupon">
                                            <input type="text" class="form-control" name="apply_voucher_code2" placeholder="Enter Voucher">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="button" id="apply-voucher-button2" onclick="addVoucher(<?=$course->id?>, $('[name=\'apply_voucher_code2\']').val())">Apply</button>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="kt-align-left" id="applied-voucher-list2"></div>
                                    </div>
                                </div>
                                @endif
                            @endif
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
                        <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides course-poster-img" style="text-align:center; min-height: 200px; border:5px solid white;background-position:center;background-image: url({{ $course->course_poster }})">
                            <div class="kt-widget19__shadow"></div>
                            <h5 class="kt-widget19__info kt-font-light" onclick="previewCourse()">
                                <i class="fa fa-play" style="font-size:2rem;"></i> <br>
                                Preview this course
                            </h5>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget19__wrapper">
                            <div class="kt-widget19__content">
                                <div class="kt-widget19__info" style="text-align:center;" id="course-total-div3">
                                    <p>
                                        @if($slots["remaining"]>0)remaining slots available:&nbsp;<b><?=$slots["remaining"]?></b></br>@endif
                                        @if($course->discount)
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ $course->discounted_price }}</span>&nbsp;&nbsp;
                                        <span style="text-decoration: line-through;font-size:25px">&#8369;{{ number_format($course->price, 2, '.', ',') }}</span><br/>
                                        <span style="font-size:18px;color:#961f45;" class="kt-font-bold">{{ $course->discount["discount"] }}% off</span>
                                        @else
                                        <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($course->price, 2, '.', ',') }}</span>&nbsp;&nbsp;
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(_can_view_live("course", $course->id))
                            <div class="kt-widget19__content" style="margin-top:-30px;">
                                <div class="kt-widget19__info">
                                    <button type="button" class="btn btn-info btn-lg" onclick="window.location='/course/live/{{ $course->url }}'">Go to Live Course</a>
                                </div>
                            </div>
                            @else
                            <div class="kt-widget19__content" style="margin-top:-30px;">
                                <div class="kt-widget19__info">
                                    <button type="button" class="btn btn-<?=$add_to_cart_button["status"] ?> btn-lg" style="margin-bottom:10px;" onclick="<?=$add_to_cart_button["link"] ? "window.location='{$add_to_cart_button["link"]}'" : "addToCart({type: 'course', data_id: {$course->id}}, this)"?>" disabled="disabled">{{ $add_to_cart_button["label"] }}</button>
                                    @if($add_to_cart_button["status"]=="success")<button type="button" class="btn btn-secondary btn-lg" id="buy-now3" onclick="buyNow(<?=$course->id?>)" disabled="disabled">Buy Now</a>@endif
                                </div>
                            </div>
                                @if($add_to_cart_button["status"]!="warning")
                                <div class="kt-widget19__content">
                                    <div class="kt-widget19__info">
                                        <div class="input-group input_coupon">
                                            <input type="text" class="form-control" name="apply_voucher_code3" placeholder="Enter Voucher">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="button" id="apply-voucher-button3" onclick="addVoucher(<?=$course->id?>, $('[name=\'apply_voucher_code3\']').val())">Apply</button>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="kt-align-left" id="applied-voucher-list3"></div>
                                    </div>
                                </div>
                                @endif
                            @endif
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
<script src="{{asset('js/course/page.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course/preview-course.js')}}" type="text/javascript"></script>
<script src="{{asset('js/course-card/course-page.js')}}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        var target_audience = <?=$course->target_students ?? []?>;
        target_audience.push("Anyone is encouraged to take this course");
        var people_involved = ["<?=$course->provider->name ?? "FastCPD"?>"];

        var el = document.createElement('script');
        el.type = 'application/ld+json';
        el.text = JSON.stringify([
            {
                "publisher":{
                    "sameAs":"www.fastcpd.com",
                    "@type":"Organization",
                    "name":"FastCPD"
                },
                "@type":"Course",
                "headline":"<?=$course->headline?>",
                "description":"<?=strip_tags($course->description)?>",
                "numberOfCredits":6,
                "educationalUse":[
                    "Continuing Professional Development",
                    "CPD"
                ],
                "occupationalCredentialAwarded":"CPD Units",
                "offers":{
                    "@type":"Offer",
                    "price":<?=$course->discount ? $course->discounted_price : $course->price?>,
                    "priceCurrency":"PHP",
                    "offeredBy":"<?=$course->provider->name ?? "FastCPD"?>",
                    "availability":"https://schema.org/OnlineOnly",
                    "validFrom":"<?=date("Y-m-d", strtotime($course->session_start))?>",
                    "url":"<?=URL::to("/course/{$course->url}")?>"
                },
                "provider":{
                    "@type":"Person",
                    "name":"<?=$course->provider->name?>",
                    "sameAs":[
                        "<?=URL::to("/provider/{$course->provider->url}")?>",
                        "<?=$course->provider->website?>",
                        "<?=$course->provider->facebook?>",
                        "<?=$course->provider->linkedin?>"
                    ],
                    "url":"<?=URL::to("/provider/{$course->provider->url}")?>"
                },
                "name":"<?=$course->title?>",
                "audience":{
                    "@type":"Audience",
                    "audienceType": target_audience
                },
                "@id":"<?=URL::to("/provider/{$course->provider->url}")?>",
                "inLanguage":"en",
                "creator": people_involved,
                "image":"<?=$course->course_poster?>",
                "isAccessibleForFree":false,
                "@context":"https://schema.org"
            },
            {
                "@context":"https://schema.org",
                "@type":"Product",
                "description":"<?=strip_tags($course->description)?>",
                "name":"<?=$course->title?>",
                "image":"<?=$course->course_poster?>",
                "sku":"<?=$course->id?>-<?=date("Y-m-d", strtotime($course->published_at))?>",
                "brand":{
                    "@type":"Brand",
                    "slogan":"The Best and Convenient Way to Earn CPD Units",
                    "description":"FastCPD is an online marketplace where PRC-accredited program providers can create and publish online courses to offer professionals in the Philippines. It is the easiest and fastest way to learn topics in a professional field while earning CPD units to renew professional licenses",
                    "logo":"https://www.fastcpd.com/img/system/icon-1.png",
                    "name":"FastCPD"
                },
                "offers":{
                    "@type":"Offer",
                    "price": <?=$course->discount ? $course->discounted_price : $course->price?>,
                    "priceCurrency":"PHP",
                    "offeredBy":{
                        "sameAs":"www.fastcpd.com",
                        "name":"<?=$course->provider->name ?? "FastCPD" ?>"
                    },
                    "seller":{
                        "sameAs":"www.fastcpd.com",
                        "name":"FastCPD"
                    },
                    "availability":"https://schema.org/OnlineOnly",
                    "validFrom":"<?=date("Y-m-d", strtotime($course->session_start))?>",
                    "priceValidUntil":"<?=date("Y-m-d", strtotime($course->session_end))?>",
                    "url":"<?=URL::to("/course/{$course->url}")?>"
                }
                ,
                "aggregateRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"<?=$course->avg_course_rating?>",
                    // "reviewCount":"100"
                }
            },
            {
                "@context":"https://schema.org",
                "@type":"VideoObject",
                "name":"<?=$course->title?>'s Promotional Video ",
                "description":"<?=$course->headline?>",
                "thumbnailUrl":[
                    "https://www.fastcpd.com/img/system/icon-1.png",
                    "<?=$course->course_poster?>",
                    "<?=$course->course_poster?>"
                ],
                "uploadDate":"<?=$course->published_at ?? $course->updated_at?>",
                "contentUrl":"<?=$course->course_video?>",
                "interactionStatistic":{
                    "@type":"InteractionCounter",
                    "interactionType":{
                        "@type":"https://schema.org/WatchAction"
                    },
                }
            },
            {
                "@context": "https://schema.org/", 
                "@type": "BreadcrumbList", 
                "itemListElement": [{
                    "@type": "ListItem", 
                    "position": 1, 
                    "name": "FastCPD Home",
                    "item": "<?=URL::to("/")?>"  
                }, {
                    "@type": "ListItem", 
                    "position": 2, 
                    "name": "<?=$course->title?>",
                    "item": "<?=URL::to("/course/{$course->url}")?>"  
                }]
            }
        ]);
        document.querySelector('head').appendChild(el);
    });
</script>
@endsection

