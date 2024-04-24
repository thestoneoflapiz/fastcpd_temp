@extends('template.master_superadmin')
@section('title', 'Reports')
@section('styles')
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}
    .hidden{display:none;}
</style>
@endsection
@section('content')
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Purchase Items</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                    <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Report - Purchase Items',route:'/data/pdf/superadmin/report/items', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="row kt-margin-l-20 kt-margin-r-20">
                            <div class="col-sm-3 col-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="start_date" id="start_date" readonly placeholder="Select Starting Date" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="end_date" id="end_date" readonly placeholder="Select Ending Date" />
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="form-group">
                                    <select class="form-control kt-select2" id="provider" name="provider" multiple="multiple">
                                            <option value="0" selected>All Providers</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end kt-margin-l-20 kt-margin-r-20 kt-margin-b-20">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable -->
                        <div class="kt-datatable" id="child_data_ajax"></div>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- begin:Modal -->
@include('template.modal.pdf')
<!-- end:Modal -->
@endsection
@section("scripts")
<script src="{{asset('js/superadmin/report/purchase-items-page.js')}}" type="text/javascript"></script>
@endsection
