<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pemberian Pakan Koloni</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 5px;
            border: 1px solid #555;
            font-size: 11px;
            vertical-align: top;
        }

        table th {
            background: #e9e9e9;
            font-weight: bold;
            text-align: left;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="title">Laporan Pemberian Pakan Koloni</div>
    <div class="subtitle">{{ $farm->name ?? '-' }}</div>

    {{-- Informasi Filter --}}
    <p>
        <strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}
        @if (!empty($filters['pen_id']))
            <br>
            <strong>Kandang:</strong> {{ $pen_name ?? '-' }}
        @endif
    </p>

    {{-- Tabel Data --}}
    {{-- Tabel Data --}}
    @if(isset($groupedItems) && $groupedItems->isNotEmpty())
        @foreach($groupedItems as $penName => $groupItems)
            <div
                style="margin-top: 20px; margin-bottom: 10px; font-weight: bold; font-size: 13px; border-bottom: 1px solid #ccc;">
                Kandang: {{ $penName }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Jml Ternak</th>
                        <th width="45%">Detail Pakan</th>
                        <th width="20%" class="text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupItems as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->feedingH->transaction_date)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $item->total_livestock }} ekor</td>
                            <td>
                                @foreach($item->feedingColonyItems as $feedItem)
                                    <div style="margin-bottom: 4px;">
                                        <strong>{{ $feedItem->name }}</strong> ({{ $feedItem->type }})<br>
                                        {{ $feedItem->qty_kg }} kg x Rp {{ number_format($feedItem->price_per_kg, 0, ',', '.') }}
                                        = Rp {{ number_format($feedItem->total_price, 0, ',', '.') }}
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-right">
                                Rp {{ number_format($item->total_cost, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Biaya {{ $penName }}</th>
                        <th class="text-right">Rp {{ number_format($groupItems->sum('total_cost'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        @endforeach

        <div style="margin-top: 30px; border-top: 2px solid #333; padding-top: 10px; text-align: right; font-weight: bold;">
            Grand Total Biaya Semua Kandang: Rp {{ number_format($items->sum('total_cost'), 0, ',', '.') }}
        </div>

    @else
        {{-- Single Pen Table (Existing Layout) --}}
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="15%">Kandang</th>
                    <th width="10%">Jml Ternak</th>
                    <th width="35%">Detail Pakan</th>
                    <th width="20%" class="text-right">Total Biaya</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->feedingH->transaction_date)->format('d/m/Y') }}</td>
                        <td>{{ $item->pen->name ?? '-' }}</td>
                        <td class="text-center">{{ $item->total_livestock }} ekor</td>
                        <td>
                            @foreach($item->feedingColonyItems as $feedItem)
                                <div style="margin-bottom: 4px;">
                                    <strong>{{ $feedItem->name }}</strong> ({{ $feedItem->type }})<br>
                                    {{ $feedItem->qty_kg }} kg x Rp {{ number_format($feedItem->price_per_kg, 0, ',', '.') }}
                                    = Rp {{ number_format($feedItem->total_price, 0, ',', '.') }}
                                </div>
                            @endforeach
                        </td>
                        <td class="text-right">
                            Rp {{ number_format($item->total_cost, 0, ',', '.') }}
                            <br>
                            <span style="font-size: 10px; color: #666;">
                                (Rp {{ number_format($item->average_cost, 0, ',', '.') }} / ekor)
                            </span>
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
                    <th class="text-right">Rp {{ number_format($items->sum('total_cost'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        Dicetak otomatis oleh sistem Ternakku — {{ date('d M Y H:i') }}
    </div>

</body>

</html>