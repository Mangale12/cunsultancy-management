@props([
    'title',
    'subtitle' => null,
    'actions' => null,
    'breadcrumb' => null
])

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        @if($breadcrumb)
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb breadcrumb-sm">
                    {{ $breadcrumb }}
                </ol>
            </nav>
        @endif
        
        <h1 class="h2 mb-0">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-muted mb-0 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if($actions)
        <div class="btn-toolbar mb-2 mb-md-0">
            {{ $actions }}
        </div>
    @endif
</div>
