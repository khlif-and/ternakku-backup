@props(['location', 'date', 'signerName'])

<table class="signatures">
    <tr>
        <td></td>
        <td></td>
        <td>
            {{ $location ?? 'Tempat' }}, {{ $date }}
            <br>Kepala Unit / Admin
            <div class="sign-space"></div>
            <div class="sign-name">( {{ $signerName }} )</div>
        </td>
    </tr>
</table>