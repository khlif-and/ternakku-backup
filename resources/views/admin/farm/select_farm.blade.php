@extends('layouts.auth.index')

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 pt-10">
        <h2 class="text-xl sm:text-2xl font-bold text-center mb-6">Pilih Farm terlebih dahulu</h2>
        <div
            class="w-full max-w-lg bg-white rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.10)] px-7 py-10 flex flex-col items-center">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 border border-green-200 text-sm">
                    {{ session('success') }}
                </div>
            @endif


            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="mb-4 flex justify-center">
                        <span
                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium shadow">{{ $error }}</span>
                    </div>
                @endforeach
            @endif

            <form action="{{ url('select-farm') }}" method="POST" class="w-full flex flex-col items-center">
                @csrf


                <div class="w-full mb-8">
                    <select id="farm_id" name="farm_id" required
                        class="block w-full rounded-xl border-0 bg-gray-100 py-6 px-6 text-gray-900 text-lg shadow-lg font-semibold focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition
                    @error('username') border-red-400 @enderror">
                        <option value="" selected disabled>Pilih Farm</option>
                        @foreach ($farms as $farm)
                            <option value="{{ $farm->farm_id }}">{{ $farm->farm->name }}</option>
                        @endforeach
                    </select>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="redirect_url" value="{{ request()->query('redirect_url') }}">

                <button type="submit"
                    class="w-full py-3 rounded-full border-2 border-black text-black font-semibold text-lg mb-4 transition-all hover:bg-gray-100 focus:outline-none">
                    pilih farm
                </button>
            </form>

            <div class="text-center font-semibold mb-2">
                belum punya farm?
            </div>
            <a href="{{ route('farm.create') }}"
                class="w-full block py-3 rounded-full bg-[#6CCF8A] hover:bg-[#5ec678] text-white text-lg font-semibold text-center shadow transition">
                pilih farm
            </a>
        </div>
    </div>
@endsection
