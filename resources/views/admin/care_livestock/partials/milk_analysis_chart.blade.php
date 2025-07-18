@php
    $labelDates = $analysisData->pluck('transaction_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->values();
    $bjData = $analysisData->pluck('bj')->values();
    $mbrtData = $analysisData->pluck('mbrt')->values();
    $proteinData = $analysisData->pluck('protein')->values();
    $fatData = $analysisData->pluck('fat')->values();
@endphp

<div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
    <h2 class="text-lg font-bold mb-4 text-gray-700">Visualisasi Analisis Susu Global</h2>
    <canvas id="milkAnalysisChart" height="120"></canvas>
    @if($labelDates->count() < 1)
        <div class="mt-3 text-sm text-gray-400 italic">Belum ada data analisis susu global.</div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('milkAnalysisChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelDates) !!},
                datasets: [
                    {
                        label: 'BJ',
                        data: {!! json_encode($bjData) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        barPercentage: 0.22,
                        categoryPercentage: 1,
                    },
                    {
                        label: 'MBRT',
                        data: {!! json_encode($mbrtData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        barPercentage: 0.22,
                        categoryPercentage: 1,
                    },
                    {
                        label: 'Protein',
                        data: {!! json_encode($proteinData) !!},
                        backgroundColor: 'rgba(255, 205, 86, 0.7)',
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 1,
                        barPercentage: 0.22,
                        categoryPercentage: 1,
                    },
                    {
                        label: 'Fat',
                        data: {!! json_encode($fatData) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        barPercentage: 0.22,
                        categoryPercentage: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    title: {
                        display: false,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        stacked: true
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
