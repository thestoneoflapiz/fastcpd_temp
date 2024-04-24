jQuery(document).ready(function(){
    /**
     * Facebook Login Integration : BEGIN!
     * 
     */
    checkFBLoginState();

    $("button.login_facebook").click(function(){
        $("button.login_facebook").addClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", true);
        FB.login(function(response) {
            FBstatusChangeCallback(response);
        }, {scope: 'public_profile,email'});
    });
});

function GOOGLEinit() {
    gapi.load('auth2', function() { 
        /* Ready. Make a call to gapi.auth2.init or some other API */
        gapi.auth2.init({ client_id: "141741110601-c1gshj30hv0ignh0bhoq77laimfvd84m" }).then((response) => {
            // DO NOT ATTEMPT TO RENDER BUTTON UNTIL THE 'Init' PROMISE RETURNS
            renderGoogleBTN();
        });
    });
}

function renderGoogleBTN(){
    gapi.signin2.render('login_google', {
        'scope': 'profile email',
        'width': 240,
        'height': 37,
        'longtitle': true,
        'theme': 'light',
        'onsuccess': (response) => {
            GOOGLELoginState(response);
        },
        'onfailure': () => {
            toastr.error("Unable to login with Google! Please try again later");
        }
    });

    gapi.signin2.render('signup_google', {
        'scope': 'profile email',
        'width': 240,
        'height': 37,
        'longtitle': true,
        'theme': 'light',
        'onsuccess': (response) => {
            GOOGLELoginState(response);
        },
        'onfailure': () => {
            toastr.error("Unable to login with Google! Please try again later");
        }
    });
}

function GOOGLELoginState(response){
    var profile = response.getBasicProfile();
    var sendData = {
        id: profile.getId(),
        name: profile.getName(),
        first_name: profile.getGivenName(),
        last_name: profile.getFamilyName(),
        image: profile.getImageUrl(),
        email: profile.getEmail(),
    };

    checkFASTCPDauth(sendData, "google");
}

function checkFBLoginState(){
    // check is use is already logged in facebook
    FB.getLoginStatus(function(response) {
        FBstatusChangeCallback(response);
    });
}

function FBstatusChangeCallback(response){
    if(response.status === "connected"){
        FB.api(`/me?fields=id,email,name`, function(getDetails) {
            if(!getDetails.hasOwnProperty("email")){
                toastr.error("Sorry you don't have </b>confirmed email registered in your Facebook account</b>. Please settle your Facebook account before using it for login. Thank you!");
                FB.logout();

                return;
            }

            checkFASTCPDauth({details: getDetails, ...response}, "facebook");
            return;
        });
    }else if(response.status === "not_authorized"){
    }else if(response.status === "unknown"){
    }else{ }

    $("button.login_facebook").removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", false);
}
/**
 * Facebook Login Integration : END!
 * 
 */


function checkFASTCPDauth(data, social){
    $.ajax({
        url: "/auth/social/login",
        data: {
            social: social,
            response: data,
        }, 
        success: function(response){
            if(response.hasOwnProperty("redirect")){
                window.location=response.redirect;
                return;
            }
        }, error: function(response) {
            switch (social) {
                case "facebook":
                    FB.logout();
                    break;

                case "google":
                    GOOGLESignOut();
                    break;
            
                default:
                    break;
            }

            var body = response.responseJSON;
            if(body.hasOwnProperty("message")){
                toastr.error(body.message);
                return;
            }

            $("button.login_facebook").removeClass("kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light").attr("disabled", false);
            toastr.error("Sorry! Unable to login... please try again later!");
        }
    });
}

function GOOGLESignOut(){
    var auth2 = gapi.auth2.getAuthInstance();
    return auth2.signOut();
}