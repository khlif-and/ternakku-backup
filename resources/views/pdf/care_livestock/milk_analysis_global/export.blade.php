<!DOCTYPE html>
<html>

<head>
    <title>Laporan Analisis Susu Global</title>
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
            font-size: 16pt;
        }

        .header p {
            margin: 5px 0;
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
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Analisis Susu Global</h1>
        <p>{{ $farm->name }}</p>
        <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
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
                    <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $item->bj ?? '-' }}</td>
                    <td>{{ $item->fat ?? '-' }}</td>
                    <td>{{ $item->snf ?? '-' }}</td>
                    <td>{{ $item->protein ?? '-' }}</td>
                    <td>{{ $item->ts ?? '-' }}</td>
                    <td>{{ $item->mbrt ?? '-' }}</td>
                    <td>{{ $item->at ? 'Positif' : 'Negatif' }}</td>
                    <td>{{ $item->ab ? 'Positif' : 'Negatif' }}</td>
                    <td class="text-left">{{ $item->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>