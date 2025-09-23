@include('front.header')
<style>
.lonyo-section-padding7 {
    display: none;
}

.lonyo-cta-section {
    display: none;
}
</style>
<div class="breadcrumb-wrapper light-bg">
    <div class="container">

      <div class="breadcrumb-content">
        <h1 class="breadcrumb-title pb-0 text-white">Refund & Cancellation</h1>
        <div class="breadcrumb-menu-wrapper">
          <div class="breadcrumb-menu-wrap">
            <div class="breadcrumb-menu">
              <ul>
                <li><a href="{{route('front.index')}}" class="text-white">Home</a></li>
                >
                <li aria-current="page">Refund & Cancellation</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End breadcrumb -->
<?php 
$refundData = App\Models\RefundCancellation::first();
?>
  <div class="lonyo-section-padding">
    <div class="integration-shape"></div>
    <div class="container">
      <div class="lonyo-integration-d-wrap">
        <div class="lonyo-default-content pb-35">
          <p class="mb-0">
          <?php 
              if (isset($refundData->refund_cancellation)) {
                  echo htmlspecialchars_decode($refundData->refund_cancellation);
              } else {
                  echo "description not available.";
              }
              ?></p>
        </div>
      
      </div>
    </div>
  </div>
@include('front.footer')