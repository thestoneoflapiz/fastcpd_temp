$(document).ready(function(){
    getAnnouncement();

});


function getAnnouncement(){
    var pathArray = window.location.pathname.split('/');
    $.ajax({
        url: "/announcements/api/get_announcement",
        data: {
            page: pathArray[1],
        },
        success: function(response){
            if(response.length != 0){
                 // Set the date we're counting down to
                $("#kt_header_mobile").addClass("top50");
                $("#kt_header").addClass("top50");
                $("#kt_wrappers").addClass("kt-wrapper");
                $("#kt_content").addClass("margin-top50");
                // $(".kt-header__topbar").addClass
                $("#navbar").show('slow');

                $("#announcement_id").val(response.id);
                

                var countDownDate = new Date(response.end_date).getTime();
                document.getElementById("message").innerHTML = response.message;
                // Update the count down every 1 second
                $("#navbar").addClass("announcement-"+response.banner_state);
                $("#table_color").addClass(response.color);
                $("#close_announcement").addClass(response.color);
                if(response.end_date){
                    var x = setInterval(function() {

                        // Get today's date and time
                        var now = new Date().getTime();
                            
                        // Find the distance between now and the count down date
                        var distance = countDownDate - now;
                            
                        // Time calculations for days, hours, minutes and seconds
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                        // Output the result in an element with id="demo"
                        document.getElementById("time_remaining").innerHTML = days + "d " + hours + "h "
                        + minutes + "m " + seconds + "s ";
                            
                        // If the count down is over, write some text 
                        if (distance < 0) {
                            clearInterval(x);
                            document.getElementById("time_remaining").innerHTML = "EXPIRED";
                        }
                    }, 1000);
                }else{
                    document.getElementById("time_remaining").innerHTML = " ";
                    $("#time_remaining").addClass("empty-space");
                }
                
            }else{
                $("#kt_wrappers").removeClass("kt-wrapper");
            }
            
        },
        error: function(response){
            // var body = response.responseJSON;
            // toastr.error(body.hasOwnProperty("message") ? body.message : "Sorry! no announcements");
            // $("#kt_header_mobile").removeClass("top50");
            // $("#kt_header").removeClass("top50");
            // $("#kt_wrappers").removeClass("kt-wrapper");
            // $("#navbar").hide('slow');
        } 
    });
}
function closeAnnouncement(){

    $("#kt_header_mobile").removeClass("top50");
    $("#kt_header").removeClass("top50");
    $("#kt_wrappers").removeClass("kt-wrapper");
    $("#kt_content").removeClass("margin-top50");
    $("#navbar").hide('slow');
    var id = $("#announcement_id").val();


    $.ajax({
        url: "/announcements/api/closed_announcements",
        data: {
            id: id,
        },
        success: function(response){
        },
        error: function(response){
        } 
    });
    
}


