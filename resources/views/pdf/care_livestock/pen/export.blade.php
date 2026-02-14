<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Kandang</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .meta {
            margin-bottom: 20px;
            font-size: 12px;
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
    <div class="header">
        <h2>Laporan Data Kandang</h2>
        <h3>{{ $farm->name }}</h3>
    </div>

    <div class="meta">
        <strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    <div style="margin-bottom: 20px; border: 1px solid #ddd; padding: 10px;">
        <strong>Ringkasan:</strong><br>
        Total Kapasitas: {{ number_format($summary['total_capacity'], 0, ',', '.') }}<br>
        Total Populasi: {{ number_format($summary['total_population'], 0, ',', '.') }}<br>
        Tingkat Okupansi: {{ $summary['occupancy_rate'] }}%
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Kandang</th>
                <th width="15%" class="text-center">Luas (m²)</th>
                <th width="15%" class="text-center">Kapasitas</th>
                <th width="15%" class="text-center">Terisi</th>
                <th width="15%" class="text-center">Sisa</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-center">{{ $item['area'] }}</td>
                    <td class="text-center">{{ $item['capacity'] }}</td>
                    <td class="text-center font-bold">{{ $item['population'] }}</td>
                    <td class="text-center">{{ $item['remaining_capacity'] }}</td>
                    <td class="text-center">{{ $item['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>