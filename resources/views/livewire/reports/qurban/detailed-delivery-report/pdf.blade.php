<!DOCTYPE html>
<html>

<head>
    <title>Laporan Detail Pengiriman Hewan Qurban</title>
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

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            color: white;
            display: inline-block;
        }

        .bg-green {
            background-color: #28a745;
        }

        .bg-yellow {
            background-color: #ffc107;
            color: black;
        }

        .bg-blue {
            background-color: #007bff;
        }

        .bg-gray {
            background-color: #6c757d;
        }

        .bg-purple {
            background-color: #6f42c1;
        }

        .nested-table {
            width: 100%;
            margin: 0;
            border: none;
        }

        .nested-table td {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 4px 0;
        }

        .nested-table tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ $farm->name }}</h2>
        <h3>Laporan Detail Pengiriman Hewan Qurban</h3>
        <p>Periode: {{ $start_date }} - {{ $end_date }}</p>
    </div>

    <div class="meta">
        Dibuat pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 15%">No Transaksi</th>
                <th style="width: 12%">Driver</th>
                <th style="width: 10%">Armada</th>
                <th style="width: 43%">Detail Penerima & Hewan</th>
                <th style="width: 10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                @php $item = $item->resolve(); @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['delivery_date'])->format('d M Y') }}</td>
                    <td><strong>{{ $item['transaction_number'] }}</strong></td>
                    <td>{{ $item['driver']['name'] ?? '-' }}</td>
                    <td>{{ $item['fleet']['police_number'] ?? '-' }}</td>
                    <td style="padding: 0;">
                        @if(count($item['delivery_orders']) > 0)
                            <table class="nested-table" style="margin: 5px;">
                                @foreach($item['delivery_orders'] as $order)
                                    <tr>
                                        <td style="width: 45%;">
                                            <strong>{{ $order['recipient_name'] }}</strong><br>
                                            <span style="font-size: 8pt;">{{ $order['recipient_phone'] }}</span><br>
                                            <span style="font-size: 8pt; color: #555;">{{ $order['recipient_address'] }}</span>
                                        </td>
                                        <td style="width: 20%; text-align: center; vertical-align: middle;">
                                            <strong>{{ $order['livestock_count'] }} Ekor</strong>
                                        </td>
                                        <td style="width: 35%; text-align: right; vertical-align: middle;">
                                            <span style="font-size: 8pt;">{{ $order['transaction_number'] }}</span>
                                            <br>
                                            <span
                                                style="font-size: 7pt; color: #777;">{{ ucfirst(str_replace('_', ' ', $order['status'])) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <div style="padding: 10px; text-align: center; color: #999; font-style: italic;">Belum ada pesanan
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $status = $item['status'];
                            $class = match ($status) {
                                'delivered' => 'bg-green',
                                'in_delivery' => 'bg-blue',
                                'ready_to_deliver' => 'bg-yellow',
                                'scheduled' => 'bg-purple',
                                default => 'bg-gray',
                            };
                        @endphp
                        <span class="badge {{ $class }}">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pengiriman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>