@props(['name', 'destination', 'policeNumber', 'date'])

<div style="margin-bottom: 10px;">Diberikan kepada :</div>
<div class="details-box">
    <table class="details-table">
        <tr>
            <td class="label-cell">Nama</td>
            <td class="separator-cell">:</td>
            <td class="value-cell">{{ $name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Tujuan</td>
            <td class="separator-cell">:</td>
            <td class="value-cell">{{ $destination ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-cell">No. Polisi</td>
            <td class="separator-cell">:</td>
            <td class="value-cell">{{ $policeNumber ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Pada Hari / Tanggal</td>
            <td class="separator-cell">:</td>
            <td class="value-cell">{{ $date ?? '-' }}</td>
        </tr>
    </table>
</div>