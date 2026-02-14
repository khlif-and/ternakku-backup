<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Inseminasi Buatan</title>
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
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Inseminasi Buatan</h2>
        <h3>{{ $farm->name }}</h3>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($filters['start_date'] ?? now())->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($filters['end_date'] ?? now())->format('d/m/Y') }}<br>
        <strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="15%">No. Eartag</th>
                <th width="15%">Petugas</th>
                <th width="15%">Bangsa Pejantan</th>
                <th width="15%">Produsen Semen</th>
                <th width="10%">Batch</th>
                <th width="10%">Biaya</th>
                <th width="10%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->insemination->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $item->reproductionCycle->livestock->eartag_number ?? '-' }}</td>
                    <td>{{ $item->officer_name }}</td>
                    <td>{{ $item->semenBreed->name ?? '-' }}</td>
                    <td>{{ $item->semen_producer }}</td>
                    <td>{{ $item->semen_batch }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                    <td>{{ $item->insemination->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>