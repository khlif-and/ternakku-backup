<!DOCTYPE html>
<html>

<head>
    <title>Laporan Perawatan Individu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
        }

        .header p {
            margin: 5px 0;
        }

        .meta {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-1 {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ $farm->name }}</h2>
        <p>Laporan Perawatan Individu</p>
    </div>

    <div class="meta">
        <strong>Periode:</strong>
        @if ($type == 'single')
            {{ \Carbon\Carbon::parse($data['transaction_date'])->format('d/m/Y') }}
        @else
            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
        @endif
        <br>
        <strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    @if ($type == 'all')
        <div class="meta">
            <strong>Ringkasan:</strong> Total Perawatan: {{ $summary['total_treatments'] }}
        </div>
    @endif

    <table class="w-full">
        <thead>
            <tr>
                <th class="border px-2 py-1">No</th>
                <th class="border px-2 py-1">Tanggal</th>
                <th class="border px-2 py-1">Ternak</th>
                <th class="border px-2 py-1">Kandang</th>
                <th class="border px-2 py-1">Penyakit</th>
                <th class="border px-2 py-1">Obat / Tindakan</th>
                <th class="border px-2 py-1">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @if ($type == 'single')
                @php $item = $data; @endphp
                <tr>
                    <td class="border px-2 py-1 text-center">1</td>
                    <td class="border px-2 py-1 text-center">
                        {{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}
                    </td>
                    <td class="border px-2 py-1">
                        <div>{{ $item['livestock_name'] }}</div>
                        <div class="text-xs text-gray-500">{{ $item['livestock_id'] }} | {{ $item['eartag'] }}</div>
                    </td>
                    <td class="border px-2 py-1 text-center">{{ $item['pen_name'] }}</td>
                    <td class="border px-2 py-1 text-center">{{ $item['disease_name'] }}</td>
                    <td class="border px-2 py-1">
                        @if (count($item['medicine_items']) > 0)
                            <div class="mb-1"><strong>Obat:</strong></div>
                            <ul style="list-style-type: disc; margin-left: 15px;">
                                @foreach ($item['medicine_items'] as $med)
                                    <li>{{ $med['name'] }} ({{ $med['qty'] }} {{ $med['uom'] }})</li>
                                @endforeach
                            </ul>
                        @endif

                        @if (count($item['treatment_items']) > 0)
                            <div class="mt-1 mb-1"><strong>Tindakan:</strong></div>
                            <ul style="list-style-type: disc; margin-left: 15px;">
                                @foreach ($item['treatment_items'] as $act)
                                    <li>{{ $act['name'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td class="border px-2 py-1">{{ $item['notes'] ?? '-' }}</td>
                </tr>
            @else
                @forelse($data as $index => $item)
                    <tr>
                        <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                        <td class="border px-2 py-1 text-center">
                            {{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}
                        </td>
                        <td class="border px-2 py-1">
                            <div>{{ $item['livestock_name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $item['livestock_id'] }} | {{ $item['eartag'] }}</div>
                        </td>
                        <td class="border px-2 py-1 text-center">{{ $item['pen_name'] }}</td>
                        <td class="border px-2 py-1 text-center">{{ $item['disease_name'] }}</td>
                        <td class="border px-2 py-1">
                            @if (count($item['medicine_items']) > 0)
                                <div class="mb-1"><strong>Obat:</strong></div>
                                <ul style="list-style-type: disc; margin-left: 15px;">
                                    @foreach ($item['medicine_items'] as $med)
                                        <li>{{ $med['name'] }} ({{ $med['qty'] }} {{ $med['uom'] }})</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (count($item['treatment_items']) > 0)
                                <div class="mt-1 mb-1"><strong>Tindakan:</strong></div>
                                <ul style="list-style-type: disc; margin-left: 15px;">
                                    @foreach ($item['treatment_items'] as $act)
                                        <li>{{ $act['name'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td class="border px-2 py-1">{{ $item['notes'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border px-2 py-1 text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
</body>

</html>