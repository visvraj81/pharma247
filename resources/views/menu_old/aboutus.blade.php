@include('front.header')

<body class="about">
    @include('front.menu')
    <main class="abtmain">
        <!-- <div class="breadcrumbsdiv py-5">
            <nav aria-label="breadcrumb" class=" py-5 my-3">
                <ol class="breadcrumb justify-content-center py-5">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h2 class="fw-bold m-0 text-white border-bottom">about us</h2>
                    </li>
                </ol>
            </nav>
        </div> -->
        <section class="abt--content section_margin">
            <div class="container">
                <div class="abt--para">
                    <div class="row justify-content-between row-gap-4">
                        <div class="col-lg-5">
                            <div class="whatwearecontentdiv whatwearecontimg">
                                <img src="{{asset('public/landing_design/images/aboutimg.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="w-100 img-fluid">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="whatwearecontentdiv ">
                                <div class="whatwearediv">
                                    <div class="title-block mb-3">
                                        <h1>
                                            About <span class="span-theme">pharma 24*7</span>
                                        </h1>
                                    </div>
                                    <p data-aos="fade-up" class="mb-4 aos-init aos-animate">At <span
                                            class="fs-5 fw-bold span-blue">Pharma
                                            24*7</span>, we understand the challenges that pharmacy owners face in today’s fast-paced healthcare environment. That’s why we’ve developed a cloud-based software solution designed to make your job easier and more efficient. Whether you're managing inventory, tracking sales, or ensuring compliance, Pharma247 has everything you need to streamline your operations.</p>
                                    <p data-aos="fade-up" class="aos-init aos-animate">Founded with a vision to simplify the complexities of pharmacy management, Pharma 24*7 offers a comprehensive, cloud-based pharmacy billing software accessible on both desktop and mobile apps. Our software includes robust inventory management features, ensuring that pharmacies can easily track stock levels, manage orders, and reduce waste.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section_margin fundamensec col-xl-7 col-md-12 m-auto">
            <div class="container">
                <div class="fundamen position-relative overflow-hidden rounded-3 px-3 pb-3">
                    <div class="title-block text-center">
                        <h2 class="fw-bold" data-aos="fade-up"><span class="span-theme">
                                Three Core Philosophical Pillars
                            </span></h2>
                        <h2 data-aos="fade-up">Guiding You to Milestones</h2>
                    </div>

                    <div class="fundamendiff">
                        <p data-aos="fade-up" class="aos-init aos-animate mb-3">Founded with a vision to simplify the complexities of pharmacy management, Pharma 24*7 offers a comprehensive, cloud-based pharmacy billing software accessible on both desktop and mobile apps. Our software includes robust inventory management features, ensuring that pharmacies can easily track stock levels, manage orders, and reduce waste.</p>
                        <p data-aos="fade-up" class="aos-init aos-animate mb-3">We understand the complexities that pharmacies face in managing inventory, billing, and customer relationships, and we strive to provide seamless, cloud-based solutions that allow them to focus on their true purpose—delivering exceptional care to their patients.</p>
                        <p data-aos="fade-up" class="aos-init aos-animate mb-3">Our commitment is to support the pharmaceutical community in its journey toward digital transformation. We believe that by providing intuitive, affordable, and scalable software, we can help pharmacies optimize their operations and meet the evolving needs of today’s healthcare landscape. With a passionate team and an unwavering dedication to excellence, we are proud to be a trusted partner to pharmacies, helping them navigate the future of healthcare with confidence.</p>
                        <p data-aos="fade-up" class="aos-init aos-animate">Together, we’re advancing pharmacy management for a healthier tomorrow.</p>
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
        <section class="section_margin fundamensec">
            <div class="container">
                <div class="fundamen position-relative overflow-hidden rounded-3 px-3 pb-3">
                    <div class="title-block text-center">
                        <h2 class="fw-bold"><span class="span-theme">Your Trusted Pharmacy Software Solution</span></h2>
                        <h2>Why Choose Pharma247?</h2>
                        <p>It’s not just about value for money and unique features that our platform offers but we care about the end users even more.</p>
                    </div>

                    <div class="fundamendiff">
                        <div class="row row-gap-4 justify-content-">
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">

                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="people-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">User-Friendly Interface</p>
                                        </div>
                                        <p>
                                            Unlike other software that can be complicated and cumbersome, Pharma247 is designed to be simple and intuitive. You don’t need to be a tech expert to use our platform effectively.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="cash-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Cost-Effective Solution</p>
                                        </div>
                                        <p>We believe in providing value without breaking the bank. Our software-as-a-service model ensures that you get top-notch features at an economical price.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="checkmark-done-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Customer Retention</p>
                                        </div>
                                        <p>By offering your customers the ability to order online, you can keep them coming back to your pharmacy, ensuring long-term loyalty.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="shield-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Reliability</p>
                                        </div>
                                        <p>Our software is designed to be reliable and secure, ensuring your data is protected.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="brush-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Customization</p>
                                        </div>
                                        <p>We understand that every pharmacy is unique, which is why our software can be tailored to meet your specific needs.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="headset-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Customer Support</p>
                                        </div>
                                        <p>Our team is here to provide you with exceptional customer service, guiding you through every step of the way.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="expdiffsec section_margin">
            <div class="expdiffdivmain container bg-theme rounded-5">
                <div class=" expdiffdiv p-5 position-relative z-1 text-white">
                    <div class="align-items-center row row-gap-4 titlediv">
                        <div class="col-md-6">
                            <h3 class="fw-bold">Experience the Pharma247 Difference</h3>
                            <a href="" class="btn btn-outline-light fs-6 px-5 text-capitalize fw-bold py-2 mt-3">check demo</a>
                        </div>
                        <div class="col-md-6">
                            <p class="">Join the growing number of pharmacies that are transforming their operations with Pharma247. Our
                                cloud-based solution is here to support you in running a more efficient, customer-focused, and
                                profitable business. Discover how Pharma247 can make a difference in your pharmacy today.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- <section class="section_margin fundamensec">
            <div class="container">
                <div class="fundamen position-relative overflow-hidden rounded-3 px-3 pb-3">
                    <div class="title-block text-center">
                        <h5 class="fw-bold"><span class="span-theme">
                                Three Fundamental Approaches For
                            </span></h5>
                        <h2>Achieving Milestones</h2>
                    </div>
                    <div class="fundamendiff">
                        <div class="row row-gap-4 justify-content-">
                            <div class="col-md-4">
                                <div
                                    class="servicecoldiv h-100 serviceindustryslider transition5s text-center p-4 overflow-hidden">

                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="notifications-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Customer Centric
                                                Approach</p>
                                        </div>
                                        <p>
                                            Pharma 24*7's customer-centric approach ensures a seamless experience,
                                            prioritizing satisfaction and loyalty. We streamline pharmacy operations,
                                            making management efficient and hassle-free.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div
                                    class="servicecoldiv h-100 serviceindustryslider transition5s text-center p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="notifications-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Technology Oriented
                                                Approach</p>
                                        </div>
                                        <p>Pharma 24*7 leverages the latest technology through continuous research,
                                            ensuring seamless pharmacy operations. We focus on innovation and empowering
                                            pharmacies to stay ahead in a digital world.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div
                                    class="servicecoldiv h-100 serviceindustryslider transition5s text-center p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="notifications-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">Regular Innovation
                                                Approach</p>
                                        </div>
                                        <p>Pharma 24*7 focuses on regular innovation, continuously enhancing our
                                            pharmacy software with the latest advancements. This ensures pharmacies stay
                                            efficient, competitive, and future-ready.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- <section class="section_margin bg-theme-0 ourteamsec tabfeature--sc ">
            <div class="container">
                <div class="title-block text-center">
                    <h5 class="fw-bold"><span class="span-theme">
                            Meet Our Dedicated
                        </span></h5>
                    <h2>Pharma 24*7 Team</h2>
                </div>

                <div class="ourteamamin">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="overflow-hidden serviceindustryslider teamcard">
                                <div class="teamimg">
                                    <img src="{{asset('public/landing_design/images/team/2151734557.jpg')}}" alt="" class="img-fluid">
                                </div>
                                <div class="p-4 teamtxet">
                                    <h4 class="fw-bold">sagar patel</h4>
                                    <p class="mb-0">B.Pharma</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="overflow-hidden serviceindustryslider teamcard">
                                <div class="teamimg">
                                    <img src="{{asset('public/landing_design/images/team/2151734557.jpg')}}" alt="" class="img-fluid">
                                </div>
                                <div class="p-4 teamtxet">
                                    <h4 class="fw-bold">sagar patel</h4>
                                    <p class="mb-0">B.Pharma</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="overflow-hidden serviceindustryslider teamcard">
                                <div class="teamimg">
                                    <img src="{{asset('public/landing_design/images/team/2151734557.jpg')}}" alt="" class="img-fluid">
                                </div>
                                <div class="p-4 teamtxet">
                                    <h4 class="fw-bold">sagar patel</h4>
                                    <p class="mb-0">B.Pharma</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section_margin abthalfsec">
            <div class="container">
                <div class="abt--haldiv">
                    <div class="justify-content-around align-items-center row">
                        <div class="abthalfcol col-5">
                            <div class="abthalf--div title-block">
                                <h5 class="fw-bold"><span class="span-theme">
                                        Our Commitment to Excellence
                                    </span></h5>
                                <h2 class="">
                                    Empowering Pharmacies for the Future
                                </h2>
                            </div>
                        </div>
                        <div class="abthalfcol col-6">
                            <div class="abthalf--div">
                                <p>
                                    At Pharma 24*7, our legacy is built on a foundation of trust and a deep commitment
                                    to advancing pharmacy management. We understand the challenges pharmacies face in
                                    delivering quality healthcare and strive to provide innovative solutions that
                                    simplify operations. Our cloud-based software empowers pharmacies to manage
                                    inventory, billing, and customer relationships efficiently, ensuring they can focus
                                    on what matters most—serving their patients. With a dedicated team and a passion for
                                    excellence, we are proud to support the pharmaceutical community in their journey
                                    toward digital transformation and improved patient care.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="fAQsec section_margin">
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
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
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
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
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
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
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
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                            aria-expanded="false" aria-controls="collapseFour">
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
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                            aria-expanded="false" aria-controls="collapseFive">
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
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                            aria-expanded="false" aria-controls="collapseSix">
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

    </main>

    @include('front.footer')