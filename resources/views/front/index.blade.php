@include('front.header')
<style>
/* General Modal Styling */
.modal-content {
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border: none;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 15px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.btn-close {
    outline: none;
    box-shadow: none;
}

/* Form Styling */
.input-group-meta input,
.input-group-meta textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
}

.input-group-meta label {
    font-weight: 600;
    font-size: 0.95rem;
    color: #444;
}

.input-group-meta textarea {
    resize: none;
}

.invalid-feedback {
    font-size: 0.875rem;
    color: red;
    display: none;
}

/* Button Styling */
.btnthremediv .btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

.btn-outline-themegreen {
    border: 2px solid #28a745;
    color: #28a745;
}

.btn-outline-themegreen:hover {
    background-color: #28a745;
    color: #fff;
}

.theme-btn {
    background-color: #28a745;
    color: #fff;
    border: none;
}

.theme-btn:hover {
    background-color: #218838;
}
</style>
<div class="lonyo-hero-section light-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 d-flex align-items-center">
                <div class="lonyo-hero-content" data-aos="fade-up" data-aos-duration="700">
                    <h1 class="hero-title text-white">India's Most Efficient Cloud-Based Pharmacy Billing Software</h1>
                    <p class="text" class="mb-3">Manage your pharmacy seamlessly with automation. Handle inventory, billing & prescriptions effortlessly, making operations smooth & hassle-free.</p>
                    <div class="lonyo-subscription-field mt-50 aos-init aos-animate m-0" data-aos="fade-up"
                        data-aos-duration="900">
                        <form action="{{route('ready.to.get.store')}}" method="post">
                           @csrf
                            <input name="phone" placeholder="Enter phone number" maxlength="10">
                            <button type="submit" class="lonyo-default-btn sub-btn" type="submit">Get a call</button>
                        </form>
                    </div>
                </div>

            </div>
            <?php
          $settingData = App\Models\Setting::first();
        ?>
            <div class="col-lg-5">
                <div class="lonyo-video-thumb2 aos-init aos-animate lonyo-hero-thumb" data-aos="fade-left"
                    data-aos-duration="700">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/2 png.png')}}" alt="">
                    <a class="play-btn video-init" href="{{isset($settingData->video) ? $settingData->video :''}}">
                        <img src="{{asset('public/landing_desgin/assets/images/shape/play-icon.svg')}}"
                            class="d-none d-md-block" alt="">
                        <img src="{{asset('public/landing_desgin/assets/images/shape/play-icon.svg')}}"
                            class="d-block d-md-none m2 ml-2" alt="" width="70%">
                        <div class="waves wave-1"></div>
                        <div class="waves wave-2"></div>
                        <div class="waves wave-3"></div>
                    </a>
                    <!-- <div class="lonyo-hero-shape">
              <img src="assets/images/shape/hero-shape1.svg" alt="">
            </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lonyo-content-shape1">
    <img src="{{asset('public/landing_desgin/assets/images/shape/shape3.svg')}}" alt="">
</div>
<div class="sec_pad position-relative">
    <div class="container">

        <div class="lonyo-section-title center">
            <h2 class="sectitle">Our Features</h2>
            <p>Pharma24*7 is the ultimate pharmacy growth solution - Smart, Fast & Profitable!</p>
        </div>
        <div class="row row-gap-4">
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="500"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Chemist App - Manage Your Pharmacy from Anywhere</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-01.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Run your pharmacy in real time, whether you own a medical store or not. Accept or reject
                            orders, track
                            sales, and manage inventory seamlessly.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="700"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Patient App - More Orders, More Sales</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-02.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Patients can order medicines from nearby pharmacies, helping you increase customer reach and
                            sales
                            effortlessly.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="900"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Cloud-Based & Multi-Device Access</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-03.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Access your pharmacy software from anywhere, anytime on multiple devices.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="500"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Smart Billing & GST Compliance</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-04.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Automate error-free billing, GST calculations, and e-invoicing for easy tax filing.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="700"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Loyalty & Customer Retention</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-05.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Reward customers with loyalty points and discounts, increasing repeat sales.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="900"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Digital Payments & WhatsApp Invoices</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-06.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Accept payments via UPI, cards, and wallets, and send invoices directly on WhatsApp. </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="900"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Instant Inventory & Expiry Alerts</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-07.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Track stock in real time and receive expiry notifications to avoid losses. </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="900"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Home Delivery & Order Management</h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-08.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Easily manage home delivery orders directly from the Chemist App. Accept, process, and track
                            deliveries to increase convenience and boost sales.</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="lonyo-service-wrap light-bg" data-aos="fade-up" data-aos-duration="900"
                    style="height: 270px;">
                    <div class="lonyo-service-title">
                        <h4 class="text-white">Smart Inventory Auto-Reorder System </h4>
                        <img src="{{asset('public/landing_desgin/assets/images/shape/Pharma website icon-04.png')}}"
                            alt="" width="70px">
                    </div>
                    <div class="lonyo-service-data">
                        <p>Smart Inventory Auto-Reorder System ensures that your pharmacy never runs out of essential
                            medicines.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class=" sec_pad_t" style="background-color: #f1f4f7;">
    <div class="container">
        <div class="lonyo-section-title center">
            <h2 class="sectitle">Advanced Features for Retail Pharmacy Software</h2> 
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="lonyo-about-us-thumb2 pr-51 aos-init aos-animate" data-aos="fade-up"
                    data-aos-duration="700">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/MAIN new.jpg')}}" alt="">
                </div>
            </div>
            <div class="col-md-7 d-flex align-items-center">
                <div class="lonyo-default-content pl-32 aos-init aos-animate" data-aos="fade-up"
                    data-aos-duration="900">
                    <h2>Cloud Based Billing Software</h2>
                    <p>Stay connected to your pharmacy with cloud technology, allowing you to manage your store from
                        anywhere.
                        Enjoy real-time updates and automatic backups that keep your data secure. Operate with the
                        flexibility and
                        reliability that modern healthcare demands.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sec_pad_t" style="background-color: #f1f4f7;">
    <div class="container">
        <div class="flex-column-reverse flex-md-row row ">
            <div class="col-md-7 d-flex align-items-center">
                <div class="lonyo-default-content aos-init aos-animate" data-aos="fade-up" data-aos-duration="900">
                    <h2>Easy Inventory Management</h2>
                    <p>Streamline your inventory with easy tracking, automated reorders, and low-stock alerts. Reduce
                        manual
                        errors and ensure your shelves are always stocked. With our system, managing your pharmacy's
                        inventory has
                        never been simpler.</p>
                </div>
            </div>
            <div class="col-md-5 pl-32">
                <div class="lonyo-about-us-thumb2 aos-init aos-animate" data-aos="fade-up" data-aos-duration="700">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/imgpsh_fullsize_anim.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sec_pad_t" style="background-color: #f1f4f7;">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="lonyo-about-us-thumb2 pr-51 aos-init aos-animate" data-aos="fade-up"
                    data-aos-duration="700">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/REPORT new.jpg')}}" alt="">
                </div>
            </div>
            <div class="col-md-7 d-flex align-items-center">
                <div class="lonyo-default-content pl-32 aos-init aos-animate" data-aos="fade-up"
                    data-aos-duration="900">
                    <h2>Customizable Reports & Analytics</h2>
                    <p>Generate tailored reports and insights with our flexible analytics tools. Track performance,
                        analyze
                        trends, and make data-driven decisions to enhance your pharmacy's operations. Customize reports
                        to fit
                        your specific needs and objectives.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sec_pad" style="background-color: #f1f4f7;">
    <div class="container">
        <div class="flex-column-reverse flex-md-row row">
            <div class="col-md-7 d-flex align-items-center">
                <div class="lonyo-default-content aos-init aos-animate" data-aos="fade-up" data-aos-duration="900">
                    <h2>Chemist App for Orders, Patient App for Delivery!</h2>
                    <p>Manage orders effortlessly with the Chemist App and deliver medicines seamlessly using the
                        Patient App.
                        Simplify online orders and boost customer convenience with Pharma24*7</p>
                </div>
            </div>
            <div class="col-md-5 pl-32">
                <div class="lonyo-about-us-thumb2 aos-init aos-animate" data-aos="fade-up" data-aos-duration="700">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/DELIVERY new-2.jpg')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="position-relative sec_mar">
    <div class="container">
        <div class="row">
            <div class="col-md-5 order-lg-2">
                <div data-aos="fade-up" data-aos-duration="700">
                    <img src="{{asset('public/landing_desgin/assets/images/v1/whyisimg.png')}}" alt="">
                </div>
            </div>
            <div class="col-md-7 d-flex align-items-center">
                <div class="lonyo-default-content pr-50" data-aos="fade-right" data-aos-duration="700">
                    <h3 class="pt-3 pb-3">Pharma24*7 - The Simple & Ultimate All-in-One Pharmacy Solution!</h3>
                    <p class="data">Pharma24*7 is the perfect all-in-one package to simplify and streamline your
                        pharmacy
                        business! Effortlessly manage billing, stock, GST reports, online orders, customer reminders,
                        and more—all
                        in one smart and efficient platform. Digitize your pharmacy today for seamless operations!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="lonyo-content-shape2"></div>
</div>
<section class="lonyo-section-padding22 sec_mar">
    <div class="container">
        <div class="lonyo-section-title center max-width-700">
            <h2 class="sectitle">Customer Testimonial</h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"Pharma24*7 has completely transformed my pharmacy business. Billing, inventory, and GST
                            reports are
                            now so easy to manage!"</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Rajesh Patel</p>
                            <span>Krishna Chemist, Ahmedabad</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"The Chemist App is a game-changer! I can now manage orders, check stock, and send invoices
                            from my
                            phone."</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Bhavesh Shah</p>
                            <span>Mamta Medical, Surat </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"Before using Pharma24*7, I struggled with manual stock tracking. Now, I get automatic expiry
                            and
                            low-stock alerts, saving me time and money!"</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Mohan Agarwal</p>
                            <span>Shree Ram Medical, Jaipur</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"The WhatsApp integration is fantastic! I can send invoices and order updates to customers
                            instantly."</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Hiren Desai</p>
                            <span>Khodiyar Medical, Vadodara </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"The Patient App is a great addition! My customers love the convenience of ordering medicines
                            online."</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Suresh Yadav</p>
                            <span>Deluxe Medical, Pune</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"Cloud-based access is a lifesaver! I can check my pharmacy sales and reports from anywhere."
                        </p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Hardik Joshi</p>
                            <span>Raj Medico, Anand</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"Pharma24*7 has helped me improve customer retention. The automated reminders for medicine
                            refills
                            are really helpful!"</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Dinesh Mehta</p>
                            <span>Sanjivani Medical, Udaipur</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"The software is very user-friendly. My staff was able to learn it quickly, and now
                            everything runs
                            smoothly!"</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Manish Bhatt</p>
                            <span>New Life Medical, Rajkot</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="lonyo-t-wrap light-bg" style="height: 250px;">
                    <div class="lonyo-t-text">
                        <p>"With Pharma24*7, I don't have to worry about GST calculations anymore. The reports are
                            accurate and
                            ready to file!"</p>
                    </div>
                    <div class="lonyo-t-author">

                        <div class="lonyo-t-author-data">
                            <p>Akash Sharma</p>
                            <span>Om Sai Medical, Indore</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
        </div>
    </div>
</section>
<div class="lonyo-section-padding100 sec_mar position-relative">
    <div class="container">
        <div class="lonyo-section-title center">
            <h2 class="sectitle title">Pricing Plans</h2>
            <p class="mb-0">Selecting the Best Pricing Plan for Your Pharmacy</p>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="lonyo-pricing-wrap aos-init aos-animate" data-aos="fade-right" data-aos-duration="500">
                    <div class="lonyo-pricing-header">
                        <h4 class="text-white">Pharma24*7 All-In-One Plan</h4>
                    </div>
                    <div class="row p-4 mb-4 gap-4">
                         @if(isset($subscriptioPlan))
                            @foreach($subscriptioPlan as $key => $list)
                                <div class="col-lg-5 p-4" style="background-color: white; border-radius: 15px;">
                                    <h2 class="">₹ {{ isset($list->annual_price) ? number_format($list->annual_price, 0) : "" }}
                                        @if(isset($key) && $key === 1) /year @endif
                                    </h2>
                                    <span class="d-block">{{ isset($list->name) ? $list->name : "" }}</span>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <div class="lonyo-pricing-body">
                        <p class="text-white">Features :</p>
                        <ul>
                            <li class="text-white">-> Full Access to all Features</li>
                            <li class="text-white">-> Easy to Use Pharmacy Billing Software</li>
                            <li class="text-white">-> Regular Updates & Customer Support</li>
                        </ul>
                    </div>
                    <div class="lonyo-pricing-footer mt-50">
                       <a href="https://medical.pharma247.in/Register"><div class="lonyo-default-btn d-block pricing-btn2" >Start your free 7-day
                         trial</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="exampleModalLabel">your pharmacy details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body cntct-sec">
                <form method="post" id="contact_form" action="{{route('ready.to.get.store')}}" novalidate="novalidate">
                    @csrf
                    <div class="messages"></div>
                    <div class="row g-4 justify-content-center">
                        <div class="input-group-meta d-flex flex-column gap-2 form-group mb-30">
                            <label class="fw-semibold" for="name">Pharmacy Name*</label>
                            <input type="text" name="name" id="name_data" required>
                            <input type="hidden" name="data" id="data_select" value="<?php date('Y-m-d') ?>" />
                            <input type="hidden" name="time" id="time_select" value="<?php date('h:i') ?>" />
                            <input type="hidden" name="plan" id="plan_id_select"
                                value="{{isset($subscriptioPlan[0]) ? $subscriptioPlan[0]->id :''}}" />
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
                            <button type="button" class="btn " data-bs-dismiss="modal"
                                style="background-color: #115d9d;color:white;">Close</button>
                            <button type="submit" id="contact" class="btn-primary tran3s btn theme-btn knwmrbtn d-block"
                                style="background-color: #115d9d;">Send Message</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
setTimeout(function() {
    $(".alert").fadeOut("slow");
}, 3000); // 3 seconds
</script>
<!-- end cta -->
<!-- Footer  -->
@include('front.footer')