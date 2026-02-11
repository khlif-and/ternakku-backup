<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Total Ternak</p>
        <p class="text-2xl font-bold text-gray-800">{{ $statistics['total_livestock'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Hidup</p>
        <p class="text-2xl font-bold text-emerald-600">{{ $statistics['alive_livestock'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Jantan</p>
        <p class="text-2xl font-bold text-blue-600">{{ $statistics['male_count'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Betina</p>
        <p class="text-2xl font-bold text-pink-600">{{ $statistics['female_count'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Pemberian Pakan</p>
        <p class="text-2xl font-bold text-orange-600">{{ $statistics['total_feedings'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Pengobatan</p>
        <p class="text-2xl font-bold text-purple-600">{{ $statistics['total_treatments'] ?? 0 }}</p>
    </div>
</div>
