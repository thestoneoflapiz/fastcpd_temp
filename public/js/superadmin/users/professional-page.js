var KTDatatableRemote = function () {
    // Private functions

    // recordTable initializer 
    var recordTable = function () {

        $('.kt-datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/professionals/api/list',
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
                    width: 30,
                    template: function (row) {

                        if (row.instructor == "in-review" && row.status == "active") {
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="dropdown">
                                    <i class="flaticon2-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" style="padding:15px 20px;">
                                    <button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/user/${row.url}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-success" onclick="alert('Approve!');"><i class="fa fa-check kt-font-info"></i> &nbsp; Approve</button>
                                    <div class="kt-space-10"></div>
                                    <button class="dropdown-item btn btn-sm btn-warning" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times kt-font-danger"></i> &nbsp; Reject</button>
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
                                `+ (row.accreditor == "none" ? (`<button class="dropdown-item btn btn-sm btn-primary" onclick="window.open('/user/${row.username}')"><i class="fa fa-eye kt-font-success"></i> &nbsp; View Page</button>`) : ``) + `
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
                        if (row.status == "inactive") {
                            return `<span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }

                        if (row.status == "active") {
                            return `<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }

                        if (row.status == "delete") {
                            return `<span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">${row.status}</span>`;
                        }
                    }
                }, {
                    field: 'name',
                    title: 'Name',
                }, {
                    field: 'user_roles',
                    title: 'Roles',
                    sortable: false,
                    template: function (row) {
                        var display = ``;

                        row.user_roles.forEach((usr) => {
                            display += `${usr}<br/>`;
                        });

                        return display;
                    }
                }, {
                    field: 'professions',
                    title: 'Professions',
                    textAlign: 'center',
                    sortable: false,
                    template: function (row) {
                        return `<button ` + (row.accreditor != "none" ? "disabled " : "") + `class="btn btn-sm btn-info" data-toggle="modal" data-target="#view-profession-modal" onclick='generateProfession(${row.professions})'>View</button>`;
                    }
                }, {
                    field: 'created_at',
                    title: 'Date Created',
                }, {
                    field: 'email',
                    title: 'Email',
                }, {
                    field: 'contact',
                    title: 'Contact',
                },{
                    field: 'email_verified_at',
                    title: 'Email Status',
                    textAlign: 'center',
                    sortable: false,
                    template: function (row) {
                        if (row.email_verified_at == null && row.accreditor == "none") {
                            return `<span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">Not Verified</span>`;
                        }else if(row.accreditor == "active"){
                            return `<span class="kt-badge  kt-badge--dark kt-badge--inline kt-badge--pill">Accreditor</span>`;
                        }else{
                            return `<span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">Verified</span>`;
                        }

                    }
                }],
        });
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

function generateProfession(professions) {
    $(`#loading-modal-form`).show();
    $(`#profession-div`).hide().empty();

    setTimeout(() => {
        if(Array.isArray(professions)){
            professions.forEach((pro, key) => {
                var input_group = `
                <div class="col-xl-12">
                    <div class="input-group">
                        <input type="text" class="form-control" aria-describedby="inputgroup-${pro.id}" disabled value="${pro.title}">
                        <div class="input-group-append"><span class="input-group-text" id="inputgroup-${pro.id}">${pro.prc_no}</span></div>
                    </div>
                </div>`;
    
                $(`#profession-div`).append(input_group);
            });
        }else{
            // console.log(professions);
            var input_group = `
            <div class="col-xl-12">
                <div class="input-group">
                    <input type="text" class="form-control" aria-describedby="inputgroup-${professions}" disabled value="${professions}">
                    <div class="input-group-append"><span class="input-group-text" id="inputgroup-${professions}">${professions}</span></div>
                </div>
            </div>`;

            $(`#profession-div`).append(input_group);
        }
       

        $(`#loading-modal-form`).hide();
        $(`#profession-div`).show();
    }, 200);
}

