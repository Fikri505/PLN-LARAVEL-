@extends('layouts.app')
@section('title', 'Mutasi Perangkat IT')
@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold">🔄 Mutasi Perangkat IT</h2>
            <p class="text-xs text-text-muted mt-1">Total: <strong>{{ $mutasi->total() }}</strong> record</p>
        </div>
        <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary btn-sm">← Kembali ke Stock</a>
    </div>

    {{-- Search --}}
    <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100">
        <form method="GET" action="{{ route('mutasi-perangkat.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama barang atau keterangan..."
                       class="form-input !py-2 !pl-9 text-xs">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request('q'))
                <a href="{{ route('mutasi-perangkat.index') }}" class="btn btn-secondary btn-sm">✕</a>
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
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutasi as $i => $row)
                <tr>
                    <td class="text-center">{{ $mutasi->firstItem() + $i }}</td>
                    <td class="font-semibold">{{ $row->perangkat->nama_barang ?? '-' }}</td>
                    <td class="text-xs">{{ $row->perangkat->type_barang ?? '-' }}</td>
                    <td class="text-center font-semibold">{{ $row->jumlah }}</td>
                    <td>
                        <span class="badge {{ $row->kondisi === 'baru' ? 'badge-success' : 'badge-warning' }}">
                            {{ strtoupper($row->kondisi) }}
                        </span>
                    </td>
                    <td class="text-xs max-w-[180px] truncate">{{ $row->keterangan ?: '-' }}</td>
                    <td class="text-xs">{{ $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('mutasi-perangkat.destroy', $row->id) }}" class="delete-form">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm !px-2" data-tippy-content="Hapus">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center opacity-70">
                            <p class="text-slate-500 font-medium text-lg">
                                {{ request('q') ? 'Data Tidak Ditemukan' : 'Belum Ada Data Mutasi' }}
                            </p>
                            <p class="text-slate-400 text-sm mt-1">
                                Klik tombol 🔄 pada tabel Stock Perangkat untuk mencatat mutasi.
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mutasi->hasPages())
        <div class="card-body border-t border-slate-100">{{ $mutasi->links() }}</div>
    @endif
</div>
@endsection