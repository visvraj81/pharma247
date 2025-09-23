@include('layouts.header')
<style>
    .brand-link .brand-image {
        max-height: 45px;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav d-none">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3a5e0b;">
            <div class="brand-link" style="border-bottom: 1px solid #3a5e0b;">
                <img src="{{asset('/public/pharma_logo.webp')}}" alt="AdminLTE Logo" class="brand-image elevation-3" style="background-color: white; width: 60%;">
                <span class="font-weight-light text-white" style="color: #3a5e0b !important;">Pharma</span>
            </div>

            <div class="sidebar">

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-header">Website Menu</li>
                        @can('pharma-list')
                        <li class="nav-item">
                            <a href="{{route('pharma.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-home"></i>
                                <p>
                                    Pharma Shop
                                </p>
                            </a>
                        </li>
                        @endcan
                       <li class="nav-item">
                            <a href="{{route('iteam.bluk.add')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-home"></i>
                                <p>
                                    Item Bulk Upload
                                </p>
                            </a>
                        </li>
                          <li class="nav-item">
                            <a href="{{route('iteam.lists')}}" class="nav-link text-white">
                               <i class="nav-icon fa fa-building-o"></i>
                              
                                <p>
                                   Recommended Item
                                </p>
                            </a>
                        </li>
                        @can('subscription-list')
                        <li class="nav-item">
                            <a href="{{route('subscription.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-bookmark"></i>
                                <p>
                                   Subscription
                                </p>
                            </a>
                        </li>
                        @endcan
                       <li class="nav-item">
                            <a href="{{route('blog-list')}}" class="nav-link text-white">
                                <i class="nav-icon fab fa-blogger-b"></i>
                                <p>
                                   Blog
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('blog.category')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-asterisk"></i>
                                <p>
                                   Blog Category
                                </p>
                            </a>
                        </li>
                        
                       <li class="nav-item">
                            <a href="{{route('logs.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-history"></i>
                                <p>
                                   Logs
                                </p>
                            </a>
                        </li>
                       <li class="nav-item">
                            <a href="{{route('faq-list')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-question-circle"></i>
                                <p>
                                   FAQ
                                </p>
                            </a>
                        </li>
                        @if((isset(Auth::user()->roles[0]->name)) && (Auth::user()->roles[0]->name == 'Admin'))
                        <!-- <li class="nav-item">
                            <a href="{{route('slider.index')}}" class="nav-link text-white">
                                <i class="nav-icon 	fas fa-images"></i>
                                <p>
                                    Slider
                                </p>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a href="{{route('video.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-youtube-play"></i>
                                <p>
                                    Home Video
                                </p>
                            </a>
                        </li>
                        @endif
                        @can('privacy-policy')
                        <li class="nav-item">
                            <a href="{{ route('privacy-policy') }}" class="nav-link text-white">
                                <i class="nav-icon fas fa-shield-alt"></i>
                                <p>
                                    Privacy Policy
                                </p>
                            </a>
                        </li>
                       <li class="nav-item">
                            <a href="{{ route('refund-cancellation-data') }}" class="nav-link text-white">
                                 <i class="nav-icon fa fa-undo"></i>
                                <p>
                                   Refund Cancellation
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('term.conditions.admin')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-cubes"></i>
                                <p>
                                  Term & Conditions
                                </p>
                            </a>
                        </li>
                        @endcan

                        @can('agent-list')
                        <li class="nav-item">
                            <a href="{{route('agent.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-user-secret"></i>
                                <p>
                                    Agent
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('support-list')
                        <li class="nav-item">
                            <a href="{{ route('support.index') }}" class="nav-link text-white">
                                <i class="nav-icon fa fa-question-circle"></i>
                                <p>Support Ticket</p>
                            </a>
                        </li>
                        @endcan
                        @can('transction-list')
                        <li class="nav-item">
                            <a href="{{route('transction.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-money-bill"></i>
                                <p>
                                    Transactions
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('lead-list')
                        <li class="nav-item">
                            <a href="{{route('offlinerequest.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-share"></i>
                                <p>
                                    Lead
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('emailqueries-list')
                        <li class="nav-item">
                            <a href="{{route('emailqueries.index')}}" class="nav-link text-white">
                                <i class="nav-icon fa fa-envelope"></i>
                                <p>
                                    Email Queries
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('user-list')
                        <li class="nav-item">
                            <a href="{{ route('superadmin.index') }}" class="nav-link text-white">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    User
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('role-list')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link text-white">
                                <i class="nav-icon fa fa-cubes"></i>
                                <p>
                                    Role
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('profile-edit')
                        <li class="nav-item">
                            <a href="{{ route('profile.index',Auth::user()->id) }}" class="nav-link text-white">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    Profile
                                </p>
                            </a>
                        </li>
                        @endcan
                       <li class="nav-item">
                            <a href="{{route('page_meta_tags')}}" class="nav-link text-white">
                                <i class="nav-icon 	fa fa-database"></i>
                                <p>
                                    Page Meta Tags 
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">Mobile Menu</li>
                        @can('banner-list')
                        <li class="nav-item">
                            <a href="{{ route('banner.index') }}" class="nav-link text-white">
                                <i class="nav-icon fa fa-image"></i>
                                <p>
                                    Banner
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('youtue-list')
                        <li class="nav-item">
                            <a href="{{ route('youtue-list') }}" class="nav-link text-white">
                                <i class="nav-icon fa fa-youtube-play"></i>
                                <p>
                                    Youtube
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('reference')
                        <li class="nav-item">
                            <a href="{{route('reference.index')}}" class="nav-link text-white">
                                <i class="nav-icon fab fa-accusoft"></i>
                                <p>
                                    Reference
                                </p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </nav>

            </div>

        </aside>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">

                    @yield('main')
                </div>
            </div>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
        @include('layouts.footer')
        @yield('js')
    </div>
</body>