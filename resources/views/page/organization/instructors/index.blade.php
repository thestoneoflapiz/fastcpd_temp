@extends('template.master_provider')
@section('title', _current_provider()->name.' — Instructors')
@section('styles')
<style>
    .dropdown-menu > li > a, .dropdown-menu > .dropdown-item{display:block;}
    .margin-bottom-10 {margin-bottom:10px;}
    .hidden{display:none;}
    .padding-right-3em{padding-right:3em;}
    .big-bolder{font-size:25px !important;font-weight:600 !important;}
    .image{width:130px;height:130px;border:4px solid #fff;border-radius:3px;}
    .image_container{margin:auto;width:130px;height:130px;border-radius:5%;-webkit-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);-moz-box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);box-shadow: 0px 2px 10px -4px rgba(0,0,0,0.41);}
    .strong{font-weight:700;}
    .kt-user-card-v2 .kt-user-card-v2__pic img{border-radius:5%;min-width:55px;min-height:55px;max-width:55px;max-height:55px;}
</style>
@endsection
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-xl-2 col-md-3 col-sm-4 col-12">
                            <div class="image_container">
                                <img alt="FastCPD Provider Logo <?=_current_provider()->name?>" src="{{ _current_provider()->logo ?? asset('img/sample/noimage.png') }}" class="image"/>
                            </div>
                        </div> 
                        <div class="col-xl-10 col-md-9 col-sm-8 col-12">
                            <div class="kt-widget5__section">
                                <a class="kt-widget5__title strong" style="font-size:20px;">
                                    Your Organization Instructors
                                </a>
                                <p class="kt-widget5__desc">
                                    <span class="kt-widget5__number big-bolder kt-font-success">{{ number_format($data['totalinstructors']) }}</span>
                                    <span class="kt-widget5__sales">&nbsp;Instructors</span>

                                    &nbsp; &nbsp; &nbsp; &nbsp;

                                    <span class="kt-widget5__number big-bolder kt-font-warning">{{ number_format($data['pendinginstructors']) }}</span>
                                    <span class="kt-widget5__sales">&nbsp;Pending Invitations</span>
                                </p>
                                <p class="kt-widget5__desc">
                                    You can add, edit, and manage permission of people who have access to your organization’s account, courses, and reports here. Only add people who are part of your organization that have authorization to configure your FastCPD account.
                                </p>
                                <p class="kt-widget5__desc">
                                    They will receive an invitation in their email once you have added them here
                                </p>
                                @if(_my_provider_permission("instructors", "create"))
                                <div class="kt-widget5__info">
                                    <span><a href="javascript:;" class="kt-font-info create-instructors-button"><h5><b>+ Invite Instructor</b></h5></a></span>
                                </div>
                                @endif
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <!-- begin:Feature action buttons -->
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label"></div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            @if(_my_provider_permission("instructors", "create"))
                            <li class="nav-item">
                                <button type="button" class="btn btn-success btn-icon create-instructors-button" data-toggle="kt-tooltip" data-placement="top" title="Invite Instructor"><i class="fa fa-plus"></i></button>
                            </li>
                            @endif

                            <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Instructors',route:'/data/pdf/provider/instructors', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            </li>
                            <!-- End:: Export & Import -->
                            <li class="nav-item">
                                <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end:Feature action buttons -->

                <!-- begin:Select All or Select Row Action buttons -->
                <!-- <div class="kt-form kt-form--label-align-right kt-margin-t-20 collapse" id="kt_datatable_actions" style="margin-left:50px;">
                    <div class="row align-items-center">
                        <div class="col-xl-12">
                            <div class="kt-form__group kt-form__group--inline">
                                <div class="kt-form__label kt-form__label-no-wrap">
                                    <label class="kt-font-bold kt-font-danger-">Selected
                                        <span id="kt_datatable_selected_ids">0</span> records:</label>
                                </div>
                                <div class="kt-form__control">
                                    <div class="btn-toolbar">
                                        <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#kt_modal_fetch_id_server">Fetch Selected Records</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="row kt-margin-t-20 kt-margin-l-20 kt-margin-r-20" id="filter_row"></div>

                <!-- end:Select All or Select Row Action buttons -->
                <div class="kt-portlet__body">
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <!--begin: Datatable -->
                        <div class="kt-datatable kt-datatable--default" id="ajax_data"></div>
                        <!--end: Datatable -->
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_name"> Name
                            <span></span>
                        </label>
                        <div id="name_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection" id="name">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="Name">
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_contact"> Contact Number
                            <span></span>
                        </label>
                        <div id="contact_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_email"> Email
                            <span></span>
                        </label>
                        <div id="email_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_status"> Status
                            <span></span>
                        </label>
                        <div id="status_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_status">
                                <option value="both">Both</option>
                                <option value="inactive">Inactive</option>
                                <option value="active">Active</option>
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
                            <input type="checkbox" id="check_image"> Image
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_name"> Name
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_contact"> Contact Number
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_email"> Email
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

<div class="modal fade" id="resend_inv_modal" tabindex="-1" role="dialog" aria-labelledby="resend_inv_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resend_inv_label">Resend Invitation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resend_inv_body" style="text-align:center;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="resend_inv_send">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
@include('template.modal.delete')
@include('template.modal.pdf')
@endsection 

@section('scripts')
<script>
    var permission_edit = <?=_my_provider_permission("instructors", "edit") == true ? 1 : 0; ?>;
    var permission_delete = <?=_my_provider_permission("instructors", "delete") == true ? 1 : 0; ?>;

    var current_user_id = "{{ Auth::user()->id }}";

    $(".create-instructors-button").click(function(){
        window.location="/provider/instructor/invite";
    });

    "use strict";
    // Class definition
    var KTDatatableRemote = function() {
        // Private functions

        // basic demo
        var instructorTable = function() {

            var dataSource = {
                // datasource definition
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/provider/instructor/api/list?provider={{_current_provider()->id}}',
                            // sample custom headers
                            // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                            map: function(raw) {
                                // sample data mapping
                                var dataSet = raw;
                                if (typeof raw.data !== 'undefined') {
                                    dataSet = raw.data;
                                }
                                return dataSet;
                            },
                            method: "get",
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true,
                },
            };

            var dataStructure = {
                extensions:{
                    // selector
                    checkbox: {},
                },

                // layout definition
                layout: {
                    scroll: false,
                    footer: false,
                },

                // column sorting
                sortable: true,
                pagination: true,

                search: {
                    input: $('#generalSearch'),
                },

                // columns definition
                columns: [ 
                    {
                        field: 'id',
                        title: '',
                        sortable: false,
                        width: 30,
                        overflow: 'visible',
                        // autoHide: false,
                        template: function(row) {

                            if(permission_edit == 1){
                                return '\
                                    <div class="dropdown">\
                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">\
                                            <i class="flaticon2-gear"></i>\
                                        </a>\
                                        <div class="dropdown-menu dropdown-menu-left">\
                                            '+((row.status == "pending") ? '<a class="dropdown-item" href="javascript:resendInv({\'id\': \''+row.id+'\', \'name\':\''+row.name+'\', \'email\':\''+row.email+'\'});"><i class="la la-bolt"></i> Resend Invitation</a>' : '' )+ '\
                                            ' +(row.status == "active" ?  '<a class="dropdown-item" href="/provider/instructor/change/'+row.id+'"><i class="la la-toggle-on"></i> Deactivate</a>' : (row.status=="inactive" ? '<a class="dropdown-item" href="instructor/change/'+row.id+'"><i class="la la-toggle-off"></i> Activate</a>' : '')) +'\
                                            '+ (permission_delete == 1 ? '<a class="dropdown-item" onclick="deleteLink(event);" href="/provider/instructor/delete/" data-toggle="modal" data-target="#delete_modal" data-resource-name="'+row.name+'" data-route="/provider/instructor/delete/'+row.id+'"><i class="la la-trash"></i> Delete</a>' : '' )+'\
                                        </div>\
                                    </div>\
                                ';
                            }

                            return '\
                                <div class="dropdown">\
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">\
                                        <i class="flaticon2-gear"></i>\
                                    </a>\
                                    <div class="dropdown-menu dropdown-menu-left">\
                                        <a class="dropdown-item" href="javascript:;"><i class="la la-ban"></i> RESTRICTRED ACCESS</a>\
                                    </div>\
                                </div>\
                            ';
                        },
                    }, {
                        field: 'image',
                        title: '',
                        sortable: false,
                        width: 58,
                        template: function(row) {
                            return `<div class="kt-user-card-v2">								
                                <div class="kt-user-card-v2__pic">									
                                    <img alt="photo" src="`+(row.image ? row.image : `{{ asset("img/sample/noimage.png") }}`)+`">								
                                </div>	
                            </div>`;
                        },
                    },  {
                        field: 'name',
                        title: 'Name',
                    }, {
                        field: 'contact',
                        title: 'Contact No.',
                    }, {
                        field: 'email',
                        title: 'Email',
                        width: 150,
                    }, {
                        field: 'status',
                        title: 'Status',
                        width: 70,
                        textAlign: 'center',
                        template: function(row) {
                            var status = {
                                "inactive": {'class': 'danger'},
                                "pending": {'class': 'warning'},
                                "active": {'class': 'success'},
                            };
                            return `<span class="kt-badge kt-badge--${status[row.status].class} kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-${status[row.status].class}">${row.status.toUpperCase()}</span>`;
                        },
                    }],
            };

            var datatable = $('#ajax_data').KTDatatable({...dataSource, ...dataStructure});
            
            datatable.columns('actions').visible(false);
    
            datatable.on(
                "kt-datatable--on-click-checkbox kt-datatable--on-layout-updated",
                function(e) {
                    // datatable.checkbox() access to extension methods
                    var ids = datatable.checkbox().getSelectedId();
                    var count = ids.length;
                    $("#kt_datatable_selected_ids").html(count);
                    if (count > 0) {
                        $("#kt_datatable_actions").collapse("show");
                    } else {
                        $("#kt_datatable_actions").collapse("hide");
                    }
                }
            );
           
            $("#kt_display").click(function(){
                if($("#check_name").is(':checked')){
                    datatable.columns('name').visible(false);
                }else{
                    datatable.columns('name').visible(true);
                }
                
                if($("#check_image").is(':checked')){
                    datatable.columns('image').visible(false);
                }else{
                    datatable.columns('image').visible(true);
                }

                if($("#check_contact").is(':checked')){
                    datatable.columns('contact').visible(false);
                }else{
                    datatable.columns('contact').visible(true);
                }

                if($("#check_email").is(':checked')){
                    datatable.columns('email').visible(false);
                }else{
                    datatable.columns('email').visible(true);
                }
                
                if($("#check_verified").is(':checked')){
                    datatable.columns('verified').visible(false);
                }else{
                    datatable.columns('verified').visible(true);
                }

                if($("#check_status").is(':checked')){
                    datatable.columns('status').visible(false);
                }else{
                    datatable.columns('status').visible(true);
                }

                if($("#check_added_by").is(':checked')){
                    datatable.columns('added_by').visible(false);
                }else{
                    datatable.columns('added_by').visible(true);
                }

                datatable.reload();
            });

            $("#kt_filter").click(function(){

                var dataApi = {
                    name: {
                        filter: $("#filter_name").is(':checked') ? $("#name_append select").val() : "=",
                        values: $("#filter_name").is(':checked') ? ($("#name_append input").val() ? $("#name_append input").val().split(",") : null) : null,
                    },
                    contact: {
                        filter: $("#filter_contact").is(':checked') ? $("#contact_append select").val() : "=",
                        values: $("#filter_contact").is(':checked') ? ($("#contact_append input").val() ? $("#contact_append input").val().split(","): null) : null,
                    },
                    email: {
                        filter: $("#filter_email").is(':checked') ? $("#email_append select").val() : "=",
                        values: $("#filter_email").is(':checked') ? ($("#email_append input").val() ? $("#email_append input").val().split(",") : null) : null,
                    },
                    status: {
                        filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : "both",
                    },
                };
            
                setTimeout(() => {
                    $.ajax({
                        url: '/api/session',
                        data: dataApi,
                        success: function(response){
                            showFilterBox(dataApi, datatable, "instructors");
                            datatable.reload();
                        }
                    });
                }, 500);
            });

        };

        return {
            // public functions
            init: function() {
                instructorTable();
            },
        };
    }();

    jQuery(document).ready(function() {
        KTDatatableRemote.init();

        $("#filter_name").click(function(){
            if($("#filter_name").is(":checked")){
                $("#name_append").slideDown(200);
            }else{
                $("#name_append").slideUp(200);
            }
        });

        $("#filter_role").click(function(){
            if($("#filter_role").is(":checked")){
                $("#role_append").slideDown(200);
            }else{
                $("#role_append").slideUp(200);
            }
        });

        $("#filter_contact").click(function(){
            if($("#filter_contact").is(":checked")){
                $("#contact_append").slideDown(200);
            }else{
                $("#contact_append").slideUp(200);
            }
        });

        $("#filter_email").click(function(){
            if($("#filter_email").is(":checked")){
                $("#email_append").slideDown(200);
            }else{
                $("#email_append").slideUp(200);
            }
        });

        $("#filter_added_by").click(function(){
            if($("#filter_added_by").is(":checked")){
                $("#added_by_append").slideDown(200);
            }else{
                $("#added_by_append").slideUp(200);
            }
        });

        $("#filter_status").click(function(){
            if($("#filter_status").is(":checked")){
                $("#status_append").slideDown(200);
            }else{
                $("#status_append").slideUp(200);
            }
        });

        $("#filter_verified").click(function(){
            if($("#filter_verified").is(":checked")){
                $("#verified_append").slideDown(200);
            }else{
                $("#verified_append").slideUp(200);
            }
        });

        $(".selection").change(function(e){
            var value = e.target.value;
            if(value=="empty" || value=="!empty"){
                $(this).next("input").attr("disabled", "disabled");
                $(this).next("input").val("");
            }else{
                $(this).next("input").removeAttr("disabled", "disabled");
            }            
        });
    });

    /**
     * Resend Inivitation
     * 
     */
    var resend_instructor_id = null;
    function resendInv(data){
        resend_instructor_id = data.id;
        $("#resend_inv_body").html(`Are you sure you want to resend invitation to<br/><b>${data.name} — ${data.email}</b>?`);
        $("#resend_inv_modal").modal("show");
    }

    $("#resend_inv_send").click(function(){
        $("#resend_inv_modal").modal("hide");
        if(resend_instructor_id){
            $.ajax({
                method: "POST",
                url: "/provider/instructor/resend",
                data: {
                    _token: "{{ csrf_token() }}",
                    instructor: resend_instructor_id,
                    provider: "{{ _current_provider()->id }}"
                },
                success: function(){
                    toastr.success("Success!", "Email Invitation has been sent!");
                },
                error: function(response){
                    var body = response.responseJSON;
                    if(body.hasOwnProperty("message")){
                        toastr.error("Error!", body.message);
                        return;
                    }

                    toastr.error("Error!", "Something went wrong! Please try again later.");
                },
            });
            resend_instructor_id = null;
        }else{
            toastr.error("Error!", "Something went wrong! Please try again later.");
        }
    });
</script>
@endsection
