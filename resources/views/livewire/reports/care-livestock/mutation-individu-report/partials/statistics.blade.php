<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Total Mutasi</p>
        <p class="text-2xl font-bold text-gray-800">{{ $statistics['total_mutations'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Ternak Terlibat</p>
        <p class="text-2xl font-bold text-blue-600">{{ $statistics['total_livestock_involved'] ?? 0 }}</p>
    </div>
    {{-- Placeholder for future stats --}}
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Periode</p>
        <p class="text-lg font-bold text-gray-800">
            {{ \Carbon\Carbon::parse($start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
        </p>
    </div>
</div>
