@if ($paginator->hasPages())

    @if ($paginator->onFirstPage())
        <a class="btn btn-primary btn-sm pagination-previous" disabled><i class="fa fa-chevron-left font-color-white"></i></a>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-primary btn-sm pagination-previous"><i class="fa fa-chevron-left font-color-white"></i></a>
    @endif

    @if ($paginator->hasMorePages())
        <a class="btn btn-primary btn-sm pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa fa-chevron-right font-color-white"></i></a>
    @else
        <a class="btn btn-primary btn-sm pagination-next" disabled><i class="fa fa-chevron-right font-color-white"></i></a>
    @endif
@endif