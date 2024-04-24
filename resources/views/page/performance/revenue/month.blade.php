@extends('template.master_provider')
@section('title', _current_provider()->name)
@section('styles')
<style>
    .hidden{display:none;}
    .strong{font-weight:600;}
</style>
@endsection
@section('content')
<div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon kt-hidden">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Revenue Report for <b>{{ date("F Y", strtotime($data["reported_date"])) ?? 'Not Found' }}</b>
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <h5><small>You earned</small> <span class="strong" id="month_earned">₱ 0.00</span> <small>during this period. Expected payment date is</small> <span class="strong" id="expected_date">Not Available</span></h5>
                    <div class="row">
                        <div class="col-xl-4 col-md-6 col-12">
                            <!--begin:: Widgets/Profit Share-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header">
                                        <h3 class="kt-widget14__title">
                                            Total Earnings
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Your total earnings
                                        </span>
                                    </div>
                                    <div class="kt-widget14__content">
                                        <div class="kt-widget14__chart">
                                            <div class="kt-widget14__stat"></div>
                                            <canvas id="earning_total_chart" style="height: 140px; width: 140px;"></canvas>
                                        </div>
                                        <div class="kt-widget14__legends" id="total_earning_legends">
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                                <span class="kt-widget14__stats" id="fast_revenue">18% FastCPD Organic</span>
                                            </div>
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-danger"></span>
                                                <span class="kt-widget14__stats" id="provider_revenue">30% Your Promotions</span>
                                            </div>
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                                <span class="kt-widget14__stats" id="endorser_revenue">19% Affiliate Programs</span>
                                            </div>
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-info"></span>
                                                <span class="kt-widget14__stats" id="refund">33% Refunds</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Profit Share-->
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <!--begin:: Widgets/Profit Share-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header">
                                        <h3 class="kt-widget14__title">
                                            Promotion Activity
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Your total promotion activity
                                        </span>
                                    </div>
                                    <div class="kt-widget14__content">
                                        <div class="kt-widget14__chart">
                                            <div class="kt-widget14__stat"></div>
                                            <canvas id="promotion_chart" style="height: 140px; width: 140px;"></canvas>
                                        </div>
                                        <div class="kt-widget14__legends" id="promotion_legend">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Profit Share-->
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <!--begin:: Widgets/Profit Share-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header">
                                        <h3 class="kt-widget14__title">
                                            Earnings by Course
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Your total earnings by course
                                        </span>
                                    </div>
                                    <div class="kt-widget14__content">
                                        <div class="kt-widget14__chart">
                                            <div class="kt-widget14__stat"></div>
                                            <canvas id="earning_courses_chart" style="height: 140px; width: 140px;"></canvas>
                                        </div>
                                        <div class="kt-widget14__legends" id="course_legend">
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Profit Share-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-tabs nav-tabs-bold nav-tabs-line nav-tabs-line-right nav-tabs-line-brand" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_portlet_tab_1_1" role="tab">
                                    Purchases &nbsp; <span class="strong kt-font-success" id="month_earned_2">₱ 0.00</span>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_portlet_tab_1_2" role="tab">
                                    Refunds &nbsp; <span class="strong kt-font-danger">-₱ 1,590.75</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_portlet_tab_1_1">
                            See details who purchased your courses
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <!--begin: Datatable -->
                                <div class="kt-datatable kt-datatable--default" id="ajax_data"></div>
                                <!--end: Datatable -->
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_portlet_tab_1_2">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled. Lorem Ipsum is simply dummy text.
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
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
<script>
     jQuery(document).ready(function () {
        $.ajax({
			url: '/provider/revenue-monthly/api/getDetails',
            data:{
                payout_id:`<?=$data['payout_id']?>`,
            },
			success: function(response){
               
                $("#month_earned").text("₱ "+response.data.month_earned);
                $("#month_earned_2").text("₱ "+response.data.month_earned);
                $("#expected_date").text(response.data.expected_date);
            }
        });
    });
    var reported_date = `<?=$data['reported_date']?>`;
    var payout_id = `<?=$data['payout_id']?>`;
</script>
<script src="{{asset('js/revenue/month.js')}}" type="text/javascript"></script>
@endsection
