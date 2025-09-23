{{-- Custom Pagination Component --}}
@if($paginator->hasPages())
    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0 small">
                        Menampilkan {{ $paginator->firstItem() }} sampai {{ $paginator->lastItem() }} 
                        dari {{ $paginator->total() }} hasil
                    </p>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Pagination Navigation" class="d-flex justify-content-end">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @if($paginator->lastPage() > 10)
                                {{-- First Page --}}
                                @if($paginator->currentPage() > 5)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                                    </li>
                                    @if($paginator->currentPage() > 6)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- Middle Pages --}}
                                @for($i = max(1, $paginator->currentPage() - 2); $i <= min($paginator->lastPage(), $paginator->currentPage() + 2); $i++)
                                    @if ($i == $paginator->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                {{-- Last Page --}}
                                @if($paginator->currentPage() < $paginator->lastPage() - 4)
                                    @if($paginator->currentPage() < $paginator->lastPage() - 5)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                                    </li>
                                @endif
                            @else
                                {{-- Show all pages if less than 10 --}}
                                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif

                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination-sm .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            margin: 0 0.125rem;
            border: 1px solid #dee2e6;
            color: #6c757d;
            transition: all 0.2s ease-in-out;
        }
        
        .pagination-sm .page-link:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
            color: #495057;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .pagination-sm .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }
        
        .pagination-sm .page-item.disabled .page-link {
            background-color: transparent;
            border-color: #dee2e6;
            color: #6c757d;
            opacity: 0.5;
        }
    </style>
@endif