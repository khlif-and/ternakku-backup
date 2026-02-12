<div>
    <div class="card p-5 mb-5 border-none shadow-none">
        <h1 class="text-xl font-bold mb-4">Laporan Penjualan Ternak</h1>
        <div class="flex flex-col gap-1 text-sm text-gray-500">
            <p>Laporan ini menampilkan data transaksi penjualan ternak (Qurban Sales).</p>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger mb-5">
            {{ session('error') }}
        </div>
    @endif

    <div class="card p-5 mb-5 border-none shadow-none">
        @include('livewire.reports.care-livestock.sales-livestock-report.partials.filter')
    </div>

    @if($showReport)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
            @include('livewire.reports.care-livestock.sales-livestock-report.partials.statistics')
        </div>

        <div class="card p-5 border-none shadow-none">
            @include('livewire.reports.care-livestock.sales-livestock-report.partials.table')
        </div>
    @endif
</div>