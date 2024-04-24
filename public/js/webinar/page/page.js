var buy_now = false;
var webinar_id_input = $("[name='webinar_id']");
var checkSlots_ajax = null;
var currentRequest = null;    
var string_months = ["", "January", "February", "March", "April", "May", 
    "June", "July", "August", "September", "October", 
    "November", "December"];
var string_days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
jQuery(document).ready(function(){
    $(`[name="apply_voucher_cart"]`).keyup(function(e){
        var value = e.target.value;
        if(value){
            $(this).val(value.toUpperCase());
        }
    });
    FormDesign.init();

    $("#add-to-cart-btn-modal").click(function(){
        $( "#add-to-cart-form" ).submit();
    });

    $(".buy-now-btn").click(function(){
        buy_now = true;
        $( "#add-to-cart-form" ).submit();
    });
});

jQuery(window).scroll(function () {
    $('#preview-video').bind('contextmenu',function() { return false; });
    var height = $(".hero-banner").innerHeight();
    if ($(this).scrollTop() > height) {
        //if scroll reached end of banner, sticky flag displays
        $(".black-banner").removeClass("kt-hidden");
        if ($(window).width() <= 1020) {
            $(".cta").addClass("kt-hidden");
        } else {
            $(".cta").removeClass("kt-hidden");
        }
    } else if ($(this).scrollTop() < height) {
        $(".black-banner").addClass("kt-hidden");
        $(".cta").addClass("kt-hidden");
    }
});

var FormDesign = function () {
   // Private functions
    var validator;  
    var input_validations = function () {
        validator = $( "#add-to-cart-form" ).validate({
            rules: {
                schedule: {
                    required: true,
                },
                offering_price: {
                    required: true,
                },
            },
            invalidHandler: function(event, validator) {
                KTUtil.scrollTop();
            },
            submitHandler: function(form) {
                var $atc_btn = $("#add-to-cart-form-submit");
                var $atc_btn_modal = $("#add-to-cart-btn-modal");
                $atc_btn.html(`Adding to Cart...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);
                $atc_btn_modal.html(`Adding to Cart...`).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled').prop("disabled", true);

                $.ajax({
                    url: "/cart/add",
                    data: {
                        type: "webinar",
                        data_id: $("[name='webinar_id']").val(),
                        offering_type: $("[name='offering_price']").val(),
                        schedule_type: $("[name='webinar_event']").val(),
                        schedule_id: $("[name='schedule']").val(),
                        discount: $("[name='discount']").val(),
                    },
                    success: function(response){
                        var data = response.data;
                        toastr.success(`Added ${data.title} <button class='btn btn-sm btn-light' onclick='window.location="/cart"'>Go to Cart</button>`);

                        if(buy_now){
                            buy_now = false;
                            setTimeout(() => {
                                window.location="/cart";
                            }, 1000);                            
                        }

                        $atc_btn.html("Go to Cart").attr("type", "button").attr("onclick", "window.location='/cart'").removeClass("btn-success").removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").addClass("btn-danger").prop("disabled", false);
                        $atc_btn.next().hide();

                        $atc_btn_modal.html("Go to Cart").attr("type", "button").attr("onclick", "window.location='/cart'").removeClass("btn-success").removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light disabled").addClass("btn-danger").prop("disabled", false);
                        $atc_btn_modal.next().hide();

                        $(".apply-voucher-group").hide();
                    },
                    error: function(response){
                        var body = response.responseJSON;
                        toastr.error(body.hasOwnProperty("message") ? body.message : "Sorry! We're unable to add to cart this webinar!");
            
                        if(body.hasOwnProperty("button")){
                            if(body.button.status=="success"){
                                $atc_btn.html(body.button.label).removeClass().addClass(`btn btn-${body.button.status} btn-lg kt-margin-r-5`).prop("disabled", false);
                                $atc_btn_modal.html(body.button.label).removeClass().addClass(`btn btn-${body.button.status} btn-lg kt-margin-b-5`).prop("disabled", false);
                                return;
                            }

                            $atc_btn.html(body.button.label).attr("type", "button").attr("onclick", `window.location='${body.button.url}'`).removeClass().addClass(`btn btn-${body.button.status} btn-lg kt-margin-r-5`).prop("disabled", false).next().hide();
                            $atc_btn_modal.html(body.button.label).attr("type", "button").attr("onclick", `window.location='${body.button.url}'`).removeClass().addClass(`btn btn-${body.button.status} btn-lg kt-margin-b-5`).prop("disabled", false).next().hide();
                        }
                    } 
                });
            }
        });     
    }

    return {
        // public functions
        init: function() {
            input_validations(); 
        }
    };
}();

$("#preview-modal").on("hidden.bs.modal", function () {
    var exist = $(`#preview-video`);
    if (exist.length != 0) {
        exist.trigger("pause");
    }
});

//Expand All or Collapse All
$(".expand-collapse").click(function () {
    if ($(".expand-collapse").html() == "Expand All") {
        $(".expand-collapse").html("Collapse All");
        $(".card-title").addClass("collapsed");
        $(".collapse").addClass("show");
    } else {
        $(".expand-collapse").html("Expand All");
        $(".card-title").removeClass("collapsed");
        $(".collapse").removeClass("show");
    }
});

//Remove maximum height assigned
$(".see-requirements").click(function () {
    $(".footer-requirements").hide();
    $("#section-requirements").removeClass("limit-requirements");
});

$(".see-description").click(function () {
    $(".footer-description").hide();
    $("#section-description").removeClass("limit-description");
});

$(".see-target").click(function () {
    $(".footer-target").hide();
    $("#section-target").removeClass("limit-target");
});

$(".see-sections").click(function () {
    $(".footer").hide();
    $("#section-body").removeClass("limit-size");
});

$(".see-reviews").click(function () {
    $(".footer-reviews").hide();
    $("#section-reviews").removeClass("limit-reviews");
});

$(".see-objectives").click(function () {
    $(".footer-objectives").hide();
    $("#section-objectives").removeClass("limit-objectives");
});

//Check if data reached the maximum height or not
//Requirement
if ($("#section-requirements").height() < 200) {
    $(".footer-requirements").hide();
    $("#section-requirements").removeClass("limit-requirements");
} else {
    $(".footer-requirements").show();
    $("#section-requirements").addClass("limit-requirements");
}

//Description
if ($("#section-description").height() < 500) {
    $(".footer-description").hide();
    $("#section-description").removeClass("limit-description");
} else {
    $(".footer-description").show();
    $("#section-description").addClass("limit-description");
}

//Target student
if ($("#section-target").height() < 200) {
    $(".footer-target").hide();
    $("#section-target").removeClass("limit-target");
} else {
    $(".footer-target").show();
    $("#section-target").addClass("limit-target");
}

//Section
if ($("#section-body").height() < 510) {
    $(".footer").hide();
    $("#section-body").removeClass("limit-size");
} else {
    $(".footer").show();
    $("#section-body").addClass("limit-size");
}

//Reviews
if ($("#section-reviews").height() < 530) {
    $(".footer-reviews").hide();
    $("#section-reviews").removeClass("limit-reviews");
} else {
    $(".footer-reviews").show();
    $("#section-reviews").addClass("limit-reviews");
}

//Objectives
if ($("#section-objectives").height() < 300) {
    $(".footer-objectives").hide();
    $("#section-objectives").removeClass("limit-objectives");
} else {
    $(".footer-objectives").show();
    $("#section-objectives").addClass("limit-objectives");
}

function checkSlots(data){
    checkSlots_ajax = $.ajax({
        url: "/api/webinar/check_slots",
        data: data,
        beforeSend : function()    {           
            if(checkSlots_ajax != null) {
                checkSlots_ajax.abort();
            }
        },
        success: function (response) {
            if(response.remaining>0){
                $(".remaining-slot").html(`remaining slots available: &nbsp; <b>${response.remaining}</b>`);
            }

            if(!response.slots){
                toastr.info("No more slots available for this schedule!");
                $(".remaining-slot").html("");

                $("#add-to-cart-form-submit").html("Out Of Slots").attr("type", "button").removeAttr("onclick").attr("id", "ofs-form-submit").removeClass().addClass("btn btn-warning btn-lg kt-margin-r-5").next().hide();
                $("#add-to-cart-btn-modal").html("Out Of Slots").attr("type", "button").removeAttr("onclick").attr("id", "ofs-btn-modal").removeClass().addClass("btn btn-warning btn-lg kt-margin-b-10").next().hide();
            }else{
                $("#ofs-form-submit").html("Add to Cart").attr("type", "submit").attr("id", "add-to-cart-form-submit").removeClass().addClass("btn btn-success btn-lg kt-margin-r-5").next().show();
                $("#ofs-btn-modal").html("Add to Cart").attr("type", "submit").attr("id", "add-to-cart-btn-modal").removeClass().addClass("btn btn-success btn-lg kt-margin-b-10").next().show();
            }
        },
        error: function (response){
            var body = response.responseJSON;
            if(body && body.hasOwnProperty("message")){
                toastr.error(body.message);
                return;
            }
        }
    });
}

/**
 * 
 * Apply voucher
 * 
 */
$("[name='apply_voucher_code1'], [name='apply_voucher_code2']").keyup(function(e){
    var value = e.target.value;
    $(this).val(value.toUpperCase());
}).keypress(function (e) {
    if (e.which == 13) {
        var value = e.target.value;
        addVoucher(webinar_id_input.val(), value, 1);
    }
});

function addVoucher($webinar_id, $voucher_code){
    if($voucher_code==""){
        toastr.error("Please enter voucher code first!");
    }else{
        var $apply_voucher_button = $(`#apply-voucher-button1, #apply-voucher-button2`);
        $apply_voucher_button.empty().addClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", true);

        $.ajax({
            url: "/webinar/voucher/add",
            data:{
                voucher_code: $voucher_code,
                webinar_id: $webinar_id,
            }, success: function(response){
                $("[name='apply_voucher_code1'], [name='apply_voucher_code2']").attr("placeholder", $voucher_code).val(null);
                $("[name='discount']").val(`${response.discount}:${response.voucher_code}`);

                setTimeout(() => {
                    toastr.success("Voucher accepted!");
                    if(response.offering_units!="both"){
                        $("#webinar-total-div").empty().append(`
                            <input type="hidden" name="offering_price" value="${response.offering_units}" />
                            <div class="remaining-slot"></div>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${response.discounted_price}</span>&nbsp;&nbsp;
                            <span style="text-decoration: line-through;font-size:25px">&#8369;${response.price}</span>&nbsp;&nbsp;
                            <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                        `);
                        $("#webinar-total-div-modal").empty().append(`
                            <div class="remaining-slot"></div>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${response.discounted_price}</span>
                            <span style="text-decoration: line-through;font-size:25px">&#8369;${response.price}</span>
                            <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                        `);
                    }else{
                        var event = null;
                        if(event = $('[name="offering_price"]').val()){
                            $("#webinar-total-div").empty().append(`
                                <input type="hidden" name="offering_price"/>
                                <div class="remaining-slot"></div>
                                <span style="font-weight:bold;font-size:32px;">&#8369;`+(event=="with"? response.discounted_price_with : response.discounted_price_without)+`</span>&nbsp;&nbsp;
                                <span style="text-decoration: line-through;font-size:25px">&#8369;`+(event=="with"? response.prices.with: response.prices.without)+`</span>&nbsp;&nbsp;
                                <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                            `);
                            $("#webinar-total-div-modal").empty().append(`
                                <div class="remaining-slot"></div>
                                <span style="font-weight:bold;font-size:32px;">&#8369;`+(event=="with"? response.discounted_price_with : response.discounted_price_without)+`</span>
                                <span style="text-decoration: line-through;font-size:25px">&#8369;`+(event=="with"? response.prices.with: response.prices.without)+`</span>
                                <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                            `);
                        }else{
                            $("#webinar-total-div").empty().append(`
                                <input type="hidden" name="offering_price"/>
                                <div class="remaining-slot"></div>
                                <span style="font-weight:bold;font-size:32px;">`+(response.discounted_price_with > response.discounted_price_without ? (`${response.discounted_price_without > 0 ? `&#8369;`+response.discounted_price_without : `FREE`} — &#8369;${response.discounted_price_with}  `) 
                                : (`${response.discounted_price_without > 0 ? `&#8369;`+response.discounted_price_without : `FREE`}`) + ` — &#8369;${response.discounted_price_with}`)+` </span><br/>
                                <span style="text-decoration: line-through;font-size:25px">`+
                                    (parseFloat(response.prices.with) < parseFloat(response.prices.without) ? 
                                    (`&#8369;${response.prices.with} — ` + (response.prices.without > 0 ? `&#8369;${response.prices.without}` : `FREE`)) : 
                                    (response.prices.without > 0 ? `&#8369;${response.prices.without}`: `FREE`) + ` — &#8369;${response.prices.with}`)+`
                                </span><br/>
                                <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                            `);
                            $("#webinar-total-div-modal").empty().append(`
                                <div class="remaining-slot"></div>
                                <span style="font-weight:bold;font-size:30px;">`+(response.discounted_price_with > response.discounted_price_without ? (`${response.discounted_price_without > 0 ? `&#8369;`+response.discounted_price_without : `FREE`} — &#8369;${response.discounted_price_with} `) 
                                : (`${response.discounted_price_without > 0 ? `&#8369;`+response.discounted_price_without : `FREE`}`) + ` — &#8369;${response.discounted_price_with}`)+` </span>
                                <span style="text-decoration: line-through;font-size:25px">`+
                                    (parseFloat(response.prices.with) < parseFloat(response.prices.without) ? 
                                    (`&#8369;${response.prices.with} — ` + (response.prices.without > 0 ? `&#8369;${response.prices.without}` : `FREE`)) : 
                                    (response.prices.without > 0 ? `&#8369;${response.prices.without}` : `FREE`) + ` — &#8369;${response.prices.with}`)+`</span>
                                <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                            `);
                        }
                    }

                    $(".discount-label").html(`${response.discount}% off on purchase!`);
                    $(`#applied-voucher-list`).empty().append(`
                        <h5><i class="flaticon2-delete" onclick="removeVoucher(${$webinar_id}, '${response.voucher_code}')"></i>&nbsp;&nbsp; <b>${response.voucher_code}</b> is applied</h5> 
                    `);
                    $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
                }, 1000);
            }, error: function(response){
                var body = response.responseJSON;
                if(body && body.hasOwnProperty("message")){
                    toastr.error(body.message);
                }else{
                    toastr.error("Something went wrong! Please try again later");
                }
    
                setTimeout(() => {
                    $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
                }, 1000);
            }
        });
    }
}

function removeVoucher($webinar_id, $voucher_code){
    var $apply_voucher_button = $(`#apply-voucher-button1, #apply-voucher-button2`);
    $apply_voucher_button.empty().addClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", true);

    $.ajax({
        url: "/webinar/voucher/remove",
        data:{
            voucher_code: $voucher_code,
            webinar_id: $webinar_id,
        }, success: function(response){
            $("[name='apply_voucher_code1'], [name='apply_voucher_code2']").attr("placeholder", "Enter Voucher").val(null);

            setTimeout(() => {
                toastr.success("Voucher removed!");

                $(`#applied-voucher-list`).empty();
                if(response.discount){
                    $("[name='discount']").val(`${response.discount}:${response.voucher_code}`);
                    if(response.offering_units!="both"){
                        $("#webinar-total-div").empty().append(`
                            <input type="hidden" name="offering_price" value="${response.offering_units}" />
                            <div class="remaining-slot"></div>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${response.discounted_price}</span>&nbsp;&nbsp;
                            <span style="text-decoration: line-through;font-size:25px">&#8369;${response.price}</span>&nbsp;&nbsp;
                            <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                        `);
                        $("#webinar-total-div-modal").empty().append(`
                            <div class="remaining-slot"></div>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${response.discounted_price}</span>
                            <span style="text-decoration: line-through;font-size:25px">&#8369;${response.price}</span>
                            <span style="font-size:18px;color:#961f45;" class="kt-font-bold discount-label">${response.discount}% off on purchase!</span>
                        `);
                    }
                    $(".discount-label").html(`${response.discount}% off on purchase!`);
                }else{
                    $("[name='discount']").val(null);
                    if(response.offering_units!="both"){
                        $("#webinar-total-div").empty().append(`
                            <input type="hidden" name="offering_price" value="${response.offering_units}" />
                            <div class="remaining-slot"></div>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${response.price}</span>&nbsp;&nbsp;
                        `);
                        $("#webinar-total-div-modal").empty().append(`
                            <div class="remaining-slot"></div>
                            <span style="font-weight:bold;font-size:32px;">&#8369;${response.price}</span>
                        `);
                    }
                    $(".discount-label").html(``);
                }
                
                $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
            }, 1000);
        }, error: function(response){
            var body = response.responseJSON;
            if(body && body.hasOwnProperty("message")){
                toastr.error(body.message);
            }else{
                toastr.error("Something went wrong! Please try again later");
            }

            setTimeout(() => {
                $apply_voucher_button.html("Apply").removeClass(`btn-icon kt-spinner kt-spinner--center kt-spinner--sm kt-spinner--light disabled`).prop("disabled", false);
            }, 1000); 
        }
    });
}