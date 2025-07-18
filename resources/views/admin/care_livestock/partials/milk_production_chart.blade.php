@php
    // Data sudah HARUS collection/array of milk production model dari controller!
    // Example: $milkProductionData = App\Models\MilkProductionGlobal::latest()->take(10)->get();
    $labelDates = $milkProductionData->pluck('transaction_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->values();
    $quantityData = $milkProductionData->pluck('quantity_liters')->values();
@endphp

<div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
    <h2 class="text-lg font-bold mb-4 text-gray-700">Visualisasi Produksi Susu Global (Liter)</h2>
    <canvas id="milkProductionChart" height="120"></canvas>
    @if($quantityData->count() == 0)
        <div class="mt-3 text-sm text-gray-400 italic">Belum ada data produksi susu yang bisa divisualisasikan.</div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('milkProductionChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelDates) !!},
                datasets: [
                    {
                        label: 'Jumlah Produksi (L)',
                        data: {!! json_encode($quantityData) !!},
                        backgroundColor: 'rgba(59,130,246,0.8)',
                        borderRadius: 6,
                        barThickness: 32,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': ' + context.parsed.y + ' L';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 12 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { color: '#6b7280', font: { size: 12 } }
                    }
                }
            }
        });
    });
</script>
@endpush
