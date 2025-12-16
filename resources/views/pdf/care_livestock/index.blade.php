<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Laporan Kandang</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            margin: 25px;
            color: #333;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 25px;
        }

        .form-box {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            max-width: 450px;
            margin: 0 auto;
            border: 1px solid #ddd;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            font-weight: bold;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #005fcc;
        }

    </style>
</head>
<body>

    <h2>Buat Laporan Kandang</h2>

    <div class="form-box">

        <form action="{{ route('admin.care-livestock.pen-report.detail', ['farm_id' => $farm->id]) }}" method="GET">

            <label>Pilih Kandang</label>
            <select name="pen_id" required>
                <option value="">-- Pilih Kandang --</option>
                @foreach ($pens as $pen)
                    <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                @endforeach
            </select>

            <label>Dari Tanggal</label>
            <input type="date" name="from_date" required>

            <label>Sampai Tanggal</label>
            <input type="date" name="to_date" required>

            <button type="submit">Tampilkan Laporan</button>
        </form>

    </div>

</body>
</html>
