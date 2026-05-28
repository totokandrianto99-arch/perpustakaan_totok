@if ($paginator->hasPages())
<nav class="pagination">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></span>
    @else
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-item disabled"><span class="page-link">{{ $element }}</span></span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                @else
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
    @else
        <span class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></span>
    @endif
</nav>
@endif
