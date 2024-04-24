
@extends('template.master')
@section('title', 'Logs Report')

@section('styles')
<style>
    .dropdown-menu > li > a, .dropdown-menu > .dropdown-item{display:block;}
    .margin-bottom-10 {margin-bottom:10px;}
    .hidden{display:none;}
    .padding-right-3em{padding-right:3em;}
    .big-bolder{font-size:25px !important;font-weight:600 !important;}
    .kt-demo-icon .kt-demo-icon__preview i{font-size:1.5rem !important;}
</style>
@endsection

@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="row">
                <div class="col-lg-3">
                    <div class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate-fast">
                        <div class="kt-portlet__body">
                            <div class="kt-iconbox__body">
                                <div class="kt-iconbox__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                            <path d="M12.4208204,17.1583592 L15.4572949,11.0854102 C15.6425368,10.7149263 15.4923686,10.2644215 15.1218847,10.0791796 C15.0177431,10.0271088 14.9029083,10 14.7864745,10 L12,10 L12,7.17705098 C12,6.76283742 11.6642136,6.42705098 11.25,6.42705098 C10.965921,6.42705098 10.7062236,6.58755277 10.5791796,6.84164079 L7.5427051,12.9145898 C7.35746316,13.2850737 7.50763142,13.7355785 7.87811529,13.9208204 C7.98225687,13.9728912 8.09709167,14 8.21352549,14 L11,14 L11,16.822949 C11,17.2371626 11.3357864,17.572949 11.75,17.572949 C12.034079,17.572949 12.2937764,17.4124472 12.4208204,17.1583592 Z" fill="#000000"/>
                                        </g>
                                    </svg>
                                </div>
                                <div class="kt-iconbox__desc">
                                    <h3 class="kt-iconbox__title">
                                        <a class="kt-link" href="#">Activity</a>
                                    </h3>
                                    <div class="kt-iconbox__content">
                                        Logs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head kt-portlet__head--noborder  kt-ribbon kt-ribbon--clip kt-ribbon--right kt-ribbon--border-dash-hor kt-ribbon--success">
                            <div class="kt-ribbon__target" style="top: 15px; height: 45px;">
                                <span class="kt-ribbon__inner"></span><i class="fa fa-star"></i>
                            </div>
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Guidelines
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body kt-portlet__body--fit-top">
                            Activity logs is a -- 
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <!-- begin:Feature action buttons -->
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Activity Logs
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="btn btn-success btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#kt_hideshow_modal"><i class="fa fa-eye-slash"></i></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end:Feature action buttons -->

                        <div class="row kt-margin-t-20 kt-margin-l-20 kt-margin-r-20" id="filter_row"></div>

                        <!-- end:Select All or Select Row Action buttons -->
                        <div class="kt-portlet__body">
                            <div class="kt-portlet__body kt-portlet__body--fit">
                                <!--begin: Datatable -->
                                <div class="kt-datatable kt-datatable--default" id="logs_ajax_data"></div>
                                <!--end: Datatable -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            <input type="checkbox" id="filter_module"> Module
                            <span></span>
                        </label>
                        <div id="module_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_activity"> Activity
                            <span></span>
                        </label>
                        <div id="activity_append" class="margin-bottom-10 hidden">
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
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="filter_by"> By
                            <span></span>
                        </label>
                        <div id="by_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="filter_created_at"> At
                            <span></span>
                        </label>
                        <div id="created_at_append" class="margin-bottom-10 hidden">
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
                            <input type="checkbox" id="check_module"> Module
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_activity"> Activity
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" id="check_by"> By
                            <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                            <input type="checkbox" id="check_created_at"> At
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
@section('scripts')
<script>

    "use strict";
    // Class definition
    var KTDatatableRemote = function() {
        
        var logsTable = function() {

            var dataSource = {
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/api/logs',
                            map: function(raw) {
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
                        field: 'module',
                        title: 'Module',
                        textAlign: 'center',
                        width: 50,
                        template: function(row){
                            var icon;
                            switch (row.module) {
                                case "sms":
                                    icon = '<i class="fa fa-sms kt-font-brand"></i>';
                                    break;

                                case "tokens":
                                    icon = '<i class="fa fa-key kt-font-success"></i>';
                                    break;
                            
                                case "users":
                                    icon = '<i class="fa fa-users kt-font-danger"></i>';
                                    break;
                            
                                case "login":
                                    icon = '<i class="fa fa-user-check kt-font-warning"></i>';
                                    break;
                            
                                default:
                                    icon = '<i class="fa fa-mug-hot kt-font-danger"></i>';
                                    break;
                            }

                            return '<div class="kt-demo-icon"><div class="kt-demo-icon__preview">'+icon+'</div></div>';
                        }
                    }, {
                        field: 'activity',
                        title: 'Activity',
                    }, {
                        field: 'by',
                        title: 'By',
                    }, {
                        field: 'created_at',
                        title: 'At',
                    }],
            };

            var datatable = $('#logs_ajax_data').KTDatatable({...dataSource, ...dataStructure});

            $("#kt_display ").click(function(){
                if($("#check_module").is(':checked')){
                    datatable.columns('module').visible(false);
                }else{
                    datatable.columns('module').visible(true);
                }

                if($("#check_activity").is(':checked')){
                    datatable.columns('activity').visible(false);
                }else{
                    datatable.columns('activity').visible(true);
                }

                if($("#check_by").is(':checked')){
                    datatable.columns('by').visible(false);
                }else{
                    datatable.columns('by').visible(true);
                }

                if($("#check_created_at ").is(':checked')){
                    datatable.columns('created_at').visible(false);
                }else{
                    datatable.columns('created_at').visible(true);
                }

                datatable.reload();
            });

            $("#kt_filter").click(function(){

                var dataApi = {
                    module: {
                        filter: $("#filter_module").is(':checked') ? $("#module_append select").val() : "=",
                        values: $("#filter_module").is(':checked') ? ($("#module_append input").val() ? $("#module_append input").val().split(",") : null) : null,
                    },
                    activity: {
                        filter: $("#filter_activity").is(':checked') ? $("#activity_append select").val() : "=",
                        values: $("#filter_activity").is(':checked') ? ($("#activity_append input").val() ? $("#activity_append input").val().split(",") : null) : null,
                    },
                    by: {
                        filter: $("#filter_by").is(':checked') ? $("#by_append select").val() : "=",
                        values: $("#filter_by").is(':checked') ? ($("#by_append input").val() ? $("#by_append input").val().split(",") : null) : null,
                    },
                    created_at: {
                        filter: $("#filter_created_at").is(':checked') ? $("#created_at_append select").val() : "=",
                        values: $("#filter_created_at").is(':checked') ? ($("#created_at_append input").val() ? $("#created_at_append input").val().split(",") : null) : null,
                    },
                };

                setTimeout(() => {
                    $.ajax({
                        url: '/api/session-logs',
                        data: dataApi,
                        success: function(response){
                            showFilterBox(dataApi, datatable, "logs");
                            datatable.reload();
                        }
                    });
                }, 500);
            });
        };

        return {
            // public functions
            init: function() {
                logsTable();
            },
        };
    }();

    jQuery(document).ready(function() {
        KTDatatableRemote.init();

        $("#filter_module").click(function(){
            if($("#filter_module").is(":checked")){
                $("#module_append").slideDown(200);
            }else{
                $("#module_append").slideUp(200);
            }
        });

        $("#filter_activity").click(function(){
            if($("#filter_activity").is(":checked")){
                $("#activity_append").slideDown(200);
            }else{
                $("#activity_append").slideUp(200);
            }
        });

        $("#filter_by").click(function(){
            if($("#filter_by").is(":checked")){
                $("#by_append").slideDown(200);
            }else{
                $("#by_append").slideUp(200);
            }
        });

        $("#filter_created_at").click(function(){
            if($("#filter_created_at").is(":checked")){
                $("#created_at_append").slideDown(200);
            }else{
                $("#created_at_append").slideUp(200);
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
