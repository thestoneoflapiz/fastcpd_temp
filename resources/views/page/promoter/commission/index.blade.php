@extends('template.master_promoter')
@section('title', 'Promoter')
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
                <!--begin:: Widgets/Best Sellers-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Commission
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="btn btn-info btn-icon" data-toggle="modal" data-target="#kt_filter_modal"><i class="fa fa-search"></i></button>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_widget5_courses_content" role="tab" aria-selected="true">
                                        See All
                                    </a>
                                </li> -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_widget5_tab2_content" role="tab" aria-selected="false">
                                        Webinar
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget5_courses_content" aria-expanded="true">
                                <div class="kt-datatable kt-datatable--default" id="ajax_datatable_commission"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Best Sellers-->
            </div>
        </div>
    </div>
</div>

<!-- begin:Modal -->
@include('template.modal.delete')
@include('template.modal.pdf')
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
                            <input type="checkbox" id="filter_created_at"> Purchase Date
                            <span></span>
                        </label>
                        <div id="date_append" class="margin-bottom-10 hidden">
                            <div class="input-daterange input-group" id="kt_datepicker_5">
                                <input type="text" class="form-control form-control-sm" name="start" placeholder="From" id="from_input">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="end" placeholder="To" id="to_input">
                            </div>
                            <span class="form-text text-muted">Linked pickers for date range selection</span>

                            <br/>
                        </div>
                        <!-- <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
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
                        </div> -->
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
<!--begin::Modal-->

@endsection
@section("scripts")
<!-- <script src="{{asset('js/superadmin/announcement/announcement.js')}}" type="text/javascript"></script> -->
<script>
    var prole = "";

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
                            url: '/api/commission/list',
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
                        field: 'type',
                        title: 'Type',
                    },{
                        field: 'created_at',
                        title: 'Purchase Date',
                    }, {
                        field: 'price',
                        title: 'Price',
                    }, {
                        field: 'promoter_revenue',
                        title: 'Commission',
                    }, {
                        field: 'total_amount',
                        title: 'Total',
                    }],
            };

            var datatable = $('#ajax_datatable_commission').KTDatatable({...dataSource, ...dataStructure});
            
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
                    created_at: {
                        from:{
                            filter: $("#filter_created_at").is(':checked') ? $("#date_append #from_select").val() : "=",
                            values: $("#filter_created_at").is(':checked') ? ($("#date_append #from_input").val() ? $("#date_append #from_input").val().split(",") : null) : null,
                        },
                        to:{
                            filter: $("#filter_created_at").is(':checked') ? $("#date_append #to_select").val() : "=",
                            values: $("#filter_created_at").is(':checked') ? ($("#date_append #to_input").val() ? $("#date_append #to_input").val().split(",") : null) : null,
                        },
                    },
                    // position: {
                    //     filter: $("#filter_position").is(':checked') ? $("#position_append select").val() : "=",
                    //     values: $("#filter_position").is(':checked') ? ($("#position_append input").val() ? $("#position_append input").val().split(",") : null) : null,
                    // },
                    // role: {
                    //     filter: $("#filter_role").is(':checked') ? $("#role_append select").val() : "=",
                    //     values: $("#filter_role").is(':checked') ? ($("#role_append input").val() ? $("#role_append input").val().split(",") : null) : null,
                    // },
                    // contact: {
                    //     filter: $("#filter_contact").is(':checked') ? $("#contact_append select").val() : "=",
                    //     values: $("#filter_contact").is(':checked') ? ($("#contact_append input").val() ? $("#contact_append input").val().split(","): null) : null,
                    // },
                    // email: {
                    //     filter: $("#filter_email").is(':checked') ? $("#email_append select").val() : "=",
                    //     values: $("#filter_email").is(':checked') ? ($("#email_append input").val() ? $("#email_append input").val().split(",") : null) : null,
                    // },
                    // added_by: {
                    //     filter: $("#filter_added_by").is(':checked') ? $("#added_by_append select").val() : "=",
                    //     values: $("#filter_added_by").is(':checked') ? ($("#added_by_append input").val() ? $("#added_by_append input").val().split(",") : null) : null,
                    // },
                    // status: {
                    //     filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : "both",
                    // },
                    // verified: {
                    //     filter: $("#filter_verified").is(':checked') ? $("#verified_append select").val() : "both",
                    // }
                };
            
                setTimeout(() => {
                    $.ajax({
                        url: '/api/session',
                        data: dataApi,
                        success: function(response){
                            showFilterBox(dataApi, datatable, "purchase");
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
        KTBootstrapDatepicker.init();

        $("#filter_created_at").click(function(){
            if($("#filter_created_at").is(":checked")){
                $("#date_append").slideDown(200);
            }else{
                $("#date_append").slideUp(200);
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
// Class definition

var KTBootstrapDatepicker = function () {

var arrows;
if (KTUtil.isRTL()) {
    arrows = {
        leftArrow: '<i class="la la-angle-right"></i>',
        rightArrow: '<i class="la la-angle-left"></i>'
    }
} else {
    arrows = {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>'
    }
}

// Private functions
var demos = function () {

    // range picker
    $('#kt_datepicker_5').datepicker({
        dateFormat: 'yyyy-mm-dd',
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        templates: arrows
    });

};

return {
    // public functions
    init: function() {
        demos(); 
    }
};
}();
</script>
@endsection
