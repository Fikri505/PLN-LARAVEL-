@extends('layouts.app')
@section('title', 'Mutasi Perangkat IT')
@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold">🔄 Mutasi Perangkat IT</h2>
            @if($filterStock)
                <p class="text-xs text-blue-600 mt-1">
                    Filter: <strong>{{ $filterStock->nama_barang }}</strong>
                    — Stok saat ini: <strong>{{ $filterStock->jumlah }}</strong> unit
                    <a href="{{ route('mutasi-perangkat.index') }}" class="text-slate-400 hover:text-slate-600 ml-2">✕ Hapus Filter</a>
                </p>
            @else
                <p class="text-xs text-text-muted mt-1">Total: <strong>{{ $mutasi->total() }}</strong> record | Halaman {{ $mutasi->currentPage() }} dari {{ $mutasi->lastPage() }}</p>
            @endif
        </div>
        <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary btn-sm">← Kembali ke Stock</a>
    </div>

    {{-- Search & Filter --}}
    <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100">
        <form method="GET" action="{{ route('mutasi-perangkat.index') }}" class="flex flex-wrap gap-2">
            <input type="hidden" name="perPage" value="{{ $perPage }}">
            @if(request('stock_id'))
                <input type="hidden" name="stock_id" value="{{ request('stock_id') }}">
            @endif
            <div class="relative flex-1 min-w-[180px]">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama barang atau keterangan..."
                       class="form-input !py-2 !pl-9 text-xs">
            </div>
            <select name="tipe" class="form-select !py-2 text-xs w-auto">
                <option value="">-- Semua Tipe --</option>
                <option value="masuk" {{ request('tipe') === 'masuk' ? 'selected' : '' }}>📥 Masuk</option>
                <option value="keluar" {{ request('tipe') === 'keluar' ? 'selected' : '' }}>📤 Keluar</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request('q') || request('tipe'))
                <a href="{{ route('mutasi-perangkat.index', array_filter(['stock_id' => request('stock_id'), 'perPage' => $perPage])) }}"
                   class="btn btn-secondary btn-sm">✕ Reset</a>
            @endif
        </form>
        @if(request('q'))
            <p class="text-xs text-blue-600 bg-blue-50 border border-blue-200 rounded-lg px-3 py-1.5 mt-2 inline-block">
                🔍 Menampilkan <strong>{{ $mutasi->total() }}</strong> hasil untuk "<strong>{{ request('q') }}</strong>"
            </p>
        @endif
    </div>

    @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Type</th>
                    <th>Tipe Mutasi</th>
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
                    <td>
                        @if($row->tipe === 'masuk')
                            <span class="badge badge-success">📥 MASUK</span>
                        @else
                            <span class="badge badge-danger">📤 KELUAR</span>
                        @endif
                    </td>
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
                    <td colspan="9" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center opacity-70">
                            <p class="text-slate-500 font-medium text-lg">
                                {{ request('q') || request('tipe') ? 'Data Tidak Ditemukan' : 'Belum Ada Data Mutasi' }}
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

    @include('layouts.partials.pagination', [
        'paginator'      => $mutasi,
        'routeUrl'       => route('mutasi-perangkat.index'),
        'currentPerPage' => $perPage,
        'queryParams'    => request()->except('perPage', 'page'),
    ])
</div>
@endsection