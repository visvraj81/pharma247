<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QR Label</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }

    .label {
      width: 200px;
      height: 120px;
      border: 1px dashed #000;
      padding: 5px;
      position: relative;
      box-sizing: border-box;
    }

    .title {
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 2px;
    }

    .qr-section {
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      height: 75px;
    }

    .qr-image {
      width: 70px;
      height: 70px;
    }

    .left-text {
      position: absolute;
      left: 5px;
      top: 0;
      bottom: 0;
      display: flex;
      align-items: center;
      writing-mode: vertical-rl;
      transform: rotate(180deg);
      font-weight: bold;
      font-size: 12px;
    }

    .batch {
      position: absolute;
      right: 35px;
      top: 20px;
      transform: rotate(-90deg);
      font-size: 12px;
      font-weight: bold;
    }

    .expiry {
      position: absolute;
      right: 15px;
      top: 22px;
      transform: rotate(-90deg);
      font-size: 12px;
      font-weight: bold;
    }

    .price {
      position: absolute;
      right: -15px;
      top: 20px;
      transform: rotate(-90deg);
      font-size: 16px;
      font-weight: bold;
      color: #000;
    }

    .footer {
      text-align: center;
      font-size: 12px;
      font-weight: bold;
      margin-top: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  </style>
</head>
<body>

<div style="display: flex; column-gap: 10px;">
  <div class="label">
    <div class="title">Dolo 650 Tab</div>

    <div class="qr-section">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=Dolo650" class="qr-image" alt="QR Code">
      <div class="batch">3091</div>
      <div class="expiry">12/26</div>
      <div class="price">₹300.00</div>
    </div>

    <div class="footer">KRISHNA CHEMIST K...</div>
  </div>
</div>

</body>
</html>
