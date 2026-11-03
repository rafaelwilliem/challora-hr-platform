@php
    $perPage = request('per_page', 10);
    // All current query params except page — we'll set page explicitly per link
    $queryParams = array_merge(request()->except(['page', 'per_page']), ['per_page' => $perPage]);

    // Helper to build a clean page URL
    $pageUrl = fn(int $p) => request()->fullUrlWithQuery(array_merge($queryParams, ['page' => $p]));
@endphp

@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav class="flex justify-between items-center w-full mt-8 border-t-2 border-border pt-4 flex-wrap gap-4" style="font-family: var(--font-mono);">

        {{-- Left: Showing X rows info + per-page selector --}}
        <div class="flex items-center gap-4">
            <div class="text-xs font-bold uppercase tracking-wider text-accent">
                @if($paginator->total() > 0)
                    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} rows
                @else
                    No rows
                @endif
            </div>

            {{-- Per-page selector --}}
            <form method="GET" action="" id="per-page-form-{{ $paginator->currentPage() }}">
                @foreach($queryParams as $key => $value)
                    @if($key !== 'per_page')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <select
                    name="per_page"
                    class="action-select-premium text-accent font-black text-xs uppercase"
                    onchange="this.form.submit()"
                    style="padding: 6px 12px; box-shadow: 3px 3px 0 black;"
                >
                    @foreach([10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ (int)$perPage === $option ? 'selected' : '' }}>
                            {{ $option }} rows
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if ($paginator->hasPages())
        {{-- Right: Page number buttons --}}
        <ul class="flex gap-2 items-center">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="action-select-premium opacity-40 cursor-not-allowed px-4 py-2 text-accent font-black" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $pageUrl($paginator->currentPage() - 1) }}" rel="prev"
                       class="action-select-premium hover:text-accent px-4 py-2 transition-colors text-accent font-black">&lsaquo;</a>
                </li>
            @endif

            {{-- Page numbers with ellipsis --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage    = $paginator->lastPage();

                // Build the window of page numbers to show
                if ($lastPage <= 6) {
                    // Show all pages when total is small
                    $pages = range(1, $lastPage);
                } else {
                    // Always show a window of 3 around current page
                    $window = collect(range(max(1, $currentPage - 1), min($lastPage, $currentPage + 1)));

                    $pages = collect();

                    // Leading ellipsis
                    if ($window->first() > 2) {
                        $pages->push(1);
                        $pages->push('...');
                    } elseif ($window->first() === 2) {
                        $pages->push(1);
                    }

                    foreach ($window as $p) {
                        $pages->push($p);
                    }

                    // Trailing ellipsis
                    if ($window->last() < $lastPage - 1) {
                        $pages->push('...');
                        $pages->push($lastPage);
                    } elseif ($window->last() === $lastPage - 1) {
                        $pages->push($lastPage);
                    }
                }
            @endphp

            @foreach ($pages as $page)
                @if ($page === '...')
                    <li aria-disabled="true">
                        <span class="px-3 py-2 text-accent font-black cursor-default select-none">…</span>
                    </li>
                @elseif ((int)$page === $currentPage)
                    <li aria-current="page">
                        <span class="action-select-premium bg-accent text-white px-4 py-2 border-accent shadow-[4px_4px_0_var(--color-accent)] cursor-default font-black">{{ $page }}</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $pageUrl((int)$page) }}"
                           class="action-select-premium text-accent hover:bg-accent hover:text-white px-4 py-2 transition-colors font-black">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $pageUrl($paginator->currentPage() + 1) }}" rel="next"
                       class="action-select-premium hover:text-accent px-4 py-2 transition-colors text-accent font-black">&rsaquo;</a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="action-select-premium opacity-40 cursor-not-allowed px-4 py-2 text-accent font-black" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
        @endif

    </nav>
@endif
