@props(['label', 'value', 'color' => 'muted', 'icon' => null])

@php
$colorClass = match($color) {
    'green'  => 'kpi-green',
    'blue'   => 'kpi-blue',
    'indigo' => 'kpi-blue',
    'yellow' => 'kpi-amber',
    'red'    => 'kpi-red',
    default  => 'kpi-muted',
};
$icons = [
    'pharmacies' => '<path d="M12 13a3 3 0 100-6 3 3 0 000 6z"/><path d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155z"/>',
    'commandes'  => '<path d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm.75-3H7.5"/>',
    'retard'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
    'prospect'   => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/>',
    'moyenne'    => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
    'mois'       => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
];
$iconPath = $icon && isset($icons[$icon]) ? $icons[$icon] : null;
@endphp

<div class="kpi-card {{ $colorClass }}">
    @if($iconPath)
    <div style="position:absolute; top:16px; right:16px; opacity:0.1;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $iconPath !!}</svg>
    </div>
    @endif
    <div class="kpi-label">{{ $label }}</div>
    <div class="kpi-value">{{ $value }}</div>
</div>
