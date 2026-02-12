<div class="card p-4 border-l-4 border-blue-500 bg-blue-50">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                <path d="M12 3v3m0 12v3" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-xl font-bold text-gray-800">Rp
                {{ number_format($statistics['total_revenue'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>

<div class="card p-4 border-l-4 border-green-500 bg-green-50">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-green-100 rounded-lg text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 7c-3 0 -4 3 -4 5.5c0 2.5 1.2 4.5 1.5 5c0.3 -0.5 4.5 -5 4.5 -5c0 -2.5 -1 -5.5 -2 -5.5z" />
                <path d="M15 7c3 0 4 3 4 5.5c0 2.5 -1.2 4.5 -1.5 5c-0.3 -0.5 -4.5 -5 -4.5 -5c0 -2.5 1 -5.5 2 -5.5z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Ternak Terjual</p>
            <p class="text-xl font-bold text-gray-800">
                {{ number_format($statistics['total_livestock_sold'] ?? 0, 0, ',', '.') }} Ekor
            </p>
        </div>
    </div>
</div>