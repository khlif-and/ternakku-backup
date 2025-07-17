            <section class="grid grid-cols-1 gap-6 p-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5" style="perspective: 1000px;">

                <button
                    onclick="event.stopPropagation(); setTimeout(() => window.location.href='{{ url('select-farm?redirect_url=care-livestock/dashboard') }}', 100)"
                    class="card-container group relative block w-full transform-gpu text-left transition-all duration-300 ease-out hover:z-10">
                    {{-- DIV UNTUK EFEK NEON DIHAPUS DARI SINI --}}
                    <div class="card-content relative z-10 flex h-full flex-col items-center justify-center rounded-2xl bg-white/80 p-6 text-center backdrop-blur-sm transition-colors duration-300 dark:bg-white/80">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-100 shadow-lg shadow-emerald-500/10 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-200 dark:bg-emerald-900/50 dark:group-hover:bg-emerald-900">
                            <img src="{{ asset('home/assets/icons/ic_pelihara_ternak.png') }}" alt="Pelihara Ternak" class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-95" />
                        </div>
                        <p class="font-bold text-slate-700 transition-colors duration-300 group-hover:text-emerald-700 dark:text-slate-700 dark:group-hover:text-emerald-700">
                            Pelihara Ternak
                        </p>
                    </div>
                </button>

                <a href="#" class="card-container group relative block w-full transform-gpu text-left transition-all duration-300 ease-out hover:z-10">
                    {{-- DIV UNTUK EFEK NEON DIHAPUS DARI SINI --}}
                    <div class="card-content relative z-10 flex h-full flex-col items-center justify-center rounded-2xl bg-white/80 p-6 text-center backdrop-blur-sm transition-colors duration-300 dark:bg-white/80">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-100 shadow-lg shadow-emerald-500/10 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-200 dark:bg-emerald-900/50 dark:group-hover:bg-emerald-900">
                            <img src="{{ asset('home/assets/icons/ic_pelihara_ternak.png') }}" alt="Pakan & Keswan" class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-95" />
                        </div>
                        <p class="font-bold text-slate-700 transition-colors duration-300 group-hover:text-emerald-700 dark:text-slate-700 dark:group-hover:text-emerald-700">
                            Pakan & Keswan
                        </p>
                    </div>
                </a>

                <a href="#" class="card-container group relative block w-full transform-gpu text-left transition-all duration-300 ease-out hover:z-10">
                    {{-- DIV UNTUK EFEK NEON DIHAPUS DARI SINI --}}
                    <div class="card-content relative z-10 flex h-full flex-col items-center justify-center rounded-2xl bg-white/80 p-6 text-center backdrop-blur-sm transition-colors duration-300 dark:bg-white/80">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-100 shadow-lg shadow-emerald-500/10 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-200 dark:bg-emerald-900/50 dark:group-hover:bg-emerald-900">
                            <img src="{{ asset('home/assets/icons/ic_pelihara_ternak.png') }}" alt="Gerai Ternak" class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-95" />
                        </div>
                        <p class="font-bold text-slate-700 transition-colors duration-300 group-hover:text-emerald-700 dark:text-slate-700 dark:group-hover:text-emerald-700">
                            Gerai Ternak
                        </p>
                    </div>
                </a>

                <a href="#" class="card-container group relative block w-full transform-gpu text-left transition-all duration-300 ease-out hover:z-10">
                    {{-- DIV UNTUK EFEK NEON DIHAPUS DARI SINI --}}
                    <div class="card-content relative z-10 flex h-full flex-col items-center justify-center rounded-2xl bg-white/80 p-6 text-center backdrop-blur-sm transition-colors duration-300 dark:bg-white/80">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-100 shadow-lg shadow-emerald-500/10 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-200 dark:bg-emerald-900/50 dark:group-hover:bg-emerald-900">
                            <img src="{{ asset('home/assets/icons/ic_pelihara_ternak.png') }}" alt="Usaha Ternak" class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-95" />
                        </div>
                        <p class="font-bold text-slate-700 transition-colors duration-300 group-hover:text-emerald-700 dark:text-slate-700 dark:group-hover:text-emerald-700">
                            Usaha Ternak
                        </p>
                    </div>
                </a>

                <button
                    onclick="event.stopPropagation(); setTimeout(() => window.location.href='{{ url('select-farm?redirect_url=qurban/dashboard') }}', 100)"
                    class="card-container group relative block w-full transform-gpu text-left transition-all duration-300 ease-out hover:z-10">
                    {{-- DIV UNTUK EFEK NEON DIHAPUS DARI SINI --}}
                    <div class="card-content relative z-10 flex h-full flex-col items-center justify-center rounded-2xl bg-white/80 p-6 text-center backdrop-blur-sm transition-colors duration-300 dark:bg-white/80">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-100 shadow-lg shadow-emerald-500/10 transition-all duration-300 group-hover:scale-110 group-hover:bg-emerald-200 dark:bg-emerald-900/50 dark:group-hover:bg-emerald-900">
                            <img src="{{ asset('home/assets/icons/ic_pelihara_ternak.png') }}" alt="Ternak Kurban" class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-95" />
                        </div>
                        <p class="font-bold text-slate-700 transition-colors duration-300 group-hover:text-emerald-700 dark:text-slate-700 dark:group-hover:text-emerald-700">
                            Ternak Kurban
                        </p>
                    </div>
                </button>

            </section>
