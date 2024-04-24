@extends('template.master_noleft')
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
</style>>
@endsection
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    @if(count(_providers()) > 0)
    <h5>Here is the list of your associated <b>Providers</b> <br/>
        <small>You are involved with one or more courses offered by the following providers:</small>
    </h5>
    @else
    <h5>You have no associated <b>Providers</b> yet<br/>
        <small>Being associated with a provider means you are involved with one or more courses offered by them.</small>
    </h5> 
    @endif
    @if(count(_providers_associated_inst(Auth::user()->id)) > 0)
    <div class="row">
        <div class="col-xl-12 col-12">
            <!--begin::Portlet-->
            <div class="kt-portlet__body" id="provider-card">                    
                <div id="provider-card-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                    <br/>
                    <div id="provider-card-carousel-inner" class="carousel-inner"></div>
                    <a id="provider-card-carousel-control-prev" class="carousel-control-prev" href="#provider-card-carousel-indicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a id="provider-card-carousel-control-next" class="carousel-control-next" href="#provider-card-carousel-indicators" role="button" data-slide="next">
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
    
    @if(count(_courses_associated()) > 0)
    <h5>Here is the list of your associated <b>Courses</b> <br/>
        <small>You are given access to manage or a <b>Provider</b> you're associated with has listed you as an <b>Instructor</b> for the course:</small>
    </h5>
    @else
    <h5>You have no associated <b>Courses</b> yet<br/>
        <small>Being associated with a course means you are given access to manage or a <b>Provider</b> you're associated with has listed you as an <b>Instructor</b> for the course.</small>
    </h5>
    @endif
    @if(count(_courses_associated_inst(Auth::user()->id)) > 0)
    <div class="row">
        <div class="col-xl-12 col-12">
            <!--begin::Portlet-->
            <div class="kt-portlet__body" id="course-card">                    
                <div id="course-card-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                    <br/>
                    <div id="course-card-carousel-inner" class="carousel-inner"></div>
                    <a id="course-card-carousel-control-prev" class="carousel-control-prev" href="#course-card-carousel-indicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a id="course-card-carousel-control-next" class="carousel-control-next" href="#course-card-carousel-indicators" role="button" data-slide="next">
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
@endsection

@section("scripts")
<script src="{{asset('js/settings/user-association-page.js')}}" type="text/javascript"></script>
@endsection
