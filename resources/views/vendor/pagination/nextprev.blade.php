@if ($paginator->hasPages())
    <div class="pagination-container text-center mb-4">
        {{-- Informasi Halaman --}}
        <div class="page-info mb-2">
            <span class="text-muted">
                Halaman <strong>{{ $paginator->currentPage() }}</strong> dari <strong>{{ $paginator->lastPage() }}</strong>
            </span>
        </div>

        {{-- Tombol Pagination --}}
        <ul class="pagination justify-content-center">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link rounded-pill bg-secondary text-white">
                        <i class="fa fa-arrow-left me-1"></i> Sebelumnya
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link rounded-pill bg-primary text-white">
                        <i class="fa fa-arrow-left me-1"></i> Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link rounded-pill bg-primary text-white">
                        Berikutnya <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link rounded-pill bg-secondary text-white">
                        Berikutnya <i class="fa fa-arrow-right ms-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </div>
@endif
