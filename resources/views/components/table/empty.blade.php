@props([
    'empty' => 'Belum ada data.',
    'colspan' => 1,
])

<tr>
    <td colspan="{{ $colspan }}" class="px-4 py-8 text-center text-gray-500">
        {{ $empty }}
    </td>
</tr>
