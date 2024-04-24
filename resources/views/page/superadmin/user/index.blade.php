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
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <!-- begin:Feature action buttons -->
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Superadmin List</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Create Superadmin User">
                                    <button type="button" class="btn btn-success btn-icon" onclick="window.location='/superadmin/users/add'"><i class="fa fa-plus"></i></button>
                                </li>
                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                    <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Users - Superadmin List',route:'/data/pdf/superadmin/users/superadmin', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                                </li>
                                <li class="nav-item"  data-toggle="kt-tooltip" data-placement="top" title="Hide & Show Columns">
                                    <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
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
<!-- begin:DataTable Modals -->
<div class="modal fade" id="view-permission-modal" tabindex="-1" role="dialog" aria-labelledby="view-permission-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view-permission-modal-label">Permissions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center" id="loading-modal-form">
                    <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div>
                </div>
                <form class="kt-form kt-form--label-left" id="form" style="display:none;">
                    <meta name="_token" content="{{ csrf_token() }}">
                    <input type="hidden" id="id" name="id"/> 
                    <div class="form-group row">
                        <div class="col-xl-12">
                            <select class="form-control kt-select2" id="permissions" name="permissions" multiple="multiple" style="width:100%;">
                                <option value="announcement">Announcements</option>
                                <option value="settings">Site-Settings</option>
                                <option value="verification">Verifications</option>
                                <option value="purchase_setting">Purchase Settings</option>
                                <option value="report">Reports</option>
                                <option value="user">User</option>
                            </select>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="savePermissions()" data-dismiss="modal">Save Permission</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="kt_hideshow_modal" tabindex="-1" role="dialog" aria-labelledby="kt_hideshowlabel_modal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kt_hideshowlabel_modal">Hide Columns</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="kt-checkbox-list" id="hideshow_checklist">
                       
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_name"> Name
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_email"> Email
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_permissions"> Permissions
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_status"> Status
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="kt_display" data-dismiss="modal">Display</button>
            </div>
        </div>
    </div>
</div>
<!-- end:Modal -->
@endsection
@section("scripts")
<script src="{{asset('js/superadmin/users/user-page.js')}}" type="text/javascript"></script>
@endsection
