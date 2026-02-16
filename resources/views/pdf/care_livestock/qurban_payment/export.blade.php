<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Penerimaan Pembayaran Qurban</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }

        .summary-box {
            margin-bottom: 15px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PENERIMAAN PEMBAYARAN QURBAN</h1>
        <p><strong>{{ $farm->name }}</strong></p>
        <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} s/d
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
    </div>

    <div class="summary-box">
        <strong>Ringkasan:</strong><br>
        Total Transaksi: {{ $summary['total_transactions'] }}<br>
        Total Pemasukan: Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">No. Transaksi</th>
                <th style="width: 25%">Pelanggan</th>
                <th style="width: 20%">Ref. Penjualan</th>
                <th style="width: 20%" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}</td>
                    <td>{{ $item['transaction_number'] }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td>{{ $item['sales_transaction_number'] }}</td>
                    <td class="text-right">Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Total</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>