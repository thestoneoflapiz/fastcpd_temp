const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const longerMonths = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
"use strict";
// Class definition
jQuery(document).ready(function () {

    RevenuePage.init();
    RevenueDatatable.init();

    $("#filter_period").click(function () {
        if ($("#filter_period").is(":checked")) {
            $("#period_append").slideDown(200);
        } else {
            $("#period_append").slideUp(200);
        }
    });

    $("#filter_amount").click(function () {
        if ($("#filter_amount").is(":checked")) {
            $("#amount_append").slideDown(200);
        } else {
            $("#amount_append").slideUp(200);
        }
    });

    $("#filter_expected_date").click(function () {
        if ($("#filter_expected_date").is(":checked")) {
            $("#expected_date_append").slideDown(200);
        } else {
            $("#expected_date_append").slideUp(200);
        }
    });

    $("#filter_notes").click(function () {
        if ($("#filter_notes").is(":checked")) {
            $("#notes_append").slideDown(200);
        } else {
            $("#notes_append").slideUp(200);
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
var RevenuePage = function () {

    var revenue_line_chart = function () {
        // LINE CHART

		var global =[];
		$.ajax({
			url: '/provider/revenue/api/lastsixmonths',
			success: function(response){
				$("#total_revenues").text(response.total_revenues);
				$.each(response.latestRevenues,function(index,value){
					global.push(value);
				});
			 new Morris.Line({
				    // ID of the element in which to draw the chart.
				    element: 'revenue-overall-line-chart',
				    // Chart data records -- each entry in this array corresponds to a point on
				    // the chart.
				     data: global
					
				    ,
				    // The name of the data record attribute that contains x-values.
				    xkey: 'x',
				    // A list of names of data record attributes that contain y-values.
				    ykeys: ['a'],
				    // Labels for the ykeys -- will be displayed when you hover over the
				    // chart.
				    parseTime: false,

				    xLabelFormat: function (x) {
					var index = parseInt(x.src.x);
					//console.log(index);
					return monthNames[index];
				    },
				    hoverCallback: function (index, options, content, row) {
					return `<div class='morris-hover-row-label'>${longerMonths[row.x]}</div><div class='morris-hover-point' style='color: #17a2b8;font-weight:600;'>Total: ₱ ${row.a.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</div>`;
				    },
				    labels: ['Total'],
				    lineColors: ['#17a2b8']
				});

			}
		});

       
    }

    return {
        init: function () {
	   
            revenue_line_chart();
		
        }
    };
}();

var RevenueDatatable = function () {
    var recordTable = function () {

        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/revenue/api/list',
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
                pageSize: 12,
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
            toolbar: {
                items: {
                    pagination: {
                        pageSizeSelect: [12, 24, 48]
                    }
                }
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    width: 60,
                    sortable: false, template: function (row) {
                        return `<button class="btn btn-label-info btn-sm" onclick="window.open('/provider/revenue/monthly/${row.id}/${row.generated_url.toLowerCase()}')">View</button>`;
                    },
                }, {
                    field: 'period',
                    title: 'Time Period',
                }, {
                    field: 'amount',
                    title: 'Amount',
                    template: function (row) {
                        if (row.amount < 1) {
                            return `<text style="color:#fd397a;font-weight:600">₱ ${row.amount.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;

                        }

                        return `<text style="color:#0abb87;font-weight:600">₱ ${row.amount.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;
                    },
                }, {
                    field: 'expected_date',
                    title: 'Expected Payment Date',
                }, {
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
                }, {
                    field: 'notes',
                    title: 'Notes',
                    width: 150,
                }],
        };

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

        $("#kt_display").click(function () {
            if ($("#check_period").is(':checked')) {
                datatable.columns('period').visible(false);
            } else {
                datatable.columns('period').visible(true);
            }

            if ($("#check_amount").is(':checked')) {
                datatable.columns('amount').visible(false);
            } else {
                datatable.columns('amount').visible(true);
            }

            if ($("#check_expected_date").is(':checked')) {
                datatable.columns('expected_date').visible(false);
            } else {
                datatable.columns('expected_date').visible(true);
            }

            if ($("#check_notes").is(':checked')) {
                datatable.columns('notes').visible(false);
            } else {
                datatable.columns('notes').visible(true);
            }

            datatable.reload();
        });

        $("#kt_filter").click(function () {
            var dataApi = {
                amount: {
                    filter: $("#filter_amount").is(':checked') ? $("#amount_append select").val() : "=",
                    values: $("#filter_amount").is(':checked') ? ($("#amount_append input").val() ? $("#amount_append input").val().split(",") : null) : null,
                },
                period: {
                    filter: $("#filter_period").is(':checked') ? $("#period_append select").val() : "=",
                    values: $("#filter_period").is(':checked') ? ($("#period_append input").val() ? $("#period_append input").val().split(",") : null) : null,
                },
                expected_date: {
                    filter: $("#filter_expected_date").is(':checked') ? $("#expected_date_append select").val() : "=",
                    values: $("#filter_expected_date").is(':checked') ? ($("#expected_date_append input").val() ? $("#expected_date_append input").val().split(",") : null) : null,
                },
                notes: {
                    filter: $("#filter_notes").is(':checked') ? $("#notes_append select").val() : "=",
                    values: $("#filter_notes").is(':checked') ? ($("#notes_append input").val() ? $("#notes_append input").val().split(",") : null) : null,
                },

            };

            setTimeout(() => {
                $.ajax({
                    url: '/provider/revenue/api/session',
                    data: dataApi,
                    success: function (response) {
                        showFilterBox(dataApi, datatable, "users");
                        datatable.reload();
                    }
                });
            }, 500);
        });

    };

    return {
        init: function () {
            recordTable();
        },
    };
}();

