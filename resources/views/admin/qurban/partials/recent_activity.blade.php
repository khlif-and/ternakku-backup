<div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h4>
    @if($recentActivities->count() > 0)
        <div class="space-y-3">
            @foreach($recentActivities as $activity)
                <div class="flex items-start gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="mt-1 flex-shrink-0">
                        @if($activity['type'] === 'sales_order')
                            <div class="w-8 h-8 rounded-full bg-sky-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700">{!! $activity['description'] !!}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-400 italic text-center py-6">Belum ada aktivitas terbaru.</p>
    @endif
</div>
