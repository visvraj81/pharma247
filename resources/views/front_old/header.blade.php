<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // Function to get Meta Page Data
        function getMetaPageData($url) {
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

    <meta property="og:title" content="{{$og_title}}" />
    <meta property="og:description" content="{{$og_description}}" />

    <meta property="og:image" content="https://medical.pharma247.in/pharmalogo.png" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Pharma24*7" />

    <meta name="twitter:site" content="@Pharma247">
    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:title" content="{{$tiwter_title}}">
    <meta name="twitter:description" content="{{ $tiwter_description}}">

    <meta name="twitter:image" content="{{ asset('public/imgpsh_fullsize_anim.png') }}">

    <meta property="business:contact_data:street_address" content="SF-14/B, Dharti City Complex, Kadi - 382715" />
    <meta property="business:contact_data:country_name" content="India" />
    <meta property="business:contact_data:email" content="inquiry@pharma247.in" />
    <meta property="business:contact_data:phone_number" content="+91 908 1111 247" />
    <meta property="business:contact_data:website" content="https://pharma247.in">
    <link rel="shortcut icon" href="{{ asset('public/imgpsh_fullsize_anim.png') }}" type="image/x-icon" >
  
    <link rel="icon" type="image/png" href="{{ asset('public/imgpsh_fullsize_anim.png') }}"  />

    <link rel="canonical" href="https://pharma247.in/" />
    <link rel="alternate" hreflang="x" href="https://pharma247.in/">
    <link rel="stylesheet"
        href="{{ asset('public/landing_design/css/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css') }}?v={{ rand(1111, 9999) }}">
    <link rel="stylesheet" href="{{ asset('public/landing_design/css/style.css') }}?v2={{ rand(1111, 9999) }}">
    <link rel="stylesheet" href="{{ asset('public/landing_design/css/grid.css') }}?v2={{ rand(1111, 9999) }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- slick slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <!-- swiper slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.1/css/swiper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- animation  -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- flatpicker -->
    <link rel="stylesheet" href="flatpicker/flatpickr-master/config/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

     <style>
        /* Basic styles for the landing page */
      
        .landing-page {
            text-align: center;
        }

        .chatbot-btn {
            background-color: #628a2f;
            color: white;
            border: none;
            padding: 11px;
            cursor: pointer;
            font-size: 20px;
            border-radius: 50%;
            position: fixed;
            bottom: 30px;
            right: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
            z-index: 1000;
        }

        .chatbot-btn:hover {
            background-color: #628a2f;
        }

        /* Chatbot container */
        .chatbot-container {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 365px;
            background-color: #fff;
            border-radius: 9px;
            border: 1px solid #e1e1e1;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        .chatbot-header {
            background-color: #628a2f;
            color: white;
            padding: 15px;
            font-size: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 9px;
            border-top-right-radius: 9px;
            font-weight: bold;
        }

        .chatbot-header img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            padding: 5px 10px;
        }

        .chatbot-body {
            max-height: 391px;
            height: 500px;
            overflow-y: auto;
            padding: 15px;
            font-size: 15px;
            color: #333;
            background-color: #fafafa;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
            display: flex;
            align-items: center;
        }

        .bot-message {
            background-color: #e3e3e3;
            text-align: left;
            margin-right: auto;
            max-width: 70%;
            width: fit-content;
        }

        .user-message {
            background-color: #e3e3e3;
            color: #000000;
            text-align: right;
            margin-left: auto;
            margin-top: 15px;
            max-width: 70%;
            width: fit-content;
        }

        .admin-message {
            background-color: #e3e3e3;
            color: #000000;
            text-align: center;
            margin: 0 auto;
        }

        .admin-message img {
            border-radius: 50%;
            margin-right: 10px;
        }

        .message .icon {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-input-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-top: 1px solid #e1e1e1;
        }

        .user-input {
            width: calc(100% - 40px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            border-top: 1px solid lightgray;
            margin: auto;
            transition: all 0.3s ease;
        }

        .user-inputt {
            border: none;
            width: 100%;
        }
        .user-inputt:focus {
            outline: none ;
        }

        .user-input:focus {
            border-color: #4CAF50;
        }

        .send-icon {
            background-color: transparent;
            border: none;
            color: #628a2f;
            font-size: 18px;
            cursor: pointer;
        }

        /* Animation for smooth opening and closing of the chatbot */
        .chatbot-container.open {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            opacity: 1;
            visibility: visible;
            height: 550px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>