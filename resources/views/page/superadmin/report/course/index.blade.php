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
                            <h3 class="kt-portlet__head-title">Courses</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                    <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Report - Courses',route:'/data/pdf/superadmin/report/courses', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="row kt-margin-l-20 kt-margin-r-20 kt-margin-b-20">
                            <div class="col-sm-4 col-12">
                                <div class="row justify-content-start form-group">
                                    <div class="col-xl-8 col-md-10 col-12">
                                        <select class="form-control kt-select2" id="provider" name="provider" multiple="multiple">
                                            <option value="0" selected>All Providers</option>
                                            @foreach(_providers() as $pro)
                                                <option value="{{ $pro->id }}">{{ $pro->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="row justify-content-start form-group">
                                    <div class="col-xl-8 col-md-10 col-12">
                                        <select class="form-control kt-select2" id="type" name="type">
                                            <option value="video-on-demand">Video on Demand</option>
                                            <option value="webinar">Webinar</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="row justify-content-end form-group">
                                    <div class="col-xl-6 col-md-10 col-12">
                                        <div class="kt-input-icon kt-input-icon--left">
                                            <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable -->
                        <div class="kt-datatable kt-datatable--default" id="ajax_data"></div>
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
<script src="{{asset('js/superadmin/report/courses-page.js')}}" type="text/javascript"></script>
@endsection
