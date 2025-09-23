<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <style>
        /* Embedded CSS styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        /* Add more styles for responsiveness */
        /* Media queries for responsive design */
        @media screen and (max-width: 600px) {
            /* Add responsive styles here */
            /* Example: Adjust font size or layout for smaller screens */
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url(dimension.png);
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            margin-top: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .terms,
        .signature,
        .totals {
            flex: 1;
        }

        .terms p {
            margin: 5px 0;
        }

        .signature p {
            margin: 10px 0;
            font-weight: bold;
        }

        .totals p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{asset('pdf_desgin/logo.png')}}" alt="Logo">
        </div>
        <h1>INVOICE 3-2-1</h1>
        <div id="company" class="clearfix">
            <div> Bill Date:@if(isset($data['bill_no'])) {{$data['bill_no']}} @endif</div>
            <div>Payment:@if(isset($data['payment_name'])) {{$data['payment_name']}} @endif<br /> @if(isset($data['order_number'])) {{$data['order_number']}} @endif</div>
        </div>
        <div id="project">
            <div><span>Doctor Name</span>@if(isset($data['doctor_name'])) {{$data['doctor_name']}} @endif</div>
            <div><span>Doctor Number</span>@if(isset($data['doctor_number'])) {{$data['doctor_number']}} @endif</div>
            <div><span>Patient Name </span>@if(isset($data['pation_name'])) {{$data['pation_name']}} @endif</div>
            <div><span>Patient Number</span> @if(isset($data['pation_number'])) {{$data['pation_number']}} @endif</div>
        </div>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Sr</th>
                    <th>Name</th>
                    <th>Exp</th>
                    <th>Mrp</th>
                    <th>Qty</th>
                    <th>MGN</th>
                    <th>GST</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($salesData))
                <?php
                $i =1; 
                ?>
                @foreach($salesData as $list)
                <tr>
                    <td>{{$i}}</td>
                    <td>@if(isset($list->getIteam->iteam_name)) {{$list->getIteam->iteam_name}} @endif</td>
                    <td>@if(isset($list->exp)) {{$list->exp}} @endif</td>
                    <td>@if(isset($list->mrp)) {{$list->mrp}} @endif</td>
                    <td>@if(isset($list->qty)) {{$list->qty}} @endif</td>
                    <td>@if(isset($list->mgn)) {{$list->mgn}} @endif</td>
                    <td>@if(isset($list->gst)) {{$list->gst}} @endif</td>
                    <td>@if(isset($list->amt)) {{$list->amt}} @endif</td>
                </tr>
                <?php
                $i++; 
                ?>
                @endforeach
                @endif
            </tbody>
        </table>
    </main>
    <footer>
        <div class="footer-content">
            <div class="terms">
                <p>Terms & Conditions</p>
                <!-- Add any additional terms here -->
            </div>
            <div class="signature">
                <p>Signature: ____________________</p>
            </div>
            <div class="totals">
                <p>Total GST @if(isset($data['total_gst'])) {{$data['total_gst']}} @endif</p>
                <p>Total Items: 1100 | Total MRP: @if(isset($data['mrp'])) {{$data['mrp']}} @endif | Round Off: @if(isset($data['round_off'])) {{$data['round_off']}} @endif</p>
                <p>Net Amount: @if(isset($data['net_amt'])) {{$data['net_amt']}} @endif</p>
            </div>
        </div>
    </footer>
</body>

</html>
