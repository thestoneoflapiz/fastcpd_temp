jQuery(document).ready(function () {
    FormDesign.init();

    $('#permissions').select2({
        placeholder: "Please choose a permission"
    });
});

var FormDesign = function () {
    var input_masks = function () {
        //email address
        $("#email").inputmask({
            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}].com",
            greedy: false,
            onBeforePaste: function (pastedValue, opts) {
                pastedValue = pastedValue.toLowerCase();
                return pastedValue.replace("mailto:", "");
            },
            definitions: {
                '*': {
                    validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
                    cardinality: 1,
                    casing: "lower"
                }
            }
        });
    }

    var input_validations = function () {
        validator = $("#form").validate({
            // define validation rules
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                permissions: {
                    required: true,
                },
            },

            //display error alert on form submit  
            invalidHandler: function (event, validator) {
                var alert = $('#form_msg');
                alert.removeClass('kt-hidden').show();
                KTUtil.scrollTop();
            },

            submitHandler: function (form) {
                $("#submit_form").addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                submit();
            }
        });
    }

    return {
        // public functions
        init: function () {
            input_masks();
            input_validations();
        }
    };
}();

function submit() {

    $.ajax({
        method: "POST",
        url: "/superadmin/users/add/action",
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            name: $('#name').val(),
            email: $('#email').val(),
            permissions: $('#permissions').val(),
        },
        success: function (response) {
            $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
            toastr.success('Success!', 'Registration for Provider is sent! Please wait for the approval for posting publicly. Thank you!');
            setTimeout(() => {
                window.location = "/superadmin/users";
            }, 1000);
        },
        error: function (response) {
            var body = response.responseJSON;
            $("#submit_form").removeClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');

            if(body && body.hasOwnProperty("message")){
                toastr.error(body.message);
                if(body.hasOwnProperty("errors") && body.errors.hasOwnProperty("email")){
                    toastr.error(body.errors.email[0]);
                }
                
                return;
            }
            toastr.error('Error!', 'Something went wrong! Please contact our support team to help you.');
        }
    });
}

