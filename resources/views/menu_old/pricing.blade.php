@include('front.header')

<body class="pricing">
    @include('front.menu')
    <main class="pricing">

        <section class="section_margin  pricesec">
            <div class="container">
                <div class="pricemnaun">
                    <div class="title-block text-center">
                        <h1 style="font-size: 25px;" class="fw-bold">
                            <span class="span-theme">
                                Selecting the Best Pricing Plan for Your Pharmacy
                            </span>
                        </h1>
                        <h2>Pricing Plans Comparison</h2>
                    </div>
                    <div class="pricingcarddiv">
                        <div class="containerr">
                            <div class="row row-gap-3">
                                <!-- Basic Plan -->
                                @if(isset($suscriptionData))
                                @foreach($suscriptionData as $index => $list)

                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="card  {{ $index % 2 === 0 ? 'card-rama' : 'card-pro' }} text-center transition5s overflow-hidden">
                                        <div class="card-header py-4">
                                            <h2 class="fw-bold">
                                                ₹{{isset($list->annual_price) ? $list->annual_price :""}}/Year</h2>
                                            <h3 class="fw-bold mb-0">{{isset($list->name) ? $list->name :""}}</h3>
                                        </div>
                                        <div class="card-body px-0 transistion5s">
                                            <ul class="list-unstyled">
                                                @if(isset($list->enable_modules))
                                                <?php 
                                                    $moduleData = explode(',',$list->enable_modules);
                                                    ?>
                                                @foreach($moduleData as $listData)
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        {{ isset($listData) ? $listData :"" }}
                                                    </p>
                                                </li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="card-footer py-3">
                                            <a href="{{ route('book.training.index', ['id' => $list->id]) }}"
                                                class="btn btn-outline px-3 fw-semibold transistion5s">Book Demo</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif

                                <!-- Advanced Plan -->
                                <!-- <div class="col-lg-4 col-md-6 col-12">
                                    <div class="card card-pro text-center transition5s overflow-hidden">
                                        <div class="card-header py-4">
                                            <h2 class="fw-bold">₹6000/Year</h2>
                                            <h5 class="fw-bold mb-0">Advanced Plan</h5>
                                        </div>
                                        <div class="card-body px-0 transistion5s">
                                            <ul class="list-unstyled">
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        3 Users
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        1 GSTIN
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Unlimited Invoices
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Basic (all features)
                                                    </p>

                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        all reports
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        stock adjustment
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        staff management
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        purchase CSV upload
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Refill Reminders
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        barcode billing
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-footer py-3">
                                            <a href="book-training.php" class="btn btn-outline px-3 fw-semibold transistion5s">Book
                                                Demo</a>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Pro Plan -->
                                <!-- <div class="col-lg-4 col-md-6 col-12">
                                    <div class="card  card-blue text-center transition5s overflow-hidden">
                                        <div class="card-header py-4">
                                            <h2 class="fw-bold">₹15000/Year</h2>
                                            <h5 class="fw-bold mb-0">Pro Plan</h5>
                                        </div>
                                        <div class="card-body px-0 transistion5s">
                                            <ul class="list-unstyled">
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Unlimited Users
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        1 GSTIN
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Unlimited Invoices
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Basic and Advanced Features
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Loyalty Points
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        API integration
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        bulk Entry
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Cash Entry
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        CRM
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Refill Reminders
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Home Delivery
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        White Label
                                                    </p>
                                                </li>
                                                <li class="m-0">
                                                    <p class="fw-medium">
                                                        Patient App
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-footer py-3">
                                            <a href="book-training.php" class="btn btn-outline px-3 fw-semibold transistion5s">Book
                                                Demo</a>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
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
                    <div class="addonsdiv mt-5">
                        <!-- Add-Ons Section -->
                        <h4 class="fw-bold text-center">Add-Ons (Available for All Plans)</h4>
                        <div class="benifdiv00 mt-4">
                            <div class="d-grid grid-template-5">
                                <div class="gridcard">
                                    <div
                                        class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="star-outline"
                                                    class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                    role="img"></ion-icon>
                                            </div>
                                            <h5 class="card_header_txt fw-bold d-flex align-items-center gap-1">Loyalty
                                                Points</h5>
                                            <p class="fw-semibold">enhancing customer engagement & brand loyalty</p>
                                            <h5 class="fw-bold d-flex align-items-center gap-1">₹5000/year</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="gridcard">
                                    <div
                                        class="hover-div servicecoldiv h-100 serviceindustryslider transition5s p-4 overflow-hidden">
                                        <div class="servicecoldiv00 px-2">
                                            <div class="ioniconsdfiv mb-3">
                                                <ion-icon name="phone-portrait-outline"
                                                    class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000"
                                                    role="img"></ion-icon>
                                            </div>
                                            <h5 class="card_header_txt fw-bold d-flex align-items-center gap-1">white
                                                lable app</h5>
                                            <p class="fw-semibold">customizzed app for your brand</p>
                                            <h5 class="fw-bold d-flex align-items-center gap-1">₹1,00,000/App</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <section class="dtportsec section_margin">
            <div class="dataportsec">
                <div class="container">
                    <div class="dtaportingdiv">
                        <div class="">
                            <div class="h-100 dttbldivport text-white rounded-5 transition5s p-4 overflow-hidden">
                                <div class="servicecoldiv00 ">
                                    <div class="row justify-content-between align-items-center row-gap-3">
                                        <div class="col-lg-6 col-md-8 col-sm-12">
                                            <div class="dtprtdiv table-responsive">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-white">Porting <h3 class="fw-bold">₹2500
                                                                </h3>
                                                            </th>
                                                            <th class="text-white">Bill To Bill Porting <h3
                                                                    class="fw-bold">₹5,000</h3>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Inventory</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>
                                                            </td>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Sale Bill</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Customer</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>
                                                            </td>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Sale Return</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Distributor</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>

                                                            </td>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Purchase Bill</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-white">

                                                                <div class="d-flex">
                                                                    <p class="flex-grow-1">Purchase Bill Return</p>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 512 512" class="checkmark_icon">
                                                                        <path
                                                                            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z"
                                                                            fill="var(--themecolor)" />
                                                                        <path
                                                                            d="M369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"
                                                                            fill="white" />
                                                                    </svg>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <div class="ioniconsdfiv mb-3 text-end">
                                                <img src="{{asset('public/landing_design/images/data-porting.png')}}"
                                                    alt="" class="img-fluid">
                                                <!-- <ion-icon name="bar-chart-outline" class="fs-3 shadow p-3 rounded-5 md hydrated" style="color:#000000" role="img"></ion-icon> -->
                                            </div>
                                        </div>
                                        <p class="fw-bold text-end">* GST EXTRA</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('front.footer')

    <script>
    // Function to simulate a task's completion
    function onTaskComplete() {
        const checkmarkIcons = document.querySelectorAll('.checkmark_icon');

        checkmarkIcons.forEach(checkmarkIcon => {
            // Show the checkmark icon (if it's hidden)
            checkmarkIcon.style.display = 'inline'; // Change to inline or block

            // Add an "animate" class that triggers the animation
            checkmarkIcon.classList.add('animate');

            // Remove the class after the animation is complete
            setTimeout(() => {
                checkmarkIcon.classList.remove('animate');
            }, 300); // Duration should match the animation duration
        });
    }

    // Example usage of the function to trigger the animation
    // This could be tied to a button click, a form submission, or an event completion
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', (e) => {
            // Simulate task completion for demonstration:
            onTaskComplete();
        });
    });
    </script>