<button {{ $attributes->merge(['type' => 'submit', 'class' => 'apple-button inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-semibold text-xs tracking-wider rounded-xl shadow-xs active:scale-95 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all duration-150']) }}>
    {{ $slot }}
</button>
