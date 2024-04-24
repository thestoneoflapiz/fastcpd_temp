@extends('template.master_superadmin')
@section('title', 'Verification')
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
                            <h3 class="kt-portlet__head-title">Webinars</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item"  data-toggle="kt-tooltip" data-placement="top" title="Filter Data">
                                    <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                                </li>
                                <li class="nav-item"  data-toggle="kt-tooltip" data-placement="top" title="Hide & Show Columns">
                                    <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end:Feature action buttons -->
                    <div class="row kt-margin-t-20 kt-margin-l-20 kt-margin-r-20" id="filter_row"></div>
                    <div class="kt-portlet__body">
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
                <h3><small>You're trying to re-draft the webinar</small><br><div id="webinar_title"></div><br/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="reject_webinar_action()" data-dismiss="modal">Re-Draft Webinar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- end:Reject -->
<div class="modal fade" id="session_links-modal" tabindex="-1" role="dialog" aria-labelledby="kt_searchlabel_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kt_searchlabel_modal">Provide Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="session_links-modal-submit" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="contacts-modal" tabindex="-1" role="dialog" aria-labelledby="kt_searchlabel_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kt_searchlabel_modal">Contacts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- begin:DataTable Modals -->
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_updated_at"> Date Updated
                            <span></span>
                        </label>
                        <div id="updated_at_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection">
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm">
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_title"> Webinar
                            <span></span>
                        </label>
                        <div id="title_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_offering"> Offering
                            <span></span>
                        </label>
                        <div id="offering_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_offering">
                                <option value="both">Both</option>
                                <option value="with">With CPD Units</option>
                                <option value="without">Without CPD Units</option>
                            </select>
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_provider"> Provider
                            <span></span>
                        </label>
                        <div id="provider_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_provider_status"> Provider Status
                            <span></span>
                        </label>
                        <div id="provider_status_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_provider_status">
                                <option value="all">All</option>
                                <option value="affiliated">Affiliated</option>
                                <option value="unaffiliated">Unaffiliated</option>
                            </select>
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_type"> Webinar Type
                            <span></span>
                        </label>
                        <div id="type_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_type">
                                <option value="all">All</option>
                                <option value="official">Official</option>
                                <option value="promotional">Promotional</option>
                            </select>
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_fast_cpd_status"> Status
                            <span></span>
                        </label>
                        <div id="fast_cpd_status_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_fast_cpd_status">
                                <option value="draft">Draft</option>
                                <option value="in-review">In-Review</option>
                                <option value="published">Published</option>
                                <option value="live">Live</option>
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_updated_at"> Date Updated
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_title"> Webinar
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_offering"> Offering
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_provider"> Provider
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_provider_status"> Provider Status
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_type"> Webinar type
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_fast_cpd_status"> Status
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
<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
<!-- end:DataTable Modals -->
<!-- end:Modal -->
@endsection
@section("scripts")
<script src="{{asset('js/superadmin/verification/webinars-page.js')}}" type="text/javascript"></script>
@endsection
