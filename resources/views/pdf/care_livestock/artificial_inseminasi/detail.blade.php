<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Artificial Inseminasi</title>

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

    <h2>Preview Laporan Artificial Inseminasi</h2>

    <p><strong>Farm:</strong> {{ $farm->name }}</p>
    <p>
        <strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}
        <br>

        @if (!empty($filters['livestock_id']))
            @php
                $cow = $farm->livestocks()->find($filters['livestock_id']);
            @endphp
            <strong>Ternak:</strong>
            {{ $cow->eartag_number ?? $cow->name ?? '-' }}
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Eartag / Nama</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Waktu</th>
                <th>Petugas</th>
                <th>Breed Semen</th>
                <th>Sire</th>
                <th>Produsen</th>
                <th>Batch</th>
                <th>Biaya</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($items as $i => $item)
                @php
                    $ls = $item->reproductionCycle->livestock;
                    $insemination = $item->insemination;

                    $eartag = $ls->eartag_number
                               ?? $ls->eartag
                               ?? $ls->rfid_number
                               ?? '-';

                    $nama = $ls->name ?? '-';

                    $jenis = optional($ls->livestockType)->name ?? '-';
                    $ras   = optional($ls->livestockBreed)->name ?? '-';

                    $breedSemen = optional($item->semenBreed)->name ?? '-';
                @endphp

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $insemination->transaction_date }}</td>
                    <td>{{ $eartag }} / {{ $nama }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ $ras }}</td>
                    <td>{{ $item->action_time ?? '-' }}</td>
                    <td>{{ $item->officer_name ?? '-' }}</td>
                    <td>{{ $breedSemen }}</td>
                    <td>{{ $item->sire_name ?? '-' }}</td>
                    <td>{{ $item->semen_producer ?? '-' }}</td>
                    <td>{{ $item->semen_batch ?? '-' }}</td>
                    <td>{{ number_format($item->cost, 0, ',', '.') }}</td>
                    <td>{{ $insemination->notes ?? '-' }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="13" style="text-align:center; padding:10px;">
                        Tidak ada data artificial inseminasi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br><br>

    <a href="{{ route('admin.care-livestock.artificial-inseminasi-report.export', [
            'farm_id'    => $farm->id,
            'start_date' => $filters['start_date'],
            'end_date'   => $filters['end_date'],
            'livestock_id' => $filters['livestock_id'] ?? null,
        ]) }}" class="btn">
        Export PDF
    </a>

</body>
</html>
