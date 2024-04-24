@extends('template.master_public')
@section('styles')
<link href="{{asset('css/course-cards-popover.css')}}" rel="stylesheet" type="text/css" />
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}

    .carousel-control-prev, .carousel-control-next{width:5%;}
    .carousel-control-prev:hover{background-image: linear-gradient(to left, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-next:hover{background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-prev-icon{background-image: url({{asset('img/carousel/prev.svg')}})}
    .carousel-control-next-icon{background-image: url({{asset('img/carousel/next.svg')}})}
    .carousel-inner{overflow:visible !important;}

    .banner-wrapper{margin:auto 0 50px 0;color:white;text-shadow: 0.5px 0.5px 0.5px rgba(0,0,0,0.5)}
    .hero-banner{width:100%;height:500px;margin:0;background-color:black;background-repeat: no-repeat;background-size: cover;background-position:center;background-image: url({{ asset('media/bg/home2.jpg') }});}
    @media (max-width: 700px) {
        .hero-banner{height:250px;}
    }
    
    .border-around-widget{border:1px solid #F2F3F8; border-radius:8px;padding:10px;}
    .border-around-widget .kt-widget5__item{margin-bottom:0;padding-bottom:0;}
    .icon-overlay{font-size:2.5rem;color:rgba(255,255,255,0.5);position:absolute;left:60;top:30;}

    .kt-widget5 .kt-widget5__item .kt-widget5__content .kt-widget5__pic img {max-width:8.5rem;min-width:8.5rem;min-height:5rem;max-height:5rem;}
    @media (max-width: 1024px){.kt-widget5 .kt-widget5__item .kt-widget5__content .kt-widget5__pic img {max-width:8.5rem;min-width:8.5rem;}}
    .select2-container--default .select2-selection--single .select2-selection__rendered{font-weight:600;text-shadow:none !important;}
</style>
@endsection

@section('metas')
<meta name="google-site-verification" content="YBSek5cqnVVkqUsvyloxjR8OMTENxHJPhxEL8q-y64g" />
<meta property="og:title" content="Home Page" />
<meta property="og:url" content="<?=URL::to('/')?>" />
<meta property="og:type" content="fastcpd_com">
<meta property="og:image" content="https://fastcpd.com/img/sample/poster-sample.png" />
<meta property="og:site_name" content="FastCPD">
<meta name="description" content="Online marketplace where professionals can earn CPD units with video courses on their own schedule. PRC courses for accountancy, nursing, teachers, and more.">
<meta property="og:description" content="Online marketplace where professionals can earn CPD units with video courses on their own schedule. PRC courses for accountancy, nursing, teachers, and more." />

@if(config("app.env") == "production")
<script type="application/ld+json">
    [{
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "https://www.fastcpd.com",
      "logo": "https://www.fastcpd.com/img/system/logo-1.png",
      "image": [
            "https://www.fastcpd.com/img/system/icon-1.png",
          	"https://www.fastcpd.com/img/system/logo-2.png",
          	"https://www.fastcpd.com/img/system/logo-1.png"
        ],
      "slogan":"The Best and Convenient Way to Earn CPD Units",
      "description":"FastCPD is an online marketplace where PRC-accredited program providers can create and publish online courses to offer professionals in the Philippines. It is the easiest and fastest way to learn topics in a professional field while earning CPD units to renew professional licenses",
      "email":"info@fastcpd.com",
      "address" : {
        "@type": "PostalAddress",
        "streetAddress": "30 Cabatuan Street",
        "addressLocality": "Quezon City",
        "addressRegion": "NCR",
        "postalCode": "1100",
        "addressCountry": "PH"
      },
      "knowsAbout":"Online CPD Courses",
      "makesOffer" :"Online CPD Courses",
      "telephone" : ["+63-2-332-6977","+63917-817-7388"],
      "sameAs": [
        "https://www.facebook.com/fastcpd",
        "https://www.instagram.com/fastcpd/",
        "https://www.fastcpd.com"
      ]
      },
      {
      "@context": "https://schema.org",
      "@type": "Brand",
      "name":"FastCPD",
      "url": "https://www.fastcpd.com",
      "logo": "https://www.fastcpd.com/img/system/logo-1.png",
      "image":"https://www.fastcpd.com/img/system/logo-1.png",
      "slogan":"The Best and Convenient Way to Earn CPD Units",
      "description":"FastCPD is an online marketplace where PRC-accredited program providers can create and publish online courses to offer professionals in the Philippines. It is the easiest and fastest way to learn topics in a professional field while earning CPD units to renew professional licenses",
      "sameAs": [
        "https://www.facebook.com/fastcpd",
        "https://www.instagram.com/fastcpd",
        "https://www.youtube.com/channel/UC-3v3AGXbogd7CmSyJQ6-jw",
        "https://www.fastcpd.com"]
      },
     {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "About FastCPD",
        "item": "https://www.fastcpd.com/site/about-fastcpd"
      }]
    }]
</script>
@endif

@endsection
@section('content')
<div class="row hero-banner">
@if(Auth::check())
    <div class="banner-wrapper">
        <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <h1 style="font-weight:700 !important;">Welcome back {{ Auth::user()->name }}</h1>
                <div class="kt-space-10"></div>
                <h4>Get CPD units at home and on your own schedule</h4>
            </div>
        </div>
    </div>
@else
    <div class="banner-wrapper">
        <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <div class="row">
                    <div class="col-xl-7 col-lg-6 col-md-6 col-sm-6 col-12">
                        <h1 style="font-weight:700 !important;">The Best and Convenient Way to Earn CPD Units</h1>
                        <div class="kt-space-10"></div>
                        <h4>Get CPD units at home and on your own schedule</h4>
                        <select class="form-control kt-select2" id="profession" name="profession[]" style="width:100%;">
                            <option disabled selected></option>
                            @foreach(_professions() as $pro)
                            <option value="{{ $pro['url'] }}">{{ $pro['profession'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div> 
        </div>
    </div>
@endif
</div>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        @if(Auth::check() && $in_progress_course = _get_latest_progress_live_course())
        <div class="row">
            <div class="col-xl-12 col-12">
                <div class="kt-portlet kt-portlet--head-lg">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Ready to jump back in? <br/>
                                <small>Continue watching your previous unfinished courses.</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body" id="unfinished-courses">
                    <div class="row justify-content-center">
                        @foreach($in_progress_course as $progress)                        
                        <div class="<?=count($in_progress_course) < 3 ? "col-xl-6 col-lg-6": "col-xl-4 col-lg-4"?> col-md-6 col-12">
                            <div class="kt-portlet">
                                <div class="kt-widget5 border-around-widget">
                                    <div class="kt-widget5__item">
                                        <div class="kt-widget5__content">
                                            <div class="kt-widget5__pic kt-widget5__pic--responsive" onclick="window.open('/course/live/<?=$progress['url']?>')" style="cursor:pointer;">
                                                <img class="kt-widget7__img" src="{{ $progress['course_poster'] }}" alt="">
                                                <i class="fa fa-play-circle icon-overlay"></i>
                                            </div>
                                            <div class="kt-widget5__section">
                                                <a href="/course/live/{{ $progress['url'] }}" target="_blank" class="kt-widget5__title">{{ $progress["title"] }}</a>
                                                <p class="kt-widget5__desc">{{ number_format($progress["progress"], 2, ".", "") }}% completed</p>
                                            </div>
                                        </div>
                                        <div class="kt-widget5__content"></div>                                                            
                                    </div>
                                </div>
                                <div class="progress" style="height:5px;background-color:rgba(0,0,0,0.1);">
                                    <div class="progress-bar kt-bg-<?= $progress['progress'] <= 50 ? "info" : ( $progress['progress'] <= 79 ? "warning" : "success") ?>" role="progressbar" style="width:{{ $progress['progress']>0 ? $progress['progress'] : 1 }}%;" aria-valuenow="{{ $progress['progress'] }}1" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-space-10"></div>
        @endif

        @if(count(_newest_courses()) > 0)
        <div class="row">
            <div class="col-xl-12 col-12">
                <h3>Newest Courses</h3>
                <!--begin::Portlet-->
                <div class="kt-portlet__body" id="newest-courses">                    
                    <div id="newest-courses-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                        <br/>
                        <div id="newest-courses-carousel-inner" class="carousel-inner"></div>
                        <a id="newest-courses-carousel-control-prev" class="carousel-control-prev" href="#newest-courses-carousel-indicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a id="newest-courses-carousel-control-next" class="carousel-control-next" href="#newest-courses-carousel-indicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>
        @endif

        <div class="row" id="newest_webinars_row" style="display:none;">
            <div class="col-xl-12 col-12">
                <h3>Newest Webinars</h3>
                <!--begin::Portlet-->
                <div class="kt-portlet__body" id="newest-webinars">                    
                    <div id="newest-webinars-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                        <br/>
                        <div id="newest-webinars-carousel-inner" class="carousel-inner"></div>
                        <a id="newest-webinars-carousel-control-prev" class="carousel-control-prev" href="#newest-webinars-carousel-indicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a id="newest-webinars-carousel-control-next" class="carousel-control-next" href="#newest-webinars-carousel-indicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>

        @if(count(_top_professions()) > 0)
        <div class="row">
            <div class="col-xl-12 col-12">
                <h3>Top Professions</h3>
                <div class="kt-portlet__body">
                    <div class="centered">
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x nav-tabs-line-info" id="nav" role="tablist">
                            @foreach(_top_professions() as $key => $pro)
                            <li class="nav-item" onclick="showTopProfessions({{ $pro['id'] }})">
                                <a class="nav-link {{ $key==0 ? 'active' : ''}}" data-toggle="tab" role="tab">{{ $pro['profession'] }}</a>
                            </li>                                      
                            @endforeach
                        </ul>
                    </div>
                    <!--begin::Portlet-->
                    <div class="kt-portlet__body" id="top-professions">  
                        <div id="top-professions-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                            <br/>
                            <div id="top-professions-carousel-inner" class="carousel-inner"></div>
                            <a id="top-professions-carousel-control-prev" class="carousel-control-prev" href="#top-professions-carousel-indicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a id="top-professions-carousel-control-next" class="carousel-control-next" href="#top-professions-carousel-indicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
        </div>
        <div class="kt-space-10"></div>
        <hr />
        <div class="kt-space-20"></div>
        @endif

        @if(count(_top_courses()) > 0)
        <div class="row">
            <div class="col-xl-12 col-12">
                <h3> Top Courses</h3>
                <!--begin::Portlet-->
                <div class="kt-portlet__body" id="top-courses">                    
                    <div id="top-courses-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                        <br/>
                        <div id="top-courses-carousel-inner" class="carousel-inner"></div>
                        <a id="top-courses-carousel-control-prev" class="carousel-control-prev" href="#top-courses-carousel-indicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a id="top-courses-carousel-control-next" class="carousel-control-next" href="#top-courses-carousel-indicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="kt-space-10"></div>
        <hr />
        <div class="kt-space-20"></div>
        @endif

        
    </div>
</div>
@endsection
@section("scripts")
<script>
    var window_width = $(window).width();
    var card_to_break = window_width < 1020 ? 1 : 2;
    var card_to_take = window_width  < 1020 ? (window_width < 740 ? 1 : 2) : 4;
    
    /**
     * Top Professions
     * 
     */
    var top_pr_id = <?= _top_professions()[0]["id"] ?? 0 ?>;
    var top_pr_page = 0;
    var top_pr_total_page = 0;
    var top_pr_slide = true;

    /**
     * Top Courses
     * 
     */
    var top_course_page = 0;
    var top_course_total_page = 0;
    var top_course_slide = true;

    /**
     * Top Courses
     * 
     */
    var newest_course_page = 0;
    var newest_course_total_page = 0;
    var newest_course_slide = true;

    $("document").ready(function () {
        if(window.location.href.indexOf('#login') != -1) {
            $('#login_modal').modal('show');
        }

        if(window.location.href.indexOf('#signup') != -1) {
            $('#signup_modal').modal('show');
        }

        $('#profession').select2({
            placeholder: "What's your profession?"
        }).change(function(e){
            var value = e.target.value;
            window.open(`/courses/${value}`);
        });

        renderInProgressCourses({page: top_pr_page, break: card_to_break, take:card_to_take, id:"in-progress-courses", slide: top_pr_slide});

        renderTopProfession({profession:top_pr_id, take: card_to_take}, {page: top_pr_page, break: card_to_break, take:card_to_take, id:"top-professions", slide: top_pr_slide});
        $(`#top-professions-carousel-control-prev`).click(function () {
            top_pr_slide = true;
            top_pr_page -= 1;

            if (top_pr_total_page > 1) {
                $(`#top-professions-carousel-control-next`).show();
            }

            if (top_pr_page == 0) {
                $(`#top-professions-carousel-control-prev`).hide();
            }

            /**
             * Submit Ajax
             * 
             */
            renderTopProfession({profession:top_pr_id, take: card_to_take}, {page: top_pr_page, break: card_to_break, take:card_to_take, id:"top-professions", slide: top_pr_slide});
        });

        $(`#top-professions-carousel-control-next`).click(function(){
            top_pr_slide = false;
            top_pr_page += 1;

            $(`#top-professions-carousel-control-prev`).show();
            
            if(top_pr_page == (top_pr_total_page-1)){
                $(`#top-professions-carousel-control-next`).hide();
            }
            
            /**
             * Submit Ajax
             * 
             */
            renderTopProfession({profession:top_pr_id, take: card_to_take}, {page: top_pr_page, break: card_to_break, take:card_to_take, id:"top-professions", slide: top_pr_slide});
        });

        /**
         * 
         * With carousel 
         */
        renderTopCourses({page: top_course_page, break: card_to_break, take:card_to_take, id:"top-courses", slide: top_course_slide});
        $(`#top-courses-carousel-control-prev`).click(function () {
            top_course_slide = true;
            top_course_page -= 1;

            if (top_course_total_page > 1) {
                $(`#top-courses-carousel-control-next`).show();
            }

            if (top_course_page == 0) {
                $(`#top-courses-carousel-control-prev`).hide();
            }

            /**
             * Submit Ajax
             * 
             */
            renderTopCourses({page: top_course_page, break: card_to_break, take:card_to_take, id:"top-courses", slide: top_course_slide});
        });

        $(`#top-courses-carousel-control-next`).click(function(){
            top_course_slide = false;
            top_course_page += 1;

            $(`#top-courses-carousel-control-prev`).show();
            
            if(top_course_page == (top_course_total_page-1)){
                $(`#top-courses-carousel-control-next`).hide();
            }
            
            /**
             * Submit Ajax
             * 
             */
            renderTopCourses({page: top_course_page, break: card_to_break, take:card_to_take, id:"top-courses", slide: top_course_slide});
        });

        /**
         * 
         * With carousel 
         */
        renderNewestCourses({page: newest_course_page, break: card_to_break, take:card_to_take, id:"newest-courses", slide: newest_course_slide});
        $(`#newest-courses-carousel-control-prev`).click(function () {
            newest_course_slide = true;
            newest_course_page -= 1;

            if (newest_course_total_page > 1) {
                $(`#newest-courses-carousel-control-next`).show();
            }

            if (newest_course_page == 0) {
                $(`#newest-courses-carousel-control-prev`).hide();
            }

            /**
             * Submit Ajax
             * 
             */
            renderNewestCourses({page: newest_course_page, break: card_to_break, take:card_to_take, id:"newest-courses", slide: newest_course_slide});
        });

        $(`#newest-courses-carousel-control-next`).click(function(){
            newest_course_slide = false;
            newest_course_page += 1;

            $(`#newest-courses-carousel-control-prev`).show();
            
            if(newest_course_page == (newest_course_total_page-1)){
                $(`#newest-courses-carousel-control-next`).hide();
            }
            
            /**
             * Submit Ajax
             * 
             */
            renderNewestCourses({page: newest_course_page, break: card_to_break, take:card_to_take, id:"newest-courses", slide: newest_course_slide});
        });
    });

    function showTopProfessions(id){
        top_pr_page = 0;
        top_pr_slide = true;
        top_pr_total_page = 0;
        top_pr_id = id;
        renderTopProfession({profession:id, take: card_to_take}, {page: top_pr_page, break: card_to_break, take:card_to_take, id:"top-professions", slide: top_pr_slide});
    }
</script>
<script src="{{asset('js/course-card/landing-page.js')}}" type="text/javascript"></script>
<script src="{{asset('js/webinar/landing/page.js')}}" type="text/javascript"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VF9ED1G1M5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-VF9ED1G1M5');
</script>
@endsection
