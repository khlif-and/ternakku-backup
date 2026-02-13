<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pakan Individu</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #333; padding: 5px; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $farm->name }}</h2>
        <p>Laporan Pemberian Pakan Individu</p>
        <p>
            Periode: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} 
            s/d {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
        </p>
        @if($pen_name)
            <p>Ternak/Kandang: {{ $pen_name }}</p>
        @endif
    </div>

    {{-- Tabel Data --}}
    @if(isset($groupedItems) && $groupedItems->isNotEmpty())
        @foreach($groupedItems as $groupName => $groupItems)
            <div style="margin-top: 20px; margin-bottom: 10px; font-weight: bold; font-size: 13px; border-bottom: 1px solid #ccc;">
                Kandang: {{ $groupName }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Tanggal</th>
                        <th width="18%">Ternak</th>
                        <th width="45%">Detail Pakan</th>
                        <th width="20%" class="text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupItems as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->feedingH->transaction_date)->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $item->livestock->name ?? '-' }}</strong><br>
                                Tag: {{ $item->livestock->eartag ?? $item->livestock->eartag_number ?? '-' }}
                            </td>
                            <td>
                                @foreach($item->feedingIndividuItems as $feedItem)
                                    <div style="margin-bottom: 4px;">
                                        <strong>{{ $feedItem->feedItem->name ?? $feedItem->name ?? '-' }}</strong> ({{ $feedItem->type }})<br>
                                        {{ $feedItem->qty_kg }} kg x Rp {{ number_format($feedItem->price_per_kg, 0, ',', '.') }}
                                        = Rp {{ number_format($feedItem->total_price, 0, ',', '.') }}
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-right">
                                Rp {{ number_format($item->feedingIndividuItems->sum('total_price'), 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Biaya {{ $groupName }}</th>
                        <th class="text-right">Rp {{ number_format($groupItems->sum(function($item) { return $item->feedingIndividuItems->sum('total_price'); }), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        @endforeach

        <div style="margin-top: 30px; border-top: 2px solid #333; padding-top: 10px; text-align: right; font-weight: bold;">
            Grand Total Biaya: Rp {{ number_format($items->sum(function($item) { return $item->feedingIndividuItems->sum('total_price'); }), 0, ',', '.') }}
        </div>

    @else
        {{-- Single Table --}}
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="15%">Kandang</th>
                    <th width="18%">Ternak</th>
                    <th width="30%">Detail Pakan</th>
                    <th width="20%" class="text-right">Total Biaya</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->feedingH->transaction_date)->format('d/m/Y') }}</td>
                        <td>{{ $item->livestock->pen->name ?? '-' }}</td>
                        <td>
                             <strong>{{ $item->livestock->name ?? '-' }}</strong><br>
                             Tag: {{ $item->livestock->eartag ?? $item->livestock->eartag_number ?? '-' }}
                        </td>
                        <td>
                            @foreach($item->feedingIndividuItems as $feedItem)
                                <div style="margin-bottom: 4px;">
                                    <strong>{{ $feedItem->feedItem->name ?? $feedItem->name ?? '-' }}</strong> ({{ $feedItem->type }})<br>
                                    {{ $feedItem->qty_kg }} kg x Rp {{ number_format($feedItem->price_per_kg, 0, ',', '.') }}
                                    = Rp {{ number_format($feedItem->total_price, 0, ',', '.') }}
                                </div>
                            @endforeach
                        </td>
                        <td class="text-right">
                            Rp {{ number_format($item->feedingIndividuItems->sum('total_price'), 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px;">
                            Tidak ada data pemberian pakan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total Biaya Periode Ini</th>
                    <th class="text-right">Rp {{ number_format($items->sum(function($item) { return $item->feedingIndividuItems->sum('total_price'); }), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        Dicetak otomatis oleh sistem Ternakku — {{ date('d M Y H:i') }}
    </div>
</body>
</html>
