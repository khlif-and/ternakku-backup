@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Pengguna ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>
                    <a href="{{ url('qurban/farm/user-list') }}" class="text-blue-600 font-semibold">
                        Data Pengguna
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[550px] w-full">
            <div class="bg-gray-300 w-full flex items-center justify-end px-8 h-[90px]">
                <a href="#"
                    class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow text-right transition-all font-sans">
                    tambah data Pengguna
                </a>
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between px-8 py-6 gap-4">
                <div class="flex items-center flex-shrink-0">
                    <span class="mr-2 text-base font-medium text-gray-700">Show</span>
                    <select
                        class="border border-gray-400 rounded-md px-3 py-2 text-base focus:outline-none focus:ring-2 focus:ring-blue-300 w-16">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span class="ml-2 text-base font-medium text-gray-700">Entries</span>
                </div>
                <div class="relative w-full md:w-[250px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"
                                fill="none" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor"
                                stroke-width="2" />
                        </svg>
                    </span>
                    <input type="text"
                        class="pl-10 pr-4 py-2 border-2 border-black rounded-xl w-full text-base outline-none"
                        placeholder="Cari Data Disini...." />
                </div>
            </div>
            <div class="overflow-x-auto mt-6 px-8 pb-8">
                <table class="w-full text-center rounded-xl border border-black border-collapse">
                    <thead>
                        <tr>
                            @foreach (['Nama', 'Email', 'No HP', 'Role', 'Aksi'] as $header)
                                <th class="py-4 px-4 border border-black font-medium text-base">
                                    <div class="flex items-center justify-between w-full">
                                        <span>{{ $header }}</span>
                                        <span class="flex flex-col items-center ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mb-[-2px]" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M8 14l4-4 4 4" stroke-width="2" stroke="currentColor"
                                                    stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-[-2px]" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M16 10l-4 4-4-4" stroke-width="2" stroke="currentColor"
                                                    stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                            </svg>
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="py-6 pl-6 border border-black text-left">{{ $user->user->name }}</td>
                                <td class="py-6 pl-6 border border-black text-left">{{ $user->user->email }}</td>
                                <td class="py-6 pl-6 border border-black text-left">{{ $user->user->phone_number }}</td>
                                <td class="py-6 pl-6 border border-black text-left">{{ $user->farm_role }}</td>
                                <td class="py-6 pl-6 border border-black text-left"></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 border border-black text-center text-gray-500">Tidak ada data
                                    pengguna</td>
                            </tr>
                        @endforelse
                    </tbody>



                    <thead>
                        <tr>
                            @foreach (['Nama', 'Email', 'No HP', 'Role', 'Aksi'] as $header)
                                <th class="py-4 px-4 border border-black font-medium text-base">
                                    <div class="flex items-center justify-between w-full">
                                        <span>{{ $header }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                </table>


                <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-4">

                    <div class="pl-2 pb-2 md:pb-0">
                        <span class="text-gray-500 text-base">
                            Showing <span class="font-medium text-gray-700">1</span> to <span
                                class="font-medium text-gray-700">2</span> of <span
                                class="font-medium text-gray-700">2</span> entries
                        </span>
                    </div>

                    <div class="flex justify-start md:justify-end">
                        <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#"
                                class="px-4 py-2 border border-gray-300 text-gray-500 bg-white hover:bg-gray-100 text-base rounded-l-md transition">Previous</a>
                            <a href="#"
                                class="px-4 py-2 border-t border-b border-gray-300 text-white bg-blue-500 font-medium text-base transition">1</a>
                            <a href="#"
                                class="px-4 py-2 border border-gray-300 text-gray-500 bg-white hover:bg-gray-100 text-base rounded-r-md transition">Next</a>
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    @endsection
