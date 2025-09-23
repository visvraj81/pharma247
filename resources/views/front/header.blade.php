<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<?php
// Function to get Meta Page Data
function getMetaPageData($url)
{
    return App\Models\MetaPage::where('url', $url)->first();
}

$metaPageData = null;
$title = $description = $keywords = '';

switch (Route::currentRouteName()) {
    case 'front.index':
        $metaPageData = getMetaPageData('Home');
        break;
    case 'product.features.index':
        $metaPageData = getMetaPageData('product & Features');
        break;
    case 'demotrain.index':
        $metaPageData = getMetaPageData('Demo & Training');
        break;
    case 'referandearn.index':
        $metaPageData = getMetaPageData('Refer & Earn');
        break;
    case 'blogs.index':
        $metaPageData = getMetaPageData('blogs');
        break;
    case 'cancellationpolicy.index':
        $metaPageData = getMetaPageData('Cancellation and Refund Policy');
        break;
    case 'pricing.index':
        $metaPageData = getMetaPageData('pricing');
        break;
    case 'aboutus.index':
        $metaPageData = getMetaPageData('about us');
        break;
    case 'contactus.index':
        $metaPageData = getMetaPageData('contact us');
        break;
    case 'privacy-policys':
        $metaPageData = getMetaPageData('privacy policy');
    case 'term-conditions':
        $metaPageData = getMetaPageData('term conditions');
        break;
}

if ($metaPageData) {
    $title = $metaPageData->meta_title ?? '';
    $description = $metaPageData->meta_description ?? '';
    $keywords = $metaPageData->meta_keywords ?? '';
    $og_title = $metaPageData->og_title ?? '';
    $og_description = $metaPageData->og_description ?? '';
    $tiwter_title = $metaPageData->tiwter_title ?? '';
    $tiwter_description = $metaPageData->tiwter_description ?? '';
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="Pharma24*7">
    <meta name='subject' content='Pharmacy Management Solutions'>
    <meta name='copyright' content="Pharma24*7">
    <meta name='classification' content="Healthcare and Pharmacy Solutions">
    <meta name='reply-to' content="inquiry@pharma247.in">
    <meta name='owner' content="Pharma24*7">
    <meta name='url' content="https://pharma247.in">

    <meta property="og:url" content="https://pharma247.in" />

    <meta property="og:title" content="{{@$og_title}}" />
    <meta property="og:description" content="{{@$og_description}}" />

    <meta property="og:image" content="https://medical.pharma247.in/pharmalogo.png" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Pharma24*7" />

    <meta name="twitter:site" content="@Pharma247">
    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:title" content="{{@$tiwter_title}}">
    <meta name="twitter:description" content="{{ @$tiwter_description}}">

    <meta name="twitter:image" content="{{ asset('public/imgpsh_fullsize_anim.png') }}">

    <meta property="business:contact_data:street_address" content="SF-14/B, Dharti City Complex, Kadi - 382715" />
    <meta property="business:contact_data:country_name" content="India" />
    <meta property="business:contact_data:email" content="inquiry@pharma247.in" />
    <meta property="business:contact_data:phone_number" content="+91 908 1111 247" />
    <meta property="business:contact_data:website" content="https://pharma247.in">
    <link rel="shortcut icon" href="{{ asset('public/imgpsh_fullsize_anim.png') }}" type="image/x-icon">

    <link rel="icon" type="image/png" href="{{ asset('public/imgpsh_fullsize_anim.png') }}" />

    <!--- End favicon-->
    <link rel="canonical" href="https://pharma247.in/" />
    <link rel="alternate" hreflang="x" href="https://pharma247.in/">

    <link
        href="https://fonts.googleapis.com/css2?family=Afacad:ital,wght@0,400..700;1,400..700&amp;family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&amp;family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;family=Instrument+Sans:ital,wght@0,400..700;1,400..700&amp;family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;display=swap"
        rel="stylesheet">

    <!-- End google font  -->

    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/aos.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/niceselect.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('public/landing_desgin/assets/css/app.min.css')}}">

    <!-- flatpicker -->
    <link rel="stylesheet" href="flatpicker/flatpickr-master/config/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

    <div class="preloader">
        <div class="preloader-inner">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="progress-bar-container">
        <div class="progress-bar"></div>
    </div>
    <div class="paginacontainer">
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
            <div class="top-arrow">
                <svg width="12" height="20" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.999999 1L8 8L1 15" stroke="#142D6F" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="lonyo-menu-wrapper">
        <div class="lonyo-menu-area text-center">
            <div class="lonyo-menu-mobile-top">
                <div class="mobile-logo">
                    <a href="{{route('front.index')}}">
                        <img src="{{asset('public/landing_desgin/assets/images/logo/logo.png')}}" alt="logo">
                    </a>
                </div>
                <button class="lonyo-menu-toggle mobile">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="lonyo-mobile-menu">
                <ul>
                    <li class="menu-item-has-children">
                        <a href="{{route('front.index')}}">Home</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{route('product.features.index')}}">Product & Features</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{route('pricing.index')}}">Pricing</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{route('demotrain.index')}}">Demo & Training</a>
                    </li>
                    <li>
                        <a href="{{route('contactus.index')}}">Contact Us</a>
                    </li>
                    <li class="d-block d-md-none px-3 py-1">
                        <a href="https://wa.me/YOUR_NUMBER" class="lonyo-default-btn sm-size w-100 text-center justify-content-center" target="_blank"
                            style="background-color: green; color: white; border-radius: 5px; display: inline-flex; align-items: center; gap: 5px; border: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                                <path
                                    d="M12 0c-6.627 0-12 5.373-12 12 0 2.09.537 4.046 1.472 5.763l-1.472 6.237 6.382-1.469c1.667.886 3.577 1.469 5.618 1.469 6.627 0 12-5.373 12-12s-5.373-12-12-12zm5.738 16.042c-.242.682-1.424 1.327-1.965 1.403-.503.073-1.161.104-1.89-.116-3.267-.979-5.715-4.217-5.901-4.401-.175-.183-1.408-1.868-1.408-3.561 0-1.694.864-2.528 1.172-2.871.308-.344.673-.429.897-.429.224 0 .449.002.646.012.209.011.49-.08.767.586.277.666.952 2.31 1.036 2.476.084.166.141.354.028.571-.114.217-.171.354-.342.544-.17.19-.354.419-.505.563-.171.163-.35.341-.151.671.198.33.882 1.456 1.899 2.358 1.306 1.152 2.42 1.502 2.747 1.609.327.107.519.089.712-.051.191-.14.82-.747 1.04-1.017.22-.27.436-.223.722-.134.287.089 1.823.861 2.135 1.017.312.157.519.232.597.365.079.133.079.773-.163 1.455z" />
                            </svg>
                            Whatsapp
                        </a>
                    </li>
                    <li class="d-block d-md-none px-3 py-1">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#bookdemomodal" class="lonyo-default-btn sm-size w-100 text-center justify-content-center"
                            style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;border: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5C3.897 4 3 4.897 3 6v14c0 1.103 0.897 2 2 2h14c1.103 0 2-0.897 2-2V6c0-1.103-0.897-2-2-2zM5 20V10h14l.001 10H5z">
                                </path>
                            </svg>
                            Book Demo
                        </a>
                    </li>
                    <li class="d-block d-md-none px-3 py-1">
                        <a href="tel:+1234567890" class="lonyo-default-btn sm-size w-100 text-center justify-content-center"
                            style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;border: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                                <path
                                    d="M20 15.5c-1.2 0-2.4-.2-3.5-.7-.5-.2-1.1 0-1.4.3l-2.2 2.2c-3.4-1.8-6.2-4.6-8-8l2.2-2.2c.4-.4.5-.9.3-1.4-.4-1.1-.7-2.3-.7-3.5C7 2 6 1 4.8 1H3C1.9 1 1 1.9 1 3c0 10.5 8.5 19 19 19 1.1 0 2-.9 2-2v-1.8c0-1.2-1-2.2-2-2.2z" />
                            </svg>
                            Call Us
                        </a>
                    </li>
                    <li class="d-block d-md-none px-3 py-1">
                        <a href="https://medical.pharma247.in/" class="lonyo-default-btn sm-size w-100 text-center justify-content-center"
                            style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px; border: none;  color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                                <path
                                    d="M10 2v2h4V2h2v2h3c1.103 0 2 .897 2 2v14c0 1.103-.897 2-2 2H5c-1.103 0-2-.897-2-2V6c0-1.103.897-2 2-2h3V2h2zm-5 6v12h14V8H5zm6 3h2v3h3l-4 4-4-4h3v-3z">
                                </path>
                            </svg>
                            Login
                        </a>
                    </li>
                </ul>
            </div>
            <!-- <div class="lonyo-mobile-menu-btn d-block d-md-none">
                <a href="https://wa.me/YOUR_NUMBER" class="lonyo-default-btn sm-size" target="_blank"
                    style="background-color: green; color: white; border-radius: 5px; display: inline-flex; align-items: center; gap: 5px; border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path
                            d="M12 0c-6.627 0-12 5.373-12 12 0 2.09.537 4.046 1.472 5.763l-1.472 6.237 6.382-1.469c1.667.886 3.577 1.469 5.618 1.469 6.627 0 12-5.373 12-12s-5.373-12-12-12zm5.738 16.042c-.242.682-1.424 1.327-1.965 1.403-.503.073-1.161.104-1.89-.116-3.267-.979-5.715-4.217-5.901-4.401-.175-.183-1.408-1.868-1.408-3.561 0-1.694.864-2.528 1.172-2.871.308-.344.673-.429.897-.429.224 0 .449.002.646.012.209.011.49-.08.767.586.277.666.952 2.31 1.036 2.476.084.166.141.354.028.571-.114.217-.171.354-.342.544-.17.19-.354.419-.505.563-.171.163-.35.341-.151.671.198.33.882 1.456 1.899 2.358 1.306 1.152 2.42 1.502 2.747 1.609.327.107.519.089.712-.051.191-.14.82-.747 1.04-1.017.22-.27.436-.223.722-.134.287.089 1.823.861 2.135 1.017.312.157.519.232.597.365.079.133.079.773-.163 1.455z" />
                    </svg>
                    Whatsapp
                </a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#bookdemomodal" class="lonyo-default-btn sm-size"
                    style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path
                            d="M19 4h-1V2h-2v2H8V2H6v2H5C3.897 4 3 4.897 3 6v14c0 1.103 0.897 2 2 2h14c1.103 0 2-0.897 2-2V6c0-1.103-0.897-2-2-2zM5 20V10h14l.001 10H5z">
                        </path>
                    </svg>
                    Book Demo
                </a>
                <a href="tel:+1234567890" class="lonyo-default-btn sm-size"
                    style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path
                            d="M20 15.5c-1.2 0-2.4-.2-3.5-.7-.5-.2-1.1 0-1.4.3l-2.2 2.2c-3.4-1.8-6.2-4.6-8-8l2.2-2.2c.4-.4.5-.9.3-1.4-.4-1.1-.7-2.3-.7-3.5C7 2 6 1 4.8 1H3C1.9 1 1 1.9 1 3c0 10.5 8.5 19 19 19 1.1 0 2-.9 2-2v-1.8c0-1.2-1-2.2-2-2.2z" />
                    </svg>
                    Call Us
                </a>
                <a href="https://medical.pharma247.in/" class="lonyo-default-btn sm-size"
                    style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px; border: none;  color: white; padding: 8px 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path
                            d="M10 2v2h4V2h2v2h3c1.103 0 2 .897 2 2v14c0 1.103-.897 2-2 2H5c-1.103 0-2-.897-2-2V6c0-1.103.897-2 2-2h3V2h2zm-5 6v12h14V8H5zm6 3h2v3h3l-4 4-4-4h3v-3z">
                        </path>
                    </svg>
                    Login
                </a>

            </div> -->
        </div>
    </div>
    <!-- End mobile menu -->
    <header class="site-header lonyo-header-section" id="sticky-menu">
        <div class="container-fluid">
            <div class="row gx-3 align-items-center justify-content-between">
                <div class="col-8 col-sm-auto ">
                    <div class="header-logo1 ">
                        <a href="{{route('front.index')}}">
                            <img src="{{asset('public/landing_desgin/assets/images/logo/logo.png')}}" alt="logo"
                                width="150px">
                        </a>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="lonyo-main-menu-item">
                        <nav class="main-menu menu-style1 d-none d-xl-block menu-left">
                            <ul>
                                <li>
                                    <a href="{{route('front.index')}}">Home</a>
                                </li>
                                <li>
                                    <a href="{{route('product.features.index')}}">Product & Features</a>
                                </li>
                                <li>
                                    <a href="{{route('pricing.index')}}">Pricing</a>
                                </li>
                                <li>
                                    <a href="{{route('demotrain.index')}}">Demo & Training</a>
                                </li>
                                <li>
                                    <a href="{{route('contactus.index')}}">Contact Us</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center justify-content-end lonyo-header-info-content">
                    <ul class="gap-2 align-items-center d-md-flex d-none">
                        <li>
                            <a href="https://wa.me/9081111247" target="_blank"
                                style="background-color: green; color: white; border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="white">
                                    <path
                                        d="M12 0c-6.627 0-12 5.373-12 12 0 2.09.537 4.046 1.472 5.763l-1.472 6.237 6.382-1.469c1.667.886 3.577 1.469 5.618 1.469 6.627 0 12-5.373 12-12s-5.373-12-12-12zm5.738 16.042c-.242.682-1.424 1.327-1.965 1.403-.503.073-1.161.104-1.89-.116-3.267-.979-5.715-4.217-5.901-4.401-.175-.183-1.408-1.868-1.408-3.561 0-1.694.864-2.528 1.172-2.871.308-.344.673-.429.897-.429.224 0 .449.002.646.012.209.011.49-.08.767.586.277.666.952 2.31 1.036 2.476.084.166.141.354.028.571-.114.217-.171.354-.342.544-.17.19-.354.419-.505.563-.171.163-.35.341-.151.671.198.33.882 1.456 1.899 2.358 1.306 1.152 2.42 1.502 2.747 1.609.327.107.519.089.712-.051.191-.14.82-.747 1.04-1.017.22-.27.436-.223.722-.134.287.089 1.823.861 2.135 1.017.312.157.519.232.597.365.079.133.079.773-.163 1.455z" />
                                </svg>
                                Whatsapp
                            </a>
                        </li>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#bookdemomodal"
                                style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="white">
                                    <path
                                        d="M19 4h-1V2h-2v2H8V2H6v2H5C3.897 4 3 4.897 3 6v14c0 1.103 0.897 2 2 2h14c1.103 0 2-0.897 2-2V6c0-1.103-0.897-2-2-2zM5 20V10h14l.001 10H5z">
                                    </path>
                                </svg>
                                Book Demo
                            </a>
                        </li>
                        <li>
                            <a href="tel:+919081111247"
                                style="border-radius: 5px; display: inline-flex; align-items: center; gap: 5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="white">
                                    <path
                                        d="M20 15.5c-1.2 0-2.4-.2-3.5-.7-.5-.2-1.1 0-1.4.3l-2.2 2.2c-3.4-1.8-6.2-4.6-8-8l2.2-2.2c.4-.4.5-.9.3-1.4-.4-1.1-.7-2.3-.7-3.5C7 2 6 1 4.8 1H3C1.9 1 1 1.9 1 3c0 10.5 8.5 19 19 19 1.1 0 2-.9 2-2v-1.8c0-1.2-1-2.2-2-2.2z" />
                                </svg>
                                Call Us
                            </a>
                        </li>
                        <li><a href="https://medical.pharma247.in/" target="_blank">Log in</a></li>
                    </ul>
                    <div class="lonyo-header-menu d-inline-block d-xl-none">
                        <nav class="navbar justify-content-between">
                            <!-- Brand Logo-->
                            <!-- mobile menu trigger -->
                            <button class="lonyo-menu-toggle ">
                                <span></span>
                            </button>
                            <!--/.Mobile Menu Hamburger Ends-->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>