<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Codes - 3x3 Grid</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 10px;
        }

        .qr-table {
            width: 100%;
            border-collapse: collapse;
        }

        .qr-cell {
            width: 25%;
            text-align: center;
            border: 1px dashed #ccc;
            padding: 10px;
            box-sizing: border-box;
        }

        .qr-cell img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .qr-cell p {
            margin: 4px 0;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <table class="qr-table">
        @php
            $qrListLimited = array_slice($qrList, 0); // Only 9 QR codes
            $chunks = array_chunk($qrListLimited, 4);   // 4 QR codes per row
        @endphp

        @foreach($chunks as $row)
            <tr>
                @foreach($row as $qr)
                    <td class="qr-cell">
                        <p>{{ $qr['item_id'] }}</p>
                        <img src="{{ $qr['qr_code_url'] }}" alt="QR Code">
                      	<p>{{ $qr['expiry'] }} | {{ $qr['mrp'] }} | {{ $qr['batch_number'] }}</p>
                        <p>{{ $qr['barcode'] }}</p>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>