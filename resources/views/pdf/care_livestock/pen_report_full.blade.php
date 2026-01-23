<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kandang - {{ $pen->name ?? 'Unknown' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .page { padding: 20px; }
        .header { text-align: center; padding-bottom: 15px; border-bottom: 2px solid #2d6a4f; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #2d6a4f; margin-bottom: 5px; }
        .header .farm-name { font-size: 14px; color: #444; }
        .header .period { font-size: 11px; color: #666; margin-top: 5px; }
        .section { margin-bottom: 20px; }
        .section-title { background: #2d6a4f; color: white; padding: 8px 12px; font-size: 12px; font-weight: bold; margin-bottom: 10px; }
        .info-grid { display: table; width: 100%; border-collapse: collapse; }
        .info-row { display: table-row; }
        .info-label, .info-value { display: table-cell; padding: 6px 10px; border: 1px solid #ddd; }
        .info-label { background: #f5f5f5; font-weight: bold; width: 30%; }
        .stats-grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .stats-grid td { width: 16.66%; text-align: center; padding: 10px; border: 1px solid #ddd; }
        .stats-grid .stat-value { font-size: 18px; font-weight: bold; color: #2d6a4f; }
        .stats-grid .stat-label { font-size: 9px; color: #666; text-transform: uppercase; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background: #e8f5e9; border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table.data-table td { border: 1px solid #ddd; padding: 5px 8px; font-size: 10px; vertical-align: top; }
        table.data-table tr:nth-child(even) { background: #fafafa; }
        .footer { position: fixed; bottom: 20px; right: 20px; font-size: 9px; color: #999; }
        .page-break { page-break-before: always; }
        .text-center { text-align: center; }
        .text-green { color: #2d6a4f; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .distribution-table { width: 100%; border-collapse: collapse; }
        .distribution-table td { padding: 4px 8px; border: 1px solid #ddd; }
        .empty-message { text-align: center; padding: 20px; color: #999; font-style: italic; }
    </style>
</head>
<body>
    <div class="page">
        {{-- HEADER --}}
        <div class="header">
            <h1>LAPORAN KANDANG</h1>
            <div class="farm-name">{{ $farm->name ?? 'Nama Farm' }}</div>
            <div class="period">
                Periode: <strong>{{ date('d M Y', strtotime($from_date)) }}</strong> - <strong>{{ date('d M Y', strtotime($to_date)) }}</strong>
            </div>
        </div>

        {{-- STATISTICS --}}
        <table class="stats-grid">
            <tr>
                <td><div class="stat-value">{{ $statistics['total_livestock'] ?? 0 }}</div><div class="stat-label">Total Ternak</div></td>
                <td><div class="stat-value text-green">{{ $statistics['alive_livestock'] ?? 0 }}</div><div class="stat-label">Hidup</div></td>
                <td><div class="stat-value" style="color:#0d6efd;">{{ $statistics['male_count'] ?? 0 }}</div><div class="stat-label">Jantan</div></td>
                <td><div class="stat-value" style="color:#e91e63;">{{ $statistics['female_count'] ?? 0 }}</div><div class="stat-label">Betina</div></td>
                <td><div class="stat-value" style="color:#ff9800;">{{ $statistics['total_feedings'] ?? 0 }}</div><div class="stat-label">Pemberian Pakan</div></td>
                <td><div class="stat-value" style="color:#9c27b0;">{{ $statistics['total_treatments'] ?? 0 }}</div><div class="stat-label">Pengobatan</div></td>
            </tr>
        </table>

        {{-- PEN INFO --}}
        <div class="section">
            <div class="section-title">Informasi Kandang</div>
            <div class="info-grid">
                <div class="info-row"><div class="info-label">Nama Kandang</div><div class="info-value">{{ $pen->name ?? '-' }}</div></div>
                <div class="info-row"><div class="info-label">Kapasitas</div><div class="info-value">{{ $pen->capacity ?? '-' }} ekor</div></div>
                <div class="info-row"><div class="info-label">Luas Area</div><div class="info-value">{{ $pen->area ?? '-' }} m²</div></div>
            </div>
        </div>

        {{-- DISTRIBUTION --}}
        @if (!empty($statistics['by_type']) && $statistics['by_type']->count() > 0)
        <div class="section">
            <div class="section-title">Distribusi Ternak</div>
            <table class="distribution-table">
                <tr>
                    <td style="width:50%;vertical-align:top;"><strong>Berdasarkan Jenis:</strong><br>@foreach ($statistics['by_type'] as $type => $count){{ $type }}: <strong>{{ $count }}</strong><br>@endforeach</td>
                    <td style="width:50%;vertical-align:top;"><strong>Berdasarkan Klasifikasi:</strong><br>@foreach ($statistics['by_classification'] as $classification => $count){{ $classification }}: <strong>{{ $count }}</strong><br>@endforeach</td>
                </tr>
            </table>
        </div>
        @endif

        {{-- LIVESTOCK LIST --}}
        <div class="section">
            <div class="section-title">Daftar Ternak ({{ count($livestocks) }} ekor)</div>
            @if (count($livestocks) > 0)
                <table class="data-table">
                    <thead><tr><th>No</th><th>Kode/Eartag</th><th>Jenis</th><th>Kelamin</th><th>Klasifikasi</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach ($livestocks as $index => $livestock)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $livestock->eartag ?? $livestock->code ?? '-' }}</td>
                                <td>{{ $livestock->livestockType->name ?? '-' }}</td>
                                <td>{{ $livestock->livestockSex->name ?? '-' }}</td>
                                <td>{{ $livestock->livestockClassification->name ?? '-' }}</td>
                                <td class="text-center">@if ($livestock->is_alive ?? true)<span class="badge badge-success">Hidup</span>@else<span class="badge badge-danger">Mati</span>@endif</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">Tidak ada data ternak.</div>
            @endif
        </div>

        {{-- FEEDING HISTORY --}}
        @if (count($feedingHistory) > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">Riwayat Pemberian Pakan ({{ count($feedingHistory) }} record)</div>
            <table class="data-table">
                <thead><tr><th>Tanggal</th><th>No. Transaksi</th><th>Pakan</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach ($feedingHistory as $feeding)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($feeding->feedingH->transaction_date ?? now())->format('d M Y') }}</td>
                            <td>{{ $feeding->feedingH->transaction_number ?? '-' }}</td>
                            <td>@foreach ($feeding->feedingColonyItems ?? [] as $item){{ $item->feed->name ?? '-' }}<br>@endforeach</td>
                            <td>@foreach ($feeding->feedingColonyItems ?? [] as $item){{ $item->quantity ?? '-' }} {{ $item->feed->unit ?? '' }}<br>@endforeach</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- TREATMENT HISTORY --}}
        @if (count($treatmentHistory) > 0)
        <div class="section">
            <div class="section-title">Riwayat Pengobatan ({{ count($treatmentHistory) }} record)</div>
            <table class="data-table">
                <thead><tr><th>Tanggal</th><th>No. Transaksi</th><th>Penyakit</th><th>Obat</th></tr></thead>
                <tbody>
                    @foreach ($treatmentHistory as $treatment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($treatment->treatmentH->transaction_date ?? now())->format('d M Y') }}</td>
                            <td>{{ $treatment->treatmentH->transaction_number ?? '-' }}</td>
                            <td>{{ $treatment->disease->name ?? '-' }}</td>
                            <td>@foreach ($treatment->treatmentColonyMedicineItems ?? [] as $item){{ $item->medicine->name ?? '-' }} ({{ $item->dosage ?? '-' }})<br>@endforeach</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- MILK PRODUCTION --}}
        @if (count($milkProduction) > 0)
        <div class="section">
            <div class="section-title">Produksi Susu ({{ count($milkProduction) }} record)</div>
            <table class="distribution-table" style="margin-bottom:10px;">
                <tr>
                    <td style="background:#e3f2fd;text-align:center;"><strong>Total:</strong> {{ number_format($statistics['total_milk'] ?? 0, 2) }} L</td>
                    <td style="background:#e3f2fd;text-align:center;"><strong>Rata-rata:</strong> {{ number_format($statistics['avg_milk_per_day'] ?? 0, 2) }} L</td>
                </tr>
            </table>
            <table class="data-table">
                <thead><tr><th>Tanggal</th><th>No. Transaksi</th><th>Volume (L)</th></tr></thead>
                <tbody>
                    @foreach ($milkProduction as $production)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($production->milkProductionH->transaction_date ?? now())->format('d M Y') }}</td>
                            <td>{{ $production->milkProductionH->transaction_number ?? '-' }}</td>
                            <td class="text-center"><strong>{{ number_format($production->volume ?? 0, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">Dicetak: {{ now()->format('d M Y H:i') }} | Ternakku</div>
    </div>
</body>
</html>
