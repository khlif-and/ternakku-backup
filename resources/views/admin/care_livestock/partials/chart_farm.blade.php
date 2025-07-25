<div class="lg:col-span-3 space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-1">Pendaftaran Ternak per Hari</h4>
        <p class="text-sm text-gray-500 mb-4">Tren pendaftaran ternak jantan dan betina.</p>
        <div class="h-80">
            <canvas id="livestockChart"></canvas>
        </div>
    </div>

    <div x-data="{ showModal: false }" class="bg-white shadow-sm rounded-2xl">
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-6 border-b border-gray-200">
            <div>
                <h4 class="text-lg font-semibold text-gray-800">Data Kandang</h4>
                <p class="text-sm text-gray-500 mt-1">Populasi dan kapasitas setiap kandang.</p>
            </div>
            <div class="relative w-full sm:w-64 mt-4 sm:mt-0">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="table-search"
                    class="block w-full pl-10 p-2.5 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Cari kandang...">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="kandang-table" class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Kandang</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Populasi</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Ketersediaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pens as $pen)
                        @php $isEmpty = ($pen->population ?? 0) == 0; @endphp
                        <tr
                            @if(!$isEmpty)
                                onclick="window.location='{{ route('admin.care-livestock.pens.show', [$farm->id, $pen->id]) }}';"
                                class="hover:bg-blue-50 cursor-pointer transition"
                            @else
                                @click="showModal = true"
                                class="bg-gray-50 text-gray-400 cursor-not-allowed"
                            @endif
                            style="transition: background 0.1s"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ $pen->photo ?? 'https://via.placeholder.com/150' }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $pen->name }}</div>
                                        <div class="text-gray-500">Kapasitas: {{ $pen->capacity ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-800 font-medium">
                                @if(!$isEmpty)
                                    {{ $pen->population }}
                                @else
                                    <span class="text-gray-400 italic">Pen kosong</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $availability = ($pen->capacity > 0) ? (($pen->population ?? 0) / $pen->capacity) * 100 : 0;
                                @endphp
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $availability > 85 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $availability }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-lg font-semibold">
                                Tidak ada kandang sama sekali.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="showModal" style="display: none" class="fixed inset-0 flex items-center justify-center z-50 bg-black/40">
            <div @click.away="showModal = false" class="bg-white p-8 rounded-xl shadow-xl max-w-xs text-center">
                <svg class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008h-.008V9.75zm-6 3a6 6 0 0112 0v.5a3 3 0 01-6 0v-.5z"/>
                </svg>
                <div class="font-bold text-lg mb-2 text-gray-700">Pen kosong</div>
                <div class="text-gray-500 mb-5">Tidak ada ternak di kandang ini.</div>
                <button @click="showModal = false" class="mt-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold">Tutup</button>
            </div>
        </div>
    </div>
</div>
