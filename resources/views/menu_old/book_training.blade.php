@include('front.header')

<body class="bookinggg">
    @include('front.menu')
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
                                        <img src="{{asset('public/landing_design/images/logo.png')}}"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}"
                                            width="250px" class="img-fluid">
                                    </div>
                                    <div class="bkclndrdesc000 mt-5">
                                        <img src="{{asset('public/landing_design/images/logo.png')}}" width="150px"
                                            height="150px"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}" class="img-fluid mb-3">
                                        <h5 class="">pharma24*7 Product Training Call</h5>
                                        <ul class="bkul009">
                                            <li><svg data-id="details-item-icon" width="20"
                                                    style="color: #444444;margin-right:10px" height="20"
                                                    viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" role="img">
                                                    <path d="M.5 5a4.5 4.5 0 1 0 9 0 4.5 4.5 0 1 0-9 0Z" fill="none"
                                                        stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                    <path d="M5 3.269V5l1.759 2.052" fill="none" stroke="currentColor"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>1 hr</li>
                                            <li><svg data-testid="web-conference-icon" width="20"
                                                    style="color: #444;margin-right:10px" height="20"
                                                    data-id="details-item-icon" viewBox="0 0 10 10"
                                                    xmlns="http://www.w3.org/2000/svg" role="img">
                                                    <path
                                                        d="M7.192 3.731V2.5a1 1 0 0 0-1-1H1.5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h4.692a1 1 0 0 0 1-1V6.269l1.573.839a.5.5 0 0 0 .735-.441V3.333a.5.5 0 0 0-.735-.441Z"
                                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
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
                                            <input type="text" name="date_select" id="datepicker"
                                                class="d-none dform-control fw-bold" placeholder="Select date" readonly>
                                        </div>
                                        <div id="time-picker" class="pe-2">
                                            <ul class="slcttimeul p-0" id="timepicker">
                                                <?php
                                                for ($hour = 0; $hour < 24; $hour++) {
                                                    // Format the hour to 12-hour format with AM/PM
                                                    $formattedTime = date("g:00a", strtotime("$hour:00"));
                                                    echo '<li class="list-unstyled mb-3 w-100">
                                                            <button class="btn w-100 btn-outline-themegreen fw-bold rounded-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                ' . $formattedTime . '
                                                            </button>
                                                        </li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="exampleModalLabel">your pharmacy details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body cntct-sec">
                    <form method="post" id="contact_form" action="#" novalidate="novalidate">
                        @csrf
                        <div class="messages"></div>
                        <div class="row g-4 justify-content-center">
                            <div class="input-group-meta d-flex flex-column gap-2 form-group mb-30">
                                <label class="fw-semibold" for="name">Pharmacy Name*</label>
                                <input type="text" name="name" id="name_data"  required>
                                <input type="hidden" name="data" id="data_select" />
                                <input type="hidden" name="time" id="time_select" />
                                <input type="hidden" name="plan" id="plan_id_select" />
                                <div class="invalid-feedback" style="color: red; display: none;">
                                    Please enter a Pharmacy Name.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                                    <label class="fw-semibold" for="email">Email*</label>
                                    <input type="email" name="email" id="email" required>
                                    <div class="invalid-feedback" style="color: red;">
                                        Please enter a valid email.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-40">
                                    <label class="fw-semibold" for="phone">Phone*</label>
                                    <input type="number" name="phone" id="phone" required>
                                    <div class="invalid-feedback" style="color: red;">
                                        Please enter a phone number.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-35">
                                    <label class="fw-semibold" for="address">Address</label>
                                    <textarea name="address" id="address" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group-meta d-flex flex-column gap-2 form-group mb-35">
                                    <label class="fw-semibold" for="message">Message</label>
                                    <textarea name="message" id="message" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="btnthremediv d-flex gap-3 justify-content-end col-lg-12">
                                <button type="button" class="btn btn-outline-themegreen"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="contact"
                                    class="btn-four tran3s btn theme-btn knwmrbtn d-block">Send Message</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @include('front.footer')
</body>

</html>