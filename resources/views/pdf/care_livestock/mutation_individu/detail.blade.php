<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Mutasi Individu</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; padding: 20px; color: #333; }
        h2 { margin-bottom: 10px; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { padding: 6px; border: 1px solid #777; }
        table th { background: #eee; }
        .btn { padding: 8px 12px; background: #3490dc; color: white; font-size: 14px; text-decoration: none; border-radius: 4px; }
    </style>
</head>

<body>

    <h2>Preview Laporan Mutasi Individu</h2>
    <p><strong>Farm:</strong> {{ $farm->name }}</p>

    <p>
        <strong>Kandang:</strong>
        @php
            $pen = $farm->pens()->find($filters['pen_id']);
        @endphp
        {{ $pen->name ?? '-' }}
        <br>
        <strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}
    </p>

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

    <br><br>

    <a href="{{ route('admin.care-livestock.mutation-individu-report.export', [
            'farm_id' => $farm->id,
            'pen_id' => $filters['pen_id'],
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ]) }}" class="btn">
        Export PDF
    </a>

</body>
</html>
