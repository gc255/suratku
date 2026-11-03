@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3.5 py-1.5 text-xs font-bold rounded-lg bg-blue-500/20 text-blue-300 border border-blue-400/30 shadow-xs transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
