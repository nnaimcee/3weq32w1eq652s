<button {{ $attributes->merge(['type' => 'submit']) }}
    style="{{ !$attributes->has('style') ? 'background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);' : '' }}"
    class="{{ $attributes->get('class', '') ?: 'inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl font-semibold text-sm text-white shadow-md hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150' }}">
    {{ $slot }}
</button>
