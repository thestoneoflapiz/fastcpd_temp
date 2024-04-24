jQuery(document).ready(function () {
    $(`#select-month-year`).select2({
        placeholder: "Please select a Month-Year report"
    });
    $(`#select-webinar`).select2({
        placeholder: "Please select a webinar"
    });
    $('#completion-datatable').hide();
    $('#webinar-datatable').hide();
    $(`#select-course`).select2({
        placeholder: "Please select a course"
    });
    $(`#prc-select-type`).select2({
        placeholder: "Please select type"
    });

    $("#month-year-row").hide();
    $("#video-on-demand-row").hide();
    $("#webinar-row").hide();
 
    $("#prc-select-type").on("change",function(){
        //$("#review-select-course").removeAttr("selected");
        if($("#prc-select-type").val() == "webinar")
        {
            $("#month-year-row").slideUp();
            $("#video-on-demand-row").slideUp();
            $("#webinar-row").slideDown();
        }
        if($("#prc-select-type").val() == "video-on-demand")
        {
            $("#video-on-demand-row").slideDown();
            $("#month-year-row").slideDown();
            $("#webinar-row").slideUp();
        }
       
    });

    $.ajax({
        url: "/provider/prc/completion/api/setMonthYearList",
        success:function(response){
            $("#select-month-year").children('option:not(:first)').remove();
            response.month_year.forEach((list,index) => {
                $("#select-month-year")
                .append($("<option></option>")
                .attr("value",list.value)
                .text(list.display));
            })
        },
        error:function(){

        },
    });

    PRCcompletionReport.init();
    WebinarCompletionReport.init();
});

var PRCcompletionReport = function () {
    var recordTable = function () {
        var selected_date = $(`#select-month-year`).val();
        var selected_course = $(`#select-course`).val();

        var dataSource = {
            // datasource definition
           
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/prc/completion/api/list?month='+selected_date+'&course='+selected_course,
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            } 
                            // console.log(selected_date);
                           
                           
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
            // columns definition
            columns: [
                {
                    field: 'date_period',
                    title: 'Date Period',
                }, {
                    field: 'participants',
                    title: 'Participants',
                }, {
                    field: 'course',
                    title: 'Course',
                }, {
                    field: 'id',
                    title: '',
                    sortable: false, 
                   
                    template: function (row) {
                        return `<button class='btn btn-label-info btn-sm' onclick='window.open("/data/pdf/provider/completion?page_orientation=P&page_size=Legal&recordID=${row.id}")'>Generate Report</button>`;
                    }
                }, {
                    field: 'attendance_id',
                    title: '',
                   
                    sortable: false, 
                    template: function (row) {
                        return `<button class='btn btn-label-success btn-sm' onclick='window.open("/data/pdf/provider/attendance?page_orientation=P&page_size=Legal&recordID=${row.attendance_id}")'>Donwload Attendance</button>`;
                    }
                }
            ],
        };

       

        $(`#select-month-year, #select-course`).change(function () {
            /**
             * set session to selection 
             * 
             */
            $('#completion-datatable').slideDown();
            setTimeout(() => {
                //console.log($("#select-month-year").val());
                selected_date = $(`#select-month-year`).val();
                selected_course = $(`#select-course`).val();
                
        
                dataSource = {
                    // datasource definition
                   
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/provider/prc/completion/api/list?month='+selected_date+'&course='+selected_course,
                                // sample custom headers
                                // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                                map: function (raw) {
                                    // sample data mapping
                                    var dataSet = raw;
                                    if (typeof raw.data !== 'undefined') {
                                        dataSet = raw.data;
                                    }// console.log(selected_date);
                                   
                                   
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
                dataStructure = {
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
                    // columns definition
                    columns: [
                        {
                            field: 'date_period',
                            title: 'Date Period',
                        }, {
                            field: 'participants',
                            title: 'Participants',
                        }, {
                            field: 'course',
                            title: 'Course',
                        }, {
                            field: 'id',
                            title: '',
                           
                            sortable: false, 
                            template: function (row) {
                                return `<button class='btn btn-label-info btn-sm' onclick='window.open("/data/pdf/provider/completion?page_orientation=P&page_size=Legal&recordID=${row.id}")'>Generate Report</button>`;
                            }
                        }, {
                            field: 'attendance_id',
                            title: '',
                          
                            sortable: false, 
                            template: function (row) {
                                return `<button class='btn btn-label-success btn-sm' onclick='window.open("/data/pdf/provider/attendance?page_orientation=P&page_size=Legal&recordID=${row.attendance_id}")'>Donwload Attendance</button>`;
                            }
                        }
                    ],
                };
                datatable.destroy();
                datatable = $('#completion-datatable').KTDatatable({ ...dataSource, ...dataStructure });
                
            }, 500);
         
        });
        var datatable = $('#completion-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);
    };

    return {
        init: function () {
            recordTable();
        },
    };
}();

// Webinar
var WebinarCompletionReport = function () {
    var recordTable = function () {
        var selected_webinar = $(`#select-webinar`).val();
       
        var dataSource = {
            // datasource definition
           
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/prc/completion/api/webinar-list?webinar='+selected_webinar,
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            } 
                            // console.log(selected_date);
                           
                           
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
            // columns definition
            columns: [
                {
                    field: 'session_date',
                    title: 'Date',
                }, {
                    field: 'participants',
                    title: 'Participants',
                }, {
                    field: 'webinar',
                    title: 'Webinar',
                }, {
                    field: 'id',
                    title: '',
                    sortable: false, 
                   
                    template: function (row) {
                        return `<button class='btn btn-label-info btn-sm' onclick='window.open("/data/pdf/provider/completion?page_orientation=P&page_size=Legal&recordID=${row.id}")'>Generate Report</button>`;
                    }
                }, {
                    field: 'webinar_id',
                    title: '',
                   
                    sortable: false, 
                    template: function (row) {
                        return `<button class='btn btn-label-success btn-sm' onclick='window.open("/data/pdf/provider/attendance?page_orientation=P&page_size=Legal&recordID=${row.webinar_id}")'>Donwload Attendance</button>`;
                    }
                }
            ],
        };

       

        $(`#select-webinar`).change(function () {
            /**
             * set session to selection 
             * 
             */
            $('#webinar-datatable').slideDown();
            setTimeout(() => {
                //console.log($("#select-month-year").val());
                selected_webinar = $(`#select-webinar`).val();
                
        
                dataSource = {
                    // datasource definition
                   
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/provider/prc/completion/api/webinar-list?webinar='+selected_webinar,
                                // sample custom headers
                                // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                                map: function (raw) {
                                    // sample data mapping
                                    var dataSet = raw;
                                    if (typeof raw.data !== 'undefined') {
                                        dataSet = raw.data;
                                    }// console.log(selected_date);
                                   
                                   
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
                dataStructure = {
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
                    // columns definition
                    columns: [
                        {
                            field: 'session_date',
                            title: 'Date',
                        }, {
                            field: 'participants',
                            title: 'Participants',
                        }, {
                            field: 'webinar',
                            title: 'Webinar',
                        }, {
                            field: 'id',
                            title: '',
                           
                            sortable: false, 
                            template: function (row) {
                                return `<button class='btn btn-label-info btn-sm' onclick='window.open("/data/pdf/provider/webinar-completion?page_orientation=P&page_size=Legal&recordID=${row.id}")'>Generate Report</button>`;
                            }
                        }, {
                            field: 'webinar_id',
                            title: '',
                          
                            sortable: false, 
                            template: function (row) {
                                return `<button class='btn btn-label-success btn-sm' onclick='window.open("/data/pdf/provider/attendance?page_orientation=P&page_size=Legal&recordID=${row.webinar_id}&webinar=webinar")'>Donwload Attendance</button>`;
                            }
                        }
                    ],
                };
                datatable.destroy();
                datatable = $('#webinar-datatable').KTDatatable({ ...dataSource, ...dataStructure });
                
            }, 500);
         
        });
        var datatable = $('#webinar-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);
    };

    return {
        init: function () {
            recordTable();
        },
    };
}();