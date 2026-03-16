<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Timbang Ulang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .meta {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Timbang Ulang</h1>
        <h2>{{ $farm->name }}</h2>
    </div>

    <div class="meta">
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Transaksi</th>
                <th>Ternak (Eartag)</th>
                <th>Bangsa</th>
                <th>Kandang</th>
                <th>Berat (kg)</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->translatedFormat('d/m/Y') }}</td>
                    <td>{{ $item['transaction_number'] }}</td>
                    <td>{{ $item['livestock']['eartag'] }}</td>
                    <td>{{ $item['livestock']['livestock_breed_name'] ?? '-' }}</td>
                    <td>{{ $item['livestock']['pen_name'] ?? '-' }}</td>
                    <td>{{ number_format($item['weight'], 2, ',', '.') }}</td>
                    <td>{{ $item['notes'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>

</html>