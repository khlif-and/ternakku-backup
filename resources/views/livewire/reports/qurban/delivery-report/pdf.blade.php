<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Pengiriman Hewan Qurban</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header h3 {
            margin: 5px 0;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9pt;
        }

        table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8pt;
            color: white;
            display: inline-block;
        }

        .bg-green {
            background-color: #28a745;
        }

        .bg-blue {
            background-color: #007bff;
        }

        .bg-yellow {
            background-color: #ffc107;
            color: black;
        }

        .bg-gray {
            background-color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Pengiriman Hewan Qurban</h2>
        <h3>{{ $farm->name }}</h3>
        <p>Periode: {{ $start_date }} - {{ $end_date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">No Transaksi</th>
                <th style="width: 20%">Driver</th>
                <th style="width: 15%">Armada</th>
                <th style="width: 15%" class="text-center">Total Pesanan</th>
                <th style="width: 15%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                {{-- Data is Collection of Resources, resolved to arrays in Controller --}}
                @php $item = $item->resolve(); @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item['delivery_date'])->format('d M Y') }}</td>
                    <td>{{ $item['transaction_number'] }}</td>
                    <td>{{ $item['driver']['name'] ?? '-' }}</td>
                    <td>{{ $item['fleet']['police_number'] ?? '-' }}</td>
                    <td class="text-center">{{ count($item['delivery_orders'] ?? []) }}</td>
                    <td class="text-center">
                        @php
                            $status = $item['status'];
                            $class = 'bg-gray';
                            if ($status == 'delivered')
                                $class = 'bg-green';
                            elseif ($status == 'in_delivery')
                                $class = 'bg-blue';
                            elseif ($status == 'pending')
                                $class = 'bg-yellow';
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

    <div style="margin-top: 30px; font-size: 8pt; color: #666; text-align: right;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>