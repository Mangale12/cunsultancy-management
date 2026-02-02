@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'primary',
    'trend' => null,
    'trendValue' => null
])

@php
    $colorClasses = [
        'primary' => 'bg-primary text-white',
        'success' => 'bg-success text-white',
        'warning' => 'bg-warning text-dark',
        'danger' => 'bg-danger text-white',
        'info' => 'bg-info text-white',
        'secondary' => 'bg-secondary text-white'
    ];
    
    $iconBgClasses = [
        'primary' => 'bg-primary bg-opacity-10 text-primary',
        'success' => 'bg-success bg-opacity-10 text-success',
        'warning' => 'bg-warning bg-opacity-10 text-warning',
        'danger' => 'bg-danger bg-opacity-10 text-danger',
        'info' => 'bg-info bg-opacity-10 text-info',
        'secondary' => 'bg-secondary bg-opacity-10 text-secondary'
    ];
    
    $cardClass = $colorClasses[$color] ?? $colorClasses['primary'];
    $iconClass = $iconBgClasses[$color] ?? $iconBgClasses['primary'];
@endphp

<div class="card {{ $cardClass }} border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="card-title mb-2 opacity-75">{{ $title }}</h6>
                <h2 class="mb-0 fw-bold">{{ $value }}</h2>
                @if($trend && $trendValue)
                    <small class="opacity-75">
                        <i data-feather="{{ $trend === 'up' ? 'trending-up' : 'trending-down' }}" style="width: 12px; height: 12px;"></i>
                        {{ $trendValue }}
                    </small>
                @endif
            </div>
            @if($icon)
                <div class="rounded-circle p-3 {{ $iconClass }}">
                    <i data-feather="{{ $icon }}" style="width: 24px; height: 24px;"></i>
                </div>
            @endif
        </div>
    </div>
</div>
