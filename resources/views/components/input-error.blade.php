@props(["messages" => []])

@if($messages)
    <ul {{ $attributes->merge(['class' => "mt-2 text-xs text-red-600 space-y-1"]) }}>
        @foreach ($messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
