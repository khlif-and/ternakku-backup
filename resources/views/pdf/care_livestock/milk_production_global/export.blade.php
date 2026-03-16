<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produksi Susu Global</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .summary {
            margin-top: 20px;
        }

        .summary p {
            margin: 5px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Produksi Susu Global</h1>
        <p>Peternakan: {{ $farm->name }}</p>
        @if($type === 'all')
            <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Waktu</th>
                <th>Pemerah</th>
                <th>Jumlah (Liter)</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @if($type === 'all')
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item['transaction_number'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($item['milking_shift']) }}</td>
                        <td>{{ $item['milking_time'] }}</td>
                        <td>{{ $item['milker_name'] }}</td>
                        <td>{{ number_format($item['quantity_liters'], 2) }}</td>
                        <td>{{ $item['milk_condition'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $data['transaction_number'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($data['transaction_date'])->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($data['milking_shift']) }}</td>
                    <td>{{ $data['milking_time'] }}</td>
                    <td>{{ $data['milker_name'] }}</td>
                    <td>{{ number_format($data['quantity_liters'], 2) }}</td>
                    <td>{{ $data['milk_condition'] }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if($type === 'all')
        <div class="summary">
            <p>Total Produksi: {{ number_format($summary['total_production'], 2) }} Liter</p>
            <p>Rata-rata Produksi: {{ number_format($summary['avg_production'], 2) }} Liter</p>
            <p>Total Pencatatan: {{ $summary['total_records'] }}</p>
        </div>
    @endif
</body>

</html>