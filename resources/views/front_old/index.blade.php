@include('front.header')

<body class="home">
    @include('front.menu')
    <?php
     $SliderData = \App\Models\SliderModel::get();
    ?>
    <main class="homemain">
        <section class="herosectionhome">
            <div class="herohome">
                <div class="swiper mySwiper position-relative overflow-hidden">
                    <div class="swiper-wrapper">
                        @if(isset($SliderData))
                        @foreach($SliderData as $key => $list)
                        <div class="swiper-slide">
                            <div class="swprslidemaindiv">
                                <div class="hometopbannerdiv py-5">
                                    <div class="container overflow-hidden">
                                        <div class="row align-items-center px-3">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="hometoptext text-start">
                                                   @if($key == 0)
                                                    <h1 class="fw-bold">
                                                       {{ isset($list->title) ? $list->title :"" }}
                                                    </h1>
                                                   @else
                                                     <h2 class="fw-bold">
                                                       {{ isset($list->title) ? $list->title :"" }}
                                                    </h2>
                                                  @endif
                                                    <p><?php echo isset($list->description) ? htmlspecialchars_decode($list->description) : ""; ?>
                                                    </p>
                                                    <div
                                                        class="btnflex d-sm-flex gap-3 flex-wrap align-items-center justify-content-start text-center">
                                                        <a href="{{route('pricing.index')}}" class="btn btn-outline-themegreen">see
                                                            pricing</a>
                                                        <a href=""
                                                            class="btn border-bottom-green theme-text rounded-0">QR
                                                            code</a>
                                                        <!-- <a href=""
                                                            class="btn border-bottom-green theme-text rounded-0">QR
                                                            code</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div
                                                    class="hometopdivimg00 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                <img src="{{asset('public/image/'.$list->image)}}"
                                                   alt="{{ $list->image_description ?? 'Pharma24*7' }}" 
                                                   title="{{ $list->image_description ?? 'Pharma24*7' }}" 
                                                   class="img-fluid" 
                                                   onerror="this.src='{{asset('public/image/default-image.jpg')}}'">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        <!-- <div class="swiper-slide">
                            <div class="swprslidemaindiv">
                                <div class="hometopbannerdiv py-5">
                                    <div class="container overflow-hidden">
                                        <div class="row align-items-center px-3">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="hometoptext text-start">
                                                    <h2 class="fw-bold">
                                                        India’s most efficient Cloud based pharmacy software
                                                    </h2>
                                                    <p>Simplifying and digitizing Pharmacy Operations at the lowest
                                                        cost.
                                                        Unlimited logins on both desktop and phone. Login from anywhere.
                                                    </p>
                                                    <div
                                                        class="btnflex d-sm-flex gap-3 flex-wrap align-items-center justify-content-start text-center">
                                                        <a href="book-training.php" class="btn theme-btn fs-6">check
                                                            demo</a>
                                                        <a href="pricing.php" class="btn btn-outline-themegreen">see
                                                            pricing</a>
                                                        <a href=""
                                                            class="btn border-bottom-green theme-text rounded-0">QR
                                                            code</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div
                                                    class="hometopdivimg00 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                    <img src="{{asset('public/landing_design/images/pharma-dashboard.png')}}"
                                                        alt="" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="swiper-slide">
                            <div class="hometopbannerdiv py-5">
                                <div class="container overflow-hidden">
                                    <div class="row align-items-center px-3">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="hometoptext text-start">
                                                <h2 class="fw-bold">
                                                    Deliver to your loyal consumers at their comfort
                                                </h2>
                                                <p>Pharma 24*7’s consumer app makes it easy for your loyal customers to
                                                    order online
                                                    from you.
                                                </p>
                                                <div
                                                    class="btnflex d-flex gap-3 align-items-center justify-content-start">
                                                    <a href="book-training.php" class="btn theme-btn fs-6">check
                                                        demo</a>
                                                    <a href="pricing.php" class="btn btn-outline-themegreen">learn
                                                        more</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div
                                                class="hometopdivimg00 border-0 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                <img src="{{asset('public/landing_design/images/2nd.png')}}" alt=""
                                                    class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="swiper-slide">
                            <div class="hometopbannerdiv py-5">
                                <div class="container overflow-hidden">
                                    <div class="row align-items-center px-3">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="hometoptext text-start">
                                                <h2 class="fw-bold">
                                                    Costly offerings at no extra costs.
                                                </h2>
                                                <p>Avail different features like SMS, loyalty programs/membership plans
                                                    at no extra
                                                    costs. Others charge hefty money for such offerings.
                                                </p>
                                                <div
                                                    class="btnflex d-flex gap-3 align-items-center justify-content-start">
                                                    <a href="pricing.php" class="btn btn-outline-themegreen">see
                                                        pricing</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div
                                                class="hometopdivimg00 border-0 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                <img src="{{asset('public/landing_design/images/3rd.jpg')}}" alt=""
                                                    class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="swiper-button-prevv swprbtn position-absolute top-50 start-0 px-3 z-1">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </div>
                    <div class="swiper-button-nextt swprbtn position-absolute top-50 end-0 px-3 z-1">
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>
                </div>
            </div>
        </section>
        <section class="section_margin whyussec overflow-hidden">
            <div class="whytrustus">
                <div class="container">
                    <div class="whywediff--div">
                        <div class="row align-items-center">
                            <div class="title-block text-center">
                                <h2 class="fw-bold"><span class="span-theme">Unlock Growth with
                                        Pharma24*7</span></h2>
                                <h2>
                                    Advanced Features for Retail Pharmacy Management
                                </h2>
                            </div>
                            <div class="whywediff--00 px-3 pe-sm-0">
                                <div class="whywediff--sliderr">
                                    <div class="row row-gap-4 align-items-center whydiff--card transition5s ">
                                        <div class="col-md-6">
                                            <div class="whydiffcol">
                                                <img src="{{asset('public/landing_design/images/pharma-dashboard.png')}}"
                                                     alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid rounded-5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-style-two vstack tran3s px-2 px-lg-5 pb-4 pb-lg-0">
                                                <h2 data-aos="fade-up"
                                                    class="fw-bold mt-30 mb-25 d-flex align-items-center gap-2">
                                                    <ion-icon name="cloud-upload-outline"
                                                        class="fs-3 shadow p-3 rounded-5"></ion-icon>Cloud Based
                                                    Software
                                                </h2>
                                                <p data-aos="fade-up" class="mb-20">Stay connected to your pharmacy with
                                                    cloud
                                                    technology, allowing you to manage your store from anywhere.
                                                    Enjoy real-time updates and automatic backups that keep your
                                                    data secure. Operate with the flexibility and reliability that
                                                    modern healthcare demands.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="row row-gap-4 flex-row-reverse align-items-center mt-5 whydiff--card transition5s ">
                                        <div class="col-md-6">
                                            <div class="whydiffcol ">
                                                <img src="{{asset('public/landing_design/images/inventoryimg.png')}}"
                                                     alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid rounded-5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-style-two vstack tran3s px-2 px-lg-5 pb-4 pb-lg-0">
                                                <h2 data-aos="fade-up"
                                                    class="fw-bold mt-30 mb-25 d-flex align-items-center gap-2">
                                                    <ion-icon name="layers-outline" class="fs-3 shadow p-3 rounded-5">
                                                    </ion-icon>Easy Inventory
                                                    Management
                                                </h2>
                                                <p data-aos="fade-up" class="mb-20">Streamline your inventory with easy
                                                    tracking,
                                                    automated reorders, and low-stock alerts. Reduce manual errors
                                                    and ensure your shelves are always stocked. With our system,
                                                    managing your pharmacy's inventory has never been simpler.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row row-gap-4 align-items-center mt-5 whydiff--card transition5s ">
                                        <div class="col-md-6">
                                            <div class="whydiffcol">
                                                <img src="{{asset('public/landing_design/images/reportimg.png')}}"
                                                     alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid rounded-5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-style-two vstack tran3s px-2 px-lg-5 pb-4 pb-lg-0">
                                                <h2 data-aos="fade-up"
                                                    class="fw-bold mt-30 mb-25 d-flex align-items-center gap-2">
                                                    <ion-icon name="analytics-outline"
                                                        class="fs-3 shadow p-3 rounded-5"></ion-icon>Customizable
                                                    Reports & Analytics
                                                </h2>
                                                <p data-aos="fade-up" class="mb-20">Generate tailored reports and
                                                    insights with our
                                                    flexible analytics tools. Track performance, analyze trends, and
                                                    make data-driven decisions to enhance your pharmacy's
                                                    operations. Customize reports to fit your specific needs and
                                                    objectives.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="row row-gap-4 align-items-center mt-5 flex-row-reverse whydiff--card transition5s ">
                                        <div class="col-md-6">
                                            <div class="whydiffcol ">
                                                <img src="{{asset('public/landing_design/images/homeDelivery.png')}}"
                                                     alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid rounded-5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-style-two vstack tran3s px-2 px-lg-5 pb-4 pb-lg-0">
                                                <h2 data-aos="fade-up"
                                                    class="fw-bold mt-30 mb-25 d-flex align-items-center gap-2">
                                                    <ion-icon name="cube-outline" class="fs-3 shadow p-3 rounded-5">
                                                    </ion-icon>Deliver your customers at doorstep
                                                </h2>
                                                <p data-aos="fade-up" class="mb-20">Deliver your customers' orders right
                                                    to their doorstep with our seamless and efficient delivery services.
                                                    Ensure timely and accurate delivery with real-time tracking and
                                                    updates. Enhance customer satisfaction by providing convenience and
                                                    reliability in every order.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
          $settingData = App\Models\Setting::first();
        ?>
        <section class="section_margin videosec">
            <div class="container">
                <div class="containerovrflo overflow-hidden rounded-4">
                        <iframe width="100%" height="100%" class="rounded-4"
                        src="{{ isset($settingData->video) ? $settingData->video . '?controls=0&rel=0&autoplay=1&mute=1' : '' }}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        <div class="containerovrflo overflow-hidden rounded-4">
                </div>
                </div>
            </div>
        </section>
        <!-- <div class="bannersection1 bannersection section_margin mb-0">
            <div class="container">
                <div class="bannertext">
                    <h1 class="fw-bold">couldn't see what you're looking for?</h1>
                    <p class="">Experience our pharmacy software firsthand. Book a demo today and see how it can transform your operations!</p>
                    <a href="book-training.php" class="btn btnbook btn-outline-light mt-4 px-5">book demo</a>
                </div>
            </div>
        </div> -->
        <section class="expdiffsec section_margin px-2">
            <div class="expdiffdivmain container bg-theme rounded-5">
                <div class=" expdiffdiv p-5 text-center px-0 px-sm-5 position-relative z-1 text-white">
                    <h2 class="fw-bold">couldn't see what you're looking for?</h2>
                    <p class="">Experience our pharmacy software firsthand. Book a demo today and see how it can
                        transform your operations!</p>
                    @if(isset($subscriptioPlan[0]) && isset($subscriptioPlan[0]->id))
                    <a href="{{ route('book.training.index', ['id' => $subscriptioPlan[0]->id]) }}"
                        class="btn btnbook btn-outline-light mt-4 px-5">
                        <ion-icon name="call-outline"></ion-icon> Book demo
                    </a>
                    @else
                    <a href="" class="btn btnbook btn-outline-light mt-4 px-5">
                        <ion-icon name="call-outline"></ion-icon> Book demo
                    </a>
                    @endif
                </div>
            </div>
        </section>
        <section class="section section_margin whatissec">
            <div class="container">
                <div class="whatweare">
                    <div class="title-block text-center">

                        <h2>
                            why is <span class="span-theme">pharma 24*7</span> the most efficient and value for money
                            solution in the
                            pharmacy ecosystem?

                        </h2>
                        <p class="f">Your Comprehensive Guide to 24*7 Pharmaceutical Services</p>
                    </div>
                    <div class="row justify-content-lg-between align-items-center justify-content-center row-gap-4">
                        <div class="col-lg-6 col-sm-6">
                            <div class="whatwearecontentdiv whatwearecontimg rounded-3 overflow-hidden">
                                <img src="{{asset('public/landing_design/images/whyisimg.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}"
                                    class="img-fluid">
                            </div>
                        </div>
                        <div class="col-lg-5 col-12">
                            <div class="whatwearecontentdiv ">
                                <div class="whatwearediv">
                                    <p data-aos="fade-up" class="mb-4 aos-init aos-animate">Our Basic, Advanced and pro
                                        offerings ensure you have everything in your kitty to smarty and
                                        efficiently manage your entire pharmacy through our digital tools. Our advanced
                                        offerings include all
                                        major pharmacy features required to make your pharmacy 100% digital. No need to
                                        buy costly
                                        additional offerings.
                                    </p>
                                    <p data-aos="fade-up" class="mb-4 aos-init aos-animate">With our Advanced offerings,
                                        you gain access to all the essential pharmacy features required to achieve
                                        complete digital transformation. This comprehensive approach means you won't
                                        have to invest in costly add-ons, ensuring that you have everything you need to
                                        run your pharmacy smoothly and effectively.
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- <section class="benif py-0 py-md-5 section_margin tabfeature--sc">
            <div class="container">
                <div class="featuretabcon--div">
                    <div class="title-block text-center">

                        <h2>
                            why is <span class="span-theme">pharma 24*7?</span> is the Best Value and <br> Most Efficient Solution in Pharmacy?
                        </h2>
                        <p>Our Free, Basic, and Advanced offerings provide all the essential tools to efficiently manage your pharmacy, making it 100% digital without costly add-ons.</p>
                    </div>
                    <div class="benifdiv00">
                        <div class="row row-gap-4 justify-content-">
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv mb-3 d-flex align-items-center gap-3">
                                            <ion-icon name="people-circle-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            <h5 class="fw-bold mb-0">User-Friendly Interface</h5>
                                        </div>
                                        <p class="fw-semibold">Pharma24*7 offers a simple, intuitive platform that's easy to use without requiring tech expertise.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv mb-3 d-flex align-items-center gap-3">
                                            <ion-icon name="cash-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            <h5 class="fw-bold mb-0">Cost-Effective Solution</h5>
                                        </div>
                                        <p class="fw-semibold">Our software offers premium features at an affordable price, delivering exceptional value.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv mb-3 d-flex align-items-center gap-3">
                                            <ion-icon name="checkmark-circle-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            <h5 class="fw-bold mb-0">Easy to Understand Offerings</h5>
                                        </div>
                                        <p class="fw-semibold">We offer cost-effective solutions with our smart basic and advanced plans, unlike others who charge heavily for extra features.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv mb-3 d-flex align-items-center gap-3">
                                            <ion-icon name="git-compare-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            <h5 class="fw-bold mb-0">Customer Retention</h5>
                                        </div>
                                        <p class="fw-semibold">Enabling online orders helps retain customers and fosters long-term loyalty to your pharmacy.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv mb-3 d-flex align-items-center gap-3">
                                            <ion-icon name="shield-checkmark-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            <h5 class="fw-bold mb-0">Reliability</h5>
                                        </div>
                                        <p class="fw-semibold">Our software is designed to be reliable and secure, ensuring your data is protected.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv mb-3 d-flex align-items-center gap-3">
                                            <ion-icon name="headset-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            <h5 class="fw-bold mb-0">Customer Support</h5>
                                        </div>
                                        <p class="fw-semibold">Our team is here to provide you with exceptional customer service, guiding you through every step of the way.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->

        <!-- dark colorrrrrr -->
        <section class="benif section_margin tabfeature--sc">
            <div class="container">
                <div class="featuretabcon--div">
                    <div class="title-block text-center text-capitalize">
                        <h2>
                            our features
                        </h2>
                    </div>
                    <div class="benifdiv00">
                        <div class="row row-gap-4 justify-content-">
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden"
                                    style="background-color: #600c3b;color: #fff">
                                    <div class="servicecoldiv00 px-2">
                                        <div
                                            class="align-items-center align-items-sm-start align-items-md-center d-flex flex-sm-column flex-row flex-md-row gap-3 ioniconsdfiv mb-3">
                                            <ion-icon name="people-circle-outline"
                                                class="fs-3 shadow p-3 bg-white text rounded-5 md hydrated" role="img">
                                            </ion-icon>
                                            <span style="font-size: 22px;" class="fw-bold mb-0">User-Friendly Interface</span>
                                        </div>
                                        <p class="fw-semibold" style="color: #ffffffb3;">Pharma24*7 offers a simple,
                                            intuitive platform that's easy to use without requiring tech expertise.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden"
                                    style="background-color: #170748;color: #fff">
                                    <div class="servicecoldiv00 px-2">
                                        <div
                                            class="align-items-center align-items-sm-start align-items-md-center d-flex flex-sm-column flex-row flex-md-row gap-3 ioniconsdfiv mb-3">
                                            <ion-icon name="cash-outline"
                                                class="fs-3 shadow p-3 bg-white text rounded-5 md hydrated" role="img">
                                            </ion-icon>
                                           <span style="font-size: 22px;" class="fw-bold mb-0">Cost-Effective Solution</span>
                                        </div>
                                        <p class="fw-semibold" style="color: #ffffffb3;">Our software offers premium
                                            features at an affordable price, delivering exceptional value.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden"
                                    style="background-color: #0c2e60;color: #fff">
                                    <div class="servicecoldiv00 px-2">
                                        <div
                                            class="align-items-center align-items-sm-start align-items-md-center d-flex flex-sm-column flex-row flex-md-row gap-3 ioniconsdfiv mb-3">
                                            <ion-icon name="checkmark-circle-outline"
                                                class="fs-3 shadow p-3 bg-white text rounded-5 md hydrated" role="img">
                                            </ion-icon>
                                            <span style="font-size: 22px;" class="fw-bold mb-0">Easy to Understand Offerings</span>
                                        </div>
                                        <p class="fw-semibold" style="color: #ffffffb3;">We offer cost-effective
                                            solutions with our smart basic and advanced plans, unlike others who charge
                                            heavily for extra features.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden"
                                    style="background-color: #0c4160;color: #fff">
                                    <div class="servicecoldiv00 px-2">
                                        <div
                                            class="align-items-center align-items-sm-start align-items-md-center d-flex flex-sm-column flex-row flex-md-row gap-3 ioniconsdfiv mb-3">
                                            <ion-icon name="git-compare-outline"
                                                class="fs-3 shadow p-3 bg-white text rounded-5 md hydrated" role="img">
                                            </ion-icon>
                                            <span style="font-size: 22px;" class="fw-bold mb-0">Customer Retention</span>
                                        </div>
                                        <p class="fw-semibold" style="color: #ffffffb3;">Enabling online orders helps
                                            retain customers and fosters long-term loyalty to your pharmacy.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden"
                                    style="background-color: #0c5460;color: #fff">
                                    <div class="servicecoldiv00 px-2">
                                        <div
                                            class="align-items-center align-items-sm-start align-items-md-center d-flex flex-sm-column flex-row flex-md-row gap-3 ioniconsdfiv mb-3">
                                            <ion-icon name="shield-checkmark-outline"
                                                class="fs-3 shadow p-3 bg-white text rounded-5 md hydrated" role="img">
                                            </ion-icon>
                                            <span style="font-size: 22px;" class="fw-bold mb-0">Reliability</span>
                                        </div>
                                        <p class="fw-semibold" style="color: #ffffffb3;">Our software is designed to be
                                            reliable and secure, ensuring your data is protected.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden"
                                    style="background-color: #0c603d;color: #fff">
                                    <div class="servicecoldiv00 px-2">
                                        <div
                                            class="align-items-center align-items-sm-start align-items-md-center d-flex flex-sm-column flex-row flex-md-row gap-3 ioniconsdfiv mb-3">
                                            <ion-icon name="headset-outline"
                                                class="fs-3 shadow p-3 bg-white text rounded-5 md hydrated" role="img">
                                            </ion-icon>
                                            <span style="font-size: 22px;" class="fw-bold mb-0">Customer Support</span>
                                        </div>
                                        <p class="fw-semibold" style="color: #ffffffb3;">Our team is here to provide you
                                            with exceptional customer service, guiding you through every step of the
                                            way.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="tstmnlsec tabfeature--sc  section_margin">
            <div class="container">
                <div class="googlereviewdiv  overflow-hidden">
                    <div class="container">
                        <div class="googlediv00">
                            <div class="title-block text-center">
                                 <span style="font-size: 22px;" class="fw-bold"><span class="span-theme">Hear from Our Satisfied Clients</span></span>
                                <h2>Customer Testimonials</h2>
                            </div>
                            <!-- Swiper Slider -->
                            <div class="swiper googleslider position-relative">
                                <div class="swiper-wrapper">
                                    <!-- Slide 1 -->
                                    <div class="swiper-slide">
                                        <div class="googlereviewcard">
                                            <div class="googleuserimg d-flex gap-3 align-items-center">
                                                <div class="gogleimg00img">
                                                    <img src="{{asset('public/landing_design/images/user2.avif')}}"
                                                         alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                                </div>
                                                <div class="googleusernamestardiv">
                                                    <strong class="usernamego">Rajesh P., Pharmacy Owner</strong>
                                                    <div class="starsgoogle">
                                                        <i class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="googlereviewdesc">
                                                <p>Pharma24*7 has transformed our billing process. It's easy to use and
                                                    has significantly reduced our billing errors.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 2 -->
                                    <div class="swiper-slide">
                                        <div class="googlereviewcard">
                                            <div class="googleuserimg d-flex gap-3 align-items-center">
                                                <div class="gogleimg00img">
                                                    <img src="{{asset('public/landing_design/images/user2.avif')}}"
                                                         alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                                </div>
                                                <div class="googleusernamestardiv">
                                                    <strong class="usernamego">Anita K., Clinic Manager</strong>
                                                    <div class="starsgoogle">
                                                        <i class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="googlereviewdesc">
                                                <p>The customer support team is outstanding! They helped us transition
                                                    smoothly and are always there when we need them.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 3 -->
                                    <div class="swiper-slide">
                                        <div class="googlereviewcard">
                                            <div class="googleuserimg d-flex gap-3 align-items-center">
                                                <div class="gogleimg00img">
                                                    <img src="{{asset('public/landing_design/images/user2.avif')}}"
                                                         alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                                </div>
                                                <div class="googleusernamestardiv">
                                                    <strong class="usernamego">Deepak S., Hospital Administrator</strong>
                                                    <div class="starsgoogle">
                                                        <i class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i><i
                                                            class="fa-solid fa-star" style="color: #ffd43b;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="googlereviewdesc">
                                                <p>Our inventory management has never been better. Pharma24*7 keeps
                                                    track of everything seamlessly.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Navigation -->
                                <div class="swiper-button-prevv swprbtn position-absolute top-50 start-0 px-3 z-1">
                                    <i class="fa-solid fa-arrow-left-long"></i>
                                </div>
                                <div class="swiper-button-nextt swprbtn position-absolute top-50 end-0 px-3 z-1">
                                    <i class="fa-solid fa-arrow-right-long"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="pricesec section_margin">
            <div class="container">
                <div class="pricemnaun px-md-5">
                    <div class="title-block text-center">
                        <span style="font-size: 22px;" class="fw-bold"><span class="span-theme">
                                Selecting the Best Pricing Plan for Your Pharmacy
                            </span></span>
                        <h2>Pricing Plans</h2>
                    </div>
                    <div class="planbasiccarddiv">
                        <div class="plancarddivmain">
                            <div class="row row-gap-3">
                                @if(isset($subscriptioPlan))
                                @foreach($subscriptioPlan as $list)
                                <div class="col-md-4 mt-0">
                                    <div class="position-relative">
                                        <h3 class="fw-bold circle-cir bg-theme">
                                            {{ isset($list->name) ? $list->name : ""}}</h3>
                                        <div class="plancarddiv pt-5">
                                            <div class="plancard text-center">
                                                <div class="priceheaddiv">
                                                    <h4 class="fw-bold mb-0">
                                                        ₹{{isset($list->annual_price) ? $list->annual_price :""}}/Year
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="btnsdivfornextpage d-flex flex-wrap gap-3 justify-content-center mt-4">
                            <a href="{{route('pricing.index')}}" class="clicklinkpage btn theme-btn">detail pricing</a>
                            @if(isset($subscriptioPlan[0]) && isset($subscriptioPlan[0]->id))
                            <a href="{{ route('book.training.index', ['id' => $subscriptioPlan[0]->id]) }}"
                                class="clicklinkpage btn theme-btn">
                                <ion-icon name="call-outline"></ion-icon> Book demo
                            </a>
                            @else
                            <a href="" class="clicklinkpage btn theme-btn">
                                <ion-icon name="call-outline"></ion-icon> Book demo
                            </a>
                            @endif

                        </div>
                    </div>
                    <!-- <div class="table-responsive">
                        <table class="table pricetable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center" width="22%">
                                        <div class="priceheaddiv">
                                            <h5 class="fw-bold">Basic Plan</h5>
                                            <h2 class="fw-bold span-theme">$25/month</h2>
                                        </div>
                                    </th>
                                    <th class="text-center" width="22%">
                                        <div class="priceheaddiv">
                                            <h5 class="fw-bold">Advanced Plan</h5>
                                            <h2 class="fw-bold span-theme">$50/month</h2>
                                        </div>
                                    </th>
                                    <th class="text-center" width="22%">
                                        <div class="priceheaddiv">
                                            <h5 class="fw-bold">Pro Plan</h5>
                                            <h2 class="fw-bold span-theme">$100/month</h2>
                                        </div>
                                    </th>
                                    <th class="text-center" width="22%">
                                        <div class="priceheaddiv">
                                            <h5 class="fw-bold">Enterprise Plan</h5>
                                            <h2 class="fw-bold span-theme">Custom pricing</h2>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Patient Management</strong></td>
                                    <td class="text-center">Basic patient profiles and history</td>
                                    <td class="text-center">All Basic Plan Features</td>
                                    <td class="text-center">All Advanced Plan Features</td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>Billing & Invoicing</strong></td>
                                    <td class="text-center">Standard billing and invoicing</td>
                                    <td class="text-center">All Basic Plan Features</td>
                                    <td class="text-center">Advanced billing for complex scenarios and insurance claims
                                    </td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>Inventory Management</strong></td>
                                    <td class="text-center">Basic inventory tracking</td>
                                    <td class="text-center">Advanced inventory management with batch and expiry date
                                        tracking</td>
                                    <td class="text-center">Inventory forecasting and predictive management</td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>Report Generation</strong></td>
                                    <td class="text-center">Standard financial and inventory reports</td>
                                    <td class="text-center">Customizable and detailed reports</td>
                                    <td class="text-center">Advanced analytics and insights</td>
                                    <td class="text-center">Advanced analytics and business intelligence</td>
                                </tr>
                                <tr>
                                    <td><strong>Support</strong></td>
                                    <td class="text-center">Email support</td>
                                    <td class="text-center">Email and phone support</td>
                                    <td class="text-center">Priority support via email, phone, and chat</td>
                                    <td class="text-center">24/7 dedicated support with a personal account manager</td>
                                </tr>
                                <tr>
                                    <td><strong>User Access</strong></td>
                                    <td class="text-center">Up to 3 users</td>
                                    <td class="text-center">Up to 10 users</td>
                                    <td class="text-center">Up to 20 users</td>
                                    <td class="text-center">Unlimited users</td>
                                </tr>
                                <tr>
                                    <td><strong>Data Backup</strong></td>
                                    <td class="text-center">Weekly automated backups</td>
                                    <td class="text-center">Daily automated backups</td>
                                    <td class="text-center">Hourly automated backups</td>
                                    <td class="text-center">Real-time automated backups</td>
                                </tr>
                                <tr>
                                    <td><strong>Security</strong></td>
                                    <td class="text-center">Basic data encryption</td>
                                    <td class="text-center">Advanced data encryption</td>
                                    <td class="text-center">Enhanced security protocols</td>
                                    <td class="text-center">Enterprise-grade security features</td>
                                </tr>
                                <tr>
                                    <td><strong>Access</strong></td>
                                    <td class="text-center">Web access via browser</td>
                                    <td class="text-center">Web and mobile app access</td>
                                    <td class="text-center">Web, mobile app, and offline access</td>
                                    <td class="text-center">Web, mobile app, offline access, and custom API access</td>
                                </tr>
                                <tr>
                                    <td><strong>Appointment Scheduling</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Integrated appointment calendar</td>
                                    <td class="text-center">All Advanced Plan Features</td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>E-Prescriptions</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Electronic prescription management</td>
                                    <td class="text-center">All Advanced Plan Features</td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>Multi-Location Support</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Manage multiple pharmacy locations</td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>Custom Fields</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Ability to add custom fields in patient and inventory
                                        management</td>
                                    <td class="text-center">All Pro Plan Features</td>
                                </tr>
                                <tr>
                                    <td><strong>Integration</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Integration with third-party EHR and POS systems</td>
                                    <td class="text-center">Custom integrations with existing systems</td>
                                </tr>
                                <tr>
                                    <td><strong>Analytics</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Advanced analytics and insights</td>
                                    <td class="text-center">In-depth analytics and business intelligence</td>
                                </tr>
                                <tr>
                                    <td><strong>Compliance</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Enhanced compliance management</td>
                                </tr>
                                <tr>
                                    <td><strong>Data Migration</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">Assistance with data migration from other systems</td>
                                </tr>
                                <tr>
                                    <td><strong>Training</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">On-site training for staff</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="addonsdiv mt-5"> 
                        <h5 class="fw-bold text-center">Add-Ons (Available for All Plans)</h5>
                        <div class="benifdiv00 mt-4">
                            <div class="d-grid grid-template-5 pb-sm-4 pb-2 px-md-4">
                                <div class="gridcard">
                                    <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="notifications-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            </div>
                                            <p class="fw-bold d-flex align-items-center gap-1">SMS/Email Notifications</p>
                                            <h4 class="fw-bold">$10 per month</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="gridcard">
                                    <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="person-add-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            </div>
                                            <p class="fw-bold d-flex align-items-center gap-1">Additional Users</p>
                                            <h4 class="fw-bold">$5 per user per month</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="gridcard">
                                    <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="document-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            </div>
                                            <p class="fw-bold d-flex align-items-center gap-1">Custom Reporting</p>
                                            <h4 class="fw-bold"> $20 per month</h4>

                                        </div>
                                    </div>
                                </div>
                                <div class="gridcard">
                                    <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="document-text-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            </div>
                                            <p class="fw-bold d-flex align-items-center gap-1"> Telemedicine Module</p>
                                            <h4 class="fw-bold"> $30 per month</h4>

                                        </div>
                                    </div>
                                </div>
                                <div class="gridcard">
                                    <div class="servicecoldiv h-100 serviceindustryslider transition5s text-start p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="star-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" role="img"></ion-icon>
                                            </div>
                                            <p class="fw-bold d-flex align-items-center gap-1">Loyalty Program Integration</p>
                                            <h4 class="fw-bold"> $15 per month</h4>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="fw-semibold text-end px-4">for more information <a href="pricing.php" class="clickherelink">Click here.</a></p>
                        </div>
                    </div> -->
                </div>
            </div>
        </section>
        <!-- <section class="fAQsec section_margin">
            <div class="container">
                <div class="faqmain">
                    <div class="faqdiv">
                        <div class="title-block text-center">
                            <h5 class="fw-bold">
                                <span class="span-theme">
                                    Frequently Asked Questions
                                </span>
                            </h5>
                            <h2>Your Pharmacy Management Queries Answered</h2>
                        </div>
                        <div class="faqaccordion">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Q: Is my data safe with Pharma 24*7?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Yes, we use secure encryption and regular backups.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Q: Can I use the software on my phone?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Yes, it works on both desktop and mobile devices.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Q: Is there a free trial?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Yes, you can try Pharma 24*7 for free.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                            Q: What is Pharma24*7?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Pharma24*7 is a cloud-based medical billing software
                                            designed to streamline billing processes for pharmacies and healthcare
                                            providers.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFive" aria-expanded="false"
                                            aria-controls="collapseFive">
                                            Q: Is Pharma24*7 suitable for my small pharmacy?
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Yes, Pharma24*7 is scalable and can be customized to fit
                                            the needs of pharmacies of all sizes.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            Q: How does Pharma24*7 manage patient records?
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Pharma24*7 securely stores and manages patient records,
                                            ensuring easy access and retrieval.
                                        </div>
                                    </div>
                                </div>
                                <div class="viewallfaqbtn text-center">
                                    <a href="faq.php" class="btn theme-btn-blue">view all</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->

        <!-- <section class="sec_mar enq_section position-relative overflow-hidden" id="enq_sec_id">
            <div class="bgimgdiv position-absolute z-0 w-100 top-0 h-100">
                <img src="assets/images/low-angle-shot-tall-glass-buildings-blue-cloudy-sky.png" alt="" class="img-fluid w-100 h-100 object-fit-cover">
            </div>
            <div class="container-fluid overflow-hidden p-0">
                <div class="enqdivmain position-relative px-3 py-5 z-1">
                    <div class="col-lg-4 col-12 p-5 enqformdiv m-auto">
                        <div class="getquoteform">
                            <h4 class="text-center text-white mb-4 fw-bold">Enquiry</h4>
                            <form method="post" id="contact_form" novalidate="novalidate">
                                <input type="hidden" name="product_id" id="product_id" value="43">
                                <div class="row row-gap-3">
                                    <div class="col-md-6">
                                        <div class="qetquotefield">
                                            <input type="text" name="fname" id="fname" placeholder="first name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="qetquotefield">
                                            <input type="text" name="lname" id="lname" placeholder="last name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="qetquotefield">
                                            <input type="email" name="email" placeholder="Email" id="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="qetquotefield">
                                            <input type="tel" name="phone" placeholder="Phone" id="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="qetquotefield">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected="">select service</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="qetquotefield">
                                            <textarea name="message" placeholder="Message" id="message" cols="30" rows="7"></textarea>
                                        </div>
                                    </div>
                                    <div class="enqbtn text-center">
                                        <button id="contact" class="btn enqbtn00 btn-outline-light py-2 fw-medium text-capitalize contact_btn" data-id="43">send message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- 
        <section class="section_margin blog-sec">
            <div class="container">
                <div class="blogsectiondiv section_margin">
                    <h3 class="text-center">blog</h3>
                    <div class="blogdiv">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="blogimgdiv">
                                    <div class="blogimgmaindiv">
                                        <img src="images/512.jpg" class="img-fluid" alt="">
                                    </div>
                                    <div class="blogpostdate">
                                        <p>september 29, 2023</p>
                                    </div>
                                    <div class="blogtitle">
                                        <h3>lorem ipsum dolor sit amett</h3>
                                        <p class="author">by lorem lorem</p>
                                    </div>
                                    <div class="blogshortdesc">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim ratione
                                            officiis ad similique assumenda est blanditiis, voluptates consectetur
                                            ducimus esse provident odio placeat voluptatem. Facilis facere velit
                                            rerum cum quod? Lorem, ipsum dolor sit amet consectetur adipisicing
                                            elit. Voluptate reprehenderit aut facilis fugit enim? Ab neque eos
                                            nostrum voluptatibus ut et numquam doloremque odit, culpa odio ullam
                                            voluptatum consequatur. Reiciendis?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="blogimgdiv">
                                    <div class="blogimgmaindiv">
                                        <img src="images/512.jpg" class="img-fluid" alt="">
                                    </div>
                                    <div class="blogpostdate">
                                        <p>september 29, 2023</p>
                                    </div>
                                    <div class="blogtitle">
                                        <h3>lorem ipsum dolor sit amett</h3>
                                        <p class="author">by lorem lorem</p>
                                    </div>
                                    <div class="blogshortdesc">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim ratione
                                            officiis ad similique assumenda est blanditiis, voluptates consectetur
                                            ducimus esse provident odio placeat voluptatem. Facilis facere velit
                                            rerum cum quod? Lorem, ipsum dolor sit amet consectetur adipisicing
                                            elit. Voluptate reprehenderit aut facilis fugit enim? Ab neque eos
                                            nostrum voluptatibus ut et numquam doloremque odit, culpa odio ullam
                                            voluptatum consequatur. Reiciendis?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="blogimgdiv">
                                    <div class="blogimgmaindiv">
                                        <img src="images/512.jpg" class="img-fluid" alt="">
                                    </div>
                                    <div class="blogpostdate">
                                        <p>september 29, 2023</p>
                                    </div>
                                    <div class="blogtitle">
                                        <h3>lorem ipsum dolor sit amett</h3>
                                        <p class="author">by lorem lorem</p>
                                    </div>
                                    <div class="blogshortdesc">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim ratione
                                            officiis ad similique assumenda est blanditiis, voluptates consectetur
                                            ducimus esse provident odio placeat voluptatem. Facilis facere velit
                                            rerum cum quod? Lorem, ipsum dolor sit amet consectetur adipisicing
                                            elit. Voluptate reprehenderit aut facilis fugit enim? Ab neque eos
                                            nostrum voluptatibus ut et numquam doloremque odit, culpa odio ullam
                                            voluptatum consequatur. Reiciendis?</p>
                                    </div>
                                </div>
                            </div>

                            <div class="viewallbtn text-center">
                                <a href class="btn theme-btn">view all</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
    </main>

    @include('front.footer')