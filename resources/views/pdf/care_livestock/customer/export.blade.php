<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Customer</title>
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
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Customer</h1>
        <h2>{{ $farm->name }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>No. HP</th>
                <th>Email</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['phone_number'] }}</td>
                    <td>{{ $item['email'] }}</td>
                    <td>{{ $item['address'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>

</html>