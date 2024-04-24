@extends('template.master')
@section('title', 'Users Management')

@section('styles')
<style>
    .dropdown-menu > li > a, .dropdown-menu > .dropdown-item{display:block;}
    .margin-bottom-10 {margin-bottom:10px;}
    .hidden{display:none;}
    .padding-right-3em{padding-right:3em;}
    .big-bolder{font-size:25px !important;font-weight:600 !important;}
</style>
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="kt-widget5">
                        <div class="kt-widget5__item">
                            <div class="kt-widget5__content">
                                <div class="kt-widget5__pic">
                                    <img class="kt-widget7__img" src="{{asset('media/products/product27.jpg')}}" alt="">
                                </div>
                                <div class="kt-widget5__section">
                                    <a href="#" class="kt-widget5__title" style="font-size:20px;">
                                        Application Users
                                    </a>
                                    <p class="kt-widget5__desc">
                                        Statistics
                                    </p>
                                    <div class="kt-widget5__info">
                                        <span><a href="/users/add" class="kt-font-info"><b>+ Create User</b></a></span>
                                        <!-- <span class="kt-font-info">23.08.17</span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget5__content">
                                <div class="kt-widget5__stats">
                                    <span class="kt-widget5__number big-bolder">{{$totalusers}}</span>
                                    <span class="kt-widget5__sales">Total App Users</span>
                                </div>
                                <div class="kt-widget5__stats padding-right-3em">
                                    <span class="kt-widget5__number big-bolder">5</span>
                                    <span class="kt-widget5__sales">Total Credits</span>
                                </div>
                                <div class="kt-widget5__stats">
                                    <span class="kt-widget5__number big-bolder">{{5 - $totalusers}}</span>
                                    <span class="kt-widget5__votes">Rem. Credits</span>
                                </div>
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
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Users List
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="btn btn-success btn-icon" id="add" data-toggle="kt-tooltip" data-placement="top" title="Create User"><i class="fa fa-plus"></i></button>
                            </li>
                            <li class="nav-item" data-toggle="kt-tooltip" data-placement="top" title="Print">
                                <button type="button" class="btn btn-secondary btn-icon" id="print" onclick="printPDF({name:'Users',route:'data/pdf/users', method:'get'});" data-toggle="modal" data-target="#print_pdf_modal"><i class="fa fa-print"></i></button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn kt-label-bg-color-1 btn-icon" id="export" onclick="window.open('data/csv/users', '_blank');" data-toggle="kt-tooltip" data-placement="top" title="Export"><i class="fa fa-file-csv"></i></button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn kt-label-bg-color-2 btn-icon" id="import" onclick="window.location.href='data/import?rc=users'" data-toggle="kt-tooltip" data-placement="top" title="Import"><i class="fa fa-file-import"></i></button>&nbsp;&nbsp; &nbsp;&nbsp;
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
                <!-- end:Feature action buttons -->

                <!-- begin:Select All or Select Row Action buttons -->
                <div class="kt-form kt-form--label-align-right kt-margin-t-20 collapse" id="kt_datatable_actions" style="margin-left:50px;">
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
                </div>

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
                            <select class="form-control form-control-sm selection" id="first_select">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="First Name" id="first_input">
                            <br/>
                            <select class="form-control form-control-sm selection" id="last_select">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" placeholder="Last Name" id="last_input">
                        </div>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_position"> Position
                            <span></span>
                        </label>
                        <div id="position_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm selection">
                                <option value="=">Is</option>
                                <option value="!=">Is Not</option>
                                <option value="like">Contains</option>
                                <option value="nlike">Doesn't Contain</option>
                                <option value="empty">Empty</option>
                                <option value="!empty">Is Not Empty</option>
                            </select>
                            <input type="text" class="form-control form-control-sm">
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
                            <input type="checkbox" id="filter_added_by"> Added By
                            <span></span>
                        </label>
                        <div id="added_by_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_verified"> Verified
                            <span></span>
                        </label>
                        <div id="verified_append" class="margin-bottom-10 hidden">
                            <select class="form-control form-control-sm" id="select_verified">
                                <option value="both">Both</option>
                                <option value="verified">Verified</option>
                                <option value="!verified">Not Verified</option>
                            </select>
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
                            <input type="checkbox" id="check_name"> Name
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_position"> Position
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
                            <input type="checkbox" id="check_added_by"> Added By
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_verified"> Verified
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

<div class="modal fade" id="kt_modal_fetch_id_server" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div>
<!--end::Modal-->
@include('template.modal.delete')
@include('template.modal.pdf')

@endsection


@section('scripts')
<script>
    var prole = "<?=_role();?>";

    $("#add").click(function(){
        window.location="/users/add";
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
                            url: '/api/users',
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
                columns: [{
                        field: 'id',
                        title: '#',
                        sortable: 'asc',
                        width: 20,
                        type: 'number',
                        selector: {class: 'kt-checkbox--solid'},
                        sortable: false,
                        // visible: false,
                        textAlign: 'center',
                    }, {
                        field: 'actions',
                        title: '',
                        sortable: false,
                        width: 20,
                        overflow: 'visible',
                        autoHide: false,
                        template: function(row) {
                            return '\
                                <div class="dropdown">\
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">\
                                        <i class="flaticon2-gear"></i>\
                                    </a>\
                                    <div class="dropdown-menu dropdown-menu-left">\
                                        <a class="dropdown-item" href="user/edit/'+row.id+'"><i class="la la-edit"></i> Edit Details</a>\
                                        ' +(row.status == "active" ?  '<a class="dropdown-item" href="user/change/'+row.id+'"><i class="la la-toggle-on"></i> Deactivate</a>' : (row.status=="inactive" ? '<a class="dropdown-item" href="user/change/'+row.id+'"><i class="la la-toggle-off"></i> Activate</a>' : '')) +'\
                                        <a class="dropdown-item" onclick="deleteLink(event);" href="user/delete/" data-toggle="modal" data-target="#delete_modal" data-resource-name="'+row.name+'" data-route="user/delete/'+row.id+'"><i class="la la-trash"></i> Delete</a>\
                                    </div>\
                                </div>\
                            ';
                        },
                    }, {
                        field: 'name',
                        title: 'Name',
                    }, {
                        field: 'position',
                        title: 'Position',
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
                        field: 'added_by',
                        title: 'Added By',
                        template: function(row) {
                            if(row.added_by!="System"){
                                return row.added_by.first_name+ ' '+row.added_by.last_name;
                            }
                            return row.added_by;
                        },
                    }, {
                        field: 'verified',
                        title: 'Verified',
                        width: 70,
                        template: function(row) {
                           if(row.verified){
                                return "Verified";
                           }

                           return "Not Verified";
                        },
                    }, {
                        field: 'status',
                        title: 'Status',
                        width: 70,
                        textAlign: 'center',
                        template: function(row) {
                            var status = {
                                "inactive": {'class': ' kt-badge--danger'},
                                "active": {'class': ' kt-badge--success'},
                            };
                            return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--sm"></span>';
                        },
                    }],
            };

            var datatable = $('#ajax_data').KTDatatable({...dataSource, ...dataStructure});
            
            if(prole!="Superadmin"){
                datatable.columns('actions').visible(false);
            }
    
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

            $("#kt_modal_fetch_id_server")
            .on("show.bs.modal", function(e) {
                var ids = datatable.checkbox().getSelectedId();
                var c = document.createDocumentFragment();
                for (var i = 0; i < ids.length; i++) {
                    var li = document.createElement("li");
                    li.setAttribute("data-id", ids[i]);
                    li.innerHTML = "Selected record ID: " + ids[i];
                    c.appendChild(li);
                }
                $(e.target)
                    .find(".kt-datatable_selected_ids")
                    .append(c);
            })
            .on("hide.bs.modal", function(e) {
                $(e.target)
                    .find(".kt-datatable_selected_ids")
                    .empty();
            });
           
            $("#kt_display").click(function(){
                if($("#check_name").is(':checked')){
                    datatable.columns('name').visible(false);
                }else{
                    datatable.columns('name').visible(true);
                }
                
                if($("#check_position").is(':checked')){
                    datatable.columns('position').visible(false);
                }else{
                    datatable.columns('position').visible(true);
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
                        first:{
                            filter: $("#filter_name").is(':checked') ? $("#name_append #first_select").val() : "=",
                            values: $("#filter_name").is(':checked') ? ($("#name_append #first_input").val() ? $("#name_append #first_input").val().split(",") : null) : null,
                        },
                        last:{
                            filter: $("#filter_name").is(':checked') ? $("#name_append #last_select").val() : "=",
                            values: $("#filter_name").is(':checked') ? ($("#name_append #last_input").val() ? $("#name_append #last_input").val().split(",") : null) : null,
                        },
                    },
                    position: {
                        filter: $("#filter_position").is(':checked') ? $("#position_append select").val() : "=",
                        values: $("#filter_position").is(':checked') ? ($("#position_append input").val() ? $("#position_append input").val().split(",") : null) : null,
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
                    added_by: {
                        filter: $("#filter_added_by").is(':checked') ? $("#added_by_append select").val() : "=",
                        values: $("#filter_added_by").is(':checked') ? ($("#added_by_append input").val() ? $("#added_by_append input").val().split(",") : null) : null,
                    },
                    status: {
                        filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : "both",
                    },
                    verified: {
                        filter: $("#filter_verified").is(':checked') ? $("#verified_append select").val() : "both",
                    }
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

    jQuery(document).ready(function() {
        KTDatatableRemote.init();

        $("#filter_name").click(function(){
            if($("#filter_name").is(":checked")){
                $("#name_append").slideDown(200);
            }else{
                $("#name_append").slideUp(200);
            }
        });

        $("#filter_position").click(function(){
            if($("#filter_position").is(":checked")){
                $("#position_append").slideDown(200);
            }else{
                $("#position_append").slideUp(200);
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
</script>
@endsection