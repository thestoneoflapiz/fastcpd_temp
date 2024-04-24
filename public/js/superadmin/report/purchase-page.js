var start_date =  getStartDate() ;
var end_date = getEndDate() ;
var method = 0 ;
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
                        url: '/superadmin/report/purchases/api/list',
                        params:{
                            start_date: start_date,
                            end_date: end_date,
                            method: method,
                        },
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

            detail: {
                title: 'Load sub table',
                content: subTableInit,
            },

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
                    textAlign: 'center',
                }, {
                    field: 'purchased_date',
                    title: 'Purchase<br/>Date',
                }, {
                    field: 'transaction_code',
                    title: 'Transaction<br/>Code',
                }, {
                    field: 'purchased_by',
                    title: 'Purchased<br/>By',
                }, {
                    field: 'payment_date',
                    title: 'Date of<br/>Payment',
                }, {
                    field: 'payment_gateway',
                    title: 'Mode of<br/>Payment',
                    template: function (row) {
                        return `${row.payment_gateway} - ${row.payment_method}`;
                    }
                }, {
                    field: 'original_price',
                    title: 'Original Price',
                    template: function (row) {
                        return `₱${row.original_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'total_discount',
                    title: 'Discounted Price',
                    template: function (row) {
                        if(row.total_discount){
                            return `₱${row.total_discount.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                        }

                        return `₱0`;
                    },
                }, {
                    field: 'items',
                    title: 'No. of<br/>Purch.<br/>   Items',
                    width: 60,
                }],
        });

       
       
        $('#kt_form_status').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_form_type').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_form_status,#kt_form_type').selectpicker();

        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.id).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/superadmin/report/items/api/list',
                            params: {
                                // custom query params
                                query: {
                                    purchaseID: e.data.id,
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
                        field: 'provider',
                        sortable: false,
                        title: 'Provider',
                    }, {
                        field: 'type',
                        sortable: false,
                        title: 'Type',
                        width: 50,
                        template: function(row){
                            return `${row.type=="Course"? "C" : "W"}`;
                        }
                    },{
                        field: 'product',
                        sortable: false,
                        title: 'Product',
                    }, {
                        field: 'channel',
                        sortable: false,
                        title: 'Channel',
                    }, {
                        field: 'original_price',
                        sortable: false,
                        title: 'Original</br>Price',
                        template: function (row) {
                            return `₱ ${row.original_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                        },
                    }, {
                        field: 'discounted_price',
                        sortable: false,
                        title: 'Discounted<br/>Price',
                        template: function (row) {
                            if(row.total_discount){
                                return `₱${row.discounted_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                            }
    
                            return `₱0`;
                        },
                    }, {
                        field: 'provider_comm',
                        sortable: false,
                        title: 'Provider<br/>Comm.&Rev',
                        template: function(row){
                            return `${row.provider_comm} - ₱${row.provider_revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                        }
                    }, {
                        field: 'fast_comm',
                        sortable: false,
                        title: 'Fast<br/>Comm.&Rev',
                        template: function(row){
                            return `${row.fast_comm} - ₱${row.fast_revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                        }
                    }, {
                        field: 'affiliate_comm',
                        sortable: false,
                        title: 'Promoter<br/>Comm.&Rev',
                        template: function(row){
                            return `${row.affiliate_comm} - ₱${row.affiliate_revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
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
  

    $('#payment_method').select2({
        placeholder: "Please choose a Payment Method"
    });

    $("#start_date").datepicker({
        todayHighlight: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        },
        endDate: '+0d',
    });

    $("#end_date").datepicker({
        todayHighlight: true,
       
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        },
        endDate: '+0d',
        setDate: getEndDate()
    });
    $("#start_date").datepicker("setDate",getStartDate());
    $("#end_date").datepicker("setDate", getEndDate());
    
    $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchases/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+method+"', method:'get'});")

    KTDatatableRemote.init();
    $('#end_date').on('change', function () {
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        method = $("#payment_method").val();
        $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchases/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+method+"', method:'get'});")
         var dataTable = $(".kt-datatable").KTDatatable();
        setTimeout(() => {
            dataTable.destroy();
            KTDatatableRemote.init();
        },500);
    
    });
    $('#start_date').on('change', function () {
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        method = $("#payment_method").val();
        $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchases/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+method+"', method:'get'});")
         var dataTable = $(".kt-datatable").KTDatatable();
        setTimeout(() => {
            dataTable.destroy();
            KTDatatableRemote.init();
        },500);
    
    });
    $('#payment_method').on('change', function () {
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        method = $("#payment_method").val();
        $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchases/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+method+"', method:'get'});")
         var dataTable = $(".kt-datatable").KTDatatable();
        setTimeout(() => {
            dataTable.destroy();
            KTDatatableRemote.init();
        },500);
    
    });
});

function getStartDate(){
    var date = new Date();
    var mm = date.getMonth() + 1;
    var yyyy = date.getFullYear();

    return mm + '/01/' + yyyy;
}
function getEndDate(){
    var date = new Date();
    var mm = date.getMonth() + 1;
    var dd = date.getDate();
    var yyyy = date.getFullYear();

    return mm + '/'+dd+'/' + yyyy;
}
function changeFormatDate($dates){
    var date = $dates.split('/');
    var new_date = date[2] + "-" + date[0] + "-" +date[1];

    return new_date;
}
