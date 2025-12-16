<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mutasi Individu</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table th, table td {
            padding: 6px;
            border: 1px solid #777;
            text-align: left;
        }
        table th {
            background: #f1f1f1;
            font-weight: bold;
        }
        .info-table td {
            border: none !important;
            padding: 2px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>

<body>

    {{-- ========================== --}}
    {{-- HEADER --}}
    {{-- ========================== --}}

    <div class="title">Laporan Mutasi Individu</div>
    <div class="subtitle">{{ $farm->name ?? 'Farm Tanpa Nama' }}</div>

    {{-- ========================== --}}
    {{-- FILTER INFO --}}
    {{-- ========================== --}}

    <div class="section-title">Informasi Filter</div>

    <table class="info-table">
        <tr>
            <td><strong>Kandang:</strong></td>
            <td>
                @php
                    $penName = '-';
                    if (!empty($filters['pen_id'])) {
                        $pen = $farm->pens()->find($filters['pen_id']);
                        $penName = $pen->name ?? '-';
                    }
                @endphp
                {{ $penName }}
            </td>
        </tr>
        <tr>
            <td><strong>Tanggal Mulai:</strong></td>
            <td>{{ $filters['start_date'] ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Akhir:</strong></td>
            <td>{{ $filters['end_date'] ?? '-' }}</td>
        </tr>
    </table>


    {{-- ========================== --}}
    {{-- DATA MUTASI --}}
    {{-- ========================== --}}

    <div class="section-title">Data Mutasi</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Eartag / Nama</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Dari Kandang</th>
                <th>Ke Kandang</th>
                <th>Catatan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($items as $i => $item)
                @php
                    $ls = $item->livestock;
                    $mut = $item->mutationH;

                    $eartag = $ls->eartag
                                ?? $ls->eartag_number
                                ?? $ls->ear_tag
                                ?? $ls->tag
                                ?? $ls->code
                                ?? $ls->rfid_number
                                ?? '-';

                    $nama = $ls->name
                            ?? $ls->nama
                            ?? $ls->display_name
                            ?? $ls->nickname
                            ?? '-';

                    $jenis = optional($ls->livestockType)->name ?? '-';
                    $ras   = optional($ls->livestockBreed)->name ?? '-';

                    $fromPen = optional($farm->pens()->find($item->from))->name ?? '-';
                    $toPen   = optional($farm->pens()->find($item->to))->name ?? '-';
                @endphp

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $mut->transaction_date ?? '-' }}</td>
                    <td>{{ $eartag }} / {{ $nama }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ $ras }}</td>
                    <td>{{ $fromPen }}</td>
                    <td>{{ $toPen }}</td>
                    <td>{{ $mut->notes ?? '-' }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:15px;">
                        Tidak ada data mutasi untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ========================== --}}
    {{-- FOOTER --}}
    {{-- ========================== --}}

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem Ternakku.
        <br>Dicetak pada: {{ date('d M Y H:i') }}
    </div>

</body>
</html>
