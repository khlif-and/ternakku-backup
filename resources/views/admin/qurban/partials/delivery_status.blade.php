@php
    $statusLabels = $deliveryStatuses->keys()->map(function ($s) {
        return match (strtolower($s)) {
            'completed' => 'Selesai',
            'pending' => 'Menunggu',
            'on_delivery' => 'Dalam Pengiriman',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($s),
        };
    })->values();
    $statusData = $deliveryStatuses->values();
    $statusColors = $deliveryStatuses->keys()->map(function ($s) {
        return match (strtolower($s)) {
            'completed' => '#22c55e',
            'pending' => '#f59e0b',
            'on_delivery' => '#3b82f6',
            'cancelled' => '#ef4444',
            default => '#8b5cf6',
        };
    })->values();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-1">Status Pengiriman</h4>
        <p class="text-sm text-gray-500 mb-4">Distribusi status surat jalan.</p>
        <div class="flex items-center justify-center h-56">
            @if($deliveryStatuses->count() > 0)
                <canvas id="deliveryStatusChart"></canvas>
            @else
                <p class="text-sm text-gray-400 italic">Belum ada data pengiriman.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-1">Ringkasan Operasional</h4>
        <p class="text-sm text-gray-500 mb-4">Detail data qurban.</p>
        <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                <span>Total Sales Order</span>
                <span class="font-semibold text-gray-900 px-2 py-0.5 bg-sky-50 rounded-md">{{ $salesOrderCount }}</span>
            </li>
            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                <span>Total Pelanggan</span>
                <span class="font-semibold text-gray-900 px-2 py-0.5 bg-emerald-50 rounded-md">{{ $customerCount }}</span>
            </li>
            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                <span>Total Penjualan Ternak</span>
                <span class="font-semibold text-gray-900 px-2 py-0.5 bg-amber-50 rounded-md">{{ $saleLivestockCount }}</span>
            </li>
            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                <span>Total Surat Jalan</span>
                <span class="font-semibold text-gray-900 px-2 py-0.5 bg-red-50 rounded-md">{{ $deliveryCount }}</span>
            </li>
            <li class="flex justify-between items-center py-2">
                <span>Instruksi Pengiriman</span>
                <span class="font-semibold text-gray-900 px-2 py-0.5 bg-purple-50 rounded-md">{{ $instructionCount }}</span>
            </li>
        </ul>
    </div>
</div>

@if($deliveryStatuses->count() > 0)
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const dsCtx = document.getElementById('deliveryStatusChart').getContext('2d');
    new Chart(dsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusLabels) !!},
            datasets: [{
                data: {!! json_encode($statusData) !!},
                backgroundColor: {!! json_encode($statusColors) !!},
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        color: '#4b5563',
                        font: { size: 12 },
                        padding: 16
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endif
