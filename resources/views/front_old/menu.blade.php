<header>
    <?php
    $UserData = \App\Models\User::with('roles')->first();
    ?>
    <div class="headernav page-header">
        <div class="topheader bg-theme py-2 text-center">
            <a href="tel:+91 908 1111 247" class="fw-medium text-capitalize calllink text-white">call for demo: {{ isset($UserData->phone_number) ? $UserData->phone_number :""}}</a>
        </div>
        <div class="container-fluid px-sm-5">
            <nav class="navbar navbar-expand-xxl">
                <div class="d-flex">
                    <!-- <button class="navbar-toggler border-0" data-bs-toggle="modal" data-bs-target="#navmodal">
                            <i class="fa-solid fa-bars-staggered"></i>
                        </button> -->
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#navmodal" aria-controls="navmodal">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <a class="navbar-brand col-md-12 col-sm-6 col-6" href="{{ url('/') }}">
                        <img src="{{asset('public/landing_design/images/logo.png')}}" class="img-fluid" width="150" alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}">
                    </a>
                </div>
                <div class="d-flex d-xxl-none gap-1" role="search">
                    <a href="https://medical.pharma247.in/" class="btn btn-outline-themegreen p-2 h-auto rounded-2" type="submit"> <span
                            class="d-none d-sm-block px-3">login</span> <span
                            class="d-flex align-items-center justify-content-center d-sm-none"><ion-icon
                                name="log-out-outline" class="fs-3"></ion-icon></span></a>
                    <a href="https://medical.pharma247.in/Register" class="btn theme-btn p-2 h-auto rounded-2" type="submit"> <span
                            class="d-none d-sm-block px-3">signup</span> <span
                            class="d-flex align-items-center justify-content-center d-sm-none"><ion-icon
                                name="person-add-outline" class="fs-3"></ion-icon></span></a>

                </div>

                <?php
                // Get the current page filename
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>

                <div class="collapse navbar-collapse d-none d-xxl-block text-capitalize" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-xxl-0 gap-3 ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ \Request::route()->getName() == 'product.features.index' ? 'active' : '' }}" href="{{ route('product.features.index') }}">product & Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ \Request::route()->getName() == 'pricing.index' ? 'active' : '' }}" href="{{ route('pricing.index') }}">pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ \Request::route()->getName() == 'demotrain.index' ? 'active' : '' }}" href="{{ route('demotrain.index') }}">Demo & Training</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ \Request::route()->getName() == 'contactus.index' ? 'active' : '' }}" href="{{ route('contactus.index') }}">contact us</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="aboutus.php">about us</a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="referandearn.php">Refer & Earn</a>
                        </li> -->
                        <!-- <li class="nav-item">
                                <a class="nav-link" href="consumerapp.php">Consumer App </a>
                            </li> -->
                        <!-- <li class="nav-item">
                                 <a class="nav-link" href="solutions.php">solutions</a>
                             </li> -->
                        <!-- <li class="nav-item">
                                <a class="nav-link" href="faq.php">FAQ</a>
                            </li> -->
                    </ul>
                    <div class="d-flex gap-1" role="search">
                        <a href="https://medical.pharma247.in/" class="btn btn-outline-themegreen px-4 rounded-2" type="submit">login </a>
                        <a href="https://medical.pharma247.in/Register" class="btn theme-btn px-4 rounded-2" type="submit">Signup </a>
                    </div>
                </div>

            </nav>
        </div>
    </div>
</header>


<div class="offcanvas offcanvas-start" tabindex="-1" id="navmodal" aria-labelledby="navmodalLabel">
    <div class="offcanvas-header">
        <div class="offcanvas-title" id="navmodalLabel">
            <a class="navbar-brand col-md-12 col-sm-6 col-6" href="{{ url('/') }}">
                <img src="{{asset('public/landing_design/images/logo.png')}}" class="img-fluid" width="150"  alt="{{ 'Pharma24*7' }}"  title="{{ 'Pharma24*7' }}">
            </a>
        </div>
        <button type="button" class="btn bg-transparent border-0 " data-bs-dismiss="offcanvas"
            aria-label="Close"><ion-icon name="close-outline" class="fs-3"></ion-icon></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item m-0">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item m-0">
                <a class="nav-link {{ \Request::route()->getName() == 'product.features.index' ? 'active' : '' }}"  href="{{ route('product.features.index') }}">product & Features</a>
            </li>
            <li class="nav-item m-0">
                <a class="nav-link {{ \Request::route()->getName() == 'pricing.index' ? 'active' : '' }}" href="{{ route('pricing.index') }}">pricing</a>
            </li>
            <li class="nav-item m-0">
                <a class="nav-link {{ \Request::route()->getName() == 'demotrain.index' ? 'active' : '' }}" href="{{ route('demotrain.index') }}">Demo & Training</a>
            </li>
            <li class="nav-item m-0">
                <a class="nav-link {{ \Request::route()->getName() == 'contactus.index' ? 'active' : '' }}" href="{{ route('contactus.index') }}">contact us</a>
            </li>
            <!-- <li class="nav-item m-0">
                    <a class="nav-link" href="consumerapp.php">Consumer App </a>
                </li> -->
            <!-- <li class="nav-item m-0">
                                 <a class="nav-link" href="solutions.php">solutions</a>
                             </li> -->
            <!-- <li class="nav-item m-0">
                    <a class="nav-link" href="faq.php">FAQ</a>
                </li> -->
        </ul>

    </div>
</div>