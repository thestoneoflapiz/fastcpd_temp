jQuery(document).ready(function() {
    $("#filter_voucher_code").click(function(){
        if($("#filter_voucher_code").is(":checked")){
            $("#voucher_code_append").slideDown(200);
        }else{
            $("#voucher_code_append").slideUp(200);
        }
    });

    $("#filter_discount").click(function(){
        if($("#filter_discount").is(":checked")){
            $("#discount_append").slideDown(200);
        }else{
            $("#discount_append").slideUp(200);
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

    $("#filter_created_by").click(function(){
        if($("#filter_created_by").is(":checked")){
            $("#created_by_append").slideDown(200);
        }else{
            $("#created_by_append").slideUp(200);
        }
    });

    $("#filter_status").click(function(){
        if($("#filter_status").is(":checked")){
            $("#status_append").slideDown(200);
        }else{
            $("#status_append").slideUp(200);
        }
    });

    $("#filter_type").click(function(){
        if($("#filter_type").is(":checked")){
            $("#type_append").slideDown(200);
        }else{
            $("#type_append").slideUp(200);
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
                        url: '/superadmin/settings/vouchers/api/list',
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
                                    <a class="dropdown-item" onclick="window.location='/superadmin/settings/vouchers/form?voucher_id=${row.id}'">
                                        <i class="la la-edit"></i> 
                                        Edit
                                    </a>
                                    <a class="dropdown-item" onclick="deleteLink(event);" data-toggle="modal" data-target="#delete_modal" data-resource-name="${row.voucher_code}" data-route="/superadmin/settings/vouchers/delete/${row.id}">
                                        <i class="la la-trash"></i> 
                                        Delete
                                    </a>
                                </div>
                            </div>
                        `;
                    },
                }, {
                    field: 'voucher_code',
                    title: 'Voucher',
                }, {
                    field: 'discount',
                    title: 'Discount(%)',
                }, {
                    field: 'session_start',
                    title: 'Start Date',
                    width: 100,
                }, {
                    field: 'session_end',
                    title: 'End Date',
                    width: 100,
                }, {
                    field: 'status',
                    title: 'Statuss',
                    width: 85,
                    textAlign: 'center',
                    template: function(row) {
                        var status = {
                            "upcoming": {'class': 'warning'},
                            "ended": {'class': 'dark'},
                            "active": {'class': 'success'},
                            "in-review": {'class': 'info'},
                            "rejected": {'class': 'danger'},
                        };
                        return `<span class="kt-badge kt-badge--${status[row.status].class} kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-${status[row.status].class}">${row.status.toUpperCase()}</span>`;
                    },
                }, {
                    field: 'type',
                    title: 'Type',
                    width: 85,
                    textAlign: 'center',
                    width: 150,
                    template: function(row) {
                        var type = {
                            "auto_applied": {'class': 'warning'},
                            "auto_applied_when_loggedin": {'class': 'success'},
                            "manual_apply": {'class': 'info'},
                        };
                        return `<span class="kt-badge kt-badge--${type[row.type].class} kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-${type[row.type].class}">${row.type.toUpperCase()}</span>`;
                    },
                },{
                    field: 'created_by',
                    title: 'Created By',
                    width: 150,
                }],


        };

        var datatable = $('#promotion_datatable').KTDatatable({...dataSource, ...dataStructure});
        
        datatable.columns('actions').visible(false);
        $("#kt_display").click(function(){
            if($("#check_voucher_code").is(':checked')){
                datatable.columns('voucher_code').visible(false);
            }else{
                datatable.columns('voucher_code').visible(true);
            }
            
            if($("#check_discount").is(':checked')){
                datatable.columns('discount').visible(false);
            }else{
                datatable.columns('discount').visible(true);
            }

            if($("#check_start_date").is(':checked')){
                datatable.columns('session_start').visible(false);
            }else{
                datatable.columns('session_start').visible(true);
            }

            if($("#check_end_date").is(':checked')){
                datatable.columns('session_end').visible(false);
            }else{
                datatable.columns('session_end').visible(true);
            }

            if($("#check_created_by").is(':checked')){
                datatable.columns('created_by').visible(false);
            }else{
                datatable.columns('created_by').visible(true);
            }

            if($("#check_status").is(':checked')){
                datatable.columns('status').visible(false);
            }else{
                datatable.columns('status').visible(true);
            }
            if($("#check_type").is(':checked')){
                datatable.columns('type').visible(false);
            }else{
                datatable.columns('type').visible(true);
            }
            datatable.reload();
            datatable.reload();
        });

        $("#kt_filter").click(function(){
            var dataApi = {
                voucher_code: {
                    filter: $("#filter_voucher_code").is(':checked') ? $("#voucher_code_append select").val() : "=",
                    values: $("#filter_voucher_code").is(':checked') ? ($("#voucher_code_append input").val() ? $("#voucher_code_append input").val().split(",") : null) : null,
                },
                discount: {
                    filter: $("#filter_discount").is(':checked') ? $("#discount_append select").val() : "=",
                    values: $("#filter_discount").is(':checked') ? ($("#discount_append input").val() ? $("#discount_append input").val().split(",") : null) : null,
                },
                start_date: {
                    filter: $("#filter_start_date").is(':checked') ? $("#start_date_append select").val() : "=",
                    values: $("#filter_start_date").is(':checked') ? ($("#start_date_append input").val() ? $("#start_date_append input").val().split(",") : null) : null,
                },
                end_date: {
                    filter: $("#filter_end_date").is(':checked') ? $("#end_date_append select").val() : "=",
                    values: $("#filter_end_date").is(':checked') ? ($("#end_date_append input").val() ? $("#end_date_append input").val().split(",") : null) : null,
                },
                created_by: {
                    filter: $("#filter_created_by").is(':checked') ? $("#created_by_append select").val() : "=",
                    values: $("#filter_created_by").is(':checked') ? ($("#created_by_append input").val() ? $("#created_by_append input").val().split(",") : null) : null,
                },
                status: {
                    filter: $("#filter_status").is(':checked') ? $("#status_append select").val() : null,
                },
                type: {
                    filter: $("#filter_type").is(':checked') ? $("#type_append select").val() : null,
                },
            };
        
            setTimeout(() => {
                $.ajax({
                    url: '/superadmin/settings/vouchers/api/session',
                    data: dataApi,
                    success: function(response){
                        showFilterBox(dataApi, datatable, "users");
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
