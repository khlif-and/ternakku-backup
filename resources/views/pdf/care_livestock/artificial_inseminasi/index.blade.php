<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Laporan Artificial Inseminasi</title>

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
        select, input[type="date"], input[type="time"], input[type="text"], input[type="number"] {
            padding: 8px;
            width: 100%;
            font-size: 14px;
            margin-bottom: 15px;
        }
        textarea {
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
            max-width: 650px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Form Laporan Artificial Inseminasi</h2>

    <form action="{{ route('admin.care-livestock.artificial-inseminasi-report.detail', ['farm_id' => $farm->id]) }}" method="GET">

        {{-- PILIH TERNAK --}}
        <div class="section">
            <label class="label">Pilih Ternak Betina</label>
            <select name="livestock_id">
                <option value="">-- Semua Ternak Betina --</option>

                @foreach ($livestocks as $livestock)
                    @php
                        $eartag = $livestock->eartag_number
                                ?? $livestock->eartag
                                ?? $livestock->ear_tag
                                ?? $livestock->rfid_number
                                ?? '-';

                        $nama = $livestock->name ?? $livestock->nama ?? null;
                        $jenis = optional($livestock->livestockType)->name ?? '-';
                        $ras   = optional($livestock->livestockBreed)->name ?? '-';

                        $label = trim($eartag . ($nama ? ' - '.$nama : ''));
                        $label .= ' (' . $jenis . ($ras !== '-' ? ' - '.$ras : '') . ')';
                    @endphp

                    <option value="{{ $livestock->id }}">{{ $label }}</option>
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

        {{-- WAKTU TINDAKAN --}}
        <div class="section">
            <label class="label">Waktu Tindakan (Opsional)</label>
            <input type="time" name="action_time">
        </div>

        {{-- PETUGAS --}}
        <div class="section">
            <label class="label">Nama Petugas (Opsional)</label>
            <input type="text" name="officer_name" placeholder="Contoh: drh. Andi">
        </div>

        {{-- BREED SEMEN --}}
        <div class="section">
            <label class="label">Ras/Breed Semen (Opsional)</label>
            <select name="semen_breed_id">
                <option value="">-- Semua Breed --</option>
                @foreach ($breeds as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- SIRE --}}
        <div class="section">
            <label class="label">Nama Pejantan (Opsional)</label>
            <input type="text" name="sire_name">
        </div>

        {{-- PRODUSEN --}}
        <div class="section">
            <label class="label">Produsen Semen (Opsional)</label>
            <input type="text" name="semen_producer">
        </div>

        {{-- BATCH --}}
        <div class="section">
            <label class="label">Batch Semen (Opsional)</label>
            <input type="text" name="semen_batch">
        </div>

        {{-- BIAYA --}}
        <div class="section">
            <label class="label">Biaya Minimal (Rp)</label>
            <input type="number" name="cost_min" min="0">

            <label class="label">Biaya Maksimal (Rp)</label>
            <input type="number" name="cost_max" min="0">
        </div>

        {{-- CATATAN --}}
        <div class="section">
            <label class="label">Catatan Berisi (opsional)</label>
            <textarea name="notes" rows="3"></textarea>
        </div>

        {{-- SUBMIT --}}
        <button type="submit">Tampilkan Laporan</button>

    </form>

</div>

</body>
</html>
