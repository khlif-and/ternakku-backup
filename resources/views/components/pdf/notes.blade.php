@props(['content' => []])

<div class="notes">
    Catatan :
    <ol>
        @foreach($content as $note)
            <li>{!! $note !!}</li>
        @endforeach
    </ol>
</div>

<div style="margin-top: 10px;">Demikian surat keterangan ini diberikan untuk dipergunakan sebagaimana perlunya.
</div>