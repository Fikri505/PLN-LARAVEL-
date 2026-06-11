{{-- Pagination Footer: tombol tengah + results kiri + perPage kanan --}}
<div class="card-body border-t border-slate-100">

    {{-- Tombol halaman (tengah) --}}
    @if($paginator->hasPages())
    <div class="flex justify-center mb-3">
        <div class="flex items-center gap-1 flex-wrap">
            {{-- Prev --}}
            @if($paginator->onFirstPage())
                <span class="inline-flex items-center px-2 py-1 text-xs text-slate-300 border border-slate-200 rounded select-none">&lt;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex items-center px-2 py-1 text-xs text-slate-600 border border-slate-200 rounded hover:bg-slate-100 transition">&lt;</a>
            @endif

            {{-- Nomor halaman --}}
            @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-white bg-pln-blue rounded select-none">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="inline-flex items-center px-3 py-1 text-xs text-slate-600 border border-slate-200 rounded hover:bg-slate-100 transition">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex items-center px-2 py-1 text-xs text-slate-600 border border-slate-200 rounded hover:bg-slate-100 transition">&gt;</a>
            @else
                <span class="inline-flex items-center px-2 py-1 text-xs text-slate-300 border border-slate-200 rounded select-none">&gt;</span>
            @endif
        </div>
    </div>
    @endif

    {{-- Results info (kiri) + Items per page (kanan) — satu baris --}}
    <div class="flex items-center justify-between gap-4">
        <p class="text-xs text-slate-500 whitespace-nowrap">
            Results: <strong>{{ $paginator->firstItem() ?? 0 }}</strong> – <strong>{{ $paginator->lastItem() ?? 0 }}</strong> of <strong>{{ $paginator->total() }}</strong>
        </p>
        <form method="GET" action="{{ $routeUrl }}" class="flex items-center gap-1.5">
            @foreach($queryParams as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <label class="text-xs text-slate-500 whitespace-nowrap">Items per page:</label>
            <select name="perPage"
                    class="text-xs text-slate-700 bg-white border border-slate-300 rounded px-1.5 py-0.5 leading-tight focus:outline-none focus:border-pln-blue"
                    onchange="this.form.submit()">
                @foreach([5, 10, 15, 20, 30] as $opt)
                    <option value="{{ $opt }}" {{ $currentPerPage == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </form>
    </div>

</div>