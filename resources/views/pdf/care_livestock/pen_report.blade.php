<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kandang</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 25px;
        }

        h2 {
            margin-bottom: 0;
            font-size: 22px;
            text-align: center;
        }

        .farm-name {
            text-align: center;
            font-size: 14px;
            margin-bottom: 5px;
            color: #444;
        }

        .period {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: #efefef;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        table td {
            border: 1px solid #ddd;
            padding: 7px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 25px;
            margin-bottom: 8px;
            color: #222;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }
    </style>

</head>
<body>

    <h2>Laporan Kandang</h2>

    <div class="farm-name">
        {{ $farm->name ?? 'Nama Farm Tidak Ditemukan' }}
    </div>

    <div class="period">
        Periode: <strong>{{ date('d M Y', strtotime($from_date)) }}</strong>
        –
        <strong>{{ date('d M Y', strtotime($to_date)) }}</strong>
    </div>

    <!-- ===================== -->
    <!-- BAGIAN DATA KANDANG   -->
    <!-- ===================== -->
    <div class="section-title">Informasi Kandang</div>

    <table>
        <tr>
            <th>Nama Kandang</th>
            <td>{{ $pen->name ?? '-' }}</td>
        </tr>

        <tr>
            <th>Kapasitas</th>
            <td>{{ $pen->capacity ?? '-' }}</td>
        </tr>

        <tr>
            <th>Luas Area</th>
            <td>
                @if(isset($pen->area))
                    {{ $pen->area }} m²
                @else
                    -
                @endif
            </td>
        </tr>

        <tr>
            <th>Jenis Kandang</th>
            <td>{{ $pen->type ?? '-' }}</td>
        </tr>

        <tr>
            <th>Deskripsi</th>
            <td>{{ $pen->description ?? '-' }}</td>
        </tr>

        <tr>
            <th>Foto</th>
            <td class="text-center">
                @if ($pen->photo)
                    <img src="{{ public_path('storage/' . $pen->photo) }}" class="photo">
                @else
                    Tidak ada foto
                @endif
            </td>
        </tr>

        <tr>
            <th>Dibuat Pada</th>
            <td>{{ $pen->created_at->format('d M Y') }}</td>
        </tr>

    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>
