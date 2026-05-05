@extends('layouts.app')
@section('title', 'Data Server')

@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold">🖥️ Daftar Server</h2>
            <p class="text-xs text-text-muted mt-1">Total: <strong>{{ $servers->total() }}</strong> server | Halaman {{ $servers->currentPage() }} dari {{ $servers->lastPage() }}</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="toggleTableView()" class="btn btn-outline btn-sm" id="toggleBtn">📖 Luaskan</button>
            <a href="{{ route('data-server.create') }}" class="btn btn-success btn-sm">+ Input Data</a>
        </div>
    </div>

    {{-- Search --}}
    <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100">
        <form method="GET" action="{{ route('data-server.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari server... (IND, Fungsi, IP, Merk, Status)"
                       class="form-input !py-2 !pl-9 text-xs" autocomplete="off" id="searchInput">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request('q'))
                <a href="{{ route('data-server.index') }}" class="btn btn-secondary btn-sm">✕</a>
            @endif
        </form>
        @if(request('q'))
            <p class="text-xs text-blue-600 bg-blue-50 border border-blue-200 rounded-lg px-3 py-1.5 mt-2 inline-block">
                🔍 Menampilkan <strong>{{ $servers->total() }}</strong> hasil untuk "<strong>{{ request('q') }}</strong>"
            </p>
        @endif
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="data-table" id="serverTable">
            <thead>
                <tr>
                    <th>No</th><th>IND</th><th>Fungsi Server</th><th>IP</th><th>Detail</th>
                    <th class="extended-col hidden">Merk</th><th class="extended-col hidden">Type</th>
                    <th class="extended-col hidden">OS</th><th class="extended-col hidden">Processor</th>
                    <th class="extended-col hidden">RAM</th><th class="extended-col hidden">Storage</th>
                    <th>Status</th><th>Gambar</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servers as $i => $s)
                <tr>
                    <td class="text-center font-medium">{{ $servers->firstItem() + $i }}</td>
                    <td><strong class="text-slate-800">{{ $s->ind }}</strong></td>
                    <td class="text-sm">{{ $s->fungsi_server }}</td>
                    <td><code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">{{ $s->ip }}</code></td>
                    <td class="text-xs text-slate-500 max-w-[120px] truncate">{{ Str::limit($s->detail, 50) }}</td>
                    <td class="extended-col hidden text-xs">{{ $s->merk ?: '-' }}</td>
                    <td class="extended-col hidden text-xs">{{ $s->type ?: '-' }}</td>
                    <td class="extended-col hidden text-xs">{{ $s->system_operasi ?: '-' }}</td>
                    <td class="extended-col hidden text-xs">
                        @if($s->processor_merk) {{ $s->processor_merk }} {{ $s->processor_type }}<br><small>{{ $s->processor_kecepatan }} GHz, {{ $s->processor_core }} cores</small> @else - @endif
                    </td>
                    <td class="extended-col hidden text-xs">
                        @if($s->ram_jenis) {{ $s->ram_jenis }} {{ $s->ram_kapasitas }}<br><small>{{ $s->ram_jumlah_keping }} keping</small> @else - @endif
                    </td>
                    <td class="extended-col hidden text-xs">
                        @if($s->storage_jenis) {{ $s->storage_jenis }} {{ $s->storage_kapasitas_total }}<br><small>{{ $s->storage_jumlah }} unit</small> @else - @endif
                    </td>
                    <td>
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('data-server.toggle-status', $s->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="group cursor-pointer bg-transparent border-0 p-0">
                                    <span class="badge {{ $s->status_server === 'HIDUP' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $s->status_server === 'HIDUP' ? '🟢 HIDUP' : '🔴 MATI' }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400 group-hover:text-pln-blue mt-0.5 text-center">klik ubah</span>
                                </button>
                            </form>
                        @else
                            <span class="badge {{ $s->status_server === 'HIDUP' ? 'badge-success' : 'badge-danger' }}">
                                {{ $s->status_server === 'HIDUP' ? '🟢 HIDUP' : '🔴 MATI' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($s->gambar)
                            <img src="{{ asset('storage/' . $s->gambar) }}" alt="{{ $s->ind }}"
                                 class="w-14 h-10 object-cover rounded-lg border border-slate-200 cursor-pointer hover:scale-105 transition"
                                 onclick="openImgModal(this.src, '{{ $s->ind }} — {{ $s->fungsi_server }}')">
                        @else
                            <div class="w-14 h-10 bg-slate-100 border border-dashed border-slate-300 rounded-lg flex items-center justify-center text-slate-400">📷</div>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-1 flex-wrap">
                            <a href="{{ route('data-server.show', $s->id) }}" class="btn btn-success btn-sm !px-2" data-tippy-content="Detail Server">👁️</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('data-server.edit', $s->id) }}" class="btn btn-warning btn-sm !px-2" data-tippy-content="Edit Server">✏️</a>
                                <form method="POST" action="{{ route('data-server.destroy', $s->id) }}" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm !px-2" data-tippy-content="Hapus Server">🗑️</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center opacity-70">
                            <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-slate-500 font-medium text-lg">{{ request('q') ? 'Data Tidak Ditemukan' : 'Belum Ada Data' }}</p>
                            <p class="text-slate-400 text-sm mt-1">{{ request('q') ? 'Coba gunakan kata kunci pencarian lain.' : 'Silakan input data server baru untuk memulai.' }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($servers->hasPages())
    <div class="card-body border-t border-slate-100">{{ $servers->links() }}</div>
    @endif
</div>

{{-- Image Modal --}}
<div id="imgModal" class="fixed inset-0 bg-black/75 z-[9999] hidden items-center justify-center" onclick="closeImgModal(event)">
    <div class="bg-white rounded-2xl p-4 max-w-[90vw] max-h-[90vh] relative text-center">
        <button onclick="closeImgModal()" class="absolute top-3 right-4 text-xl text-slate-500 hover:text-red-500">✕</button>
        <p id="imgModalTitle" class="font-bold text-sm text-slate-800 mb-3"></p>
        <img id="imgModalImg" src="" class="max-w-[80vw] max-h-[75vh] rounded-xl object-contain">
    </div>
</div>

@push('scripts')
<script>
let isExpanded = false;
function toggleTableView() {
    const cols = document.querySelectorAll('.extended-col');
    const btn = document.getElementById('toggleBtn');
    isExpanded = !isExpanded;
    cols.forEach(c => c.classList.toggle('hidden', !isExpanded));
    btn.textContent = isExpanded ? '📕 Ringkaskan' : '📖 Luaskan';
}
function openImgModal(src, title) {
    document.getElementById('imgModalImg').src = src;
    document.getElementById('imgModalTitle').textContent = title;
    const m = document.getElementById('imgModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeImgModal(e) {
    if (!e || e.target === document.getElementById('imgModal')) {
        const m = document.getElementById('imgModal');
        m.classList.add('hidden'); m.classList.remove('flex');
    }
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImgModal(); });
</script>
@endpush
@endsection
