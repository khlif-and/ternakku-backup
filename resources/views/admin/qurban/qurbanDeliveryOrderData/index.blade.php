@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Surat Jalan Qurban ]</p>
        </div>

        <div id="reweight-list-container"
            class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[550px] w-full opacity-0 translate-y-4 transition-all duration-700">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ route('qurban-delivery-order-data.create') }}"
                    class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow text-right transition-all font-sans">
                    tambah Surat Jalan
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
                    <select class="border border-gray-400 rounded-md px-3 py-2 text-base focus:outline-none focus:ring-2 focus:ring-blue-300 w-16">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span class="ml-2 text-base font-medium text-gray-700">Entries</span>
                </div>
                <div class="relative w-full md:w-[250px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </span>
                    <input type="text" id="search-input"
                        class="pl-10 pr-4 py-2 border-2 rounded-xl w-full text-base outline-none transition-all duration-200 search-input-custom"
                        placeholder="Cari Data Disini...." />
                </div>
            </div>

            <div class="overflow-x-auto mt-6 px-8 pb-8">
                <table class="w-full text-center rounded-xl border border-black border-collapse">
                    <thead>
                        <tr>
                            @foreach (['Nomor Transaksi', 'Tanggal Transaksi', 'Foto', 'Jadwal Pengiriman', 'Farm', 'Customer', 'Alamat Customer', 'Detail', 'Aksi'] as $header)
                                <th class="py-4 px-4 border border-black font-medium text-base">
                                    <div class="flex items-center justify-between w-full">
                                        <span>{{ $header }}</span>
                                        @if (!in_array($header, ['Aksi', 'Detail', 'Foto']))
                                            <span class="flex flex-col items-center ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mb-[-2px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M8 14l4-4 4 4" stroke-width="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-[-2px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M16 10l-4 4-4-4" stroke-width="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dummy Data --}}
                        <tr>
                            <td class="py-6 pl-6 border border-black text-left">TRX-001</td>
                            <td class="py-6 pl-6 border border-black text-left">2025-05-21</td>
                            <td class="py-6 pl-6 border border-black text-left">foto.jpg</td>
                            <td class="py-6 pl-6 border border-black text-left">23 Mei 2025</td>
                            <td class="py-6 pl-6 border border-black text-left">Farm A</td>
                            <td class="py-6 pl-6 border border-black text-left">Muhammad Iqbal</td>
                            <td class="py-6 pl-6 border border-black text-left">Jl. Raya No. 88</td>
                            <td class="py-6 pl-6 border border-black text-left">3 ekor sapi, 2 kerbau</td>
                            <td class="py-6 pl-6 border border-black text-left">
                                <div class="flex space-x-2">
                                    <a href="{{ url('qurban-delivery-order-data/1/edit') }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-[#22C55E]/10 hover:bg-[#22C55E]/20 text-[#22C55E] rounded-lg text-sm font-semibold shadow-sm transition-all duration-150 hover:scale-105 group"
                                        style="border:1.5px solid #22C55E;">
                                        <svg class="h-4 w-4 mr-1 group-hover:rotate-6 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M15.232 5.232l3.536 3.536M16.5 3.5a2.121 2.121 0 113 3L7 19.5 3 21l1.5-4L16.5 3.5z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                        onclick="openDeleteDeliveryModal('{{ url('qurban-delivery-order-data/1') }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-[#F87171]/10 hover:bg-[#F87171]/20 text-[#F87171] rounded-lg text-sm font-semibold shadow-sm transition-all duration-150 hover:scale-105 group"
                                        style="border:1.5px solid #F87171;">
                                        <svg class="h-4 w-4 mr-1 group-hover:rotate-[12deg] transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M3 6h18M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2m-7 0v12a2 2 0 002 2h4a2 2 0 002-2V6" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-4">
                    <div class="pl-2 pb-2 md:pb-0">
                        <span class="text-gray-500 text-base">
                            Showing 1 to 1 of 1 entries
                        </span>
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

    <div id="deleteDeliveryModal" class="fixed inset-0 bg-black/40 z-50 hidden transition-all" style="backdrop-filter: blur(2px);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border-b-4 border-red-500 animate-fadeInUp">
                <div class="flex flex-col items-center text-center space-y-5">
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">Hapus Data?</h2>
                    <p class="text-base text-gray-700 mb-2">
                        Apakah kamu yakin ingin menghapusnya?
                    </p>
                </div>
                <form method="POST" class="flex flex-col gap-4 mt-6">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="delete_url" value="">
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeDeleteDeliveryModal()"
                            class="px-6 py-2 rounded-xl font-semibold border border-gray-200 bg-gray-100 text-gray-700 hover:bg-white hover:shadow transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 rounded-xl font-semibold bg-red-500 text-white hover:bg-red-600 shadow transition-all">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
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
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(24px);}
            100% { opacity: 1; transform: translateY(0);}
        }
        .animate-fadeInUp { animation: fadeInUp .22s cubic-bezier(.4,0,.2,1) both; }
    </style>
    <script>
        function openDeleteDeliveryModal(deleteUrl) {
            $('#deleteDeliveryModal').fadeIn(150).css('opacity', 1).css('pointer-events', 'auto');
            $('#deleteDeliveryModal input[name="delete_url"]').val(deleteUrl);
            $('#deleteDeliveryModal form').attr('action', deleteUrl);
        }

        function closeDeleteDeliveryModal() {
            $('#deleteDeliveryModal').fadeOut(150).css('opacity', 0).css('pointer-events', 'none');
        }

        $(document).ready(function() {
            setTimeout(function () {
                $('#reweight-list-container').removeClass('opacity-0 translate-y-4')
                    .addClass('opacity-100 translate-y-0');
            }, 100);
        });
    </script>
@endsection
