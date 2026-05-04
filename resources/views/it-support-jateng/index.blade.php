@extends('layouts.app')
@section('title', 'IT Support Jateng')
@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div><h2 class="text-lg font-bold">👨‍💻 Data IT Support Jateng</h2><p class="text-xs text-text-muted mt-1">Total: <strong>{{ $items->total() }}</strong> personil</p></div>
        <a href="{{ route('it-support-jateng.create') }}" class="btn btn-primary btn-sm">+ Tambah</a>
    </div>
    <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100">
        <form method="GET" action="{{ route('it-support-jateng.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, penempatan..." class="form-input !py-2 !pl-9 text-xs">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request('q'))<a href="{{ route('it-support-jateng.index') }}" class="btn btn-secondary btn-sm">✕</a>@endif
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>No HP</th><th>Penempatan</th><th>OPS/STI</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($items as $i => $item)
                <tr>
                    <td class="text-center">{{ $items->firstItem() + $i }}</td>
                    <td class="font-semibold">{{ $item->nama }}</td>
                    <td class="text-xs">{{ $item->email ?: '-' }}</td>
                    <td class="text-xs">{{ $item->no_hp ?: '-' }}</td>
                    <td><span class="badge badge-info">{{ $item->penempatan ?: '-' }}</span></td>
                    <td class="text-xs">{{ $item->ops_sti ?: '-' }}</td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('it-support-jateng.edit', $item->id) }}" class="btn btn-warning btn-sm !px-2">✏️</a>
                            <form method="POST" action="{{ route('it-support-jateng.destroy', $item->id) }}" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm !px-2">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-12 text-text-muted">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())<div class="card-body border-t border-slate-100">{{ $items->links() }}</div>@endif
</div>
@endsection
