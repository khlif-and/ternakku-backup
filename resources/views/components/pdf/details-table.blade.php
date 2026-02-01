@props(['title' => '', 'data' => []])

@if(!empty($title))
    <div style="margin-bottom: 10px;">{{ $title }}</div>
@endif

<div class="details-box">
    <table class="details-table">
        @foreach($data as $label => $value)
            <tr>
                <td class="label-cell">{{ $label }}</td>
                <td class="separator-cell">:</td>
                <td class="value-cell">{{ $value }}</td>
            </tr>
        @endforeach
    </table>
</div>