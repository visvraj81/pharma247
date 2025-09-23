@include('front.header')

<body class="home">
    @include('front.menu')
    <main class="product ">
        <!-- <div class="breadcrumbsdivcc">
            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h2 class="fw-bold m-0">products & features</h2>
                    </li>
                </ol>
            </nav>
        </div> -->
        <!-- <div class="breadcrumbsdiv py-5">
            <nav aria-label="breadcrumb" class=" py-5 my-3">
                <ol class="breadcrumb justify-content-center py-5">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h2 class="fw-bold m-0 text-white border-bottom">products & features</h2>
                    </li>
                </ol>
            </nav>
        </div> -->
        <section class="section_margin abthalfsec">
            <div class="container">
                <div class="abt--haldiv">
                    <div class=" rrow">
                        <div class="abthalfcol">
                            <div class="abthalf--div title-block text-center">
                                <h1 class="mb-4" style="font-size: 28px;">
                                    Smart <span class="span-theme"> Pharmacy Solutions </span> for Medical Stores
                                </h1>
                                <p>
                                    Pharma24*7 offers a comprehensive suite of pharmacy management tools, including
                                    web-based software, chemist app, and a patient app, to streamline operations,
                                    improve customer relationships, and boost productivity.
                                </p>
                                <!-- <h5 class="fw-bold"><span class="span-theme">
                                        Try our training videos by clicking on each feature
                                    </span></h5> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="herosectionfhome">
            <div class="herohome container">
                <div class="swiper position-relative overflow-hidden">
                    <div class="swiper-slide mb-3">
                        <div class="swprslidemaindiv">
                            <div class="hometopbannerdiv">
                                <div class="containerr overflow-hidden">
                                    <div class="row align-items-center px-3 row-gap-3">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="hometopdivimg00 text-start mt-mb-0 rounded-2 overflow-hidden">
                                                <img src="{{asset('public/landing_design/images/pharma-dashboard.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="hometoptext text-start">
                                                <div class="title-block text-start mb-5">
                                                    <h2> <span class="span-theme"> Web-Based </span> Software for
                                                        Chemists </h2>
                                                    <p class="f">Manage all aspects of your pharmacy with our
                                                        cloud-based platform designed for medical stores.</p>
                                                </div>
                                                <h3 class="fw-bold" style="    font-size: 18px;">Key Features:</h3>
                                                <ul>
                                                    <li class="itms"><strong>Inventory Management:</strong> Easily track
                                                        stock levels and manage products across multiple locations.</li>
                                                    <li class="itms"><strong>Expiry Drug Management:</strong> Stay
                                                        informed of near-expiry drugs and process returns to suppliers
                                                        before deadlines.</li>
                                                    <li class="itms"><strong>Barcode Management:</strong> Quick scanning
                                                        for accurate inventory and sales management.</li>
                                                    <li class="itms"><strong>Purchase & Sales Billing:</strong>
                                                        Streamline sales and purchase processes, including returns.</li>
                                                    <li class="itms"><strong>GST-Compliant Billing:</strong>
                                                        Automatically calculate GST and create compliant invoices.</li>
                                                    <li class="itms"><strong>Reports & Analytics:</strong> Generate
                                                        detailed sales, inventory, and financial reports.</li>
                                                    <li class="itms"><strong>Role-Based Access:</strong> Secure system
                                                        with user-specific access levels.</li>
                                                </ul>

                                                <!-- <p>All for just <span class="span-theme fs-5 fw-bold">Rs 6/day</span>.</p> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="swprslidemaindiv">
                            <div class="hometopbannerdiv">
                                <div class="containerr overflow-hidden">
                                    <div class="row align-items-center flex-column-reverse flex-lg-row px-3">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="hometoptext text-start">
                                                <div class="title-block text-start mb-5">
                                                    <h2> <span class="span-theme"> Chemist App </h2>
                                                    <p class="f">A mobile-friendly solution for pharmacists to manage
                                                        their store operations on the go, with real-time sync to the
                                                        web-based software.</p>
                                                </div>
                                                 <h3 class="fw-bold" style="    font-size: 18px;">Key Features:</h5>
                                                <ul>
                                                    <li class="itms"><strong>Order Management:</strong> Receive and
                                                        fulfill orders from the Patient App instantly.</li>
                                                    <li class="itms"><strong>Stock Tracking:</strong> Monitor inventory
                                                        and expiry statuses from your phone.</li>
                                                    <li class="itms"><strong>Quick Billing:</strong> Process sales and
                                                        returns on the go.</li>
                                                    <li class="itms"><strong>Live Data Sync:</strong> Stay updated with
                                                        real-time information across devices.</li>
                                                </ul>


                                                <!-- <p>All for just <span class="span-theme fs-5 fw-bold">Rs 6/day</span>.</p> -->
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div
                                                class="hometopdivimg00 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                <img src="{{asset('public/landing_design/images/mobileapp.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="swprslidemaindiv">
                            <div class="hometopbannerdiv">
                                <div class="containerr overflow-hidden">
                                    <div class="align-items-center px-3 row">
                                        <div class="col-lg-6 col-md-12">
                                            <div
                                                class="hometopdivimg00 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                <img src="{{asset('public/landing_design/images/mobileapp.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="hometoptext text-start">
                                                <div class="title-block text-start mb-5">
                                                    <h2> <span class="span-theme"> Patient App </h2>
                                                    <p class="f">A convenient app for customers to order medicines from
                                                        nearby pharmacies with fast delivery.</p>
                                                </div>
                                                 <h3 class="fw-bold" style="font-size: 18px;">Key Features:</h5>
                                                <ul>
                                                    <li class="itms"><strong>Find Nearby Chemists:</strong> Locate and
                                                        order from the closest pharmacy using the Chemist App.</li>
                                                    <li class="itms"><strong>Order Tracking:</strong> Get real-time
                                                        updates on your orders.</li>
                                                    <li class="itms"><strong>Prescription Upload:</strong> Upload
                                                        prescriptions for easy order processing.</li>
                                                    <li class="itms"><strong>Fast Delivery:</strong> Receive medications
                                                        in under 15 minutes.</li>
                                                </ul>
                                                <!-- <p>All for just <span class="span-theme fs-5 fw-bold">Rs 6/day</span>.</p> -->
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
        <!-- <section class="section_margin enq_section position-relative overflow-hidden" id="enq_sec_id">
            <div class="bgimgdiv position-absolute z-0 w-100 top-0 h-100">
                <img src="assets/images/low-angle-shot-tall-glass-buildings-blue-cloudy-sky.png" alt="" class="img-fluid w-100 h-100 object-fit-cover">
            </div>
            <div class="container-fluid overflow-hidden p-0">
                <div class="enqdivmain position-relative px-3 py-5 z-1">
                    <div class="col-lg-4 col-12 p-5 enqformdiv m-auto">
                        <div class="getquoteform">
                            <h4 class="text-center text-white mb-4 fw-bold">Ready to Get Started? </h4>
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

        <section class="section_margin fundamensec herosectionhome py-5">
            <div class="container">
                <div class="fundamen position-relative overflow-hidden rounded-3 px-3 pb-3">
                    <div class="title-block text-center">
                        <!-- <h5 class="fw-bold"><span class="span-theme">Your Trusted Pharmacy Software Solution</span></h5> -->
                        <h2>Benefits of Pharma24*7</h2>
                    </div>

                    <div class="fundamendiff mb-4">


                        <div class="row row-gap-4 justify-content-">
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">

                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="people-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Reduce Time Spent on Data Entry</p>
                                        </div>
                                        <p class="">
                                            Automate repetitive tasks to focus more on your customers and business
                                            growth.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="cash-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                                style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Boost Efficiency & Productivity</p>
                                        </div>
                                        <p>Streamline operations with tools designed to make pharmacy management faster
                                            and easier.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="card-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                                style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Automated Payment Reconciliation</p>
                                        </div>
                                        <p>Pharma24*7 handles payment processes, improving accuracy and saving time.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="happy-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                                style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Improve Customer Relationships</p>
                                        </div>
                                        <p>Maintain detailed customer profiles, send medication reminders, and share
                                            invoices via WhatsApp or SMS.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="stats-chart-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Increase Sales with Loyalty Programs</p>
                                        </div>
                                        <p>Reward customers and encourage repeat visits with customizable loyalty
                                            programs.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="server-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Data-Driven Decisions</p>
                                        </div>
                                        <p>Analyze pharmacy performance with detailed reports for better planning and
                                            informed decision-making.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="shield-checkmark-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Faster & Accurate Billing</p>
                                        </div>
                                        <p>Speed up transactions and reduce errors, allowing for efficient customer
                                            service.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="thumbs-up-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Efficient Product Returns & Reallocation</p>
                                        </div>
                                        <p>Simplify the management of expired or damaged products with intuitive return
                                            processes.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="pie-chart-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Optimize Staff Performance</p>
                                        </div>
                                        <p>Manage staff performance easily with role-based access and individual
                                            reporting tools.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 ">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="shuffle-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Seamless Data Migration</p>
                                        </div>
                                        <p>Switch to Pharma24*7 easily with secure data transfer, ensuring minimal
                                            disruption to your operations.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section_margin fundamensec">
            <div class="container">
                <div class="fundamen position-relative overflow-hidden rounded-3 px-3 pb-3">
                    <div class="title-block text-center">
                        <!-- <h5 class="fw-bold"><span class="span-theme">Your Trusted Pharmacy Software Solution</span></h5> -->
                        <h2>Solutions for Retail Pharmacies</h2>
                        <p>Pharma24*7 provides complete pharmacy management solutions designed to simplify everyday
                            tasks, reduce manual work, and enhance customer experiences.</p>
                    </div>

                    <div class="fundamendiff ">
                        <div class="row row-gap-4 justify-content-">
                            <div class="col-md-4">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="receipt-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Smart Inventory & Order Management</p>
                                        </div>
                                        <p>Keep track of stock and customer orders with real-time updates to avoid
                                            shortages or overstock.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="reader-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Automated Accounting & GST Compliance</p>
                                        </div>
                                        <p>Automate complex financial processes with integrated accounting tools and
                                            generate GST-compliant invoices easily.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="speedometer-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Delivery Management</p>
                                        </div>
                                        <p>Ensure fast, reliable deliveries with an integrated system that tracks and
                                            fulfills orders efficiently.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="podium-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Comprehensive Reports & Analytics</p>
                                        </div>
                                        <p>Make data-driven decisions with detailed sales, stock, and financial
                                            insights.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="phone-portrait-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Custom Pharmacy App</p>
                                        </div>
                                        <p>Build a stronger brand by offering a personalized pharmacy app for your
                                            customers.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="radio-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                                style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Seamless Integration</p>
                                        </div>
                                        <p>All Pharma24*7 products integrate smoothly, creating a unified experience
                                            that enhances your pharmacy’s workflow.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="pt-3">
                        <div class="container">
                            <div class="text-">
                                <p class="">With<span class="span-theme fw-bold"> Pharma24*7 </span>, medical store
                                    owners can manage their operations more efficiently, improve customer satisfaction,
                                    and ensure compliance with industry regulations. Whether you're looking for
                                    real-time inventory management, fast invoicing, or a mobile solution for managing
                                    customer orders, Pharma24*7 offers the tools you need to grow your business and
                                    provide exceptional service.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="fAQsec section_margin max-991px">
            <div class="container">
                <div class="faqmain">
                    <div class="faqdiv">
                        <div class="title-block text-center">
                             <h3 class="fw-bold" style="    font-size: 25px;">
                                <span class="span-theme">
                                    Frequently Asked Questions
                                </span>
                            </h3>
                            <h2>Your Pharmacy Management Queries Answered</h2>
                        </div>
                        <div class="faqaccordion">
                            <div class="accordiondiv accordion" id="accordionExample">
                                <?php 
                                     $qestionData = App\Models\FAQModel::all();
                                ?>
                                @if(isset($qestionData))
                                @foreach($qestionData as $key => $listData)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne{{$key}}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne{{$key}}" aria-expanded="true"
                                            aria-controls="collapseOne{{$key}}">
                                            {{ isset($listData->question) ? $listData->question : "" }}
                                        </button>
                                    </h2>
                                    <div id="collapseOne{{$key}}"
                                        class="accordion-collapse collapse @if($key == 0) show @endif"
                                        aria-labelledby="headingOne{{$key}}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {!! htmlspecialchars_decode($listData->answer) !!}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <!-- <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            Q: How can Pharma24*7 help my pharmacy ?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>A:</strong> Pharma24*7 helps your pharmacy by:
                                            <ul>
                                                <li>Simplifying inventory management with real-time tracking and alerts.
                                                </li>
                                                <li>Streamlining billing and invoicing processes.</li>
                                                <li>Enabling online ordering for your customers, increasing your sales
                                                    channels.</li>
                                                <li>Offering detailed sales and performance reports for better
                                                    decision-making.</li>
                                                <li>Ensuring you stay focused on what matters most—serving your
                                                    patients.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            Q: Is Pharma24*7 difficult to use ?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse "
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> No, Pharma24*7 is designed to be user-friendly and
                                            intuitive, even for those with little technical experience. We also provide
                                            onboarding support and training to help you get started.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                            aria-expanded="false" aria-controls="collapseFour">
                                            Q: Can Pharma24*7 be used by pharmacies of all sizes ?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse "
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Yes, Pharma24*7 is scalable and customizable to suit the
                                            needs of both small, independent pharmacies and larger pharmacy chains.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                            aria-expanded="false" aria-controls="collapseFive">
                                            Q: How does Pharma24*7 handle online orders ?
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse "
                                        aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Pharma24*7 enables pharmacies to offer online ordering
                                            services to their customers, integrating these orders seamlessly with your
                                            in-store operations.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                            aria-expanded="false" aria-controls="collapseSix">
                                            Q: How is my data stored and is it secure ?
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse "
                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Pharma24*7 is a cloud-based platform, meaning your data
                                            is securely stored on remote servers with advanced encryption and
                                            industry-best security protocols.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                            aria-expanded="false" aria-controls="collapseSeven">
                                            Q: What kind of reports can Pharma24*7 generate ?
                                        </button>
                                    </h2>
                                    <div id="collapseSeven" class="accordion-collapse collapse "
                                        aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Pharma24*7 generates comprehensive reports on sales
                                            performance, inventory levels, customer purchasing trends, and more.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                            aria-expanded="false" aria-controls="collapseEight">
                                            Q: How much does Pharma24*7 cost ?
                                        </button>
                                    </h2>
                                    <div id="collapseEight" class="accordion-collapse collapse "
                                        aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Pharma24*7 offers flexible pricing plans tailored to
                                            different business needs. Contact our sales team for specific pricing.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseNine"
                                            aria-expanded="false" aria-controls="collapseNine">
                                            Q: Can I access Pharma24*7 from anywhere ?
                                        </button>
                                    </h2>
                                    <div id="collapseNine" class="accordion-collapse collapse "
                                        aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Yes, as a cloud-based solution, Pharma24*7 can be
                                            accessed from any device with an internet connection.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTen"
                                            aria-expanded="false" aria-controls="collapseTen">
                                            Q: Does Pharma24*7 integrate with existing pharmacy systems ?
                                        </button>
                                    </h2>
                                    <div id="collapseTen" class="accordion-collapse collapse "
                                        aria-labelledby="headingTen" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Yes, Pharma24*7 integrates seamlessly with most existing
                                            pharmacy management systems.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEleven"
                                            aria-expanded="false" aria-controls="collapseEleven">
                                            Q: How do I get started with Pharma24*7 ?
                                        </button>
                                    </h2>
                                    <div id="collapseEleven" class="accordion-collapse collapse "
                                        aria-labelledby="headingEleven" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Getting started is easy! Sign up for a free demo and our
                                            team will guide you through the setup process.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwelve"
                                            aria-expanded="false" aria-controls="collapseTwelve">
                                            Q: What kind of customer support does Pharma24*7 offer ?
                                        </button>
                                    </h2>
                                    <div id="collapseTwelve" class="accordion-collapse collapse "
                                        aria-labelledby="headingTwelve" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> Pharma24*7 provides 24/7 email and chat support, phone
                                            support during business hours, and onboarding materials.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item ">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThirteen"
                                            aria-expanded="false" aria-controls="collapseThirteen">
                                            Q: How can I request a demo ?
                                        </button>
                                    </h2>
                                    <div id="collapseThirteen" class="accordion-collapse collapse "
                                        aria-labelledby="headingThirteen" data-bs-parent="#accordionExample">
                                        <div class="accordion-body py-3 px-3">
                                            <strong>A:</strong> You can request a free demo through our website or
                                            contact our sales team at [Phone] or [Email].
                                        </div>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- <section class="expdiffsec section_margin">
            <div class="expdiffdivmain container bg-theme rounded-5">
                <div class=" expdiffdiv p-5 position-relative z-1 text-white">
                    <div class="align-items-center row row-gap-4 titlediv">
                        <div class="col-md-6">
                            <h3 class="fw-bold">Ready to Transform Your Pharmacy?</h3>
                            <p>For any questions or additional information, reach out to us at
                                <br>
                                <a href="tel:+8877887788" class="btn btn-outline-light fs-6 px-5 text-capitalize fw-bold py-2 mt-3"><ion-icon name="call-outline"></ion-icon> 8877887788</a>
                                or
                                <a href="mailto:example@gmail.com" class="btn btn-outline-light fs-6 px-5 text-capitalize fw-bold py-2 mt-3"><ion-icon name="mail-unread-outline"></ion-icon> example@gmail.com</a>.
                            </p> 
                        </div>
                        <div class="col-md-6">
                            <p class="">Join the growing number of pharmacies that are transforming their operations with Pharma24*7. Our
                                cloud-based solution is here to support you in running a more efficient, customer-focused, and
                                profitable business. Discover how Pharma24*7 can make a difference in your pharmacy today.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
    </main>

    @include('front.footer')