@include('front.header')

<body class="home">
    @include('front.menu')
    <main class="demo ">
        <!-- <div class="breadcrumbsdiv py-5">
            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb justify-content-center ">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h2 class="fw-bold m-0 border-bottom ">Refer & Earn</h2>
                    </li>
                </ol>
            </nav>
        </div> -->
        <section class="herosectionfhome section_margin">
            <div class="herohome">
                <div class="container">
                    <div class="title-block text-center mb-5">
                        <h1 style="font-size: 30px;">Pharma247 <span class="span-theme">Refer & Earn </span> Program</h1>
                        <p class="f">Spread the Word and Earn Rewards!</p>
                    </div>
                </div>
                <div class="swiper position-relative overflow-hidden">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="swprslidemaindiv">
                                <div class="hometopbannerdiv">
                                    <div class="container overflow-hidden">
                                        <div class="row align-items-center flex-column-reverse flex-md-row px-3">
                                            <div class="col-lg-6 col-md-12">
                                                <div class="hometoptext text-start ">
                                                    <p>Do you love using Pharma247 to manage your pharmacy? Why not
                                                        share it with others and earn rewards while helping fellow
                                                        pharmacists improve their operations?
                                                    </p>
                                                    <h2 class="fw-bold" style="font-size: 22px;">how it works</h2>
                                                    <ol class="steps">
                                                        <li><strong>Refer:</strong> Tell your fellow pharmacy owners
                                                            about Pharma247 and share your unique referral link with
                                                            them.</li>
                                                        <li><strong>They Sign Up:</strong> When they sign up for
                                                            Pharma247 and start using the platform, you both benefit.
                                                        </li>
                                                        <li><strong>Earn:</strong> For every successful referral, you’ll
                                                            earn rewards, discounts, or cashback that can be applied to
                                                            your Pharma247 subscription.</li>
                                                    </ol>
                                                    <!-- <p>All for just <span class="span-theme fs-5 fw-bold">Rs 6/day</span>.</p> -->
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div
                                                    class="hometopdivimg00 text-start mt-mb-0 rounded-2 overflow-hidden">
                                                    <img src="{{asset('public/landing_design/images/refer.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid">
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
                        <h2 class="fw-bold"><span class="span-theme">Earn Rewards with Every Referral</span></h2>
                        <h2>What Can You Earn?</h2>
                    </div>


                    <div class="fundamendiff">
                        <div class="row row-gap-4 justify-content-center">
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">

                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="pricetags-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Discounts </p>
                                        </div>
                                        <p>
                                            Save on your next billing cycle with exclusive subscription fee discounts.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="trophy-outline"
                                                class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Rewards</p>
                                        </div>
                                        <p>Unlock special perks, tools, and early access to new features with exclusive
                                            rewards.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div
                                    class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                    <div class="servicecoldiv00 px-2">
                                        <div class="ioniconsdfiv d-flex gap-3 mb-3">
                                            <ion-icon name="cash-outline" class="fs-3 shadow p-3 rounded-5 md hydrated"
                                                style="color:#000000" role="img"></ion-icon>
                                            <p class="card_header_txt fw-bold d-flex mb-0 align-items-center gap-1">
                                                Cashback Offers</p>
                                        </div>
                                        <p>Earn real cash rewards for every successful referral with cashback offers.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section_margin howtostart">
            <div class="container">
                <div class="howtostartrowdiv">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex flex-column howtoroe justify-content-center row-gap-3 text-center">
                                <img src="{{asset('public/landing_design/images/howtostart.png')}}" alt="Pharma24*7" class="img-fluid m-auto" width="50px">
                                <h3 class="fw-bold">How to get started?</h3>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="howtoroe">
                                <ol class="steps">
                                    <li><strong>Login to Your Account:</strong> Go to your dashboard and find the ‘Refer
                                        & Earn’ section.</li>
                                    <li><strong>Share Your Link:</strong> Copy your unique referral link and share it
                                        with your pharmacy network via email, social media, or messaging apps.</li>
                                    <li><strong>Track Your Referrals:</strong> Keep track of your successful referrals
                                        and watch the rewards come in!</li>
                                </ol>
                                <p>Start sharing today and earn while you help others succeed with Pharma247.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    @include('front.footer')