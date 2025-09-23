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
<div class="breadcrumb-wrapper light-bg">
    <div class="container">

        <div class="breadcrumb-content">
            <h1 class="breadcrumb-title pb-0 text-white">Pricing</h1>
            <div class="breadcrumb-menu-wrapper">
                <div class="breadcrumb-menu-wrap">
                    <div class="breadcrumb-menu">
                        <ul>
                            <li><a href="{{route('front.index')}}" class="text-white">Home</a></li>
                            >
                            <li aria-current="page" class="text-white">Pricing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- End breadcrumb -->
<div class="lonyo-section-padding10 position-relative">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="lonyo-section-title center">
            <h2 class="title">Pricing Plans</h2>
            <p class="mb-0">Selecting the Best Pricing Plan for Your Pharmacy</p>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="lonyo-pricing-wrap aos-init aos-animate" data-aos="fade-right" data-aos-duration="500">
                    <div class="lonyo-pricing-header">
                        <h4 class="text-white">Pharma24*7 All-In-One Plan</h4>
                    </div>
                    <div class="row p-4 mb-4 gap-4">
                      @if(isset($suscriptionData))
                      @foreach($suscriptionData as $list)
                      <div class="col-lg-5 p-4" style="background-color: white; border-radius: 15px;">
                          <h2 class="">₹ {{ isset($list->annual_price) ? number_format($list->annual_price, 0) : "" }}
                              /year</h2>
                          <span class=" d-block">{{ isset($list->name) ? $list->name : ""}}</span>
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
                                value="{{isset($suscriptionData[0]) ? $suscriptionData[0]->id :''}}" />
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
@include('front.footer')