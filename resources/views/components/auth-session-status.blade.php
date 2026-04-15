@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-sm text-emerald-600 bg-emerald-50 px-4 py-3 rounded-xl border border-emerald-100 shadow-sm shadow-emerald-500/10']) }}>
        {{ $status }}
    </div>
@endif
