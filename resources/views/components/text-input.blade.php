@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-slate-700 text-base bg-white placeholder-slate-400 transition-colors w-full px-4 py-3']) !!}>
