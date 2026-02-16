<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Penjualan Qurban</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .meta {
            margin-bottom: 20px;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        ul {
            padding-left: 15px;
            margin: 0;
        }

        li {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Penjualan Qurban</h2>
        <h3>{{ $farm->name }}</h3>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}<br>
        <strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    @if(!empty($summary))
        <div style="margin-bottom: 20px; border: 1px solid #ddd; padding: 10px;">
            <strong>Ringkasan:</strong><br>
            Total Pendapatan: Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}<br>
            Total Ternak Terjual: {{ number_format($summary['total_livestock_sold'], 0, ',', '.') }} Ekor
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="15%">No. Transaksi</th>
                <th width="20%">Pelanggan</th>
                <th width="35%">Detail Ternak</th>
                <th width="15%" class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}</td>
                    <td>{{ $item['transaction_number'] }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td>
                        <ul>
                            @foreach($item['details'] as $detail)
                                <li>
                                    {{ $detail['livestock_name'] }} ({{ $detail['weight'] }} kg)
                                    - Rp {{ number_format($detail['amount'], 0, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right font-bold">{{ number_format($item['total_amount'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>