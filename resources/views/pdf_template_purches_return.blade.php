<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
  <meta charset="utf-8">
  <title>Pharma 24/7</title>
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
  <link rel="shortcut icon" sizes="192x192" href="https://pharma247.in/public/imgpsh_fullsize_anim.png">

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
      width: 600px;
      align-items: flex-start;
      margin-right: 15px;
      border-right: 1px solid black;
    }

    .about_invoice {
      flex: 1;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      position: relative;
    }

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
      margin-right: 10px;
      text-transform: uppercase;
      position: relative;
    }

    .pharmacy_details .kyc_details li.font-9 {
      font-size: 9px
    }

    .pharmacy_details .kyc_details li.font-7 {
      font-size: 7px
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
      font-size: 9px;
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
      width: 170px;
      padding: 0 10px;
      display: flex;
      flex-direction: column;
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
      width: 390px;
      padding-left: 10px;
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
      width: 340px;
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
      overflow: visible;
      text-overflow: unset;
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
    align-items: flex-start;
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
    flex-wrap: wrap;
  }

  .aitem {
    flex: 1;
    padding: 0 10px;
    min-width: 50px;
    box-sizing: border-box;
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

  .aitem.item_name {
    flex: 2;
    text-align: left;
    padding: 0 10px;
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
    word-wrap: break-word;
  }

  .aitem.batch {
    text-align: center;
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
    word-wrap: break-word;
  }

  .items.heading .aitem.item_name {
    font-weight: bold;
    white-space: normal;
  }

  .aitem.manuf,
  .aitem.packing,
  .aitem.expiry {
    text-align: center;
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
    word-wrap: break-word;
  }
  </style>
</head>

<body class="A5-print-page">
  <div class="invoice-container">
    <div class="container">

      <div class="header">
        <div class="about_pharmacy">
          @if(!empty($purchesDetails['logo']))
          <div class="logo">
            <img src="{{ isset($purchesDetails['logo']) ? $purchesDetails['logo'] :'https://pharma247.in/public/landing_design/images/logo.png'}}" border="0"
              alt="Chemist Name" style="width: 70px; height:70px" />
          </div>
          @endif
          <div class="pharmacy_details">
            <p class="pharmacy_name">
              {{ isset($purchesDetails['user_name']) ? $purchesDetails['user_name'] :"-"}}
            </p>
            <p>
            </p>
            <p class="address" style="margin-top: 5px;">
              {{ isset($purchesDetails['address']) ? $purchesDetails['address'] :"-"}}
            </p>
            <p class="Mobile" style="font-weight: 400; font-size: 12px; margin-top: 5px;">
              <b>M.</b> {{ isset($purchesDetails['phone_number']) ? $purchesDetails['phone_number'] :"-"}}
            </p>
          </div>
        </div>
        <div class="about_invoice">
          <div class="invoice_details">
            <p class="label">
              Purchase Return
              <label class="bill_number" style="margin-top: 3px;">Bill No :
                {{ isset($purchesDetails['bill_no']) ? $purchesDetails['bill_no'] :"-"}}
              </label>
            </p>
            <p class="order_number order_methodname" style="margin-top: 3px;">Payment : {{ isset($purchesDetails['payment_type']) ? $purchesDetails['payment_type'] :"-"}}</p>
            <p class="order_datetime" style="margin-top: 3px;">{{ isset($purchesDetails['bill_date']) ? Carbon\Carbon::parse($purchesDetails['bill_date'])->format('d-m-Y') :"-"}}</p>
          </div>
        </div>
      </div>
      <div class="customer-details">
        <ul>
          <li>
            <label>LICENSE 20 : </label>{{ isset($purchesDetails['license_20']) ? $purchesDetails['license_20'] :"-"}}
          </li>
          <li>
            <label>LICENSE 21 : </label> {{ isset($purchesDetails['license_21']) ? $purchesDetails['license_21'] :"-"}}
          </li>
          <li>
            <label>FSSAI : </label> {{ isset($purchesDetails['fssai_no']) ? $purchesDetails['fssai_no'] :"-"}}
          </li>
          <li>
            <label>GSTIN : </label> {{ isset($purchesDetails['gst_pan']) ? $purchesDetails['gst_pan'] :"-"}}
          </li>
        </ul>
      </div>
      <div class="customer-details">
        <ul>
          <li>
            <label>Distributor Name :</label>{{ isset($purchesDetails['distributor_name']) ? $purchesDetails['distributor_name'] : "-" }}
          </li>
          <li>
            <label>M :</label>{{ isset($purchesDetails['distributor_phone_number']) ? $purchesDetails['distributor_phone_number'] : "-" }}
          </li>
          <li>
            <label>GST :</label>{{ isset($purchesDetails['distributor_gst']) ? $purchesDetails['distributor_gst'] : "-" }}
          </li>
        </ul>
      </div>
      <div class="items-section">
        <!-- Heading Row -->
        <div class="items heading">
          <div class="aitem sr_num">Sr.</div>
          <div class="aitem ">Item Name</div>
          <div class="aitem">Unit</div>
          <div class="aitem manuf">Batch</div>
          <div class="aitem packing">Exp</div>
          <div class="aitem mrp">MRP</div>
          <div class="aitem qty">QTY</div>
          <div class="aitem batch">Free</div>
          <div class="aitem">PTR</div>
          <div class="aitem">CD%</div>
          <div class="aitem">GST</div>
          <div class="aitem">Loc.</div>
          <div class="aitem amount">Amount</div>
        </div>

        <!-- Items List -->
        <div class="items-list" style="min-height: 280px; position: relative;">
          <!-- Background Image -->
          @if(!empty($purchesDetails['logo']))
          <div class="invoice-bg-image">
            <img src="{{ isset($purchesDetails['logo']) ? $purchesDetails['logo'] :'https://pharma247.in/public/landing_design/images/logo.png'}}" style="border-radius: 50%; height: 150px; opacity: inherit;" alt="Background Logo">
          </div>
		  @endif
          <!-- Check if item list exists -->
          @if(isset($purchesDetails['item_list']))
            <?php $i = 1; ?>
            @foreach($purchesDetails['item_list'] as $listData)
              <div class="items body">
                <div class="aitem sr_num">{{ $i }}</div>
                <div class="aitem ">{{ $listData['item_name'] ?? "" }}</div>
                <div class="aitem">{{ $listData['unit'] ?? "" }}</div>
                <div class="aitem manuf">{{ $listData['batch_number'] ?? "" }}</div>
                <div class="aitem packing">{{ $listData['expiry'] ?? "" }}</div>
                <div class="aitem mrp">{{ $listData['mrp'] ?? "" }}</div>
                <div class="aitem qty">{{ $listData['qty'] ?? "" }}</div>
                <div class="aitem batch">{{ $listData['fr_qty'] ?? "" }}</div>
                <div class="aitem">{{ $listData['ptr'] ?? "" }}</div>
                <div class="aitem">{{ $listData['disocunt'] ?? "" }}</div>
                <div class="aitem">{{ $listData['gst_name'] ?? "" }}</div>
                <div class="aitem">{{ $listData['location'] ?? "" }}</div>
                <div class="aitem amount">{{ $listData['amount'] ?? "" }}</div>
              </div>
              <?php $i++; ?>
            @endforeach
          @endif
        </div>
      </div>

      <div class="footer">
        <div class="gst-bifurcation">
          <p>
            <span>Total Qty</span>
            <label>
              {{isset($purchesDetails['total_item_qty']) ? $purchesDetails['total_item_qty'] :"₹0.0"}} + {{isset($purchesDetails['total_item_free_qty']) ? $purchesDetails['total_item_free_qty'] :"₹0.0"}}
            </label>
          </p>
          <p>
            <span>Total Item(s)</span>
            <label>
              {{isset($purchesDetails['iteam_count']) ? $purchesDetails['iteam_count'] :"0"}}
            </label>
          </p>
        </div>
        <div class="invoice-total-bifurcation">
          <p>
            <span>Total GST</span>
            <label>
              {{isset($purchesDetails['total_gst']) ? $purchesDetails['total_gst'] :"₹0.0"}}
            </label>
          </p>
          <p>
            <span>Other Amount</span>
            <label>
              {{isset($purchesDetails['other_amount']) ? $purchesDetails['other_amount'] :"₹0.0"}}
            </label>
          </p>
        </div>
        <div class="invoice-total-bifurcation">
          <p>
            <span>Total Amount</span>
            <label>
              {{isset($purchesDetails['total_amount']) ? $purchesDetails['total_amount'] :"₹0.0"}}
            </label>
          </p>
          <p>
            <span>Round off.</span>
            <label>{{isset($purchesDetails['round_off']) ? $purchesDetails['round_off'] :"₹0.0"}} </label>
          </p>
        </div>
        <div class="net-payable">
          <p class="net-amount" style="margin-top: 5px;">
            <label>Net Amount</label>
            <label>
              {{isset($purchesDetails['net_amount']) ? $purchesDetails['net_amount'] :"₹0.0"}}
            </label>
          </p>
        </div>
      </div>
      <div class="evital-footer">
        <div class="custom-message">
          <p>https://pharma247.in/</p>
        </div>
        <div class="branding">
          <ul>
            <li style="display: flex; align-items: center;">
              <!-- <img src="{{ isset($purchesDetails['logo']) ? $purchesDetails['logo'] :'https://pharma247.in/public/landing_design/images/logo.png'}}"
                alt="Pharam247" title="Pharam247" class="footer-logo" />Pharma247
            </li> -->Thank You !
          </ul>
        </div>
      </div>
    </div>
  </div>
</body>

</html>