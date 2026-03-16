<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penerimaan Hewan Qurban</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2,
        .header h3 {
            margin: 0;
        }

        .meta {
            margin-bottom: 15px;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .nested-table {
            width: 100%;
            margin: 0;
            border: none;
        }

        .nested-table th {
            background-color: #f9f9f9;
            font-size: 8pt;
        }

        .nested-table td {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 4px 6px;
            font-size: 8pt;
        }

        .nested-table tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ $farm->name }}</h2>
        <h3>Laporan Penerimaan Hewan Qurban</h3>
        <p>Periode: {{ $start_date }} - {{ $end_date }}</p>
    </div>

    <div class="meta">
        Dibuat pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 12%">No Transaksi</th>
                <th style="width: 12%">Supplier</th>
                <th style="width: 56%">Detail Hewan Qurban</th>
                <th style="width: 10%" class="text-center">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                @php $item = $item->resolve(); @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d M Y') }}</td>
                    <td><strong>{{ $item['transaction_number'] }}</strong></td>
                    <td>{{ $item['supplier'] ?? '-' }}</td>
                    <td style="padding: 0;">
                        @if(count($item['livestock_items']) > 0)
                            <table class="nested-table" style="margin: 3px;">
                                <tr>
                                    <th style="width: 20%">Eartag</th>
                                    <th style="width: 18%">Jenis</th>
                                    <th style="width: 17%">Ras</th>
                                    <th style="width: 10%" class="text-center">Kelamin</th>
                                    <th style="width: 12%" class="text-center">Umur</th>
                                    <th style="width: 10%" class="text-center">Berat</th>
                                    <th style="width: 13%" class="text-right">Harga Qurban</th>
                                </tr>
                                @foreach($item['livestock_items'] as $livestock)
                                    <tr>
                                        <td><strong>{{ $livestock['eartag_number'] }}</strong></td>
                                        <td>{{ $livestock['livestock_type'] }}</td>
                                        <td>{{ $livestock['livestock_breed'] }}</td>
                                        <td class="text-center">{{ $livestock['livestock_sex'] }}</td>
                                        <td class="text-center">{{ $livestock['age_years'] ?? 0 }}T
                                            {{ $livestock['age_months'] ?? 0 }}B</td>
                                        <td class="text-center">{{ number_format($livestock['weight'], 1) }} Kg</td>
                                        <td class="text-right">
                                            @if($livestock['qurban_price'])
                                                Rp {{ number_format($livestock['qurban_price'], 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <div style="padding: 10px; text-align: center; color: #999; font-style: italic;">Belum ada data
                                hewan</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <strong>{{ $item['total_livestock'] }} Ekor</strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data penerimaan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>