@extends('layouts.care_livestock.index')

@section('content')
@php
    $farmId = request()->route('farm_id');
@endphp

<div class="px-8 py-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Detail Artificial Inseminasi</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan tanggal, ternak, data semen, biaya, dan catatan.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.reproduction.artificial-insemination.edit', ['farm_id' => $farmId, 'id' => $item->id]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Edit
        </a>
        <a href="{{ route('admin.reproduction.artificial-insemination.index', ['farm_id' => $farmId]) }}"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Kembali
        </a>
    </div>
</div>

@if (session('success'))
    <div class="px-8">
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="px-8 py-4 grid lg:grid-cols-3 gap-6">
    {{-- Kolom kiri: info utama --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg border p-4 space-y-3">
            <div>
                <div class="text-xs text-gray-500">Tanggal Inseminasi</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($item->insemination)->transaction_date
                        ? \Carbon\Carbon::parse($item->insemination->transaction_date)->format('d M Y')
                        : '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Waktu Tindakan</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ $item->action_time ?? '-' }}
                </div>
            </div>

            @php
                $lv = optional($item->reproductionCycle)->livestock;
                $eartag = optional($lv)->eartag
                    ?? optional($lv)->eartag_number
                    ?? optional($lv)->ear_tag
                    ?? optional($lv)->tag
                    ?? optional($lv)->code
                    ?? optional($lv)->rfid_number
                    ?? '-';
                $name = optional($lv)->name
                    ?? optional($lv)->nama
                    ?? optional($lv)->display_name
                    ?? optional($lv)->nickname
                    ?? '-';
            @endphp

            <div>
                <div class="text-xs text-gray-500">Eartag</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ $eartag }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Nama Ternak</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ $name }}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <div class="text-xs text-gray-500">Jenis</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ optional(optional($lv)->livestockType)->name ?? '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Ras</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ optional(optional($lv)->livestockBreed)->name ?? '-' }}
                    </div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Kandang</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional(optional($lv)->pen)->name ?? '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Tanggal Perkiraan Siklus</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ $item->cycle_date ? \Carbon\Carbon::parse($item->cycle_date)->format('d M Y') : '-' }}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <div class="text-xs text-gray-500">Biaya</div>
                    <div class="text-sm font-semibold text-gray-800">
                        Rp {{ number_format($item->cost ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Nomor Inseminasi</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $item->insemination_number ?? '-' }}
                    </div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Catatan</div>
                <div class="text-sm text-gray-800">
                    {{ $item->notes ?: (optional($item->insemination)->notes ?: '-') }}
                </div>
            </div>

            <form action="{{ route('admin.reproduction.artificial-insemination.destroy', ['farm_id' => $farmId, 'id' => $item->id]) }}"
                  method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" class="pt-2">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold rounded-lg px-4 py-2 text-sm transition-all">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Kolom kanan: detail semen --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg border overflow-hidden">
            <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Detail Semen & Petugas</div>
            <div class="p-4">
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="space-y-1">
                        <div class="text-gray-500">Nama Petugas</div>
                        <div class="font-semibold text-gray-800">{{ $item->officer_name ?? '-' }}</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500">Breed Semen</div>
                        <div class="font-semibold text-gray-800">
                            {{ optional($item->semenBreed)->name ?? '-' }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500">Nama Pejantan (Sire)</div>
                        <div class="font-semibold text-gray-800">{{ $item->sire_name ?? '-' }}</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500">Produsen Semen</div>
                        <div class="font-semibold text-gray-800">{{ $item->semen_producer ?? '-' }}</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500">Batch Semen</div>
                        <div class="font-semibold text-gray-800">{{ $item->semen_batch ?? '-' }}</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500">Nomor Bunting</div>
                        <div class="font-semibold text-gray-800">{{ $item->pregnant_number ?? '-' }}</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500">Perkiraan Anak ke-</div>
                        <div class="font-semibold text-gray-800">{{ $item->children_number ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
