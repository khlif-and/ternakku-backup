<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Natural Inseminasi</title>

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

    <h2>Preview Laporan Natural Inseminasi</h2>

    <p><strong>Farm:</strong> {{ $farm->name }}</p>

    <p>
        <strong>Ternak:</strong>
        @php
            $ls = $farm->livestocks()->find($filters['livestock_id']);
        @endphp
        {{ $ls->eartag_number ?? '-' }}

        <br>

        <strong>Periode:</strong>
        {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Eartag</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($items as $i => $item)
                @php
                    $inse = $item->insemination;
                    $liv  = $item->reproductionCycle->livestock;

                    $eartag = $liv->eartag_number ?? '-';
                    $jenis  = optional($liv->livestockType)->name ?? '-';
                    $ras    = optional($liv->livestockBreed)->name ?? '-';
                    $notes  = $inse->notes ?? '-';
                @endphp

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $inse->transaction_date }}</td>
                    <td>{{ $eartag }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ $ras }}</td>
                    <td>{{ $notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <a href="{{ route('admin.care-livestock.natural-inseminasi-report.export', [
            'farm_id'      => $farm->id,
            'livestock_id' => $filters['livestock_id'],
            'start_date'   => $filters['start_date'],
            'end_date'     => $filters['end_date'],
        ]) }}" class="btn">
        Export PDF
    </a>

</body>
</html>
