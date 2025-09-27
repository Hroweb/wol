<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-40 inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 py-3.5 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600']) }}>
    {{ $slot }}
</button>
