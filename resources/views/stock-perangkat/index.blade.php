@extends('layouts.app')
@section('title', 'Stock Perangkat IT')
@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold">📦 Stock Perangkat IT</h2>
            <p class="text-xs text-text-muted mt-1">Total: <strong>{{ $items->total() }}</strong> item</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mutasi-perangkat.index') }}" class="btn btn-secondary btn-sm">🔄 Mutasi</a>
            <a href="{{ route('stock-perangkat.create') }}" class="btn btn-primary btn-sm">+ Tambah</a>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100">
        <form method="GET" action="{{ route('stock-perangkat.index') }}" class="flex flex-wrap gap-2">
            <div class="relative flex-1 min-w-[180px]">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama, type, kondisi..."
                       class="form-input !py-2 !pl-9 text-xs">
            </div>
            <select name="status" class="form-select !py-2 text-xs w-auto">
                <option value="">-- Semua Status --</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="non-aktif" {{ request('status') === 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request('q') || request('status'))
                <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary btn-sm">✕ Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Type</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i => $item)
                <tr>
                    <td class="text-center">{{ $items->firstItem() + $i }}</td>
                    <td class="font-semibold">{{ $item->nama_barang }}</td>
                    <td class="text-xs">{{ $item->type_barang ?: '-' }}</td>
                    <td class="text-center font-semibold">{{ $item->jumlah }}</td>
                    <td>
                        <span class="badge {{ $item->kondisi === 'baru' ? 'badge-success' : 'badge-warning' }}">
                            {{ strtoupper($item->kondisi) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $item->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td class="text-xs max-w-[150px] truncate">{{ $item->keterangan ?: '-' }}</td>
                    <td>
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" class="w-12 h-9 object-cover rounded border">
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('mutasi-perangkat.create', $item->id) }}"
                               class="btn btn-secondary btn-sm !px-2" data-tippy-content="Mutasi">🔄</a>
                            <a href="{{ route('stock-perangkat.edit', $item->id) }}"
                               class="btn btn-warning btn-sm !px-2" data-tippy-content="Edit Data">✏️</a>
                            <form method="POST" action="{{ route('stock-perangkat.destroy', $item->id) }}" class="delete-form">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm !px-2" data-tippy-content="Hapus Data">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center opacity-70">
                            <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-slate-500 font-medium text-lg">
                                {{ request('q') || request('status') ? 'Data Tidak Ditemukan' : 'Belum Ada Data' }}
                            </p>
                            <p class="text-slate-400 text-sm mt-1">
                                {{ request('q') || request('status') ? 'Coba ubah filter pencarian.' : 'Silakan tambah data baru.' }}
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="card-body border-t border-slate-100">{{ $items->links() }}</div>
    @endif
</div>
@endsection