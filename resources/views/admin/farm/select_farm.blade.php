@extends('layouts.auth.index')

@section('content')
<div class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-12 font-sans">
    <div class="mx-auto w-full max-w-md animate-fade-in" x-data="{
        open: false,
        selectedFarm: '{{ old('farm_id') ?? $farms->first()?->farm_id }}',
        selectedName: '{{ old('farm_id') ? $farms->firstWhere('farm_id', old('farm_id'))?->farm->name : $farms->first()?->farm->name }}'
    }">
        <div class="text-center">
            <h2 class="text-3xl font-bold tracking-tight text-slate-800">Pilih Peternakan</h2>
            <p class="mt-2 text-base text-slate-500">Pilih salah satu peternakan Anda untuk melanjutkan.</p>
        </div>

        <div class="mt-8 rounded-2xl border border-slate-200/80 bg-white p-8 shadow-xl">

            @if (session('success'))
                <div class="mb-6 rounded-lg border-l-4 border-green-400 bg-green-50 p-4" role="alert">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border-l-4 border-red-400 bg-red-50 p-4" role="alert">
                    <h3 class="font-bold text-red-800">Oops! Ada kesalahan</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('select-farm') }}" method="POST" class="w-full"
                  @submit.prevent="$refs.farmInput.value = selectedFarm; $el.submit();">
                @csrf
                <input type="hidden" name="redirect_url" value="{{ request()->query('redirect_url') }}">
                <input type="hidden" name="farm_id" x-ref="farmInput">

                <div class="mb-8">
                    @if($farms->isNotEmpty())
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Pilih dari Peternakan Anda:</label>

                        <div class="relative">
                            <button type="button"
                                    class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-slate-50 px-4 py-4 text-left font-semibold text-slate-800 shadow-sm transition hover:ring-2 hover:ring-emerald-300"
                                    @click="open = !open">
                                <span x-text="selectedName"></span>
                                <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform duration-300"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <ul x-show="open" @click.away="open = false"
                                x-transition
                                class="absolute z-10 mt-2 max-h-60 w-full overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg">
                                @foreach ($farms as $farm)
                                    <li>
                                        <button type="button"
                                                @click="selectedFarm = '{{ $farm->farm_id }}'; selectedName = '{{ $farm->farm->name }}'; open = false"
                                                class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                            {{ $farm->farm->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                            <p class="text-sm font-medium text-slate-600">Anda belum memiliki farm.</p>
                            <p class="text-xs text-slate-500">Silakan buat peternakan baru untuk memulai.</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    @if($farms->isNotEmpty())
                        <button type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-emerald-600 px-6 py-3 text-base font-bold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:bg-emerald-700 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Lanjutkan
                            <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @endif

                    <a href="{{ route('farm.create') }}"
                       class="flex w-full items-center justify-center rounded-lg bg-slate-800 px-6 py-3 text-base font-bold text-white shadow-lg shadow-slate-500/20 transition-all duration-300 hover:bg-slate-900 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                             viewBox="0 0 20 20">
                            <path
                                d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                        </svg>
                        Buat Farm Baru
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center text-sm">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="font-medium text-slate-500 hover:text-slate-800 transition">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>
</div>

{{-- Alpine JS --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
