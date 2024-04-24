@extends('template.master_superadmin')
@section('title', 'Purchase Settings')
@section('styles')
<style>
    .hidden{display:none;}
    .margin-bottom-10 {margin-bottom:10px;}
    .recenter{text-align:center;margin:2rem auto;}
    .strong{font-weight:700;}
    .dropdown-menu > li > a, .dropdown-menu > .dropdown-item{display:block;color:#74788d}
</style>
@endsection
@section('content')
{{ csrf_field() }}
<div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Site-Wide Vouchers
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="btn btn-success btn-icon create-users-button" data-toggle="kt-tooltip" data-placement="top" title="Create Voucher" onclick="window.location='/superadmin/settings/vouchers/form'"><i class="fa fa-plus"></i></button>
                                </li>
                                <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                    <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Site-Wide Vouchers',route:'/data/pdf/superadmin/settings/vouchers', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row kt-margin-t-20 kt-margin-l-20 kt-margin-r-20" id="filter_row"></div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable kt-datatable--default" id="promotion_datatable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--begin::Modal-->
<div class="modal fade" id="kt_filter_modal" tabindex="-1" role="dialog" aria-labelledby="kt_searchlabel_modal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kt_searchlabel_modal">Filter Preferences 
                    <small><span class="form-text text-muted">For multi-search filter; add a comma at the end of your value.<br/><br/>Ex: Eli, Marco, Polo, Sam, Mead </span></small>
                </h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_voucher_code"> Voucher Code
                            <span></span>
                        </label>
                        <div id="voucher_code_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection" id="voucher_code">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="Voucher Code">
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_discount"> Discount
                            <span></span>
                        </label>
                        <div id="discount_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" >
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_start_date"> Start Date
                            <span></span>
                        </label>
                        <div id="start_date_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" >
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_end_date"> End Date
                            <span></span>
                        </label>
                        <div id="end_date_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" >
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_created_by"> Created By
                            <span></span>
                        </label>
                        <div id="created_by_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" >
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_status"> Status
                            <span></span>
                        </label>
                        <div id="status_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_status">
                                <option value="all">All</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="active">Active</option>
                                <option value="ended">Ended</option>
                                <option value="in-review">In-Review</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_type"> Type
                            <span></span>
                        </label>
                        <div id="type_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_type">
                                <option value="all">All</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="active">Active</option>
                                <option value="ended">Ended</option>
                                <option value="in-review">In-Review</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="kt_filter" data-dismiss="modal">Filter</button>
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
                            <input type="checkbox" id="check_voucher_code"> Voucher Code
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_discount"> Discount
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_start_date"> Start Date
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_end_date"> End date
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_created_by"> Created By
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_status"> Status
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_type"> Type
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

<!-- begin:View Course -->
<div class="modal fade" id="view-course-modal" tabindex="-1" role="dialog" aria-labelledby="view-course-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="view-course-body" style="text-align:center;">
            </div>
        </div>
    </div>
</div>
<!-- end:View Course -->
<!--end::Modal-->
@include('template.modal.delete')
@include('template.modal.pdf')

@endsection
@section("scripts")
<script src="{{asset('js/superadmin/purchase_settings/voucher-page.js')}}" type="text/javascript"></script>
@endsection
