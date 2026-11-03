@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-white/15 bg-slate-900/80 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 rounded-xl shadow-inner text-sm py-2.5 px-3.5 transition']) }}>
