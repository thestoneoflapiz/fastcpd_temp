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
                        url: '/superadmin/users/api/list',
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
                    width: 30,
                    overflow: 'visible',
                    visible: false,
                }, {
                    field: 'name',
                    title: 'Name',
                }, {
                    field: 'email',
                    title: 'Email',
                }, {
                    field: 'permissions',
                    title: 'Permission',
                    sortable: false,
                    template: function (row) {
                        return `<button class="btn btn-sm btn-info" data-toggle="modal" onclick="generatePermission(${row.id})" data-target="#view-permission-modal">View</button>`;
                    },
                }, {
                    field: 'superadmin',
                    title: 'Status',
                    template: function (row) {
                        if (row.superadmin == "inactive") {
                            return `<span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">${row.superadmin}</span>`;
                        }

                        if (row.superadmin == "active") {
                            return `<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">${row.superadmin}</span>`;
                        }

                        if (row.superadmin == "waiting") {
                            return `<span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">${row.superadmin}</span>`;
                        }

                        if (row.superadmin == "delete") {
                            return `<span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">${row.superadmin}</span>`;
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
            if ($("#check_name").is(':checked')) {
                datatable.columns('name').visible(false);
            } else {
                datatable.columns('name').visible(true);
            }

            if ($("#check_email").is(':checked')) {
                datatable.columns('email').visible(false);
            } else {
                datatable.columns('email').visible(true);
            }

            if ($("#check_permissions").is(':checked')) {
                datatable.columns('permissions').visible(false);
            } else {
                datatable.columns('permissions').visible(true);
            }

            if ($("#check_status").is(':checked')) {
                datatable.columns('superadmin').visible(false);
            } else {
                datatable.columns('superadmin').visible(true);
            }

            datatable.reload();
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
    $('#permissions').select2({
        placeholder: "Please choose a permission"
    });

    Datatable.init();

    $("#filter_name").click(function () {
        if ($("#filter_name").is(":checked")) {
            $("#name_append").slideDown(200);
        } else {
            $("#name_append").slideUp(200);
        }
    });

    $("#filter_email").click(function () {
        if ($("#filter_email").is(":checked")) {
            $("#email_append").slideDown(200);
        } else {
            $("#email_append").slideUp(200);
        }
    });

    $("#filter_permissions").click(function () {
        if ($("#filter_permissions").is(":checked")) {
            $("#permissions_append").slideDown(200);
        } else {
            $("#permissions_append").slideUp(200);
        }
    });

    $("#filter_status").click(function () {
        if ($("#filter_status").is(":checked")) {
            $("#status_append").slideDown(200);
        } else {
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

function generatePermission(user_id) {
    $(`#loading-modal-form`).show();
    $(`#form`).hide();
    $.ajax({
        url: '/superadmin/users/permissions',
        data: {
            id: user_id,
        },
        success: function (response) {
            var data = response.data;
            var to_display = [];
            $("#permissions > option").each(function (key, option) {
                var opt_value = $(option).val();
                if (jQuery.inArray(opt_value, data) >= 0) {
                    to_display.push(opt_value);
                }
            });

            $("#id").val(user_id);
            $("#permissions").val(to_display).trigger('change');
            $(`#loading-modal-form`).hide();
            $(`#form`).show();
        },
        error: function () {

            toastr.error('Error!', 'Something went wrong! Unable to retrieve permissions.');
            $(`#view-permission-modal`).modal('hide');
        }
    });
}

function savePermissions() {
    $(`#loading-modal-form`).show();
    $(`#form`).hide();

    $.ajax({
        url: '/superadmin/users/permissions/action',
        method: 'POST',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            id: $('#id').val(),
            permissions: $('#permissions').val(),
        },
        success: function (response) {
            $(`#loading-modal-form`).hide();
            $(`#view-permission-modal`).modal('hide');
            toastr.success('Saved!', 'Permission updates has been saved');
        },
        error: function () {

            toastr.error('Error!', 'Something went wrong! Unable to retrieve permissions.');
            $(`#view-permission-modal`).modal('hide');
        }
    });
}
