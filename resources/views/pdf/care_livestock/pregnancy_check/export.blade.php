<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Pemeriksaan Kehamilan</title>
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
        <h1>Laporan Pemeriksaan Kehamilan</h1>
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
                <th>Eartag</th>
                <th>Bangsa</th>
                <th>Kandang</th>
                <th>Status</th>
                <th>Usia (Bln)</th>
                <th>Est. Lahir</th>
                <th>Pemeriksa</th>
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
                    <td>{{ $item['result_text'] }}</td>
                    <td>{{ $item['pregnant_age'] ?? '-' }}</td>
                    <td>{{ $item['estimated_birth_date'] ? \Carbon\Carbon::parse($item['estimated_birth_date'])->translatedFormat('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $item['officer_name'] ?? '-' }}</td>
                    <td>{{ $item['notes'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>

</html>