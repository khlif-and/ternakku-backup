<header class="bg-white w-full px-6 py-3 flex items-center justify-end shadow z-20">
    @php
        $currentFarm = \App\Models\Farm::find(session('selected_farm'));
    @endphp

    <div class="relative" x-data="{ open:false }">
        <button @click="open = !open"
                class="flex items-center gap-2 px-3 py-1 rounded-full hover:bg-slate-100">
            <img src="{{ asset('admin/img/profile.jpg') }}"
                 class="h-8 w-8 rounded-full ring-2 ring-white" alt="Foto">
            <span class="text-sm font-semibold text-slate-700 hidden sm:inline">
                Halo, {{ strtok(auth()->user()->name,' ') }}
            </span>
            <svg :class="{ 'rotate-180': open }"
                 class="h-4 w-4 text-gray-500 transition"
                 viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                      clip-rule="evenodd"/>
            </svg>
        </button>

        {{-- ===== Dropdown ===== --}}
        <div  x-show="open" x-transition @click.away="open = false"
              class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg z-50 p-2 border border-slate-200">

            {{-- Info user + kandang --}}
            <div class="p-3 border-b border-slate-100 mb-2 flex items-center gap-3">
                <img src="{{ asset('admin/img/profile.jpg') }}" class="h-10 w-10 rounded-full" alt="Foto">
                <div>
                    <p class="font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>

                    @if($currentFarm)
                        <p class="text-xs font-medium text-emerald-600 mt-1">
                            🐄 {{ $currentFarm->name }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- ===== Menu items ===== --}}
            <a href="{{ url('dashboard') }}"
               class="block px-3 py-2 text-sm hover:bg-slate-100 rounded">
                Home / Dashboard
            </a>

            <a href="#" class="block px-3 py-2 text-sm hover:bg-slate-100 rounded">
                Profil Saya
            </a>

            <button @click="window.location.href='{{ url('select-farm') }}'"
                    class="block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 rounded">
                Ganti Kandang / Pen
            </button>

            <button @click="logoutModal = true; open = false"
                    class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
                Logout
            </button>
        </div>
    </div>
</header>
