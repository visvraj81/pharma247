@include('front.header')

<body class="home">
    @include('front.menu')
    <main class="demo ">
        <!-- <div class="breadcrumbsdiv py-5">
            <nav aria-label="breadcrumb" class=" py-5 my-3">
                <ol class="breadcrumb justify-content-center py-5">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h2 class="fw-bold m-0 text-white border-bottom">Demo & Training</h2>
                    </li>
                </ol>
            </nav>
        </div> -->
        <section class="herosectionfhome section_margin">
            <div class="herohome">
                <div class="container">
                    <div class="title-block text-center mb-5">
                        <h1 style="font-size: 30px;">Discover the <span class="span-theme">Future of Pharmacy</span> Management</h1>
                        <p class="f">Try the Pharma24*7 Demo for Free</p>
                    </div>
                </div>
                <div class="swiper position-relative overflow-hidden">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="swprslidemaindiv">
                                <div class="hometopbannerdiv">
                                    <div class="container overflow-hidden">
                                        <div class="row align-items-center flex-column-reverse flex-md-row px-3">
                                            <div class="col-lg-12 col-md-12 col-xl-6">
                                                <div class="hometoptext text-start col-12 ms-auto">
                                                    <p>At Pharma24*7, we believe in empowering pharmacies with the tools
                                                        they need to thrive in the digital era. Whether you’re managing
                                                        inventory, processing online orders, or enhancing customer
                                                        engagement, Pharma24*7 simplifies every step.
                                                    </p>
                                                    <p>
                                                        Want to see how it works? Explore our platform through a quick
                                                        demo to understand how Pharma24*7 can revolutionize your
                                                        business.
                                                    </p>
                                                    <p>Experience the platform yourself! Sign up for a <strong>free
                                                            demo</strong> to see how Pharma24*7 can streamline your
                                                        pharmacy’s operations. Our intuitive, cloud-based software is
                                                        designed to be easy to use and customize, making it perfect for
                                                        pharmacies of any size.</p>
                                                    <h2 style="    font-size: 20px;" class="fw-bold">What You’ll Learn in the Demo:</h2>
                                                    <ul>
                                                        <li class="itms">How to simplify day-to-day operations and save
                                                            time.</li>
                                                        <li class="itms">Ways to boost sales with online orders and
                                                            customer management.</li>
                                                        <li class="itms">How to effectively manage your store remotely
                                                        </li>
                                                        <li class="itms">How to make smarter business decisions with
                                                            real-time data and analytics.</li>
                                                    </ul>
                                                    <!-- <p>All for just <span class="span-theme fs-5 fw-bold">Rs 6/day</span>.</p> -->
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xl-6">
                                                <div
                                                    class="hometopdivimg00 text-start mt-4 mt-mb-0 rounded-2 overflow-hidden">
                                                    <img src="{{asset('public/landing_design/images/pharma-dashboard.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
                                                </div>
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
        <section class="section_margin enq_section position-relative overflow-hidden" id="enq_sec_id">
            <!-- <div class="bgimgdiv position-absolute z-0 w-100 top-0 h-100">
                <img src="assets/images/low-angle-shot-tall-glass-buildings-blue-cloudy-sky.png" alt="" class="img-fluid w-100 h-100 object-fit-cover">
            </div> -->
            <div class="container-fluid overflow-hidden p-0">
                <div class="enqdivmain position-relative px-3 py-5 z-1">
                    <div class="col-xxl-4 col-xl-6 col-md-8 col-12 p-5 px-3 px-sm-5 enqformdiv m-auto">
                        <div class="getquoteform">
                            <h3 class="text-center text mb-4 fw-bold">Ready to Get Started? </h3>
                            <form action="{{ route('ready.to.get.store') }}" method="post" novalidate="novalidate">
                                @csrf
                                <input type="hidden" name="product_id" id="product_id" value="43">
                                <div class="row row-gap-3">
                                    <div class="col-md-12">
                                        <div class="qetquotefield">
                                            <input type="text" class="fs-6" name="fname" id="fname" placeholder="Pharmacy Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="qetquotefield">
                                            <input type="email" class="fs-6" name="email" placeholder="Email" id="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="qetquotefield">
                                            <input type="number" class="fs-6" name="phone" placeholder="Phone" id="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="qetquotefield">
                                            <select class="form-select" name="plan" aria-label="Default select example">
                                                <option value="">select service</option>
                                                @if(isset($plan))
                                                @foreach($plan as $list)
                                                <option value="{{ $list->id }}">{{ $list->name }} - ₹ {{ $list->annual_price }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="qetquotefield">
                                            <textarea name="message" placeholder="Remark" id="message" cols="30" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="enqbtn text-end">
                                        <button type="submit" class="btn enqbtn00 theme-btn py-2 fw-medium text-capitalize contact_btn" data-id="43">send message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section_margin abthalfsec">
            <div class="container">
                <div class="abt--haldiv">
                    <div class="justify-content-around row">
                        <div class="abthalfcol col-md-5">
                            <div class="abthalf--div text-center text-md-start  title-block">
                                <h2 class="">
                                    Already Bought the software?
                                </h2>
                                <h3 class="fw-bold" style="font-size: 20px;"><span class="span-theme">
                                        Try our training videos by clicking on each feature
                                    </span></h3>
                            </div>
                        </div>
                        <div class="abthalfcol col-md-6">
                            <div class="abthalf--div text-center text-md-start ">
                                <p>
                                    Extensive all in one training, Inventory Management, Location wise Stock Management,
                                    Expiry Drug Management, Find Drug Alternative, Barcode Management, Online Purchase
                                    Import, Reports & Analytics, GST Reports, Prescription reminder, Send invoice to
                                    WhatsApp/Text, Referrals.
                                </p>
                            </div>
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
        <section class="section_margin fundamensec">
            <div class="container">
                <div class="fundamen position-relative overflow-hidden rounded-3 px-3 pb-3">
                    <div class="title-block text-center">
                        <h3 class="fw-bold" style="font-size: 20px;"><span class="span-theme">Your Trusted Pharmacy Software Solution</span></h3>
                        <h2>Why Choose Pharma24*7?</h2>
                    </div>

                    <div class="fundamendiff">
                        <div class="row row-gap-4 justify-content-center">
                            <div class="col-lg-4 col-md-6 ">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">

                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="people-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                All-in-One Solution</p>
                                        </div>
                                        <p>
                                            Unlike other software that can be complicated and cumbersome, Pharma24*7 is
                                            designed to be simple and intuitive. You don’t need to be a tech expert to
                                            use our platform effectively.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 ">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="cash-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                                style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Cloud-Based Convenience</p>
                                        </div>
                                        <p>Access your data from anywhere, anytime.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 ">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="checkmark-done-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Affordable & Scalable</p>
                                        </div>
                                        <p>Whether you’re a single store or a chain, Pharma24*7 grows with your business.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        $UserData = \App\Models\User::with('roles')->first();
        ?>
        <section class="expdiffsec section_margin">
            <div class="expdiffdivmain container bg-theme rounded-5">
                <div class=" expdiffdiv p-5 position-relative z-1 text-white">
                    <div class="align-items-center row row-gap-4 titlediv">
                        <div class="col-md-6">
                            <h3 class="fw-bold">Ready to Transform Your Pharmacy?</h3>
                            <p>For any questions or additional information, reach out to us at
                            </p>
                            <div class="d-flex align-items-center column-gap-3 row-gap-2 flex-wrap mt-3">
                                <a href="{{ isset($UserData->phone_number) ? $UserData->phone_number :''}}"
                                    class="align-items-center btn btn-outline-light d-flex fs-6 fw-bold gap-3 text-capitalize"><ion-icon
                                        name="call-outline"></ion-icon> {{ isset($UserData->phone_number) ? $UserData->phone_number :""}}</a>
                                or
                                <a href="mailto:inquiry@pharma247.in"
                                    class="mail_btn btn btn-outline-light fs-6 fw-bold d-flex align-items-center gap-3"><ion-icon
                                        name="mail-unread-outline"></ion-icon> inquiry@pharma247.in</a>
                            </div>
                            <!-- <a href="" class="btn btn-outline-light fs-6 px-5 text-capitalize fw-bold py-2 mt-3">check demo</a> -->
                        </div>
                        <div class="col-md-6">
                            <p class="" style="text-align:justify">Join the growing number of pharmacies that are transforming their operations
                                with Pharma24*7. Our
                                cloud-based solution is here to support you in running a more efficient,
                                customer-focused, and
                                profitable business. Discover how Pharma24*7 can make a difference in your pharmacy
                                today.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('front.footer')