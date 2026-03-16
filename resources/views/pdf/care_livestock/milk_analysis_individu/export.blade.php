<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Analisis Susu Individu</title>
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
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Analisis Susu Individu</h2>
        <h3>{{ $farm->name }}</h3>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}<br>
        <strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    @if(!empty($summary))
        <div style="margin-bottom: 20px; border: 1px solid #ddd; padding: 10px;">
            <strong>Rata-rata:</strong>
            Lemak: {{ number_format($summary['avg_fat'], 2) }}% |
            SNF: {{ number_format($summary['avg_snf'], 2) }}% |
            Protein: {{ number_format($summary['avg_protein'], 2) }}% |
            BJ: {{ number_format($summary['avg_bj'], 2) }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Ternak</th>
                <th>BJ</th>
                <th>Lemak (%)</th>
                <th>SNF (%)</th>
                <th>Protein (%)</th>
                <th>TS (%)</th>
                <th>MBRT</th>
                <th>AT</th>
                <th>AB</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}</td>
                    <td>{{ $item['livestock_name'] }}</td>
                    <td>{{ $item['bj'] ?? '-' }}</td>
                    <td>{{ $item['fat'] ?? '-' }}</td>
                    <td>{{ $item['snf'] ?? '-' }}</td>
                    <td>{{ $item['protein'] ?? '-' }}</td>
                    <td>{{ $item['ts'] ?? '-' }}</td>
                    <td>{{ $item['mbrt'] ?? '-' }}</td>
                    <td>{{ $item['at'] }}</td>
                    <td>{{ $item['ab'] }}</td>
                    <td>{{ $item['notes'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>