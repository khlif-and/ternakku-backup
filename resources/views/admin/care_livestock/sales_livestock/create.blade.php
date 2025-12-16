@extends('layouts.care_livestock.index')

@section('title', 'Tambah Sales Livestock')

@section('content')
    <form action="{{ route('admin.care-livestock.sales-livestock.store', $farm->id) }}" method="POST" class="space-y-6">
        @csrf

        {{-- Livestock --}}
        <div>
            <label for="livestock_id" class="block text-sm font-medium text-gray-700">Pilih Ternak</label>
            <select name="livestock_id" id="livestock_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                required>
                <option value="">-- Pilih Ternak --</option>

                @foreach ($availableLivestock as $livestock)
                    <option value="{{ $livestock->id }}">
                        {{ $livestock->code }} - {{ $livestock->gender }} - {{ $livestock->weight }} kg
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Harga --}}
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="price" id="price"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                required>
        </div>

        {{-- Notes --}}
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="notes" id="notes"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                rows="3"></textarea>
        </div>

        {{-- Submit --}}
        <div class="pt-4 border-t border-gray-200 flex justify-end">
            <a href="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}"
                class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Simpan
            </button>
        </div>
    </form>
@endsection
