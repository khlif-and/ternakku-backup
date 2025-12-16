<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PDF Laporan Artificial Inseminasi</title>

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

    <div class="title">Laporan Artificial Inseminasi</div>
    <div class="subtitle">{{ $farm->name ?? '-' }}</div>

    {{-- Informasi Filter --}}
    <p><strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}</p>

    @if (!empty($filters['livestock_id']))
        @php
            $cow = $farm->livestocks()->find($filters['livestock_id']);
        @endphp
        <p><strong>Ternak:</strong> {{ $cow->name ?? $cow->eartag_number ?? '-' }}</p>
    @endif

    {{-- Tabel Data --}}
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

    <div class="footer">
        Dicetak otomatis oleh sistem Ternakku — {{ date('d M Y H:i') }}
    </div>

</body>
</html>
