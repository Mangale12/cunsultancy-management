@props([
    'title' => null,
    'headerClass' => 'bg-white border-bottom',
    'bodyClass' => '',
    'footer' => null,
    'shadow' => 'shadow-sm',
    'border' => 'border-0'
])

<div class="card {{ $shadow }} {{ $border }}">
    @if($title)
        <div class="card-header {{ $headerClass }} py-3">
            <h5 class="mb-0">{{ $title }}</h5>
        </div>
    @endif
    
    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer bg-white border-top">
            {{ $footer }}
        </div>
    @endif
</div>
