@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'apple-input border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl shadow-2xs text-xs sm:text-sm font-medium transition-all']) }}>
