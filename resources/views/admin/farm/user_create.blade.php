<div class="bg-white shadow-md rounded-lg mt-8 p-6">
    <div class="flex items-center justify-between mb-4">
        <h4 class="font-semibold text-lg">Data Pengguna</h4>
        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Data</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">No HP</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Role</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->phone_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->role }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
