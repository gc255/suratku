@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-[#003366] focus:ring-0 rounded-xl shadow-xs text-sm py-2 px-3.5 transition font-semibold']) }}>
