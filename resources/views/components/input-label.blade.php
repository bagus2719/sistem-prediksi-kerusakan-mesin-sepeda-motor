@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-slate-700 tracking-wide']) }}>
    {{ $value ?? $slot }}
</label>
