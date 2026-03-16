<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Pembelian Pakan & Obat</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .meta {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .item-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            margin-bottom: 3px;
        }

        .item-name {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Pembelian Pakan & Obat</h1>
        <h2>{{ $farm->name }}</h2>
    </div>

    <div class="meta">
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}</p>
        @if($purchase_type)
            <p><strong>Tipe Pembelian:</strong>
                {{ ucfirst($purchase_type == 'medicine' ? 'Obat-obatan' : ($purchase_type == 'forage' ? 'Pakan Hijauan' : 'Pakan Konsentrat')) }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Transaksi</th>
                <th>Supplier</th>
                <th width="40%">Detail Item</th>
                <th>Total</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->translatedFormat('d/m/Y') }}</td>
                    <td>{{ $item['transaction_number'] }}</td>
                    <td>{{ $item['supplier'] }}</td>
                    <td>
                        <ul class="item-list">
                            @foreach($item['items'] as $detail)
                                <li>
                                    <span class="item-name">{{ $detail->item_name }}</span><br>
                                    <span style="font-size: 10px; color: #555;">({{ ucfirst($detail->purchase_type) }})</span>
                                    {{ $detail->quantity }} {{ $detail->unit }} x Rp
                                    {{ number_format($detail->price_per_unit, 0, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">Rp {{ number_format($item['total_amount'], 0, ',', '.') }}</td>
                    <td>{{ $item['notes'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>

</html>