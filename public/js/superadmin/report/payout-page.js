var month = getPreviousMonth();
var year = getCurrentYear();
var user_type = $("#user_type").val();
var KTDatatableRemote = function () {
    // Private functions

    // recordTable initializer
    var recordTable = function () {
	var m = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
        var datatable = $('.kt-datatable').KTDatatable({
            // datasource definition
	
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/superadmin/report/payouts/api/list',
                        params:{
                            month: month,
                            year: year,
                            user_type: user_type,
                        },
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
			    var setOfData = [];
			    var setData;
                            if (typeof raw.data !== 'undefined') {
				
                                dataSet = raw.data;
				dataSet.forEach(function(element) { setOfData = setOfData.concat(element); });
                            }
                            return setOfData;
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
            },

            // column sorting
            sortable: true,

            pagination: true,

            // detail: {
            //     title: 'Load sub table',
            //     content: subTableInit,
            // },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [
                {
                    field: 'id',
                    visible: false,
                    title: '',
                    sortable: false,
                    width: 30,
                    textAlign: 'center',
                },
 		        {
                    field: 'month',
                    title: 'Month - Year',
                    template: function (row) {
                        return `${m[row.month-1]} - ${row.year}`;
                    },
                }, {
                    field: 'type',
                    title: 'User',
                }, {
                    field: 'provider_name',
                    title: 'Provider',
                }, {
                    field: 'full_name',
                    title: 'Name',
                }, {
                    field: 'amount',
                    sortable: false,
                    title: 'Amount',
                    template: function (row) {
                        return `₱${row.amount.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'expected_payment_date',
                    sortable: false,
                    title: 'Expected Payment Date',
                }, 
                {
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
                },
                {
                    field: 'notes',
                    sortable: false,
                    title: 'Notes',
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
            $('<div/>').attr('payout_id', 'child_data_ajax_' + e.data.payout_id).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: '/superadmin/report/payouts/items/api/list',
                            params: {
                                // custom query params
                                
                                payoutID: e.data.payout_id,
                                userType: e.data.user_type,
                                providerId: e.data.provider_id,
                                month: e.data.month,
                                year: e. data.year,
                                
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
                search: {
                    input: $('#generalSearch'),
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
                        title: 'Provider',
                    }, {
                        field: 'fast_cpd',
                        title: 'Fast CPD',
                    }, {
                        field: 'name',
                        title: 'name',
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
                        field: 'notes',
                        title: 'Notes',
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
  

    $('#user_type').select2({
        placeholder: "Please choose a user type"
    });

    $('#month_select').select2({
        placeholder: "Please choose a month"
    });

    $('#year_select').select2({
        placeholder: "Please choose a year"
    });

    $("#month_select").val(getPreviousMonth()).change();
    month = $("#month_select").val();
    $("#print").attr("onclick","printPDF({name:'Report - Purchases',route:'/data/pdf/superadmin/reports/purchases/"+month+"/"+year+"/"+user_type+"', method:'get'});")
    setTimeout(() => {
        KTDatatableRemote.init();
        $("#print").attr("onclick","printPDF({name:'Report - Payouts',route:'/data/pdf/superadmin/reports/payouts/"+month+"/"+year+"/"+user_type+"', method:'get'});")
    },1000);
    $('#month_select').on("change",function(){
        month = $("#month_select").val();
        year = $("#year_select").val();
        user_type = $("#user_type").val();
         var dataTable = $(".kt-datatable").KTDatatable();
         dataTable.destroy();
        setTimeout(() => {
           
            KTDatatableRemote.init();
            $("#print").attr("onclick","printPDF({name:'Report - Payouts',route:'/data/pdf/superadmin/reports/payouts/"+month+"/"+year+"/"+user_type+"', method:'get'});")
        },500);
    
    });
    $('#year_select').on("change",function(){
        month = $("#month_select").val();
        year = $("#year_select").val();
        user_type = $("#user_type").val();
         var dataTable = $(".kt-datatable").KTDatatable();
         dataTable.destroy();
        setTimeout(() => {
           
            KTDatatableRemote.init();
            $("#print").attr("onclick","printPDF({name:'Report - Payouts',route:'/data/pdf/superadmin/reports/payouts/"+month+"/"+year+"/"+user_type+"', method:'get'});")
        },500);
    
    });
    $('#user_type').on("change",function(){
        month = $("#month_select").val();
        year = $("#year_select").val();
        user_type = $("#user_type").val();
         var dataTable = $(".kt-datatable").KTDatatable();
         dataTable.destroy();
        setTimeout(() => {
           
            KTDatatableRemote.init();
            $("#print").attr("onclick","printPDF({name:'Report - Payouts',route:'/data/pdf/superadmin/reports/payouts/"+month+"/"+year+"/"+user_type+"', method:'get'});")
        },500);
    
    });

  
    // $("#user_type").val(0).change();

});
function getPreviousMonth(){
    date = new Date();
    var mm = date.getMonth();
    return mm;
}
function getCurrentYear(){
    date = new Date();
    var yy = date.getFullYear();
    return yy;
}