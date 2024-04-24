@extends('template.master_public')

@section('metas')
<meta name="description" content="{{ $user->headline }}">
<meta name="keywords" content="FastCPD,Online,Courses,Webinar,CPD,PRC,{{ $user->username }},{{ $user->name }}">
<meta name="author" content="{{ $user->name }}">

<meta property="og:title" content="<?=$course_head_title ??  $user->name. " - ".$user->name." | Users | FastCPD "?>" />
<meta property="og:url" content="<?=URL::to("/user/{$user->username}")?>" />
<meta property="og:type" content="fastcpd_com">
<meta property="og:description" content="{{ $user->headline }}" />
<meta property="og:image" content="{{ $user->image ?? 'https://fastcpd.com/img/sample/poster-sample.png' }}" />
<meta property="og:site_name" content="FastCPD">

@endsection

@section('styles')
<link href="{{asset('css/course-cards-popover.css')}}" rel="stylesheet" type="text/css" />
<style>
    .nothing-note{font-style: italic;color:#959cb6;}
    .about-me{font-weight:700;}
    .kt-portlet.kt-portlet--sticky > .kt-portlet__head.kt-portlet__head--lg{background-color: #172830;}
    .black-banner{background-color: #172830 !important;}
    .blue-banner{background: #0575E6 !important;background: -webkit-linear-gradient(to right, #021B79, #0575E6) !important;background: linear-gradient(to right, #021B79, #0575E6) !important;}
    .white-color{color:#fff !important;}
    .title{font-size:2em !important;}

    /* Images */
    .kt-widget.kt-widget--user-profile-2 .kt-widget__head .kt-widget__media .kt-widget__img{border-radius:3px;}
    .kt-widget.kt-widget--user-profile-2 .kt-widget__head .kt-widget__media .kt-widget__pic{border-radius:3px;}
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
    
    /* .center-contents{display: block; margin-left: auto;margin-right: auto;width:30%} */
    .social-icons{font-size:2em;margin:auto;justify-content:center;text-align:center;}
    .spaces-up-bottom{margin:3em 0 2em 0;}
    @media only screen and (min-width: 720px) {
        .margin-big-profile{padding:0 5% 0 5%;}
    }

    .carousel-control-prev, .carousel-control-next{width:5%;}
    .carousel-control-prev:hover{background-image: linear-gradient(to left, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-next:hover{background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-prev-icon{background-image: url({{asset('img/carousel/prev.svg')}})}
    .carousel-control-next-icon{background-image: url({{asset('img/carousel/next.svg')}})}
    .carousel-inner{overflow:visible !important;}
</style>
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <input type="hidden" value="{{ $user->id }}" name="user_id"/>
            @if($user->instructor != "active")
            <div class="kt-portlet kt-portlet--last kt-portlet--head-xl kt-portlet--responsive-mobile" id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--xl black-banner">
                    <div class="kt-portlet__head-label">
                        <h1 class="kt-portlet__head-title white-color title">{{ $user->name }}
                            @if ($user->headline)
                            <br/>
                            <small class="white-color">{{ $user->headline }}</small>
                            @endif
                        </h1>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-actions">
                            @if($user->website)
                            <a href="{{ $user->website }}" class="btn btn-outline-light btn-sm btn-icon btn-icon-md" target="_blank">
                                <i class="flaticon2-world"></i>
                            </a>
                            @endif

                            @if($user->facebook)
                            <a href="{{ $user->facebook }}" class="btn btn-outline-light btn-sm btn-icon btn-icon-md" target="_blank">
                                <i class="flaticon-facebook-letter-logo"></i>
                            </a>
                            @endif

                            @if($user->linkedin)
                            <a href="{{ $user->linkedin }}" class="btn btn-outline-light btn-sm btn-icon btn-icon-md" target="_blank">
                                <i class="flaticon-linkedin-logo"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="margin-big-profile">
                        <div class="row">
                            <div class="col-xl-12">
                                <!-- Profile:Begin -->
                                <div class="row">
                                    <div class="col-xl-2 col-lg-2 col-md-3 center-contents">
                                        <div class="image_container">
                                            <img alt="FastCPD User Image <?=$user->name?>" src="{{ $user->image ?? asset('img/sample/noimage.png') }}" class="image"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-10 col-lg-10 col-md-9">
                                        <div class="row">
                                            <h3 class="about-me">About me</h3>
                                        </div>
                                        <div class="row">
                                            <?= $user->about ? htmlspecialchars_decode($user->about) : "<div><text class='nothing-note'>Nothing to tell about...</text></div>" ?>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- Profile:End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="kt-portlet kt-portlet--last kt-portlet--head-md kt-portlet--responsive-mobile" id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--md blue-banner">
                    <div class="kt-portlet__head-label">
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-actions">
                            @if($user->website)
                            <a href="{{ $user->website }}" class="btn btn-outline-light btn-sm btn-icon btn-icon-md" target="_blank">
                                <i class="flaticon2-world"></i>
                            </a>
                            @endif

                            @if($user->facebook)
                            <a href="{{ $user->facebook }}" class="btn btn-outline-light btn-sm btn-icon btn-icon-md" target="_blank">
                                <i class="flaticon-facebook-letter-logo"></i>
                            </a>
                            @endif

                            @if($user->linkedin)
                            <a href="{{ $user->linkedin }}" class="btn btn-outline-light btn-sm btn-icon btn-icon-md" target="_blank">
                                <i class="flaticon-linkedin-logo"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="margin-big-profile">
                        <div class="row">
                            <div class="col-xl-12">
                                <!-- Profile:Begin -->
                                <div class="row">
                                    <div class="col-xl-2 col-lg-2 col-md-3 center-contents">
                                        <div class="image_container">
                                            <img alt="FastCPD User Image <?=$user->name?>" src="{{ $user->image ?? asset('img/sample/noimage.png') }}" class="image"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-10 col-lg-10 col-md-9">
                                        <div class="row">
                                            <h3 class="about-me">
                                                <small>Instructor <i class="flaticon2-correct kt-font-success"></i></small> <br/>
                                                {{ $user->name }} 
                                            </h3>
                                        </div>
                                        @if($user->headline)
                                        <div class="row">
                                            {{ $user->headline }}
                                        </div>
                                        @endif
                                        @if($user->about)
                                        <br/>
                                        <div class="row">
                                            <h3 class="about-me">About me</h3>
                                        </div>
                                        <div class="row">
                                            <?= htmlspecialchars_decode($user->about) ?>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- Profile:End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
            @endif
        </div>

        @if($user->instructor == "active")
        <div class="kt-space-20"></div>
        <div class="row">
            <div class="col-12">
                @if(count(_courses_associated_inst($user->id)) > 0)
                <h5><span class="kt-badge kt-badge--danger">{{ count(_courses_associated_inst($user->id)) }}</span> <b>Courses</b> associated with me<br/>
                    <small></small>
                </h5>
                <div class="row">
                    <div class="col-xl-12 col-12">
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
                @endif
 
                <hr/>
                <br/>

                @if(count(_webinars_associated_inst($user->id)) > 0)
                <h5><span class="kt-badge kt-badge--danger">{{ count(_webinars_associated_inst($user->id)) }}</span> <b>Webinars</b> associated with me<br/>
                    <small></small>
                </h5>
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet__body" id="instructor-webinars">                    
                            <div id="instructor-webinars-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                                <br/>
                                <div id="instructor-webinars-carousel-inner" class="carousel-inner"></div>
                                <a id="instructor-webinars-carousel-control-prev" class="carousel-control-prev" href="#instructor-webinars-carousel-indicators" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a id="instructor-webinars-carousel-control-next" class="carousel-control-next" href="#instructor-webinars-carousel-indicators" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>

                <hr/>
                <br/>
                @endif

                @if(count(_providers_associated_inst($user->id)) > 0)
                <h5><span class="kt-badge kt-badge--danger">{{ count(_providers_associated_inst($user->id)) }}</span> <b>Providers</b> associated with me<br/>
                    <small></small>
                </h5>
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet__body" id="instructor-providers">                    
                            <div id="instructor-providers-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                                <br/>
                                <div id="instructor-providers-carousel-inner" class="carousel-inner"></div>
                                <a id="instructor-providers-carousel-control-prev" class="carousel-control-prev" href="#instructor-providers-carousel-indicators" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a id="instructor-providers-carousel-control-next" class="carousel-control-next" href="#instructor-providers-carousel-indicators" role="button" data-slide="next">
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
        </div>
        @endif
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset('js/course-card/user-page.js')}}" type="text/javascript"></script>
<script src="{{asset('js/webinar/public/user/card.js')}}" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        var el = document.createElement('script');
        el.type = 'application/ld+json';
        el.text = JSON.stringify([{
            "@context": "https://schema.org/",
            "@type": "Person",
            "name": "<?=$user->name?>",
            "url": "<?=URL::to("/".Request::segment(1)."/{$user->username}")?>",
            "image": "<?=$user->image ?? 'https://fastcpd.com/img/system/icon-1.png'?>",
            "sameAs": [
                "<?=$user->website?>",
                "<?=$user->facebook?>",
                "<?=$user->linkedin?>"
            ],
            "jobTitle": "Headline"  
        }, {
            "@context": "https://schema.org/", 
            "@type": "BreadcrumbList", 
            "itemListElement": [{
                "@type": "ListItem", 
                "position": 1, 
                "name": "FastCPD Home",
                "item": "<?=URL::to("/")?>"  
            },{
                "@type": "ListItem", 
                "position": 2, 
                "name": "<?=$user->name?>",
                "item": "<?=URL::to("/".Request::segment(1)."/{$user->username}")?>"  
            }]
        }]);

        document.querySelector('head').appendChild(el);
    });
</script>
@endsection
