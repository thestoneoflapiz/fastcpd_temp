"use strict";
// Class definition
var Datatable = function () {
    // Private functions

    // basic demo
    var recordTable = function () {
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/announcements/api/list',
                        map: function (raw) {
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
            extensions: {
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
                    template: function(row) {
                        return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left">
                                    <a class="dropdown-item"  href="/superadmin/announcements/change_status/${row.id}">
                                        <i class="fa fa-eye kt-font-success"></i>  
                                        Change Status
                                    </a>
                                    <a class="dropdown-item" href="/superadmin/announcements/edit/${row.id}">
                                        <i class="la la-edit"></i> 
                                        Edit
                                    </a>
                                    <a class="dropdown-item" onclick="deleteLink(event);" data-toggle="modal" data-target="#delete_modal" data-resource-name="${row.title}" data-route="/superadmin/announcements/delete/${row.id}">
                                        <i class="la la-trash"></i> 
                                        Delete
                                    </a>
                                </div>
                            </div>
                        `;
                    },
                }, {
                    field: 'title',
                    title: 'Title',
                }, {
                    field: 'target_audience',
                    title: 'Target Audience',
                }, {
                    field: 'start_date',
                    title: 'Start Date',
                }, {
                    field: 'end_date',
                    title: 'End Date',
                }, {
                    field: 'status',
                    title: 'Status',
                    template: function (row) {
                        if (row.status == "inactive") {
                            return `<span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }

                        if (row.status == "active") {
                            return `<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }

                        if (row.status == "expired") {
                            return `<span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }

                        if (row.status == "pending") {
                            return `<span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }
                    },
                }
            ]
        };

        var datatable = $('.kt-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);
        datatable.on(
            "kt-datatable--on-click-checkbox kt-datatable--on-layout-updated",
            function (e) {
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

        $("#kt_display").click(function () {
            if ($("#check_title").is(':checked')) {
                datatable.columns('title').visible(false);
            } else {
                datatable.columns('title').visible(true);
            }

            if ($("#check_audience").is(':checked')) {
                datatable.columns('target_audience').visible(false);
            } else {
                datatable.columns('target_audience').visible(true);
            }

            if ($("#check_date_start").is(':checked')) {
                datatable.columns('start_date').visible(false);
            } else {
                datatable.columns('start_date').visible(true);
            }

            if ($("#check_date_end").is(':checked')) {
                datatable.columns('end_date').visible(false);
            } else {
                datatable.columns('end_date').visible(true);
            }

            if ($("#check_status").is(':checked')) {
                datatable.columns('status').visible(false);
            } else {
                datatable.columns('status').visible(true);
            }

            datatable.reload();
        });
        $("#kt_filter").click(function(){
            var dataApi = {
                title: {
                    filter: $("#filter_title").is(':checked') ? $("#title_append select").val() : "=",
                    values: $("#filter_title").is(':checked') ? ($("#title_append input").val() ? $("#title_append input").val().split(",") : null) : null,
                },
                target_audience: {
                    filter: $("#filter_target_audience").is(':checked') ? $("#target_audience_append select").val() : "=",
                    values: $("#filter_target_audience").is(':checked') ? ($("#target_audience_append input").val() ? $("#target_audience_append input").val().split(",") : null) : null,
                },
                start_date: {
                    filter: $("#filter_start_date").is(':checked') ? $("#start_date_append select").val() : "=",
                    values: $("#filter_start_date").is(':checked') ? ($("#start_date_append input").val() ? $("#start_date_append input").val().split(",") : null) : null,
                },
                end_date: {
                    filter: $("#filter_end_date").is(':checked') ? $("#end_date_append select").val() : "=",
                    values: $("#filter_end_date").is(':checked') ? ($("#end_date_append input").val() ? $("#end_date_append input").val().split(",") : null) : null,
                },
                status: {
                    filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : null,
                },
            };
        
            setTimeout(() => {
                $.ajax({
                    url: '/superadmin/announcements/api/session',
                    data: dataApi,
                    success: function(response){
                        showFilterBox(dataApi, datatable, "announcements");
                        datatable.reload();
                    }
                });
            }, 500);
        });


    };

    return {
        // public functions
        init: function () {
            recordTable();
        },
    };
}();

jQuery(document).ready(function () {
    $("input[name='start_date']").datepicker();
    $("input[name='end_date']").datepicker();

    $("input[name='voucher_code']").keyup(function(e) {
        var value = e.target.value;
        $(this).val(value.toUpperCase());
    });

    
    $('#permissions').select2({
        placeholder: "Please choose a permission"
    });

    Datatable.init();

    $("#filter_title").click(function(){
        if($("#filter_title").is(":checked")){
            $("#title_append").slideDown(200);
        }else{
            $("#title_append").slideUp(200);
        }
    });

    $("#filter_target_audience").click(function(){
        if($("#filter_target_audience").is(":checked")){
            $("#target_audience_append").slideDown(200);
        }else{
            $("#target_audience_append").slideUp(200);
        }
    });

    $("#filter_start_date").click(function(){
        if($("#filter_start_date").is(":checked")){
            $("#start_date_append").slideDown(200);
        }else{
            $("#start_date_append").slideUp(200);
        }
    });

    $("#filter_end_date").click(function(){
        if($("#filter_end_date").is(":checked")){
            $("#end_date_append").slideDown(200);
        }else{
            $("#end_date_append").slideUp(200);
        }
    });

    $("#filter_status").click(function(){
        if($("#filter_status").is(":checked")){
            $("#status_append").slideDown(200);
        }else{
            $("#status_append").slideUp(200);
        }
    });

    $(".selection").change(function (e) {
        var value = e.target.value;
        if (value == "empty" || value == "!empty") {
            $(this).next("input").attr("disabled", "disabled");
            $(this).next("input").val("");
        } else {
            $(this).next("input").removeAttr("disabled", "disabled");
        }
    });
});
