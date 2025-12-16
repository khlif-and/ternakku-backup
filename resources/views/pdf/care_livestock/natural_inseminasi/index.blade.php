<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Laporan Natural Inseminasi</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            padding: 20px;
            color: #333;
        }
        h2 {
            margin-bottom: 10px;
            font-size: 22px;
        }
        .section {
            margin-bottom: 25px;
        }
        .label {
            font-weight: bold;
            margin-bottom: 6px;
            display: inline-block;
        }
        select, input[type="date"] {
            padding: 8px;
            width: 100%;
            font-size: 14px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 16px;
            font-size: 15px;
            border: none;
            color: white;
            background: #3b82f6;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background: #2563eb;
        }
        .container {
            max-width: 500px;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Form Laporan Natural Inseminasi</h2>

        <form action="{{ route('admin.care-livestock.natural-inseminasi-report.detail', ['farm_id' => $farm->id]) }}" method="GET">

            {{-- PILIH TERNAK --}}
            <div class="section">
                <label class="label">Pilih Ternak Betina</label>
                <select name="livestock_id" required>
                    <option value="">-- Pilih Ternak --</option>
                    @foreach ($livestocks as $ls)
                        <option value="{{ $ls->id }}">
                            {{ $ls->eartag_number ?? '-' }} - {{ optional($ls->livestockBreed)->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TANGGAL MULAI --}}
            <div class="section">
                <label class="label">Tanggal Mulai</label>
                <input type="date" name="start_date" required>
            </div>

            {{-- TANGGAL AKHIR --}}
            <div class="section">
                <label class="label">Tanggal Akhir</label>
                <input type="date" name="end_date" required>
            </div>

            {{-- SUBMIT --}}
            <button type="submit">Tampilkan Laporan</button>

        </form>

    </div>

</body>
</html>
