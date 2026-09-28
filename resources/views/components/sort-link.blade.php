@props(['field', 'default' => 'id'])

@php
    $currentSort = request('sort', $default);
    $currentDirection = request('direction', 'desc') === 'asc' ? 'asc' : 'desc';
    $isActive = $currentSort === $field;
    $nextDirection = ($isActive && $currentDirection === 'asc') ? 'desc' : 'asc';
    // Drop the page number so a new sort always starts from page 1.
    $url = request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDirection, 'page' => null]);
@endphp

<a href="{{ $url }}" {{ $attributes->merge(['class' => 'text-dark text-decoration-none fw-bold text-nowrap']) }} title="Sort {{ $nextDirection === 'asc' ? 'ascending' : 'descending' }}">
    {{ $slot }}
    @if ($isActive)
        <span class="text-primary">{{ $currentDirection === 'asc' ? '↑' : '↓' }}</span>
    @else
        <span class="text-muted" style="opacity: .45;">⇅</span>
    @endif
</a>
