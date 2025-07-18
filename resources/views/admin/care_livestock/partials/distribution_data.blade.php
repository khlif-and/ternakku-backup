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
