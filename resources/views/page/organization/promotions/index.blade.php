@extends('template.master_provider')
@section('title', _current_provider()->name.' — Promotions')
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
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-12">
            <div class="accordion accordion-solid accordion-panel accordion-toggle-svg" id="join-accordion">
                <div class="card">
                    <div class="card-header" id="headingOne3">
                        <div class="card-title {{ _current_provider()->allow_marketing ? 'collapsed' : '' }}" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="{{ _current_provider()->allow_marketing ? 'false' : 'true' }}" aria-controls="collapseOne3">
                            Join FastCPD's Marketing Programs
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div id="collapseOne3" class="collapse {{ _current_provider()->allow_marketing ? '' : 'show' }}" aria-labelledby="headingOne3">
                        <div class="card-body">
                            <div class="kt-portlet">
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            To attract new professionals to use FastCPD.com and to purchase courses like yours. We conduct marketing programs and establish partnerships that will help providers like you generate more sales. We have programs such as the following:
                                            <ul>
                                                <li>Partnering with affiliates such as advertisers, endorsers, and influencers to promote traffic and purchase</li>
                                                <li>Site-wide discounts to boost purchases</li>
                                            </ul>
                                            The complete details of our promotions and how it will affect your revenue share was discussed in detail in our provider agreement.
                                            <br/><br/>
                                            You may or may not join these programs. Joining our programs may greatly increase purchases of your courses.
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="kt-space-20"></div>
                                            <div class="kt-checkbox-list">
                                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--warning">
                                                    <input type="checkbox" name="joining" {{ _current_provider()->allow_marketing ? "checked" : "" }}> I accept
                                                    <span></span> 
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-space-20"></div>
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Promotions
                        </h3>
                    </div>
                    @if($total > 0)
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-success btn-icon create-users-button" data-toggle="kt-tooltip" data-placement="top" title="Create Voucher" onclick="window.location='/provider/promotions/form'"><i class="fa fa-plus"></i></button>
                            </li>
                            <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Promotions',route:'/data/pdf/provider/promotions', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="row kt-margin-t-20 kt-margin-l-20 kt-margin-r-20" id="filter_row"></div>
                <div class="kt-portlet__body">
                    @if($total == 0)
                    <div class="row justify-content-center">
                        <div class="col-6 recenter">
                            <i class="flaticon-open-box" style="font-size:5em;"></i>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <h2>Create your first voucher</h2>
                            <div class="kt-space-20"></div>
                            <div class="kt-space-20"></div>
                            <button type="button" class="btn btn-info" data-toggle="kt-tooltip" data-placement="top" title="Click here to Create" onclick="window.location='/provider/promotions/form'">&nbsp;<i class="fa fa-plus"></i><span class="hidden--sm">Create Voucher</span></button>
                        </div>
                    </div>
                    @else
                    <div class="kt-datatable kt-datatable--default" id="promotion_datatable"></div>
                    @endif
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
@section('scripts')
<script src="{{asset('js/provider/promotions.js')}}" type="text/javascript"></script>
@endsection 
