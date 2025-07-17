@extends('layouts.care_livestock.index')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Kandang ]</p>
    </div>

    <div id="pen-list-container"
        class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[550px] w-full opacity-0 translate-y-4 transition-all duration-700">
        <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
            <a href="{{ route('admin.care-livestock.pens.create', $farm->id) }}"
                class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow text-right transition-all font-sans">
                Tambah Data Kandang
            </a>
        </div>

        @if (session('success'))
            <div class="px-8 py-4">
                <div class="mb-4 px-4 py-3 rounded bg-green-100 border border-green-400 text-green-700 font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-8 py-6 gap-4">
            <div class="flex items-center flex-shrink-0">
                <span class="mr-2 text-base font-medium text-gray-700">Show</span>
                <select class="border border-gray-400 rounded-md px-3 py-2 text-base focus:outline-none w-16">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <span class="ml-2 text-base font-medium text-gray-700">Entries</span>
            </div>
            <div class="relative w-full md:w-[250px]">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" />
                    </svg>
                </span>
                <input type="text" id="search-input"
                    class="pl-10 pr-4 py-2 border-2 rounded-xl w-full text-base outline-none search-input-custom"
                    placeholder="Cari Kandang..." />
            </div>
        </div>

        <div class="overflow-x-auto mt-6 px-8 pb-8">
            <table class="w-full text-center rounded-xl border border-black border-collapse">
                <thead>
                    <tr>
                        @foreach (['No', 'Nama Kandang', 'Image', 'Populasi', 'Kapasitas', 'Aksi'] as $header)
                            <th class="py-4 px-4 border border-black font-medium text-base">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($pens as $index => $pen)
                        <tr>
                            <td class="py-4 px-4 border border-black">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 border border-black text-left">{{ $pen->name }}</td>
                            <td class="py-4 px-4 border border-black">
                                @if ($pen->photo)
                                    <img src="{{ $pen->photo }}" alt="{{ $pen->name }}" class="w-20 h-20 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 border border-black">{{ $pen->population ?? 0 }}</td>
                            <td class="py-4 px-4 border border-black">{{ $pen->capacity ?? '-' }}</td>
                            <td class="py-4 px-4 border border-black">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.care-livestock.pens.edit', [$farm->id, $pen->id]) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-[#22C55E]/10 hover:bg-[#22C55E]/20 text-[#22C55E] rounded-lg text-sm font-semibold shadow-sm transition-all duration-150 hover:scale-105 group"
                                       style="border:1.5px solid #22C55E;">
                                        <svg class="h-4 w-4 mr-1 group-hover:rotate-6 transition-all" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M15.232 5.232l3.536 3.536M16.5 3.5a2.121 2.121 0 113 3L7 19.5 3 21l1.5-4L16.5 3.5z" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.care-livestock.pens.destroy', [$farm->id, $pen->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kandang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-[#F87171]/10 hover:bg-[#F87171]/20 text-[#F87171] rounded-lg text-sm font-semibold shadow-sm transition-all duration-150 hover:scale-105 group"
                                            style="border:1.5px solid #F87171;">
                                            <svg class="h-4 w-4 mr-1 group-hover:rotate-[12deg] transition-all" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 6h18M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2m-7 0v12a2 2 0 002 2h4a2 2 0 002-2V6" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-gray-500 text-center border border-black">
                                Belum ada data kandang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-4">
                <div class="pl-2 pb-2 md:pb-0">
                    <span class="text-gray-500 text-base">Showing 1 to {{ $pens->count() }} of {{ $pens->count() }} entries</span>
                </div>
                <div class="flex justify-start md:justify-end">
                    <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                        <a href="#" class="px-4 py-2 border border-gray-300 text-gray-500 bg-white hover:bg-gray-100 text-base rounded-l-md transition">Previous</a>
                        <a href="#" class="px-4 py-2 border-t border-b border-gray-300 text-white bg-blue-500 font-medium text-base transition">1</a>
                        <a href="#" class="px-4 py-2 border border-gray-300 text-gray-500 bg-white hover:bg-gray-100 text-base rounded-r-md transition">Next</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<style>
    .search-input-custom {
        border-color: rgba(0, 0, 0, 0.24) !important;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input-custom:focus {
        border-color: #28c76f !important;
        box-shadow: 0 0 0 2px #28c76f44;
    }
</style>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#pen-list-container').removeClass('opacity-0 translate-y-4')
                .addClass('opacity-100 translate-y-0');
        }, 100);
    });
</script>
@endsection
