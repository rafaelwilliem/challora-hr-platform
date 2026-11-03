@php
    $perPage = request('per_page', 10);
    // All current query params except page — we'll set page explicitly per link
    $queryParams = array_merge(request()->except(['page', 'per_page']), ['per_page' => $perPage]);

    // Helper to build a clean page URL
    $pageUrl = fn(int $p) => request()->fullUrlWithQuery(array_merge($queryParams, ['page' => $p]));
@endphp

@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav class="flex justify-between items-center w-full mt-8 border-t border-[#1d1d1d] pt-5 flex-wrap gap-4" style="font-family: var(--font-mono);">

        {{-- Left: Showing X rows info + per-page selector --}}
        <div class="flex items-center gap-4">
            <div class="text-[11px] font-bold uppercase tracking-wider text-[#888]">
                @if($paginator->total() > 0)
                    Showing <span class="text-white">{{ $paginator->firstItem() }}</span> to <span class="text-white">{{ $paginator->lastItem() }}</span> of <span class="text-white">{{ $paginator->total() }}</span> rows
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
                    class="bg-[#090909] border border-[#262626] text-[#E5E5DF] font-bold text-[11px] uppercase px-3 py-1.5 cursor-pointer hover:border-[#FF4D30]"
                    onchange="this.form.submit()"
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
        <ul class="flex gap-1.5 items-center">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="border border-[#1d1d1d] bg-[#090909] opacity-40 cursor-not-allowed px-3.5 py-1.5 text-gray-500 font-bold text-xs" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $pageUrl($paginator->currentPage() - 1) }}" rel="prev"
                       class="border border-[#262626] bg-[#090909] hover:border-[#FF4D30] hover:text-white px-3.5 py-1.5 transition-colors text-gray-300 font-bold text-xs">&lsaquo;</a>
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
                        <span class="px-2 py-1.5 text-gray-500 font-bold text-xs cursor-default select-none">…</span>
                    </li>
                @elseif ((int)$page === $currentPage)
                    <li aria-current="page">
                        <span class="bg-[#FF4D30] text-white px-3.5 py-1.5 border border-[#FF4D30] cursor-default font-bold text-xs">{{ $page }}</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $pageUrl((int)$page) }}"
                           class="border border-[#262626] bg-[#090909] text-gray-300 hover:border-[#FF4D30] hover:text-white px-3.5 py-1.5 transition-colors font-bold text-xs">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $pageUrl($paginator->currentPage() + 1) }}" rel="next"
                       class="border border-[#262626] bg-[#090909] hover:border-[#FF4D30] hover:text-white px-3.5 py-1.5 transition-colors text-gray-300 font-bold text-xs">&rsaquo;</a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="border border-[#1d1d1d] bg-[#090909] opacity-40 cursor-not-allowed px-3.5 py-1.5 text-gray-500 font-bold text-xs" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
        @endif

    </nav>
@endif
