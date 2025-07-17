{{-- resources/views/admin/qurban/sales-livestock/edit.blade.php --}}

@extends('layouts.qurban.form')

@section('title', 'Edit Sales Livestock')

@section('form')
    <form action="{{ route('admin.sales-livestock.update', [$farm->id, $salesLivestock->id]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Livestock (disabled) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Ternak</label>
            <input type="text"
                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm"
                   value="{{ $salesLivestock->livestock->code }} - {{ $salesLivestock->livestock->gender }} - {{ $salesLivestock->livestock->weight }} kg"
                   disabled>
        </div>

        {{-- Harga --}}
        <div>
            <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="harga" id="harga"
                   value="{{ old('harga', $salesLivestock->harga) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                   required>
        </div>

        {{-- Diskon --}}
        <div>
            <label for="diskon" class="block text-sm font-medium text-gray-700">Diskon (%)</label>
            <input type="number" name="diskon" id="diskon"
                   value="{{ old('diskon', $salesLivestock->diskon) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                   min="0" max="100">
        </div>

        {{-- Submit --}}
        <div class="pt-4 border-t border-gray-200 flex justify-end">
            <a href="{{ route('admin.sales-livestock.index', $farm->id) }}"
               class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Update
            </button>
        </div>
    </form>
@endsection
