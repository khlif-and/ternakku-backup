<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PDF Laporan Natural Inseminasi</title>

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

    <div class="title">Laporan Natural Inseminasi</div>
    <div class="subtitle">{{ $farm->name }}</div>

    {{-- TERNAK --}}
    <p><strong>Ternak:</strong>
        @php
            $ls = $farm->livestocks()->find($filters['livestock_id']);
        @endphp
        {{ $ls->eartag_number ?? '-' }}
    </p>

    {{-- PERIODE --}}
    <p><strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}</p>

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
                @endphp

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $inse->transaction_date }}</td>
                    <td>{{ $eartag }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ $ras }}</td>
                    <td>{{ $inse->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem Ternakku — {{ date('d M Y H:i') }}
    </div>

</body>
</html>
