<div class="col-xl-12 col-12">
    <div class="kt-portlet">
        <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Top Professions
                    </h3>
                </div>
            </div>
        <div class="kt-portlet__body">
            <div class="centered">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x nav-tabs-line-info" id="nav" role="tablist">
                    @foreach(_top_professions() as $key => $pro)
                    <li class="nav-item" onclick="renderTopCourse({{ $pro['id'] }})">
                        <a class="nav-link {{ $key==0 ? 'active' : ''}}" data-toggle="tab" role="tab">{{ $pro['profession'] }}</a>
                    </li>                                      
                    @endforeach
                </ul>
            </div>

            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--bordered">
                <div class="kt-portlet__body">
                    <div class="row" id="top-courses-row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 popover__wrapper">
                            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid kt-portlet--bordered popover__main">
                                <div class="kt-portlet__head kt-portlet__space-x">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title kt-font-light">
                                            What to Learn from this Course of Agriculture and more about Plant...
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget27">
                                        <div class="kt-widget27__visual">
                                            <img src="{{ asset('media/bg/bg-4.jpg') }}" class="card-img">
                                            <div class="popover__overlay"></div>
                                            <div class="kt-widget27__btn">
                                                <a class="btn btn-pill btn-warning btn-elevate btn-bold">Coming Soon</a>
                                            </div>
                                        </div>
                                        <div class="kt-widget27__container kt-portlet__space-x">
                                            <div class="row">
                                                <div class="col-12"><h5>Agricultural & Biosystems Engineering</h5></div>
                                                <div class="col-12">CPD UNITS 9.5</div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <span class="rating"></span>
                                            </div>
                                            <br/>
                                            <div class="row card-price">
                                                <span class="card-price--original">
                                                    ₱ 2,500.50
                                                </span>
                                                &nbsp; &nbsp;
                                                <span class="card-price--discounted">
                                                    ₱ 1,250.50
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet kt-portlet--height-fluid- popover__content--right">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label kt-label--adjust">
                                        <span>Get until Aug. 20, 2020</span>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <!--begin::Widget -->
                                    <div class="kt-widget kt-widget--user-profile-2">
                                        <div class="kt-widget__head">
                                            <div class="kt-widget__info">
                                                <a href="#" class="kt-widget__username">
                                                    Contrary to popular belief, Lorem Ipsum is not simply random text.
                                                </a>
                                                <span class="kt-widget__desc">
                                                    Agriculuture / Profession
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__body">
                                            <div class="kt-widget__section">
                                                Headline :: Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.
                                            </div>
                                            <div class="kt-widget__content">
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-presentation"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Lecture</span>
                                                        <span class="kt-widget__value">249,500</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-time"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Time</span>
                                                        <span class="kt-widget__value">1.5 H</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-medal"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">UNITS</span>
                                                        <span class="kt-widget__value">1.5A</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-widget__item">
                                                <div class="kt-widget__contact">
                                                    <span class="kt-widget__data">
                                                        <ul>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                        </ul>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-widget__footer">
                                            <button type="button" class="btn btn-success btn-lg btn-upper">Add to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 popover__wrapper">
                            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid kt-portlet--bordered popover__main">
                                <div class="kt-portlet__head kt-portlet__space-x">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title kt-font-light">
                                            What to Learn from this Course of Agriculture and more about Plant...
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget27">
                                        <div class="kt-widget27__visual">
                                            <img src="{{ asset('media/bg/bg-4.jpg') }}" class="card-img">
                                            <div class="popover__overlay"></div>
                                            <div class="kt-widget27__btn">
                                                <a class="btn btn-pill btn-warning btn-elevate btn-bold">Coming Soon</a>
                                            </div>
                                        </div>
                                        <div class="kt-widget27__container kt-portlet__space-x">
                                            <div class="row">
                                                <div class="col-12"><h5>Agricultural & Biosystems Engineering</h5></div>
                                                <div class="col-12">CPD UNITS 9.5</div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <span class="rating"></span>
                                            </div>
                                            <br/>
                                            <div class="row card-price">
                                                <span class="card-price--original">
                                                    ₱ 2,500.50
                                                </span>
                                                &nbsp; &nbsp;
                                                <span class="card-price--discounted">
                                                    ₱ 1,250.50
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet kt-portlet--height-fluid- popover__content--right">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <span>Get until Aug. 20, 2020</span>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <!--begin::Widget -->
                                    <div class="kt-widget kt-widget--user-profile-2">
                                        <div class="kt-widget__head">
                                            <div class="kt-widget__info">
                                                <a href="#" class="kt-widget__username">
                                                    Contrary to popular belief, Lorem Ipsum is not simply random text.
                                                </a>
                                                <span class="kt-widget__desc">
                                                    Agriculuture / Profession
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__body">
                                            <div class="kt-widget__section">
                                                Headline :: Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.
                                            </div>
                                            <div class="kt-widget__content">
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-presentation"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Lecture</span>
                                                        <span class="kt-widget__value">249,500</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-time"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Time</span>
                                                        <span class="kt-widget__value">1.5 H</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-medal"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">UNITS</span>
                                                        <span class="kt-widget__value">1.5A</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-widget__item">
                                                <div class="kt-widget__contact">
                                                    <span class="kt-widget__data">
                                                        <ul>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                        </ul>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-widget__footer">
                                            <button type="button" class="btn btn-success btn-lg btn-upper">Add to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 popover__wrapper">
                            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid kt-portlet--bordered popover__main">
                                <div class="kt-portlet__head kt-portlet__space-x">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title kt-font-light">
                                            What to Learn from this Course of Agriculture and more about Plant...
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget27">
                                        <div class="kt-widget27__visual">
                                            <img src="{{ asset('media/bg/bg-5.jpg') }}" class="card-img">
                                            <div class="popover__overlay"></div>
                                            <div class="kt-widget27__btn">
                                                <a class="btn btn-pill btn-dark btn-elevate btn-bold">Ended</a>
                                            </div>
                                        </div>
                                        <div class="kt-widget27__container kt-portlet__space-x">
                                            <div class="row">
                                                <div class="col-12"><h5>Agricultural & Biosystems Engineering</h5></div>
                                                <div class="col-12">CPD UNITS 9.5</div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <span class="rating"></span>
                                            </div>
                                            <br/>
                                            <div class="row card-price">
                                                <span class="card-price--original">
                                                    ₱ 2,500.50
                                                </span>
                                                &nbsp; &nbsp;
                                                <span class="card-price--discounted">
                                                    ₱ 1,250.50
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet kt-portlet--height-fluid- popover__content--left">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <span>Get until Aug. 20, 2020</span>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <!--begin::Widget -->
                                    <div class="kt-widget kt-widget--user-profile-2">
                                        <div class="kt-widget__head">
                                            <div class="kt-widget__info">
                                                <a href="#" class="kt-widget__username">
                                                    Contrary to popular belief, Lorem Ipsum is not simply random text.
                                                </a>
                                                <span class="kt-widget__desc">
                                                    Agriculuture / Profession
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__body">
                                            <div class="kt-widget__section">
                                                Headline :: Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.
                                            </div>
                                            <div class="kt-widget__content">
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-presentation"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Lecture</span>
                                                        <span class="kt-widget__value">249,500</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-time"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Time</span>
                                                        <span class="kt-widget__value">1.5 H</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-medal"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">UNITS</span>
                                                        <span class="kt-widget__value">1.5A</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-widget__item">
                                                <div class="kt-widget__contact">
                                                    <span class="kt-widget__data">
                                                        <ul>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                        </ul>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-widget__footer">
                                            <button type="button" class="btn btn-success btn-lg btn-upper">Add to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 popover__wrapper">
                            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid kt-portlet--bordered popover__main">
                                <div class="kt-portlet__head kt-portlet__space-x">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title kt-font-light">
                                            What to Learn from this Course of Agriculture and more about Plant...
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget27">
                                        <div class="kt-widget27__visual">
                                            <img src="{{ asset('media/bg/bg-1.jpg') }}" class="card-img">
                                            <div class="popover__overlay"></div>
                                            <div class="kt-widget27__btn">
                                                <a class="btn btn-pill btn-success btn-elevate btn-bold">Most Popular</a>
                                            </div>
                                        </div>
                                        <div class="kt-widget27__container kt-portlet__space-x">
                                            <div class="row">
                                                <div class="col-12"><h5>Agricultural & Biosystems Engineering</h5></div>
                                                <div class="col-12">CPD UNITS 9.5</div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <span class="rating"></span>
                                            </div>
                                            <br/>
                                            <div class="row card-price">
                                                <span class="card-price--original">
                                                    ₱ 2,500.50
                                                </span>
                                                &nbsp; &nbsp;
                                                <span class="card-price--discounted">
                                                    ₱ 1,250.50
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet kt-portlet--height-fluid- popover__content--left">
                                <div class="kt-portlet__head kt-portlet__head--noborder">
                                    <div class="kt-portlet__head-label">
                                        <span>Get until Aug. 20, 2020</span>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <!--begin::Widget -->
                                    <div class="kt-widget kt-widget--user-profile-2">
                                        <div class="kt-widget__head">
                                            <div class="kt-widget__info">
                                                <a href="#" class="kt-widget__username">
                                                    Contrary to popular belief, Lorem Ipsum is not simply random text.
                                                </a>
                                                <span class="kt-widget__desc">
                                                    Agriculuture / Profession
                                                </span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__body">
                                            <div class="kt-widget__section">
                                                Headline :: Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical.
                                            </div>
                                            <div class="kt-widget__content">
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-presentation"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Lecture</span>
                                                        <span class="kt-widget__value">249,500</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-time"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">Time</span>
                                                        <span class="kt-widget__value">1.5 H</span>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__stats">
                                                    <div class="kt-widget__icon minimize">
                                                        <i class="flaticon-medal"></i>
                                                    </div>
                                                    <div class="kt-widget__details">
                                                        <span class="kt-widget__title">UNITS</span>
                                                        <span class="kt-widget__value">1.5A</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-widget__item">
                                                <div class="kt-widget__contact">
                                                    <span class="kt-widget__data">
                                                        <ul>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                            <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
                                                        </ul>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-widget__footer">
                                            <button type="button" class="btn btn-success btn-lg btn-upper">Add to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
</div>

<div class="col-xl-12 col-12">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Top Courses
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="centered">
                <h3>No details yet.</h3>
            </div>
        </div>
    </div>
</div>