<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-lg border border-emerald-100 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-emerald-800 shadow-sm transition duration-150 ease-in-out hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
