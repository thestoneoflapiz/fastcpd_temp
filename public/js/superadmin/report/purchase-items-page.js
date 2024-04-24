var start_date =  getStartDate() ;
var end_date = getEndDate() ;
var provider = $("#provider").val() ;
var KTDatatableRemote = function () {
    // Private functions

    // recordTable initializer
    var recordTable = function () {

        var datatable = $('#child_data_ajax').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/report/items/api/list',
                        params:{
                            
                            start_date: start_date,
                            end_date: end_date,
                            provider: provider,
                            
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

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    visible: false,
                }, {
                    field: 'purchased_date',
                    title: 'Purchase<br>Date',
                }, {
                    field: 'transaction_code',
                    title: 'Transaction<br>Code',
                }, {
                    field: 'provider',
                    title: 'Provider',
                }, {
                    field: 'purchased_by',
                    title: 'Purchased<br>By',
                }, {
                    field: 'type',
                    title: 'Type',
                    width: 50,
                    template: function(row){
                        return `${row.type=="Course"?"C":"W"}`;
                    }
                }, {
                    field: 'voucher',
                    title: 'Voucher',
                    template: function(row){
                        return `${row.voucher ? row.voucher : "<i class='fa fa-times kt-font-danger'></i>"}`;
                    }
                }, {
                    field: 'course',
                    title: 'Product',
                }, {
                    field: 'channel',
                    title: 'Channel',
                }, {
                    field: 'original_price',
                    title: 'Original Price',
                    template: function (row) {
                        return `₱${row.original_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'discounted_price',
                    title: 'Discounted Price',
                    template: function (row) {
                        if(row.discounted_price){
                            return `₱${row.discounted_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                        }

                        return `₱0`;
                    },
                }, {
                    field: 'provider_comm',
                    title: 'Provider Comm.&Rev',
                    template: function (row) {
                        return `${row.provider_comm} - ₱${row.provider_revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'fast_comm',
                    title: 'Fast Comm.&Rev',
                    template: function (row) {
                        return `${row.fast_comm} - ₱${row.fast_revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'affiliate_comm',
                    title: 'Promoter Comm.&Rev',
                    template: function (row) {
                        return `${row.affiliate_comm} - ₱${row.affiliate_revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
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
   
    $.ajax({
        url: "/superadmin/report/courses/api/provider-list",
        data: {
           
        },
        success:function(response){
            $("#provider").children('option:not(:first)').remove();
            response.data.forEach((list,index) => {
                $("#provider")
                .append($("<option></option>")
                .attr("value",list.id)
                .text(list.name));
            })
        },
        error:function(){

        },
    });

    $('#provider').select2({
        placeholder: "Please choose a Provider"
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
    $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchase-items/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+provider+"', method:'get'});")
    setTimeout(() => {
        KTDatatableRemote.init();
    },1000);
    $('#end_date').on('change', function () {
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        provider = $("#provider").val();
         var dataTable = $(".kt-datatable").KTDatatable();
         dataTable.destroy();
        setTimeout(() => {
            $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchase-items/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+provider+"', method:'get'});")
            KTDatatableRemote.init();
        },500);
    
    });
    $('#start_date').on('change', function () {
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        provider = $("#provider").val();
         var dataTable = $(".kt-datatable").KTDatatable();
         dataTable.destroy();
        setTimeout(() => {
            $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchase-items/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+provider+"', method:'get'});")
            KTDatatableRemote.init();
        },500);
    
    });
    $('#provider').on('change', function () {
        start_date = $("#start_date").val();
        end_date = $("#end_date").val();
        provider = $("#provider").val();
         var dataTable = $(".kt-datatable").KTDatatable();
         dataTable.destroy();
        setTimeout(() => {
            $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchase-items/"+changeFormatDate(start_date)+"/"+changeFormatDate(end_date)+"/"+provider+"', method:'get'});")
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
