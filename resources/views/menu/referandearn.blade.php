@include('front.header')
<div class="breadcrumb-wrapper light-bg">
        <div class="container">

            <div class="breadcrumb-content">
                <h1 class="breadcrumb-title pb-0 text-white">Refer & Earn</h1>
                <div class="breadcrumb-menu-wrapper">
                    <div class="breadcrumb-menu-wrap">
                        <div class="breadcrumb-menu">
                            <ul>
                                <li><a href="{{route('front.index')}}" class="text-white">Home</a></li>
                                >
                                <li aria-current="page" class="text-white">Refer & Earn</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="lonyo-section-padding10">
        <div class="container">
            <div class="lonyo-section-title max-width-750 pb-40">
                <h2 class="title">Pharma247 Refer & Earn Program</h2>
                <!-- <p>Spread the Word and Earn Rewards!</p> -->
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="lonyo-about-us-thumb2 pr-51" data-aos="fade-up" data-aos-duration="700">
                        <img src="{{asset('public/landing_desgin/assets/images/shape/howtoearn.png')}}" alt="">
                    </div>
                </div>
                <div class="col-lg-7 d-flex align-items-center">
                    <div class="lonyo-default-content pl-32" data-aos="fade-up" data-aos-duration="900">
                        <p>Do you love using Pharma247 to manage your pharmacy? Why not share it with others and earn
                            rewards while helping fellow pharmacists improve their operations?</p>

                        <h2>how it works</h2>
                        <ul>
                            <li class="mt-2 mb-2">
                                <b>Refer: </b>Tell your fellow pharmacy owners about Pharma247 and share your unique
                                referral link with them.
                            </li>
                            <li class="mt-2 mb-2">
                                <b>They Sign Up: </b>When they sign up for Pharma247 and start using the platform, you
                                both benefit.
                            </li>
                            <li class="mt-2 mb-2">
                                <b>Earn: </b>For every successful referral, you'll earn rewards, discounts, or cashback
                                that can be applied to your Pharma247 subscription.
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <!-- <div class="lonyo-feature-shape">
                <img src="/assets/images/v1/medicine-3.jpg" alt="">
            </div> -->
        </div>
    </div>
    <!-- end -->

    <div class="lonyo-section-padding2 position-relative">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="lonyo-service-wrap" data-aos="fade-up" data-aos-duration="500"
                        style="height: 180px; background-color: #115D9D;">
                        <div class="lonyo-service-title">

                            <h4 class="text-white">Refer a Pharmacy</h4>
                            <i class="fas fa-store text-white fa-2x"></i>
                        </div>
                        <div class="lonyo-service-data">
                            <p class="text-white">Share Pharma24*7 with other medical stores.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="lonyo-service-wrap" data-aos="fade-up" data-aos-duration="700"
                        style="height: 180px;background-color: #115D9D;">
                        <div class="lonyo-service-title">
                            <h4 class="text-white">They Join, You Earn</h4>
                            <i class="fas fa-hand-holding-usd text-white fa-2x"></i>
                        </div>
                        <div class="lonyo-service-data">
                            <p class="text-white">Get rewards for every successful referral.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="lonyo-service-wrap" data-aos="fade-up" data-aos-duration="900"
                        style="height: 180px;background-color: #115D9D;">
                        <div class="lonyo-service-title">
                            <h4 class="text-white">Unlimited Earnings</h4>
                            <i class="fas fa-coins text-white fa-2x"></i>
                        </div>
                        <div class="lonyo-service-data">
                            <p class="text-white">More referrals, more rewards!</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- <div class="lonyo-feature-shape-left d-none d-md-block">
            <img src="/assets/images/v1/medicine-4.png" alt="">
        </div> -->
    </div>

    <div class="lonyo-section-padding3">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="lonyo-about-us-thumb2 pr-51" data-aos="fade-up" data-aos-duration="700">
                        <img src="{{asset('public/landing_desgin/assets/images/shape/howtostart.png')}}" alt="">
                    </div>
                </div>
                <div class="col-lg-7 d-flex align-items-center">
                    <div class="lonyo-default-content pl-32" data-aos="fade-up" data-aos-duration="900">
                        <h2>how to get started ?</h2>
                        <ul>
                            <li class="mt-2 mb-2">
                                <b>Login to Your Account: </b>Go to your dashboard and find the 'Refer & Earn' section.
                            </li>
                            <li class="mt-2 mb-2">
                                <b>Share Your Link: </b>Copy your unique referral link and share it with your pharmacy
                                network via email, social media, or messaging apps.
                            </li>
                            <li class="mt-2 mb-2">
                                <b>Track Your Referrals: </b>Keep track of your successful referrals and watch the
                                rewards come in.
                            </li>
                            <p>Start sharing today and earn while you help others succeed with Pharma247.</p>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('front.footer')