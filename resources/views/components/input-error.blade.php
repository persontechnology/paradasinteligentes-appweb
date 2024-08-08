@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger']) }}>
        @foreach ((array) $messages as $message)
            <li><strong>{{ $message }}</strong></li>
        @endforeach
    </ul>
@endif
