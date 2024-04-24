@extends('template.master_accreditor')
@section('styles')
<style>
    .required{color:#fd397a;}
    .hidden{display:none;}
    .icon-image{height:80px;}
    .bold{font-weight:600}
    @media (max-width: 600px){p.bold{text-align:center;}}
    .course-image{width:180px;border-radius:8px;}
    .kt-widget.kt-widget--user-profile-3 .kt-widget__top .kt-widget__media img{width:180px;}
    @media (max-width: 768px){.kt-widget.kt-widget--user-profile-3 .kt-widget__top .kt-widget__media img{max-width:180px;}}
    .kt-widget.kt-widget--user-profile-3 .kt-widget__top .kt-widget__content .kt-widget__head .kt-widget__action button.btn {margin-top:0.5rem;}
</style>
@endsection
@section('content')
@if($data)
<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-12">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Welcome to FastCPD!
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <p>We would like to apply <b>{{ $data->title ?? 'not-found' }}</b> for accreditation. It is an online video <?=$type?> conducted through <b><a target="_blank" href="www.fastcpd.com">www.FastCPD.com</a></b> where professionals can select the <?=$type?>, pay online, learn at home, and receive their CPD certificate instantly. FastCPD has features to ensure quality learning for each individual.</p>
                    <div class="kt-space-20"></div>
                    <div class="row justify-content-center">
                            <div class="col-xl-3 col-md-4 col-12">
                            <div class="row">
                                <div class="col-xl-3 col-md-4 col-sm-4 col-12" align="center">
                                    <img src="{{ asset('img/system/play.png') }}" class="icon-image">
                                </div>
                                <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                                    <p class="bold">Disabled video forwarding and seek to ensure viewing</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12">
                            <div class="row">
                                <div class="col-xl-3 col-md-4 col-sm-4 col-12" align="center">
                                    <img src="{{ asset('img/system/database.png') }}" class="icon-image">
                                </div>
                                <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                                    <p class="bold">Downloadable handouts, tests with grading, and assessmentof the topic</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-12">
                            <div class="row">
                                <div class="col-xl-3 col-md-4 col-sm-4 col-12" align="center">
                                    <img src="{{ asset('img/system/wifi.png') }}" class="icon-image">
                                </div>
                                <div class="col-xl-8 col-md-7 col-sm-7 col-12">
                                    <p class="bold">Unlimited bandwidth servers to ensure smooth video player and experience</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="kt-portlet ">
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            <div class="kt-widget__media course-dispose">
                                <img alt="FastCPD <?=ucwords($type)?>s <?=$data->title ?? ""?>" src="<?=$data->poster ?? asset('media/products/product1.jpg')?>" alt="image">
                            </div>
                            <div class="kt-widget__content">
                                <img class="hidden course-image" src="<?=$data->poster ?? asset('media/products/product1.jpg')?>" alt="image">
                                <div class="kt-widget__head">
                                    <a href="#" class="kt-widget__title">{{ $data->title ?? 'not-found' }}</a>
                                    <div class="kt-widget__action">
                                        <button onclick="window.open('/data/pdf/user/<?=$type=='course' ? 'certificate' : 'webinar_certificate'?>/<?=$data->id?>')" type="button" class="btn btn-sm btn-upper" style="background: #edeff6">sample certificate</button>&nbsp;
                                        <button onclick="window.open('/accreditation/<?=$type?>/<?=$data->url ?? 'not-found'?>')" type="button" class="btn btn-success btn-sm btn-upper"><?=$type?> details</button>&nbsp;
                                        <button onclick="window.open('/accreditation/<?=$type?>/preview/<?=$data->url ?? 'not-found'?>')" type="button" class="btn btn-brand btn-sm btn-upper">live <?=$type?></button>
                                        <button class="btn btn-danger btn-sm btn-upper" data-toggle="modal" data-target="#accreditation-feedback-modal" data-backdrop="static" data-keyboard="false">provide feedback</button>
                                    </div>
                                </div>
                                <div class="kt-space-10"></div>
                                <div class="kt-widget__info">
                                    <div class="kt-widget__desc">
                                        <?=$data->description ? html_entity_decode($data->description) : ''?>
                                    </div>
                                    <div class="kt-widget__stats d-flex align-items-center flex-fill">
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__label">
                                                @if($type=="course")
                                                <span class="btn btn-label-brand btn-sm btn-bold btn-upper" style="margin-bottom:0.5rem;">Start Date: {{ $data->session_start ? date('F d, Y', strtotime($data->session_start)): 'not-found' }}</span> &nbsp; &nbsp;
                                                <span class="btn btn-label-danger btn-sm btn-bold btn-upper" style="margin-bottom:0.5rem;">End Date: {{ $data->session_end ? date('F d, Y', strtotime($data->session_end)): 'not-found' }}</span>
                                                @else
                                                    @if($data->event=="day")
                                                    <div class="row align-content-start">
                                                        @foreach($data->schedule as $sched)
                                                        <span class="btn btn-label-brand btn-sm btn-bold btn-upper kt-margin-b-5 kt-margin-r-5">
                                                            <i class="fa fa-calendar-day"></i><b><?=$sched["session_date"]?></b>
                                                            @foreach($sched["sessions"] as $sess)
                                                            <br><i class="fa fa-clock"></i> <?=$sess->start?> — <?=$sess->end?>
                                                            @endforeach
                                                        </span>
                                                        @endforeach
                                                    </div>
                                                    @else
                                                    <div class="row align-content-start">
                                                        @foreach($data->schedule as $series)
                                                        @foreach($series["sessions"] as $day => $sched)
                                                        <span class="btn btn-label-brand btn-sm btn-bold btn-upper kt-margin-b-5 kt-margin-r-5">
                                                            <i class="fa fa-calendar-day"></i><b>Day <?=$day+1?> of Series <?=$series["series_order"]?> — <?=$sched["session_date"]?></b>
                                                            @foreach($sched["sessions"] as $sess)
                                                            <br><i class="fa fa-clock"></i> <?=$sess->start?> — <?=$sess->end?>
                                                            @endforeach
                                                        </span>
                                                        @endforeach
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($type=="course")
                        <div class="kt-widget__bottom">
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-presentation"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Article</span>
                                    <span class="kt-widget__value">{{ $data->id ? _course_total_article($data->id) : 0 }} min</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-time"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Video Length</span>
                                    <span class="kt-widget__value">{{ $data->id ? _course_total_video_length($data->id) : "00:00:00" }}</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-list"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Quiz</span>
                                    <span class="kt-widget__value">{{ $data->id ? _course_total_quizzes($data->id) : 0 }} {{ $data->id ? (_course_total_quizzes($data->id) > 1 ? 'items' : 'item') : 'item' }}</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-interface-11"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Handouts</span>
                                    <span class="kt-widget__value">{{ $data->id ? _course_total_handout($data->id)->count() : 0 }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="kt-widget__bottom">
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-presentation"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Article</span>
                                    <span class="kt-widget__value"><?=$data->total["article"] ?? 0?></span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-list"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Quiz</span>
                                    <span class="kt-widget__value"><?=$data->total["quiz"] ?? 0?></</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-interface-11"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Handouts</span>
                                    <span class="kt-widget__value"><?=$data->total["handout"] ?? 0?></</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h1><?=ucwords($type)?> is either approved or drafted!</h1>
@endif
@endsection
@section("scripts")
<script>
    var window_width = $(window).width();
    if(window_width < 700){
        $(`.course-image`).removeClass(`hidden`);
        $(`.course-dispose`).addClass(`hidden`);
    }
</script>
@endsection
