@extends('template.master_provider')
@section('title', _current_provider()->name.' — Users')
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
                                    Your Organization Users
                                </a>
                                <p class="kt-widget5__desc">
                                    <span class="kt-widget5__number big-bolder kt-font-success">{{ number_format($data['totalusers']) }}</span>
                                    <span class="kt-widget5__sales">&nbsp;Users</span>

                                    &nbsp; &nbsp; &nbsp; &nbsp;

                                    <span class="kt-widget5__number big-bolder kt-font-warning">{{ number_format($data['pendingusers']) }}</span>
                                    <span class="kt-widget5__sales">&nbsp;Pending Invitations</span>
                                </p>
                                <p class="kt-widget5__desc">
                                    You can add, edit, and manage permission of people who have access to your organization’s account, courses, and reports here. Only add people who are part of your organization that have authorization to configure your FastCPD account.
                                </p>
                                <p class="kt-widget5__desc">
                                    They will receive an invitation in their email once you have added them here
                                </p>
                                @if(_my_provider_permission("users", "create"))
                                <div class="kt-widget5__info">
                                    <span><a href="javascript:;" class="kt-font-info create-users-button"><h5><b>+ Invite User</b></h5></a></span>
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
                            @if(_my_provider_permission("users", "create"))
                            <li class="nav-item">
                                <button type="button" class="btn btn-success btn-icon create-users-button" data-toggle="kt-tooltip" data-placement="top" title="Invite User"><i class="fa fa-plus"></i></button>
                            </li>
                            @endif

                            <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Users',route:'/data/pdf/provider/users', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            </li>
                            <!-- Begin:: Export & Import -->
                            <!-- <li class="nav-item">
                                <button type="button" class="btn kt-label-bg-color-1 btn-icon" id="export" onclick="window.open('data/csv/users', '_blank');" data-toggle="kt-tooltip" data-placement="top" title="Export"><i class="fa fa-file-csv"></i></button>
                            </li> -->
                            <!-- <li class="nav-item">
                                <button type="button" class="btn kt-label-bg-color-2 btn-icon" id="import" onclick="window.location.href='data/import?rc=users'" data-toggle="kt-tooltip" data-placement="top" title="Import"><i class="fa fa-file-import"></i></button>&nbsp;&nbsp; &nbsp;&nbsp;
                            </li> -->
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="filter_role"> Role
                            <span></span>
                        </label>
                        <div id="role_append" class="margin-bottom-10 hidden">
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_image"> Image
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_name"> Name
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_role"> Role
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

<!-- <div class="modal fade" id="kt_modal_fetch_id_server" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="kt-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-height="200">
                    <ul class="kt-datatable_selected_ids"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="set_perm_modal" tabindex="-1" role="dialog" aria-labelledby="set_perm_label" aria-hidden="true">
    <form class="kt-form" id="user_set_perm_form">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="set_perm_label">Setting up Permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="set_perm_body">
                    <div class="form-group row">
                        <div class="col-xl-12 col-md-12 col-12">
                            <b>Co-Provider Permission Checklist</b>
                            <span class="form-text text-muted">Please check to give selected users access to the following features:</span>
                        </div>
                        <div class="col-xl-12 col-md-12 col-12">
                            <br/>
                            <div class="kt-checkbox-list row">
                                <div class="col-xl-6 col-md-6 col-sm-6 col-12">
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                        <input type="checkbox" name="permissions[]" value="create_courses"> Create Courses
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" name="permissions[]" value="edit_courses"> Edit Courses
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                        <input type="checkbox" name="permissions[]" value="create_webinars"> Create Webinars
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" name="permissions[]" value="edit_webinars"> Edit Webinars
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                        <input type="checkbox" name="permissions[]" value="view_overview"> View Overview
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-6 col-12">

                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                        <input type="checkbox" name="permissions[]" value="view_revenue"> View Revenue
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" name="permissions[]" value="view_review"> View Review
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                        <input type="checkbox" name="permissions[]" value="edit_provider_profile"> Edit Provider Profile
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                        <input type="checkbox" name="permissions[]" value="manage_instructors"> Manage Instructors
                                        <span></span>
                                    </label>
                                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                                        <input type="checkbox" name="permissions[]" value="manage_users"> Manage Users
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>                                
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="set_perm_send">Set Permission</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </form>
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
    var permission_edit = <?=_my_provider_permission("users", "edit") == true ? 1 : 0; ?>;
    var permission_delete = <?=_my_provider_permission("users", "delete") == true ? 1 : 0; ?>;

    var current_user_id = "{{ Auth::user()->id }}";

    $(".create-users-button").click(function(){
        window.location="/provider/user/invite";
    });

    "use strict";
    // Class definition
    var KTDatatableRemote = function() {
        // Private functions

        // basic demo
        var userTable = function() {

            var dataSource = {
                // datasource definition
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/provider/user/api/list?provider={{_current_provider()->id}}',
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
                    // {
                    //     field: 'id',
                    //     title: '#',
                    //     sortable: 'asc',
                    //     width: 20,
                    //     type: 'number',
                    //     selector: {class: 'kt-checkbox--solid'},
                    //     sortable: false,
                    //     // visible: false,
                    //     textAlign: 'center',
                    // }, 
                    {
                        field: 'id',
                        title: '',
                        sortable: false,
                        width: 30,
                        overflow: 'visible',
                        // autoHide: false,
                        template: function(row) {

                            if(row.id != current_user_id && permission_edit == 1 && row.role != "ADMIN"){
                                return '\
                                    <div class="dropdown">\
                                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">\
                                            <i class="flaticon2-gear"></i>\
                                        </a>\
                                        <div class="dropdown-menu dropdown-menu-left">\
                                            '+((row.status == "pending") ? '<a class="dropdown-item" href="javascript:resendInv({\'id\': \''+row.id+'\', \'name\':\''+row.name+'\', \'email\':\''+row.email+'\'});"><i class="la la-bolt"></i> Resend Invitation</a>' : '' )+ '\
                                            <a class="dropdown-item" href="javascript:setPermission({\'id\': \''+row.id+'\', \'name\':\''+row.name+'\', \'perms\':\''+row.permissions+'\'});"><i class="la la-key"></i> Set Permission</a>\
                                            ' +(row.status == "active" ?  '<a class="dropdown-item" href="/provider/user/change/'+row.id+'"><i class="la la-toggle-on"></i> Deactivate</a>' : (row.status=="inactive" ? '<a class="dropdown-item" href="user/change/'+row.id+'"><i class="la la-toggle-off"></i> Activate</a>' : '')) +'\
                                            '+ (permission_delete == 1 ? '<a class="dropdown-item" onclick="deleteLink(event);" href="/provider/user/delete/" data-toggle="modal" data-target="#delete_modal" data-resource-name="'+row.name+'" data-route="/provider/user/delete/'+row.id+'"><i class="la la-trash"></i> Delete</a>' : '' )+'\
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
                        field: 'role',
                        title: 'Role',
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

            // Selection of IDS
            // $("#kt_modal_fetch_id_server")
            // .on("show.bs.modal", function(e) {
            //     var ids = datatable.checkbox().getSelectedId();
            //     var c = document.createDocumentFragment();
            //     for (var i = 0; i < ids.length; i++) {
            //         var li = document.createElement("li");
            //         li.setAttribute("data-id", ids[i]);
            //         li.innerHTML = "Selected record ID: " + ids[i];
            //         c.appendChild(li);
            //     }
            //     $(e.target)
            //         .find(".kt-datatable_selected_ids")
            //         .append(c);
            // })
            // .on("hide.bs.modal", function(e) {
            //     $(e.target)
            //         .find(".kt-datatable_selected_ids")
            //         .empty();
            // });
           
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

                if($("#check_role").is(':checked')){
                    datatable.columns('role').visible(false);
                }else{
                    datatable.columns('role').visible(true);
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
                    role: {
                        filter: $("#filter_role").is(':checked') ? $("#role_append select").val() : "=",
                        values: $("#filter_role").is(':checked') ? ($("#role_append input").val() ? $("#role_append input").val().split(",") : null) : null,
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
                            showFilterBox(dataApi, datatable, "users");
                            datatable.reload();
                        }
                    });
                }, 500);
            });

        };

        return {
            // public functions
            init: function() {
                userTable();
            },
        };
    }();

    var SetPermissionForm = function () {
        var input_validations = function () {
            validator = $("#user_set_perm_form" ).validate({
                // define validation rules
                rules: {
                    'permissions[]': {
                        required: true,
                    }, 
                },
                messages: {
                    'permissions[]':{
                        required: 'Please choose at least one(1) from permission checklist.'
                    },
                },
                
                //display error alert on form submit  
                invalidHandler: function(event, validator) {             
                    toastr.error('Required!', 'Please choose at least one(1) from permission checklist!');
                },
                
                submitHandler: function (form) {

                    permissions = [];
                    $('input[name="permissions[]"]:checked').each(function() {
                        permissions.push(this.value);
                    })

                    $("#set_perm_send").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                    if(set_perm_id){
                        var set_perm_send = $.ajax({
                            url: '/provider/user/permission',
                            data: {
                                user: set_perm_id,
                                permissions: permissions,
                            },
                            success: function(){
                                toastr.success('Success!', 'Permission is set!');
                                
                                setTimeout(() => {
                                    set_perm_id = null;
                                    location.reload();
                                }, 2500);
                            },
                            error: function(response){
                                $("#set_perm_send").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

                                var body = response.responseJSON;
                                if(body.hasOwnProperty("message")){

                                    toastr.error('Error!', body.message);
                                    return;
                                }

                                toastr.error('Error!', 'Something went wrong! Unable to set permission. Please try again later.');
                            }
                        });
                    }else{
                        toastr.error('Error!', 'Unable to set permission! Missing USER. Please <b>refresh</b> browser & try again.');
                    }
                }
            });       
        }

        return {
            // public functions
            init: function() {
                input_validations(); 
            }
        };
    }();
   
    jQuery(document).ready(function() {
        KTDatatableRemote.init();
        SetPermissionForm.init();

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
    var resend_user_id = null;
    function resendInv(data){
        resend_user_id = data.id;
        $("#resend_inv_body").html(`Are you sure you want to resend invitation to<br/><b>${data.name} — ${data.email}</b>?`);
        $("#resend_inv_modal").modal("show");
    }

    $("#resend_inv_send").click(function(){
        $("#resend_inv_modal").modal("hide");
        if(resend_user_id){
            $.ajax({
                method: "POST",
                url: "/provider/user/resend",
                data: {
                    _token: "{{ csrf_token() }}",
                    user: resend_user_id,
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
            resend_user_id = null;
        }else{
            toastr.error("Error!", "Something went wrong! Please try again later.");
        }
    });

    /**
     * Set Perm
     * 
     */
    var set_perm_id = null;
    function setPermission(data){
        set_perm_id = data.id;
        $('#set_perm_label').html(`Setting up Permission for ${data.name}`);
        $('input[name="permissions[]"]').each(function() {
            if(jQuery.inArray(this.value, data.perms.split(',')) >= 0){
                $(this).prop("checked", true);
            }else{
                $(this).prop("checked", false);
            }
        });

        $("#set_perm_modal").modal("show");
    }
</script>
@endsection
