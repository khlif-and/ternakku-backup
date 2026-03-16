@php
    $months = collect();
    for ($i = 5; $i >= 0; $i--) {
        $months->push(\Carbon\Carbon::now()->subMonths($i)->format('Y-m'));
    }
    $soData = $months->map(fn($m) => $salesOrderTrend[$m] ?? 0)->values();
    $slData = $months->map(fn($m) => $saleLivestockTrend[$m] ?? 0)->values();
    $monthLabels = $months->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'))->values();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-1">Tren Sales Order</h4>
        <p class="text-sm text-gray-500 mb-4">Jumlah sales order per bulan (6 bulan terakhir).</p>
        <div class="h-64">
            <canvas id="salesOrderChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-1">Tren Penjualan Ternak</h4>
        <p class="text-sm text-gray-500 mb-4">Jumlah transaksi penjualan per bulan (6 bulan terakhir).</p>
        <div class="h-64">
            <canvas id="saleLivestockChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const soCtx = document.getElementById('salesOrderChart').getContext('2d');
    new Chart(soCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Sales Order',
                data: {!! json_encode($soData) !!},
                backgroundColor: 'rgba(14, 165, 233, 0.8)',
                borderRadius: 6,
                barThickness: 32,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#6b7280', font: { size: 12 } } },
                y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#6b7280', font: { size: 12 }, stepSize: 1 } }
            }
        }
    });

    const slCtx = document.getElementById('saleLivestockChart').getContext('2d');
    new Chart(slCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [{
                label: 'Penjualan Ternak',
                data: {!! json_encode($slData) !!},
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderRadius: 6,
                barThickness: 32,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#6b7280', font: { size: 12 } } },
                y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#6b7280', font: { size: 12 }, stepSize: 1 } }
            }
        }
    });
});
</script>
@endpush
