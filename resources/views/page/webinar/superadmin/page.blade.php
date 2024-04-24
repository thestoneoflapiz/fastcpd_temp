@extends('template.webinar.master_page')
@section('title', 'Webinar Page')
@section("styles")
<link href="{{asset('css/course-cards-popover.css')}}" rel="stylesheet" type="text/css" />
<style>
    .banner-wrapper{width:100%;margin:auto 0;color:white;}
    .grey-bar{margin-left:10vw;}
    /* .banner-wrapper{width:100%;margin:auto 0 50px 0;color:white;text-shadow: 0.5px 0.5px 0.5px rgba(0,0,0,0.5)} */
    .poster-video{position:relative;height:290px;width:450px;border:3px solid white;border-radius:5px;box-shadow: rgba(0,0,0,0.5) 0px 5px 15px;background-color:black;background-repeat: no-repeat;background-size: cover;background-position:center;background-image: url(<?=$posters->small_size ?? $webinar->webinar_poster?>);}
    .poster-video > h5{position:absolute;text-align:center;font-weight:600;left:0;right:0;bottom:0;cursor:pointer;text-shadow: 0.5px 0.5px 10px rgba(0,0,0,0.5)}
   
    .hero-banner{width:100%;min-height:600px;margin:0;background-color:black;background-repeat: no-repeat;background-size: cover;background-position:center;background-image: url({{ asset('media/bg/bg-5.jpg') }});}
    @media (max-width: 1080px) {
        /* .hero-banner{height:250px;} */
        .poster-video{height:200px;width:330px;}
        .cta{display:none;}
    }
    @media (max-width: 750px) { 
        .grey-bar{margin-left:10px;}
    }

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
    
    .black-banner{position:fixed;z-index:1;width:100%;margin:0;padding:10px;background-color:rgba(23, 40, 48, 0.9) !important;}
    .cta{position:fixed;top:20;right:10;z-index:2!important;transition: all 0.3s ease;}

    .calendar-wrap{background-color:white;width:80px;height:70px;border-radius:3px;text-align:center;display:inline-block;}
    .calendar-month{font-weight:700;font-size:0.9rem;padding:3px;border-top-left-radius:2px;border-top-right-radius:2px;background-color:#0d2c44;width:100%;height:20px;}
    .calendar-date{font-weight:700;font-size:3rem;color:#0d2c44;margin:auto;}
    .calendar-day{font-weight:700;font-size:1rem;}
    .calendar-full{font-weight:700;font-size:1.5rem;}
    .calendar-time{font-size:1rem;border-top:2px solid rgba(255,255,255,0.5);padding-top:3px;}

    .preview-course-video{width:100%;}
    .preview-webinar-video{width:100%;}
    body{overflow:scroll;}

    .accordion.accordion-toggle-plus .card .card-header .card-title{color:#646c9a !important;}
    .kt-infobox .kt-infobox__body .accordion .card .card-body{color:#646c9a !important;font-size:1rem;}
    
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}
</style>
@endsection

@section('content')
<input type="hidden" name="webinar_id" value="<?=$webinar->id?>"/>
<input type="hidden" name="provider_id" value="<?=$provider->id?>"/>
<div class="row black-banner kt-hidden">
    <div class="banner-wrapper">
        <div class="row">
            <div class="col">
                <div class="grey-bar">
                    <h3 class="kt-font-light"><?=$webinar->title?></h3>
                    @if($accreditation)
                    <span class="kt-font-light">
                        @foreach($accreditation as $acc)
                        <i class="fa fa-check"></i> <?=$acc["profession"]?> <i class="flaticon-medal"></i> CPD UNITS <?=$acc["units"]?>  &#x25CF; <?=$acc["program_no"]?> <br/>
                        @endforeach
                    <span>
                    @endif
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-4 cta kt-hidden">
                <div class="kt-portlet" style="color:#6c757d !important;box-shadow:#0000005c 1px 1px 10px">
                    <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                        <div class="kt-portlet__body">
                            <div class="kt-widget19__wrapper">
                                <div class="kt-widget19__content">
                                    <div class="kt-widget19__info" style="text-align:center;" id="webinar-total-div3">
                                        <p>
                                            @if($webinar->offering_units=="both")
                                            <span style="font-weight:bold;font-size:32px;"> 
                                                <?=$webinar->prices->with < $webinar->prices->without ? 
                                                "&#8369;".number_format($webinar->prices->with, 2, '.', ',')." — ".($webinar->prices->without > 0 ?  "&#8369;".number_format($webinar->prices->without, 2, '.', ',')  : "FREE") 
                                                : ($webinar->prices->without > 0 ?  "&#8369;".number_format($webinar->prices->without, 2, '.', ',')  : "FREE")." — &#8369;".number_format($webinar->prices->with, 2, '.', ',')?>
                                            </span><br>
                                            @elseif($webinar->offering_units=="with")
                                            <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($webinar->prices->with, 2, '.', ',') }}</span>&nbsp;&nbsp;
                                            @else
                                            <span style="font-weight:bold;font-size:32px;"><?=$webinar->prices->without > 0 ?  "&#8369;".number_format($webinar->prices->without, 2, '.', ',')  : "FREE"?></span>&nbsp;&nbsp;
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="kt-widget19__content">
                                    <div class="kt-widget19__info">
                                        <button class="btn-label-warning btn btn-lg">FOR PROFESSIONAL VIEW ONLY</button>
                                    </div>
                                </div>
                                <div class="kt-widget19__content">
                                    <div class="kt-widget19__info" style="line-height:1.2;">
                                        <p><b>This webinar includes:</b></p>
                                        <p>
                                            <i class="flaticon-medal kt-font-warning"></i>
                                            <span>Instant CPD Certificate on completion</span>
                                        </p>
                                        <p>
                                            <i class="flaticon2-list-1 kt-font-success"></i>
                                            <span><?=$total["quiz"]?> quiz<?=$total["quiz"] > 1 ? "zes" : ""?></span>
                                        </p>
                                        <p>
                                            <i class="la la-newspaper-o kt-font-danger"></i>
                                            <span><?=$total["article"]?> article<?=$total["article"] > 1 ? "s" : ""?></span>
                                        </p>
                                        <p>
                                            <i class="la la-file-text"></i>
                                            <span><?=$total["handout"]?> handout<?=$total["handout"] > 1 ? "s" : ""?></span>
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
</div>
<div class="row hero-banner">
    <div class="banner-wrapper">
        <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-6 col-md-10">
                        <h1><?=$webinar->title?></h1>
                        <p><?=$webinar->headline?></p>
                        @if($accreditation)
                        <p class="kt-font-light kt-font-lg">
                            @foreach($accreditation as $acc)
                            <i class="fa fa-check"></i> <?=$acc["profession"]?> <i class="flaticon-medal"></i> CPD UNITS <?=$acc["units"]?>  &#x25CF; <?=$acc["program_no"]?> <br/>
                            @endforeach
                        <p>
                        @endif
                        @if($schedule)
                            @if($webinar->event=="day")
                            <div class="row">
                                <div class="col-xl-10 col-lg-10 col-md-9 kt-margin-b-15 schedule-display">
                                    <div class="calendar-wrap kt-margin-r-10">
                                        <div class="calendar-month"><?=date("F", strtotime($schedule[0]["session_date"]))?></div>
                                        <span class="calendar-date"><?=date("d", strtotime($schedule[0]["session_date"]))?></span>
                                    </div>
                                    <div style="display:inline-block;">
                                        <div class="calendar-day"><?=date("l", strtotime($schedule[0]["session_date"]))?></div>
                                        <div class="calendar-full"><?=$schedule[0]["session_date"]?></div>
                                        <div class="calendar-time"><i class="fa fa-clock"></i> <?=$schedule[0]["sessions"][0]->start?> to <?=$schedule[0]["sessions"][0]->end?></div>
                                    </div>
                                </div>
                                <div class="col-xl-10 col-lg-10 col-md-9">
                                    <select class="form-control kt-select2" id="schedule" name="schedule" style="width:100%;">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-xl-10 col-lg-10 col-md-9 kt-margin-b-15 schedule-display">
                                    @foreach($schedule as $sess)
                                    <div class="calendar-wrap kt-margin-r-10 kt-margin-5">
                                        <div class="calendar-month"><?=date("F", strtotime($sess["session_date"]))?></div>
                                        <span class="calendar-date"><?=date("d", strtotime($sess["session_date"]))?></span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-xl-10 col-lg-10 col-md-9">
                                    <select class="form-control kt-select2" id="schedule" name="schedule" style="width:100%;">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            @endif
                        @endif
                        <div class="kt-space-40"></div>
                        <div class="row">
                            <div class="col-12">
                                @if($webinar->offering_units=="both")
                                <span style="font-weight:bold;font-size:32px;"> 
                                    <?=$webinar->prices->with < $webinar->prices->without ? 
                                    "&#8369;".number_format($webinar->prices->with, 2, '.', ',')." — ".($webinar->prices->without > 0 ?  "&#8369;".number_format($webinar->prices->without, 2, '.', ',')  : "FREE") 
                                    : ($webinar->prices->without > 0 ?  "&#8369;".number_format($webinar->prices->without, 2, '.', ',')  : "FREE")." — &#8369;".number_format($webinar->prices->with, 2, '.', ',')?>
                                </span>&nbsp;&nbsp;
                                @elseif($webinar->offering_units=="with")
                                <span style="font-weight:bold;font-size:32px;">&#8369;{{ number_format($webinar->prices->with, 2, '.', ',') }}</span>&nbsp;&nbsp;
                                @else
                                <span style="font-weight:bold;font-size:32px;"><?=$webinar->prices->without > 0 ?  "&#8369;".number_format($webinar->prices->without, 2, '.', ',')  : "FREE"?></span>&nbsp;&nbsp;
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 kt-margin-b-10">
                                <button class="btn-label-warning btn btn-lg">FOR PROFESSIONAL VIEW ONLY</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-4 col-9">
                        <div class="kt-space-40"></div>
                        <div class="poster-video">
                            <h5 class="kt-widget19__info kt-font-light" onclick="previewWebinar()">
                                <i class="fa fa-play" style="font-size:2rem;"></i> <br>
                                Preview this webinar
                            </h5>
                        </div>
                        <div class="kt-space-20"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row justify-content-start">
            <div class="col-1"></div>
            <div class="col-xl-8 col-lg-10 col-md-10">
                <div class="kt-portlet">
                    <div class="kt-portlet__body" id="section-objectives">
                        <div class="kt-infobox">
                            <div class="kt-infobox__header">
                                <h2 class="kt-infobox__title">What you'll learn?</h2>
                            </div>
                            <div class="kt-infobox__body">
                                <div class="row">
                                    @if($objectives = $webinar->objectives)
                                    @foreach(json_decode($objectives) as $obj)
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="row">
                                            <div class="col">
                                                <i class="flaticon2-check-mark"></i> &nbsp;&nbsp;
                                            </div>
                                            <div class="col-11">
                                                <span style="text-indent:1em;"><?=$obj?></span>
                                            </div>
                                        </div>
                                        <div class="kt-space-10"></div>
                                    </div>
                                    @endforeach
                                    @endif
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
                                <p><?=html_entity_decode($webinar->description) ?? "No Description yet"?></p>
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
                @if($webinar->event=="day")
                <div class="kt-portlet ">
                    <div class="kt-portlet__body">
                        <div class="kt-infobox">    
                            <div class="kt-infobox__header">
                                <h2 class="kt-infobox__title"> Webinar Content <a class="btn btn-clean kt-font-brand expand-collapse">Expand All</a> &nbsp;&nbsp;&nbsp;&nbsp;</h2>
                                <div style="margin-left:auto;">
                                    <span>
                                    <b style="font-size:1.3rem;"><?=$sections->number_of_parts?></b> Part<?=$sections->number_of_parts > 1 ? "s" : ""?> &nbsp;</b> 
                                    </span>
                                </div>
                            </div>
                            <div class="kt-infobox__body">
                                <!--begin::Accordion-->
                                <div class="accordion accordion-light accordion-toggle-plus">
                                    <div class="card">
                                        <div class="card-header" id="heading1">
                                            <div class="card-title" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1"><?=$sections->name?></div>
                                        </div>
                                        <div id="collapse1" class="collapse" aria-labelledby="heading1">
                                            <div class="card-body" style="padding-left:2rem;">
                                                @foreach($sections->arranged_parts as $part)
                                                <div class="row">
                                                    <div class="kt-align-left col-9 col-sm-9 col-md-10">
                                                        <span>
                                                        <i class="<?=$part["type"]=="quiz" ? "flaticon2-list-1 kt-font-success" : "flaticon2-file kt-font-danger"?> kt-font-bold"></i>
                                                        &nbsp; <?=ucwords($part["title"])?>
                                                        </span>
                                                    </div>
                                                    <div class="kt-align-right col-3 col-sm-3 col-md-2">
                                                        <span class="kt-font-bold">
                                                            @if($part["type"]=="quiz")
                                                            <?=$part["minute"]?> item<?=$part["minute"] > 1 ? "s" : ""?> 
                                                            @else
                                                            <?=$part["minute"]?> min<?=$part["minute"] > 1 ? "s" : ""?> read
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="kt-space-20"></div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Accordion-->
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="kt-portlet ">
                    <div class="kt-portlet__body">
                        <div class="kt-infobox">    
                            <div class="kt-infobox__header">
                                <h2 class="kt-infobox__title">  Webinar Content <a class="btn btn-clean kt-font-brand expand-collapse">Expand All</a> &nbsp;&nbsp;&nbsp;&nbsp;</h2>
                                <div style="margin-left:auto;">
                                    <span>
                                    <b style="font-size:1.3rem;"><?=$sections["total_parts"]?></b> Part<?=$sections["total_parts"] > 1 ? "s" : ""?></b> 
                                    </span>
                                </div>
                            </div>
                            <div class="kt-infobox__body">
                                <!--begin::Accordion-->
                                <div class="accordion accordion-light accordion-toggle-plus">
                                    @foreach($sections["data"] as $section)
                                    <div class="card">
                                        <div class="card-header" id="heading1">
                                            <div class="card-title" data-toggle="collapse" data-target="#collapse<?=$section->id?>" aria-expanded="false" aria-controls="collapse<?=$section->id?>"><?=$section->name?></div>
                                        </div>
                                        <div id="collapse<?=$section->id?>" class="collapse" aria-labelledby="heading1">
                                            <div class="card-body" style="padding-left:2rem;">
                                                @foreach($section->arranged_parts as $part)
                                                <div class="row">
                                                    <div class="kt-align-left col-9 col-sm-9 col-md-10">
                                                        <span>
                                                        <i class="<?=$part["type"]=="quiz" ? "flaticon2-list-1 kt-font-success" : "flaticon2-file kt-font-danger"?> kt-font-bold"></i>
                                                        &nbsp; <?=ucwords($part["title"])?>
                                                        </span>
                                                    </div>
                                                    <div class="kt-align-right col-3 col-sm-3 col-md-2">
                                                        <span class="kt-font-bold">
                                                            @if($part["type"]=="quiz")
                                                            <?=$part["minute"]?> item<?=$part["minute"] > 1 ? "s" : ""?> 
                                                            @else
                                                            <?=$part["minute"]?> min<?=$part["minute"] > 1 ? "s" : ""?> read
                                                            @endif
                                                        </span>
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
                @endif

                <div class="kt-portlet">
                    <div class="kt-portlet__body" id="section-requirements">
                        <div class="kt-infobox">
                            <div class="kt-infobox__header">
                                <h2 class="kt-infobox__title">Requirements</h2>
                            </div>
                            <div class="kt-infobox__body">
                                <div class="kt-infobox__section">
                                    <ul>
                                        @if($requirements = $webinar->requirements)
                                        @foreach(json_decode($requirements) as $req)
                                        <li class="kt-font-lg"><?=$req?></li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
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
                                <h2 class="kt-infobox__title">Who is this webinar for?</h2>
                            </div>
                            <div class="kt-infobox__body">
                                <div class="kt-infobox__section">
                                    <ul>
                                        @if($target_students = $webinar->target_students)
                                        @foreach(json_decode($target_students) as $ts)
                                        <li class="kt-font-lg"><?=$ts?></li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot footer-target">
                        <div class="kt-chat__input kt-align-center">
                            <span class="btn btn-clean btn-label-band see-target">See more</span>
                        </div>
                    </div>
                </div>
                <!-- end:: Content -->
            
                @if($webinar->provider_id)
                <!-- profile provider::start -->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-infobox">
                            <div class="row">
                                <div class="col-xl-2 col-md-3 col-sm-4 col-12 kt-margin-b-20">
                                    <div class="image_container">
                                        <img alt="FastCPD Provider Logo <?=$provider->name?>" src="<?=$provider->logo?>" class="image"/>
                                    </div>
                                </div> 
                                <div class="col-xl-10 col-md-9 col-sm-8 col-12">
                                    <h3 class="kt-font-bolder"><?=$provider->name?> @if($provider->status=="approved")<i class="flaticon2-correct kt-font-success"></i>@endif</h3>

                                    <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;"><?=$provider->course_total?></span>
                                    <span  style="font-size:1rem;">&nbsp;Course<?=$provider->course_total > 1 ? "s" : ""?></span>
                                    &nbsp; &nbsp;
                                    <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;"><?=$provider->webinar_total?></span>
                                    <span  style="font-size:1rem;">&nbsp;Webinar<?=$provider->webinar_total > 1 ? "s" : ""?></span>
                                    &nbsp; &nbsp;
                                    <span class="kt-font-bolder kt-font-info" style="font-size:1.7rem;"><?=$provider->instructor_total?></span>
                                    <span  style="font-size:1rem;">&nbsp;Instructor<?=$provider->instructor_total > 1 ? "s" : ""?></span>
                                    <br/>
                                    <br/>
                                    <p><?=$provider->headline?></p>    
                                    <br/>
                                    <br/>
                                    <a href="/provider/<?=$provider->url?>" class="btn btn-label-info btn-md kt-font-bolder">VIEW PROVIDER</a>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <!-- profile provider::end -->
                @endif
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
@if($webinar->event == "day")
<script>
    var schedule = <?=json_encode($schedule)?>;
    jQuery(document).ready(function(){
        $('[name="schedule"], [name="schedule_cart"]').select2({
            width: '100%',
            placeholder: 'Please select a schedule',
            data: schedule,
            templateResult: function(state){
                if(!state.id){
                    return state.text;
                }

                var time_string = ``;
                state.sessions.forEach(sess => {
                    time_string += `<br/><i class="fa fa-clock"></i> ${sess.start} to ${sess.end}`;
                });
                
                return `<i class="fa fa-calendar-day"></i> &nbsp; <b>${state.session_date}</b>${time_string}`;
            },
            templateSelection: function(state){
                if(!state.id){
                    return state.text;
                }
                
                return `<i class="fa fa-calendar-day"></i> &nbsp; ${state.session_date}`;
            },
            escapeMarkup: function (markup) { return markup; },
        });
    });

    $('[name="schedule"], [name="schedule_cart"]').change(function(){
        var index = $(this).val();
        var data = schedule.find(val => val.id == index);
        if(data){
            var date_ = new Date(data.session_date);
            var schedule_display = $(".schedule-display");
            schedule_display.find(".calendar-month").html(`${string_months[date_.getMonth()+1]}`);
            schedule_display.find(".calendar-date").html(`${date_.getDate() > 9 ? date_.getDate() : '0'+date_.getDate()}`);
            schedule_display.find(".calendar-day").html(`${string_days[date_.getDay()]}`);
            schedule_display.find(".calendar-full").html(`${data.session_date}`);
            schedule_display.find(".calendar-time").html(`<i class="fa fa-clock"></i> ${data.sessions[0].start} to ${data.sessions[0].end}`);
        }
    });
</script>
@else
<script>
    var schedule = <?=json_encode($schedules)?>;
    jQuery(document).ready(function(){
        $('[name="schedule"], [name="schedule_cart"]').select2({
            width: '100%',
            placeholder: 'Please select a schedule',
            data: schedule,
            templateResult: function(state){
                if(!state.id){
                    return state.text;
                }

                var date_string = ``;
                state.sessions.forEach(sched => {
                    var time_string = ``;
                    sched.sessions.forEach(sess => {
                        time_string += `<br/>&nbsp; &nbsp; <i class="fa fa-clock"></i> ${sess.start} to ${sess.end}`;
                    });

                    date_string += `<br/><div>&nbsp; &nbsp; <i class="fa fa-calendar-day"></i> &nbsp; <b>${sched.session_date}</b>${time_string}</div>`;
                });

                return `<i class="fa fa-pencil-alt"></i> &nbsp; Webinar Series #${state.series_order}${date_string}`;
            },
            templateSelection: function(state){
                if(!state.id){
                    return state.text;
                }

                return `<i class="fa fa-pencil-alt"></i> &nbsp; Webinar Series #${state.series_order}`;
            },
            escapeMarkup: function (markup) { return markup; },
        });
    });

    $('[name="schedule"], [name="schedule_cart"]').change(function(){
        var index = $(this).val();
        var series = schedule.find(val => val.id == index);
        if(series){
            var schedule_display = $(".schedule-display").empty();
            series.sessions.forEach(data => {
                var date_ = new Date(data.session_date);
                schedule_display.append(`
                    <div class="calendar-wrap kt-margin-r-10 kt-margin-5">
                        <div class="calendar-month">${string_months[date_.getMonth()+1]}</div>
                        <span class="calendar-date">${date_.getDate() > 9 ? date_.getDate() : '0'+date_.getDate()}</span>
                    </div>
                `);
            });
        }
    });
</script>
@endif
<script>
    function previewWebinar() {
        $(`#preview-modal`).modal("show");

        var exist = $(`#preview-modal_body video`);
        if (exist.length == 0) {
            $(`#preview-modal_body`).empty()
                .append(`<video id="preview-video" class="preview-course-video" poster="<?=$webinar->webinar_poster?>" controls>
                    <source src="<?=$webinar->webinar_video?>"/>
                </video>`);
            $('#preview-video').bind('contextmenu',function() { return false; });
        }
    }
</script>
<script src="{{asset('js/webinar/page/page.js')}}" type="text/javascript"></script>
@endsection
