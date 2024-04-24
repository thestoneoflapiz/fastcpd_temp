var KTDatatableRemote = function () {
    // Private functions

    // recordTable initializer
    var recordTable = function () {

        var datatable = $('.kt-datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/providers/api/list',
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
                pageSize: 10, // display 20 records per page
                serverPaging: true,
                serverFiltering: false,
                serverSorting: true,
            },

            // layout definition
            layout: {
                scroll: true,
                height: 400,
                footer: false,
            },
            detail: {
                title: 'Load sub table',
                content: subTableInit,
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
                    field: 'provider_id',
                    title: '',
                    width:10,
                },
                {
                    field: 'id',
                    title: '',
                    width: 30,
                    template: function (row) {

                        if (row.status != "approved") {
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                    <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/provider/${row.url}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-danger" onclick="window.open('/data/request/provider?id=${row.id}')"><i class="fa fa-eye kt-font-warning"></i> &nbsp; View Profile Request</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-success" data-toggle="modal" data-target="#approve-modal" onclick="changeApproveModalTitle({name: '${row.name}', id: ${row.id}})"><i class="fa fa-check kt-font-info"></i> &nbsp; Approve</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-warning" data-toggle="modal" data-target="#reject-modal" onclick="changeRejectModalTitle({name: '${row.name}', id: ${row.id}})"><i class="fa fa-times kt-font-danger"></i> &nbsp; Reject</button>
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
                                <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/provider/${row.url}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                            </div>
                        </div>
                        `;
                    },
                }, {
                    field: 'status',
                    title: 'Status',
                    width: 70,
                    textAlign: 'center',
                    template: function (row) {

                        if (row.status == "approved") {
                            return `<i class="fa fa-check kt-font-success" data-toggle="kt-tooltip" data-placement="top" title="${row.status}" data-original-title="${row.status}"></i>`;
                        }

                        if (row.status == "in-review") {
                            return `<i class="fa fa-hourglass kt-font-warning" data-toggle="kt-tooltip" data-placement="top" title="${row.status}" data-original-title="${row.status}"></i>`;
                        }

                        return `<i class="fa fa-times kt-font-danger" data-toggle="kt-tooltip" data-placement="top" title="${row.status}" data-original-title="${row.status}"></i>`;
                    }
                }, {
                    field: 'name',
                    title: 'Name',
                }, {
                    field: 'created_at',
                    title: 'Date Created',
                }, {
                    field: 'email',
                    title: 'Email',
                }, {
                    field: 'contact',
                    title: 'Contact',
                }],
        });
        function subTableInit(e) {
            console.log(e);
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.provider_id).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/superadmin/providers/list-details',
                            params: {
                                // custom query params
                                query: {
                                    providerID: e.data.provider_id,
                                    type: 'child',
                                },
                            },
                            method: "get",
                        },
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: false,
                    serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true,
                    height: 400,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },
                rows: {
                    autoHide: true,
                },

                sortable: true,

                // columns definition
                columns: [
                    {
                        field: 'id',
                        title: '',
                        visible: false,
                    }, {
                        field: 'name',
                        title: 'Name',
                    }, {
                        field: 'role',
                        title: 'Role',
                        template: function (row){
                            var str = row.role;
                            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                return letter.toUpperCase();
                            });
                            return str;
                        }
                    },{
                        field: 'permissions',
                        title: 'Permissions',
                        template: function (row) {
                            var display = ``;
    
                            row.permissions.forEach((data) => {
                                // console.log(data);
                                if(data){
                                    var str = data.module_name;
                                    if(str == "traffic_conversion"){
                                        str = "traffic conversion";
                                    }
                                    if(str == "provider_profile"){
                                        str = "provider profile";
                                    }
                                    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });
                                    var icons = "";
                                    if(data.view == 1){
                                        icons+= `<i class="fa fa-eye kt-font-info"></i> ` ;
                                    }
                                    if(data.create == 1){
                                        icons+=`<i class="fa fa-plus-circle kt-font-success"></i> ` ;
                                    }
                                    if(data.edit == 1){
                                        icons+=`<i class="fa fa-pen kt-font-warning"></i> ` ;
                                    }
                                    if(data.delete == 1){
                                        icons+=`<i class="fa fa-trash kt-font-danger"></i> `;
                                    }
                                    display += `<b>${str}</b> ${icons}<br/>`;
                                }
                                //display += `(${data.role}) ${data.profile.name}<br/>`;
                            });
    
                            return display;
                        }
                    }, {
                        field: 'instructors',
                        title: 'Instructors',
                        template: function (row) {
                            var display = ``;
                            if(row.instructors){
                                row.instructors.forEach((data) => {
                                    if(data.profile){
                                        display += `${data.profile.name ?? "" }<br/>`;
                                    }
                                });
                            }else{
                                display = "";
                            }
                           
    
                            return display;
                        }
                    }],
            });
        }
    };

    return {
        // Public functions
        init: function () {
            // init dmeo
            recordTable();
        },
    };
}();

jQuery(document).ready(function () {
    KTDatatableRemote.init();
});

function changeRejectModalTitle(data) {
    $("#reject-provider-id").val(data.id);
    $("#reject-modal-title").html(data.name);
}

function changeApproveModalTitle(data) {
    $("#approve-provider-id").val(data.id);
    $("#approve-modal-title").html(`${data.name}?`);
}

function submitRejection() {
    $.ajax({
        url: "/superadmin/providers/reject",
        method: "POST",
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            id: $('#reject-provider-id').val(),
            message: $('#reject-modal-notes').val(),
        },
        success: function (response) {
            toastr.success('Success!', 'You have rejected the submitted provider profile');
            var dataTable = $(".kt-datatable").KTDatatable();
            setTimeout(() => {
                dataTable.destroy();
                KTDatatableRemote.init();
            }, 1000);
        },
        error: function (response) {
            toastr.error('Error!', 'Unable to save action. Please try again later');
        }
    });
}

function submitApproval() {
    $.ajax({
        url: "/superadmin/providers/approve",
        method: "POST",
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            id: $('#approve-provider-id').val(),
            message: $('#approve-modal-notes').val(),
        },
        success: function (response) {
            toastr.success('Success!', 'You have approved the submitted provider profile');
            var dataTable = $(".kt-datatable").KTDatatable();
            setTimeout(() => {
                dataTable.destroy();
                KTDatatableRemote.init();
            }, 1000);
        },
        error: function (response) {
            toastr.error('Error!', 'Unable to save action. Please try again later');
        }
    });
}