var globe_ref = null;
var globe_method = null;

jQuery(document).ready(function() {
    $('[name="month_filter"], [name="year_filter"]').select2({
        width: "100%"
    });

    fetch_records();
});

function dpPayment(ref, proceed){
    globe_ref = ref;
    globe_method = "dp";

    if(!proceed){
        var cancel = $(`#cancel-order-modal`);
        cancel.modal("show");
        cancel.find(`.modal-body`).html(`<p style="text-align:center;">You're trying to cancel reference# <b>${ref}</b>.<br>Are you sure you want to cancel it?</p>`);
        return;
    }

    var $submit = $(`#continue-payment`);
    $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning disabled`).prop("disabled", true);

    $.ajax({
        method: "POST",
        url: "/checkout/pdragon/payment/process/continue",
        data: {
            _token: $("[name='_token']").val(),
            reference_number: globe_ref,
        },
        success: function(response){
            setTimeout(() => {
                window.location=response.redirect;
            }, 1500);
        },
        error: function(response){
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }else{
                toastr.error("Something went wrong! Please refresh your browser");
            }
            $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning disabled`).prop("disabled", false);
        }
    });
}

function pmPayment(ref, proceed){
    globe_ref = ref;
    globe_method = "pm";

    if(!proceed){
        var cancel = $(`#cancel-order-modal`);
        cancel.modal("show");
        cancel.find(`.modal-body`).html(`<p style="text-align:center;">You're trying to cancel reference# <b>${ref}</b>.<br>Are you sure you want to cancel it?</p>`);
        return;
    }
}

$(`button#cancel-order-modal-submit`).click(function(){
    var $submit = $(`#cancel-payment`);
    $submit.addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning disabled`).prop("disabled", true);

    if(globe_method=="pm"){
        $.ajax({
            method: "POST",
            url: "/checkout/pmongo/e-wallet-payment/process/cancel",
            data: {
                _token: $("[name='_token']").val(),
                reference_number: globe_ref,
            },
            success: function(response){
                toastr.info(`Order reference# <b>${globe_ref}</b> has been cancelled!`);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(response){
                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){
                    toastr.error(body.message);
                }else{
                    toastr.error("Something went wrong! Please refresh your browser");
                }
                $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning disabled`).prop("disabled", false);
            }
        });
    }else{
        $.ajax({
            method: "POST",
            url: "/checkout/pdragon/payment/process/cancel",
            data: {
                _token: $("[name='_token']").val(),
                reference_number: globe_ref,
            },
            success: function(response){
                toastr.info(`Order reference# <b>${globe_ref}</b> has been cancelled!`);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(response){
                var body = response.responseJSON;
                if(body.hasOwnProperty("message")){
                    toastr.error(body.message);
                }else{
                    toastr.error("Something went wrong! Please refresh your browser");
                }
                $submit.removeClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--warning disabled`).prop("disabled", false);
            }
        });
    }
    
});

function viewUnits($item_id){
    toastr.info("Please wait...");

    var $viewUnits = $("#view-modal");
    $.ajax({
        url: "/api/courses/view/units",
        data: { item_id: $item_id },
        success: function(response){
            var $bodyText = "";
            response.forEach(cpd => {
                $bodyText = `<tr>
                    <td>${cpd.title}</td>
                    <td>${cpd.units}</td>
                    <td>${cpd.program_no}</td>
                </tr>`;
            });

            $viewUnits.find(".modal-body").html(`
                <table class="table table-striped" width="100%">
                <tr>
                    <td>Profession</td>
                    <td>CPD Units</td>
                    <td>Program No.</td>
                </tr>
                ${$bodyText}
                </table>
            `);

            setTimeout(() => {
                $viewUnits.modal("show");
            }, 1000);
        },
        error: function(response){
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }else{
                toastr.error("Something went wrong! Please refresh your browser");
            }
        }
    });
}

function viewSchedule($item_id){
    toastr.info("Please wait...");

    var $viewSched = $("#view-modal");
    $.ajax({
        url: "/profile/webinar/view/schedule",
        data: { item_id: $item_id },
        success: function(response){
            var $bodyText = "";
            response.data.forEach(session => {

                var $schedule = ``;
                session.sessions.forEach(sched => {
                    $schedule += `${sched.start} to ${sched.end} <br/>`;
                });

                $bodyText += `<tr>
                    <td>${session.session_date_string}</td>
                    <td colspan="2">${$schedule}</td>
                </tr>`;
            });

            $viewSched.find(".modal-body").html(`
                <table class="table table-striped" width="100%">
                <tr>
                    <td>Date</td>
                    <td colspan="2">Schedule</td>
                </tr>
                ${$bodyText}
                </table>
            `);

            setTimeout(() => {
                $viewSched.modal("show");
            }, 1000);
        },
        error: function(response){
            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
            }else{
                toastr.error("Something went wrong! Please refresh your browser");
            }
        }
    });
}

$('[name="month_filter"]').change(function() {
    month = $(this).val();
    fetch_records();
});

$('[name="year_filter"]').change(function() {
    year = $(this).val();
    fetch_records();
});

var fr_ajax = null;
function fetch_records(){
    $("#overview-purchase-accordion").empty();
    loading(true);

    fr_ajax = $.ajax({
        url: "/profile/overview/list",
        data: {
            month: month,
            year: year,
        },
        beforeSend : function()    {           
            if(fr_ajax != null) {
                fr_ajax.abort();
            }
        },
        success: function (response) {
            display_overview_records(response);
        }, 
        error: function (response){
            var body = response.responseJSON;
            if(body && body.hasOwnProperty("message")){
                toastr.error(body.message);
                return;
            }

            toastr.error("Unable to retrieve records! Please refresh your browser.");
        }
    });
}

function display_overview_records(response){
    var accordion_wrapper = $("#overview-purchase-accordion");

    response.data.forEach(purchase => {
        var card_wrapper = `
            <div class="card">
                <div class="card-header" id="purchase-card-${purchase.id}">
                    <div class="card-title collapsed ${purchase.payment_status}-status" data-toggle="collapse" data-target="#purchase-card-bodywrap-${purchase.id}" aria-expanded="false" aria-controls="purchase-card-bodywrap-${purchase.id}">
                        <div class="row" style="width:100%">
                            <div class="col-xl-3 col-md-3 col-sm-6">${purchase.created_at_string}</div>
                            <div class="col-xl-3 col-md-3 col-sm-6">${purchase.reference_number}</div>
                            <div class="col-xl-3 col-md-3 col-sm-6">${purchase.payment_normal_method}</div>
                            <div class="col-xl-3 col-md-3 col-sm-6">&#8369;${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(purchase.total_amount))}</div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                            </g>
                        </svg> 
                    </div>
                </div>
                <div id="purchase-card-bodywrap-${purchase.id}" class="collapse" aria-labelledby="purchase-card-${purchase.id}" data-parent="#overview-purchase-accordion">
                </div>
            </div>
        `;

        accordion_wrapper.append(card_wrapper);

        var card_body_wrapper = $(`#purchase-card-bodywrap-${purchase.id}`);
        var card_body = `
            <div class="card-body">
                <table class="table table-striped" width="100%" style="font-size:1rem;">
                    <thead> 
                        <tr>
                            <th>Payment At</th>
                            <th>Payment Status</th>
                            `+((purchase.payment_status=="waiting" || purchase.payment_status=="pending") && purchase.payment_gateway == "dragonpay" ? "<th>Actions</th>" : "")+`
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>`+(purchase.payment_at ? purchase.payment_at_string : "---")+`</td>
                            <td>`+(purchase.payment_status=="waiting" && purchase.payment_gateway == "dragonpay" ? "WAITING FOR CONFIRMATION" : purchase.payment_status.toUpperCase())+`</td>
                            <td>
                            `+( purchase.payment_gateway == "dragonpay" && (purchase.payment_status=="waiting" || purchase.payment_status=="pending") ? `
                                `+(purchase.payment_status=="waiting"?`<button class="btn btn-info btn-sm kt-margin-b-10" id="continue-payment" onclick="dpPayment('${purchase.reference_number}', true)">Continue Payment</button> <br/>`: ``)+`
                                <button class="btn btn-secondary btn-sm" id="cancel-payment" onclick="dpPayment('${purchase.reference_number}', false)">Cancel Order</button>
                                `: (jQuery.inArray(purchase.payment_method, ["gcash", "grab_pay"]) >= 0 && jQuery.inArray(purchase.payment_status, ["waiting", "pending"]) >= 0 ? `
                                <button class="btn btn-info btn-sm kt-margin-b-10" id="continue-payment" onclick="window.open('${purchase.payment_notes}')">Continue Payment</button> <br/>
                                <button class="btn btn-secondary btn-sm" id="cancel-payment" onclick="pmPayment('${purchase.reference_number}', false)">Cancel Order</button>
                            `: 
                            ""))+`
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;

        card_body_wrapper.append(card_body);

        purchase.items.forEach(item => {
            if(item.type=="course"){
                var item_wrapper = `
                    <div class="kt-portlet kt-portlet--bordered kt-margin-15 kt-padding-10">
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-4 col-6 kt-margin-b-10">
                                <div class="background_image-div" style="background-image:url(${item.data_record.poster});"></div>
                            </div>
                            <div class="col-xl-5 col-lg-6 col-md-6 col-12 kt-margin-b-10">
                                <h5>
                                    ${item.data_record.title} <br/>
                                    <button class="btn btn-sm btn-secondary kt-margin-b-15 kt-margin-t-10" onclick="viewUnits(${item.id})">View CPD Units</button></br>
                                    <b>&#8369;${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(item.total_amount))}</b>
                                    `+(item.amount_saved > 0 ? `<br/><small>You saved <b>&#8369;${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(item.amount_saved))}</b> with <b>${item.voucher}</b> code</small>` : "")+`
                                </h5>
                            </div>
                            <div class="col-xl-3 col-lg-5 col-md-3 col-12">
                                <h5 class="kt-margin-b-20">
                                    <b>`+(item.fast_status!="completed" ? "IN-PROGRESS" : "COMPLETE")+`</b>
                                    <br/>
                                    <br/>
                                    <span>`+(`${(item.progress_list.complete ? `<i class="fa fa-check-circle kt-font-success" style="font-size:18px;"></i>` : `<i class="la la-circle" style="font-size:18px;"></i>`)}`)+`&nbsp; Finish the Course</span><br/>
                                    <span>`+(`${(item.progress_list.feedback ? `<i class="fa fa-check-circle kt-font-success" style="font-size:18px;"></i>` : `<i class="la la-circle" style="font-size:18px;"></i>`)}`)+`&nbsp; Leave a Rating</span>
                                </h5>
                                `+(purchase.payment_status=="paid" && purchase.fast_status=="confirmed" ? (
                                    item.progress_list.complete && item.progress_list.feedback ? `
                                        <form action="/data/pdf/certificate" method="get" target='_blank'>
                                            `+(item.certificate_code ? `<input type="hidden" name="certificate_code" id="certificate_hash_2" value="${item.certificate_code.certificate_code}" />` : "")+`
                                            <button class="btn btn-warning" onclick="toastr.info('Loading document...')">View Certificate</button>
                                        </form>
                                    ` : `<button class="btn btn-info" onclick="window.open('/${item.type}/live/${item.data_record.url}')">Visit Live Course</button>`
                                ): "")+`
                            </div>
                        </div>
                    </div>
                `;
            }else{
                var item_wrapper = `
                    <div class="kt-portlet kt-portlet--bordered kt-margin-15 kt-padding-10">
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-4 col-6 kt-margin-b-10">
                                <div class="background_image-div" style="background-image:url(${item.data_record.poster});"></div>
                            </div>
                            <div class="col-xl-5 col-lg-6 col-md-6 col-12 kt-margin-b-10">
                                <h5>
                                    ${item.data_record.title} <br/>
                                    `+(item.offering_type!="without" ? `<button class="btn btn-sm btn-secondary kt-margin-b-15 kt-margin-t-10" onclick="viewUnits(${item.id})">View CPD Units</button>` : "")+`
                                    <button class="btn btn-sm btn-secondary kt-margin-b-15 kt-margin-t-10" onclick="viewSchedule(${item.id})">View Schedule</button></br>
                                    <b>&#8369;${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(item.total_amount))}</b>
                                    `+(item.amount_saved > 0 ? `<br/><small>You saved <b>&#8369;${(new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2}).format(item.amount_saved))}</b> with <b>${item.voucher}</b> code</small>` : "")+`
                                </h5>
                            </div>
                            <div class="col-xl-3 col-lg-5 col-md-3 col-12">
                                <h5 class="kt-margin-b-20">
                                    <b>`+(item.fast_status!="completed" ? "IN-PROGRESS" : "COMPLETE")+`</b>
                                    <br/>
                                    <br/>
                                    <span>`+(`${(item.progress_list.complete ? `<i class="fa fa-check-circle kt-font-success" style="font-size:18px;"></i>` : `<i class="la la-circle" style="font-size:18px;"></i>`)}`)+`&nbsp; Complete Attendance</span><br/>
                                    <span>`+(`${(item.progress_list.feedback ? `<i class="fa fa-check-circle kt-font-success" style="font-size:18px;"></i>` : `<i class="la la-circle" style="font-size:18px;"></i>`)}`)+`&nbsp; Leave a Rating</span>
                                </h5>
                                `+(purchase.payment_status=="paid" && purchase.fast_status=="confirmed" ? (
                                    item.progress_list.complete && item.progress_list.feedback ? `
                                        <form action="/data/pdf/certificate" method="get" target='_blank'>
                                            `+(item.certificate_code ? `<input type="hidden" name="certificate_code" id="certificate_hash_2" value="${item.certificate_code.certificate_code}" />` : "")+`
                                            <button class="btn btn-warning" onclick="toastr.info('Loading document...')">View Certificate</button>
                                        </form>
                                    ` : `<button class="btn btn-info" onclick="window.open('/${item.type}/live/${item.data_record.url}')">Visit Live Webinar</button>`
                                ): "")+`
                            </div>
                        </div>
                    </div>
                `;
            }

            card_body_wrapper.find(".card-body").append(item_wrapper);
        });

    });

    loading(false);
}

/**
 * loading show/hide
 */
function loading(show){
    var loading_div = $(".loading-div");
    if(show){
        loading_div.show();
        loading_div.next().hide();
        return;
    }

    loading_div.hide();
    loading_div.next().show();
}