@props([
    'headers' => [],
    'empty' => 'Belum ada data.',
    'colspan' => null,
])

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                @foreach($headers as $header)
                    <th class="px-4 py-3 text-left font-semibold {{ $header['class'] ?? '' }}">{{ $header['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
        @if(isset($footer))
            <tfoot class="bg-gray-50 border-t border-gray-100">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>
