"use strict";
// Class definition

var webinar_id = null;

function contacts_webinar($id) {
    $.ajax({
        url: '/superadmin/verification/webinars/api/contacts',
        data: {
            id: $id,
            _token: $(`input[name="_token"]`).val()
        },
        success: function (response) {
            if(response.data.length>0){
                ("#contacts-modal").modal("show");
                var $body_ = $("#contacts-modal").find(".modal-body").empty();
                response.data.forEach(contact => {
                    $body_.append(`
                        <div class="row">  
                            <div class="col-3">
                                User: ${contact.user_type.toUpperCase()}
                            </div>
                            <div class="col-9">
                                Name: ${contact.first_name} ${contact.middle_name} ${contact.last_name}
                            </div>
                            <div class="col-6">
                                Email: ${contact.email} ${contact.contact}
                            </div>
                            <div class="col-6">
                                Contact: ${contact.contact}
                            </div>
                            <div class="col-12">
                                Address: ${contact.address}
                            </div>
                        </div>
                    `);
                });
                return;
            }

            toastr.info("No contacts yet!");
        },
        error: function (response) {
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }
            toastr.error("Something went wrong, please refresh browser!");
        },
    });
}

function approve_webinar($id) {
    webinar_id = $id;

    var datatable = $('#ajax_data').KTDatatable();
    $.ajax({
        url: '/superadmin/verification/webinars/api/approve',
        type: 'POST',
        data: {
            id: $id,
            _token: $(`input[name="_token"]`).val()
        },
        success: function (response) {
            toastr.success("Webinar published!");
            datatable.reload();
        },
        error: function (response) {
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }
            toastr.error("Something went wrong, please refresh browser!");
            datatable.reload();
        },
    });
}

function links_webinar($id) {
    webinar_id = $id;

    var datatable = $('#ajax_data').KTDatatable();
    $.ajax({
        url: '/superadmin/verification/webinars/api/sessions/list',
        data: {
            id: $id,
        },
        success: function (response) {
            $("#session_links-modal").modal("show");
            var $body_ = $("#session_links-modal").find(".modal-body").empty();
            if(response.hasOwnProperty("data")){
                response.data.forEach(sess => {
                    $body_.append(`
                        <div class="row">  
                            <div class="col-12">
                                <label><b>${sess.session_date}</b> Link</label>
                                <input type="text" name="[${sess.id}][link]" value="${sess.link ? sess.link : ''}" placeholder="https://" class="form-control"/>
                            </div>
                        </div>
                    `);
                });
            }
            datatable.reload();
        }
    });
    return;
}

$(`#session_links-modal-submit`).click(function() {
    var datatable = $('#ajax_data').KTDatatable();
    var $links = [];

    $(`[name*="[link]"]`).each(function() {
        var name = $(this).attr("name");
        var id = name.split(/[\[\]]+/)[1];

        $links.push({
            id: id,
            value: $(this).val()
        });
    });

    $.ajax({
        url: '/superadmin/verification/webinars/api/sessions/save',
        type: 'POST',
        data: {
            links: $links,
            _token: $(`input[name="_token"]`).val()
        },
        success: function (response) {
            toastr.success("Link updated!");
            datatable.reload();
        }
    });
});

function reject_webinar($id, $webinar) {
    document.getElementById("webinar_title").innerHTML = $webinar;
    webinar_id = $id;
}

function reject_webinar_action() {
    var datatable = $('#ajax_data').KTDatatable();
    $.ajax({
        url: '/superadmin/verification/webinars/api/draft',
        type: 'POST',
        data: {
            id: webinar_id,
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
                        url: '/superadmin/verification/webinars/api/list',
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
                        if(row.fast_cpd_status == "in-review"){
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                    <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/superadmin/view/webinar/${row.id}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-success" onclick="approve_webinar(${row.id})"  data-id="${row.id}"><i class="fa fa-check kt-font-info"></i> &nbsp; Approve</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-warning" data-toggle="modal" onclick="reject_webinar(${row.id},\``+row.title+`\`)" data-target="#reject-modal"><i class="fa fa-times kt-font-danger"></i> &nbsp; Re-Draft</button>
                                </div>
                            </div>
                            `;
                        }else if(row.fast_cpd_status == "draft"){
                            if(row.provide_link){
                                return `
                                <div class="dropdown">
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                        <i class="flaticon2-gear"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                        <button class="dropdown-item btn btn-sm btn-success" onclick="links_webinar(${row.id})" data-id="${row.id}"><i class="fa fa-check kt-font-light"></i> &nbsp; Provide Links</button>
                                    </div>
                                </div>
                                `;
                            }

                            return ``;                            
                        }else{
                            if(row.type=="promotional"){
                                return `
                                <div class="dropdown">
                                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                        <i class="flaticon2-gear"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                        <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/superadmin/view/webinar/${row.id}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                                        <div class="kt-space-10"></div>
                                        <button class="dropdown-item btn btn-sm btn-warning" data-toggle="modal" onclick="reject_webinar(${row.id},\``+row.title+`\`)" data-target="#reject-modal"><i class="fa fa-times kt-font-danger"></i> &nbsp; Re-Draft</button>
                                        <div class="kt-space-10"></div>
                                        <button class="dropdown-item btn btn-sm btn-success" onclick="contacts_webinar(${row.id})" data-id="${row.id}"><i class="fa fa-address-card kt-font-warning"></i> &nbsp; Request Contacts</button>
                                    </div>
                                </div>
                                `;
                            }
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                    <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/superadmin/view/webinar/${row.id}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-success" onclick="contacts_webinar(${row.id})" data-id="${row.id}"><i class="fa fa-address-card kt-font-warning"></i> &nbsp; Request Contacts</button>
                                </div>
                            </div>
                            `;
                        }
                        
                    },
                }, {
                    field: 'updated_at',
                    title: 'Date Updated',
                }, {
                    field: 'title',
                    title: 'Webinar',
                }, {
                    field: 'offering',
                    title: 'Offering',
                    width: 80,
                    template: function (row) {  
                        return row.offering.toUpperCase();
                    },
                }, {
                    field: 'provider',
                    title: 'Provider',
                }, {
                    field: 'provider_status',
                    title: 'Provider<br/>Status',
                    width: 80,
                    template: function (row) {
                        if(row.provider_status=="approved"){
                            return `<i class="fa fa-check kt-font-success" style="font-size:1.5rem"></i>`;
                        }
                        return `<i class="fa fa-check kt-font-danger" style="font-size:1.5rem"></i>`;
                    },
                }, {
                    field: 'type',
                    title: 'Webinar<br/>Type',
                    template: function (row) {
                        if(row.type=="official"){
                            return `<span class="kt-badge kt-badge--success kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-success">OFFICIAL</span>`;
                        }else if(row.type=="promotional"){
                            return `<span class="kt-badge kt-badge--info kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-info">PROMOTIONAL</span>`;
                        }
                        return `<span class="kt-badge kt-badge--dark kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-dark"></span>`;
                    },
                }, {
                    field: 'fast_cpd_status',
                    title: 'Status',
                    template: function (row) {
                        if(row.fast_cpd_status=="in-review"){
                            return `<span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">in-review</span>`;
                        }else if(row.fast_cpd_status=="draft"){
                            return `<span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">draft</span>`;
                        }
                        return `<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">${row.fast_cpd_status}</span>`;
                    },
                }
            ]
        };

        var datatable = $('#ajax_data').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);

        $("#kt_display").click(function () {
            if ($("#check_updated_at").is(':checked')) {
                datatable.columns('updated_at').visible(false);
            } else {
                datatable.columns('updated_at').visible(true);
            }

            if ($("#check_title").is(':checked')) {
                datatable.columns('title').visible(false);
            } else {
                datatable.columns('title').visible(true);
            }

            if ($("#check_offering").is(':checked')) {
                datatable.columns('offering').visible(false);
            } else {
                datatable.columns('offering').visible(true);
            }

            if ($("#check_provider").is(':checked')) {
                datatable.columns('provider').visible(false);
            } else {
                datatable.columns('provider').visible(true);
            }

            if ($("#check_provider_status").is(':checked')) {
                datatable.columns('provider_status').visible(false);
            } else {
                datatable.columns('provider_status').visible(true);
            }

            if ($("#check_type").is(':checked')) {
                datatable.columns('type').visible(false);
            } else {
                datatable.columns('type').visible(true);
            }

            if ($("#check_fast_cpd_status").is(':checked')) {
                datatable.columns('fast_cpd_status').visible(false);
            } else {
                datatable.columns('fast_cpd_status').visible(true);
            }

            datatable.reload();
        });

        $("#kt_filter").click(function () {
            var dataApi = {
                updated_at: {
                    filter: $("#filter_updated_at").is(':checked') ? $("#updated_at_append select").val() : "=",
                    values: $("#filter_updated_at").is(':checked') ? ($("#updated_at_append input").val() ? $("#updated_at_append input").val().split(",") : null) : null,
                },
                title: {
                    filter: $("#filter_title").is(':checked') ? $("#title_append select").val() : "=",
                    values: $("#filter_title").is(':checked') ? ($("#title_append input").val() ? $("#title_append input").val().split(",") : null) : null,
                },
                offering: {
                    filter: $("#filter_offering").is(':checked') ? $("#offering_append select").val() : null,
                },
                provider: {
                    filter: $("#filter_provider").is(':checked') ? $("#provider_append select").val() : "=",
                    values: $("#filter_provider").is(':checked') ? ($("#provider_append input").val() ? $("#provider_append input").val().split(",") : null) : null,
                },
                provider_status: {
                    filter: $("#filter_provider_status").is(':checked') ? $("#provider_status_append select").val() : null,
                },
                type: {
                    filter: $("#filter_type").is(':checked') ? $("#type_append select").val() : null,
                },
                fast_cpd_status: {
                    filter: $("#filter_fast_cpd_status").is(':checked') ? $("#fast_cpd_status_append select").val() : null,
                },
            };

            setTimeout(() => {
                $.ajax({
                    url: '/superadmin/verification/webinars/api/session',
                    data: dataApi,
                    success: function (response) {
                        showFilterBox(dataApi, datatable, "webinars");
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
    $("#filter_updated_at").click(function () {
        if ($("#filter_updated_at").is(":checked")) {
            $("#updated_at_append").slideDown(200);
        } else {
            $("#updated_at_append").slideUp(200);
        }
    });

    $("#filter_title").click(function () {
        if ($("#filter_title").is(":checked")) {
            $("#title_append").slideDown(200);
        } else {
            $("#title_append").slideUp(200);
        }
    });

    $("#filter_offering").click(function () {
        if ($("#filter_offering").is(":checked")) {
            $("#offering_append").slideDown(200);
        } else {
            $("#offering_append").slideUp(200);
        }
    });

    $("#filter_provider").click(function () {
        if ($("#filter_provider").is(":checked")) {
            $("#provider_append").slideDown(200);
        } else {
            $("#provider_append").slideUp(200);
        }
    });

    $("#filter_provider_status").click(function () {
        if ($("#filter_provider_status").is(":checked")) {
            $("#provider_status_append").slideDown(200);
        } else {
            $("#provider_status_append").slideUp(200);
        }
    });

    $("#filter_type").click(function () {
        if ($("#filter_type").is(":checked")) {
            $("#type_append").slideDown(200);
        } else {
            $("#type_append").slideUp(200);
        }
    });

    $("#filter_fast_cpd_status").click(function () {
        if ($("#filter_fast_cpd_status").is(":checked")) {
            $("#fast_cpd_status_append").slideDown(200);
        } else {
            $("#fast_cpd_status_append").slideUp(200);
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
