@props([
    'editRoute' => null,
    'showRoute' => null,
    'deleteRoute' => null,
    'editId' => null,
    'showId' => null,
    'deleteId' => null,
    'size' => 'sm',
    'deleteConfirm' => 'Are you sure you want to delete this item?'
])

<div class="btn-group" role="group">
    @if($showRoute && $showId)
        <a href="{{ route($showRoute, $showId) }}" class="btn btn-{{ $size }} btn-outline-primary">
            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
        </a>
    @endif
    
    @if($editRoute && $editId)
        <a href="{{ route($editRoute, $editId) }}" class="btn btn-{{ $size }} btn-outline-secondary">
            <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
        </a>
    @endif
    
    @if($deleteRoute && $deleteId)
        <form action="{{ route($deleteRoute, $deleteId) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $deleteConfirm }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-{{ $size }} btn-outline-danger">
                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
            </button>
        </form>
    @endif
</div>
