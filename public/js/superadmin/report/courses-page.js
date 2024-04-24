var type = "video-on-demand";
var provider = $("#provider").val();
"use strict";

// Class definition



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
                        url: '/superadmin/report/courses/api/list',
                        params:{
                            type: type,
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
                pageSize: 10,
                serverPaging: true,
                serverFiltering: false,
                serverSorting: true,
            },
        };

        var dataStructure = structure(type);
       

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
    };

    return {
        // public functions
        init: function () {
            recordTable();
        },
    };
}();

function structure(type){
    if(type == "video-on-demand")
    {
        dataStructure = {
            extensions: {
                // selector
                checkbox: {},
            },

            // layout definition
            layout: {
                scroll: true,
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
                    // overflow: 'visible',
                    visible: false,
                }, {
                    field: 'provider_name',
                    title: 'Provider',
                }, {
                    field: 'course_title',
                    title: 'Course',
                }, {
                    field: 'course_published_at',
                    title: 'Date Published',
                }, {
                    field: 'course_session_start',
                    title: 'Start Date',
                }, {
                    field: 'course_price',
                    title: 'Price',
                    template: function (row) {
                        return `₱ ${row.course_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'enrollees_total',
                    sortable: false,
                    title: 'No. of Enrollees',
                }, {
                    field: 'cpd_units',
                    sortable: false,
                    title: 'CPD UNITS',
                    width: 350,
                    template: function (row) {

                        var display = ``;
                        row.cpd_units.forEach((value, index) => {
                            display = `${value.profession} - ${value.units}<br/>`;
                        });

                        return display;
                    },
                }, {
                    field: 'instructors',
                    sortable: false,
                    title: 'Instructors',
                    template: function (row) {

                        var display = ``;
                        row.instructors.forEach((value, index) => {
                            display = `${value.name}<br/>`;
                        });

                        return display;
                    },
                }
            ]
        };
    }
    else
    {

        dataStructure = {
            extensions: {
                // selector
                checkbox: {},
            },
            // layout definition
            layout: {
                scroll: true,
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
                    // overflow: 'visible',
                    visible: false,
                }, {
                    field: 'provider_name',
                    title: 'Provider',
                }, {
                    field: 'course_title',
                    title: 'Course',
                }, {
                    field: 'course_published_at',
                    title: 'Date Published',
                }, {
                    field: 'start_date',
                    sortable: false,
                    title: 'Start Date',
                }, {
                    field: 'price_with',
                    sortable: false,
                    title: 'Price w/ CPD units',
                    template: function (row) {
                        return `₱ ${row.price_with.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                },{
                    field: 'price_without',
                    sortable: false,
                    title: 'Price w/o CPD units',
                    template: function (row) {
                        return `₱ ${row.price_without.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}`
                    },
                }, {
                    field: 'enrollees_total',
                    sortable: false,
                    title: 'No. of Enrollees',
                }, {
                    field: 'cpd_units',
                    sortable: false,
                    title: 'CPD UNITS',
                    width: 350,
                    template: function (row) {

                        var display = ``;
                        row.cpd_units.forEach((value, index) => {
                            display = `${value.profession} - ${value.units}<br/>`;
                        });

                        return display;
                    },
                }, {
                    field: 'instructors',
                    sortable: false,
                    title: 'Instructors',
                    template: function (row) {

                        var display = ``;
                        row.instructors.forEach((value, index) => {
                            display = `${value.name}<br/>`;
                        });

                        return display;
                    },
                }
            ]
        };

    }
    return dataStructure;
}

jQuery(document).ready(function () {
    KTDatatableRemote.init();

    $("#print").attr("onclick","printPDF({name:'Report - Courses',route:'/data/pdf/superadmin/reports/courses/"+type+"/"+provider+"', method:'get'});")

    $('#provider').select2({
        placeholder: "Please choose a Provider"
    });

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
    $("#provider").on("change",function(){
        provider = $("#provider").val();
        $("#print").attr("onclick","printPDF({name:'Report - Courses',route:'/data/pdf/superadmin/reports/courses/"+type+"/"+provider+"', method:'get'});")
        var datatable = $("#ajax_data").KTDatatable();
        datatable.destroy();
        setTimeout(() => {
           
            KTDatatableRemote.init();
        }, 500);
    });

    $("#type").on("change",function(){
        type = $("#type").val();
        $("#print").attr("onclick","printPDF({name:'Report - Courses',route:'/data/pdf/superadmin/reports/courses/"+type+"/"+provider+"', method:'get'});")
        var datatable = $('#ajax_data').KTDatatable();
        datatable.destroy();
        setTimeout(() => {
            
            KTDatatableRemote.init();
        }, 500);
    });

  
});
