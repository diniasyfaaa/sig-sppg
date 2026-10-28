@if ($paginator->hasPages())
    <nav class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-2" role="navigation" aria-label="Navigasi Halaman">
        @if (method_exists($paginator, 'total'))
            <div class="text-muted small">
                Menampilkan 
                <span class="text-dark fw-medium">{{ $paginator->firstItem() }}</span>
                -
                <span class="text-dark fw-medium">{{ $paginator->lastItem() }}</span>
                dari
                <span class="text-dark fw-medium">{{ $paginator->total() }}</span>
                data
            </div>
        @else
            <div></div>
        @endif

        <ul class="pagination pagination-sm mb-0 align-items-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                    <span class="page-link text-muted px-3" aria-hidden="true">
                        <i class="bi bi-chevron-left me-1"></i> Sebelumnya
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link px-3" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left me-1"></i> Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
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

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link px-3" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Selanjutnya">
                        Selanjutnya <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="Selanjutnya">
                    <span class="page-link text-muted px-3" aria-hidden="true">
                        Selanjutnya <i class="bi bi-chevron-right ms-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
