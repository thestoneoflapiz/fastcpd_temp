const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const longerMonths = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

jQuery(document).ready(function () {
    RevenuePurchaseDatatable.init();
});


function renderTable(table) {
    switch (table) {
        case 'revenue':
            RevenuePurchaseDatatable.init();
            break;
        case 'enrollment':
            EnrollmentDatatable.init();
            break;
        case 'ratingVideoOnDemand':
            CourseRatingDatatable.init();
            break;
        case 'ratingWebinar':
            WebinarRatingDatatable.init();
            break;
    }
}

var RevenuePurchaseDatatable = function () {
    var purchaseTable = function () {
        var today = new Date();
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/revenue/month/api/overviewlist?month=' + `${longerMonths[today.getMonth()].toLowerCase()}-${today.getFullYear()}`,
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function (raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
			    var total_revenue = raw.total_revenue ? raw.total_revenue : 0;
			    var current_month_rev = raw.current_month_rev ? raw.current_month_rev : 0
 			    var total_enrolled = raw.total_enrolled ? raw.total_enrolled : 0;
			    var current_month_enrolled = raw.current_month_enrolled ? raw.current_month_enrolled : 0
 			    var total_ratings_course = raw.total_ratings_course ? raw.total_ratings_course : 0;
                var current_month_ratings_course = raw.current_month_ratings_course ? raw.current_month_ratings_course : 0
                var total_ratings_webinar= raw.total_ratings_webinar? raw.total_ratings_webinar: 0;
			    var current_month_ratings_webinar= raw.current_month_ratings_webinar? raw.current_month_ratings_webinar: 0

			    $("#total_revenue").html( total_revenue + `<br/><small class="kt-label-font-color-2" id="current_revenue">₱ ${current_month_rev} this month</small>`); 
			    $("#total_enrolled").html( total_enrolled + `<br/><small class="kt-label-font-color-2" id="current_month_enrolled">${current_month_enrolled} this month</small>`);
                $("#total_ratings_course").html( total_ratings_course + `<br/><small class="kt-label-font-color-2" id="current_month_ratings">${current_month_ratings_course} ratings this month</small>`);
                $("#total_ratings_webinar").html( total_ratings_webinar + `<br/><small class="kt-label-font-color-2" id="current_month_ratings">${current_month_ratings_webinar} ratings this month</small>`);
			    


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

        var datatable = $('#revenue-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);

        datatable.on('click', 'tr', function (row) {
            var is_toggle = $(row.target);
            if (is_toggle.hasClass(`fa fa-caret-down`)) { return };

            var row = $(row.currentTarget).data();
            var rowId = row.row;
            var rowData = row.obj;
            if(rowData){
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
            }
        });
    };

    return {
        init: function () {
            purchaseTable();
        },
    };
}();

var EnrollmentDatatable = function () {
    var enrollmentTable = function () {
        var today = new Date();
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/overview/enrollment/api/list?month=' + `${longerMonths[today.getMonth()].toLowerCase()}-${today.getFullYear()}`,
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
            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    visible: false,
                }, {
                    field: 'type',
                    title: 'Type',
                },{
                    field: 'course',
                    title: 'Course',
                }, {
                    field: 'revenue',
                    title: 'Revenue',
                    template: function (row) {
                        if (row.revenue < 1) {
                            return `<text style="color:#fd397a;font-weight:600">₱ ${row.revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;

                        }

                        return `<text style="color:#0abb87;font-weight:600">₱ ${row.revenue.toLocaleString(undefined, {maximumFractionDigits:2, minimumFractionDigits: 2})}</text>`;
                    },
                }, {
                    field: 'total_enrolled',
                    title: 'Enrollments',
                }],
        };

        var datatable = $('#enrollment-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);
    };

    return {
        init: function () {
            enrollmentTable();
        },
    };
}();

var CourseRatingDatatable = function () {
    var ratingTable = function () {
        var today = new Date();
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/overview/rating/api/list?month=' + `${longerMonths[today.getMonth()].toLowerCase()}-${today.getFullYear()}`,
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
            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    visible: false,
                }, {
                    field: 'feedback_date',
                    title: 'Date',
                }, {
                    field: 'customer',
                    title: 'Customer',
                }, {
                    field: 'course',
                    title: 'Course',
                }, {
                    field: 'rating',
                    title: 'Rating Grade',
                }, {
                    field: 'feedback',
                    title: 'Comment',
                }],
        };

        var datatable = $('#rating-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);
    };

    return {
        init: function () {
            ratingTable();
        },
    };
}();

var WebinarRatingDatatable = function () {
    var ratingTable = function () {
        var today = new Date();
        var dataSource = {
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: '/provider/overview/webinar-rating/api/list?month=' + `${longerMonths[today.getMonth()].toLowerCase()}-${today.getFullYear()}`,
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
            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    visible: false,
                }, {
                    field: 'feedback_date',
                    title: 'Date',
                }, {
                    field: 'customer',
                    title: 'Customer',
                }, {
                    field: 'webinar',
                    title: 'Course',
                }, {
                    field: 'rating',
                    title: 'Rating Grade',
                }, {
                    field: 'feedback',
                    title: 'Comment',
                }],
        };

        var datatable = $('#webinar-rating-datatable').KTDatatable({ ...dataSource, ...dataStructure });
        datatable.columns('actions').visible(false);
    };

    return {
        init: function () {
            ratingTable();
        },
    };
}();