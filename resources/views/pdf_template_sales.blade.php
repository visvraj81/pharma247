<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
  <meta charset="utf-8">
  <title>Pharma 24/7</title>
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
  <link rel="shortcut icon" sizes="192x192" href="https://pharma247.in/public/imgpsh_fullsize_anim.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    @page {
      margin: 0cm !important;
      font-family: 'Manrope';
    }

    * {
      font-family: Tahoma, Verdana, Helvetica, sans-serif;
      font-style: normal;
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }

    body.A5-print-page {
      margin: 0cm !important;
      padding: 10px;
      position: relative !important;
    }

    .invoice-container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
    }

    .header {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid black;
      padding-bottom: 5px;
    }

    .about_pharmacy {
      display: flex;
      justify-content: flex-start;
      width: 500px;
      align-items: flex-start;
      margin-right: 10px;
      border-right: 1px solid black;
    }

    .about_invoice {
      flex: 1;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      position: relative;
    }

    /* .about_invoice:before { position: absolute; content: ""; width: 1px; left: -19px; height: 100%; right: 0; background: black; } */
    .logo {
      margin-right: 10px;
      width: 70px;
      height: 70px;
    }

    .logo img {
      width: 70px;
      height: 70px;
    }

    p {
      margin: 0;
      padding: 0;
    }

    .aitem.item_name a {
      color: black;
    }

    .pharmacy_details .pharmacy_name {
      font-size: 22px;
      line-height: 24px;
      font-weight: 600;
      color: black;
      margin-bottom: 3px;
    }

    .pharmacy_details .address {
      font-weight: 400;
    }

    .pharmacy_details {
      padding-right: 10px;
    }

    .pharmacy_details p {
      font-size: 12px;
    }

    .pharmacy_details .kyc_details {
      list-style: none;
      display: flex;
      align-items: center;
    }

    .pharmacy_details .kyc_details li {
      font-size: 9px;
      font-weight: 600;
      /* margin-right: 10px; */
      text-transform: uppercase;
      position: relative;
    }

    .pharmacy_details .kyc_details li.font-9 {
      font-size: 9px
    }

    .pharmacy_details .kyc_details li.font-7 {
      font-size: 10px
    }

    .pharmacy_details .kyc_details li label {
      font-weight: 400;
      margin-right: 5px;
    }

    .invoice_details .label {
      font-size: 16px;
      font-weight: 600;
    }

    .invoice_details .label label {
      display: flex;
      font-size: 12px;
      font-weight: 600;
    }

    .invoice_details p.order_methodname {
      font-size: 12px;
    }

    .invoice_details p {
      font-size: 12px;
    }

    .QR_details img {
      width: 55px;
    }

    .customer-details ul {
      list-style: none;
      display: flex;
      align-items: center;
    }

    .customer-details ul li {
      font-size: 10px;
      text-transform: uppercase;
      padding-right: 10px;
      line-height: 14px;
    }

    .customer-details.small-font ul li {
      font-size: 8px;
      text-transform: uppercase;
      padding-right: 10px;
    }

    .customer-details ul li label {
      font-weight: 600;
      margin-right: 5px;
    }

    .customer-details {
      border-bottom: 1px solid black;
      padding: 3px 0;
      background: #00000014;
    }

    .items-section .items {
      display: flex;
      justify-content: space-between;
      padding: 2px 0;
      border-bottom: 1px dashed #00000042;
    }

    .items.heading {
      border-bottom: 1px solid black;
      font-size: 12px;
    }

    .heading .aitem {
      font-weight: 600;
      font-size: 10px;
    }

    .items-section .items .aitem {
      font-size: 10px;
    }

    .items-section .items .aitem.item_name {
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
      width: 165px;
      text-align: left;
    }

    .items-section .items-list {
      border-bottom: 1px solid black;
      min-height: 295px;
      position: relative;
    }

    .items.body {
      border-bottom: 1px dashed #00000042;
      padding: 3px 0;
    }

    .aitem.sr_num {
      width: 20px !important;
    }

    .aitem.manuf {
      width: 35px;
    }

    .aitem.hsn {
      width: 67px;
      padding-left: 5px;
    }

    .aitem.packing {
      width: 55px;
      text-transform: capitalize;
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
    }

    .aitem.location {
      width: 43px;
      text-transform: uppercase;
    }

    .aitem.batch {
      width: 80px;
    }

    .aitem.expiry {
      width: 33px;
    }

    .aitem.mrp {
      width: 45px;
    }

    .aitem.qty {
      width: 25px;
    }

    .aitem.discount_price {
      width: 45px;
    }

    .aitem.gst {
      width: 25px;
    }

    .aitem.amount {
      width: 45px;
    }

    .im-sr-no {
      font-size: 10px;
      width: 20px;
    }

    .body .aitem.manuf,
    .body .aitem.hsn,
    .body .aitem.item_name {
      text-transform: uppercase;
    }

    .footer {
      border-bottom: 1px solid black;
      display: flex;
      padding: 4px 0;
    }

    .footer .pharmacy-signature img {
      height: 35px;
    }

    .footer .terms-conditions {
      width: 218px;
      border-right: 1px solid black;
      padding-right: 10px;
    }

    .footer .terms-conditions label {
      display: block;
      font-size: 12px;
      font-weight: 600;
    }

    .footer .terms-conditions p {
      font-size: 9px;
      line-height: 12px;
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .footer .pharmacy-signature {
      display: flex;
      align-items: center;
      flex-direction: column;
      justify-content: flex-end;
      padding: 0 10px;
      width: 80px;
    }

    .gst-bifurcation {
      width: 130px;
      /* border-left: 1px solid black; */
      padding: 0 10px;
      display: flex;
      /* flex-direction: column; */
      justify-content: space-around;
    }

    .gst-bifurcation p {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
    }

    .footer .pharmacy-signature label {
      font-size: 12px;
      margin-top: 14px;
      font-weight: 600;
    }

    .invoice-total-bifurcation {
      width: 140px;
      border-right: 1px solid black;
      border-left: 1px solid black;
      padding: 0 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-around;
    }

    .invoice-total-bifurcation p {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
    }

    .footer .net-payable {
      width: 350px;
      padding-left: 10px;
    }

    .footer .net-payable .net-amount {
      margin-bottom: 4px;
    }

    .net-payable .net-amount label {
      font-weight: 600;
      font-size: 16px;
    }

    .net-payable .net-amount {
      display: flex;
      justify-content: space-between;
    }

    .net-payable .loyalty-saving {
      display: flex;
    }

    .net-payable .loyalty-saving span {
      font-size: 10px;
    }

    .loyalty-saving span {
      text-align: right;
      display: block;
    }

    /* .invoice-total-bifurcation p span { font-size: 10px; font-weight: 600; } */
    .invoice_details p.order_datetime {
      font-size: 12px;
      font-weight: 600;
    }

    .invoice_details p.order_number {
      font-size: 12px;
      font-weight: 600;
    }

    .evital-footer {
      display: flex;
      justify-content: space-between;
      padding-top: 4px;
    }

    .footer-logo {
      width: 16px;
      filter: grayscale(100%);
      margin-right: 5px;
      height: 16px;
      border-radius: 50%;
    }

    .evital-footer .custom-message p {
      font-size: 10px;
      font-weight: 600;
    }

    .evital-footer .custom-message {
      width: auto;
    }

    .evital-footer .branding {
      flex: 1;
    }

    .evital-footer .branding ul {
      list-style: none;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .evital-footer .branding ul li {
      font-size: 10px;
      margin-left: 10px;
      font-weight: 600;
    }

    .app-icons {
      display: flex;
    }

    .app-icons img {
      width: 13px;
      margin-left: 8px;
    }

    @media print {
      .invoice-container {
        position: relative;
        max-width: 850px;
        margin: 0 auto;
        padding: 10px;
        border: 0;
      }
    }
    
    .items-section {
      display: flex;
      flex-direction: column;
    }

    .items.heading {
      display: flex;
      font-weight: bold;
      background-color: #f1f1f1;
      text-align: left;
    }

    .items.body {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #ccc;
      padding: 10px 0;
    }

    .aitem {
      flex: 1;
      text-align: left;
      padding: 0 10px;
      min-width: 50px;
    }

    .aitem.sr_num {
      text-align: center;
    }

    .aitem.mrp,
    .aitem.discount_price,
    .aitem.amount {
      text-align: right;
    }

    .aitem.qty {
      text-align: center;
    }

    .invoice-bg-image {
      position: absolute;
      text-align: center;
      left: 0;
      right: 0;
      top: 25%;
      z-index: -99;
      opacity: 0.5;
    }

    .aitem.item_name {
      flex: 2;
      text-align: left;
      padding: 0 10px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .items.heading .aitem.item_name {
      font-weight: bold;
      white-space: normal;
    }

    .aitem.manuf,
    .aitem.packing,
    .aitem.batch,
    .aitem.expiry {
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .aitem.item_name {
      flex: 2;
      text-align: left;
      padding: 0 10px;
      white-space: normal;
      /* Allow the text to wrap to the next line */
      overflow: visible;
      /* Ensure text is not cut off */
      text-overflow: unset;
      /* Disable text truncation */
    }
    
     .items-section {
    display: flex;
    flex-direction: column;
  }

  /* Header row styling */
  .items.heading {
    display: flex;
    font-weight: bold;
    background-color: #f1f1f1;
    text-align: left;
  }

  /* Body rows styling */
  .items.body {
    display: flex;
    align-items: flex-start; /* Align items to the start to accommodate multi-line text */
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
    flex-wrap: wrap; /* Allow items to wrap if necessary */
  }

  /* General item styling */
  .aitem {
    flex: 1;
    padding: 0 10px;
    min-width: 50px;
    box-sizing: border-box; /* Ensure padding is included in the width */
  }

  /* Specific alignments */
  .aitem.sr_num {
    text-align: center;
  }

  .aitem.mrp,
  .aitem.discount_price,
  .aitem.amount {
    text-align: right;
  }

  .aitem.qty {
    text-align: center;
  }

  /* Item Name styling */
  .aitem.item_name {
    flex: 2; /* Allow more space for item names */
    text-align: left;
    padding: 0 10px;
    white-space: normal; /* Allow text to wrap */
    overflow: visible;   /* Ensure text is not cut off */
    text-overflow: unset; /* Disable text truncation */
    word-wrap: break-word; /* Break long words to prevent overflow */
  }

  /* Batch Name styling */
  .aitem.batch {
    text-align: center;
    white-space: normal; /* Allow text to wrap */
    overflow: visible;   /* Ensure text is not cut off */
    text-overflow: unset; /* Disable text truncation */
    word-wrap: break-word; /* Break long words to prevent overflow */
  }

  /* Heading specific Item Name styling */
  .items.heading .aitem.item_name {
    font-weight: bold;
    white-space: normal; /* Allow heading to wrap for better visibility */
  }

  /* Other item fields styling */
  .aitem.manuf,
  .aitem.packing,
  .aitem.expiry {
    text-align: center;
    white-space: normal; /* Allow text to wrap */
    overflow: visible;   /* Ensure text is not cut off */
    text-overflow: unset; /* Disable text truncation */
    word-wrap: break-word; /* Break long words to prevent overflow */
  }    
</style>

</head>

<body class="A5-print-page">
  <div class="invoice-container">
  	<h4 style="text-align: center;">Bill of Supply</h4>
    <div class="container">
      <div class="header">
        <div class="about_pharmacy">
          @if(!empty($salesDetails['logo']))
          <div class="logo">
            <img src="{{ isset($salesDetails['logo']) ? $salesDetails['logo'] : '' }}" border="0"
              alt="Chemist Name" style="width: 70px; height:70px" />
          </div>
          @endif
          <div class="pharmacy_details">
            <p class="pharmacy_name">
              {{ isset($salesDetails['user_name']) ? $salesDetails['user_name'] :"-"}}
            </p>
            <p class="address">
              {{ isset($salesDetails['address']) ? $salesDetails['address'] :"-"}}
            </p>
            <p class="Mobile" style="font-weight: 400; font-size: 12px; margin-top: 5px;">
              <b>M.</b> {{ isset($salesDetails['phone_number']) ? $salesDetails['phone_number'] :"-"}}
            </p>
          </div>
        </div>
        <div class="about_invoice">
          <div class="invoice_details">
            <p class="label">
              Sale Invoice
              <label class="bill_number">Bill No :
                {{ isset($salesDetails['bill_no']) ? $salesDetails['bill_no'] :"-"}}
              </label>
            </p>
            <p class="order_number order_methodname">Payment :
              {{ isset($salesDetails['payment_name']) ? $salesDetails['payment_name'] : "-" }}
            </p>
            <p class="order_number order_methodname">Type :
              {{ isset($salesDetails['pickup']) ? $salesDetails['pickup'] :"-"}}
            </p>
            <p class="order_datetime">
              {{ isset($salesDetails['bill_date']) ? Carbon\Carbon::parse($salesDetails['bill_date'])->format('d-m-Y H:i A') : "-" }}
            </p>
          </div>
        </div>
        <img src="https://testadmin.pharma247.in/pharmacy_logo/website_qr_code.png" style="width: 80px;">
      </div>
      <div class="customer-details">
        <ul>
          <li>
            <label>LICENSE 20 :</label>{{ isset($salesDetails['license_20']) ? $salesDetails['license_20'] :"-"}}
          </li>
          <li>
            <label>LICENSE 21 :</label>{{ isset($salesDetails['license_21']) ? $salesDetails['license_21'] :"-"}}
          </li>
          <li>
            <label>FSSAI :</label>{{ isset($salesDetails['fssai_no']) ? $salesDetails['fssai_no'] :"-"}}
          </li>
          <li>
            <label>GSTIN :</label>{{ isset($salesDetails['gst_pan']) ? $salesDetails['gst_pan'] :"-"}}
          </li>
        <div style="margin-left: 100px;">
            <i class="fa-brands fa-google-play"></i>
            <i class="fa-brands fa-apple"></i>
        </div>
        </ul>
      </div>
      <div class="customer-details">
        <ul>
          <li>
            <label>Customer Name :</label>{{ isset($salesDetails['customer_name']) ? $salesDetails['customer_name'] :"-"}}
          </li>
          <li>
            <label>M :</label>{{ isset($salesDetails['mobile_numbr']) ? $salesDetails['mobile_numbr'] :"-"}}
          </li>
          <li>
            <label>ADDRESS :</label>{{ isset($salesDetails['customer_address']) ? $salesDetails['customer_address'] :"-"}}
          </li>
          <li>
            <label>Doctor :</label>{{ isset($salesDetails['doctor_name']) ? $salesDetails['doctor_name'] :"-"}}
          </li>
        </ul>
      </div>
      <div class="items-section">
        <!-- Heading Row -->
        <div class="items heading">
          <div class="aitem sr_num">Sr.</div>
          <div class="aitem">Item Name</div>
          <div class="aitem">Unit</div>
          <div class="aitem manuf">Batch</div>
          <div class="aitem packing">Exp</div>
          <div class="aitem mrp">MRP</div>
          <div class="aitem qty">QTY.</div>
          <!-- <div class="aitem">GST</div> -->
          <div class="aitem">Location</div>
          <div class="aitem amount">Amount</div>
        </div>
        <!-- Items List -->
        <div class="items-list" style="min-height:260px; position: relative;">
          <!-- Background Image -->
          @if(!empty($salesDetails['logo']))
          <div class="invoice-bg-image">
            <img src="{{ isset($salesDetails['logo']) ? $salesDetails['logo'] : '' }}" style="border-radius: 50%; height: 150px; opacity: inherit;" alt="Background Logo">
          </div>
		  @endif
          <!-- Check if item list exists -->
          @if(isset($salesDetails['sales_item']))
            <?php $i = 1; ?>
            @foreach($salesDetails['sales_item'] as $listData)
              <div class="items body">
                <div class="aitem sr_num">{{ $i }}</div>
                <div class="aitem">{{ $listData['item_name'] ?? "" }}</div>
                <div class="aitem">{{ $listData['unit'] ?? "" }}</div>
                <div class="aitem manuf">{{ $listData['batch'] ?? "" }}</div>
                <div class="aitem packing">{{ $listData['exp'] ?? "" }}</div>
                <div class="aitem mrp">{{ $listData['mrp'] ?? "" }}</div>
                <div class="aitem qty">{{ $listData['qty'] ?? "" }}</div>
                <!-- <div class="aitem">{{ $listData['gst'] ?? "" }}</div> -->
                <div class="aitem">{{ $listData['location'] ?? "" }}</div>
                <div class="aitem amount">{{ $listData['net_rate'] ?? "" }}</div>
              </div>
              <?php $i++; ?>
            @endforeach
          @endif
        </div>
      </div>

      <div class="footer">
        <div class="terms-conditions" style="height: 70px;">
          @if(!empty($salesDetails['loyalti_point']))
          <p style="border-bottom: 1px solid #000; margin-bottom: 3px;">Loyalti Point : {{ $salesDetails['loyalti_point'] ?? '0' }}</p>
          @endif
          <label>Terms & Conditions</label>
          <p>Return/exchange within 15 days with bill. No damage/cutting.</p>
        </div>
        <div class="gst-bifurcation">
          <p><label><b>Sign</b></label></p>
        </div>
        <div class="invoice-total-bifurcation">
          <p>
            <span>Total Item(s)</span>
            <label>{{ isset($salesDetails['total_iteam']) ? $salesDetails['total_iteam'] : "0" }}</label>
          </p>
          <p>
            <span>Total MRP</span>
            <label>{{ isset($salesDetails['total_amount']) ? $salesDetails['total_amount'] : "Rs. 0.0" }}</label>
          </p>
          <p style="visibility: hidden;">
            <span>Round off.</span>
            <label>{{ isset($salesDetails['round_off']) ? $salesDetails['round_off'] : "Rs. 0.0" }}</label>
          </p>
        </div>
        <div class="net-payable">
          <p class="net-amount">
            <label>Total Amount</label>
            <label>{{ isset($salesDetails['total_amount']) ? 'Rs. ' . $salesDetails['total_amount'] : "Rs. 0.0" }}</label>
          </p>
          <p class="net-amount">
            <label>Discount</label>
            <label>{{ isset($salesDetails['discount_amount']) ? 'Rs. ' . $salesDetails['discount_amount'] : "Rs. 0.0" }}</label>
          </p>
          <p class="net-amount">
            <label>Net Amount</label>
            <!-- <label>{{ isset($salesDetails['total_amount']) ? 'Rs. ' . $salesDetails['total_amount'] : "Rs. 0.0" }}</label> -->
            <label>{{ isset($salesDetails['net_amount']) ? $salesDetails['net_amount'] : "Rs. 0.0" }}</label>
          </p>
        </div>
      </div>
      <div class="evital-footer">
        <div class="custom-message">
          <p>Software by Pharma24*7.in : Customer Care No : 9081111247</p>
        </div>
        <!-- <div class="branding">
          <ul>
            <li style="display: flex; align-items: center;">
              <img src="{{ isset($salesDetails['logo']) ? $salesDetails['logo'] : 'https://pharma247.in/public/landing_design/images/logo.png' }}" alt="eVital" title="eVital" class="footer-logo" />Pharma24*7.in
            </li>
          </ul>
        </div> -->
      </div>
    </div>
  </div>
</body>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" 
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</html>