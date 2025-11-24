@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === config('app.name'))
<span style="color: #ffffff; font-size: 24px; font-weight: bold; font-family: 'Montserrat', sans-serif;">
    {{ config('app.name') }}
</span>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
