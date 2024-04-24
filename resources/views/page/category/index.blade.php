@extends('template.master_public')

@section('metas')
@include('page.category.meta-tags')
@endsection

@section('styles')
<link href="{{asset('css/course-cards-popover.css')}}" rel="stylesheet" type="text/css" />
<style>
    .white-color{color:#fff !important;}
    .title{font-size:2em !important;}
    .centered{margin:auto;text-align:center;}
    .minimize > i{font-size:2rem !important;}

    .carousel-control-prev, .carousel-control-next{width:5%;}
    .carousel-control-prev:hover{background-image: linear-gradient(to left, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-next:hover{background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(79, 101, 209, 0.4));}
    .carousel-control-prev-icon{background-image: url({{asset('img/carousel/prev.svg')}})}
    .carousel-control-next-icon{background-image: url({{asset('img/carousel/next.svg')}})}
    .carousel-inner{overflow:visible !important;}

    .hero-banner{width:100%;height:80px;margin:0;background: #000428;  /* fallback for old browsers */background: -webkit-linear-gradient(to left, #004e92, #000428);  /* Chrome 10-25, Safari 5.1-6 */background: linear-gradient(to left, #004e92, #000428); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */ }
    /**
    * Icons on Headline
    * 
    */
    .icon-image{height:80px;}
    .user-image{height:80px;border-radius:8px;}

    .course-list-poster{width:175px !important;height:105px;}
    @media (max-width: 768px) {
        .course-list-poster{display:none;}
    }

</style>
@endsection
@section('content')
<div class="row hero-banner">
    <h3 style="margin:auto 0 auto 30px" class="white-color">{{ $profession->title }}</h3>
</div>
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        @if(count(_category_courses($profession->id))>0)
        <div class="row">
            <div class="col-12">
                <h3> Most Popular courses to get you started</h3>
                <div class="kt-space-10"></div>
                <!--begin::Carousel-->
                <div class="kt-portlet__body" id="course-type">  
                    <div class="centered" style="display:none;"><h3>No courses yet.</h3></div>
                    <div id="course-type-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                        <br/>
                        <div id="course-type-carousel-inner" class="carousel-inner"></div>
                        <a id="course-type-carousel-control-prev" class="carousel-control-prev" href="#course-type-carousel-indicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a id="course-type-carousel-control-next" class="carousel-control-next" href="#course-type-carousel-indicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!--end::Carousel-->
            </div>
        </div>
        @endif

        <div class="row" id="category_webinars_div" style="display:none;">
            <div class="col-12">
                <h3> Most Popular webinars to get you started</h3>
                <div class="kt-space-10"></div>
                <!--begin::Carousel-->
                <div class="kt-portlet__body" id="webinar-type">  
                    <div id="webinar-type-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                        <br/>
                        <div id="webinar-type-carousel-inner" class="carousel-inner"></div>
                        <a id="webinar-type-carousel-control-prev" class="carousel-control-prev" href="#webinar-type-carousel-indicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a id="webinar-type-carousel-control-next" class="carousel-control-next" href="#webinar-type-carousel-indicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!--end::Carousel-->
            </div>
        </div>

        <div class="kt-space-10"></div>

        <div class="row justify-content-center row-eq-height"> 
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-4 col-4 centered">
                                <img src="{{ asset('img/system/award.png') }}" class="icon-image"/>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8 col-8">
                                <h5>
                                    CPD Completion Certificate <br/>
                                    <small>Get a certificate with accredited CPD units instantly after finishing</small>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="kt-portlet kt-portlet--bordered">
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-4 col-4 centered">
                                <img src="{{ asset('img/system/business.png') }}" class="icon-image"/>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8 col-8">
                                <h5>
                                    Knowledge from accredited industry experts <br/>
                                    <small>Learn best practices from top organizations</small>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="kt-portlet kt-portlet--bordered">
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-4 col-4 centered">
                                <img src="{{ asset('img/system/talk.png') }}" class="icon-image"/>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8 col-8">
                                <h5>
                                    Learn whenever, wherever <br/>
                                    <small>Enjoy 24 / 7 access to courses in your own devices</small>
                                </h5>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>

        <div class="kt-space-10"></div>
 
        <div class="row">
            <div class="col-12">
                <h3 class="kt-portlet__head-title">
                    Popular Providers
                </h3>
                <div class="kt-portlet__body" id="popular-providers">                                                
                    <div class="centered" style="display:none;"><h3>No providers yet.</h3></div>
                    <div id="popular-providers-carousel-indicators" class="carousel slide" data-ride="carousel" data-interval="false">
                        <br/>
                        <div id="popular-providers-carousel-inner" class="carousel-inner"></div>
                        <a id="popular-providers-carousel-control-prev" class="carousel-control-prev" href="#popular-providers-carousel-indicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a id="popular-providers-carousel-control-next" class="carousel-control-next" href="#popular-providers-carousel-indicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-space-10"></div>

        <div class="row">
            <div class="col-12">
                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a class="btn btn-sm btn-secondary"><i class="fa fa-filter"></i>Filter <b class="filter_count"></b></a>
                                <div class="dropdown dropdown-inline">
                                    <a class="btn btn-sm btn-secondary sorting-courses" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort By: <b>POPULARITY</b></a>
                                    <div class="dropdown-menu dropdown-menu-left" style="">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item" onclick="sortedBy('popularity')">
                                                <a class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Popularity</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item" onclick="sortedBy('newest')">
                                                <a class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Newest</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item" onclick="sortedBy('highest price')">
                                                <a class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Highest Price</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item" onclick="sortedBy('lowest price')">
                                                <a class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Lowest Price</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item" onclick="sortedBy('highest unit')">
                                                <a class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Highest CPD Units</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item" onclick="sortedBy('lowest unit')">
                                                <a class="kt-nav__link">
                                                    <span class="kt-nav__link-text">Lowest CPD Units</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <a class="btn btn-sm btn-secondary"><span id="total_results"></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">                                                
                        <div class="row">
                            <div class="col-xl-3">
                                <div class="accordion accordion-light accordion-toggle-arrow">
                                    <div class="card">
                                        <div class="card-header" id="headingOne2">
                                            <div class="card-title" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2">
                                                Quizzess
                                            </div>
                                        </div>
                                        <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne2" style="">
                                            <div class="card-body">
                                                <div class="kt-checkbox-list">
                                                    <label class="kt-checkbox">
                                                        <input type="checkbox" name="filter_checkbox[]" value="quiz"> Contains Quizzes
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-checkbox">
                                                        <input type="checkbox" name="filter_checkbox[]" value="no-quiz"> No Quizzes
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTwo2">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                                                Ratings
                                            </div>
                                        </div>
                                        <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo2">
                                            <div class="card-body">
                                                <div class="kt-radio-list">
                                                    <label class="kt-radio">
                                                        <input type="radio" name="filter_radio[]" value="4"><text class="rating-sm-4"></text>&nbsp; 4 stars and up
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-radio">
                                                        <input type="radio" name="filter_radio[]" value="3"><text class="rating-sm-3"></text>&nbsp; 3 stars and up
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-radio">
                                                        <input type="radio" name="filter_radio[]" value="2"><text class="rating-sm-2"></text>&nbsp; 2 stars and up
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-radio">
                                                        <input type="radio" name="filter_radio[]" value="1"><text class="rating-sm-2"></text>&nbsp; low rated
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingThree2">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree2" aria-expanded="false" aria-controls="collapseThree2">
                                                Language
                                            </div>
                                        </div>
                                        <div id="collapseThree2" class="collapse" aria-labelledby="headingThree2">
                                            <div class="card-body">
                                                <div class="kt-checkbox-list">
                                                    <label class="kt-checkbox">
                                                        <input type="checkbox" name="filter_checkbox[]" value="english"> English
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-checkbox">
                                                        <input type="checkbox" name="filter_checkbox[]" value="tagalog"> Tagalog
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-checkbox">
                                                        <input type="checkbox" name="filter_checkbox[]" value="mixed"> Mixed
                                                        <span></span>
                                                    </label>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9">
                                <div id="loading" style="display:none;">
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__body" style="align-items:center;">
                                            <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info bigger-spin"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="all_courses"></div> 
                                <div class="row pagination-row-courses">
                                    <div class="col-12">
                                        <!--begin: Pagination-->
                                        <div class="kt-pagination kt-pagination--brand">
                                            <ul class="kt-pagination__links"></ul>
                                            <div class="kt-pagination__toolbar">
                                                <select class="form-control kt-font-brand" name="records" style="width: 50px">
                                                    <option value="12">12</option>
                                                    <option value="24">24</option>
                                                    <option value="48">48</option>
                                                    <option value="96">96</option>
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
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script>
    var window_width = $(window).width();
    var card_to_break = window_width < 1020 ? 1 : 2;
    var card_to_take = window_width  < 1020 ? (window_width < 740 ? 1 : 2) : 4;
    var profession_id = <?=$profession->id?>;

    /**
     * Course Type
     * 
     */
    var course_type_page = 0;
    var course_type_total_page = 0;
    var course_type_slide = true;
    var course_type_is = 'popular';

    var popular_providers_page = 0;
    var popular_providers_total_page = 0;
    var popular_providers_slide = true;

    $(document).ready(function(){
        $('.rating').starRating({
            totalStars: 5,
            initialRating: 2.5,
            readOnly: true,
            starShape: "rounded",
            starSize: 15,
        });
        
        renderCourseType({profession:profession_id,type:course_type_is, take: card_to_take}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"course-type", slide: course_type_slide});
        $(`#course-type-carousel-control-prev`).click(function () {
            course_type_slide = true;
            course_type_page -= 1;

            if (course_type_total_page > 1) {
                $(`#course-type-carousel-control-next`).show();
            }

            if (course_type_page == 0) {
                $(`#course-type-carousel-control-prev`).hide();
            }

            /**
             * Submit Ajax
             * 
             */
            renderCourseType({profession:profession_id,type:course_type_is, take: card_to_take}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"course-type", slide: course_type_slide});
        });

        $(`#course-type-carousel-control-next`).click(function(){
            course_type_slide = false;
            course_type_page += 1;

            $(`#course-type-carousel-control-prev`).show();
            
            if(course_type_page == (course_type_total_page-1)){
                $(`#course-type-carousel-control-next`).hide();
            }
            
            /**
             * Submit Ajax
             * 
             */
            renderCourseType({profession:profession_id,type:course_type_is, take: card_to_take}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"course-type", slide: course_type_slide});
        });


        renderPopularProvider({profession:profession_id}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"popular-providers", slide: course_type_slide});
        $(`#popular-providers-carousel-control-prev`).click(function () {
            course_type_slide = true;
            course_type_page -= 1;

            if (course_type_total_page > 1) {
                $(`#popular-providers-carousel-control-next`).show();
            }

            if (course_type_page == 0) {
                $(`#popular-providers-carousel-control-prev`).hide();
            }

            /**
             * Submit Ajax
             * 
             */
            renderPopularProvider({profession:profession_id}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"popular-providers", slide: course_type_slide});
        });

        $(`#popular-providers-carousel-control-next`).click(function(){
            course_type_slide = false;
            course_type_page += 1;

            $(`#popular-providers-carousel-control-prev`).show();
            
            if(course_type_page == (course_type_total_page-1)){
                $(`#popular-providers-carousel-control-next`).hide();
            }
            
            /**
             * Submit Ajax
             * 
             */
            renderPopularProvider({profession:profession_id}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"popular-providers", slide: course_type_slide});
        });
    });

    function showCourseType(type){
        course_type_is = type;
        course_type_page = 0;
        course_type_slide = true; 
        course_type_total_page = 0;

        renderCourseType({profession:profession_id,type:course_type_is, take: card_to_take}, {page: course_type_page, break: card_to_break, take:card_to_take, id:"course-type", slide: course_type_slide});
    }
</script>
<script src="{{asset('js/course-card/category-page.js')}}" type="text/javascript"></script>
<script src="{{asset('js/webinar/category/page.js')}}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        
    });

    function renderXML(){
        var new_top5 = top_5_courses.map((datamy, i)=>{
            return {
                "@type":"ListItem",
                "position": i+1,
                "url": "<?=URL::to("/course")?>"+`/${datamy.url}`,
                "name": datamy.name,
            };
        });
        var el = document.createElement('script');
        el.type = 'application/ld+json';
        el.text = JSON.stringify([
            {
                "@context":"https://schema.org",
                "@type":"ItemList",
                "itemListElement": new_top5
            },
            {
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
                    "name": "<?=$profession->title?> Courses",
                    "item": "<?=URL::to("/courses/{$profession->url}")?>"  
                }]
            }
        ]);
        document.querySelector('head').appendChild(el);
    }
</script>
@endsection 

