<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop public-footer" id="kt_footer">
    <div class="kt-container  kt-container--fluid">
        <div class="kt-footer__menu">
            <div class="row">
                <div class="col-12">
                    <h4 style="margin:0 0 0 1.25rem;" class="kt-margin-b-30">Resources</h4>
                    <a href="/site/about-fastcpd" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">About FastCPD</a><br/>
                    <a href="/site/how-to-earn-cpd-units" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">How to Earn CPD Units</a><br/>
                    <a href="/site/become-a-fastcpd-provider" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">Become a FastCPD Provider</a><br/>
                    <a href="/site/become-an-instructor" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">Become an Instructor</a><br/>
                    <a href="/site/help-center" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">Help Center</a><br/>
                    <a href="/site/blog" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">Blog</a><br/>
                </div>
            </div>
        </div>
        <div class="kt-footer__menu">
            <div class="row">
                <div class="col-12">
                    <h4 style="margin:0 0 0 1.25rem;" class="kt-margin-b-30">Courses by Profession</h4>
                    <a href="#" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">All Professions</a><br/>
                    @foreach(_professions() as $key => $top_pr)
                    @if($key < 6)
                    <a href="/courses/{{$top_pr['url']}}" style="color:white !important;" class="kt-footer__menu-link kt-link kt-margin-b-15">{{$top_pr["profession"]}}</a><br/>
                    @else
                    @break
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="kt-footer__menu">
            <div class="row">
                <div class="col-12">
                    <h4 style="margin:0 0 0 1.25rem;" class="kt-margin-b-30">Contact Us</h4>
                    <div class="row kt-margin-b-20">
                        <div class="col-2">
                            <i class="fa fa-phone socicons-footer"></i>
                        </div>
                        <div class="col-10">
                            <b>Phone:</b></br>+63 2 8332 6977
                        </div>
                    </div>
                    <div class="row kt-margin-b-20">
                        <div class="col-2">
                            <i class="fa fa-mobile-alt socicons-footer"></i>
                        </div>
                        <div class="col-10">
                            <b>Mobile:</b></br>+63 917 817 7388
                        </div>
                    </div>
                    <div class="row kt-margin-b-20">
                        <div class="col-2">
                            <i class="fa fa-at socicons-footer"></i>
                        </div>
                        <div class="col-10">
                            <b>Email:</b></br>info@fastcpd.com
                        </div>
                    </div>
                    <div class="row kt-margin-b-20">
                        <div class="col-2">
                            <i class="fa fa-map-marker-alt socicons-footer"></i> 
                        </div>
                        <div class="col-10">
                            <b>Office:</b></br>30 Cabatuan Street, Quezon City, PH
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop public-footer" id="kt_footer">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-footer__menu" style="text-align:right;">
            <a href="/verify" style="color:white !important;" class="kt-footer__menu-link kt-link kt-font-bold">Verify Certificate</a>
            <a href="#" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link kt-font-bold">Browse CPD Courses</a>
            <a href="/legal/terms-of-service" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link kt-font-bold">Terms of Service</a>
            <a href="/legal/privacy-policy" target="_blank" style="color:white !important;" class="kt-footer__menu-link kt-link kt-font-bold">Privacy Policy</a>
        </div>
        <div class="kt-footer__menu">
            <!-- <a href="https://www.facebook.com/fastcpd" target="_blank" class="facebook-circle-footer kt-footer__menu-link kt-link"><i class="socicons-footer fab fa-facebook"></i></a>
            <a href="javascript:toastr.info('Soon!');" target="_blank" class="twitter-circle-footer kt-footer__menu-link kt-link"><i class="socicons-footer fab fa-twitter"></i></a>
            <a href="javascript:toastr.info('Soon!');" target="_blank" class="instagram-circle-footer kt-footer__menu-link kt-link"><i class="socicons-footer fab fa-instagram"></i></a>
            <a href="javascript:toastr.info('Soon!');" target="_blank" class="linkedin-circle-footer kt-footer__menu-link kt-link"><i class="socicons-footer fab fa-linkedin"></i></a> -->
        </div>
    </div>
</div>

<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-footer__copyright" style="margin:auto !important;">
            <a href="javascript:;" class="kt-link" style="color:white !important;">Copyright &copy; 2020 FastCPD.com</a>
        </div>
    </div>
</div>