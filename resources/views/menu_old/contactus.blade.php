@include('front.header')

<body class="about">
    @include('front.menu')
    <main class="abtmain">
        <!-- <div class="breadcrumbsdiv py-5">
            <nav aria-label="breadcrumb" class=" py-5 my-3">
                <ol class="breadcrumb justify-content-center py-5">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h2 class="fw-bold m-0 text-white border-bottom">contact us</h2>
                    </li>
                </ol>
            </nav>
        </div> -->
        <div class="section_margin">
            <div class="container">
                <div class="topsec">
                    <div class="title-block mb-3 text-center">
                        <h1 class="fw-bold" style="font-size: 23px;"><span class="span-theme">Get In Touch With Us</span></h1>
                        <h2>We're Here to Assist You</h2>
                    </div>

                    <div class="topseccont--content">
                        <p data-aos="fade-up" class="aos-init aos-animate mb-3 text-center">At Pharma24*7, we’re
                            committed to providing exceptional customer service and support to help you get the most out
                            of our platform. Whether you have a question, need assistance with the software, or require
                            technical support, our team is always ready to assist.</p>
                        <div class="supportrtowdiv pb-0 pt-5 supportrtowdiv">
                            <div class="row row-gap-3">
                                <div class="col-lg-3 col-md-6 ">
                                    <div class="supportarddiv cntct-sec text-center">
                                        <div
                                            class="iconsq conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                            <ion-icon class="text-white fs-3" name="headset-outline"></ion-icon>
                                        </div>
                                        <h2 style="font-size: 20px;" class="fw-bold">Technical Support</h2>
                                        <p class="fw-medium">Troubleshooting and resolving any issues you encounter with
                                            Pharma24*7.</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 ">
                                    <div class="supportarddiv cntct-sec text-center">
                                        <div
                                            class="iconsq conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                            <ion-icon class="text-white fs-3" name="help-outline"></ion-icon>
                                        </div>
                                        <h2 style="font-size: 20px;" class="fw-bold">Product Inquiries</h2>
                                        <p class="fw-medium">Get detailed information about our features, pricing, and
                                            how Pharma24*7 can benefit your pharmacy.</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 ">
                                    <div class="supportarddiv cntct-sec text-center">
                                        <div
                                            class="iconsq conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                            <ion-icon class="text-white fs-3"
                                                name="extension-puzzle-outline"></ion-icon>
                                        </div>
                                        <h2 style="font-size: 20px;" class="fw-bold">Onboarding and Setup</h2>
                                        <p class="fw-medium">Need help getting started? Our team will guide you through
                                            the setup process.</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 ">
                                    <div class="supportarddiv cntct-sec text-center">
                                        <div
                                            class="iconsq conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                            <ion-icon class="text-white fs-3" name="color-wand-outline"></ion-icon>
                                        </div>
                                        <h2 style="font-size: 20px;" class="fw-bold">Feature Requests</h2>
                                        <p class="fw-medium">Have a suggestion for a new feature? We’d love to hear it!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="sec_mar cntct-sec">
            <div class="container">
                <div class="con--form">
                    <div class="bg-wrapper light-bg mt-80 lg-mt-40">
                        <div class="align-items-center">
                            <!-- <div class="col-lg-5">
                                <div class="cont--div">
                                    <div class="cont-top">
                                        <div class="row row-gap-5">
                                            <div class="d-flex gap-5">
                                                <div class="col-md-6 col-lg-12 col-sm-6 col-12">
                                                    <div class="address-block-one text-center">
                                                        <div
                                                            class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                            <img src="{{asset('public/landing_design/images/icon/telephone.svg')}}" alt=""
                                                                class="lazy-img img-fluid">
                                                        </div>
                                                        <h4 class="cont--title fw-bold">Contact Info</h4>
                                                        <p class="fw-medium">Monday to Saturday, 9 AM to 6 PM (IST)</p>
                                                        <p>Speak directly with our support team for real-time
                                                            assistance.<br>
                                                        <div class="mt-2">
                                                            <a href="tel:+91 908 1111 247"
                                                                class="call text-lg fw-500">+91
                                                                908 1111 247</a>
                                                        </div>
                                                        </p>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6 col-lg-12 col-sm-6 col-12">
                                                    <div class="address-block-one text-center">
                                                        <div
                                                            class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                            <img src="{{asset('public/landing_design/images/icon/mail.png')}}" alt=""
                                                                class="lazy-img img-fluid">
                                                        </div>
                                                        <h4 class="cont--title fw-bold">Email us</h4>
                                                        <p>Send us an email for detailed queries or requests, and we’ll
                                                            get
                                                            back to you within 24 hours.<br>
                                                        <div class="mt-2">
                                                            <a href="mailto: inquiry@Pharma24*7.in" class="webaddress">
                                                                inquiry@Pharma24*7.in</a>
                                                        </div>
                                                        </p>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="d-flex gap-5">
                                                <div class="col-md-6 col-lg-12 col-sm-6 col-12">
                                                    <div class="address-block-one text-center">
                                                        <div
                                                            class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                            <ion-icon name="chatbox-outline"
                                                                class="fs-2 text-white"></ion-icon>
                                                        </div>
                                                        <h4 class="cont--title fw-bold">Live Chat</h4>
                                                        <p>For quick help, use our live chat feature. Our support
                                                            representatives are ready to assist you with any issues or
                                                            questions.</p>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6 col-lg-12 col-sm-6 col-12">
                                                    <div class="address-block-one text-center">
                                                        <div
                                                            class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                            <ion-icon name="information-circle-outline"
                                                                class="fs-2 text-white"></ion-icon>
                                                        </div>
                                                        <h4 class="cont--title fw-bold">Help Center</h4>
                                                        <p>Browse through our knowledge base for step-by-step guides,
                                                            FAQs,
                                                            and troubleshooting articles.</p>
                                                        <div class="mt-2">
                                                            <a href="" class="webaddress"> Visit our Help Center</a>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="container py-5">
                                <div class="row g-4 justify-content-center">
                                    <!-- Contact Info Card -->
                                    <div class="col-md-6 col-lg-5">
                                        <div class="address-block-one text-center p-4 rounded shadow-sm h-100">
                                            <div
                                                class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                <img src="{{asset('public/landing_design/images/icon/telephone.svg')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="lazy-img img-fluid">
                                            </div>
                                            <h3 class="cont--title fw-bold">Contact Info</h3>
                                            <p class="fw-medium">Monday to Saturday, 9 AM to 6 PM (IST)</p>
                                            <p>Speak directly with our support team for real-time assistance.</p>
                                            <div class="mt-2">
                                                <a href="tel:+91 908 1111 247" class="call text-lg fw-500">+91 908 1111
                                                    247</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email Card -->
                                    <div class="col-md-6 col-lg-5">
                                        <div class="address-block-one text-center p-4 rounded shadow-sm h-100">
                                            <div
                                                class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                <img src="{{asset('public/landing_design/images/icon/mail.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="lazy-img img-fluid">
                                            </div>
                                            <h3 class="cont--title fw-bold">Email us</h3>
                                            <p>Send us an email for detailed queries or requests, and we’ll get back to
                                                you within 24 hours.</p>
                                            <div class="mt-2">
                                                <a href="mailto: inquiry@Pharma247.in"
                                                    class="webaddress">inquiry@Pharma247.in</a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Live Chat Card -->
                                    <div class="col-md-6 col-lg-5">
                                        <div class="address-block-one text-center p-4 rounded shadow-sm h-100">
                                            <div
                                                class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                <ion-icon name="chatbox-outline" class="fs-2 text-white"></ion-icon>
                                            </div>
                                            <h3 class="cont--title fw-bold">Live Chat</h3>
                                            <p>For quick help, use our live chat feature. Our support representatives
                                                are ready to assist you.</p>
                                            <div class="mt-2">
                                                <a href="#" class="webaddress"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Help Center Card -->
                                    <div class="col-md-6 col-lg-5">
                                        <div class="address-block-one text-center p-4 rounded shadow-sm h-100">
                                            <div
                                                class="conticon mb-3 rounded-circle d-flex align-items-center justify-content-center m-auto">
                                                <ion-icon name="information-circle-outline"
                                                    class="fs-2 text-white"></ion-icon>
                                            </div>
                                            <h3 class="cont--title fw-bold">Help Center</h3>
                                            <p>Browse through our knowledge base for step-by-step guides, FAQs, and
                                                troubleshooting articles.</p>
                                            <div class="mt-2">
                                                <a href="#" class="webaddress">Visit our Help Center</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container py-5 max-991px">
                                <div class="form-style-one row g-4 justify-content-center">
                                    <div class="title-one text-center text-lg-start">
                                        <h3 class="fw-bold text-center">Have inquiries? Reach out via message</h3>
                                    </div>
                                    <form action="{{ route('contactus.store') }}" method="POST" class="needs-validation" novalidate="">
                                        @csrf
                                        <div class="messages"></div>
                                        <div class="row g-4 justify-content-center">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-30">
                                                    <label class="fw-semibold" for="">Name<span class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="name" required>
                                                    <div class="invalid-feedback font-weight-bold" style="font-size: 12px;">
                                                        Please Enter Name
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-12 mb-4">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-30">
                                                    <label class="fw-semibold" for="">Company Name</label>
                                                    <input type="text" name="company_name" id="company_name" required="required">
                                                </div>
                                            </div> -->
                                            <div class="col-md-6 col-lg-6">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                                                    <label class="fw-semibold" for="">Email<span class="text-danger">*</span></label>
                                                    <input type="email" name="email" id="email" required>
                                                    <div class="invalid-feedback font-weight-bold" style="font-size: 12px;">
                                                        Please Enter Email
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                                                    <label class="fw-semibold" for="">Phone<span class="text-danger">*</span></label>
                                                    <input type="number" name="phone" id="phone" required>
                                                    <div class="invalid-feedback font-weight-bold" style="font-size: 12px;">
                                                        Please Enter Phone Number
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                                                    <label class="fw-semibold" for="">subject</label>
                                                    <input type="text" name="subject" id="subject" required="required">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                                                    <label class="fw-semibold" for="">address</label>
                                                    <input type="text" name="address" id="address" required="required">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-35">
                                                    <label class="fw-semibold" for="">message</label>
                                                    <textarea name="message" id="message" required="required" rows="5"></textarea>
                                                </div>
                                            </div>
                                            <div class="btnthremediv d-flex justify-content-end col-lg-10 col-md">
                                                <button type="submit" class="btn-four tran3s btn theme-btn knwmrbtn d-block">Send Message</button>
                                            </div>
                                        </div>
                                    </form>
                                </div> <!-- /.form-style-one -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="expdiffsec section_margin px-3 ">
            <div class="expdiffdivmain container bg-theme rounded-5">
                <div class=" expdiffdiv p-5 position-relative z-1 text-white">
                    <div class="align-items-center row row-gap-4 titlediv">
                        <div class="col-md-6">
                            <h3 class="fw-bold">Need Immediate Assistance?</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="">If you’re experiencing a critical issue with the platform, please contact our
                                Emergency Support Line: <a href="tel: +91 12345 67891"
                                    class="text-decoration-underline text-white fs-6 text-capitalize fw-bold">+91 908
                                    1111 247</a>. We are available to resolve urgent technical issues.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            $(document).ready(function() {
                $('.hover-div').hover(function() {
                    $('.hover-div').stop().fadeTo('fast', 0.3);
                    $(this).stop().fadeTo('fast', 1);
                }, function() {
                    $('.hover-div').stop().fadeTo('fast', 1);
                });
            });
        </script>
        <section class="section_margin newhhgehf">
            <div class="container">
                <div class="fertsfgyut">
                    <div class="row row-gap-4 justify-content-">
                        <div class="col-md-6">
                            <div
                                class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">

                                <div class="servicecoldiv00 px-2">
                                    <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                        <ion-icon name="id-card-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                            style="color:#000000" role="img"></ion-icon>
                                        <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Stay
                                            Connected</p>
                                    </div>
                                    <p>
                                        We value your feedback and are here to help you maximize the potential of
                                        Pharma24*7. Follow us on social media or subscribe to our newsletter for updates,
                                        new features, and tips on using Pharma24*7 more effectively.
                                    </p>
                                    <li class="footerli d-flex gap-3 mt-3">
                                        <a href="https://www.facebook.com/profile.php?id=61568780619517&mibextid=ZbWKwL"
                                            class="footerlink socialicon"><i class="fa-brands fa-facebook-f fs-4"
                                                style="color: #115e9c;"></i></a>
                                        <a href="https://www.instagram.com/pharma24_7/profilecard/?igsh=MTkwNWk1OXRlNXE0aA=="
                                            class="footerlink socialicon"><i class="fa-brands fa-instagram fs-4"
                                                style="color:#d62976;"></i></a>
                                        <a href="https://x.com/Pharma24_7?t=OGys8DNHJlt0tOoW98WJmw&s=09"
                                            class="footerlink socialicon"><i
                                                class="fa-brands fa-x-twitter fs-4 text-dark"></i></a>
                                    </li>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                <div class="servicecoldiv00 px-2">
                                    <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                        <ion-icon name="thumbs-up-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                            style="color:#000000" role="img"></ion-icon>
                                        <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Our
                                            Promise</p>
                                    </div>
                                    <p>At Pharma24*7, our philosophy is simple—your success is our priority. We aim to
                                        provide the best support possible, ensuring your pharmacy runs smoothly and
                                        efficiently with our software. No matter the issue, we’re always here to assist
                                        you every step of the way.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('front.footer')