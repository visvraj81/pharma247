
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate List Pdf</title>
    <link rel="icon" href="https://media.licdn.com/dms/image/D560BAQHpcQsgK4-XZg/company-logo_200_200/0/1705322515642?e=2147483647&v=beta&t=73LDAKR6yljMrwQC4GOyRxV6181CkkiH2AfFMa_Mrc4">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h2 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }

        header h4 {
            color: #000;
        }

        .bill-table table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .bill-table th,
        .bill-table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        .bill-table th {
            background-color: #f8f8f8;
            color: #333;
        }

        .bill-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .bill-table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="container">
         <div id="logo">
            <img src="https://thekapda.in/pharmalogo.png" alt="Company Logo" style="width: 150px;">
          </div>
        <header>
            <h2>GSTR 1 Report</h2>
        </header>
        <section class="bill-table">
            <table>
                <thead>
                    <tr>
                         <th>GST No</th>
                          <th>Name</th>
                          <th>Bill No</th>
                          <th>Bill Date</th>
                          <th>SGST</th>
                          <th>CGST</th>
                          <th>Net Amount</th>
                          <th>GST</th>
                          <th>Taxable Amount</th>
                    </tr>
                </thead>
                <tbody>
                       @foreach ($purchesList['gst_bill'] as $detail)
                      <tr>
                        <td>{{ $detail['gst_no'] }}</td>
                        <td>{{ $detail['name'] }}</td>
                        <td>{{ $detail['bill_no'] }}</td>
                        <td>{{ $detail['bill_date'] }}</td>
                        <td>{{ $detail['sgst'] }}</td>
                        <td>{{ $detail['cgst'] }}</td>
                        <td>{{ $detail['net_amount'] }}</td>
                        <td>{{ $detail['gst'] }}</td>
                        <td>{{ $detail['taxable_amount'] }}</td>
                      </tr>
                    @endforeach
                      <tr>
                      <td colspan="4"></td>
                      <td>{{ $purchesList['sgst'] }}</td>
                      <td>{{ $purchesList['cgst'] }}</td>
                      <td>{{ $purchesList['total_amount'] }}</td>
                      <td></td>
                      <td>{{ $purchesList['taxable_amount'] }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>
