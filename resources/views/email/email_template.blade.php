
    <!-- @extends('template.mail') -->
    @section('styles')
    <style>
        
    </style>
    @endsection
    @section('content')
    <div class="center" style="margin: 30px;">
        <img alt="FastCPD Company Logo" src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/images/Logos/logo-1.png" width="150">
    </div>
    
    <div class="header-box">
        <table width="100%;" class="header-bg">
            <tr>
                <td>
                    <h2 class="h2-style">
                        Your purchase is almost complete!
                    </h2>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-box">
        <div class="body-container">
            

            <br/>
            <div class="message">
            Hi  test bente uno <br/><br/>

            Please follow the instructions sent by our payment partner to complete your payment. Check your inbox or spam folder for the instruction email from DragonPay. <br/><br/>

            Purchases:<br/>
            <div class="center" style="margin:auto;">
                <table width="90%">
                    <tr >
                        <td class="center"><img src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/courses/raw/provider11/course17/course_poster_175ef434a016765.jpg" width="150"></td>
                        <td class="left">
                            <b>Improving personality of a Real Estate Agent</b> <br/>
                            Real Estate Broker - 2.0 CPD units<br/>
                        </td>
                        <td class="right">Php 20 <br/></td>
                    </tr>
                    <tr>
                        <td class="center"><img src="https://fastcpd.s3-ap-northeast-1.amazonaws.com/courses/raw/provider11/course17/course_poster_175ef434a016765.jpg" width="150"></td>
                        <td class="left">
                            <b>Introduction to Accounting </b> <br/>
                            Real Estate Broker - 2.0 CPD units<br/>
                            Account - 3.0 CPD units<br/>
                            Teachers - 4.0 CPD units<br/>
                        </td>
                        <td class="right">Php 20 <br/><del>Php 40</del></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="right"> TOTAL: 40</td>
                    </tr>
                </table><br/><br/>
            </div>
            
            You're almost done! We'll send you a confirmation email once we receive payment.<br/><br/>

            You can view your payment transactions in your account overview. Click the button to access your account<br/><br/>               
            
        </div>
        <div class="body-footer center ">
                <a href="{{ URL::to("/") }}" target="_blank" class="center_div button">GO TO PERFORMANCE</a>
        </div>
    </div>
    @endsection