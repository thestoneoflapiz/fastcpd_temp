var counter = 0;
var courseId = 0;
var type = "";
jQuery(document).ready(function () {
    $("#course").slideUp();
    $("#webinar").slideUp();
 
    $("#review-select-type").on("change",function(){
        $("#review-select-course").removeAttr("selected");
        if($("#review-select-type").val() == "webinar")
        {
            $("#webinar").slideDown();
            $("#course").slideUp();
        }
        if($("#review-select-type").val() == "video-on-demand")
        {
            $("#webinar").slideUp();
            $("#course").slideDown();
        }
       
    });
   
   
   if($("#review-select-course").val() == ""){
       $("#review_list").slideUp();
       $("#see_more").slideUp();
   }else{
    $("#review_list").slideDown();
    $("#see_more").slideDown();
   }
    $(`#review-select-course`).select2({
        placeholder: "Please select a course"
    });
    $(`#review-select-type`).select2({
        placeholder: "Please select type"
    });
    $(`#review-select-webinar`).select2({
        placeholder: "Please select course"
    });
   
    // $('.rating').starRating({
    //     totalStars: 5,
    //     initialRating: 0.0,
    //     readOnly: true,
    //     starShape: "rounded",
    //     starSize: 15,
    //     forceRoundUp: true,
    //     ratedColor: 'gold',
    //     ratedColors:['#333333', '#555555', '#888888', '#AAAAAA', '#CCCCCC'],
    // });

    $("#review-select-course").on("change",function(){
        $("#review_list").slideDown();
        $("#see_more").slideDown();
        $("#radio_list").slideDown();
        $("#course_details").slideDown();
        $("#rating_details").slideDown();
        var counter = 5;
        //`<? $counter ? $counter : 5 ?>`
        courseId = this.value;
        type = "course"
        // alert(courseId);
       
        fetchReviews(counter, courseId, filter,type);
        ChartRatingReviews.init();
    });
    $("#review-select-webinar").on("change",function(){
        $("#review_list").slideDown();
        $("#see_more").slideDown();
        $("#radio_list").slideDown();
        $("#course_details").slideDown();
        $("#rating_details").slideDown();
        var counter = 5;
        //`<? $counter ? $counter : 5 ?>`
        courseId = this.value;
        type = "webinar"
        // alert(courseId);
       
        fetchReviews(counter, courseId, filter,type);
        ChartRatingReviews.init();
    });
    $("#see_more").click(function(){
        counter = Number(counter) + 5;
        fetchReviews(counter, courseId, filter,type);
    });
    $("input[type=radio]").click(function(){
        thisRadio = $(this);
        var values = $(this).val();
        if (thisRadio.hasClass("imChecked")) {
            thisRadio.removeClass("imChecked");
            thisRadio.prop('checked', false);
            values = "";
        } else { 
            thisRadio.prop('checked', true);
            thisRadio.addClass("imChecked");
        };
        fetchReviews(counter, courseId, values,type);
        filter = values;
        ChartRatingReviews.init();
       
    });
    
   
});
function fetchReviews(counter, courseId, filter){
    $("#review_list").slideUp(250);
    $("#loading").slideDown(500);

    $.ajax({
        url: "/provider/review/api/showreviews",
        data: {
            course_id: courseId,
            counter: counter,
            filter: filter,
            type: type,
        },
        success:function(response){
            var courses = response.data;
            // if(response.total_ratings == 1.5){
            //     $('#star_rates').starRating("setRating", response.total_ratings,false);
            // }else if(response.total_ratings == 2.5){
            //     $('#star_rates').starRating("setRating", response.total_ratings,false);
            // }else if(response.total_ratings == 3.5){
            //     $('#star_rates').starRating("setRating", response.total_ratings,false);
            // }else if(response.total_ratings == 4.5){
            //     $('#star_rates').starRating("setRating", response.total_ratings,false);
            // }else if(response.total_ratings == 0.5){
            //     $('#star_rates').starRating("setRating", response.total_ratings,false);
            // }else{
            //     $('#star_rates').starRating("setRating", response.total_ratings,true);
            // }

           
           // $('.rating').starRating("forceRoundUp",true);
            // $('.rating').starRating({
            //     totalStars: 5,
            //     initialRating: response.total_ratings,
            //     readOnly: true,
            //     starShape: "rounded",
            //     starSize: 20,
            // });
            //$('#star_rating').html(response.total_ratings);
 
            // renderPagination(response, pagination);
            // renderCourseRow(response);
            $("#valuable_info").attr("style","width:"+response.performance_course.valuable_info+"%;"); 
            $("#concepts").attr("style","width:"+response.performance_course.concepts+"%;");
            $("#instructor_delivery").attr("style","width:"+response.performance_course.instructor_delivery+"%;");
            $("#opportunities").attr("style","width:"+response.performance_course.opportunities+"%;");
            $("#expectations").attr("style","width:"+response.performance_course.expectations+"%;");
            $("#knowledgeable").attr("style","width:"+response.performance_course.knowledgeable+"%;");

            $("#valuable_info_percentage").html(Math.round(response.performance_course.valuable_info)+"%"); 
            $("#concepts_percentage").html(Math.round(response.performance_course.concepts)+"%");
            $("#instructor_delivery_percentage").html(Math.round(response.performance_course.instructor_delivery)+"%");
            $("#opportunities_percentage").html(Math.round(response.performance_course.opportunities)+"%");
            $("#expectations_percentage").html(Math.round(response.performance_course.expectations)+"%");
            $("#knowledgeable_percentage").html(Math.round(response.performance_course.knowledgeable)+"%");

            renderReviewRow(response);
            setTimeout(() => {
                $("#review_list").slideDown(750);
                $("#loading").slideUp(500);
            }, 1000);
        },
        error:function(){

        },
    });
}
"use strict";

// Class definition
var ChartRatingReviews = function () {
    var ratingsChart = function () {
        if (!KTUtil.getByID('ratingsChart')) {
            return;
        }
        $.ajax({
            url: "/provider/review/api/showreviews",
            data: {
                course_id: courseId,
                counter: counter,
                filter: filter,
                type: type,
            },
            success:function(response){
                var bgColorPicker = [];
                var courses = response.data;
                if(response.total_ratings <= 2){
                    bgColorPicker.push(KTApp.getStateColor('danger'))
                }else if(response.total_ratings >= 2.5 && response.total_ratings <= 3.5){
                    bgColorPicker.push(KTApp.getStateColor('warning'))
                }else{
                    bgColorPicker.push(KTApp.getStateColor('success'))
                }
                var letter = `${response.total_ratings} <br/><span style="font-size: 10px;font-weight:normal;">stars</span>`;
                $("#rating_count").html(letter);
            
            var config = {
                type: 'doughnut',
                data: {
                datasets: [{
                    data: [100],
                    backgroundColor: bgColorPicker
                }],
                labels: response.courses
                },
                options: {
                cutoutPercentage: 75,
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
                    enabled: false,
                },
            
                }
            };

            var ctx = KTUtil.getByID('ratingsChart').getContext('2d');
            var myDoughnut = new Chart(ctx, config);		
        },
    });
        
    }

    return {
        // Init demos
        init: function () {
            // init charts
            ratingsChart();
        }
    };
}();

