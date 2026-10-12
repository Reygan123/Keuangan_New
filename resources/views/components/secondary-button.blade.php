<button {{ $attributes->merge(['type' => 'button', 'class' => 'apple-button apple-button-secondary inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-semibold text-xs text-slate-700 tracking-wider shadow-xs border border-slate-200/80 active:scale-95 focus:outline-none focus:ring-2 focus:ring-slate-400/20 disabled:opacity-30 transition-all duration-150']) }}>
    {{ $slot }}
</button>
