@include('pharma.layout.header')
<!-- partial -->
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
        @yield('main')
        </div>
    </div>
    <!-- main-panel ends -->
</div>
@include('pharma.layout.footer')
@yield('js')

   