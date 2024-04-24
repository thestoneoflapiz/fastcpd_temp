@extends('template.master_profile')

@section('styles')
<style>
    .nothing-note{font-style: italic;color:#959cb6;}
    .about-me{font-weight:700;}
    .kt-portlet.kt-portlet--sticky > .kt-portlet__head.kt-portlet__head--lg{background-color: #172830;}
    .black-banner{background-color: #172830 !important;}
    .white-color{color:#fff !important;}
    .title{font-size:2em !important;}

    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
    /* .center-contents{display: block; margin-left: auto;margin-right: auto;width:30%} */
    .social-icons{font-size:2em;margin:auto;justify-content:center;text-align:center;}
    .spaces-up-bottom{margin:3em 0 2em 0;}
    @media only screen and (min-width: 720px) {
        .margin-big-profile{padding:0 5% 0 5%;}
    }
</style>
@endsection
@section('content')
<div class="kt-portlet" style="border:1px solid black">
    <div class="kt-portlet__body">
        <ul class="nav nav-tabs nav-tabs-line" id="nav" role="tablist">
            @foreach(_top_professions() as $pro)
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab"  data-id="{{ $pro['id'] }}" href="#{{ $pro['id'] }}" role="tab">{{ $pro['profession'] }}</a>
            </li>                                      
			@endforeach
        </ul>
        <div class="tab-content">
        @foreach(_top_professions() as $pro)
            <div class="tab-pane active" id="{{ $pro['id'] }}" role="tabpanel" style="border:1px solid black">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <!--begin:: Widgets/Blog-->
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget19" style="border:1px solid black">
                                <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill">
                                    <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides">
                                        <img id="popover_image" src="https://www.ais-cpa.com/wp-content/uploads/2019/11/Best-CPA-Review-Courses.png" title="" alt="">
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget19__wrapper">
                                        <div class="kt-widget19__content">
                                            <div class="kt-widget19__info" style="margin-left:-10px;margin-right:10px;">
                                                <span class="kt-font-dark" style="margin-top:-10px;font-size:12px;color:#054F7D;" id="profession">
                                                </span>
                                                <span class="kt-widget19__username kt-font-dark" style="font-size:18px;line-height:1.5;" id="title">
                                                </span>
                                                <span class="kt-font-dark" style="font-size:14px;" id="provider"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__content" style="width:100%;margin-top:-10px;">
                                            <div class="my_rating" data-rating="4" style="width:55%;"></div> 
                                            <div class="kt-font-dark" style="width:45%;">
                                                <span id="rating"></span>
                                                &nbsp;
                                                <span class="total"></span>
                                            </div> 
                                        </div>
                                        <div class="kt-widget19__content" style="margin-left:-10px;margin-right:10px;">
                                            <div class="kt-widget19__info">
                                                <span class="kt-widget19__username" style="color:#054F7D;font-size:20px;" id="units"> 
                                                </span>
                                            </div>
                                            <div class="kt-widget19__stats">
                                                <span class="kt-widget19__number kt-font-dark" style="font-size:20px;" id="price"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__info" style="margin-top:-20px;">
                                            <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded" id="status">
                                                Coming Soon
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Blog-->
                        </div>
                        <div class="col-md-3">
                            <!--begin:: Widgets/Blog-->
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget19" style="border:1px solid black">
                                <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill">
                                    <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides">
                                        <img id="popover_image" src="https://www.ais-cpa.com/wp-content/uploads/2019/11/Best-CPA-Review-Courses.png" title="" alt="">
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget19__wrapper">
                                        <div class="kt-widget19__content">
                                            <div class="kt-widget19__info" style="margin-left:-10px;margin-right:10px;">
                                                <span class="kt-font-dark" style="margin-top:-10px;font-size:12px;color:#054F7D;" id="profession">
                                                </span>
                                                <span class="kt-widget19__username kt-font-dark" style="font-size:18px;line-height:1.5;" id="title">
                                                </span>
                                                <span class="kt-font-dark" style="font-size:14px;" id="provider"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__content" style="width:100%;margin-top:-10px;">
                                            <div class="my_rating" data-rating="4" style="width:55%;"></div> 
                                            <div class="kt-font-dark" style="width:45%;">
                                                <span id="rating"></span>
                                                &nbsp;
                                                <span class="total"></span>
                                            </div> 
                                        </div>
                                        <div class="kt-widget19__content" style="margin-left:-10px;margin-right:10px;">
                                            <div class="kt-widget19__info">
                                                <span class="kt-widget19__username" style="color:#054F7D;font-size:20px;" id="units"> 
                                                </span>
                                            </div>
                                            <div class="kt-widget19__stats">
                                                <span class="kt-widget19__number kt-font-dark" style="font-size:20px;" id="price"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__info" style="margin-top:-20px;">
                                            <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded" id="status">
                                                Coming Soon
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Blog-->
                        </div>
                        <div class="col-md-3">
                            <!--begin:: Widgets/Blog-->
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget19" style="border:1px solid black">
                                <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill">
                                    <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides">
                                        <img id="popover_image" src="https://www.ais-cpa.com/wp-content/uploads/2019/11/Best-CPA-Review-Courses.png" title="" alt="">
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget19__wrapper">
                                        <div class="kt-widget19__content">
                                            <div class="kt-widget19__info" style="margin-left:-10px;margin-right:10px;">
                                                <span class="kt-font-dark" style="margin-top:-10px;font-size:12px;color:#054F7D;" id="profession">
                                                </span>
                                                <span class="kt-widget19__username kt-font-dark" style="font-size:18px;line-height:1.5;" id="title">
                                                </span>
                                                <span class="kt-font-dark" style="font-size:14px;" id="provider"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__content" style="width:100%;margin-top:-10px;">
                                            <div class="my_rating" data-rating="4" style="width:55%;"></div> 
                                            <div class="kt-font-dark" style="width:45%;">
                                                <span id="rating"></span>
                                                &nbsp;
                                                <span class="total"></span>
                                            </div> 
                                        </div>
                                        <div class="kt-widget19__content" style="margin-left:-10px;margin-right:10px;">
                                            <div class="kt-widget19__info">
                                                <span class="kt-widget19__username" style="color:#054F7D;font-size:20px;" id="units"> 
                                                </span>
                                            </div>
                                            <div class="kt-widget19__stats">
                                                <span class="kt-widget19__number kt-font-dark" style="font-size:20px;" id="price"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__info" style="margin-top:-20px;">
                                            <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded" id="status">
                                                Coming Soon
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Blog-->
                        </div>
                        <div class="col-md-3">
                            <!--begin:: Widgets/Blog-->
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget19" style="border:1px solid black">
                                <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill">
                                    <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides">
                                        <img id="popover_image" src="https://www.ais-cpa.com/wp-content/uploads/2019/11/Best-CPA-Review-Courses.png" title="" alt="">
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget19__wrapper">
                                        <div class="kt-widget19__content">
                                            <div class="kt-widget19__info" style="margin-left:-10px;margin-right:10px;">
                                                <span class="kt-font-dark" style="margin-top:-10px;font-size:12px;color:#054F7D;" id="profession">
                                                </span>
                                                <span class="kt-widget19__username kt-font-dark" style="font-size:18px;line-height:1.5;" id="title">
                                                </span>
                                                <span class="kt-font-dark" style="font-size:14px;" id="provider"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__content" style="width:100%;margin-top:-10px;">
                                            <div class="my_rating" data-rating="4" style="width:55%;"></div> 
                                            <div class="kt-font-dark" style="width:45%;">
                                                <span id="rating"></span>
                                                &nbsp;
                                                <span class="total"></span>
                                            </div> 
                                        </div>
                                        <div class="kt-widget19__content" style="margin-left:-10px;margin-right:10px;">
                                            <div class="kt-widget19__info">
                                                <span class="kt-widget19__username" style="color:#054F7D;font-size:20px;" id="units"> 
                                                </span>
                                            </div>
                                            <div class="kt-widget19__stats">
                                                <span class="kt-widget19__number kt-font-dark" style="font-size:20px;" id="price"> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget19__info" style="margin-top:-20px;">
                                            <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded" id="status">
                                                Coming Soon
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Blog-->
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>

@endsection

@section("scripts")
<script>
$("document").ready(function() {

    $("#nav li a").on("click", function(e) {
        var id = $(this).attr("data-id");
        console.log($(this).attr("href"));
        $.ajax({
            url: '/profession_courses/{id}',
                type: 'GET',
                data: { id: id },
                success: function(response)
                {
                    $.each(response.data, function(index, value){
                        //$('div.popup').find("#title").html(course[key].title);
                    });
                }
        });

    });
});
</script>
@endsection
