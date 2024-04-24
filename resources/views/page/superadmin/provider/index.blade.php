@extends('template.master_superadmin')
@section('title', 'Users')
@section('styles')
<style>
    .centered{margin:auto;}
    .minimize > i{font-size:2rem !important;}
    .hidden{display:none;}
</style>
@endsection
@section('content')
<meta name="_token" content="{{ csrf_token() }}">
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label"> 
                            <h3 class="kt-portlet__head-title">Providers</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                    <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Users - Providers',route:'/data/pdf/superadmin/users/providers', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
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
<!-- begin:Reject -->
<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="approve-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resend_inv_body" style="text-align:center;">
                <h3><small>Are you sure to approve </small><br><div id="approve-modal-title"></div></h3>
                <div class="kt-space-20"></div>
                <div class="kt-space-30"></div>
                <input type="hidden" id="approve-provider-id" name="approve-provider-id"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="submitApproval()">Approve</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- end:Reject -->
<!-- begin:Reject -->
<div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="reject-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resend_inv_body" style="text-align:center;">
                <h3><small>You're trying to reject the pending profile for </small><br><div id="reject-modal-title"></div><small>Can you tell us why?</small></h3>
                <div class="kt-space-20"></div>
                <div class="kt-space-30"></div>
                <input type="hidden" id="reject-provider-id" name="reject-provider-id"/> 
                <textarea id="reject-modal-notes" class="form-control" placeholder="Please explain it elaborately..."></textarea>
                <div class="kt-space-30"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="submitRejection()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- end:Reject -->
<!-- end:Modal -->
@endsection
@section("scripts")
<script src="{{asset('js/superadmin/users/provider-page.js')}}" type="text/javascript"></script>
@endsection
