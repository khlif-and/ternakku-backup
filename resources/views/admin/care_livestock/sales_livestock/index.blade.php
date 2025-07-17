@extends('layouts.care_livestock.index')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Data Penjualan Ternak</h1>
        <a href="{{ route('admin.care-livestock.sales-livestock.create', $farmId) }}"
           class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 transition">
            + Tambah Penjualan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
<thead class="bg-gray-50">
    <tr>
        <th class="px-4 py-2">No</th>
        <th class="px-4 py-2">Kode Ternak</th>
        <th class="px-4 py-2">Berat</th>
        <th class="px-4 py-2">Harga / kg</th>
        <th class="px-4 py-2">Harga / ekor</th>
        <th class="px-4 py-2">Rencana Kirim</th>
        <th class="px-4 py-2">Aksi</th>
    </tr>
</thead>
<tbody>
    @forelse ($salesLivestocks as $index => $item)
        <tr>
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $item->livestock->code ?? '-' }}</td>
            <td class="px-4 py-2">{{ $item->weight }} kg</td>
            <td class="px-4 py-2">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</td>
            <td class="px-4 py-2">Rp {{ number_format($item->price_per_head, 0, ',', '.') }}</td>
            <td class="px-4 py-2">{{ $item->delivery_plan_date ? date('d M Y', strtotime($item->delivery_plan_date)) : '-' }}</td>
            <td class="px-4 py-2 text-right">
                <a href="{{ route('admin.care-livestock.sales-livestock.edit', [$farmId, $item->id]) }}"
                    class="text-blue-600 hover:underline text-sm">Edit</a>
                <form action="{{ route('admin.care-livestock.sales-livestock.destroy', [$farmId, $item->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline text-sm ml-3"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center text-sm text-gray-500 px-4 py-4">Belum ada data penjualan</td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>
</div>
@endsection
