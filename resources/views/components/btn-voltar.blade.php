@props(['route' => null])

<a href="{{ $route ?? 'javascript:history.back()' }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium transition-colors mb-4']) }}>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M19 12H5"/>
        <path d="M12 19l-7-7 7-7"/>
    </svg>
    <span>{{ $slot->isEmpty() ? 'Voltar' : $slot }}</span>
</a>
