@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
        {{-- HEADER --}}
        <div>
            <h3 class="font-bold text-2xl text-gray-800">Ternak Kurban Dashboard</h3>
            <p class="text-gray-500 mt-1">Ringkasan visual data ternak dan kandang di peternakan Anda.</p>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-lg bg-green-100 p-4 text-sm font-semibold text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✨ TATA LETAK UTAMA DENGAN GRID --}}
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Kolom Kiri: Chart & Tabel --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- CHART GARIS --}}
                <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-1">Pendaftaran Ternak per Hari</h4>
                    <p class="text-sm text-gray-500 mb-4">Tren pendaftaran ternak jantan dan betina.</p>
                    <div class="h-80">
                        <canvas id="livestockChart"></canvas>
                    </div>
                </div>
                {{-- TABEL KANDANG --}}
                <div class="bg-white shadow-sm rounded-2xl">
                     <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Data Kandang</h4>
                            <p class="text-sm text-gray-500 mt-1">Populasi dan kapasitas setiap kandang.</p>
                        </div>
                         <div class="relative w-full sm:w-64 mt-4 sm:mt-0">
                             <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                 <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
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
                                    <tr class="hover:bg-gray-50">
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
                                            {{ $pen->population ?? 0 }}
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
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada data kandang.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Kartu Info & Chart Donut --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- KARTU INFO UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="text-sm text-gray-500 mb-1">Total Ternak</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $maleCount + $femaleCount }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="text-sm text-gray-500 mb-1">Jantan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $maleCount }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="text-sm text-gray-500 mb-1">Betina</p>
                        <p class="text-3xl font-bold text-pink-600">{{ $femaleCount }}</p>
                    </div>
                </div>

                {{-- DISTRIBUSI DATA --}}
                <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Data</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div>
                            <h5 class="font-semibold text-gray-600 text-sm mb-2">Berdasarkan Jenis</h5>
                            <ul class="space-y-2 text-sm text-gray-700">
                                @forelse ($typeCounts as $type => $count)
                                    <li class="flex justify-between items-center">
                                        <span>{{ $type }}</span>
                                        <span class="font-semibold text-gray-900 px-2 py-0.5 bg-gray-100 rounded-md">{{ $count }}</span>
                                    </li>
                                @empty
                                    <li>Tidak ada data</li>
                                @endforelse
                            </ul>
                            <h5 class="font-semibold text-gray-600 text-sm mt-4 mb-2">Berdasarkan Klasifikasi</h5>
                            <ul class="space-y-2 text-sm text-gray-700">
                                @forelse ($classificationCounts as $class => $count)
                                    <li class="flex justify-between items-center">
                                        <span>{{ $class }}</span>
                                        <span class="font-semibold text-gray-900 px-2 py-0.5 bg-gray-100 rounded-md">{{ $count }}</span>
                                    </li>
                                @empty
                                    <li>Tidak ada data</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="h-48 md:h-full flex items-center justify-center">
                             <canvas id="classificationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Blok PHP dijaga tetap sama --}}
@php
    use Illuminate\Support\Carbon;

    $grouped = $livestocks->groupBy(
        fn($item) => Carbon::parse($item->livestockReceptionH->transaction_date)->format('Y-m-d'),
    );
    $allDates = $grouped->keys()->sort()->values();
    $maleData = [];
    $femaleData = [];

    foreach ($allDates as $date) {
        $group = $grouped[$date];
        $maleData[] = $group->filter(fn($x) => strtolower($x->livestockSex->name ?? '') === 'jantan')->count();
        $femaleData[] = $group->filter(fn($x) => strtolower($x->livestockSex->name ?? '') === 'betina')->count();
    }

    $typeCounts = $livestocks
        ->groupBy(fn($item) => $item->livestockType->name ?? 'Lainnya')
        ->map->count();

    $classificationCounts = $livestocks
        ->groupBy(fn($item) => $item->livestockClassification->name ?? 'Tidak diketahui')
        ->map(fn($group) => $group->count());
@endphp

{{-- Skrip disesuaikan untuk chart baru dan gaya modern --}}
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ✨ KONFIGURASI LINE CHART DENGAN UI MODERN
            const livestockChartCtx = document.getElementById('livestockChart').getContext('2d');
            new Chart(livestockChartCtx, {
                type: 'line',
                data: {
                    labels: @json($allDates),
                    datasets: [
                        {
                            label: 'Jantan',
                            data: @json($maleData),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointBackgroundColor: '#3b82f6',
                            pointHoverRadius: 6,
                            pointHoverBorderWidth: 2,
                            pointHoverBorderColor: 'white'
                        },
                        {
                            label: 'Betina',
                            data: @json($femaleData),
                            borderColor: '#ec4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointBackgroundColor: '#ec4899',
                            pointHoverRadius: 6,
                            pointHoverBorderWidth: 2,
                            pointHoverBorderColor: 'white'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // ✨ Konfigurasi Sumbu (Scales) dengan Grid Halus
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6', // Warna grid sangat lembut
                                borderDash: [3, 5],
                            },
                            border: {
                                display: false // Sembunyikan garis sumbu Y
                            },
                            ticks: {
                                color: '#6b7280', // Warna teks sumbu
                                font: { size: 12 }
                            }
                        },
                        x: {
                            grid: {
                                display: false, // Sembunyikan grid vertikal
                            },
                            ticks: {
                                color: '#6b7280',
                                font: { size: 12 }
                            }
                        }
                    },
                    // ✨ Konfigurasi Plugin (Legenda & Tooltip)
                    plugins: {
                        // Legenda yang lebih rapi
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#4b5563',
                                font: { size: 12, family: 'Inter, sans-serif' },
                                padding: 20
                            }
                        },
                        // Tooltip dengan gaya modern
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#111827', // Background gelap
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            boxPadding: 4,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return ' ' + label;
                                }
                            }
                        }
                    },
                    // ✨ Mode Interaksi untuk Tooltip yang Lebih Baik
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });

            // Konfigurasi Donut Chart (Tetap sama)
            const classificationChartCtx = document.getElementById('classificationChart').getContext('2d');
            new Chart(classificationChartCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($classificationCounts->keys()),
                    datasets: [{
                        data: @json($classificationCounts->values()),
                        backgroundColor: ['#3b82f6', '#ef4444', '#22c55e', '#f97316', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });

            // Logic Pencarian Tabel (Tetap sama)
            const searchInput = document.getElementById('table-search');
            const tableRows = document.querySelectorAll('#kandang-table tbody tr');
            searchInput.addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
