<section class="lonyo-cta-section bg-heading">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="lonyo-cta-thumb" data-aos="fade-up" data-aos-duration="500">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/cta-thumb.png')}}" alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lonyo-default-content lonyo-cta-wrap" data-aos="fade-up" data-aos-duration="700">
                    <h2>Pharma24*7 Patient App - Fast Medicine Delivery</h2>
                    <p>Get medicines delivered fast with the Pharma24*7 Patient App. Easy ordering, reminders & health
                        tracking!
                        Download now on Android & iOS.</p>
                    <div class="lonyo-cta-info mt-50" data-aos="fade-up" data-aos-duration="900">
                        <ul>
                            <li>
                                <a href="https://www.apple.com/app-store/"><img
                                        src="{{asset('public/landing_desgin/assets/images/v1/app-store.svg')}}"
                                        alt=""></a>
                            </li>
                            <li>
                                <a href="https://playstore.com/"><img
                                        src="{{asset('public/landing_desgin/assets/images/v1/play-store.svg')}}"
                                        alt=""></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="lonyo-cta-section bg-heading">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="lonyo-default-content lonyo-cta-wrap" data-aos="fade-up" data-aos-duration="700">
                    <h2> Pharma24*7 Chemist App - Pharmacy Billing & Management</h2>
                    <p>Effortlessly manage your pharmacy with the Pharma24*7 Chemist App. Fast billing, inventory
                        tracking,
                        online orders & more! Download now on Android & iOS.</p>
                    <div class="lonyo-cta-info mt-50" data-aos="fade-up" data-aos-duration="900">
                        <ul>
                            <li>
                                <a href="https://www.apple.com/app-store/"><img
                                        src="{{asset('public/landing_desgin/assets/images/v1/app-store.svg')}}"
                                        alt=""></a>
                            </li>
                            <li>
                                <a href="https://playstore.com/"><img
                                        src="{{asset('public/landing_desgin/assets/images/v1/play-store.svg')}}"
                                        alt=""></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lonyo-cta-thumb" data-aos="fade-up" data-aos-duration="500">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/cta-thumb.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<?php 
        $categoryData = Route::currentRouteName();
        $qestionData = App\Models\FAQModel::where('faq_category',$categoryData)->get();
?>
<section>
    <div class="lonyo-section-padding7">
        <div class="container">
            <div class="lonyo-section-title max600">
                <h2>FAQ's</h2>
            </div>
            <div class="lonyo-faq-wrap1">
             @if(isset($qestionData))
             @foreach($qestionData as $key => $listData)
                <div class="lonyo-faq-item item2" data-aos="fade-up" data-aos-duration="500">
                    <div class="lonyo-faq-header">
                        <h4>{{ isset($listData->question) ? $listData->question : "" }}</h4>
                        <div class="lonyo-active-icon">
                            <img class="plasicon" src="{{asset('public/landing_desgin/assets/images/v1/mynus.svg')}}"
                                alt="">
                            <img class="mynusicon" src="{{asset('public/landing_desgin/assets/images/v1/plas.svg')}}"
                                alt="">
                        </div>
                    </div>
                    <div class="lonyo-faq-body body2">
                        <p>{!! htmlspecialchars_decode($listData->answer) !!}</p>
                    </div>
                </div>
                @endforeach
                @endif
             
            </div>
        </div>
    </div>
</section>
<div class="lonyo-content-shape">
    <img src="{{asset('public/landing_desgin/assets/images/shape/shape2.svg')}}" alt="">
</div>
<footer class="lonyo-footer-section light-bg">
    <div class="container">
        <div class="lonyo-footer-one">
            <div class="row">
                <div class="col-xxl-4 col-xl-12 col-md-6">
                    <div class="lonyo-footer-textarea">
                        <a href="#">
                            <img src="{{asset('public/landing_desgin/assets/images/v1/new.png')}}" alt="" width="150px">
                        </a>
                        <p>Manage your pharmacy anytime, anywhere with Pharma24*7's cloud-based software, Chemist App &
                            Patient
                            App. Automate billing, inventory, online orders, GST reports, and customer engagement
                            seamlessly. Stay
                            connected and grow your business with ease!
                        </p>
                        <div class="lonyo-social-wrap2">
                            <ul>
                                <li>
                                    <a href="https://www.facebook.com/people/Pharma247/61568780619517/?mibextid=ZbWKwL">
                                        <svg width="10" height="18" viewBox="0 0 9 17" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.61987 16.7464V9.37041H0.137695V6.49583H2.61987V4.37591C2.61987 1.91577 4.12245 0.576172 6.31707 0.576172C7.36832 0.576172 8.27181 0.654439 8.53511 0.689422V3.26042L7.01302 3.26111C5.81946 3.26111 5.58836 3.82827 5.58836 4.66054V6.49583H8.43488L8.06426 9.37041H5.58836V16.7464H2.61987Z"
                                                fill="#fff" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://x.com/i/flow/login?redirect_after_login=%2FPharma24_7">
                                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.47743 6.98936L14.9996 0.570312H13.691L8.89613 6.14388L5.06647 0.570312H0.649414L6.44061 8.99854L0.649414 15.7299H1.95806L7.02158 9.84402L11.066 15.7299H15.483L9.47711 6.98936H9.47743ZM7.68506 9.0728L7.09829 8.23353L2.42958 1.55544H4.43959L8.20729 6.94488L8.79406 7.78414L13.6916 14.7896H11.6816L7.68506 9.07312V9.0728Z"
                                                fill="#fff" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/pharma24_7/?igsh=MTkwNWk1OXRlNXE0aA%3D%3D">
                                        <svg width="17" height="17" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.65632 0.561604C10.2832 0.559198 10.91 0.565498 11.5367 0.580502L11.7034 0.586515C11.8958 0.593387 12.0856 0.601977 12.315 0.612286C13.229 0.655236 13.8526 0.799551 14.3998 1.01173C14.9667 1.22992 15.4444 1.52542 15.922 2.00303C16.3587 2.4322 16.6967 2.95134 16.9124 3.52434C17.1246 4.07154 17.2689 4.69604 17.3119 5.61003C17.3222 5.83853 17.3308 6.02923 17.3376 6.22165L17.3428 6.3883C17.358 7.01468 17.3646 7.64125 17.3625 8.26782L17.3634 8.90865V10.034C17.3655 10.6608 17.3589 11.2877 17.3436 11.9143L17.3385 12.081C17.3316 12.2734 17.323 12.4632 17.3127 12.6926C17.2698 13.6066 17.1237 14.2302 16.9124 14.7774C16.6974 15.351 16.3593 15.8706 15.922 16.2996C15.4924 16.7363 14.973 17.0742 14.3998 17.29C13.8526 17.5022 13.229 17.6465 12.315 17.6895C12.0856 17.6998 11.8958 17.7084 11.7034 17.7153L11.5367 17.7204C10.91 17.7357 10.2832 17.7423 9.65632 17.7402L9.0155 17.741H7.89105C7.26419 17.7431 6.63734 17.7366 6.01067 17.7213L5.84402 17.7161C5.6401 17.7087 5.43622 17.7001 5.2324 17.6903C4.31841 17.6474 3.69476 17.5014 3.14671 17.29C2.57348 17.0747 2.05425 16.7367 1.6254 16.2996C1.1882 15.8703 0.8499 15.3509 0.634095 14.7774C0.421919 14.2302 0.277604 13.6066 0.234654 12.6926C0.225087 12.4888 0.216497 12.2849 0.208883 12.081L0.204588 11.9143C0.188751 11.2877 0.181592 10.6608 0.183113 10.034V8.26782C0.180715 7.64126 0.187015 7.01469 0.202011 6.3883L0.208024 6.22165C0.214896 6.02923 0.223487 5.83853 0.233795 5.61003C0.276745 4.69518 0.42106 4.0724 0.633236 3.52434C0.849169 2.95106 1.18813 2.43206 1.62626 2.00389C2.05481 1.56644 2.57372 1.22783 3.14671 1.01173C3.69476 0.799551 4.31755 0.655236 5.2324 0.612286L5.84402 0.586515L6.01067 0.58222C6.63704 0.566391 7.26361 0.559232 7.89019 0.560745L9.65632 0.561604ZM8.77326 4.85668C8.20416 4.84863 7.63915 4.95376 7.11104 5.16598C6.58294 5.37819 6.10228 5.69325 5.69699 6.09284C5.29171 6.49244 4.96988 6.96859 4.75022 7.49365C4.53056 8.0187 4.41744 8.58217 4.41744 9.15132C4.41744 9.72047 4.53056 10.2839 4.75022 10.809C4.96988 11.334 5.29171 11.8102 5.69699 12.2098C6.10228 12.6094 6.58294 12.9244 7.11104 13.1367C7.63915 13.3489 8.20416 13.454 8.77326 13.446C9.91238 13.446 11.0048 12.9934 11.8103 12.188C12.6158 11.3825 13.0683 10.29 13.0683 9.15089C13.0683 8.01176 12.6158 6.9193 11.8103 6.11381C11.0048 5.30833 9.91238 4.85668 8.77326 4.85668ZM8.77326 6.5747C9.11558 6.5684 9.45573 6.63037 9.77383 6.75699C10.0919 6.88362 10.3816 7.07237 10.626 7.31221C10.8703 7.55205 11.0644 7.83817 11.1969 8.15387C11.3294 8.46956 11.3977 8.8085 11.3978 9.15088C11.3978 9.49326 11.3297 9.83222 11.1972 10.148C11.0648 10.4637 10.8708 10.7499 10.6266 10.9898C10.3823 11.2297 10.0927 11.4186 9.77463 11.5453C9.45657 11.672 9.11644 11.7341 8.77411 11.7279C8.09064 11.7279 7.43516 11.4564 6.95187 10.9731C6.46858 10.4898 6.19707 9.83436 6.19707 9.15089C6.19707 8.46741 6.46858 7.81193 6.95187 7.32864C7.43516 6.84535 8.09064 6.57384 8.77411 6.57384L8.77326 6.5747ZM13.2831 3.56815C13.006 3.57925 12.7439 3.69714 12.5517 3.89713C12.3596 4.09713 12.2523 4.36372 12.2523 4.64106C12.2523 4.91841 12.3596 5.185 12.5517 5.38499C12.7439 5.58499 13.006 5.70288 13.2831 5.71397C13.5679 5.71397 13.841 5.60084 14.0423 5.39947C14.2437 5.1981 14.3568 4.92498 14.3568 4.6402C14.3568 4.35542 14.2437 4.08231 14.0423 3.88094C13.841 3.67956 13.5679 3.56644 13.2831 3.56644V3.56815Z"
                                                fill="#fff" />
                                        </svg>
                                    </a>
                                </li>
                                <!-- <li>
                    <a href="https://www.bd.linkedin.com/">
                      <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.31682 2.35804C4.31658 2.83452 4.12707 3.2914 3.78997 3.62816C3.45288 3.96492 2.99581 4.15397 2.51933 4.15373C2.04284 4.15349 1.58597 3.96398 1.24921 3.62689C0.912449 3.28979 0.723395 2.83273 0.723633 2.35624C0.723871 1.87976 0.913383 1.42288 1.25048 1.08612C1.58757 0.749364 2.04464 0.560309 2.52112 0.560547C2.99761 0.560785 3.45448 0.750297 3.79124 1.08739C4.128 1.42449 4.31706 1.88155 4.31682 2.35804ZM4.37072 5.48411H0.777531V16.7308H4.37072V5.48411ZM10.0479 5.48411H6.47273V16.7308H10.012V10.829C10.012 7.54121 14.2969 7.23579 14.2969 10.829V16.7308H17.8452V9.60729C17.8452 4.0648 11.5032 4.27141 10.012 6.99325L10.0479 5.48411Z" fill="#142D6F" />
                      </svg>
                    </a>
                  </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-md-6 p-4">
                    <div class="lonyo-footer-menu">
                        <h4 class="text-white">Main pages</h4>
                        <div class="lonyo-footer-menu-wrap">
                            <div class="lonyo-footer-menu1">
                                <ul>
                                    <li>
                                        <a href="{{ url('/') }}" class="text-white">Home</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pricing.index') }}" class="text-white">Pricing</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('aboutus.index') }}" class=" text-white">About Us</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('contactus.index') }}" class=" text-white">Contact US</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('privacy-policys') }}" class=" text-white">Privacy Policy</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="lonyo-footer-menu1">
                                <ul>
                               
                                    <li>
                                        <a href="{{ route('demotrain.index') }}" class=" text-white">Demo & Training</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('referandearn.index') }}" class=" text-white">Refer & Earn</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('blogs.index') }}" class=" text-white">Blogs</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('cancellationpolicy.index') }}" class=" text-white">Cancellation and
                                            Refund Policy</a>
                                    </li>
                                    <li>
                                        <a href="{{route('term-conditions')}}" class=" text-white">Term & Conditions</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-md-6 p-4">
                    <div class="lonyo-footer-menu pl-30">
                        <h4 class="text-white">Contact Info</h4>
                        <a href="https://maps.app.goo.gl/vK7Q35fPBFR5srwD7" class="text-white d-block">SF-14/B DHARTI
                            CITY COMPLEX KADI 382715
                        </a>
                        <?php
                            $UserData = \App\Models\User::with('roles')->first();
                        ?>
                        <a href="tel:{{ isset($UserData->phone_number) ? $UserData->phone_number :''}}" class="text-white d-block mt-3">{{ isset($UserData->phone_number) ? $UserData->phone_number :""}}</a>
                        <a href="mailto:inquiry@pharma247.in" class="text-white d-block mt-3">inquiry@pharma247.in</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="lonyo-footer-bottom-text">
            <p>© Copyright <span id="current-year"></span>, All Rights Reserved by Pharma247 / <span
                    class="ml-2">Designed by: <a href="https://shopnoecommerce.com/" class="text-white">Shopno Ecommerce Pvt Ltd</a></span></p>
        </div>
    </div>
</footer>
<style>
.toggleButton.active {
    background-color: #28a745 !important; /* Change to your theme color */
    color: white;
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="bookdemomodal" tabindex="-1" aria-labelledby="bookdemomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header p-0 justify-content-end"> 
          <button type="button" class="bg-transparent border-0 p-3 text-white" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body bookinggg rounded py-5">
          <main>
            <section class="booktrnsession section_margin">
              <div class="container">
                <div class="title-block text-center">
                  <h5 class="fw-bold">
                    <span class="span-theme">Book Your Training Session Now</span>
                  </h5>
                  <h2>
                    Get Hands-On Experience with Pharma24*7
                  </h2>
                </div>
                <div class="bktrngssndiv">
                  <div class="booktrnclndrdiv mt-5">
                    <div class="row row-gap-4">
                      <div class="col-xxl-6 col-lg-5">
                        <div class="bookclndrmaincarddiv brdrright">
                          <div class="mainboklogo">
                            <img src="{{asset('public/landing_desgin/assets/images/logo/logo.png')}}" alt="" width="250px" class="img-fluid">
                          </div>
                          <div class="bkclndrdesc000 mt-5">
                            <img src="{{asset('public/landing_desgin/assets/images/logo/logo.png')}}" width="150px" height="150px" alt="" class="img-fluid mb-3">
                            <h5 class="">pharma24*7 Product Training Call</h5>
                            <ul class="bkul009">
                              <li><svg data-id="details-item-icon" width="20" style="color: #444444;margin-right:10px"
                                  height="20" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" role="img">
                                  <path d="M.5 5a4.5 4.5 0 1 0 9 0 4.5 4.5 0 1 0-9 0Z" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                  <path d="M5 3.269V5l1.759 2.052" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>1 hr</li>
                              <li><svg data-testid="web-conference-icon" width="20"
                                  style="color: #444;margin-right:10px" height="20" data-id="details-item-icon"
                                  viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" role="img">
                                  <path
                                    d="M7.192 3.731V2.5a1 1 0 0 0-1-1H1.5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h4.692a1 1 0 0 0 1-1V6.269l1.573.839a.5.5 0 0 0 .735-.441V3.333a.5.5 0 0 0-.735-.441Z"
                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                  </path>
                                </svg>Web conferencing details provided upon confirmation.</li>
                            </ul>
                            <div class="cookieclinent00">
                              <p>Dear client,</p>
                              <p>Thank you for choosing pharma24*7.</p>
                              <p>In this meeting, You'll be helped with setting up your account and
                                detailed training will be given to you with hidden features so that you
                                can make the best out of pharma24*7.</p>
                              <a href="" class="mt-4 cookisetting">cookie settings</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xxl-6 col-lg-7">
                        <div class="bookclndrmaincarddiv p-4">
                          <h5 class="fw-bold mb-4">Select a Date & Time</h5>
                          <div class="d-flex gap-5 slctdattmfxhight flex-sm-nowrap flex-wrap">
                            <div class="form-group">
                              <input type="text" id="datepicker" class="d-none dform-control fw-bold"
                                placeholder="Select date" readonly>
                            </div>
                            <div id="time-picker" class="pe-2">
                              <label for="timepicker" class="text-capitalize mb-3 fs-6 fw-bold">monday december
                                21</label>
                              <ul class="slcttimeul p-0" id="timepicker">
                                <li class="list-unstyled mb-3 w-100">
                                  <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0 toggleButton" style="background-color: #115d9d;color: white;">
                                    12:00am
                                  </button>
                                </li>
                                <li class="list-unstyled mb-3 w-100">
                                  <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0 toggleButton" style="background-color: #115d9d;color: white;">
                                    1:00am
                                  </button>
                                </li>
                                <li class="list-unstyled mb-3 w-100">
                                  <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0 toggleButton" style="background-color: #115d9d;color: white;">
                                    2:00am
                                  </button>
                                </li>
                                <li class="list-unstyled mb-3 w-100">
                                  <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0 toggleButton" style="background-color: #115d9d;color: white;">
                                    3:00am
                                  </button>
                                </li>
                                <li class="list-unstyled mb-3 w-100">
                                  <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0 toggleButton" style="background-color: #115d9d;color: white;">
                                    4:00am
                                  </button>
                                </li>
                                <li class="list-unstyled mb-3 w-100">
                                  <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0 toggleButton" style="background-color: #115d9d;color: white;">
                                    5:00am
                                  </button>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal-body cntct-sec hidden" id="toggleForm">
                      <hr size style="height: 2px;">
                      <form  id="contact_form" class="mt-5" 
                        novalidate="novalidate">
                       @csrf 
                        <div class="messages"></div>
                        <div class="title-block mb-5 text-center">
                          <h4 class="fw-bold">
                            <span class="span-theme">Book Your Training Session Now</span>
                          </h4> 
                        </div>
                        <div class="row g-4 justify-content-center">
                          <div class="col-md-4">
                            <div class="input-group-meta d-flex flex-column gap-2 form-group mb-30">
                              <label class="fw-semibold" for="">Pharmacy Name*</label>
                              <input type="text" name="name" id="name" required="required">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                              <label class="fw-semibold" for="">Email*</label>
                              <input type="email" name="email" id="email_add" required="required">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                              <label class="fw-semibold" for="">Phone*</label>
                              <input type="number" name="phone" id="phone_add" required="required">
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="input-group-meta d-flex flex-column gap-2 form-group mb-35">
                              <label class="fw-semibold" for="">address</label>
                              <textarea name="address" id="address_add" required="required" rows="3"></textarea>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="input-group-meta d-flex flex-column gap-2 form-group mb-35">
                              <label class="fw-semibold" for="">message</label>
                              <textarea name="message" id="message_add" required="required" rows="5"></textarea>
                            </div>
                          </div>
                          <div class="btnthremediv d-flex gap-3 justify-content-end col-lg-12">
                            <button type="button" class="btn btn-outline-themegreen"
                              data-bs-dismiss="modal">Close</button>

                            <button type="button" id="contactdata"
                              class="knwmrbtn rounded theme-btn tran3s">Send
                              Message</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </main>
        </div>
      </div>
    </div>
  </div>
<script src="{{asset('public/landing_desgin/assets/js/jquery-3.7.1.min.js')}}"></script>

<script src="{{asset('public/landing_desgin/assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/menu/menu.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/ScrollSmoother.html')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/pricing.min.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/countdown.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/skillbar.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/slick-animation.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/slick-animation.min.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/faq.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/tabs-slider.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/product-increment.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/aos.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/niceselect.js')}}"></script>
<script src="{{asset('public/landing_desgin/assets/js/wow.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyArZVfNvjnLNwJZlLJKuOiWHZ6vtQzzb1Y"></script>
<script src="{{asset('public/landing_desgin/assets/js/slick.js')}}"></script>

<script src="{{asset('public/landing_desgin/assets/js/app.js')}}"></script>
<!-- Swiper JS -->
<script>
document.getElementById("search-input").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let items = document.querySelectorAll("#category-list li");

    items.forEach(function(item) {
        let text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = "";
        } else {
            item.style.display = "none";
        }
    });
});
</script>


<script>
    // Initialize Flatpickr for date selection
    flatpickr("#datepicker", {
      dateFormat: "Y-m-d",
      inline: true,
      onChange: function (selectedDates, dateStr) {
        // Show the time picker when a date is selected
        //document.getElementById("time-picker").style.display = "block";
        document.getElementById("time-picker").classList.add("d-block");
      }
    });

    // Initialize Flatpickr for time selection
    flatpickr("#timepicker", {
      //   enableTime: true,
      noCalendar: true,
      inline: true
      //dateFormat: "H:i"
    });
  </script>
  <script>
    // Initialize Flatpickr for date selection
    flatpickr("#datepickerscdl", {
      dateFormat: "Y-m-d",
      inline: true,
      onChange: function (selectedDates, dateStr) {
        // Show the time picker when a date is selected
        //document.getElementById("time-picker").style.display = "block";
        document.getElementById("time-pickerscdl").classList.add("d-block");
      }
    });

    // Initialize Flatpickr for time selection
    flatpickr("#timepickerscdl", {
      //   enableTime: true,
      noCalendar: true,
      inline: true
      //dateFormat: "H:i"
    });
  </script>

  <script>
    // Select all buttons with the class 'toggleButton'
    const buttons = document.querySelectorAll(".toggleButton");
    const form = document.getElementById("toggleForm");

    buttons.forEach(button => {
      button.addEventListener("click", function () {
        // Toggle form visibility
        form.classList.toggle("hidden");
      });
    });

$(document).on('click', '#contactdata', function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Fetch CSRF token

    var formData = {
        _token: csrfToken,  // Add CSRF token
        name: $("#name").val(),
        email: $("#email_add").val(),
        phone: $("#phone_add").val(),
        address: $("#address_add").val(),
        message: $("#message_add").val(),
        date: $("#datepicker").val(), 
        time: $(".toggleButton.active").text().trim()
    };

    console.log("Selected Time:", formData.time); 
  
    $.ajax({
        url: "{{route('ready.to.get.store')}}",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken // Set CSRF token in request header
        },
        success: function (response) {
                location.reload();
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const timeButtons = document.querySelectorAll(".toggleButton");

    timeButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Remove active class from all buttons
            timeButtons.forEach(btn => btn.classList.remove("active"));

            // Add active class to the clicked button
            this.classList.add("active");
        });
    });
});

  </script>
</body>


</html>