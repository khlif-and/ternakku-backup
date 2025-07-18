@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-4 sm:p-6 bg-gray-50 min-h-screen">

        <div>
            <h3 class="font-bold text-2xl text-gray-800">Ternak Kurban Dashboard</h3>
            <p class="text-gray-500 mt-1">Ringkasan visual data ternak dan kandang di peternakan Anda.</p>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-lg bg-green-100 p-4 text-sm font-semibold text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-6 pb-6">
            @include('admin.care_livestock.partials.chart_farm')

            <div class="lg:col-span-2 space-y-6 pb-6">
                @include('admin.care_livestock.partials.main_card')
                @include('admin.care_livestock.partials.distribution_data')
            </div>
        </div>

        @include('admin.care_livestock.partials.milk_analysis_chart', ['analysisData' => $analysisData])
        @include('admin.care_livestock.partials.milk_production_chart', [
            'milkProductionData' => $milkProductionData,
        ])

    </div>
@endsection
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

    $typeCounts = $livestocks->groupBy(fn($item) => $item->livestockType->name ?? 'Lainnya')->map->count();

    $classificationCounts = $livestocks
        ->groupBy(fn($item) => $item->livestockClassification->name ?? 'Tidak diketahui')
        ->map(fn($group) => $group->count());
@endphp

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const livestockChartCtx = document.getElementById('livestockChart').getContext('2d');
            new Chart(livestockChartCtx, {
                type: 'line',
                data: {
                    labels: @json($allDates),
                    datasets: [{
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
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6',
                                borderDash: [3, 5],
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#4b5563',
                                font: {
                                    size: 12,
                                    family: 'Inter, sans-serif'
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#111827',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
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
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });

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
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            const searchInput = document.getElementById('table-search');
            const tableRows = document.querySelectorAll('#kandang-table tbody tr');
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
