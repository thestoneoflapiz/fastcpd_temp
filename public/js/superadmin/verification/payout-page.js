"use strict";
// Class definition
var KTDatatableRemote = function () {
    // Private functions
	var m = [ "January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December" ];
    // basic demo
    var recordTable = function () {
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/verification/payout/api/list',
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
                    width: 85,
                    overflow: 'visible',
                    // autoHide: false,
                    template: function (row) {
                        return `<button class="dropdown-item btn btn-sm btn-info btn-manage" id="`+row.id+`" data-id="[`+row.month+`]" data-target="#manage-modal" data-toggle="modal">Manage</button>`;
                    },
                }, {
                    field: 'month',
                    title: 'Month',
                    template: function(row){
                        return m[row.month-1];
                    }
                }, {
                    field: 'year',
                    title: 'Year',
                }, {
                    field: 'type',
                    title: 'User Type',
                },{
                    field: 'provider_name',
                    title: "Provider",
                }, {
                    field: 'full_name',
                    title: 'Recipient',
                }, {
                    field: 'amount',
                    title: 'Amount',
                    template: function (row) {
                        return `₱ ${row.amount.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'expected_payment_date',
                    title: 'Expected Payment Date',
                }, {
                    field: 'status',
                    title: "Status",
                    textAlign:'center',
                    template: function(row) {
                        var status = {
                            1: {'title': 'Waiting for Payout', 'class': ' kt-badge--primary'},
                            2: {'title': 'On Hold', 'class': ' kt-badge--warning'},
                            3: {'title': 'Paid', 'class': ' kt-badge--success'},
                        };
                        //console.log(status[row.status]);
                        return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
                    },
                },{
                    field: 'notes',
                    title: 'Notes',
                }
            ]
        };

        var datatable = $('#ajax_data').KTDatatable({ ...dataSource, ...dataStructure });
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
        datatable.on("click",".btn-manage", function(e){
            $(`textarea#notes`).empty();
            $(`textarea#notes`).html("");
            $(`textarea#notes`).val("");
            e.preventDefault();
            $.ajax({
                url: "/superadmin/verification/payout/api/payout-details",
                data: {
                   payout_id: $(this).attr("id"),
                },
                success:function(response){
                   // console.log(response[0]);
                   var content = `<small>You're trying to change status for </small><br>"`+response[0].month+` `+response[0].year+`, `+response[0].type+` from `+response[0].full_name+`"<br/>`;
                   $("#modal_content").html(content);
                   $(`#status_selection option[value='${response[0].status}']`).prop('selected',true);
                  
                   $(`textarea#notes`).html(response[0].notes);
                   $(`textarea#notes`).val(response[0].notes);

                },
                error:function(){
    
                },
            });
            $("#manage_id").val($(this).attr("id"));
        });

        $("#kt_display").click(function () {
            if ($("#check_month").is(':checked')) {
                datatable.columns('month').visible(false);
            } else {
                datatable.columns('month').visible(true);
            }

            if ($("#check_year").is(':checked')) {
                datatable.columns('check_year').visible(false);
            } else {
                datatable.columns('check_year').visible(true);
            }

            if ($("#check_usertype").is(':checked')) {
                datatable.columns('type').visible(false);
            } else {
                datatable.columns('type').visible(true);
            }

            if ($("#check_recipient").is(':checked')) {
                datatable.columns('recipient').visible(false);
            } else {
                datatable.columns('recipient').visible(true);
            }

            if ($("#check_amount").is(':checked')) {
                datatable.columns('amount').visible(false);
            } else {
                datatable.columns('amount').visible(true);
            }

            if ($("#check_expected_payment_date").is(':checked')) {
                datatable.columns('expected_payment_date').visible(false);
            } else {
                datatable.columns('expected_payment_date').visible(true);
            }

            if ($("#check_notes").is(':checked')) {
                datatable.columns('notes').visible(false);
            } else {
                datatable.columns('notes').visible(true);
            }

            datatable.reload();
        });

        $("#kt_filter").click(function () {

            var dataApi = {
                month: {
                    filter: $("#filter_month").is(':checked') ? $("#month_append select").val() : "=",
                    values: $("#filter_month").is(':checked') ? ($("#month_append input").val() ? $("#month_append input").val().split(",") : null) : null,
                },
                year: {
                    filter: $("#filter_year").is(':checked') ? $("#year_append select").val() : "=",
                    values: $("#filter_year").is(':checked') ? ($("#year_append input").val() ? $("#year_append input").val().split(",") : null) : null,
                },
                user_type: {
                    filter: $("#filter_usertype").is(':checked') ? $("#usertype_append select").val() : "=",
                    values: $("#filter_usertype").is(':checked') ? ($("#usertype_append input").val() ? $("#usertype_append input").val().split(",") : null) : null,
                },
                provider: {
                    filter: $("#filter_provider").is(':checked') ? $("#provider_append select").val() : "=",
                    values: $("#filter_provider").is(':checked') ? ($("#provider_append input").val() ? $("#provider_append input").val().split(",") : null) : null,
                },
                recipient: {
                    filter: $("#filter_recipient").is(':checked') ? $("#recipient_append select").val() : "=",
                    values: $("#filter_recipient").is(':checked') ? ($("#recipient_append input").val() ? $("#recipient_append input").val().split(",") : null) : null,
                },
                amount: {
                    filter: $("#filter_amount").is(':checked') ? $("#amount_append select").val() : "=",
                    values: $("#filter_amount").is(':checked') ? ($("#amount_append input").val() ? $("#amount_append input").val().split(",") : null) : null,
                },
                notes: {
                    filter: $("#filter_notes").is(':checked') ? $("#notes_append select").val() : "=",
                    values: $("#filter_notes").is(':checked') ? ($("#notes_append input").val() ? $("#notes_append input").val().split(",") : null) : null,
                },
            };

            setTimeout(() => {
                $.ajax({
                    url: '/api/session',
                    data: dataApi,
                    success: function (response) {
                        showFilterBox(dataApi, datatable, "payouts");
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

    // $('#status_selection').select2({
    //     placeholder: "Please choose a status"
    // });
    FormDesign.init();
    $("#filter_month").click(function () {
        if ($("#filter_month").is(":checked")) {
            $("#month_append").slideDown(200);
        } else {
            $("#month_append").slideUp(200);
        }
    });

    $("#filter_year").click(function () {
        if ($("#filter_year").is(":checked")) {
            $("#year_append").slideDown(200);
        } else {
            $("#year_append").slideUp(200);
        }
    });

    $("#filter_usertype").click(function () {
        if ($("#filter_usertype").is(":checked")) {
            $("#usertype_append").slideDown(200);
        } else {
            $("#usertype_append").slideUp(200);
        }
    });

    $("#filter_recipient").click(function () {
        if ($("#filter_recipient").is(":checked")) {
            $("#recipient_append").slideDown(200);
        } else {
            $("#recipient_append").slideUp(200);
        }
    });

    $("#filter_amount").click(function () {
        if ($("#filter_amount").is(":checked")) {
            $("#amount_append").slideDown(200);
        } else {
            $("#amount_append").slideUp(200);
        }
    });

    $("#filter_expected_payment_date").click(function () {
        if ($("#filter_expected_payment_date").is(":checked")) {
            $("#expected_payment_date_append").slideDown(200);
        } else {
            $("#expected_payment_date_append").slideUp(200);
        }
    });

    $("#filter_notes").click(function () {
        if ($("#filter_notes").is(":checked")) {
            $("#notes_append").slideDown(200);
        } else {
            $("#notes_append").slideUp(200);
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

var FormDesign = function() {

    var input_validations = function() {
    var validator = $("#form").validate({ 
            // define validation rules
            rules: {
                status_selection: {
                    required: true,
                }

            },

            //display error alert on form submit  
            invalidHandler: function(event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function(form) {
                $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true).html("Saving...");
                var submit_form = $.ajax({
                    url: '/superadmin/verification/payout/update',
                    type: 'POST',
                    data: {
                        payout_id: $("#manage_id").val(),
                        status_selection: $('#status_selection').val(),
                        notes: $('#notes').val(),
                        _token: $('meta[name="_token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success('Success!', response.message);
                            var datatable = $('#ajax_data').KTDatatable();
                            datatable.destroy();
                            setTimeout(() => {
                                $('#manage-modal').modal('hide');
                                KTDatatableRemote.init();
                                $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                            }, 1000);
                        } else {
                            $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                            toastr.error('Error!', response.message);
                        }
                    },
                    error: function(response) {
                        $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", false).html("Submit");
                        toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
                    }
                });
            }
        });
    }

    return {
        init: function() {
            input_validations();
        }
    };
}();

function removeFilter(resource) {
    var { type, name, target } = resource;
    var target = $(resource.target);

    $("#filter_row_" + name).remove();
    var row = $("#filter_row");
    var not = row.children.length;
    if (not < 2) {
        row.empty();
    }

    switch (type) {
        case 'status':
            $("#filter_" + name).prop("checked", false);
            $("#" + name + "_append").hide();
            target.prop("value", "both");
            break;

        default:
            $("#filter_" + name).prop("checked", false);
            $("#" + name + "_append").hide();
            $("#" + name + "_append").find("select").prop("value", "=");
            $("#" + name + "_append").find("input").prop("value", null);

            break;
    }

    sendDataApi();
}

function sendDataApi() {
    var resource = getApiData(apiType);

    setTimeout(() => {
        $.ajax({
            url: resource.route,
            data: resource.dataApi,
            success: function (response) {
                ajax_datatable.reload();
            }
        });
    }, 100);
}

function getApiData(type) {
    switch (type) {
    case "payouts":
        return {
            dataApi: {
                month: {
                    filter: $("#filter_month").is(':checked') ? $("#month_append select").val() : "=",
                    values: $("#filter_month").is(':checked') ? ($("#month_append input").val() ? $("#month_append input").val().split(",") : null) : null,
                },
                year: {
                    filter: $("#filter_year").is(':checked') ? $("#year_append select").val() : "=",
                    values: $("#filter_year").is(':checked') ? ($("#year_append input").val() ? $("#year_append input").val().split(",") : null) : null,
                },
                user_type: {
                    filter: $("#filter_usertype").is(':checked') ? $("#usertype_append select").val() : "=",
                    values: $("#filter_usertype").is(':checked') ? ($("#usertype_append input").val() ? $("#usertype_append input").val().split(",") : null) : null,
                },
                provider: {
                    filter: $("#filter_provider").is(':checked') ? $("#provider_append select").val() : "=",
                    values: $("#filter_provider").is(':checked') ? ($("#provider_append input").val() ? $("#provider_append input").val().split(",") : null) : null,
                },
                recipient: {
                    filter: $("#filter_recipient").is(':checked') ? $("#recipient_append select").val() : "=",
                    values: $("#filter_recipient").is(':checked') ? ($("#recipient_append input").val() ? $("#recipient_append input").val().split(",") : null) : null,
                },
                amount: {
                    filter: $("#filter_amount").is(':checked') ? $("#amount_append select").val() : "=",
                    values: $("#filter_amount").is(':checked') ? ($("#amount_append input").val() ? $("#amount_append input").val().split(",") : null) : null,
                },
                notes: {
                    filter: $("#filter_notes").is(':checked') ? $("#notes_append select").val() : "=",
                    values: $("#filter_notes").is(':checked') ? ($("#notes_append input").val() ? $("#notes_append input").val().split(",") : null) : null,
                },
            },
            route: '/api/session'
        };

break;

        default:
            return apiData;
            break;
    }

}