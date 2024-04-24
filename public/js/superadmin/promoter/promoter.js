jQuery(document).ready(function() {
    $("#filter_name").click(function(){
        if($("#filter_name").is(":checked")){
            $("#name_append").slideDown(200);
        }else{
            $("#name_append").slideUp(200);
        }
    });

    $("#filter_email").click(function(){
        if($("#filter_email").is(":checked")){
            $("#email_append").slideDown(200);
        }else{
            $("#email_append").slideUp(200);
        }
    });

    $("#filter_status").click(function(){
        if($("#filter_status").is(":checked")){
            $("#status_append").slideDown(200);
        }else{
            $("#status_append").slideUp(200);
        }
    });

    $("#filter_voucher_code").click(function(){
        if($("#filter_voucher_code").is(":checked")){
            $("#voucher_code_append").slideDown(200);
        }else{
            $("#voucher_code_append").slideUp(200);
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
    
    KTDatatableRemote.init();
});

var KTDatatableRemote = function() {
    var recordTable = function() {

        var dataSource = {
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/promoter/api/list',
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
                checkbox: {},
            },

            layout: {
                scroll: false,
                footer: false,
            },

            sortable: true,
            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

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
                                    <a class="dropdown-item" onclick="window.location='/superadmin/promoter/edit/${row.id}'">
                                        <i class="la la-edit"></i> 
                                        Edit
                                    </a>
                                    <a class="dropdown-item" onclick="deleteLink(event);" data-toggle="modal" data-target="#delete_modal" data-resource-name="${row.id}" data-route="/superadmin/promoter/delete/${row.id}">
                                        <i class="la la-trash"></i> 
                                        Delete
                                    </a>
                                </div>
                            </div>
                        `;
                    },
                }, {
                    field: 'name',
                    title: 'Name',
                }, {
                    field: 'email',
                    title: 'Email',
                }, {
                    field: 'voucher_code',
                    title: 'Voucher Code',
                }, {
                    field: 'status',
                    title: 'Status',
                    width: 85,
                    textAlign: 'center',
                    template: function(row) {
                        var status = {
                            "invited": {'class': 'warning'},
                            "delete": {'class': 'dark'},
                            "active": {'class': 'success'},
                            "inactive": {'class': 'danger'},
                        };
                        return `<span class="kt-badge kt-badge--${status[row.status].class} kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-${status[row.status].class}">${row.status.toUpperCase()}</span>`;
                    },
                }],


        };

        var datatable = $('#promoter_datatable').KTDatatable({...dataSource, ...dataStructure});
        
        datatable.columns('actions').visible(false);
        $("#kt_display").click(function(){
            if($("#check_name").is(':checked')){
                datatable.columns('name').visible(false);
            }else{
                datatable.columns('name').visible(true);
            }
            
            if($("#check_discount").is(':checked')){
                datatable.columns('discount').visible(false);
            }else{
                datatable.columns('discount').visible(true);
            }

            if($("#check_status").is(':checked')){
                datatable.columns('status').visible(false);
            }else{
                datatable.columns('status').visible(true);
            }

            if($("#check_voucher_code").is(':checked')){
                datatable.columns('voucher_code').visible(false);
            }else{
                datatable.columns('voucher_code').visible(true);
            }

            datatable.reload();
        });

        $("#kt_filter").click(function(){
            var dataApi = {
                name: {
                    filter: $("#filter_name").is(':checked') ? $("#name_append select").val() : "=",
                    values: $("#filter_name").is(':checked') ? ($("#name_append input").val() ? $("#name_append input").val().split(",") : null) : null,
                },
                email: {
                    filter: $("#filter_email").is(':checked') ? $("#email_append select").val() : "=",
                    values: $("#filter_email").is(':checked') ? ($("#email_append input").val() ? $("#email_append input").val().split(",") : null) : null,
                },
                voucher_code: {
                    filter: $("#filter_voucher_code").is(':checked') ? $("#voucher_code_append select").val() : "=",
                    values: $("#filter_voucher_code").is(':checked') ? ($("#voucher_code_append input").val() ? $("#voucher_code_append input").val().split(",") : null) : null,
                },
                status: {
                    filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : "=",
                    values: $("#filter_status").is(':checked') ? ($("#status_append input").val() ? $("#status_append input").val().split(",") : null) : null,
                },
            };
        
            setTimeout(() => {
                $.ajax({
                    url: '/superadmin/promoter/api/session',
                    data: dataApi,
                    success: function(response){
                        showFilterBox(dataApi, datatable, "promoters");
                        datatable.reload();
                    }
                });
            }, 500);
        });

    };

    return {
        // public functions
        init: function() {
            recordTable();
        },
    };
}();
