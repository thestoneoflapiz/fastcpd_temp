var ajax_gc = null;
var join_referer = null;

jQuery(document).ready(function() {
    KTInputmask.init();

    $("#generate_code").click(function(){
        $(this).addClass(`kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light`).prop("disabled", true);

        ajax_gc = $.ajax({
            method: "GET",
            url: "/profile/referral/generate/code",
            beforeSend: function () {
                if(ajax_gc!=null){   
                    ajax_gc.abort();
                }
            },
            success: function (response) {
                toastr.info("Please wait, we're reloading your browser...");
                setTimeout(() => {
                    window.location="/profile/referral";
                }, 1000);
            },
            error: function(response){
                var body = response.responseJSON;
                if(body && body.hasOwnProperty("message")){
                    toastr.error(body.message);
                    return;
                }

                toastr.error("Something went wrong! Please try again later.");
            }
        });
    });
});

function copy_() {
    var copyText = $("#copy_referral_code");
    copyText.select();
    document.execCommand("copy", false);

    toastr.info("Copied!");
}


var KTInputmask = function () {
    
    // Private functions
    var input_masks = function () {
        // referral code input mask
        $("[name='referral_code']").inputmask("([A]|[9])([A]|[9])([A]|[9])([A]|[9])([A]|[9])([A]|[9])([A]|[9])([A]|[9])");        
    }   

    var input_validations = function () {
        validator = $( "#referral_code_form" ).validate({
            // define validation rules
            rules: {
                referral_code: {
                    required: true,
                    maxlength: 8,
                    minlength: 8,
                },
            },
            
            //display error alert on form submit  
            invalidHandler: function(event, validator) {  
                KTUtil.scrollTop();
            },

            submitHandler: function (form) {
                $("#referral_code_submit").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", true);
                var join_referer = $.ajax({
                    url: '/profile/referral/join',
                    beforeSend: function () {
                        if(join_referer!=null){   
                            join_referer.abort();
                        }
                    },
                    data: {
                        _rc: $('[name="referral_code"]').val(),
                    },
                    success: function(response){
                        toastr.success("Successfully joined a referer!");
                        toastr.info("Please wait, we're reloading your browser...");
                        setTimeout(() => {
                            window.location="/profile/referral";
                        }, 1000);
                    },
                    error: function(response){
                        $("#referral_code_submit").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light').prop("disabled", false);
                        var body = response.responseJSON;
                        if(body && body.hasOwnProperty("message")){
                            toastr.error(body.message);
                            return;
                        }

                        toastr.error("Something went wrong! Please try again later.");
                    }
                });
            }
        });       
    }
    
    return {
        // public functions
        init: function() {
            input_masks(); 
            input_validations();
        }
    };
}();