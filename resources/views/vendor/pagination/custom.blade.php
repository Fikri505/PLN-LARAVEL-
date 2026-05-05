@if ($paginator->hasPages())
<div class="flex flex-col gap-3 py-3 px-1">

    {{-- ROW 1: Pagination Buttons (tengah) --}}
    <div class="flex items-center justify-center gap-1 flex-wrap">

        {{-- Previous: hanya tampil jika bukan halaman pertama --}}
        @unless ($paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}"
               class="pag-btn pag-nav"
               aria-label="Previous">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endunless

        {{-- Nomor Halaman + Ellipsis --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pag-btn pag-dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pag-btn pag-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pag-btn pag-page">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next: hanya tampil jika bukan halaman terakhir --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="pag-btn pag-nav"
               aria-label="Next">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif

    </div>

    {{-- ROW 2: Summary (kiri) + Per Page Dropdown (kanan) --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">

        {{-- Kiri: Pagination Summary --}}
        <p class="text-xs text-slate-500">
            Results:
            <span class="font-semibold text-slate-700">{{ number_format($paginator->firstItem()) }}</span>
            –
            <span class="font-semibold text-slate-700">{{ number_format($paginator->lastItem()) }}</span>
            of
            <span class="font-semibold text-slate-700">{{ number_format($paginator->total()) }}</span>
        </p>

        {{-- Kanan: Items per page dropdown --}}
        <div class="flex items-center gap-2">
            <label for="perPageSelect" class="text-xs text-slate-500 whitespace-nowrap">
                Items per page:
            </label>
            <select
                id="perPageSelect"
                class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 bg-white text-slate-700
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                       hover:border-slate-300 transition-colors cursor-pointer"
                onchange="changePerPage(this.value)"
            >
                @foreach([10, 25, 50, 100] as $size)
                    <option
                        value="{{ $size }}"
                        {{ request('per_page', 10) == $size ? 'selected' : '' }}
                    >
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

</div>

<style>
    .pag-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 0.45rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid #e2e8f0;
        line-height: 1;
    }
    .pag-nav {
        color: #64748b;
        background: #ffffff;
    }
    .pag-nav:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #334155;
    }
    .pag-page {
        color: #475569;
        background: #ffffff;
    }
    .pag-page:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #2563eb;
    }
    .pag-active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 1px 4px rgba(37, 99, 235, 0.35);
        cursor: default;
    }
    .pag-dots {
        color: #94a3b8;
        background: transparent;
        border-color: transparent;
        cursor: default;
        letter-spacing: 0.05em;
    }
</style>

<script>
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
</script>

@endif