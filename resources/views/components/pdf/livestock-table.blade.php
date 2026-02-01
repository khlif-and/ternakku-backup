@props(['items'])

<table class="data-table">
    <thead>
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 35%;">Jenis Hewan</th>
            <th style="width: 15%;">Jumlah</th>
            <th style="width: 20%;">Jenis Kelamin</th>
            <th style="width: 25%;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{ $item->livestock?->livestockType?->name ?? '-' }} -
                    {{ $item->livestock?->livestockBreed?->name ?? '-' }}
                    <br>
                    <small>(Tag: {{ $item->livestock?->eartag_number ?? '-' }})</small>
                </td>
                <td class="text-center">1 Ekor</td>
                <td class="text-center">{{ $item->livestock?->livestockSex?->name ?? '-' }}</td>
                <td>{{ $item->livestock?->qurbanSaleLivestockD?->qurbanCustomerAddress?->remark ?? '-' }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: right; font-weight: bold;">JUMLAH</td>
            <td class="text-center" style="font-weight: bold;">{{ $items->count() }} ekor</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>