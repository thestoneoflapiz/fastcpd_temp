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
                <!--begin:: Widgets/Activity-->
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
                    <div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Dashboard
                            </h3>
                        </div>
                        <!-- <div class="kt-portlet__head-toolbar">
                            <a href="#" class="btn btn-label-light btn-sm btn-bold dropdown-toggle" data-toggle="dropdown">
                                Export
                            </a>
                            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                <ul class="kt-nav">
                                    <li class="kt-nav__section kt-nav__section--first">
                                        <span class="kt-nav__section-text">Finance</span>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-graph-1"></i>
                                            <span class="kt-nav__link-text">Statistics</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
                                            <span class="kt-nav__link-text">Events</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-layers-1"></i>
                                            <span class="kt-nav__link-text">Reports</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__section">
                                        <span class="kt-nav__section-text">Customers</span>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-calendar-4"></i>
                                            <span class="kt-nav__link-text">Notifications</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-file-1"></i>
                                            <span class="kt-nav__link-text">Files</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <div class="kt-widget17">
                            <div class="kt-widget17__visual kt-widget17__visual--chart kt-portlet-fit--top kt-portlet-fit--sides" style="background-color: #fd397a">
                                <div style="height:120px;">
                                    <!-- <canvas id="kt_chart_activities"></canvas> -->
                                </div>
                            </div>
                            <div class="kt-widget17__stats">
                                <div class="kt-widget17__items">
                                    <div class="kt-widget17__item">
                                        <span class="kt-widget17__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                                                    <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg> </span>
                                        <span class="kt-widget17__subtitle">
                                            {{$voucher->voucher_code}}
                                        </span>
                                        <span class="kt-widget17__desc">
                                            {{$voucher->discount ?? "0"}}% Discount
                                        </span>
                                    </div>
                                    <div class="kt-widget17__item">
                                        <span class="kt-widget17__icon">
                                            <i class="flaticon2-line-chart kt-font-danger"></i>
                                        </span>
                                        <span class="kt-widget17__subtitle">
                                            Php {{$current_balance['balance']}}
                                        </span>
                                        <span class="kt-widget17__desc">
                                            {{$current_balance['balance']}} Total Earnings
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Activity-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
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
                                    <a class="nav-link active" href="/commission" role="tab" aria-selected="true">
                                        See All
                                    </a>
                                </li>
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
            <div class="col-md-4">
                <!--Begin::Portlet-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Payout Transactions
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="/payout_request" aria-selected="true">
                                        See All
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <!--Begin::Timeline 3 -->
                        <div class="kt-timeline-v2">
                            <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
                                <?php foreach($payout_transaction as $p_transact){ ?>
                                    <div class="kt-timeline-v2__item">
                                        <span class="kt-timeline-v2__item-time">{{$p_transact->status}}</span>
                                        <div class="kt-timeline-v2__item-cricle">
                                            <i class="fa fa-genderless kt-font-<?=$p_transact->status=="paid" ? "success" : ($p_transact->status=="waiting" ? "warning" : "danger")?>"></i>
                                        </div>
                                        <div class="kt-timeline-v2__item-text  kt-padding-top-5">
                                            Transact Date: {{date_format($p_transact->created_at,'M j,y')}}<br/>
                                            Amount to collect: <b>PHP{{$p_transact->promoter_revenue}}</b><br/>
                                            Method: {{$p_transact->payment_method}}
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <!--End::Timeline 3 -->
                    </div>
                </div>

                <!--End::Portlet-->
            </div>
        </div>
    </div>
</div>

<!-- begin:Modal -->
@include('template.modal.delete')
@include('template.modal.pdf')
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
