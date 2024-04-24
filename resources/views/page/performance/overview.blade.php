@extends('template.master_provider')
@section('title', _current_provider()->name)
@section('styles')
<style>
    .hidden{display:none;}
    .strong{font-weight:600;}
    .nav-link > br {display:block;content:}
    .navigation-div-label{margin:5px;}
</style>
@endsection
@section('content')
<div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--head-lg">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon kt-hidden">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Overview<br>
                            <small>Get top insights about your performance for {{ date("F Y") }}</small>
                        </h3>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--tabs kt-portlet--head-xl">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-tabs nav-tabs-bold nav-tabs-line nav-tabs-line-right nav-tabs-line-brand" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-to-revenue" role="tab" onclick="renderTable('revenue')">
                                    <div class="navigation-div-label">
                                        Total Revenue<br/>
                                        <h5 class="strong kt-font-success" id="total_revenue">
                                            ₱ 0.00<br/>
                                            <small class="kt-label-font-color-2" id="current_revenue">₱ 0.00 this month</small>
                                        </h5>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-to-enrollments" role="tab" onclick="renderTable('enrollment')">
                                    <div class="navigation-div-label">
                                        Total Enrollments<br/>
                                        <h5 class="strong kt-font-success" id="total_enrolled">
                                            0<br/>
                                            <small class="kt-label-font-color-2">0 this month</small>
                                        </h5>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-to-provider-video-on-demand-ratings" role="tab" onclick="renderTable('ratingVideoOnDemand')">
                                    <div class="navigation-div-label" >
                                        Video on Demand Rating<br/>
                                        <h5 class="strong kt-font-success" id="total_ratings_course">
                                            0.0<br/>
                                            <small class="kt-label-font-color-2">0 ratings this month</small>
                                        </h5>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-to-provider-webinar-ratings" role="tab" onclick="renderTable('ratingWebinar')">
                                    <div class="navigation-div-label" >
                                        Webinar Rating<br/>
                                        <h5 class="strong kt-font-success" id="total_ratings_webinar">
                                            0.0<br/>
                                            <small class="kt-label-font-color-2">0 ratings this month</small>
                                        </h5>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-to-revenue">
                            <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Revenue Report for the Month',route:'/data/pdf/provider/overview/revenue/month', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            &nbsp; &nbsp; See details who purchased your courses
                            <div class="kt-space-10"></div>
                            <div class="kt-portlet kt-portlet--bordered">
                                <div class="kt-portlet__body kt-portlet__body--fit">
                                    <div class="kt-datatable kt-datatable--default" id="revenue-datatable"></div>
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn-label-info btn-sm" style="margin:auto;" onclick="window.location='/provider/revenue'">Payout Report <i class="la la-angle-double-right"></i></button>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-to-enrollments">
                            <div class="kt-portlet kt-portlet--bordered">
                                <div class="kt-portlet__body kt-portlet__body--fit">
                                    <div class="kt-datatable kt-datatable--default" id="enrollment-datatable"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-to-provider-video-on-demand-ratings">
                            <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Provider Rating for the Month',route:'/data/pdf/provider/rating/month', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            <div class="kt-space-10"></div>
                            <div class="kt-portlet kt-portlet--bordered">
                                <div class="kt-portlet__body kt-portlet__body--fit">
                                    <div class="kt-datatable kt-datatable--default" id="rating-datatable"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-to-provider-webinar-ratings">
                            <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Provider Rating for the Month',route:'/data/pdf/provider/webinar-rating/month', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            <div class="kt-space-10"></div>
                            <div class="kt-portlet kt-portlet--bordered">
                                <div class="kt-portlet__body kt-portlet__body--fit">
                                    <div class="kt-datatable kt-datatable--default" id="webinar-rating-datatable"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="customer_earnings_modal" tabindex="-1" role="dialog" aria-labelledby="customer_earnings_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customer_earnings_modal_label">Earning Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body customer_earning_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end:Modal -->
@include('template.modal.pdf')
@endsection
@section("scripts")
<script src="{{asset('js/overview/overview-page.js')}}" type="text/javascript"></script>
@endsection
