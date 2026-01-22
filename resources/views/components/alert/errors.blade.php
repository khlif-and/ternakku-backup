<div class="mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800">
    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
    <ul class="list-disc list-inside text-sm space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
