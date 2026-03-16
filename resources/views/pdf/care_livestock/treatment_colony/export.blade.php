<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Perawatan Koloni</title>

    <style>
        body {
            font-family: sans-serif;
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

        ul {
            margin: 0;
            padding-left: 15px;
        }
    </style>
</head>

<body>

    <div class="title">Laporan Perawatan Koloni</div>
    <div class="subtitle">{{ $farm->name ?? '-' }}</div>

    @if($type === 'all')
        <p>
            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} s/d
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
            @if (!empty($filters['pen_id']))
                <br><strong>Kandang:</strong> {{ $data->first()['pen_name'] ?? '-' }}
            @endif
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Kandang</th>
                <th width="20%">Penyakit</th>
                <th width="45%">Obat / Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d/m/Y') }}</td>
                    <td>{{ $item['pen_name'] }}</td>
                    <td>{{ $item['disease_name'] }}</td>
                    <td>
                        @if(count($item['medicine_items']) > 0)
                            <div style="margin-bottom: 5px;">
                                <strong>Obat:</strong>
                                <ul>
                                    @foreach($item['medicine_items'] as $med)
                                        <li>{{ $med['name'] }} ({{ $med['qty'] }} {{ $med['uom'] }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($item['treatment_items']) > 0)
                            <div>
                                <strong>Tindakan:</strong>
                                <ul>
                                    @foreach($item['treatment_items'] as $act)
                                        <li>{{ $act['name'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($item['medicine_items']) == 0 && count($item['treatment_items']) == 0)
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem Ternakku — {{ date('d M Y H:i') }}
    </div>

</body>

</html>