@include('front.header')

<style>
p {
    font-size: 15px;
}
</style>
<?php 
  $privacyPolicy = App\Models\PrivacyPolicy::first();
?>

<body class="privacy-policy">
    @include('front.menu')
    <main class="privacy-policy-main">
        <div class="container">
            <div class="section_margin">
                <div class="container">
                    <div class="policy-section rounded-3 p-4">
                        <div class="title-block text-center mb-4">
                            <h1 class="fw-bold"><span>Privacy Policy for
                                    Pharma24*7</span></h1>
                        </div>
                        <div class="policy-content">
                            <p class="mb-4">
                                <?php 
                                    if (isset($privacyPolicy->description)) {
                                        echo htmlspecialchars_decode($privacyPolicy->description);
                                    } else {
                                        echo "Privacy policy description not available.";
                                    }
                                    ?>
                            </p>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('front.footer')