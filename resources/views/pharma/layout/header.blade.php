<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pharma</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{asset('/public/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/vendors/base/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('/public/images/favicon.png')}}" />
    <link rel="stylesheet" href="{{asset('/public/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
</head>

<body>
    <div class="container-scroller">
        <div class="row p-0 m-0 proBanner" id="proBanner">
            <div class="col-md-12 p-0 m-0">
                <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
                    <div class="ps-lg-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#" target="_blank" class="btn me-2 buy-now-btn border-0"></a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="https://www.bootstrapdash.com/product/kapella-admin-pro/"><i class="mdi mdi-home me-3 text-white"></i></a>
                        <button id="bannerClose" class="btn border-0 p-0">
                            <i class="mdi mdi-close text-white me-0"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial:partials/_horizontal-navbar.html -->
        <div class="horizontal-menu">
            <nav class="navbar top-navbar col-lg-12 col-12 p-0">
                <div class="container-fluid">
                    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
                    </div>
                </div>
            </nav>
            <nav class="bottom-navbar">
                <div class="container">
                    <ul class="nav page-navigation">
                        <li class="nav-item d-flex">
                            <a class="nav-link" href="{{route('home')}}">
                                <i class="mdi mdi-file-document-box menu-icon"></i>
                                <span class="menu-title">Item Master</span>
                            </a>
                            <a class="nav-link" href="{{route('distributer')}}" style="margin-left: 50px;">
                                <i class="mdi mdi-file-document-box menu-icon"></i>
                                <span class="menu-title">Add Distributer</span>
                            </a>
                            <a class="nav-link" href="{{route('purches.add')}}" style="margin-left: 50px;">
                                <i class="mdi mdi-file-document-box menu-icon"></i>
                                <span class="menu-title">Add Purches</span>
                            </a>
                            
                            <a class="nav-link" href="{{route('purches.return')}}" style="margin-left: 50px;">
                                <i class="mdi mdi-file-document-box menu-icon"></i>
                                <span class="menu-title">Purches Return</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>