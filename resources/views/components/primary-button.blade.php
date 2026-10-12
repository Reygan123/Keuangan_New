<button {{ $attributes->merge(['type' => 'submit', 'class' => 'apple-button apple-button-primary inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-semibold text-xs text-white tracking-wider active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all duration-150']) }}>
    {{ $slot }}
</button>
