"use strict";

// Class definition
var RevenueMonthChart = function () {
    // Profit Share Chart.
    // Based on Chartjs plugin - http://www.chartjs.org/
    var earningtotalChart = function () {
        if (!KTUtil.getByID('earning_total_chart')) {
            return;
        }
	var totalearnings;
	

        
		$.ajax({
			url: "/provider/revenue/api/totalearnings",
			data:{
				"payout_id":payout_id,
			},
			success: function(response){
				
			var total_earnings = response.data.fast_revenue + response.data.provider_revenue + response.data.endorser_revenue;
			var fast_revenue = Math.round((response.data.fast_revenue/total_earnings)*100) == 0 ? 0 : Math.round((response.data.fast_revenue/total_earnings)*100) ;
			var provider_revenue = Math.round((response.data.provider_revenue/total_earnings)*100) == 0 ? 0 : Math.round((response.data.provider_revenue/total_earnings)*100);
			var affilliate = Math.round((response.data.endorser_revenue/total_earnings)*100) == 0 ? 0 : Math.round((response.data.endorser_revenue/total_earnings)*100);
			var refund = Math.round(((response.data.refund ? response.data.refund : 0) /total_earnings)*100) == 0 ? 0 : Math.round((response.data.refund/total_earnings)*100) ;
			if(response.data.fast_revenue || response.data.provider_revenue || response.data.endorser_revenue ){
				$("#total_earning_legends").show();
				$("#fast_revenue").text(fast_revenue+"% FastCPD Organic");
				$("#provider_revenue").text(provider_revenue+"% Your Promotions");
				$("#endorser_revenue").text(affilliate+"% Affiliate Programs");
                $("#refund").text(refund+"% Refunds");
                $("#total_earning_legends").attr("style","display:absolute");
    
			}else{
				$("#total_earning_legends").hide();
				
			}
			
			
			var config = {
			    	type: 'doughnut',
				data: {
				     datasets: [{
					  data: [
					       response.data.fast_revenue ? response.data.fast_revenue : 100, 
					       response.data.provider_revenue ? response.data.provider_revenue : 0,
				     	       response.data.endorser_revenue ? response.data.endorser_revenue : 0, 
 				   	       0
					   ],
					   backgroundColor: [
						KTApp.getStateColor('success'),
						KTApp.getStateColor('danger'),
						KTApp.getStateColor('warning'),
						KTApp.getStateColor('info'),
					   ]
					}],
				    },
			       options: {
				       cutoutPercentage: 50,
				       responsive: true,
				       maintainAspectRatio: false,
				       legend: {
					    display: false,
					},
					animation: {
					    animateScale: true,
					    animateRotate: true
					},
					tooltips: {
					    enabled: response.data.fast_revenue || response.data.provider_revenue || response.data.endorser_revenue ? true : false,
					    callbacks: {
						label: function (tooltipItem, data) {
						    var indice = tooltipItem.index;
						    return ' ₱ ' + data.datasets[0].data[indice].toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2}) + '';
						}
					    }
					}
			    	}
				
 			};

			var ctx = KTUtil.getByID('earning_total_chart').getContext('2d');
			var myDoughnut = new Chart(ctx, config);
		},
	});
		    
       

    }

    var promotionChart = function () {
        if (!KTUtil.getByID('promotion_chart')) {
            return;
        }
 	$.ajax({
		url: "/provider/revenue/api/promotionactivity",
		data:{
			"payout_id":payout_id,
		},
		success: function(response){
			var promote = [];
			var promotions = response.vouchers;
			var promotion_legend = response.promotion_legend;
            var bgColorPicker = [];
            var counterZero = 0;
			promotion_legend.forEach(function(value,index){
				var color = ["kt-bg-info","kt-bg-warning","kt-bg-danger","kt-bg-success"];
				var bgcolor = [KTApp.getStateColor('info'),KTApp.getStateColor('warning'),KTApp.getStateColor('danger'),KTApp.getStateColor('success')];
				var promotion_used = Object.values(value);
				var promotion_key_used = Object.keys(value);
				var random_num = Math.floor(Math.random() * 4);
				bgColorPicker.push(bgcolor[random_num]);
				var percentage = Math.round(promotion_used != 0 ? ((promotion_used/response.total_promotion)*100) : 0)
					$(`#promotion_legend`).append(`
					    <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet ${color[random_num]}"></span>
                                                <span class="kt-widget14__stats">${percentage}% Promo ${promotion_key_used}</span>
                                            </div>`);
                if(percentage == 0){
                    counterZero = counterZero + 1; 
                    }
            });

            if(counterZero == promotion_legend.length){
                $(`#promotion_legend`).attr("style","display:none");
                bgColorPicker.push(KTApp.getStateColor('danger'));
            }
            //console.log(response.data[0]);
			
			var config = {
				    type: 'doughnut',
				    data: {
					datasets: [{
					    data: response.data[0] ? response.data : [100,0,0,0],
					    backgroundColor: response.data ? bgColorPicker : KTApp.getStateColor('info')
					}],
					labels: promotions
				    },
				    options: {
					cutoutPercentage: 50,
					responsive: true,
					maintainAspectRatio: false,
					legend: {
					    display: false,
					},
					animation: {
					    animateScale: true,
					    animateRotate: true
					},
					tooltips: {
					    enabled: counterZero == promotion_legend.length ? false :true,
					    callbacks: {
						label: function (tooltipItem, data) {
						    var indice = tooltipItem.index;
						    return data.labels[indice] + ': ' + data.datasets[0].data[indice] + ' time/s';
						}
					    }
					}
				    }
				};

				var ctx = KTUtil.getByID('promotion_chart').getContext('2d');
				var myDoughnut = new Chart(ctx, config);
		},
	});

        

    }

    var earningcoursesChart = function () {
        if (!KTUtil.getByID('earning_courses_chart')) {
            return;
        }
	$.ajax({
		url: "/provider/revenue/api/earningsbycourse",
		data:{
			"payout_id":payout_id,
		},
		success: function(response){
			var course_list_legend = response.courses_list_legend;
            var bgColorPicker = [];
            var counterZero = 0;
            //console.log(course_list_legend.length);
			course_list_legend.forEach(function(value,index){
				var color = ["kt-bg-info","kt-bg-warning","kt-bg-danger","kt-bg-success"];
				var bgcolor = [KTApp.getStateColor('info'),KTApp.getStateColor('warning'),KTApp.getStateColor('danger'),KTApp.getStateColor('success')];
				var course_used = Object.values(value);
				var course_key_used = Object.keys(value);
				var random_num = Math.floor(Math.random() * 4);
				bgColorPicker.push(bgcolor[random_num]);
				var percentage = Math.round(course_used != 0 ? (course_used/response.total_courses_earned)*100 : 0)
					$(`#course_legend`).append(`
					    <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet ${color[random_num]}"></span>
                                                <span class="kt-widget14__stats">${percentage}% ${course_key_used}</span>
                                            </div>`);
                if(percentage == 0){
                   counterZero = counterZero + 1; 
                }
            });
            if(counterZero == course_list_legend.length){
                $(`#course_legend`).attr("style","display:none");
                bgColorPicker.push(KTApp.getStateColor('danger'));
            }
			
			var config = {
			    type: 'doughnut',
			    data: {
				datasets: [{
				    data: response.data[0] != null ? response.data : [100,0,0,0],
				    backgroundColor: bgColorPicker
				}],
				labels: response.courses
			    },
			    options: {
				cutoutPercentage: 50,
				responsive: true,
				maintainAspectRatio: false,
				legend: {
				    display: false,
				},
				animation: {
				    animateScale: true,
				    animateRotate: true
				},
				tooltips: {
				    enabled: counterZero == course_list_legend.length ? false :true,
				    callbacks: {
				        label: function (tooltipItem, data) {
				            var indice = tooltipItem.index;
				            return '₱ ' + data.datasets[0].data[indice].toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2}) + '';
				        }
				    }
				}
			    }
			};

			var ctx = KTUtil.getByID('earning_courses_chart').getContext('2d');
			var myDoughnut = new Chart(ctx, config);		
		},
	});
        
    }

    return {
        // Init demos
        init: function () {
            // init charts
            earningtotalChart();
            promotionChart();
            earningcoursesChart();
        }
    };
}();

var RevenuePurchaseDatatable = function () {
    var purchaseTable = function () {
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/revenue/month/api/list?month=' + reported_date+'&payout_id='+payout_id,
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
            toolbar: {
                items: {
                    pagination: {
                        pageSizeSelect: [10, 20, 30]
                    }
                }
            },
            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    visible: false,
                }, {
                    field: 'purchase_date',
                    title: 'Date',
                    width: 85,
                }, {
                    field: 'customer',
                    title: 'Customer',
                },
                {
                    field: 'type',
                    title: 'Type',
                },
                {
                    field: 'course',
                    title: 'Course',
                },
                {
                    field: 'coupon_code',
                    title: 'Coupon Code',
                },
                {
                    field: 'channel',
                    title: 'Channel',
                },
                {
                    field: 'price_paid',
                    title: 'Price Paid',
                    template: function (row) {
                        if (row.price_paid < 1) {
                            return `<text style="color:#fd397a;font-weight:600">₱ ${row.price_paid.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;

                        }

                        return `<text style="font-weight:600">₱ ${row.price_paid.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;
                    },
                },
                {
                    field: 'revenue',
                    title: 'Revenue',
                    template: function (row) {
                        if (row.revenue < 1) {
                            return `<text style="color:#fd397a;font-weight:600">₱ ${row.revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;

                        }

                        return `<text style="color:#0abb87;font-weight:600">₱ ${row.revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;
                    },
                }],
        };

        var datatable = $('#ajax_data').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);

        datatable.on('click', 'tr', function (row) {
            var is_toggle = $(row.target);
            if (is_toggle.hasClass(`fa fa-caret-down`)) { return };

            var row = $(row.currentTarget).data();
            var rowId = row.row;
            var rowData = row.obj;

            $(`.customer_earning_body`).empty().html(`
                <div class="row">
                    <div class="col-6 strong">Original Price</div>
                    <div class="col-6">₱ ${rowData.original_price.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Price Paid</div>
                    <div class="col-6">₱ ${rowData.price_paid.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Your Revenue</div>
                    <div class="col-6">₱ ${rowData.revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})} (${rowData.percent}%)</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Course</div>
                    <div class="col-6">${rowData.course}</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Customer</div>
                    <div class="col-6">${rowData.customer}</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Purchase Date</div>
                    <div class="col-6">${rowData.purchase_date}</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Payment Date</div>
                    <div class="col-6">${rowData.payment_date}</div>
                </div>
                <div class="row">
                    <div class="col-6 strong">Channel</div>
                    <div class="col-6">${rowData.channel}</div>
                </div>
            `);

            $(`#customer_earnings_modal`).modal("show");
        });
    };

    return {
        init: function () {
            purchaseTable();
        },
    };
}();

// Class initialization on page load
jQuery(document).ready(function () {
    RevenueMonthChart.init();
    RevenuePurchaseDatatable.init();
});
