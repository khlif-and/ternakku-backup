                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="text-sm text-gray-500 mb-1">Total Ternak</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $maleCount + $femaleCount }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="text-sm text-gray-500 mb-1">Jantan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $maleCount }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <p class="text-sm text-gray-500 mb-1">Betina</p>
                        <p class="text-3xl font-bold text-pink-600">{{ $femaleCount }}</p>
                    </div>
                </div>
