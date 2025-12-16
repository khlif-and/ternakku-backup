<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PDF Laporan Mutasi Individu</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .subtitle { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { padding: 5px; border: 1px solid #555; font-size: 11px; }
        table th { background: #e9e9e9; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #777; }
    </style>
</head>

<body>

    <div class="title">Laporan Mutasi Individu</div>
    <div class="subtitle">{{ $farm->name }}</div>

    <p><strong>Kandang:</strong>
        @php
            $pen = $farm->pens()->find($filters['pen_id']);
        @endphp
        {{ $pen->name ?? '-' }}
    </p>

    <p><strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Eartag / Nama</th>
                <th>Dari</th>
                <th>Ke</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($items as $i => $item)
                @php
                    $ls = $item->livestock;
                    $mut = $item->mutationH;
                @endphp

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $mut->transaction_date }}</td>
                    <td>{{ $ls->eartag ?? $ls->name }}</td>
                    <td>{{ optional($farm->pens()->find($item->from))->name ?? '-' }}</td>
                    <td>{{ optional($farm->pens()->find($item->to))->name ?? '-' }}</td>
                    <td>{{ $mut->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem Ternakku — {{ date('d M Y H:i') }}
    </div>

</body>
</html>
