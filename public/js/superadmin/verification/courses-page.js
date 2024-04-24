"use strict";
// Class definition
function approve_course($id) {
    var datatable = $('#ajax_data').KTDatatable();
    
    $.ajax({
        url: '/superadmin/verification/courses/api/update_data',
        type: 'POST',
        data: {
            course_id: $id,
            state: "approve",
            _token: $(`input[name="_token"]`).val()
        },
        success: function (response) {
            datatable.reload();
        }
    });
}

function reject_course($id, $course) {
    document.getElementById("course_title").innerHTML = $course;
    document.getElementById("course_id").value = $id;
}

function reject_course_action() {
    var course_id = document.getElementById("course_id").value;

    var datatable = $('#ajax_data').KTDatatable();
    
    $.ajax({
        url: '/superadmin/verification/courses/api/update_data',
        type: 'POST',
        data: {
            course_id: course_id,
            state: "reject",
            _token: $(`input[name="_token"]`).val()
        },
        success: function (response) {
            datatable.reload();
        }
    });

}



var KTDatatableRemote = function () {
    // Private functions

    // basic demo
    var recordTable = function () {
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/verification/courses/api/list',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
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
                    template: function (row) {
                        return `
                        <div class="dropdown">
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                <i class="flaticon2-gear"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/course/${row.url}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                                <div class="kt-space-10"></div>
                                <button class="dropdown-item btn btn-sm btn-success" onclick="approve_course(${row.id})"  data-id="${row.id}"><i class="fa fa-check kt-font-info"></i> &nbsp; Approve</button>
                                <div class="kt-space-10"></div>
                                <button class="dropdown-item btn btn-sm btn-warning" data-toggle="modal" onclick="reject_course(${row.id},\``+row.course+`\`)" data-target="#reject-modal"><i class="fa fa-times kt-font-danger"></i> &nbsp; Reject</button>
                            </div>
                        </div>
                        `;
                    },
                }, {
                    field: 'submitted_at',
                    title: 'Date Submitted',
                }, {
                    field: 'course',
                    title: 'Course',
                }, {
                    field: 'submitted_by',
                    title: 'Submitted By',
                }, {
                    field: 'provider',
                    title: 'Provider',
                }
            ]
        };

        var datatable = $('#ajax_data').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);

        

        $("#kt_display").click(function () {
            if ($("#check_date").is(':checked')) {
                datatable.columns('submitted_at').visible(false);
            } else {
                datatable.columns('submitted_at').visible(true);
            }

            if ($("#check_course").is(':checked')) {
                datatable.columns('course').visible(false);
            } else {
                datatable.columns('course').visible(true);
            }

            if ($("#check_by").is(':checked')) {
                datatable.columns('submitted_by').visible(false);
            } else {
                datatable.columns('submitted_by').visible(true);
            }

            if ($("#check_provider").is(':checked')) {
                datatable.columns('provider').visible(false);
            } else {
                datatable.columns('provider').visible(true);
            }

            datatable.reload();
        });

        $("#kt_filter").click(function () {

            var dataApi = {
                submitted_at: {
                    filter: $("#filter_date").is(':checked') ? $("#date_append select").val() : "=",
                    values: $("#filter_date").is(':checked') ? ($("#date_append input").val() ? $("#date_append input").val().split(",") : null) : null,
                },
                course: {
                    filter: $("#filter_course").is(':checked') ? $("#course_append select").val() : "=",
                    values: $("#filter_course").is(':checked') ? ($("#course_append input").val() ? $("#course_append input").val().split(",") : null) : null,
                },
                submitted_by: {
                    filter: $("#filter_by").is(':checked') ? $("#by_append select").val() : "=",
                    values: $("#filter_by").is(':checked') ? ($("#by_append input").val() ? $("#by_append input").val().split(",") : null) : null,
                },
                provider: {
                    filter: $("#filter_provider").is(':checked') ? $("#provider_append select").val() : "=",
                    values: $("#filter_provider").is(':checked') ? ($("#provider_append input").val() ? $("#provider_append input").val().split(",") : null) : null,
                },
            };

            setTimeout(() => {
                $.ajax({
                    url: '/api/session',
                    data: dataApi,
                    success: function (response) {
                        showFilterBox(dataApi, datatable, "instructors");
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
    KTDatatableRemote.init();
    $("#filter_date").click(function () {
        if ($("#filter_date").is(":checked")) {
            $("#date_append").slideDown(200);
        } else {
            $("#date_append").slideUp(200);
        }
    });

    $("#filter_course").click(function () {
        if ($("#filter_course").is(":checked")) {
            $("#course_append").slideDown(200);
        } else {
            $("#course_append").slideUp(200);
        }
    });

    $("#filter_by").click(function () {
        if ($("#filter_by").is(":checked")) {
            $("#by_append").slideDown(200);
        } else {
            $("#by_append").slideUp(200);
        }
    });

    $("#filter_provider").click(function () {
        if ($("#filter_provider").is(":checked")) {
            $("#provider_append").slideDown(200);
        } else {
            $("#provider_append").slideUp(200);
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
