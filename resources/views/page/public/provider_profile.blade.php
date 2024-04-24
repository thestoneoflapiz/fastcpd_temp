@extends('template.master_public')

@section('metas')
<meta name="description" content="{{ $provider->headline }}">
<meta name="keywords" content="FastCPD,Online,Courses,Webinar,CPD,PRC,{{ $provider->url }},{{ $provider->name }}">
<meta name="author" content="{{ $provider->name }}">

<meta property="og:title" content="{{ $provider->name }}" />
<meta property="og:url" content="<?=URL::to("/provider/{$provider->url}")?>" />
<meta property="og:type" content="fastcpd_com">
<meta property="og:description" content="{{ $provider->headline }}" />
<meta property="og:image" content="{{ $provider->logo ?? 'https://fastcpd.com/img/sample/poster-sample.png' }}" />
<meta property="og:site_name" content="FastCPD">
@endsection

@section('styles')
<link href="{{asset('css/course-cards-popover.css')}}" rel="stylesheet" type="text/css" />
<style>
    .kt-widget.kt-widget--user-profile-4 .kt-widget__head .kt-widget__media .kt-widget__img{border-radius:5% !important;}
    .about-me{font-weight:700;}

    .carousel-control-prev, .carousel-control-next{width:5%;}
    .carousel-control-prev:hover{background-image: linear-gradient(to left, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-next:hover{background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-prev-icon{background-image: url({{asset('img/carousel/prev.svg')}})}
    .carousel-control-next-icon{background-image: url({{asset('img/carousel/next.svg')}})}
    .carousel-inner{overflow:visible !important;}

    .see_more--active{max-height:435px !important;overflow:hidden;position:relative;}
    .see_more--inactive{position:relative;}
    .see_more{position:absolute;left:0;bottom:0;height:100px;width:100%;z-index:1;background-image: linear-gradient(to bottom, rgba(0,0,0,0), rgba(112, 117, 142, 0.4));}
    .see_more > p {position:absolute;right:5%;bottom:0;font-weight:600;font-size:25px;color:white;text-shadow:1px 1px 2px rgba(112, 117, 142, 0.4)}

    /* Images */
    .kt-widget.kt-widget--user-profile-2 .kt-widget__head .kt-widget__media .kt-widget__img{border-radius:3px;}
    .kt-widget.kt-widget--user-profile-2 .kt-widget__head .kt-widget__media .kt-widget__pic{border-radius:3px;}
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}

</style>
@endsection
@section('content')
<?php $instructor_count = count(_provider_instructors($provider->id)); ?>
<input type="hidden" value="{{ $provider->id }}" name="provider_id"/>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-4">
                <!--begin:: Profile-->
                <div class="kt-portlet kt-portlet--height-fluid-">
                    <div class="kt-portlet__body">
                        <!--begin::Widget -->
                        <div class="kt-widget kt-widget--user-profile-4">
                            <div class="kt-widget__head">
                                <div class="kt-widget__media">
                                    @if ( $provider->logo )
                                    <div class="image_container">
                                        <img alt="FastCPD Provider Logo <?=$provider->name?>" src="{{ $provider->logo ?? asset('img/sample/noimage.png') }}" class="image"/>
                                    </div>
                                    @else
                                    <div class="kt-widget__pic kt-widget__pic--success kt-font-success kt-font-boldest" style="border-radius:5% !important;">
                                        {{ strtoupper($provider->name[0].$provider->name[1]) }}
                                    </div>
                                    @endif 
                                </div>
                                <div class="kt-widget__content">
                                    <div class="kt-widget__section">
                                        <a href="javascript:;" class="kt-widget__username">
                                            <b>{{ $provider->name }}</b> <i class="flaticon2-correct kt-font-success"></i>
                                            <br><small>Provider</small>
                                        </a>
                                        <div class="kt-widget__desc" style="text-align:center;">
                                            {{ $provider->headline }}
                                        </div>
                                        <div class="kt-widget__action">
                                            @if( $provider->website )
                                            <a href="{{ $provider->website }}" target="_blank" class="btn btn-icon btn-google">
                                                <i class="flaticon2-world"></i>
                                            </a>
                                            @endif

                                            @if( $provider->facebook )
                                            <a href="{{ $provider->facebook }}" target="_blank" class="btn btn-icon btn-facebook">
                                                <i class="socicon-facebook"></i>
                                            </a>
                                            @endif

                                            @if( $provider->linkedin )
                                            <a href="{{ $provider->linkedin }}" target="_blank" class="btn btn-icon btn-twitter">
                                                <i class="socicon-linkedin"></i>
                                            </a>
                                            @endif 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__body">
                                <a href="#our_courses" class="kt-widget__item">
                                    Our Courses 
                                </a>
                                <a href="#our_instructors" class="kt-widget__item">
                                    Our Instructors 
                                </a>
                            </div>
                        </div>
                        <!--end::Widget -->
                    </div>
                </div>
                <!--end:: Profile-->
            </div>
            <div class="col-xl-9 col-lg-8 col-md-8">
                @if($provider->about)
                <div class="kt-portlet kt-portlet--height-fluid-">
                    <div class="kt-portlet__body about--limit">
                        <h3 class="about-me">About <small>{{ $provider->name }} &#9679; {{ $provider->accreditation_number }}</small></h3>
                        <?=htmlspecialchars_decode($provider->about)?>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                        @if($instructor_count > 0) 
                        <h5>Here is the list of our <b>Instructors</b> <span class="kt-badge kt-badge--danger">{{ $instructor_count }}</span><br/>
                            <small></small>
                        </h5>
                        <div class="row" id="our_instructors">
                            <div class="col-xl-12 col-12">
                                <!--begin::Portlet-->
                                <div class="kt-portlet__body" id="provider-instructors">                    
                                    <div id="provider-instructors-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                                        <br/>
                                        <div id="provider-instructors-carousel-inner" class="carousel-inner"></div>
                                        <a id="provider-instructors-carousel-control-prev" class="carousel-control-prev" href="#provider-instructors-carousel-indicators" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a id="provider-instructors-carousel-control-next" class="carousel-control-next" href="#provider-instructors-carousel-indicators" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Portlet-->
                            </div>
                        </div>
                        @else
                        <h5>It seems like <b>{{ $provider->name }}</b> doesn't have <b>Instructors</b> yet</h5>
                        <br/>
                        @endif                       

                        <hr/>
                        <br/> 

                        @if(count(_provider_courses($provider->id)) > 0)
                        <h5>Here is the list of our <b>Courses</b> <span class="kt-badge kt-badge--danger">{{ count(_provider_courses($provider->id)) }}</span><br/>
                            <small></small>
                        </h5>
                        <div class="row" id="our_courses">
                            <div class="col-xl-12 col-12">
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
                        @else
                        <h5>It seems like <b>{{ $provider->name }}</b> doesn't have offering for <b>Courses</b> yet<br/>
                            <small>You can enroll in one of their courses once they've been published.</small>
                        </h5>
                        @endif

                        <hr/>
                        <br/> 

                        @if(_provider_webinars($provider->id, true) > 0)
                        <h5>Here is the list of our <b>Webinars</b> <span class="kt-badge kt-badge--danger">{{ _provider_webinars($provider->id, true) }}</span><br/>
                            <small></small>
                        </h5>
                        <div class="row" id="our_webinars">
                            <div class="col-xl-12 col-12">
                                <!--begin::Portlet-->
                                <div class="kt-portlet__body" id="provider-webinars">                    
                                    <div id="provider-webinars-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                                        <br/>
                                        <div id="provider-webinars-carousel-inner" class="carousel-inner"></div>
                                        <a id="provider-webinars-carousel-control-prev" class="carousel-control-prev" href="#provider-webinars-carousel-indicators" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a id="provider-webinars-carousel-control-next" class="carousel-control-next" href="#provider-webinars-carousel-indicators" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Portlet-->
                            </div>
                        </div>
                        @else
                        <h5>It seems like <b>{{ $provider->name }}</b> doesn't have offering for <b>Webinars</b> yet<br/>
                            <small>You can enroll in one of their webinars once they've been published.</small>
                        </h5>
                        @endif
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script src="{{asset('js/course-card/provider-page.js')}}" type="text/javascript"></script>
<script src="{{asset('js/webinar/public/provider/card.js')}}" type="text/javascript"></script>
<script>
    function seemore_details(){
        $(".see_more").remove();
        $(".about--limit").removeClass("see_more--active").addClass("see_more--inactive").append("<div class='see_more' onclick='seeless_details()'><p>See Less</p></div>");
    }

    function seeless_details(){
        $(".see_more").remove();
        $(".about--limit").removeClass("see_more--inactive").addClass("see_more--active").append("<div class='see_more' onclick='seemore_details()'><p>See More</p></div>");
    }

    $(document).ready(function(){
        var el = document.createElement('script');
        el.type = 'application/ld+json';
        el.text = JSON.stringify([{
            "@context": "https://schema.org/",
            "@type": "Person",
            "name": "<?=$provider->name?>",
            "url": "<?=URL::to("/provider/{$provider->url}")?>",
            "image": "<?=$provider->logo ?? 'https://fastcpd.com/img/system/icon-1.png'?>",
            "sameAs": [
                "<?=$provider->website?>",
                "<?=$provider->facebook?>",
                "<?=$provider->linkedin?>"
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
            }, {
                "@type": "ListItem", 
                "position": 2, 
                "name": "<?=$provider->name?>",
                "item": "<?=URL::to("/provider/{$provider->url}")?>"  
            }]
        }]);

        document.querySelector('head').appendChild(el);
    });

</script>
@endsection
