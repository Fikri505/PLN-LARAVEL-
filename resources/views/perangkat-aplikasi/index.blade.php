@extends('layouts.app')
@section('title', 'Perangkat Aplikasi')
@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div><h2 class="text-lg font-bold">🗂️ Perangkat Aplikasi</h2><p class="text-xs text-text-muted mt-1">Total: <strong>{{ $items->total() }}</strong> data</p></div>
        <div class="flex gap-2">
            <a href="{{ route('perangkat-aplikasi.export') }}" class="btn btn-success btn-sm">📥 Export</a>
            <a href="{{ route('perangkat-aplikasi.create') }}" class="btn btn-primary btn-sm">+ Tambah</a>
        </div>
    </div>
    <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100">
        <form method="GET" action="{{ route('perangkat-aplikasi.index') }}" class="flex gap-2">
            <div class="relative flex-1"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari jenis, URL, IP, brand, lokasi..." class="form-input !py-2 !pl-9 text-xs"></div>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request('q'))<a href="{{ route('perangkat-aplikasi.index') }}" class="btn btn-secondary btn-sm">✕</a>@endif
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>No</th><th>Jenis</th><th>URL</th><th>IP</th><th>Brand</th><th>Lokasi</th><th>Bidang</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($items as $i => $item)
                <tr>
                    <td class="text-center">{{ $items->firstItem() + $i }}</td>
                    <td class="font-semibold text-sm">{{ $item->jenis_perangkat }}</td>
                    <td class="text-xs max-w-[150px] truncate">{{ $item->url ?: '-' }}</td>
                    <td><code class="bg-slate-100 px-1.5 py-0.5 rounded text-[11px]">{{ $item->ip ?: '-' }}</code></td>
                    <td class="text-xs">{{ $item->brand ?: '-' }}</td>
                    <td class="text-xs">{{ $item->lokasi ?: '-' }}</td>
                    <td><span class="badge badge-neutral">{{ $item->bidang ?: '-' }}</span></td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('perangkat-aplikasi.edit', $item->id) }}" class="btn btn-warning btn-sm !px-2">✏️</a>
                            <form method="POST" action="{{ route('perangkat-aplikasi.destroy', $item->id) }}" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm !px-2">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-12 text-text-muted">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())<div class="card-body border-t border-slate-100">{{ $items->links() }}</div>@endif
</div>
@endsection
