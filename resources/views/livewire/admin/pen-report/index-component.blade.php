<div>
    {{-- Flash Messages --}}
    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-800 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Laporan Kandang</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Pen Select --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kandang</label>
                <select wire:model="pen_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">-- Pilih Kandang --</option>
                    @foreach ($pens as $pen)
                        <option value="{{ $pen->id }}">{{ $pen->name }} (Kapasitas: {{ $pen->capacity }})</option>
                    @endforeach
                </select>
                @error('pen_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- From Date --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" wire:model="from_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('from_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- To Date --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" wire:model="to_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('to_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-end gap-2">
                <button wire:click="generateReport" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="generateReport">Tampilkan Laporan</span>
                    <span wire:loading wire:target="generateReport">Loading...</span>
                </button>

                @if ($showReport)
                    <button wire:click="resetReport" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Report Content --}}
    @if ($showReport && $pen)
        {{-- Statistics Cards --}}
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

        {{-- Pen Info Card --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-bold text-gray-800">Informasi Kandang: {{ $pen->name }}</h3>
                <button wire:click="exportPdf" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center gap-2 disabled:opacity-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span wire:loading.remove wire:target="exportPdf">Download PDF</span>
                    <span wire:loading wire:target="exportPdf">Generating...</span>
                </button>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Kapasitas:</span>
                    <span class="font-semibold">{{ $pen->capacity ?? '-' }} ekor</span>
                </div>
                <div>
                    <span class="text-gray-500">Luas Area:</span>
                    <span class="font-semibold">{{ $pen->area ?? '-' }} m²</span>
                </div>
                <div>
                    <span class="text-gray-500">Periode:</span>
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($to_date)->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Farm:</span>
                    <span class="font-semibold">{{ $farm->name }}</span>
                </div>
            </div>
        </div>

        {{-- Tabs for different sections --}}
        <div x-data="{ activeTab: 'livestock' }" class="bg-white rounded-xl shadow-sm border overflow-hidden">
            {{-- Tab Headers --}}
            <div class="flex border-b">
                <button @click="activeTab = 'livestock'" :class="activeTab === 'livestock' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition">
                    Daftar Ternak ({{ count($livestocks) }})
                </button>
                <button @click="activeTab = 'feeding'" :class="activeTab === 'feeding' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition">
                    Riwayat Pakan ({{ count($feedingHistory) }})
                </button>
                <button @click="activeTab = 'treatment'" :class="activeTab === 'treatment' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition">
                    Riwayat Pengobatan ({{ count($treatmentHistory) }})
                </button>
                <button @click="activeTab = 'milk'" :class="activeTab === 'milk' ? 'bg-emerald-50 text-emerald-700 border-b-2 border-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-medium transition">
                    Produksi Susu ({{ count($milkProduction) }})
                </button>
            </div>

            {{-- Tab Content: Livestock --}}
            <div x-show="activeTab === 'livestock'" class="p-6">
                @if (count($livestocks) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode/Eartag</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis Kelamin</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Klasifikasi</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($livestocks as $index => $livestock)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $livestock->eartag ?? $livestock->code ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $livestock->livestockType->name ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $livestock->livestockSex->name ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $livestock->livestockClassification->name ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            @if ($livestock->is_alive)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hidup</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Mati</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Tidak ada data ternak di kandang ini.</p>
                @endif
            </div>

            {{-- Tab Content: Feeding --}}
            <div x-show="activeTab === 'feeding'" x-cloak class="p-6">
                @if (count($feedingHistory) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Pakan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Jumlah</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($feedingHistory as $feeding)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($feeding->feeding_date)->format('d M Y') }}</td>
                                        <td class="px-4 py-3">
                                            @foreach ($feeding->feedingColonyDs as $detail)
                                                <span class="block">{{ $detail->feed->name ?? '-' }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3">
                                            @foreach ($feeding->feedingColonyDs as $detail)
                                                <span class="block">{{ $detail->quantity ?? '-' }} {{ $detail->feed->unit ?? '' }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3">{{ $feeding->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Tidak ada riwayat pemberian pakan pada periode ini.</p>
                @endif
            </div>

            {{-- Tab Content: Treatment --}}
            <div x-show="activeTab === 'treatment'" x-cloak class="p-6">
                @if (count($treatmentHistory) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Obat</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Dosis</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($treatmentHistory as $treatment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($treatment->treatment_date)->format('d M Y') }}</td>
                                        <td class="px-4 py-3">
                                            @foreach ($treatment->treatmentColonyDs as $detail)
                                                <span class="block">{{ $detail->medicine->name ?? '-' }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3">
                                            @foreach ($treatment->treatmentColonyDs as $detail)
                                                <span class="block">{{ $detail->dosage ?? '-' }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3">{{ $treatment->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Tidak ada riwayat pengobatan pada periode ini.</p>
                @endif
            </div>

            {{-- Tab Content: Milk Production --}}
            <div x-show="activeTab === 'milk'" x-cloak class="p-6">
                @if (count($milkProduction) > 0)
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-600">Total Produksi:</span>
                                <span class="font-bold text-blue-800">{{ number_format($statistics['total_milk'] ?? 0, 2) }} Liter</span>
                            </div>
                            <div>
                                <span class="text-blue-600">Rata-rata per Hari:</span>
                                <span class="font-bold text-blue-800">{{ number_format($statistics['avg_milk_per_day'] ?? 0, 2) }} Liter</span>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Volume (L)</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($milkProduction as $production)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($production->production_date)->format('d M Y') }}</td>
                                        <td class="px-4 py-3 font-medium">{{ number_format($production->total_volume ?? 0, 2) }}</td>
                                        <td class="px-4 py-3">{{ $production->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">Tidak ada data produksi susu pada periode ini.</p>
                @endif
            </div>
        </div>
    @endif
</div>
