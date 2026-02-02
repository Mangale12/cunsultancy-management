@props([
    'headers' => [],
    'data' => [],
    'actions' => false,
    'emptyMessage' => 'No records found.',
    'responsive' => true
])

<div class="@if($responsive) table-responsive @endif">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                @foreach($headers as $header)
                    <th {{ $header['attributes'] ?? '' }}>
                        {{ $header['title'] }}
                    </th>
                @endforeach
                @if($actions)
                    <th class="text-center">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    @foreach($headers as $key => $header)
                        <td {{ $header['cellAttributes'] ?? '' }}>
                            {{ $header['value']($item) }}
                        </td>
                    @endforeach
                    @if($actions)
                        <td class="text-center">
                            {{ $actions($item) }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-4">
                        <div class="text-muted">
                            <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                            <p class="mt-2 mb-0">{{ $emptyMessage }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
