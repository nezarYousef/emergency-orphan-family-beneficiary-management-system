@if ($paginator->hasPages())
    @php($rtl = app()->getLocale() === 'ar')
    <nav class="d-flex justify-items-center justify-content-between" aria-label="{{ __('pagination.navigation') }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link"><span aria-hidden="true" dir="ltr">{{ $rtl ? '›' : '‹' }}</span> {{ __('pagination.previous') }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><span aria-hidden="true" dir="ltr">{{ $rtl ? '›' : '‹' }}</span> {{ __('pagination.previous') }}</a>
                    </li>
                @endif

                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('pagination.next') }} <span aria-hidden="true" dir="ltr">{{ $rtl ? '‹' : '›' }}</span></a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ __('pagination.next') }} <span aria-hidden="true" dir="ltr">{{ $rtl ? '‹' : '›' }}</span></span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div class="small text-muted">
                {{ __('pagination.showing') }}
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                {{ __('pagination.to') }}
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                {{ __('pagination.of') }}
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                {{ __('pagination.results') }}
            </div>

            <div>
                <ul class="pagination">
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="page-link" aria-hidden="true" dir="ltr">{{ $rtl ? '›' : '‹' }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"><span aria-hidden="true" dir="ltr">{{ $rtl ? '›' : '‹' }}</span></a>
                        </li>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"><span aria-hidden="true" dir="ltr">{{ $rtl ? '‹' : '›' }}</span></a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="page-link" aria-hidden="true" dir="ltr">{{ $rtl ? '‹' : '›' }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
