<section class="mt-12 font-sans">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Aktivitas Terkini</h2>
        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
            Lihat Semua
        </a>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-white/60 backdrop-blur-xl border border-slate-200/80 shadow-md">
        <ul class="relative space-y-8">
            @if(!$recentActivities->isEmpty())
                <div class="absolute left-5 top-2 bottom-2 w-0.5 bg-slate-200/70 rounded-full"></div>
            @endif

            @forelse ($recentActivities as $act)
                @php
                    $types = [
                        'sale' => ['label' => 'Penjualan', 'badge' => 'bg-green-100 text-green-800', 'iconBg' => 'bg-green-100 text-green-700', 'icon' => 'checkmark-done-outline'],
                        'milk_production' => ['label' => 'Produksi', 'badge' => 'bg-blue-100 text-blue-800', 'iconBg' => 'bg-blue-100 text-blue-700', 'icon' => 'flask-outline'],
                        'milk_analysis' => ['label' => 'Analisis', 'badge' => 'bg-purple-100 text-purple-800', 'iconBg' => 'bg-purple-100 text-purple-700', 'icon' => 'analytics-outline'],
                        'feed_medicine_purchase' => ['label' => 'Pembelian', 'badge' => 'bg-orange-100 text-orange-800', 'iconBg' => 'bg-orange-100 text-orange-700', 'icon' => 'cart-outline'],
                        'pen' => ['label' => 'Kandang', 'badge' => 'bg-yellow-100 text-yellow-800', 'iconBg' => 'bg-yellow-100 text-yellow-700', 'icon' => 'business-outline'],
                        'milk' => ['label' => 'Susu', 'badge' => 'bg-teal-100 text-teal-800', 'iconBg' => 'bg-teal-100 text-teal-700', 'icon' => 'water-outline'],
                    ];
                    $typeInfo = $types[$act['type']] ?? ['label' => 'Lainnya', 'badge' => 'bg-gray-100 text-gray-800', 'iconBg' => 'bg-gray-200 text-gray-500', 'icon' => 'ellipsis-horizontal-outline'];
                @endphp

                <li class="relative flex items-start space-x-6">
                    <div class="relative flex-shrink-0 z-10">
                        <span class="absolute -top-1 -left-1 w-12 h-12 bg-white/0 rounded-full"></span>
                        <div class="relative w-10 h-10 flex items-center justify-center rounded-full ring-8 ring-white/80 backdrop-blur-sm {{ $typeInfo['iconBg'] }}">
                            <ion-icon name="{{ $typeInfo['icon'] }}" class="text-xl"></ion-icon>
                        </div>
                    </div>

                    <div class="flex-grow min-w-0">
                        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                             <p class="text-slate-700 text-sm font-medium flex-1">{!! $act['description'] !!}</p>
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $typeInfo['badge'] }}">
                                 {{ $typeInfo['label'] }}
                             </span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="flex flex-col items-center justify-center text-center py-12">
                    <div class="w-16 h-16 flex items-center justify-center bg-slate-100 rounded-full mb-4">
                        <ion-icon name="archive-outline" class="text-3xl text-slate-400"></ion-icon>
                    </div>
                    <h3 class="font-semibold text-slate-700">Belum Ada Aktivitas</h3>
                    <p class="text-sm text-slate-500 mt-1">Semua aktivitas terbaru akan muncul di sini.</p>
                </li>
            @endforelse
        </ul>
    </div>
</section>
