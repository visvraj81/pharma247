@include('front.header')
<style>
.lonyo-section-padding7 {
    display: none;
}

.lonyo-cta-section {
    display: none;
}
</style>
<style>
p {
    font-size: 20px;
}
</style>
<div class="breadcrumb-wrapper light-bg">
    <div class="container">

        <div class="breadcrumb-content">
            <h1 class="breadcrumb-title pb-0 text-white">Privacy Policy</h1>
            <div class="breadcrumb-menu-wrapper">
                <div class="breadcrumb-menu-wrap">
                    <div class="breadcrumb-menu">
                        <ul>
                            <li><a href="{{route('front.index')}}" class="text-white">Home</a></li>
                            >
                            <li aria-current="page">Privacy Policy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- End breadcrumb -->
<?php 
  $privacyPolicy = App\Models\PrivacyPolicy::first();
?>

<div class="lonyo-section-padding">
    <div class="integration-shape"></div>
    <div class="container">
        <div class="lonyo-integration-d-wrap">

            <div class="lonyo-default-content pb-35">
                <?php 
                    if (isset($privacyPolicy->description)) {
                        echo htmlspecialchars_decode($privacyPolicy->description);
                    } else {
                        echo "Privacy policy description not available.";
                    }
                    ?>
            </div>

        </div>
    </div>
</div>
@include('front.footer')