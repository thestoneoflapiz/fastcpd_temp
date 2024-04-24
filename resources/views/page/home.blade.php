@extends('template.master')

@section('title', 'Dashboard')

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-lg-3">
            <div class="kt-portlet kt-iconbox kt-iconbox--brand kt-iconbox--animate-slower">
                <div class="kt-portlet__body">
                    <div class="kt-iconbox__body">
                        <div class="kt-iconbox__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                    <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                </g>
                            </svg> </div>
                        <div class="kt-iconbox__desc">
                            <h3 class="kt-iconbox__title">
                                <a class="kt-link" href="javascript:;">Getting Started</a>
                            </h3>
                            <div class="kt-iconbox__content">
                                Base FAQ Questions
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate-slow">
                <div class="kt-portlet__body">
                    <div class="kt-iconbox__body">
                        <div class="kt-iconbox__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"></path>
                                    <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000"></path>
                                    <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"></path>
                                </g>
                            </svg> </div>
                        <div class="kt-iconbox__desc">
                            <h3 class="kt-iconbox__title">
                                <a class="kt-link" href="javascript:;">Tutorials</a>
                            </h3>
                            <div class="kt-iconbox__content">
                                Books &amp; Articles
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="kt-portlet kt-iconbox kt-iconbox--warning kt-iconbox--animate-fast">
                <div class="kt-portlet__body">
                    <div class="kt-iconbox__body">
                        <div class="kt-iconbox__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg> </div>
                        <div class="kt-iconbox__desc">
                            <h3 class="kt-iconbox__title">
                                <a class="kt-link" href="javascript:;">User Guide</a>
                            </h3>
                            <div class="kt-iconbox__content">
                                Useful Guidelines
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="kt-portlet kt-iconbox kt-iconbox--danger kt-iconbox--animate-faster">
                <div class="kt-portlet__body">
                    <div class="kt-iconbox__body">
                        <div class="kt-iconbox__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M13.0799676,14.7839934 L15.2839934,12.5799676 C15.8927139,11.9712471 16.0436229,11.0413042 15.6586342,10.2713269 L15.5337539,10.0215663 C15.1487653,9.25158901 15.2996742,8.3216461 15.9083948,7.71292558 L18.6411989,4.98012149 C18.836461,4.78485934 19.1530435,4.78485934 19.3483056,4.98012149 C19.3863063,5.01812215 19.4179321,5.06200062 19.4419658,5.11006808 L20.5459415,7.31801948 C21.3904962,9.0071287 21.0594452,11.0471565 19.7240871,12.3825146 L13.7252616,18.3813401 C12.2717221,19.8348796 10.1217008,20.3424308 8.17157288,19.6923882 L5.75709327,18.8875616 C5.49512161,18.8002377 5.35354162,18.5170777 5.4408655,18.2551061 C5.46541191,18.1814669 5.50676633,18.114554 5.56165376,18.0596666 L8.21292558,15.4083948 C8.8216461,14.7996742 9.75158901,14.6487653 10.5215663,15.0337539 L10.7713269,15.1586342 C11.5413042,15.5436229 12.4712471,15.3927139 13.0799676,14.7839934 Z" fill="#000000"></path>
                                    <path d="M14.1480759,6.00715131 L13.9566988,7.99797396 C12.4781389,7.8558405 11.0097207,8.36895892 9.93933983,9.43933983 C8.8724631,10.5062166 8.35911588,11.9685602 8.49664195,13.4426352 L6.50528978,13.6284215 C6.31304559,11.5678496 7.03283934,9.51741319 8.52512627,8.02512627 C10.0223249,6.52792766 12.0812426,5.80846733 14.1480759,6.00715131 Z M14.4980938,2.02230302 L14.313049,4.01372424 C11.6618299,3.76737046 9.03000738,4.69181803 7.1109127,6.6109127 C5.19447112,8.52735429 4.26985715,11.1545872 4.51274152,13.802405 L2.52110319,13.985098 C2.22450978,10.7517681 3.35562581,7.53777247 5.69669914,5.19669914 C8.04101739,2.85238089 11.2606138,1.72147333 14.4980938,2.02230302 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                </g>
                            </svg> </div>
                        <div class="kt-iconbox__desc">
                            <h3 class="kt-iconbox__title">
                                <a class="kt-link" href="javascript:;">Contact Support</a>
                            </h3>
                            <div class="kt-iconbox__content">
                                24/7 Hotline
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="kt-portlet kt-callout kt-callout--secondary kt-callout--diagonal-bg">
                <div class="kt-portlet__body">
                    <div class="kt-callout__body">
                        <div class="kt-callout__content">
                            <h3 class="kt-callout__title">Get in Touch</h3>
                            <p class="kt-callout__desc">
                                Windows 10 automatically installs updates to make for sure
                            </p>
                        </div>
                        <div class="kt-callout__action">
                            <a href="javascript:;" class="btn btn-custom btn-bold btn-upper btn-font-sm  btn-warning">Submit a Request</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="kt-portlet kt-callout kt-callout--info kt-callout--diagonal-bg">
                <div class="kt-portlet__body">
                    <div class="kt-callout__body">
                        <div class="kt-callout__content">
                            <h3 class="kt-callout__title">Live Chat</h3>
                            <p class="kt-callout__desc">
                                Windows 10 automatically installs updates to make for sure
                            </p>
                        </div>
                        <div class="kt-callout__action">
                            <a href="javascript:;" class="btn btn-custom btn-bold btn-upper btn-font-sm  btn-info">Start Chat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="kt-portlet kt-callout kt-callout--success kt-callout--diagonal-bg">
                <div class="kt-portlet__body">
                    <div class="kt-callout__body">
                        <div class="kt-callout__content">
                            <h3 class="kt-callout__title">Live Chat</h3>
                            <p class="kt-callout__desc">
                                Windows 10 automatically installs updates to make for sure
                            </p>
                        </div>
                        <div class="kt-callout__action">
                            <a href="javascript:;" class="btn btn-custom btn-bold btn-upper btn-font-sm  btn-success">Start Chat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-12 order-lg-3 order-xl-1">
            <!--begin:: Widgets/Best Sellers-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Best Sellers
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_widget5_tab1_content" role="tab">
                                    Latest
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget5_tab2_content" role="tab">
                                    Month
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget5_tab3_content" role="tab">
                                    All time
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget5_tab1_content" aria-expanded="true">
                            <div class="kt-widget5">
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product27.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Great Logo Designn
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic admin themes.
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Keenthemes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">19,200</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">1046</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product22.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Branding Mockup
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic bootstrap themes.
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Fly themes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">24,583</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">3809</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product15.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Awesome Mobile App
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic admin themes.Lorem Ipsum Amet
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Fly themes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">210,054</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">1103</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget5_tab2_content">
                            <div class="kt-widget5">
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product10.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Branding Mockup
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic bootstrap themes.
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Fly themes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">24,583</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">3809</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product11.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Awesome Mobile App
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic admin themes.Lorem Ipsum Amet
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Fly themes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">210,054</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">1103</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product6.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Great Logo Designn
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic admin themes.
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Keenthemes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">19,200</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">1046</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget5_tab3_content">
                            <div class="kt-widget5">
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product11.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Awesome Mobile App
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic admin themes.Lorem Ipsum Amet
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Fly themes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">210,054</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">1103</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product6.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Great Logo Designn
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic admin themes.
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Keenthemes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">19,200</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">1046</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget5__item">
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__pic">
                                            <img class="kt-widget7__img" src="{{asset('media/products/product10.jpg')}}" alt="">
                                        </div>
                                        <div class="kt-widget5__section">
                                            <a href="javascript:;" class="kt-widget5__title">
                                                Branding Mockup
                                            </a>
                                            <p class="kt-widget5__desc">
                                                Metronic bootstrap themes.
                                            </p>
                                            <div class="kt-widget5__info">
                                                <span>Author:</span>
                                                <span class="kt-font-info">Fly themes</span>
                                                <span>Released:</span>
                                                <span class="kt-font-info">23.08.17</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget5__content">
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">24,583</span>
                                            <span class="kt-widget5__sales">sales</span>
                                        </div>
                                        <div class="kt-widget5__stats">
                                            <span class="kt-widget5__number">3809</span>
                                            <span class="kt-widget5__votes">votes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Best Sellers-->
        </div>
        <div class="col-xl-4 col-lg-6 order-lg-3 order-xl-1">

            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            New Users
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_widget4_tab1_content" role="tab">
                                    Today
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget4_tab2_content" role="tab">
                                    Month
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget4_tab1_content">
                            <div class="kt-widget4">
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_4.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Anna Strong
                                        </a>
                                        <p class="kt-widget4__text">
                                            Visual Designer,Google Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-brand btn-bold">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_14.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Milano Esco
                                        </a>
                                        <p class="kt-widget4__text">
                                            Product Designer, Apple Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-warning btn-bold">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_11.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Nick Bold
                                        </a>
                                        <p class="kt-widget4__text">
                                            Web Developer, Facebook Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-danger btn-bold">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_1.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Wiltor Delton
                                        </a>
                                        <p class="kt-widget4__text">
                                            Project Manager, Amazon Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-success btn-bold">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_5.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Nick Stone
                                        </a>
                                        <p class="kt-widget4__text">
                                            Visual Designer, Github Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-primary btn-bold">Follow</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget4_tab2_content">
                            <div class="kt-widget4">
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_2.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Kristika Bold
                                        </a>
                                        <p class="kt-widget4__text">
                                            Product Designer,Apple Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-success">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_13.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Ron Silk
                                        </a>
                                        <p class="kt-widget4__text">
                                            Release Manager, Loop Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-brand">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_9.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Nick Bold
                                        </a>
                                        <p class="kt-widget4__text">
                                            Web Developer, Facebook Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-danger">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_2.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Wiltor Delton
                                        </a>
                                        <p class="kt-widget4__text">
                                            Project Manager, Amazon Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-success">Follow</a>
                                </div>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                        <img src="{{asset('media/users/100_8.jpg')}}" alt="">
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a href="javascript:;" class="kt-widget4__username">
                                            Nick Bold
                                        </a>
                                        <p class="kt-widget4__text">
                                            Web Developer, Facebook Inc
                                        </p>
                                    </div>
                                    <a href="javascript:;" class="btn btn-sm btn-label-info">Follow</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/New Users-->
        </div>
        <div class="col-xl-4 col-lg-4 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Daily Sales-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header kt-margin-b-30">
                        <h3 class="kt-widget14__title">
                            Daily Sales
                        </h3>
                        <span class="kt-widget14__desc">
                            Check out each collumn for more details
                        </span>
                    </div>
                    <div class="kt-widget14__chart" style="height:120px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="kt_chart_daily_sales" style="display: block; width: 360px; height: 120px;" width="360" height="120" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Daily Sales-->
        </div>
        <div class="col-xl-4 col-lg-4 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Profit Share-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Profit Share
                        </h3>
                        <span class="kt-widget14__desc">
                            Profit Share between customers
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <div class="kt-widget14__stat">45</div>
                            <canvas id="kt_chart_profit_share" style="height: 140px; width: 140px; display: block;" width="140" height="140" class="chartjs-render-monitor"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats">37% Sport Tickets</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats">47% Business Events</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-brand"></span>
                                <span class="kt-widget14__stats">19% Others</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Profit Share-->
        </div>
        <div class="col-xl-4 col-lg-4 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Revenue Change-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Revenue Change
                        </h3>
                        <span class="kt-widget14__desc">
                            Revenue change breakdown by cities
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div id="kt_chart_revenue_change" style="height: 150px; width: 150px;"></div>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats">+10% New York</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats">-7% London</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-brand"></span>
                                <span class="kt-widget14__stats">+20% California</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Revenue Change-->
        </div>
        <div class="col-xl-4 col-lg-6 order-lg-3 order-xl-1">

            <!--begin:: Widgets/Tasks -->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Tasks
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_widget2_tab1_content" role="tab">
                                    Today
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget2_tab2_content" role="tab">
                                    Week
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget2_tab3_content" role="tab">
                                    Month
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget2_tab1_content">
                            <div class="kt-widget2">
                                <div class="kt-widget2__item kt-widget2__item--primary">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Metronic Great Again.Lorem Ipsum Amet
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--warning">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Prepare Docs For Metting On Monday
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Sean
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--brand">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Widgets Development. Estudiat Communy Elit
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Aziko
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--success">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Metronic Development. Lorem Ipsum
                                        </a>
                                        <a class="kt-widget2__username">
                                            By James
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--danger">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--info">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Sean
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget2_tab2_content">
                            <div class="kt-widget2">
                                <div class="kt-widget2__item kt-widget2__item--success">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Metronic Development.Lorem Ipsum
                                        </a>
                                        <a class="kt-widget2__username">
                                            By James
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--warning">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Prepare Docs For Metting On Monday
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Sean
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--danger">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--primary">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Metronic Great Again.Lorem Ipsum Amet
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--info">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Sean
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--brand">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Widgets Development.Estudiat Communy Elit
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Aziko
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget2_tab3_content">
                            <div class="kt-widget2">
                                <div class="kt-widget2__item kt-widget2__item--warning">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Metronic Development. Lorem Ipsum
                                        </a>
                                        <a class="kt-widget2__username">
                                            By James
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--danger">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--brand">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Widgets Development.Estudiat Communy Elit
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Aziko
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--info">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Sean
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--success">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Completa Financial Report For Emirates Airlines
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-widget2__item kt-widget2__item--primary">
                                    <div class="kt-widget2__checkbox">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <a href="javascript:;" class="kt-widget2__title">
                                            Make Metronic Great Again.Lorem Ipsum Amet
                                        </a>
                                        <a href="javascript:;" class="kt-widget2__username">
                                            By Bob
                                        </a>
                                    </div>
                                    <div class="kt-widget2__actions">
                                        <a href="javascript:;" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                            <i class="flaticon-more-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                        <span class="kt-nav__link-text">Reports</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-send"></i>
                                                        <span class="kt-nav__link-text">Messages</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-pie-chart-1"></i>
                                                        <span class="kt-nav__link-text">Charts</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-avatar"></i>
                                                        <span class="kt-nav__link-text">Members</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="javascript:;" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                        <span class="kt-nav__link-text">Settings</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Tasks -->
        </div>
        <div class="col-xl-4 col-lg-6 order-lg-3 order-xl-1">

            <!--begin:: Widgets/Notifications-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Notifications
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_widget6_tab1_content" role="tab">
                                    Latest
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget6_tab2_content" role="tab">
                                    Week
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_widget6_tab3_content" role="tab">
                                    Month
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget6_tab1_content" aria-expanded="true">
                            <div class="kt-notification">
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"></path>
                                                <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been received.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.98630124,11 4.48466491,11.0516454 4,11.1500272 L4,7 C4,5.8954305 4.8954305,5 6,5 L20,5 C21.1045695,5 22,5.8954305 22,7 L22,16 C22,17.1045695 21.1045695,18 20,18 L12.9835977,18 Z M19.1444251,6.83964668 L13,10.1481833 L6.85557487,6.83964668 C6.4908718,6.6432681 6.03602525,6.77972206 5.83964668,7.14442513 C5.6432681,7.5091282 5.77972206,7.96397475 6.14442513,8.16035332 L12.6444251,11.6603533 C12.8664074,11.7798822 13.1335926,11.7798822 13.3555749,11.6603533 L19.8555749,8.16035332 C20.2202779,7.96397475 20.3567319,7.5091282 20.1603533,7.14442513 C19.9639747,6.77972206 19.5091282,6.6432681 19.1444251,6.83964668 Z" fill="#000000"></path>
                                                <path d="M8.4472136,18.1055728 C8.94119209,18.3525621 9.14141644,18.9532351 8.89442719,19.4472136 C8.64743794,19.9411921 8.0467649,20.1414164 7.5527864,19.8944272 L5,18.618034 L5,14.5 C5,13.9477153 5.44771525,13.5 6,13.5 C6.55228475,13.5 7,13.9477153 7,14.5 L7,17.381966 L8.4472136,18.1055728 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New member is registered and pending for approval.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                <circle fill="#000000" cx="12" cy="9" r="5"></circle>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Membership application has been added.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M18.5,8 C17.1192881,8 16,6.88071187 16,5.5 C16,4.11928813 17.1192881,3 18.5,3 C19.8807119,3 21,4.11928813 21,5.5 C21,6.88071187 19.8807119,8 18.5,8 Z M18.5,21 C17.1192881,21 16,19.8807119 16,18.5 C16,17.1192881 17.1192881,16 18.5,16 C19.8807119,16 21,17.1192881 21,18.5 C21,19.8807119 19.8807119,21 18.5,21 Z M5.5,21 C4.11928813,21 3,19.8807119 3,18.5 C3,17.1192881 4.11928813,16 5.5,16 C6.88071187,16 8,17.1192881 8,18.5 C8,19.8807119 6.88071187,21 5.5,21 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M5.5,8 C4.11928813,8 3,6.88071187 3,5.5 C3,4.11928813 4.11928813,3 5.5,3 C6.88071187,3 8,4.11928813 8,5.5 C8,6.88071187 6.88071187,8 5.5,8 Z M11,4 L13,4 C13.5522847,4 14,4.44771525 14,5 C14,5.55228475 13.5522847,6 13,6 L11,6 C10.4477153,6 10,5.55228475 10,5 C10,4.44771525 10.4477153,4 11,4 Z M11,18 L13,18 C13.5522847,18 14,18.4477153 14,19 C14,19.5522847 13.5522847,20 13,20 L11,20 C10.4477153,20 10,19.5522847 10,19 C10,18.4477153 10.4477153,18 11,18 Z M5,10 C5.55228475,10 6,10.4477153 6,11 L6,13 C6,13.5522847 5.55228475,14 5,14 C4.44771525,14 4,13.5522847 4,13 L4,11 C4,10.4477153 4.44771525,10 5,10 Z M19,10 C19.5522847,10 20,10.4477153 20,11 L20,13 C20,13.5522847 19.5522847,14 19,14 C18.4477153,14 18,13.5522847 18,13 L18,11 C18,10.4477153 18.4477153,10 19,10 Z" fill="#000000"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New report file has been uploaded.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            5 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M16.3740377,19.9389434 L22.2226499,11.1660251 C22.4524142,10.8213786 22.3592838,10.3557266 22.0146373,10.1259623 C21.8914367,10.0438285 21.7466809,10 21.5986122,10 L17,10 L17,4.47708173 C17,4.06286817 16.6642136,3.72708173 16.25,3.72708173 C15.9992351,3.72708173 15.7650616,3.85240758 15.6259623,4.06105658 L9.7773501,12.8339749 C9.54758575,13.1786214 9.64071616,13.6442734 9.98536267,13.8740377 C10.1085633,13.9561715 10.2533191,14 10.4013878,14 L15,14 L15,19.5229183 C15,19.9371318 15.3357864,20.2729183 15.75,20.2729183 C16.0007649,20.2729183 16.2349384,20.1475924 16.3740377,19.9389434 Z" fill="#000000"></path>
                                                <path d="M4.5,5 L9.5,5 C10.3284271,5 11,5.67157288 11,6.5 C11,7.32842712 10.3284271,8 9.5,8 L4.5,8 C3.67157288,8 3,7.32842712 3,6.5 C3,5.67157288 3.67157288,5 4.5,5 Z M4.5,17 L9.5,17 C10.3284271,17 11,17.6715729 11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L4.5,20 C3.67157288,20 3,19.3284271 3,18.5 C3,17.6715729 3.67157288,17 4.5,17 Z M2.5,11 L6.5,11 C7.32842712,11 8,11.6715729 8,12.5 C8,13.3284271 7.32842712,14 6.5,14 L2.5,14 C1.67157288,14 1,13.3284271 1,12.5 C1,11.6715729 1.67157288,11 2.5,11 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New user feedback received and pending for review.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            8 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000"></path>
                                                <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5"></circle>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Database sever #2 has been fully restarted.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            23 hrs ago
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget6_tab2_content" aria-expanded="false">
                            <div class="kt-notification">
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M9.35321926,16.3736278 L16.3544311,10.3706602 C16.5640654,10.1909158 16.5882961,9.87526197 16.4085517,9.66562759 C16.3922584,9.64662485 16.3745611,9.62887247 16.3556091,9.6125202 L9.35439731,3.57169798 C9.14532254,3.39130299 8.82959492,3.41455255 8.64919993,3.62362732 C8.5708616,3.71442013 8.52776329,3.83034375 8.52776329,3.95026134 L8.52776329,15.9940512 C8.52776329,16.2701936 8.75162092,16.4940512 9.02776329,16.4940512 C9.14714624,16.4940512 9.2625893,16.4513356 9.35321926,16.3736278 Z" fill="#000000" transform="translate(12.398118, 9.870355) rotate(-450.000000) translate(-12.398118, -9.870355) "></path>
                                                <rect fill="#000000" opacity="0.3" transform="translate(12.500000, 17.500000) scale(-1, 1) rotate(-270.000000) translate(-12.500000, -17.500000) " x="11" y="11" width="3" height="13" rx="0.5"></rect>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New company application has been approved.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"></rect>
                                                <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"></rect>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"></rect>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New report has been received.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            23 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--danger">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"></path>
                                                <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New file has been uploaded and pending for review.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            5 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                <circle fill="#000000" cx="12" cy="9" r="5"></circle>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Membership application has been added.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--info">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M12,4.25932872 C12.1488635,4.25921584 12.3000368,4.29247316 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 L12,4.25932872 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M12,4.25932872 L12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.277344,4.464261 11.6315987,4.25960807 12,4.25932872 Z" fill="#000000"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 days ago
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane" id="kt_widget6_tab3_content" aria-expanded="false">
                            <div class="kt-notification">
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--warning">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M14.5,11 C15.0522847,11 15.5,11.4477153 15.5,12 L15.5,15 C15.5,15.5522847 15.0522847,16 14.5,16 L9.5,16 C8.94771525,16 8.5,15.5522847 8.5,15 L8.5,12 C8.5,11.4477153 8.94771525,11 9.5,11 L9.5,10.5 C9.5,9.11928813 10.6192881,8 12,8 C13.3807119,8 14.5,9.11928813 14.5,10.5 L14.5,11 Z M12,9 C11.1715729,9 10.5,9.67157288 10.5,10.5 L10.5,11 L13.5,11 L13.5,10.5 C13.5,9.67157288 12.8284271,9 12,9 Z" fill="#000000"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been received.
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M13,5.06189375 C12.6724058,5.02104333 12.3386603,5 12,5 C11.6613397,5 11.3275942,5.02104333 11,5.06189375 L11,4 L10,4 C9.44771525,4 9,3.55228475 9,3 C9,2.44771525 9.44771525,2 10,2 L14,2 C14.5522847,2 15,2.44771525 15,3 C15,3.55228475 14.5522847,4 14,4 L13,4 L13,5.06189375 Z" fill="#000000"></path>
                                                <path d="M16.7099142,6.53272645 L17.5355339,5.70710678 C17.9260582,5.31658249 18.5592232,5.31658249 18.9497475,5.70710678 C19.3402718,6.09763107 19.3402718,6.73079605 18.9497475,7.12132034 L18.1671361,7.90393167 C17.7407802,7.38854954 17.251061,6.92750259 16.7099142,6.53272645 Z" fill="#000000"></path>
                                                <path d="M11.9630156,7.5 L12.0369844,7.5 C12.2982526,7.5 12.5154733,7.70115317 12.5355117,7.96165175 L12.9585886,13.4616518 C12.9797677,13.7369807 12.7737386,13.9773481 12.4984096,13.9985272 C12.4856504,13.9995087 12.4728582,14 12.4600614,14 L11.5399386,14 C11.2637963,14 11.0399386,13.7761424 11.0399386,13.5 C11.0399386,13.4872031 11.0404299,13.4744109 11.0414114,13.4616518 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <rect fill="#000000" opacity="0.3" x="2" y="9" width="20" height="13" rx="2"></rect>
                                                <rect fill="#000000" opacity="0.3" x="5" y="5" width="14" height="2" rx="0.5"></rect>
                                                <rect fill="#000000" opacity="0.3" x="7" y="1" width="10" height="2" rx="0.5"></rect>
                                                <path d="M10.8333333,20 C9.82081129,20 9,19.3159906 9,18.4722222 C9,17.6284539 9.82081129,16.9444444 10.8333333,16.9444444 C11.0476105,16.9444444 11.2533018,16.9750785 11.4444444,17.0313779 L11.4444444,12.7916011 C11.4444444,12.4782408 11.6398662,12.2012404 11.9268804,12.1077729 L15.4407693,11.0331119 C15.8834716,10.8889438 16.3333333,11.2336005 16.3333333,11.7169402 L16.3333333,12.7916011 C16.3333333,13.1498215 15.9979332,13.3786009 15.7222222,13.4444444 C15.3255297,13.53918 14.3070112,13.7428837 12.6666667,14.0555556 L12.6666667,18.5035214 C12.6666667,18.5583862 12.6622174,18.6091837 12.6535404,18.6559869 C12.5446237,19.4131089 11.771224,20 10.8333333,20 Z" fill="#000000"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Application has been approved
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--warning">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect opacity="0.200000003" x="0" y="0" width="24" height="24"></rect>
                                                <path d="M4.5,7 L9.5,7 C10.3284271,7 11,7.67157288 11,8.5 C11,9.32842712 10.3284271,10 9.5,10 L4.5,10 C3.67157288,10 3,9.32842712 3,8.5 C3,7.67157288 3.67157288,7 4.5,7 Z M13.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L13.5,18 C12.6715729,18 12,17.3284271 12,16.5 C12,15.6715729 12.6715729,15 13.5,15 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M17,11 C15.3431458,11 14,9.65685425 14,8 C14,6.34314575 15.3431458,5 17,5 C18.6568542,5 20,6.34314575 20,8 C20,9.65685425 18.6568542,11 17,11 Z M6,19 C4.34314575,19 3,17.6568542 3,16 C3,14.3431458 4.34314575,13 6,13 C7.65685425,13 9,14.3431458 9,16 C9,17.6568542 7.65685425,19 6,19 Z" fill="#000000"></path>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer comment recieved
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 days ago
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:;" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--danger">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"></path>
                                                <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                            </g>
                                        </svg> </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 days ago
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Notifications-->
        </div>
        <div class="col-xl-4 col-lg-6 order-lg-3 order-xl-1">

            <!--begin:: Widgets/Support Tickets -->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Support Tickets
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="dropdown dropdown-inline">
                            <button type="button" class="btn btn-clean btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="flaticon-more-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-md">

                                <!--begin::Nav-->
                                <ul class="kt-nav">
                                    <li class="kt-nav__head">
                                        Export Options
                                        <span data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more...">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand kt-svg-icon--md1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"></circle>
                                                    <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"></rect>
                                                    <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"></rect>
                                                </g>
                                            </svg> </span>
                                    </li>
                                    <li class="kt-nav__separator"></li>
                                    <li class="kt-nav__item">
                                        <a href="javascript:;" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-drop"></i>
                                            <span class="kt-nav__link-text">Activity</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="javascript:;" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-calendar-8"></i>
                                            <span class="kt-nav__link-text">FAQ</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="javascript:;" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-telegram-logo"></i>
                                            <span class="kt-nav__link-text">Settings</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="javascript:;" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-new-email"></i>
                                            <span class="kt-nav__link-text">Support</span>
                                            <span class="kt-nav__link-badge">
                                                <span class="kt-badge kt-badge--success kt-badge--rounded">5</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__separator"></li>
                                    <li class="kt-nav__foot">
                                        <a class="btn btn-label-danger btn-bold btn-sm" href="javascript:;">Upgrade plan</a>
                                        <a class="btn btn-clean btn-bold btn-sm" href="javascript:;" data-toggle="kt-tooltip" data-placement="right" title="" data-original-title="Click to learn more...">Learn more</a>
                                    </li>
                                </ul>

                                <!--end::Nav-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget3">
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{asset('media/users/user1.jpg')}}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="javascript:;" class="kt-widget3__username">
                                        Melania Trump
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-info">
                                    Pending
                                </span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{asset('media/users/user4.jpg')}}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="javascript:;" class="kt-widget3__username">
                                        Lebron King James
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        1 day ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-brand">
                                    Open
                                </span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.Ut wisi enim ad minim veniam,quis nostrud exerci tation ullamcorper.
                                </p>
                            </div>
                        </div>
                        <div class="kt-widget3__item">
                            <div class="kt-widget3__header">
                                <div class="kt-widget3__user-img">
                                    <img class="kt-widget3__img" src="{{asset('media/users/user5.jpg')}}" alt="">
                                </div>
                                <div class="kt-widget3__info">
                                    <a href="javascript:;" class="kt-widget3__username">
                                        Deb Gibson
                                    </a><br>
                                    <span class="kt-widget3__time">
                                        3 weeks ago
                                    </span>
                                </div>
                                <span class="kt-widget3__status kt-font-success">
                                    Closed
                                </span>
                            </div>
                            <div class="kt-widget3__body">
                                <p class="kt-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Support Tickets -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet" id="kt_portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="flaticon-map-location"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Basic Calendar
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="javascript:;" class="btn btn-brand btn-elevate">
                            <i class="la la-plus"></i>
                            Add Event
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div id="kt_calendar"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Alert Styles
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <!--begin::Section-->
                    <div class="kt-section">
                        <div class="kt-section__info">Outline style examples</div>
                        <div class="kt-section__content">
                            <div class="alert alert-primary fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">A simple primary alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-secondary  fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">A simple secondary alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">A simple success alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-danger fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">A simple danger alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-warning fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">A simple warning alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-info fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">A simple info alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-light alert-elevate fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">A simple light alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-dark fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">A simple dark alert—check it out!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Section-->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-xl-6">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Basic User Pics
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-section">
                        <!--begin::Section-->
                        <div class="kt-section__info">Alternative user pics:</div>
                        <div class="kt-section__content d-flex flex-wrap kt-section__content--solid--">
                            <span class="kt-media kt-media--danger kt-margin-r-5 kt-margin-t-5">
                                <span>JD</span>
                            </span>
                            <span class="kt-media kt-media--info kt-margin-r-5 kt-margin-t-5">
                                <span>SA</span>
                            </span>
                            <span class="kt-media kt-media--success kt-margin-r-5 kt-margin-t-5">
                                <span>ER</span>
                            </span>
                            <span class="kt-media kt-media--warning kt-margin-r-5 kt-margin-t-5">
                                <span>BD</span>
                            </span>
                            <span class="kt-media kt-media--danger kt-margin-r-5 kt-margin-t-5">
                                <span>CD</span>
                            </span>
                            <span class="kt-media kt-media--brand kt-margin-r-5 kt-margin-t-5">
                                <span>NG</span>
                            </span>
                            <span class="kt-media kt-media--success kt-margin-r-5 kt-margin-t-5">
                                <span>MR</span>
                            </span>
                        </div>

                        <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>

                        <div class="kt-section__info">Sizing options(sm, lg, xl):</div>
                        <div class="kt-section__content d-flex flex-wrap kt-section__content--solid--">
                            <span class="kt-media kt-media--sm kt-media--success kt-media--circle kt-margin-r-5 kt-margin-t-5">
                                <span>MS</span>
                            </span>
                            <span class="kt-media kt-media--sm kt-media--circle kt-media--danger kt-margin-r-5 kt-margin-t-5">
                                <span>AC</span>
                            </span>
                            <span class="kt-media kt-media--sm kt-media--circle kt-media--warning kt-margin-r-5 kt-margin-t-5">
                                <span>KL</span>
                            </span>
                            <span class="kt-media kt-media--sm kt-media--circle kt-media--brand kt-margin-r-5 kt-margin-t-5">
                                <span>FR</span>
                            </span>
                        </div>

                        <!--end::Section-->

                        <!--begin::Section-->
                        <div class="kt-section__content d-flex flex-wrap kt-margin-t-30 kt-section__content--solid--">
                            <span class="kt-media kt-media--lg kt-media--brand kt-margin-r-5 kt-margin-t-5">
                                <span>BT</span>
                            </span>
                            <span class="kt-media kt-media--lg  kt-media--danger kt-margin-r-5 kt-margin-t-5">
                                <span>PY</span>
                            </span>
                            <span class="kt-media kt-media--lg kt-media--warning kt-margin-r-5 kt-margin-t-5">
                                <span>JU</span>
                            </span>
                            <span class="kt-media kt-media--lg  kt-media--success kt-margin-r-5 kt-margin-t-5">
                                <span>GF</span>
                            </span>
                        </div>

                        <!--end::Section-->

                        <!--begin::Section-->
                        <div class="kt-section__content d-flex flex-wrap kt-margin-t-30 kt-section__content--solid--">
                            <span class="kt-media kt-media--xl kt-media--circle kt-media--danger kt-margin-r-5 kt-margin-t-5">
                                <span>BT</span>
                            </span>
                            <span class="kt-media kt-media--xl kt-media--circle kt-media--warning kt-margin-r-5 kt-margin-t-5">
                                <span>PY</span>
                            </span>
                            <span class="kt-media kt-media--xl kt-media--circle kt-media--brand kt-margin-r-5 kt-margin-t-5">
                                <span>JU</span>
                            </span>
                            <span class="kt-media kt-media--xl kt-media--circle kt-media--success kt-margin-r-5 kt-margin-t-5">
                                <span>GF</span>
                            </span>
                        </div>

                        <!--end::Section-->
                    </div>
                </div>
            </div>

            <!--end::Portlet-->
        </div>
    </div>

</div>
@endsection
@section("scripts")
<script src="{{asset('js/pages/components/calendar/basic.js')}}" type="text/javascript"></script>
@endsection
