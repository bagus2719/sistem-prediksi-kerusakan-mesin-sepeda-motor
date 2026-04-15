@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-rose-600 space-y-1 bg-rose-50 px-4 py-2 rounded-lg border border-rose-100']) }}>
        @foreach ((array) $messages as $message)
            <li class="font-medium">{{ $message }}</li>
        @endforeach
    </ul>
@endif
