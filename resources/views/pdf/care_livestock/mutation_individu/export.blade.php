<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Mutasi Individu</title>
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
        <h2>Laporan Mutasi Kandang Individu</h2>
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
                <th width="15%">Tanggal</th>
                <th width="20%">No. Transaksi</th>
                <th width="15%">Kode Ternak</th>
                <th width="15%">Asal</th>
                <th width="15%">Tujuan</th>
                <th width="15%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->mutationH->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $item->mutationH->transaction_number }}</td>
                    <td>{{ $item->livestock->livestock_code ?? '-' }}</td>
                    <td>{{ $item->penFrom->name ?? '-' }}</td>
                    <td>{{ $item->penTo->name ?? '-' }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>