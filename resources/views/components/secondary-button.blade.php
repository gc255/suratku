<button {{ $attributes->merge(['type' => 'button', 'class' => 'glass-pill inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-bold text-xs text-slate-300 hover:text-white hover:bg-white/10 border border-white/15 transition cursor-pointer uppercase tracking-wider disabled:opacity-25']) }}>
    {{ $slot }}
</button>
